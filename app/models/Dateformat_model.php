<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dateformat_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getManageDateformat($offset="",$record="", $countType="")
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

			if(empty($_GET['keywords'])){
				$keywords = 'NULL';
			}else{
				$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}
			
			$query = "select * from org_date_formats

			where 1=1
				and (
					org_date_formats.date_format like coalesce($keywords,org_date_formats.date_format) or 
					org_date_formats.date_format_description like coalesce($keywords,org_date_formats.date_format_description)
				)
				and org_date_formats.active_flag = if('".$active_flag."' = 'All',org_date_formats.active_flag,'".$active_flag."')
				order by org_date_formats.date_format_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}	
	}
	
	
}
