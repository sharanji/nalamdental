<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_model extends CI_Model 
{
 	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageCustomer($offset="",$record="",$countType="")
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

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
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
			cus_customers.contact_person,	
			cus_customers.created_date,	
			cus_customers.active_flag	
					
					from cus_customers as cus_customers
			where 1=1
			and cus_customers.customer_id = coalesce($customer_id,cus_customers.customer_id)
			and cus_customers.mobile_number like coalesce($mobile_number,cus_customers.mobile_number)
			and cus_customers.active_flag = if('".$active_flag."' = 'All',cus_customers.active_flag,'".$active_flag."')
			order by cus_customers.customer_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}else{
			return array();
		}
	}

	function getManageCustomerSites($offset="",$record="", $countType="")
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
				$customer_id  = 'NULL';
			}else{
				$customer_id  = $_GET['customer_id'];
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			if(empty($_GET['site_type'])){
				$site_type = 'NULL';
			}else{
				$site_type = $_GET['site_type'];
			}

			$query = "select customer_site.*,cus_customers.customer_name from cus_customer_sites as customer_site
			left join cus_customers on cus_customers.customer_id = customer_site.customer_id
		
			where 1=1
				and cus_customers.customer_id = coalesce($customer_id,cus_customers.customer_id)  
				
				and customer_site.active_flag = if('".$active_flag."' = 'All',customer_site.active_flag,'".$active_flag."')
				and customer_site.site_type = if('".$site_type."' = 'All',customer_site.site_type,'".$site_type."')
				order by customer_site.customer_site_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
   
	function getAjaxCustomerAll($customer_name='')
	{
		$query="select customer_id,customer_name from cus_customers as customer
				where 1=1 
				and customer.customer_name LIKE '%" . $customer_name . "%'
				and customer.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getDashboardCount()
	{

		$query = "select 
		cus_customers.customer_id,
		cus_customers.customer_number,
		cus_customers.customer_name
		from cus_customers as cus_customers
		where 1=1
		AND cus_customers.active_flag='Y'
		GROUP BY cus_customers.customer_id
		order by cus_customers.customer_name ASC
		";

		$result = $this->db->query($query)->result_array();		
		return $result;		
		
	}
	
}