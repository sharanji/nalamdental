<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Discount_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageDiscount($offset="",$record="",$countType="")
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

			$query = "select * from discount
			where 1=1
				and (
					discount.discount_name like coalesce($keywords,discount.discount_name) or 
					discount.discount_value like coalesce($keywords,discount.discount_value)
				)
				and discount.active_flag = if('".$active_flag."' = 'All',discount.active_flag,'".$active_flag."')
				order by discount.discount_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
}
