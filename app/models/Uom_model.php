<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Uom_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getManageUom($offset="",$record="", $countType="")
	{
		if($_GET)
		{
			if($countType == 1) #GetTotalCount
			{
				$limit = "";
			}
			else if($countType == 2) #Get Page Wise Count
			{
				$limit = "limit ".$record." , ".$offset." "; 
			}

			$active_flag = $_GET['active_flag'];
			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";

			$query = "select * from uom
			where 1=1
				and (
					uom.uom_code like coalesce($keywords,uom.uom_code) or 
					uom.uom_description like coalesce($keywords,uom.uom_description)
				)
				and uom.active_flag = if('".$active_flag."' = 'All',uom.active_flag,'".$active_flag."')
				order by uom.uom_id desc $limit";
			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}

	function getUomAll() 
	{
		$query = "SELECT uom.uom_id, uom.uom_code FROM uom
					WHERE 1=1
					and uom.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
}
