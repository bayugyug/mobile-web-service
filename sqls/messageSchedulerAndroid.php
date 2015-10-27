<?php
require_once('swift/lib/swift_required.php');
require_once('db.inc');
require_once('settings.inc');

$employeeId = $_GET['employeeid'];
$employeeEmail = $_GET['employeeemail'];
$employeeName = $_GET['name'];
$schedulerEmail = $_GET['scheduleremail'];
$subject = $_GET['subject'];
$message = $_GET['message'];

//$eaddArray = explode('@',$employeeEmail);


$emailMessage = Swift_Message::newInstance()

// Give the message a subject
->setSubject('Emp ID' . $employeeId . '/' . $employeeName . ' Re: ' . $subject)

// Set the From address with an associative array
->setFrom(array('rclandroid@rclcrewtravel.com' => $employeeName))

// Set the To addresses with an associative array
// ->setTo(array($schedulerEmail => $schedulerEmail ))
->setTo(array($schedulerEmail => $schedulerEmail ))
// ->setTo(array('dndomingo@gmail.com' => 'dndomingo@gmail.com' ))
//->setCc(array($employeeEmail => $employeeEmail))
// Set the message body
->setBody($message);

$transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  ->setUsername('noreply')
  ->setPassword('p@$$w0rd')
  ;
$mailer = Swift_Mailer::newInstance($transport);


$emailMessage2 = Swift_Message::newInstance()

->setSubject('Your message has been sent to your scheduler.'  . ' Re: ' . $subject)

->setFrom(array('rclandroid@rclcrewtravel.com' => 'RCLCrewTravel'))

// Set the To addresses with an associative array
// ->setTo(array($schedulerEmail => $schedulerEmail ))
->setTo(array($employeeEmail => $employeeEmail ))
// ->setTo(array('dndomingo@gmail.com' => 'dndomingo@gmail.com' ))
//->setCc(array($employeeEmail => $employeeEmail))
// Set the message body
->setBody("Here is a copy of your message: <br /> ".$message);

 $transport2 = Swift_SmtpTransport::newInstance('localhost',25);
$mailer2 = Swift_Mailer::newInstance($transport2);

$mailer2->send($emailMessage2);

$ret["success"] = 0;

if($mailer->send($emailMessage))
{
	$ret["success"]=1;
}
else
{
	$ret["success"]=0;
}



echo json_encode($ret);


?>

