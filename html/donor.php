<!DOCTYPE html>
<html lang = "en">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'donate';
$navbar_title = 'Donor Page';
include('layouts/navbar.php');
require_once('helpers/mysqli.php');

    
$sql = "SELECT * FROM CategoriesTable";
$result_set = $mysqli->query($sql);
$category_array = array();
while($row =  mysqli_fetch_array($result_set)){
     $category_array[] = $row;
}

/*$item = mysqli_real_escape_string($mysqli, $_GET["request"]);
$amount = mysqli_real_escape_string($mysqli, $_GET["first"]);

if(isset($_SESSION["id"]))
{
	$temp = $_SESSION["id"];

	$updateAmount = "INSERT INTO dd_indonation (donor_id, amount, date_pledged) VALUES ($temp, '$amount', NOW())";

	if($r = $mysqli->query($updateAmount))
	{
		
	}

	$mysqli->close();
}*/
?>

<div class="container">
	<h3>Item Donation Form</h3> <br>
	<form action="donor.php">
		<?php
		 foreach($category_array as $category)
		 {
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
				echo '<tr><td>' . $item['Name'] . '</td><td>' . ($item['Threshold']-$item['Amount']) . '</td><td><input type="number" value="item1" name="first" min="0" scale="1"></td></tr>';
			}
			echo '</table></div></div></div></div>';
		 	 	
		 }
		?>
		
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
