<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Summary_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function onhandAvailability($offset="",$record="",$countType="")
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

			$organization_id = !empty($_GET['organization_id']) ? $_GET['organization_id'] : 'NULL';
			$branch_id = !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			$item_id = !empty($_GET['item_id']) ? $_GET['item_id'] : 'NULL';
			
			$query = "
			select
				item.item_name,
				organization.organization_name,
				branch.branch_name,
				sub_inventory.inventory_code,
				item_locators.locator_no,
				transaction.lot_number,
				transaction.serial_number,
				sum(transaction.transaction_qty) as trans_qty
				from inv_transactions as transaction
				left join inv_sys_items as item on item.item_id = transaction.item_id
				left join inv_categories as category on category.category_id = item.category_id
				left join org_organizations as organization on organization.organization_id = transaction.organization_id
				left join branch on branch.branch_id=transaction.branch_id
				left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
				left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
				where 1=1 
				and item.item_id = coalesce($item_id,item.item_id)
				and organization.organization_id = coalesce($organization_id,organization.organization_id)
				and branch.branch_id = coalesce($branch_id,branch.branch_id)
				
				
				group by 
				item.item_name,
				organization.organization_name,
				sub_inventory.inventory_code,
				item_locators.locator_no,
				transaction.lot_number,
				transaction.serial_number
				HAVING trans_qty != 0
				$limit";
					
				/*$query = "select 
				header_tbl.*,
				bom_department_header.department_name
		 		from inv_wip_headers as header_tbl

				left join bom_department_header on 
					bom_department_header.department_header_id = header_tbl.department_id

				where 1=1
				
				and header_tbl.department_id = coalesce($department_id,header_tbl.department_id)
				and header_tbl.wip_number like coalesce($wip_number,header_tbl.wip_number)
				and ( 
					date_format(header_tbl.wip_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.wip_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.wip_date, '%Y-%m-%d'))
				)
			order by header_tbl.wip_header_id desc $limit" ; */
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function getSalesSummary($offset="",$record="", $countType="")
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

			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$cardQry = "select 
					(
						select sum(t1.Total_Order_Amount) from 
						(select sum((ord_order_lines_v.order_amount + tax_amount)- offer_amount) as Total_Order_Amount
						from ord_order_lines_v
						left join ord_order_headers_v on ord_order_headers_v.header_id = ord_order_lines_v.header_id
						where 1=1
						and ord_order_headers_v.order_status in ('Delivered', 'Closed')
						and ord_order_lines_v.cancel_status != 'Y'
						and (DATE_FORMAT(ord_order_headers_v.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ord_order_headers_v.ordered_date) and coalesce('".$toDate."',ord_order_headers_v.ordered_date))
						group by ord_order_lines_v.header_id) t1
					) Total_Order_Amount,
			
					(
						select sum(t1.Total_Cancelled_Amount) from 
						(select sum((ord_order_lines_v.order_amount + tax_amount)- offer_amount) as Total_Cancelled_Amount
						from ord_order_lines_v
						left join ord_order_headers_v on ord_order_headers_v.header_id = ord_order_lines_v.header_id
						where 1=1
						and ord_order_headers_v.order_status in ('Delivered', 'Closed')
						and ord_order_lines_v.cancel_status = 'Y'
						and (DATE_FORMAT(ord_order_headers_v.ordered_date, '%Y-%m-%d') 
							BETWEEN coalesce('".$fromDate."',ord_order_headers_v.ordered_date) 
								and coalesce('".$toDate."',ord_order_headers_v.ordered_date))
						group by ord_order_lines_v.header_id) t1
					) Total_Cancelled_Amount
			from dual";
			$result["cardResult"] = $this->db->query($cardQry)->result_array();
			//print_r($cardQry);
			$listQry ="
					select t.branch_id, 
					t.branch_name, 
					t.ordered_date,
					sum(t.Total_Order_Amount) as Total_Order_Amount, 
					sum(t.Total_Cancelled_Amount) as Total_Cancelled_Amount
					from
					(
						select 
						ord_order_headers_v.branch_name,
						DATE_FORMAT(ord_order_headers_v.ordered_date, '%Y-%m-%d') AS ordered_date,
						ord_order_headers_v.branch_id,
						(
							select sum((ord_lines1.order_amount + tax_amount) - offer_amount) as Total_Order_Amount
							from ord_order_lines_v as ord_lines1
							left join ord_order_headers_v as ord_header1 on ord_header1.header_id = ord_lines1.header_id
							where 1 = 1
							and ord_lines1.header_id = ord_order_headers_v.header_id 
							and ord_header1.order_status in ('Delivered', 'Closed')
							and ord_lines1.cancel_status != 'Y'
							and (DATE_FORMAT(ord_header1.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ord_header1.ordered_date) and coalesce('".$toDate."',ord_header1.ordered_date))
						) as Total_Order_Amount,
					
						(
							select sum(ord_lines2.order_amount- offer_amount) as Total_Cancelled_Amount
							from ord_order_lines_v as ord_lines2
							left join ord_order_headers_v as ord_header2 on ord_header2.header_id = ord_lines2.header_id
							where 1 = 1
							and ord_lines2.header_id = ord_order_headers_v.header_id 
							and ord_header2.order_status in ('Delivered', 'Closed')
							and ord_lines2.cancel_status = 'Y'
							and (DATE_FORMAT(ord_header2.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ord_header2.ordered_date) and coalesce('".$toDate."',ord_header2.ordered_date))
						) as Total_Cancelled_Amount
						from ord_order_headers_v
						where 1=1
						and (DATE_FORMAT(ord_order_headers_v.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ord_order_headers_v.ordered_date) 
						and coalesce('".$toDate."',ord_order_headers_v.ordered_date))
					) t
			group by t.branch_id,t.ordered_date
			
			order by t.branch_name";

			$result["listing"] = $this->db->query($listQry)->result_array();

			$result["totalCount"] = $result["listing"];
			return $result;
		}
		else
		{
			$result["cardResult"] = array();
			$result["listing"] = array();

			$result["totalCount"] = $result["listing"];
			return $result;
		}
	}

	function getCustomerSOA($offset="",$record="", $countType="")
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

			$customer_id = !empty($_GET['customer_id']) ? $_GET['customer_id']:'NULL';
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			/* $listQry = "
			select 
			t.customer_name,
			t.invoice_number,
			t.invoice_date,
			t.sales_total,
			coalesce(t.paid_amount,null,0) paid_amount,
			coalesce(t.sales_total,null,0) - coalesce(t.paid_amount,null,0) balance_amount,
			t.age
			from 
			(
				select 
				customer_tbl.customer_name,
				customer_tbl.mobile_number,
				header_tbl.invoice_number,

				date_format(header_tbl.invoice_date,'%d-%M-%Y') as invoice_date,
				round(sum(line_tbl.quantity * line_tbl.price),2) as amount,
				round(sum((line_tbl.quantity * line_tbl.price) * (line_tbl.tax/100)),2) as tax,        

				 round(sum(line_tbl.total),0) sales_total, 

				(select round(sum(payment_amount),2) from inv_invoice_payment_line where invoice_id = header_tbl.header_id) paid_amount,
				DATEDIFF(date_format(curdate(),'%Y-%m-%d'), date_format(header_tbl.invoice_date,'%Y-%m-%d')) age

				from inv_invoice_lines as line_tbl

				left join inv_invoice_headers as header_tbl on
				header_tbl.header_id = line_tbl.header_id
				
				left join cus_customers as customer_tbl on
				customer_tbl.customer_id = header_tbl.customer_id
				
				where 1=1
				and customer_tbl.customer_id = coalesce(".$customer_id.", customer_tbl.customer_id)	
				
				and (
					date_format(header_tbl.invoice_date, '%Y-%m-%d')
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.invoice_date, '%Y-%m-%d'))
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.invoice_date, '%Y-%m-%d'))
				)
				group by header_tbl.header_id
			) t
			where 
			coalesce(t.sales_total,null,0) - coalesce(t.paid_amount,null,0) > 0
			order by customer_name asc,age desc
			"; */
			
			$customer_name = "concat('%','".serchFilter($_GET['customer_name'])."','%')";
			$invoice_number = "concat('%','".serchFilter($_GET['invoice_number'])."','%')";
			

			$listQry = "
			select 
			t.invoice_source,
			t.customer_name,
			t.invoice_number,
			t.invoice_date,
			round(coalesce(t.sales_total,0)) as sales_total,
			round(coalesce(t.paid_amount,null,0)) paid_amount,
			round(coalesce(t.sales_total,null,0) - coalesce(t.paid_amount,null,0),0) as balance_amount,
			t.age,
			t.amount,
			t.tax,
			t.linetotal,
			t.tax_value
			from 
			(
				(
					select
					'Party Orders' as invoice_source,
					customer_tbl.customer_name,
					header_tbl.invoice_number,

					date_format(header_tbl.invoice_date,'%d-%M-%Y') as invoice_date,
					round(sum(line_tbl.quantity * line_tbl.price),2) as amount,
					round(sum((line_tbl.quantity * line_tbl.price) * (line_tbl.tax/100)),2) as tax,        
					round(sum(line_tbl.total),0) sales_total, 
					'' linetotal,
					'' tax_value,
					
					(select round(sum(payment_amount),2) from inv_invoice_payment_line as inv_line_tbl
					left join inv_invoice_payment_header as inv_header_tbl on inv_header_tbl.header_id = inv_line_tbl.header_id
					where 1=1 and inv_line_tbl.invoice_id = header_tbl.header_id and inv_header_tbl.invoice_source='PARTY-ORDERS') paid_amount,

					DATEDIFF(date_format(curdate(),'%Y-%m-%d'), date_format(header_tbl.invoice_date,'%Y-%m-%d')) age

					from inv_invoice_lines as line_tbl

					left join inv_invoice_headers as header_tbl on
					header_tbl.header_id = line_tbl.header_id
					
					left join cus_customers as customer_tbl on
					customer_tbl.customer_id = header_tbl.customer_id
					
					where 1=1
					and customer_tbl.customer_name like coalesce($customer_name,customer_tbl.customer_name)
					and header_tbl.invoice_number  like coalesce($invoice_number,header_tbl.invoice_number )
					
					and customer_tbl.customer_id = coalesce(".$customer_id.", customer_tbl.customer_id)	
				
					and (
						date_format(header_tbl.invoice_date, '%Y-%m-%d')
						BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.invoice_date, '%Y-%m-%d'))
						and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.invoice_date, '%Y-%m-%d'))
					)

					group by header_tbl.header_id 
				)
				
				UNION ALL
				
				(
					select 
					'Online Orders' as invoice_source,
					consumer.customer_name as customer_name,
					ord_header_tbl.order_number as invoice_number,
				
					date_format(ord_header_tbl.ordered_date,'%d-%M-%Y') as invoice_date,
					
					round(sum(ord_line_tbl.quantity * ord_line_tbl.price),2) as amount,
					round(sum((ord_line_tbl.quantity * ord_line_tbl.price) * (ord_line_tbl.tax_percentage/100)),2) as tax,  
						
					((select round( sum((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))),2) from ord_order_headers as header_tbl1
					left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
					where 1=1
					and ord_header_tbl.header_id = header_tbl1.header_id 
					and line_tbl1.line_status != 'Cancelled')
					
					+
					
					(select round( sum( ((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))) * (coalesce(tax_percentage,0) /100)),2) from ord_order_headers as header_tbl1
					left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
					where 1=1
					and ord_header_tbl.header_id = header_tbl1.header_id 
					and line_tbl1.line_status != 'Cancelled')) as sales_total,
				
					(select round( sum((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))),2) from ord_order_headers as header_tbl1
					left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
					where 1=1
					and ord_header_tbl.header_id = header_tbl1.header_id 
					and line_tbl1.line_status != 'Cancelled') as linetotal,
					
					(select round( sum( ((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))) * (coalesce(tax_percentage,0) /100)),2) from ord_order_headers as header_tbl1
					left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
					where 1=1
					and ord_header_tbl.header_id = header_tbl1.header_id 
					and line_tbl1.line_status != 'Cancelled') as tax_value,
				
					(select round(sum(payment_amount),2) from inv_invoice_payment_line as inv_line_tbl
					left join inv_invoice_payment_header as inv_header_tbl on inv_header_tbl.header_id = inv_line_tbl.header_id
					where 1=1 and inv_line_tbl.invoice_id = ord_header_tbl.header_id and inv_header_tbl.invoice_source='ONLINE-ORDERS') paid_amount,
					
					DATEDIFF(date_format(curdate(),'%Y-%m-%d'), date_format(ord_header_tbl.ordered_date,'%Y-%m-%d')) age
					
					from ord_order_lines as ord_line_tbl
					left join ord_order_headers as ord_header_tbl on ord_header_tbl.header_id = ord_line_tbl.header_id
					
					left join per_user as user on
					user.user_id = ord_header_tbl.customer_id
					
					left join cus_consumers as consumer on
					consumer.customer_id = user.reference_id
						
					where 1=1
					and ord_header_tbl.payment_due='Unpaid'
					and ord_header_tbl.order_number like coalesce($invoice_number,ord_header_tbl.order_number)
					and consumer.customer_name like coalesce($customer_name,consumer.customer_name)
					and consumer.customer_id = coalesce(".$customer_id.", consumer.customer_id)	
					
					and (
						date_format(ord_header_tbl.ordered_date, '%Y-%m-%d')
						BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(ord_header_tbl.ordered_date, '%Y-%m-%d'))
						and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(ord_header_tbl.ordered_date, '%Y-%m-%d'))
					)
					group by ord_header_tbl.header_id
				)
			) t
			where 
			1=1
			and round(coalesce(t.sales_total,null,0) - coalesce(t.paid_amount,null,0),0) > 0
			order by customer_name asc,age desc,invoice_source asc
			$limit ";
			$result = $this->db->query($listQry)->result_array();
			return $result;
		}
		else
		{
			$result = array();
			
			return $result;
		}
	}

	function getSupplierSOA($offset="",$record="", $countType="")
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

			$supplier_id = !empty($_GET['supplier_id']) ? $_GET['supplier_id']:'NULL';
			$supplier_site_id = !empty($_GET['supplier_site_id']) ? $_GET['supplier_site_id']:'NULL';

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$listQry = "
			select 
			t.supplier_name,
			t.site_name,
			t.po_number,
			t.receipt_number,
			t.receipt_date,
			
			coalesce(t.paid_amount,null,0) paid_amount,
			coalesce(t.sales_total,null,0) - coalesce(t.paid_amount,null,0) balance_amount,
			t.age,
            
            coalesce(t.amount,null,0) amount,
            coalesce(t.discount_amount,null,0) discount_amount,
            coalesce(t.tax_amount,null,0) tax_amount,
            
            coalesce(t.discount_amount,null,0) per_discount_amount,
            
            (coalesce(t.amount,null,0) - coalesce(t.discount_amount,null,0) + coalesce(t.tax_amount,null,0)) as sales_total

			from 
			(
				select 
				supplier_tbl.supplier_name,
				supplier_site_tbl.site_name,
				po_header_tbl.po_number,
				header_tbl.receipt_number,
				date_format(header_tbl.receipt_date,'%d-%M-%Y') as receipt_date,

				round(sum(po_line_tbl.quantity * po_line_tbl.price),2) as amount,
				round(sum((po_line_tbl.quantity * po_line_tbl.price) * (po_line_tbl.tax/100)),2) as tax_amount,     
                round(sum((po_line_tbl.quantity * po_line_tbl.price) * (po_line_tbl.discount/100)),2) as per_discount_amount,
                
                if(discount_type = 'Percentage',round(sum((po_line_tbl.quantity * po_line_tbl.price) * (po_line_tbl.discount/100)),2),po_line_tbl.discount ) as discount_amount,
               
				round(sum(po_line_tbl.total),0) sales_total, 

				(select round(sum(payment_amount),2) from inv_supplier_payment_line where receipt_id = header_tbl.receipt_header_id) paid_amount,

				DATEDIFF(date_format(curdate(),'%Y-%m-%d'), date_format(header_tbl.receipt_date,'%Y-%m-%d')) age

				from rcv_receipt_lines as line_tbl

				left join rcv_receipt_headers as header_tbl on
					header_tbl.receipt_header_id = line_tbl.receipt_header_id
				
				left join po_headers as po_header_tbl on
					po_header_tbl.po_header_id = header_tbl.po_header_id

				left join po_lines as po_line_tbl on
					po_line_tbl.po_header_id = po_header_tbl.po_header_id
				
				left join sup_suppliers as supplier_tbl on
					supplier_tbl.supplier_id = po_header_tbl.supplier_id

				left join sup_supplier_sites as supplier_site_tbl on
					supplier_site_tbl.supplier_site_id = po_header_tbl.supplier_site_id
				
				where 1=1
				and supplier_tbl.supplier_id = coalesce(".$supplier_id.", supplier_tbl.supplier_id)	
				and supplier_site_tbl.supplier_site_id = coalesce(".$supplier_site_id.", supplier_site_tbl.supplier_site_id)	
				
				and (
					date_format(header_tbl.receipt_date, '%Y-%m-%d')
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.receipt_date, '%Y-%m-%d'))
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.receipt_date, '%Y-%m-%d'))
				)
				group by header_tbl.receipt_header_id
			) t
			where 
			coalesce(t.sales_total,null,0) - coalesce(t.paid_amount,null,0) > 0
			order by supplier_name asc,age desc $limit";


			$result = $this->db->query($listQry)->result_array();
			return $result;
		}
		else
		{
			$result = array();
			
			return $result;
		}

	}

	function minimumStock($offset="",$record="",$countType="")
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

			$organization_id = !empty($_GET['organization_id']) ? $_GET['organization_id'] : 'NULL';
			$item_id = !empty($_GET['item_id']) ? $_GET['item_id'] : 'NULL';
			
			$query = "
			select
			item.item_name,
			coalesce(item.minimum_qty,null,0) as minimum_qty,

			
			organization.organization_name,
			sub_inventory.inventory_code,
			item_locators.locator_no,
			transaction.lot_number,
			transaction.serial_number,
			sum(transaction.transaction_qty) as trans_qty
			from inv_transactions as transaction
			left join inv_sys_items as item on item.item_id = transaction.item_id
			left join inv_categories as category on category.category_id = item.category_id
			left join org_organizations as organization on organization.organization_id = transaction.organization_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
			where 1=1 
			and item.item_id = coalesce($item_id,item.item_id)
			and organization.organization_id = coalesce($organization_id,organization.organization_id)
			

			group by 
			item.item_name,
			organization.organization_name,
			sub_inventory.inventory_code,
			item_locators.locator_no,
			transaction.lot_number,
			transaction.serial_number
			HAVING trans_qty != 0
			$limit";
					
				
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function getItemWiseSalesSummary($offset="",$record="", $countType="")
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

			$branch_id = !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			$item_id = !empty($_GET['item_id']) ? $_GET['item_id'] : 'NULL';

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$listQry ="
			SELECT 
			cnt.item_id, 
			cnt.branch_name, 
			cnt.item_name, 
			cnt.category_name,
			SUM(cnt.total_order_amount) total_order_amount, 
			SUM(cnt.offer_amount) offer_amount, 
			SUM(cnt.tax_amount) tax_amount, 
			SUM(cnt.total_order_amount) - SUM(cnt.offer_amount) +  SUM(cnt.tax_amount) AS payment_amount,
			
			SUM(cnt.sales_count) sales_count
			FROM
			(
				SELECT inv_sys_items.item_id, 
				ohv.branch_name, 
				olv.item_name,
			
				(SELECT c1.category_name FROM inv_categories AS c1, inv_sys_items AS i1 WHERE c1.category_id = i1.category_id AND i1.item_id = olv.product_id) AS category_name, 
			
				(
					SELECT SUM(COALESCE(olv1.order_amount, 0)) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id  
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS total_order_amount, 
			
				(
					SELECT SUM(COALESCE(olv1.offer_amount,0)) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1 
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS offer_amount, 
			
			   (
					SELECT SUM(COALESCE(olv1.tax_amount,0))
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1 
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS tax_amount, 
			
				(
					SELECT SUM(COALESCE(olv1.order_amount, 0) - (coalesce(olv1.offer_amount,0))) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS payment_amount,
			
				(
					SELECT SUM(COALESCE(olv1.quantity, 0)) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS sales_count 
				
				FROM 
				ord_order_lines_v olv, 
				ord_order_headers_v ohv, 
				inv_sys_items, 
				inv_categories
				WHERE
				NOT ohv.order_status = 'Cancelled'
				AND ohv.header_id = olv.header_id
				AND inv_categories.category_id = inv_sys_items.category_id
				AND inv_sys_items.item_id = olv.product_id
				
				and (DATE_FORMAT(ohv.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ohv.ordered_date) and coalesce('".$toDate."',ohv.ordered_date))
				and ohv.branch_id = coalesce($branch_id,ohv.branch_id)
				and inv_sys_items.item_id = coalesce($item_id,inv_sys_items.item_id)
				
			) cnt
			WHERE 1=1
			GROUP BY
			cnt.item_id,
			cnt.branch_name
			
			ORDER BY
			cnt.branch_name ASC,
			cnt.item_id ASC
			$limit ";

			$result["listing"] = $this->db->query($listQry)->result_array();
			return $result;
		}
		else
		{
			$result["listing"] = array();
			return $result;
		}
	}
	function getCaptainWiseSalesSummary($offset="",$record="", $countType="")
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

			$user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : 'NULL';
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$listQry ="select 
			s.reference_header_id as reference_header_id,
			s.line_reference_header_id as line_reference_header_id,
			s.order_number,
			s.customer_name,
			s.mobile_number,
			s.branch_name,
			s.order_status,
			s.waiter_name as captain_name,
			SUM(s.total_order_amount) total_order_amount, 
			SUM(s.offer_amount) offer_amount, 
			SUM(s.tax_amount) tax_amount, 
			round(SUM(s.total_order_amount) - SUM(s.offer_amount) +  SUM(s.tax_amount)) AS payment_amount
			from 
			(
				select 
				ohv.reference_header_id,
				olv.reference_header_id as line_reference_header_id,
				ohv.customer_id,
				ohv.order_number,
				ohv.customer_name,
				ohv.mobile_number,
				ohv.branch_name,
				ohv.order_status,
				user.user_name as waiter_username,
				employee.first_name as waiter_name,
				(
					SELECT SUM(COALESCE(olv1.order_amount, 0)) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS total_order_amount, 
				
				(
					SELECT SUM(COALESCE(olv1.offer_amount,0)) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1 
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS offer_amount, 
				
				(
					SELECT SUM(COALESCE(olv1.tax_amount,0))
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1 
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS tax_amount, 
				
				(
					SELECT SUM(COALESCE(olv1.order_amount, 0) - (coalesce(olv1.offer_amount,0))) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id
				) 
				AS payment_amount
				from ord_order_headers_v as ohv
				left join per_user as user on user.user_id = ohv.waiter_id
				left join per_people_all as employee on employee.person_id = user.person_id
				left join ord_order_lines_v as olv on olv.header_id = ohv.header_id
				WHERE 1=1
				
				AND ohv.order_status NOT IN('Cancelled')
				AND ohv.order_source IN('DINE_IN')
				and (DATE_FORMAT(ohv.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ohv.ordered_date) and coalesce('".$toDate."',ohv.ordered_date))
				and user.user_id = coalesce($user_id,user.user_id)
			) s
			where 1=1
			group by s.line_reference_header_id
			order by s.reference_header_id desc $limit";

			$result["listing"] = $this->db->query($listQry)->result_array();
			
			return $result;

		}
		else
		{
			$result["listing"] = array();
			return $result;
		}
	}

	function getPrintJobStatusSummary($offset="",$record="", $countType="")
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
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$listQry = "select 
			i_header_tbl.order_number,
			branch.branch_name,
			job_status.job_id,
			job_status.description,
			job_status.file,
			job_status.print_type,
			job_status.sent_to_printer,
			job_status.print_status,
			coalesce(job_status.auto_print_count,0) as auto_print_count,
			coalesce(job_status.manual_print_count,0) as manual_print_count,
			job_status.jspm_status,
			job_status.created_by,
			job_status.created_date
			
			from org_print_job_status as job_status
			left join ord_order_interface_headers as i_header_tbl on i_header_tbl.interface_header_id = job_status.order_id
			left join branch on branch.branch_id = i_header_tbl.branch_id
			where 1=1
			and (DATE_FORMAT(job_status.created_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',job_status.created_date) and coalesce('".$toDate."',job_status.created_date))
			order by job_status.print_job_id desc $limit
			";

			$result["listing"] = $this->db->query($listQry)->result_array();
			
			return $result;

		}
		else
		{
			$result["listing"] = array();
			return $result;
		}
	}

	function getpurchaseOrderSummary($offset="",$record="", $countType="")
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

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			$po_status = !empty($_GET['po_status']) ? $_GET['po_status'] : NULL;
			$po_number = "concat('%','".serchFilter($_GET['po_number'])."','%')";
			$organization_id = !empty($_GET['organization_id']) ? $_GET['organization_id'] : 'NULL';
			$branch_id = !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			
			$query = "
					select *,
					po_headers.po_number,
					inv_sys_items.item_name,
					inv_sys_items.item_description,
					po_lines.base_price,
					po_lines.created_date,
					po_lines.line_status,
					inv_categories.category_name,
					(po_lines.discount / 100) * (po_lines.base_price*po_lines.quantity) as basetotal,
					uom.uom_code
					from po_lines

					left join po_headers on po_headers.po_header_id = po_lines.po_header_id
					left join inv_sys_items on inv_sys_items.item_id = po_lines.item_id
					left join inv_categories on inv_categories.category_id = po_lines.category_id
					left join uom on uom.uom_id = po_lines.uom
					left join org_organizations as organization on organization.organization_id = po_lines.organization_id
					left join branch on branch.branch_id = po_lines.branch_id
					where 1=1
					
					and (DATE_FORMAT(po_lines.created_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',po_lines.created_date) and coalesce('".$toDate."',po_lines.created_date))
					and po_lines.line_status = coalesce(if('".$po_status."' = '',NULL,'".$po_status."'),po_lines.line_status)
					and po_headers.po_number like coalesce($po_number,po_headers.po_number) 
					and organization.organization_id = coalesce($organization_id,organization.organization_id)
					and branch.branch_id = coalesce($branch_id,branch.branch_id)
					$limit
			";
			$result["line_data"] = $this->db->query($query)->result_array();
			
			return $result;
		}
		else
		{
			$result["line_data"] = array();
			return $result;
		}
	}
	
	function getRMSalesSummary($offset="",$record="", $countType="")
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

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			$line_status = !empty($_GET['line_status']) ? $_GET['line_status'] : NULL;
			
			$order_number = "concat('%','".serchFilter($_GET['order_number'])."','%')";
			
			$query = "
					select *,
					ord_sale_headers.order_number,
					inv_sys_items.item_name,
					inv_sys_items.item_description,
					ord_sale_lines.created_date,
					ord_sale_lines.line_status,
					inv_categories.category_name,
					uom.uom_code
					from ord_sale_lines

					left join ord_sale_headers on ord_sale_headers.sales_header_id = ord_sale_lines.sales_header_id
					left join inv_sys_items on inv_sys_items.item_id = ord_sale_lines.item_id
					left join inv_categories on inv_categories.category_id = ord_sale_lines.category_id
					left join uom on uom.uom_id = ord_sale_lines.uom
					left join org_organizations as organization on organization.organization_id = ord_sale_headers.organization_id
					left join branch on branch.branch_id = ord_sale_headers.branch_id
					left join payment_terms on payment_terms.payment_term_id = ord_sale_headers.payment_term_id
					where 1=1
					and (DATE_FORMAT(ord_sale_lines.created_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ord_sale_lines.created_date) and coalesce('".$toDate."',ord_sale_lines.created_date))
					and ord_sale_lines.line_status = coalesce(if('".$line_status."' = '',NULL,'".$line_status."'),ord_sale_lines.line_status)
					and ord_sale_headers.order_number like coalesce($order_number,ord_sale_headers.order_number)
			";
			$result["data"] = $this->db->query($query)->result_array();
			
			$result["totalCount"] = $result["data"];
			return $result;
		}
		else
		{
			$result["data"] = array();
			$result["totalCount"] = $result["data"];
			return $result;
		}
	}

	function getitemConsumptionSummary($offset="",$record="", $countType="")
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

			$branch_id 	= !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			$item_id	= !empty($_GET['item_id']) ? $_GET['item_id'] : 'NULL';
			$fromDate 	= !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate 	= !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			$query = "
				select
				t.item_id,
				t.item_name,
				t.item_description,
				t.item_cost,
				t.branch_name,
				t.organization_name,
				t.uom_code,
				t.received_quantity,
				t.sale_quantity,
				(t.sale_quantity * t.item_cost) as inventory_cost,
				(t.received_quantity - ABS(t.sale_quantity)) as balance_qty
				from 
				(
					select 
					rct_trns_tbl.item_id,
					rct_trns_tbl.created_date,
					rct_trns_tbl.transaction_type,
					sum(rct_trns_tbl.transaction_qty) as received_quantity,
					inv_sys_items.item_name,
					inv_sys_items.item_description,
					inv_sys_items.item_cost,
					organization.organization_name,
					branch.branch_name,
					uom.uom_code,
					
					(SELECT sum(sale_trns_tbl.transaction_qty) as sold_quantity 
					from inv_transactions AS sale_trns_tbl
					
					left join inv_sys_items on inv_sys_items.item_id = sale_trns_tbl.item_id
					left join uom on uom.uom_id = sale_trns_tbl.uom
					left join org_organizations as organization on organization.organization_id = sale_trns_tbl.organization_id
					left join branch on branch.branch_id = sale_trns_tbl.branch_id
					where 1=1
					and transaction_type = 'ORD'
					and rct_trns_tbl.item_id = sale_trns_tbl.item_id
					and rct_trns_tbl.branch_id = sale_trns_tbl.branch_id
					) as sale_quantity
					
					from inv_transactions AS rct_trns_tbl
					
					left join inv_sys_items on inv_sys_items.item_id = rct_trns_tbl.item_id
					left join uom on uom.uom_id = rct_trns_tbl.uom
					left join org_organizations as organization on organization.organization_id = rct_trns_tbl.organization_id
					left join branch on branch.branch_id = rct_trns_tbl.branch_id
					where 1=1
					and transaction_type = 'RCV'
					and inv_sys_items.item_id = coalesce($item_id,inv_sys_items.item_id)
					and branch.branch_id = coalesce($branch_id,branch.branch_id)
					and (DATE_FORMAT(rct_trns_tbl.created_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',rct_trns_tbl.created_date) and coalesce('".$toDate."',rct_trns_tbl.created_date))
					
					group by rct_trns_tbl.item_id
				)t;
			";

			$result["data"] = $this->db->query($query)->result_array();
			$result["totalCount"] = $result["data"];
			return $result;
		}
		else
		{
			$result["data"] = array();
			$result["totalCount"] = $result["data"];
			return $result;
		}
	}
}
