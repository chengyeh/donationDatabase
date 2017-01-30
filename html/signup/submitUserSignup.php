<?php

require_once('../helpers/mysqli.php');
require_once('../helpers/csrf.php');

// verify csrf token
verify_csrf_token();

// grab and filter inputs
$firstname = mysqli_real_escape_string($mysqli, $_POST['firstname']);
$lastname = mysqli_real_escape_string($mysqli, $_POST['lastname']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$city = mysqli_real_escape_string($mysqli, $_POST['city']);
$state = mysqli_real_escape_string($mysqli, $_POST['state']);
$zip = mysqli_real_escape_string($mysqli, $_POST['zip']);
$phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
// TODO: salt these
$password = hash('sha256', $_POST['password']);
$passwordconf = hash('sha256', $_POST['passwordconf']);

if ($password !== $passwordconf) {
	// TODO: this should be verified with javascript (instead?)
	http_response_code();
	die('Error 400: Bad request');
}

// TODO: run checks to see if a user with this email address exists

// create this user in the database
$query = <<<SQL
INSERT INTO `Users`
	(FirstName, LastName, Telephone, Email, Address, City, State, Zip, Password)
	VALUES
	('$firstname', '$lastname', '$phone', '$email', '$address', '$city', '$state', '$zip', '$password');
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

?>

user created.
