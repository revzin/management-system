<?php
require_once('../php/common/ora_session.php');
require_once('../php/unit_tools.php');
require_once('../php/echo_tools.php');
require_once('../php/unit_content.php');
require_once('../php/employee_tools.php');

define('FPDF_FONTPATH','../php/font/');

require_once('../php/barcode39.php');

define('PDF_ROW_H', 10);

function print_pdf_row(&$pdf, $rows, $rowid, $c_y)
{
	$vals = $rows[$rowid];
	$pdf->SetY($c_y);
	$pdf->Ln();
	$c_y += PDF_ROW_H;
	$pdf->Cell(10, 10, $rowid + 1);
	$pdf->Cell(40, 10, OracleTrimTimestampToDateTime($vals['ml_time']));
	$pdf->Cell(100, 10, OracleInString(ToolsKillHTML($vals['ml_text'])));
	return $c_y;
}

function AMSUnitCreatePDFRept($unit_id) 
{
	if (!AMSEmployeeHasPermission(AMS_PERM_UNIT_REPORT)) {
		echo '
			<head>
			<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			<link rel="stylesheet" href="style.css">
			</head>
			';
		die('AMSUnitCreatePDFRept: no permission for ' . AMSEmployeeID2Name($_SESSION[SESSIONKEY_EMPLOYEE_ID]));
		return;
	}
	
	$ml_rows = array();
	$numrows = OracleQuickReadQuery(
						QueryStringReplace(QUERY_SELECT_FROM_LOG, "unit_id", $unit_id),
						array("ml_text", "ml_time"),
						$ml_rows);
	
	if (0 == $numrows)
		return;
	
	$serial = AMSUnitID2Serial($unit_id);
						
	$pdf=new PDF_Code39();
	$pdf->AddPage();
	$pdf->AddFont('Arial','','arial_cyr.php');
	$pdf->AddFont('Times','','times_cyr.php');
	$pdf->SetFont('Arial','', 11);
	$pdf->Code39(150, 20, $serial, 1, 10);

	$pdf->Cell(10, 10, OracleInString(MSG_COMPANY_NAME));
	$pdf->Ln();
	$pdf->Cell(10, 10, OracleInString('Форма 8841-О'));

	$pdf->SetY(70);
	$pdf->Cell(70, 7, OracleInString('Отчёт о изготовлении изделия ' . $serial), 1);

	$c_y = 80;

	for ($i = 0; $i < $numrows; $i += 1) {
		$c_y = print_pdf_row($pdf, $ml_rows, $i, $c_y);
	}

	$pdf->Ln();
	$pdf->Cell(40, 10, OracleInString('Текущее состояние изделия: ' . AMSUnitStateString($unit_id)));


	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(175, 10, OracleInString('Подпись ответственного лица. ______М._П.______'), 0, 0, 'R');

	$pdf->Output();
}

AMSEmployeeRedirectAuth();
if (isset($_GET['unit_id']) and isset($_GET['unitrep']))
	AMSUnitCreatePDFRept($_GET['unit_id']);
?>