<?php

$errorCode = 0;

$errors = [
	// 0: No error
	// site errors
	1 => 'Incorrect username or password.',
	2 => 'One or more required fields were not filled out.',
	3 => 'The provided email address is already in use.',
	4 => 'One or more fields contained invalid data.',
	5 => 'We need donor information from you before you can do that.',
	6 => 'We need donee information from you before you can do that.',
	// generic HTML status codes
	401 => 'You must be logged in to access the specified resource.',
	403 => 'You are not currently permitted to access the specified resource.',
	404 => 'The specified resource was not found.',
	418 => 'Cannot brew coffee: I am a teapot.',
	419 => 'Cannot display webpage: I am a fox.',
	420 => 'Please wait a few minutes and try again.',
	498 => 'Token mismatch. Please go back and try again.',
	499 => 'Token not supplied. Please go back and try again.'
];

if (isset($_GET['err'])) {
	$err = htmlspecialchars($_GET['err']);

	if (array_key_exists($err, $errors)) {
		$msg = $errors[$err];
		$errorCode = $err;
	} else {
		$err = $err ? " ($err)" : '';
		$msg = "An undefined error$err has occured.";
	}
	?>
	<div class="alert alert-danger">
		<b>Error!</b> <?= $msg ?>
	</div>
	<?php
}
