<!DOCTYPE html>
<html lang = "en">

<?php
session_start();

include('helpers/mysqli.php');

if(isset($_SESSION["id"]))
{	
	$temp = $_SESSION["id"];

	$updateAmount = "INSERT INTO dd_outdonation (donee_id, amount, date_generated) VALUES ('$temp', '$first', NOW())";

	if($r = $mysqli->query($updateAmount))
	{
		
	}

	$mysqli->close();
}
$navbar_active = 'request';
include("layouts/navbar.php");
?>

<div class="container">
	<h3>Item Requesting Form</h3> <br>
	<form action="donee.php">
		<table class="table table-striped">
			<tr>
				<th>Item</th>
				<th>Category</th>
				<th>Quantity</th>
			</tr>
			<tr>
				<td>{Item 1}</td>
				<td>{Category 1}</td>
				<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
			</tr>
			<tr>
				<td>{Item 2}</td>
				<td>{Category 2}</td>
				<td><input type="number" value="item2" name="second" min="0" scale="1"></td>
			</tr>
			<tr>
				<td>{Item 3}</td>
				<td>{Category 3}</td>
				<td><input type="number" value="item3" name="third" min="0" scale="1"></td>
			</tr>
			<tr>
				<td>{Item 4}</td>
				<td>{Category 4}</td>
				<td><input type="number" value="item4" name="fourth" min="0" scale="1"></td>
			</tr>
			<tr>
				<td>{Item 5}</td>
				<td>{Category 5}</td>
				<td><input type="number" value="item5" name="fifth" min="0" scale="1"></td>
			</tr>
		</table>
		<hr>
		<div class="form-group">
			<label for="specialRequests" class="col-sm-2 control-label">Special Requests</label>
			<div class="col-sm-10">
				<input type="text" class="form-control input" name="specialRequests" placeholder="Request special items not shown above">
			</div>
		</div>
		<hr> <br>
		
		<input type="submit" value="Request Donation">
	</form>
</div>

<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>
