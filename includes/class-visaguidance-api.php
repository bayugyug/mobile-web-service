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
				case API_HIT_VG_NATIONALITY_SEARCH:
					$this->do_nationality_search();
					break;
					
				case API_HIT_VG_DESTINATION_SEARCH:
					$this->do_destination_search();
					break;
					
				case API_HIT_VISAGUIDANCE_SEARCH:
					$this->do_entry_search();
					break;
				case API_HIT_VG_VISATYPE_SEARCH:
					$this->do_visatype_search();
					break;
				//notfound
				default:	
					echo 'tite';
					exit();
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
	
	//search nationality
	protected function do_nationality_search()
	{
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_nationalities(
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
			
			$this->send_reply($reply);
			
	}
	

	
	//search nationality
	protected function do_destination_search()
	{
			$country_code  = @trim($_REQUEST['country_code']);
			
			//sanity check -> LISTS
			/*
			if(
				!strlen($country_code) 
			)
			{
				//fmt reply 500
				$reply['statuscode'] = HTTP_INTERNAL_SERVER_ERROR;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				return;
			}
			*/
			
			$res = $this->get_countries(
							array(
							'country_code'=> $country_code,
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
			
			$this->send_reply($reply);
			
	}
	
	
	//search visatype
	protected function do_visatype_search()
	{
			$nationality_code  = trim($_REQUEST['nationality_code']);
			$destination_code  = trim($_REQUEST['destination_code']);
			if(
				!strlen($destination_code) || !strlen($nationality_code)
			)
			{
				//fmt reply 500
				$reply['statuscode'] = HTTP_INTERNAL_SERVER_ERROR;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				return;
			}
			
			
			$res = $this->get_visatypes(
							array(
							'nationality_code'=> $nationality_code,
							'destination_code'=> $destination_code,
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
			//paging
			$reply['totalrows']  = $res['totalrows']['total'];
			
			$this->send_reply($reply);
			
	}
	
	//search
	protected function do_entry_search()
	{
			//get params
			$nationality_code  = trim($_REQUEST['nationality_code']);
			$destination_code  = trim($_REQUEST['destination_code']);
			$visa_type    = trim($_REQUEST['visa_type']);
			
			if(
				!strlen($visa_type) || 
				!strlen($nationality_code) || 
				!strlen($destination_code)
			)
			{
				//fmt reply 500
				$reply['statuscode'] = HTTP_INTERNAL_SERVER_ERROR;
				$reply['message']    = "Invalid parameters!";
				//give it back
				$this->send_reply($reply);
				return;
			}
			
			$res = $this->get_page_list(
						array(
							'visa_type'    => $visa_type,
							'nationality_code'    => $nationality_code,
							'destination_code'    => $destination_code,
							
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
			// $reply['batch']      = @intval(@count($res['data']));
			
			//paging
			$reply['totalrows']  = $res['totalrows']['total'];
			// $reply['currpage']   = (@count($res['data'])) ? ( $paging['page']    ) : 1;
			// $reply['nextpage']   = (@count($res['data'])) ? ( $paging['page'] + 1) : 1;
			
			$this->send_reply($reply);
			
	}
	
	

	
	
	
	
	protected function get_nationalities()
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_nationalities() : INFO : [ $dmp;]");
		
		//filter
		$xwhere    = array();
		
		//fmt-params
		//$limit = " LIMIT $pg, $mx ";
		$limit = " " ;
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "select a.id, concat(a.nationality, ' (', b.name, ')') AS name, b.country_code
				FROM tbl_nationality a, tbl_port_country b
				WHERE 1=1
					AND a.country_code = b.id 
				ORDER BY a.id
				$limit
		       ";
			   
		// echo $sql;
		// exit();
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_nationalities() : ERROR : $sql");

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
		
		debug("get_nationalities() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
	}
	
	protected function get_countries($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_countries() : INFO : [ $dmp;]");
		
		//filter
		$xwhere    = array();
		
		$selectAll = ' id,country_code,name ';
		
		//filter
		if(strlen($pdata['country_code']))
		{
			$t        = addslashes($pdata['country_code']);
			$xwhere[] = " AND country_code != '$t' ";
				
		}
		
		//fmt-params
		//$limit = " LIMIT $pg, $mx ";
		$limit = " " ;
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
				  $selectAll
				FROM tbl_port_country 
				WHERE 1=1
					$more
				ORDER BY id
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_countries() : ERROR : $sql");

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
		
		debug("get_nationalities() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
	}
	
	protected function get_visatypes($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_visatypes() : INFO : [ $dmp;]");
		
		//filter
		$xwhere    = array();
		
		$selectAll = ' id,visa_type ';

		
		//filter
		
		if(strlen($pdata['nationality_code']))
		{
			$t        = addslashes($pdata['nationality_code']);
			$xwhere[] = " AND nationality_code = '$t' ";
				
		}
		
		if(strlen($pdata['destination_code']))
		{
			$t        = addslashes($pdata['destination_code']);
			$xwhere[] = " AND destination_code = '$t' ";
				
		}
		
		//fmt-params
		//$limit = " LIMIT $pg, $mx ";
		$limit = " " ;
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
				  $selectAll
				FROM tbl_visa_guidance 
				WHERE 1=1
					$more
				ORDER BY id
				$limit
		       ";
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_countries() : ERROR : $sql");

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
		
		debug("get_visatypes() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
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
		$selectAll = ' id,state,additional_info,requirements,other_requirements,ordering ';
	
		//filter
		if(strlen($pdata['nationality_code']))
		{
			$t        = addslashes($pdata['nationality_code']);
			$xwhere[] .= " AND nationality_code = '$t' ";
				
		}
			
		if(strlen($pdata['destination_code']))
		{
			$u        = addslashes($pdata['destination_code']);
			$xwhere[] .= " AND destination_code = '$u' ";
		}
		
		if(strlen($pdata['visa_type']))
		{
			$v        = addslashes($pdata['visa_type']);
			$xwhere[] .= " AND visa_type = '$v' ";
		}
	
		
		//fmt-params
		// $limit = " LIMIT $pg, $mx ";
		
		$limit = " ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT  $selectAll
				FROM tbl_visa_guidance 
				WHERE 1=1 $more
				ORDER BY id
				$limit
		       ";
		// echo $sql;
		// exit();
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