<?php

require_once('db.inc');
require_once('settings.inc');
$query = "select airport_name, url from tbl_port_airports order by airport_name";

$stmt = $conn->prepare($query);
$stmt->execute();

$airports = $stmt->fetchAll();
$responseArray = array();

///print_r($airports);

for ($i = 0; $i < count($airports); $i++)
{
   $airport = new AirportInfo();
   $airport->airport_name = $airports[$i]['airport_name'];
   $airport->airport_url = $airports[$i]['url'];
   $responseArray[] = $airport;
}

echo json_encode(array("Airports" =>$responseArray));

class AirportInfo {
   public $airport_name = null;
   public $airport_url  = null;
}

?>

