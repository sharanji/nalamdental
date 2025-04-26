<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stock_adjustment_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getStockAdjustment($offset="",$record="",$countType="")
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

			$header_id = !empty($_GET['adj_number_id']) ? $_GET['adj_number_id'] : 'NULL';
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$headerQry = "select 
				header_tbl.*,
				organizations.organization_name
				from inv_adjustment_header as header_tbl

				left join inv_adjustment_line as line_tbl on line_tbl.header_id = header_tbl.header_id

				left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id

				where 1=1
				and header_tbl.header_id = coalesce($header_id,header_tbl.header_id)
				and ( 
					date_format(header_tbl.adj_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.adj_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.adj_date, '%Y-%m-%d'))
				)
				group by header_tbl.header_id
				order by header_tbl.header_id desc $limit" ;
			
			$result["header_data"] = $this->db->query($headerQry)->result_array();

			$lineQry = "select 
				header_tbl.*,
				line_tbl.*,
				items.item_name,
				items.uom,
				organizations.organization_name,
				sub_inventory.inventory_code,
				locators.locator_no
				
				from inv_adjustment_header as header_tbl
				left join inv_adjustment_line as line_tbl on line_tbl.header_id = header_tbl.header_id
				left join inv_sys_items as items on items.item_id = line_tbl.item_id
				left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = line_tbl.sub_inventory_id
				left join inv_item_locators as locators on locators.locator_id = line_tbl.locator_id
				left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id
				where 1=1
				and header_tbl.header_id = coalesce($header_id,header_tbl.header_id)
				and ( 
					date_format(header_tbl.adj_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.adj_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.adj_date, '%Y-%m-%d'))
				)
				order by header_tbl.header_id desc $limit" ;
			
			$result["line_data"] = $this->db->query($lineQry)->result_array();

		}
		else
		{
			$result["header_data"] = array();
			$result["line_data"] = array();
			
		}
		return $result;
	}

	#getViewData
	// public function getViewData($id='')
	// {
	// 	$headerQry ="select header_tbl.*
	// 		from inv_wip_headers as header_tbl

	// 	where header_tbl.wip_header_id='".$id."' " ;

	// 	$result['edit_data'] = $this->db->query($headerQry)->result_array();

	// 	$lineQry ="select 
	// 		line_tbl.*,
	// 		item.item_name,
	// 		item.item_description,
			
	// 		organization.organization_name,
	// 		sub_inventory.inventory_code,
	// 		sub_inventory.inventory_name,
	// 		item_locators.locator_no,
	// 		item_locators.locator_name,
	// 		uom.uom_code,

	// 		(select sum(transaction.transaction_qty)  from inv_transactions as transaction
	// 		where transaction.item_id = line_tbl.item_id
	// 		and transaction.organization_id = line_tbl.organization_id
	// 		and transaction.sub_inventory_id = line_tbl.sub_inventory_id
	// 		and transaction.locator_id = line_tbl.locator_id
	// 		and transaction.lot_number = line_tbl.lot_number
	// 		and transaction.serial_number = line_tbl.serial_number
	// 		and coalesce(transaction.order_line_id, 'NULL') != line_tbl.wip_line_id) as trans_qty
			
	// 		from inv_wip_lines as line_tbl

	// 		left join inv_sys_items as item on item.item_id = line_tbl.item_id
	// 		left join uom on uom.uom_id = item.uom

	// 		left join org_organizations as organization on organization.organization_id = line_tbl.organization_id
	// 		left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = line_tbl.sub_inventory_id
	// 		left join inv_item_locators as item_locators on item_locators.locator_id = line_tbl.locator_id
			
	// 		where line_tbl.wip_header_id = '".$id."'";
			
	// 	$result['line_data'] = $this->db->query($lineQry)->result_array();

	// 	return $result;
	// }
	public function getViewData($id='')
	{
		$headerQry ="select 
						header_tbl.*
						from inv_adjustment_header as header_tbl

						where 1=1
						and header_id = '".$id."' " ;

		$result['headerData'] = $this->db->query($headerQry)->result_array();

		$lineQry ="select 
					line_tbl.*,
					items.item_name,
					uom.uom_code,
					organizations.organization_name,
					sub_inventory.inventory_code,
					locators.locator_no
					
					from inv_adjustment_line as line_tbl

					left join inv_sys_items as items on items.item_id = line_tbl.item_id
					left join inv_adjustment_header as header_tbl on header_tbl.header_id = line_tbl.header_id

					left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = line_tbl.sub_inventory_id
					left join inv_item_locators as locators on locators.locator_id = line_tbl.locator_id

					left join org_organizations as organizations on organizations.organization_id = line_tbl.organization_id

					left join uom on uom.uom_id=line_tbl.uom_id

					where 1=1
					and line_tbl.header_id = '".$id."' ";
			
		$result['lineData'] = $this->db->query($lineQry)->result_array();

		return $result;
	}

	
	function getAjaxItemList($item_name='') 
	{
		
		$query = "SELECT 
		items.item_id, 
		items.item_name, 
		items.item_description, 
		items.uom
	FROM 
		inv_sys_items AS items
	
	WHERE 
		1 = 1
		AND items.item_name LIKE '%" . $item_name . "%'  
		AND items.item_type_id = 31  
		AND items.active_flag = '" . $this->active_flag . "'";
	
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxUom($uom_id='') 
	{
		
		$query = "SELECT uom.uom_id, uom.uom_code FROM uom
					WHERE 1=1
					and uom.uom_id = '" . $uom_id . "' 
					and uom.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxTransQty($item_id='') 
	{
		
		$query = "SELECT transactions.transaction_id, COALESCE(SUM(transactions.transaction_qty), 0) AS transaction_qty FROM inv_transactions as transactions
					WHERE 1=1
					and transactions.item_id = '" . $item_id . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxOrganization() 
	{
		$query = "select organization_id,organization_name from org_organizations 
					where 1=1
					and org_organizations.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxSubInventory($organization_id='') 
	{
		$query = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
					where 1=1
					and organization_id ='".$organization_id."'
					and inv_item_sub_inventory.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxSubInventoryLocators($inventory_id='') 
	{
		$query = "select locator_id,locator_no,locator_name from inv_item_locators
					where 1=1
					and inv_item_locators.inventory_id ='".$inventory_id."'
					and inv_item_locators.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getAjaxAdjustNumberAll($adj_number='')
	{
		$query="select header_tbl.header_id,header_tbl.adj_number from inv_adjustment_header as header_tbl
				where 1=1 
				and header_tbl.adj_number LIKE '%" . $adj_number . "%'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
