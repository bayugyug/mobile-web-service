<?php

// Old password value: 8487d94c5cd1b3dc1b7177ba8a4044fd:gDKIBqGrxnXTdTTSGQXSYo3ZszLGKRFX 
// Old employee id: 13456820

$host   = "10.8.0.35";
$dbname = "gses";
$username = "gses";
$password = "ph03n1x1";

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected.";
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

/*
$salt = 'Cf0VhayOqdOKtgFPXqVryp8eWK7lpOVa';
$pass = 'password';

$encryptedPass = md5($pass . $salt);
$newPass = $encryptedPass . ":" . $salt;
$userId = 68;

$stmt = $conn->prepare('update jos_users set password = :newPass where id = :id');
$stmt->bindParam(':newPass',$newPass);
$stmt->bindParam(':id',$userId);

$stmt->execute();

echo "Password updated";
*/
?>

