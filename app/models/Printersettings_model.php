<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Printersettings_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManagePrintersettingsCount()
	{
		$condition = " 1=1";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							branch.branch_code like "%'.serchFilter($_GET['keywords']).'%" or
							branch.branch_name like "%'.serchFilter($_GET['keywords']).'%"
						)
						';
		}

		$query = "select header_id from org_print_count_header
		
		left join branch on 
			branch.branch_id = org_print_count_header.branch_id
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManagePrintersettings($offset="",$record="")
	{
		$condition = " 1=1";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							branch.branch_code like "%'.serchFilter($_GET['keywords']).'%" or
							branch.branch_name like "%'.serchFilter($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select 
		org_print_count_header.*,
		branch.branch_code,
		branch.branch_name

		from org_print_count_header

		left join branch on 
			branch.branch_id = org_print_count_header.branch_id

		where $condition
			order by header_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	public function getRecord($id)
	{
		$sql = "select 
			org_print_count_header.*
				from org_print_count_header 
			where org_print_count_header.header_id = ?";
			
		if($query = $this->db->query($sql,array($id))){
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	public function getMenuitems($header_id="")
	{
		$query = "select 
			org_print_count_line.*
				
				from org_print_count_line
				
		where org_print_count_line.header_id ='".$header_id."'
				
		";
		
		$result = $this->db->query($query)->result();
		return $result;

	}
	
	
}
