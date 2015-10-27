<?php
require_once('db.inc');
require_once('settings.inc');

$country_iatacode = $_GET['nationality'];
$code = $_GET['country'];

// Check required parameters
if ( !($country_iatacode && $code) ) {
   die('ERROR: No parameters passed');
}

// Check if a connection was established
if (!$conn) {
   die("No connection");
}

$responseArray = array();

//tbl_visa_requirements_view 
$query1 = "select ".$code." from tbl_visa_requirements_view WHERE country_iatacode = '".$country_iatacode."'";

$stmt2 = $conn->prepare($query1);
$stmt2->execute();

$visa_req = $stmt2->fetch();




$code_replace = preg_replace('#<br\s*/?>#i', "\n" , $visa_req[$code]);
echo json_encode(array("Requirement"=>strip_tags(trim($code_replace))));


class Country {
   public $code = -1;
   public $name  = null;
}


?>

