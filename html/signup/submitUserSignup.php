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
	header('Location:userSignup.php?err=3');
	exit;
}
else if ($firstname == '' || $lastname == '' || $address == '' || $city == '' || $state == '') {
	header('Location:userSignup.php?err=2');
	exit;
}

// make sure nobody has registered with this email yet
$query = <<<SQL
SELECT Email from UserTable WHERE Email='$email';
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
} else if ($result->num_rows > 0) {
	header('Location:userSignup.php?err=3');
	exit;
}

// grab passwords, compare, and hash
$password = $_POST['password'];
$passwordconf = $_POST['passwordconf'];

if ($password !== $passwordconf) {
	header('Location:userSignup.php?err=7');
	exit;
}

$passwordSalt = substr(cs_prng(), 0, 16);
$passwordHash = hash_password($password, $passwordSalt);

//send account verification email
if($config['use_email_verification']){
	$contact_us = $config['contact_us_email'];
	$path_web = $config['path_web'] . 'html';
	$nonprofit_name = $config['nonprofit_name'];
	$subject = $nonprofit_name . ' account verification';
	$to = $email;
	$body = <<<HTM
Hello $firstname,<br /><br />
Thank you for signing up with $nonprofit_name!<br />
Please use the following link to activate your account. <br /><br />
<a href="$path_web/verifyEmail.php?email=$email&hash=$passwordSalt">
$path_web/verifyEmail.php?email=$email&hash=$passwordSalt</a><br /><br />
<b>Note:</b> This message was sent from an unmonitored address.<br />
Please do no respond to this message.<br />
To contact us, please email <a href="mailto:$contact_us">$contact_us</a>.
HTM;
	require_once('../helpers/mail.php');
}

// create this user in the database
$donor_flag = $firstname && $lastname && $address && $city && $state && $zip &&
		$phone;

$donee_flag = $donor_flag && $gender && $ethnicity && $numInHouse && $age &&
		$income;

$active = !$config['use_email_verification'];

$query = <<<SQL
INSERT INTO `UserTable`
	(FirstName, LastName, State, City, Zip, AddressLine1, AddressLine2,
	CumulativeRecValue, Telephone, Email, PassHash, PassSalt, FlagAdmin,
	FlagUser, FlagDonor, FlagDonee, Active, Age, HouseholdSize, Ethnicity,
	Gender, Income)
	VALUES
	('$firstname', '$lastname', '$state', '$city', '$zip', '$address',
	'$address2', 0, '$phone', '$email', '$passwordHash', '$passwordSalt',
	False, False, '$donor_flag', '$donee_flag', '$active', '$age',
	'$numInHouse', '$ethnicity', '$gender', '$income');
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

header('Location:../index.php?msg=3');
?>

