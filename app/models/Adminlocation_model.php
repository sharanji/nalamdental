<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adminlocation_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
    }
	
	function get_country($offset="", $record="", $countType="")
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
			if(empty($_GET['country_id'])){
				$country_id = 'NULL';
			}else{
				$country_id = $_GET['country_id'];
			}
				
			$query = "select * from geo_countries as country
			where 1=1
				and country.country_id = coalesce($country_id,country.country_id)
				and country.active_flag = if('".$active_flag."' = 'All',country.active_flag,'".$active_flag."')
				order by country.country_name $limit";
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
	function get_state($offset="", $record="", $countType="")
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
			if(empty($_GET['country_id'])){
				$country_id = 'NULL';
			}else{
				$country_id = $_GET['country_id'];
			}

			if(empty($_GET['state_id'])){
				$state_id = 'NULL';
			}else{
				$state_id = $_GET['state_id'];
			}
				
			$query = "select state.*,country.country_id,country.country_name from geo_states as state
			left join geo_countries as country  on country.country_id = state.country_id
			
			where 1=1
				and state.country_id = coalesce($country_id,state.country_id)
				and state.state_id = coalesce($state_id,state.state_id)
				and state.active_flag = if('".$active_flag."' = 'All',state.active_flag,'".$active_flag."')
				order by country.country_name,state.state_name asc $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
	function get_district($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (country.country_name like "%'.serchFilter($_GET['keywords']).'%")
								or (state.state_name like "%'.serchFilter($_GET['keywords']).'%")
								or (district.district_name like "%'.serchFilter($_GET['keywords']).'%")
								';
		}
		
		$query = "select district.*,country.country_id,country.country_name,state.state_id,state.state_name from district
		left join country on country.country_id = district.country_id
		left join state on state.state_id = district.state_id
		where $condition
		order by district.district_id desc
			limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function get_city($offset="", $record="", $countType="")
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
			if(empty($_GET['country_id'])){
				$country_id = 'NULL';
			}else{
				$country_id = $_GET['country_id'];
			}

			if(empty($_GET['state_id'])){
				$state_id = 'NULL';
			}else{
				$state_id = $_GET['state_id'];
			}

			if(empty($_GET['city_id'])){
				$city_id = 'NULL';
			}else{
				$city_id = $_GET['city_id'];
			}
				
			$query = "select city.*,country.country_id,country.country_name,state.state_id,state.state_name from 
			geo_cities as city
			left join geo_countries as country  on country.country_id = city.country_id
			left join geo_states as state on state.state_id = city.state_id
			
			where 1=1
				and city.country_id = coalesce($country_id,city.country_id)
				and city.state_id = coalesce($state_id,city.state_id)
				and city.city_id = coalesce($city_id,city.city_id)

				and city.active_flag = if('".$active_flag."' = 'All',city.active_flag,'".$active_flag."')
				order by country.country_name,state.state_name,city.city_name $limit";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
	function getCountryData($id="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (country.country_name like "%'.serchFilter($_GET['keywords']).'%")';
		}
		
		$query = "select * from geo_countries as country
		where $condition
		";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

}
