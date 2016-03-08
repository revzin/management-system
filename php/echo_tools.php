<?php

require_once("common/ora_session.php");

define('MSG_INSUFFICIENT_PERMISSIONS', "У вас нет прав для данного действия.");
define('MSG_INSUFFICIENT_PERMISSIONS_VIEW_EMPL', "У вас нет прав для просмотра данного сотрудника");
define('MSG_INSUFFICIENT_PERMISSIONS_HIREFIRE', "У вас нет прав для найма и увольнения сотрудников");

define('MSG_NO_DATA_TO_SHOW', 'Нет данных для отображения.');
define('MSG_FAILED_EMPLOYEE_CREATION', 'Не удалось создать пользователя.');
define('MSG_UNMATCHING_PASSWORDS', 'Пароли не совпдают.');

function ToolsEchoColumn($row, $column_name, $link = FALSE, $timestamp_to_date = FALSE)
{
	$data = $row[$column_name];
	echo '<td>';
	if ($link) {
		echo '<a href="' . strval($link) . '">';
	}
	if ($timestamp_to_date)
		echo OracleTrimTimestampToDate($data);
	else
		echo $data;
	
	
	if ($link) {
		echo '</a>';
	}
	echo '</td>';
}

	

function ToolsEnquote($str)
{
	return "'" . $str . "'";
}


?>