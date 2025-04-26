<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getProducts($offset="",$record="", $countType="")
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

			if(empty($_GET['item_id'])){
				$item_id = 'NULL';
			}else{
				$item_id = $_GET['item_id'];
			}

			if(empty($_GET['category_id'])){
				$category_id = 'NULL';
			}else{
				$category_id = $_GET['category_id'];
			}
			
			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$query = "select 
			items.item_id,
			items.item_code,
			items.item_name,
			items.item_description,
			uom.uom_code,
			items.short_code,
			items.item_cost,
			items.active_flag,
			inv_hsn_codes.hsn_code,
			inv_categories.category_name, 
			inv_categories.category_id 
			from inv_sys_items as items
					
			left join inv_categories on inv_categories.category_id = items.category_id
			left join inv_hsn_codes on inv_hsn_codes.hsn_code_id = items.hsn_code_id
			left join uom on uom.uom_id = items.uom
			where 1=1
			and items.item_id = coalesce($item_id,items.item_id)
			and items.category_id = coalesce($category_id,items.category_id)
			and items.active_flag = if('".$active_flag."' = 'All',items.active_flag,'".$active_flag."')
			order by items.item_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
	
	function getProductsPriceCount()
	{
		$condition = " 1=1";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								products.product_name like "%'.serchFilter($_GET['keywords']).'%" or 
								products.product_code like "%'.serchFilter($_GET['keywords']).'%" or
								inv_categories.category_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select 
			price_id
			from product_price

		left join products on 
			products.product_id = product_price.product_id

		left join inv_categories on 
		inv_categories.category_id = products.category_id

		where $condition";
		$result = $this->db->query($query)->result_array();

		return count($result);
	}
	
	function getProductsPrice($offset="",$record="")
	{
		$condition = " 1=1";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								products.product_name like "%'.serchFilter($_GET['keywords']).'%" or 
								products.product_code like "%'.serchFilter($_GET['keywords']).'%" or
								inv_categories.category_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select product_price.*,
		products.product_code,
		products.product_name,
		inv_categories.category_name

		from product_price

		left join products on 
			products.product_id = product_price.product_id

		left join inv_categories on 
		inv_categories.category_id = products.category_id

		where $condition
			limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}


	function getAssignProductLocatorCount()
	{
		$condition = " 1=1";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								warehouse.warehouse_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select 
			inv_assign_product_locator_header.header_id
		
		from inv_assign_product_locator_header

		left join warehouse on 
			warehouse.warehouse_id = inv_assign_product_locator_header.warehouse_id

		where $condition";

		$result = $this->db->query($query)->result_array();

		return count($result);
	}
	
	function getAssignProductLocator($offset="",$record="")
	{
		$condition = " 1=1";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								warehouse.warehouse_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select 
			inv_assign_product_locator_header.*,
			warehouse.warehouse_name
		
		from inv_assign_product_locator_header

		left join warehouse on 
			warehouse.warehouse_id = inv_assign_product_locator_header.warehouse_id

		where $condition
				order by inv_assign_product_locator_header.header_id desc
			limit ".$record." , ".$offset." ";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getProductsWarehousesCount($product_id="")
	{
		$condition = " 1=1 and inv_product_assign_warehouse.product_id='".$product_id."' ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								warehouse.warehouse_code like "%'.serchFilter($_GET['keywords']).'%" or 
								warehouse.warehouse_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select inv_product_assign_warehouse.assign_id from inv_product_assign_warehouse
		left join warehouse on warehouse.warehouse_id = inv_product_assign_warehouse.warehouse_id
		
		where $condition 
		";

		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getProductsWarehouses($offset="",$record="",$product_id="")
	{
		$condition = " 1=1 and inv_product_assign_warehouse.product_id='".$product_id."' ";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								warehouse.warehouse_code like "%'.serchFilter($_GET['keywords']).'%" or 
								warehouse.warehouse_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select 
			warehouse.warehouse_code,
			warehouse.warehouse_name,
			inv_product_assign_warehouse.*
			from inv_product_assign_warehouse
		left join warehouse on warehouse.warehouse_id = inv_product_assign_warehouse.warehouse_id
		
		where $condition limit ".$record." , ".$offset." ";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	// function getOrgBranchItems($organization_id='',$branch_id=''){
	// 	$query = "SELECT DISTINCT 
	// 				(items.item_id), 
	// 				transactions.organization_id, 
	// 				transactions.branch_id, 
	// 				items.item_name 
	// 				FROM 
	// 				inv_transactions AS transactions 
	// 				LEFT JOIN inv_sys_items AS items ON items.item_id = transactions.item_id
	// 				WHERE 1=1
	// 					and transactions.organization_id = '".$organization_id."' 
	// 					and transactions.branch_id = '".$branch_id."'" ;
		
	// 	$result = $this->db->query($query)->result_array();

	// 	return $result;
	// }
	
	function getItemAll(){
		$query = "SELECT 
					items.item_id,
					items.item_name FROM inv_sys_items AS items 
	 				WHERE 1=1
	 				and items.active_flag = '".$this->active_flag."'";
		
		$result = $this->db->query($query)->result_array();

		return $result;
	}
}
