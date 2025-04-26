<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_wallet_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	
	function getCustomerwallet($offset="",$record="",$countType="")
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

			if(empty($_GET['customer_id'])){
				$customer_id = 'NULL';
			}else{
				$customer_id = $_GET['customer_id'];
			}


			if(empty($_GET['mobile_number'])){
				$mobile_number = 'NULL';
			}else{
				$mobile_number = "concat('%','".$_GET['mobile_number']."','%')";
			}

			
			$query = "select 
			cus_customers.customer_id,
			cus_customers.customer_number,
			cus_customers.customer_name,
			cus_customers.mobile_number,	
			cus_customers.created_date,	
			cus_customer_wallet.wallet_amount,
			branch.branch_id,
			branch.branch_name
					
			from cus_consumers as cus_customers

			left join cus_customer_wallet on cus_customer_wallet.customer_id = cus_customers.customer_id
			left join branch on branch.branch_id = cus_customer_wallet.branch_id

			where 1=1
			and cus_customers.customer_id = coalesce($customer_id,cus_customers.customer_id)
			and cus_customers.mobile_number like coalesce($mobile_number,cus_customers.mobile_number)
			order by cus_customers.customer_id asc $limit";
			
			$result = $this->db->query($query)->result_array();
			//print_r($result);exit;
			return $result;
		}else{
			return array();
		}
	}
	
	
}
