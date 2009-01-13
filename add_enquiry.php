<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/add_enquiry.php,v 1.3 2009/01/13 20:16:45 lsces Exp $
 *
 * @author       lsces  <lester@lsces.co.uk>
 * @package      tasks
 * @copyright    2008 bitweaver
 * @license      LGPL {@link http://www.gnu.org/licenses/lgpl.html}
 **/

/**
 * Setup
 */ 
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'tasks' );

require_once( TASKS_PKG_PATH.'Tasks.php');
$gTask = new Tasks();

$offset = 0;
if( !empty( $_REQUEST['type'] ) ) {
	$offset =  ($_REQUEST['type']-1) * 10000;
}
if( !empty( $_REQUEST['pass'] ) ) {
	$offset =  50000;
}
$newtask = array();
$newtask['task_offset'] = $offset;
$gTask->store( $newtask );
$gBitUser->storePreference('task_process', $gTask->mContentId );

// Refresh display
//header ("location: ".TASKS_PKG_URL."index.php");
//die;
?>