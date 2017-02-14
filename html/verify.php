<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

 //NOT SURE WHOSE EECS AACOUNT WE"RE GOING TO USE FOR HOSTING THE DATABASES SO I LEFT ALL RELEVANT FIELD EMPTY FOR THE TIME BEING
$mysqli = new mysqli("mysql.eecs.ku.edu", "rriedel", "TJueFeMFESrEHV8S", "rriedel");
if ($mysqli->connect_errno)
{
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}

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

if(isset($_SESSION["id"]))
{
	echo $_SESSION["id"];
}
else
{
	echo "FAIL";
}

session_destroy();
?>