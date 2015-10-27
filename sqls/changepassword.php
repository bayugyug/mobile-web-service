<?php
require_once('db.inc');
require_once('settings.inc');


$token = $_GET['token'];
$newpassword = $_GET['password'];

$salt  = 'Cf0VhayOqdOKtgFPXqVryp8eWK7lpOVa';
$newpassword = md5($newpassword . $salt) . ":" . $salt;
$query = "select user_id from tbl_users_mobile where resetToken = :token";
$stmt = $conn->prepare($query);
$stmt->bindParam(':token',$token);
$stmt->execute();
$user = $stmt->fetch();

//print_r($user);

$query = "update tbl_users set password = :newpassword where id = :id";
$stmt2 = $conn->prepare($query);
$stmt2->bindParam(':newpassword',$newpassword);
$stmt2->bindParam(':id',$user['user_id']);
$stmt2->execute();

$result->errNo = 1;
$result->errDesc = "Password changed";

echo json_encode(array("Result"=>$result));

class Result {
   public $errNo;
   public $errDesc;
}

?>
