<?php
/*************************
	GET PARAMETERS
	getlatestnews2.php
**************************/
$position =$_GET['pos'];

/*************************
	SQL
	getlatestnews2.php
**************************/
$query = "select title, 
			CONCAT(tbl_content.introtext, tbl_content.fulltext) as maintext 
			from tbl_content where id=".$position;





/*************************
	SQL
	getlatestnews3.php
**************************/
$query = "SELECT c.id,c.title,c.catid,c.introtext 
			FROM tbl_content c 
		INNER JOIN tbl_content_frontpage f ON f.content_id = c.id
		INNER JOIN tbl_categories cat ON cat.id = c.catid ORDER BY f.ordering DESC";






/*************************
	SQL
	news.php
**************************/
$query = "select id,
				 title,
				 catid,
				 introtext,
				 tbl_content.fulltext as maintext 
				 from tbl_content where catid in (25,23) and state = '1' 
				 order by publish_up desc limit 10";

?>