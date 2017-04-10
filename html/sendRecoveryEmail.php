<?php
require_once('../config.php');
require_once('helpers/captcha.php');
require_once('helpers/crypto.php');
require_once('helpers/mysqli.php');

function bail_out($err)
{
	header("Location:recoverPassword.php?err=$err");
	exit(1);
}

verify_captcha();
verify_csrf_token();

if (!isset($_POST['email']))
	bail_out(2);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);

// make sure a user with this email exists
$query = <<<SQL
SELECT FirstName, UserId, PassSalt FROM UserTable WHERE Email = '$email';
SQL;
$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
} else if (!$result->num_rows) {
	bail_out(1);
}
$row = $result->fetch_assoc();

$firstname = $row['FirstName'];
$uid = $row['UserId'];
$token = $row['PassSalt'];
$nonprofit_name = $config['nonprofit_name'];
$path_web = $config['path_web'] . 'html';
$contact_us = $config['contact_us_email'];
$url = "$path_web/recoverPassword.php?uid=$uid&token=$token";

$to = $email;
$subject = $nonprofit_name . ' account recovery';
$body = <<<HTM
Hello $firstname,<br /><br />
Thank you for signing up with $nonprofit_name!<br />
Please use the following link to activate your account.<br /><br />
<a href="$url">$url</a><br /><br />
<b>Note:</b> This message was sent from an unmonitored address.<br />
Please do no respond to this message.<br />
To contact us, please email <a href="mailto:$contact_us">$contact_us</a>.
HTM;

require('helpers/mail.php');
