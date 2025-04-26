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

    function getManageCustomerTypeCount()
	{
		$condition = "1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
                             customer_type.customer_type_name like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select customer_type_id from customer_type
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
    function getManageCustomerType($offset="",$record="")
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							customer_type.customer_type_name like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		
		$query = "select * from customer_type
		
		where $condition
				order by customer_type_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}





	function getManageAddressCount($id="")
	{
		$condition = " 1=1 and cus_customer_address.customer_id='".$id."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								cus_customer_address.customer_id like "%'.($_GET['keywords']).'%" 
							)
							';
		}
		
		$query = "select cus_customer_address.customer_id from cus_customer_address
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	function getManageAddress($offset="",$record="",$id=" ")
	{
		$condition = " 1=1 and cus_customer_address.customer_id='".$id."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								cus_customer_address.postal_code like "%'.($_GET['keywords']).'%" 
							)
							';
		}
		
		$query = "select 
		cus_customer_address.* ,
		cus_customers.customer_id,
		cus_customer_address.address_name,
		cus_customers.customer_name,
		cus_customers.email_address,
		cus_customers.mobile_number

		
		from cus_customer_address
		left join cus_customers on cus_customers.customer_id = cus_customer_address.customer_id
		where $condition
				order by cus_customer_address.customer_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	
	function getManageFavouriteCount($id="")
	{
		$condition = " 1=1 and ord_favourite_orders.header_id='".$id."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							ord_favourite_orders.header_id like "%'.($_GET['keywords']).'%" 
							)
							';
		}
		
		$query = "select ord_favourite_orders.header_id from ord_favourite_orders
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageFavourite($offset="",$record="",$id=" ")
	{
		$condition = " 1=1 and ord_favourite_orders.header_id='".$id."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								ord_favourite_orders.postal_code like "%'.($_GET['keywords']).'%" 
							)
							';
		}


		$query = "select 
					ord_favourite_orders.*,
					ord_order_headers.header_id,
					ord_order_headers.customer_id,
					ord_order_headers.order_number,
					cus_customers.customer_name,
					cus_customers.mobile_number,
					ord_order_headers.ordered_date,
					(ord_order_lines.quantity * ord_order_lines.price) as linetotal,
					sum(ord_order_lines.price * ord_order_lines.quantity) as bill_amount
					
					from ord_favourite_orders
					
				left join ord_order_headers on 
					ord_order_headers.header_id = ord_favourite_orders.header_id
				
				left join ord_order_lines on 
					ord_order_lines.header_id = ord_favourite_orders.header_id
					
				left join cus_customers on 
					cus_customers.customer_id = ord_favourite_orders.customer_id

				where $condition
					order by ord_favourite_orders.header_id desc
						limit ".$record." , ".$offset."
				";
		
		$result = $this->db->query($query)->result_array();
		//print_r($result);exit;
		return $result;
	}
	
	
	
	
	



	function getOrdersCount($user_id)
	{
		$query = 'select ord_order_headers.header_id from ord_order_headers where ord_order_headers.customer_id = '.$user_id;

		$result = $this->db->query($query)->result_array(); 
		return count($result);
	}
	function getOrdersHistory($limit="", $offset="",$id="")
	{
		$query = 'select 
					ord_order_headers.header_id,
					ord_order_headers.order_status,
					ord_order_headers.order_number,

					ord_order_headers.payment_method,
					ord_order_headers.ordered_date,
					ord_order_headers.created_date
					
					
					
					from ord_order_headers 

					

					where ord_order_headers.customer_id = '.$id.'
					order by ord_order_headers.created_date desc limit 10 offset '.$offset;

		$result = $this->db->query($query)->result_array(); 

		return $result;
	}
	function getloginCount($user_id)
	{
		$condition = " 1=1 and usr_customer_login_audits.user_id='".$user_id."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							usr_customer_login_audits.full_name like "%'.($_GET['keywords']).'%" or 
							usr_customer_login_audits.mobile_number like "%'.($_GET['keywords']).'%" or 
							usr_customer_login_audits.email like "%'.($_GET['keywords']).'%" 
							)
							';
		}
		if(!empty($_GET['device_type']))
		{
			$condition .= ' and usr_customer_login_audits.device_type="'.$_GET['device_type'].'"';
		}

		if(!empty($_GET['os_type']))
		{
			$condition .= ' and usr_customer_login_audits.os_type="'.$_GET['os_type'].'"';
		}

		$query = "select usr_customer_login_audits.user_id from usr_customer_login_audits
		 where $condition";

		$result = $this->db->query($query)->result_array(); 
		return count($result);
	}

	function getloginHistory($offset="",$record="",$id="")
	{
		$condition = " 1=1 and usr_customer_login_audits.user_id='".$id."' ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							usr_customer_login_audits.full_name like "%'.($_GET['keywords']).'%" or 
							usr_customer_login_audits.mobile_number like "%'.($_GET['keywords']).'%" or 
							usr_customer_login_audits.email like "%'.($_GET['keywords']).'%" 
							)
							';
		}
		if(!empty($_GET['device_type']))
		{
			$condition .= ' and usr_customer_login_audits.device_type="'.$_GET['device_type'].'"';
		}
		if(!empty($_GET['os_type']))
		{
			$condition .= ' and usr_customer_login_audits.os_type="'.$_GET['os_type'].'"';
		}

		$query = "select 
					usr_customer_login_audits.*
					
					from usr_customer_login_audits 
					where  $condition limit ".$record." , ".$offset." ";

		$result = $this->db->query($query)->result_array(); 

		return $result;
	}
	#Customer Sites
	function getManageCustomerSitesCount()
	{
		$condition = " 1=1  ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							sites.customer_id like "%'.($_GET['keywords']).'%" or 
							sites.site_name like "%'.($_GET['keywords']).'%"or
							sites.email like "%'.($_GET['keywords']).'%"or
							sites.address like "%'.($_GET['keywords']).'%"or
							sites.phone_number like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select customer_site_id from customer_sites as sites
		
		left join users on users.user_id = sites.customer_id
		left join country as billing_country on billing_country.country_id = sites.billing_country_id
		left join state as billing_state on billing_state.state_id = sites.billing_state_id
		left join city as billing_city on billing_city.city_id = sites.billing_city_id

		left join country as shipping_country on shipping_country.country_id = sites.shipping_country_id
		left join state as shipping_state on shipping_state.state_id = sites.shipping_state_id
		left join city as shipping_city on shipping_city.city_id = sites.shipping_city_id

		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageCustomerSites($offset="",$record="")
	{
		$condition = " 1=1  ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							sites.customer_id like "%'.($_GET['keywords']).'%" or 
							sites.site_name like "%'.($_GET['keywords']).'%"or
							sites.email like "%'.($_GET['keywords']).'%"or
							sites.address like "%'.($_GET['keywords']).'%"or
							sites.phone_number like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select 
			sites.*,
			users.first_name,
			billing_country.country_name as billing_country_name,
			billing_state.state_name as billing_state_name,
			billing_city.city_name as billing_city_name,
			
			shipping_country.country_name as shipping_country_name,
			shipping_state.state_name as shipping_state_name,
			shipping_city.city_name as shipping_city_name
			
			
		from customer_sites as sites
		
		left join users on users.user_id = sites.customer_id

		left join country as billing_country on billing_country.country_id = sites.billing_country_id
		left join state as billing_state on billing_state.state_id = sites.billing_state_id
		left join city as billing_city on billing_city.city_id = sites.billing_city_id

		left join country as shipping_country on shipping_country.country_id = sites.shipping_country_id
		left join state as shipping_state on shipping_state.state_id = sites.shipping_state_id
		left join city as shipping_city on shipping_city.city_id = sites.shipping_city_id

		where $condition
				order by sites.customer_site_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}