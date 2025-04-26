<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Organization_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getOrganizations($offset="", $record="", $countType="")
	{
		if($_GET)
		{
			if($countType == 1) #GetTotal Count
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
				organization.*,
				active_flag
				from org_organizations as organization
			
			where 1=1
				and ( 
						organization.organization_code like coalesce($keywords,organization.organization_code) 
						or organization.organization_name like coalesce($keywords,organization.organization_name)
					)
				and organization.active_flag = if('".$active_flag."' = 'All',organization.active_flag,'".$active_flag."')
				order by organization.organization_id desc
				$limit
			";
			
			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}

	function ajaxSelectCompleteAddress($location_id)
	{
		$locationQry = 'select 
			loc_location_all.address1,
			loc_location_all.address2,
			loc_location_all.address3,
			loc_location_all.postal_code,
			geo_countries.country_name,
			geo_states.state_name,
			geo_cities.city_name
			
			from loc_location_all 
			left join geo_countries on geo_countries.country_id = loc_location_all.country_id
			left join geo_states on geo_states.state_id = loc_location_all.state_id
			left join geo_cities on geo_cities.city_id = loc_location_all.city_id
		where 
			loc_location_all.location_id="'.$location_id.'" ';

		$locatioData = $this->db->query($locationQry)->result_array();
		
		$address1 = isset($locatioData[0]["address1"]) ? ucfirst($locatioData[0]["address1"]) : NULL;
		$address2 = isset($locatioData[0]["address2"]) ? ucfirst($locatioData[0]["address2"]) : NULL;
		$address3 = isset($locatioData[0]["address3"]) ? ucfirst($locatioData[0]["address3"]) : NULL;
		$postalCode = isset($locatioData[0]["postal_code"]) ? ucfirst($locatioData[0]["postal_code"]) : NULL;

		$countryName = isset($locatioData[0]["country_name"]) ? ucfirst($locatioData[0]["country_name"]) : NULL;
		$stateName = isset($locatioData[0]["state_name"]) ? ucfirst($locatioData[0]["state_name"]) : NULL;
		$cityName = isset($locatioData[0]["city_name"]) ? ucfirst($locatioData[0]["city_name"]) : NULL;

		if($address2 != NULL)
		{
			$Address2 = ", ".$address2;
		}else{$Address2="";}

		if($address3 != NULL)
		{
			$Address3 = ", ".$address3;
		}else{$Address3="";}

		if($countryName != NULL)
		{
			$CountryName = ", ".$countryName;
		}else{$CountryName="";}

		if($stateName != NULL)
		{
			$StateName = ", ".$stateName;
		}else{$StateName="";}

		if($cityName != NULL)
		{
			$CityName = ", ".$cityName;
		}else{$CityName="";}

		if($postalCode != NULL)
		{
			$PostalCode = "".$StateName."-".$postalCode.".";
		}else{$PostalCode="";}
		
		$completeAddress = $address1.$Address2.$Address3.$CountryName.$StateName.$CityName.$PostalCode;

		return $completeAddress;
	}

	function getOrgAll()
	{
		$organizationQry = "select organization_id,organization_code,organization_name from org_organizations
		where active_flag='Y' order by organization_name asc
		";
		$getOrganization = $this->db->query($organizationQry)->result_array();

		return $getOrganization;
	}

	function getOrg($organization_id="")
	{
		$organizationQry = "select organization_id,organization_code,organization_name from org_organizations
		where active_flag='Y' and organization_id = '".$organization_id."'
		";
		$getOrganization = $this->db->query($organizationQry)->result_array();

		return $getOrganization;
	}
	
}
