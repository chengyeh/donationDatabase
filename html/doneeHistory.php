<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'donate';
$navbar_title = 'Request History';
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
	if(!empty($_POST['cancel_request']))
	{
		foreach($_POST['cancel_request'] as $reference_num)
		{	
			//Delete selected row from the table
			$sql = "DELETE FROM OutDonationTable WHERE RefNum =" . $reference_num;
			$mysqli->query($sql);
		}
	}
}
?>
<?php
//Query data from outgoing donation table where the request is being reviewed
$sql = "SELECT * FROM OutDonationTable WHERE DoneeID =" . $id . " AND FulfillDate IS NULL ORDER BY RefNum ASC";
$result_set = $mysqli->query($sql);
$pendingRequest_array = array();
while($row =  mysqli_fetch_array($result_set)){
     $pendingRequest_array[] = $row;
}

//Query data from outgoing donation table where the donation is granted
$sql = "SELECT * FROM OutDonationTable WHERE DoneeID =" . $id . " AND FulfillDate IS NOT NULL ORDER BY RefNum ASC";
$result_set = $mysqli->query($sql);
$grantedRequest_array = array();
while($row =  mysqli_fetch_array($result_set)){
     $grantedRequest_array[] = $row;
}
?>
<!DOCTYPE html>
<html lang = "en">
<div class="container">
	<h3>Request History</h3> <br>
	<form action="doneeHistory.php" method="post">
		<div class="panel-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" href="#pending">Pending</a>
					</h4>
				</div>
				<div id="pending" class="panel-collapse collapse">
					<div class="panel-body">
					<?php if(!empty($pendingRequest_array)){ ?>
						<table class="table table-striped">
							<tr>
								<th>Reference Number</th>
								<th>Item</th>
								<th class="text-center">Amount Requested</th>
								<th class="text-center">Cancel</th>
							</tr>
							<?php
							foreach($pendingRequest_array as $item)
							{
								$sql = "SELECT Name FROM InventoryTable WHERE ItemID =" . $item['ItemID'];
								$result_set = $mysqli->query($sql);
								$itemName = array();
								while($row =  mysqli_fetch_array($result_set)){
								     $itemName[] = $row;
								}
								
								echo '<tr><td>' . $item['RefNum'] . '</td><td>' . $itemName[0]['Name'] . '</td><td style="text-align:center">' . $item['Amount'] . '</td>';
								echo '<td style="text-align:center"><input type="checkbox" name="cancel_request[]" value="' . $item['RefNum'] . '"></td></tr>';
							}	
							?>
							<tr><td></td><td></td><td></td><td class="text-center"><button type="submit" class="btn btn-default" name ="cancel">Confirm</button></td></tr>
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
					<a data-toggle="collapse" href="#granted">Granted</a>
				</h4>
			</div>
			<div id="granted" class="panel-collapse collapse">
				<div class="panel-body">
				<?php if(!empty($grantedRequest_array)){ ?>	
					<table class="table table-striped">
						<tr>
							<th>Reference Number</th>
							<th>Item</th>
							<th class="text-center">Amount</th>
							<th>Fulfill Date</th>
						</tr>
						<?php
						foreach($grantedRequest_array as $item)
						{
							$sql = "SELECT Name FROM InventoryTable WHERE ItemID =" . $item['ItemID'];
							$result_set = $mysqli->query($sql);
							$itemName = array();
							while($row =  mysqli_fetch_array($result_set)){
							     $itemName[] = $row;
							}
							
							echo '<tr><td>' . $item['RefNum'] . '</td><td>' . $itemName[0]['Name'] . '</td><td style="text-align:center">' . $item['Amount'] . '</td>';
							echo '<td>' . $item['FulfillDate'] . '</td></tr>';
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