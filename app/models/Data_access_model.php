<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data_access_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getDataAccess($offset="",$record="",$countType="")
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

			$user_id 		= !empty($_GET['user_id']) ? $_GET['user_id'] : 'NULL';
			$user_name 		= !empty($_GET['user_name']) ? $_GET['user_name'] : 'NULL';
			$active_flag 	= !empty($_GET['active_flag']) ? $_GET['active_flag'] : 'NULL';
			
			// echo $person_id;

			$headerQry = "select 
				header_tbl.*,
				per_people.employee_number,
				per_people.first_name,
				per_people.middle_name,
				per_people.last_name,
				organizations.organization_name,
				branch.branch_name,
				branch1.branch_name as user_branch

				from org_data_access_headers as header_tbl
				
				left join per_user as users on users.user_id=header_tbl.user_id

				left join org_data_access_lines as line_tbl on line_tbl.header_id = header_tbl.header_id

				left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id
				
				left join branch on branch.branch_id = line_tbl.branch_id

				left join per_people_all as per_people on per_people.person_id=users.person_id

				left join branch as branch1 on branch1.branch_id = per_people.branch_id
				
				where 1=1
				and header_tbl.user_id = coalesce($user_id,header_tbl.user_id)
				and header_tbl.active_flag = if('".$active_flag."' = 'All',header_tbl.active_flag,'".$active_flag."')
				group by header_tbl.user_id
				order by header_tbl.user_id desc $limit" ;
			
			$result["header_data"] = $this->db->query($headerQry)->result_array();

			$lineQry = "select 
				header_tbl.*,
				line_tbl.*,
				organizations.organization_name,
				branch.branch_name
				from org_data_access_lines as line_tbl
				left join org_data_access_headers as header_tbl on header_tbl.header_id = line_tbl.header_id
				left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id
				left join branch on branch.branch_id = line_tbl.branch_id
				left join per_user as users on users.user_id=header_tbl.user_id

				where 1=1
				and header_tbl.user_id = coalesce($user_id,header_tbl.user_id)
				
				order by header_tbl.header_id desc $limit" ;
			
			$result["line_data"] = $this->db->query($lineQry)->result_array();

		}
		else
		{
			$result["header_data"] = array();
			$result["line_data"] = array();
			
		}
		return $result;
	}

	
	public function getViewData($id='')
	{
		$headerQry ="select 
						header_tbl.*,
						per_people.employee_number,
						per_people.first_name

						from org_data_access_headers as header_tbl
				
						left join per_user as users on users.user_id=header_tbl.user_id

						left join per_people_all as per_people on per_people.person_id=users.person_id

						where 1=1
						and header_id = '".$id."' " ;

		$result['editData'] = $this->db->query($headerQry)->result_array();

		$lineQry ="select 
					line_tbl.*,
					organizations.organization_name,
					branch.branch_name
					
					from org_data_access_lines as line_tbl

					left join org_data_access_headers as header_tbl on header_tbl.header_id = line_tbl.header_id

					left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id
					
					left join branch on branch.branch_id = line_tbl.branch_id

					where 1=1
					and line_tbl.header_id = '".$id."'";
			
		$result['lineData'] = $this->db->query($lineQry)->result_array();

		return $result;
	}

	function checkDataAccessExist($user_id='',$type='',$id='')
	{
		if($type==='add' || $type==='import')
		{
			$condition=' 1=1';
		}
		else
		{
			$condition=" header_tbl.header_id!='".$id."'";
		}

		$query="select user_id from org_data_access_headers as header_tbl
				where 1=1 
				and $condition
				and header_tbl.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function branchCount($id){
		$lineQry ="select 
					line_tbl.*,
					organizations.organization_name,
					branch.branch_name
					
					from org_data_access_lines as line_tbl

					left join org_data_access_headers as header_tbl on header_tbl.header_id = line_tbl.header_id

					left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id
					
					left join branch on branch.branch_id = line_tbl.branch_id

					where 1=1
					and line_tbl.header_id = '".$id."' 
					and line_tbl.active_flag='".$this->active_flag."'";
			
		$result['lineData'] = $this->db->query($lineQry)->result_array();

		return $result;
	}
	
}
