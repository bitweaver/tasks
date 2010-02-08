<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/logon_list.php,v 1.3 2010/02/08 21:27:26 wjames5 Exp $
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

$currentInfo = array();
$currentInfo['title'] = 'View Current Logon Status';

$gBitSmarty->assign_by_ref( 'currentInfo', $currentInfo );

// Display the template
$gBitSystem->display( 'bitpackage:tasks/logon.tpl', tra( 'View Current Logon Status' ) , array( 'display_mode' => 'list' ));
?>