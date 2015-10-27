<?php
/******************************
	GET PARAMETER
	getpoi.php
*******************************/
$port = trim($_GET['port']);

/******************************
	SQL
	getpoi.php
*******************************/
$query = "select id,
				 title,
				 introtext 
				 from tbl_content 
				 where title like 'What to do in " . $port . "%'";







/******************************
	GET PARAMETER
	getportagent.php
*******************************/
$jos_content_id = $_GET['port'];

/******************************
	SQL
	getportagent.php
*******************************/
$query = "select port_information 
		  from tbl_portdetails 
		  where port_name = '" . $jos_content_id."'";







/******************************
	GET PARAMETER
	getportimages.php
*******************************/
$portName = $_GET['port'];








/******************************
	GET PARAMETER
	getports.php
*******************************/
$continentId = $_GET['id'];
$isPullmantur = $_GET['pullmantur'];

/******************************
	SQL
	getports.php
*******************************/
$query2 = "select id 
			from tbl_menu 
			where menutype='".$menutype."' 
			and published =1 
			and title like '".$continentId."' 
			order by title asc";

$query = "select *
			from tbl_menu 
			where menutype='".$menutype."' 
			and published =1 
			and parent_id = ".$cid." 
			order by title asc";










/******************************
	GET PARAMETER
	gettableport.php
*******************************/
$position =$_GET['pos'];

/******************************
	SQL
	gettableport.php
*******************************/
$query = "select * 
			from jos_content 
			where catid=196 and state=1 
			order by ordering 
			asc limit $position,1";

$query = "select introtext  
			from jos_content 
			where catid=196 and state=1 
			order by ordering asc";








/******************************
	GET PARAMETER
	portDetails.php
*******************************/
$jos_content_id = $_GET['id'];

/******************************
	SQL
	portDetails.php
*******************************/
$query = "select id,
				 title,
				 introtext 
			from tbl_content 
			where title like '" . $jos_content_id . "%'";

 ?>