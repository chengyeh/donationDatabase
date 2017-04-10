<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'donate';
$navbar_title = 'Donation History';
include('layouts/navbar.php');
require_once('helpers/mysqli.php');

$id = '';

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
	} else { // !$_SESSION['donor']
		$path = $config['path_web'] . 'html/profile.php';
		$err = 5;
		header("Location:$path?err=$err&dest=donor");
	}
	exit;
}
?>
<?php
//Confirm cancel button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	//If at least one checkbox is selected
	if(!empty($_POST['delete_donation']))
	{
		foreach($_POST['delete_donation'] as $reference_num)
		{
			$sql = "SELECT * FROM IncDonationTable WHERE RefNum =" . $reference_num;
			$result_set = $mysqli->query($sql);
			$delete_item = array();
			while($row =  mysqli_fetch_array($result_set)){
			     $delete_item[] = $row;
			}
			
			//Delete donation if amount received is 0, else update the amount and put it to the complete donation table
			if($delete_item[0]['ActualAmount'] == 0)
			{
				$sql = "DELETE FROM IncDonationTable WHERE RefNum =" . $reference_num;
				$mysqli->query($sql);
			}
			else
			{
				//Set timezone
				date_default_timezone_set('America/Chicago');
				$date = new DateTime();
				$fdate = $date->format('Y-m-d H:i:s');

				$sql = "UPDATE IncDonationTable SET Amount = ActualAmount, ReceiveDate ='" . $fdate . "' WHERE RefNum =" . $reference_num;
				$mysqli->query($sql);
			}
		}
	}
}
?>
<?php
//Query data from incoming donation table where the donation is not complete
$sql = "SELECT * FROM IncDonationTable WHERE DonorID =" . $id . " AND Amount != ActualAmount ORDER BY RefNum ASC";
$result_set = $mysqli->query($sql);
$incompleteDonation_array = array();
while($row =  mysqli_fetch_array($result_set)){
     $incompleteDonation_array[] = $row;
}

//Query data from incoming donation table where the donation is complete
$sql = "SELECT * FROM IncDonationTable WHERE DonorID =" . $id . " AND Amount = ActualAmount ORDER BY RefNum ASC";
$result_set = $mysqli->query($sql);
$completeDonation_array = array();
while($row =  mysqli_fetch_array($result_set)){
     $completeDonation_array[] = $row;
}
?>
<!DOCTYPE html>
<html lang = "en">
<div class="container">
	<h3>Donation History</h3> <br>
	<form action="donorHistory.php" method="post">
		<div class="panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#incomplete">Incomplete</a>
					</h4>
				</div>
				<div id="incomplete" class="panel-collapse collapse">
					<div class="panel-body">
					<?php if(!empty($incompleteDonation_array)){ ?>	
						<table class="table table-striped">
							<tr>
								<th>Reference Number</th>
								<th>Item</th>
								<th class="text-center">Amount Pledged</th>
								<th class="text-center">Amount Received</th>
								<th>Pledge Date</th>
								<th class="text-center">Cancel</th>
							</tr>
							<?php
							foreach($incompleteDonation_array as $item)
							{
								$sql = "SELECT Name FROM InventoryTable WHERE ItemID =" . $item['ItemID'];
								$result_set = $mysqli->query($sql);
								$itemName = array();
								while($row =  mysqli_fetch_array($result_set)){
								     $itemName[] = $row;
								}

								echo '<tr><td>' . $item['RefNum'] . '</td><td>' . $itemName[0]['Name'] . '</td><td style="text-align:center">' . $item['Amount'] . '</td><td style="text-align:center">' . $item['ActualAmount'] . '</td>';
								echo '<td>' . $item['PledgeDate'] . '</td><td style="text-align:center"><input type="checkbox" name="delete_donation[]" value="' . $item['RefNum'] . '"></td></tr>';
							}
							?>
							<tr><td></td><td></td><td></td><td></td><td></td><td class="text-center"><button type="submit" class="btn btn-default" name ="delete">Confirm</button></td></tr>
						</table>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>		
	</form>
	<div class="panel-group">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" href="#complete">Complete</a>
				</h4>
			</div>
			<div id="complete" class="panel-collapse collapse">
				<div class="panel-body">
				<?php if(!empty($completeDonation_array)){ ?>	
					<table class="table table-striped">
						<tr>
							<th>Reference Number</th>
							<th>Item</th>
							<th class="text-center">Amount</th>
							<th class="text-center">Value</th>
							<th>Complete Date</th>
						</tr>
						<?php
						foreach($completeDonation_array as $item)
						{
							$sql = "SELECT Name FROM InventoryTable WHERE ItemID =" . $item['ItemID'];
							$result_set = $mysqli->query($sql);
							$itemName = array();
							while($row =  mysqli_fetch_array($result_set)){
							     $itemName[] = $row;
							}
							
							echo '<tr><td>' . $item['RefNum'] . '</td><td>' . $itemName[0]['Name'] . '</td><td style="text-align:center">' . $item['Amount'] . '</td>';
							echo '<td style="text-align:center">' . $item['Value'] . '</td><td>' . $item['ReceiveDate'] . '</td></tr>';
						}	
						?>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
</html>