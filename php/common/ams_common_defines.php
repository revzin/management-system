<?php

// Состояния изделий
define("AMS_STATE_NEW", 			0);
//define("AMS_STATE_BOM_ORDER", 	1); /* не используются в этой АСУ */
//define("AMS_STATE_ASMY", 			2);
define("AMS_STATE_ASMY_COMPLETE",	3);
define("AMS_STATE_FINISH_OK", 		4);
define("AMS_STATE_FINISH_FAILURE", 	5);

// Типы компонентов на складе
//define("AMS_CTYPE_ASMY", 			0);
//define("AMS_CTYPE_PART", 			1);
//define("AMS_CTYPE_CNP", 			2);
//define("AMS_CTYPE_CP",			3);
//define("AMS_CTYPE_R", 			4);
//define("AMS_CTYPE_DA", 			5);
//define("AMS_CTYPE_DD", 			6);
//define("AMS_CTYPE_X", 			7);
//define("AMS_CTYPE_ETC",			8);
/* не используются в этой АСУ */

// Роли сотрудников
define("AMS_ROLE_BOSS", 			0);
define("AMS_ROLE_ASMY_WRK", 		1);
define("AMS_ROLE_CTL", 				2);
define("AMS_ROLE_MGR",				3);
//define("AMS_ROLE_WM", 			4); /* не используются в этой АСУ */
//define("AMS_ROLE_ADM", 			5); /* не используются в этой АСУ */
define("AMS_ROLE_FIRED",			6);
define("AMS_ROLE_TOTAL_COUNT",		6);

// Типы BOM
//define("AMS_BOMTYPE_TEMPLATE",		0);
//define("AMS_BOMTYPE_WORKING",		1);
//define("AMS_BOMTYPE_FREEZE",		2);
/* не используются в этой АСУ */

// Разрешения
define("AMS_PERM_EMP_VIEW_EMPLOYEES", 	1);
define("AMS_PERM_EMP_EDIT_EMPLOYEES",	2);
define("AMS_PERM_EMP_VIEW",				3);
define("AMS_PERM_EMP_VIEW_GROUP",		4);
define("AMS_PERM_EMP_HIREFIRE",			5);
define("AMS_PERM_EMP_PROMOTE_TO_BOSS",	6);
define("AMS_PERM_EMP_CAN_EDIT_BOSSES",	7);
//define("AMS_PERM_WRHS_VIEW",			8); 	/* не используются в этой АСУ */
//define("AMS_PERM_WRHS_EDIT",			9); 	/* не используются в этой АСУ */
//define("AMS_ADMPERM_ADM",				10); 	/* не используются в этой АСУ */
define("AMS_PERM_UNIT_PLACE_ORDER",		11);
define("AMS_PERM_UNIT_VIEW_RELEVANT",	12);
define("AMS_PERM_UNIT_VIEW_ALL",		13);
define("AMS_PERM_UNIT_ASSEMBLE",		14);
define("AMS_PERM_UNIT_EDIT_ORDER",		15);
define("AMS_PERM_UNIT_CONTROL",			16);
define("AMS_PERM_UNIT_DISCARD",			17);
define("AMS_PERM_UNIT_REPORT",			18);
?>
