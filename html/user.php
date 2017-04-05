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
	$id = 0;

	//get ItemId for the reference number
	$sql = "SELECT ItemID FROM IncDonationTable WHERE RefNum ='$refNum'";
	if($result_set = $mysqli->query($sql)){
		while($row =  mysqli_fetch_array($result_set)){
	    	$id = $row['ItemID'];
		}
	} 
	else 
		die("Error: " . $mysqli->error);

	//update the incoming donation table
	$query = "UPDATE IncDonationTable SET ActualAmount=ActualAmount+'$amount', Value='$value', ReceiveDate=NOW() WHERE RefNum='$refNum'";
	if($result = $mysqli->query($query)){} else die("Error: " . $mysqli->error);

	//update the inventory table
	$query2 = "UPDATE InventoryTable SET Amount=Amount+'$amount' WHERE ItemId='$id'";
	if($result = $mysqli->query($query2)){} else die("Error: " . $mysqli->error);
}
?>

<div class="container">
	<h3>Insert Item</h3> <br>
	<form class="form-horizontal" action="user.php" method="POST">
		<h4>Item Information</h4><br />
		<?php
		form_field('reference', 'Donation Reference Number*', 'number');
		?><hr>
		<?php
		form_field('itemquantity', 'Item Quantity*', 'number');
		form_field('itemvalue', 'Item Value*', 'number');
		csrf_token_field();
		?>
		<input type="submit" value="Enter Item"> <br>
	</form>
</div> <br><br>
<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>
