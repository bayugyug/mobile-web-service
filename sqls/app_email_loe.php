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
require_once('api/swift/lib/swift_required.php');
require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');

$employee_ID = $_GET['uid'];
$controlNumber = isset($_GET['ctrlno']) ? trim($_GET['ctrlno']) : ""; 

// get the items to display from the helper
$LOEList = get_employeeLOE($employee_ID, $controlNumber);
$LOE_Details = $LOEList['return']['listEmployeeLOE'];

if (array_key_exists('0',$LOE_Details)){  
    
    $employeeLOE = $LOE_Details[0];
        
}else{
    
      $employeeLOE = $LOE_Details; 
}                  

//get exipire date 
$expireDate = date('m/d/y',strtotime($employeeLOE['signOnDate'] . "+7 day"));

$activeEmployeeLOEInfo = array('controlNumber' => $employeeLOE['controlNumber'], 
                                  'airportCode'   => $employeeLOE['airportCode'], 
                                  'airportName'   => $employeeLOE['airportName'], 
                                  'arrivalSequence'=> $employeeLOE['arrivalSequence'], 
                                  'costCenter'    => $employeeLOE['costCenter'], 
                                  'countryCode'   => $employeeLOE['countryCode'],
                                  'countryName'   => $employeeLOE['countryName'],
                                  'dateOfBirth'   => $employeeLOE['dateOfBirth'],
                                  'employeeName'   => $employeeLOE['employeeName'],
                                  'passportNo'   => $employeeLOE['passportNo'],
                                  'fileName'   => $employeeLOE['fileName'],
                                  'IDNumber'   => $employeeLOE['IDNumber'],
                                  'isNewHire'   => $employeeLOE['isNewHire'],
                                  'isReEmployment'   => $employeeLOE['isReEmployment'],
                                  'issueDate'   => $employeeLOE['issueDate'],
                                  'issueID'   => $employeeLOE['issueID'],
                                  'lemcu'   => $employeeLOE['lemcu'],
                                  'nationalityCode'   => $employeeLOE['nationalityCode'],
                                  'nationalityName'   => $employeeLOE['nationalityName'],
                                  'positionCode'   => $employeeLOE['positionCode'],
                                  'positionName'   => $employeeLOE['positionName'],
                                  'registryCode'   => $employeeLOE['registryCode'],
                                  'seaportCode'   => $employeeLOE['seaportCode'],
                                  'seaportName'   => $employeeLOE['seaportName'],
                                  'shipCode'   => $employeeLOE['shipCode'],
                                  'shipName'   => $employeeLOE['shipName'],
                                  'signOnDate'   => $employeeLOE['signOnDate'],
                                  'signOffDate'   => $employeeLOE['signOffDate']
                                    
                            ); 

  $shipName = $employeeLOE['shipName'];
      $rci = 'SEAS'; $cel = 'CELEBRITY';  $aza = 'AZA'; $pul = 'PUL';
      
      if (strpos($shipName, $rci) !== false ) {
          $brandlogo = 'RCI';
      } else if (strpos($shipName, $cel) !== false ) {
          $brandlogo = 'CEL';
      }else  if (strpos($shipName, $aza) !== false ) {
          $brandlogo = 'AZA';
      } else{
          $brandlogo = 'PUL';
      }
        
    $gpg = '/user/bin/gpg';
    $passphrase = 'RCLCr3wtr@vel'; 
    //putenv("GNUPGHOME= /root/.gnupg");
     //base64_encode()
    if (file_exists(__DIR__ . '/loesign/authorizedsign.gpg'))
    {
        $encrypted_file =  '/var/www/html/httpdocs/loesign/authorizedsign.gpg' ;
        $signature_file = "/var/www/html/httpdocs/loesign/app-".md5($username).".png";
        
    } else {
        
        echo 'No file exist'; die();
    }

     try {
          
                     
          echo exec("/usr/bin/gpg --yes --batch --output $signature_file --passphrase=$passphrase $encrypted_file", $output, $return);
          //var_dump($output, $return);

            
        } catch (Exception $e) {
            
            echo 'Error: decrypting file: '. $e->getMessage();
            die();
        } 
        
               
    // get the HTML
    ob_start();
    include(dirname(__FILE__).'/modules/mod_emploe/tmpl/loe.php');
    $content = ob_get_clean();

// convert to PDF

try {
   $html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'ISO-8859-15');  
   $html2pdf->setDefaultFont('arial');
   $html2pdf->writeHTML($content);
   $html2pdf->Output('/var/www/html/httpdocs/api_new/pdfs/LOE-'.$username.'.pdf','F');
   $filepath = '/var/www/html/httpdocs/api_new/pdfs/LOE-' . $username . '.pdf';
	
   $emailMessage = Swift_Message::newInstance()
   ->setSubject('LOE')
   ->setFrom(array('noreply@rclcrewtrel.com' => 'RCLCrewTravel Automated Email'))
   ->setTo(array( $email  => $email  ))
   ->setBody('Attached is your letter of employment.')
   ->attach(Swift_Attachment::fromPath($filepath));
   
   // This transport is VERY slow, replaced it with SMTP
   // $transport = Swift_MailTransport::newInstance();
    $transport = Swift_SmtpTransport::newInstance('10.8.0.36', 25)
  	->setUsername('shrss')
 	 ->setPassword('p@$$w0rd');
   $mailer = Swift_Mailer::newInstance($transport);
   
   $resultObj = new ResultObject(); 

   if ($mailer->send($emailMessage)) {
    
      $resultObj->result = 1;

     //remove signature file & LOI file
      exec("rm $signature_file", $output, $return);
      exec("rm $filepath", $output, $return);

   }
  
   

   echo(json_encode($resultObj)); 
}
   catch(HTML2PDF_exception $e) {
   echo $e;
   exit;
}

class ResultObject {
   public $result = 0;
}


function get_employeeLOE($username, $controlNumber) {
       
        $soapClient = new nusoap_client('http://10.8.0.23:8787/RCTWebService/services/cxfRCT?wsdl',true);
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
