<?php
//generates a pdf of the current inventory status
require_once(__DIR__ . '/../helpers/mysqli.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/fpdf/fpdf.php');

//set time zone to get proper time back
date_default_timezone_set($config['time_zone']);
$date = date('m/d/Y h:i:s a', time());

//make pdf of needed
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(40,10,"Inventory Report");
$pdf->Ln();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(40,8,$date);
$pdf->Ln();

//categores query
$sql = "SELECT * FROM CategoriesTable";
$result_set = $mysqli->query($sql);
$category_array = array();
while($row =  mysqli_fetch_array($result_set)){

	//print category headers
	$pdf->SetFont('Arial','B',12);
	$category_array[] = $row;
    $pdf->Cell(40,8,$row['Name']);
    $pdf->Ln();

    //print out column headers
    $pdf->SetFont('Arial','B',10);
	$pdf->Cell(80,8,"Item Name",1,0,'L',0);
	$pdf->Cell(30,8,"Amount",1,0,'L',0);
	$pdf->Ln();

    //reset font to normal printout
    $pdf->SetFont('Arial','',10);

    //items in category query
	$sql = "SELECT * FROM InventoryTable WHERE CategoryNum =" . $row['CategoryNum'];
	$res = $mysqli->query($sql);
	while($row =  mysqli_fetch_array($res))
	{
		//print out each item and amount
		$pdf->Cell(80,8,$row['Name'],1,0,'L',0);
		$pdf->Cell(30,8,$row['Amount'],1,0,'L',0);
		$pdf->Ln();
	}
	//new line between tables
	$pdf->Ln();
}

//display pdf
$pdf->Output();
?>