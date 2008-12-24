<?php
/**
 * @version      $Header: /cvsroot/bitweaver/_bit_tasks/add_enquiry.php,v 1.1 2008/12/24 09:04:37 lsces Exp $
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

$currentInfo = array();
$currentInfo[title] = 'Add new Enquiry';

$gBitSmarty->assign_by_ref( 'currentInfo', $currentInfo );

// Display the template
$gBitSystem->display( 'bitpackage:tasks/add_enquiry.tpl', tra( 'Add new Enquiry' ) , array( 'display_mode' => 'display' ));
?>