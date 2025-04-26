<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Banner_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getBanner($offset="",$record="", $countType="")
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

			if(empty($_GET['banner_title'])){
				$banner_title = 'NULL';
			}else{
				$banner_title = "concat('%','".serchFilter($_GET['banner_title'])."','%')";
			}

			$banner_type = $_GET['banner_type'];
			
			$query = "select 
			banner.banner_id,
			banner.banner_title,
			banner.banner_description,
			banner.active_flag,
			banner.created_date,
			banner.default_banner,
			sm_list_type_values.list_value as banner_type_name
			from banner

			left join sm_list_type_values on sm_list_type_values.list_code = banner.banner_type
			
			where 1=1
			and banner.banner_type = coalesce(if('".$banner_type."' = '',NULL,'".$banner_type."'),banner.banner_type)
				
			and ( banner.banner_title like coalesce($banner_title,banner.banner_title) )
			order by banner.banner_id desc
			$limit ";
			$result = $this->db->query($query)->result_array();
			return $result;
		}else{
			return array();
		}

	
	
	}
	
	
}
