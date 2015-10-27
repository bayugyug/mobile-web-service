<?php

require_once('db.inc');
require_once('settings.inc');

error_reporting(E_ALL);
ini_set('display_errors', '1');


$query = "SELECT * from tbl_portdetails";




$stmt = $conn->prepare($query);
$stmt->execute();
$newsItems = $stmt->fetchAll();

print_r($newsItems);


?>

