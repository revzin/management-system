-- НЕ ЗАПУСКАТЬ КОПИ-ПАСТОМ, ТОЛЬКО ЧЕРЕЗ @!
INSERT INTO employee
	(
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
	3,
	'Иван',
	'Капышевский-Аэрпээмович',
	'kp0603@kontora.ru',
	'8060306030603',
	30000,
	'kp0603',
	'arpm',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Принят на работу')
	)
);
	
INSERT INTO employee
	(
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
	'Щук',
	'Инь',
	'ur5_master@kontora.ru',
	'8921555444333',
	30000,
	'shukine',
	'ryba',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Принят на работу')
	)
);
	
INSERT INTO employee
	(
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
	5,
	'Игорь',
	'Власович',
	'vlasovitch@kontora.ru',
	'89901231212',
	300,
	'reglament666',
	'iu4',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Принят на работу')
	)
);

INSERT INTO employee
	(
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
	5,
	'Викентий',
	'Альтушуллерович',
	'littlegreenmen@kontora.ru',
	'88005553535',
	90000,
	'vicenty',
	'iu4',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Принят на работу')
	)
);

INSERT INTO employee
	(
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
	'Анатолий',
	'Скумбрия',
	'skumbria_best_ryba@kontora.ru',
	'88005553535',
	90000,
	'skumbria',
	'ryba',
	jrnl_table (
		journal_t(CURRENT_TIMESTAMP, 0, 'Принят на работу')
	)
);

COMMIT;
