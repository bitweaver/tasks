<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_tasks/Tasks.php,v 1.1 2008/11/28 11:55:37 lsces Exp $
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
require_once( LIBERTY_PKG_PATH.'LibertyContent.php' );		// Citizen base class

define( 'TASKS_CONTENT_TYPE_GUID', 'task_ticket' );

/**
 * @package citizen
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
 			$query = "select ci.*, a.*, n.*, p.*, lc.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				FROM `".BIT_DB_PREFIX."citizen` ci
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."address_book` a ON a.usn = ci.usn
				LEFT JOIN `".BIT_DB_PREFIX."nlpg_blpu` n ON n.`uprn` = ci.`nlpg`
				LEFT JOIN `".BIT_DB_PREFIX."nlpg_lpi` p ON p.`uprn` = ci.`nlpg` AND p.`language` = 'ENG' AND p.`logical_status` = 1
				WHERE ci.`content_id`=?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );

			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = (int)$result->fields['content_id'];
				$this->mCitizenId = (int)$result->fields['usn'];
				$this->mParentId = (int)$result->fields['usn'];
				$this->mCitizenName = $result->fields['title'];
				$this->mInfo['creator'] = (isset( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = (isset( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$os1 = new OSRef($this->mInfo['x_coordinate'], $this->mInfo['y_coordinate']);
				$ll1 = $os1->toLatLng();
				$this->mInfo['prop_lat'] = $ll1->lat;
				$this->mInfo['prop_lng'] = $ll1->lng;
			}
		}
		LibertyContent::load();
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

		if ( empty( $pParamHash['parent_id'] ) )
			$pParamHash['parent_id'] = $this->mContentId;
			
		// content store
		// check for name issues, first truncate length if too long
		if( empty( $pParamHash['surname'] ) || empty( $pParamHash['forename'] ) )  {
			$this->mErrors['names'] = 'You must enter a forename and surname for this citizen.';
		} else {
			$pParamHash['title'] = substr( $pParamHash['prefix'].' '.$pParamHash['forename'].' '.$pParamHash['surname'].' '.$pParamHash['suffix'], 0, 160 );
			$pParamHash['content_store']['title'] = $pParamHash['title'];
		}	

		// Secondary store entries
		$pParamHash['citizen_store']['prefix'] = $pParamHash['prefix'];
		$pParamHash['citizen_store']['forename'] = $pParamHash['forename'];
		$pParamHash['citizen_store']['surname'] = $pParamHash['surname'];
		$pParamHash['citizen_store']['suffix'] = $pParamHash['suffix'];
		$pParamHash['citizen_store']['organisation'] = $pParamHash['organisation'];

		if ( !empty( $pParamHash['nino'] ) ) $pParamHash['citizen_store']['nino'] = $pParamHash['nino'];
		if ( !empty( $pParamHash['dob'] ) ) $pParamHash['citizen_store']['dob'] = $pParamHash['dob'];
		if ( !empty( $pParamHash['eighteenth'] ) ) $pParamHash['citizen_store']['eighteenth'] = $pParamHash['eighteenth'];
		if ( !empty( $pParamHash['dod'] ) ) $pParamHash['citizen_store']['dod'] = $pParamHash['dod'];

		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Store task data
	* @param $pParamHash contains all data to store the task ticket
	* @param $pParamHash[title] title of the new citizen
	* @param $pParamHash[edit] description of the citizen
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			// Start a transaction wrapping the whole insert into liberty 

			$this->mDb->StartTrans();
			if ( LibertyContent::store( $pParamHash ) ) {
				$table = BIT_DB_PREFIX."citizen";

				// mContentId will not be set until the secondary data has commited 
				if( $this->verifyId( $this->mCitizenId ) ) {
					if( !empty( $pParamHash['citizen_store'] ) ) {
						$result = $this->mDb->associateUpdate( $table, $pParamHash['citizen_store'], array( "content_id" => $this->mContentId ) );
					}
				} else {
					$pParamHash['citizen_store']['content_id'] = $pParamHash['content_id'];
					$pParamHash['citizen_store']['usn'] = $pParamHash['content_id'];
					if( isset( $pParamHash['citizen_id'] ) && is_numeric( $pParamHash['citizen_id'] ) ) {
						$pParamHash['citizen_store']['usn'] = $pParamHash['citizen_id'];
					} else {
						$pParamHash['citizen_store']['usn'] = $this->mDb->GenID( 'citizen_id_seq');
					}	

					$pParamHash['citizen_store']['parent_id'] = $pParamHash['citizen_store']['content_id'];
					$this->mCitizenId = $pParamHash['citizen_store']['content_id'];
					$this->mParentId = $pParamHash['citizen_store']['parent_id'];
					$this->mContentId = $pParamHash['content_id'];
					$result = $this->mDb->associateInsert( $table, $pParamHash['citizen_store'] );
				}
				// load before completing transaction as firebird isolates results
				$this->load();
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
				$this->mErrors['store'] = 'Failed to store this citizen.';
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
	 * Returns Request_URI to a Citizen content object
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

		return CITIZEN_PKG_URL.'index.php?content_id='.$pContentId;
	}

	/**
	 * Returns HTML link to display a Citizen object
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
	 * Returns title of an Citizen object
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
			$ret = "Citizen - ".$this->mInfo['title'];
		} elseif( !empty( $pHash['content_description'] ) ) {
			$ret = $pHash['content_description'];
		}
		return $ret;
	}

	/**
	 * Returns list of contract entries
	 *
	 * @param integer 
	 * @param integer 
	 * @param integer 
	 * @return string Text for the title description
	 */
	function getList( &$pListHash ) {
		LibertyContent::prepGetList( $pListHash );
		
		$whereSql = $joinSql = $selectSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

		if ( isset($pListHash['find']) ) {
			$findesc = '%' . strtoupper( $pListHash['find'] ) . '%';
			$whereSql .= " AND (UPPER(con.`SURNAME`) like ? or UPPER(con.`FORENAME`) like ?) ";
			array_push( $bindVars, $findesc );
		}

		if ( isset($pListHash['add_sql']) ) {
			$whereSql .= " AND $add_sql ";
		}

		$query = "SELECT con.*, lc.*, 
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name $selectSql
				FROM `".BIT_DB_PREFIX."citizen` ci 
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				$joinSql
				WHERE lc.`content_type_guid`=? $whereSql  
				order by ".$this->mDb->convertSortmode( $pListHash['sort_mode'] );
		$query_cant = "SELECT COUNT(lc.`content_id`) FROM `".BIT_DB_PREFIX."citizen` ci
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = ci.`content_id` )
				$joinSql
				WHERE lc.`content_type_guid`=? $whereSql";

		$ret = array();
		$this->mDb->StartTrans();
		$result = $this->mDb->query( $query, $bindVars, $pListHash['max_records'], $pListHash['offset'] );
		$cant = $this->mDb->getOne( $query_cant, $bindVars );
		$this->mDb->CompleteTrans();

		while ($res = $result->fetchRow()) {
			$res['citizen_url'] = $this->getDisplayUrl( $res['content_id'] );
			$ret[] = $res;
		}

		$pListHash['cant'] = $cant;
		LibertyContent::postGetList( $pListHash );
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
	 * getXrefList( &$pParamHash );
	 * Get list of xref records for this citizen record
	 */
	function loadXrefList() {
		if( $this->isValid() && empty( $this->mInfo['xref'] ) ) {
		
			$sql = "SELECT x.`last_update_date`, x.`source`, x.`cross_reference` 
				FROM `".BIT_DB_PREFIX."citizen_xref` x
				WHERE x.content_id = ?";

			$result = $this->mDb->query( $sql, array( $this->mContentId ) );

			while( $res = $result->fetchRow() ) {
				$this->mInfo['xref'][] = $res;
				if ( $res['source'] == 'POSTFIELD' ) $caller[] = $res['cross_reference'];
			}

			$sql = "SELECT t.* FROM `".BIT_DB_PREFIX."task_ticket` t 
				WHERE t.usn = ?";
			$result = $this->mDb->query( $sql, array( $this->mCitizenId ) );
			while( $res = $result->fetchRow() ) {
				$this->mInfo['tickets'][] = $res;
			}
		}
	}

}
?>
