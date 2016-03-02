<?php

define('QUERY_ALL_USER_TABLE_NAMES', 'SELECT table_name FROM user_tables');


define('QUERY_GET_USER_ROLE', 'SELECT emp_role FROM employee WHERE emp_id = ^id');



function QueryStringReplace($query, $argarray)
{
	
}


?>