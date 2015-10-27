<?php

$sender    = $_POST['sender'];
$recipient = $_POST['recipient'];
$message   = $_POST['message'];
$fullmessage = $_POST['fullmessage'];

try {
    $host   = "10.8.0.23";
    $dbname = "gses";
    $username = "gses";
    $password = "ph03n1x1";

    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

$query = "select * from jos_users where username = :user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$recipient);

$stmt->execute();

$userRec = $stmt->fetch();

$deviceToken = $userRec['deviceToken'];
print_r($userRec);
echo $deviceToken;
echo "JSKADJKASJDKASJDLKJSAD";


?>
