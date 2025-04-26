<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lov_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getListType($offset="",$record="", $countType="")
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

			$query = "select * from sm_list_types
						
				where 1=1
				and (
					sm_list_types.list_name like coalesce($keywords,sm_list_types.list_name) or 
					sm_list_types.list_description like coalesce($keywords,sm_list_types.list_description)
				)
				and sm_list_types.active_flag = if('".$active_flag."' = 'All',sm_list_types.active_flag,'".$active_flag."')
				order by sm_list_types.list_type_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
	function getListTypeValue($offset="",$record="", $id="", $countType="")
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
		
		$query = "select sm_list_type_values.* from sm_list_type_values 
		
			left join sm_list_types on 
			sm_list_types.list_type_id = sm_list_type_values.list_type_id
			
			where 1=1 
				and sm_list_type_values.list_type_id='".$id."'
				and (
					sm_list_type_values.list_code like coalesce($keywords,sm_list_type_values.list_code) or 
					sm_list_type_values.list_value like coalesce($keywords,sm_list_type_values.list_value)
				)
				$limit
				";

		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
