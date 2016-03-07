<?php

define('QUERY_ALL_USER_TABLE_NAMES', 'SELECT table_name FROM user_tables');


define('QUERY_GET_EMPLOYEES_OVERVIEW', 'SELECT emp_role, emp_name, emp_surname, emp_email, emp_phone, emp_salary, emp_login FROM employee');

define('QUERY_GET_USER_ROLE', 'SELECT emp_role FROM employee WHERE emp_id = ^id');

define('QUERY_GET_ENCODING', "SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");

define('USE_STRING_CONVERSION', FALSE);

function QueryStringReplace($query, $keys, $args)
{
	if (!is_array($keys) && !is_array($args)) {
		return str_replace('^' . strval($keys), strval($args), $query);
	} 
	else {
		if (count($keys) != count($args))
			return FALSE;
		
		$i = 0;
		foreach($keys as $k) {
			$query = str_replace('^' . strval($k), strval($args[$i]), $query);
			$i += 1;
		}
		return $query;
	}
}

?>
