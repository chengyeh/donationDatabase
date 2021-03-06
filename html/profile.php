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
	Telephone, Email, Age, HouseholdSize, Ethnicity, Gender, Income
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
$income = $row['Income'];
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
$incomeclass = '';
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
	$successClasses = ''; // 'alert-success';
	$errorClasses = 'alert-danger';
	if ($errorCode == 6) { // need donee information
		$firstnameclass = $firstname ? $successClasses : $errorClasses;
		$lastnameclass = $lastname ? $successClasses : $errorClasses;
		$ageclass = $age ? $successClasses : $errorClasses;
		$genderclass = $gender ? $successClasses : $errorClasses;
		$ethnicityclass = $ethnicity ? $successClasses : $errorClasses;
		$numInHouseclass = $numInHouse ? $successClasses : $errorClasses;
		$addressclass = $address ? $successClasses : $errorClasses;
		$cityclass = $city ? $successClasses : $errorClasses;
		$stateclass = $state ? $successClasses : $errorClasses;
		$zipclass = $zip ? $successClasses : $errorClasses;
		$phoneclass = $phone ? $successClasses : $errorClasses;
		$incomeclass = $income ? $successClasses : $errorClasses;
	} else if ($errorCode == 5) { // need donor information
		$firstnameclass = $firstname ? $successClasses : $errorClasses;
		$lastnameclass = $lastname ? $successClasses : $errorClasses;
		$addressclass = $address ? $successClasses : $errorClasses;
		$cityclass = $city ? $successClasses : $errorClasses;
		$stateclass = $state ? $successClasses : $errorClasses;
		$zipclass = $zip ? $successClasses : $errorClasses;
		$phoneclass = $phone ? $successClasses : $errorClasses;
	}
	?>
	<h3>Edit profile</h3>
	<p>Changing a field marked with an asterisk (*) will require you to reenter your password.</p>
	<form class="form-horizontal" action="submitProfile.php" method="POST">
		<?php
		form_field('firstname', 'First name', 'text', '', $firstname, $firstnameclass);
		form_field('lastname', 'Last name', 'text', '', $lastname, $lastnameclass);
		form_number_field('age', 'Age', 'Age', 127, 13, $age, $ageclass);
		form_gender_field(false, $gender, $genderclass);
		form_ethnicity_field(false, $ethnicity, $ethnicityclass);
		form_number_field('numInHouse', 'Number in household', 'Number in household', 127, 1, $numInHouse, $numInHouseclass);
		form_number_field('income', 'Income', 'Income', PHP_INT_MAX, 0, $income, $incomeclass);
		form_field('address', 'Address', 'text', '', $address, $addressclass);
		form_field('address2', 'Address line 2', 'text', '', $address2);
		form_field('city', 'City', 'text', '', $city, $cityclass);
		form_field('state', 'State', 'text', '', $state, $stateclass);
		form_number_field('zip', 'Zip code', 'Zip code', 99999, 10000, $zip, $zipclass);
		form_field('phone', 'Phone number', 'text', '(555) 555-555', $phone, $phoneclass);
		?> <hr> <?php
		form_field('email', 'Email*', 'email', '', $email);
		form_field('password', 'New password', 'password', '(No change)');
		form_field('passwordconf', 'Confirm new password', 'password', '(No change)');
		?> <hr>
		<p>If you are changing your email or password, you must enter your current password.</p>
		<?php
		form_field('curpassword', 'Current password', 'password');
		// captcha_field(true);
		csrf_token_field();
		form_submit_button('Submit changes');
		?>
	</form>
</div>

<script type="text/javascript" src="jquery.maskedinput.min.js"></script>
<script type="text/javascript">
	$("#phone").mask("(999) 999-9999");
</script>
</html>
