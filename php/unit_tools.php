<?php
require_once('common/ora_session.php');
require_once('echo_tools.php');
require_once('employee_tools.php');
require_once('barcode39.php');

function AMSUnitGetStateForRole($role) {
	if (($role == AMS_ROLE_BOSS) or ($role == AMS_ROLE_MGR)) {
		return 'ALL';
	}
	if ($role == AMS_ROLE_ASMY_WRK) {
		return AMS_STATE_NEW;
	}
	
	if ($role == AMS_ROLE_CTL) {
		return AMS_STATE_ASMY_COMPLETE;
	}
	
	return 'NO';
}

function AMSUnitCanChangeAsmyWorker($unit_data) 
{	
	if (($unit_data['u_state'] == AMS_STATE_NEW) 
								and (AMSEmployeeHasPermission(AMS_PERM_UNIT_EDIT_ORDER)))
	{
		return TRUE;
	}
	return FALSE;
}

function AMSUnitCanChangeCtlWorker($unit_data) 
{
	if ((($unit_data['u_state'] == AMS_STATE_ASMY_COMPLETE) 
				or ($unit_data['u_state'] == AMS_STATE_NEW)) 
				and AMSEmployeeHasPermission(AMS_PERM_UNIT_EDIT_ORDER)) 
	{
		return TRUE;
	}
	
	return FALSE;
}

function AMSUnitMyAbilitiesWithUnit($unit_id)
{
	$my_id = $_SESSION[SESSIONKEY_EMPLOYEE_ID];
	
	$ret = array();
	
	$rows = array();
	$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_GET_UNIT_DATA, "u_id", $unit_id),
						array("u_serial", "u_id", "u_asmy_mng_id", "u_asmy_work_id", 
										"u_asmy_cont_id", "u_state", "u_ord_time",
										"u_asmy_disc_id", "u_asm_time", "u_ctrl_time",
										"u_disc_time"),
						$rows);
	if (0 == $numrows)
		return array();
	
	$unit_data = $rows[0];
	$state = $unit_data['u_state'];

	if (($state == AMS_STATE_FINISH_OK) or ($state == AMS_STATE_FINISH_FAILURE)) 
	{
		return array();
	}

	if ($state == AMS_STATE_NEW) {
		$asmy_unit_id = $unit_data['u_asmy_work_id'];
		if ($asmy_unit_id) {
			if (($asmy_unit_id == $my_id) or (AMSEmployeeHasPermission(AMS_PERM_UNIT_EDIT_ORDER))) 
			{
				$ret[] = strval('CAN_ASSEMBLE');
			}
		}
	}

	if ($state == AMS_STATE_ASMY_COMPLETE) {
		$ctl_unit_id = $unit_data['u_asmy_cont_id'];
		if ($ctl_unit_id) {
			if (($ctl_unit_id == $my_id) or (AMSEmployeeHasPermission(AMS_PERM_UNIT_EDIT_ORDER))) 
			{
				$ret[] = strval('CAN_CONTROL');
			}
		}
	}
	
	if (AMSUnitCanChangeAsmyWorker($unit_data)) 
		$ret[] = strval('CAN_CHANGE_ASMY_WORKER');
		
	if (AMSUnitCanChangeCtlWorker($unit_data))
		$ret[] = strval('CAN_CHANGE_CTL_WORKER');
	
	if (AMSEmployeeHasPermission(AMS_PERM_UNIT_DISCARD))
		$ret[] = strval('CAN_DISCARD');
	
	return $ret;
}

function AMSUnitPlaceOrder()
{
	$my_role = $_SESSION[SESSIONKEY_EMPLOYEE_ROLE];
	$my_id = $_SESSION[SESSIONKEY_EMPLOYEE_ID];
	
	if (AMSEmployeeHasPermission(AMS_PERM_UNIT_PLACE_ORDER)) {
		$e = OracleQuickWriteQuery(QueryStringReplace(QUERY_INSERT_ORDER, "mng_id", $my_id));
		
		if ($e != 'SUCCESS')
			die('AMSUnitPlaceOrder: bad internal error: ' . var_dump($e));
		
		$rows = array();
		$numrows = OracleQuickReadQuery(QUERY_GET_LAST_PLACED_UNIT_ID, array("u_id"), $rows);
		$u_id = $rows[0]['u_id'];
		
		AMSUnitLog($u_id, manlog_link_employee(MSG_MANLOG_ORDER_PLACED, $my_id));
		AMSEmployeeJournalWrite($my_id, empj_link_unit(MSG_JOURNAL_PLACED_ORDER, $u_id));
		
		echo MSG_ORDER_CREATED;
		return $u_id;
	}
	else
		die('AMSUnitPlaceOrder: permission denied');
}

function manlog_link_employee($text, $employee_id) 
{
	$text = str_replace("%%LINK%%", "employee.php?employee_id=" . $employee_id, $text);
	$text = str_replace("%%NAME%%", AMSEmployeeID2Name($employee_id), $text);
	return $text;
}

function empj_link_unit($text, $unit_id) 
{
	$text = str_replace("%%LINK%%", "unit.php?id=" . $unit_id, $text);
	$text = str_replace("%%ORDER%%", AMSUnitID2Serial($unit_id), $text);
	return $text;
}

function AMSUnitID2Serial($id)
{
	$rows = array();
	$numrows = OracleQuickReadQuery(
							QueryStringReplace(QUERY_UNIT_ID_TO_SERIAL, 'u_id', $id), 
							array("u_serial"), 
							$rows);
	if (!$numrows) {
		die('AMSUnitID2Serial: nonexisting order');
	}

	$serial = $rows[0]["u_serial"];
	return $serial;
}

function AMSUnitUpdateAsmyWorker($unit_id, $worker_id)
{
	$rows = array();
	$numrows = OracleQuickReadQuery(
				QueryStringReplace(QUERY_GET_UNIT_DATA, "u_id", $unit_id),
				array("u_asmy_work_id", "u_state"),
				$rows);
	
	if ($rows[0]['u_state'] >= AMS_STATE_ASMY_COMPLETE)
		return;

	if ($rows[0]['u_asmy_work_id'] == $worker_id)
		return;

	$query = QueryStringReplace(QUERY_UPDATE_ASMY_WORKER, 
											array("u_id", "u_asmy_work_id"), 
											array($unit_id, $worker_id));
	$e = OracleQuickWriteQuery($query);
										
	if ($e != 'SUCCESS')
		die('AMSUnitUpdateAsmyWorker: bad internal error');
		
	AMSUnitLog($unit_id, manlog_link_employee(MSG_MANLOG_ORDER_ASMY_SET, $worker_id));
	AMSEmployeeJournalWrite($worker_id, empj_link_unit(MSG_JOURNAL_ASMY_SET, $unit_id));
}

function AMSUnitUpdateCtrlWorker($unit_id, $worker_id)
{
	$rows = array();
	$numrows = OracleQuickReadQuery(
				QueryStringReplace(QUERY_GET_UNIT_DATA, "u_id", $unit_id),
				array("u_asmy_cont_id", "u_state"),
				$rows);
	
	if ($rows[0]['u_state'] >= AMS_STATE_FINISH_OK)
		return;

	if ($rows[0]['u_asmy_cont_id'] == $worker_id)
		return;
		
	$e = OracleQuickWriteQuery(
					QueryStringReplace(QUERY_UPDATE_CONTROLLER, 
											array("u_id", "u_asmy_cont_id"), 
											array($unit_id, $worker_id)));
										
	if ($e != 'SUCCESS')
		die('AMSUnitUpdateCtrlWorker: bad internal error');
		
	AMSUnitLog($unit_id, manlog_link_employee(MSG_MANLOG_ORDER_CTRL_SET, $worker_id));
	AMSEmployeeJournalWrite($worker_id, empj_link_unit(MSG_JOURNAL_CTRL_SET, $unit_id));
}

function AMSUnitAssembleOrder($u_id)
{
	$rows = array();
	$numrows = OracleQuickReadQuery(
				QueryStringReplace(QUERY_GET_UNIT_DATA, "u_id", $u_id),
				array("u_asmy_work_id", "u_state"),
				$rows);
	
	if ($rows[0]['u_state'] >= AMS_STATE_ASMY_COMPLETE)
		return;
	
	$my_id = $_SESSION[SESSIONKEY_EMPLOYEE_ID];
	
	if ($rows[0]['u_asmy_work_id'] != $my_id) {
		AMSUnitUpdateAsmyWorker($u_id, $my_id);
	}
	
	$query = QueryStringReplace(QUERY_ASSEMBLE_UNIT, "u_id", $u_id);

	$e = OracleQuickWriteQuery($query);
	if ($e != 'SUCCESS')
		die('AMSUnitAssembleOrder: bad internal error');
	
	AMSUnitLog($u_id, manlog_link_employee(MSG_MANLOG_ORDER_ASSEMBLED, $my_id));
	AMSEmployeeJournalWrite($my_id, empj_link_unit(MSG_JOURNAL_ASSEMBLED_ORDER, $u_id));
	
	echo MSG_ORDER_ASSEMBLED;	
}

function AMSUnitControlOrder($u_id, $is_good)
{	
	$rows = array();
	$numrows = OracleQuickReadQuery(
				QueryStringReplace(QUERY_GET_UNIT_DATA, "u_id", $u_id),
				array("u_asmy_cont_id", "u_state"),
				$rows);

	if ($rows[0]['u_state'] >= AMS_STATE_FINISH_OK)
		return;
	
	$my_id = $_SESSION[SESSIONKEY_EMPLOYEE_ID];
	
	if ($rows[0]["u_asmy_cont_id"] != $my_id) {
		AMSUnitUpdateCtrlWorker($u_id, $my_id);
	}
	
	AMSUnitLog($u_id, manlog_link_employee(MSG_MANLOG_ORDER_CONTROLLED, $my_id));
	AMSEmployeeJournalWrite($my_id, empj_link_unit(MSG_JOURNAL_CONTROLLED_ORDER, $u_id));
	
	if ($is_good) {
		$e = OracleQuickWriteQuery(QueryStringReplace(QUERY_CONTROL_UNIT_OK, "u_id", $u_id));
		if ($e != 'SUCCESS')
			die('AMSUnitControlOrder: bad internal error');
		
		AMSUnitLog($u_id, manlog_link_employee(MSG_MANLOG_ORDER_RELEASED, $my_id));
		AMSEmployeeJournalWrite($my_id, empj_link_unit(MSG_JOURNAL_RELEASED_ORDER, $u_id));
	}
	else {
		$e = OracleQuickWriteQuery(QueryStringReplace(QUERY_CONTROL_UNIT_DISCARD, "u_id", $u_id));
		if ($e != 'SUCCESS')
			die('AMSUnitControlOrder: bad internal error');
			
		AMSUnitLog($u_id, manlog_link_employee(MSG_MANLOG_ORDER_DISCARDED, $my_id));
		AMSEmployeeJournalWrite($my_id, empj_link_unit(MSG_JOURNAL_DISCARDED_ORDER, $u_id));
	}
	
	echo MSG_ORDER_CONTROLLED;	
}

function AMSUnitMakePDFReport($id)
{
	$pdf=new PDF_Code39();
	$pdf->AddPage();
	$pdf->Code39(80,40,'CODE 39',1,10);
	$pdf->Output();
}

function AMSUnitLog($id, $text)
{
	$query = QueryStringReplace(QUERY_INSERT_INTO_LOG, array("unit_id", "text"), array($id, $text));
	$r = OracleQuickWriteQuery($query);
	if ($r != 'SUCCESS')
		die('AMSUnitLog: query failure: ' . $query);
}

?>
