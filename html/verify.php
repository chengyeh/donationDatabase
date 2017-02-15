<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include('layouts/navbar.php');
include('helpers/mysqli.php');

session_start();

$query = "SELECT id, first_name, password FROM dd_donor";

if($result = $mysqli->query($query))
{
	while($row = $result->fetch_assoc())
	{
		if($_POST["firstname"] == $row["first_name"] && $_POST["password"] == $row["password"])
		{
			$_SESSION["id"] = $row["id"];
		}
	}
}

header("Location:https://people.eecs.ku.edu/~mbechtel/donationDatabase/html/index.php");
?>