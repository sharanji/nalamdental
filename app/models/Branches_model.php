<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Branches_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageBranch($offset="", $record="", $countType="")
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
				branch.*,
				location.location_name,
				branch.active_flag
				from branch

			left join loc_location_all as location on
			location.location_id = branch.location_id
			where 1=1
			and ( branch.branch_code like coalesce($keywords,branch.branch_code) or branch.branch_name like coalesce($keywords,branch.branch_name) )
			and branch.active_flag = if('".$active_flag."' = 'All',branch.active_flag,'".$active_flag."')
			order by branch.branch_id desc
			$limit ";
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function getBranchZones($offset="",$record="", $branch_id="", $countType="")
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

			if(empty($_GET['keywords'])){
				$keywords = 'NULL';
			}else{
				$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			}

			
			$query = "select * from branch_zones
			
			where 1=1 
			and branch_zones.branch_id=".$branch_id."
			and ( branch_zones.zone_name like coalesce($keywords,branch_zones.zone_name) )
			order by branch_zones.zone_id desc
			$limit ";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function getBranchAll()
	{
		$query = "select 
		branch.branch_id,
		branch.branch_name
		from branch
		where 1=1
		and branch.active_flag='Y'
		order by branch.branch_name asc";
		$result = $this->db->query($query)->result_array();
		return $result;	
	}

	function getOrgBranch($organization_id=''){
		$query = "select 
					branch.branch_id, 
					branch.branch_name
					from branch
					where 1=1
					and branch.organization_id='".$organization_id."'
					and branch.active_flag='".$this->active_flag."'" ;
		
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	function getBranch($branch_id="")
	{
		$query = "select 
		branch.branch_id,
		branch.branch_name
		from branch
		where 1=1
		and branch.active_flag='Y' and branch_id = '".$branch_id."'
		";
		$result = $this->db->query($query)->result_array();
		return $result;	
	}
}
