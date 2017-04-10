<!DOCTYPE html>
<html lang = "en">
<?php

require_once('../helpers/form.php');
require_once('../helpers/captcha.php');

$navbar_active = 'signup';
$navbar_title = 'User Signup Page';
include('../layouts/navbar.php');

?>

<script type="text/javascript">
function validateForm() {
	// var fname = document.forms["signup"]["firstname"];
	var emailPattern = /\w@\w\.\w/i;
	var email = document.forms["signup"]["email"];
	if (emailPattern.exec(email)) {
		alert('!!!');
	} else {
		alert('???');
	}
}
</script>

<div class="container">
	<?php
	include('../layouts/message.php');
	?>
	<h3>User Sign Up</h3>
	<p>Fields marked with an asterisk (*) are required.</p>
	<!-- <button onclick="validateForm()">Button</button> -->
	<form name = "signup" class="form-horizontal" action="submitUserSignup.php" method="POST">
		<h4>Required for Donor/User</h4>
		<?php
		// be very thorough for now ..
		form_field('firstname', 'First name*', 'text', 'First name');
		form_field('lastname', 'Last name*', 'text', 'Last name');
		form_field('address', 'Address*', 'text', 'Address');
		form_field('address2', 'Address line 2');
		form_field('city', 'City*', 'text', 'City');
		form_field('state', 'State*', 'text', 'State');
		//form_field('zip', 'Zip code*', 'number', 'Zip code');
		form_number_field('zip', 'Zip code*', 'Zip code', 99999, 10000);
		form_field('phone', 'Phone number*', 'text', '(555) 555-5555');
		?> <hr> <h4>Required for Donee</h4><?php
		form_number_field('age', 'Age', 'Age', 127, 13);
		form_gender_field();
		form_ethnicity_field();
		form_number_field('numInHouse', 'Number in household', 'Number in household', 127, 1);
		form_number_field('income', 'Income', 'Income', 0);
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

<script type="text/javascript" src="../jquery.maskedinput.min.js"></script>
<script type="text/javascript">
	$("#phone").mask("(999) 999-9999");
</script>
</html>
