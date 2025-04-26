<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dine_in_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageDineInOrders($offset="",$record="", $countType="")
	{
		if($_GET)
		{
			if($this->branch_id){
				$branch_id = $this->branch_id;
				#$branch_id = 1;
			}else{
				$branch_id = '';
			}

			if($countType == 1) #GetTotalCount
			{
				$limit = "";
			}
			else if($countType == 2) #Get Page Wise Count
			{
				$limit = "limit ".$record." , ".$offset." "; 
			}

			if(empty($_GET['branch_id'])){
				$all_branch_id = 'NULL';
			}else{
				$all_branch_id = $_GET['branch_id'];
			}

			if(empty($_GET['table_location_id'])){
				$table_location_id = 'NULL';
			}else{
				$table_location_id = $_GET['table_location_id'];
			}

			if(empty($_GET['tables'])){
				$table_id = 'NULL';
			}else{
				$table_id = $_GET['tables'];
			}

			$order_number = "concat('%','".serchFilter($_GET['order_number'])."','%')";
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			/* round( sum((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal, 
			round( sum( ((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0) /100)),2) as tax_value,
			 */
			$query = "select 
				header_tbl.waiter_id, 
				header_tbl.header_id, 
				header_tbl.payment_due,
				header_tbl.order_number, 
				header_tbl.ordered_date, 
				header_tbl.order_status, 
				header_tbl.notification_read_status,
				header_tbl.payment_method, 
				branch.branch_name, 
				payment_type.payment_type,   
				line_tbl.cancel_status, 
				sum(line_tbl.price) as price, 
				sum(line_tbl.price * line_tbl.quantity) as bill_amount, 

				
				round( sum((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount, 
			
		
				CONCAT(din_tbl.table_code,coalesce(header_tbl.sub_table,'')) as table_name,

				per_people_all.first_name as waiter_name,
				ltv.list_value as table_location_name,
				

				(select round( sum((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))),2) from ord_order_headers as header_tbl1
				left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
				where 1=1
				and header_tbl.header_id = header_tbl1.header_id 
				and line_tbl1.line_status != 'Cancelled') as linetotal,

				(select round( sum( ((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))) * (coalesce(tax_percentage,0) /100)),2) from ord_order_headers as header_tbl1
				left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
				where 1=1
				and header_tbl.header_id = header_tbl1.header_id 
				and line_tbl1.line_status != 'Cancelled') as tax_value
				
				from ord_order_headers as header_tbl 
				left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id 
				
				left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method 
				left join branch on branch.branch_id = header_tbl.branch_id 

				join per_user on per_user.user_id = header_tbl.created_by

				left join din_table_lines as din_tbl on din_tbl.line_id = header_tbl.table_id
				left join din_table_headers as din_tbl_header on din_tbl_header.header_id = din_tbl.header_id
				left join sm_list_type_values as ltv on ltv.list_type_value_id = din_tbl_header.table_location_id

				left join per_user as waiter on waiter.user_id = header_tbl.waiter_id
				left join per_people_all on per_people_all.person_id = waiter.person_id

				WHERE 1=1
				and header_tbl.branch_id = coalesce($all_branch_id,header_tbl.branch_id)
				and ltv.list_type_value_id = coalesce($table_location_id,ltv.list_type_value_id)
				and header_tbl.table_id = coalesce($table_id,header_tbl.table_id)
				
				and header_tbl.branch_id = coalesce(if('".$branch_id."' = '',NULL,'".$branch_id."'),header_tbl.branch_id)
				and header_tbl.order_source ='DINE_IN'
				and header_tbl.order_number like coalesce($order_number,header_tbl.order_number)
				and ( 
					date_format(header_tbl.ordered_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.ordered_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.ordered_date, '%Y-%m-%d'))
				)
				group by line_tbl.header_id
				order by header_tbl.header_id desc
				$limit		
			";
		
			$result["listing"] = $this->db->query($query)->result_array();
			$result["totalCount"] = $result["listing"];
			return $result;
			#and header_tbl.created_by = coalesce(if('".$this->user_id."' = '1',NULL,'".$this->user_id."'),header_tbl.created_by)		
		}
		else
		{
			$result["listing"] = array();
			$result["totalCount"] = $result["listing"];
			return $result;
		}
	}
}
