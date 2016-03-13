PROMPT Удаление типа t_jrnl_table
DROP TYPE t_jrnl_table;

PROMPT Пересоздание типа t_journal
CREATE OR REPLACE TYPE t_journal AS OBJECT
(
	j_date			TIMESTAMP,
	j_author_id		INTEGER,
	j_text			VARCHAR2(200)
);
/

PROMPT Cоздание типа t_jrnl_table
CREATE OR REPLACE TYPE t_jrnl_table 
IS TABLE OF t_journal;
/

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
	emp_password 	VARCHAR2(20) 	NOT NULL,
	emp_journal		t_jrnl_table		
)
NESTED TABLE emp_journal 
STORE AS emp_journals;

PROMPT Удаление таблицы unit
DROP TABLE  unit;

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
