<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Accounts_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageCashExpense($offset="",$record="", $countType="")
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

			$expense_no = "concat('%','".serchFilter($_GET['expense_no'])."','%')";

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			$query = "select 
			header_tbl.*,
			sum(line_tbl.expense_cost) as total_amount
			from acc_cash_expense_header as header_tbl
			left join acc_cash_expense_line as line_tbl on
			line_tbl.header_id = header_tbl.header_id
			
			where 1=1
			and header_tbl.expense_number like coalesce($expense_no,header_tbl.expense_number)
			and ( 
				date_format(header_tbl.expense_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.expense_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.expense_date, '%Y-%m-%d'))
			)
			group by line_tbl.header_id
			order by header_tbl.header_id desc  $limit";

			$result["header_data"] = $this->db->query($query)->result_array();

			$lineQuery = "select 
			header_tbl.*, 
			line_tbl.*
			from acc_cash_expense_line as line_tbl	

			left join acc_cash_expense_header as header_tbl on
			line_tbl.header_id = header_tbl.header_id

			where 1=1
			and header_tbl.expense_number like coalesce($expense_no,header_tbl.expense_number)
			and ( 
				date_format(header_tbl.expense_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.expense_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.expense_date, '%Y-%m-%d'))
			)
			
			order by line_tbl.line_id asc";
			
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

	function getmanageSupplierPayment($offset="",$record="", $countType="")
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

			$payment_number = "concat('%','".serchFilter($_GET['payment_number'])."','%')";

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			$query = "select 
			header_tbl.*,
			COALESCE(SUM(COALESCE(line_tbl.quantity, 0) * COALESCE(line_tbl.amount, 0)), 0) AS total_amount,
			sup_suppliers.supplier_name

			from acc_supplier_payment_header as header_tbl
			left join acc_supplier_payment_line as line_tbl on
			line_tbl.header_id = header_tbl.header_id

			LEFT JOIN sup_suppliers ON sup_suppliers.supplier_id = line_tbl.supplier_id
		

			where 1=1
			and header_tbl.payment_number like coalesce($payment_number,header_tbl.payment_number)
			and ( 
				date_format(header_tbl.payment_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.payment_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.payment_date, '%Y-%m-%d'))
			)
			group by line_tbl.header_id
			order by header_tbl.header_id desc  $limit";

			$result["header_data"] = $this->db->query($query)->result_array();

			$lineQuery = "select 
			header_tbl.*, 
			line_tbl.*,
			(COALESCE(line_tbl.quantity, 0) * COALESCE(line_tbl.amount, 0)) AS total_amount,
			sup_suppliers.supplier_name,
			uom.uom_code,
			category.category_name
			from acc_supplier_payment_line as line_tbl	

			left join acc_supplier_payment_header as header_tbl on
			line_tbl.header_id = header_tbl.header_id
			
			LEFT JOIN sup_suppliers ON sup_suppliers.supplier_id = line_tbl.supplier_id

			LEFT JOIN uom ON uom.uom_id = line_tbl.uom_id

			LEFT JOIN sup_supplier_category as category ON category.category_id = line_tbl.category_id
			

			where 1=1
			and header_tbl.payment_number like coalesce($payment_number,header_tbl.payment_number)
			and ( 
				date_format(header_tbl.payment_date, '%Y-%m-%d') 
				BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.payment_date, '%Y-%m-%d')) 
				and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.payment_date, '%Y-%m-%d'))
			)
			
			order by line_tbl.line_id asc";
			
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

	#getViewData
	public function getViewData($id='')
	{
		$headerQry = "select 
			header_tbl.*,
			sum(line_tbl.expense_cost) as total_amount
			from acc_cash_expense_header as header_tbl
			left join acc_cash_expense_line as line_tbl on
			line_tbl.header_id = header_tbl.header_id
			where 1=1
			and header_tbl.header_id = '".$id."'
			group by line_tbl.header_id
			";

		$result['headerData'] = $this->db->query($headerQry)->result_array();

		$lineQry = "select 
			line_tbl.*
			from acc_cash_expense_line as line_tbl	
			where 1=1
			and line_tbl.header_id = '".$id."' 
			order by line_tbl.line_id asc";

		$result['lineData'] = $this->db->query($lineQry)->result_array();

		return $result;
	}
	public function getsuppilerViewData($id='')
	{
		$headerQry = "select 
			header_tbl.*,
			COALESCE(SUM(COALESCE(line_tbl.quantity, 0) * COALESCE(line_tbl.amount, 0)), 0) AS total_amount
			from acc_supplier_payment_header as header_tbl
			left join  acc_supplier_payment_line as line_tbl on
			line_tbl.header_id = header_tbl.header_id
			where 1=1
			and header_tbl.header_id = '".$id."'
			group by line_tbl.header_id
			";

		$result['headerData'] = $this->db->query($headerQry)->result_array();

		$lineQry = "select 
			line_tbl.*
			from  acc_supplier_payment_line as line_tbl	
			where 1=1
			and line_tbl.header_id = '".$id."' 
			order by line_tbl.line_id asc";

		$result['lineData'] = $this->db->query($lineQry)->result_array();

		return $result;
	}

	public function checkLineData($header_id='',$line_id='')
	{	
		$lineQry = "select 
			line_tbl.line_id
			from acc_cash_expense_line as line_tbl	
			where 1=1
			and line_tbl.header_id = '".$header_id."' 
			and line_tbl.line_id = '".$line_id."'";

		$result = $this->db->query($lineQry)->result_array();

		return $result;
	}

	public function checkLineDatas($header_id='',$line_id='')
	{	
		$lineQry = "select 
			line_tbl.line_id
			from  acc_supplier_payment_line as line_tbl	
			where 1=1
			and line_tbl.header_id = '".$header_id."' 
			and line_tbl.line_id = '".$line_id."'";

		$result = $this->db->query($lineQry)->result_array();

		return $result;
	}
}
