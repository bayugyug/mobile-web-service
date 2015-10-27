<?php
/******************************
	SQL
	gettraveltips.php
*******************************/
$query = "select id,
				 title,
				 introtext,
				 tbl_content.fulltext as maintext 
				 from tbl_content where catid=577 
				 and state=1 
				 order by id desc limit 10";


?>