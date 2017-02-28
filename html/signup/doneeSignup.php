<!DOCTYPE html>
<html lang = "en">
<?php

require_once('../helpers/form.php');

$navbar_active = 'request';
$navbar_title = 'Donee Signup';
include('../layouts/navbar.php');

?>

<div class="container">
	<h3>Donee Sign Up </h3> <br>
	<form class="form-horizontal" action="submitDoneeSignup.php" method="POST">
		<?php
		form_field('firstname', 'First name');
		form_field('lastname', 'Last name');
		form_field('company', 'Company');
		form_field('address', 'Address');
		form_field('city', 'City');
		form_field('state', 'State');
		form_field('zip', 'Zip code');
		form_field('phone', 'Phone', 'text', '(555) 555-5555');
		form_field('email', 'Email', 'email', 'yourname@example.com');
		?>
	<hr>
	<h3>Demographic Information</h3>
		<?php
		form_field('age', 'Age', 'number');
		//TODO: extend forms.php to handle dropdowns
		?>
		<div class="form-group">
			<label class="col-sm-2 control-label">Ethnicity</label>
			<div class="col-sm-10">
				<select class="form-control">
					<!-- NOT-OD-15-089 -->
					<option value="aioan">American Indian or Alaskan Native</option>
					<option value="asian">Asian</option>
					<option value="black">Black or African American</option>
					<option value="nhoopi">Native Hawaiian or Other Pacific Islander</option>
					<option value="white">White</option>
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
		<?php
		form_field('adults', 'Adults in household', 'number');
		form_field('children', 'Children in household', 'number');
		?>
		<hr>
		<?php
		form_field('password', 'Password', 'password', '');
		form_field('passwordconf', 'Confirm password', 'password', '');
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
