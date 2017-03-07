<?php

require_once('../helpers/mysqli.php');
require_once('../helpers/crypto.php');
require_once('../helpers/captcha.php');
require_once('../../config.php');

// verify csrf token and captcha
verify_csrf_token();
verify_captcha();

// grab and filter inputs
$firstname = mysqli_real_escape_string($mysqli, $_POST['firstname']);
$lastname = mysqli_real_escape_string($mysqli, $_POST['lastname']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$address2 = mysqli_real_escape_string($mysqli, $_POST['address2']);
$city = mysqli_real_escape_string($mysqli, $_POST['city']);
$state = mysqli_real_escape_string($mysqli, $_POST['state']);
$zip = mysqli_real_escape_string($mysqli, $_POST['zip']);
$phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
$age = mysqli_real_escape_string($mysqli, $_POST['age']);
$gender = mysqli_real_escape_string($mysqli, $_POST['gender']);
$ethnicity = mysqli_real_escape_string($mysqli, $_POST['ethnicity']);
$numInHouse = mysqli_real_escape_string($mysqli, $_POST['numInHouse']);
$income = mysqli_real_escape_string($mysqli, $_POST['income']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);

if ($email === '') {
	http_response_code(400);
	die('Error 400: Bad request (no email address provided).');
}

// make sure nobody has registered with this email yet
$query = <<<SQL
SELECT Email from UserTable WHERE Email='$email';
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
} else if ($result->num_rows > 0) {
	http_response_code(400);
	die('Error 400: A user with this email address already exists.');
}

// grab passwords, compare, and hash
// TODO: check whether sending passwords from client->server in plaintext is bad
$password = $_POST['password'];
$passwordconf = $_POST['passwordconf'];

if ($password !== $passwordconf) {
	http_response_code(400);
	die('Error 400: Bad request (passwords don\'t match).');
}

$passwordSalt = substr(cs_prng(), 0, 16);
$passwordHash = hash_password($password, $passwordSalt);

//send account verification email
if($config['use_email_verification']){
	$subject = '' . $config['nonprofit_name'] . ' account verification';
	$to = $email;
	$body = 'Hello ' . $firstname . ', <br><br>' .
	'Thank you for signing up with ' . $config['nonprofit_name'] . '!<br>' . 
	'Please use the following link to activate your account. <br><br>' .
	'<a href="' . $config['path_web'] . 'html/verifyEmail.php?email=' .
	$email . '&hash=' . $passwordHash .'">' . $config['path_web'] . 
	'html/verifyEmail.php?email=' . $email . '&hash=' . $passwordHash . '</a>' .
	'<br><br>' .
	'<b>Note:</b> This message was sent from an unmonitored address.<br>' .
	'Please do no respond to this message. <br>' .
	'To contact us, please email ' . 
	'<a href="mailto:' . $config['contact_us_email'] . '">' . 
	$config['contact_us_email'] . '</a>';
	require_once('../helpers/mail.php');
}

// create this user in the database
$query = <<<SQL
INSERT INTO `UserTable`
	(FirstName, LastName, State, City, Zip, AddressLine1, AddressLine2, CumulativeRecValue,
	    Telephone, Email, PassHash, PassSalt, FlagAdmin, FlagUser, FlagDonor, FlagDonee, Age, HouseholdSize, Ethnicity, Gender, Income)
	VALUES
	('$firstname', '$lastname', '$state', '$city', '$zip', '$address', '$address2', 
	    0, '$phone', '$email', '$passwordHash', '$passwordSalt', 0, 1, 0, 0, '$age', '$numInHouse', '$ethnicity', '$gender', '$income');
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

?>



<p>Congratulations! You have registered successfully.</p>
<p>Your email address: <?= $email ?></p>
<p>Your password salt: <?= $passwordSalt ?></p>
<p>Your password hash: <?= $passwordHash ?></p>
