<?php
require_once('employee_tools.php');
require_once('common/ora_session.php');
require_once('echo_tools.php');


function append_role_option($role_id, $str, $employee_role)
{
	$str .= '<option value =' . strval($role_id);
	if ($role_id == $employee_role)
		$str .= ' selected';
	$str .= '>';
	$str .= AMSEmployeeRoleToString($role_id);
	$str .= '</option>';
	return $str;
}

function make_role_dropdown($can_fire, $employee_role) 
{
	if ($employee_role == AMS_ROLE_FIRED and !AMSEmployeeHasPermission(AMS_PERM_EMP_HIREFIRE)) {
		return AMSEmployeeRoleToString($employee_role);
	}
	
	$str = '<select name = "emp_role">';
			
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_PROMOTE_TO_BOSS)) 
		$str = append_role_option(AMS_ROLE_BOSS, $str, $employee_role); 
	
	$str = append_role_option(AMS_ROLE_ASMY_WRK, $str, $employee_role);
	$str = append_role_option(AMS_ROLE_MGR, $str, $employee_role);
	$str = append_role_option(AMS_ROLE_ADM, $str, $employee_role);
	$str = append_role_option(AMS_ROLE_WM, $str, $employee_role);
	
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_HIREFIRE))
		$str = append_role_option(AMS_ROLE_FIRED, $str, $employee_role);
	
	
	$str .= '</select>';
	return $str;
}

function my_abilities_on_employee($his_role, $my_role, $id) 
{	
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_HIREFIRE))
		return 'FULL';
	
	if ($my_role == AMS_ROLE_MGR and $his_role == AMS_ROLE_BOSS)
		// ToolsMakeUpSex();
		return 'VIEW';
		
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_EDIT_EMPLOYEES))
		return 'EDIT';
	
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_GROUP) and ($his_role == $my_role))
		return 'VIEW';
	
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_EMPLOYEES))
		return 'VIEW';
	
	if (($_SESSION[SESSIONKEY_EMPLOYEE_ID] == $id) 
			and (AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_EMPLOYEES)))
		return 'VIEW';
		
	return 'NONE';
}

function validate_id($id) 
{
	if ($id == 'NEW') {
		return TRUE;
	}
	if (intval($id) > 9) { 
		// см. install_tables.sql триггер автоинкремента
		return TRUE;
	}
	return FALSE;
}

function journal_echo_row($row_array, $row_number)
{
	$row = $row_array[$row_number];
	$employee_link_ref = 'employee.php?mode=detail&employee_id=' . $row['j_author_id'];
	
	$row['j_author_id'] = AMSEmployeeID2Name($row['j_author_id']);

	echo '<tr>';
	echo '<td>' . strval($row_number + 1) .  '</td>';

	ToolsEchoColumn($row, 'j_date', $link = FALSE, $timestamp_to_date = TRUE);
	ToolsEchoColumn($row, 'j_text');
	ToolsEchoColumn($row, 'j_author_id', $employee_link_ref);
	
	echo '</tr>';
}

function AMSEchoEmployeeDetail($id)
{
	if (!validate_id($id))
		return;

	$can_edit = FALSE;
	$can_fire = FALSE;
	$can_view_journal = FALSE;
	
	$his_role = 0;
	$my_role = $_SESSION[SESSIONKEY_EMPLOYEE_ROLE];

	if ($id == 'NEW') {
		if (!AMSEmployeeHasPermission(AMS_PERM_EMP_HIREFIRE)) {
			echo MSG_INSUFFICIENT_PERMISSIONS_HIREFIRE;
			return;
		}
		$can_edit = TRUE;	
	}
	else {
		$his_role = AMSEmployeeGetRole($id);
		
		$abilities = my_abilities_on_employee($his_role, $my_role, $id);
		
		if ($abilities == 'NONE') {
			echo MSG_INSUFFICIENT_PERMISSIONS_VIEW_EMPL;
			return;
		}
		
		if ($abilities == 'VIEW') {
			$can_edit = FALSE;
			if ($id == $_SESSION[SESSIONKEY_EMPLOYEE_ID]) {
				$can_view_journal = TRUE;
			}
			else {
				$can_view_journal = FALSE;
			}
		}
		
		if ($abilities == 'EDIT') {
			$can_edit = TRUE;
			$can_view_journal = TRUE;
		}
		
		if ($abilities == 'FULL') {
			$can_edit = TRUE;
			$can_fire = TRUE;
			$can_view_journal = TRUE;
		}
	}	

	if ($id == 'NEW') {
		$emp_data["emp_id"] = 'будет установлен после создания';
		$emp_data["emp_name"] = 'Новый';
		$emp_data["emp_surname"] = 'Сотрудник';
		$emp_data["emp_phone"] = '';
		$emp_data["emp_salary"] = '';
		$emp_data["emp_email"] = '';
		$emp_data["emp_login"] = '';
		$emp_data["emp_password"] = '';
	}
	else {
		$rows = array();
		$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_EMPLOYEE_EVERYTHING, "emp_id", $id), 
						array("emp_id", "emp_role", 
							"emp_name", "emp_surname",
							"emp_phone", "emp_salary",
							"emp_email", "emp_login",
							"emp_password"),
						$rows);

		if (0 == $numrows)
			return 'WRONG_EMPLOYEE_ID';
			
		$emp_data = $rows[0];
	}
	if ($can_edit) {
		$html = file_get_contents("../html/employee_editor_form.html");
	}
	else
		$html = file_get_contents("../html/employee_viewer.html");
		

	$html = str_replace('%%ID%%', $emp_data["emp_id"], $html);
	$html = str_replace('%%NAME%%', $emp_data["emp_name"], $html);
	$html = str_replace("%%SURNAME%%", $emp_data["emp_surname"], $html);
	$html = str_replace("%%PHONE%%", $emp_data["emp_phone"], $html);
	
	if ($can_edit OR ($id == $_SESSION[SESSIONKEY_EMPLOYEE_ID])) {
		$html = str_replace("%%SALARY%%", $emp_data["emp_salary"], $html);
	} 
	else {
		$html = str_replace("%%SALARY%%", MSG_SALARY_HIDDEN, $html);
	}
	
	$html = str_replace("%%EMAIL%%", $emp_data["emp_email"], $html);
	$html = str_replace("%%LOGIN%%", $emp_data["emp_login"], $html);
	$html = str_replace("%%PASSWORD%%", $emp_data["emp_password"], $html);
	
	if ($can_edit) {
		$html = str_replace("%%ROLE_SELECT%%", 
							make_role_dropdown($can_fire, $his_role), 
							$html);
	}
	else {
		$html = str_replace("%%ROLE_SELECT%%", 
							AMSEmployeeRoleToString($emp_data["emp_role"]), 
							$html);
	}
	
	if ($id == 'NEW') {
		$html = str_replace("%%EMPLOYEEID%%", 'NEW', $html);
	}
	else {
		$html = str_replace("%%EMPLOYEEID%%", $id, $html);
	}
		
	echo $html;
	
	echo '<br />';
	
	// Журнал записей
	
	if ($id == 'NEW')
		return;
	
	if ($can_view_journal == FALSE)
		return;
	
	$row_array = array();
	$numrows = OracleQuickReadQuery(
			QueryStringReplace(QUERY_READ_EMPLOYEE_JOURNAL, "emp_id", $id), 
			array("j_author_id", "j_text", "j_date"),
			$row_array);
				
	if (0 == $numrows)
		die('NO JOURNAL FOR EMPLOYEE'); // нет записей в журнале, хотя это не должно быть

	echo "<h3>" . 'Сотрудник ' . AMSEmployeeID2Name($id) . ': Журнал записей в личное дело  </h3>';
		
	echo "<form name = 'journal_new_row' method = 'POST' action = 'employee.php'>";
	echo "<input type = hidden name = 'target_employee_id' value = '". $id ."' />";
	echo "<input type = hidden name = 'journal_edtior_form' value = 'TRUE' />";
	echo "<table class = 'employees'> <tbody>";	
	$header = file_get_contents("../html/journal_table_header.html");
	echo $header;
	
	for($i = 0; $i < count($row_array); $i += 1) {
		journal_echo_row($row_array, $i);
	}
	
	if ($can_edit) {
		$html = file_get_contents("../html/journal_table_newrow.html");
		$html = str_replace('%%CURRENT_DATE%%', date('d.m.y'), $html);
		$html = str_replace('%%CURRENT_USER_NAME%%', 'Вы: ' . 
													$_SESSION[SESSIONKEY_EMPLOYEE_NAME], 
													$html);
		echo $html;
	}
	
	echo "</tbody> </table>";
	echo "</form>";
}

function AMSEchoEmployeeList()
{
	
	$row_array = emp_build_list($sort_column = 'emp_surname', 
									$sort_direction = 'ASC', 
									$group_by_role = TRUE);
	
	
	
	echo "<h3>" . 'Список сотрудников' . "</h3>";
		
	echo "<table class = 'employees'> <tbody>";
	$header = file_get_contents("../html/employee_table_header.html");
	echo $header;
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
		echo MSG_INSUFFICIENT_PERMISSIONS;
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
	$rows = array();
	$numrows = OracleQuickReadQuery($query, 
									array("emp_id", "emp_role", "emp_name", "emp_surname", "emp_phone",
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
	$detail_link_ref = 'employee.php?mode=detail&employee_id=' . $row['emp_id'];
	echo '<tr>';
	echo '<td>' . '<a href = "' . $detail_link_ref . '">' . strval($row_number + 1) . '</a> </td>';
	ToolsEchoColumn($row, 'emp_surname', $detail_link_ref);
	ToolsEchoColumn($row, 'emp_name');
	ToolsEchoColumn($row, 'emp_email');
	ToolsEchoColumn($row, 'emp_phone');
	emp_echo_column_role($row, 'emp_role');
	echo '</tr>';
}

function AMSEchoCurrentUserData()
{
	echo 'Пользователь: ' . $_SESSION[SESSIONKEY_EMPLOYEE_NAME] .
		 ', ' . AMSEmployeeRoleToString($_SESSION[SESSIONKEY_EMPLOYEE_ROLE]) . '<br />';
		 
}


function validate_name($name, $surname) 
{
	return TRUE;
}

function validate_email($email)
{
	return TRUE;
}

function validate_phone($phone)
{
	return TRUE;
}

function validate_password($pwd1, $pwd2)
{
	return TRUE;
}

function validate_salary($salary)
{
	return TRUE;
}

function validate_role($role)
{
	return TRUE;
}

function handle_post_employee_editor()
{
	$emp_id = $_POST['emp_id'];
	$emp_name = $_POST['emp_name'];
	$emp_surname = $_POST['emp_surname'];
	$emp_login = $_POST['emp_login'];
	$emp_email = $_POST['emp_email'];
	$emp_phone = $_POST['emp_phone'];
	$emp_password = $_POST['emp_password'];
	$emp_salary = $_POST['emp_salary'];
	$emp_role	= $_POST['emp_role'];
	$emp_name = $_POST['emp_name'];
	
	if ($emp_id == 'NEW') {
		if (!AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_EMPLOYEES)) {
			echo MSG_INSUFFICIENT_PERMISSIONS_HIREFIRE;
			return;
		}	
		
		$query = QueryStringReplace(QUERY_INSERT_NEW_EMPLOYEE,
									array('emp_name', 'emp_surname', 'emp_email', 
											'emp_login', 'emp_password', 'emp_role', 'emp_salary', 'emp_phone'),
									array($emp_name, $emp_surname, $emp_email, $emp_login, 
											$emp_password, $emp_role, $emp_salary, $emp_phone));
									
		$r = OracleQuickWriteQuery($query);

		if ('SUCCESS' != $r) {
			if ($r == ORAERR_UNIQUE_FAILED) {
				echo MSG_LOGIN_TAKEN . ' ' . $emp_login;
			}
			else 
				echo MSG_BAD_POST;
			return;
		}
				
		$rows = array();
		$numrows = OracleQuickReadQuery(
					QueryStringReplace(QUERY_LOGIN_TO_ID, 'emp_login', $emp_login), "emp_id", $rows);
					
		if (!$numrows) 
			die('bad internal error');
		
		$modified_employee_id = $rows[0];
		
		AMSEmployeeAddRowToJournal($modified_employee_id, MSG_JOURNAL_HIRED);
	} 
	else {
		$old_role = AMSEmployeeGetRole($emp_id);
		
		$query = QueryStringReplace(QUERY_UPDATE_EMPLOYEE,
							array('emp_name', 'emp_surname', 'emp_email', 
									'emp_login', 'emp_password', 'emp_role', 'emp_salary', 'emp_phone', 'emp_id'),
							array($emp_name, $emp_surname, $emp_email, $emp_login, $emp_password, 
									$emp_role, $emp_salary, $emp_phone, $emp_id));
							
		$r = OracleQuickWriteQuery($query);

		if ('SUCCESS' != $r) {
			if ($r == ORAERR_UNIQUE_FAILED) {
				echo MSG_LOGIN_TAKEN . ' ' . $emp_login;
			}
			else 
				echo MSG_BAD_POST;
			return;
		}
		
		$new_role = $emp_role;
		if ($old_role == AMS_ROLE_FIRED and $new_role != AMS_ROLE_FIRED) {
			AMSEmployeeAddRowToJournal($emp_id, MSG_JOURNAL_REHIRED);
		}
		else if ($old_role != AMS_ROLE_FIRED and $new_role == AMS_ROLE_FIRED) {
			AMSEmployeeAddRowToJournal($emp_id, MSG_JOURNAL_FIRED);
		}
		else
			AMSEmployeeAddRowToJournal($emp_id, MSG_JOURNAL_DATA_CHANGE);
			
		if ($emp_id == $_SESSION[SESSIONKEY_EMPLOYEE_ID])
			AMSEmployeeSetupSession($emp_id);
	}
}


function handle_post_employee_journal()
{
	$text = $_POST['journal_text'];
	$id = $_POST['target_employee_id'];
	if ($text != '') {
		AMSEmployeeAddRowToJournal($id, $text);
		echo 'Запись в журнал добавлена';
	}
	AMSEchoEmployeeDetail($id);
}


function EmployeeHandlePOST()
{

	if (!isset($_POST))
		return;
	
	if (isset($_POST["employee_editor_form"])) {
		handle_post_employee_editor();
		return;
	}
		
	if (isset($_POST["journal_edtior_form"])) {
		handle_post_employee_journal();
		return;
	}	
}



function EmployeeHandleGET()
{


}

?>
