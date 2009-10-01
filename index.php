<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tasks/index.php,v 1.9 2009/10/01 13:45:49 wjames5 Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 *
 * @package tasks
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
	$gTask = new Tasks( null, $userstate );
	$gTask->load();
	if( !empty( $_REQUEST['finish'] ) ) {
		$updatetask = array();
		$updatetask['new_room'] = -1;
	 	$gTask->store( $updatetask );
	 	$gBitUser->storePreference('task_process', 0 );
		$userstate = 0; 	
	} else if( !empty( $_REQUEST['refer'] ) ) {
		$updatetask = array();
		$updatetask['new_room'] = $gTask->mInfo['department'] + 80;
	 	$gTask->store( $updatetask ); 	
		$gBitUser->storePreference('task_process', 0 );
		$userstate = 0; 	
	} else if( !empty( $_REQUEST['new_tag'] ) ) {
		$updatetask = array();
		$updatetask['new_tag'] = $_REQUEST['new_tag'];
	 	$gTask->store( $updatetask ); 	
	} else if( !empty( $_REQUEST['new_dept'] ) and ( $_REQUEST['new_dept'] <> $gTask->mInfo['department'] ) ) {
		$updatetask = array();
		$updatetask['new_dept'] = $_REQUEST['new_dept'];
	 	$updatetask['new_tag'] = 0;
	 	$gTask->store( $updatetask ); 	
	}	
} else if( !empty( $_REQUEST['content_id'] ) ) {
	$gTask = new Tasks( null, $_REQUEST['content_id'] );
	$gTask->load();
	$gBitUser->storePreference('task_process', $_REQUEST['content_id'] );
	$userstate = $_REQUEST['content_id'];
} else {
	$gTask = new Tasks();
}

if ( $gTask->isValid() and $userstate <> 0 ) {
	$gBitSmarty->assign_by_ref( 'userstate', $userstate );
	$gBitSmarty->assign_by_ref( 'taskInfo', $gTask->mInfo );
	$dept_tree = $gTask->listQueues();
	$gBitSmarty->assign_by_ref( 'departments', $dept_tree['depts'] );
	$gBitSmarty->assign_by_ref( 'tags', $dept_tree['tags'] );
	$gBitSmarty->assign_by_ref( 'subtags', $dept_tree['subtags'] );

	require_once( CITIZEN_PKG_PATH.'Citizen.php');
	$gCitizen = new Citizen( null, $gTask->mCitizenId );
	$gCitizen->load();
	if ( $gCitizen->isValid() ) {
		$gCitizen->loadXrefList();
		$gBitSmarty->assign_by_ref( 'citizenInfo', $gCitizen->mInfo );
	}
	
	$gBitSystem->setBrowserTitle("Task List Item");
	$gBitSystem->display( 'bitpackage:tasks/show_task.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".TASKS_PKG_URL."view.php");
	die;
}
?>