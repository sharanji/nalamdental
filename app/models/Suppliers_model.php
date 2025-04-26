<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Suppliers_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageSuppliers($offset="",$record="", $countType="")
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

			if(empty($_GET['supplier_id'])){
				$supplier_id = 'NULL';
			}else{
				$supplier_id = $_GET['supplier_id'];
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$query = "select supplier.* from sup_suppliers as supplier
			where 1=1
					and supplier.supplier_id = coalesce($supplier_id,supplier.supplier_id)
					and supplier.active_flag = if('".$active_flag."' = 'All',supplier.active_flag,'".$active_flag."')
					order by supplier.supplier_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
	
	#Supplier Sites
	function getManageSupplierSites($offset="",$record="", $countType="")
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

			if(empty($_GET['supplier_id'])){
				$supplier_id = 'NULL';
			}else{
				$supplier_id = $_GET['supplier_id'];
			}

			if(empty($_GET['supplier_site_id'])){
				$supplier_site_id = 'NULL';
			}else{
				$supplier_site_id = $_GET['supplier_site_id'];
			}

			$active_flag = $_GET['active_flag'];
		

			$query = "select sup_supplier_sites.*,sup_suppliers.supplier_name from  sup_supplier_sites

			left join sup_suppliers on sup_suppliers.supplier_id = sup_supplier_sites.supplier_id
			where 1=1
				and (
					sup_supplier_sites.supplier_id like coalesce($supplier_id,sup_supplier_sites.supplier_id) or 
					sup_supplier_sites.site_name like coalesce($supplier_id,sup_supplier_sites.site_name)
				)
				and sup_supplier_sites.active_flag = if('".$active_flag."' = 'All',sup_supplier_sites.active_flag,'".$active_flag."')
				order by sup_supplier_sites.supplier_site_id   desc $limit";
			$result = $this->db->query($query)->result_array();

			
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function getSupplierAll()
	{
		$query = "select 
		supplier.supplier_id,
		supplier.supplier_number,
		supplier.supplier_name
		from sup_suppliers as supplier
		where 1=1
		and supplier.active_flag='Y'
		order by supplier.supplier_name asc";
		$result = $this->db->query($query)->result_array();
		return $result;	
	}

	function getAjaxSupplierAll($supplier_name='')
	{
		$query="select supplier_id,supplier_name from sup_suppliers as supplier
				where 1=1 
				and supplier.supplier_name LIKE '%" . $supplier_name . "%'
				and supplier.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	function getAjaxSupplierSites($supplier_id='')
	{
		$query="select sup_site.supplier_site_id,sup_site.site_name from sup_supplier_sites as sup_site
				where 1=1 
				and sup_site.supplier_id = '". $supplier_id . "'
				and sup_site.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	
}
