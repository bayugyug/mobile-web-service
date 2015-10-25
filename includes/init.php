<?php
@error_reporting(E_ALL & ~( E_WARNING|E_STRICT|E_NOTICE|E_DEPRECATED|E_USER_DEPRECATED ));
//@header('Content-type: application/json');
/**
|	@Filename	:	const.php
|	@Description	:	all global vars
|                               
|	@Date		:	2009-04-25
|	@Ver		:	Ver 0.01
|	@Author		:	bayugyug@gmail.com
|
|
|       @Modified Date  :
|       @Modified By    :
|    
**/
@session_cache_limiter(false);
@session_start();

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR .
					    dirname(__FILE__). DIRECTORY_SEPARATOR .'com.php.utils.libs'. PATH_SEPARATOR .'.');
			
//misc
include_once('status.codes.php');
include_once('const.php');
include_once('cfg.php');



//api
include_once('class-ldap-api.php');
include_once('class-ldap-groups-api.php');


//more web-services
include_once('class-default-api.php');
include_once('class-visaguidance-api.php');
include_once('class-travel-tips-api.php');
include_once('class-travel-itinerary-api.php');
include_once('class-port-guide-api.php');
include_once('class-latest-news-api.php');
include_once('class-inbox-messages-api.php');
include_once('class-email-messages-api.php');

//libs
include_once('com.utils.init.php');

//mailer
include_once('PHPMailer/PHPMailerAutoload.php');

//framework
include_once('Slim/Slim.php');

//-----
//@misc
//-----
if(1){
		//init dbs here
		global $gSqlDb,$DBOPTS,$gSqlDbSvc,$DBCREWTRAVEL;
		
		//default db conn for LDAP
		$gSqlDb = new mySqlDbh2($DBOPTS);
		$gSqlDb->dbh();
		
		//more-web-svc
		$gSqlDbSvc['DBCREWTRAVEL'] = new mySqlDbh2($DBCREWTRAVEL);
		$gSqlDbSvc['DBCREWTRAVEL']->dbh();
}


//logger-formatting
$gLoggerConf = array('append' => true,'mode' => 0666, 'timeFormat' => '[%Y%m%d %H:%M:%S]');

?>