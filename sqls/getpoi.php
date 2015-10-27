<?php

require_once('db.inc');
require_once('settings.inc');

$port = trim($_GET['port']);

// $query = "select id,title,introtext from jos_content where catid=160 and sectionid=48 and title like '%" . $port . "%'";
$query = "select id,title,introtext from tbl_content where title like 'What to do in " . $port . "%'";
$stmt = $conn->prepare($query);
$stmt->execute();

$portInfo = $stmt->fetch();
echo json_encode(array("PortPOI" => utf8_encode($portInfo['introtext'])));
?>
