<?php
/* helpers/mysqli.php
 * initializes a connection to a mysql database using PHP's built-in mysqli
 * objects.
 */

require_once(__DIR__.'/../../config.php');

$mysql_addr = $config['mysql_addr'];
$mysql_user = $config['mysql_user'];
$mysql_pass = $config['mysql_pass'];
$mysql_db = $config['mysql_db'];

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

