<?php
/**
 * @version      $Header$
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
	$queue =  $_REQUEST['queue_id'];
}

$currentInfo = array();
$currentInfo['title'] = 'View Enquiries for '.$gTask->getQueueTitle($queue);
$currentInfo['tickets'] = $gTask->getList( $_REQUEST );

$gBitSmarty->assign_by_ref( 'currentInfo', $currentInfo );

// Display the template
$gBitSystem->display( 'bitpackage:tasks/view_queue.tpl', tra( 'View Outstanding Enquiries' ) , array( 'display_mode' => 'list' ));
?>