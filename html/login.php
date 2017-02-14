<!DOCTYPE html>
<html lang = "en">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('helpers/form.php');

$navbar_active = 'signup';
$navbar_title = 'Donee Signup';
include('layouts/navbar.php');

?>

<div class="container">
	<h3>Login</h3> <br>
	<form class="form-horizontal" action="verify.php" method="POST">
		<input type="text" name="firstname" value="First Name"> <br>
		<input type="text" name="password" value="Password"> <br>
		<input type="submit" value="Log In">
	<hr>
	</form>
</div>

<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>
