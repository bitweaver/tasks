<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/view.php,v 1.2 2009/01/12 20:07:25 lsces Exp $
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
$currentInfo[title] = 'View Outstanding Enquiries';
$currentInfo[queues] = $gTask->getQueueList();

$gBitSmarty->assign_by_ref( 'currentInfo', $currentInfo );

// Display the template
$gBitSystem->display( 'bitpackage:tasks/view.tpl', tra( 'View Outstanding Enquiries' ) , array( 'display_mode' => 'list' ));
?>