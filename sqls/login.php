<?php
require_once('nusoap/nusoap.php');

ini_set('default_charset', 'UTF-8');

/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

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

$user = $_GET['username'];
$pass = $_GET['password'];
$token = $_GET['token'];

if ( !($user && $pass) ) {
   die('ERROR: No parameters passed');
}

$salt = 'Cf0VhayOqdOKtgFPXqVryp8eWK7lpOVa';
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
                    'employeeid' => $userRec['employeeid'],
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
   	   // $client = new soapclient("http://www.rclcrewtravel.com:8787/rclctravel/services/cxfAuth?wsdl",true);

           $client = new soapclient("http://10.8.0.60:8787/rclctravel/services/cxfAuth?wsdl",true);
   	   $response = $client->call('getActiveReservationsDetailed',array('SeafarerId' => $seafarerId));

	   // $client = new SoapClient("http://121.96.59.120:8787/rclctravel/services/cxfAuth?wsdl");
           // $response = $client->getActiveReservationsDetailed(array('SeafarerId' => $seafarerId));
 
	   $result  = $response['return'];

           // $flights = $result['activeflightsDetailed'];
	   // $hotels  = $result['activehotelDetailed'];
           $flights = ($result['activeflightsDetailed'] ? $result['activeflightsDetailed'] : null);
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

        $responseArray = array("User" => $profile, "Flights" => $flights, "Hotels" => $hotels, "Scheduler" => $scheduler);
        echo json_encode($responseArray);
} else {
   echo "Password is incorrect";
}

class SchedulerInfo {
   public $name = null;
   public $email = null;
}

?>

