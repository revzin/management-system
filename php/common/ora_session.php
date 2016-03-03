<?php
require("ora_user.php");

function OracleConnect() 
{
	$dbc = OCILogon(ORACLE_USER, ORACLE_PASSWORD, ORACLE_SERVICE);
	if (!$dbc) {
		error_log("ERROR: OCILogon failed: " . var_dump(OCIError()));
		return false;
	}
	return $dbc;
}
	
function OracleConnectSafe() 
{
	$dbc = OracleConnect();
	if (!$dbc)
		die("db failure");
	else
		return $dbc;
}

function OracleDisconnect($dbc)
{
	OCICommit($dbc);
	OCILogoff($dbc);
}

function OracleQuickQuery($query_string, $key_array)
{
	$dbc = OracleConnectSafe();
	$q = OCIParse($dbc, $query_string);
	OCIExecute($q);
	
	$result = array();
	$i = 0;
	while (OCIFetch($q)) {
		foreach($key_array as $k) {
			$result[$i][strval($k)] = OCIResult($q, strtoupper(strval($k)));
		}
		$i += 1;
	}
	OracleDisconnect($dbc);
	return $result;
}

?>
