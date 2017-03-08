<?php
echo 'Sending an email!';
$contactUsEmail = 'admin@example.com';
$subject = 'a subject of an email';
$to = 'team12@sharklasers.com';
$body =
'stuff here' .
'<br>' .
'more stuff' .
'<br><br>' .
'<b>Note:</b> This message was sent from an unmonitored address.<br>' .
'Please do no respond to this message. <br>' .
'To contact us, please email ' . 
'<a href="mailto:' . $contactUsEmail . '">' . $contactUsEmail . '</a>';

require_once('helpers/mail.php');
?>