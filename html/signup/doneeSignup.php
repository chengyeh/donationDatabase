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
    <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap-theme.min.css">
	<!-- load our css after bootstrap -->
	<title>Donee Signup Page</title>
</head>
<body>

<nav class="navbar navbar-default navbar-remote navbar-decoration ">
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
	<h3> Donee Sign Up </h3> <br>
	<form class="form-horizontal">
		<div class="form-group">
			<label for="firstname" class="col-sm-2 control-label">First name</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="firstname" placeholder="First name">
			</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-2 control-label">Last name</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="lastname" placeholder="Last name">
			</div>
		</div>
		<div class="form-group">
			<label for="company" class="col-sm-2 control-label">Company</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="company" placeholder="Company">
			</div>
		</div>
		<div class="form-group">
			<label for="address" class="col-sm-2 control-label">Address</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="address" placeholder="Address">
			</div>
		</div>
		<div class="form-group">
			<label for="city" class="col-sm-2 control-label">City</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="city" placeholder="City">
			</div>
		</div>
		<div class="form-group">
			<label for="state" class="col-sm-2 control-label">State</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="state" placeholder="State">
			</div>
		</div>
		<div class="form-group">
			<label for="zip" class="col-sm-2 control-label">Zip Code</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="zip" placeholder="Zip code">
			</div>
		</div>
		<div class="form-group">
			<label for="phone" class="col-sm-2 control-label">Phone</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="phone" placeholder="(555) 555-5555">
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" name="email" placeholder="Email address">
			</div>
		</div>
	</form>
	<hr>
	<h3>Demographic Information</h3>
	<form class="form-horizontal">
		<div class="form-group">
			<label for="age" class="col-sm-2 control-label">Age</label>
			<div class="col-sm-10">
				<input type="number" class="form-control" name="age" min="0" step="1">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Ethnicity</label>
			<div class="col-sm-10">
				<select class="form-control">
					<option value="white">Human</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Employment status</label>
			<div class="col-sm-10">
				<select class="form-control">
					<option value="employed">Employed</option>
					<option value="unemployed">Unemployed</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Income</label>
			<div class="col-sm-10">
				<select class="form-control">
					<option value="noanswer">Prefer not to answer</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Marital status</label>
			<div class="col-sm-10">
				<select class="form-control">
					<option value="married">Married</option>
					<option value="single">Single</option>
					<option value="relationship"?>In a relationship</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Adults in household</label>
			<div class="col-sm-10">
				<input type="number" class="form-control" value="adults" min="1" scale="1">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Children in household</label>
			<div class="col-sm-10">
				<input type="number" class="form-control" value="children" min="0" scale="1">
			</div>
		</div>
		<hr>
		<div class="form-group">
			<label for="password" class="col-sm-2 control-label">Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" name="password" placeholder="Password">
			</div>
		</div>
		<div class="form-group">
			<label for="passwordconf" class="col-sm-2 control-label">Confirm Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" name="passwordconf" placeholder="Retype Password">
			</div>
		</div>
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
