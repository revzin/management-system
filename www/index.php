<?php



?>


<html>
<head>
<title> DA </title>
</head>
<body>
<?php

require("../php/common/ora_session.php");
require("../php/common/ora_queries.php");

$depts = OracleQuickQuery('SELECT emp_login, emp_name, emp_surname from employee', array('emp_login', 'emp_name', 'emp_surname'));

foreach($depts as $d) {
	echo $d['emp_name'] . '  ' . $d['emp_surname'] . $d['emp_login'] . ' <br />';
}

echo var_dump($depts);

?>

<br /> <hr /> <br />

<?php

?>

</body>
</html>