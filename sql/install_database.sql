DISC
SPOOL C:\revzin_install.log

PROMPT ======== Установка базы данных АСУ (Г. Ревзин, ИУ4-82) =========

PROMPT ==== Вход в СУБД ====
@@start_session.sql
PROMPT ==== Вход окончен ====

PROMPT ==== Удаление БД ====
@@drop_everything.sql
PROMPT ==== БД удалена ====

PROMPT ==== Создание определений таблиц ====
@@create_tables.sql
PROMPT ==== Определения таблиц созданы ====

PROMPT ==== Создание ограничений ====
@@create_constraints.sql
PROMPT ==== Ограничения созданы ====

PROMPT ==== Создание триггеров автоинкремента ====
@@create_triggers.sql
PROMPT ==== Триггеры автоинкремента созданы ====

PROMPT ==== Создание сотрудника АСУ по умолчанию ====
@@create_default_employee.sql
PROMPT ==== Сотрудник создан, логин/пароль: system/manager ====

PROMPT ======== Применение изменений =========
COMMIT;

PROMPT ======== Установка завершена =========
DISC

SPOOL OFF
	