<?php
require_once('common/ams_common_defines.php');
require_once('common/ora_session.php');

function permissions_by_role($role)
{
	switch ($role) {
		case AMS_ROLE_ADM: {
			return array(
						AMS_ADMPERM_ADM
						)
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
						)
		default:
			return FALSE;
		}
	}
}



function AMSEmployeeGetPermissions($id) 
{
	$role = OracleQuickQuery(QueryStringReplace(QUERY_GET_USER_ROLE, 'id', $id), 'emp_role');
	$role = int($role);
	return permission_by_role($role);
}

function AMSEmployeeHasPermission($id, $permission)
{


}


function AMSEmployeeGetRole($id)
{

}

function AMSEmployeeSetupSession($id)
{

}



?>
