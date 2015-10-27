<?php
require_once('db.inc');
require_once('settings.inc');
require_once('nusoap/nusoap.php');

$user  = $_GET['username'];
$pass  = $_GET['password'];
$token = (isset($_GET['token']) ? $_GET['token'] : null );

// Check required parameters
if ( !($user && $pass) ) {
   die('ERROR: No parameters passed');
}

// Check if a connection was established
if (!$conn) {
   die("No connection");
}

$query = "select id,name,username,password,gid,email,lastvisitDate from jos_users where username = :user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->execute();

$userRec = $stmt->fetch();
$pwParts = explode(':', $userRec['password']);
$encryptedPw = $pwParts[0];
$dbSalt = $pwParts[1];

$responseArray = array();
if ( md5($pass . $dbSalt) === $encryptedPw ) {
        $profile = array('id' => $userRec['id'],
                    'name' => $userRec['name'],
                    'username' => $userRec['username'],
                    'email' => $userRec['email'],
                    'lastvisit' => $userRec['lastvisitDate'],
                    'gid' => $userRec['gid']);

        $seafarerId = $user;

        $query = "update jos_users set deviceToken = :token where username = :user";
        $stmt  = $conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        try {
           $client = new soapclient("http://10.8.0.60:8787/rclctravel/services/cxfAuth?wsdl",true);
           $response = $client->call('getActiveReservationsDetailed',array('SeafarerId' => $seafarerId));
           $result  = $response['return'];

           $portObject = null;
           $flights = ($result['activeflightsDetailed'] ? $result['activeflightsDetailed'] : null);
           if ( $flights ) {
		 // If there are flights, retrieve transfer information from the last port/airport and return it
              $lastFlight  = $flights[count($flights)-1];
              $lastAirport = $lastFlight['arrivalAirportCode'];
              // $lastAirport = "SIN";

              $portDetailQuery = "select airport.id,airport_code,airport_name,airport.city_code,airport.country_code, country.id as country_id, country.name, city.name as city_name from jos_port_airports as airport, jos_port_country as country, jos_port_city as city where airport_code='" . $lastAirport . "' and country.country_iatacode = airport.country_code and country.id = city.country_id and city.city_code = airport.city_code";
              $stmt = $conn->prepare($portDetailQuery);
              $stmt->execute();
              $portInfo = $stmt->fetch();

              $portObject = new PortInformationObject();
              $portObject->airportCode = $lastAirport;
              $portObject->airportName = $portInfo['airport_name'];
              $portObject->cityCode    = $portInfo['city_code'];
              $portObject->cityName    = $portInfo['city_name'];
              $portObject->countryCode = $portInfo['country_code'];
              $portObject->countryName = $portInfo['name'];

              $transferInfoQuery = "select port_information from jos_portdetails where port_name like '%" . strtolower($portObject->cityName) . "%'";
              $stmtPort = $conn->prepare($transferInfoQuery);
              $stmtPort->execute();
              $portInfo2 = $stmtPort->fetch();

              $transferInfo = null;
              $transferInfoText = $portInfo2['port_information'];
              if ( ($transferInfoText)  && (strlen($transferInfoText) > 0) ) {
                 $transferInfo = new TransferInfoObject();
                 $transferInfo->text = utf8_encode($transferInfoText);
              }
           }
           $hotels  = ($result['activehotelDetailed'] ? $result['activehotelDetailed'] : null);

           $client = new soapclient("http://10.8.0.60:8787/rclctravel/services/cxfAuth?wsdl",true);
           $response = $client->call('getEmployeeProfile',array('SeafarerId' => $seafarerId));
           $result = $response['return'];

           $scheduler = new SchedulerInfo();
           $scheduler->email = $result['schedulerEmail'];
           $scheduler->name  = $result['schedulerName'];
        } catch(Exception $e) {
           echo $e->getMessage();
           $flights = null;
           $hotels = null;
        }

        $responseArray = array("User" => $profile, "Flights" => $flights, "Hotels" => $hotels, "Scheduler" => $scheduler, "TransferInfo" => $transferInfo);
        echo json_encode($responseArray);
} else {
   echo "Password is incorrect";
}

class SchedulerInfo {
   public $name = null;
   public $email = null;
}

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
