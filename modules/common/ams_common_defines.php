<?php

// Состояния изделий
define("AMS_STATE_NEW", 			0);
define("AMS_STATE_BOM_ORDER", 		1);
define("AMS_STATE_ASMY", 			2);
define("AMS_STATE_ASMY_COMPLETE",	3);
define("AMS_STATE_FINISH_OK", 		4);
define("AMS_STATE_FINISH_FAILURE", 	5);

// Типы компонентов на складе
define("AMS_CTYPE_ASMY", 			0);
define("AMS_CTYPE_PART", 			1);
define("AMS_CTYPE_CNP", 			2);
define("AMS_CTYPE_CP",				3);
define("AMS_CTYPE_R", 				4);
define("AMS_CTYPE_DA", 				5);
define("AMS_CTYPE_DD", 				6);
define("AMS_CTYPE_X", 				7);
define("AMS_CTYPE_ETC",				8);

// Роли сотрудников
define("AMS_ROLE_MGR", 				0);
define("AMS_ROLE_ASMY_WRK", 		1);
define("AMS_ROLE_CTL", 				2);
define("AMS_ROLE_HR",				3);
define("AMS_ROLE_WM", 				4);
define("AMS_ROLE_ADM", 				5);

// Типы BOM
define("AMS_BOMTYPE_TEMPLATE",		0);
define("AMS_BOMTYPE_WORKING",		1);
define("AMS_BOMTYPE_FREEZE",		2);

?>
