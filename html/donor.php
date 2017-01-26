<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$item = $_GET["request"];
$amount = $_GET["first"];

 //NOT SURE WHOSE EECS AACOUNT WE"RE GOING TO USE FOR HOSTING THE DATABASES SO I LEFT ALL RELEVANT FIELD EMPTY FOR THE TIME BEING
$mysqli = new mysqli("mysql.eecs.ku.edu", "rriedel", "Password123!", "rriedel");
if ($mysqli->connect_errno)
{
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}

$updateAmount = "INSERT INTO dd_indonation (amount, date_pledged) VALUES ('$amount', NOW())";

if($r = $mysqli->query($updateAmount))
{
	
}

$mysqli->close();

header("Location:https://people.eecs.ku.edu/~mbechtel/donationDatabase/html/doner.html");
?>