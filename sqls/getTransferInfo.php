<?php
require_once('db.inc');
require_once('settings.inc');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$port = $_GET['port'];
$port = strtoupper($port);

$query = "select airport.id,airport.iata_code,airport_name,city.city_code,country.country_code, country.id as country_id, country.name, city.name as city_name from tbl_port_airports as airport, tbl_port_country as country, tbl_port_city as city where city.name ='" . $port . "' and country.id = airport.country_id and city.id = airport.city_id";
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

$query = "select port_information from tbl_portdetails where port_code like '%" . $portObject->airportCode . "%'";
$stmtPort = $conn->prepare($query);
$stmtPort->execute();
$portInfo2 = $stmtPort->fetch();

$transferInfo = new TransferInfoObject();
$transferInfo->text = utf8_encode($portInfo2['port_information']);

echo json_encode(array("TransferInfo" => utf8_encode($portInfo2['port_information'])));


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
