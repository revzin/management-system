<?php
require_once('common/ams_common_defines.php');
require_once('common/ora_session.php');
require_once('www_tools.php');

define("SESSIONKEY_EMPLOYEE_ID", "employee_id");
define("SESSIONKEY_EMPLOYEE_ROLE", "employee_role");
define("SESSIONKEY_EMPLOYEE_NAME", "employee_name");
define("SESSIONKEY_EMPLOYEE_PERMISSIONS", "employee_permissions");

function AMSEmployeePermissionsByRole($role)
{
	switch ($role) {
		case AMS_ROLE_FIRED: {
			return array();
		}
		/*case AMS_ROLE_ADM: {
			return array(
						AMS_ADMPERM_ADM
						);
		}*/
		case AMS_ROLE_BOSS: {
			return array(
						AMS_PERM_EMP_VIEW_EMPLOYEES,
						AMS_PERM_EMP_EDIT_EMPLOYEES,
						AMS_PERM_EMP_HIREFIRE,
						AMS_PERM_EMP_PROMOTE_TO_BOSS,
						AMS_PERM_EMP_CAN_EDIT_BOSSES,
						
						//AMS_PERM_WRHS_VIEW,
						//AMS_PERM_WRHS_EDIT,
						
						AMS_PERM_UNIT_PLACE_ORDER,
						AMS_PERM_UNIT_EDIT_ORDER,
						AMS_PERM_UNIT_VIEW_ALL,
						AMS_PERM_UNIT_ASSEMBLE
						);
		}
		case AMS_ROLE_MGR: {
			return array(
						AMS_PERM_EMP_VIEW_EMPLOYEES,
						AMS_PERM_EMP_EDIT_EMPLOYEES,
						
						AMS_PERM_UNIT_PLACE_ORDER,
						AMS_PERM_UNIT_EDIT_ORDER,
						AMS_PERM_UNIT_VIEW_ALL,
						AMS_PERM_UNIT_ASSEMBLE
						);
		}
		case AMS_ROLE_ASMY_WRK: {
			return array(
						AMS_PERM_EMP_VIEW_GROUP,
						//AMS_PERM_WRHS_VIEW,
						
						AMS_PERM_UNIT_VIEW_RELEVANT,
						AMS_PERM_UNIT_ASSEMBLE
						);
		}
		default:
			return FALSE;
		
	}
}

function AMSEmployeeRoleToString($role) 
{
	switch ($role) {
		case AMS_ROLE_BOSS:
			return 'босс';
		case AMS_ROLE_ASMY_WRK:
			return 'монтажник';
		case AMS_ROLE_CTL:
			return 'контролёр';
		case AMS_ROLE_MGR:
			return 'менеджер';
		/* не задействованы */
		//case AMS_ROLE_WM:
		//	return 'складской специалист';
		//case AMS_ROLE_ADM:
		//	return 'IT';
		case AMS_ROLE_FIRED:
		default:
			return 'прошедший в жопу';
	}
}

function AMSEmployeeGetPermissions($id) 
{
	return AMSEmployeePermissionsByRole(AMSEmployeeGetRole($id));
}

function AMSEmployeeHasPermission($permission, $id = 'CURRENT')
{
	if ($id == 'CURRENT') {
		$perms = $_SESSION[SESSIONKEY_EMPLOYEE_PERMISSIONS];
	} 
	else {
		$perms = AMSEmployeeGetPermissions($id);
	}
	foreach ($perms as $p) {
		if ($p == $permission) {
			return TRUE;
		}
	}
	return FALSE;
}


function AMSEmployeeGetRole($id)
{
	$query = QueryStringReplace(QUERY_GET_USER_ROLE, 'id', $id);
	$rows = array();
	$numrows = OracleQuickReadQuery($query, 'emp_role', $rows);
	
	if (0 == $numrows)
		return AMS_ROLE_FIRED;
	
	$role = intval($rows[0]);
	return $role;
}

function AMSEmployeeSetupSession($id)
{
	$query = QueryStringReplace(QUERY_GET_EMPLOYEE_SESSIONDATA, 'emp_id', $id);
	$rows = array();
	$numrows = OracleQuickReadQuery($query, array('emp_role', 'emp_name', 'emp_surname'), $rows);
	if (0 == $numrows)
		die('employee ' . $id . ' does not exist');
		
	$empldata = $rows[0];
	
	$permissions = AMSEmployeePermissionsByRole($empldata["emp_role"]);
	$name = $empldata["emp_name"] . ' ' . $empldata["emp_surname"];
	if (!ToolsSessionExists())
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

function AMSEmployeeID2Name($id)
{
	$query = QueryStringReplace(QUERY_GET_EMPLOYEE_SESSIONDATA, 'emp_id', $id);
	$rows = array();
	$numrows = OracleQuickReadQuery($query, array('emp_role', 'emp_name', 'emp_surname'), $rows);
	if (0 == $numrows)
		die('employee ' . $id . ' does not exist');
		
	$empldata = $rows[0];
	
	return $empldata["emp_name"] . ' ' . $empldata["emp_surname"];
}

function AMSEmployeeLogin($login, $password)
{
	$query = QueryStringReplace(QUERY_GET_USER_ID_PASSWORD, "emp_login", $login);
	$rows = array();
	$numrows = OracleQuickReadQuery($query, array("emp_password", "emp_id"), $rows);
	
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

function AMSEmployeeJournalAccessible($my_role, $his_role, $my_id, $his_id)
{
	$my_role = intval($my_role);
	$his_role = intval($his_role);
	$my_id = intval($my_id);
	$his_id = intval($his_id);
	
	if (AMSEmployeeHasPermission(AMS_PERM_EMP_VIEW_EMPLOYEES)) {
		if (($his_role == AMS_ROLE_BOSS) and !AMSEmployeeHasPermission(AMS_PERM_EMP_CAN_EDIT_BOSSES))
			return FALSE;
		return TRUE;
	}
	
	if ($my_id == $his_id)
		return TRUE;
	
	return FALSE;
}

function AMSEmployeeAddRowToJournal($id, $text)
{
	$my_role = $_SESSION[SESSIONKEY_EMPLOYEE_ROLE];
	$my_id	= $_SESSION[SESSIONKEY_EMPLOYEE_ID];
	
	$his_id = $id;
	$his_role = AMSEmployeeGetRole($id);
	
	if (!AMSEmployeeJournalAccessible($my_role, $his_role, $my_id, $his_id))
		die("AMSEmployeeAddRowToJounral: permsission denied");
	
	$query = QueryStringReplace(QUERY_INSERT_INTO_EMP_JOURNAL, 
								array("emp_id", "author_id", "journal_emp_text"),
								array($his_id, $my_id, $text));
					
	$r = OracleQuickWriteQuery($query);				
	if ('SUCCESS' != $r) {
		die('AMSEmployeeAddRowToJounral: ORA-' . $r);
	}
}

function AMSEmployeeCountRole($role)
{
	$query = QueryStringReplace(QUERY_COUNT_ROLE, "emp_role", $role);
	$rows = array();
	$numrows = OracleQuickReadQuery($query, "count", $rows);
	return intval($rows[0]);
}
	
?>
