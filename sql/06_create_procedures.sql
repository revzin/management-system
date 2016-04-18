CREATE OR REPLACE PROCEDURE p_gather_stats 
	(o_totsalary 			OUT INTEGER, 	-- общий поток трат на зарплату
	o_ready_percent 		OUT REAL,		-- процент выполнения плана
	o_busyest_asmyman_id	OUT INTEGER,	-- самый занятый монтажник 
	o_tot_personnel			OUT INTEGER,	-- всего сотрудников
	o_avg_minutes_per_unit	OUT REAL) 		-- среднее время выполнения зазказа
											-- на изготовление
	IS
				
	CURSOR l_c_asmymen
	IS 
		SELECT emp_id
			FROM employee
			WHERE emp_role = 3;
			
	l_cplt_units 			INTEGER;
	l_tot_units 			INTEGER;

	l_asmyman_id			INTEGER;	
	l_units_on_asmyman		INTEGER;
	l_units_on_asmyman_max INTEGER;
	
BEGIN
	SELECT SUM(emp_salary) INTO o_totsalary FROM employee;
	
	SELECT COUNT(*) emp_id INTO o_tot_personnel FROM employee;
	
	/* ------------------------------------------------------- */

	l_units_on_asmyman_max := -1;
	o_busyest_asmyman_id := -1;
	
	LOOP
		FETCH l_c_asmymen INTO l_asmyman_id;
		EXIT WHEN l_c_asmymen%NOTFOUND;
		
		SELECT COUNT(*) 
			INTO l_units_on_asmyman 
			FROM unit u 
			WHERE u.u_asmy_work_id = l_asmyman_id;
			-- сколько изделий приходится на монтажника?
		
		IF l_units_on_asmyman > l_units_on_asmyman_max THEN
			l_units_on_asmyman_max := l_units_on_asmyman;
			o_busyest_asmyman_id := l_asmyman_id;
			--  больше, чем уже найденный максимум?
		END IF;
		
	END LOOP;

	/* ------------------------------------------------------- */
				
	SELECT 1440 * AVG(TO_NUMBER(u_ctrl_time - u_ord_time)) 
		INTO o_avg_minutes_per_unit 
		FROM unit 
		WHERE u_ctrl_time IS NOT NULL ;
	
	SELECT COUNT(*) INTO l_tot_units FROM unit;
	
	IF l_tot_units > 0 THEN
		SELECT COUNT(*) INTO l_cplt_units 
			FROM unit 
			WHERE (u_ctrl_time IS NOT NULL) OR (u_disc_time IS NOT NULL);
			-- в выполнении плана учитываются изделия, производство которых окончено
		o_ready_percent := 100 * l_cplt_units / l_tot_units;
	ELSE
		o_ready_percent := 0;
	END IF;
	
END p_gather_stats;
/
