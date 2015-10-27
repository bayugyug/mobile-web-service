<?php
require_once('db.inc');
require_once('settings.inc');

$jos_content_id = $_GET['port'];
if($_GET['port'] == "Ashdod")
   $jos_content_id = "JERUSALEM (ASHDOD)"; 
$jos_content_id = strtoupper($jos_content_id);

$query = "select port_information from tbl_portdetails where port_name = '" . $jos_content_id."'";
$stmt = $conn->prepare($query);
$stmt->execute();

$portInfo = $stmt->fetch();
echo json_encode(array("PortAgent" => utf8_encode($portInfo['port_information'])));
// echo utf8_encode($portInfo['introtext']);
?>
