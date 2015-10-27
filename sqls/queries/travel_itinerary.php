<?php
/******************************
	GET PARAMETERS
	emailItinerary3.php
*******************************/
$username = $_GET['user'];
$email = $_GET['email'];




/******************************
	SQL
	emailItinerary3.php
*******************************/
$query = 'SELECT p.* FROM tbl_portdetails AS p WHERE p.port_code = "'.$port_code.'"'; 
?>