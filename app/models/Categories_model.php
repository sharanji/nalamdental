<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Categories_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageCategories($offset="",$record="", $countType="")
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

			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			$active_flag = $_GET['active_flag'];

			$query = "select 
				category.category_id,
				category.category_name,
				category.category_description,
				category.cat_level_1,
				category.cat_level_2,
				category.cat_level_3,
				category.active_flag
				from inv_categories as category
			where 1=1
			and category.category_name like coalesce($keywords,category.category_name)
			and category.active_flag = if('".$active_flag."' = 'All',category.active_flag,'".$active_flag."')
			order by category.category_id desc
			$limit ";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function getBlogCategory(){
		$query = "select 
		ltv.list_type_value_id,
		ltv.list_code,
		ltv.list_value,
		count(blogs.blog_id) as blog_count
		from sm_list_type_values as ltv
		left join blogs on blogs.blog_category = ltv.list_code
		where 1=1
		and ltv.active_flag = '".$this->active_flag."'
		and blogs.active_flag = '".$this->active_flag."'
		group by ltv.list_type_value_id
		having count(blogs.blog_id) > 0";

		$result = $this->db->query($query)->result_array();
		return $result;
		
	}
	
}
