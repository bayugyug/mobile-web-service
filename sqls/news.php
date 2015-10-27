<?php

require_once('db.inc');
require_once('settings.inc');

error_reporting(E_ALL);
ini_set('display_errors', '1');


$query = "select id,title,catid,introtext,tbl_content.fulltext as maintext from tbl_content where catid in (25,23) and state = '1' order by publish_up desc limit 10";
$stmt = $conn->prepare($query);
$stmt->execute();

$newsItems = $stmt->fetchAll();
$responseArray = array();

for ($i = 0; $i < count($newsItems); $i++)
{
   $newsItem = new NewsItem();
   $newsItem->id = $newsItems[$i]['id'];
   $newsItem->title = $newsItems[$i]['title'];
   $newsItem->sectionid = $newsItems[$i]['catid'];
   $newsItem->introtext = base64_encode($newsItems[$i]['introtext']);
   $newsItem->maintext  = base64_encode($newsItems[$i]['maintext']);
/*
   $newsItem = array('id' => $newsItems[$i]['id'],
                    'title' => htmlspecialchars($newsItems[$i]['title']));
                    'introtext' => htmlspecialchars($newsItems[$i]['introtext']),
                    'details' => htmlspecialchars($newsItems[$i]['details']));
   array_push($responseArray,$newsItem);
*/
   $responseArray[] = $newsItem;
}



echo json_encode(array("News" =>$responseArray));

class NewsItem {
   public $id = -1;
   public $title  = null;
   public $sectionid = null;
   public $introtext = null;
   public $maintext = null;
}

?>

