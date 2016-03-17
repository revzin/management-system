<?php
require_once('../php/common/ora_queries.php');
require_once('../php/common/ora_session.php');
require_once('../php/employee_tools.php');
require_once('../php/echo_tools.php');


function generate_index_content()
{
	$str = '';
	
	$str .= '<center> Здравствуйте, <a href="employee.php?employee_id=' . $_SESSION[SESSIONKEY_EMPLOYEE_ID] . '">' .
				AMSEmployeeID2Name($_SESSION[SESSIONKEY_EMPLOYEE_ID]) . '.</a> </center> <br /> <br />';
	
	$str .= '<h3> Статистика фирмы </h3>';
	
	$rows = array();
	$numrows = OracleQuickReadQuery(QUERY_COUNT_UNIT_FOR_INDEX, array("total"), $rows);

	$count_total = intval($rows[0]['total']);
	
	$str .= 'Всего заказов: ' . $count_total . '. </br>';
	
	$rows = array();
	$numrows = OracleQuickReadQuery(QUERY_COUNT_ASMY_FOR_INDEX,  array("total"), $rows);
	$count_asmy = $rows[0]['total'];
	
	$rows = array();
	$numrows = OracleQuickReadQuery(QUERY_COUNT_GOOD_FOR_INDEX,  array("total"), $rows);
	$count_good = $rows[0]['total'];
	
	$rows = array();
	$numrows = OracleQuickReadQuery(QUERY_COUNT_BAD_FOR_INDEX,  array("total"), $rows);
	$count_bad = $rows[0]['total'];
	
	$str .= 'Из них выпущено заказов: ' . $count_good . '; забраковано: ' . $count_bad . ' </br>';
	
	if ($count_total > 0) {
		$str .= '<b> Процент брака:  </b>' . sprintf('%.1f', ($count_bad / $count_total * 100)) . '%<br />';
		$str .= '<b> Выполнение плана: </b> ' . sprintf('%.1f', ($count_good / $count_total * 100)) . '%<br />';
	}
	return $str;
	
}

AMSEmployeeRedirectAuth();
?>

<html>
<head>
<title> Главная страница </title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php

$html = file_get_contents("../html/workstation_template.html");

$html = str_replace('%MODULE_CONTENT_GENERATE%', generate_index_content(), $html);
$html = str_replace('%MODULE_NAME%', "Главная страница АСУ", $html);
$html = str_replace('%GENERATE_NAV%', ToolsGenerateNav(), $html);
$html = AMSEmployeeFillCredForm($html);
echo $html;

?>
