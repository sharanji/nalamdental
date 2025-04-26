<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Paymenttype_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getPaymenttype($offset="",$record="", $countType="")
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

			$payment_type = "concat('%','".serchFilter($_GET['payment_type'])."','%')";
			$active_flag = $_GET['active_flag'];

			$query = "select 
			pay_payment_types.*
			from pay_payment_types
			where 1=1
			and ( pay_payment_types.payment_type like coalesce($payment_type,pay_payment_types.payment_type) )
			and pay_payment_types.active_flag = if('".$active_flag."' = 'All',pay_payment_types.active_flag,'".$active_flag."')
			order by pay_payment_types.payment_type_id desc
			$limit ";
			$result = $this->db->query($query)->result_array();
			return $result;
		}else{
			return array();
		}
	
	}
	
	
}
