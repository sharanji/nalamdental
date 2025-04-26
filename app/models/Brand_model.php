<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Brand_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getManageBrand($offset="",$record="" ,$countType="")
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
				$keywords = "concat('%','".$_GET['keywords']."','%')";
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			
			
			$query = "select * from brand
			
			where 1=1
				and (
					brand.brand_name like coalesce($keywords,brand.brand_name) 
					
				)
				and brand.active_flag = coalesce('".$active_flag."',brand.active_flag)
				order by brand.brand_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
}
