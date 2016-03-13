<?php

define('QUERY_ALL_USER_TABLE_NAMES', 	'SELECT table_name FROM user_tables');


define('QUERY_GET_EMPLOYEES_OVERVIEW', 	'SELECT emp_id, emp_role, emp_name, emp_surname, 
											emp_email, emp_phone, emp_salary, emp_login 
											FROM employee');
											
define('QUERY_GET_EMPLOYEE_SESSIONDATA','SELECT emp_role, emp_name, emp_surname 
											FROM employee WHERE emp_id = ^emp_id');
define('QUERY_GET_EMPLOYEE_EVERYTHING',	'SELECT emp_id, emp_role, emp_name, emp_surname,
												emp_login, emp_phone, emp_email,
												emp_salary, emp_login, emp_password
												FROM employee WHERE emp_id = ^emp_id');
											
define('QUERY_GET_USER_ID_PASSWORD',	"SELECT emp_id, emp_password 
											FROM employee 
											WHERE emp_login = '^emp_login'");
											
define('QUERY_GET_USER_ROLE', 			'SELECT emp_role 
											FROM employee 
											WHERE emp_id = ^id');

define('QUERY_GET_ENCODING', 			"SELECT value 
											FROM nls_database_parameters 
											WHERE parameter = 'NLS_CHARACTERSET'");

define('QUERY_READ_EMPLOYEE_JOURNAL', 	"SELECT ej.j_author_id, ej.j_text, ej.j_date
										FROM employee e, TABLE(e.emp_journal) ej 
										WHERE e.emp_id = ^emp_id ORDER BY ej.j_date ASC");

define('QUERY_INSERT_NEW_EMPLOYEE',		"INSERT INTO employee 
											(emp_name, emp_surname, emp_email, emp_login, emp_password,
											emp_role, emp_salary, emp_phone, emp_journal)
											 VALUES
											 ('^emp_name', '^emp_surname', '^emp_email', '^emp_login', '^emp_password',
											 ^emp_role, ^emp_salary, ^emp_phone, jrnl_table())");
											 

define('QUERY_UPDATE_EMPLOYEE',			"UPDATE employee
										SET emp_name = '^emp_name',
											emp_surname = '^emp_surname',
											emp_email = '^emp_email',
											emp_login = '^emp_login',
											emp_password = '^emp_password',
											emp_role	 = ^emp_role,
											emp_salary = ^emp_salary,
											emp_phone = ^emp_phone
										WHERE emp_id = ^emp_id");

define('QUERY_LOGIN_TO_ID',				"SELECT emp_id FROM employee WHERE emp_login = '^emp_login'");
										
define('QUERY_INSERT_INTO_EMP_JOURNAL',	"INSERT
											INTO TABLE(
												SELECT emp_journal
												FROM employee
												WHERE emp_id = ^emp_id
											)
											VALUES(
												journal_t(CURRENT_TIMESTAMP, ^author_id, '^journal_emp_text')
											)");

define('QUERY_COUNT_ROLE',				"SELECT COUNT(emp_id) 
											AS count 
											FROM employee 
											WHERE emp_role = ^emp_role");
											
define('QUERY_SGMT_ORDER_BY_ASC',		'ORDER BY ^sort ASC');
define('QUERY_SGMT_ORDER_BY_DESC',		'ORDER BY ^sort DESC');
define('QUERY_SGMT_WHERE_VALUE',		"WHERE ^param = '^value'");
define('QUERY_SGMT_GROUP_BY',			'GROUP BY ^criteria');

define('USE_STRING_CONVERSION', FALSE);

function QueryStringReplace($query, $keys, $args)
{	
	if (!is_array($keys) && !is_array($args)) {
		$query = str_replace('^' . strval($keys), strval($args), $query);
	} 
	else {
		if (count($keys) != count($args))
			return FALSE;
		
		$i = 0;
		foreach($keys as $k) {
			$query = str_replace('^' . strval($k), strval($args[$i]), $query);
			$i += 1;
		}
	}
	
	if (strpos($query, '^') !== FALSE)
		die("QueryStringReplace: bad keys // " . debug_print_backtrace());
		
	return $query;
}

function OracleTrimTimestampToDate($timestamp)
{
	return substr($timestamp, 0, strlen('DD-MM-YY'));
}

?>
