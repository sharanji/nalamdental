<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customerfeedback_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getCustomerfeedback($offset="",$record="", $countType="")
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

			if(empty($_GET['customer_name'])){
				$customer_name = 'NULL';
			}else{
				$customer_name = "concat('%','".serchFilter($_GET['customer_name'])."','%')";
			}

            if(empty($_GET['mobile_number'])){
				$mobile_number = 'NULL';
			}else{
				$mobile_number = "concat('%','".serchFilter($_GET['mobile_number'])."','%')";
			}

			$query = "select 
			feedback_id,
			customer_name,
			email,
            mobile_number,
            message,
			created_date
			from cus_feedback
			where 1=1
			and (cus_feedback.customer_name like coalesce($customer_name,cus_feedback.customer_name))
            and (cus_feedback.mobile_number like coalesce($mobile_number,cus_feedback.mobile_number))
			order by cus_feedback.feedback_id desc
			$limit ";
			$result = $this->db->query($query)->result_array();
			return $result;
		}
        else
        {
			return array();
		}
	
	}
	
	
}
