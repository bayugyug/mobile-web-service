<?php

//gtalk
include_once('includes/init.php');




/**
|	@Filename	:	ldap.api.php
|	@Description:	entry point
|                               
|	@Date		:	2015-10-03
|	@Ver		:	Ver 0.01
|	@Author		:	bayugyug@gmail.com
|
|
|   @Modified Date:
|   @Modified By  :
|    
**/

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */



//auto-load
\Slim\Slim::registerAutoloader();

//instance 
$app = new \Slim\Slim(array(
));
 
//more settings
const MY_APP_NAME = 'LDAPApi';

$app->setName(MY_APP_NAME);

//cfg here
$app->config('debug', false);

//just in-case
if(0)
{
		$app->config('cookies.lifetime', '720 minutes');
		$app->config('cookies.path',     '/');
		$app->config('cookies.encrypt',    true);
		$app->config('cookies.secret_key', md5( sprintf("%s-%s",MY_APP_NAME,@date('Ymd') ) ) );
		$app->config('http.version', '1.1');
}



//instantiate it here
debug("api(): Start!");
/**--------------------------------------------------------------------------
|
|	@run LDAP_Api
|
|	ex: 
|          ($action = API_HIT_ENTRY_SEARCH,$showres=true,$status=false)
|
|	- ACTION
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|----------------------------------------------------------------------------
**/

//run
$api['LDAP_Api'] = new LDAP_Api(API_HIT_ENTRY_RESTAPI,true,true);

$module++;
debug("api($module): VIA LDAP_Api > ");
//@ MAPPING of ROUTES


//ldap group
$app->group('/ldap', function () use ($app,&$api) 
{

	$app->group('/restapi', function () use ($app,&$api) 
	{
		//sign-in
		$app->map('/signin', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_SIGN_IN,$app);
			return true;
		})->via('GET', 'POST');  
	 
        //add entry
		$app->map('/add', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_ADD,$app);
			return true;
		})->via('POST', 'PUT');  
        
		//update entry
		$app->map('/modify', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_UPDATE,$app);
			return true;
		})->via('POST', 'PUT');
		
		//search
		$app->map('/search', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_SEARCH,$app);
			return true;
		})->via('GET', 'POST');
		
		//list
		$app->map('/list', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_LIST,$app);
			return true;
		})->via('GET', 'POST');
    	
		//change-password
		$app->map('/changepass', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_CHPASS,$app);
			return true;
		})->via('GET', 'POST');
		
		//member-of
		$app->map('/memberof', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_MEMBER,$app);
			return true;
		})->via('GET', 'POST');  
		
		//session
		$app->map('/session', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_SESSION,$app);
			return true;
		})->via('GET', 'POST');  
		
		//session id
		$app->map('/sid', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_SID,$app);
			return true;
		})->via('GET', 'POST');  
		
		//sign-out
		$app->map('/signout', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_SIGN_OUT,$app);
			return true;
		})->via('GET', 'POST'); 
		
		//csv upload
		$app->map('/dumpcsv', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_CSV_DUMP,$app);
			return true;
		})->via('GET', 'POST'); 
		//word encrypt
		$app->map('/encryptword', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_WORD_ENC,$app);
			return true;
		})->via('GET', 'POST'); 
		//word decrypt
		$app->map('/decryptword', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_WORD_DEC,$app);
			return true;
		})->via('GET', 'POST'); 
		//reset password 
		$app->map('/resetpass', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_RESET_PASS,$app);
			return true;
		})->via('GET', 'POST'); 
		//change-email
		$app->map('/changemail', function () use ($app,&$api) 
		{
			$api['LDAP_Api']->hit(API_HIT_ENTRY_CHMAIL,$app);
			return true;
		})->via('GET', 'POST');
		
		
	}); //MAP REST-API
	
}); //MAP LDAP-GROUP




/**--------------------------------------------------------------------------
|
|	@run VISA_GUIDANCE_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['VISA_GUIDANCE_Api'] = new VISA_GUIDANCE_Api(API_HIT_ENTRY_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA VISA_GUIDANCE_Api > ");
 
//@ VISA_GUIDANCE_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/newsfeed', function () use ($app,$api) 
		{
			//visa
			$app->map('/visaguidance', function () use ($app, $api) 
			{
				$api['VISA_GUIDANCE_Api']->hit(API_HIT_VISAGUIDANCE_SEARCH);
				return true;
			})->via('GET', 'POST'); 

		}); //MAP VISA_GUIDANCE_Api
}); //MAP VISA_GUIDANCE_Api

 
 
 
/**--------------------------------------------------------------------------
|
|	@run TRAVEL_TIPS_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['TRAVEL_TIPS_Api'] = new TRAVEL_TIPS_Api(API_HIT_TRAVEL_TIPS_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA TRAVEL_TIPS_Api > ");
 
//@ TRAVEL_TIPS_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/traveltips', function () use ($app,$api) 
		{
			//visa
			$app->map('/default', function () use ($app, $api) 
			{
				$api['TRAVEL_TIPS_Api']->hit(API_HIT_TRAVEL_TIPS_SEARCH);
				return true;
			})->via('GET', 'POST'); 

		}); //MAP TRAVEL_TIPS_Api
}); //MAP TRAVEL_TIPS_Api

 
 
 
 

/**--------------------------------------------------------------------------
|
|	@run TRAVEL_ITINERARY_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['TRAVEL_ITINERARY_Api'] = new TRAVEL_ITINERARY_Api(API_HIT_TRAVEL_ITINERARY_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA TRAVEL_ITINERARY_Api > ");
 
//@ TRAVEL_ITINERARY_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/travelitinerary', function () use ($app,$api) 
		{
			//visa
			$app->map('/default', function () use ($app, $api) 
			{
				$api['TRAVEL_ITINERARY_Api']->hit(API_HIT_TRAVEL_ITINERARY_SEARCH);
				return true;
			})->via('GET', 'POST'); 

		}); //MAP TRAVEL_ITINERARY_Api
}); //MAP TRAVEL_ITINERARY_Api

 
 

/**--------------------------------------------------------------------------
|
|	@run PORT_GUIDE_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['PORT_GUIDE_Api'] = new PORT_GUIDE_Api(API_HIT_TRAVEL_ITINERARY_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA PORT_GUIDE_Api > ");
 
//@ PORT_GUIDE_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/portguide', function () use ($app,$api) 
		{
			//port of interest (poi)
			$app->map('/poi', function () use ($app, $api) 
			{
				$api['PORT_GUIDE_Api']->hit(API_HIT_PORT_GUIDE_POI);
				return true;
			})->via('GET', 'POST'); 
			//port agent (get port name)
			$app->map('/agent', function () use ($app, $api) 
			{
				$api['PORT_GUIDE_Api']->hit(API_HIT_PORT_GUIDE_AGENT);
				return true;
			})->via('GET', 'POST'); 
			//get ports details (menu,title)
			$app->map('/getports1', function () use ($app, $api) 
			{
				$api['PORT_GUIDE_Api']->hit(API_HIT_PORT_GUIDE_PORTS1);
				return true;
			})->via('GET', 'POST'); 			
			//get ports details (menu,parent)
			$app->map('/getports2', function () use ($app, $api) 
			{
				$api['PORT_GUIDE_Api']->hit(API_HIT_PORT_GUIDE_PORTS2);
				return true;
			})->via('GET', 'POST'); 			
			//get table port 
			$app->map('/getport', function () use ($app, $api) 
			{
				$api['PORT_GUIDE_Api']->hit(API_HIT_PORT_GUIDE_PORT);
				return true;
			})->via('GET', 'POST'); 
			//get port details
			$app->map('/getportdetails', function () use ($app, $api) 
			{
				$api['PORT_GUIDE_Api']->hit(API_HIT_PORT_GUIDE_PORT_DTLS);
				return true;
			})->via('GET', 'POST'); 						
		}); //MAP PORT_GUIDE_Api
}); //MAP PORT_GUIDE_Api
 
  


/**--------------------------------------------------------------------------
|
|	@run LATEST_NEWS_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['LATEST_NEWS_Api'] = new LATEST_NEWS_Api(API_HIT_LATEST_NEWS_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA LATEST_NEWS_Api > ");
 
//@ LATEST_NEWS_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/latestnews', function () use ($app,$api) 
		{
			//latest-news-1
			$app->map('/news1', function () use ($app, $api) 
			{
				$api['LATEST_NEWS_Api']->hit(API_HIT_LATEST_NEWS_SEARCH1);
				return true;
			})->via('GET', 'POST'); 
			//latest-news-2
			$app->map('/news2', function () use ($app, $api) 
			{
				$api['LATEST_NEWS_Api']->hit(API_HIT_LATEST_NEWS_SEARCH2);
				return true;
			})->via('GET', 'POST'); 
			//latest-news-3
			$app->map('/news3', function () use ($app, $api) 
			{
				$api['LATEST_NEWS_Api']->hit(API_HIT_LATEST_NEWS_SEARCH3);
				return true;
			})->via('GET', 'POST'); 

		}); //MAP LATEST_NEWS_Api
}); //MAP LATEST_NEWS_Api

 
  
 
 


/**--------------------------------------------------------------------------
|
|	@run INBOX_MESSAGES_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['INBOX_MESSAGES_Api'] = new INBOX_MESSAGES_Api(API_HIT_INBOX_MESSAGES_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA INBOX_MESSAGES_Api > ");
 
//@ INBOX_MESSAGES_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/inbox', function () use ($app,$api) 
		{
			//inbox-msg-1
			$app->map('/messages1', function () use ($app, $api) 
			{
				$api['INBOX_MESSAGES_Api']->hit(API_HIT_INBOX_MESSAGES_SEARCH1);
				return true;
			})->via('GET', 'POST'); 
			//inbox-msg-2
			$app->map('/messages2', function () use ($app, $api) 
			{
				$api['INBOX_MESSAGES_Api']->hit(API_HIT_INBOX_MESSAGES_SEARCH2);
				return true;
			})->via('GET', 'POST'); 
			//set-read-flag
			$app->map('/flagread', function () use ($app, $api) 
			{
				$api['INBOX_MESSAGES_Api']->hit(API_HIT_INBOX_MESSAGES_FLAG_READ);
				return true;
			})->via('POST','PUT'); 

		}); //MAP INBOX_MESSAGES_Api
}); //MAP INBOX_MESSAGES_Api
 
 
 
  
 
 


/**--------------------------------------------------------------------------
|
|	@run EMAIL_MESSAGES_Api
|
|	ex: 
|          ($action=API_HIT_ENTRY_SEARCH,$slim=null,$showheader=false,$showres=true)
|
|	- ACTION
|
|	- SLIM
|
|   - SHOW-RESULT-HEADER(status-codes)
|         a. true  = show status-code
|         b. false = dont show status-code
|
|   - SHOW-RESULT-AS-JSON
|         a. true  = show json
|         b. false = dont show json
|
|
|----------------------------------------------------------------------------
**/


$api['EMAIL_MESSAGES_Api'] = new EMAIL_MESSAGES_Api(API_HIT_EMAIL_MESSAGES_SEARCH,$app,true,true);

$module++;
debug("api($module): VIA EMAIL_MESSAGES_Api > ");
 
//@ EMAIL_MESSAGES_Api ROUTES
$app->group('/websvc', function () use ($app, $api) 
{
		$app->group('/email', function () use ($app,$api) 
		{
			//scheduler
			$app->map('/scheduler', function () use ($app, $api) 
			{
				$api['EMAIL_MESSAGES_Api']->hit(API_HIT_EMAIL_MESSAGES_SCHEDULER);
				return true;
			})->via('GET', 'POST'); 
			//techsupport
			$app->map('/techsupport', function () use ($app, $api) 
			{
				$api['EMAIL_MESSAGES_Api']->hit(API_HIT_EMAIL_MESSAGES_TECHSUPPORT);
				return true;
			})->via('GET', 'POST'); 

		}); //MAP EMAIL_MESSAGES_Api
}); //MAP EMAIL_MESSAGES_Api
 
 
 
 
 
 
 
//----------------------------------------------------------------------------
//HTTP-NOT-FOUND > 404
//----------------------------------------------------------------------------
$app->notFound(function () use ($app,&$api) 
{
    $api['LDAP_Api']->send_reply(
					$api['LDAP_Api']->notfound(REST_RESP_404,
							       "WEB-SERVICE-API: Method not found!")
					);
});

 
 
/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();

debug("api(): Done!");




/*
//GET variable
$paramValue = $app->request->get('paramName');

//POST variable
$paramValue = $app->request->post('paramName');

//PUT variable
$paramValue = $app->request->put('paramName');
*/