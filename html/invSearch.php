<?php
require_once('helpers/mysqli.php');

$array = array();

if(!empty($_POST['keywords']))
{
	$search = $mysqli->real_escape_string($_POST['keywords']);
	
	$sql = "SELECT * FROM InventoryTable WHERE Name LIKE '%" . $search . "%'";
	if($result = $mysqli->query($sql))
	{
		while($row = $result->fetch_assoc())
		{
			$cateNum = $row['CategoryNum'];
			
			$category = "SELECT * FROM CategoriesTable WHERE CategoryNum=$cateNum";
			
			if($r = $mysqli->query($category))
			{
				$type = $r->fetch_assoc();
				
				$array[] = array('name' => $row['Name'], 'category' => $type['Name']);
			}
		}
	}
}	

echo json_encode($array);
?>