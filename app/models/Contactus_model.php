<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contactus_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getContactUs($offset="",$record="",$countType="")
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
				$customer_name = 'NULL';
			}
			else
			{
				$customer_name = "concat('%','".serchFilter($_GET['customer_name'])."','%')";
			}

			$query = "
			select
			contact_us.contact_us_id,
			contact_us.customer_name,
			contact_us.email,
			contact_us.mobile_number,
			contact_us.message,
			contact_us.subject,
			contact_us.created_date
			from contact_us 
			where 1=1
			and	( contact_us.customer_name like coalesce($customer_name,contact_us.customer_name) or contact_us.email like coalesce($customer_name,contact_us.email) or contact_us.mobile_number like coalesce($customer_name,contact_us.mobile_number))

			order by contact_us_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}


}
