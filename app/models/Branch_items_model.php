<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Branch_items_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getmanegeBranchItems($offset="",$record="", $countType="")
	{
		if($_GET)
		{
				/* if($this->user_id==1) #Admin
			{
				$condition = " 1=1";
				$joinQuery ="";
			}
			else #Branch Admins
			{
				$condition = " 1=1 and 
					branch_users.user_id='".$this->user_id."' and 
					branch_users.branch_id='".$this->admin_branch_id."'
				";
				
				$joinQuery ="
					join users as branch_users on branch_users.branch_id = vb_branch_items_header.branch_id 
				";
			} */
			
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

			if(empty($_GET['mobile_number'])){
				$mobile_number = 'NULL';
			}else{
				$mobile_number = "concat('%','".serchFilter($_GET['mobile_number'])."','%')";
			}

			$query = "select 
				inv_item_branch_assign.*,
				branch.branch_id,
				branch.branch_code,
				branch.branch_name,
				branch.mobile_number
				
				from inv_item_branch_assign
			
			join branch on 
				branch.branch_id = inv_item_branch_assign.branch_id
			
			where 1=1
			and ( inv_item_branch_assign.branch_id = coalesce($branch_id,inv_item_branch_assign.branch_id) )
			and ( branch.mobile_number like coalesce($mobile_number,branch.mobile_number) )

			group by inv_item_branch_assign.branch_id
			order by branch.branch_name asc
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
				
		where branch_item_header_id = ?";
		if($query = $this->db->query($sql,array($id))){
		
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getBranchItems($branch_id="")
	{
		$condition = 'inv_item_branch_assign.branch_id ="'.$branch_id.'"';
				
		$query = "select 
				inv_item_branch_assign.*,
				inv_sys_items.item_id,
				inv_sys_items.item_name,
				inv_sys_items.item_description

				from inv_item_branch_assign
				
				left join inv_sys_items on 
					inv_sys_items.item_id = inv_item_branch_assign.item_id
				
				left join branch on 
					branch.branch_id = inv_item_branch_assign.branch_id
				
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
