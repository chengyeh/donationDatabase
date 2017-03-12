<?php

require_once('../config.php');
require_once('helpers/mysqli.php');
require_once('helpers/crypto.php');
require_once('helpers/captcha.php');

if (!isset($_SESSION['id'])) {
	// if not logged in, redirect to login page
	// this shouldn't happen but it can't hurt to check
	header('Location:' . $config['path_web'] . 'html/login.php?err=401');
	exit();
}
$id = $_SESSION['id'];

// verify csrf token and captcha
verify_csrf_token();
//verify_captcha();

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
$curpassword = $_POST['curpassword'];
$newpassword = $_POST['password'];
$newpasswordconf = $_POST['passwordconf'];

// grab current data for comparison
$oldquery = <<<SQL
SELECT
	FirstName, LastName, State, City, Zip, AddressLine1, AddressLine2,
	Telephone, Email, Age, HouseholdSize, Ethnicity, Gender, PassSalt, PassHash
FROM `UserTable`
WHERE UserID=$id;
SQL;

$result = $mysqli->query($oldquery);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}
$oldrow = $result->fetch_assoc();

// verify password if necessary
if ($newpassword != '' || $oldrow['Email'] != $email) {
	$salt = $oldrow['PassSalt'];
	$hash = $oldrow['PassHash'];
	if (hash_password($curpassword, $salt) != $hash) {
		header('Location:profile.php?err=9');
		exit;
	}
}

// DYNAMIC MYSQL! LET'S DO IT!
$newquery_arr = [
	'FirstName' => "'$firstname'",
	'LastName' => "'$lastname'",
	'State' => "'$state'",
	'City' => "'$city'",
	'Zip' => "'$zip'",
	'AddressLine1' => "'$address'",
	'AddressLine2' => "'$address2'",
	'Telephone' => "'$phone'",
	'Age' => "'$age'",
	'HouseholdSize' => "'$numInHouse'",
	'Ethnicity' => "'$ethnicity'",
	'Gender' => "'$gender'"
];

if ($newpassword != '') {
	if ($newpassword != $newpasswordconf) {
		header('Location:profile.php?err=7');
		exit;
	}
	// best practice is to regenerate the salt
	$newsalt = substr(cs_prng(), 0, 16);
	$newquery_arr['PassSalt'] = "'$newsalt'";
	$newquery_arr['PassHash'] = "'".hash_password($newpassword, $newsalt)."'";
}

if ($email != $oldrow['Email']) {
	// make sure nobody has registered with this email yet
	$query = <<<SQL
SELECT Email from UserTable WHERE Email='$email';
SQL;

	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	} else if ($result->num_rows > 0) {
		$err = '&err=3';
	} else {
		$newquery_arr['Email'] = "'$email'";
	}
}

// build the new query
$newquery = 'UPDATE UserTable SET ';
foreach ($newquery_arr as $key => $value) {
	$newquery .= "$key=$value, ";
}
// cut off trailing comma and space
$newquery = substr($newquery, 0, strlen($newquery) - 2);
$newquery .= " WHERE UserId=$id;";

$result = $mysqli->query($newquery);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

switch($dest) {
	case 'donor':
		header("Location:donor.php?msg=2$err");
		break;
	case 'donee':
		header("Location:donee.php?msg=2$err");
		break;
	default:
		header("Location:profile.php?msg=2$err");
		break;
}
?>
