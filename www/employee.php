<?php

?>

<?php 
require_once("../php/employee_tools.php");
require_once("../php/employees_content.php");
AMSEmployeeRedirectAuth(); 
?>

<html>
<head>
<title> Сотрудники_v0 </title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
EmployeeHandlePOST();

AMSEchoCurrentUserData();

echo '<hr />';



echo '<hr />';

EmployeeHandleGET();

?>

<br /> <hr /> <br />

<?php

?>

</body>
</html>