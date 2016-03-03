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

$depts = OracleQuickQuery('SELECT dname, loc from DEPT', array('dname', 'loc'));

foreach($depts as $d) {
	echo $d['dname'] . '  ' . $d['loc'] . '<br />';
}


?>

<br /> <hr /> <br />

<?php

echo QueryStringReplace(QUERY_GET_USER_ROLE, 'id', 4);

?>

</body>
</html>