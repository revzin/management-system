<?php
require('common/ams_common_defines.php');
require('common/ora_session.php');

function permission_by_role($role)
{
	switch ($role) {
		case AMS_ROLE_MGR: {
			return array(
						AMS_EMPPERM_VIEW_EMPLOYEES,
						AMS_EMPPERM_EDIT_EMPLOYEES,
						AMS_EMPPERM_HIREFIRE
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
						AMS_EMPPERM_VIEW_GROUP
						)
		}
	}
}



function AMSEmployeeGetPermissions($id) 
{
	OracleQuickQuery(QueryStringReplace(QUERY_GET_USER_ROLE, array($id));
	
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


$dbc = OracleConnect();


?>
