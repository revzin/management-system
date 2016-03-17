<?php
require_once('unit_tools.php');
require_once('employee_tools.php');
require_once('echo_tools.php');

function unit_expand_state($unit_row_values)
{
	$state = $unit_row_values['u_state'];
	
	$r = array();
	
	$r['PLACED'] = FALSE;
	$r['ASSEMBLED'] = FALSE;
	$r['CONTROLLED'] = FALSE;
	$r['DISCARDED'] = FALSE;
	
	if ($state['u_asmy_work_id'])
		$r['ASMY_MAN_SET'] = TRUE;
	else 
		$r['ASMY_MAN_SET'] = FALSE;
	
	if ($state['u_asmy_ctl_id'])
		$r['CTL_MAN_SET'] = TRUE;
	else 
		$r['CTL_MAN_SET'] = FALSE;
	
	if ($state == AMS_STATE_NEW) {
		$r['PLACED'] = TRUE;
		$r['ASSEMBLED'] = FALSE;
		$r['CONTROLLED'] = FALSE;
		$r['DISCARDED'] = FALSE;
	}
	
	if ($state == AMS_STATE_ASMY_COMPLETE) {
		$r['PLACED'] = TRUE;
		$r['ASSEMBLED'] = TRUE;
		$r['CONTROLLED'] = FALSE;
		$r['DISCARDED'] = FALSE;
	}
	
	if ($state == AMS_STATE_FINISH_OK) {
		$r['PLACED'] = TRUE;
		$r['ASSEMBLED'] = TRUE;
		$r['CONTROLLED'] = TRUE;
		$r['DISCARDED'] = FALSE;
	}
	
	if ($state == AMS_STATE_FINISH_FAILURE) {
		$r['PLACED'] = TRUE;
		$r['DISCARDED'] = TRUE;
		
		if ($unit_row_values['u_asm_time']) {
			$r['ASSEMBLED'] = TRUE;
		}
		else {
			$r['ASSEMBLED'] = FALSE;
			$r['CONTROLLED'] = FALSE;
		}	

		if ($unit_row_values['u_ctrl_time']) {
			$r['CONTROLLED'] = TRUE;
		}
		else {
			$r['ASSEMBLED'] = FALSE;
			$r['CONTROLLED'] = FALSE;
		}
	}
	
	return $r;
}

function expanded_state_to_css_class($expanded_state) 
{
	if ($expanded_state['DISCARDED'])
		return 'order-finish-bad';
	if ($expanded_state['CONTROLLED'])
		return 'order-finish-good';
	return 'order-in-progress';
}

function state_to_string($state) 
{
	switch ($state) {
		case AMS_STATE_NEW:
			return "ожидает монтаж";
		case AMS_STATE_ASMY_COMPLETE:
			return "ожидает контроль";
		case AMS_STATE_FINISH_OK:
			return "выпущено";
		case AMS_STATE_FINISH_FAILURE:
			return "забраковано";
		default:
			return "КИШКИ КРОВЬ";
	}
}

function expanded_state_to_string($expanded_state, $state_key)
{
	$val = $expanded_state[$state_key];
	
	if ($state_key == 'PLACED') {
		return 'заказано';
	}
	if ($state_key == 'ASSEMBLED') {
		if ($val)
			return 'cмонтировано';
		else
			return 'не смонтировано';	
	}
	if ($state_key == 'CONTROLLED') {
		if ($val)
			return 'прошло контроль';
		else
			return 'не проходило контроль';	
	}
	
	if ($state_key == 'DISCARDED') {
		if ($val)
			return 'забраковано';
		else
			return 'исправно';	
	}
}

function build_list_row($row_template, $rows, $row_id)
{
	/* собираем данные */
	$r = strval($row_template);
	$values = $rows[$row_id];
	$state = $values['u_state'];	
	
	$expanded_state = unit_expand_state($values);
	
	if ($expanded_state['PLACED'] == FALSE)
		die('unit table build_list_row(): fucking impossible');
	
	$emp_placed_data = NULL;	
	$emp_asmy_data = NULL;
	$emp_ctl_data = NULL;
	$emp_disc_data = NULL;
	
	$employee_link = 'employee.php?mode=detail&employee_id=';
	
	if ($expanded_state['PLACED']) {
		$rows = array();
		$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_EMPLOYEE_NAME, "emp_id", $values['u_asmy_mng_id']),
						array('emp_name', 'emp_surname', 'emp_id'), 
						$rows);
						
		$emp_placed_data = $rows[0];
	}
	
	if ($expanded_state['ASSEMBLED']) {
		$rows = array();
		$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_EMPLOYEE_NAME, "emp_id", $values['u_asmy_work_id']),
						array('emp_name', 'emp_surname', 'emp_id'), 
						$rows);
						
		$emp_asmy_data = $rows[0];
	}
	
	if ($expanded_state['CONTROLLED']) {
		$rows = array();
		$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_EMPLOYEE_NAME, "emp_id", $values['u_asmy_cont_id']),
						array('emp_name', 'emp_surname', 'emp_id'), 
						$rows);
						
		$emp_ctl_data = $rows[0];
	}
	
	if ($expanded_state['DISCARDED']) {
		$rows = array();
		$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_EMPLOYEE_NAME, "emp_id", $values['u_asmy_cont_id']),
						array('emp_name', 'emp_surname', 'emp_id'), 
						$rows);
						
		$emp_disc_data = $rows[0];
	}
	
	
	/* заполняем шаблон */
	
	$r = str_replace('%%READY_STATE%%', state_to_string($state), $r);
	
	if ($expanded_state['DISCARDED']) {
		$r = str_replace('%%READY_TIME%%',  OracleTrimTimestampToDateTime($values['u_disc_time']), $r);
	}
	else if ($expanded_state['CONTROLLED']) {
		$r = str_replace('%%READY_TIME%%',  OracleTrimTimestampToDateTime($values['u_ctrl_time']), $r);
	}
	else {
		$r = str_replace('%%READY_TIME%%', '', $r);
	}
	
	$r = str_replace('%%ROW_CSS_CLASS%%', expanded_state_to_css_class($expanded_state), $r);
	
	$r = str_replace('%%UNIT_DETAIL_LINK%%', 	"unit.php?id=" . $values['u_id'], $r);	
	$r = str_replace('%%SERIAL%%', 				$values['u_serial'], 					$r);
	
	$r = str_replace('%%PLACED_STATE%%', 	expanded_state_to_string($expanded_state, 'PLACED'), $r);
	$r = str_replace('%%ASMY_STATE%%', 		expanded_state_to_string($expanded_state, 'ASSEMBLED'), $r);
	$r = str_replace('%%DISC_STATE%%',		expanded_state_to_string($expanded_state, 'DISCARDED'), $r);
	$r = str_replace('%%CTL_STATE%%', 		expanded_state_to_string($expanded_state, 'CONTROLLED'), $r);
	
	if ($expanded_state['PLACED']) {
		$r = str_replace('%%PLACED_TIME%%', OracleTrimTimestampToDateTime($values['u_ord_time']), $r);	
		$r = str_replace('%%PLACED_EMPL_LINK%%', $employee_link . $emp_placed_data['emp_id'], $r);
		$r = str_replace('%%PLACED_EMPL_NAME%%', 
							$emp_placed_data['emp_name'] . ' ' . $emp_placed_data['emp_surname'], $r);
	}
	else {
		$r = str_replace('%%PLACED_TIME%%', '', $r);
		$r = str_replace('%%PLACED_EMPL_LINK%%', '', $r);
		$r = str_replace('%%PLACED_EMPL_NAME%%', '', $r);
	}
	
	if ($expanded_state['CONTROLLED']) {
		$r = str_replace('%%CTL_TIME%%', OracleTrimTimestampToDateTime($values['u_ctrl_time']), $r);	
		$r = str_replace('%%CTL_EMPL_LINK%%', $employee_link . $emp_ctl_data['emp_id'], $r);
		$r = str_replace('%%CTL_EMPL_NAME%%', 
							$emp_ctl_data['emp_name'] . ' ' . $emp_ctl_data['emp_surname'], $r);
	}
	else {
		$r = str_replace('%%CTL_TIME%%', '', $r);
		$r = str_replace('%%CTL_EMPL_LINK%%', '', $r);
		$r = str_replace('%%CTL_EMPL_NAME%%', '', $r);
	}
	
	if ($expanded_state['ASSEMBLED']) {
		$r = str_replace('%%ASMY_TIME%%', OracleTrimTimestampToDateTime($values['u_asm_time']), $r);	
		$r = str_replace('%%ASMY_EMPL_LINK%%', $employee_link . $emp_asmy_data['emp_id'], $r);
		$r = str_replace('%%ASMY_EMPL_NAME%%', 
							$emp_asmy_data['emp_name'] . ' ' . $emp_asmy_data['emp_surname'], $r);
	}
	else {
		$r = str_replace('%%ASMY_TIME%%', '', $r);
		$r = str_replace('%%ASMY_EMPL_LINK%%', '', $r);
		$r = str_replace('%%ASMY_EMPL_NAME%%', '', $r);
	}
	
	if ($expanded_state['DISCARDED']) {
		$r = str_replace('%%DISC_TIME%%', OracleTrimTimestampToDateTime($values['u_disc_time']), $r);	
		$r = str_replace('%%DISC_EMPL_LINK%%', $employee_link . $emp_disc_data['emp_id'], $r);
		$r = str_replace('%%DISC_EMPL_NAME%%', 
							$emp_disc_data['emp_name'] . ' ' . $emp_disc_data['emp_surname'], $r);
	}
	else {
		$r = str_replace('%%DISC_TIME%%', '', $r);
		$r = str_replace('%%DISC_EMPL_LINK%%', '', $r);
		$r = str_replace('%%DISC_EMPL_NAME%%', '', $r);
	}
	return $r;
}

function echo_order_list()
{
	$my_role = $_SESSION[SESSIONKEY_EMPLOYEE_ROLE];
	$my_id = $_SESSION[SESSIONKEY_EMPLOYEE_ID];
	
	$what_units = AMSUnitGetStateForRole($my_role);
	
	$query = QUERY_GET_UNITS;
	
	$html_table = file_get_contents("../html/unit_overview_table.html");
	
	$order_inp_string = "<input type = submit value = 'Создать заказ' /> <input type = hidden name = 'new_order'>";
	
	$order_filter_string = "<a href = 'unit.php?only_active'> Только активные изделия </a> &nbsp; <a href = 'unit.php'> Все изделия </a>";
	
	if (AMSEmployeeHasPermission(AMS_PERM_UNIT_PLACE_ORDER))
		$html_table = str_replace('%%PLACE_ORDER%%', $order_inp_string, $html_table);
	else 
		$html_table = str_replace('%%PLACE_ORDER%%', '', $html_table);
	
	$html_table = str_replace("%%FILTER_ORDERS%%", $order_filter_string, $html_table);
	
	if (strval($what_units) == 'NO') {
		$html_table = str_replace('%%UNIT_TABLE_ROWS%%', MSG_NO_DATA_TO_SHOW, $html_table);
		echo $html_table;
		return;
	}
	
	if (strval($what_units) != 'ALL') {
		$query .= ' ';
		if ($my_role == AMS_ROLE_ASMY_WRK) {
			$query .= QueryStringReplace(
									QUERY_SGMT_WHERE_VALUE, 
									array('param', 'value'), 
									array('u_asmy_work_id', $my_id));
		}
		if ($my_role == AMS_ROLE_CTL) {
			$query .= QueryStringReplace(
									QUERY_SGMT_WHERE_VALUE, 
									array('param', 'value'), 
									array('u_asmy_cont_id', $my_id));
		}
	}
	
	if (isset($_GET['only_active'])) {
		if (strpos($query, 'WHERE')) {
			$query .= 'AND (u_state = 0 OR u_state = 3) ';
		}
		else {
			$query .= ' WHERE u_state = 0 OR u_state = 3 ';
		}
	}
	
	$query .= ' ';
	$query .= QueryStringReplace(QUERY_SGMT_ORDER_BY_DESC, 'sort', 'u_state');
		
	$rows = array();
	$numrows = OracleQuickReadQuery($query, 
								array("u_serial", "u_id", "u_asmy_mng_id", "u_asmy_work_id", 
										"u_asmy_cont_id", "u_state", "u_ord_time",
										"u_asmy_disc_id", "u_asm_time", "u_ctrl_time",
										"u_disc_time"),
								$rows);

	if (0 == $numrows) {
		$html_table = str_replace('%%UNIT_TABLE_ROWS%%', MSG_NO_DATA_TO_SHOW, $html_table);
		echo $html_table;
		return;
	}
	
	$row_template = file_get_contents("../html/unit_overview_table_row.html");
	$html_rows = '';
	
	
	for ($i = 0; $i < $numrows; $i += 1) {
		$html_rows .= build_list_row($row_template, $rows, $i);
	}

	$html_table = str_replace('%%UNIT_TABLE_ROWS%%', $html_rows, $html_table);

	echo $html_table;
}

function get_employee_dropdown($emp_role, $select_id, &$result)
{
	$str = '';
	$rows = array();
	$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_EMPL_BY_ROLE, "emp_role", $emp_role),
						array("emp_id", "emp_name", "emp_surname"),
						$rows);
						
	if (0 == $numrows) {
		if (AMS_ROLE_ASMY_WRK == $emp_role)
			$str .= MSG_NO_ASMY_WORKERS;
		if (AMS_ROLE_CTL == $emp_role)
			$str .= MSG_NO_CTL_WORKERS;
		$result = FALSE;
		return $str;
	}
	
	$str .= '<select name ="'; 
	
	if (AMS_ROLE_ASMY_WRK == $emp_role)
		$str .= 'asmy_wrk';
	if (AMS_ROLE_CTL == $emp_role)
		$str .= 'ctl_wrk';
		
	$str .= '">';
	for ($i = 0; $i < $numrows; $i += 1) {
		$emp_id = $rows[$i]['emp_id'];
		$str .= '<option value = ' . $emp_id;
		if ($select_id == $emp_id) {
			$str .= ' selected';
		}
		$str .= '>';
		$str .= $rows[$i]['emp_name'] . ' ' . $rows[$i]['emp_surname'];
		$str .= '</option>';
	}
	$str .= '</select>';	
	$result = TRUE;
	return $str;
}

function get_button_set_workers() 
{
	$but = '<input type = submit name = "set_workers" value = "Назначить ответственных"/> <br />';
	return $but;
}

function get_button_assemble()
{
	$but = '<input type = submit name = "assemble" value = "Отметка о окончании монтажа"/> <br />';
	return $but;
}

function get_button_control_ok()
{
	$but = '<input type = submit name = "control_ok" value = "Отметка о успешном прохождении контроля"/> <br />';
	return $but;
}

function get_button_control_fail()
{
	$but = '<input type = submit name = "control_fail" value = "Отметка о браке"/> <br />';
	return $but;
}

function get_my_role()
{
	return 'Моя роль: ' . AMSEmployeeRoleToString($_SESSION[SESSIONKEY_EMPLOYEE_ROLE]);
}

function get_man_log_row($rows, $row_id, $html_template)
{
	$html_template 
		= str_replace('%%TIMESTAMP%%', 
						OracleTrimTimestampToDateTime($rows[$row_id]["ml_time"]), 
						$html_template);
	
	$html_template = str_replace('%%NUMBER%%', $row_id + 1, $html_template);
	$html_template = str_replace('%%TEXT%%', $rows[$row_id]["ml_text"], $html_template);
	return $html_template;
}

function get_man_log($u_id)
{
	$html_table = file_get_contents("../html/manlog_table.html");
	$html_table_row = file_get_contents("../html/manlog_table_row.html");

	$rows = array();
	$numrows = OracleQuickReadQuery(
					QueryStringReplace(QUERY_SELECT_FROM_LOG, "unit_id", $u_id),
					array("ml_text", "ml_time"),
					$rows);
					
	if (0 == $numrows)
		die('fucking impossible');
	
	$html_rows = '';
	for ($i = 0; $i < $numrows; $i += 1) {
		$html_rows .= get_man_log_row($rows, $i, strval($html_table_row));
	}
	
	$html_table = str_replace('%%MANLOG_ROWS%%', $html_rows, $html_table);
	
	return $html_table;
}

function echo_order_detail($u_id)
{
	$abilities = AMSUnitMyAbilitiesWithUnit($u_id);

	$editor_html = file_get_contents("../html/unit_editor_table.html");
	$dropdown_result = TRUE;

	$rows = array();
	
	$numrows = OracleQuickReadQuery(
							QueryStringReplace(QUERY_GET_UNIT_DATA, 'u_id', $u_id),
							array("u_serial", "u_id", "u_asmy_mng_id", "u_asmy_work_id", 
										"u_asmy_cont_id", "u_state", "u_ord_time",
										"u_asmy_disc_id", "u_asm_time", "u_ctrl_time",
										"u_disc_time"),
							$rows);
	
	$unit = $rows[0];
	$expanded_state = unit_expand_state($unit);
	
	$editor_html = str_replace('%%SERIAL%%', $unit['u_serial'], $editor_html);
	
 	$editor_html = str_replace('%%UNITID%%', $u_id, $editor_html);
	
	$editor_empl_link = '<a href = "employee.php?employee_id=%%ID%%">%%NAME%%</a>';
	
	
	if (in_array('CAN_CHANGE_ASMY_WORKER', $abilities)) {
		$editor_html 
			= str_replace('%%ASMY_DROPDOWN%%', 
						get_employee_dropdown(AMS_ROLE_ASMY_WRK, $unit["u_asmy_work_id"], $dropdown_result), 
						$editor_html);
	}
	else if ($unit["u_asmy_work_id"]) {
	
		$empl_html = str_replace('%%ID%%', strval($u_id), $editor_empl_link);
		$empl_html = str_replace('%%NAME%%', AMSEmployeeID2Name($unit["u_asmy_work_id"]), $empl_html);
		$editor_html 
			= str_replace('%%ASMY_DROPDOWN%%', $empl_html, $editor_html);
	} else
		$editor_html = str_replace('%%ASMY_DROPDOWN%%', MSG_NOT_SET, $editor_html);
	
	if (in_array('CAN_CHANGE_CTL_WORKER', $abilities)) {
		$editor_html 
			= str_replace('%%CTL_DROPDOWN%%', 
						get_employee_dropdown(AMS_ROLE_CTL, $unit["u_asmy_cont_id"], $dropdown_result), 
						$editor_html);
	}
	else if ($unit["u_asmy_cont_id"]) {
		$empl_html = str_replace('%%ID%%', strval($u_id), $editor_empl_link);
		$empl_html = str_replace('%%NAME%%', AMSEmployeeID2Name($unit["u_asmy_cont_id"]), $empl_html);
		$editor_html 
			= str_replace('%%CTL_DROPDOWN%%', $empl_html, $editor_html);
	} else
		$editor_html = str_replace('%%CTL_DROPDOWN%%', MSG_NOT_SET, $editor_html);
	
	$editor_html = str_replace('%%MY_ROLE%%', get_my_role(), $editor_html);
	
	$editor_html = str_replace('%%READY_STATE%%', state_to_string($unit['u_state']), $editor_html);
	
	if ($expanded_state['DISCARDED']) {
		$editor_html 
			= str_replace('%%READY_TIME%%',  OracleTrimTimestampToDateTime($unit['u_disc_time']), $editor_html);
	}
	else if ($expanded_state['CONTROLLED']) {
		$editor_html 
			= str_replace('%%READY_TIME%%',  OracleTrimTimestampToDateTime($unit['u_ctrl_time']), $editor_html);
	}
	else {
		$editor_html = str_replace('%%READY_TIME%%', '', $editor_html);
	}
	
	/* кнопки */
	
	$buttons = '';
	
	if (in_array('CAN_CHANGE_CTL_WORKER', $abilities) or in_array('CAN_CHANGE_ASMY_WORKER', $abilities)) {
		$buttons .= get_button_set_workers();
	}
	
	if (in_array('CAN_ASSEMBLE', $abilities)) {
		$buttons .= get_button_assemble();
	}
	
	if (in_array('CAN_CONTROL', $abilities)) {
		$buttons .= get_button_control_ok();
		$buttons .= get_button_control_fail();
	}
	
	$editor_html = str_replace('%%STATE_MODIFY_INPUTS%%', $buttons, $editor_html);
	
	/* ------ */
	
	$editor_html = str_replace('%%MAN_LOG_TABLE%%', get_man_log($u_id), $editor_html);
	
	echo $editor_html;
}

function UnitHandlePOST()
{
	if (!isset($_POST)) {
		return;
	}
	
	if (isset($_POST['new_order'])) {
		$unit_id = AMSUnitPlaceOrder();
		return;
	}
	
	if (isset($_POST['unit_id'])) {	
		/* хак, чтобы открывался сразу нужный detail */
		$unit_id = $_GET["id"] = $_POST['unit_id'];

		if (isset($_POST['assemble'])) {	
			AMSUnitAssembleOrder($unit_id, TRUE);
			return;
		}	
		
		if (isset($_POST['control_ok'])) {	
			AMSUnitControlOrder($unit_id, TRUE);
			return;
		}	
		
		if (isset($_POST['control_fail'])) {	
			AMSUnitControlOrder($unit_id, FALSE);
			return;
		}	
		
		if (isset($_POST['set_workers'])) {
				if (!isset($_POST['unit_id']))
				return;
			
			if (isset($_POST['asmy_wrk'])) {
				$worker_id = $_POST['asmy_wrk'];
				AMSUnitUpdateAsmyWorker($unit_id, $worker_id);
			}
			
			if (isset($_POST['ctl_wrk'])) {
				$worker_id = $_POST['ctl_wrk'];
				AMSUnitUpdateCtrlWorker($unit_id, $worker_id);
			}	
		}
	}
}

function UnitHandleGET()
{
	if (!isset($_GET)) {
		return;
	}
	
	if (isset($_GET['id'])) {
		echo_order_detail($_GET['id']);
	}
	echo '</hr>';
	echo_order_list();
}

?>
