<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
/**
 * PARAMETERS REQUIRED BY THIS LAYOUT
 * $navbar_active	(optional) one of the indices of the $nav_liclass array
 * 		below
 * $navbar_title	the title of this page
 */

require_once(__DIR__.'/../../config.php');

$path = $config['path_web'];
$active = 0;

if(isset($_SESSION["id"]))
{
	//$id = $_SESSION['id'];
	$isDonor = $_SESSION['donor'];
	$isDonee = $_SESSION['donee'];
	$isAdmin = $_SESSION['admin'];
	// no use for this just yet
	$isUser = $_SESSION['user'];
	$active = $_SESSION['active'];
	
	$logPage = "logout.php";
	$logTitle = "Logout";
	
	$helloString = "Hello, " . $_SESSION["name"];
}
else
{
	$logPage = "login.php";
	$logTitle = "Login";
}

$nav_liclass['signup'] = '';
$nav_liclass['home'] = '';
$nav_liclass['donate'] = '';
$nav_liclass['about'] = '';
$nav_liclass['request'] = '';
$nav_liclass['login'] = '';
$nav_liclass['admin'] = '';

if (isset($navbar_active)) {
    $nav_liclass[$navbar_active] .= 'active';
}

?>
<head>
	<!-- The following 3 meta tags *must* come first! -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first! -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="<?= $path ?>html/bootstrap/js/bootstrap.min.js"></script>

	<link href="<?= $path ?>html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= $path ?>html/bootstrap/css/custom.css" rel="stylesheet">
	<!-- load our css after bootstrap -->
	<title>
		<?= isset($navbar_title) ? $navbar_title : 'Donation database' ?>
	</title>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container topbar">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= $path ?>html/index.php">Donation database</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="<?= $nav_liclass['home'] ?>"><a href="<?= $path ?>html/index.php">Home</a></li>
				<li class="<?= $nav_liclass['about'] ?>">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-chevron-down" style="font-size:smaller"></span> About Us</a>
					<ul class="dropdown-menu">
						<li><a href="<?= $path ?>#contact">Contact Us</a></li>
						<li><a href="<?= $path ?>#newsletter">Newsletter</a></li>
					</ul>
				</li>
				<li class="<?= $nav_liclass['donate'] ?>" >
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-chevron-down" style="font-size:smaller"></span> Donate</a>
					<ul class="dropdown-menu">
						<li><a href="<?= $path ?>html/donor.php">Donate</a></li>
						<li><a href="<?= $path ?>html/donorHistory.php">Donation History</a></li>
						<li><a href="<?= $path ?>html/taxReceiptGen.php">Request Tax Receipt</a></li>
					</ul>
				</li>
				<li class="<?= $nav_liclass['request'] ?>">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-chevron-down" style="font-size:smaller"></span> Request Services</a>
					<ul class="dropdown-menu">
						<li><a href="<?= $path ?>html/donee.php">Request Services</a></li>
						<li><a href="<?= $path ?>html/doneeHistory.php">Request History</a></li>
					</ul>
				</li>
				<?php if (!isset($_SESSION['id'])) { ?> <li class="<?= $nav_liclass['signup'] ?>"><a href="<?= $path ?>html/signup/userSignup.php"> Sign Up</a></li> <?php } ?>
				<?php if (isset($_SESSION['id'])) { ?>
					<li>
						<a data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-chevron-down" style="font-size:smaller"></span> <?= $helloString ?></a>
						<ul class="dropdown-menu">
							<li><a href="<?= $path ?>html/profile.php">Edit information</a></li>
							<?php if (isset($isUser) && $isUser) { ?>
								<!--
								<li class=''><a href='<?= $path ?>html/user.php'>User</a></li>
								-->
							<?php } ?>
							<?php if($isUser) { ?>
								<li><a href="<?= $path ?>html/user.php">Employee Page</a></li>
							<?php } ?>
							<li><a href="<?= $path ?>html/logout.php">Log out</a></li>
						</ul>
					<li>
				<?php } else { ?>
					<li class="<?= $nav_liclass['login'] ?>"><a href="<?= $path ?>html/login.php">Log in</a></li>
				<?php } ?>
				<?php if(isset($_SESSION['admin']) && $isAdmin) { ?>
					<li class="<?= $nav_liclass['admin'] ?>">
						<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin</a>
						<ul class="dropdown-menu">
							<li><a href="<?= $path ?>html/admin/index.php">Admin Panel</a></li>
						</ul>
					</li>
				<?php } ?>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>

