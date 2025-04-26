<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Qualification_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

    function getManageQualification($offset="",$record="")
	{
		$condition = " 1=1 ";
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									qualification.qualification_name like "%'.($_GET['keywords']).'%"
								)
							';
		}
		
		$query = "select *
			from qualification
		
		where $condition
			order by qualification.qualification_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	#Qualification Ends

    function getQualification(){
        $query = "select 
        qualification.qualification_id, 
        qualification.qualification_name 
        from qualification 
        where 1=1
        and qualification.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
    }
}
