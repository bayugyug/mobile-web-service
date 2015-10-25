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


class INBOX_MESSAGES_Api extends Default_Api{

	
	protected $action = API_HIT_INBOX_MESSAGES_SEARCH1;
	
	/**
	* main API
	*
	*/
	function __construct($action=API_HIT_INBOX_MESSAGES_SEARCH1,$slim=null,$showheader=false,$showres=true)
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

		
		try 
		{
			
			//chk it
			switch($this->action)
			{
				case API_HIT_INBOX_MESSAGES_SEARCH1:
					$this->do_entry_search_inbox_msg1();
					break;
				case API_HIT_INBOX_MESSAGES_SEARCH2:
					$this->do_entry_search_inbox_msg2();
					break;
				case API_HIT_INBOX_MESSAGES_FLAG_READ:
					$this->do_entry_set_flag_read();
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
	
	//search:1
	protected function do_entry_search_inbox_msg1()
	{
		
			//get params
			$user              = trim($_REQUEST['user']);
			$lastMsgId         = @intval(trim($_REQUEST['lastMsgId']));
			
			//sanity check -> LISTS
			if( !strlen($user)  )
			{
				//fmt reply 500
				$reply['statuscode'] = HTTP_BAD_REQUEST;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				
				return;
			}

			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_list_inbox_msg1(
						array(
							'page'       => $paging['pagex'],
							'batch'      => $paging['batch'],
							'lastMsgId'  => $lastMsgId,
							'user'       => $user,
							)
						);
						
			if(!$res['exists'])
			{
				//fmt reply 404
				$reply['statuscode'] = HTTP_NOT_FOUND;
				$reply['message']    = "No List found!";
				//give it back
				$this->send_reply($reply);
				return;
			}
			
			//fmt reply 200
			$reply['status']     = true;
			$reply['statuscode'] = HTTP_SUCCESS;
			$reply['message']    = "List(s) found!";
			$reply['result']     = $res['data'];
			$reply['batch']      = @intval(@count($res['data']));
			
			//paging
			$reply['totalrows']  = $res['totalrows']['total'];
			$reply['currpage']   = (@count($res['data'])) ? ( $paging['page']    ) : 1;
			$reply['nextpage']   = (@count($res['data'])) ? ( $paging['page'] + 1) : 1;
			
			$this->send_reply($reply);
			
	}
	
	//search:2
	protected function do_entry_search_inbox_msg2()
	{
		
			//get params
			$user         = trim($_REQUEST['user']);
			
			//get paging
			$paging            = $this->paging();
			
			//sanity check -> LISTS
			if( !strlen($user)  )
			{
				//fmt reply 500
				$reply['statuscode'] = HTTP_BAD_REQUEST;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				
				return;
			}

			
			$res = $this->get_list_inbox_msg2(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'user'       => $user,
							)
						);
						
			if(!$res['exists'])
			{
				//fmt reply 404
				$reply['statuscode'] = HTTP_NOT_FOUND;
				$reply['message']    = "No List found!";
				//give it back
				$this->send_reply($reply);
				return;
			}
			
			//fmt reply 200
			$reply['status']     = true;
			$reply['statuscode'] = HTTP_SUCCESS;
			$reply['message']    = "List(s) found!";
			$reply['result']     = $res['data'];
			$reply['batch']      = @intval(@count($res['data']));
			
			//paging
			$reply['totalrows']  = $res['totalrows']['total'];
			$reply['currpage']   = (@count($res['data'])) ? ( $paging['page']    ) : 1;
			$reply['nextpage']   = (@count($res['data'])) ? ( $paging['page'] + 1) : 1;
			
			$this->send_reply($reply);
			
	}
	
	//set flag=1
	protected function do_entry_set_flag_read()
	{
		
			//get params
			$id         = @intval(trim($_REQUEST['id']));
			
			if($id  <= 0)
			{
				//fmt reply 500
				$reply['statuscode'] = HTTP_BAD_REQUEST;
				$reply['message']    = "Invalid parameters!";

				//give it back
				$this->send_reply($reply);
				return;
			}
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->set_list_inbox_flag_read(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'id'       => $id,
							)
						);
						
			
			//fmt reply 200
			$reply['status']     = true;
			$reply['statuscode'] = HTTP_SUCCESS;
			$reply['message']    = "Update is successful!";
			
			$this->send_reply($reply);
			
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------------------
	//get data
	protected function get_list_inbox_msg1($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_list_inbox_msg1() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere = array();
		if(1)
		{
			$t        = addslashes($pdata['user']);
			$xwhere[] = " AND recipient = '$t' ";
		}
		if(strlen($lastMsgId))
		{

			$t        = addslashes($pdata['lastMsgId']);
			$xwhere[] = " AND id > '$t' ";	
			
		}
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					id,
					sender,
					recipient,
					message,
					status 
				FROM 
					messages 
				 WHERE  1=1
					$more
				ORDER BY id
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_list_inbox_msg1() : ERROR : $sql");

		//total-rows
		$is_ok = $gSqlDbSvc['DBCREWTRAVEL']->numRows($res);
		$data  = array();
		$sdata = array('exists' => intval($is_ok));
		
		//get data
		if($is_ok>0)
		{
			while($strow = $gSqlDbSvc['DBCREWTRAVEL']->getAssoc($res))
			{
				$data[] = $strow;
			}
		}
		
		//save
		$sdata['data']      = $data;
		$sdata['totalrows'] = $this->get_total_rows();
		
		debug("get_list_inbox_msg1() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}

	//get data
	protected function get_list_inbox_msg2($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);

		debug("get_list_inbox_msg2() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];

				
		//filter
		$xwhere = array();
				
		if(1)
		{
			$t        = addslashes($pdata['user']);
			$xwhere[] = " AND toid = '$t' ";
		}
		
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					 toread,
					 tbl_uddeim.id as id,
					 fromid,
					 toid,
					 message,
					 datum,
					 short_message,
					 tbl_users.name as name,
					 systemmessage,
					 toread					 
				 from tbl_uddeim 
					join tbl_users on tbl_uddeim.fromid = tbl_users.id 
				 WHERE  1=1
				    AND totrash = 0 
					$more
				ORDER BY id desc
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_list_inbox_msg2() : ERROR : $sql");

		//total-rows
		$is_ok = $gSqlDbSvc['DBCREWTRAVEL']->numRows($res);
		$data  = array();
		$sdata = array('exists' => intval($is_ok));
		
		//get data
		if($is_ok>0)
		{
			while($strow = $gSqlDbSvc['DBCREWTRAVEL']->getAssoc($res))
			{
				$data[] = $strow;
			}
		}
		
		//save
		$sdata['data']      = $data;
		$sdata['totalrows'] = $this->get_total_rows();
		
		debug("get_list_inbox_msg2() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}
	
	//latest news 3
	protected function set_list_inbox_flag_read($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("set_list_inbox_flag_read() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere = array();
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		$t     = addslashes(trim($pdata['id']));
		
		//select
		$sql = "UPDATE 
				tbl_uddeim
				SET  toread = 1 
				WHERE 
					id = '$t'
				LIMIT 1
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "set_list_inbox_flag_read() : ERROR : $sql");

		//total-rows
		$is_ok = $gSqlDbSvc['DBCREWTRAVEL']->updRows($res);
		
		$data  = array();
		$sdata = array('exists' => intval($is_ok));
		
		//save
		debug("set_list_inbox_flag_read() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//get data
	protected function get_total_rows()
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;

		debug("get_total_rows() : INFO : ");
		
		//select
		$sql = "SELECT FOUND_ROWS() as rows";
		$res = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_total_rows() : ERROR : $sql");

		//total-rows
		$is_ok = $gSqlDbSvc['DBCREWTRAVEL']->numRows($res);
		$data  = array();
		$sdata = array('exists' => intval($is_ok));
		
		//get data
		if($is_ok>0)
		{
			$strow = $gSqlDbSvc['DBCREWTRAVEL']->getAssoc($res);
			$total = intval($strow['rows']);
		}
		
		//save
		$sdata['total'] = $total;
		
		debug("get_total_rows() : INFO : [ $sql => $is_ok / $total;]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}
	
	
}//class	
?>