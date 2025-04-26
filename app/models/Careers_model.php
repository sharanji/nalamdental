<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Careers_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getInternship($offset="",$record="",$countType="")
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

			
			if(empty($_GET['keywords']))
			{
				$keywords = 'NULL';
			}
			else
			{
				$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			}

			$query = "select
			careers.careers_id,
			careers.customer_name,
			careers.email,
			careers.mobile_number,
			careers.internship_duration,
			careers.created_date
			from careers 
			where 1=1
			and	( careers.customer_name like coalesce($keywords,careers.customer_name) or 
			careers.email like coalesce($keywords,careers.email) or 
			careers.mobile_number like coalesce($keywords,careers.mobile_number) )
			order by careers_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}


}
