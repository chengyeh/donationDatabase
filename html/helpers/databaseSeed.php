<?php
/* helpers/databaseSeed.php
 * implements functions used to seed the database with test data.
 */

require_once(__DIR__.'/mysqli.php');
require_once(__DIR__.'/crypto.php');

/*
 * init_seed_metadata
 * Called by various seed functions that create database entries that require
 * relationships to other tables in the database to determine what parameters
 * to use for these relationships.
 */
function init_seed_metadata()
{
	global $seed_metadata, $mysqli;

	// count number of users
	$query = <<<SQL
SELECT COUNT(*) FROM UserTable;
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		var_dump($query);
		die('MySQL error: ' . $mysqli->error);
	}
	$result = $result->fetch_assoc();
	$seed_metadata['UserTable_count'] = (int)$result['COUNT(*)'];

	// count number of items in inventory
	$query = <<<SQL
SELECT COUNT(*) FROM InventoryTable;
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		var_dump($query);
		die('MySQL error: ' . $mysqli->error);
	}
	$result = $result->fetch_assoc();
	$seed_metadata['InventoryTable_count'] = (int)$result['COUNT(*)'];

	// count number of categories
	$query = <<<SQL
SELECT COUNT(*) FROM CategoriesTable;
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		var_dump($query);
		die('MySQL error: ' . $mysqli->error);
	}
	$result = $result->fetch_assoc();
	$seed_metadata['CategoriesTable_count'] = (int)$result['COUNT(*)'];
}

/*
 * seed_address
 * Produces a random street address, e.g. '5432 Jayhawk Blvd.'
 * @return	string
 */
function seed_address()
{
	$streets = [
		'Kansas', 'Massachusetts', 'Missouri', 'Mississippi', 'Tennessee',
		'Iowa', 'Jayhawk', 'Sunnyside', 'Haskell', 'Ousdahl',
		'Main', 'Mulberry'
	];
	$streetTitles = [ 'St.', 'Ave.', 'Blvd.', 'Ln.', 'Pkwy.' ];
	$number = rand(100, 9999);
	if (rand(0, 1))
		$street = rand(10, 60) . 'th';
	else
		$street = $streets[rand(0, count($streets)-1)];
	$streetTitle = $streetTitles[rand(0, count($streetTitles)-1)];
	$address = "$number $street $streetTitle";
	return $address;
}

/*
 * seed_city
 * Produces a random city, e.g. 'New York'
 * @return	string
 */
function seed_city()
{
	$cities = [
		'Kansas City', 'Lawrence', 'St. Louis', 'Los Angeles', 'New York',
		'Honolulu', 'San Diego', 'Jefferson City', 'Dallas', 'Warrensburg',
		'Manhattan', 'Wichita', 'Hayes', 'Topeka', 'Clyde', 'Oakley', 'Inman'
	];
	$city = $cities[rand(0, count($cities)-1)];
	return $city;
}

/*
 * seed_state
 * Produces a random state, e.g. 'Kansas'
 * @return	string
 */
function seed_state()
{
	$states = [
		'Alabama', 'Arkansas', 'Connecticut', 'Delaware', 'Florida',
		'Hawai\'i', 'Idaho', 'Indiana', 'Iowa', 'Kansas', 'Kentucky',
		'Louisiana', 'Massachusetts', 'Mississippi', 'Missouri', 'Montana',
		'Nebraska', 'New York', 'North Dakota', 'Oregon', 'Oklahoma',
		'Rhode Island', 'South Dakota', 'Texas', 'Utah', 'Vermont',
		'Washington', 'Wisconsin', 'Wyoming'
	];
	$state = $states[rand(0, count($states)-1)];
	return $state;
}

/*
 * seed_zip
 * Produces a random zip code, e.g. '66064'
 * @return	string
 */
function seed_zip()
{
	$zip = rand(10000, 80000);
	return "$zip";
}

/*
 * seed_name_part
 * Produces a single name (i.e. a first name or a last name).
 * @return	string
 */
function seed_name_part()
{
	$prefixes = [
		'Bu', 'Du', 'Pe', 'Bo', 'Mc', 'Pe', 'Le', 'Nor', 'Wu'
	];
	$roots = [
		'Bibb', 'Wump', 'Tung', 'Jarb', 'Porb', 'Kubb', 'Ding', 'Curd', 'Bump',
		'Beef', 'Pork', 'Mang', 'Dork', 'Clong', 'Bulg', 'Scrimm', 'Beeb',
		'Georg', 'Mugg', 'Chogg', 'Bulb', 'Bong', 'Bung', 'Fung', 'Sausag',
		'Loin', 'Bigg', 'Ting', 'Wapp', 'Gord', 'Beep', 'Ming', 'Reed', 'Bob',
		'Gripp', 'Ork'
	];
	$suffixes = [
		'', 'le', 'ook', 'us', 'o', 'a', 'er', 'y', 'on', 'sta', 'man'
	];
	$name = $roots[rand(0, count($roots)-1)] . $suffixes[rand(0, count($suffixes)-1)];
	if (rand(0, 6) == 0) {
		$prefix = $prefixes[rand(0, count($prefixes)-1)];
		if ($prefix !== 'Mc')
			$name = strtolower($name);
		$name = $prefix . $name;
	}
	return $name;
}

/*
 * seed_item
 * Produces the name of an item.
 * @return	string
 */
function seed_item()
{
	$bases = [
		'Fruit', 'Milk', 'Butter', 'Cheese', 'Meat', 'Slime', 'Water', 'Rock',
		'Powder', 'Fish', 'Prickle', 'Mold', 'Paste', 'Dirt', 'Crumble',
		'Blood', 'Spice', 'Leather', 'Lemon', 'Worm', 'Crisp', 'Wood', 'Ice',
		'Grain', 'Chunk'
	];
	$base1 = $bases[rand(0, count($bases)-1)];
	if (substr($base1, -1) == 'e')
		$base1 = substr($base1, 0, -1);
	$base2 = $bases[rand(0, count($bases)-1)];
	$item = $base1 . 'y ' . $base2;
	return $item;
}

/*
 * seed_identity
 * Produces an array containing a first name, a last name, and an email address
 * @return	array
 */
function seed_identity()
{
	$first = seed_name_part();
	$last = seed_name_part();
	$email = strtolower($first . $last) . '@example.com';
	return [
		'first' => $first,
		'last' => $last,
		'email' => $email
	];
}

/*
 * seed_phone
 * @return	A randomly generated phone number in the form "(XXX) XXX-XXXX"
 */
function seed_phone()
{
	$area_code = rand(100, 999);
	$part_1 = rand(100, 999);
	$part_2 = rand(1000, 9999);
	$phone = "($area_code) $part_1-$part_2";
	return $phone;
}

/*
 * seed_income
 */
function seed_income()
{
	return rand(1, 10) * 10000;
}

/*
 * seed_gender
 * @return	A character: m, f, or o.
 */
function seed_gender()
{
	switch (rand(0, 2)) {
		case 0: $gender = 'm'; break;
		case 1: $gender = 'f'; break;
		case 2: $gender = 'o'; break;
	}
	return $gender;
}

/*
 * seed_user
 * Calls the other seed functions to generate an example user, then adds the
 * example user to the database.
 */
function seed_user()
{
	if (!isset($seed_metadata))
		init_seed_metadata();
	global $seed_metadata, $mysqli;
	// generate data
	// we only escape to prevent characters like the apostrophe in Hawai'i from
	// breaking the query string - our seed data shouldn't generate injections
	$identity = seed_identity();
	$email = mysqli_real_escape_string($mysqli, $identity['email']);
	$firstname = mysqli_real_escape_string($mysqli, $identity['first']);
	$lastname = mysqli_real_escape_string($mysqli, $identity['last']);
	$address = mysqli_real_escape_string($mysqli, seed_address());
	$city = mysqli_real_escape_string($mysqli, seed_city());
	$state = mysqli_real_escape_string($mysqli, seed_state());
	$zip = seed_zip();
	$phone = seed_phone();
	$gender = seed_gender();
	$ethnicity = rand(1, 5);
	$income = seed_income();
	$numInHouse = rand(1, 6);
	$salt = substr(cs_prng(), 0, 16);
	$password = $firstname . '123'; // bad user! bad!
	$passHash = hash_password($password, $salt);

	//add to database
	$query = <<<SQL
INSERT INTO `UserTable`
	(FirstName, LastName, Email, AddressLine1, City, State, Zip, Telephone,
	Gender, Ethnicity, Income, HouseholdSize, PassSalt, PassHash, Active,
	FlagDonor, FlagDonee, lastTaxGenDate)
	VALUES
	('$firstname', '$lastname', '$email', '$address', '$city', '$state', '$zip',
	'$phone', '$gender', '$ethnicity', '$income', '$numInHouse', '$salt',
	'$passHash', True, True, True, NOW());
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	}
	$seed_metadata['UserTable_count']++;
}

/*
 * seed_recent_date
 * Creates a date prior to the present date/time in a format that MySQL will
 * accept.
 * @param	int	timescale	The scale of time to alter (lower is higher). 0 is
 * 		years, 1 is months, 2 is days, ... (max 5, default 2)
 * @return	string	A date in a format acceptable for use in a MySQL query.
 */
function seed_recent_date($timescale = 2, $magnitude = 5)
{
	$unit_arr = ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
	$unit = $unit_arr[$timescale];
	$date = date_create('now');
	if ($magnitude > 1) {
		$magnitude = rand(1, $magnitude - 1);
		date_modify($date, "-$magnitude $unit");
	}
	if ($timescale < 1) {
		$magnitude = rand(1, 12);
		date_modify($date, "-$magnitude months");
	}
	if ($timescale < 2) {
		$magnitude = rand(1, 28);
		date_modify($date, "-$magnitude days");
	}
	if ($timescale < 3) {
		$magnitude = rand(1, 24);
		date_modify($date, "-$magnitude hours");
	}
	if ($timescale < 4) {
		$magnitude = rand(1, 60);
		date_modify($date, "-$magnitude minutes");
	}
	if ($timescale < 5) {
		$magnitude = rand(1, 60);
		date_modify($date, "-$magnitude seconds");
	}
	return date_format($date, 'Y-m-d H:i:s');
}

function seed_category()
{
	if (!isset($seed_metadata))
		init_seed_metadata();
	global $seed_metadata, $mysqli;

	$category = seed_item() . ' Category';

	$query = <<<SQL
INSERT INTO `CategoriesTable` (Name) VALUES ('$category');
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	}

	$seed_metadata['CategoriesTable_count']++;
}

function seed_inventory()
{
	if (!isset($seed_metadata))
		init_seed_metadata();
	global $seed_metadata, $mysqli;

	$item_name = seed_item();
	$category = rand(1, $seed_metadata['CategoriesTable_count']);
	$threshold = rand(1, 10) * 100;
	$amount = rand(0, $threshold);

	$query = <<<SQL
INSERT INTO `InventoryTable`
	(Name, CategoryNum, Amount, Threshold)
VALUES
	('$item_name', '$category', '$amount', '$threshold');
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	}

	$seed_metadata['InventoryTable_count']++;
}

function seed_incoming_donation()
{
	if (!isset($seed_metadata))
		init_seed_metadata();
	global $seed_metadata, $mysqli;

	$donor_id = rand(1, $seed_metadata['UserTable_count']);
	$item_id = rand(1, $seed_metadata['InventoryTable_count']);
	$amount = rand(1, 10);
	$actualAmount = rand(0, $amount);
	$value = $amount * rand(1, 20);
	$pledgeDate = seed_recent_date(0, 2);
	if ($actualAmount > 0)
		$receiveDate = seed_recent_date(0, 1);
	else
		$receiveDate = 'NULL';
	
	$query = <<<SQL
INSERT INTO `IncDonationTable`
	(DonorID, ItemID, Amount, ActualAmount, Value, PledgeDate, ReceiveDate)
	VALUES
	('$donor_id', '$item_id', '$amount', '$actualAmount', '$value',
	'$pledgeDate', '$receiveDate');
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	}
}

function seed_outgoing_donation()
{
	if (!isset($seed_metadata))
		init_seed_metadata();
	global $seed_metadata, $mysqli;

	$donee_id = rand(1, $seed_metadata['UserTable_count']);
	$item_id = rand(1, $seed_metadata['InventoryTable_count']);
	$amount = rand(1, 10);
	$amountGranted = rand(0, $amount);
	$value = $amount * rand(1, 20);
	$requestDate = seed_recent_date(0, 2);
	if ($amountGranted > 0)
		$fulfillDate = seed_recent_date(0, 1);
	else
		$fulfillDate = 'NULL';

	$query = <<<SQL
INSERT INTO `OutDonationTable`
	(DoneeID, ItemID, Amount, AmountGranted, RequestDate, FulfillDate)
	VALUES
	('$donee_id', '$item_id', '$amount', '$amountGranted', '$requestDate',
	'$fulfillDate');
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	}
}

