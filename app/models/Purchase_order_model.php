<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchase_order_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	#Manage Purchase
	function getManagePurchase($offset="",$record="",$countType="")
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

			$po_status = $_GET['po_status'];
			$organization_id = !empty($_GET['organization_id']) ? $_GET['organization_id'] : 'NULL';
			$branch_id = !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			$supplier_id = !empty($_GET['supplier_id']) ? $_GET['supplier_id'] : 'NULL';
			$supplier_site_id = !empty($_GET['supplier_site_id']) ? $_GET['supplier_site_id'] : 'NULL'; 
			
			$po_number = "concat('%','".serchFilter($_GET['po_number'])."','%')";
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$query = "select 
				po_headers.*,
				sup_suppliers.supplier_name,
				geo_currencies.currency,
				sum(po_lines.total) amount,
				sup_supplier_sites.site_name,
				organization.organization_name,
				branch.branch_name
		 		from po_headers

				left join sup_suppliers on sup_suppliers.supplier_id = po_headers.supplier_id

				left join sup_supplier_sites on sup_supplier_sites.supplier_site_id = po_headers.supplier_site_id

				left join geo_currencies on geo_currencies.currency_id = po_headers.po_currency
				
				left join po_lines on po_lines.po_header_id = po_headers.po_header_id

				left join org_organizations as organization on organization.organization_id = po_headers.organization_id

				left join branch on branch.branch_id = po_headers.branch_id

				where 1=1
				
				and po_headers.po_status = coalesce(if('".$po_status."' = '',NULL,'".$po_status."'),po_headers.po_status)
				and organization.organization_id = coalesce($organization_id,organization.organization_id)
				and branch.branch_id = coalesce($branch_id,branch.branch_id)
				and po_headers.supplier_id = coalesce($supplier_id,po_headers.supplier_id)
				and po_headers.supplier_site_id = coalesce($supplier_site_id,po_headers.supplier_site_id)
				and po_headers.po_number like coalesce($po_number,po_headers.po_number)
				and ( 
					date_format(po_headers.po_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(po_headers.po_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(po_headers.po_date, '%Y-%m-%d'))
				)
				group by po_headers.po_header_id
					order by po_headers.po_header_id desc $limit" ;
			
			$result = $this->db->query($query)->result_array();
			return $result;
			//and po_headers.po_status = coalesce('".$po_status."',po_headers.po_status)
		}
		else
		{
			return array();
		}
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
			po_headers.*,
			sup_suppliers.supplier_name,
			sup_supplier_sites.site_name,
			geo_currencies.currency,

			sum(po_lines.line_value) order_amount,
			sum(po_lines.total_tax) total_tax,
			sum(po_lines.total) total,
			sup_supplier_sites.site_name,
			sup_supplier_sites.address1,
			sup_supplier_sites.address2,
			sup_supplier_sites.address3,
			sup_supplier_sites.gst_number,
			sup_supplier_sites.cin_number,
			sup_supplier_sites.contact_person,
			sup_supplier_sites.email_address,
			sup_supplier_sites.mobile_number,
			geo_countries.country_name,
			geo_cities.city_name,
			geo_states.state_name
			from po_headers

			left join sup_suppliers on 
			sup_suppliers.supplier_id = po_headers.supplier_id

			left join sup_supplier_sites on 
			sup_supplier_sites.supplier_site_id = po_headers.supplier_site_id

			left join geo_countries on 
				geo_countries.country_id = sup_supplier_sites.country_id

			left join geo_cities on 
				geo_cities.city_id = sup_supplier_sites.city_id

			left join geo_states on 
				geo_states.state_id = sup_supplier_sites.state_id

			left join geo_currencies on 
				geo_currencies.currency_id = po_headers.po_currency
			
			left join po_lines on 
				po_lines.po_header_id = po_headers.po_header_id

			where po_headers.po_header_id='".$id."' 
			
			group by po_headers.po_header_id" ;

		$result['edit_data'] = $this->db->query($headerQry)->result_array();

		$lineQry ="select 
			po_lines.*,
			items.item_name, 
			items.item_description, 
			categories.category_name,
			uom.uom_code
			
			from po_lines 
			
			left join inv_sys_items items on 
				items.item_id = po_lines.item_id

			left join inv_categories categories on 
				categories.category_id = po_lines.category_id

			left join uom on uom.uom_id=po_lines.uom

			where po_lines.po_header_id='".$id."' ";
		$result['line_data'] = $this->db->query($lineQry)->result_array();

		return $result;
	}

	function getAjaxPoAll($po_number='')
	{
		$query="select header_tbl.po_header_id,header_tbl.po_number from po_headers as header_tbl
				where 1=1 
				and header_tbl.po_number LIKE '%" . $po_number . "%'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxReceiptAll($receipt_number='')
	{
		$query="select header_tbl.receipt_header_id,header_tbl.receipt_number from rcv_receipt_headers as header_tbl
				where 1=1 
				and header_tbl.receipt_number LIKE '%" . $receipt_number . "%'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getPODetails($header_id='')
	{
		$query="
		select 
		header_tbl.po_number,
		org_organizations.organization_name,
		branch.branch_name,
		header_tbl.po_date,
		sum(po_lines.total) as poAmount

		from po_headers as header_tbl
		left join po_lines on po_lines.po_header_id = header_tbl.po_header_id
		left join org_organizations on org_organizations.organization_id = header_tbl.organization_id
		left join branch on branch.branch_id = header_tbl.branch_id
				where 1=1 
				and header_tbl.po_header_id ='".$header_id."' 
		group by po_lines.po_header_id";
		$result = $this->db->query($query)->result_array();
		return $result;
	}


	
}
