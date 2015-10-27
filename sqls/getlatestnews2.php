<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('db.inc');
require_once('settings.inc');

$position =$_GET['pos'];

//$port = trim($_GET['port']);
$query = "select title, CONCAT(tbl_content.introtext, tbl_content.fulltext) as maintext from tbl_content where id=".$position;
$stmt = $conn->prepare($query);
$stmt->execute();
$portInfo = $stmt->fetch();
$news = new LatestNews();
$news->introText = base64_encode($portInfo['maintext']);
$news->title = $portInfo['title'];
echo json_encode($news);

class LatestNews
{
   public $introText;
   public $title;
}



//echo json_encode(array("PortAgent" => utf8_encode($portInfo['port_information'])));
?>

