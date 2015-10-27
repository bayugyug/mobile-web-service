<?php
require_once('nusoap/nusoap.php');

ini_set('default_charset', 'UTF-8');

$host   = "10.8.0.23";
$dbname = "gses";
$username = "gses";
$password = "ph03n1x1";

try {
    $conn = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('ERROR: ' . $e->getMessage());
}

        try {
           // $client = new soapclient("http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl",true);
           // $seafarerId = "788078";
           // $seafarerId = "652782";
           $seafarerId = "246374";
           $client = new soapclient("http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl",true);
           $response = $client->call('getEmployeeProfile',array('SeafarerId' => $seafarerId));

	   // $client = new SoapClient("http://121.96.59.120:8787/rclctravel/services/cxfAuth?wsdl");
           // $response = $client->getActiveReservationsDetailed(array('SeafarerId' => $seafarerId));
 
	   $result  = $response['return'];
           $profile  = ($result['employeeProfShip'] ? $result['employeeProfShip'] : null);
           // print_r($result['schedulerEmail']);
           print_r($result);

           // print_r($profile['schedulerEmail']);
	} catch(Exception $e) {
	   echo $e->getMessage();
	   $flights = null;
	   $hotels = null;
	}

?>

