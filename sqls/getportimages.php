<?php

$portName = $_GET['port'];
$portName = strtolower($portName);
$portName = str_replace(' ', '', $portName);
$portName = str_replace('.', '', $portName);
$portName = str_replace(',', '', $portName);

$dir = "/var/www/html/httpdocs/images/ports_places/" . $portName;
$files = scandir($dir);

// print_r($files);
// echo json_encode(array("Ports" =>$responseArray));

$images = glob($dir . '/*.{jpeg,jpg,gif,png}', GLOB_BRACE);
//print_r($images);
// echo json_encode($images);

echo json_encode(array("PortImages" => $images));
?>

