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
define('MSG_NO_ASMY_WORKERS', 			'Наймите хотя бы одного монтажника');
define('MSG_NO_CTL_WORKERS', 			'Наймите хотя бы одного контролёра');
define('MSG_COMPANY_NAME',	 			'ООО "Вектор-Плюс"');

define('MSG_ORDER_CREATED',				'Заказ на изготовление размещён');
define('MSG_ORDER_ASSEMBLED',			'Изделие отмечено как собранное');
define('MSG_ORDER_CONTROLLED_OK',		'Изделие выпущено как годное');
define('MSG_ORDER_CONTROLLED_FAIL',		'Изделие забраковано');
define('MSG_ORDER_CONTROLLED',			'Изделие проконтролировано');

define('MSG_JOURNAL_HIRED', 			'Принят на работу');
define('MSG_JOURNAL_DATA_CHANGE', 		'Данные сотрудника были изменены');
define('MSG_JOURNAL_FIRED', 			'Уволен без выходного пособия');
define('MSG_JOURNAL_REHIRED', 			'C фанфарами восстановлен на работу');

define('MSG_JOURNAL_PLACED_ORDER',		'Разместил заказ на сборку изделия <a href="%%LINK%%">№%%ORDER%%</a>');
define('MSG_JOURNAL_ASMY_SET',			'Назначен ответственным за сборку изделия <a href="%%LINK%%">№%%ORDER%%</a>');
define('MSG_JOURNAL_CTRL_SET',			'Назначен ответственным за контроль изделия <a href="%%LINK%%">№%%ORDER%%</a>');
define('MSG_JOURNAL_ASSEMBLED_ORDER',	'Выполнил заказ на сборку изделия  <a href="%%LINK%%">№%%ORDER%%</a>');
define('MSG_JOURNAL_CONTROLLED_ORDER',	'Проверил изделие <a href="%%LINK%%">№%%ORDER%%</a>');
define('MSG_JOURNAL_DISCARDED_ORDER',	'Забраковал изделие <a href="%%LINK%%">№%%ORDER%%</a>');
define('MSG_JOURNAL_RELEASED_ORDER',	'Выпустил изделие <a href="%%LINK%%">№%%ORDER%%</a>');

define('MSG_BAD_POST', 					'POST-запрос сформирован неверно');
define('MSG_CANT_DEMOTE_LAST_BOSS',		'Невозможно понизить в должности последнего босса');
define('MSG_NOT_SET',					'Не установлен');
define('MSG_YES',						'Да');
define('MSG_NO',						'Нет');

define('MSG_MANLOG_ORDER_PLACED',		'Заказ размещён сотрудником <a href=%%LINK%%>%%NAME%%</a>');
define('MSG_MANLOG_ORDER_ASMY_SET',		'<a href=%%LINK%%>%%NAME%%</a> назначен ответственным за сборку изделия');
define('MSG_MANLOG_ORDER_CTRL_SET',		'<a href=%%LINK%%>%%NAME%%</a> назначен ответственным за контроль изделия');
define('MSG_MANLOG_ORDER_ASSEMBLED',	'Изделие смонтировано сотрудником <a href=%%LINK%%>%%NAME%%</a>');
define('MSG_MANLOG_ORDER_CONTROLLED',	'Изделие проверено сотрудником <a href=%%LINK%%>%%NAME%%</a>');
define('MSG_MANLOG_ORDER_DISCARDED',	'Изделие забраковано сотрудником <a href=%%LINK%%>%%NAME%%</a>');
define('MSG_MANLOG_ORDER_RELEASED',		'Изделие выпущено');


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

function ToolsKillHTML($str)
{
	
	$ret = '';
	
	$copying = TRUE;
	
	for ($i = 0; $i < strlen($str); $i += 1) {
		if ('<' == $str[$i])
			$copying = FALSE;
			
		if ($copying)
			$ret .= strval($str[$i]);
			
		if ('>' == $str[$i])
			$copying = TRUE;
	}
	
	$ret = str_replace('>', '', $ret);
	$ret = str_replace(',', '', $ret);
	$ret = str_replace('.', '', $ret);
	$ret = str_replace('"', '', $ret);
	return $ret;
}


function ToolsGenerateNav()
{
	$html = '';
	$html = '<h2> Навигация по АСУ </h2>';
	$html .= '<ul>';
	$html .= '<li><a href = "index.php"> Главная страница </a>';
	$html .= '<li><a href = "unit.php"> Изделия </a>';
	$html .= '<li><a href = "employee.php"> Сотрудники </a>';
	$html .= '<li><a href = "auth.php"> Авторизация </a>';
	$html .= '</ul>';
	return $html;
}

?>
