<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('../helpers/mysqli.php');

date_default_timezone_set($config['time_zone']);
$date = new DateTime();
$fdate = $date->format('Y-m-d H:i:s');

$sql = "SELECT * FROM InventoryTable";
$result_set = $mysqli->query($sql);
$inventory_array = array();
while($row =  mysqli_fetch_array($result_set)){
	if($row['Amount'] > 0)
	{
		$inventory_array[] = $row;
	}
}

foreach($inventory_array as $index => $item)
{
	$itemID = $item['ItemID'];
	$itemName = $item['Name'];
	echo $itemName . " ID:" . $itemID . "<br>";	
	
	$sql = "SELECT * FROM OutDonationTable WHERE ItemID=$itemID";
	$result_set = $mysqli->query($sql);
	$request_array = array();
	$time_array = array();
	while($row =  mysqli_fetch_array($result_set))
	{
		if($row['Amount'] != $row['AmountGranted'])
		{
			$request_array[] = $row;
			$time_array[] = $row['RequestDate'];
		}
	}
	array_multisort($time_array, SORT_ASC, $request_array);

	$amountLeft = $item['Amount'];
	
	foreach($request_array as $index => $request)
	{		
		$requestAmount = $request['Amount'] - $request['AmountGranted'];
		
		echo "---->" . $request['DoneeID'] . " requested " . $requestAmount;
		
		if($requestAmount <= $amountLeft || $amountLeft != 0)
		{			
			if($requestAmount < $amountLeft)
			{
				$amount = $requestAmount;
				
				echo " ...  FULFILLED <br>";
			}
			else
			{
				$amount = $amountLeft;
				
				echo " ...  PARTIALLY FULFILLED <br>";
			}
			
			$amountLeft = $amountLeft - $amount;
			$query = "UPDATE InventoryTable SET Amount=$amountLeft WHERE ItemID=$itemID";
			if($result = $mysqli->query($query))
			{
			}
			else
			{
				die("MySQL error: " . $mysqli->error);
			}
			
			$refNum = $request['RefNum'];
			$query2 = "UPDATE OutDonationTable SET AmountGranted=AmountGranted+$amount, FulfillDate='$fdate' WHERE RefNum='$refNum'";
			if($result = $mysqli->query($query2))
			{
			}
			else
			{
				die("MySQL error: " . $mysqli->error);
			}
		}
		else
		{
			echo " ...  NOT FULFILLED <br>"; 
		}
	}
}
?>