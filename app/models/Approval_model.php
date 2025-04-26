<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Approval_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageApproval($offset="",$record="", $countType="")
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

			/* if(empty($_GET['approval_type'])){
				$approval_type = NULL;
			}else{
				$approval_type = $_GET['approval_type'];
			} */

			$approval_type = $_GET['approval_type'];

			$query = "select org_approval_header.* from org_approval_header
				where 1=1
				and org_approval_header.approval_type = coalesce(if('".$approval_type."' = '',NULL,'".$approval_type."'),org_approval_header.approval_type)
				";
			$result = $this->db->query($query)->result_array();
			return $result;
		} 
		else
		{
			return array();
		}
	}
	
}
