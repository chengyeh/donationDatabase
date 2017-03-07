<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'donate';
$navbar_title = 'Donor Page';
include('layouts/navbar.php');
require_once('helpers/mysqli.php');

$id = '';

if (isset($_SESSION['id']) && $_SESSION['donor'])
{
	$id = $_SESSION['id'];
}
else
{
	if (!isset($_SESSION['id'])) {
		$path = $config['path_web'] . 'html/login.php';
		$err = 401;
		header("Location:$path?err=$err");
	} else { // !$_SESSION['donor']
		$path = $config['path_web'] . 'html/profile.php';
		$err = 5;
		header("Location:$path?err=$err");
	}
	exit();
}

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
				$query = "INSERT INTO IncDonationTable (DonorID, ItemID, Amount, PledgeDate) VALUES ($id, $itemId, '$amount', NOW())";
				
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
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"> </script>

<form role="form" method="post">
	<input type="text" class="form-control" id="search" placeholder="Search for an item">
</form>

<ul id="results"></ul>

<script type="text/javascript">
	$(document).ready(function(){
		$('#search').on('input', function() {
			var substr = $(this).val();
			if(substr.length >= 2)
			{
				$.post('invSearch.php', {keywords: substr}, function(data) {
					$('ul#results').empty();
					$.each(data, function() {
						$('ul#results').append('<li>' + this.name + ' can be found in ' + this.category + '</li>');
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
				echo '<tr><td>' . $item['Name'] . '</td><td>' . ($item['Threshold']-$item['Amount']) . '</td><td><input type="number" value="0" name="'. $inputName .'[]" min="0" scale="1"></td></tr>';
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
