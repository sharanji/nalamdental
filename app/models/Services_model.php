<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Services_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getServices($offset="",$record="",$countType="")
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

			$service_contact_type = !empty($_GET['service_contact_type']) ? $_GET['service_contact_type'] : NULL;

			$query = "select 
			services.service_id, 
			services.service_contact_type, 
			services.full_name, 
			services.email, 
			services.mobile_number, 
			services.company_name, 
			services.marketing_goals, 
			services.platform_type, 
			services.existing_app, 
			services.website_type, 
			services.current_challenges, 
			services.project_detail, 
			services.project_description, 
			services.industry_type, 
			services.created_date 
			from services 
			where 1=1
			and services.service_contact_type = coalesce(if('".$service_contact_type."' = '',NULL,'".$service_contact_type."'),services.service_contact_type)
			order by services.service_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	

}
