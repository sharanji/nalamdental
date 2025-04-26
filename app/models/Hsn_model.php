<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hsn_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getManageHSNCode($offset="",$record="", $countType="")
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
			
			$query = "select inv_hsn_codes.*,
			gen_tax.tax_name 
			from inv_hsn_codes
			left join gen_tax on gen_tax.tax_id = inv_hsn_codes.tax_id
			
			where 1=1
				and (
					inv_hsn_codes.hsn_code like coalesce($keywords,inv_hsn_codes.hsn_code) or 
					inv_hsn_codes.hsn_code_description like coalesce($keywords,inv_hsn_codes.hsn_code_description)
				)
				and inv_hsn_codes.active_flag = if('".$active_flag."' = 'All',inv_hsn_codes.active_flag,'".$active_flag."')
				order by inv_hsn_codes.hsn_code_id desc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
			

		} 
		else
		{
			return array();
		}
	}
	
}
