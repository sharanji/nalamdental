<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Orders_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getOrders($offset="",$record="", $countType="")
	{	
		if($countType == 1) #GetTotalCount
		{
			$limit = "";
		}
		else if($countType == 2) #Get Page Wise Count
		{
			$limit = "limit ".$record." , ".$offset." "; 
		}

		$condition ="1=1 
		and header_tbl.order_status not in('Closed','Cancelled')
		and header_tbl.cancel_status='N'
		and header_tbl.order_source NOT IN('POS','DINE_IN','HOME_DELIVERY')";
		
		if(!empty($_GET['order_number']))
		{
			$condition .= ' and (
								header_tbl.order_number like "%'.serchFilter($_GET['order_number']).'%" 
							)
							';
		}
		
		if(!empty($_GET['mobile_number']))
		{
			$condition .= ' and (
				customer.mobile_number like "%'.serchFilter($_GET['mobile_number']).'%" 
			)
			';
		}

		if(!empty($_GET['order_status']))
		{
			if($_GET['order_status'] == "Total_Orders")
			{

			}
			else
			{
				$condition .= ' and header_tbl.order_status="'.$_GET['order_status'].'" ';
			}
		}

		if(!empty($_GET['payment_type_id']))
		{
			$condition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}

		$query = "select 
		header_tbl.header_id,
		header_tbl.paid_status,
		header_tbl.cancel_status,
		header_tbl.order_number,
		header_tbl.ordered_date,
		header_tbl.order_status,
		header_tbl.payment_method,
		branch.branch_name,
		payment_type.payment_type,
		customer.customer_name,
		customer.mobile_number,
		country.country_code,
		customer_address.address_name,
		customer_address.address1,
		customer_address.address2,
		customer_address.address3,
		customer_address.land_mark,
		customer_address.address_type,
		customer_address.postal_code,
		sum(line_tbl.price) as price,
		sum(line_tbl.price * line_tbl.quantity) as bill_amount,

		round( sum((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
		round( sum((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
		round( sum( ((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))) * (tax_percentage/100)),2) as tax_value
		
		from ord_order_headers as header_tbl

		left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id
		
		left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

		left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
		left join branch on branch.branch_id = header_tbl.branch_id
		left join cus_customer_address as customer_address on 
			customer_address.customer_address_id = header_tbl.address_id
		left join geo_countries as country on 
			country.country_id = customer.country_id

		WHERE $condition 
		and line_tbl.cancel_status = 'N'
		and line_tbl.line_status != 'Cancelled'
		group by line_tbl.header_id
		order by header_tbl.order_status asc,header_tbl.header_id asc
		$limit";
		
		$result["listing"] = $this->db->query($query)->result_array();
		//print_r($result["listing"]);exit;
		$result["totalCount"] = $result["listing"];
		

		#Booked Query start here
		$bookedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$bookedCondition .="and header_tbl.cancel_status='N'";
		$bookedCondition .="and header_tbl.order_status = 'Booked' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		if(!empty($_GET['order_number']))
		{
			$bookedCondition .= ' and header_tbl.order_number="'.serchFilter($_GET['order_number']).'" ';
		}

		if(!empty($_GET['mobile_number']))
		{
			$bookedCondition .= ' and customer.mobile_number="'.serchFilter($_GET['mobile_number']).'" ';
		}

		if(!empty($_GET['payment_type_id']))
		{
			$bookedCondition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$bookedCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}

		$bookedQuery = "select count(header_tbl.header_id) as bookedCount
			from ord_order_headers as header_tbl
			
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $bookedCondition";

		$result["bookedCount"] = $this->db->query($bookedQuery)->result_array();
		#Booked Qry End

		#Confirmed Qry End
		$confirmedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$confirmedCondition .="and header_tbl.cancel_status='N'";
		$confirmedCondition .="and header_tbl.order_status = 'Confirmed' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		if(!empty($_GET['order_number']))
		{
			$confirmedCondition .= ' and header_tbl.order_number="'.serchFilter($_GET['order_number']).'" ';
		}

		if(!empty($_GET['mobile_number']))
		{
			$confirmedCondition .= ' and customer.mobile_number="'.serchFilter($_GET['mobile_number']).'" ';
		}

		if(!empty($_GET['payment_type_id']))
		{
			$confirmedCondition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$confirmedCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		$confirmedQuery = "select count(header_tbl.header_id) as confirmedCount
			from ord_order_headers as header_tbl
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $confirmedCondition";
		$result["confirmedCount"] = $this->db->query($confirmedQuery)->result_array();
		#Confirmed Qry End

		#Preparing Qry End
		$preparingCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$preparingCondition .="and header_tbl.cancel_status='N'";
		$preparingCondition .="and header_tbl.order_status = 'Preparing' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";
		if(!empty($_GET['order_number']))
		{
			$preparingCondition .= ' and header_tbl.order_number="'.serchFilter($_GET['order_number']).'" ';
		}

		if(!empty($_GET['mobile_number']))
		{
			$preparingCondition .= ' and customer.mobile_number="'.serchFilter($_GET['mobile_number']).'" ';
		}

		if(!empty($_GET['payment_type_id']))
		{
			$preparingCondition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$preparingCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		$preparingQuery = "select count(header_tbl.header_id) as preparingCount
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $preparingCondition";
		$result["preparingCount"] = $this->db->query($preparingQuery)->result_array();
		#Preparing Qry End

		#Shipped Qry End
		$shippedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$shippedCondition .="and header_tbl.cancel_status='N'";
		$shippedCondition .="and header_tbl.order_status = 'Shipped' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		if(!empty($_GET['order_number']))
		{
			$shippedCondition .= ' and header_tbl.order_number="'.serchFilter($_GET['order_number']).'" ';
		}

		if(!empty($_GET['mobile_number']))
		{
			$shippedCondition .= ' and customer.mobile_number="'.serchFilter($_GET['mobile_number']).'" ';
		}

		if(!empty($_GET['payment_type_id']))
		{
			$shippedCondition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$shippedCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		$shippedQuery = "select count(header_tbl.header_id) as shippedCount
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $shippedCondition";
		$result["shippedCount"] = $this->db->query($shippedQuery)->result_array();
		#Shipped Qry End

		#Delivered Qry End
		$deliveredCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$deliveredCondition .="and header_tbl.cancel_status='N'";
		$deliveredCondition .="and header_tbl.order_status = 'Delivered' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		if(!empty($_GET['order_number']))
		{
			$deliveredCondition .= ' and header_tbl.order_number="'.serchFilter($_GET['order_number']).'" ';
		}

		if(!empty($_GET['mobile_number']))
		{
			$deliveredCondition .= ' and customer.mobile_number="'.serchFilter($_GET['mobile_number']).'" ';
		}

		if(!empty($_GET['payment_type_id']))
		{
			$deliveredCondition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$deliveredCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		$deliveredQuery = "select count(header_tbl.header_id) as deliveredCount
			from ord_order_headers as header_tbl
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $deliveredCondition";
		$result["deliveredCount"] = $this->db->query($deliveredQuery)->result_array();
		#Delivered Qry End

		#All Order Qry start
		$totalOrdersCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$totalOrdersCondition .="and header_tbl.cancel_status='N' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";
		
		if(!empty($_GET['order_number']))
		{
			$totalOrdersCondition .= ' and header_tbl.order_number="'.serchFilter($_GET['order_number']).'" ';
		}

		if(!empty($_GET['mobile_number']))
		{
			$totalOrdersCondition .= ' and customer.mobile_number="'.serchFilter($_GET['mobile_number']).'" ';
		}

		if(!empty($_GET['payment_type_id']))
		{
			$totalOrdersCondition .= ' and header_tbl.payment_method="'.$_GET['payment_type_id'].'" ';
		}

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$totalOrdersCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		$totalOrdersQuery = "select count(header_tbl.header_id) as totalOrdersCount
			from ord_order_headers as header_tbl
			
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $totalOrdersCondition";
		$result["totalOrdersCount"] = $this->db->query($totalOrdersQuery)->result_array();
		#All Order Qry End

		return $result;
	}

	function getManageOrders($offset="",$record="", $countType="")
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

			$order_number = "concat('%','".serchFilter($_GET['order_number'])."','%')";
			$mobile_number = "concat('%','".serchFilter($_GET['mobile_number'])."','%')";


			$order_status = $_GET['order_status'];
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$query = "select 
			header_tbl.header_id,
			header_tbl.order_number,
			header_tbl.ordered_date,
			header_tbl.order_status,
			header_tbl.notification_read_status,
			header_tbl.payment_method,
			branch.branch_name,
			payment_type.payment_type,
			customer.customer_name,
			customer.mobile_number,
			country.country_code,
			customer_address.address_name,
			customer_address.address1,
			customer_address.address2,
			customer_address.address3,
			customer_address.land_mark,
			customer_address.address_type,
			customer_address.postal_code,
			line_tbl.cancel_status,
			sum(line_tbl.price) as price,
			sum(line_tbl.price * line_tbl.quantity) as bill_amount,

			round( sum((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount, 
			round( sum((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal, 
			round( sum( ((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0) /100)),2) as tax_value
			
			from ord_order_headers as header_tbl

			left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join 	cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id

			left join cus_customer_address as customer_address on 
				customer_address.customer_address_id = header_tbl.address_id

			left join geo_countries as country on 
				country.country_id = customer.country_id

				WHERE 1=1 
				and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')

				and header_tbl.order_status = if('".$order_status."' = 'All',header_tbl.order_status,'".$order_status."')
				and header_tbl.order_number like coalesce($order_number,header_tbl.order_number)
				and customer.mobile_number like coalesce($mobile_number,customer.mobile_number)
				and ( 
					date_format(header_tbl.ordered_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.ordered_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.ordered_date, '%Y-%m-%d'))
				)
				
				group by line_tbl.header_id
				order by header_tbl.header_id desc
				$limit		
			";

			$result["listing"] = $this->db->query($query)->result_array();

			$result["totalCount"] = $result["listing"];
			return $result;
		}
		else
		{
			$result["listing"] = array();
			$result["totalCount"] = $result["listing"];
			return $result;
		}
	}
	
	function getOrderItems($id="")
    {
        $query = "select 
				ord_order_headers.*,
				cus_consumers.customer_name
			

                from ord_order_headers

                left join cus_consumers on cus_customers.customer_id = ord_order_headers.customer_id
            
                where header_id = $id;
            ";

        $result = $this->db->query($query)->result_array();
		return $result;
    }

    function getOrderDetails($id="")
    {
        $query = "select 
			header_tbl.header_id,
			header_tbl.last_updated_by,
			header_tbl.order_number,
			header_tbl.order_status,
			header_tbl.order_type,
			header_tbl.payment_method,
			header_tbl.created_date,
			header_tbl.order_source,
			header_tbl.attribute_1,
			cus_customers.email_address,
			cus_customers.customer_name,
			cus_customers.mobile_number,
			cus_customer_address.address1,
			cus_customer_address.address2,
			cus_customer_address.address_name,
			cus_customer_address.postal_code,
			cus_customer_address.address1,
			cus_customer_address.land_mark,
            expense_payment_type.payment_type,
			line_tbl.quantity,

			pos_customer.customer_name as pos_customer_name,
			pos_customer.mobile_number as pos_mobile_number,
			pos_customer.address1 as pos_address1,
			pos_customer.address2 as pos_address2,
			pos_customer.address3 as pos_address3,
			pos_customer.postal_code as pos_postal_code,
			CONCAT(din_tbl.table_code,coalesce(header_tbl.sub_table,'')) as table_name,
			per_people_all.first_name as waiter_name
			
            from ord_order_headers as header_tbl
		
		left join ord_order_lines as line_tbl on line_tbl.header_id= header_tbl.header_id
		left join per_user on per_user.user_id = header_tbl.customer_id
		left join cus_consumers as cus_customers on cus_customers.customer_id = per_user.reference_id
        left join pay_payment_types AS expense_payment_type on expense_payment_type.payment_type_id = header_tbl.payment_method
        left join cus_customer_address on cus_customer_address.customer_address_id = header_tbl.address_id  
        left join cus_consumers as pos_customer on pos_customer.customer_id = header_tbl.customer_id
		left join din_table_lines as din_tbl on din_tbl.line_id = header_tbl.table_id
		left join per_user as waiter on waiter.user_id = header_tbl.waiter_id
		left join per_people_all on per_people_all.person_id = waiter.person_id
		where header_tbl.header_id = '".$id."' ";

        $result = $this->db->query($query)->result_array();
		
		return $result;
    }

	function getOrderItemsPrint($id="")
    {
		//coalesce((t.quantity * t.price),0) - coalesce((t.offer_percentage / 100) * (t.quantity * t.price),0) as sub_total,
       $LineQuery = "
	    select 
		t.line_id,
		t.quantity,
		t.offer_percentage,
		t.tax_percentage,

		t.product_id,
		t.cancel_status,
		t.header_id,
		t.item_name,
		t.item_description,
		t.price,
		coalesce((t.quantity * t.price),0) as linetotal,
		coalesce((t.quantity * t.price),0) as sub_total,
		(t.tax_percentage / 100) * (coalesce((t.quantity * t.price),0) - coalesce((t.offer_percentage / 100) * (t.quantity * t.price),0)) as tax_value,
		coalesce((t.offer_percentage / 100) * (t.quantity * t.price),0) as offer_amount
		from
		(
			select 
			ord_order_lines.line_id,
			sum(ord_order_lines.quantity) as quantity,
			ord_order_lines.offer_percentage,
			ord_order_lines.tax_percentage,

			ord_order_lines.product_id,
			ord_order_lines.cancel_status,
			ord_order_headers.header_id,
			products.item_name,
			products.item_description,
			ord_order_lines.price,

			round((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price),2) as offer_amount, 
			round( (ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price)),2) as linetotal, 
			round(((ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

			from ord_order_lines

			left join ord_order_headers on 
			ord_order_headers.header_id = ord_order_lines.header_id

			left join inv_sys_items as products on 
			products.item_id = ord_order_lines.product_id

			where  ord_order_lines.cancel_status ='N' and
			ord_order_lines.header_id='".$id."'
			group by ord_order_lines.header_id,ord_order_lines.product_id
		) t
		";

        $result = $this->db->query($LineQuery)->result_array();
		return $result;
    }

	function getOrderslist()
	{
		if($_GET)
		{
			if($this->user_id==1) #Admin
			{
				#order_closed_status = 0-Not closd, 1-closed
				$condition = " 1=1 and order_status !=1 and order_status !=4";
				$joinQuery ="";
			}
			else #Branch Admins
			{
				$condition = " 1=1 and order_status !=1 and 
					order_status !=4 and
					branch_users.user_id='".$this->user_id."' and 
					branch_users.branch_id='".$this->admin_branch_id."'
				";
				
				$joinQuery ="
					join users as branch_users on branch_users.branch_id = ord_order_headers.branch_id 
				";
			}
			
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
									ord_order_headers.order_number like "%'.($_GET['keywords']).'%" or
									customer.mobile_number like "%'.($_GET['keywords']).'%" 
								)
								';
			}

			if(!empty($_GET['payment_type']))
			{
				$condition .= ' and ord_order_headers.payment_method='.$_GET['payment_type'];	
			}
			
			if(!empty($_GET['branch_id']))
			{
				$condition .= ' and branch.branch_id='.$_GET['branch_id'];
			}
			
			if(isset($_GET['status']) && $_GET['status'] == '0')
			{
				$condition .= ' and (
					ord_order_headers.order_status != 1 and
					ord_order_headers.order_status != 2 and
					ord_order_headers.order_status != 3 and
					ord_order_headers.order_status != 4 )
				';
			}
			else if(!empty($_GET['status']))
			{
				$condition .= ' and ord_order_headers.order_status='.$_GET['status'];
			}
			
			#From & To Date search start
			if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
			{
				$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
				$toDate = date("Y-m-d",strtotime($_GET['to_date']));
				
				$condition .= " and (ord_order_headers.order_date BETWEEN '".$fromDate."' and '".$toDate."') ";
			}
			
			if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
			{
				$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
				
				$condition .= " and ord_order_headers.order_date <= '".$fromDate."' ";
			}
			
			if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
			{
				$toDate = date("Y-m-d",strtotime($_GET['to_date']));
				
				#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
				$condition .= " and ord_order_headers.order_date >= '".$toDate."' ";
			}
			#From & To Date search end
			
			$query = "select 
				ord_order_headers.header_id,
				ord_order_headers.branch_id,
				ord_order_headers.order_number,
				ord_order_headers.created_date,
				ord_order_headers.sub_total,
				ord_order_headers.grand_total,
				ord_order_headers.order_status,
				ord_order_headers.created_date,
				ord_order_headers.order_type,
				ord_order_headers.payment_method,
				ord_order_headers.order_number,
				ord_order_headers.delivered_date,
				ord_order_headers.payment_status,
				ord_order_headers.wallet_chk_enabled,
				ord_order_headers.wallet_amount,
				ord_order_headers.payable_amount,
				ord_order_headers.tax_value,
				ord_order_headers.paid_status,
				ord_order_headers.offer_amount,
				
				expense_payment_type.payment_type,
				
				branch.branch_code,
				branch.branch_name,
				branch.address,
				branch.phone_number,
				branch.email,
				
				cus_customer.customer_id,
				cus_customer.customer_name,
				cus_customer.mobile_number,
				country.country_code,
				cus_customer_address.address as customer_address,
				cus_customer_address.address1,
				cus_customer_address.address2,
				cus_customer_address.address3
				
				
				
				from ord_order_headers

			left join cus_customer on cus_customer.customer_id = ord_order_headers.customer_id
			
			left join expense_payment_type on expense_payment_type.payment_type_id = ord_order_headers.payment_method
			
			left join cus_customer_address on 
				cus_customer_address.customer_address_id = ord_order_headers.address_id
			
			join branch on branch.branch_id = ord_order_headers.branch_id 
			
			left join country on 
				country.country_id = users.country_id
		
		
			$joinQuery
			
			where $condition
					order by 
							ord_order_headers.header_id DESC
					
			";
			//	limit ".$record." , ".$offset.";
			$result = $this->db->query($query)->result_array();
			
			return $result;
		} else
		{
			return array();
		}
	}
	
	function getCancelledOrderslist()
	{
		if($_GET)
		{
			if($this->user_id==1) #Admin
			{
				#order_closed_status = 0-Not closd, 1-closed
				$condition = " 1=1 and order_status !=1 and order_status =4";
				$joinQuery ="";
			}
			else #Branch Admins
			{
				$condition = " 1=1 and order_status !=1 and 
					order_status =4 and
					branch_users.user_id='".$this->user_id."' and 
					branch_users.branch_id='".$this->admin_branch_id."'
				";
				
				$joinQuery ="
					join users as branch_users on branch_users.branch_id = ord_order_headers.branch_id 
				";
			}
			
			if(!empty($_GET['keywords']))
			{
				$condition .= ' and (
									ord_order_headers.order_number like "%'.($_GET['keywords']).'%" or
									customer.mobile_number like "%'.($_GET['keywords']).'%" 
								)
								';
			}

			if(!empty($_GET['payment_type']))
			{
				$condition .= ' and ord_order_headers.payment_method='.$_GET['payment_type'];	
			}
			
			if(!empty($_GET['branch_id']))
			{
				$condition .= ' and branch.branch_id='.$_GET['branch_id'];
			}
			
			if(isset($_GET['status']) && $_GET['status'] == '0')
			{
				$condition .= ' and (
					ord_order_headers.order_status != 1 and
					ord_order_headers.order_status != 2 and
					ord_order_headers.order_status != 3 and
					ord_order_headers.order_status != 4 )
				';
			}
			else if(!empty($_GET['status']))
			{
				$condition .= ' and ord_order_headers.order_status='.$_GET['status'];
			}
			
			#From & To Date search start
			if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
			{
				$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
				$toDate = date("Y-m-d",strtotime($_GET['to_date']));
				
				$condition .= " and (ord_order_headers.order_date BETWEEN '".$fromDate."' and '".$toDate."') ";
			}
			
			if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
			{
				$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
				
				$condition .= " and ord_order_headers.order_date <= '".$fromDate."' ";
			}
			
			if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
			{
				$toDate = date("Y-m-d",strtotime($_GET['to_date']));
				
				#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
				$condition .= " and ord_order_headers.order_date >= '".$toDate."' ";
			}
			#From & To Date search end
			
			$query = "select 
				ord_order_headers.header_id,
				ord_order_headers.branch_id,
				ord_order_headers.order_number,
				ord_order_headers.created_date,
				ord_order_headers.sub_total,
				ord_order_headers.grand_total,
				ord_order_headers.order_status,
				ord_order_headers.order_type,
				ord_order_headers.payment_method,
				ord_order_headers.order_number,
				ord_order_headers.delivered_date,
				ord_order_headers.payment_status,
				ord_order_headers.wallet_chk_enabled,
				ord_order_headers.wallet_amount,
				ord_order_headers.payable_amount,
				ord_order_headers.tax_value,
				ord_order_headers.paid_status,
				ord_order_headers.offer_amount,
				
				expense_payment_type.payment_type,
				
				branch.branch_code,
				branch.branch_name,
				branch.address,
				branch.phone_number,
				branch.email,
				
				customer.customer_id,
				customer.customer_name,
				customer.mobile_number,
				country.country_code,
				cus_customer_address.address as customer_address,
				cus_customer_address.door_number,
				cus_customer_address.building_number,
				cus_customer_address.address_line_2,
				cus_customer_address.delivery_address as delivery_address
				
				
				from ord_order_headers

			left join customer on customer.customer_id = ord_order_headers.customer_id
			
			left join expense_payment_type on expense_payment_type.payment_type_id = ord_order_headers.payment_method
			
			left join cus_customer_address on 
				cus_customer_address.customer_address_id = ord_order_headers.address_id
			
			join branch on branch.branch_id = ord_order_headers.branch_id 
			
			left join country on 
				country.country_id = customer.country_id
		
		
			$joinQuery
			
			where $condition
					order by 
							ord_order_headers.header_id DESC
					
			";
			//	limit ".$record." , ".$offset.";


			
			$result = $this->db->query($query)->result_array();
			
			return $result;
		} else
		{
			return array();
		}
	}
	
	function getOrderslistamount()
	{
		if($this->user_id==1) #Admin
		{
			#order_closed_status = 0-Not closd, 1-closed
			$condition = " 1=1 and ord_order_headers.order_status !=1 and 
					ord_order_headers.order_status !=4 and ord_order_lines.cancel_status != 1";
			$joinQuery ="";
		}
		else #Branch Admins
		{
			$condition = " 1=1 and order_status !=1 and 
				order_status !=4 and
				and ord_order_lines.cancel_status != 1 and 
				branch_users.user_id='".$this->user_id."' and 
				branch_users.branch_id='".$this->admin_branch_id."'
			";
			
			$joinQuery ="
				join users as branch_users on branch_users.branch_id = ord_order_headers.branch_id 
			";
		}
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								ord_order_headers.order_number like "%'.($_GET['keywords']).'%" or
								customer.mobile_number like "%'.($_GET['keywords']).'%" 
							)
							';
		}

		if(!empty($_GET['payment_type']))
		{
			$condition .= ' and ord_order_headers.payment_method='.$_GET['payment_type'];	
		}
		
		if(!empty($_GET['branch_id']))
		{
			$condition .= ' and branch.branch_id='.$_GET['branch_id'];
		}
		
		if(isset($_GET['status']) && $_GET['status'] == '0')
		{
			$condition .= ' and (
				ord_order_headers.order_status != 1 and
				ord_order_headers.order_status != 2 and
				ord_order_headers.order_status != 3 and
				ord_order_headers.order_status != 4 )
			';
		}
		else if(!empty($_GET['status']))
		{
			$condition .= ' and ord_order_headers.order_status='.$_GET['status'];
		}
		
		#From & To Date search start
		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$condition .= " and (ord_order_headers.order_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if( !empty($_GET['from_date']) && empty($_GET['to_date']) )
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			
			$condition .= " and ord_order_headers.order_date <= '".$fromDate."' ";
		}
		
		if( empty($_GET['from_date']) && !empty($_GET['to_date']) )
		{
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and ord_order_headers.order_date >= '".$toDate."' ";
		}
		#From & To Date search end
		
		$query = "select 
		
			sum(ord_order_headers.grand_total) as order_grand_total,
			ord_order_headers.tax_value
			
			from ord_order_headers

		join ord_order_lines on ord_order_lines.header_id = ord_order_headers.header_id

	
		$joinQuery
		
		where $condition
			order by 
				ord_order_headers.header_id DESC
		";
		//	limit ".$record." , ".$offset.";

		//echo $query;exit;
		$result = $this->db->query($query)->result_array();
		
		return $result;
		
	}

	function getOpenPrintOrderDetails($id="")
    {
        $query = "select 
			header_tbl.interface_header_id as header_id,
			header_tbl.order_number,
			header_tbl.order_status,
			header_tbl.order_type,
			header_tbl.payment_method,
			header_tbl.created_date,
			header_tbl.order_source,
			header_tbl.bill_print_count,
			cus_customers.email_address,
			cus_customers.customer_name,
			cus_customers.mobile_number,
			cus_customer_address.address1,
			cus_customer_address.address2,
			cus_customer_address.address_name,
			cus_customer_address.postal_code,
			cus_customer_address.address1,
			cus_customer_address.land_mark,
            expense_payment_type.payment_type,
			line_tbl.quantity,

			pos_customer.customer_name as pos_customer_name,
			pos_customer.mobile_number as pos_mobile_number,
			pos_customer.address1 as pos_address1,
			pos_customer.address2 as pos_address2,
			pos_customer.address3 as pos_address3,
			pos_customer.postal_code as pos_postal_code,
			CONCAT(din_tbl.table_code,coalesce(header_tbl.sub_table,'')) as table_name,
			per_people_all.first_name as waiter_name
			
            from ord_order_interface_headers as header_tbl
		
		left join ord_order_interface_lines as line_tbl on line_tbl.reference_header_id= header_tbl.interface_header_id
		left join per_user on per_user.user_id = header_tbl.customer_id
		left join cus_consumers as cus_customers on cus_customers.customer_id = per_user.reference_id
        left join pay_payment_types AS expense_payment_type on expense_payment_type.payment_type_id = header_tbl.payment_method
        left join cus_customer_address on cus_customer_address.customer_address_id = header_tbl.address_id  
        left join cus_consumers as pos_customer on pos_customer.customer_id = header_tbl.customer_id
		left join din_table_lines as din_tbl on din_tbl.line_id = header_tbl.table_id
		left join per_user as waiter on waiter.user_id = header_tbl.waiter_id
		left join per_people_all on per_people_all.person_id = waiter.person_id
		where 
			header_tbl.interface_header_id = '".$id."' ";

        $result = $this->db->query($query)->result_array();
		
		return $result;
    }

	function getOpenPrintOrderItems($id="")
    {
		#coalesce((t.quantity * t.price),0) - coalesce((t.offer_percentage / 100) * (t.quantity * t.price),0) as sub_total,
		$LineQuery = "
		select 
		t.line_id,
		t.quantity,
		t.previous_quantity,
		t.offer_percentage,
		t.tax_percentage,
		t.cooking_instructions,
		t.product_id,
		t.cancel_status,
		t.reference_header_id,
		t.item_name,
		t.item_description,
		t.price,
		coalesce((t.quantity * t.price),0) as linetotal,
		coalesce((t.quantity * t.price),0)  as sub_total,
		(t.tax_percentage / 100) * (coalesce((t.quantity * t.price),0) - coalesce((t.offer_percentage / 100) * (t.quantity * t.price),0)) as tax_value,
		coalesce((t.offer_percentage / 100) * (t.quantity * t.price),0) as offer_amount
		from
		(
			select 
			line_tbl.interface_line_id as line_id,
			sum(line_tbl.quantity) as quantity,
			line_tbl.previous_quantity,
			line_tbl.offer_percentage,
			line_tbl.tax_percentage,
			line_tbl.cooking_instructions,

			line_tbl.product_id,
			line_tbl.cancel_status,
			line_tbl.reference_header_id,
			products.item_name,
			products.item_description,
			line_tbl.price,

			round((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price),2) as offer_amount, 
			round( (line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as linetotal, 
			round(((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

			from ord_order_interface_lines as line_tbl

			left join ord_order_interface_headers as header_tbl on 
				header_tbl.interface_header_id = line_tbl.reference_header_id

			left join inv_sys_items as products on 
			products.item_id = line_tbl.product_id

			where 1=1
			and line_tbl.reference_header_id='".$id."'
			and line_tbl.cancel_status='N'
			group by line_tbl.product_id,line_tbl.reference_header_id
		) as t
		";
		
        $result = $this->db->query($LineQuery)->result_array();

		return $result;
    }

	function getKOTOrderItems($id="")
    {
		$LineQuery = "select 
		line_tbl.interface_line_id as line_id,
		(line_tbl.quantity - line_tbl.previous_quantity) as quantity,
		line_tbl.previous_quantity,
		line_tbl.offer_percentage,
		line_tbl.tax_percentage,
		line_tbl.cooking_instructions,

		line_tbl.product_id,
		line_tbl.cancel_status,
		line_tbl.reference_header_id,
		products.item_name,
		products.item_description,
		line_tbl.price,

		round((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price),2) as offer_amount, 
		round( (line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as linetotal, 
		round(((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

		from ord_order_interface_lines as line_tbl

		left join ord_order_interface_headers as header_tbl on 
			header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as products on 
		products.item_id = line_tbl.product_id

		where 1=1
		and line_tbl.reference_header_id='".$id."'
		and (line_tbl.quantity - line_tbl.previous_quantity) > 0
		";
		//and line_tbl.kot_print_status != 'Y'
        $result = $this->db->query($LineQuery)->result_array();

		return $result;
    }

	function getDineInKOTOrderItems($id="")
    {
		$LineQuery = "select 
		line_tbl.interface_line_id as line_id,
		(line_tbl.quantity - line_tbl.previous_quantity) as quantity,
		line_tbl.previous_quantity,
		line_tbl.offer_percentage,
		line_tbl.tax_percentage,
		line_tbl.cooking_instructions,

		line_tbl.product_id,
		line_tbl.cancel_status,
		line_tbl.reference_header_id,
		products.item_name,
		products.item_description,
		line_tbl.price,

		round((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price),2) as offer_amount, 
		round( (line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as linetotal, 
		round(((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

		from ord_order_interface_lines as line_tbl

		left join ord_order_interface_headers as header_tbl on 
			header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as products on 
		products.item_id = line_tbl.product_id

		where 1=1
		and line_tbl.reference_header_id='".$id."'
		and (line_tbl.quantity - line_tbl.previous_quantity) > 0
		and line_tbl.kot_print_status != 'Y'";
        $result = $this->db->query($LineQuery)->result_array();

		return $result;
    }
	
	function getOrderHeaderDetails($id="")
    {
        $query = "select * from ord_order_headers as header_tbl
		where header_tbl.header_id = '".$id."' ";
		$result = $this->db->query($query)->result_array();
		return $result;
    }

	function getOrderLineDetails($id="")
    {
       $LineQuery = "select 
		ord_order_lines.line_id,
		ord_order_lines.quantity,
		ord_order_lines.offer_percentage,
		ord_order_lines.tax_percentage,

		ord_order_lines.product_id,
		ord_order_lines.cancel_status,
		ord_order_headers.header_id,
		products.item_name,
		products.item_description,
		ord_order_lines.price,

		round((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price),2) as offer_amount, 
		round( (ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price)),2) as linetotal, 
		round(((ord_order_lines.quantity * ord_order_lines.price) - ((coalesce(offer_percentage,0) / 100) * (ord_order_lines.quantity * ord_order_lines.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

		from ord_order_lines

		left join ord_order_headers on 
		ord_order_headers.header_id = ord_order_lines.header_id

		left join inv_sys_items as products on 
		products.item_id = ord_order_lines.product_id

		where 1=1 and
		ord_order_lines.header_id='".$id."'";

        $result = $this->db->query($LineQuery)->result_array();
		return $result;
    }
	
	
	function getKOTCompletedOrderItems($id="")
    {
		$LineQuery = "select 
		line_tbl.line_id,
		line_tbl.offer_percentage,
		line_tbl.tax_percentage,
		line_tbl.quantity,

		line_tbl.product_id,
		line_tbl.cancel_status,
		line_tbl.reference_header_id,
		products.item_name,
		products.item_description,
		line_tbl.price,
		line_tbl.cooking_instructions,
		round((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price),2) as offer_amount, 
		round( (line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as linetotal, 
		round(((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

		from ord_order_lines as line_tbl

		left join ord_order_headers as header_tbl on 
			header_tbl.header_id = line_tbl.header_id

		left join inv_sys_items as products on 
		products.item_id = line_tbl.product_id

		where 1=1
		and line_tbl.header_id='".$id."'
		and (line_tbl.quantity) > 0
		";
		//and line_tbl.kot_print_status != 'Y'
        $result = $this->db->query($LineQuery)->result_array();

		return $result;
    }

	function getDineInOrderSeqKOTOrder($id="",$order_seq_number='')
    {
		$LineQuery = "select 
		line_tbl.interface_line_id as line_id,
		(line_tbl.quantity - line_tbl.previous_quantity) as quantity,
		line_tbl.previous_quantity,
		line_tbl.offer_percentage,
		line_tbl.tax_percentage,
		line_tbl.cooking_instructions,

		line_tbl.product_id,
		line_tbl.cancel_status,
		line_tbl.reference_header_id,
		products.item_name,
		products.item_description,
		line_tbl.price,

		round((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price),2) as offer_amount, 
		round( (line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as linetotal, 
		round(((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0)/100),2) as tax_value 

		from ord_order_interface_lines as line_tbl

		left join ord_order_interface_headers as header_tbl on 
			header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as products on 
		products.item_id = line_tbl.product_id

		where 1=1
		and line_tbl.reference_header_id='".$id."'
		and line_tbl.order_seq_number='".$order_seq_number."'
		and (line_tbl.quantity - line_tbl.previous_quantity) > 0
		";

		
        $result = $this->db->query($LineQuery)->result_array();

		return $result;
    }
}
