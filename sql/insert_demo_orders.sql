INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		11,
		0,
		CURRENT_TIMESTAMP
	);
	
INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		10,
		0,
		CURRENT_TIMESTAMP
	);	
	
INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		10,
		0,
		CURRENT_TIMESTAMP
	);	


INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		11,
		0,
		CURRENT_TIMESTAMP
	);	
	
INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		10,
		0,
		CURRENT_TIMESTAMP
	);	

INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		10,
		0,
		CURRENT_TIMESTAMP
	);	

INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		10,
		0,
		CURRENT_TIMESTAMP
	);	

INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
	VALUES
	(
		10,
		0,
		CURRENT_TIMESTAMP
	);		

INSERT INTO unit
	(
		u_asmy_mng_id,
		u_state,
		u_ord_time
	)
VALUES
	(
		10,
		0, /* AMS_STATE_NEW */
		CURRENT_TIMESTAMP
	);	

UPDATE unit
	SET 
		u_state = 3,
		u_asmy_work_id = 12,
		u_asm_time = CURRENT_TIMESTAMP
	WHERE u_id < 7;	
	
UPDATE unit
	SET 
		u_state = 5,
		u_asmy_disc_id = 14,	
		u_asmy_cont_id = 13,
		u_disc_time = CURRENT_TIMESTAMP,
		u_ctrl_time = CURRENT_TIMESTAMP
	WHERE u_id < 5;

UPDATE unit
	SET 
		u_state = 4,
		u_asmy_cont_id = 13,
		u_ctrl_time = CURRENT_TIMESTAMP
	WHERE u_id < 2;


COMMIT;
