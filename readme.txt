
========================================
Вывод:
Все функции вывода выводят данные согласно логину в сессии

AMSEchoEmployeeEditor($id)
Выводит редактор/просмоторщик пользователя с $id

AMSEchoEmployeeList()
Выводит список пользователей

=======================================
Роли/разрешения:
AMSEmployeePermissionsByRole($role)
Возвращает массив разрешений для роли $role

AMSEmployeeRoleToString($role) 
Возвращает текстовое название роли $role

AMSEmployeeGetPermissions($id) 
Возвращает массив разрешений (AMS_PERM_*) для сотрудника $id

AMSEmployeeHasPermission($permission, $id = 'CURRENT')
Проверяет наличие разрешения $permission у сотрудника $id
Если $id = 'CURRENT', разрешения берутся из сессии

AMSEmployeeGetRole($id)
Возвращает роль сотрудника

=======================================
Логины/сессии:
AMSEmployeeLogin($login, $password)
Логинится, возвращается id пользователя, если логин успешен, 
и 'NO_SUCH_USER'/'WRONG_PASSWORD' в случае ошибки

AMSEmployeeSetupSession($id)
Настраивает текущую сессию с данными для пользователя $id

AMSEmployeeDestroySession()
Завершает текущую сессию (разлогинивает пользователя)

AMSEmployeeRedirectAuth()
Проверяет состояние сессии и, если её нет, отправляет пользователя логиниться
	
=======================================
Оркале:
OracleConnect() 
Подключается к БД, возвращает database connection

OracleConnectSafe() 
Подключается к БД, возвращает database connection, делает die(), если подключения нет

OracleDisconnect($dbc) 
Отключается от БД $dbc, делает Commit и Logoff

OracleGetDBEncoding()
Читает кодировку строк в БД

OracleOutString($dbstring)
Преобразует строку $dbstring из кодировки БД в UTF8

OracleInString($dbstring)
Преобразует строку $dbstring из UTF8 в кодировку БД

OracleQuickReadQuery($query_string, $keys, &$result, $use_default_ocires = FALSE)
"Быстрый" запрос на чтение. Возвращает число выбранных строк
$query_string 			-- запрос
$keys 					-- массив названий столбцов, которые хочется прочесть, или один столбец.
&$result 				-- передать сюда пустой array(); в него будут сложены результаты в таком формате:
							$result[номер_выбранной_строки][название_столбца]
$use_default_ocires 	-- использовать стандартный OCIResult, а не кастомный (нужно в 0,001% случаев)

Напр:
$rows = array();
$numrows = OracleQuickReadQuery("SELECT ename, job FROM emp WHERE sal > 100", 
									array("ename", "job"),
									$rows);
echo $rows[3]["job"];
echo $rows[$numrows - 1]["ename"];							

OracleTestDatabaseInstallation()
Возвращает TRUE, если БД установлена	
