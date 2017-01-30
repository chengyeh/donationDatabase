<?php

require_once('../helpers/form.php');

?>
<!DOCTYPE html>
<html lang = "en">
<?php

$navbar_active = 'signup';
$navbar_title = 'User Signup Page';
include('../layouts/navbar.php');

?>

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
