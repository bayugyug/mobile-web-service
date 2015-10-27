<?php
require_once('db.inc');
require_once('settings.inc');

// Get the id passed by the application
$user = $_GET['user'];
$lastId = $_GET['lastMsgId'];

$query = "select id,sender,recipient,message,status from messages where recipient = :user and id > :lastId order by id desc";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->bindParam(':lastId',$lastId);
$stmt->execute();

$messageItems = $stmt->fetchAll();
$responseArray = array();

for ($i = 0; $i < count($messageItems); $i++)
{
   $messageItem = new MessageItem();
   $messageItem->id = $messageItems[$i]['id'];
   $messageItem->sender = $messageItems[$i]['sender'];
   $messageItem->recipient = $messageItems[$i]['recipient'];
   $messageItem->message = $messageItems[$i]['message'];
   $messageItem->status = $messageItems[$i]['status'];
   $responseArray[] = $messageItem;
}

echo json_encode(array("Messages" =>$responseArray));

class MessageItem {
   public $id = -1;
   public $sender  = null;
   public $recipient = null;
   public $message = null;
   public $status = -1;
}

?>

