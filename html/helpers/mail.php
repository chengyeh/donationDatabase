<?php
require_once(__DIR__.'/../../config.php');
require_once($_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/swiftmailer-5.x/lib/swift_required.php');

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
  ->setUsername($config['No_Reply_email_address'])
  ->setPassword($config['No_Reply_email_password']);

$mailer = Swift_Mailer::newInstance($transport);

$message = Swift_Message::newInstance($subject)
  ->setFrom(array($config['No_Reply_email_address'] => $config['nonprofit_name']))
  ->setTo(array($to))
  ->setContentType("text/html")
  ->setBody($body);
  //->attach(Swift_Attachment::fromPath('a_pdf.pdf'));

$result = $mailer->send($message);
?>