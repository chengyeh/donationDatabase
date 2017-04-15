<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

$navbar_active = 'admin';
$navbar_title = 'Admin panel - Inventory';
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
	//If the edit button is clicked, update the item amount and threshold
	if($_POST['action'] == 'edit')
	{	
	    $sql = "UPDATE InventoryTable SET Amount = " . $_POST['amount'] . ", Threshold = " . $_POST['threshold'] . " WHERE ItemID = " . $_POST['item_id'];
	    $mysqli->query($sql);
	}
	
	// If the delete button is clicked, delete the item from the inventory table
    if($_POST['action'] == 'delete')
    {
    	$sql = "DELETE FROM InventoryTable WHERE ItemID =" . $_POST['item_id'];
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
            <div class="row">
                <!-- left, vertical navbar -->
                <div class="col-md-2 bootstrap-admin-col-left">
                    <ul class="nav navbar-collapse collapse bootstrap-admin-navbar-side">
                        <li>
                            <a href="index.php"><i class="glyphicon glyphicon-chevron-right"></i> Recently Added</a>
                        </li>
                        <li>
                            <a href="users.php"><span class="badge pull-right"><?php echo count_all("UserTable"); ?></span> Users</a>
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
                    </ul>
                </div>

                <!-- content -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page-header">
                                <h1>Inventory
                                    <div class="col-md-2 pull-right">
                                		<form action="add_category.php"><input type="submit" class="btn btn-success" value="Insert new category" /></form>
                                	</div>
                                	<div class="col-md-2 pull-right">
                                		<form action="add_item.php"><input type="submit" class="btn btn-success" value="Insert new item" /></form>
                                	</div>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="text-muted bootstrap-admin-box-title">Inventory</div>
                                    <div class="pull-right"><span class="badge"><?php echo count_all("InventoryTable"); ?></span></div>
                                </div>
                                <div class="bootstrap-admin-panel-content">                                                             
                                    <table class="table bootstrap-admin-table-with-actions">
                                        <thead>
                                            <tr>
                                                <th>Item ID</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Amount</th>
                                                <th>Threshold</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $inventory_array = get_rows("InventoryTable", "ItemID");
										foreach($inventory_array as $item)
										{ 
											echo "<form action='' method='POST'>";
											$sql = "SELECT Name FROM CategoriesTable WHERE CategoryNum =" . $item['CategoryNum'];
												$result_set = $mysqli->query($sql);
												$category = array();
												while($row =  mysqli_fetch_array($result_set)){
												     $category[] = $row;
												}
										?>				
                                        	<tr>
                                                <td><?php echo $item['ItemID']; ?></td>
                                                <td><?php echo $item['Name']; ?></td>
                                                <td><?php echo $category[0]['Name']; ?></td>
                                                <td><input type="number" name="amount"  value="<?php echo $item['Amount']; ?>"></td>
                                                <td><input type="number" name="threshold" value="<?php echo $item['Threshold']; ?>"></td>
                                                <td>
                                                	<input type="hidden" name="item_id" value="<?php echo $item['ItemID']; ?>">
                                                	<button type="submit" class="btn btn-sm btn-primary" name="action" value="edit">Edit</button>
                                                	<button type="submit" class="btn btn-sm btn-danger" name="action" value="delete">Delete</button>
	                                            </td>
                                            </tr></form>
                                        <?php } ?>                                            
                                        </tbody>	                                       
                                    </table>  
                                </div>
                            </div>
                        </div>
                    </div>           
                </div>
            </div>
        </div>
    </body>
</html>
