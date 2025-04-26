<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ingredients_item_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getmanageIngredientsItems($offset="",$record="", $countType="")
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

			

			$branch_id = $_GET['branch_id'];

			$query = "select 
				ing_header.*,
				inv_sys_items.item_name,
                inv_sys_items.item_description,
                branch.branch_name
			
				from inv_item_ingredient_header as ing_header
			
			left join inv_sys_items on inv_sys_items.item_id = ing_header.item_id
            left join branch on branch.branch_id = ing_header.branch_id
			
			where 1=1
			and ing_header.branch_id = '".$branch_id."'
			order by inv_sys_items.item_name
			$limit ";
			
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
