<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchase_receipt_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManagePurchaseReceipt($offset="",$record="",$countType="")
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

			$po_header_id = !empty($_GET['po_header_id']) ? $_GET['po_header_id'] : 'NULL';
			$organization_id = !empty($_GET['organization_id']) ? $_GET['organization_id'] : 'NULL';
			$branch_id = !empty($_GET['branch_id']) ? $_GET['branch_id'] : 'NULL';
			$receipt_number = "concat('%','".serchFilter($_GET['receipt_number'])."','%')";
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$query = "select 
				header_tbl.*,
				po_headers.po_number,
				organization.organization_name,
				branch.branch_name
		 		from rcv_receipt_headers as header_tbl

				left join org_organizations as organization on organization.organization_id = header_tbl.organization_id

				left join branch on branch.branch_id = header_tbl.branch_id

				left join po_headers on po_headers.po_header_id = header_tbl.po_header_id

				where 1=1
				
				and po_headers.po_header_id = coalesce($po_header_id,po_headers.po_header_id)
				and organization.organization_id = coalesce($organization_id,organization.organization_id)
				and branch.branch_id = coalesce($branch_id,branch.branch_id)
				and header_tbl.receipt_number like coalesce($receipt_number,header_tbl.receipt_number)
				and ( 
					date_format(header_tbl.receipt_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.receipt_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.receipt_date, '%Y-%m-%d'))
				)
					and receipt_type = 'PO_REC'
					order by header_tbl.receipt_header_id desc $limit" ;

			$result = $this->db->query($query)->result_array();
			return $result;
		}
		else
		{
			return array();
		}
	}
}
