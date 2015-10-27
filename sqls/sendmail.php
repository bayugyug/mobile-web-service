<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('swift/lib/swift_required.php');

$message = Swift_Message::newInstance()

// Give the message a subject
->setSubject('Your subject')

// Set the From address with an associative array
->setFrom(array('john@doe.com' => 'John Doe'))

// Set the To addresses with an associative array
->setTo(array('joseph@stratuscast.com' => 'Dennis Domingo'))

->setBody('Dindo pa reply sakin ');

// $transport = Swift_SendmailTransport::newInstance();

 $transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  ->setUsername('rclandroid')
  ->setPassword('p@$$w0rd12345');

$mailer = Swift_Mailer::newInstance($transport);
$mailer->send($message);

?>

