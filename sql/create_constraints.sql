PROMPT Создание внутренних ограничений таблицы employee
ALTER TABLE employee
	ADD CONSTRAINT i_pk_employee PRIMARY KEY (emp_id);

ALTER TABLE employee
	ADD CONSTRAINT c_unique_emp_login UNIQUE (emp_login);

PROMPT Создание внутренних ограничений таблицы unit
ALTER TABLE unit
	ADD CONSTRAINT i_pk_unit PRIMARY KEY (u_id);

ALTER TABLE unit
	ADD CONSTRAINT c_unique_u_serial UNIQUE (u_serial);
	
PROMPT Создение ограничений внешних ключей на поля unit
ALTER TABLE unit
    ADD CONSTRAINT c_fk_u_asmy_mng_id 
	FOREIGN KEY (u_asmy_mng_id)
	REFERENCES employee (emp_id);
	
ALTER TABLE unit
    ADD CONSTRAINT c_fk_u_asmy_work_id
	FOREIGN KEY (u_asmy_work_id)
	REFERENCES employee (emp_id);	
	
ALTER TABLE unit
    ADD CONSTRAINT c_fk_u_asmy_cont_id
	FOREIGN KEY (u_asmy_cont_id)
	REFERENCES employee (emp_id);	
	
ALTER TABLE unit
    ADD CONSTRAINT c_fk_u_asmy_disc_id
	FOREIGN KEY (u_asmy_disc_id)
	REFERENCES employee (emp_id);	
	
PROMPT Создание внутренних ограничений таблицы ejournal
ALTER TABLE ejournal
	ADD CONSTRAINT i_pk_ejournal PRIMARY KEY (ej_id);

PROMPT Создание ограничений внешних ключей таблицы ejournal	
ALTER TABLE ejournal
    ADD CONSTRAINT c_fk_ej_id
	FOREIGN KEY (ej_empl)
	REFERENCES employee (emp_id);

ALTER TABLE ejournal
    ADD CONSTRAINT c_fk_ej_id
	FOREIGN KEY (ej_author_id)
	REFERENCES employee (emp_id);	
	
PROMPT Создание внутренних ограничений таблицы ejournal
ALTER TABLE ejournal
	ADD CONSTRAINT i_pk_ejournal PRIMARY KEY (ej_id);

PROMPT Создание ограничений внешних ключей таблицы ejournal	
ALTER TABLE ejournal
    ADD CONSTRAINT c_fk_ej_id
	FOREIGN KEY (ej_empl)
	REFERENCES employee (emp_id);

ALTER TABLE ejournal
    ADD CONSTRAINT c_fk_ej_id
	FOREIGN KEY (ej_author_id)
	REFERENCES employee (emp_id);
		
	