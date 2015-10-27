<?php
require_once('db.inc');
require_once('settings.inc');
/*
$nationality = $_GET['nationality'];
$country = $_get['country'];

// Check required parameters
if ( !($nationality && $country) ) {
   die('ERROR: No parameters passed');
}

// Check if a connection was established
if (!$conn) {
   die("No connection");
}
*/

//tbl_visa_requirements_view 
$query1 = "select country_iatacode,nationality from tbl_visa_requirements_view order by nationality";

$stmt2 = $conn->prepare($query1);
$stmt2->execute();

$country_from = $stmt2->fetchAll();



for ($i = 0; $i < count($country_from); $i++)
{
   $cf = new Country();
   $cf->code = $country_from[$i]['country_iatacode'];
   $cf->name = $country_from[$i]['nationality'];
   
   $responseArray["Countries"][] = $cf;
}

$query1 = "select code,name from tbl_visa_header order by name";

$stmt = $conn->prepare($query1);
$stmt->execute();

$country_visiting = $stmt->fetchAll();

for ($i = 0; $i < count($country_visiting); $i++)
{
   $cf = new Country();
   $cf->code = $country_visiting[$i]['code'];
   $cf->name = $country_visiting[$i]['name'];
   
   $responseArray["Visiting"][] = $cf;
}
//$stmt2->close();

//$responseArray = array("Countries"=>$country_from,"Visiting"=>$country_visiting);



//$ports = $stmt->fetchAll();
//$responseArray = array();





echo json_encode($responseArray);

class Country {
   public $code = -1;
   public $name  = null;
}


?>

