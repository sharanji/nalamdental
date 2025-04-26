<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Invoice_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	#Manage Purchase
	function getmanageinvoice($offset="",$record="",$countType="")
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
			
			$invoice_type = $_GET['invoice_type'];
			$invoice_id = $header_id= !empty($_GET['invoice_id']) ? $_GET['invoice_id'] : 'NULL';
			$customer_id = !empty($_GET['customer_id']) ? $_GET['customer_id'] : 'NULL';

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$headerQuery = "select 
				header_tbl.*,
				sm_list_type_values.list_value as invoiceType,
				sum(line_tbl.total) amount,
				cus_customers.customer_name,
				payment_terms.payment_term

		 		from inv_invoice_headers as header_tbl

				left join inv_invoice_lines as line_tbl on 
					line_tbl.header_id = header_tbl.header_id

				left join cus_customers on 
					cus_customers.customer_id = header_tbl.customer_id

				left join sm_list_type_values on 
					sm_list_type_values.list_code = header_tbl.invoice_type
					
				left join payment_terms  on 
					payment_terms.payment_term_id = header_tbl.payment_term_id

				where 1=1
				and header_tbl.invoice_type = coalesce(if('".$invoice_type."' = '',NULL,'".$invoice_type."'),header_tbl.invoice_type)
				and header_tbl.header_id = coalesce($header_id,header_tbl.header_id)
				and header_tbl.customer_id = coalesce($customer_id,header_tbl.customer_id)
				and ( 
					date_format(header_tbl.invoice_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.invoice_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.invoice_date, '%Y-%m-%d'))
				)
				group by header_tbl.header_id
				order by header_tbl.header_id desc $limit";
			
			$result["header_data"] = $this->db->query($headerQuery)->result_array();

			$lineQuery = "select 
				header_tbl.*,
				line_tbl.*,
				sm_list_type_values.list_value as invoiceType,
				cus_customers.customer_name,
				payment_terms.payment_term,
				uom.uom_code
		 		from inv_invoice_headers as header_tbl

				left join inv_invoice_lines as line_tbl on 
					line_tbl.header_id = header_tbl.header_id

				left join cus_customers on 
					cus_customers.customer_id = header_tbl.customer_id

				left join sm_list_type_values on 
					sm_list_type_values.list_code = header_tbl.invoice_type

				left join payment_terms  on 
					payment_terms.payment_term_id = header_tbl.payment_term_id

				left join uom on 
					uom.uom_id = line_tbl.uom

				

				where 1=1
				and header_tbl.invoice_type = coalesce(if('".$invoice_type."' = '',NULL,'".$invoice_type."'),header_tbl.invoice_type)
				and header_tbl.header_id = coalesce($header_id,header_tbl.header_id)
				and header_tbl.customer_id = coalesce($customer_id,header_tbl.customer_id)
				and ( 
					date_format(header_tbl.invoice_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.invoice_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.invoice_date, '%Y-%m-%d'))
				)
				";
			
			$result["line_data"] = $this->db->query($lineQuery)->result_array();	
		}
		else
		{
			$result["header_data"] = array();
			$result["line_data"] = array();
		}
		return $result;
	}
	
	#Discount
	public function getDiscount()
	{
		return $this->db->get_where('discount',array('active_flag'=>'Y'))->result();
	}
	
	#Tax
	public function getTax()
	{
		return $this->db->get_where('gen_tax',array('active_flag'=>'Y'))->result();
	}

	#getViewData
	public function getViewData($id='')
	{
		$headerQry ="select 
			header_tbl.*,
			sm_list_type_values.list_value as invoiceType,
			cus_customers.customer_name,
			cus_customers.mobile_number,
			cus_customers.email_address,
			cus_customers.address1,
			cus_customers.address2,
			cus_customers.address3,
			cus_customers.postal_code,
			cus_customers.contact_person,
			cus_customers.alt_mobile_number,
			cus_customers.gst_number,

			
			geo_countries.country_id,
			geo_countries.country_name,
			geo_states.state_name,
			geo_states.state_number,
			geo_cities.city_name,

			sum(inv_invoice_lines.line_value) order_amount,
			sum(inv_invoice_lines.total_tax) total_tax,
			sum(inv_invoice_lines.total) total,
			sum(inv_invoice_lines.total_discount) totalDiscount,
			payment_terms.payment_term

			from inv_invoice_headers as header_tbl
			
			left join inv_invoice_lines on 
				inv_invoice_lines.header_id = header_tbl.header_id

			left join cus_customers on 
					cus_customers.customer_id = header_tbl.customer_id
			
			left join payment_terms on 
				payment_terms.payment_term_id = header_tbl.payment_term_id

			left join sm_list_type_values on 
				sm_list_type_values.list_code = header_tbl.invoice_type


			left join geo_countries on 
				geo_countries.country_id = cus_customers.country_id

			left join geo_states on 
				geo_states.state_id = cus_customers.state_id
			
			left join geo_cities on 
				geo_cities.city_id = cus_customers.city_id

			where header_tbl.header_id='".$id."' 
			
			group by header_tbl.header_id" ;

		$result['edit_data'] = $this->db->query($headerQry)->result_array();

		$lineQry = "select 
			
			line_tbl.*,
			sm_list_type_values.list_value as invoiceType,
			cus_customers.customer_name,
			payment_terms.payment_term,
			uom.uom_code,

			geo_countries.country_id,
			geo_countries.country_name,
			geo_states.state_name,
			geo_states.state_number,
			geo_cities.city_name

			from inv_invoice_headers as header_tbl

			left join inv_invoice_lines as line_tbl on 
				line_tbl.header_id = header_tbl.header_id

			left join cus_customers on 
				cus_customers.customer_id = header_tbl.customer_id

			left join sm_list_type_values on 
				sm_list_type_values.list_code = header_tbl.invoice_type

			left join payment_terms  on 
				payment_terms.payment_term_id = header_tbl.payment_term_id

			left join uom on 
				uom.uom_id = line_tbl.uom

			left join geo_countries on 
				geo_countries.country_id = cus_customers.country_id

			left join geo_states on 
				geo_states.state_id = cus_customers.state_id
			
			left join geo_cities on 
				geo_cities.city_id = cus_customers.city_id

			where 1=1
			and line_tbl.header_id='".$id."'
			";

			

		$result['line_data'] = $this->db->query($lineQry)->result_array();

		return $result;
	}

	public function checkLineExist($header_id = "", $line_id = "")
	{
		$headerQry ="select 
			line_tbl.line_id
			from inv_invoice_lines as line_tbl
			where line_tbl.line_id='".$line_id."' 
			and line_tbl.header_id='".$header_id."' " ;
		$result = $this->db->query($headerQry)->result_array();
		return $result;
	}

	function getAjaxInvoice($invoice_type=NULL)
	{
		$query = "select header_id,invoice_number from inv_invoice_headers as header_tbl 
		where 1=1
		and header_tbl.invoice_type='".$invoice_type."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	public function getAjaxInvoiceCustomers() 
	{
		$query = "select customer_id,customer_name,mobile_number from cus_customers where active_flag='Y'";
		$result = $this->db->query($query)->result_array();

		return $result;
	}
	
	public function getPendingInvoice(){
		$query="select header_tbl.customer_id,customer.customer_name,customer.mobile_number from inv_invoice_headers as header_tbl
				left join cus_customers as customer on customer.customer_id=header_tbl.customer_id
				where 1=1
				and invoice_status='PENDING'
				and active_flag='Y'";
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	function getAjaxInvoiceAll($invoice_number='')
	{
		$query = "select header_id,invoice_number from inv_invoice_headers as header_tbl 
					where 1=1
					and header_tbl.invoice_number LIKE '%" . $invoice_number . "%'";
					$result = $this->db->query($query)->result_array();
					return $result;
		
	}
}
