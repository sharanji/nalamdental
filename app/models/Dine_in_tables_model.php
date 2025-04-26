<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dine_in_tables_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getDineInTables($offset="",$record="",$countType="")
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

			if(empty($_GET['branch_id'])){
				$branch_id = 'NULL';
			}else{
				$branch_id = $_GET['branch_id'];
			}

			if(empty($_GET['table_location_id'])){
				$table_location_id = 'NULL';
			}else{
				$table_location_id = $_GET['table_location_id'];
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$query = "select 
			header_tbl.*,
			branch.branch_name,
			ltv.list_value as table_location,
			branch.branch_code 
			
			from din_table_headers as header_tbl
			left join branch on branch.branch_id = header_tbl.branch_id

			left join sm_list_type_values as ltv on ltv.list_type_value_id = header_tbl.table_location_id

			where 1=1
			and header_tbl.branch_id = coalesce($branch_id,header_tbl.branch_id)
			and header_tbl.table_location_id = coalesce($table_location_id,header_tbl.table_location_id)
			and header_tbl.active_flag = if('".$active_flag."' = 'All',header_tbl.active_flag,'".$active_flag."')
			order by header_tbl.header_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			
			return $result;
		}
		else{
			return array();
		}
	}
	
	public function getRecord($id="")
	{
		$sql = "select vb_branch_items_header.*,
			branch.branch_id,
			branch.branch_name,
			branch.branch_code,
			branch.phone_number,
			branch.address

			from vb_branch_items_header 
		left join branch on 
					branch.branch_id = vb_branch_items_header.branch_id
				
		";
		if($query = $this->db->query($sql,array($id))){
		
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getItemIngredients($item_id="")
	{
		$condition = 'inv_item_ingredient_line.item_id ="'.$item_id.'"';
				
		$query = "select 
				inv_item_branch_assign.*,
				inv_sys_items.item_id,
				inv_sys_items.item_name,
				inv_sys_items.item_description,
				inv_item_ingredient_line.ingredient_name,
				inv_item_ingredient_line.ingredient_description,
				inv_item_ingredient_line.ingredient_cost,
				branch.branch_name
				
				

				from inv_item_ingredient_header
				
				left join inv_sys_items on 
					inv_sys_items.item_id = inv_item_ingredient_header.item_id
				
				left join branch on 
					branch.branch_id = inv_item_ingredient_header.branch_id

				left join inv_item_ingredient_line on
				inv_item_ingredient_line.item_id=inv_item_ingredient_header.item_id
				
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	public function deleteBranchItems($branch_item_header_id='',$product_id='',$branch_id='')
	{
		$sql = "delete from vb_branch_items_line 
			where 
				branch_item_header_id = ? AND 
					product_id = ?
				";
		if($this->db->query($sql,array($branch_item_header_id,$product_id)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
