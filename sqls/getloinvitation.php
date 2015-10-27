<?php 

// Global definitions
define('DS', DIRECTORY_SEPARATOR); 
define('JPATH_BASE', __DIR__);    
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);
array_pop($parts);

// Defines
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_SITE',          JPATH_ROOT);

$user_id = $_GET['uid'];

// Check required parameters
if ( !($user_id) ) {
   die('ERROR: No parameters passed');
}

$loi = null;

// check loi available in local directory
	if (file_exists(JPATH_SITE.DS."images".DS."pdf".DS."LOI".DS.$user_id.'.pdf'))
	{
		$filename = $user_id.'.pdf';
		$loi = '/images/pdf/LOI/'.$filename;
	}

echo json_encode(array("loi_path" => $loi));

?>