PROMPT удаление таблицы employee
DROP TABLE employee;
PROMPT создание таблицы employee
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
	emp_phone 		INTEGER 		NOT NULL,
		-- телефон
	emp_salary 		INTEGER 		NOT NULL,
		-- зп
	emp_login 		VARCHAR2(20) 	NOT NULL,
		-- логин
	emp_password 	VARCHAR2(20) 	NOT NULL,
		-- пароль
	emp_pd_table 	VARCHAR2(50) 	NOT NULL, 
		-- название таблицы с ЛД
);

PROMPT удаление таблицы unit
DROP TABLE unit;
PROMPT создание таблицы unit
CREATE TABLE unit
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
	u_ord_date		DATETIME		NOT NULL,
		-- дата заказа
	u_asm_date		DATETIME,
		-- дата сборки
	u_ctrl_date		DATETIME
		-- дата контроля
);
