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
$empinfo = get_employeeProfile($user_id);
$employeeProfile = $empinfo['return'];

if ($employeeProfile['currStatus'] != 'ON') { 
   // return 0 if not signing on
   $resultObj = new ResultObject();
   echo(json_encode($resultObj));

} else {
      
	 // Get LOE data
	    $result = get_employeeLOE( $user_id, '' );
	    $empLOEinfo = $result['return']['listEmployeeLOE'];

	    $hasLOE = ( !empty($empLOEinfo) || is_array($empLOEinfo) )  ? 1 : 0 ; 
	    
	    if (array_key_exists('0',$empLOEinfo)){  

            $employeeLOE = $empLOEinfo[1];
                
        }/*else{
            
              $employeeLOE = $empLOEinfo; 
        }*/
        /*echo '<pre>';
        print_r($employeeLOE);
        echo '</pre>';*/
        // Check if valid to have LOI
       if ( !empty($employeeLOE) ) {

         // $insuranceHelper = new EmpViewInsuranceHelper();
              
         // if ($insuranceHelper->checkUserVisaRequirement($employeeLOE, $employeeProfile)) {
                // Check Layout to display
               // TO - DO
               
               /*if ($employeeLOE['shipCode'] == "AN") {*/
                   
                ob_start();
    				    include(JPATH_BASE.'/modules/mod_empviewinsurance/tmpl/insuranceletter.php');
    				    $content = ob_get_clean();
               /*}else {
            
                   return;
               }*/


			     // convert to PDF
			    require_once(JPATH_BASE.'/html2pdf/html2pdf.class.php');
			    
			    try
			    {
			       $html2pdf = new HTML2PDF('P', 'A4', 'en');
			       //$html2pdf->addFont('BookAntiquaFont','','/var/www/html/httpdocs/html2pdf/font/BookAntiquaFont.php');
			       //$html2pdf->addFont('bookantiqua','','bookantiqua.php');
			       $html2pdf->setDefaultFont('times');
			       $html2pdf->pdf->setTitle('Letter of Insurance --'.$employeeLOE['employeeName']);
			       $html2pdf->pdf->setJPEGQuality((int)100);
			       $html2pdf->writeHTML($content);
			       $html2pdf->Output('/var/www/html/httpdocs/api_new/pdfs/InsuranceLetter_'.$user_id.'.pdf','F');

			       $filepath = '/var/www/html/httpdocs/api_new/pdfs/InsuranceLetter_' . $user_id . '.pdf';

			        $resultObj = new ResultObject();
			        $resultObj->result = 1;
   					echo(json_encode($resultObj));

			    }
			    catch(HTML2PDF_exception $e) {
			        echo $e;
			        exit;
			    }
               
          }/* else {
              // if visa not valid return 0
              $resultObj = new ResultObject();
              echo(json_encode($resultObj));
          }*/
           
       
       } else {
            
           return;
       }

}


class ResultObject {
   public $result = 0;
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