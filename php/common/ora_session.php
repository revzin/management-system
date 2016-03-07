<?php
require_once("ora_user.php");
require_once("ora_queries.php");

function OracleConnect() 
{
	$dbc = OCILogon(ORACLE_USER, ORACLE_PASSWORD, ORACLE_SERVICE);
	if (!$dbc) {
		error_log("ERROR: OCILogon failed: " . var_dump(OCIError()));
		return false;
	}
	return $dbc;
}
	
function OracleConnectSafe() 
{
	$dbc = OracleConnect();
	if (!$dbc)
		die("db failure");
	else
		return $dbc;
}

function OracleDisconnect($dbc)
{
	OCICommit($dbc);
	OCILogoff($dbc);
}

function OracleGetDBEncoding()
{
	$rows = array();
	OracleQuickReadQuery(QUERY_GET_ENCODING, "value", $rows, TRUE);
	$enc = $rows[0];
	if ($enc == 'CL8MSWIN1251')
		return 'CP1251';
}

function OracleOutString($dbstring)
{
	return _cp1251_to_utf8($dbstring);
}

function OracleInString($dbstring)
{
	return _cp1251_to_utf8($dbstring);
}

function OCIResultCustom($statement, $colname)
{	
	$result = OCIResult($statement, strtoupper($colname));
	
	if (USE_STRING_CONVERSION) {
		$need_convert = FALSE;
		if ($session_id == '') {	
			$need_convert = (OracleGetDBEncoding() == 'UTF-8');
		}
		else {
			if (!isset($_SESSION['ORA_ENCODING'])) {
				$_SESSION['ORA_ENCODING'] = OracleGetDBEncoding();
			} 	
			$need_convert = ($_SESSION['ORA_ENCODING'] == 'UTF-8');
		}
		
		if (is_string($result) and $need_convert) 
			return OracleOutString($result);
		else
			return $result;	
	} 
	else
		return $result;
}


function OracleQuickReadQuery($query_string, $keys, &$result, $use_default_ocires = FALSE)
{
	$dbc = OracleConnectSafe();

	$qr = OCIParse($dbc, $query_string);
	
	$e = OCIError($qr);
	if ($e) {
		echo var_dump($e);
		die("Query parse failure: " . $query_string);
	}
	
	OCIExecute($qr, OCI_DEFAULT);
	
	$e = OCIError($qr);
	if ($e) { 
		echo var_dump($e);
		die("Query execute failure: " . $query_string);
	}
	

	$i = 0;
	
	if (is_array($keys)) {
		while (OCIFetch($qr)) {
			foreach($keys as $k) {
				if ($use_default_ocires)
					$result[$i][strval($k)] = OCIResult($qr, strtoupper(strval($k)));
				else
					$result[$i][strval($k)] = OCIResultCustom($qr, strtoupper(strval($k)));
			}
			$i += 1;
		}
	}
	else {		
		while (OCIFetch($qr)) {
			if ($use_default_ocires)
				$result[$i] = OCIResult($qr, strtoupper(strval($keys)));
			else
				$result[$i] = OCIResultCustom($qr, strtoupper($keys));
			$i += 1;
		}
	}
	
	OracleDisconnect($dbc);
	return $i;
}


$_in_arr = array (
        chr(208), chr(192), chr(193), chr(194),
        chr(195), chr(196), chr(197), chr(168),
        chr(198), chr(199), chr(200), chr(201),
        chr(202), chr(203), chr(204), chr(205),
        chr(206), chr(207), chr(209), chr(210),
        chr(211), chr(212), chr(213), chr(214),
        chr(215), chr(216), chr(217), chr(218),
        chr(219), chr(220), chr(221), chr(222),
        chr(223), chr(224), chr(225), chr(226),
        chr(227), chr(228), chr(229), chr(184),
        chr(230), chr(231), chr(232), chr(233),
        chr(234), chr(235), chr(236), chr(237),
        chr(238), chr(239), chr(240), chr(241),
        chr(242), chr(243), chr(244), chr(245),
        chr(246), chr(247), chr(248), chr(249),
        chr(250), chr(251), chr(252), chr(253),
        chr(254), chr(255)
    );   
 
$_out_arr = array (
        chr(208).chr(160), chr(208).chr(144), chr(208).chr(145),
        chr(208).chr(146), chr(208).chr(147), chr(208).chr(148),
        chr(208).chr(149), chr(208).chr(129), chr(208).chr(150),
        chr(208).chr(151), chr(208).chr(152), chr(208).chr(153),
        chr(208).chr(154), chr(208).chr(155), chr(208).chr(156),
        chr(208).chr(157), chr(208).chr(158), chr(208).chr(159),
        chr(208).chr(161), chr(208).chr(162), chr(208).chr(163),
        chr(208).chr(164), chr(208).chr(165), chr(208).chr(166),
        chr(208).chr(167), chr(208).chr(168), chr(208).chr(169),
        chr(208).chr(170), chr(208).chr(171), chr(208).chr(172),
        chr(208).chr(173), chr(208).chr(174), chr(208).chr(175),
        chr(208).chr(176), chr(208).chr(177), chr(208).chr(178),
        chr(208).chr(179), chr(208).chr(180), chr(208).chr(181),
        chr(209).chr(145), chr(208).chr(182), chr(208).chr(183),
        chr(208).chr(184), chr(208).chr(185), chr(208).chr(186),
        chr(208).chr(187), chr(208).chr(188), chr(208).chr(189),
        chr(208).chr(190), chr(208).chr(191), chr(209).chr(128),
        chr(209).chr(129), chr(209).chr(130), chr(209).chr(131),
        chr(209).chr(132), chr(209).chr(133), chr(209).chr(134),
        chr(209).chr(135), chr(209).chr(136), chr(209).chr(137),
        chr(209).chr(138), chr(209).chr(139), chr(209).chr(140),
        chr(209).chr(141), chr(209).chr(142), chr(209).chr(143)
    );   
	
function _cp1251_to_utf8($txt)  {
	global $_in_arr, $_out_arr;
	$txt = str_replace($_in_arr, $_out_arr, $txt); 
	return $txt;
}

function _utf8_to_cp1251($txt)
{  
	$txt = str_replace($_out_arr,$_in_arr, $txt);  
	return $txt;
}
?>
