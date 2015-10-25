<?php
//gtalk
include_once('init.php');

//gtalk
include_once('jwt-init.php');

use \Firebase\JWT\JWT;
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


class Default_Api{

	public $SHOWRES = false;
	public $SHOWHDR = false;
	public $SLIM     = null;
	public $LIMIT    = 10;
	
	
	/**
	* main API
	*
	*/
	function __construct($slim=null,$showheader=false,$showres=true)
	{
		//init
		$this->SHOWRES = $showres;
		$this->SHOWHDR = $showheader;
		$this->SLIM    = $slim;
	}	
	
	public function init_resp()
	{
		return array(
				'status'      => false,
				'statuscode'  => HTTP_NOT_FOUND,
				'result'      => array(),
				'message'     => null,
				);
		
	}
	
	//paging
	public function paging()
	{
		
		$batch   = @intval(trim($_REQUEST['batch']) );
		$page    = @intval(trim($_REQUEST["page"] ) );

		//default
		if($batch <= $this->LIMIT)
			$batch = $this->LIMIT;

		//calc
		$pagex = ($page <= 1) ?  (0) : (($page-1)*$batch);

		return array(
			'page'            => (($page < 1) ? (1) : ($page)),
			'batch' => $batch,
			'pagex'           => $pagex,
		);
	}
	//msg
	public function send_reply($reply=array())
	{
		
		$code = ($reply['statuscode'] > 0) ? ($reply['statuscode']) :(REST_RESP_510);
		
		//send header
		if($this->SHOWHDR and $this->SLIM != null)
		{
			debug("send_reply() : MESSAGE-RESULTS-HEADER : [ $code; ]");
			$this->SLIM->response->setStatus($code);	
		}
		
		if($this->SHOWRES)
		{
			$dmp = @var_export($reply,1);
			debug("send_reply() : MESSAGE-RESULTS-JSON : [ $dmp; ]");
			echo json_encode($reply);
		}
			
	}

	//error
	public function notfound($code=HTTP_UNAUTHORIZED, $msg='Method not found!')
	{
			//HTTP_UNAUTHORIZED
			return array(
					'status'      => false,
					'statuscode'  => $code,
					'result'      => array(),
					'message'     => $msg,
					'authsid'     => session_id(),
			);

	}
	
	//encrypt
	public function str_enc($word='')
	{
		//give it back
		return  base64_encode(openssl_encrypt(
					base64_encode($word),       
					LDAP_API_ENC_METHOD, 
					LDAP_API_ENC_PASS, 
					false, 
					LDAP_API_ENC_IV) );
	}
	
	//decrypt
	public function str_dec($word='')
	{
		//give it back
		return rtrim( base64_decode( openssl_decrypt(
				base64_decode($word), 
				LDAP_API_ENC_METHOD, 
				LDAP_API_ENC_PASS, 
				false, 
				LDAP_API_ENC_IV ) ), "\0" );
	
	}
	
	//chk
	public function is_valid_email($email='')
	{
		$patt  = "/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/i";
		return @preg_match($patt,$email);
	}	
	
	
}//class	
?>