<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Captain_app_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getOauthDetails()
	{
		$query = "select * from api_authorization where default_auth=1";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function adminLogin($email="",$password="")
	{
		$loginQry = "select 
		per_user.user_id,
		per_user.active_flag,
		per_people_all.branch_id,
		per_user.last_login_status
			
		from per_user 
		left join per_people_all on 
		per_people_all.person_id = per_user.person_id
		where 1=1
		and user_name ='".$email."'  
		and password='".md5($password)."'";

		$result = $this->db->query($loginQry)->result_array();
		
		$userId = isset($result[0]['user_id']) ? $result[0]['user_id']:NULL;
			
		if(count($result) == 1)
		{
			if( !empty($result[0]['active_flag']) && $result[0]['active_flag'] == "Y" )
			{
				$branch_id = isset($result[0]['branch_id']) ? $result[0]['branch_id'] :  NULL;
				
				$data = array(
					'last_login_date'    => $this->date_time,
				);

				$this->db->where('user_id', $result[0]['user_id']);
				$result = $this->db->update('per_user', $data);
				
				$loginAuditData = array(
					'user_id'       	=> $userId,
					'created_by'    	=> $userId,
					'created_date'  	=> $this->date_time,
					'last_updated_by'   => $userId,
					'last_updated_date' => $this->date_time,
					'login_type' 		=> "INTERNAL-USER",
					'ip_address' 		=> $_SERVER['SERVER_ADDR'],
					'branch_id' 		=> $branch_id,
				);

				$this->db->insert('org_login_audits', $loginAuditData);
				$id = $this->db->insert_id();


				$userQry = "select 
				per_user.user_id,
				per_user.last_login_date,
				people_all.employee_number,
				people_all.first_name,
				people_all.middle_name,
				people_all.last_name,
				people_all.mobile_number,
				people_all.branch_id,
				branch.branch_name
				from per_user 
				left join per_people_all as people_all on
				people_all.person_id = per_user.person_id

				left join branch on
				branch.branch_id = people_all.branch_id

				where 1=1
				and per_user.reg_user_type ='EMP'
				and per_user.user_id ='".$userId."'
				";
				$getUserDetails = $this->db->query($userQry)->result_array();
	
				$return_status = array(
					"resturn_status" => 10,
					"user_details"   => $getUserDetails,
				);
				return $return_status;	
			}

			$return_status = array(
				"resturn_status" => 9,
				"user_details"   => array(),
			);
			return $return_status;
		}
		else
		{
			if($result == 0)
			{
				$return_status = array(
					"resturn_status" => 8,
					"user_details"   => array(),
				);

				return $return_status;
			}
			$return_status = array(
				"resturn_status" => 0,
				"user_details"   => array(),
			);
			return $return_status;
		}
	}

	function getEmpUserDetails($employee_id="")
	{
		$userQry = "select 
		per_user.user_id,
		per_user.last_login_date,
		people_all.person_id,
		people_all.employee_number,
		people_all.first_name,
		people_all.middle_name,
		people_all.last_name,
		people_all.mobile_number,
		people_all.branch_id,
		people_all.email_address,
		people_all.father_name,
		people_all.mother_name,
		people_all.date_of_birth,
		people_all.gender,
		branch.branch_name
		from per_user 
		left join per_people_all as people_all on
		people_all.person_id = per_user.person_id

		left join branch on
		branch.branch_id = people_all.branch_id

		where 1=1
		and per_user.reg_user_type ='EMP'
		and per_user.person_id ='".$employee_id."'
		";
		$result = $this->db->query($userQry)->result_array();
		return $result;
	}

	function getUserDetails($userId="")
	{
		$userQry = "select 
		per_user.user_id,
		per_user.last_login_date,
		per_user.password,
		people_all.person_id,
		people_all.employee_number,
		people_all.first_name,
		people_all.middle_name,
		people_all.last_name,
		people_all.mobile_number,
		people_all.branch_id,
		people_all.email_address,
		people_all.father_name,
		people_all.mother_name,
		people_all.date_of_birth,
		people_all.gender,
		branch.branch_name
		from per_user 
		left join per_people_all as people_all on
		people_all.person_id = per_user.person_id

		left join branch on
		branch.branch_id = people_all.branch_id

		where 1=1
		and per_user.reg_user_type ='EMP'
		and per_user.user_id ='".$userId."'
		";
		$result = $this->db->query($userQry)->result_array();
		return $result;
	}
	
	function getTables($branch_id="",$user_id="")
	{
		$checkQry ="
			select din_table_lines.line_id
			from din_table_lines
			left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id

			left join din_table_waiters on din_table_waiters.table_line_id = din_table_lines.line_id
			
			left join per_people_all as emp on emp.person_id = din_table_waiters.user_id

			left join per_user as user on user.person_id = emp.person_id

			where 1=1 
			and din_table_lines.active_flag = 'Y'
			and user.user_id = '".$user_id."' 
			and (
				din_table_headers.branch_id = '".$branch_id."' 
				
			)
		"; 

		$checkExist = $this->db->query($checkQry)->result_array();

		if(count($checkExist) > 0)
		{
			$getTablesQry ="
				select din_table_lines.table_name,
				din_table_headers.header_id,
				din_table_headers.table_location_id,
				ltv.list_value as floor_name,
				din_table_lines.line_id,
				din_table_lines.table_code,
				din_table_lines.table_no_of_persons
				from din_table_lines
				left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
				left join sm_list_type_values as ltv on ltv.list_type_value_id = din_table_headers.table_location_id

				left join din_table_waiters on din_table_waiters.table_line_id = din_table_lines.line_id

				left join per_people_all as emp on emp.person_id = din_table_waiters.user_id

				left join per_user as user on user.person_id = emp.person_id

				where 1=1 
				and din_table_lines.active_flag = 'Y'
				and user.user_id = '".$user_id."' 
				and din_table_headers.branch_id = '".$branch_id."'
				"; 
		}
		else
		{
			$getTablesQry ="
				select din_table_lines.table_name,
				din_table_headers.header_id,
				din_table_lines.line_id,
				din_table_lines.table_code,
				din_table_lines.table_no_of_persons
				from din_table_lines
				left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
				where 1=1 
				and din_table_lines.active_flag = 'Y'
				and din_table_headers.branch_id = '".$branch_id."'
			"; 
		}

		$result = $this->db->query($getTablesQry)->result_array();
		return $result;
	}

	function getRunningTables($table_id="")
	{
		$query = "select interface_header_id from ord_order_interface_headers 
		where 1=1
		and table_id='".$table_id."'
		and order_status NOT IN('Delivered','Cancelled')
		";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getPrintBillOrderDetails($table_id="")
	{
		$billedQry = "select 
		interface_header_id
		from ord_order_interface_headers as intergace_header_tbl
		where 1=1
		and bill_print_status='Printed'
		and table_id='".$table_id."'
		and order_status !='Delivered'
		";
		$getBillDetails = $this->db->query($billedQry)->result_array();

		return $getBillDetails;
	}
	
	// Hasain API Starts
	function getCategoryList()
	{
		$userQry = "
		select
			sm_list_type_values.list_code,
			sm_list_type_values.list_value,
			sm_list_type_values.list_type_value_id 
			from sm_list_type_values 
			left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
			where 
			sm_list_types.active_flag='Y' and 
			coalesce(sm_list_types.start_date,$this->date) <= ".$this->date." and 
			coalesce(sm_list_types.end_date,$this->date) >= ".$this->date." and
			sm_list_types.deleted_flag='N' and


			sm_list_type_values.active_flag='Y' and 
			coalesce(sm_list_type_values.start_date,$this->date) <= ".$this->date." and 
			coalesce(sm_list_type_values.end_date,$this->date) >= ".$this->date." and
			sm_list_type_values.deleted_flag='N' and 

			sm_list_types.list_name = '".$this->category_level1_name."'
			order by order_sequence asc
		";
		$result = $this->db->query($userQry)->result_array();
		return $result;
	}

	function getCategoryItemsList($main_category_code="",$branch_id="")
	{
		$query = "
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
			branch_id,
			category_id,
			category_name,
			break_fast_from,
			break_fast_to,
			lunch_from,
			lunch_to,
			dinner_from,
			dinner_to,
			cat2_order_sequence
			from 
			(
				select 
				branch.branch_id,
				branch.branch_name,
				branch.minimum_order_value,
				branch.break_fast_from,
				branch.break_fast_to,
				branch.lunch_from,
				branch.lunch_to,
				branch.dinner_from,
				branch.dinner_to,

				categories.category_id,
				categories.category_name,
				items.item_id,
				items.item_name,
				items.item_description,

				uom.uom_id,
				uom.uom_code,

				branch_items.dine_in_price as item_price,

				branch_items.available_quantity,
				branch_items.minimum_order_quantity,
				coalesce(branch_items.breakfast_flag,'N') as breakfast_flag,
				coalesce(branch_items.lunch_flag,'N') as lunch_flag,
				coalesce(branch_items.dinner_flag,'N') as dinner_flag,

				organization.organization_id,
				organization.organization_code,
				organization.organization_name,
				cat1.list_value as category_name_1,
				cat2.list_value as category_name_2,
				cat3.list_value as category_name_3,
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
					
				where 1=1
				and cat1.list_code = coalesce(if('".$main_category_code."' = 'All',cat1.list_code,'".$main_category_code."'),'All')
				
				and (
					branch_items.branch_id = '".$branch_id."' 
					or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y'))
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
			)";

    		 #and cat2.list_code = coalesce(if('".$sub_category_code."' = 'All',cat2.list_code,'".$sub_category_code."'),'All')
                    
		$result = $this->db->query($query)->result_array();
		//print_r($result);exit;
		return $result;
	}

	function gettrackOrdersList($branch_id="",$waiter_id="")
	{
		$checkQry ="select din_table_lines.line_id
			from din_table_lines
			left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
			left join din_table_waiters on din_table_waiters.table_line_id = din_table_lines.line_id
			left join per_people_all as emp on emp.person_id = din_table_waiters.user_id
			left join per_user as user on user.person_id = emp.person_id
			where 1=1 
			and din_table_lines.active_flag = 'Y'
			and user.user_id = '".$waiter_id."' 
			and din_table_headers.branch_id = '".$branch_id."'
			and exists (select * from ord_order_interface_headers where order_status = 'Created' and order_source = 'DINE_IN' and table_id = din_table_lines.line_id) 
		"; 

		$checkExist = $this->db->query($checkQry)->result_array();

		if(count($checkExist) > 0)
		{
			$getTablesQry ="
				select din_table_lines.table_name,
				din_table_headers.header_id,
				din_table_lines.line_id,
				din_table_lines.table_code,
				din_table_lines.table_no_of_persons
				from din_table_lines
				left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
				left join din_table_waiters on din_table_waiters.table_line_id = din_table_lines.line_id
				left join per_people_all as emp on emp.person_id = din_table_waiters.user_id
				left join per_user as user on user.person_id = emp.person_id
				where 1=1 
				and din_table_lines.active_flag = 'Y'
				and user.user_id = '".$waiter_id."' 
				and din_table_headers.branch_id = '".$branch_id."'
				and exists (select * from ord_order_interface_headers where order_status = 'Created' and order_source = 'DINE_IN' and table_id = din_table_lines.line_id) 
				";
		}
		else
		{
			$getTablesQry ="
				select din_table_lines.table_name,
				din_table_headers.header_id,
				din_table_lines.line_id,
				din_table_lines.table_code,
				din_table_lines.table_no_of_persons
				from din_table_lines
				left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
				
				where 1=1 
				and din_table_lines.active_flag = 'Y'
				and din_table_headers.branch_id = '".$branch_id."'
				and exists (select * from ord_order_interface_headers where order_status = 'Created' and order_source = 'DINE_IN' and table_id = din_table_lines.line_id) 
			"; 
		}
		$result = $this->db->query($getTablesQry)->result_array();

		return $result;
	}

	function gettrackOrderItemsList($branch_id="",$table_id="")
	{
		$query = "select 
        header_tbl.branch_id,
        header_tbl.table_id,
        tables.table_code,
		header_tbl.interface_header_id,
		line_tbl.interface_line_id,
		coalesce(line_tbl.order_seq_number,0) order_seq_number,
		header_tbl.created_date
		from ord_order_interface_lines as line_tbl 

		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as item on
		item.item_id = line_tbl.product_id

		left join din_table_lines as tables on
		tables.line_id = header_tbl.table_id

		where 1=1 
		and header_tbl.branch_id ='".$branch_id."' 
		and header_tbl.table_id = '".$table_id."' 
		and header_tbl.order_status='Created'
        group by line_tbl.order_seq_number
        order by line_tbl.order_seq_number desc
		limit 0,1
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	// Hasain API Ends


	function getBranchOrganization($branch_id="")
	{
		$query = "select organization_id from branch where branch_id='".$branch_id."' ";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getCartItems($organization_id,$branch_id,$user_id,$table_id)
	{
		
		$cartItemsQry = "select 
		line_tbl.*,
		line_tbl.item_id as product_id,
		(line_tbl.price * line_tbl.quantity) as line_total,
		item.item_name,
		item.item_description

		from ord_cart_items as line_tbl

		left join inv_sys_items as item on
		item.item_id = line_tbl.item_id

		where 1=1
		and organization_id ='".$organization_id."'
		and	branch_id ='".$branch_id."'
		
		and	waiter_id ='".$user_id."'
		and	table_id ='".$table_id."'
		and	cart_status IS NULL
		";

		$result = $this->db->query($cartItemsQry)->result_array();

		return $result;
	}

	function getDineInSeqOrder($table_id="", $branch_id="",$user_id="")
	{
		$dineInOrderQry = "select 
        header_tbl.branch_id,
        header_tbl.table_id,
        tables.table_code,
		header_tbl.interface_header_id,
		header_tbl.order_number,
		line_tbl.interface_line_id,
		line_tbl.tax_percentage,
		coalesce(line_tbl.order_seq_number,0) order_seq_number,
		header_tbl.created_date,
		header_tbl.discount_remarks
		from ord_order_interface_lines as line_tbl 

		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as item on
		item.item_id = line_tbl.product_id

		left join din_table_lines as tables on
		tables.line_id = header_tbl.table_id
		where 1=1 
		and header_tbl.branch_id ='".$branch_id."' 
		and header_tbl.table_id = '".$table_id."' 
		and header_tbl.waiter_id = '".$user_id."' 
		and header_tbl.order_status='Created'
        group by line_tbl.order_seq_number
        order by line_tbl.order_seq_number asc
		";
		$result = $this->db->query($dineInOrderQry)->result_array();

		return $result;
	}

	function checkRunningTables($branch_id='',$to_table_id='')
	{
		$query = "select interface_header_id from ord_order_interface_headers 
				where 1=1
				and branch_id='".$branch_id."' 
				and table_id='".$to_table_id."' 
				and order_status='Created' 
				";
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	function getDocumentData($branch_id='')
	{
		$query = "select doc_num_id,prefix_name,suffix_name,next_number 
		from doc_document_numbering as dm
		left join sm_list_type_values ltv on 
		ltv.list_type_value_id = dm.doc_type
		where 1=1
		and dm.doc_document_type = 'dine-in-orders'
		and dm.branch_id = '".$branch_id."'
		and ltv.list_code = 'CUS_ORD' 
		and dm.active_flag = 'Y'
		and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
		and coalesce(dm.to_date,CURDATE()) >= CURDATE()
		";
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	function getOrderData($table_id='',$interface_header_id='')
	{
		$query = "
		select 
		line_tbl.interface_line_id,
		line_tbl.order_seq_number 
		from ord_order_interface_lines as line_tbl
		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id
		where 1=1 
		and header_tbl.table_id= '".$table_id."'
		and line_tbl.reference_header_id='".$interface_header_id."'
		order by line_tbl.order_seq_number desc
		limit 0,1";
		$getOrderData = $this->db->query($query)->result_array();
		return $getOrderData;
	}


}
