<?php

require_once("../php/employee_tools.php");
require_once("../php/www_tools.php");
require_once("../php/echo_tools.php");

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

<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css">
</head>

<?


$login_form = "
	<body>
		<form action = 'auth.php' method = 'POST'>
			Логин:
			<input type = 'text' name = 'login' class = 'auth'/> <br />
			Пароль:
			<input type = 'password' name = 'password'  class = 'auth'/> <br />
			<input type = 'submit' name = 'Логин'> <br />
		</form>
	</body> ";


$code = '';
if ($flag_bad_login) 
	$code .= 'Данные неверны </br>';

$code .= $login_form;

$html = file_get_contents("../html/workstation_template.html");

$html = str_replace('%MODULE_CONTENT_GENERATE%', $code, $html);
$html = str_replace('%MODULE_NAME%', "Авторизация", $html);
$html = str_replace('%GENERATE_NAV%', ToolsGenerateNav(), $html);
$html = AMSEmployeeFillCredForm($html);

echo $html;	


?>
