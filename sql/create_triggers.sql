PROMPT Удаление последовательности автоинкремента employee
DROP SEQUENCE s_emp_id_incr;

PROMPT Создание последовательности автоинкремента employee
CREATE SEQUENCE s_emp_id_incr -- запрещается emp_id = 0
	START WITH 10 
	INCREMENT BY 1;

PROMPT Создание триггера автоинкремента employee
CREATE OR REPLACE TRIGGER t_emp_auto_increment
BEFORE INSERT ON employee
FOR EACH ROW
BEGIN
	SELECT s_emp_id_incr.NEXTVAL
	INTO :NEW.emp_id
	FROM DUAL;
END;
/

PROMPT Включение триггера автоинкремента employee
ALTER TRIGGER t_emp_auto_increment ENABLE;


PROMPT Удаление последовательности автоинкремента unit
DROP SEQUENCE s_unit_id_incr;

PROMPT Создание последовательности автоинкремента unit
CREATE SEQUENCE s_unit_id_incr -- запрещается emp_id = 0
	START WITH 1 
	INCREMENT BY 1;

PROMPT Создание триггера автоинкремента unit
CREATE OR REPLACE TRIGGER t_unit_auto_increment
BEFORE INSERT ON unit
FOR EACH ROW
BEGIN
	SELECT s_unit_id_incr.NEXTVAL
	INTO :NEW.u_id
	FROM DUAL;
END;
/

PROMPT Включение триггера автоинкремента unit
ALTER TRIGGER t_unit_auto_increment ENABLE;


PROMPT Удаление последовательности автоинкремента серийного номера unit
DROP SEQUENCE s_unit_serial_incr;

PROMPT Создание последовательности автоинкремента серийного номера unit
CREATE SEQUENCE s_unit_serial_incr -- запрещается emp_id = 0
	START WITH 10000 
	INCREMENT BY 1;

PROMPT Создание триггера автоинкремента unit
CREATE OR REPLACE TRIGGER t_unit_serial_auto_increment
BEFORE INSERT ON unit
FOR EACH ROW
BEGIN
	SELECT s_unit_serial_incr.NEXTVAL
	INTO :NEW.u_serial
	FROM DUAL;
END;
/

PROMPT Включение триггера автоинкремента unit
ALTER TRIGGER t_unit_auto_increment ENABLE;



PROMPT Удаление последовательности автоинкремента ejournal
DROP SEQUENCE s_ej_id_incr;

PROMPT Создание последовательности автоинкремента ejournal
CREATE SEQUENCE s_ej_id_incr -- запрещается emp_id = 0
	START WITH 1 
	INCREMENT BY 1;

PROMPT Создание триггера автоинкремента ejournal
CREATE OR REPLACE TRIGGER t_ej_id_auto_increment
BEFORE INSERT ON ejournal
FOR EACH ROW
BEGIN
	SELECT s_ej_id_incr.NEXTVAL
	INTO :NEW.ej_id
	FROM DUAL;
END;
/

PROMPT Включение триггера автоинкремента ejournal
ALTER TRIGGER t_ej_id_auto_increment ENABLE;
