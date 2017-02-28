<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
/**
 * PARAMETERS REQUIRED BY THIS LAYOUT
 * $navbar_active	(optional) one of the indices of the $nav_liclass array
 * 		below
 * $navbar_title	the title of this page
 */
 
 $filler = '';
 
 if(substr(getcwd(), -6) == 'signup')
 {
	 $filler = '../';
 }
 
 require_once($filler . 'helpers/mysqli.php');
 
 $donorLink = '';
 $doneeLink = '';
 $userLink = '';
 $adminLink = '';
 
 if(isset($_SESSION["id"]))
{
	$id = $_SESSION['id'];
	
	$logPage = "logout.php";
	$logTitle = "Logout";
	
	$helloString = "Hello, " . $_SESSION["name"];
	
	$query = "SELECT FlagDonor, FlagDonee, FlagUser, FlagAdmin FROM UserTable WHERE UserID = $id";
	
	if($result = $mysqli->query($query))
	{
		$row = $result->fetch_assoc();
		
		if($row['FlagDonor'])
		{
			$donorLink = $config['path_web'] . 'html/donor.php';
		}
		
		if($row['FlagDonee'])
		{
			$doneeLink = $config['path_web'] . 'html/donee.php';
		}
		
		if($row['FlagUser'] == 1)
		{
			$userLink = $config['path_web'] . '/html/user.php';
		}
		
		if($row['FlagAdmin'] == 1)
		{
			$adminLink = $config['path_web'] . '/html/admin/index.html';
		}	
	}
}
else
{
	$logPage = "login.php";
	$logTitle = "Login";
	
	$helloString = "";
}

require_once(__DIR__.'/../../config.php');

$nav_liclass['signup'] = '';
$nav_liclass['home'] = '';
$nav_liclass['donate'] = '';
$nav_liclass['getinvolved'] = '';
$nav_liclass['about'] = '';
$nav_liclass['request'] = '';
$nav_liclass['login'] = '';
$nav_liclass['hello'] = '';
$nav_liclass['admin'] = '';
$nav_liclass['user'] = '';

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
	<script src="<?= $config['path_web'] ?>html/bootstrap/js/bootstrap.min.js"></script>

	<link href="<?= $config['path_web'] ?>html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= $config['path_web'] ?>html/bootstrap/css/custom.css" rel="stylesheet">
	<!-- load our css after bootstrap -->
	<title><?= $navbar_title ?></title>
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
			<a class="navbar-brand" href="<?= $config['path_web'] ?>html/index.php">Donation database</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="<?= $nav_liclass['home'] ?>"><a href="<?= $config['path_web'] ?>html/index.php">Home</a></li>
				<li class="<?= $nav_liclass['about'] ?>">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About Us</a>
					<ul class="dropdown-menu">
						<li><a href="<?= $config['path_web'] ?>#contact">Contact Us</a></li>
						<li><a href="<?= $config['path_web'] ?>#newsletter">Newsletter</a></li>
					</ul>
				</li>
				
				<li class="<?= $nav_liclass['donate'] ?>"><a href="<?= $config['path_web'] ?>html/donor.php">Donate</a></li>
				
				<!--
				<li class="<?= $nav_liclass['getinvolved'] ?>"><a href="<?= $config['path_web'] ?>#getinvolved">Get Involved</a></li>
				-->
				
				<li class="<?= $nav_liclass['request'] ?>"><a href="<?= $config['path_web'] ?>html/donee.php">Request Services</a></li>
				
				
				<li class="<?= $nav_liclass['signup'] ?>">
					<a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sign Up</a>
					<ul class="dropdown-menu">
						<li><a href="<?= $config['path_web'] ?>html/signup/userSignup.php">As user</a></li>
						<li><a href="<?= $config['path_web'] ?>html/signup/donorSignup.php">As donor</a></li>
						<li><a href="<?= $config['path_web'] ?>html/signup/doneeSignup.php">As donee</a></li>
					</ul>
				</li>
				
				<li class="<?= $nav_liclass['login'] ?>"> <a href="<?= $config['path_web'] ?>html/<?=$logPage?>"><?= $logTitle ?></a></li>
				<li class="<?= $nav_liclass['hello'] ?>"> <?= $helloString ?></a></li>
				
				<?php
				if($userLink != '')
				{
					echo '<li class=' . $nav_liclass["user"] . '><a href=' . $userLink . '>Employee</a></li>';
				}
				
				if($adminLink != '')
				{
					echo '<li class=' . $nav_liclass["admin"] . '><a href=' . $adminLink . '>Admin</a></li>';
				}
				?>
				
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>
