<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();
$navbar_active = 'donate';
$navbar_title = 'Donor Page';
include('layouts/navbar.php');
require_once('helpers/mysqli.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/fpdf/fpdf.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/swiftmailer-5.x/lib/swift_required.php');

//Set timezone
date_default_timezone_set($config['time_zone']);

$id = '';
$donorEmail = '';
$paypal_id = $config['paypal_hosted_button_id'];

if (isset($_SESSION['id']) && $_SESSION['donor'] && $_SESSION['active'])
{
	$id = $_SESSION['id'];
}
else
{
	if (!isset($_SESSION['id']) || !$_SESSION['active']) {
		$path = $config['path_web'] . 'html/login.php';
		$err = 401;
		header("Location:$path?err=$err&dest=donor");
	}
	else { // !$_SESSION['donor']
		$path = $config['path_web'] . 'html/profile.php';
		$err = 5;
		header("Location:$path?err=$err&dest=donor");
	}
	exit;
}

//get the donors email address
$res = $mysqli->query('SELECT * FROM UserTable WHERE UserID="'.$id.'"');
$row=  mysqli_fetch_array($res);
$donorEmail = $row['Email'];

?>
<!DOCTYPE html>
<html lang = "en">
<?php

$sql = "SELECT * FROM CategoriesTable";
$result_set = $mysqli->query($sql);
$category_array = array();
while($row =  mysqli_fetch_array($result_set)){
     $category_array[] = $row;
}

if(isset($_GET["input0"]) && isset($_SESSION["id"]))
{	
	//variables to hold the timestamp of the pledge
	$date = new DateTime();
	$fdate = $date->format('Y-m-d H:i:s');
	$reportDate = date('m/d/Y h:i:s a', time());

	//input the pledged items into the incoming donation table
	foreach($category_array as $index => $category)
	{
		$inputName = "input".$index;

		$sql = "SELECT * FROM InventoryTable WHERE CategoryNum =" . $category['CategoryNum'] . " AND Amount != Threshold";
		$result_set = $mysqli->query($sql);
		$inventory_array = array();
		while($row =  mysqli_fetch_array($result_set))
		{
			$inventory_array[] = $row;
		}

		$input_array = array();
		$input_array = $_GET[$inputName];

		foreach($inventory_array as $index => $item)
		{
			if($input_array[$index] != 0)
			{
				$amount = $input_array[$index];
				$itemId = $item["ItemID"];
				$query = "INSERT INTO IncDonationTable (DonorID, ItemID, Amount, PledgeDate) VALUES ($id, $itemId, '$amount', '$fdate')";

				if($result = $mysqli->query($query))
				{

				}
				else
				{
					die("MySQL error: " . $mysqli->error);
				}
			}
		}
	}

	//create the pledge receipt pdf to send the donor
	//get the date and time for the report
	//$reportDate = date('m/d/Y h:i:s a', time());

	//make pdf object
	$pdf = new FPDF();
	$pdf->AddPage();

	//add the non-profits logo in the top left of the document
	$pdf->Image('image/logo.jpg',10,10);

	//document title
	$pdf->SetFont('Arial','B',24);
	$pdf->Cell(0, 110, "Donation Pledge Receipt", 0, 0, 'C');//center the header
	$pdf->SetFont('Arial','B',14);
	$pdf->setX(10); //reset current position to left margin
	$pdf->Cell(0, 125, $reportDate, 0, 0, 'C');
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
	$pdf->setY(75);
	$pdf->SetFont('Arial','',10);

	//get donor information from the use table
	$sql = "SELECT * FROM UserTable WHERE UserID=$id";
	$res = $mysqli->query($sql);
	$donor = mysqli_fetch_array($res);

	//output the donors information and instructions
	$pdf->Cell(200,6,$donor['FirstName']." ".$donor['LastName']);
	$pdf->Ln();
	$pdf->Cell(120,6,$donor['AddressLine1']);
	$pdf->Cell(80,6,"User ID: ".$donor['UserID']);
	$pdf->Ln();
	$pdf->Cell(120,6,$donor['AddressLine2']);
	$pdf->Cell(80,6,"Phone #: ".$donor['Telephone']);
	$pdf->Ln();
	$pdf->Cell(120,6,$donor['City'].", ".$donor['State']." ".$donor['Zip']);
	$pdf->Cell(80,6,"Email: ".$donor['Email']);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(200,5,"         Thank you for your pledge! If you need to cancel any of your pledged items, please use the donation history link");
	$pdf->Ln();
	$pdf->Cell(200,5,"in the donors tab of the website and select any pledged donations you would like to cancel. Please fill out the actual");
	$pdf->Ln();
	$pdf->Cell(200,6,"quantity of each item you are donating and include this document with your donation.");
	$pdf->Ln();
	$pdf->Ln();

	//output table headers
	$pdf->Cell(33,8,"Reference Number",1,0,'L',0);
	$pdf->Cell(15,8,"Item ID",1,0,'L',0);
	$pdf->Cell(85,8,"Item Name",1,0,'L',0);
	$pdf->Cell(30,8,"Quantity Pledged",1,0,'L',0);
	$pdf->Cell(27,8,"Actual Quantity",1,0,'L',0);
	$pdf->Ln();

	//printout all pledges from this pledge session
	$sql = "SELECT * FROM IncDonationTable WHERE DonorID='$id' AND PledgeDate='$fdate'";
	$result_set = $mysqli->query($sql);
	while($row =  mysqli_fetch_array($result_set))
	{
		//get the item name from the inventory table
		$sql = "SELECT * FROM InventoryTable WHERE ItemID=".$row['ItemID']."";
		$item = $mysqli->query($sql);
		$itemInfo = mysqli_fetch_array($item);

		//printout the pledged item data
		$pdf->Cell(33,8,$row['RefNum'],1,0,'L',0);
		$pdf->Cell(15,8,$row['ItemID'],1,0,'L',0);
		$pdf->Cell(85,8,$itemInfo['Name'],1,0,'L',0);
		$pdf->Cell(30,8,$row['Amount'],1,0,'L',0);
		$pdf->Cell(27,8,'',1,0,'L',0);
		$pdf->Ln();
	}

	//create the pdf as a string so it does not need to be saved to the server to send
	$content = $pdf->Output('S');

	//create an email instance to send to the donor
	$to = $donorEmail;
	$subject = $config['nonprofit_name'] . ' Donation Pledge Receipt';
	$body = 'Thank you for your pledge! If you need to cancel any of your pledged items, 
		please use the donation history link in the donors tab of the website and select 
		any pledged donations you would like to cancel. Please fill out the actual 
		quantity of each item you are donating and include this document with your donation.';
		

	//create an attachment for the pdf
	$attachment = Swift_Attachment::newInstance()
	  ->setFilename('PledgeReceipt.pdf')
	  ->setContentType('text/pdf')
	  ->setBody($content);

	//send the email to the donor
	require_once('helpers/mail.php');
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"> </script>

<form role="form" method="post">
	<input type="text" class="form-control" id="search" placeholder="Search for an item">
</form>

<ul id="results"></ul>

<script type="text/javascript">
function tableToggle(category, id)
{
	$('#cat' + category + '').collapse('show');
	$('#item' + id + '').addClass(" alert-info");

}

$(document).ready(function(){
	$('#search').on('input', function() {
		var substr = $(this).val();
		if(substr.length >= 2)
		{
			$.post('invSearch.php', {keywords: substr}, function(data) {
				$('ul#results').empty();
				$.each(data, function() {
					$('ul#results').append('<li><a onclick=tableToggle(' + this.catNum + ',' + this.id + ') href=' + '#item' + this.id + '>' + this.name + '</a></li>');
				});
			}, "json");
		}
	});
});
</script>

<div class="container">
	<h3>Item Donation Form</h3> <br>

	<form action="donor.php">
		<?php


		 foreach($category_array as $index => $category)
		 {
			$inputName = "input" . $index;

		 	echo '<div class="panel-group">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" href="#cat' . $category['CategoryNum'] . '">' . $category['Name'] . '</a>
							</h4>
						</div>
						<div id="cat' . $category['CategoryNum'] . '" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="table table-striped">
									<tr>
										<th>Item</th>
										<th>Need</th>
										<th>Quantity</th>
									</tr>';

			$sql = "SELECT * FROM InventoryTable WHERE CategoryNum =" . $category['CategoryNum'] . " AND Amount != Threshold";
			$result_set = $mysqli->query($sql);
			$inventory_array = array();
			while($row =  mysqli_fetch_array($result_set))
			{
				$inventory_array[] = $row;
			}

			foreach($inventory_array as $item)
			{
				if(($item['Threshold']-$item['Amount']) <= 0)
					echo '<tr id=item' . $item['ItemID'] . '><td>' . $item['Name'] . '</td><td>' . 0 . '</td><td><input type="number" value="0" name="'. $inputName .'[]" min="0" scale="1"></td></tr>';
				else
					echo '<tr id=item' . $item['ItemID'] . '><td>' . $item['Name'] . '</td><td>' . ($item['Threshold']-$item['Amount']) . '</td><td><input type="number" value="0" name="'. $inputName .'[]" min="0" scale="1"></td></tr>';
			}
			echo '</table></div></div></div></div>';

		 }
		?>
		<hr>
		<input type="submit" value="Pledge Donation">
	</form>
	<p>An email will be sent to you regarding your donation pledge and instructions.</p>
	<?php
		$contact_us = $config['contact_us_email'];
		echo '<p>To donate any items that are not listed above, please contact us at <a href="mailto:'.$contact_us.'">'.$contact_us.'</a>.';
	?>

	<br> <h3>Donate using PayPal</h3> <br>

	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value=<?php echo $paypal_id;?>>
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>

</div>


</body>
</html>
