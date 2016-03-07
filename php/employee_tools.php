<?php
require_once('common/ams_common_defines.php');
require_once('common/ora_session.php');
require_once('www_tools.php');

define("SESSIONKEY_EMPLOYEE_ID", "employee_id");
define("SESSIONKEY_EMPLOYEE_ROLE", "employee_role");
define("SESSIONKEY_EMPLOYEE_NAME", "employee_name");
define("SESSIONKEY_EMPLOYEE_PERMISSIONS", "employee_permissions");

function AMSPermissionsByRole($role)
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

function AMSEmployeeRoleToString($role) 
{
	switch ($role) {
		case AMS_ROLE_MGR:
			return 'менеджер';
		case AMS_ROLE_ASMY_WRK:
			return 'монтажник';
		case AMS_ROLE_CTL:
			return 'контролёр';
		case AMS_ROLE_HR:
			return 'кадровик';
		case AMS_ROLE_WM:
			return 'складской специалист';
		case AMS_ROLE_ADM:
			return 'IT';
		case AMS_ROLE_FIRED:
		default:
			return 'прошедший в жопу';
	}
}


function AMSEmployeeGetPermissions($id) 
{
	return AMSPermissionsByRole(AMSEmployeeGetRole($id));
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
	$query = QueryStringReplace(QUERY_GET_USER_ROLE, 'id', $id);
	$rows = array();
	$numrows = OracleQuickQuery($query, 'emp_role', $rows);
	
	if (0 == $numrows)
		die('employee ' . $id . ' does not exist');
	
	$role = intval($rows[0]);
	return $role;
}

function AMSEmployeeSetupSession($id)
{
	$query = QueryStringReplace(QUERY_GET_EMPLOYEE_SESSIONDATA, 'emp_id', $id);
	$rows = array();
	$numrows = OracleQuickQuery($query, array('emp_role', 'emp_name', 'emp_surname'), $rows);
	if (0 == $numrows)
		die('employee ' . $id . ' does not exist');
		
	$empldata = $rows[0];
	
	$permissions = AMSPermissionsByRole($empldata["emp_role"]);
	$name = $empldata["emp_name"] . ' ' . $empldata["emp_surname"];
	
	session_start();
	$_SESSION[SESSIONKEY_EMPLOYEE_ID] = $id;
	$_SESSION[SESSIONKEY_EMPLOYEE_PERMISSIONS] = $permissions;
	$_SESSION[SESSIONKEY_EMPLOYEE_NAME] = $name;
	$_SESSION[SESSIONKEY_EMPLOYEE_ROLE] = $empldata["emp_role"];
	session_commit();
}

function AMSEmployeeDestroySession()
{
	ToolsEndSession();
}

function AMSEmployeeLogin($login, $password)
{
	$query = QueryStringReplace(QUERY_GET_USER_ID_PASSWORD, "emp_login", $login);
	$rows = array();
	$numrows = OracleQuickQuery($query, array("emp_password", "emp_id"), $rows);
	
	if (0 == $numrows)
		return 'NO_SUCH_LOGIN';
	
	if ($password == $rows[0]["emp_password"])
		return $rows[0]["emp_id"];
	else
		return 'WRONG_PASSWORD';
}

function AMSEmployeeRedirectAuth()
{
	session_start();
	if (!ToolsSessionExists()) {
		ToolsEndSession();
		ToolsRedirectClient("auth.php");
	}
	if (!isset($_SESSION[SESSIONKEY_EMPLOYEE_ID])) {
		ToolsEndSession();
		ToolsRedirectClient("auth.php");
	}
}

?>
