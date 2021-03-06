<?php
/**
 * @version $Header$
 *
 * Copyright (c) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package citizen
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

$gBitSystem->verifyPackage( 'citizen' );
include_once( CITIZEN_PKG_PATH.'Citizen.php' );

$gBitSystem->verifyPermission( 'p_citizen_view' );

$userstate = $gBitUser->getPreference( 'task_process', 0 );
if ( $userstate ) {
	if( !empty( $_REQUEST["content_id"] ) ) {
		$gTask = new Tasks( null, $userstate );
		$gTask->load();
		$updatetask = array();
		$updatetask['new_citizen'] = $_REQUEST['content_id'];
	 	$gTask->store( $updatetask ); 	
	}
}
$gBitSmarty->assignByRef( 'userstate', $userstate );
	
$gContent = new Citizen( );

if( !empty( $_REQUEST["find_org"] ) ) {
	$_REQUEST["find_name"] = '';
	$_REQUEST["sort_mode"] = 'organisation_asc';
} else if( empty( $_REQUEST["sort_mode"] ) ) {
	$_REQUEST["sort_mode"] = 'surname_asc';
	$_REQUEST["find_name"] = 'a,a';
}

//$citizen_type = $gContent->getCitizensTypeList();
//$gBitSmarty->assignByRef('citizen_type', $citizen_type);
$listHash = $_REQUEST;
// Get a list of matching citizen entries
$listcitizens = $gContent->getCitizenList( $listHash );

$gBitSmarty->assignByRef( 'listcitizens', $listcitizens );
$gBitSmarty->assignByRef( 'listInfo', $listHash['listInfo'] );

$gBitSystem->setBrowserTitle("View Citizens List");
// Display the template
$gBitSystem->display( 'bitpackage:citizen/list.tpl', NULL, array( 'display_mode' => 'list' ));

?>
