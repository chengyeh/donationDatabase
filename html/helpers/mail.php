<?php
/*
	This mail helper requires 3 variables to be set before being "called".
	These variables are the following:
	$to is a string that holds the destination email address.
	$subject is the subject line of the email to send.
	$body is the contents of the email body to send. This can include html.
	($attachment is an optional attachment for the email.)

	After these 3(4) variables are declared in any calling file, simply use a 
	require_once with the relative path to this file for the mail to be sent. \
	eg, require_once('helpers/mail.php');
 */
require_once(__DIR__.'/../../config.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/swiftmailer-5.x/lib/swift_required.php');

//create a new smtp transport instance
$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  ->setUsername($config['No_Reply_email_address'])
  ->setPassword($config['No_Reply_email_password']);

//new mail object instance
$mailer = Swift_Mailer::newInstance($transport);

//new email message instance
$message = Swift_Message::newInstance($subject)
  ->setFrom(array($config['No_Reply_email_address'] => $config['nonprofit_name']))
  ->setTo(array($to))
  ->setContentType("text/html")
  ->setBody($body);

//attach the attachment to the email if present
if (isset($attachment)) {
	$message->attach($attachment);
}

//send the mail
$result = $mailer->send($message);
?>
