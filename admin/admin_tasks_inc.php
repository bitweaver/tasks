<?php

// $Header$

// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

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
