<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_audits_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function loginAudits($offset="",$record="",$countType="")
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
			
			$login_type = $_GET['login_type'];

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			$query = "
			select
			login_audit.*,
			branch.branch_name,
			ltv.list_value as login_type_name,
			if(login_audit.login_type = 'INTERNAL-USER', if(login_audit.user_id='1','Admin',employee.first_name),consumer.customer_name) as user_name,
			if(login_audit.login_type = 'INTERNAL-USER', employee.mobile_number,consumer.mobile_number) as mobile_number,
			
			employee.first_name as employee_name,
			employee.mobile_number as emp_mobile_number,
			
			consumer.customer_name,
			consumer.mobile_number as customer_mobile_number,
			user.last_login_status,
			user.last_login_date,
			user.logout_date
			
			from org_login_audits as login_audit

			left join branch on branch.branch_id = login_audit.branch_id
			left join sm_list_type_values as ltv on ltv.list_code = login_audit.login_type

			left join per_user as user on user.user_id = login_audit.user_id
			left join per_people_all as employee on employee.person_id = user.person_id
			left join cus_consumers as consumer on consumer.customer_id = user.reference_id

			where 1=1 
			and login_audit.login_type = coalesce(if('".$login_type."' = '',NULL,'".$login_type."'),login_audit.login_type)
			and (
				date_format(login_audit.created_date, '%Y-%m-%d')
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(login_audit.created_date, '%Y-%m-%d'))
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(login_audit.created_date, '%Y-%m-%d'))
			)
			order by login_audit.login_id desc
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
