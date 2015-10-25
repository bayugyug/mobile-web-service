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


class VISA_GUIDANCE_Api extends Default_Api{

	
	protected $action = API_HIT_VISAGUIDANCE_SEARCH;
	
	/**
	* main API
	*
	*/
	function __construct($action=API_HIT_VISAGUIDANCE_SEARCH,$slim=null,$showheader=false,$showres=true)
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
				case API_HIT_VISAGUIDANCE_SEARCH:
					$this->do_entry_search();
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
	protected function do_entry_search()
	{
			//get params
			$country      = trim($_REQUEST['country']);
			$nationality  = trim($_REQUEST['nationality']);
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_page_list(
						array(
							'page'       => $paging['pagex'],
							'batch'      => $paging['batch'],
							'country'    => $country,
							'nationality'=> $nationality,
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------------------
	//get data
	protected function get_page_list($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_page_list() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
		
		//filter
		$xwhere    = array();
		$selectAll = ' id,country_iatacode,nationality ';

		//filter
		if(strlen($pdata['nationality']))
		{
			$t        = addslashes($pdata['nationality']);
			$xwhere[] = " AND country_iatacode = '$t' ";
				
		}
			
		if(strlen($pdata['country']))
		{
			$t        = addslashes($pdata['country']);
			$selectAll= " id, $t ";
		}
		
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
				  $selectAll
				FROM tbl_visa_requirements_view 
				WHERE 1=1
					$more
				ORDER BY id
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_page_list() : ERROR : $sql");

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
		
		debug("get_page_list() : INFO : [ $sql => $is_ok ]");
		
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