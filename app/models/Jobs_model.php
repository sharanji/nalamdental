<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobs_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getJobs($offset="",$record="",$countType="")
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

			$job_category_id 	= !empty($_GET['job_category_id']) ? $_GET['job_category_id'] : 'NULL';
			$role_id 			= !empty($_GET['role_id']) ? $_GET['role_id'] : 'NULL';
			$experience_id 		= !empty($_GET['experience_id']) ? $_GET['experience_id'] : 'NULL';
			$active_flag 		= !empty($_GET['active_flag']) ? $_GET['active_flag'] : 'NULL';
			

			$query = "select 
			jobs.job_id,
			jobs.job_category_id,
			jobs.functional_area,
			jobs.salary,
			jobs.job_location,
			jobs.key_skills,
			jobs.roles_and_response,
			jobs.job_qualification,
			jobs.requirements_and_skills,
			jobs.valid_from,
			jobs.valid_to,
			jobs.created_date,
			jobs.active_flag,
			jobs_category.job_name,
			roles.role_name,
			ltv_1.list_value as industry_type,
			ltv_2.list_value as employment_type,
			ltv_3.list_value as experience,
			designations.designation_name,
			qualification.qualification_name
			from org_jobs as jobs
			left join org_job_category as jobs_category on jobs_category.job_category_id = jobs.job_category_id
			left join org_roles as roles on roles.role_id = jobs.role_id
			left join sm_list_type_values as ltv_1 on ltv_1.list_type_value_id = jobs.industry_type_id
			left join sm_list_type_values as ltv_2 on ltv_2.list_type_value_id = jobs.employment_type_id
			left join sm_list_type_values as ltv_3 on ltv_3.list_type_value_id = jobs.experience_id
			left join emp_designations as designations on designations.designation_id = jobs.designation_id
			left join qualification on qualification.qualification_id = jobs.qualification_id
			where 1=1
			and jobs_category.job_category_id = coalesce($job_category_id,jobs_category.job_category_id)
			and roles.role_id = coalesce($role_id,roles.role_id)
			and ltv_3.list_type_value_id = coalesce($experience_id,ltv_3.list_type_value_id)
			and jobs.active_flag = if('".$active_flag."' = 'All',jobs.active_flag,'".$active_flag."')
			order by jobs.job_id desc $limit" ;
			
			$result = $this->db->query($query)->result_array();
		}
		else
		{
			$result = array();
			
		}
		return $result;
	}

	function checkJobExist($job_category_id='',$employment_type_id='',$qualification_id='',$experience_id='',$designation_id='',$type='',$id='')
	{
		if($type==='add' || $type==='import')
		{
			$condition=' 1=1';
		}
		else
		{
			$condition="jobs.job_id!='".$id."'";
		}

		$query="select jobs.job_id from org_jobs as jobs
		where 1=1 
		and jobs.job_category_id='".$job_category_id."'
		and jobs.employment_type_id='".$employment_type_id."'
		and jobs.qualification_id='".$qualification_id."'
		and jobs.experience_id='".$experience_id."'
		and jobs.designation_id='".$designation_id."'
		and $condition";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getViewData($id='')
	{
		$query = "select 
		jobs.job_id, 
		jobs.job_category_id,
		jobs.role_id,
		jobs.industry_type_id,
		jobs.designation_id,
		jobs.functional_area,
		jobs.employment_type_id,
		jobs.qualification_id,
		jobs.experience_id,
		jobs.job_description,
		jobs.salary,
		jobs.job_location,
		jobs.key_skills,
		jobs.roles_and_response,
		jobs.job_qualification,
		jobs.requirements_and_skills,
		jobs.valid_from,
		jobs.valid_to,
		jobs.active_flag
		from org_jobs as jobs
		where 1=1
		and jobs.job_id='".$id."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getGalleryCount()
	{
		$condition = "1=1 ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_gallery.gallery_description like "%'.($_GET['keywords']).'%"
							)';
		}
		
		$query = "select org_gallery.gallery_id from org_gallery

				where $condition ";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getGallery($offset="",$record="")
	{
		$condition = "1=1 ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_gallery.gallery_description like "%'.($_GET['keywords']).'%"
							)';
		}
		
		$query = "select org_gallery.* from org_gallery

				where $condition 
					order by org_gallery.gallery_id desc
						limit ".$record." , ".$offset." ";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getAppliedJobsCount($job_id="")
	{
		$condition = "1=1 and org_applied_jobs.job_id='".$job_id."' ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_applied_jobs.full_name like "%'.($_GET['keywords']).'%" or	
								org_applied_jobs.email like "%'.($_GET['keywords']).'%" 	
							)';
		}
		
		$query = "select org_applied_jobs.applied_job_id from org_applied_jobs where $condition ";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getAppliedJobs($offset="",$record="",$job_id="")
	{
		$condition = "1=1 and org_applied_jobs.job_id='".$job_id."' ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_applied_jobs.full_name like "%'.($_GET['keywords']).'%" or	
								org_applied_jobs.email like "%'.($_GET['keywords']).'%" 	
							)';
		}
		
		$query = "select org_applied_jobs.*
		
				from org_applied_jobs

				where $condition 
					order by org_applied_jobs.applied_job_id desc
						limit ".$record." , ".$offset." ";
		$result = $this->db->query($query)->result_array();
		return $result;
	}


	function jobCategoryList(){
		$query = "select 
		jobs.job_id,
		job_category.job_category_id, 
		job_category.job_name 
		from org_job_category as job_category
		left join org_jobs as jobs on jobs.job_category_id=job_category.job_category_id
		where 1=1
		and job_category.active_flag='".$this->active_flag."'
		group by job_category.job_category_id";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function jobCategory(){
		$query = "select 
		jobs.job_id,
		job_category.job_category_id, 
		job_category.job_name 
		from org_job_category as job_category
		left join org_jobs as jobs on jobs.job_category_id=job_category.job_category_id
		where 1=1
		and job_category.active_flag='".$this->active_flag."'
		group by job_category.job_category_id
		having count(jobs.job_id)>0";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getJobsCategory($offset="",$record="",$countType="")
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

			$job_category_id 	= !empty($_GET['job_category_id']) ? $_GET['job_category_id'] : 'NULL';
			$active_flag 		= !empty($_GET['active_flag']) ? $_GET['active_flag'] : 'NULL';
			

			$query = "select 
				job_category.job_category_id,
				job_category.job_name,
				job_category.active_flag
				from org_job_category as job_category
				where 1=1
				and job_category.job_category_id = coalesce($job_category_id,job_category.job_category_id)
				and job_category.active_flag = if('".$active_flag."' = 'All',job_category.active_flag,'".$active_flag."')
				order by job_category.job_category_id desc $limit" ;
			
			$result = $this->db->query($query)->result_array();
		}
		else
		{
			$result = array();
			
		}
		return $result;
	}

	function checkJobCategoryExist($job_name='',$type='',$id='')
	{
		if($type==='add' || $type==='import')
		{
			$condition=' 1=1';
		}
		else
		{
			$condition=" job_category.job_category_id!='".$id."'";
		}

		$query="select job_category.job_category_id from org_job_category as job_category
				where 1=1 
				and job_category.job_name='".$job_name."'
				and $condition";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getJobsCategoryView($id="")
	{
		$query = "select 
			job_category.job_category_id,
			job_category.job_name,
			job_category.active_flag
			from org_job_category as job_category
			where 1=1
			and job_category.job_category_id = '".$id."'" ;
		
		$result = $this->db->query($query)->result_array();
		
		
		return $result;
	}

	function jobCategoryAll(){
		$query = "select 
		job_category_id, 
		job_name 
		from org_job_category";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	

	function getJobLists($job_id='',$job_category_id='')
	{
		$query = "select 
		jobs.job_id, 
		jobs_category.job_category_id,
		jobs_category.job_name,
		jobs.salary,
		jobs.job_location,
		jobs.key_skills,
		jobs.roles_and_response,
		jobs.job_qualification,
		jobs.requirements_and_skills,
		ltv_3.list_value
		from org_jobs as jobs
		left join org_job_category as jobs_category on jobs_category.job_category_id = jobs.job_category_id
		left join org_roles as roles on roles.role_id = jobs.role_id
		left join sm_list_type_values as ltv_1 on ltv_1.list_type_value_id = jobs.industry_type_id
		left join sm_list_type_values as ltv_2 on ltv_2.list_type_value_id = jobs.employment_type_id
		left join sm_list_type_values as ltv_3 on ltv_3.list_type_value_id = jobs.experience_id
		left join emp_designations as designations on designations.designation_id = jobs.designation_id
		left join qualification on qualification.qualification_id = jobs.qualification_id
		where 1=1
		and jobs_category.job_category_id='".$job_category_id."'
		and jobs_category.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getJobDetails($job_id='',$job_category_id='')
	{
		$query = "select 
		jobs.job_id, 
		jobs.job_category_id,
		jobs.salary,
		jobs.job_location,
		jobs.key_skills,
		jobs.roles_and_response,
		jobs.job_qualification,
		jobs.requirements_and_skills,
		jobs.functional_area,
		jobs.valid_from,
		jobs.job_description,
		jobs.valid_to,
		jobs.active_flag,
		jobs_category.job_category_id,
		jobs_category.job_name,
		roles.role_name,
		qualification.qualification_name,
		ltv_1.list_value as industry_type,
		ltv_2.list_value as employment_type,
		ltv_3.list_value as experience
		from org_jobs as jobs
		left join org_job_category as jobs_category on jobs_category.job_category_id = jobs.job_category_id
		left join org_roles as roles on roles.role_id = jobs.role_id
		left join sm_list_type_values as ltv_1 on ltv_1.list_type_value_id = jobs.industry_type_id
		left join sm_list_type_values as ltv_2 on ltv_2.list_type_value_id = jobs.employment_type_id
		left join sm_list_type_values as ltv_3 on ltv_3.list_type_value_id = jobs.experience_id
		left join emp_designations as designations on designations.designation_id = jobs.designation_id
		left join qualification on qualification.qualification_id = jobs.qualification_id
		where 1=1
		and jobs.job_id='".$job_id."'
		and jobs_category.job_category_id='".$job_category_id."'
		and jobs_category.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function jobsCount($job_category_id='')
	{
		$query = "select 
		jobs.applied_job_id
		from org_applied_jobs as jobs
		where 1=1
		and jobs.job_category_id='".$job_category_id."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
