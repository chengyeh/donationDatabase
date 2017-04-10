<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'admin';
$navbar_title = 'Admin panel - Incoming donation';
include('../layouts/navbar.php');
require_once('../helpers/mysqli.php');

$id = '';

if (isset($_SESSION['id']) && $_SESSION['admin'] && $_SESSION['active'])
{
	$id = $_SESSION['id'];
}
else
{
	if (!isset($_SESSION['id']) || !$_SESSION['active']) {
		$path = $config['path_web'] . 'html/login.php';
		$err = 401;
		header("Location:$path?err=$err&dest=donee");
	} else { // !$_SESSION['admin']
		$path = $config['path_web'] . 'html/profile.php';
		$err = 5;
		header("Location:$path?err=$err&dest=donor");
	}
	exit;
}
?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	//If the submit change button is clicked, update the incoming donation table
	if($_POST['action'] == 'change')
	{	
	    $sql = "UPDATE IncDonationTable SET Amount = " . $_POST['donate_amount'] . ", ActualAmount = " . $_POST['receive_amount'] . ", Value = " 
	    	. $_POST['value'] . " WHERE RefNum = " . $_GET['id'];
	    $mysqli->query($sql);
	}
	
	// If the delete button is clicked, delete the selected row from the incoming donation table
    if($_POST['action'] == 'delete')
    {
    	$sql = "DELETE FROM IncDonationTable WHERE RefNum =" . $_GET['id'];
    	$mysqli->query($sql);
    }
}
?>
<?php
//Query row from the incoming donation table based on the id in the URL
$sql = "SELECT * FROM IncDonationTable WHERE RefNum = " . $_GET['id'];
$result_set = $mysqli->query($sql);
$view_donation_array = array();
while($row =  mysqli_fetch_array($result_set))
{
	$view_donation_array[] = $row;
}

if(!$view_donation_array){
	$path = $config['path_web'] . 'html/admin/incDonation.php';
	header("Location:$path");
}

//Returns the count of rows from the table
function count_all($table_name)
{
	global $mysqli;
    $sql = "SELECT COUNT(*) FROM " . $table_name;
    $result_set = $mysqli->query($sql);
    $row = mysqli_fetch_array($result_set);
    return array_shift($row);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap -->
        <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap-theme.min.css">

        <!-- Bootstrap Admin Theme -->
        <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap-admin-theme.css">

        <!-- Datatables -->
        <link rel="stylesheet" media="screen" href="../bootstrap/css/DT_bootstrap.css">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
           <script type="text/javascript" src="js/html5shiv.js"></script>
           <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bootstrap-admin-with-small-navbar">

        <div class="container">
            <!-- left, vertical navbar & content -->
            	<div class="col-md-2 bootstrap-admin-col-left">
                    <ul class="nav navbar-collapse collapse bootstrap-admin-navbar-side">
                        <li>
                            <a href="index.php"><i class="glyphicon glyphicon-chevron-right"></i> Recently Added</a>
                        </li>
                        <li>
                            <a href="users.php"><span class="badge pull-right"><?php echo count_all("UserTable"); ?></span> User</a>
                        </li>
                        <li>
                            <a href="inventory.php"><span class="badge pull-right"><?php echo count_all("InventoryTable"); ?></span> Inventory</a>
                        </li>
                        <li class="active">
                            <a href="incDonation.php"><span class="badge pull-right"><?php echo count_all("IncDonationTable"); ?></span> Incoming Donation</a>
                        </li>
                        <li>
                            <a href="outDonation.php"><span class="badge pull-right"><?php echo count_all("OutDonationTable"); ?></span> Outgoing Donation</a>
                        </li>
                    </ul>
                </div>

                <!-- content -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page-header">
                                <h1>Incoming Donation Detail</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                	<?php
					foreach($view_donation_array as $donation)
					{ 
						$sql = "SELECT * FROM UserTable WHERE UserID =" . $donation['DonorID'];
												$result_set = $mysqli->query($sql);
												$donor = array();
												while($row =  mysqli_fetch_array($result_set)){
												     $donor[] = $row;
												}
						$sql = "SELECT Name FROM InventoryTable WHERE ItemID =" . $donation['ItemID'];
							$result_set = $mysqli->query($sql);
							$item = array();
							while($row =  mysqli_fetch_array($result_set)){
							     $item[] = $row;
							}
					?>                    	                          
                    	<form class="form-horizontal" action="" method="POST">
						  	<div class="form-group">
						    	<label for="ref_num" class="col-sm-2 control-label">Reference number</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="ref_num" placeholder="Reference number" value="<?php echo $donation['RefNum'] ?>" disabled="disabled">
						    	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="from" class="col-sm-2 control-label">From</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="from" placeholder="From" value="<?php echo $donor[0]['FirstName'] . " " . $donor[0]['LastName'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="item_name" class="col-sm-2 control-label">Item name</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="item_name" placeholder="Item name" value="<?php echo $item[0]['Name'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="donate_amount" class="col-sm-2 control-label">Donate amount</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="donate_amount" name="donate_amount" placeholder="Donate amount" value="<?php echo $donation['Amount'] ?>" min="1" required>
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="receive_amount" class="col-sm-2 control-label">Receive amount</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="receive_amount" name="receive_amount" placeholder="Receive amount" value="<?php echo $donation['ActualAmount'] ?>" min="0" required>
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="value" class="col-sm-2 control-label">Value</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="value" name="value" placeholder="Value" value="<?php echo $donation['Value'] ?>" min="0" required>
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="pledge_date" class="col-sm-2 control-label">Pledge date</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="pledge_date" placeholder="Pledge date" value="<?php echo $donation['PledgeDate'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="receive_date" class="col-sm-2 control-label">Receive date</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="receive_date" placeholder="Receive date" value="<?php echo $donation['ReceiveDate'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>				  											  	
						  	<div class="form-group">
						    	<div class="col-sm-offset-2 col-sm-10">
						      		<button type="submit" class="btn btn-primary" name="action" value="change">Submit change</button>
						      		<button type="submit" class="btn btn-danger" name="action" value="delete">Delete</button>	
						    	</div>
						  	</div>
						</form>
					<?php } ?>	
                    </div>
                </div>
            </div>
        </div>

        <!-- footer -->
        <div class="navbar navbar-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <footer role="contentinfo">
                            <p class="right">&copy; 2017 <a href="#">Donation database</a></p>
                        </footer>
                    </div>
                </div>
            </div>
        </div>

    </body>    
</html>
