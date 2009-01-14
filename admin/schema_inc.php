<?php
$tables = array(

'task_ticket' => "
  ticket_id I8 PRIMARY,
  ticket_ref T NOT NULL,
  office I4 NOTNULL,
  ticket_no I4 NOT NULL,
  department I2 DEFAULT 0,
  tags I4 DEFAULT 0,
  clearance I2 DEFAULT 0,
  room I2 DEFAULT 0,
  last T,
  staff_id I4 NOTNULL,
  init_id I4 NOTNULL,
  caller_id I8,
  appoint_id I8 DEFAULT 0,
  applet V(1) DEFAULT '' NOTNULL,
  note C(40),
  memo X
",

'task_transaction' => "
  ticket_id I8 PRIMARY,
  transact_no I2 PRIMARY,
  transact T NOTNULL,
  ticket_ref T NOTNULL,
  office I4 NOTNULL,
  ticket_no I4 NOTNULL,
  staff_id I4 NOTNULL,
  previous I4 NOTNULL,
  room I2,
  proom I2,
  tags I4 DEFAULT 0,
  clearance I2 DEFAULT 0,
  applet V(1) DEFAULT '' NOTNULL
",

'task_reason' => "
  reason I2 PRIMARY,
  title C(40),
  reason_type I2,
  reason_source I2,
  tag V(3),
  qty I4
",

'task_appointment' => "
  appoint_id I2 PRIMARY,
  appoint_date T NOTNULL,
  appoint_time T NOTNULL,
  staff_id I4 NOTNULL,
  citizen_id I4 NOTNULL,
  reason I2,
  office I4,
  room I2,
  note C(40),
  letter C(40),
  ticket_id I4
",

'task_booking_plan' => "
  office I4 PRIMARY,
  row I2 PRIMARY,
  title C(5),
  atime T,
  rooms I2
",

'task_stats' => "
  office I4 PRIMARY,
  time_st T PRIMARY,
  queue I2 PRIMARY,
  line1 I2,
  line2 I2,
  line3 I2
",

'task_stats_print' => "
  office I4 PRIMARY,
  col I2 PRIMARY,
  title C(5),
  line1 I2,
  line2 I2,
  line3 I2,
  coltime T
",

'task_roomstat' => "
  office I4 PRIMARY,
  terminal I2 PRIMARY,
  title C(40),
  head C(8),
  announce C(32),
  ter_type I2,
  led I2,
  ledhead C(4),
  beacon I2,
  camera I2,
  serving I2,
  act1 I2,
  fro_ I2,
  alarm I2,
  curmode I2,
  x1 I2,
  x2 I2,
  x3 I2,
  x4 I2,
  x5 I2,
  x6 I2,
  x7 I2,
  x8 I2,
  x9 I2,
  x10 I2,
  status I2,
  logon I4,
  ter_location C(32),
  ticketprint C(32),
  reportprint C(32),
  booking I4,
  book I4
",

'task_caller' => "
  caller_id I8 PRIMARY,
  cltype I2,
  title V(35) DEFAULT '',
  surname C(32) DEFAULT '',
  forename C(32) DEFAULT '',
  company C(40),
  ni C(9),
  hbis C(10),
  address C(255) DEFAULT '',
  postcode C(10) DEFAULT '',
  lastvisit T,
  specialneeds C(4),
  staff_id I4 DEFAULT 0,
  note C(40),
  memo X,
  cllink I4,
  usn I8
",

'task_staff' => "
  staff_id I8 PRIMARY,
  surname C(32) DEFAULT '',
  forename C(32) DEFAULT '',
  initials C(4) DEFAULT '',
  direct C(16) DEFAULT '',
  team I4 DEFAULT 0,
  ext C(8) DEFAULT '',
  category I2 DEFAULT 0,
  logon C(4),
  note C(40),
  logged I2 DEFAULT 0,
  host C(32),
  content_id I4,
  appoint T,
  office I2
",

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( TASKS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( TASKS_PKG_NAME, array(
	'description' => "Base Task management package for ticket record management on ",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array (
	'ticket_department_idx' => array( 'table' => 'task_ticket', 'cols' => 'department', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( TASKS_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'task_id_seq' => array( 'start' => 1 ),
);
$gBitInstaller->registerSchemaSequences( TASKS_PKG_NAME, $sequences );

// ### Defaults

// ### Default User Permissions
$gBitInstaller->registerUserPermissions( TASKS_PKG_NAME, array(
	array('p_tasks_view', 'Can browse the Task List', 'basic', TASKS_PKG_NAME),
	array('p_tasks_update', 'Can update the Task content', 'editors', TASKS_PKG_NAME),
	array('p_tasks_create', 'Can create a new Task entry', 'registered', TASKS_PKG_NAME),
	array('p_tasks_supervise', 'Can supervise Task List', 'admin', TASKS_PKG_NAME),
	array('p_tasks_admin', 'Can admin Task List', 'admin', TASKS_PKG_NAME),
	array('p_tasks_expunge', 'Can remove a Task entry', 'admin', TASKS_PKG_NAME)
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( TASKS_PKG_NAME, array(
	array( TASKS_PKG_NAME, 'task_list_created','y'),
	array( TASKS_PKG_NAME, 'task_list_lastmodif','y'),
	array( TASKS_PKG_NAME, 'task_list_notes','y'),
	array( TASKS_PKG_NAME, 'task_list_title','y'),
	array( TASKS_PKG_NAME, 'task_list_user','y'),
) );

// Processing/Serving will disable other access to that record, Finished will prevent any further editing of a record
$gBitInstaller->registerSchemaDefault( LIBERTY_PKG_NAME, array(
	"INSERT INTO `".BIT_DB_PREFIX."liberty_content_status` (`content_status_id`,`content_status_name`) VALUES (-50, 'Processing')",
	"INSERT INTO `".BIT_DB_PREFIX."liberty_content_status` (`content_status_id`,`content_status_name`) VALUES (60, 'Finished')",
) );

?>
