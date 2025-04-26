<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function lov($list_name="")
	{
		/* $listTypeValuesQry = "select 
		sm_list_type_values.list_type_value_id,
		sm_list_type_values.list_code,
		sm_list_type_values.list_value	
		from sm_list_type_values

		left join sm_list_types on 
		sm_list_types.list_type_id = sm_list_type_values.list_type_id
		where 
		sm_list_type_values.active_flag = 'Y' and 
		sm_list_types.list_name = '".$list_name."' 
		order by sm_list_type_values.list_value asc"; */ 

		$listTypeValuesQry ="select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
		left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
		where 

		sm_list_types.active_flag='Y' and 
		coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
		coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
		sm_list_types.deleted_flag='N' and


		sm_list_type_values.active_flag='Y' and 
		coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
		coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
		sm_list_type_values.deleted_flag='N' and 

		sm_list_types.list_name = '".$list_name."' 
		order by sm_list_type_values.order_sequence asc";

		$result = $this->db->query($listTypeValuesQry)->result_array();
		return $result;
	}


	function documentNumber($list_code="")
	{
		$query = "select doc_num_id,prefix_name,suffix_name,next_number 
			from doc_document_numbering as dm
			left join sm_list_type_values ltv on 
			ltv.list_type_value_id = dm.doc_type
			where 
			ltv.list_code = '".$list_code."' 
			and dm.active_flag = 'Y'
			and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
			and coalesce(dm.to_date,CURDATE()) >= CURDATE()
			";
		$result=$this->db->query($query)->result_array();
		return $result;
	}

	public function getPaymentTerms() 
	{
		$query = "select payment_term_id,payment_term from payment_terms 
			where 1=1 and active_flag='Y' order by payment_terms.payment_term asc";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	

	function dashboardCounts()
	{
			
		$result["pos_count"] = $this->db->query("select count(header_id) as pos_count from ord_order_headers where 1=1 and order_source='POS'")->result_array();

		$result["home_delivery_count"] = $this->db->query("select count(header_id) as home_delivery_count from ord_order_headers where 1=1 and order_source='HOME_DELIVERY'")->result_array();
		
		$result["dine_in_count"] = $this->db->query("select count(header_id) as dine_in_count from ord_order_headers where 1=1 and order_source='DINE_IN'")->result_array();
		
		$result["items_count"] = $this->db->query("select count(item_id) as items_count from inv_sys_items")->result_array();
		$result["purchase_order_Count"] = $this->db->query("select count(po_header_id) as purchase_order_Count from po_headers")->result_array();
		$result["purchase_receipt_Count"] = $this->db->query("select count(receipt_header_id) as purchase_receipt_Count from rcv_receipt_headers")->result_array();
		$result["sales_order_Count"] = $this->db->query("select count(sales_header_id) as sales_order_Count from ord_sale_headers")->result_array();
		$result["invoice_order_Count"] = $this->db->query("select count(header_id) as invoice_order_Count from inv_invoice_headers")->result_array();
		$result["consumer_order_Count"] = $this->db->query("select count(customer_id) as consumer_order_Count from cus_consumers")->result_array();
		
		return $result;
		

	}

	function getDesignation()
	{
		$query = "select 
		designation.designation_id, 
		designation.designation_name 
		from emp_designations as designation
		where 1=1
		and designation.designation_status=1";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
