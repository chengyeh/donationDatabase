<!DOCTYPE html>
<html lang = "en">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('helpers/form.php');

$navbar_active = 'login';
$navbar_title = 'Login';
include('layouts/navbar.php');

?>

<div class="container">
	<h3>Login</h3> <br>
	<form class="form-horizontal" action="verify.php" method="POST">
		<?php
		form_field('email', 'Email address', 'email');
		form_field('password', 'Password', 'password');
		?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button class="btn btn-default">Log in</button>
			</div>
		</div>
	<hr>
	</form>
</div>

<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>