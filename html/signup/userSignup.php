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
	<?php
	include('../layouts/message.php');
	?>
	<h3>User Sign Up</h3>
	<p>Fields marked with an asterisk (*) are required.</p>
	<form class="form-horizontal" action="submitUserSignup.php" method="POST">
		<h4>Required for Donor/User</h4>
		<?php
		// be very thorough for now ..
		form_field('firstname', 'First name*', 'text', 'First name');
		form_field('lastname', 'Last name*', 'text', 'Last name');
		form_field('address', 'Address*', 'text', 'Address');
		form_field('address2', 'Address line 2');
		form_field('city', 'City*', 'text', 'City');
		form_field('state', 'State*', 'text', 'State');
		form_field('zip', 'Zip code*', 'number', 'Zip code');
		form_field('phone', 'Phone number*', 'text', '(555) 555-5555');
		?> <hr> <h4>Required for Donee</h4><?php
		form_field('age', 'Age', 'number');
		form_field('gender', 'Gender');
		form_field('ethnicity', 'Ethnicity', 'number');
		form_field('numInHouse', 'Number in household', 'number');
		form_field('income', 'Income', 'number');
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
