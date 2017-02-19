<?php

require_once('../helpers/mysqli.php');
require_once('../helpers/crypto.php');
require_once('../helpers/captcha.php');

// verify csrf token and captcha
verify_csrf_token();
verify_captcha();

// grab and filter inputs
$firstname = mysqli_real_escape_string($mysqli, $_POST['firstname']);
$lastname = mysqli_real_escape_string($mysqli, $_POST['lastname']);
$age = mysqli_real_escape_string($mysqli, $_POST['age']);
$gender = mysqli_real_escape_string($mysqli, $_POST['gender']);
$ethnicity = mysqli_real_escape_string($mysqli, $_POST['ethnicity']);
$numInHouse = mysqli_real_escape_string($mysqli, $_POST['numInHouse']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$address2 = mysqli_real_escape_string($mysqli, $_POST['address2']);
$city = mysqli_real_escape_string($mysqli, $_POST['city']);
$state = mysqli_real_escape_string($mysqli, $_POST['state']);
$zip = mysqli_real_escape_string($mysqli, $_POST['zip']);
$phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
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

// create this user in the database
$query = <<<SQL
INSERT INTO `UserTable`
	(FirstName, LastName, State, City, Zip, AddressLine1, AddressLine2, CumulativeRecValue,
	    Telephone, Email, PassHash, PassSalt, FlagAdmin, FlagUser, FlagDonor, FlagDonee, Age, HouseholdSize, Ethnicity, Gender)
	VALUES
	('$firstname', '$lastname', '$state', '$city', '$zip', '$address', '$address2', 
	    0, '$phone', '$email', '$passwordHash', '$passwordSalt', 0, 1, 0, 0, '$age', '$numInHouse', '$ethnicity', '$gender');
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
