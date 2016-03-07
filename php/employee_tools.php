<?php
require_once('common/ams_common_defines.php');
require_once('common/ora_session.php');

function permissions_by_role($role)
{
	switch ($role) {
		case AMS_ROLE_FIRED: {
			return array();
		}
		case AMS_ROLE_ADM: {
			return array(
						AMS_ADMPERM_ADM
						);
		}
		case AMS_ROLE_MGR: {
			return array(
						AMS_EMPPERM_VIEW_EMPLOYEES,
						AMS_EMPPERM_EDIT_EMPLOYEES,
						AMS_EMPPERM_HIREFIRE,
						
						AMS_WRHSPERM_VIEW,
						AMS_WRHSPERM_EDIT
						);
		}
		case AMS_ROLE_HR: {
			return array(
						AMS_EMPPERM_VIEW_EMPLOYEES,
						AMS_EMPPERM_EDIT_EMPLOYEES
						);
		}
		case AMS_ROLE_ASMY_WRK: {
			return array(
						AMS_EMPPERM_VIEW_GROUP,
						AMS_WRHSPERM_VIEW
						);
		}
		default:
			return FALSE;
		
	}
}


function AMSEmployeeGetPermissions($id) 
{
	$query = QueryStringReplace(QUERY_GET_USER_ROLE, 'id', $id);
	$rows = array();
	$numrows = OracleQuickQuery($query, 'emp_role', $rows);
	
	if (0 == $numrows)
		die('employee ' . $id . ' does not exist');
	
	$role = intval($rows[0]);
	return permissions_by_role($role);
}

function AMSEmployeeHasPermission($id, $permission)
{
	$perms = AMSEmployeeGetPermissions($id);
	foreach ($perms as $p) {
		if ($p == $permission)
			return TRUE;
	}
	return FALSE;
}


function AMSEmployeeGetRole($id)
{

}

function AMSEmployeeSetupSession($id)
{

}



?>
