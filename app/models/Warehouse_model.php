<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Warehouse_model  extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getManageWarehouse($offset="",$record="",$countType="")
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

			if(empty($_GET['keywords'])){
				$keywords = 'NULL';
			}else{
				$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$query = "select 
						warehouses.warehouse_id,
						warehouses.warehouse_code,
						warehouses.warehouse_name,
						warehouses.email,
						warehouses.active_flag,
						warehouses.postal_code,
						warehouses.mobile_number,
						geo_countries.country_name,
						warehouses.created_date,
						branch.branch_name
						
			from warehouse as warehouses
			
			left join branch on branch.branch_id = warehouses.branch_id

			left join geo_countries on geo_countries.country_id = warehouses.country_id

			where 1=1
				and (
					warehouses.warehouse_code like coalesce($keywords,warehouses.warehouse_code) or 
					warehouses.warehouse_name like coalesce($keywords,warehouses.warehouse_name) or
					branch.branch_name like coalesce($keywords,branch.branch_name) or
					warehouses.mobile_number like coalesce($keywords,warehouses.mobile_number)
				)
				and warehouses.active_flag = if('".$active_flag."' = 'All',warehouses.active_flag,'".$active_flag."')
				order by warehouses.warehouse_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			
			return $result;

		}else{
			return array();
		}
	}
	
}


