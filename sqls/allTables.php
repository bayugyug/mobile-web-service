<?php
require_once('db.inc');
require_once('settings.inc');

$query = "SHOW TABLES FROM DB_RCT";
$stmt = $conn->prepare($query);
$stmt->execute();

while($userRec = $stmt->fetch())
{
    $query = "SHOW COLUMNS FROM ".$userRec['Tables_in_DB_RCT'];
    $stmt2 = $conn->prepare($query);
    $stmt2->execute();
    echo $userRec['Tables_in_DB_RCT']."<br />";
    while($rec = $stmt2->fetch())
        echo $rec['Field']."<br />";
        
    echo "<hr />";
}

?>