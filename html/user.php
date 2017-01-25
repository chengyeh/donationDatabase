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
$company = $_GET["company"];
$address = $_GET["address"];
$phone = $_GET["zip"];
$email = $_GET["email"];

 //NOT SURE WHOSE EECS AACOUNT WE"RE GOING TO USE FOR HOSTING THE DATABASES SO I LEFT ALL RELEVANT FIELD EMPTY FOR THE TIME BEING
$mysqli = new mysqli("mysql.eecs.ku.edu", "rriedel", "Password123!", "rriedel");
if ($mysqli->connect_errno)
{
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}

$date = CURDATE() + 0;

$updateDate = "UPDATE Incoming Donations SET Date='$date' WHERE Reference_ID='$reference'";

if($result = $mysqli->query($updateDate))
{
}

$updateItem = "UPDATE Inventory SET Quantity=Quantity+'$itemQuantity' WHERE Donor_ID='$itemId'";

if($result = $mysqli->query($updateItem))
{
}

$mysqli->close();
?>