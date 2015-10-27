<?php
require_once('db.inc');
require_once('settings.inc');



// Get the id passed by the application
$user = $_GET['user'];
$lastId = $_GET['lastMsgId'];

$query = "select toread,tbl_uddeim.id as id,fromid,toid,message,datum,short_message,tbl_users.name as name,systemmessage from tbl_uddeim join tbl_users on tbl_uddeim.fromid = tbl_users.id where toid = :user and totrash = 0 order by id desc";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->execute();


$messageItems = $stmt->fetchAll();
$responseArray = array();

for ($i = 0; $i < count($messageItems); $i++)
{
   $messageItem = new MessageItem();
   $messageItem->status = $messageItems[$i]['toread'];
   $messageItem->id = $messageItems[$i]['id'];
   $messageItem->sender = $messageItems[$i]['name'];
   $messageItem->recipient = $messageItems[$i]['toid'];
   $messageItem->message = $messageItems[$i]['message'];
   $messageItem->short_message = $messageItems[$i]['short_message'];
   $messageItem->date = date("Y-m-d H:i:s",$messageItems[$i]['datum']);
   $messageItem->system_message = $messageItems[$i]['systemmessage'];
   $responseArray[] = $messageItem;
}
//print_r($messageItems);
echo json_encode(array("Messages" =>$responseArray));

class MessageItem {
   public $id = -1;
   public $sender  = null;
   public $recipient = null;
   public $message = null;
   public $short_message = null;
   public $system_message = null;
   public $status = 0;
}

?>
