<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_tasks/display_citizen.php,v 1.3 2009/10/01 14:17:05 wjames5 Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package citizen
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'tasks' );
include_once( TASKS_PKG_PATH.'Tasks.php' );

$userstate = $gBitUser->getPreference( 'task_process', 0 );
if ( $userstate ) {
	if( !empty( $_REQUEST["content_id"] ) ) {
		$gTask = new Tasks( null, $userstate );
		$gTask->load();
		$updatetask = array();
		$updatetask['new_citizen'] = $_REQUEST['content_id'];
	 	$gTask->store( $updatetask ); 	
	}
}
header ("location: ".TASKS_PKG_URL."index.php");
die;
?>
