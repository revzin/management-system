<?php



?>


<html>
<head>
<title> DA </title>
</head>
<body>
<?php

require_once("../php/common/ora_session.php");
require_once("../php/common/ora_queries.php");


$depts = OracleQuickQuery('SELECT emp_name FROM employee', array('emp_name'));


foreach($depts as $dept) {
	echo strval($dept['emp_name']) . '   <br />';
}


?>

<br /> <hr /> <br />

<?php

?>

</body>
</html>