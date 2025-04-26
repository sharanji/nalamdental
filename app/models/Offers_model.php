<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Offers_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getManageOffers($offset="",$record="", $countType="")
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

			$query = "select inv_item_offers.*,branch.branch_name
			from inv_item_offers
			left join branch on branch.branch_id = inv_item_offers.branch_id
			
			where 1=1
				and (
					inv_item_offers.offer_percentage like coalesce($keywords,inv_item_offers.offer_percentage) 
					or branch.branch_name like coalesce($keywords,branch.branch_name)
				)
				and inv_item_offers.active_flag = if('".$active_flag."' = 'All',inv_item_offers.active_flag,'".$active_flag."')
				order by inv_item_offers.offer_id desc $limit";
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
	
}
