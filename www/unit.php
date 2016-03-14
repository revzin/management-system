<?php 
require_once("../php/employees_content.php");
require_once("../php/employee_tools.php");
require_once("../php/unit_tools.php");
require_once("../php/unit_content.php");
AMSEmployeeRedirectAuth(); 
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css">
<title> Изделия_v0 </title>
</head>
<body>

<?php

AMSEchoCurrentUserData();

echo '<hr />';

UnitHandleGET();

echo '<hr />';


?>

<br /> <hr /> <br />

<?php

?>

</body>
</html>