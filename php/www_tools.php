<?php

function ToolsRedirectClient($page, $statusCode = 303)
{
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header('Location: http://' . $host . $uri . '/' . $page, TRUE, $statusCode);
	die();
}

function ToolsSessionExists()
{
	return session_id() != '';
}

function ToolsEndSession()
{
	$_SESSION = array();
	session_destroy();
	session_id('');
}

function ToolsDropAllBrowserCache()
{
	header("Cache-Control: no-cache, no-store, must-revalidate
		Pragma: no-cache
		Expires: 0");
}

?>
