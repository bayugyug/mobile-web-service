<?php

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/

require_once('db.inc');
require_once('settings.inc');

require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
// include the helper file
require_once(JPATH_BASE.DS.'modules'.DS.'mod_empviewinsurance'.DS.'helper.php');
  //echo JPATH_MODULES.DS.'mod_empviewinsurance'.DS.'helper.php';
require_once('nusoap/nusoap.php');

//get employee username
$user_id = $_GET['uid'];

// Check required parameters
if ( !($user_id) ) {
   die('ERROR: No parameters passed');
}
 
// Get Employee Profile
// $empinfo = get_employeeProfile($user_id);
// $employeeProfile = $empinfo['return'];

// if ($employeeProfile['currStatus'] != 'ON') { 
//    // return 0 if not signing on
//    $resultObj = new ResultObject();
//    echo(json_encode($resultObj));

// } else {
      
	 // Get LOE data
	    $result = get_employeeLOE( $user_id, '' );
	    $empLOEinfo = $result['return']['listEmployeeLOE'];

	    $hasLOE = ( !empty($empLOEinfo) || is_array($empLOEinfo) )  ? 1 : 0 ; 
	    
	    //if (array_key_exists('0',$empLOEinfo)){  

           // $employeeLOE = $empLOEinfo[0];
                
       // }/*else{
            
              //$employeeLOE = $empLOEinfo; 


        //}*/
        $insuranceHelper = new EmpViewInsuranceHelper();

        $employeeLOE = $insuranceHelper->checkShipInsuranceInfo($empLOEinfo);

        // Check if valid to have LOI
       if ( !empty($employeeLOE) ) {

         $result = array();

                for($i=0; $i<count($employeeLOE); $i++) {
                    $currentloe = $employeeLOE[$i];
                   // print_r($currentloe);
                     $insuranceinfo = new ResultObject(); 
                     
                     foreach($insuranceinfo as $key => $value) {
                        $insuranceinfo->$key = $currentloe[$key];
                     }

                     $insuranceinfo->uid =base64_encode($user_id).":".sha1($employeeLOE['employeeName']);  

                     $result[$i] = $insuranceinfo;

                 }

   					echo(json_encode(array("InsuranceLetter"=>$result)));
           
       
       } else {
            
             $resultObj = new ResultObject();
            echo(json_encode($resultObj));
       }    

//}


class ResultObject {

    public $uid = null;
    public $controlNumber = null;
    public $countryCode = null;
    public $countryName = null;
    public $employeeName = null;
    public $IDNumber = null;
    public $seaportCode = null;
    public $seaportName = null;
    public $shipCode = null;
    public $shipName = null;
    public $signOffDate = null;
    public $signOnDate = null;
}


function get_employeeProfile($username) {
       
        $soapClient = new nusoap_client('http://10.8.0.60:8787/rclctravel/services/cxfAuth?wsdl',true);
        $soapClient->soap_defencoding = 'UTF-8';
        $soapClient->decode_utf8 = false; 
         $param = array ("SeafarerId" => $username);
                 
         $result = $soapClient->call('getEmployeeProfile', $param); 
             
             // fault if any
             if ($soapClient->fault) {
            
                  $error = 1;
                  $response->status   = JAUTHENTICATE_STATUS_FAILURE;
                  $response->type = JAUTHENTICATE_STATUS_FAILURE;
                  $response->error_message ="Sorry, getEmployeeProfile returned the following ERROR: ".$fault->faultcode."-".$fault->faultstring.". We will now take you back to our home page.'"; 
                  
                  echo '<h2>Fault Begin Authentication</h2><pre>';
  
                   print_r($result);
                  
                   echo '</pre>';
                  
                  return false;
             }
             
           $err = $soapClient->getError();
        
         if ($err != "") {
          
           echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
           return false;
          
         }  
       return $result;
    }

function get_employeeLOE($username, $controlNumber) {
       
        $soapClient = new nusoap_client('http://10.8.0.60:8787/RCTWebService/services/cxfRCT?wsdl',true);
        $soapClient->soap_defencoding = 'UTF-8';
        $soapClient->decode_utf8 = false; 
        
        $param = array ("SeafarerId" => $username,
                        "ControlNo" => $controlNumber
                );
                 
         $result = $soapClient->call('getActiveEmployeeLOE', $param); 
             
             // fault if any
             if ($soapClient->fault) {
            
                  $error = 1;
                  $response->status   = JAUTHENTICATE_STATUS_FAILURE;
                  $response->type = JAUTHENTICATE_STATUS_FAILURE;
                  $response->error_message ="Sorry, getEmployeeProfile returned the following ERROR: ".$fault->faultcode."-".$fault->faultstring.". We will now take you back to our home page.'"; 
                  
                  echo '<h2>Fault Begin Authentication</h2><pre>';
  
                   print_r($result);
                  
                   echo '</pre>';
                  
                  return false;
             }
             
           $err = $soapClient->getError();
        
         if ($err != "") {
          
           echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
           return false;
          
         }  
       return $result;
    }


?>