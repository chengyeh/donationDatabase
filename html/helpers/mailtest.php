<?php
require_once(__DIR__.'/../../config.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/swiftmailer-5.x/lib/swift_required.php');

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  ->setUsername($config['email_address'])
  ->setPassword($config['email_password']);

$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance('A subject from the donation database')
  ->setFrom(array('no-reply@example.com' => 'Non-profit_Name'))
  ->setTo(array('kueecs.team10@gmail.com'))
  ->setBody('This is a test email from the donation database.');
  //->attach(Swift_Attachment::fromPath('a_pdf.pdf'));

$result = $mailer->send($message);
?>