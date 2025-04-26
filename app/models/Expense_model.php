<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Expense_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageExpense($offset="",$record="", $countType="")
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

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			$expense_no = "concat('%','".serchFilter($_GET['expense_no'])."','%')";

			$expense_status = !empty($_GET['expense_status']) ? $_GET['expense_status'] : "";

			$query = "select 
				header_tbl.*,
				coalesce(sum(line_tbl.expense_cost),0) as expense_cost
				from expense_header as header_tbl
				left join expense_line as line_tbl on line_tbl.header_id = header_tbl.header_id
			where 1=1
				and header_tbl.expense_status = coalesce(if('".$expense_status."' = '',NULL,'".$expense_status."'),header_tbl.expense_status)
				and header_tbl.expense_number like coalesce($expense_no,header_tbl.expense_number)
				and ( 
					date_format(header_tbl.expense_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.expense_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.expense_date, '%Y-%m-%d'))
				)
			group by line_tbl.header_id
			order by header_tbl.header_id desc $limit";
			$result["header_data"] = $this->db->query($query)->result_array();


			$lineQuery = "select 
				header_tbl.*,
				line_tbl.*,
				expense_type.type_name,
				expense_particulars.particular_name,
				pay_payment_types.payment_type

				from expense_header as header_tbl
				left join expense_line as line_tbl on line_tbl.header_id = header_tbl.header_id
				left join expense_type on expense_type.type_id = line_tbl.expense_type_id
				left join expense_particulars on expense_particulars.particular_id = line_tbl.category_id

				left join pay_payment_types on pay_payment_types.payment_type_id = line_tbl.payment_type_id

			where 1=1
				and header_tbl.expense_status = coalesce(if('".$expense_status."' = '',NULL,'".$expense_status."'),header_tbl.expense_status)
				and header_tbl.expense_number like coalesce($expense_no,header_tbl.expense_number)
				and ( 
					date_format(header_tbl.expense_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.expense_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.expense_date, '%Y-%m-%d'))
				)
			
			order by header_tbl.header_id desc $limit";
			$result["line_data"] = $this->db->query($lineQuery)->result_array();
			return $result;
		}
		else
		{
			$result["header_data"] = array();
			$result["line_data"] = array();
			return $result;
		}
	}
	
	function getManageExpenseParticularCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							expense_particulars.particular_name like "%'.($_GET['keywords']).'%" or
							expense_type.type_name like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select particular_id from expense_particulars
		left join expense_type on expense_type.type_id = expense_particulars.expense_type_id
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageParticular($offset="",$record="")
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							expense_particulars.particular_name like "%'.($_GET['keywords']).'%" or
							expense_type.type_name like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select expense_particulars.*,
				expense_type.type_id,expense_type.type_name from expense_particulars

		left join expense_type on expense_type.type_id = expense_particulars.expense_type_id
		where $condition
				order by particular_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getManageExpensePaymentTypeCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							expense_payment_type.payment_type like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select payment_type_id from expense_payment_type
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManagePaymentType($offset="",$record="")
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							expense_payment_type.payment_type like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		
		$query = "select * from expense_payment_type
		
		where $condition
				order by payment_type_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getManageExpenseTypeCount()
	{
		$condition = " 1=1";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							expense_type.type_name like "%'.($_GET['keywords']).'%" or
							expense_type.type_description like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select type_id from expense_type
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageExpenseType($offset="",$record="")
	{
		$condition = " 1=1";

		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							expense_type.type_name like "%'.($_GET['keywords']).'%" or
							expense_type.type_description like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select * from expense_type
		where $condition
				order by expense_type.type_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
