DISC

SPOOL C:\revzin_install.log

PROMPT ======== Установка базы данных АСУ ТП (Г. Ревзин, ИУ4-82) =========

PROMPT ==== Вход в СУБД ====
@@01_start_session.sql
PROMPT ==== Вход окончен ====

PROMPT ==== Удаление БД ====
@@02_drop_tables_cascade.sql
PROMPT ==== БД удалена ====

PROMPT ==== Создание определений таблиц ====
@@03_create_tables.sql
PROMPT ==== Определения таблиц созданы ====

PROMPT ==== Создание ограничений ====
@@04_create_constraints.sql
PROMPT ==== Ограничения созданы ====

PROMPT ==== Создание триггеров автоинкремента ====
@@05_create_triggers.sql
PROMPT ==== Триггеры автоинкремента созданы ====

PROMPT === Создание хранимых процедур ====
@@06_create_procedures.sql
PROMPT === Процедуры созданы ====

PROMPT ==== Создание сотрудника АСУ по умолчанию ====
@@07_create_default_employee.sql
PROMPT ==== Сотрудник создан, логин/пароль: system/manager ====

PROMPT ======== Применение изменений =========
COMMIT;

PROMPT ======== Установка завершена =========
DISC

SPOOL OFF
	