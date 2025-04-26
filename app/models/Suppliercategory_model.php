<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Suppliercategory_model  extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageSupplierCategory($offset="",$record="", $countType="")
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

			$active_flag = $_GET['active_flag'];

			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";

			$query = "select * from sup_supplier_category as supplier_category
			where 1=1
				and (
					supplier_category.category_name like coalesce($keywords,supplier_category.category_name)
				)
				and supplier_category.active_flag = if('".$active_flag."' = 'All',supplier_category.active_flag,'".$active_flag."')
				order by supplier_category.category_id desc";
			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}

	function getCategoryAll() 
	{
		
		$query = "SELECT category.category_id, category.category_name,category.category_description FROM sup_supplier_category as category
					WHERE 1=1
					and category.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getCategoryDescription($category_id='') 
	{
		$query = "SELECT category.category_id, category.category_name,category.category_description FROM sup_supplier_category as category
					WHERE 1=1
					and category.category_id='" . $category_id . "'
					and category.active_flag='" . $this->active_flag . "'";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

}
