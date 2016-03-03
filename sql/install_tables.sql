PROMPT удаление таблицы employee;
DROP TABLE employee;

PROMPT удаление  типа jrnl_table;
DROP TYPE jrnl_table;

PROMPT пересоздание типа journal_t;
CREATE OR REPLACE TYPE journal_t AS OBJECT  -- запись в журнале
(
	j_date			TIMESTAMP,
	j_author_id		INTEGER,
	j_text			VARCHAR2(200)
);

PROMPT пересоздание типа jrnl_table; -- таблица записей в ЛД
CREATE OR REPLACE TYPE jrnl_table IS TABLE OF journal_t;

PROMPT создание таблицы employee;
CREATE TABLE employee
(
	emp_id 			INTEGER 		PRIMARY KEY,
	emp_role 		INTEGER 		NOT NULL,
		-- роль по AMS_ROLE
	emp_name 		VARCHAR2(50) 	NOT NULL,
		-- имя
	emp_surname 	VARCHAR2(50) 	NOT NULL,
		-- фамилия
	emp_email		VARCHAR2(50) 	NOT NULL,
		-- электропочта
	emp_phone 		VARCHAR2(50)	NOT NULL,
		-- телефон
	emp_salary 		INTEGER 		NOT NULL,
		-- зп
	emp_login 		VARCHAR2(20) 	NOT NULL,
		-- логин
	emp_password 	VARCHAR2(20) 	NOT NULL,
		-- пароль
	emp_journal		jrnl_table		
		-- записи в ЛД
)
NESTED TABLE emp_journal 
STORE AS emp_journals;

PROMPT удаление триггера автоинкремента
DROP TRIGGER t_emp_auto_increment;
DROP SEQUENCE emp_id_incr;
CREATE SEQUENCE emp_id_incr;

PROPMT создание триггера автоинкремента
CREATE TRIGGER t_emp_auto_increment
BEFORE INSERT ON employee
FOR EACH ROW
BEGIN
	SELECT emp_id_incr.NEXTVAL
	INTO :new.id
	FROM dual;
END;



PROMPT удаление таблицы unit;
DROP TABLE IF EXISTS unit;
PROMPT создание таблицы unit;
CREATE TABLE  unit
(
	u_id			INTEGER 		PRIMARY KEY,
	u_serial		INTEGER			NOT NULL,
		-- серийный номер
	u_bom_table		VARCHAR(50)		NOT NULL,
		-- табличка с BOM
	u_asmy_mng_id	INTEGER,
		-- менеджер
	u_asmy_work_id	INTEGER,
		-- монтажник
	u_asmy_cont_id	INTEGER,
		-- контролёр
	u_state			INTEGER,
		-- состояние по AMS_STATE
	u_ord_date		TIMESTAMP		NOT NULL,
		-- дата заказа
	u_asm_date		TIMESTAMP,
		-- дата сборки
	u_ctrl_date		TIMESTAMP
		-- дата контроля
);
