<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$itemName = $_GET["itemname"];
$itemQuantity = $_GET["itemquantity"];
$itemValue = $_GET["itemvalue"];
$reference = $_GET["reference"];
$donorId = $_GET["donorid"];
$firstName = $_GET["firstname"];
$lastName = $_GET["lastname"];
$address = $_GET["address"];
$phone = $_GET["phone"];
$email = $_GET["email"];

 //NOT SURE WHOSE EECS AACOUNT WE"RE GOING TO USE FOR HOSTING THE DATABASES SO I LEFT ALL RELEVANT FIELD EMPTY FOR THE TIME BEING
$mysqli = new mysqli("mysql.eecs.ku.edu", "rriedel", "Password123!", "rriedel");
if ($mysqli->connect_errno)
{
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}

$updateDate = "UPDATE dd_indonation SET date_completed=NOW() WHERE id='$reference'";

if($result = $mysqli->query($updateDate))
{
}

$updateValue = "UPDATE dd_indonation SET value='$itemValue' WHERE id='$reference'";

if($result = $mysqli->query($updateValue))
{
}

$updateReceived = "UPDATE dd_indonation SET amount_received='$itemQuantity' WHERE id='$reference'";

if($result = $mysqli->query($updateReceived))
{
}

$updateItem = "UPDATE dd_inventory SET amount=amount+'$itemQuantity' WHERE id='$itemName'";

if($result = $mysqli->query($updateItem))
{
}

$mysqli->close();

header("Location:https://people.eecs.ku.edu/~mbechtel/donationDatabase/html/user.html");
?>