<!DOCTYPE html>
<html lang = "en">
<?php

session_start();

$navbar_active = 'donate';
$navbar_title = 'Donor Page';
include('layouts/navbar.php');
include('helpers/mysqli.php');

$item = $_GET["request"];
$amount = $_GET["first"];

if(isset($_SESSION["id"]))
{	
	$temp = $_SESSION["id"];

	$updateAmount = "INSERT INTO dd_indonation (donor_id, amount, date_pledged) VALUES ($temp, '$amount', NOW())";

	if($r = $mysqli->query($updateAmount))
	{
		
	}

	$mysqli->close();
}
?>

<div class="container">
	<h3>Item Donation Form</h3> <br>
	<form action="donor.php">
		<table class="table table-striped">

			<tr>
				<th>Item</th>
				<th>Category</th>
				<th>Quantity</th>
			</tr>

			<tr>
				<td>
				<select name='request'>
					<option selected>Select item</option>
					<option>Shirts</option>
					<option>Shorts</option>
					<option>Food item 1</option>
					<option>Food item 2</option>
				</select>
				</td>
				<td>{Autopopulate category here}</td>
				<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
			</tr>
		</table>
		<hr>
		<div class="form-group">
			<label for="specialRequests" class="col-sm-2 control-label">Special Donations</label>
			<div class="col-sm-10">
				<input type="text" class="form-control input" name="specialRequests" placeholder="[Name of special item not available above]">
				<input type="number" class="form-control input" value="item1" min="0" scale="1" placeholder="[Number of item]">
			</div>

		</div>
		<hr> <br>
		
		<input type="submit" value="Pledge Donation">
	</form>
</div>

</body>
</html>
