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
												
define('QUERY_GET_EMPLOYEE_NAME',		'SELECT emp_id, emp_name, emp_surname
												FROM employee WHERE emp_id = ^emp_id');
												
define('QUERY_GET_USER_ID_PASSWORD',	"SELECT emp_id, emp_password 
											FROM employee 
											WHERE emp_login = '^emp_login'");
											
define('QUERY_GET_USER_ROLE', 			'SELECT emp_role 
											FROM employee 
											WHERE emp_id = ^id');
											
define('QUERY_GET_EMPL_BY_ROLE',		'SELECT emp_id, emp_name, emp_surname 
											FROM employee 
											WHERE emp_role = ^emp_role');
											
define('QUERY_GET_ENCODING', 			"SELECT value 
											FROM nls_database_parameters 
											WHERE parameter = 'NLS_CHARACTERSET'");

define('QUERY_READ_EMPLOYEE_JOURNAL', 	"SELECT ej_empl, ej_timestamp, ej_author_id, ej_text
											FROM ejournal
											WHERE ej_empl = ^emp_id 
											ORDER BY ej_timestamp ASC");

define('QUERY_INSERT_NEW_EMPLOYEE',		"INSERT INTO employee 
											(
												emp_name, 
												emp_surname, 
												emp_email, 
												emp_login, 
												emp_password,
												emp_role,
												emp_salary, 
												emp_phone)
											 VALUES
											 (
												'^emp_name', 
												'^emp_surname', 
												'^emp_email', 
												'^emp_login', 
												'^emp_password',
												^emp_role, 
												^emp_salary, 
												^emp_phone
											)");
											 

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
										
define('QUERY_INSERT_INTO_EMP_JOURNAL',	"INSERT INTO ejournal
											(
												ej_empl,
												ej_timestamp,
												ej_author_id,
												ej_text
											)	
											VALUES
											(
												^emp_id, 
												CURRENT_TIMESTAMP, 
												^author_id, 
												'^journal_emp_text'
											)");

define('QUERY_COUNT_ROLE',				"SELECT COUNT(emp_id) 
											AS count 
											FROM employee 
											WHERE emp_role = ^emp_role");

define('QUERY_INSERT_ORDER',			"INSERT INTO unit
											(
												u_asmy_mng_id,
												u_state,
												u_ord_time
											)
											VALUES
											(
												^mng_id,
												0, /* AMS_STATE_NEW */
												CURRENT_TIMESTAMP
											)");
											
define('QUERY_ASSEMBLE_UNIT',			"UPDATE unit
											SET 
												u_asm_time = CURRENT_TIMESTAMP,
												u_state = 3 /* AMS_STATE_ASMY_COMPLETE */
											WHERE
												u_id = ^u_id");
												
define('QUERY_UPDATE_ASMY_WORKER',		"UPDATE unit
												SET 
													u_asmy_work_id = ^u_asmy_work_id
												WHERE
													u_id = ^u_id");
													
define('QUERY_UPDATE_CONTROLLER',		"UPDATE unit
											SET 
												u_asmy_cont_id = ^u_asmy_cont_id
											WHERE
												u_id = ^u_id");
													
define('QUERY_GET_LAST_PLACED_UNIT_ID',	"SELECT MAX(u_id)
											AS u_id
											FROM unit");
											
define('QUERY_UNIT_ID_TO_SERIAL',		"SELECT u_serial
											FROM unit
											WHERE 
												u_id = ^u_id");
											
define('QUERY_CONTROL_UNIT_OK',			"UPDATE unit
											SET 
												u_ctrl_time = CURRENT_TIMESTAMP,
												u_state = 4 /* AMS_STATE_FINISH_COMPLETE */
											WHERE
												u_id = ^u_id");
												
define('QUERY_CONTROL_UNIT_DISCARD',	"UPDATE unit
											SET 
												u_ctrl_time = CURRENT_TIMESTAMP,
												u_state = 5 /* AMS_STATE_FINISH_FAILURE */
											WHERE
												u_id = ^u_id");

define('QUERY_INSERT_INTO_LOG',			"INSERT INTO manlog
											(
												ml_unit_id,
												ml_text,
												ml_time
											)	
											VALUES
											(
												^unit_id,
												'^text',
												CURRENT_TIMESTAMP
											)");

define('QUERY_SELECT_FROM_LOG',			"SELECT ml_text, ml_time, ml_unit_id
											FROM
												manlog
											WHERE
												ml_unit_id = ^unit_id
											ORDER BY
												ml_time ASC");
																								
define('QUERY_GET_UNITS',				"SELECT 
											u_serial,
											u_id,
											u_asmy_mng_id,
											u_asmy_work_id,
											u_asmy_cont_id,
											u_asmy_disc_id,
											u_state,
											u_ord_time,
											u_asm_time,
											u_ctrl_time,
											u_disc_time
										FROM
											unit");

define('QUERY_GET_UNIT_DATA',			"SELECT 
											u_serial,
											u_id,
											u_asmy_mng_id,
											u_asmy_work_id,
											u_asmy_cont_id,
											u_asmy_disc_id,
											u_state,
											u_ord_time,
											u_asm_time,
											u_ctrl_time,
											u_disc_time
										FROM
											unit
										WHERE
											u_id = ^u_id");											
											
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
		die("QueryStringReplace: bad keys // " . $query);
		
	return $query;
}

function OracleTrimTimestampToDate($timestamp)
{
	return substr($timestamp, 0, strlen('DD-MM-YY'));
}

function OracleTrimTimestampToDateTime($timestamp)
{
	return substr($timestamp, 0, strlen('DD-MM-YY HH-MM-SS'));
}

?>
