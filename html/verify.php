<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('helpers/mysqli.php');
require_once('helpers/crypto.php');
require_once('helpers/captcha.php');

verify_csrf_token();
verify_captcha();

$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$inputPassword = $_POST['password'];
$dest = htmlspecialchars($_POST['dest']);

$query = <<<SQL
SELECT
	UserId, FirstName, PassSalt, PassHash, FlagAdmin, FlagUser, FlagDonor,
	FlagDonee
FROM UserTable WHERE Email = '$email';
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
} else if ($result->num_rows == 0) {
	header('Location:login.php?err=1');
	exit;
}

$row = $result->fetch_assoc();
$passwordSalt = $row['PassSalt'];
$passwordHash = $row['PassHash'];

if (hash_password($inputPassword, $passwordSalt) === $passwordHash) {
	$_SESSION['id'] = $row['UserId'];
	$_SESSION['name'] = $row['FirstName'];
	$_SESSION['admin'] = $row['FlagAdmin'];
	$_SESSION['user'] = $row['FlagUser'];
	$_SESSION['donor'] = $row['FlagDonor'];
	$_SESSION['donee'] = $row['FlagDonee'];
} else {
	header("Location:login.php?err=1&dest=$dest");
	exit;
}

switch($dest) {
	case 'donor':
		header("Location:donor.php?msg=4");
		break;
	case 'donee':
		header("Location:donee.php?msg=4");
		break;
	default:
		header("Location:index.php?msg=4");
		break;
}

?>
