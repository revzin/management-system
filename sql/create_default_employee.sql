INSERT INTO employee
	(
	emp_role, 
	emp_name, 
	emp_surname, 
	emp_email, 
	emp_phone, 
	emp_salary, 
	emp_login, 
	emp_password
	)
VALUES
(
	0,
	'Сотрудник',
	'Фирмов',
	'empl@kontora.ru',
	'88002000600',
	1000,
	'system',
	'manager'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	10, CURRENT_TIMESTAMP, 10, 'Установил АСУ фирмы'
);
