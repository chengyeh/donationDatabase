<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'admin';
$navbar_title = 'Admin panel - User Detail';
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
	//If the submit change button is clicked, update the user authorities
	if($_POST['action'] == 'change')
	{	
	    $sql = "UPDATE UserTable SET FlagDonor = " . $_POST['is_donor'] . ", FlagDonee = " . $_POST['is_donee'] . ", FlagUser = " 
	    	. $_POST['is_user'] . ", FlagAdmin = " . $_POST['is_admin'] . " WHERE UserID = " . $_GET['id'];
	    $mysqli->query($sql);
	}
	
	// If the deactivate account button is clicked, deactivate the user
    if($_POST['action'] == 'deactivate')
    {
    	$sql = "UPDATE UserTable SET Active = 0 WHERE UserID = " . $_GET['id'];
    	$mysqli->query($sql);
    }
    
    //If the activate account button is clicked, activate the user
    if($_POST['action'] == 'activate')
    {
    	$sql = "UPDATE UserTable SET Active = 1 WHERE UserID = " . $_GET['id'];
    	$mysqli->query($sql);
    }
}
?>
<?php
//Query user row from the database based on the id in the URL
$sql = "SELECT * FROM UserTable WHERE UserID = " . $_GET['id'];
$result_set = $mysqli->query($sql);
$view_user_array = array();
while($row =  mysqli_fetch_array($result_set))
{
	$view_user_array[] = $row;
}

if(!$view_user_array){
	$path = $config['path_web'] . 'html/admin/users.php';
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
                        <li class="active">
                            <a href="users.php"><span class="badge pull-right"><?php echo count_all("UserTable"); ?></span> User</a>
                        </li>
                        <li>
                            <a href="inventory.php"><span class="badge pull-right"><?php echo count_all("InventoryTable"); ?></span> Inventory</a>
                        </li>
                        <li>
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
                                <h1>User Detail</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                	<?php
					foreach($view_user_array as $user)
					{ ?>                    	                          
                    	<form class="form-horizontal" action="" method="POST">
						  	<div class="form-group">
						    	<label for="user_id" class="col-sm-2 control-label">User ID</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="user_id" placeholder="User ID" value="<?php echo $user['UserID'] ?>" disabled="disabled">
						    	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="fname" class="col-sm-2 control-label">First name</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="fname" placeholder="First name" value="<?php echo $user['FirstName'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="lname" class="col-sm-2 control-label">Last name</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="lname" placeholder="Last name" value="<?php echo $user['LastName'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="age" class="col-sm-2 control-label">Age</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="age" placeholder="Age" value="<?php echo $user['Age'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="gender" class="col-sm-2 control-label">Gender</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="gender" placeholder="Gender" value="<?php echo $user['Gender'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="ethnicity" class="col-sm-2 control-label">Ethnicity</label>
						    	<div class="col-sm-10">
						      		<select class="form-control" id="ethnicity" value="<?php echo $user['Ethnicity'] ?>" disabled="disabled">
						      			<?php						      		
						      			if($user['Ethnicity'] == 1)
						      			{
						      				echo '<option value="1">American Indian or Alaskan Native</option>';
						      			}
						      			else if($user['Ethnicity'] == 2)
						      			{
						      				echo '<option value="2">Asian</option>';
						      			}
						      			else if($user['Ethnicity'] == 3)
						      			{
						      				echo '<option value="3">Black or African American</option>';
						      			}
						      			else if($user['Ethnicity'] == 4)
						      			{
						      				echo '<option value="4">Native Hawaiian or Other Pacific Islander</option>';
						      			}
						      			else if($user['Ethnicity'] == 5)
						      			{
						      				echo '<option value="5">White</option>';
						      			}
						      			?>
						      		</select>
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="household_size" class="col-sm-2 control-label">Number in household</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="household_size" placeholder="Number in household" value="<?php echo $user['HouseholdSize'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="income" class="col-sm-2 control-label">Income</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="income" placeholder="Income" value="<?php echo $user['Income'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="address1" class="col-sm-2 control-label">Address line 1</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="address1" placeholder="Address line 1" value="<?php echo $user['AddressLine1'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="address2" class="col-sm-2 control-label">Address line 2</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="address2" placeholder="Address line 2" value="<?php echo $user['AddressLine2'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="city" class="col-sm-2 control-label">City</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="city" placeholder="City" value="<?php echo $user['City'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="state" class="col-sm-2 control-label">State</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="state" placeholder="State" value="<?php echo $user['State'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="zip" class="col-sm-2 control-label">Zip code</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="zip" placeholder="Zip code" value="<?php echo $user['Zip'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="phone" class="col-sm-2 control-label">Phone number</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="phone" placeholder="Phone number" value="<?php echo $user['Telephone'] ?>" disabled="disabled">
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="donor_flag" class="col-sm-2 control-label">Donor</label>
						    	<div class="col-sm-4">
						      		<select class="form-control" id="donor_flag" name="is_donor" >
						      			<?php
						      			if($user['FlagDonor'] == 0)
						      			{
						      				echo '<option value="0" selected>No</option>';
						      				echo '<option value="1">Yes</option>';
						      			}
						      			else
						      			{
						      				echo '<option value="0">No</option>';
						      				echo '<option value="1" selected>Yes</option>';
						      			}
						      			?>
						      		</select>	
						   	 	</div>
						   	 	<label for="donee_flag" class="col-sm-2 control-label">Donee</label>
						    	<div class="col-sm-4">
						      		<select class="form-control" id="donee_flag" name="is_donee" >
						      			<?php
						      			if($user['FlagDonee'] == 0)
						      			{
						      				echo '<option value="0" selected>No</option>';
						      				echo '<option value="1">Yes</option>';
						      			}
						      			else
						      			{
						      				echo '<option value="0">No</option>';
						      				echo '<option value="1" selected>Yes</option>';
						      			}
						      			?>
						      		</select>	
						   	 	</div>					   	 	
						  	</div>
						  	<div class="form-group">
						    	<label for="user_flag" class="col-sm-2 control-label">User</label>
						    	<div class="col-sm-4">
						      		<select class="form-control" id="user_flag" name="is_user" >
						      			<?php
						      			if($user['FlagUser'] == 0)
						      			{
						      				echo '<option value="0" selected>No</option>';
						      				echo '<option value="1">Yes</option>';
						      			}
						      			else
						      			{
						      				echo '<option value="0">No</option>';
						      				echo '<option value="1" selected>Yes</option>';
						      			}
						      			?>
						      		</select>	
						   	 	</div>
						   	 	<label for="admin_flag" class="col-sm-2 control-label">Admin</label>
						    	<div class="col-sm-4">
						      		<select class="form-control" id="admin_flag" name="is_admin" >
						      			<?php
						      			if($user['FlagAdmin'] == 0)
						      			{
						      				echo '<option value="0" selected>No</option>';
						      				echo '<option value="1">Yes</option>';
						      			}
						      			else
						      			{
						      				echo '<option value="0">No</option>';
						      				echo '<option value="1" selected>Yes</option>';
						      			}
						      			?>
						      		</select>	
						   	 	</div>					   	 	
						  	</div>												  	
						  	<div class="form-group">
						    	<div class="col-sm-offset-2 col-sm-10">
						      		<button type="submit" class="btn btn-primary" name="action" value="change">Submit change</button>
						      		<?php
						      		if($user['Active'] == 0 || $user['Active'] == NULL)
						      		{ ?>
						      			<button type="submit" class="btn btn-success" name="action" value="activate">Activate account</button>
						      		<?php } else { ?>
						      			<button type="submit" class="btn btn-danger" name="action" value="deactivate">Deactivate account</button>
						      		<?php } ?>		
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
