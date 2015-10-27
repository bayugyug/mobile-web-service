<?php

require_once('db.inc');
require_once('settings.inc');

if(isset($_GET['pullmantur']))
    $isPullmantur = $_GET['pullmantur'];
else
    $isPullmantur = 0;

if($isPullmantur == 1)
    $menutype = "port-guide-pullmantur";
else
    $menutype = "port-guide-hidden";

$query = "select * from tbl_menu where menutype='".$menutype."' and published =1 and link!='' order by title asc";

$stmt = $conn->prepare($query);
$stmt->execute();


$ports = $stmt->fetchAll();
//print_r($ports);
$responseArray = array();

for ($i = 0; $i < count($ports); $i++)
{
   $port = new PortInfo();
   $port->id = $ports[$i]['id'];

   $fulltitle = trim($ports[$i]['title']);
   
   $port->title = $fulltitle;
   $port->sectionid = $ports[$i]['port_continent'];
   
   $responseArray[] = $port;
}

echo json_encode(array("Ports" =>$responseArray));

class PortInfo {
   public $id = -1;
   public $title  = null;
   public $sectionid = -1;
}

?>

