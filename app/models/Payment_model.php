<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payment_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageCustomerPayment($offset="",$record="", $countType="")
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

			
			if(empty($_GET['customer_id']))
			{
				$customer_id = 'NULL';
			}
			else
			{
				$customer_id = $_GET['customer_id'];
			}

			if(empty($_GET['payment_id']))
			{
				$header_id = 'NULL';
			}
			else
			{
				$header_id = $_GET['payment_id'];
			}

			if(empty($_GET['payment_method']))
			{
				$payment_method = 'NULL';
			}
			else
			{
				$payment_method = $_GET['payment_method'];
			}

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			$invoice_source = $_GET['invoice_source'];

			if($invoice_source == "PARTY-ORDERS")
			{
				#$partyOrderCus = "and header_tbl.customer_id = coalesce($customer_id,header_tbl.customer_id)";
				$partyOrderCus = "and customer.customer_name like '%".$_GET['customer_name']."%' ";
				$onlieOrderCus = "";
			}
			else if($invoice_source == "ONLINE-ORDERS")
			{
				$partyOrderCus = "";
				#$onlieOrderCus = "and header_tbl.consumer_id = coalesce($customer_id,header_tbl.consumer_id)";
				$onlieOrderCus = "and consumer.customer_name like '%".$_GET['customer_name']."%' ";
			}
			else
			{
				$onlieOrderCus = $partyOrderCus = "and (customer.customer_name like '%".$_GET['customer_name']."%') or (consumer.customer_name like '%".$_GET['customer_name']."%')";
			}

			$query = "select 
			header_tbl.*,
			ltv.list_value as invoice_source_name,
			expense_payment_type.payment_type,
			customer.customer_name,
			consumer.customer_name as con_customer_name,
			sum(line_tbl.payment_amount) as amount
			
			from inv_invoice_payment_header as header_tbl
			left join inv_invoice_payment_line as line_tbl on line_tbl.header_id=header_tbl.header_id
			left join sm_list_type_values as ltv on ltv.list_code = header_tbl.invoice_source
			left join expense_payment_type on expense_payment_type.payment_type_id = header_tbl.payment_method
			left join cus_customers as customer on customer.customer_id = header_tbl.customer_id

			left join cus_consumers as consumer on consumer.customer_id = header_tbl.consumer_id

			where 1=1
			and header_tbl.invoice_source = coalesce(if('".$invoice_source."' = '',NULL,'".$invoice_source."'),header_tbl.invoice_source)
			
			$partyOrderCus
			$onlieOrderCus

			and	header_tbl.header_id = coalesce($header_id,header_tbl.header_id)
			and	header_tbl.payment_method = coalesce($payment_method,header_tbl.payment_method)
			and ( 
				date_format(header_tbl.payment_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.payment_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.payment_date, '%Y-%m-%d'))
			)
			group by header_tbl.header_id
			order by header_tbl.header_id desc $limit";
			
			$result = $this->db->query($query)->result_array();

			return $result;
		}
		else
		{
			return array();
		}
	}
	
	function getManageSupplierPayment($offset="",$record="",$countType="")
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

			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";

			$query = "select 
			inv_invoice_payment_header.*,
			expense_payment_type.payment_type,
			users.first_name as supplier_name,
			acc_bank_details.bank_name,
			acc_bank_details.bank_account_number
			
			from inv_invoice_payment_header
			
			left join expense_payment_type on 
				expense_payment_type.payment_type_id = inv_invoice_payment_header.payment_method

			left join users on 
				users.user_id = inv_invoice_payment_header.supplier_id
			
			left join acc_bank_details on 
				acc_bank_details.account_id = inv_invoice_payment_header.account_id

			where 1=1
				and inv_invoice_payment_header.open_balance_type = 1 
				and inv_invoice_payment_header.payment_type=2
				and (
					users.first_name like coalesce($keywords,users.first_name) or 
					inv_invoice_payment_header.receipt_number like coalesce($keywords,inv_invoice_payment_header.receipt_number)
				)
				and ( 
					date_format(inv_invoice_payment_header.receipt_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(inv_invoice_payment_header.receipt_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(inv_invoice_payment_header.receipt_date, '%Y-%m-%d'))
				)
				order by inv_invoice_payment_header.header_id desc $limit";

			/* 
			where $condition
				order by header_id desc
					limit ".$record." , ".$offset."
			"; */

			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}


	function getManageSupplierPaymentResult($offset="",$record="", $countType="")
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

			
			if(empty($_GET['supplier_id']))
			{
				$supplier_id = 'NULL';
			}
			else
			{
				$supplier_id = $_GET['supplier_id'];
			}

			if(empty($_GET['payment_id']))
			{
				$header_id = 'NULL';
			}
			else
			{
				$header_id = $_GET['payment_id'];
			}

			if(empty($_GET['po_header_id']))
			{
				$po_header_id = 'NULL';
			}
			else
			{
				$po_header_id = $_GET['po_header_id'];
			}
			if(empty($_GET['receipt_header_id']))
			{
				$receipt_header_id = 'NULL';
			}
			else
			{
				$receipt_header_id = $_GET['receipt_header_id'];
			}

			if(empty($_GET['payment_method']))
			{
				$payment_method = 'NULL';
			}
			else
			{
				$payment_method = $_GET['payment_method'];
			}

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			

			$query = "select 
			header_tbl.*,
			expense_payment_type.payment_type,
			po_headers.po_number,
			rcv_header.receipt_number,
			supplier.supplier_name,
			sum(line_tbl.payment_amount) as amount
			
			from inv_supplier_payment_header as header_tbl
			
			left join inv_supplier_payment_line as line_tbl on line_tbl.header_id=header_tbl.header_id


			left join expense_payment_type on 

				expense_payment_type.payment_type_id = header_tbl.payment_method

			left join sup_suppliers as supplier on supplier.supplier_id = header_tbl.supplier_id

			

			left join rcv_receipt_headers as rcv_header on rcv_header.receipt_header_id=line_tbl.receipt_id

			left join po_headers on po_headers.po_header_id=rcv_header.po_header_id
			
			where 1=1

			and	header_tbl.supplier_id = coalesce($supplier_id,header_tbl.supplier_id)

			
			and	po_headers.po_header_id = coalesce($po_header_id,po_headers.po_header_id)

			and	rcv_header.receipt_header_id = coalesce($receipt_header_id,rcv_header.receipt_header_id)

			and	header_tbl.header_id = coalesce($header_id,header_tbl.header_id)

			and	header_tbl.payment_method = coalesce($payment_method,header_tbl.payment_method)
			
			and ( 
				date_format(header_tbl.payment_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.payment_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.payment_date, '%Y-%m-%d'))
			)
			group by header_tbl.header_id
			order by header_tbl.header_id desc $limit";
			
			$result = $this->db->query($query)->result_array();

			return $result;
		}
		else
		{
			return array();
		}
	}
	

	function getAjaxCustomerPaymentList($customer_id='')
	{
		#(select round(sum(payment_amount),2) from inv_invoice_payment_line where invoice_id = inv_header_tbl.header_id) paid_amount
						
		$query="select 
					t.header_id,
					t.invoice_number,
					t.customer_id,
					t.date,
					t.payment_days,
					t.inv_total,
					coalesce(t.paid_amount,null,0) paid_amount,
					coalesce(t.inv_total,null,0) - coalesce(t.paid_amount,null,0) balance_amount
					from 
					(
						select
						inv_header_tbl.header_id,
						inv_header_tbl.invoice_number,
						inv_header_tbl.customer_id,
						date_format(inv_header_tbl.invoice_date,'%d-%M-%Y') as date,
						payment_terms.payment_term as payment_days,
						sum(inv_line_tbl.total) as inv_total,
						
						
						(select round(sum(payment_line_tbl.payment_amount),2) from inv_invoice_payment_line as payment_line_tbl 
						left join inv_invoice_payment_header as payment_header_tbl on payment_header_tbl.header_id = payment_line_tbl.header_id
						where payment_line_tbl.invoice_id = inv_header_tbl.header_id and payment_header_tbl.invoice_source='PARTY-ORDERS') as paid_amount
				
						
						from inv_invoice_lines as inv_line_tbl
						left join inv_invoice_headers as inv_header_tbl 
							ON inv_header_tbl.header_id = inv_line_tbl.header_id

						left join payment_terms ON 
							payment_terms.payment_term_id = inv_header_tbl.payment_term_id

						WHERE
						1=1
						and (inv_header_tbl.invoice_status = 'DRAFT' || inv_header_tbl.invoice_status = 'PENDING') 
						and inv_header_tbl.customer_id='".$customer_id."'
						group by inv_line_tbl.header_id
					) t
					where 
					coalesce(t.inv_total,null,0) - coalesce(t.paid_amount,null,0) > 0";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getAjaxSupplierPaymentList($supplier_id='') 
	{
		$query = "select
		rh.*,
		rh.receipt_header_id as header_id,
		ph.po_number,
		COALESCE(SUM(rl.received_qty * pl.base_price), 0) AS inv_total,
		COALESCE(SUM(rl.received_qty * pl.base_price) - (
		SELECT COALESCE(SUM(payment_amount), 0) 
		FROM inv_supplier_payment_line 
		WHERE receipt_id = rh.receipt_header_id
		), 0) AS balance_amount
		FROM
		rcv_receipt_headers as rh
		JOIN po_headers as ph ON rh.po_header_id = ph.po_header_id
		LEFT JOIN rcv_receipt_lines as rl ON rh.receipt_header_id = rl.receipt_header_id
		LEFT JOIN po_lines as pl ON rl.po_line_id = pl.po_line_id
		WHERE 1=1
		and (rh.receipt_status = 'DRAFT' || rh.receipt_status = 'PENDING') 

		and ph.supplier_id = '".$supplier_id."'
		GROUP BY rh.receipt_header_id, ph.po_number";
	
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getAjaxPaymentAll($payment_number='')
	{
		$query="select header_tbl.header_id,header_tbl.payment_number from inv_invoice_payment_header as header_tbl
				where 1=1 
				and header_tbl.payment_number LIKE '%" . $payment_number . "%'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getAjaxSupplierPaymentAll($payment_number='')
	{
		$query="select header_tbl.header_id,header_tbl.payment_number from inv_supplier_payment_header as header_tbl
				where 1=1 
				and header_tbl.payment_number LIKE '%" . $payment_number . "%'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function loadAgainstOrderCus()
	{
		$query="select 
			consumer.customer_id, 
			consumer.customer_number, 
			consumer.customer_name, 
			consumer.mobile_number 
			from per_user 
			left join ord_order_headers as ord_header_tbl on ord_header_tbl.customer_id = per_user.user_id 
			left join cus_consumers as consumer on consumer.customer_id = per_user.reference_id 
			where 
			1=1 
			and consumer.active_flag='Y' 
			and ord_header_tbl.order_source IN('POS','DINE_IN','HOME_DELIVERY') 
			group by ord_header_tbl.customer_id
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getAjaxCustomerPaymentDueList($customer_id='',$from_date="",$to_date="")
	{
		$fromDate = !empty($from_date) ? date_format(date_create($from_date),"Y-m-d") : NULL;
		$toDate = !empty($to_date) ? date_format(date_create($to_date),"Y-m-d") : NULL;

		$query = "
			select 
			s.header_id,
			s.order_number as invoice_number,
			s.date,
			s.payment_due,
			coalesce(s.offer_percentage,0) as offer_percentage,
			s.tax_percentage,
			( s.linetotal +  s.tax_value)  total_order_amount, 
			SUM(s.offer_amount) offer_amount, 
			SUM(s.tax_amount) tax_amount, 
             s.linetotal as linetotal,
            s.tax_value as tax_value,
            
			COALESCE(round(SUM(s.total_order_amount) - SUM(s.offer_amount) +  SUM(s.tax_amount)),0) AS payment_amount_old,
            
			round( s.linetotal +  s.tax_value) as payment_amount,
			COALESCE(s.paid_amount, 0) as paid_amount,
			round( s.linetotal +  s.tax_value) - COALESCE(s.paid_amount, 0) balance_amount
			from
			(
				select 
				ord_header_tbl.header_id,
				ord_line_tbl.header_id as line_header_id,
				ord_header_tbl.order_number,
				ord_header_tbl.customer_id,
				date_format(ord_header_tbl.ordered_date,'%d-%M-%Y') as date,
				ord_header_tbl.payment_due,
				ord_line_tbl.tax_percentage,
				ord_line_tbl.offer_percentage,
				
				(
					SELECT 
					round( sum(olv1.quantity * olv1.price))
					FROM ord_order_headers ohv1, ord_order_lines AS olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = ord_line_tbl.line_id
				) 
				AS total_order_amount, 
				
				(
					SELECT 
					round( sum((coalesce(olv1.offer_percentage,0) / 100) * (olv1.quantity * olv1.price)),2)
					FROM ord_order_headers ohv1, ord_order_lines AS olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = ord_line_tbl.line_id
				) 
				AS offer_amount,  
                
				(
					SELECT 
					round( sum((coalesce(olv1.tax_percentage,0) / 100) * (olv1.quantity * olv1.price)),2)
					FROM ord_order_headers ohv1, ord_order_lines AS olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = ord_line_tbl.line_id
				) 
				AS tax_amount, 
                
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
                
				
				(
					( 
						SELECT 
						round( sum(olv1.quantity * olv1.price))
						FROM ord_order_headers ohv1, ord_order_lines AS olv1
						WHERE 
						ohv1.header_id = olv1.header_id 
						AND olv1.cancel_status = 'N' 
						AND olv1.line_id = ord_line_tbl.line_id
					)
					
					-
					
					( 
						SELECT 
						round( sum((coalesce(olv1.offer_percentage,0) / 100) * (olv1.quantity * olv1.price)),2)
						FROM ord_order_headers ohv1, ord_order_lines AS olv1
						WHERE 
						ohv1.header_id = olv1.header_id 
						AND olv1.cancel_status = 'N' 
						AND olv1.line_id = ord_line_tbl.line_id
					)				
				)
				as payment_amount,
				
				(select round(sum(payment_line_tbl.payment_amount),2) from inv_invoice_payment_line as payment_line_tbl 
				left join inv_invoice_payment_header as payment_header_tbl on payment_header_tbl.header_id = payment_line_tbl.header_id
				where payment_line_tbl.invoice_id = ord_header_tbl.header_id and payment_header_tbl.invoice_source='ONLINE-ORDERS') as paid_amount
				
				from ord_order_lines as ord_line_tbl
				left join ord_order_headers as ord_header_tbl ON ord_header_tbl.header_id = ord_line_tbl.header_id
				left join per_user as user ON user.user_id = ord_header_tbl.customer_id
				WHERE
				1=1
				and ord_header_tbl.order_source IN('POS','DINE_IN','HOME_DELIVERY')
				and ord_header_tbl.payment_due = 'Unpaid'
				and ord_header_tbl.cancel_status = 'N'
				and user.reference_id='".$customer_id."'
				and (DATE_FORMAT(ord_header_tbl.ordered_date, '%Y-%m-%d') BETWEEN coalesce('".$fromDate."',ord_header_tbl.ordered_date) and coalesce('".$toDate."',ord_header_tbl.ordered_date))
						
			) s  
			where 
			1=1
			and (round( s.linetotal +  s.tax_value) - COALESCE(s.paid_amount, 0)) > 0
			group by s.header_id
			order by s.header_id desc
		";	

		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	
}
