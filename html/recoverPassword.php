<!DOCTYPE html>
<html lang = "en">
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('helpers/form.php');
require_once('helpers/captcha.php');

// check if a password reset token was provided in the URL
$token_correct = false;
$token = '';
$user_id = '';

if (isset($_GET['token']) && isset($_GET['uid'])) {
	require_once('helpers/mysqli.php');
	$token = mysqli_real_escape_string($mysqli, $_GET['token']);
	$user_id = mysqli_real_escape_string($mysqli, $_GET['uid']);

	// verify that the token matches the user
	$query = <<<SQL
SELECT UserId FROM UserTable WHERE UserId = '$user_id' AND PassSalt = '$token';
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	} else if (!$result->num_rows) {
		$_GET['err'] = 11;
	} else {
		// at this point, we know that the token matches the user and can
		// proceed
		$token_correct = true;
	}
}

$navbar_title = 'Recover password';
include('layouts/navbar.php');

?>

<div class="container">
	<?php
	include('layouts/message.php');

	$form_action = $token_correct ? 'submitRecoverPassword.php' : 'sendRecoveryEmail.php';
	if (!$token_correct):
		?>
		<h3>Recover password</h3>
		<p>Please enter the email address associated with your account. Instructions
		for recovering your account will be emailed to you.</p>
		<form class="form-horizontal" action="sendRecoveryEmail.php" method="POST">
			<?php
			form_field('email', 'Email address', 'email');
			captcha_field();
			csrf_token_field();
			form_submit_button('Send recovery email');
			?>
		</form>
	<?php else: ?>
		<h3>Update password</h3>
		<p>Please enter a new password for your account.</p>
		<form class="form-horizontal" action="submitRecoverPassword.php" method="POST">
			<?php
			form_field('password', 'New password', 'password', 'Password');
			form_field('passwordConf', 'Confirm new password', 'password', 'Password');
			form_hidden_field('uid', $user_id);
			form_hidden_field('recover_token', $token);
			csrf_token_field();
			form_submit_button('Update password');
			?>
		</form>
	<?php endif; ?>
</div>

</body>
</html>

