<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Locator_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getSubInventory($offset="",$record="",$countType="")
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

			if(empty($_GET['organization_id'])){
				$organization_id = 'NULL';
			}else{
				$organization_id = $_GET['organization_id'];
			}


			$active_flag = $_GET['active_flag'];
			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";

			$query = "select inv_item_sub_inventory.*,org_organizations.organization_code,org_organizations.organization_name from inv_item_sub_inventory
			left join org_organizations on 
			org_organizations.organization_id = inv_item_sub_inventory.organization_id
			where 1=1
				and inv_item_sub_inventory.organization_id = coalesce($organization_id,inv_item_sub_inventory.organization_id)
				and (
					inv_item_sub_inventory.inventory_code like coalesce($keywords,inv_item_sub_inventory.inventory_code) or 
					inv_item_sub_inventory.inventory_name like coalesce($keywords,inv_item_sub_inventory.inventory_name)				
				)
				and inv_item_sub_inventory.active_flag = if('".$active_flag."' = 'All',inv_item_sub_inventory.active_flag,'".$active_flag."')
				order by inv_item_sub_inventory.inventory_id desc $limit";
			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}
	
	function getLocators($offset="",$record="", $id="",$status="",$countType="")
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

			$query = "select * from inv_item_locators
			where 1=1
				and inv_item_locators.organization_id='".$id."'
				and inv_item_locators.inventory_id='".$status."'
				and (
					inv_item_locators.locator_no like coalesce($keywords,inv_item_locators.locator_no) or
					inv_item_locators.locator_name like coalesce($keywords,inv_item_locators.locator_name)	
                  )
				and inv_item_locators.active_flag = if('".$active_flag."' = 'All',inv_item_locators.active_flag,'".$active_flag."')
				order by inv_item_locators.inventory_id desc $limit";
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
}
