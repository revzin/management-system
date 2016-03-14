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
			return 'прошло проверку';
		else
			return 'не прошло проверку';	
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
						QueryStringReplace(QUERY_GET_EMPLOYEE_NAME, "emp_id", $values['u_asmy_disc_id']),
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
	
	$r = str_replace('%%UNIT_DETAIL_LINK%%', 	"unit.php?detail_id=" . $values['u_id'], $r);	
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
	
	$what_units = AMSUnitGetStateForRole($my_role);
	
	$query = QUERY_GET_UNITS;
	
	if (strval($what_units) == 'NO') {
		echo MSG_NO_DATA_TO_SHOW;
		return;
	}
	
	if (strval($what_units) != 'ALL') {
		$query .= ' ';
		$query .= QueryStringReplace(
								QUERY_SGMT_WHERE_VALUE, 
								array('param', 'value'), 
								array('u_state', $what_units));
	}
	
	$query .= ' ';
	$query .= QueryStringReplace(QUERY_SGMT_ORDER_BY_DESC, 'sort', 'u_state');
	
	echo $query . '<br />';
	
	$rows = array();
	$numrows = OracleQuickReadQuery($query, 
								array("u_serial", "u_id", "u_asmy_mng_id", "u_asmy_work_id", 
										"u_asmy_cont_id", "u_state", "u_ord_time",
										"u_asmy_disc_id", "u_asm_time", "u_ctrl_time",
										"u_disc_time"),
								$rows);

	if (0 == $numrows) {
		echo MSG_NO_DATA_TO_SHOW;
		return;
	}
	
	$row_template = file_get_contents("../html/unit_overview_table_row.html");
	$html_rows = '';
	
	
	for ($i = 0; $i < $numrows; $i += 1) {
		$html_rows .= build_list_row($row_template, $rows, $i);
	}

	$html_table = file_get_contents("../html/unit_overview_table.html");
	$html_table = str_replace('%%UNIT_TABLE_ROWS%%', $html_rows, $html_table);
	
	echo $html_table;
											
}

function UnitHandleGET()
{
	if (!isset($_GET)) {
		return;
	}
	
	echo_order_list();

}

?>
