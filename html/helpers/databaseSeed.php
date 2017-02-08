<?php
/* helpers/databaseSeed.php
 * implements functions used to seed the database with test data.
 */

require_once('./mysqli.php');
require_once('./crypto.php');

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
	if (rand(0, 1) == 0)
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
		'Bib', 'Wump', 'Tung', 'Jarb', 'Porb', 'Kubb', 'Ding', 'Curd', 'Cust',
		'Bump', 'Beef', 'Pork', 'Mang', 'Dork', 'Clong', 'Bulg', 'Malg'
	];
	$suffixes = [
		'le', 'ook', 'us', 'o', 'a', 'er'
	];
	$name = $prefixes[rand(0, count($prefixes)-1)] . $suffixes[rand(0, count($suffixes)-1)];
	return $name;
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

function seed_phone()
{
	$phone = rand(1000000000, 9999999999);
	return $phone;
}

function seed_user()
{
	global $mysqli;
	// generate data
	// we only escape to prevent characters like the apostrophe in Hawai'i from
	// breaking the query string - our seed data shouldn't generate injections
	$identity = seed_identity();
	$firstname = mysqli_real_escape_string($mysqli, $identity['first']);
	$lastname = mysqli_real_escape_string($mysqli, $identity['last']);
	$address = mysqli_real_escape_string($mysqli, seed_address());
	$city = mysqli_real_escape_string($mysqli, seed_city());
	$state = mysqli_real_escape_string($mysqli, seed_state());
	$zip = mysqli_real_escape_string($mysqli, seed_zip());
	$phone = mysqli_real_escape_string($mysqli, seed_phone());
	$email = mysqli_real_escape_string($mysqli, $identity['email']);
	// TODO: will want to salt this eventually
	$password = hash('sha256', $firstname . '123'); // bad user! bad!

	//add to database
	$query = <<<SQL
INSERT INTO `Users`
	(FirstName, LastName, Telephone, Email, Address, City, State, Zip, Password)
	VALUES
	('$firstname', '$lastname', '$phone', '$email', '$address', '$city', '$state', '$zip', '$password');
SQL;
	$result = $mysqli->query($query);
	if (!$result) {
		die('MySQL error: ' . $mysqli->error);
	}
}

