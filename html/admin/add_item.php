<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'admin';
$navbar_title = 'Admin panel - Insert item';
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
	//If the insert item button is clicked, insert a new item into the inventory table
	if($_POST['action'] == 'insert')
	{	
		$category_num = $_POST['category'];
		$item_name = $_POST['name'];
		$amount = $_POST['amount'];
		$threshold = $_POST['threshold'];
		 
	    $sql = "INSERT INTO InventoryTable (CategoryNum, Name, Amount, Threshold) VALUES ($category_num, '$item_name', $amount, $threshold)";
	    $mysqli->query($sql);
	}
}
?>
<?php
//Query all the rows from the table
function get_rows($table_name, $order)
{
	global $mysqli;
	$sql = "SELECT * FROM " . $table_name . " ORDER BY " . $order . " ASC";
	$result_set = $mysqli->query($sql);
	$object_array = array();
	while($row =  mysqli_fetch_array($result_set))
	{
    	$object_array[] = $row;
	}
	return $object_array;
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
                        <li class="active">
                            <a href="inventory.php"><span class="badge pull-right"><?php echo count_all("InventoryTable"); ?></span> Inventory</a>
                        </li>
                        <li>
                            <a href="incDonation.php"><span class="badge pull-right"><?php echo count_all("IncDonationTable"); ?></span> Incoming Donation</a>
                        </li>
                        <li>
                            <a href="outDonation.php"><span class="badge pull-right"><?php echo count_all("OutDonationTable"); ?></span> Outgoing Donation</a>
                        </li>
						<li>
							<a href="tools.php">Tools</a>
						</li>
                    </ul>
                </div>

                <!-- content -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page-header">
                                <h1>Insert Item</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">                       	                          
                    	<form class="form-horizontal" action="" method="POST">
						  	<div class="form-group">
						    	<label for="name" class="col-sm-2 control-label">Name</label>
						    	<div class="col-sm-10">
						      		<input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
						    	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="category" class="col-sm-2 control-label">Category</label>
						    	<div class="col-sm-10">
						      		<select class="form-control" id="category" name="category" required>
						      			<option></option>
						      		<?php
						      		$category_array = get_rows("CategoriesTable", "CategoryNum");
						      		foreach($category_array as $category)
						      		{ ?>
						      			<option value="<?php echo $category['CategoryNum'] ?>"><?php echo $category['Name'] ?></option>
						      		<?php } ?>	
						      		</select>
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="amount" class="col-sm-2 control-label">Amount</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="amount" name="amount" min="0" required>
						   	 	</div>
						  	</div>
						  	<div class="form-group">
						    	<label for="threshold" class="col-sm-2 control-label">Threshold</label>
						    	<div class="col-sm-10">
						      		<input type="number" class="form-control" id="threshold" name="threshold" min="1" required>
						   	 	</div>
						  	</div>						  							  							 			  								  	
						  	<div class="form-group">
						    	<div class="col-sm-offset-2 col-sm-10">
						      		<button type="submit" class="btn btn-primary" name="action" value="insert">Insert item</button>					
						    	</div>
						  	</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
    </body>    
</html>
