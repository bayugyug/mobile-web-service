<?php


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


class EMAIL_MESSAGES_Api extends Default_Api{

	
	protected $action = API_HIT_EMAIL_MESSAGES_SCHEDULER;
	
	/**
	* main API
	*
	*/
	function __construct($action=API_HIT_EMAIL_MESSAGES_SCHEDULER,$slim=null,$showheader=false,$showres=true)
	{
		//init
		$this->action = $action;
		
		//flags
		$this->SHOWRES = $showres;
		$this->SHOWHDR = $showheader;

		if($slim != null)
			$this->SLIM = $slim;
		
		//call ur mama, pls ;-)
		parent::__construct($this->SLIM,$this->SHOWHDR,$this->SHOWRES);
	}	

	//do=it
	public function hit($act=null)
	{
		global $_JWT;
		
		if($act != null)
		{
			$this->action = $act;
		}
		
		//chk
		$dmp = @var_export($_REQUEST,1);
		debug("PARAMS-REQUEST> $dmp");
		$dmp    = $this->action;
		debug("hit() : INFO : [ ACTION=$dmp; ENDPOINT-URI={$_SERVER['REQUEST_URI']}]");

		try{	
			//chk it
			switch($this->action)
			{
				case API_HIT_EMAIL_MESSAGES_SCHEDULER:
					$this->do_entry_email_scheduler();
					break;
				case API_HIT_EMAIL_MESSAGES_TECHSUPPORT:
					$this->do_entry_email_tech_support();
					break;
				//notfound
				default:	
					$this->send_reply($this->notfound());
			}
		} 
		catch (Exception $e) 
		{
			$dmp = @var_export($e->getMessage(),1);
			debug("hit() : EXCEPTION : [ $dmp; ]");
			
			$this->send_reply($this->notfound(
						HTTP_NOT_IMPLEMENTED,
						"WEB-SERVICE-API: Method not found ($dmp)!"
					));
			
		}	
	}	
	
	//search
	protected function do_entry_email_scheduler()
	{
		
			//get params
			//from
			$employeeId     = trim($_REQUEST['employeeid']   );
			$employeeEmail  = trim($_REQUEST['employeeemail']);
			$employeeName   = trim($_REQUEST['name']         );
			//to
			$schedulerEmail = trim($_REQUEST['scheduleremail']);
			//msg
			$subject        = trim($_REQUEST['subject']);
			$message        = trim($_REQUEST['message']);

			//sanity check -> LISTS
			if( 
			      !strlen($employeeId)     or
				  !strlen($employeeEmail)  or
				  !strlen($employeeName)   or
				  !strlen($schedulerEmail) or
				  !strlen($subject)        or
				  !strlen($message)  
			  )
			{
				//fmt reply 400
				$reply['statuscode'] = HTTP_BAD_REQUEST;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				
				return;
			}
			
			//chk-params
			if( (! $this->is_valid_email($employeeEmail) )
				or  
			    (! $this->is_valid_email($schedulerEmail) ) 
			)
			{
				//fmt reply 417
				$reply['statuscode'] = HTTP_EXPECTATION_FAILED;
				$reply['message']    = "Invalid parameters [Email format]!";
				//give it back
				$this->send_reply($reply);
				return;				
			}
			
			//get params
			$fromName       = sprintf("%s / %s", $employeeName,$employeeId);
			$employeeId     = trim($_REQUEST['employeeid']   );
			$employeeEmail  = trim($_REQUEST['employeeemail']);
			$employeeName   = trim($_REQUEST['name']         );
			//to
			$schedulerEmail = trim($_REQUEST['scheduleremail']);
			//msg
			$subject        = trim($_REQUEST['subject']);
			$message        = trim($_REQUEST['message']);			
						
			//email-obj
			$mail = new PHPMailer;
			$mail->isSendmail();
			
			//FROM
			$mail->setFrom($employeeEmail, $fromName);

			//REPLY-TO
			$mail->addReplyTo($employeeEmail, $fromName);

			//TO
			$mail->addAddress($schedulerEmail, $schedulerEmail);

			//SUBJECT
			$mail->Subject = sprintf("%s", $subject);
			
			//MESSAGE
			$mail->msgHTML($message);

			//MESSAGE-alt
			$mail->AltBody = "$message";

			//failed
			if (!$mail->send()) 
			{
				//chk
				$dmp = @var_export($mail->ErrorInfo,1);
				debug("EMAIl-SEND-FAILED> $dmp");

				//fmt reply 409
				$reply['statuscode'] = HTTP_CONFLICT;
				$reply['message']    = "Email Scheduler sent failed! [$dmp]";

				//give it back
				$this->send_reply($reply);
				return;
			}
			
			//fmt reply 200
			$reply['status']     = true;
			$reply['statuscode'] = HTTP_SUCCESS;
			$reply['message']    = "Email Scheduler sent successful!";
			$reply['uuid']       = md5($this->str_enc(sprintf("%s-%s",mt_rand(),uniqid(true))));
			$this->send_reply($reply);
			
	}
	
	
	
	
	//search
	protected function do_entry_email_tech_support()
	{
		
			//get params
			//from
			$employeeId     = trim($_REQUEST['employeeid']   );
			$employeeEmail  = trim($_REQUEST['employeeemail']);
			$employeeName   = trim($_REQUEST['name']         );
			//to
			$schedulerEmail = trim($_REQUEST['scheduleremail']);
			//msg
			$subject        = trim($_REQUEST['subject']);
			$message        = trim($_REQUEST['message']);

			//sanity check -> LISTS
			if( 
			      !strlen($employeeId)     or
				  !strlen($employeeEmail)  or
				  !strlen($employeeName)   or
				  !strlen($schedulerEmail) or
				  !strlen($subject)        or
				  !strlen($message)  
			  )
			{
				//fmt reply 400
				$reply['statuscode'] = HTTP_BAD_REQUEST;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				
				return;
			}
			
			//chk-params
			if( (! $this->is_valid_email($employeeEmail) )
				or  
			    (! $this->is_valid_email($schedulerEmail) ) 
			)
			{
				//fmt reply 417
				$reply['statuscode'] = HTTP_EXPECTATION_FAILED;
				$reply['message']    = "Invalid parameters [Email format]!";
				//give it back
				$this->send_reply($reply);
				return;				
			}
			
			//get params
			$fromName       = sprintf("%s / %s", $employeeName,$employeeId);
			$employeeId     = trim($_REQUEST['employeeid']   );
			$employeeEmail  = trim($_REQUEST['employeeemail']);
			$employeeName   = trim($_REQUEST['name']         );
			
			//to
			$schedulerEmail = trim($_REQUEST['scheduleremail']);
			
			//msg
			$subject        = trim($_REQUEST['subject']);
			$message        = trim($_REQUEST['message']);			
						
			//email-obj
			$mail = new PHPMailer;
			$mail->isSendmail();
			
			//FROM
			$mail->setFrom($employeeEmail, $fromName);

			//REPLY-TO
			$mail->addReplyTo($employeeEmail, $fromName);

			//TO
			$mail->addAddress($schedulerEmail, $schedulerEmail);

			//SUBJECT
			$mail->Subject = sprintf("%s", $subject);
			
			//MESSAGE
			$mail->msgHTML($message);

			//MESSAGE-alt
			$mail->AltBody = "$message";

			//failed
			if (!$mail->send()) 
			{
				//chk
				$dmp = @var_export($mail->ErrorInfo,1);
				debug("EMAIl-SEND-FAILED> $dmp");

				//fmt reply 409
				$reply['statuscode'] = HTTP_CONFLICT;
				$reply['message']    = "Email Tech Support sent failed! [$dmp]";

				//give it back
				$this->send_reply($reply);
				return;
			}
			
			//fmt reply 200
			$reply['status']     = true;
			$reply['statuscode'] = HTTP_SUCCESS;
			$reply['message']    = "Email Tech Support sent successful!";
			$reply['uuid']       = md5($this->str_enc(sprintf("%s-%s",mt_rand(),uniqid(true))));
			$this->send_reply($reply);
			
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}//class	
?>