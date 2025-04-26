<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Seo_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getSeoContents($offset="",$record="",$countType="")
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

			$keywords 			= "concat('%','".serchFilter($_GET['keywords'])."','%')";
			$active_flag 		= !empty($_GET['active_flag']) ? $_GET['active_flag'] : 'NULL';
			

			$query = "select 
			seo.seo_id,
			seo.page_title,
			seo.meta_subject,
			seo.meta_title,
			seo.meta_keywords,
			seo.meta_description,
			seo.page_url,
			seo.active_flag from seo_settings as seo
			where 1=1
			and ( seo.page_title like coalesce($keywords,seo.page_title) or 
				  seo.page_url like coalesce($keywords,seo.page_url) or
			      seo.meta_title like coalesce($keywords,seo.meta_title)
				)
			and seo.active_flag = if('".$active_flag."' = 'All',seo.active_flag,'".$active_flag."')
			order by seo.seo_id desc $limit" ;
			
			$result = $this->db->query($query)->result_array();
		}
		else
		{
			$result = array();
			
		}
		return $result;
	}


	function getViewData($id='')
	{
		$query = "select 
		seo.seo_id,
		seo.page_title,
		seo.meta_subject,
		seo.meta_title,
		seo.meta_keywords,
		seo.meta_description,
		seo.page_url,
		seo.og_title,       			
		seo.og_description,
		seo.og_url,
		seo.og_sitename
		from seo_settings as seo
		where 1=1
		and seo.seo_id='".$id."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function checkSeoList($page_url='',$type='',$id='')
	{
		if($type==='add')
		{
			$condition=' 1=1';
		}
		else
		{
			$condition=" seo.seo_id!='".$id."'";
		}

		$query="select seo.seo_id from seo_settings as seo
		where 1=1 
		and seo.page_url='".$page_url."'
		and $condition";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

}
