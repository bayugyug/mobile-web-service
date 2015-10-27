
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('swift/lib/swift_required.php');

$body = $_GET['message'];
$subject = $_GET['subject'];
$to = $_GET['to'];
$employeeId = $_GET['employeeId'];
$employeeName = $_GET['employeeName'];




$message = Swift_Message::newInstance()

// Give the message a subject
->setSubject('Emp ID' . $employeeId . '/' . $employeeName . ' Re: ' . $subject)

// Set the From address with an associative array
->setFrom(array('rclandroid@rclcrewtravel.com' => $employeeName))

// Set the To addresses with an associative array
->setTo(array('crewassist@rccl.com' => 'Crew Assist'))
->setCc(array($to => $to))
->setBody($body);

// $transport = Swift_SendmailTransport::newInstance();
 $transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  ->setUsername('noreply')
  ->setPassword('p@$$w0rd')
  ;
$mailer = Swift_Mailer::newInstance($transport);

if($mailer->send($message)){
	$data["success"] = 1;
}
else{
	$data["success"] = 0;
}
echo json_encode($data);
?>

