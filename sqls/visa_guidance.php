<?php
/******************************
	SQL
	visarequirements.php
*******************************/
$query1 = "select country_iatacode,
				  nationality 
			from tbl_visa_requirements_view 
			order by nationality";







/******************************
	GET PARAMETERS
	visarequirements2.php
*******************************/
$country_iatacode = $_GET['nationality'];
$code = $_GET['country'];

/******************************
	SQL
	visarequirements2.php
*******************************/
$query1 = "select ".$code." 
			from tbl_visa_requirements_view 
			WHERE country_iatacode = '".$country_iatacode."'";

?>