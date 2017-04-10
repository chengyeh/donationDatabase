<?php
//generates a pdf of database metrics for the grant writer
require_once(__DIR__ . '/../helpers/mysqli.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/fpdf/fpdf.php');

//set time zone to get proper time back
date_default_timezone_set($config['time_zone']);
$date = date('m/d/Y h:i:s a', time());

//variables for metrics
$numTotal = 0;
$numDonors = 0;
$numDonees = 0;
$numMale = 0;	//genders
$numFemale = 0;
$numSub18 = 0;	//age ranges
$numSub25 = 0;
$numSub35 = 0;
$numSub45 = 0;
$numGrt45 = 0;
$numEth0 = 0;	//ethnicity categories
$numEth1 = 0;
$numEth2 = 0;
$numEth3 = 0;
$numEth4 = 0;
$numIncSub5k = 0;	//income categories
$numIncSub15k = 0;
$numIncSub30k = 0;
$numIncGrt30k = 0;
$totalDonationValue = 0;	//cumulative donation value
$zipcodes = array();	//array to hold zipcodes for each donee

//query the users table
$sql = "SELECT * FROM UserTable";
$result = $mysqli->query($sql);
while($row =  mysqli_fetch_array($result))
{
	//collect data on donors and donees
	if(($row['FlagDonor'] || $row['FlagDonee']) && $row['Active'])
	{	
		//increment the total number of donors and donees
		$numTotal++;

		//get number of donors and donees
		if($row['FlagDonor'])
			$numDonors++;
		if($row['FlagDonee'])
		{
			$numDonees++;
			$zipcodes[] = $row['Zip'];
		}

		//get the genders of users
		if($row['Gender'] == 'M')
			$numMale++;
		elseif($row['Gender'] == 'F')
			$numFemale++;

		//categorize the age of each user
		if($row['Age'] < 18)
			$numSub18++;
		elseif($row['Age'] < 25)
			$numSub25++;
		elseif($row['Age'] < 35)
			$numSub35++;
		elseif($row['Age'] < 45)
			$numSub45++;
		elseif($row['Age'] >= 45)
			$numGrt45++;

		//categorize ethnicity
		if($row['Ethnicity'] == 0)
			$numEth0++;
		elseif($row['Ethnicity'] == 1)
			$numEth1++;
		elseif($row['Ethnicity'] == 2)
			$numEth2++;
		elseif($row['Ethnicity'] == 3)
			$numEth3++;
		elseif($row['Ethnicity'] == 4)
			$numEth4++;

		//categorize income
		if($row['Income'] < 5000)
			$numIncSub5k++;
		elseif($row['Income'] < 15000)
			$numIncSub15k++;
		elseif($row['Income'] < 30000)
			$numIncSub30k++;
		elseif($row['Income'] >= 30000)
			$numIncGrt30k++;
	}
}

//aggregate the zipcodes array
$zipCount = (array_count_values($zipcodes));

//query the incoming donation table
$sql = "SELECT * FROM IncDonationTable";
$result = $mysqli->query($sql);
while($row =  mysqli_fetch_array($result))
{	
	//get the total cumulative value of donations estimated by employees
	$totalDonationValue += $row['Value'];
}

//create a pdf and set the title and date/time of generation
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',20);
$pdf->Cell(40,10,"Grant Metrics Report");
$pdf->Ln();
$pdf->SetFont('Arial','B',11);
$pdf->Cell(40,8,$date);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Ln();

//value of donations
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"Total Value of Donations (Estimated by Employees)",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(80,8,"$".$totalDonationValue,1,0,'L',0);
$pdf->Ln();
$pdf->Ln();

//donors and donees statistics
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"User Statistics",1,0,'L',0);
$pdf->Cell(40,8,"Number of Users",1,0,'L',0);
$pdf->Cell(40,8,"Percent of Users",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(100,8,"Total number of donors and donees",1,0,'L',0);
$pdf->Cell(40,8,$numTotal,1,0,'L',0);
$pdf->Cell(40,8,"",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Users that are donors",1,0,'L',0);
$pdf->Cell(40,8,$numDonors,1,0,'L',0);
$pdf->Cell(40,8,(($numDonors/$numTotal)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Users that are donees",1,0,'L',0);
$pdf->Cell(40,8,$numDonees,1,0,'L',0);
$pdf->Cell(40,8,(($numDonees/$numTotal)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Ln();

//donee gender statistics
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"Donee Gender Statistics",1,0,'L',0);
$pdf->Cell(40,8,"Number of Donees",1,0,'L',0);
$pdf->Cell(40,8,"Percent of Donees",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(100,8,"Number of male donees",1,0,'L',0);
$pdf->Cell(40,8,$numMale,1,0,'L',0);
$pdf->Cell(40,8,(($numMale/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Number of female donees",1,0,'L',0);
$pdf->Cell(40,8,$numFemale,1,0,'L',0);
$pdf->Cell(40,8,(($numFemale/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Ln();

//donee age statistics
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"Donee Age Statistics",1,0,'L',0);
$pdf->Cell(40,8,"Number of Donees",1,0,'L',0);
$pdf->Cell(40,8,"Percent of Donees ",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(100,8,"Age < 18 years old",1,0,'L',0);
$pdf->Cell(40,8,$numSub18,1,0,'L',0);
$pdf->Cell(40,8,(($numSub18/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Age 18-24 years old",1,0,'L',0);
$pdf->Cell(40,8,$numSub25,1,0,'L',0);
$pdf->Cell(40,8,(($numSub25/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Age 25-34 years old",1,0,'L',0);
$pdf->Cell(40,8,$numSub35,1,0,'L',0);
$pdf->Cell(40,8,(($numSub35/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Age 35-44 years old",1,0,'L',0);
$pdf->Cell(40,8,$numSub45,1,0,'L',0);
$pdf->Cell(40,8,(($numSub45/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Age 45+ years old",1,0,'L',0);
$pdf->Cell(40,8,$numGrt45,1,0,'L',0);
$pdf->Cell(40,8,(($numGrt45/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Ln();

//donee ethnicity statistics
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"Donee Ethnicity Statistics",1,0,'L',0);
$pdf->Cell(40,8,"Number of Donees",1,0,'L',0);
$pdf->Cell(40,8,"Percent of Donees ",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(100,8,"American Indian or Alaskan Native",1,0,'L',0);
$pdf->Cell(40,8,$numEth0,1,0,'L',0);
$pdf->Cell(40,8,(($numEth0/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Asian",1,0,'L',0);
$pdf->Cell(40,8,$numEth1,1,0,'L',0);
$pdf->Cell(40,8,(($numEth1/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Black or African American",1,0,'L',0);
$pdf->Cell(40,8,$numEth2,1,0,'L',0);
$pdf->Cell(40,8,(($numEth2/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Native Hawiian or Pacific Islander",1,0,'L',0);
$pdf->Cell(40,8,$numEth3,1,0,'L',0);
$pdf->Cell(40,8,(($numEth3/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"White",1,0,'L',0);
$pdf->Cell(40,8,$numEth4,1,0,'L',0);
$pdf->Cell(40,8,(($numEth4/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Ln();

//donee income statistics
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"Donee Income Statistics",1,0,'L',0);
$pdf->Cell(40,8,"Number of Donees",1,0,'L',0);
$pdf->Cell(40,8,"Percent of Donees ",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Cell(100,8,"Income $0-$4,999",1,0,'L',0);
$pdf->Cell(40,8,$numIncSub5k,1,0,'L',0);
$pdf->Cell(40,8,(($numIncSub5k/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Income $5,000-$14,999",1,0,'L',0);
$pdf->Cell(40,8,$numIncSub15k,1,0,'L',0);
$pdf->Cell(40,8,(($numIncSub15k/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Income $15,000-$29,999",1,0,'L',0);
$pdf->Cell(40,8,$numIncSub30k,1,0,'L',0);
$pdf->Cell(40,8,(($numIncSub30k/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Cell(100,8,"Income $30,000+",1,0,'L',0);
$pdf->Cell(40,8,$numIncGrt30k,1,0,'L',0);
$pdf->Cell(40,8,(($numIncGrt30k/$numDonees)*100)."%",1,0,'L',0);
$pdf->Ln();
$pdf->Ln();

//print donee zipcode statistics
$pdf->SetFont('Arial','B',11);
$pdf->Cell(100,8,"Donee Zip Code Statistics",1,0,'L',0);
$pdf->Cell(40,8,"Number of Donees",1,0,'L',0);
$pdf->Cell(40,8,"Percent of Donees ",1,0,'L',0);
$pdf->SetFont('Arial','',10);
$pdf->Ln();
foreach($zipCount as $key => $value)
{
	if($value != 0)
	{
		$pdf->Cell(100,8,$key,1,0,'L',0);
		$pdf->Cell(40,8,$value,1,0,'L',0);
		$pdf->Cell(40,8,(($value/$numDonees)*100)."%",1,0,'L',0);
		$pdf->Ln();
	}
}
//break the reference with the last element
unset($value);

//display pdf
$pdf->Output();
?>