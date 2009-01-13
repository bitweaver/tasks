<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/add_enquiry.php,v 1.2 2009/01/13 08:39:08 lsces Exp $
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
$newtask['offset'] = $offset;
$gTask->store($newtask);
vd($gTask);

// Display the template
$gBitSystem->display( 'bitpackage:tasks/show_task.tpl', tra( 'New Enquiry' ) , array( 'display_mode' => 'display' ));
?>