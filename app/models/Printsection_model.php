<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Printsection_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManagePrintsectionCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
						org_print_section_types.type_name like "%'.serchFilter($_GET['keywords']).'%" 
						)
						';
		}
		$query = "select type_id from org_print_section_types
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManagePrintsection($offset="",$record="")
	{
		if($_GET)
		{
			$active_flag = $_GET['active_flag'];
			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";

			$query = "select * from org_print_section_types
			where 1=1
				and (
					org_print_section_types.type_name like coalesce($keywords,org_print_section_types.type_name)  
					
				)
				and org_print_section_types.active_flag = if('".$active_flag."' = 'All',org_print_section_types.active_flag,'".$active_flag."')
				order by org_print_section_types.type_id desc ";
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
	 /*{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
						org_print_section_types.type_name like "%'.($_GET['keywords']).'%" 
						)
						';
		}

	  	$query = "select 
					org_print_section_types.location_id,
					org_print_section_types.location_name,
					org_print_section_types.active_flag
				from org_print_section_types
			where 1=1
				and org_print_section_types.type_name like coalesce($keywords,org_print_section_types.type_name) 
				and org_print_section_types.active_flag = if('".$active_flag."' = 'All',org_print_section_types.active_flag,'".$active_flag."')
				order by org_print_section_types.type_id desc
				$limit";
			$result = $this->db->query($query)->result_array();
			return $result; 
		$query = "select * from org_print_section_types
		where $condition
				order by type_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result; 
	}*/
	
}
