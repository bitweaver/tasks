<?php
global $gBitSystem, $gBitSmarty;
$registerHash = array(
	'package_name' => 'tasks',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'tasks' ) ) {
	$menuHash = array(
		'package_name'  => TASKS_PKG_NAME,
		'index_url'     => TASKS_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:tasks/menu_tasks.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}

?>
