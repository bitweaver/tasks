<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/get_queue.php,v 1.2 2010/02/08 21:27:26 wjames5 Exp $
 *
 * @author       lsces  <lester@lsces.co.uk>
 * @package      tasks
 * @copyright    2008 bitweaver
 * @license      LGPL {@link http://www.gnu.org/licenses/lgpl.html}
 **/

/**
 * Setup
 */ 
require_once( '../kernel/setup_inc.php' );

$gBitSystem->verifyPackage( 'tasks' );

require_once( TASKS_PKG_PATH.'Tasks.php');
$gTask = new Tasks();

if( !empty( $_REQUEST['queue_id'] ) ) {
	$gTask->getNextTask( $_REQUEST['queue_id'] );
}

if ( $gTask->isValid() ) {
	$gBitSmarty->assign_by_ref( 'taskInfo', $gTask->mInfo );

	require_once( CITIZEN_PKG_PATH.'Citizen.php');
	$gCitizen = new Citizen( $this->mCitizenId );
	if ( $gCitizen->isValid() ) {
		$gBitSmarty->assign_by_ref( 'citizenInfo', $gCitizen->mInfo );
	}
	
	$gBitSystem->setBrowserTitle("Task List Item");
	$gBitSystem->display( 'bitpackage:tasks/show_task.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".TASKS_PKG_URL."view.php");
	die;
}?>