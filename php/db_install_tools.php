<?php

require('common/ora_queries.php');
require('common/ora_session.php');

define('SQL_SCRIPT_PATH', '../sql/');

$check_table_names = array('employee', 'unit', 'warehouse');

function db_tables_exist($db_table_names)
{
	foreach($db_table_names as $name) {
		if (!array_key_exists($name, $db_table_names))
			return FALSE;
	}
	return TRUE;
}


function OracleTestDatabaseInstallation()
{
	$dbc = OracleConnect();
	
	if (!$dbc) {
		error_log("ERROR: can't login to Oracle");
		return 'LOGIN_FAILURE';
	}
	else {
		$q = OCIParse($dbc, QUERY_ALL_USER_TABLE_NAMES);
		OCIExecute($q);
		
		if (OCIError($q)) {
			error_log("ERROR: can't get all table names");
			return 'QUERY_FAILURE';
		}
		
		$table_names = array();
		
		while (OCIFetch($q)) {
			array_push($table_names, OCIResult($q, 'TABLE_NAME'));
		}
		
		if (db_tables_exist($table_names)) {
			return 'DATABASE_INSTALLED';
		}
		else {
			return 'DATABASE_NOT_INSTALLED';
		}
		
	}
	
	OracleDisconnect($dbc);
}


function OracleRunSQLScript($sql_filename)
{
	$dbc = OracleConnect();
	echo file_get_contents(SQL_SCRIPT_PATH . $sql_filename);
	$query = OCIParse($dbc, file_get_contents(SQL_SCRIPT_PATH . $sql_filename));
	OCIExecute($query);
	$err = OCIError($query);
	if (FALSE != $err) {
		error_log('ERROR: Failed executing ' . $sql_filename . ' ' . var_dump($err));
	}
	
	OracleDisconnect($dbc);	
}


?>

