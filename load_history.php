<?php
/*
 * Created on 5 Jan 2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 * @package tasks
 * @subpackage functions
 */

/**
 * Initialization
 */
require_once( '../kernel/setup_inc.php' );
require_once( TASKS_PKG_PATH.'Tasks.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'citizen' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_citizen_admin' );

$task = new Tasks();
set_time_limit(0);

if( empty( $_REQUEST["update"] ) ) {
	$task->HistoryExpunge();
	$update = 0;
} else {
	$update = $_REQUEST["update"];
}

$row = 0;

$handle = fopen("data/ticket.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 800, "\t")) !== FALSE) {
    	if ( $row ) {
    		$task->TicketRecordLoad( $data );
    	}
    	$row++;
	}
	fclose($handle);
}

$gBitSmarty->assign( 'ticket', $row );

$row = 0;
$handle = fopen("data/transactions.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 4000, "\t")) !== FALSE) {
		if ( $row ) {
			$task->TransactionRecordLoad( $data );
		}
    	$row++;
	} 
	fclose($handle);
}
$gBitSmarty->assign( 'transaction', $row );

$row = 0;
$handle = fopen("data/reason.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 4000, "\t")) !== FALSE) {
		if ( $row ) {
			$task->ReasonRecordLoad( $data );
		}
    	$row++;
	} 
	fclose($handle);
}
$gBitSmarty->assign( 'reason', $row );

$row = 0;
$handle = fopen("data/roomstat.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 4000, "\t")) !== FALSE) {
		if ( $row ) {
			$task->RoomstatRecordLoad( $data );
		}
    	$row++;
	} 
	fclose($handle);
}
$gBitSmarty->assign( 'roomstat', $row );

$row = 0;
$handle = fopen("data/caller.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 4000, "\t", '¬')) !== FALSE) {
		if ( $row ) {
			$task->CallerRecordLoad( $data );
		}
    	$row++;
	} 
	fclose($handle);
}
$gBitSmarty->assign( 'caller', $row );

$row = 0;
$handle = fopen("data/staff.csv", "r");
if ( $handle == FALSE) {
	$row = -999;
} else {
	while (($data = fgetcsv($handle, 4000, "\t")) !== FALSE) {
		if ( $row ) {
			$task->StaffRecordLoad( $data );
		}
    	$row++;
	} 
	fclose($handle);
}
$gBitSmarty->assign( 'staff', $row );

$gBitSystem->display( 'bitpackage:tasks/load_history.tpl', tra( 'Load results: ' ) );
?>
