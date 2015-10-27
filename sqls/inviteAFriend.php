<?php
require_once('swift/lib/swift_required.php');
require_once('db.inc');
require_once('settings.inc');

$senderName = $_GET['senderName'];
$friendsEmail = $_GET['friendsEmail'];
$inviteMessage = $_GET['inviteMessage'];

$result = new Result();
// Fix-up the message body before setting it.
$inviteMessage = $inviteMessage . "<br/><br/><a href='http://www.azamaracareersatsea.com'>http://www.azamaracareersatsea.com</a><br/>";
$inviteMessage = $inviteMessage . "<a href='http://www.celebritycareersatsea.com'>http://www.celebritycareersatsea.com</a><br/>";
$inviteMessage = $inviteMessage . "<a href='http://www.cdfcareersatsea.com'>http://www.cdfcareersatsea.com</a><br/>";
$inviteMessage = $inviteMessage . "<a href='http://www.pullmanturcareersatsea.com'>http://www.pullmanturcareersatsea.com</a><br/>";
$inviteMessage = $inviteMessage . "<a href='http://www.royalcareersatsea.com'>http://www.royalcareersatsea.com</a><br/>";
$inviteMessage = $inviteMessage . "<br/><br/>" . $senderName;

$emailMessage = Swift_Message::newInstance()

// Give the message a subject
->setSubject('Be part of our team')

// Set the From address with an associative array
->setFrom(array('DO-NOT-REPLY@rccl.com' => 'RCLCrewTravel Automated Email Response'))

// Set the To addresses with an associative array
// ->setTo(array($schedulerEmail => $schedulerEmail ))
->setTo(array( $friendsEmail  =>  $friendsEmail))

// Fix-up the message body before setting it. 
->setBody($inviteMessage,'text/html');

// This transport is really slow, replaced it with SmtpTransport
// $transport = Swift_MailTransport::newInstance();
$transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
->setUsername('rclandroid')
->setPassword('p@$$w0rd12345');

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
