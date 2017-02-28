<!DOCTYPE html>
<html lang = "en">
<?php

require_once('../helpers/form.php');
require_once('../helpers/captcha.php');

$navbar_active = 'signup';
$navbar_title = 'User Signup Page';
include('../layouts/navbar.php');

?>

<div class="container">
	<h3>User Sign Up</h3>
	<p>Fields marked with an asterisk (*) are required.</p>
	<form class="form-horizontal" action="submitUserSignup.php" method="POST">
		<?php
		// be very thorough for now ..
		form_field('firstname', 'First name');
		form_field('lastname', 'Last name');
		form_field('age', 'Age', 'number');
		form_field('gender', 'Gender');
		form_field('ethnicity', 'Ethnicity', 'number');
		form_field('numInHouse', 'Number in household', 'number');
		form_field('address', 'Address');
		form_field('address2', 'Address line 2');
		form_field('city', 'City');
		form_field('state', 'State');
		form_field('zip', 'Zip code', 'number');
		form_field('phone', 'Phone number', 'text', '(555) 555-5555');
		?> <hr> <?php
		form_field('email', 'Email*', 'email', 'yourname@example.com');
		form_field('password', 'Password*', 'password');
		form_field('passwordconf', 'Confirm password*', 'password');
		captcha_field(true);
		csrf_token_field();
		form_submit_button('Sign up');
		?>
	</form>
</div>

</body>
</html>
