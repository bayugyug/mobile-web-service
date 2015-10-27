<?php
require_once('db.inc');
require_once('settings.inc');
$query = "select id,title,introtext,tbl_content.fulltext as maintext from tbl_content where catid=577 and state=1 order by id desc limit 10";
$stmt = $conn->prepare($query);
$stmt->execute();

$tips = $stmt->fetchAll();
$responseArray = array();

for ($i = 0; $i < count($tips); $i++)
{
   $main    = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $tips[$i]['introtext']);
   $tip = new TravelTip();
   $tip->id = $tips[$i]['id'];
   $tip->title = $tips[$i]['title'];
   $tip->introtext = base64_encode($main);
   $tip->maintext = base64_encode($tips[$i]['maintext']);
   $responseArray[] = $tip;;
}

echo json_encode(array("TravelTips" =>$responseArray));

class TravelTip {
   public $id = -1;
   public $title  = null;
   public $introtext = null;
   public $maintext = null;
}

?>

