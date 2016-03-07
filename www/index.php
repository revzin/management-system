<?php



?>
<?PHP header("Content-Type: text/html; charset=utf-8");?>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

<html>
<head>
<title> DA </title>
</head>
<body>
<?php
require_once("../php/common/ora_session.php");
require_once("../php/common/ora_queries.php");
require_once("../php/employee_tools.php");
require_once("../php/employees_content.php");

AMSEmployeeRedirectAuth();

//echo var_dump($_SESSION); 

AMSEchoEmployeeList();


?>

<br /> <hr /> <br />

<?php
	
?>

</body>
</html>