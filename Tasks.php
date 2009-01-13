<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_tasks/Tasks.php,v 1.7 2009/01/13 20:16:45 lsces Exp $
 *
 * Copyright ( c ) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * @package citizen
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyContent.php' );		// Tasks base class

define( 'TASKS_CONTENT_TYPE_GUID', 'task_ticket' );

/**
 * @package tasks
 */
class Tasks extends LibertyContent {
	var $mTicketId;
	var $mParentId;

	/**
	 * Constructor 
	 * 
	 * Build a Citizen object based on LibertyContent
	 * @param integer Citizen Id identifer
	 * @param integer Base content_id identifier 
	 */
	function Tasks( $pTicketId = NULL, $pContentId = NULL ) {
		LibertyContent::LibertyContent();
		$this->registerContentType( TASKS_CONTENT_TYPE_GUID, array(
				'content_type_guid' => TASKS_CONTENT_TYPE_GUID,
				'content_description' => 'Task Ticket',
				'handler_class' => 'Tasks',
				'handler_package' => 'tasks',
				'handler_file' => 'Tasks.php',
				'maintainer_url' => 'http://lsces.co.uk'
			) );
		$this->mTicketId = (int)$pTicketId;
		$this->mContentId = (int)$pContentId;
		$this->mCitizenId = 0;
		$this->mContentTypeGuid = TASKS_CONTENT_TYPE_GUID;
				// Permission setup
		$this->mViewContentPerm  = 'p_tasks_view';
		$this->mCreateContentPerm  = 'p_tasks_create';
		$this->mUpdateContentPerm  = 'p_tasks_update';
		$this->mAdminContentPerm = 'p_tasks_admin';
		
	}

	/**
	 * Load a Task Ticket
	 *
	 * (Describe Task object here )
	 */
	function load($pContentId = NULL) {
		if ( $pContentId ) $this->mContentId = (int)$pContentId;
		if( $this->verifyId( $this->mContentId ) ) {
 			$query = "select ti.*, lc.*, rs.`title` AS dept_title, rs.`ter_type` AS dept_mode,
 				tag.`title` AS reason, tag.`reason_source` AS subtag, tag.`tag` AS tag_abv,
 				MOD( ti.`clearance`, 256 ) AS clearance_code,
				CAST( ti.`clearance` / 256 AS INTEGER ) AS survey,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				FROM `".BIT_DB_PREFIX."task_ticket` ti
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ti.`ticket_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."task_roomstat` rs ON (ti.`department` + 80 = rs.`terminal`)
				LEFT JOIN `".BIT_DB_PREFIX."task_reason` tag ON (ti.`tags` = tag.`reason`)
				WHERE ti.`ticket_id`=?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );

			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = (int)$result->fields['content_id'];
				$this->mCitizenId = (int)$result->fields['caller_id'];
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$this->mInfo['title'] = 'Ticket Number - '.$this->mInfo['ticket_no'];
				$this->mInfo['reason'] = $this->mInfo['tag_abv'].' - '.$this->mInfo['reason'];
				if ( $this->mInfo['department'] == 0 ) { $this->mInfo['dept_title'] = 'Please select a department'; }
			}
		}
		LibertyContent::load();
		$this->loadTransactionList();
		return;
	}

	/**
	* verify, clean up and prepare data to be stored
	* @param $pParamHash all information that is being stored. will update $pParamHash by reference with fixed array of itmes
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access private
	**/
	function verify( &$pParamHash ) {
		// make sure we're all loaded up if everything is valid
		if( $this->isValid() && empty( $this->mInfo ) ) {
			$this->load( TRUE );
		}

		// It is possible a derived class set this to something different
		if( empty( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( !empty( $this->mContentId ) ) {
			$pParamHash['content_id'] = $this->mContentId;
		} else {
			unset( $pParamHash['content_id'] );
		}

		// content store
		// check for name issues, first truncate length if too long

		// Secondary store entries
		if( $this->isValid() ) {
			if ( !empty( $pParamHash['new_citizen'] ) ) {
				$pParamHash['task_store']['caller_id'] = $pParamHash['new_citizen'];
				$pParamHash['task_store']['usn'] = $pParamHash['new_citizen'];
			}
			if ( !empty( $pParamHash['new_dept'] ) ) {
				$pParamHash['task_store']['department'] = $pParamHash['new_dept'];
			}
			if ( !empty( $pParamHash['new_room'] ) ) {
				$pParamHash['task_store']['room'] = $pParamHash['new_room'];
				// Add transaction table insert here to replace database trigger
			}
			if ( !empty( $pParamHash['new_tag'] ) ) {
				$pParamHash['task_store']['tags'] = $pParamHash['new_tag'];
				// Add transaction table insert here to replace database trigger
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Store task data
	* @param $pParamHash contains all data to store the task ticket
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			// Start a transaction wrapping the whole insert into liberty 

			$this->mDb->StartTrans();
			if ( LibertyContent::store( $pParamHash ) ) {
				$table = BIT_DB_PREFIX."task_ticket";
				if( $this->isValid() && !empty( $pParamHash['task_store'] ) ) {
						$result = $this->mDb->associateUpdate( $table, $pParamHash['task_store'], array( "ticket_id" => $this->mContentId ) );
				} else {
					global $gBitUser;
					
					$pParamHash['task_store']['ticket_id'] = $pParamHash['content_id'];
					$pParamHash['task_store']['ticket_ref'] = $this->mDb->NOW();
					$pParamHash['task_store']['last'] = $this->mDb->NOW();
					$pParamHash['task_store']['ticket_no'] = $pParamHash['task_offset']+1;
					$pParamHash['task_store']['office'] = 1;
					$pParamHash['task_store']['staff_id'] = $gBitUser->mUserId;
					$pParamHash['task_store']['init_id'] = $gBitUser->mUserId;
					$pParamHash['task_store']['caller_id'] = 0;
					$pParamHash['task_store']['department'] = 0;
				
					$this->mContentId = $pParamHash['content_id'];
					$result = $this->mDb->associateInsert( $table, $pParamHash['task_store'] );
				}
				// load before completing transaction as firebird isolates results
				$this->load();
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
				$this->mErrors['store'] = 'Failed to store this task ticket.';
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * Delete content object and all related records
	 */
	function expunge()
	{
		$ret = FALSE;
		if ($this->isValid() ) {
			$this->mDb->StartTrans();
			$query = "DELETE FROM `".BIT_DB_PREFIX."citizen` WHERE `content_id` = ?";
			$result = $this->mDb->query($query, array($this->mContentId ) );
			$query = "DELETE FROM `".BIT_DB_PREFIX."citizen_type_map` WHERE `content_id` = ?";
			$result = $this->mDb->query($query, array($this->mContentId ) );
			if (LibertyContent::expunge() ) {
			$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}
    
	/**
	 * Returns Request_URI to a Task content object
	 *
	 * @param string name of
	 * @param array different possibilities depending on derived class
	 * @return string the link to display the page.
	 */
	function getDisplayUrl( $pContentId=NULL ) {
		global $gBitSystem;
		if( empty( $pContentId ) ) {
			$pContentId = $this->mContentId;
		}

		return TASKS_PKG_URL.'index.php?content_id='.$pContentId;
	}

	/**
	 * Returns HTML link to display a Task object
	 * 
	 * @param string Not used ( generated locally )
	 * @param array mInfo style array of content information
	 * @return the link to display the page.
	 */
	function getDisplayLink( $pText, $aux ) {
		if ( $this->mContentId != $aux['content_id'] ) $this->load($aux['content_id']);

		if (empty($this->mInfo['content_id']) ) {
			$ret = '<a href="'.$this->getDisplayUrl($aux['content_id']).'">'.$aux['title'].'</a>';
		} else {
			$ret = '<a href="'.$this->getDisplayUrl($aux['content_id']).'">'."Citizen - ".$this->mInfo['title'].'</a>';
		}
		return $ret;
	}

	/**
	 * Returns title of an Task object
	 * @todo Need to expand this to handle type of task and date information
	 *
	 * @param array mInfo style array of content information
	 * @return string Text for the title description
	 */
	function getTitle( $pHash = NULL ) {
		$ret = NULL;
		if( empty( $pHash ) ) {
			$pHash = &$this->mInfo;
		} else {
			if ( $this->mContentId != $pHash['content_id'] ) {
				$this->load($pHash['content_id']);
				$pHash = &$this->mInfo;
			}
		}

		if( !empty( $pHash['title'] ) ) {
			$ret = "Ticket - ".$this->mInfo['title'];
		} elseif( !empty( $pHash['content_description'] ) ) {
			$ret = $pHash['content_description'];
		}
		return $ret;
	}

	/**
	 * Returns title of a queue 
	 * @todo Need to cache department/queue information in object
	 *
	 * @param integer Queue Number
	 * @return string Text for the title description
	 */
	function getQueueTitle( $queue ) {
		$query = "SELECT rs.`title` AS queue FROM `".BIT_DB_PREFIX."task_roomstat` rs WHERE rs.`terminal` = 80 + $queue";
		return $this->mDb->getOne( $query );
	}
	
	/**
	 * Gets the next ticket number from a queue 
	 *
	 * @param integer Queue Number
	 * @return bool True if switched to a valid task
	 */
	function getNextTask( $queue ) {
		$query = "SELECT cd.`ticket_id` FROM  `".BIT_DB_PREFIX."task_ticket` cd
						 WHERE cd.`ticket_ref` BETWEEN 'TODAY' AND 'TOMORROW' AND cd.`room` = $queue + 80
						 AND cd.`office` = 1
				  		 ORDER BY cd.`ticket_ref`";
		$next = $this->mDb->getOne( $query );
// Add switch of user state to serving!
		if ( $next ) return true;
		else return false;
	}
	
	/**
	 * Returns title of a queue 
	 * @todo Need to cache department/queue information in object
	 *
	 * @param integer Queue Number
	 * @return string Text for the title description
	 */
	function createTask( $queue ) {
		$query = "SELECT cd.`ticket_id` FROM  `".BIT_DB_PREFIX."task_ticket` cd
						 WHERE ti.`ticket_ref` BETWEEN TODAY AND TOMORROW AND cd.`room` = $queue + 80
						 AND cd.`office` = 1
				  		 ORDER BY cd.`ticket_ref`";
		$next = $this->mDb->getOne( $query );
// Add switch of user state to serving!
		if ( $next ) return true;
		else return false;
	}
	
	/**
	 * Returns list of tesk entries
	 *
	 * @param integer 
	 * @return array Enquiry tickets
	 */
	function getList( &$pListHash ) {
		LibertyContent::prepGetList( $pListHash );
		
		$whereSql = $joinSql = $selectSql = '';
		$bindVars = array();
// Update to more flexible date management later
		array_push( $bindVars, 'TODAY' );
		array_push( $bindVars, 'TOMORROW' );
//		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

		if ( isset($pListHash['queue_id']) ) {
			$whereSql .= " AND ti.`room` = 80 + ? ";
			array_push( $bindVars, $pListHash['queue_id'] );
		}

// init_id and staff_id will map to creator_user_id and modifier_user_id when fully converted to LC
// , lc.* 				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )

		$query = "SELECT ti.*, ci.*, tr.`title` as reason,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
				FROM `".BIT_DB_PREFIX."task_ticket` ti 
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = ti.`staff_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = ti.`init_id`)
				LEFT JOIN `".BIT_DB_PREFIX."citizen` ci ON (ci.`usn` = ti.`usn`)
				LEFT JOIN `".BIT_DB_PREFIX."task_reason` tr ON (tr.`reason` = ti.`tags`)
				$joinSql
				WHERE ti.`ticket_ref` BETWEEN ? AND ? $whereSql  
				order by ti.`ticket_ref`";
		$query_cant = "SELECT COUNT(ti.`ticket_no`) FROM `".BIT_DB_PREFIX."task_ticket` ti
				$joinSql
				WHERE ti.`ticket_ref` BETWEEN ? AND ? $whereSql";

		$ret = array();
		$this->mDb->StartTrans();
		$result = $this->mDb->query( $query, $bindVars, $pListHash['max_records'], $pListHash['offset'] );
		$cant = $this->mDb->getOne( $query_cant, $bindVars );
		$this->mDb->CompleteTrans();

		while ($res = $result->fetchRow()) {
			$res['ticket_url'] = $this->getDisplayUrl( $res['ticket_id'] );
			$ret[] = $res;
		}

		$pListHash['cant'] = $cant;
		LibertyContent::postGetList( $pListHash );
		return $ret;
	}
	
	/**
	 * Returns list of queues
	 *
	 * @param integer 
	 * @return array Queue records
	 */
	function listQueues() {
		$query = "SELECT rs.`terminal` - 81, (rs.`terminal` - 80) AS que_no, rs.`title`, rs.`ter_type` AS dep_type
			FROM `".BIT_DB_PREFIX."task_roomstat` rs
			WHERE rs.`ter_type` > 6 AND rs.`terminal` > 80
			ORDER BY rs.`terminal`";
		$result = array();
		$result['depts'] = $this->mDb->GetAssoc( $query );
		if ( $this->mInfo['department'] > 0 ) {
			$result['tags']	= $this->mDb->GetAssoc("SELECT `reason`, `reason` AS tag_no, `title`, `tag` FROM `".BIT_DB_PREFIX."task_reason` WHERE `reason_type` = ".$this->mInfo['department']." ORDER BY `reason`");
			if ( $this->mInfo['subtag'] > 0 ) {
				$result['subtags'] = $this->mDb->GetAssoc("SELECT `reason`, `reason` AS tag_no, `title`, `tag` FROM `".BIT_DB_PREFIX."task_reason` WHERE `reason_type` = ".$this->mInfo['subtag']." ORDER BY `reason`");
			}
		}
		return $result;
	}
	
	/**
	 * Returns list of queue activity
	 *
	 * @param integer 
	 * @return array Queue activity records
	 */
	function getQueueList( &$pListHash ) {
		$query = "SELECT rs.`office`, rs.`terminal`, rs.`title`, rs.`ter_type`, rs.`x1` AS no_warn, rs.`x2` AS no_alarm, rs.`x3` AS aw_warn, rs.`x4` AS aw_alarm,
			COUNT(tic.`ticket_ref`) AS `no_waiting`,
			AVG(((CURRENT_TIMESTAMP - tic.`last`) * 86400)) AS `avg_wait`
			FROM `".BIT_DB_PREFIX."task_roomstat` rs
			LEFT JOIN `".BIT_DB_PREFIX."task_ticket` tic ON tic.`office` = rs.`office` AND tic.`room` = rs.`terminal` AND tic.`ticket_ref` BETWEEN CURRENT_DATE AND CURRENT_DATE + 1
			WHERE rs.`ter_type` > 6 AND rs.`terminal` > 80
			GROUP BY rs.`office`, rs.`terminal`, rs.`title`, rs.`ter_type`, rs.`x1`, rs.`x2`, rs.`x3`, rs.`x4`
			ORDER BY rs.`terminal`";

		$result = $this->mDb->query( $query );
		while ($res = $result->fetchRow()) {
			$res['queue_id'] = $res['terminal'] - 80;
			$res['display_url'] = TASKS_PKG_URL.'view_queue.php?queue_id='.$res['queue_id'];
			$ret[] = $res;
		}
		return $ret;
	}

	/**
	 * TicketRecordLoad( $data );
	 * Ticket file import  
	 */
	function TicketRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."task_ticket";
		
		$pDataHash['data_store']['office'] = $data[0];
		$pDataHash['data_store']['ticket_id'] = $data[1];
		$pDataHash['data_store']['ticket_ref'] = $data[2];
		$pDataHash['data_store']['ticket_no'] = $data[3];
		$pDataHash['data_store']['tags'] = $data[4];
		$pDataHash['data_store']['clearance'] = $data[5];
		$pDataHash['data_store']['room'] = $data[6];
		if ( $data[7] == '[null]' )
			$pDataHash['data_store']['note'] = '';
		else
			$pDataHash['data_store']['note'] = $data[7];
		if ( $data[8] == '[null]' )
			$pDataHash['data_store']['last'] = '';
		else
			$pDataHash['data_store']['last'] = $data[8];
		$pDataHash['data_store']['staff_id'] = $data[9];
		$pDataHash['data_store']['init_id'] = $data[10];
		$pDataHash['data_store']['caller_id'] = $data[11];
		$pDataHash['data_store']['appoint_id'] = $data[12];
		$pDataHash['data_store']['applet'] = $data[13];
		if ( $data[14] != '[null]' ) $pDataHash['data_store']['memo'] = $data[14];
		if ( $data[15] == '[null]' )
			$pDataHash['data_store']['department'] = 0;
		else
			$pDataHash['data_store']['department'] = $data[15];
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * TransactionRecordLoad( $data );
	 * Transaction file import  
	 */
	function TransactionRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."task_transaction";
		
		$pDataHash['data_store']['ticket_id'] = $data[0];
		$pDataHash['data_store']['transact_no'] = $data[1];
		$pDataHash['data_store']['transact'] = $data[2];
		$pDataHash['data_store']['ticket_ref'] = $data[3];
		$pDataHash['data_store']['staff_id'] = $data[4];
		$pDataHash['data_store']['previous'] = $data[5];
		$pDataHash['data_store']['room'] = $data[6];
		$pDataHash['data_store']['applet'] = $data[7];
		$pDataHash['data_store']['office'] = $data[8];
		$pDataHash['data_store']['ticket_no'] = $data[9];
		if ( $data[10] == '[null]' )
			$pDataHash['data_store']['proom'] = 0;
		else
			$pDataHash['data_store']['proom'] = $data[10];
		if ( $data[11] == '[null]' )
			$pDataHash['data_store']['tags'] = 0;
		else
			$pDataHash['data_store']['tags'] = $data[11];
		if ( $data[12] == '[null]' )
			$pDataHash['data_store']['clearance'] = 0;
		else
			$pDataHash['data_store']['clearance'] = $data[12];
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * ReasonRecordLoad( $data );
	 * Reason file import  
	 */
	function ReasonRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."task_reason";
		
		$pDataHash['data_store']['reason'] = $data[0];
		$pDataHash['data_store']['title'] = $data[1];
		$pDataHash['data_store']['reason_type'] = $data[2];
		$pDataHash['data_store']['reason_source'] = $data[3];
		if ( $data[4] == '[null]' )
			$pDataHash['data_store']['tag'] = '';
		else
			$pDataHash['data_store']['tag'] = $data[4];
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * RoomstatRecordLoad( $data );
	 * Roomstat file import  
	 */
	function RoomstatRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."task_roomstat";
		
		$pDataHash['data_store']['office'] = $data[0];
		$pDataHash['data_store']['terminal'] = $data[1];
		$pDataHash['data_store']['title'] = $data[2];
		if ( $data[3] == '[null]' )
			$pDataHash['data_store']['head'] = '';
		else
			$pDataHash['data_store']['head'] = $data[3];
		if ( $data[4] == '[null]' )
			$pDataHash['data_store']['announce'] = '';
		else
			$pDataHash['data_store']['announce'] = $data[4];
		$pDataHash['data_store']['ter_type'] = $data[5];
		$pDataHash['data_store']['led'] = $data[6];
		if ( $data[7] != '[null]' ) $pDataHash['data_store']['ledhead'] = $data[7];
		if ( $data[8] != '[null]' ) $pDataHash['data_store']['beacon'] = $data[8];
		if ( $data[9] != '[null]' ) $pDataHash['data_store']['camera'] = $data[9];
		if ( $data[10] != '[null]' ) $pDataHash['data_store']['serving'] = $data[10];
		if ( $data[11] != '[null]' ) $pDataHash['data_store']['act1'] = $data[11];
		if ( $data[12] != '[null]' ) $pDataHash['data_store']['fro_'] = $data[12];
		if ( $data[13] != '[null]' ) $pDataHash['data_store']['alarm'] = $data[13];
		if ( $data[14] != '[null]' ) $pDataHash['data_store']['curmode'] = $data[14];
		if ( $data[15] != '[null]' ) $pDataHash['data_store']['x1'] = $data[15];
		if ( $data[16] != '[null]' ) $pDataHash['data_store']['x2'] = $data[16];
		if ( $data[17] != '[null]' ) $pDataHash['data_store']['x3'] = $data[17];
		if ( $data[18] != '[null]' ) $pDataHash['data_store']['x4'] = $data[18];
		if ( $data[19] != '[null]' ) $pDataHash['data_store']['x5'] = $data[19];
		if ( $data[20] != '[null]' ) $pDataHash['data_store']['x6'] = $data[20];
		if ( $data[21] != '[null]' ) $pDataHash['data_store']['x7'] = $data[21];
		if ( $data[22] != '[null]' ) $pDataHash['data_store']['x8'] = $data[22];
		if ( $data[23] != '[null]' ) $pDataHash['data_store']['x9'] = $data[23];
		if ( $data[24] != '[null]' ) $pDataHash['data_store']['x10'] = $data[24];
		if ( $data[25] != '[null]' ) $pDataHash['data_store']['status'] = $data[25];
		if ( $data[26] != '[null]' ) $pDataHash['data_store']['logon'] = $data[26];
		if ( $data[27] != '[null]' ) $pDataHash['data_store']['ter_location'] = $data[27];
		if ( $data[28] != '[null]' ) $pDataHash['data_store']['ticketprint'] = $data[28];
		if ( $data[29] != '[null]' ) $pDataHash['data_store']['reportprint'] = $data[29];
		if ( $data[30] != '[null]' ) $pDataHash['data_store']['booking'] = $data[30];
		if ( $data[31] != '[null]' ) $pDataHash['data_store']['book'] = $data[31];
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * ReasonRecordLoad( $data );
	 * Reason file import  
	 */
	function CallerRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."task_caller";
		
		$pDataHash['data_store']['caller_id'] = $data[0];
		if ( $data[1] == '[null]' )
			$pDataHash['data_store']['cltype'] = 0;
		else
			$pDataHash['data_store']['cltype'] = $data[1];
		$pDataHash['data_store']['title'] = $data[2];
		$pDataHash['data_store']['surname'] = $data[3];
		$pDataHash['data_store']['forename'] = $data[4];
		$pDataHash['data_store']['company'] = $data[5];
		if ( $data[6] == '[null]' )
			$pDataHash['data_store']['ni'] = '';
		else
			$pDataHash['data_store']['ni'] = $data[6];
		if ( $data[7] == '[null]' )
			$pDataHash['data_store']['hbis'] = '';
		else
			$pDataHash['data_store']['hbis'] = $data[7];
		$pDataHash['data_store']['address'] = $data[8];
		$pDataHash['data_store']['postcode'] = $data[9];
		if ( $data[10] != '[null]' ) $pDataHash['data_store']['lastvisit'] = $data[10];
		if ( $data[11] == '[null]' )
			$pDataHash['data_store']['specialneeds'] = '';
		else
			$pDataHash['data_store']['specialneeds'] = $data[11];
		if ( $data[12] == '[null]' )
			$pDataHash['data_store']['staff_id'] = 0;
		else
			$pDataHash['data_store']['staff_id'] = $data[12];
		if ( $data[13] == '[null]' )
			$pDataHash['data_store']['note'] = '';
		else
			$pDataHash['data_store']['note'] = $data[13];
		if ( $data[14] != '[null]' ) $pDataHash['data_store']['memo'] = $data[14];
		if ( $data[15] != '[null]' ) $pDataHash['data_store']['cllink'] = $data[15];
		if ( $data[16] == '[null]' )
			$pDataHash['data_store']['usn'] = 0;
		else
			$pDataHash['data_store']['usn'] = $data[16];
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * StaffRecordLoad( $data );
	 * Staff file import  
	 */
	function StaffRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."task_staff";
		
		$pDataHash['data_store']['staff_id'] = $data[0];
		$pDataHash['data_store']['surname'] = $data[1];
		$pDataHash['data_store']['forename'] = $data[2];
		$pDataHash['data_store']['initials'] = $data[3];
		if ( $data[4] == '[null]' )
			$pDataHash['data_store']['direct'] = '';
		else
			$pDataHash['data_store']['direct'] = $data[4];
		$pDataHash['data_store']['team'] = $data[5];
		if ( $data[6] == '[null]' )
			$pDataHash['data_store']['ext'] = '';
		else
			$pDataHash['data_store']['ext'] = $data[6];
		$pDataHash['data_store']['category'] = $data[7];
		$pDataHash['data_store']['logon'] = $data[8];
		if ( $data[9] == '[null]' )
			$pDataHash['data_store']['note'] = '';
		else
			$pDataHash['data_store']['note'] = $data[9];
		$pDataHash['data_store']['logged'] = 0;
		$pDataHash['data_store']['content_id'] = 0;
		$pDataHash['data_store']['office'] = $data[14];
		$result = $this->mDb->associateInsert( $table, $pDataHash['data_store'] );
	}

	/**
	 * Delete golden object and all related records
	 */
	function HistoryExpunge()
	{
		$ret = FALSE;
		$query = "DELETE FROM `".BIT_DB_PREFIX."task_ticket`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."task_transaction`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."task_reason`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."task_roomstat`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."task_caller`";
		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."task_staff`";
		$result = $this->mDb->query( $query );
		return $ret;
	}

	/**
	 * loadTransactionList( &$pParamHash );
	 * Get list of transaction records relating to the active ticket
	 */
	function loadTransactionList() {
		if( $this->isValid() ) {
		
			$sql = "SELECT tran.* 
				FROM `".BIT_DB_PREFIX."task_transaction` tran
				WHERE tran.ticket_id = ?";

			$result = $this->mDb->query( $sql, array( $this->mContentId ) );

			while( $res = $result->fetchRow() ) {
				$this->mInfo['trans'][$res['transact_no']] = $res;
			}
		}
	}

}
?>
