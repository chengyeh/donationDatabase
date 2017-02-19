<?php

require_once('helpers/form.php');

?>
<!DOCTYPE html>
<html lang = "en">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$navbar_active = 'signup';
$navbar_title = 'User Page';

include("layouts/navbar.php");
require_once('helpers/mysqli.php');

if(isset($_POST['itemquantity']) && isset($_POST['itemvalue']) && isset($_POST['reference']))
{	
	$refNum = $_POST['reference'];
	$amount = $_POST['itemquantity'];
	$value = $_POST['itemvalue'];

	$query = "UPDATE IncDonationTable SET ActualAmount='$amount', Value='$value', ReceiveDate=NOW() WHERE RefNum='$refNum'";

	if($result = $mysqli->query($query))
	{
		
	}
	else
	{
		die("Error: " . $mysqli->error);
	}
}
?>

<div class="container">
	<h3>Insert Item</h3> <br>
	<form class="form-horizontal" action="user.php" method="POST">
		<h4>Item Information</h4><br />
		<?php
		form_field('itemname', 'Item ID*', 'number');
		form_field('itemquantity', 'Item Quantity*', 'number');
		form_field('itemvalue', 'Item Value*', 'number');
		?>
		<hr>
		<h4>Donor Information</h4><br />
		<?php
		form_field('reference', 'Donation Reference Number*', 'number');
		form_field('donorid', 'Donor ID*', 'text');
		?>
		<b>- or -</b><br />
		<?php
		form_field('firstname', 'First Name*', 'text');
		form_field('lastname', 'Last Name*', 'text');
		form_field('address', 'Address*', 'text');
		form_field('phone', 'Phone*', 'text');
		form_field('email', 'Email*', 'email');
		csrf_token_field();
		?>
		<input type="submit" value="Enter Item"> <br>
	</form>
</div> <br><br>
<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>
