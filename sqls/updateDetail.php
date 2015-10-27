<?php

// error_reporting(E_ALL);
// ini_set('display_errors', '1');
// 050e2b1a59df1d5fbc81c2fdc738d333:hCZ8Me3EXBQr1tyBPTgHhmQqyNjBDeM5

$host   = "localhost";
$dbname = "gses";
$username = "gses";
$password = "ph03n1x1";

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

$salt = 'Cf0VhayOqdOKtgFPXqVryp8eWK7lpOVa';

$username  = $_GET['username'];
$userEmail = $_GET['email'];
$userPass  = $_GET['password'];

if ( $userEmail ) {
   $query_email = "update jos_users set email = :email where username = :user";
   $stmt_email  = $conn->prepare($query_email);
   $stmt_email->bindParam(':email',$userEmail);
   $stmt_email->bindParam(':user',$username);
   $stmt_email->execute();
   $responseArray = array("Result" => "SUCCESS");
   echo json_encode($responseArray);
} else if ($userPass) {
   $encNewPassword = md5($userPass . $salt);
   $query_pass = "update jos_users set password = :pass where username = :user";
   $stmt_pass  = $conn->prepare($query_pass);

   $newPass = $encNewPassword . ":" . $salt;
   $stmt_pass->bindParam(':pass', $newPass);
   $stmt_pass->bindParam(':user', $username);
   $stmt_pass->execute();
  
   $responseArray = array("Result" => "SUCCESS");
   echo json_encode($responseArray);
}
?>

