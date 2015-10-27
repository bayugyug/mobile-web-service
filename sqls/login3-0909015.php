<?php

require_once('db.inc');
require_once('settings.inc');

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

require_once('nusoap/nusoap.php');
require_once('PasswordHash.php');



$user  = $_GET['username'];
$pass  = $_GET['password'];

$latest = "unknown";
$latest_version = "2.2.6";
$latest_version2 = "2.2.6";
$version = "unknown";

if(isset($_GET['version']))
{
    $version = $_GET['version'];
    if( $latest_version2 == $_GET['version'] )
        $latest = "true";
    else
        $latest = "false";
}


$token = (isset($_GET['token']) ? $_GET['token'] : null );

// Check required parameters
if ( !($user && $pass) ) {
   die('ERROR: No parameters passed');
}

// Check if a connection was established
if (!$conn) {
   die("No connection");
}



//echo $query;
$query = "select id,name,username,password,email,lastvisitDate from tbl_users where username = :user";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user',$user);
$stmt->execute();

$userRec = $stmt->fetch();


$query ="SELECT  g.title FROM tbl_users u INNER JOIN tbl_user_usergroup_map gm ON u.id = gm.user_id
INNER JOIN tbl_usergroups g ON gm.group_id  = g.id WHERE u.username = :user";

$stmt4 = $conn->prepare($query);
$stmt4->bindParam(':user',$user);
$stmt4->execute();

$gm = $stmt4->fetch();

if(empty($gm))
    $gm['title'] = "User";
/*
// Use PHPass's portable hashes with a cost of 10.
$phpass = new PasswordHash(10, true);

$epass =  $phpass->HashPassword($pass);
*/
$t_hasher = new PasswordHash(10, true);

    //print_r($userRec);
    $correct = false;
    
    $hash = $userRec['password'];
    $pwParts = preg_split ('/:/',$hash);
   // print_r($pwParts);
    if(isset($pwParts[1])){
        $encryptedPw = $pwParts[0];
        $dbSalt = $pwParts[1];
        //echo md5($pass.$dbSalt)." = ".$encryptedPw;
        if(md5($pass.$dbSalt) === $encryptedPw)
            $correct = true;
    }
    else
    {   
        $check = $t_hasher->CheckPassword($pass,$hash);
        if($check == 1)
            $correct = true;   
    }
       

$responseArray = array();
if ($correct ) {
        $profile = array('id' => $userRec['id'],
                    'name' => $userRec['name'],
                    'username' => $userRec['username'],
                    'email' => $userRec['email'],
                    'lastvisit' => $userRec['lastvisitDate'],
                    'usergroup'=> $gm['title'],
                    'is_latest' => $latest
                    );

        $seafarerId = $user;
        
        $query = "select user_id from tbl_users_mobile where user_id = :user";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user',$profile['id']);
        $stmt->execute();
    
        $mobile_user = $stmt->fetch();

         if($mobile_user)
        {
            $query = "update tbl_users_mobile set deviceToken = :token, version = :version where user_id = :user";
            $stmt  = $conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':version', $version);
           // $stmt->bindParam(':lastdate', date("Y-m-d H:i:s"));
            $stmt->bindParam(':user', $profile['id']);
            $stmt->execute();
        }
        else
        {  
            $query = "insert into tbl_users_mobile (deviceToken,version,user_id) values (:token,:version,:user)";
            $stmt  = $conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':version', $version);
            //$stmt->bindParam(':lastdate', date("Y-m-d H:i:s"));
            $stmt->bindParam(':user', $profile['id']);
            $stmt->execute();
        }
        
            $query = "update tbl_users set lastvisitDate = :lastdate where id = :user";
            $stmt  = $conn->prepare($query);
            $stmt->bindParam(':lastdate', date("Y-m-d H:i:s"));
            $stmt->bindParam(':user', $profile['id']);
            $stmt->execute();

        try {
           $client = new nusoap_client("http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl",true);
           $client->soap_defencoding = 'UTF-8';
           $client->decode_utf8 = false;
           $response = $client->call('getActiveReservationsDetailed',array('SeafarerId' => $seafarerId));
           $result  = $response['return'];

           $portObject = null;
           $flights = ($result['activeflightsDetailed'] ? $result['activeflightsDetailed'] : null);
           //transport detailed
            $transport = ($result['activetransportDetailed']?$result['activetransportDetailed']:null);
           if ( $flights ) {
		 // If there are flights, retrieve transfer information from the last port/airport and return it
              $lastFlight  = $flights[count($flights)-1];
              $lastAirport = $lastFlight['arrivalAirportCode'];
              // $lastAirport = "SIN";

              // GET PORT INFORMATION
              $portDetailQuery = "select airport.id,airport.iata_code,airport_name,city.city_code,country.country_code, country.id as country_id, country.name, city.name as city_name from tbl_port_airports as airport, tbl_port_country as country, tbl_port_city as city where airport.iata_code='" . $lastAirport . "' and country.id = airport.country_id and airport.city_id = city.id";
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

              // GET TRANSFER INFO
              $transferInfoQuery = "select port_information from tbl_portdetails where port_name like '%" . strtolower($portObject->cityName) . "%'";
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

           // GET HOTEL INFO
           $hotels  = ($result['activehotelDetailed'] ? $result['activehotelDetailed'] : null);

           $client = new nusoap_client("http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl",true);
           $response = $client->call('getEmployeeProfile',array('SeafarerId' => $seafarerId));
           $result = $response['return'];

           $scheduler = new SchedulerInfo();
           $scheduler->email = $result['schedulerEmail'];
           $scheduler->name  = $result['schedulerName'];
           $scheduler->nationality = $result['nationality'];

           // GET LOE INFO
           $client = new nusoap_client("http://10.8.0.23:8787/RCTWebService/services/cxfRCT?wsdl",true);
           $client->soap_defencoding = 'UTF-8';
           $client->decode_utf8 = false;
           $response = $client->call('getActiveEmployeeLOE',array('SeafarerId' => $seafarerId));
           $returnvalue  = $response['return']['listEmployeeLOE'];

            if (array_key_exists( 0 ,$returnvalue)){      
                   $loe= $returnvalue[0];
                }else{
                     $loe= $returnvalue; 
                }
           $loeinfo = new LOEInfoObject();

           foreach($loe as $key => $value) {
              $loeinfo->$key = $value;
           }

           $loeinfo->uid =base64_encode($seafarerId).":".sha1($userRec['name']);   

        } catch(Exception $e) {
           echo $e->getMessage();
           $flights = null;
           $hotels = null;
        }


        $responseArray = array("User" => $profile, "Flights" => $flights, "Hotels" => $hotels, "Scheduler" => $scheduler, "Transport"=>$transport, "Version"=>$latest_version, "LOE"=>$loeinfo, "TransferInfo" => $transferInfo);
        echo json_encode($responseArray);
} else {
   echo json_encode(array("Error"=>"Username and password do not match or you do not have an account yet."));
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

class LOEInfoObject
{
    public $uid = null;
    public $airportCode = null;
    public $airportName = null;
    public $arrivalSequence = null;
    public $controlNumber = null;
    public $costCenter = null;
    public $countryCode = null;
    public $countryName = null;
    public $dateOfBirth = null;
    public $employeeName = null;
    public $fileName = null;
    public $IDNumber = null;
    public $isNewHire = null;
    public $isReEmployment = null;
    public $issueDate = null;
    public $issueID = null;
    public $lemcu = null;
    public $nationalityCode = null;
    public $nationalityName = null;
    public $passportNo = null;
    public $positionCode = null;
    public $positionName = null;
    public $registryCode = null;
    public $seaportCode = null;
    public $seaportName = null;
    public $shipCode = null;
    public $shipName = null;
    public $signOffDate = null;
    public $signOnDate = null;
}

class TransportInfoObject
{
   public $text = null;
}
class TransferInfoObject
{
   public $text = null;
}

?>
