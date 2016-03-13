<?php

require_once("common/ora_session.php");

define('MSG_INSUFFICIENT_PERMISSIONS', 				"У вас нет прав для данного действия.");
define('MSG_INSUFFICIENT_PERMISSIONS_VIEW_EMPL', 	"У вас нет прав для просмотра данного сотрудника");
define('MSG_INSUFFICIENT_PERMISSIONS_HIREFIRE', 	"У вас нет прав для найма и увольнения сотрудников");

define('MSG_NO_DATA_TO_SHOW', 			'Нет данных для отображения.');
define('MSG_FAILED_EMPLOYEE_CREATION', 	'Не удалось создать пользователя.');
define('MSG_UNMATCHING_PASSWORDS', 		'Пароли не совпдают.');
define('MSG_SALARY_HIDDEN', 			'Зарплата скрыта из вежливости');
define('MSG_LOGIN_TAKEN', 				'Логин занят:');
define('MSG_JOURNAL_HIRED', 			'Принят на работу');
define('MSG_JOURNAL_DATA_CHANGE', 		'Данные сотрудника были изменены');
define('MSG_JOURNAL_FIRED', 			'Уволен без выходного пособия');
define('MSG_JOURNAL_REHIRED', 			'C фанфарами восстановлен на работу');
define('MSG_BAD_POST', 					'POST-запрос сформирован неверно');
define('MSG_CANT_DEMOTE_LAST_BOSS',		'Невозможно понизить в должности последнего босса');

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