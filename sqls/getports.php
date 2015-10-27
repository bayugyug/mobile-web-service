<?php
require_once('db.inc');
require_once('settings.inc');

$continentId = $_GET['id'];
if(isset($_GET['pullmantur']))
    $isPullmantur = $_GET['pullmantur'];
else
    $isPullmantur = 0;

if($continentId == "northamerica")
    $continentId = "north america";
if($continentId == "southamerica")
    $continentId = "south america";

//echo $continentId;
if($isPullmantur == 1)
    $menutype = "port-guide-pullmantur";
else
    $menutype = "port-guide-hidden";

$query2 = "select id from tbl_menu where menutype='".$menutype."' and published =1 and title like '".$continentId."' order by title asc";

$stmt2 = $conn->prepare($query2);
$stmt2->execute();

$continent = $stmt2->fetch();

//print_r($continent);
$cid = $continent['id'];

$query = "select * from tbl_menu where menutype='".$menutype."' and published =1 and parent_id = ".$cid." order by title asc";

$stmt = $conn->prepare($query);
$stmt->execute();

$ports = $stmt->fetchAll();
$responseArray = array();



for ($i = 0; $i < count($ports); $i++)
{
    if($ports[$i]['link']!=""){
        $port = new PortInfo();
        $port->id = $ports[$i]['id'];

        $fulltitle = trim($ports[$i]['title']);
        $port->title =$fulltitle;
        // $port->title = $ports[$i]['title'];
        $port->sectionid = $continentId;
        $responseArray[] = $port;
    }
    $query = "select * from tbl_menu where menutype='".$menutype."' and published =1 and parent_id = ".$ports[$i]['id']." order by title asc";
    
    $stmt3 = $conn->prepare($query);
    $stmt3->execute();

    $ports2 = $stmt3->fetchAll();
    //print_r($ports2);
    for ($j = 0; $j < count($ports2); $j++)
    {
        $port2 = new PortInfo();
        $port2->id = $ports2[$j]['id'];

        $fulltitle = trim($ports2[$j]['title']);
        $port2->title =$fulltitle;
        // $port->title = $ports[$i]['title'];
        $port2->sectionid = $continentId;
        $responseArray[] = $port2;   
    }
    
    
}

foreach ($responseArray as $key => $val) {

    $port_title[$key] = $val->title;
}

array_multisort($port_title, SORT_ASC, $responseArray);


echo json_encode(array("Ports" =>$responseArray));

class PortInfo {
   public $id = -1;
   public $title  = null;
   public $sectionid = -1;
}

?>

