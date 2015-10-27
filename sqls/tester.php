<?php
require_once('nusoap/nusoap.php');

$seafarerId = "246374";
try {
   // $client = new soapclient("http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl",true);
   // $response = $client->call('getActiveReservationsDetailed',array('SeafarerId' => $seafarerId));
   $result  = $response['return'];

   $client = new soapclient("http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl",true);
   $response = $client->call('getEmployeeProfile',array('SeafarerId' => $seafarerId));
   var_dump($response);
} catch(Exception $e) {
   echo $e->getMessage();
   $flights = null;
   $hotels = null;
}

$responseArray = array("Response" => $result);
echo json_encode($responseArray);

?>

