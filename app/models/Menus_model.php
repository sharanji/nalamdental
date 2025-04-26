<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menus_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getMenusCount()
	{
		$condition = "1=1 and menu_layer=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								menu_name like "%'.serchFilter($_GET['keywords']).'%" or
								menu_description like "%'.serchFilter($_GET['keywords']).'%"
							)';
		}
		$query = "select menu_id from org_menus where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getMenus($offset="",$record="",$countType="")
	{
		if($_GET)
		{
			/* $condition = "1=1 and menu_layer=1";
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
									menu_name like "%'.($_GET['keywords']).'%" or
									menu_description like "%'.($_GET['keywords']).'%"
								)';
			} */

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
			
			$query = "select * from org_menus
			where 1=1
			and main_menu_id = 0
			and org_menus.menu_name like coalesce($keywords,org_menus.menu_name) 
			and org_menus.active_flag = if('".$active_flag."' = 'All',org_menus.active_flag,'".$active_flag."')
			order by org_menus.menu_id desc
			$limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}else{
			return array();
		}
	}
	
	function getSubMenusCount($menu_id="",$menu_layer="")
	{
		$condition = "1=1 and 
			main_menu_id='".$menu_id."' and 
				menu_layer='".$menu_layer."' ";
				
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								menu_name like "%'.serchFilter($_GET['keywords']).'%" or
								menu_description like "%'.serchFilter($_GET['keywords']).'%" or
								active_flag like "%'.serchFilter($_GET['keywords']).'%"
							)';
		}
		$query = "select menu_id from org_menus where $condition";
		$result = $this->db->query($query)->result_array();
		
		return count($result);
	}
	
	function getSubMenus($offset="",$record="",$menu_id="",$menu_layer="")
	{
		$condition = "1=1 and 
			main_menu_id='".$menu_id."' and 
				menu_layer='".$menu_layer."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								menu_name like "%'.serchFilter($_GET['keywords']).'%" or
								menu_description like "%'.serchFilter($_GET['keywords']).'%" or
								active_flag like "%'.serchFilter($_GET['keywords']).'%"
							)';
		}
		
		$query = "select * from org_menus
					where $condition
					order by menu_id desc
					
					limit ".$record." , ".$offset."
					";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
