<?php

/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/


// Set flag that this is a parent file
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );

require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once('api_new/nusoap/nusoap.php');   
require_once('api_new/db.inc');
require_once('api_new/settings.inc');
// get the user reference to the database

/* DND
$recordLocator = isset($_GET['rl']) ? trim($_GET['rl']) : "";
$parameter = trim($_GET['uid']);
$user = explode("%",$parameter);
$username = base64_decode($user[0]);
*/

$username = $_GET['user'];
$email = $_GET['email'];

$flightReservationInfo = array();
// get the items to display from the helper
//$infos = ModGses::getProfile();
//$dinfos = ModGses::getDataInfo();
$empinfo = get_employeeProfile($username);
$employee = $empinfo['return'];
$infos = $employee['employeeProfShip'][0];
$empInfos = array('employeeid'=> $infos['e1ID'],
                  'name'=>$employee['name'],
                  'rank'=>$infos['rank'],
                  'gender'=>$employee['gender'],
                  'nationality'=>$employee['nationality'],
                  );
       
$flight = get_ActiveReservationAll($username);
$flightInfo = $flight['return'];

/*echo '<pre>';
print_r($empinfo);
echo '</pre>';*/
// flights detailed
//$pdf = 
$activeReservationDetailed = $flightInfo['actreserveDetailed']['activeflightsDetailed']; 
$activeReservationFlights = $flightInfo['actreserve']['activeflights'];        
//$ticketNumber = $flightInfo['actreserveDetailed']['ticketNumber'];             
//hotel detailed
$activeHotelDetailed = $flightInfo['actreserveDetailed']['activehotelDetailed'];
//transport detailed
   $activeTransportDetailed = $flightInfo['actreserveDetailed']['activetransportDetailed'];
// other flights detailed
$activeReservationOthers = $flightInfo['actreserveOthers'];



//loop to get the arrival/departure date and location of the trip
if (array_key_exists('0',$activeReservationDetailed)){  
    $i = 0;
    foreach($activeReservationDetailed as $value)
    {
        if ($i < 1)
        {
            $dateTripFrom = $value['departureDateTime'];
        }else{
             $dateTripTo = $value['arrivalDateTime'];
             $tripLocationTo = $value['arrivalAirportName'];
             $recordLocator = $value['recordLocator'];
	     $port_dest = $value['arrivalAirportCode'];
        }
        $i++;
    }
}else{
    
      $dateTripFrom = $activeReservationDetailed['departureDateTime'];
     $dateTripTo = $activeReservationDetailed['arrivalDateTime'];
     $tripLocationTo = $activeReservationDetailed['arrivalAirportName'];
     $recordLocator = $activeReservationDetailed['recordLocator'];
     $port_dest = $activeReservationDetailed['arrivalAirportCode'];
}                  
$flightReservationInfo = array('dateTripFrom'=>$dateTripFrom, 
                            'dateTripTo'=>$dateTripTo, 
                            'tripToLocation'=>$tripLocationTo, 
                            'tripFor'=> $employee['name'], 
                            'reservationCode'=>$recordLocator, 
                            'airLineReservationCode'=>'');
       
        
 
    $portAgentInfo = getPortInfo($port_dest,$conn);                
    // get the HTML
    ob_start();
    include(dirname(__FILE__).'/modules/mod_empflightdetails/tmpl/profile.php');
    $content = ob_get_clean();

// convert to PDF
require_once('api/swift/lib/swift_required.php');
require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
try {
   $html2pdf = new HTML2PDF('P', 'A4', 'en');
   $html2pdf->setDefaultFont('arial');
   $html2pdf->writeHTML($content);
   $html2pdf->Output('/home/httpdocs/api/pdfs/flight_itinerary_'.$username.'.pdf','F');
	
   $emailMessage = Swift_Message::newInstance()
   ->setSubject('Travel Details')
   ->setFrom(array('noreply@rclcrewtrel.com' => 'RCLCrewTravel Automated Email'))
   ->setTo(array( $email  => $email  ))
   ->setBody('Attached is your travel itinerary')
   ->attach(Swift_Attachment::fromPath('/home/httpdocs/api/pdfs/flight_itinerary_' . $username . '.pdf'));
   
   // This transport is VERY slow, replaced it with SMTP
   // $transport = Swift_MailTransport::newInstance();
    $transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  	->setUsername('shrss')
 	 ->setPassword('p@$$w0rd');
   $mailer = Swift_Mailer::newInstance($transport);
   $result = $mailer->send($emailMessage);
  
   $resultObj = new ResultObject();
   $resultObj->result = $result;
   echo(json_encode($resultObj)); 
}
   catch(HTML2PDF_exception $e) {
   echo $e;
   exit;
}

class ResultObject {
   public $result = 0;
}
class PortAgentObject
{
   public $port_information="";
}
    function getPortInfo($portCode,$conn)
    {
        // Check if a connection was established
	if (!$conn) {
   	die("No connection");
	}	
	$ret = new PortAgentObject();
	
	$port_code = $portCode;
        
        // get a list of $userCount randomly ordered users 
        $query = 'SELECT p.* FROM tbl_portdetails AS p WHERE p.port_code = "'.$port_code.'"';
	$stmt = $conn->prepare($query); 
        $stmt->execute();
	$e = $stmt->fetch();
	$ret->port_information = $e['port_information'];
	$dinfos = ($dinfos = $ret)?$dinfos:array();

        return $dinfos;
    }
    function get_ActiveReservationAll($username,$recordLocator='') {
       
        $soapClient = new nusoap_client('http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl',true);
        $soapClient->soap_defencoding = 'UTF-8';
        //$soapClient->decode_utf8 = false;     
        
       //  $soapClient->setUseCurl(1);
         if ($recordLocator !=''){ 
             $param = array ("SeafarerId" => $username,"RecordLocator"=>$recordLocator);
         }else{
             $param = array ("SeafarerId" => $username,"RecordLocator"=>'');    
         }
         $result = $soapClient->call('getActiveReservationsAll', $param); 
                      
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
    function get_employeeProfile($username) {
       
        $soapClient = new nusoap_client('http://10.8.0.23:8787/rclctravel/services/cxfAuth?wsdl',true);
       //  $soapClient->setUseCurl(1); 
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
?>
