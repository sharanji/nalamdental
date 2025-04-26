<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category_banner_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getCategoryBanner($offset="",$record="", $countType="")
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

			if(empty($_GET['category_name'])){
				$category_name = 'NULL';
			}else{
				$category_name = "concat('%','".serchFilter($_GET['category_name'])."','%')";
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$query = "select 
			inv_category_banners.banner_id,
			inv_category_banners.category_name,
			inv_category_banners.category_description,
			inv_category_banners.active_flag,
			inv_category_banners.created_date,
			inv_category_banners.default_banner,
			branch.branch_id,
			branch.branch_name
			from inv_category_banners

			left join branch on branch.branch_id = inv_category_banners.branch_id

			where 1=1
			and inv_category_banners.category_name like coalesce($category_name,inv_category_banners.category_name)
			and inv_category_banners.active_flag = if('".$active_flag."' = 'All',inv_category_banners.active_flag,'".$active_flag."')
			
			order by inv_category_banners.banner_id desc
			$limit ";
			$result = $this->db->query($query)->result_array();
			//print_r($result);exit;
			return $result;
		}else{
			return array();
		}

	
	
	}
	
	
}
