<!DOCTYPE html>
<html lang = "en">

<?php
require_once('../config.php');
require_once('helpers/crypto.php');

if (!isset($_SESSION['id'])) {
	// if not logged in, redirect to login page
	// this shouldn't happen but it can't hurt to check
	header('Location:login.php?err=401');
	exit;
}
$id = $_SESSION['id'];

require_once('helpers/mysqli.php');
require_once('helpers/form.php');

//$navbar_active = '';
$navbar_title = 'Edit profile';
include('layouts/navbar.php');

// grab current info to prepopulate form
$query = <<<SQL
SELECT
	FirstName, LastName, State, City, Zip, AddressLine1, AddressLine2,
	Telephone, Email, Age, HouseholdSize, Ethnicity, Gender
FROM `UserTable`
WHERE UserID=$id;
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

$row = $result->fetch_assoc();

$firstname = $row['FirstName'];
$lastname = $row['LastName'];
$age = $row['Age'];
$gender = $row['Gender'];
$ethnicity = $row['Ethnicity'];
$numInHouse = $row['HouseholdSize'];
$address = $row['AddressLine1'];
$address2 = $row['AddressLine2'];
$city = $row['City'];
$state = $row['State'];
$zip = $row['Zip'];
$phone = $row['Telephone'];
$email = $row['Email'];

$firstnameclass = '';
$lastnameclass = '';
$ageclass = '';
$genderclass = '';
$ethnicityclass = '';
$numInHouseclass = '';
$addressclass = '';
$cityclass = '';
$stateclass = '';
$zipclass = '';
$phoneclass = '';
// email is required for signup, so we won't need to highlight it

?>

<div class="container">
	<?php
	include('layouts/message.php');
	$errorClasses = 'alert-danger';
	if ($errorCode == 6) { // need donee information
		$firstnameclass = $firstname ? '' : $errorClasses;
		$lastnameclass = $lastname ? '' : $errorClasses;
		$ageclass = $age ? '' : $errorClasses;
		$genderclass = $gender ? '' : $errorClasses;
		$ethnicityclass = $ethnicity ? '' : $errorClasses;
		$numInHouseclass = $numInHouse ? '' : $errorClasses;
		$addressclass = $address ? '' : $errorClasses;
		$cityclass = $city ? '' : $errorClasses;
		$stateclass = $state ? '' : $errorClasses;
		$zipclass = $zip ? '' : $errorClasses;
		$phoneclass = $phone ? '' : $errorClasses;
		// income
	} else if ($errorCode == 5) { // need donor information
		$firstnameclass = $firstname ? '' : $errorClasses;
		$lastnameclass = $lastname ? '' : $errorClasses;
		$addressclass = $address ? '' : $errorClasses;
		$cityclass = $city ? '' : $errorClasses;
		$stateclass = $state ? '' : $errorClasses;
		$zipclass = $zip ? '' : $errorClasses;
		$phoneclass = $phone ? '' : $errorClasses;
	}
	?>
	<h3>Edit profile</h3>
	<p>Changing a field marked with an asterisk (*) will require you to reenter your password.</p>
	<form class="form-horizontal" action="submitProfile.php" method="POST">
		<?php
		form_field('firstname', 'First name', 'text', '', $firstname, $firstnameclass);
		form_field('lastname', 'Last name', 'text', '', $lastname, $lastnameclass);
		form_field('age', 'Age', 'number', '', $age, $ageclass);
		form_field('gender', 'Gender', 'text', '', $gender, $genderclass);
		form_field('ethnicity', 'Ethnicity', 'number', '', $ethnicity, $ethnicityclass);
		form_field('numInHouse', 'Number in household', 'number', '', $numInHouse, $numInHouseclass);
		form_field('address', 'Address', 'text', '', $address, $addressclass);
		form_field('address2', 'Address line 2', 'text', '', $address2);
		form_field('city', 'City', 'text', '', $city, $cityclass);
		form_field('state', 'State', 'text', '', $state, $stateclass);
		form_field('zip', 'Zip code', 'number', '', $zip, $zipclass);
		form_field('phone', 'Phone number', 'text', '(555) 555-555', $phone, $phoneclass);
		?> <hr> <?php
		form_field('email', 'Email*', 'email', '', $email);
		form_field('password', 'New password', 'password', '(No change)');
		form_field('passwordconf', 'Confirm new password', 'password', '(No change)');
		?> <hr> <?php
		form_field('curpassword', 'Current password', 'password');
		// captcha_field(true);
		csrf_token_field();
		form_submit_button('Submit changes');
		?>
	</form>
</div>
