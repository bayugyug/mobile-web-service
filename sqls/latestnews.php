<?php

require_once('db.inc');
require_once('settings.inc');

$query = "select id,title,sectionid,introtext,jos_content.fulltext as maintext from tbl_content where id = 297";
$stmt = $conn->prepare($query);
$stmt->execute();

$newsItems = $stmt->fetchAll();
$responseArray = array();

for ($i = 0; $i < count($newsItems); $i++)
{
   $main    = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $newsItems[$i]['maintext']);
   $intro    = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $newsItems[$i]['introtext']);
   $newsItem = new NewsItem();
   $newsItem->id = $newsItems[$i]['id'];
   $newsItem->title = $newsItems[$i]['title'];
   $newsItem->sectionid = $newsItems[$i]['sectionid'];
   $newsItem->introtext = base64_encode($intro);
   $newsItem->maintext  = base64_encode($main);
   $responseArray[] = $newsItem;
}



echo json_encode(array("LatestNews" =>$responseArray));

class NewsItem {
   public $id = -1;
   public $title  = null;
   public $sectionid = null;
   public $introtext = null;
   public $maintext = null;
}

?>

