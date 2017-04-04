<?php
//tax recepit generator
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once(__DIR__ . '/helpers/mysqli.php');
require_once(__DIR__.'/../config.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/fpdf/fpdf.php');

//get the information required for the donor generating this report
$id = '';
$lastTaxGen = '';
$donorEmail = '';
$sendPDFstatus = True;

if (isset($_SESSION['id']) && $_SESSION['donor'])
{
	$id = $_SESSION['id'];
}
else
{
	if (!isset($_SESSION['id'])) {
		$path = $config['path_web'] . 'html/login.php';
		$err = 401;
		header("Location:$path?err=$err&dest=donor");
	} else { // !$_SESSION['donor']
		$path = $config['path_web'] . 'html/profile.php';
		$err = 5;
		header("Location:$path?err=$err&dest=donor");
	}
	exit;
}

//get the donors information from the database
$res = $mysqli->query('SELECT * FROM UserTable WHERE UserID="'.$id.'"');
while($row =  mysqli_fetch_array($res))
{
	$lastTaxGen = $row['lastTaxGenDate'];
	$donorEmail = $row['Email'];
}

//make pdf object
$pdf = new FPDF();
$pdf->AddPage();

//add the non-profits logo in the top left of the document
$pdf->Image('image/logo.jpg',10,10);

//document title
$pdf->SetFont('Arial','B',24);
$pdf->Cell(0, 110, "DONATION RECEIPT", 0, 0, 'C');//center the header
$pdf->SetFont('Arial','B',14);
$pdf->setX(10); //reset current position to left margin
$pdf->Cell(0, 125, "TAX RECORD FORM", 0, 0, 'C');
$pdf->setX(10); //reset current position to left margin

//add the non-profits contact information in the top right
//set the margin for right alignment (2/3 of the page by default)
$pdf->setLeftMargin(143);
$pdf->Write(5,$config['nonprofit_name']);
$pdf->SetFont('Arial','',12);
$pdf->setX(10); //reset current position to left margin
$pdf->Write(5, $config['tax_receipt_info']);
$pdf->setLeftMargin(10); //reset the margin
$pdf->setX(10); //reset current position to left margin
$pdf->SetFont('Arial','',10);

//add the non-profit representative signature
$pdf->Image('image/signature.png',10,75, -150, -150);
$pdf->Line(10, 95, 100, 95);
$pdf->Cell(10, 110, ($config['nonprofit_name'] . " Representative Signature"));

//add a line for the date (donee fills out)
$pdf->Line(130, 95, 195, 95);
$pdf->Text(132, 101, "Date");

//add a line for the donees name (donee fills out)
$pdf->Line(10, 112, 195, 112);
$pdf->Text(12, 117, "Name");

//add a line for the donees address (donee fills out)
$pdf->Line(10, 127, 195, 127);
$pdf->Text(12, 132, "Address");

//add a line for the donees city (donee fills out)
$pdf->Line(10, 142, 110, 142);
$pdf->Text(12, 148, "City");

//add a line for the donees state (donee fills out)
$pdf->Line(120, 142, 160, 142);
$pdf->Text(122, 148, "State");

//add a line for the donees zip code (donee fills out)
$pdf->Line(170, 142, 195, 142);
$pdf->Text(172, 148, "Zip Code");

//table of donations headers
$pdf->setX(10);
$pdf->setY(160);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(140,7,'Donation',1,0,'L',0);
$pdf->Cell(20,7,'Quantity',1,0,'L',0);
$pdf->Cell(27,7,'Value',1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();

//output table of donations recieved since the last tax receipt was generated
$sql = "SELECT * FROM IncDonationTable WHERE DonorID='".$id.
	"' AND ReceiveDate > '".$lastTaxGen."'";
$result_set = $mysqli->query($sql);

//determine if any donations have been made since the last receipt generation
if(($result_set->num_rows) == 0){
	$sendPDFstatus = False;
}

//add any donations to the tax receipt document
while($row =  mysqli_fetch_array($result_set)){

	//lookup the item name from the ItemID per each reference number
	$ItemName = mysqli_fetch_array($mysqli->query("SELECT * FROM InventoryTable 
		WHERE ItemID=".$row['ItemID'].""));

	//output a row in the table
	$pdf->Cell(140,7,$ItemName[2],1,0,'L',0);
	$pdf->Cell(20,7,$row['ActualAmount'],1,0,'L',0);
	$pdf->Cell(27,7,'',1,0,'L',0); //leave this field blank
	$pdf->Ln();
}

//update the last time generated time stamp
$mysqli->query('UPDATE UserTable SET lastTaxGenDate=NOW() 
	WHERE UserID="'.$id.'"') or die(mysql_error());

//add a total field
$pdf->setX(155);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(15, 7, 'Total');
$pdf->Cell(27,7,'',1,0,'L',0);

//create the pdf as a string so it does not need to be saved to the server to send
$content = $pdf->Output('S');

//send the tax receipt doc because it meets all valid parameters
/*if($sendPDFstatus)
{*/
	//create an email instance to send to the donor
	//require the swiftmailer lib to create a pdf attachment
	require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/swiftmailer-5.x/lib/swift_required.php');
	$to = $donorEmail;
	$subject = $config['nonprofit_name'] . ' Donation Tax Receipt';
	$body = 'Thank you for your donation! Your tax receipt is attached to this email.
	Please fill out all remaining fields to complete the form.';

	//create an attachment for the pdf
	$attachment = Swift_Attachment::newInstance()
	  ->setFilename('taxDoc.pdf')
	  ->setContentType('text/pdf')
	  ->setBody($content);

	//send the email
	require_once('helpers/mail.php');

	//output confirmation message
	header('Location:index.php?msg=6');
/*}
else
{	
	//TODO error message if no there are no items donated since last tax receipt generated
	//output error message
	echo "no donations since last time you requested a tax receipt!";
}*/

?>