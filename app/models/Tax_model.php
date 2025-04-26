<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tax_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getTax($offset="",$record="", $countType="")
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

			if(empty($_GET['tax_name'])){
				$tax_name = 'NULL';
			}else{
				$tax_name = "concat('%','".serchFilter($_GET['tax_name'])."','%')";
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$query = "select 
			tax_id,
			tax_name,
			tax_value,
			active_flag,
			created_date,
			default_tax
			from gen_tax
			where 1=1
			and gen_tax.tax_name like coalesce($tax_name,gen_tax.tax_name)
			and gen_tax.active_flag = if('".$active_flag."' = 'All',gen_tax.active_flag,'".$active_flag."')
			order by gen_tax.tax_id desc
			$limit ";
			$result = $this->db->query($query)->result_array();
			return $result;
		}else{
			return array();
		}

	
	
	}
	
	
}
