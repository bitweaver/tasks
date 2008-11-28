<?php

// $Header: /cvsroot/bitweaver/_bit_tasks/admin/admin_tasks_inc.php,v 1.1 2008/11/28 11:35:52 lsces Exp $

// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

include_once( TASKS_PKG_PATH.'Tasks.php' );

$formCitizenListFeatures = array(
	"task_list_created" => array(
		'label' => 'Record created',
	),
	"tasks_list_lastmodif" => array(
		'label' => 'Last modified',
	),
	"tasks_list_notes" => array(
		'label' => 'Record note entry',
	),
	"tasks_list_title" => array(
		'label' => 'Record summary',
	),
	"tasks_list_user" => array(
		'label' => 'Created By',
	),
);
$gBitSmarty->assign( 'formtasksListFeatures',$formtasksListFeatures );

if (isset($_REQUEST["taskslistfeatures"])) {
	
	foreach( $formtasksListFeatures as $item => $data ) {
		simple_set_toggle( $item, tasks_PKG_NAME );
	}
}

?>
