<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Web_dine_in_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getMainCategories()
	{
		$query = "select
			sm_list_type_values.list_code,
			sm_list_type_values.list_value,
			sm_list_type_values.list_type_value_id 
			from sm_list_type_values 
			left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
			where 1=1

			and sm_list_type_values.list_code NOT IN('RAW-MATERIALS','PACKING-MATERIALS','EXPENSE-ITEMS')

			and sm_list_types.active_flag='Y' 
			and coalesce(sm_list_types.start_date,$this->date) <= ".$this->date." 
			and coalesce(sm_list_types.end_date,$this->date) >= ".$this->date."
			and sm_list_types.deleted_flag='N'

			and sm_list_type_values.active_flag='Y'  
			and coalesce(sm_list_type_values.start_date,$this->date) <= ".$this->date."  
			and coalesce(sm_list_type_values.end_date,$this->date) >= ".$this->date."
			and sm_list_type_values.deleted_flag='N'
			and sm_list_types.list_name = '".$this->category_level1_name."'
			order by order_sequence asc
			";
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	function getPosItemSearch($keywords,$branch_id,$admin_user_id)
	{
		$output = '';
		$itemQuery = "
			select 
			t.item_id,
			t.item_name,
			t.item_description,
			t.short_code,
			t.food_time,
			t.breakfast_flag,
			t.lunch_flag,
			t.dinner_flag
			from 
			(
				select 
					branch.branch_id,
					categories.category_id,
					items.item_id,
					items.item_name,
					items.item_description,
					items.short_code,
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag, 
					coalesce(branch_items.lunch_flag,'N') as lunch_flag, 
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,
					(
						case
							when '".$this->currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$this->currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$this->currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time

				from inv_item_branch_assign as branch_items

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
				left join org_organizations as organization on organization.organization_id = branch.organization_id	
				where
				1=1
				and (
					items.item_name like coalesce($keywords,items.item_name) or 
					items.short_code like coalesce($keywords,items.short_code) or 
					items.item_description like coalesce($keywords,items.item_description)
				)
				and (
					branch_items.branch_id = '".$branch_id."' 
					or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$admin_user_id." = 1)
				)
				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				and branch.active_flag = 'Y' 
				group by branch_id,category_id,item_id 
				order by items.item_description asc
			) t
			HAVING ( 
				breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
				lunch_flag = if (food_time = 'Lunch', 'Y','') or
				dinner_flag = if (food_time = 'Dinner', 'Y','') 
			)
		";

		/* $itemQuery = "
			select 
			t.item_id,
			t.item_name,
			t.item_description,
			t.short_code,
			t.food_time,
			t.breakfast_flag,
			t.lunch_flag,
			t.dinner_flag
			from 
			(
				select 
					branch.branch_id,
					categories.category_id,
					items.item_id,
					items.item_name,
					items.item_description,
					items.short_code,
					coalesce(branch_items.breakfast_flag,'N') as breakfast_flag, 
					coalesce(branch_items.lunch_flag,'N') as lunch_flag, 
					coalesce(branch_items.dinner_flag,'N') as dinner_flag,
					(
						case
							when '".$this->currentTime."' between branch.break_fast_from AND branch.break_fast_to then 'BreakFast'
							when '".$this->currentTime."' between branch.lunch_from AND branch.lunch_to then 'Lunch'
							when '".$this->currentTime."' between branch.dinner_from AND branch.dinner_to then 'Dinner'
							else ''
						end 
					) food_time

				from inv_item_branch_assign as branch_items

				left join inv_sys_items as items on items.item_id = branch_items.item_id
				
				left join inv_categories as categories on categories.category_id = items.category_id
				left join branch on branch.branch_id = branch_items.branch_id
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
					items.item_name like coalesce($keywords,items.item_name) or 
					items.short_code like coalesce($keywords,items.short_code) or 
					items.item_description like coalesce($keywords,items.item_description)
				)
				
				and (
					branch_items.branch_id = '".$branch_id."' 
					or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$admin_user_id." = 1)
				)

				and branch_items.active_flag = 'Y' 
				and items.active_flag = 'Y' 
				and categories.active_flag = 'Y'
				
				and branch.active_flag = 'Y' 
				
				group by branch_id,category_id,item_id 
				order by items.item_description asc
			) t
			
			HAVING ( 
				breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
				lunch_flag = if (food_time = 'Lunch', 'Y','') or
				dinner_flag = if (food_time = 'Dinner', 'Y','') 
			)
		"; */
				
		#and branch_items.branch_id = coalesce($branch_id,branch_items.branch_id)
		#and branch.default_branch = 'Y'
		#having coalesce(t.food_time,'') != ''

		$result = $this->db->query($itemQuery)->result_array();

		return $result;
	}

	

	function getItems($main_category_code,$branch_id,$admin_user_id)
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
				or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$admin_user_id." = 1)
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

		return $result;
	}

	function getCartItems($organization_id,$branch_id,$customer_id,$waiter_id,$table_id)
	{
		if( $waiter_id != NULL && $table_id != NULL  )
		{
			$condition = "where 
			organization_id ='".$organization_id."'
			and	branch_id ='".$branch_id."'
			
			and	table_id ='".$table_id."'
			and	cart_status IS NULL
			";

			//and	waiter_id ='".$waiter_id."'
		}
		else
		{
			$condition = "where organization_id ='".$organization_id."'
			and	branch_id ='".$branch_id."'
			and	customer_id ='".$customer_id."' 
			and	cart_status IS NULL";
		}

		$cartItemsQry = "select 
		line_tbl.*,
		line_tbl.item_id as product_id,
		(line_tbl.price * line_tbl.quantity) as line_total,
		item.item_name,
		item.item_description

		from ord_cart_items as line_tbl

		left join inv_sys_items as item on
		item.item_id = line_tbl.item_id

		$condition";
	
		$result = $this->db->query($cartItemsQry)->result_array();

		return $result;
	}

	function getManageOrders($branch_id,$customer_id)
	{
		$query = "select 
		header_tbl.header_id,
		header_tbl.order_number,
		header_tbl.ordered_date,
		header_tbl.order_status,
		header_tbl.notification_read_status,
		header_tbl.payment_method,
		branch.branch_name,
		payment_type.payment_type,
		customer.customer_name,
		customer.mobile_number,
		country.country_code,
		customer_address.address_name,
		customer_address.address1,
		customer_address.address2,
		customer_address.address3,
		customer_address.land_mark,
		customer_address.address_type,
		customer_address.postal_code,
		line_tbl.cancel_status,
		sum(line_tbl.price) as price,
		sum(line_tbl.price * line_tbl.quantity) as bill_amount,

		round( sum((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount, 
		round( sum((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal, 
		round( sum( ((line_tbl.quantity * line_tbl.price) - ((coalesce(offer_percentage,0) / 100) * (line_tbl.quantity * line_tbl.price))) * (coalesce(tax_percentage,0) /100)),2) as tax_value

		from ord_order_headers as header_tbl

		left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id

		left join per_user on per_user.user_id = header_tbl.customer_id
		left join 	cus_consumers as customer on customer.customer_id = per_user.reference_id

		left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
		left join branch on branch.branch_id = header_tbl.branch_id

		left join cus_customer_address as customer_address on 
		customer_address.customer_address_id = header_tbl.address_id

		left join geo_countries as country on 
		country.country_id = customer.country_id

		WHERE 1=1 
		and header_tbl.customer_id = '".$customer_id."'
		group by line_tbl.header_id
		order by header_tbl.header_id desc		
		";

		$result = $this->db->query($query)->result_array();
		
		return $result;
	}

	function getTables($branch_id,$waiter_id)
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
			and user.user_id = '".$waiter_id."' 
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

	function getOrderTables($branch_id,$waiter_id)
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

		/* left join din_table_waiters on din_table_waiters.table_line_id = din_table_lines.line_id
		left join per_people_all as emp on emp.person_id = din_table_waiters.user_id
		left join per_user as user on user.person_id = emp.person_id */
		
		$result = $this->db->query($getTablesQry)->result_array();
		return $result;
	}

	function getSelectedTables($branch_id,$waiter_id,$table_id)
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
			and user.user_id = '".$waiter_id."' 
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
				and din_table_lines.line_id = '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'"; 
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
				and din_table_lines.line_id = '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'
			"; 
		}

		$result = $this->db->query($getTablesQry)->result_array();
		return $result;
	}

	function getShiftTables($branch_id,$waiter_id,$table_id)
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
			and user.user_id = '".$waiter_id."' 
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
				and din_table_lines.line_id != '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'"; 
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
				and din_table_lines.line_id != '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'
			"; 
		}

		$result = $this->db->query($getTablesQry)->result_array();
		return $result;
	}

	function getMergeTables($branch_id="",$waiter_id="",$table_id="")
	{
		/* $checkQry ="
			select din_table_lines.line_id
			from din_table_lines
			left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id

			left join din_table_waiters on din_table_waiters.table_line_id = din_table_lines.line_id

			left join per_people_all as emp on emp.person_id = din_table_waiters.user_id

			left join per_user as user on user.person_id = emp.person_id

			where 1=1 
			and din_table_lines.active_flag = 'Y'
			and user.user_id = '".$waiter_id."' 
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
				and din_table_lines.line_id != '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'"; 
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
				and din_table_lines.line_id != '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'
			"; 
		} */

		$getTablesQry ="
				select 
				din_table_lines.table_name,
				din_table_headers.header_id,
				din_table_lines.line_id,
				din_table_lines.table_code,
				din_table_lines.table_no_of_persons
				from ord_order_interface_headers as header_tbl
				left join din_table_lines on din_table_lines.line_id = header_tbl.table_id

				left join din_table_headers on din_table_headers.header_id = din_table_lines.header_id
				where 1=1 
				and din_table_lines.active_flag = 'Y'
				and din_table_lines.line_id != '".$table_id."' 
				and din_table_headers.branch_id = '".$branch_id."'
				group by header_tbl.table_id
			"; 
		
		$result = $this->db->query($getTablesQry)->result_array();
		return $result;
	}
}
