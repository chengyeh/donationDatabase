<?php
/* helpers/mysqli.php
 * initializes a connection to a mysql database using PHP's built-in mysqli
 * objects.
 */

// extend this with your development environment!
if ($_SERVER["MYSQL_HOME"] == "\\xampp\\mysql\\bin") {
	// xampp environment
	$mysql_addr = 'localhost';
	$mysql_user = 'root';
	$mysql_pass = '';
	$mysql_db = 'donation';
} else {
	// eecs server environment
	$mysql_addr = 'mysql.eecs.ku.edu';
	$mysql_user = 'rriedel';
	$mysql_pass = 'Password123!';
	$mysql_db = 'rriedel';
}

$mysqli = new mysqli($mysql_addr, $mysql_user, $mysql_pass, $mysql_db);
if ($mysqli->connect_errno)
{
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}

/*
 * NB: the mysqli connection will automatically close when PHP finishes
 * execution. explicitly closing it is not required.
 * After this point the $mysqli object created here can be used freely.
 */

