<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('helpers/mysqli.php');
require_once('helpers/crypto.php');

// session started in crypto.php
// session_start();

$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$inputPassword = $_POST['password'];

$query = <<<SQL
SELECT UserId, passwordSalt, passwordHash FROM Users WHERE Email = '$email';
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
} else if ($result->num_rows == 0) {
	die('No such user!');
}

$row = $result->fetch_assoc();
$userId = $row['UserId'];
$passwordSalt = $row['passwordSalt'];
$passwordHash = $row['passwordHash'];

if (hash_password($inputPassword, $passwordSalt) === $passwordHash) {
	$_SESSION["id"] = $userId;
	?>
	<p>Welcome. Your session ID is now <?= $userId ?>.</p>
	<?php
} else {
	die('Incorrect password!');
}

/*
$redirect_url = $config['path_web'] . 'html/index.php';
header("Location:$redirect_url");
*/
?>
