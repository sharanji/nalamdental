<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payment_terms_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	
	function getManagePayment_terms($offset="",$record="",$countType="")
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

			$query = "select * from payment_terms	
			where 1=1
				and (
					payment_terms.payment_term like coalesce($keywords,payment_terms.payment_term) or 
					payment_terms.payment_description like coalesce($keywords,payment_terms.payment_description) or
					payment_terms.payment_days like coalesce($keywords,payment_terms.payment_days)
				)
				and payment_terms.active_flag = if('".$active_flag."' = 'All',payment_terms.active_flag,'".$active_flag."')
				order by payment_terms.payment_term_id desc $limit";

			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}

}
