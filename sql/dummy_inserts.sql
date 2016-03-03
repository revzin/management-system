INSERT INTO employee
	(
	emp_id,
	emp_role, 
	emp_name, 
	emp_surname, 
	emp_email, 
	emp_phone, 
	emp_salary, 
	emp_login, 
	emp_password, 
	emp_journal)
VALUES
(
	0,
	0,
	'Сименс',
	'Станиславович',
	'siemens@kontora.ru',
	'891611122233',
	30000,
	'siemens',
	'manager',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Основал компанию')
	)
);

INSERT INTO employee
	(
	emp_id,
	emp_role, 
	emp_name, 
	emp_surname, 
	emp_email, 
	emp_phone, 
	emp_salary, 
	emp_login, 
	emp_password, 
	emp_journal)
VALUES
(
	1,
	3,
	'Иван',
	'Капышевский-Аэрпээмович',
	'kp0603@kontora.ru',
	'8060306030603',
	30000,
	'siemens',
	'manager',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Принят на работу')
	)
);
	
	
	
	