<?php

require_once('../helpers/mysqli.php');
require_once('../helpers/crypto.php');

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

// grab passwords, compare, and hash
$password = $_POST['password'];
$passwordconf = $_POST['passwordconf'];

if ($password !== $passwordconf) {
	http_response_code(400);
	die('Error 400: Bad request');
}

// TODO: salt this
$password = hash('sha256', $password);

// TODO: run checks to see if a user with this email address exists

// create this user in the database
$query = <<<SQL
INSERT INTO `donortable`
	(FirstName, LastName, Telephone, Email, Address, City, State, Zip, Password)
	VALUES
	('$firstname', '$lastname', '$phone', '$email', '$address', '$city', '$state', '$zip', '$password');
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

?>

donor created.

