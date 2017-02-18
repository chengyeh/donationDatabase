<!DOCTYPE html>
<html lang = "en">
<?php

session_start();

$navbar_active = 'donate';
$navbar_title = 'Donor Page';
include('layouts/navbar.php');
require_once('helpers/mysqli.php');

$item = mysqli_real_escape_string($mysqli, $_GET["request"]);
$amount = mysqli_real_escape_string($mysqli, $_GET["first"]);

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
		<!--category 1 collapsable panel-->
		<div class="panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#cat1">Category 1</a>
					</h4>
				</div>
				<div id="cat1" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table table-striped">
							<tr>
								<th>Item</th>
								<th>Quantity</th>
							</tr>
							<tr>
								<td>Mens Large Tshirt</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Womens Medium Tshirt</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Socks</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Shoes (size 12)</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Pants</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!--end cat 1 collapsable panel-->

		<!--category 2 collapsable panel-->
		<div class="panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#cat2">Hygiene Products</a>
					</h4>
				</div>
				<div id="cat2" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table table-striped">
							<tr>
								<th>Item</th>
								<th>Quantity</th>
							</tr>
							<tr>
								<td>Toothbrush</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Wintergreen Toothpaste</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Bubblegum Toothpaste</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Mens deoderant</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
							<tr>
								<td>Womens deoderant</td>
								<td><input type="number" value="item1" name="first" min="0" scale="1"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!--end cat 1 collapsable panel-->
		
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
