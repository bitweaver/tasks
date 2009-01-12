<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_tasks/index.php,v 1.3 2009/01/12 14:44:36 lsces Exp $
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
 *
 * @package tasks
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

include_once( TASKS_PKG_PATH.'Tasks.php' );

$gBitSystem->isPackageActive('tasks', TRUE);

if( !empty( $_REQUEST['content_id'] ) ) {
	$gTask = new Task( null, $_REQUEST['content_id'] );
	$gTask->load();
	$gTask->loadXrefList();
} else {
	$gTask = new Tasks();
}

$gBitSmarty->assign_by_ref( 'taskInfo', $gTask->mInfo );
$gBitSmarty->assign_by_ref( 'citizenInfo', $gCitizen->mInfo );
if ( $gTask->isValid() ) {
	$gBitSystem->setBrowserTitle("Task List Item");
	$gBitSystem->display( 'bitpackage:tasks/show_task.tpl', NULL, array( 'display_mode' => 'display' ));
} else {
	header ("location: ".TASKS_PKG_URL."view.php");
	die;
}
?>