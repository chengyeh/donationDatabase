<?php

require_once('helpers/form.php');

?>
<!DOCTYPE html>
<html lang = "en">
<?php

$navbar_active = 'signup';
$navbar_title = 'User Page';

include("layouts/navbar.php");

?>

<div class="container">
	<h3>Insert Item</h3> <br>
	<form class="form-horizontal" action="user.php" method="POST">
		<h4>Item Information</h4><br />
		<?php
		form_field('itemname', 'Item ID*', 'number');
		form_field('itemquantity', 'Item Quantity*', 'number');
		form_field('itemvalue', 'Item Value*', 'number');
		?>
		<hr>
		<h4>Donor Information</h4><br />
		<?php
		form_field('reference', 'Donation Reference Number*', 'number');
		form_field('donorid', 'Donor ID*', 'text');
		?>
		<b>- or -</b><br />
		<?php
		form_field('firstname', 'First Name*', 'text');
		form_field('lastname', 'Last Name*', 'text');
		form_field('address', 'Address*', 'text');
		form_field('phone', 'Phone*', 'text');
		form_field('email', 'Email*', 'email');
		csrf_token_field();
		?>
		<input type="submit" value="Enter Item"> <br>
	</form>
</div> <br><br>
<script type="text/javscript" source="js/bootstrap.min.js"></script>

</body>
</html>
