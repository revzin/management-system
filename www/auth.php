<?php

require_once("../php/employee_tools.php");
require_once("../php/www_tools.php");

ToolsDropAllBrowserCache();

function is_logout_attempt() 
{		
	return isset($_GET["logout"]);
}

function is_login_attempt() 
{
	return isset($_POST["login"]) and isset($_POST["password"]);
}

$flag_bad_login = FALSE;

if (is_logout_attempt()) {
	session_start();
	AMSEmployeeDestroySession();
}

if (is_login_attempt()) {
	$login = $_POST["login"];
	$password = $_POST["password"];
	$result = AMSEmployeeLogin($login, $password);
	
	if ($result == 'NO_SUCH_LOGIN' or $result == 'WRONG_PASSWORD') {
		AMSEmployeeDestroySession();
		$flag_bad_login = TRUE;
	}
	else {
		AMSEmployeeSetupSession(intval($result));
		ToolsRedirectClient("index.php");
	}
}
?>

<html>
	<header>
		<meta charset="utf-8" /> 
		<title> Вход </title>
	</header>
	<body>
		<?php if ($flag_bad_login) echo "Данные неверны"; ?>
		<form action = 'auth.php' method = 'POST'>
			Логин:
			<input type = 'text' name = 'login' /> <br />
			Пароль:
			<input type = 'password' name = 'password' /> <br />
			<input type = 'submit' name = 'Логин'> <br />
		</form>
	</body>
</html>
