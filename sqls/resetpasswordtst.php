<?php
require_once('swift/lib/swift_required.php');
require_once('db.inc');
require_once('settings.inc');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user = $_GET['username'];

$query = "select id,name,username,password,gid,email,lastvisitDate from jos_users where username = ':user'";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->execute();

$result = new Result();
$userRec = $stmt->fetch();

echo($userRec['email']);
return;

if ($userRec) {
   // $resetToken = mt_rand_str(32);
   $resetToken = mt_rand_str(4);
   $query = "update jos_users set resetToken = :token where username = :user";
   $stmt = $conn->prepare($query);
   $stmt->bindParam(':token',$resetToken);
   $stmt->bindParam(':user',$user);
   $stmt->execute();

   // $messageBody = $resetToken;

   $messageBody = "Hello,
  
A request has been made to reset your RCLCrewTravel account password. To reset your password, you will need to submit this reset code in order to verify that the request was legitimate.
  
The reset code is " . $resetToken;

   $messageBody = $messageBody . "

Copy the code above and paste it into the appropriate field in the RCLCrewTravel App to proceed with resetting your password.

Thank you
Your SHRSS Friends,
Bringing the Best of the World into One Team!";
    $email = trim($userRec['email']);
   $message = Swift_Message::newInstance()

   // Give the message a subject
   ->setSubject('Your RCLCrewTravel Password reset request')

   // Set the From address with an associative array
   ->setFrom(array('crewassist@rccl.com' => 'RCLCrewTravel Automated Email Response'))

   // Set the To addresses with an associative array
   ->setTo(array( $email => $email ))

   // Set the message body
   ->setBody($messageBody);

   // This transport is really slow, replaced it with SmtpTransport
   // $transport = Swift_MailTransport::newInstance();
   $transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  ->setUsername('rclandroid')
  ->setPassword('p@$$w0rd12345')
  ;
   $mailer = Swift_Mailer::newInstance($transport);
   $mailer->send($message);
   
   $result->errNo = 0;
   $result->errDesc = $resetToken;
} else {
   $result->errNo = 404;
   $result->errDesc = "User not found";
}

echo json_encode(array("Result"=>$result));


function mt_rand_str ($l, $c = 'abcdef1234567890') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}

class Result {
   public $errNo;
   public $errDesc;
}

?>
