UPDATE employee
	SET emp_name = 'Акула',
		emp_surname = 'Акулова',
		emp_email = 'best_ryba_ever@ryba-online.ru',
		emp_login = 'akulushka',
		emp_password = 'ryba',
		emp_role	 = 0,
		emp_salary = 90000,
		emp_phone = 880020001010
	WHERE emp_id = 10;


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
	'Гришан',
	'Полужид',
	'agent_revzin@mossad.net',
	'88002000605',
	1000,
	'agent_revzin',
	'manager'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	11, CURRENT_TIMESTAMP, 10, 'Принят на работу'
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
	emp_password
	)
VALUES
(
	1,
	'Никита',
	'Окнесинед',
	'not_denisenko@not_gmail.com',
	'88002000607',
	10000,
	'not_denisenko',
	'plastic'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	12, CURRENT_TIMESTAMP, 11, 'Принят на работу'
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
	emp_password
	)
VALUES
(
	2,
	'Чётылыбишь',
	'Аэрпээмов',
	'kp0603_only@bmstu.ru',
	'88002000608',
	10000,
	'kp0603',
	'arpm'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	13, CURRENT_TIMESTAMP, 11, 'Принят на работу'
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
	emp_password
)
VALUES
(
	3,
	'Анатолий',
	'Скумбрия',
	'skumbria_master_race@ryba-online.ru',
	'88002000609',
	10000,
	'skumbria',
	'ryba'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	14, CURRENT_TIMESTAMP, 10, 'Принят на работу'
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
	emp_password
)
VALUES
(
	1,
	'Остап',
	'Бендер',
	'ostap@rogakopyta.ru',
	'88002000610',
	40000,
	'tov_bender',
	'roga'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	15, CURRENT_TIMESTAMP, 10, 'Принят на работу'
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
	emp_password
)
VALUES
(
	6,
	'Зека',
	'Сидевший',
	'sidevshy1488@zona-online.ru',
	'88002000655',
	20000,
	'crimelord1488',
	'fired'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	16, CURRENT_TIMESTAMP, 10, 'Принят на работу'
);

INSERT INTO ejournal
(
	ej_empl, ej_timestamp, ej_author_id, ej_text
)
VALUES
(
	16, CURRENT_TIMESTAMP, 11, 'Был уволен как криминальный элемент'
);
