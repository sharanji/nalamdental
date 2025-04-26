<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Applied_jobs_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getAppliedJobs($offset="",$record="",$countType="")
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

			
			if(empty($_GET['customer_name']))
			{
				$full_name = 'NULL';
			}
			else
			{
				$full_name = "concat('%','".serchFilter($_GET['customer_name'])."','%')";
			}

			$query = "
			select
			jobs.applied_job_id,
			jobs.full_name,
			jobs.email,
			jobs.mobile_number,
			jobs.experience,
			jobs.location,
			jobs.current_company,
			jobs.expected_salary,
			jobs.message,
			jobs.created_date,
			ltv.list_value,
			job_category.job_name
			from org_applied_jobs as jobs
			left join sm_list_type_values as ltv on ltv.list_code = jobs.notice_period
			left join org_job_category as job_category on job_category.job_category_id = jobs.job_category_id
			where 1=1
			and	( jobs.full_name like coalesce($full_name,jobs.full_name) or jobs.email like coalesce($full_name,jobs.email) or jobs.mobile_number like coalesce($full_name,jobs.mobile_number) or job_category.job_name like coalesce($full_name,job_category.job_name))
			group by ltv.list_code
			order by applied_job_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}


}
