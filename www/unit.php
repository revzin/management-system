<?php 
require_once("../php/employees_content.php");
require_once("../php/employee_tools.php");
require_once("../php/unit_tools.php");
require_once("../php/unit_content.php");
AMSEmployeeRedirectAuth(); 
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php

ob_start();
UnitHandlePOST();
UnitHandleGET();
$content = ob_get_clean();

$html = file_get_contents("../html/workstation_template.html");

$html = str_replace('%MODULE_CONTENT_GENERATE%', $content, $html);
$html = str_replace('%MODULE_NAME%', "Изделия", $html);
$html = str_replace('%GENERATE_NAV%', ToolsGenerateNav(), $html);	
$html = AMSEmployeeFillCredForm($html);

echo $html;

?>