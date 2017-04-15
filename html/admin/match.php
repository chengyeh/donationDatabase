<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

require_once('../helpers/mysqli.php');
require_once(__DIR__.'/../../config.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/fpdf/fpdf.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/swiftmailer-5.x/lib/swift_required.php');

//get the information required for the donor generating this report
$id = '';
$adminEmail = '';

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
	$adminEmail = $row['Email'];
}

date_default_timezone_set($config['time_zone']);
$date = new DateTime();
$fdate = $date->format('Y-m-d H:i:s');
$reportDate = date('m/d/Y h:i:s a', time());

//make a new pdf and add the title and date
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(40,10,"Donation Allocation Report");
$pdf->Ln();
$pdf->Cell(40,8,$reportDate);
$pdf->Ln();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,8,"One donee's fulfilled requests will be printed per page.");
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$allocNumArray = array();

$sql = "SELECT * FROM InventoryTable";
$result_set = $mysqli->query($sql);
$inventory_array = array();
while($row =  mysqli_fetch_array($result_set)){
	if($row['Amount'] > 0)
	{
		$inventory_array[] = $row;
	}
}

foreach($inventory_array as $index => $item)
{
	$itemID = $item['ItemID'];
	$itemName = $item['Name'];
	
	$sql = "SELECT * FROM OutDonationTable WHERE ItemID=$itemID";
	$result_set = $mysqli->query($sql);
	$request_array = array();
	$time_array = array();
	while($row =  mysqli_fetch_array($result_set))
	{
		if($row['Amount'] != $row['AmountGranted'])
		{
			$request_array[] = $row;
			$time_array[] = $row['RequestDate'];
		}
	}
	array_multisort($time_array, SORT_ASC, $request_array);

	$amountLeft = $item['Amount'];
	
	foreach($request_array as $index => $request)
	{
		$requestAmount = $request['Amount'] - $request['AmountGranted'];
		$userID = $request['DoneeID'];
		
		if($requestAmount <= $amountLeft || $amountLeft != 0)
		{
			if($requestAmount < $amountLeft)
			{
				$amount = $requestAmount;
			}
			else
			{
				$amount = $amountLeft;
			}
			
			$amountLeft = $amountLeft - $amount;
			$query = "UPDATE InventoryTable SET Amount=$amountLeft WHERE ItemID=$itemID";
			if($result = $mysqli->query($query))
			{
			}
			else
			{
				die("MySQL error: " . $mysqli->error);
			}
			
			$refNum = $request['RefNum'];
			$query2 = "UPDATE OutDonationTable SET AmountGranted=AmountGranted+$amount, FulfillDate='$fdate' WHERE RefNum='$refNum'";
			if($result = $mysqli->query($query2))
			{
			}
			else
			{
				die("MySQL error: " . $mysqli->error);
			}

			//add array of reference numbers and amounts for the pdf report
			$allocNumArray[$refNum] = $amount;
		}
	}
}

//Make the pdf report of allocated donations
$sendEmailFlag = 0;
//query the users table
$sql = "SELECT * FROM UserTable";
$result = $mysqli->query($sql);
$infoPrinted = array();
while($userInfo = mysqli_fetch_array($result))
{	
	$userID = $userInfo[0];
	$infoPrinted[$userID] = 0;
	
	//get every ref num related the the current user ID
	foreach($allocNumArray as $key => $value)
	{	
		$sql = "SELECT * FROM OutDonationTable WHERE RefNum=$key";
		$res = $mysqli->query($sql);
		$donRef = mysqli_fetch_array($res);

		//get the item id of the of the donation row
		$itemID = $donRef['ItemID'];

		//check if the user info has already been printd out
		if($userID == $donRef['DoneeID'])
		{
			if($infoPrinted[$userID] == 0)
			{
				//user info
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(200,8,"Donee Information");
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(200,6,$userInfo['FirstName']." ".$userInfo['LastName']);
				$pdf->Ln();
				$pdf->Cell(200,6,$userInfo['AddressLine1']);
				$pdf->Ln();
				$pdf->Cell(200,6,$userInfo['AddressLine2']);
				$pdf->Ln();
				$pdf->Cell(200,6,$userInfo['City'].", ".$userInfo['State']." ".$userInfo['Zip']);
				$pdf->Ln();
				$pdf->Cell(200,6,"User ID: ".$userInfo['UserID']);
				$pdf->Ln();
				$pdf->Cell(200,6,"Phone #: ".$userInfo['Telephone']);
				$pdf->Ln();
				$pdf->Cell(200,6,"Email: ".$userInfo['Email']);
				$pdf->Ln();
				$pdf->Ln();

				//output table headers
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(40,8,"Reference Number",1,0,'L',0);
				$pdf->Cell(30,8,"Item ID",1,0,'L',0);
				$pdf->Cell(85,8,"Item Name",1,0,'L',0);
				$pdf->Cell(30,8,"Quantity",1,0,'L',0);
				$pdf->SetFont('Arial','',10);
				$pdf->Ln();

				//set the flag to not print the user info again
				$infoPrinted[$userID] = 1;
				$sendEmailFlag = 1;
			}

			//get the item information
			$sql = "SELECT * FROM InventoryTable WHERE ItemID=$itemID";
			$res = $mysqli->query($sql);
			$itemInfo = mysqli_fetch_array($res);

			//output allocated item information
			$pdf->Cell(40,8,$key,1,0,'L',0);
			$pdf->Cell(30,8,$itemID,1,0,'L',0);
			$pdf->Cell(85,8,$itemInfo['Name'],1,0,'L',0);
			$pdf->Cell(30,8,$value,1,0,'L',0);
			$pdf->Ln();
		}
	}
}

//create the pdf as a string so it does not need to be saved to the server to send
$content = $pdf->Output('S');

if($sendEmailFlag == 1)
{
	//create an email instance to send to the admin
	$to = $adminEmail;
	$subject = $config['nonprofit_name'] . ' Donation Allocation Report';
	$body = 'Attached is the report of each donation allocated to 
		donees that requested the service via the matching algorithm';

	//create an attachment for the pdf
	$attachment = Swift_Attachment::newInstance()
	  ->setFilename('DonationAllocationReport.pdf')
	  ->setContentType('text/pdf')
	  ->setBody($content);

	//send the email
	require_once('../helpers/mail.php');

	//output confirmation message
	header('Location:../index.php?msg=9');
}
else
	//show error that the matching algorithm didnt produce a new report
	header('Location:../index.php?err=13');


?>