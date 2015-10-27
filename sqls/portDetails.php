<?php
require_once('db.inc');
require_once('settings.inc');

$jos_content_id = $_GET['id'];


$query = "select id,title,introtext from tbl_content where title like '" . $jos_content_id . "%'";
//echo $query;
$stmt = $conn->prepare($query);
$stmt->execute();

$portInfo = $stmt->fetch();
echo json_encode(array("PortInfo" => $portInfo['introtext']));
// echo utf8_encode($portInfo['introtext']);
?>
