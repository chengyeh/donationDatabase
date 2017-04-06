<!DOCTYPE html>
<html lang = "en">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('helpers/form.php');
require_once('helpers/crypto.php');
require_once('helpers/captcha.php');

$navbar_active = 'login';
$navbar_title = 'Log in';
include('layouts/navbar.php');

?>

<div class="container">
	<?php
	include('layouts/message.php');

	$dest = isset($_GET['dest']) ? htmlspecialchars($_GET['dest']) : '';
	?>
	<h3>Login</h3>
	<p>If you don't have an account, sign up <a href="signup/userSignup.php">here</a>.
	If you've forgotten your password, click <a href="recoverPassword.php">here</a>.</p>
	<form class="form-horizontal" action="verify.php" method="POST">
		<?php
		form_field('email', 'Email address', 'email');
		form_field('password', 'Password', 'password');
		captcha_field();
		csrf_token_field();
		?>
		<input type="hidden" name="dest" value="<?= $dest ?>">
		<?php
		form_submit_button('Log in');
		?>
	<hr>
	</form>
</div>

</body>
</html>
