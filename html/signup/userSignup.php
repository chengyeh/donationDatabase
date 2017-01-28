<?php

require_once('../helpers/form.php');

?>
<!DOCTYPE html>
<html lang = "en">

<head>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
	<!-- The following 3 meta tags *must* come first! -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first! -->
	<link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" media="screen" href="../bootstrap/css/custom.css"> <!-- load our css after bootstrap -->
	<title>User Signup Page</title>
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
			<a class="navbar-brand" href="#">Donation database</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li><a href="#">Sign Up</a></li>
				<li><a href="#home">Home</a></li>
				<li><a href="#donate">Donate</a></li>
				<li><a href="#getinvolved">Get Involved</a></li>
				<li class="dropdown"><a href="#about">About Us</a>
					<ul class="dropdown-menu">
						<li><a href="#contact">Contact Us</a></li>
						<li><a href="#newsletter">Newsletter</a></li>
					</ul>
				</li>
				<li><a href="#request">Request Services</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>

<div class="container">
	<h3> User Sign Up </h3> <br>
	<form class="form-horizontal" action="submitUserSignup.php" method="POST">
		<?php
		form_field('firstname', 'First name');
		form_field('lastname', 'Last name');
		form_field('address', 'Address');
		form_field('city', 'City');
		form_field('state', 'State');
		form_field('zip', 'Zip code');
		form_field('phone', 'Phone', 'text', '(555) 555-5555');
		form_field('email', 'Email', 'email', 'yourname@example.com');
		form_field('password', 'Password', 'password');
		form_field('passwordconf', 'Confirm password', 'password');
		csrf_token_field();
		?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-default">Sign up</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>
