<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_enquiries_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getCustomerEnquires($offset="",$record="", $countType="")
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

			$query = "select * from ord_enquiry
			where 1=1
			and (ord_enquiry.customer_name like coalesce($customer_name,ord_enquiry.customer_name))
            and (ord_enquiry.mobile_number like coalesce($mobile_number,ord_enquiry.mobile_number))
			order by ord_enquiry.enquiry_id desc
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
