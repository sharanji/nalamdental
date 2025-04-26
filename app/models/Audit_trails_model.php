<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Audit_trails_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function auditTrails($offset="",$record="",$countType="")
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
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			$query = "
			select
			audit_trail.*,
			branch.branch_name,
			if(audit_trail.user_id='1','Admin',employee.first_name) as employee_name,
			employee.mobile_number
			from audit_trails as audit_trail
			left join branch on branch.branch_id = audit_trail.branch_id
			left join per_user as user on user.user_id = audit_trail.user_id
			left join per_people_all as employee on employee.person_id = user.person_id
			where 1=1 
			and (
				date_format(audit_trail.created_date, '%Y-%m-%d')
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(audit_trail.created_date, '%Y-%m-%d'))
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(audit_trail.created_date, '%Y-%m-%d'))
			)
			order by audit_trail.audit_trial_id desc
			$limit";
				
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
}
