<?php
require_once('db.inc');
require_once('settings.inc');
require_once('nusoap/nusoap.php');
require_once('PasswordHash.php');
/*
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/
$user_id = $_GET['uid'];

// Check required parameters
if ( !($user_id) ) {
   die('ERROR: No parameters passed');
}

// Check if a connection was established
if (!$conn) {
   die("No connection");
}

$seafarerId = $user_id;

    try {
          // GET USER INFO
          $query = "select id,name,username,password,email,lastvisitDate from tbl_users where username = :user";
          $stmt = $conn->prepare($query);
          $stmt->bindParam(':user',$user);
          $stmt->execute();

          $userRec = $stmt->fetch();


          // GET LOE INFO
           $client = new nusoap_client("http://10.8.0.60:8787/RCTWebService/services/cxfRCT?wsdl",true);
           $client->soap_defencoding = 'UTF-8';
           $client->decode_utf8 = false;
           $response = $client->call('getActiveEmployeeLOE',array('SeafarerId' => $seafarerId));
           $returnvalue  = $response['return']['listEmployeeLOE'];
           $loe = $returnvalue; 
           
           if ( empty($loe) )
           {

             $responseArray = array( "LOE" => Null );
             echo json_encode($responseArray);
             die();

           }
        
            if (array_key_exists( 0 ,$returnvalue)){ 

                $result = array();

                for($i=0; $i<count($loe); $i++) {
                    $currentloe = $loe[$i];

                     $loeinfo = new LOEInfoObject(); 
                     
                     foreach($currentloe as $key => $value) {
                        $loeinfo->$key = $value;
                     }

                     $loeinfo->uid =base64_encode($seafarerId).":".sha1($userRec['name']);  

                     $result[$i] = $loeinfo;

                 }
     
                 
            }else{

                $loeinfo = new LOEInfoObject();

                foreach($loe as $key => $value) {
                    $loeinfo->$key = $value;
                 }

                 $loeinfo->uid =base64_encode($seafarerId).":".sha1($userRec['name']);  

                 $result = $loeinfo; 
             
            }
     
           
            /*echo '<pre>';
            print_r($result);
            echo '</pre>';*/

           $responseArray = array( "LOE"=>$result );
           echo json_encode($responseArray);

          
        } catch(Exception $e) {
           echo $e->getMessage();

           $loeinfo = null;
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


?>