PROMPT �������� ������� employee;
DROP TABLE employee;

PROMPT ��������  ���� jrnl_table;
DROP TYPE jrnl_table;

PROMPT ������������ ���� journal_t;
CREATE OR REPLACE TYPE journal_t AS OBJECT  -- ������ � �������
(
	j_date			TIMESTAMP,
	j_author_id		INTEGER,
	j_text			VARCHAR2(200)
);

PROMPT ������������ ���� jrnl_table; -- ������� ������� � ��
CREATE OR REPLACE TYPE jrnl_table IS TABLE OF journal_t;

PROMPT �������� ������� employee;
CREATE TABLE employee
(
	emp_id 			INTEGER 		PRIMARY KEY,
	emp_role 		INTEGER 		NOT NULL,
		-- ���� �� AMS_ROLE
	emp_name 		VARCHAR2(50) 	NOT NULL,
		-- ���
	emp_surname 	VARCHAR2(50) 	NOT NULL,
		-- �������
	emp_email		VARCHAR2(50) 	NOT NULL,
		-- ������������
	emp_phone 		VARCHAR2(50)	NOT NULL,
		-- �������
	emp_salary 		INTEGER 		NOT NULL,
		-- ��
	emp_login 		VARCHAR2(20) 	NOT NULL,
		-- �����
	emp_password 	VARCHAR2(20) 	NOT NULL,
		-- ������
	emp_journal		jrnl_table		
		-- ������ � ��
)
NESTED TABLE emp_journal 
STORE AS emp_journals;

PROMPT �������� �������� ��������������
DROP TRIGGER t_emp_auto_increment;
DROP SEQUENCE emp_id_incr;
CREATE SEQUENCE emp_id_incr;

PROPMT �������� �������� ��������������
CREATE TRIGGER t_emp_auto_increment
BEFORE INSERT ON employee
FOR EACH ROW
BEGIN
	SELECT emp_id_incr.NEXTVAL
	INTO :new.id
	FROM dual;
END;



PROMPT �������� ������� unit;
DROP TABLE IF EXISTS unit;
PROMPT �������� ������� unit;
CREATE TABLE  unit
(
	u_id			INTEGER 		PRIMARY KEY,
	u_serial		INTEGER			NOT NULL,
		-- �������� �����
	u_bom_table		VARCHAR(50)		NOT NULL,
		-- �������� � BOM
	u_asmy_mng_id	INTEGER,
		-- ��������
	u_asmy_work_id	INTEGER,
		-- ���������
	u_asmy_cont_id	INTEGER,
		-- ��������
	u_state			INTEGER,
		-- ��������� �� AMS_STATE
	u_ord_date		TIMESTAMP		NOT NULL,
		-- ���� ������
	u_asm_date		TIMESTAMP,
		-- ���� ������
	u_ctrl_date		TIMESTAMP
		-- ���� ��������
);
