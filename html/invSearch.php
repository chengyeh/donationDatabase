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
				
				$need = $row['Threshold'] - $row['Amount'];
				
				if($need < 0)
				{
					$need = 0;
				}
				
				$array[] = array('name' => $row['Name'], 'category' => $type['Name'], 'need' => $need, 'instock' => $row[Amount], 'id' => $row['ItemID'], 'catNum' => $type['CategoryNum']);
			}
		}
	}
}	

echo json_encode($array);
?>