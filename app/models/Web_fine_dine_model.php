<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Web_fine_dine_model extends CI_Model 
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
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	function getPosItemSearch($keywords,$branch_id,$admin_user_id)
	{
		$output = '';
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
			dinner_flag
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
					branch_items.dine_in_price as item_price,
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
					) food_time

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
					items.item_name like coalesce($keywords,items.item_name) or 
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
			and	waiter_id ='".$waiter_id."'
			and	table_id ='".$table_id."'";
		}
		else
		{
			$condition = "where organization_id ='".$organization_id."'
			and	branch_id ='".$branch_id."'
			and	customer_id ='".$customer_id."'";
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

	function getOrders()
	{	
		$condition ="1=1 
		and header_tbl.order_status not in('Closed','Cancelled')
		and header_tbl.cancel_status='N'
		and header_tbl.order_source NOT IN('POS','DINE_IN','HOME_DELIVERY')";
		
		$query = "select 
		header_tbl.header_id,
		header_tbl.paid_status,
		header_tbl.cancel_status,
		header_tbl.order_number,
		header_tbl.ordered_date,
		header_tbl.order_status,
		header_tbl.payment_method,
		header_tbl.payment_type as header_payment_type,
		header_tbl.cancel_status,
		header_tbl.paid_status,
		header_tbl.card_number,
		header_tbl.cancel_date,
		header_tbl.order_type,
		header_tbl.order_source,
		header_tbl.attribute_1,

		pos_customer.customer_name as pos_customer_name,
		pos_customer.mobile_number as pos_mobile_number,
		pos_customer.address1 as pos_address1,
		pos_customer.address2 as pos_address2,
		pos_customer.address3 as pos_address3,
		pos_customer.postal_code as pos_postal_code,
		CONCAT(din_tbl.table_code,coalesce(header_tbl.sub_table,'')) as table_name,
		per_people_all.first_name as waiter_name,

		header_tbl.waiter_id,
		header_tbl.accepted_date,
		header_tbl.preparing_date,
		header_tbl.out_for_delivery_date,
		header_tbl.delivered_date,


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
		sum(line_tbl.price) as price,
		sum(line_tbl.price * line_tbl.quantity) as bill_amount,

		round( sum((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
		round( sum((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
		round( sum( ((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))) * (tax_percentage/100)),2) as tax_value
		
		from ord_order_headers as header_tbl

		left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id
		
		left join per_user on per_user.user_id = header_tbl.customer_id
		left join cus_consumers as customer on customer.customer_id = per_user.reference_id

		left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
		left join branch on branch.branch_id = header_tbl.branch_id
		left join cus_customer_address as customer_address on 
		customer_address.customer_address_id = header_tbl.address_id

		left join geo_countries as country on 
		country.country_id = customer.country_id

		left join cus_consumers as pos_customer on pos_customer.customer_id = header_tbl.customer_id
		left join din_table_lines as din_tbl on din_tbl.line_id = header_tbl.table_id
		left join per_user as waiter on waiter.user_id = header_tbl.waiter_id
		left join per_people_all on per_people_all.person_id = waiter.person_id

		WHERE $condition 
		and line_tbl.cancel_status = 'N'
		and line_tbl.line_status != 'Cancelled'
		group by line_tbl.header_id
		order by header_tbl.order_status asc,header_tbl.header_id asc
		";

		$result["listing"] = $this->db->query($query)->result_array();

		#Booked Query start here
		$bookedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$bookedCondition .="and header_tbl.cancel_status='N'";
		$bookedCondition .="and header_tbl.order_status = 'Booked' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		$bookedQuery = "select count(header_tbl.header_id) as bookedCount
			from ord_order_headers as header_tbl
			
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $bookedCondition";

		
		$result["bookedCount"] = $this->db->query($bookedQuery)->result_array();
		#Booked Qry End

		#Confirmed Qry End
		$confirmedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$confirmedCondition .="and header_tbl.cancel_status='N'";
		$confirmedCondition .="and header_tbl.order_status = 'Confirmed' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		$confirmedQuery = "select count(header_tbl.header_id) as confirmedCount
			from ord_order_headers as header_tbl
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $confirmedCondition";
		$result["confirmedCount"] = $this->db->query($confirmedQuery)->result_array();
		#Confirmed Qry End

		#Preparing Qry End
		$preparingCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$preparingCondition .="and header_tbl.cancel_status='N'";
		$preparingCondition .="and header_tbl.order_status = 'Preparing' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";
		
		$preparingQuery = "select count(header_tbl.header_id) as preparingCount
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $preparingCondition";
		$result["preparingCount"] = $this->db->query($preparingQuery)->result_array();
		#Preparing Qry End

		#Shipped Qry End
		$shippedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$shippedCondition .="and header_tbl.cancel_status='N'";
		$shippedCondition .="and header_tbl.order_status = 'Shipped' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		
		$shippedQuery = "select count(header_tbl.header_id) as shippedCount
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $shippedCondition";
		$result["shippedCount"] = $this->db->query($shippedQuery)->result_array();
		#Shipped Qry End

		#Delivered Qry End
		$deliveredCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$deliveredCondition .="and header_tbl.cancel_status='N'";
		$deliveredCondition .="and header_tbl.order_status = 'Delivered' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		
		$deliveredQuery = "select count(header_tbl.header_id) as deliveredCount
			from ord_order_headers as header_tbl
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $deliveredCondition";
		$result["deliveredCount"] = $this->db->query($deliveredQuery)->result_array();
		#Delivered Qry End
		
		/* #$limit
		#print_r($query);exit;
		$result["totalCount"] = $result["listing"];
		
		#All Order Qry start
		$totalOrdersCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$totalOrdersCondition .="and header_tbl.cancel_status='N' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";
		
		$totalOrdersQuery = "select count(header_tbl.header_id) as totalOrdersCount
			from ord_order_headers as header_tbl
			
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $totalOrdersCondition";
		$result["totalOrdersCount"] = $this->db->query($totalOrdersQuery)->result_array(); */
		#All Order Qry End

		return $result;
	}


	function getFilterCardOrders($order_status="")
	{	
		$condition ="1=1 
		and header_tbl.order_status not in('Closed','Cancelled')
		and header_tbl.cancel_status='N'
		and header_tbl.order_status='".$order_status."'
		and header_tbl.order_source NOT IN('POS','DINE_IN','HOME_DELIVERY')";
		
		$query = "select 
		header_tbl.header_id,
		header_tbl.paid_status,
		header_tbl.cancel_status,
		header_tbl.order_number,
		header_tbl.ordered_date,
		header_tbl.order_status,
		header_tbl.payment_method,
		header_tbl.payment_type as header_payment_type,
		header_tbl.cancel_status,
		header_tbl.paid_status,
		header_tbl.card_number,
		header_tbl.cancel_date,
		header_tbl.order_type,
		header_tbl.order_source,
		header_tbl.attribute_1,

		pos_customer.customer_name as pos_customer_name,
		pos_customer.mobile_number as pos_mobile_number,
		pos_customer.address1 as pos_address1,
		pos_customer.address2 as pos_address2,
		pos_customer.address3 as pos_address3,
		pos_customer.postal_code as pos_postal_code,
		CONCAT(din_tbl.table_code,coalesce(header_tbl.sub_table,'')) as table_name,
		per_people_all.first_name as waiter_name,

		header_tbl.waiter_id,
		header_tbl.accepted_date,
		header_tbl.preparing_date,
		header_tbl.out_for_delivery_date,
		header_tbl.delivered_date,


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
		sum(line_tbl.price) as price,
		sum(line_tbl.price * line_tbl.quantity) as bill_amount,

		round( sum((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price)),2) as offer_amount,
		round( sum((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))),2) as linetotal,
		round( sum( ((line_tbl.quantity * line_tbl.price) - ((line_tbl.offer_percentage / 100) * (line_tbl.quantity * line_tbl.price))) * (tax_percentage/100)),2) as tax_value
		
		from ord_order_headers as header_tbl

		left join ord_order_lines as line_tbl on line_tbl.header_id = header_tbl.header_id
		
		left join per_user on per_user.user_id = header_tbl.customer_id
		left join cus_consumers as customer on customer.customer_id = per_user.reference_id

		left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
		left join branch on branch.branch_id = header_tbl.branch_id
		left join cus_customer_address as customer_address on 
		customer_address.customer_address_id = header_tbl.address_id

		left join geo_countries as country on 
		country.country_id = customer.country_id

		left join cus_consumers as pos_customer on pos_customer.customer_id = header_tbl.customer_id
		left join din_table_lines as din_tbl on din_tbl.line_id = header_tbl.table_id
		left join per_user as waiter on waiter.user_id = header_tbl.waiter_id
		left join per_people_all on per_people_all.person_id = waiter.person_id

		WHERE $condition 
		and line_tbl.cancel_status = 'N'
		and line_tbl.line_status != 'Cancelled'
		group by line_tbl.header_id
		order by header_tbl.order_status asc,header_tbl.header_id asc
		";
		
		$result["listing"] = $this->db->query($query)->result_array();


		#Booked Query start here
		$bookedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$bookedCondition .="and header_tbl.cancel_status='N'";
		$bookedCondition .="and header_tbl.order_status = 'Booked' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		$bookedQuery = "select count(header_tbl.header_id) as bookedCount
			from ord_order_headers as header_tbl
			
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $bookedCondition";

		
		$result["bookedCount"] = $this->db->query($bookedQuery)->result_array();
		#Booked Qry End

		#Confirmed Qry End
		$confirmedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$confirmedCondition .="and header_tbl.cancel_status='N'";
		$confirmedCondition .="and header_tbl.order_status = 'Confirmed' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		$confirmedQuery = "select count(header_tbl.header_id) as confirmedCount
			from ord_order_headers as header_tbl
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $confirmedCondition";
		$result["confirmedCount"] = $this->db->query($confirmedQuery)->result_array();
		#Confirmed Qry End

		#Preparing Qry End
		$preparingCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$preparingCondition .="and header_tbl.cancel_status='N'";
		$preparingCondition .="and header_tbl.order_status = 'Preparing' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";
		
		$preparingQuery = "select count(header_tbl.header_id) as preparingCount
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $preparingCondition";
		$result["preparingCount"] = $this->db->query($preparingQuery)->result_array();
		#Preparing Qry End

		#Shipped Qry End
		$shippedCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$shippedCondition .="and header_tbl.cancel_status='N'";
		$shippedCondition .="and header_tbl.order_status = 'Shipped' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$fromDate = date("Y-m-d",strtotime($_GET['from_date']));
			$toDate = date("Y-m-d",strtotime($_GET['to_date']));
			
			$shippedCondition .= " and (STR_TO_DATE(header_tbl.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		$shippedQuery = "select count(header_tbl.header_id) as shippedCount
			from ord_order_headers as header_tbl

			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $shippedCondition";
		$result["shippedCount"] = $this->db->query($shippedQuery)->result_array();
		#Shipped Qry End

		#Delivered Qry End
		$deliveredCondition = "1=1 and header_tbl.order_status not in('Closed','Cancelled')";
		$deliveredCondition .="and header_tbl.cancel_status='N'";
		$deliveredCondition .="and header_tbl.order_status = 'Delivered' and header_tbl.order_source not in ('POS','DINE_IN','HOME_DELIVERY')";

		
		$deliveredQuery = "select count(header_tbl.header_id) as deliveredCount
			from ord_order_headers as header_tbl
			left join per_user on per_user.user_id = header_tbl.customer_id
			left join cus_consumers as customer on customer.customer_id = per_user.reference_id

			left join pay_payment_types as payment_type on payment_type.payment_type_id = header_tbl.payment_method
			left join branch on branch.branch_id = header_tbl.branch_id
			left join cus_customer_address as customer_address on customer_address.customer_address_id = header_tbl.address_id
			left join geo_countries as country on  country.country_id = customer.country_id
		where $deliveredCondition";
		$result["deliveredCount"] = $this->db->query($deliveredQuery)->result_array();
		#Delivered Qry End
		
		
		return $result;
	}

	function getPOStakeawayOrders($order_type="")
	{
		if(isset($order_type) && $order_type == "POS")
		{
			$posCon = "and header_tbl.order_source = 'POS' ";
		}else if(isset($order_type) && $order_type == "HOME_DELIVERY")
		{
			$posCon = "and header_tbl.order_source = 'HOME_DELIVERY' ";
		}
		else
		{
			$posCon = "and 1 = 1";
		}

		$checkOrders = "
		select 
		header_tbl.interface_header_id,
		header_tbl.order_number,
		header_tbl.ordered_date,
		header_tbl.order_status,
		header_tbl.interface_status,
		header_tbl.customer_id,
		customer.customer_name,
		customer.mobile_number,
		header_tbl.order_source,
		paymenttype.payment_type

		from ord_order_interface_headers as header_tbl
		
		left join per_user as user on 
		user.user_id = header_tbl.customer_id

		left join cus_consumers as customer on 
		customer.customer_id = user.reference_id

		left join pay_payment_types as paymenttype on 
		paymenttype.payment_type_id = header_tbl.payment_type

		where 1=1 $posCon
		and header_tbl.interface_status != 'Success'
		";
		
		$result = $this->db->query($checkOrders)->result_array();
		
		return $result;
	}

	function getDineInOrderItems($table_id="", $branch_id="", $order_seq_number="")
	{
		if($order_seq_number){
			$order_seq_number = "and coalesce(line_tbl.order_seq_number='".$order_seq_number."',line_tbl.order_seq_number)";
		}else{
			$order_seq_number ='and 1=1';
		}

		$dineInOrderQry = "select 
		
		header_tbl.interface_header_id,
		header_tbl.interface_status,
		line_tbl.interface_line_id as line_id,
		line_tbl.cooking_instructions,
		line_tbl.cancel_remarks,
		line_tbl.interface_line_id,
		line_tbl.quantity,
		line_tbl.created_date,
		line_tbl.cancel_status,
		line_tbl.price,
		line_tbl.product_id,
		line_tbl.product_id as item_id,
		(line_tbl.price * line_tbl.quantity) as line_total,
		(line_tbl.offer_percentage /100 * line_tbl.price * line_tbl.quantity) as offer_amount_new,
		
		item.item_name,
		item.item_description,
		tables.table_code,
		tables.table_name
		
		from ord_order_interface_lines as line_tbl 

		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as item on
		item.item_id = line_tbl.product_id

		left join din_table_lines as tables on
		tables.line_id = header_tbl.table_id

		where 1=1 
		and header_tbl.branch_id='".$branch_id."' 
		and header_tbl.table_id='".$table_id."' 
		$order_seq_number
		
		and header_tbl.order_status='Created' 
		";
		
		$result = $this->db->query($dineInOrderQry)->result_array();

		return $result;
	}

	function getPOSOrderItems($order_id="")
	{
		$dineInOrderQry = "select 
		header_tbl.*,
		line_tbl.*,
		line_tbl.product_id as item_id,
		(line_tbl.price * line_tbl.quantity) as line_total,

		(line_tbl.offer_percentage /100 * line_tbl.price * line_tbl.quantity) as offer_amount_new,
		item.item_name,
		item.item_description,
		tables.table_code,
		tables.table_name
		
		from ord_order_interface_lines as line_tbl 

		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as item on
		item.item_id = line_tbl.product_id

		left join din_table_lines as tables on
		tables.line_id = header_tbl.table_id

		where 1=1 
		and header_tbl.interface_header_id='".$order_id."' 
		";
		
		//and header_tbl.order_status='Created' 
		
		$result = $this->db->query($dineInOrderQry)->result_array();

		return $result;
	}


	function getTodayItemWiseReport()
	{	
		$currentDate = date("Y-m-d");

		$query = "
				SELECT 
				cnt.item_id, 
				cnt.branch_name, 
				cnt.item_name, 
				cnt.category_name,
				SUM(cnt.total_order_amount) total_order_amount, 
				SUM(cnt.offer_amount) offer_amount, 
				SUM(cnt.accepted_order_amount) accepted_order_amount, 
				SUM(cnt.item_count) item_count
				FROM
				(
					SELECT inv_sys_items.item_id, ohv.branch_name, olv.item_name,

					(SELECT c1.category_name FROM inv_categories AS c1, inv_sys_items AS i1 WHERE c1.category_id = i1.category_id AND i1.item_id = olv.product_id) AS category_name, 

					(SELECT SUM(ROUND(COALESCE(olv1.order_amount, 0))) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id  
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id) 
					AS total_order_amount, 


					(SELECT SUM(ROUND(COALESCE(olv1.offer_amount,0))) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v AS olv1 
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id) 
					AS offer_amount, 


					(SELECT SUM(COALESCE(olv1.order_amount, 0) - (coalesce(olv1.offer_amount,0))) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id 
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id) 
					AS accepted_order_amount,


					(SELECT SUM(COALESCE(olv1.quantity, 0)) 
					FROM ord_order_headers_v ohv1, ord_order_lines_v olv1
					WHERE 
					ohv1.header_id = olv1.header_id 
					AND olv1.product_id = inv_sys_items.item_id
					AND olv1.cancel_status = 'N' 
					AND olv1.line_id = olv.line_id) 
					AS item_count 

					FROM ord_order_lines_v olv, 
					ord_order_headers_v ohv, 
					inv_sys_items, 
					inv_categories

					WHERE 1=1
					AND NOT ohv.order_status = 'Cancelled'
					AND ohv.header_id = olv.header_id
					AND inv_categories.category_id = inv_sys_items.category_id
					AND inv_sys_items.item_id = olv.product_id
					AND DATE_FORMAT(ohv.ordered_date, '%Y-%m-%d')= '".$currentDate."'

				) cnt

				GROUP BY
				cnt.item_id,
				cnt.branch_name
				ORDER BY
				cnt.branch_name ASC,
				cnt.item_id ASC

		";

		$result["listing"] = $this->db->query($query)->result_array();

		return $result;
	}

	function getDineInSeqOrder($table_id="", $branch_id="")
	{
		$dineInOrderQry = "select 
        header_tbl.branch_id,
        header_tbl.table_id,
        tables.table_code,
		header_tbl.interface_header_id,
		header_tbl.order_number,
		line_tbl.interface_line_id,
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
		and header_tbl.order_status='Created'
        group by line_tbl.order_seq_number
        order by line_tbl.order_seq_number asc
		";
		$result = $this->db->query($dineInOrderQry)->result_array();

		return $result;
	}


	function getCapDineInSeqOrder($table_id="", $branch_id="")
	{
		$dineInOrderQry = "select 
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
		
		$result = $this->db->query($dineInOrderQry)->result_array();

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


	function getCaptainRoleDetails($branch_id="",$role_id="")
	{
		$roleQry = "select 
		role_name,
		role_code

		from org_roles as role
		where 1=1
		and role.role_id='".$role_id."'
		and role.branch_id='".$branch_id."'
		and role.active_flag ='Y'
		";
		$roleDetails = $this->db->query($roleQry)->result_array();

		return $roleDetails;
	}

	

	
}
