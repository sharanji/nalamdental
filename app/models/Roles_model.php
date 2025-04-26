<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Roles_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getRoles($offset="",$record="", $countType="")
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
			
			$query = "select org_roles.*from org_roles

				where 1=1
				and (
					org_roles.role_name like coalesce($keywords,org_roles.role_name) or 
					org_roles.role_description like coalesce($keywords,org_roles.role_description)
				)
				and org_roles.active_flag = if('".$active_flag."' = 'All',org_roles.active_flag,'".$active_flag."')
				order by org_roles.role_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
	
	function getRolesMenusCount($id="")
	{
		$condition = "1=1 and
			org_roles_items.role_id='".$id."' and 
				org_menus.menu_layer=1
		";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_menus.menu_name like "%'.serchFilter($_GET['keywords']).'%"
							)';
		}
		
		$query = "select org_roles_items.* from org_roles_items 
		left join org_roles on org_roles.role_id = org_roles_items.role_id
		left join org_menus on org_menus.menu_id = org_roles_items.menu_id
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getRolesMenus($offset="",$record="",$id="")
	{
		$condition = "1=1 and
			org_roles_items.role_id='".$id."' and 
				org_menus.menu_layer=1
		";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_menus.menu_name like "%'.serchFilter($_GET['keywords']).'%"
							)';
		}
		
		$query = "select org_roles_items.*,org_menus.menu_name,org_menus.menu_id from org_roles_items 
		
		left join org_roles on org_roles.role_id = org_roles_items.role_id
		
		left join org_menus on org_menus.menu_id = org_roles_items.menu_id
		where $condition
			order by org_roles_items.role_item_id asc
				limit ".$record." , ".$offset."
					";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getRolesAll()
	{
		$query = "select 
		roles.role_id, 
		roles.role_name 
		from org_roles as roles
		where 1=1
		and roles.role_status=1";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
