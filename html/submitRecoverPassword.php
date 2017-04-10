<?php

require_once('helpers/mysqli.php');
require_once('helpers/crypto.php');

function bail_out($err)
{
	$uid = $_POST['uid'];
	$token = $_POST['recover_token'];
	header("Location:recoverPassword.php?uid=$uid&token=$token&err=$err");
	exit(1);
}

verify_csrf_token();

if (!isset($_POST['password']) or
	!isset($_POST['passwordConf']) or
	!isset($_POST['uid']) or
	!isset($_POST['recover_token'])) {
	bail_out(2);
}

$password = $_POST['password'];
$passwordConf = $_POST['passwordConf'];
$uid = mysqli_real_escape_string($mysqli, $_POST['uid']);
$recover_token = mysqli_real_escape_string($mysqli, $_POST['recover_token']);

if ($password != $passwordConf)
	bail_out(7);

// verify uid and token are correct
$query = <<<SQL
SELECT UserId FROM UserTable WHERE UserId = '$uid' AND PassSalt = '$recover_token';
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
} else if (!$result->num_rows) {
	bail_out(4);
}

// if a result was returned the uid and token are correct, so we can proceed
$passwordSalt = substr(cs_prng(), 0, 16);
$passwordHash = hash_password($password, $passwordSalt);
$query = <<<SQL
UPDATE UserTable SET PassSalt='$passwordSalt', PassHash='$passwordHash'
	WHERE UserId = '$uid';
SQL;

$result = $mysqli->query($query);
if (!$result) {
	die('MySQL error: ' . $mysqli->error);
}

header('Location:login.php?msg=8');
