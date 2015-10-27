<?php

require_once('db.inc');
require_once('settings.inc');

//$port = trim($_GET['port']);
$query = "select * from tbl_port_airlines order by airline_name";
$stmt = $conn->prepare($query);
$stmt->execute();
//print_r($stmt->fetch());
$st = array();
while($portInfo = $stmt->fetch())
{
   $temp = new Airline();
   $temp->name = $portInfo['airline_name'];
   $temp->url = $portInfo['url'];
   $st[] = $temp;
}
echo json_encode(array("Airlines"=>$st));
class Airline
{
   public $name;
   public $url;
}

?>
