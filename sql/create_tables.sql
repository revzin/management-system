PROMPT Создание таблицы ejournal
CREATE TABLE ejournal 
(
	ej_id			INTEGER,
	ej_empl			INTEGER			NOT NULL,
	ej_timestamp	TIMESTAMP		NOT NULL,
	ej_author_id	INTEGER			NOT NULL,
	ej_text			VARCHAR2(500)	NOT NULL
);

PROMPT Создание таблицы employee
CREATE TABLE employee
(
	emp_id 			INTEGER,
	emp_role 		INTEGER 		NOT NULL,
	emp_name 		VARCHAR2(50) 	NOT NULL,
	emp_surname 	VARCHAR2(50) 	NOT NULL,
	emp_email		VARCHAR2(50) 	NOT NULL,
	emp_phone 		VARCHAR2(50)	NOT NULL,
	emp_salary 		INTEGER 		NOT NULL,
	emp_login 		VARCHAR2(20) 	NOT NULL,
	emp_password 	VARCHAR2(20) 	NOT NULL
);

PROMPT Создание таблицы unit
CREATE TABLE  unit
(
	u_id			INTEGER,
	u_serial		INTEGER			NOT NULL,
	u_bom_table		VARCHAR(50)		NOT NULL,
	u_asmy_mng_id	INTEGER,
	u_asmy_work_id	INTEGER,
	u_asmy_cont_id	INTEGER,
	u_state			INTEGER,
	u_ord_date		TIMESTAMP		NOT NULL,
	u_asm_date		TIMESTAMP,
	u_ctrl_date		TIMESTAMP
);
