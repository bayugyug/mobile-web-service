<?php

$dbhost = "localhost"; // unix_socket=/var/lib/mysql/mysql.sock;
$dbname = "DB_RCT";
$dbuser = "gses";
$dbpass = "ph03n1x1"; //

try {
    $conn = new PDO('mysql:host='.$dbhost.';dbname=' . $dbname, $dbuser, $dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

?>
