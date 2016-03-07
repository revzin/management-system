<?php
require_once('employee_tools.php');
require_once('common/ora_session.php');
require_once('echo_tools.php');

function AMSEchoEmployeeEditor($id)
{
	
}

function AMSEchoEmployeeList()
{
	$row_array = emp_build_list($sort_column = 'emp_surname', $sort_direction = 'ASC', $group_by_role = TRUE);
	
	echo "<table> <tbody>";
	for($i = 0; $i < count($row_array); $i += 1) {
		emp_echo_row($row_array, $i);
	}
	echo "</tbody> </table>";
}

function emp_build_list($sort_column = null, 
							$sort_direction = null, $group_by_role = FALSE)
{
	$current_role = $_SESSION[SESSIONKEY_EMPLOYEE_ROLE];
	
	if (!AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_EMPLOYEES) and
		!AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_GROUP))
	{
		echo MSG_INSUFFICIENT_PERMSSIONS;
		return;
	}
	
	$query = QUERY_GET_EMPLOYEES_OVERVIEW;
	
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_EMPLOYEES))  {
	
	} 
	else if (AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_GROUP)) {
		$query .= ' ' . QueryStringReplace(QUERY_SGMT_WHERE_VALUE, 
												array("param", "value"), 
												array("emp_role", strval($current_role)));
	} 
	else {
		echo MSG_INSUFFICIENT_PERMSSIONS;
		return;
	}
	
	if ($group_by_role) {
		if ($sort_column) {
			if ($sort_direction == 'DESC') {
				$query .= ' ' . QueryStringReplace(QUERY_SGMT_ORDER_BY_DESC, 
													"sort", 
													"emp_role, " . $sort_column);		
			} else {
				$query .= ' ' . QueryStringReplace(QUERY_SGMT_ORDER_BY_ASC, 
													"sort", 
													"emp_role, " . $sort_column);
			}
		} 
		else {
			$query .= ' ' . QueryStringReplace(QUERY_SGMT_ORDER_BY_ASC, 
													"sort", 
													"emp_role");
		}
	}
	echo $query . '<br />';
	$rows = array();
	$numrows = OracleQuickReadQuery($query, 
									array("emp_role", "emp_name", "emp_surname", "emp_phone",
											"emp_email"),
									$rows);
	
	if (0 == $numrows) {
		echo MSG_NO_DATA_TO_SHOW;
		return NULL;
	}
	else
		return $rows;
}


function emp_echo_column_role($row, $role_column_name)
{
	echo '<td>';
	echo AMSEmployeeRoleToString($row[$role_column_name]);
	echo '</td>';
}

function emp_echo_row($row_array, $row_number)
{
	$row = $row_array[$row_number];
	echo '<tr>';
	echo '<td>' . ($row_number + 1) . '</td>';
	ToolsEchoColumn($row, 'emp_surname');
	ToolsEchoColumn($row, 'emp_name');
	ToolsEchoColumn($row, 'emp_email');
	ToolsEchoColumn($row, 'emp_phone');
	emp_echo_column_role($row, 'emp_role');
	echo '</tr>';
}



?>

