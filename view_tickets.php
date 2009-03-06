<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/view_tickets.php,v 1.2 2009/03/06 08:04:27 lsces Exp $
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

$currentInfo = array();
$currentInfo['title'] = 'View Enquiries for ';
$currentInfo['tickets'] = $gTask->getList( $_REQUEST );

$gBitSmarty->assign_by_ref( 'currentInfo', $currentInfo );

// Display the template
$gBitSystem->display( 'bitpackage:tasks/view_tickets.tpl', tra( 'View Enquiries' ) , array( 'display_mode' => 'list' ));
?>