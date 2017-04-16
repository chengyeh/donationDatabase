<?php
//session_start();

$navbar_active = 'admin';
$navbar_title = 'Admin panel';
include('../layouts/navbar.php');
require_once('../helpers/mysqli.php');
require_once('../helpers/form.php');

if (isset($_POST['form'])) {
	if ($_POST['form'] == 'seeds') {
		require_once('../helpers/databaseSeed.php');

		for ($i = 0; $i < $_POST['users']; $i++)
			seed_user();
		for ($i = 0; $i < $_POST['categories']; $i++)
			seed_category();
		for ($i = 0; $i < $_POST['items']; $i++)
			seed_inventory();
		for ($i = 0; $i < $_POST['inc']; $i++)
			seed_incoming_donation();
		for ($i = 0; $i < $_POST['out']; $i++)
			seed_outgoing_donation();

		$seeds_success = true;
	}
}

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

function count_all($table_name)
{
	global $mysqli;
    $sql = "SELECT COUNT(*) FROM " . $table_name;
    $result_set = $mysqli->query($sql);
    $row = mysqli_fetch_array($result_set);
    return array_shift($row);
}
?>

<link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap-admin-theme.css">


<body class="bootstrap-admin-with-small-navbar">
	<div class="container">
		<div class="row">
			<div class="col-sm-2 bootstrap-admin-col-left">
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
					<li>
						<a href="incDonation.php"><span class="badge pull-right"><?php echo count_all("IncDonationTable"); ?></span> Incoming Donation</a>
					</li>
					<li>
						<a href="outDonation.php"><span class="badge pull-right"><?php echo count_all("OutDonationTable"); ?></span> Outgoing Donation</a>
					</li>
					<li class="active">
						<a href="tools.php">Tools</a>
					</li>
				</ul>
			</div>
			<div class="col-sm-10">
				<div class="row">
					<div class="page-header">
						<h1>Admin tools</h1>
					</div>
					<form name="seeds" class="form-horizontal" action="tools.php" method="POST">
						<h4>Database seeds</h4>
						<?php if (isset($seeds_success)): ?>
							<div class = "alert alert-success">
								<b>Success!</b> Test data was added to your database.
							</div>
						<?php endif; ?>
						<p>This tool can seed your database with test data.</p>
						<?php
						form_number_field('users', 'User seeds to generate', 0, 100, 0, 0);
						form_number_field('categories', 'Category seeds to generate', 0, 100, 0, 0);
						form_number_field('items', 'Inventory item seeds to generate', 0, 100, 0, 0);
						form_number_field('inc', 'Incoming donation seeds to generate', 0, 100, 0, 0);
						form_number_field('out', 'Outgoing donation seeds to generate', 0, 100, 0, 0);
						csrf_token_field();
						form_hidden_field('form', 'seeds');
						form_submit_button('Generate seeds');
						?>
					</form>
					<!-- <hr> -->
				</div>
			</div>
		</div>
	</div>
</body>
</html>
