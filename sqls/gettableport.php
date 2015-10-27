<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/

require_once('db.inc');
require_once('settings.inc');

$position =$_GET['pos'];

//$port = trim($_GET['port']);
$query = "select * from jos_content where catid=196 and state=1 order by ordering asc limit $position,1";
$stmt = $conn->prepare($query);
$stmt->execute();

$portInfo = $stmt->fetch();
//print_r($portInfo);

$query = "select introtext  from jos_content where catid=196 and state=1 order by ordering asc";
$dt = $conn->prepare($query);
$dt->execute();
$count = 0;
while( $dt->fetch())
   $count++;
$news = new LatestNews();

$intro    = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $portInfo['introtext']);
$news->introText = base64_encode($intro);
$news->count = $count;
$news->status = $portInfo['status'];
echo json_encode($news);

class LatestNews
{
   public $count;
   public $introText;
   public $status;
}



//echo json_encode(array("PortAgent" => utf8_encode($portInfo['port_information'])));
?>

