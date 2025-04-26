<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sales_order_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageSalesOrder($offset="",$record="",$countType="")
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

			$so_status = $_GET['so_status'];
			$customer_id = !empty($_GET['customer_id']) ? $_GET['customer_id'] : 'NULL';
			$branch_id = !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			$order_number = "concat('%','".serchFilter($_GET['order_number'])."','%')";
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$query = "select 
				header_tbl.*,
				cus_customers.customer_name,
				geo_currencies.currency,
				sum(line_tbl.total) amount,
				organization.organization_name,
				branch.branch_name,
				payment_terms.payment_term
		 		from ord_sale_headers as header_tbl

				left join cus_customers on cus_customers.customer_id = header_tbl.customer_id

				left join geo_currencies on geo_currencies.currency_id = header_tbl.so_currency
				
				left join ord_sale_lines as line_tbl on line_tbl.sales_header_id = header_tbl.sales_header_id

				left join org_organizations as organization on organization.organization_id = header_tbl.organization_id

				left join branch on branch.branch_id = header_tbl.branch_id

				left join payment_terms on payment_terms.payment_term_id = header_tbl.payment_term_id
				

				where 1=1
				
				and header_tbl.so_status = coalesce(if('".$so_status."' = '',NULL,'".$so_status."'),header_tbl.so_status)
				and header_tbl.customer_id = coalesce($customer_id,header_tbl.customer_id)
				and branch.branch_id = coalesce($branch_id,branch.branch_id)
				and header_tbl.order_number like coalesce($order_number,header_tbl.order_number)
				and ( 
					date_format(header_tbl.order_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.order_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.order_date, '%Y-%m-%d'))
				)
				group by header_tbl.sales_header_id
					order by header_tbl.sales_header_id desc $limit" ;
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	#getViewData
	public function getViewData($id='')
	{
		$headerQry ="select 
			ord_sale_headers.*,
			sum(ord_sale_lines.line_value) order_amount,
			sum(ord_sale_lines.total_tax) total_tax,
			sum(ord_sale_lines.total) total,
			cus_customers.customer_id,
			cus_customers.customer_name,
			cus_customers.contact_person,
			cus_customers.mobile_number,
			organization.organization_name,
			branch.branch_name,
			per_people_all.first_name as Createdby,
			per_user.user_id as Createdbyid,
			payment_terms.payment_term,
			geo_currencies.currency

			from ord_sale_headers

			left join ord_sale_lines on 
			ord_sale_lines.sales_header_id = ord_sale_headers.sales_header_id

			left join cus_customers on 
			cus_customers.customer_id = ord_sale_headers.customer_id

			left join org_organizations as organization on organization.organization_id = ord_sale_headers.organization_id

			left join branch on branch.branch_id = ord_sale_headers.branch_id

			left join payment_terms on payment_terms.payment_term_id = ord_sale_headers.payment_term_id

			left join geo_currencies on geo_currencies.currency_id = ord_sale_headers.so_currency

			left join per_user on 
				per_user.user_id = ord_sale_headers.created_by

			left join per_people_all on 
				per_people_all.person_id = per_user.person_id

			

			where ord_sale_headers.sales_header_id='".$id."' 
			
			group by ord_sale_headers.sales_header_id" ;

		$result['edit_data'] = $this->db->query($headerQry)->result_array();

		$lineQry ="select 
			line_tbl.*,
			item.item_name,
			item.item_description,
			category.category_name,
			organization.organization_name,
			sub_inventory.inventory_code,
			sub_inventory.inventory_name,
			item_locators.locator_no,
			item_locators.locator_name,
			uom.uom_code,
			
			(select sum(transaction.transaction_qty)  from inv_transactions as transaction
			where transaction.item_id = line_tbl.item_id
			and transaction.organization_id = line_tbl.organization_id
			and transaction.sub_inventory_id = line_tbl.sub_inventory_id
			and transaction.locator_id = line_tbl.locator_id
			and transaction.lot_number = line_tbl.lot_number
			and transaction.serial_number = line_tbl.serial_number
			and coalesce(transaction.order_line_id, 'NULL') != line_tbl.sales_line_id) as trans_qty  
			
			from ord_sale_lines as line_tbl 

			left join inv_sys_items as item on item.item_id = line_tbl.item_id
			left join uom as uom on uom.uom_id = line_tbl.uom
			
			left join inv_categories as category on category.category_id = line_tbl.category_id
			left join org_organizations as organization on organization.organization_id = line_tbl.organization_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = line_tbl.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = line_tbl.locator_id
		
			where line_tbl.sales_header_id = '".$id."'";
			
		$result['line_data'] = $this->db->query($lineQry)->result_array();

		return $result;
	}
}
