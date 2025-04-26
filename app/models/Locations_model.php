<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Locations_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getLocations($offset="", $record="", $countType="")
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

			$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			$active_flag = $_GET['active_flag'];

			$query = "select 
					location.location_id,
					location.location_name,
					location.address1,
					location.active_flag
				from loc_location_all as location
			where 1=1
				and location.location_name like coalesce($keywords,location.location_name) 
				and location.active_flag = if('".$active_flag."' = 'All',location.active_flag,'".$active_flag."')
				order by location.location_id desc
				$limit";
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
}
