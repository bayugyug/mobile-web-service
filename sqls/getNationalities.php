<?php
require_once('db.inc');
require_once('settings.inc');

$responseArray = array();

//tbl_visa_requirements_view 
$query1 = "select country_iatacode,nationality from tbl_visa_requirements_view order by nationality";

$stmt2 = $conn->prepare($query1);
$stmt2->execute();

$country_from = $stmt2->fetchAll();

for ($i = 0; $i < count($country_from); $i++)
{
   $cf = new Nationalities();
   $cf->code = $country_from[$i]['country_iatacode'];
   $cf->name = $country_from[$i]['nationality'];
   
   $responseArray["Nationalities"][] = $cf;
}

echo json_encode($responseArray);

class Nationalities {
   public $code = -1;
   public $name  = null;
}


?>

