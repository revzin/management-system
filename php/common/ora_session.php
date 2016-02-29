<?php
require("ora_user.php");

function OracleConnect() 
{
	$dbc = OCILogon(ORACLE_USER, ORACLE_PASSWORD, ORACLE_SERVICE);
	if (!$dbc) {
		error_log("ERROR: OCILogon failed: " . var_dump(OCIError()))
		return false;
	}
	return $dbc;
}

function OracleDisconnect($dbc)
{
	OCICommit($dbc);
	OCILogoff($dbc);
}


?>
