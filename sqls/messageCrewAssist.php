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

$result = new Result();
$emailMessage = Swift_Message::newInstance()

// Give the message a subject
->setSubject('Emp ID' . $employeeId . '/' . $employeeName . ' Re: ' . $subject)

// Set the From address with an associative array
->setFrom(array($employeeEmail => $employeeName))

// Set the To addresses with an associative array
// ->setTo(array($schedulerEmail => $schedulerEmail ))
// ->setTo(array($schedulerEmail => $schedulerEmail ))
->setTo(array('crewassist@rccl.com' => 'crewassist@rccl.com' ))

// Set the message body
->setBody($message);

$transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  ->setUsername('noreply')
  ->setPassword('p@$$w0rd')
  ;
$mailer = Swift_Mailer::newInstance($transport);

$mailer->send($emailMessage);

$result->errNo = 0;
$result->errDesc = "Email sent";
   
echo json_encode(array("Result"=>$result));

// ----------

class Result {
   public $errNo;
   public $errDesc;
}

?>
