<?php

require_once('db.inc');
require_once('settings.inc');




$port = $_GET['port'];
error_reporting(E_ALL);
ini_set('display_errors', '1');



$query = "select airport.id,airport_code,airport_name,airport.city_code,airport.country_code, country.id as country_id, country.name, city.name as city_name from jos_port_airports as airport, jos_port_country as country, jos_port_city as city where airport_code='" . $port . "' and country.country_iatacode = airport.country_code and country.id = city.country_id and city.city_code = airport.city_code";
$stmt = $conn->prepare($query);
$stmt->execute();
$portInfo = $stmt->fetch();

$portObject = new PortInformationObject();
$portObject->airportCode = $port;
$portObject->airportName = $portInfo['airport_name'];
$portObject->cityCode    = $portInfo['city_code'];
$portObject->cityName    = $portInfo['city_name'];
$portObject->countryCode = $portInfo['country_code'];
$portObject->countryName = $portInfo['name'];

$query = "select * from jos_airlines";
$stmtPort = $conn->prepare($query);
$stmtPort->execute();
$portInfo2 = $stmtPort->fetch();
//echo $portObject->cityName." ";
//$transferInfo = new TransferInfoObject();
//$transferInfo->text = utf8_encode($portInfo2['port_information']);
//echo $portObject->cityName;
//echo json_encode(array("TransferInfo" => utf8_encode($portInfo2['port_information'])));
print_r($portInfo2);

class PortInformationObject
{
   public $airportCode = null;
   public $airportName = null;
   public $cityCode    = null;
   public $cityName    = null;
   public $countryCode = null;
   public $countryName = null;
}

class TransferInfoObject
{
   public $text = null;
}

?>
