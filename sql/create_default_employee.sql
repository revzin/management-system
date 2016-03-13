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
	'Сотрудник',
	'Фирмов',
	'empl@kontora.ru',
	'88002000600',
	1000,
	'system',
	'manager',
	t_jrnl_table (
		t_journal(CURRENT_TIMESTAMP, 10, 'Создал БД')
	)
);
