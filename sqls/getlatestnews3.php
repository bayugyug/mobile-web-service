<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('db.inc');
require_once('settings.inc');

$position =0;

//$port = trim($_GET['port']);
$query = "SELECT c.id,c.title,c.catid,c.introtext FROM tbl_content c INNER JOIN tbl_content_frontpage f ON f.content_id = c.id
INNER JOIN tbl_categories cat ON cat.id = c.catid ORDER BY f.ordering DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$newsItems = $stmt->fetchAll();

//print_r($portInfo);



for ($i = 0; $i < count($newsItems); $i++)
{
   $intro    = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $newsItems[$i]['introtext']);
   $newsItem = new NewsItem();
   $newsItem->id = $newsItems[$i]['id'];
   $newsItem->title = $newsItems[$i]['title'];
   $newsItem->sectionid = $newsItems[$i]['catid'];
   $newsItem->introtext = base64_encode($intro);
   $responseArray[] = $newsItem;
}

echo json_encode(array("LatestNews" =>$responseArray));

class NewsItem {
   public $id = -1;
   public $title  = null;
   public $sectionid = null;
   public $introtext = null;
}



//echo json_encode(array("PortAgent" => utf8_encode($portInfo['port_information'])));
?>

