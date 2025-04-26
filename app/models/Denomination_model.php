<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Denomination_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getmanageDenomination($offset="",$record="", $countType="")
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

			if($this->user_id == 1){
				$condition = '1=1';
			}else{
				$condition = 'header_tbl.created_by='.$this->user_id.'';
			}

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$query = "select 
			header_tbl.*,
			emp.first_name
			from pay_denomination_headers as header_tbl

			left join per_user as user on user.user_id = header_tbl.created_by
			left join per_people_all as emp on emp.person_id = user.person_id
			where 
			1=1
			and $condition
			and ( 
				date_format(header_tbl.denomination_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.denomination_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.denomination_date, '%Y-%m-%d'))
			)
			order by header_tbl.header_id desc $limit";
			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}

	#getViewData
	public function getViewData($id='')
	{
		$headerQry = "select 
			header_tbl.*,
			emp.first_name,
			user.last_login_date,
			user.logout_date
			from pay_denomination_headers as header_tbl

			left join per_user as user on user.user_id = header_tbl.created_by
			left join per_people_all as emp on emp.person_id = user.person_id
			where 
			1=1
			and header_tbl.header_id='".$id."' ";
			
		$result['headerData'] = $this->db->query($headerQry)->result_array();

		$lineQry = "select 
			line_tbl.*
			from pay_denomination_lines as line_tbl	
			left join pay_denomination_headers as header_tbl on 
				header_tbl.header_id = line_tbl.header_id
			
			where 1=1
			and line_tbl.header_id = '".$id."'";

		$result['lineData'] = $this->db->query($lineQry)->result_array();

		return $result;
	}
	
}
