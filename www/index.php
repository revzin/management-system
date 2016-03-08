<?php



?>
<?PHP header("Content-Type: text/html; charset=utf-8");?>


<html>
<head>
<title> DA </title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>
<?php
require_once("../php/common/ora_session.php");
require_once("../php/common/ora_queries.php");
require_once("../php/employee_tools.php");
require_once("../php/employees_content.php");

AMSEmployeeRedirectAuth();


?>

</body>
</html>