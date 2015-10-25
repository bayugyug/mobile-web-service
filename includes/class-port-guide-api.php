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


class PORT_GUIDE_Api extends Default_Api{

	
	protected $action = API_HIT_PORT_GUIDE_POI;
	
	/**
	* main API
	*
	*/
	function __construct($action=API_HIT_PORT_GUIDE_POI,$slim=null,$showheader=false,$showres=true)
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
				case API_HIT_PORT_GUIDE_POI:
					$this->do_entry_search_poi();
					break;
				case API_HIT_PORT_GUIDE_AGENT:
					$this->do_entry_search_port_name();
					break;
				case API_HIT_PORT_GUIDE_PORTS1:
					$this->do_entry_search_port_details1();
					break;
				case API_HIT_PORT_GUIDE_PORTS2:
					$this->do_entry_search_port_details2();
					break;
				case API_HIT_PORT_GUIDE_PORT:
					$this->do_entry_search_table_port();
					break;
				case API_HIT_PORT_GUIDE_PORT_DTLS:
					$this->do_entry_search_table_port_dtls();
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
	protected function do_entry_search_poi()
	{
		
			//get params
			$port  = trim($_REQUEST['port']);
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_port_of_interest(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'port'     => $port,
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
	
	//search
	protected function do_entry_search_port_name()
	{
		
			//get params
			$port  = trim($_REQUEST['port']);
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_port_of_agent(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'port'     => $port,
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
	
	
	//port-details (menu,title)
	protected function do_entry_search_port_details1()
	{
		
			//get params
			$menu  = trim($_REQUEST['menu']);
			$title = trim($_REQUEST['title']);
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_port_details1(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'menu'    => $menu,
							'title'    => $title,
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
	
	//port-details (menu,parent)
	protected function do_entry_search_port_details2()
	{
		
			//get params
			$menu   = trim($_REQUEST['menu']);
			$parent = trim($_REQUEST['parent']);
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_port_details2(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'menu'     => $menu,
							'parent'   => $parent,
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
	
	//port-table (no-params)
	protected function do_entry_search_table_port()
	{
		
			//get params
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_table_port(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
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
	
	
	
	//port-details (title)
	protected function do_entry_search_table_port_dtls()
	{
		
			//get params
			$title   = trim($_REQUEST['title']);
			
			//get paging
			$paging            = $this->paging();
			
			$res = $this->get_table_port_dtls(
						array(
							'page'     => $paging['pagex'],
							'batch'    => $paging['batch'],
							'title'    => $title,
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
	protected function get_port_of_interest($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_port_of_interest() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere   = array();
		
		//filter
		if(strlen($pdata['port']))
		{
			$t        = addslashes($pdata['port']);
			$xwhere[] = " AND title LIKE  CONCAT ( 'What to do in ','$t','%') ";
		}
		
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					id,
					title,
					introtext 
				FROM 
					tbl_content 
				WHERE 1=1
				$more	
				ORDER BY id
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_port_of_interest() : ERROR : $sql");

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
		
		debug("get_port_of_interest() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}

	//get data
	protected function get_port_of_agent($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_port_of_agent() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere   = array();
		
		//filter
		if(strlen($pdata['port']))
		{
			$t        = addslashes($pdata['port']);
			$xwhere[] = " AND port_name = '$t' ";
		}
	
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					id,
					port_name,
					port_information 
				FROM
					tbl_portdetails 
				WHERE 1=1
				$more	
				ORDER BY id
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_port_of_agent() : ERROR : $sql");

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
		
		debug("get_port_of_agent() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}


	//port-details-1
	protected function get_port_details1($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_port_details1() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere   = array();
		
		//filter (menu,title)
		$selectAll = ' * ';
		if(strlen($pdata['menu']))
		{
			$t         = addslashes($pdata['menu']);
			$xwhere[]  = " AND menutype = '$t' ";
			$selectAll = " id, menutype, title  ";
		}
		if(strlen($pdata['title']))
		{
			$t         = addslashes($pdata['title']);
			$xwhere[] = " AND title LIKE  '$t%' ";
		}
	
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					$selectAll
				FROM
					tbl_menu 
				WHERE 1=1
				AND  published =1 
					 $more	
				ORDER BY title ASC
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_port_details1() : ERROR : $sql");

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
		
		debug("get_port_details1() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}

	//port-details-2
	protected function get_port_details2($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_port_details2() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere   = array();
		
		//filter (menu,parent)
		$selectAll = ' * ';
		if(strlen($pdata['menu']))
		{
			$t         = addslashes($pdata['menu']);
			$xwhere[]  = " AND menutype = '$t' ";
		}
		if(strlen($pdata['parent']))
		{
			$t         = addslashes($pdata['parent']);
			$xwhere[] = " AND parent_id =  '$t' ";
		}
	
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					$selectAll
				FROM
					tbl_menu 
				WHERE 1=1
				AND  published =1 
					 $more	
				ORDER BY title ASC
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_port_details2() : ERROR : $sql");

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
		
		debug("get_port_details2() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}

	
	//table-port
	protected function get_table_port($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_table_port() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere   = array();
		
		//filter (menu,parent)
		$selectAll = ' * ';

		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = trim(@join("\n",$xwhere));

		//select jos_content
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					$selectAll
				
				FROM tbl_content 
				
				WHERE 1=1
					AND catid = '196' 
					AND state = '1' 
				ORDER BY ordering ASC
				$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_table_port() : ERROR : $sql");

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
		
		debug("get_table_port() : INFO : [ $sql => $is_ok ]");
		
		//free-up
		if($res) $gSqlDbSvc['DBCREWTRAVEL']->free($res);
		
		//give it back ;-)
		return $sdata;
		
	}

	//get table port details
	protected function get_table_port_dtls($pdata=array())
	{
		//globals here
		global $gSqlDb,$gSqlDbSvc;
	
		$dmp = @var_export($pdata,1);
		debug("get_table_port_dtls() : INFO : [ $dmp;]");
		
		//fmt
		$pg    = $pdata['page'];
		$mx    = $pdata['batch'];
			
		//filter
		$xwhere   = array();
		
		//filter (menu,parent)
		$selectAll = ' * ';
		if(strlen($pdata['title']))
		{
			$t         = addslashes($pdata['title']);
			$xwhere[]  = " AND title LIKE '%$t%' ";
		}
	
		//fmt-params
		$limit = " LIMIT $pg, $mx ";
		$more  = @join("\n",$xwhere);
		
		//select
		$sql = "SELECT SQL_CALC_FOUND_ROWS 
					id,
					title,
					introtext 
				FROM tbl_content 
				WHERE 1=1
					$more	
				ORDER BY title ASC
					$limit
		       ";
		
		$res   = $gSqlDbSvc['DBCREWTRAVEL']->query($sql, "get_table_port_dtls() : ERROR : $sql");

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
		
		debug("get_table_port_dtls() : INFO : [ $sql => $is_ok ]");
		
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