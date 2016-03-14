<?php
require_once('common/ora_session.php');
require_once('echo_tools.php');
require_once('employee_tools.php');

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

function AMSUnitPlaceOrder()
{
	
}

function AMSUnitUpdateResponsible($id, $resp_type_array, $resp_type_updates)
{

}

function AMSUnitAssembleOrder($id)
{

}

function AMSUnitControlOrder($id)
{

}

function AMSUnitCompleteOrder($id)
{

}

function AMSUnitMakePDFReport($id)
{

}

?>
