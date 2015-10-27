<?php

require_once("nusoap/nusoap.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$seafarerId = '543669';
$client = new soapclient("http://www.rclcrewtravel.com:8787/rclctravel/services/cxfAuth?wsdl",true);
$response = $client->call('getActiveReservationsDetailed',array('SeafarerId' => $seafarerId));

$result = $response['return'];
$flights = $result['activeflightsDetailed'];
$hotels  = $result['activehotelDetailed'];

// print_r($flights);
print_r($hotels);
?>

