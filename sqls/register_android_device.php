<?php

require_once('db.inc');
require_once('settings.inc');
/*
error_reporting(E_ALL);
ini_set('display_errors', '1');*/

$user    = $_GET['user'];
$token = $_GET['code'];


//echo $query;
$query = "select id from tbl_users where username = :user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->execute();

$userRec = $stmt->fetch();

if($userRec)
{
    $query = "select user_id from tbl_users_mobile where user_id = :user";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user',$userRec['id']);
    $stmt->execute();

    $mobile_user = $stmt->fetch();
    if($mobile_user)
    {
        $query = "update tbl_users_mobile set androidToken = :token where user_id = :user";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user',$userRec['id']);
        $stmt->bindParam(':token',$token);
        $stmt->execute();
    }
    else
    {
        $query = "insert into tbl_users_mobile (androidToken,user_id) values (:token,:user)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':token',$token);
        $stmt->bindParam(':user',$userRec['id']);
        $stmt->execute();
    }

}
$message = "Android device updated";

echo json_encode($message);


?>
