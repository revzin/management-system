<?php

define('MSG_INSUFFICIENT_PERMSSIONS', "У вас прав для данного действия.");
define('MSG_NO_DATA_TO_SHOW', 'Нет данных для отображения.');

function ToolsEchoColumn($row, $column_name, $link = FALSE)
{
	echo '<td>';
	if ($link) {
		echo '<a href="' . strval($link) . '">';
	}
	echo $row[$column_name];
	if ($link) {
		echo '</a>';
	}
	echo '</td>';
}



?>