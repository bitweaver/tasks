<?php
/**
 * $Header$
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.
 *
 * @package tasks
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

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
	$gBitSmarty->assignByRef( 'userstate', $userstate );
	$gBitSmarty->assignByRef( 'taskInfo', $gTask->mInfo );
	$dept_tree = $gTask->listQueues();
	$gBitSmarty->assignByRef( 'departments', $dept_tree['depts'] );
	$gBitSmarty->assignByRef( 'tags', $dept_tree['tags'] );
	$gBitSmarty->assignByRef( 'subtags', $dept_tree['subtags'] );

	require_once( CITIZEN_PKG_PATH.'Citizen.php');
	$gCitizen = new Citizen( null, $gTask->mCitizenId );
	$gCitizen->load();
	if ( $gCitizen->isValid() ) {
		$gCitizen->loadXrefList();
		$gBitSmarty->assignByRef( 'citizenInfo', $gCitizen->mInfo );
	}
	
	$gBitSystem->setBrowserTitle("Task List Item");
	$gBitSystem->display( 'bitpackage:tasks/show_task.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".TASKS_PKG_URL."view.php");
	die;
}
?>