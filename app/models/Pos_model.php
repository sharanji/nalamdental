<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pos_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getPosItems()
	{
		if($this->branch_id){
			$branch_id = $this->branch_id;
			//$branch_id = 1;
		}else{
			$branch_id = 'NULL';
		}

		$itemQuery = "
			select 
			item_id,
			item_name,
			item_description,
			item_price,
			uom_id,
			uom_code,
			food_time,
			breakfast_flag,
			lunch_flag,
			dinner_flag,
            cat1_order_sequence,
            cat2_order_sequence
			from 
			(
				select 
					branch.branch_id,
					categories.category_id,
					items.item_id,
					items.item_name,
					items.item_description,
					uom.uom_id,
					uom.uom_code,
					branch_items.item_price,
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag, 
					coalesce(branch_items.lunch_flag,'N') as lunch_flag, 
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,
					offers.offer_percentage,
					(
						case
							when '".$this->currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$this->currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$this->currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time,
                    cat1.order_sequence as cat1_order_sequence,
                    cat2.order_sequence as cat2_order_sequence

				from inv_item_branch_assign as branch_items

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join uom on uom.uom_id = items.uom
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join inv_item_offers as offers on offers.branch_id = branch.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id
				
				left join sm_list_type_values as cat1 on
					cat1.list_type_value_id = categories.cat_level_1

				left join sm_list_type_values as cat2 on
					cat2.list_type_value_id = categories.cat_level_2

				left join sm_list_type_values as cat3 on
					cat3.list_type_value_id = categories.cat_level_3
					
				where
				1=1
				and (
					branch_items.branch_id = '".$branch_id."' 
					or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
				)
				
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				and branch.active_flag = 'Y' 
				group by branch_id,category_id,item_id
				order by cat1_order_sequence,cat2_order_sequence,item_id asc
			) t
			
			HAVING ( 
				breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
				lunch_flag = if (food_time = 'Lunch', 'Y','') or
				dinner_flag = if (food_time = 'Dinner', 'Y','') 
			)
		";

		//and branch.default_branch = 'Y'
		##having coalesce(t.food_time,'') != ''

		
		
		$result = $this->db->query($itemQuery)->result_array();

		return $result;
	}

	function getManagePosOrders($offset="",$record="", $countType="")
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

			$order_number = "concat('%','".serchFilter($_GET['order_number'])."','%')";
			
			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;
			
			/* round( sum((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal, 
			round( sum( ((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0) /100)),2) as tax_value 
 			*/
			$query = "select 
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
	
				(select round( sum((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))),2) from ord_order_headers as header_tbl1
				left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
				where 1=1
				and header_tbl.header_id = header_tbl1.header_id 
				and line_tbl1.line_status != 'Cancelled') as linetotal,

				(select round( sum( ((line_tbl1.quantity * line_tbl1.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl1.quantity * line_tbl1.price))) * (coalesce(tax_percentage,0) /100)),2) from ord_order_headers as header_tbl1
				left join ord_order_lines as line_tbl1 on line_tbl1.header_id = header_tbl1.header_id
				where 1=1
				and header_tbl.header_id = header_tbl1.header_id 
				and line_tbl1.line_status != 'Cancelled') as tax_value,

				consumer.customer_name,
				consumer.mobile_number
				
				
				from ord_order_headers as header_tbl 
				left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id 
				
				left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method 
				left join branch on branch.branch_id = header_tbl.branch_id 

				left join per_user on per_user.user_id = header_tbl.created_by

				left join per_user as customer on customer.user_id = header_tbl.customer_id
				left join cus_consumers as consumer on consumer.customer_id = customer.reference_id




				WHERE 1=1
				and header_tbl.branch_id = coalesce($all_branch_id,header_tbl.branch_id)
				and header_tbl.branch_id = coalesce(if('".$branch_id."' = '',NULL,'".$branch_id."'),header_tbl.branch_id)
				
				and header_tbl.order_source ='POS'
				and header_tbl.order_number like coalesce($order_number,header_tbl.order_number)
				and ( 
					date_format(header_tbl.ordered_date, '%Y-%m-%d') 
					BETWEEN coalesce(coalesce(date_format('".$fromDate."','%Y-%m-%d'),NULL), date_format(header_tbl.ordered_date, '%Y-%m-%d')) 
					and coalesce(coalesce(date_format('".$toDate."','%Y-%m-%d'),NULL),date_format(header_tbl.ordered_date, '%Y-%m-%d'))
				)
				group by line_tbl.header_id,line_tbl.cancel_status
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
