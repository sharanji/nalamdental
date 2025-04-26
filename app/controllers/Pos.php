<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Pos extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
      
        #Cache Control
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
	}
	
	#POS Start here
	function posOrder($order_type="", $interface_status="", $interface_header_id="")
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}

		$_SESSION["MODULE_ACCESS"] = 'pos_dashboard';

		if(isset($_POST['pos_dine_in_type'])) {
			$_SESSION['pos_dine_in_type'] = $_POST['pos_dine_in_type'];
		}
		else if(isset($order_type) && !empty($order_type) ) {
			$_SESSION['pos_dine_in_type'] = strtoupper($order_type);
		}else{
			$_SESSION['pos_dine_in_type'] = NULL;
		}

		$page_data['order_type'] = !empty($order_type) ? $order_type : NULL;
		
		$page_data['interface_status'] = !empty($interface_status) ? $interface_status : NULL;
		$page_data['interface_header_id'] = !empty($interface_header_id) ? $interface_header_id : NULL;

		$page_data['pos'] = 1;
		$page_data['page_name']  = 'pos/posOrder';
		$page_data['page_title'] = 'POS Order';

		#POS list start 
		if( $interface_header_id )
		{
			$dineInOrderQry = "select 
			line_tbl.*,
			(line_tbl.price * line_tbl.quantity) as line_total,
			item.item_name,
			item.item_description
			
			from ord_order_interface_lines as line_tbl 

			left join ord_order_interface_headers as header_tbl on
			header_tbl.interface_header_id = line_tbl.reference_header_id

			left join inv_sys_items as item on
			item.item_id = line_tbl.product_id

			where 1=1 
			and header_tbl.interface_header_id='".$interface_header_id."' ";
			$page_data['table_data'] = $page_data['dineInOrders'] = $dineInOrders = $this->db->query($dineInOrderQry)->result_array();
		}
		#POS list end

		$page_data["item_list"] = $this->pos_model->getPosItems();
		$this->load->view($this->adminTemplate, $page_data);
	}

	public function getPOLineDatas($item_id='')
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
				and items.item_id ='".$item_id."'

				and (
					branch_items.branch_id = '".$branch_id."' 
					or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
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

		//and branch.default_branch = 'Y' 
		#having coalesce(t.food_time,'') != ''

		$data['items'] = $this->db->query($itemQuery)->result_array();
	    echo json_encode($data);
		exit;
	}

	function posItemSearch()
    {
		if(isset($_POST["query"]))  
		{  
			if($this->branch_id){
				$branch_id = $this->branch_id;
				//$branch_id = 1;
			}else{
				$branch_id = 'NULL';
			}

			$output = '';  
			
			$keywords = "concat('%','".serchFilter($_POST["query"])."','%')";

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
						or (branch_items.branch_id = (select a.branch_id from branch a where a.default_branch = 'Y') and ".$this->user_id." = 1)
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
			
			$output = '<ul class="list-unstyled-pos">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$item_id = $row["item_id"];
					$item_description = ucfirst($row["item_name"]);
					
					#$output .= '<li onclick="return getappointmentuserId(\'' .$patinetID. '\',\'' .$phone_number. '\',\'' .$email. '\',\'' .$customer_name. '\',\'' .$random_user_id. '\');">'.$row["phone_number"].'</li>';  
					$output .= '<li onclick="return selectSearchPosItems(\'' .$item_id. '\');">'.$item_description.'</li>';  
				}  
			}  
			else  
			{  
				$item_id = NULL;
				$output .= '<li onclick="return selectSearchPosItems(\'' .$item_id. '\');">No Items</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	function getAjaxSubCategories()
    {
		$category_code = isset($_POST["category_code"]) ? $_POST["category_code"] : NULL;
		$category_id = isset($_POST["category_id"]) ? $_POST["category_id"] : NULL;

		if($category_id != NULL)
		{
			$query = "select distinct ltv.list_code,ltv.list_value,ltv.list_type_value_id,
			(select category_id from inv_categories where cat_level_2 in
			(select list_type_value_id from sm_list_type_values where list_type_value_id = ltv.list_type_value_id) limit 1) category_id
			from sm_list_type_values ltv, inv_categories ics, inv_sys_items iss
			where 1 = 1
			and ltv.list_type_value_id = ics.cat_level_2
			and ics.category_id = iss.category_id
            
			and ics.cat_level_1 = coalesce(if('".$category_id."' = 'All',ics.cat_level_1,'".$category_id."'),'All')
						
			and ics.active_flag='Y'";

			#and ics.cat_level_1 = '".$category_id."'
			#echo $query;
			$sub_category = $this->db->query($query)->result_array();

			if(count($sub_category) > 0)
			{
				$page_data['sub_category'] = $sub_category;
                $pos_sub_categories = $this->load->view("backend/pos/posSubCategories.php", $page_data, true);
			}
			else
			{
				$pos_sub_categories = "";
			}

			echo $pos_sub_categories;
		}
		exit;
	}

	function getAjaxCategoryItems()
    {
		$main_category_code = isset($_POST["main_category_code"]) ? $_POST["main_category_code"] : NULL;
		$main_category_id = isset($_POST["main_category_id"]) ? $_POST["main_category_id"] : NULL;

		$sub_category_code = isset($_POST["sub_category_code"]) ? $_POST["sub_category_code"] : NULL;
		$sub_category_id = isset($_POST["sub_category_id"]) ? $_POST["sub_category_id"] : NULL;

		if($this->branch_id){
			$branch_id = $this->branch_id;
			//$branch_id = 1;
		}else{
			$branch_id = 'NULL';
		}

		if($main_category_code != NULL)
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


						branch_items.item_price,
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
						and cat2.list_code = coalesce(if('".$sub_category_code."' = 'All',cat2.list_code,'".$sub_category_code."'),'All')
						
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
					)";

					#and branch_items.branch_id = coalesce($branch_id,branch_items.branch_id)
					//and branch.default_branch = 'Y' 

					#and (cat1.list_code = '".$categoryCode."' || cat2.list_code = '".$categoryCode."')
					/* 	HAVING (
						branch_items.breakfast_flag = if (food_time = 'BreakFast', 'Y','') or
						branch_items.lunch_flag = if (food_time = 'Lunch', 'Y','') or
						branch_items.dinner_flag = if (food_time = 'Dinner', 'Y','') 
					) */
		
			//echo $query;
			$categoryItems = $this->db->query($query)->result_array();

			//and cat1.list_code = '".$main_category_code."'
			/* if(count($categoryItems) > 0)
			{
				$page_data['item_list'] = $categoryItems;
				$category_Items = $this->load->view("backend/pos/posItems.php", $page_data, true);
			}
			else
			{
				$category_Items = "";
			}
 			*/
			$page_data['item_list'] = $categoryItems;
			$category_Items = $this->load->view("backend/pos/posItems.php", $page_data, true);

			echo $category_Items;
		}exit;
	}

	function insertPosOrderItems($type="",$button_type="",$direct_save_print="")
	{
		if(isset($_POST["dine_in_interface_header_id"]) && !empty($_POST["dine_in_interface_header_id"])){
			$interface_header_id = isset($_POST["dine_in_interface_header_id"]) ? $_POST["dine_in_interface_header_id"] : NULL;
		}
		else if(isset($_POST["interface_header_id"]) && !empty($_POST["interface_header_id"])){
			$interface_header_id = isset($_POST["interface_header_id"]) ? $_POST["interface_header_id"] : NULL;
		}else{
			$interface_header_id = NULL;
		}

		/* if(!empty($button_type) && $button_type == 'SAVE_PRINT')
		{
			$this->updateSavePrint($interface_header_id);
		}
 		*/

		if($this->user_id == 1)
		{
			$getBranch = $this->db->query("select branch_id from branch where default_branch='Y' and active_flag='Y' ")->result_array();
			$branch_id = isset($getBranch[0]["branch_id"]) ? $getBranch[0]["branch_id"] : NULL;
		}
		else
		{
			$branch_id = !empty($this->branch_id) ? $this->branch_id : 1;
		}
		
		if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "TAKEAWAY") #POS
		{
			$documentNumberCondition = "and dm.doc_document_type = 'pos-orders'";
		}
		else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "HOME_DELIVERY") #HOME_DELIVERY
		{
			$documentNumberCondition = "and dm.doc_document_type = 'home-delivery-orders'";
		}
		else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "DINE_IN") #POS
		{
			$documentNumberCondition = "and dm.doc_document_type = 'dine-in-orders'";
		}

		$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
		from doc_document_numbering as dm
		left join sm_list_type_values ltv on 
		ltv.list_type_value_id = dm.doc_type
		where 
		1=1
		$documentNumberCondition
		and dm.branch_id = '".$branch_id."'
		and ltv.list_code = 'CUS_ORD' 
		and dm.active_flag = 'Y'
		and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
		and coalesce(dm.to_date,CURDATE()) >= CURDATE()
		";

		$getDocumentData = $this->db->query($documentQry)->result_array();
		
		$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
		$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
		$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
		$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

		#if( (($type == 'order_interface_tbl') && (!empty($button_type) && $button_type != 'SAVE_PRINT')) || ($direct_save_print == "direct_save_print") )
		if( ( ($type == 'order_interface_tbl') && (!empty($button_type)) ) || ($direct_save_print == "direct_save_print") )
		{	
			if($_POST)
			{
				$orgQry = "select organization_id from branch where branch_id='".$branch_id."' ";
				$getOrganization = $this->db->query($orgQry)->result_array();
				$organization_id = isset($getOrganization[0]['organization_id']) ? $getOrganization[0]['organization_id'] : NULL;
				
				if(count($getDocumentData) > 0)
				{
					#Update Next Val DOC Number tbl start
					$nextValue = $startingNumber + 1;
					$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
					
					$UpdateData['next_number'] = $nextValue;
					$this->db->where('doc_num_id', $doc_num_id);
					$this->db->where('branch_id', $branch_id);
					$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
					#Update Next Val DOC Number tbl end

					if( isset($_POST['payment_method']) && $_POST['payment_method'] == 5) //Cash
					{
						$payment_type = 'Cash';
						$paid_status = 'Y';	
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 6) //Card
					{
						$payment_type = 'Card';
						$paid_status = 'Y';
						
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 7) //UPI
					{
						$payment_type = 'UPI';
						$paid_status = 'Y';
					}

					$payment_transaction_status = NULL;
					
					$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : NULL;
					$sub_table = !empty($_POST['sub_table']) ? $_POST['sub_table'] : NULL;

					$_SESSION['pos_dine_in_type'] = isset($_POST['pos_dine_in_type']) ? $_POST['pos_dine_in_type'] : NULL;

					if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "TAKEAWAY") #POS
					{
						$order_source = 'POS';
						$waiter_id = NULL;
						$table_id  = NULL;	
						$order_type = 1; #Take Away

						#$interface_status = NULL;
						#$order_status = "Delivered";

						if($button_type == "SAVE" && $direct_save_print == "")
						{
							$order_status = $interface_status = "Created";
						}
						#else if($button_type == "SAVE" &&  $direct_save_print == "direct_save_print")
						else if($button_type == "SAVE_PRINT" ||  $direct_save_print == "direct_save_print")
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = NULL;
						}
					}
					else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "HOME_DELIVERY") #HOME_DELIVERY
					{
						$order_source = 'HOME_DELIVERY';
						$waiter_id = NULL;
						$table_id  = NULL;	
						$order_type = 1; #Take Away

						#$interface_status = NULL;
						#$order_status = "Delivered";

						if($button_type == "SAVE" && $direct_save_print == "")
						{
							$order_status = $interface_status = "Created";
						}
						#else if($button_type == "SAVE" &&  $direct_save_print == "direct_save_print")
						else if($button_type == "SAVE_PRINT" ||  $direct_save_print == "direct_save_print")
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = NULL;
						}
					}
					else if( isset($_POST['pos_dine_in_type']) && $_POST['pos_dine_in_type'] == "DINE_IN") #DINE_IN
					{					
						$order_source = 'DINE_IN';
						$waiter_id = $this->user_id;
						$table_id  = isset($_POST['table_id']) ? $_POST['table_id'] : NULL;
						$order_type = NULL; 

						if($button_type == "SAVE" && $direct_save_print == "")
						{
							$order_status = $interface_status = "Created";
						}
						//else if($button_type == "SAVE" &&  $direct_save_print == "direct_save_print")
						else if($button_type == "SAVE_PRINT" ||  $direct_save_print == "direct_save_print")
						{
							$order_status = $interface_status = "Printed";
						}
						else
						{
							$order_status = $interface_status = NULL;
						}
					}
					else
					{
						$order_source = NULL;
						$waiter_id = NULL;
						$table_id  = NULL;
						$order_status = $interface_status = NULL;
					}

					if($interface_header_id !=NULL && $sub_table !=NULL)
					{
						$interfaceTblQry = "select interface_header_id,order_number from ord_order_interface_headers 
						where 1=1
						and interface_header_id ='".$interface_header_id."'
						and sub_table ='".$sub_table."'
						";
						$checkInterfaceTbl = $this->db->query($interfaceTblQry)->result_array();
					}
					else
					{
						$interfaceTblQry = "select interface_header_id,order_number from ord_order_interface_headers 
						where 1=1
						and interface_header_id ='".$interface_header_id."'";
						$checkInterfaceTbl = $this->db->query($interfaceTblQry)->result_array();
					}
					
					if( count($checkInterfaceTbl) == 0 ) #Insert
					{
						if(
							isset($_POST["pos_dine_in_type"]) 
							&& $sub_table == NULL
							&& (
								$_POST["pos_dine_in_type"] == "DINE_IN" 
								|| $_POST["pos_dine_in_type"] == "POS" 
								|| $_POST["pos_dine_in_type"] == "HOME_DELIVERY"
							)
						)
						{
							$dine_in_interface_header_id = isset($_POST["dine_in_interface_header_id"]) ? $_POST["dine_in_interface_header_id"] : NULL;

							$checkInterFaceHeaderQry = "select interface_header_id,order_number from ord_order_interface_headers 
							where 1=1
							and interface_header_id ='".$dine_in_interface_header_id."'";
							$checkInterfaceTbl1 = $this->db->query($checkInterFaceHeaderQry)->result_array();

							#$this->db->where('reference_header_id', $dine_in_interface_header_id);
							#$this->db->delete('ord_order_interface_lines');
						}
						else
						{
							$checkInterfaceTbl1 = array();
						}

						$headerData= array(
							'order_number'                  => $documentNumber,
							'customer_id'                   => isset($_POST["new_customer_id"]) ? $_POST["new_customer_id"] : '-1',
							'address_id'                    => '-1',
							'ordered_date'                  => $this->date_time,
							'organization_id'               => $organization_id,
							'branch_id'                     => $branch_id, 
							'order_status'                  => $order_status,
							'order_type'                    => $order_type, #1=> TAKE AWAY, 2=>Delivery
							'payment_method'                => $payment_method,
							'delivery_instructions'         => NULL,
							'packing_instructions'          => NULL,
							'payment_type'                  => isset($payment_type) ? $payment_type : NULL,
							'card_number'                   => NULL,
							'payment_transaction_ref_1'     => NULL,
							'payment_transaction_status'    => NULL,
							'currency'                      => CURRENCY_CODE,
							'delivery_options'              => NULL,
							'paid_status'                   => isset($paid_status) ? $paid_status : 'N',
							'order_source'                  => $order_source,
							'waiter_id'                     => $waiter_id,
							'table_id'                      => $table_id,
							'interface_status'              => $interface_status,
							'sub_table'              		=> !empty($_POST['sub_table']) ? $_POST['sub_table'] : NULL,
							'coupon_code'                   => NULL,
							'coupon_amount'                 => NULL,
							'wallet_amount'                 => NULL,

							'created_by'                    => $this->user_id,
							'created_date'                  => $this->date_time,
							'last_updated_by'               => $this->user_id,
							'last_updated_date'             => $this->date_time,
						);
						
						if(count($checkInterfaceTbl1) > 0)
						{
							$updateData = array(
								'created_by'                    => $this->user_id,
								'created_date'                  => $this->date_time,
								'last_updated_by'               => $this->user_id,
								'last_updated_date'             => $this->date_time,
								'order_status'                  => $order_status,
							);
							$this->db->where('interface_header_id', $dine_in_interface_header_id);
							$update_result = $this->db->update('ord_order_interface_headers', $updateData);
							$this->db->where('reference_header_id', $dine_in_interface_header_id);
							$this->db->delete('ord_order_interface_lines');

							$interface_header_id = $dine_in_interface_header_id;
						}
						else
						{
							$this->db->insert('ord_order_interface_headers',$headerData);
							$interface_header_id = $this->db->insert_id();
						}

						if($interface_header_id)
						{
							$count = count(array_filter($_POST['text_item_id']));

							for($dp=0;$dp<$count;$dp++)
							{
								$product_id = isset($_POST['text_item_id'][$dp]) ? $_POST['text_item_id'][$dp]:NULL;
								$price = isset($_POST['rate'][$dp]) ? $_POST['rate'][$dp]:NULL;
								$quantity = isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp]:NULL;

								$discountType = isset($_POST['discount_type']) ? $_POST['discount_type'] : NULL;
								$discount = isset($_POST['discount']) ? $_POST['discount'] : NULL; 

								if( ($discountType != NULL && !empty($discountType)) && ($discount != NULL && !empty($discount)) )
								{
									if($discountType == 1) #Percentage
									{
										$discount_percentage = $discount; 
										$discount_amount = NULL;
									}
									else if($discountType == 2) #Amount
									{
										$discount_percentage = NULL; 
										$discount_amount = $discount;
									}
								}
								else
								{
									$discount_percentage = NULL;
									$discount_amount = NULL;
								}

								$tax_percentage = isset($_POST['tax_value']) ? $_POST['tax_value'] : NULL;

								$lineData = array(
									'reference_header_id'=> $interface_header_id,
									'product_id'	     => $product_id,
									'price'	             => $price,
									'quantity'	         => $quantity,

									'offer_percentage'	 => $discount_percentage,
									'offer_amount'	     => $discount_amount,
									'tax_percentage'	 => $tax_percentage,
									'line_status'	 	 => $order_status,
									'created_by'         => $this->user_id,
									'created_date'       => $this->date_time,
									'last_updated_by'    => $this->user_id,
									'last_updated_date'  => $this->date_time,
								);
								$this->db->insert('ord_order_interface_lines', $lineData);
								$interface_line_id = $this->db->insert_id();
							}

							$response["pos_items"] = array(	
								"documentNumber"       => $documentNumber,
								"interface_header_id"  => $interface_header_id,
								"status"               => 1,
								"message"              => "Order Created Successfully!"
							);
						}
					}
					else if( count($checkInterfaceTbl) > 0 ) #Update
					{
						$order_number = $checkInterfaceTbl[0]["order_number"];
						$interface_header_id = $checkInterfaceTbl[0]["interface_header_id"];

						$headerData= array(
							#'order_number'                  => $documentNumber,
							'customer_id'                   => isset($_POST["new_customer_id"]) ? $_POST["new_customer_id"] : '-1',
							'address_id'                    => '-1',
							'ordered_date'                  => $this->date_time,
							'organization_id'               => $organization_id,
							'branch_id'                     => $branch_id, 
							'order_status'                  => $order_status,
							'interface_status'              => $interface_status,
							'order_type'                    => $order_type, #1=> TAKE AWAY, 2=>Delivery
							'payment_method'                => $payment_method,
							'delivery_instructions'         => NULL,
							'packing_instructions'          => NULL,
							'payment_type'                  => isset($payment_type) ? $payment_type : NULL,
							'card_number'                   => NULL,
							'payment_transaction_ref_1'     => NULL,
							'payment_transaction_status'    => NULL,
							'currency'                      => CURRENCY_CODE,
							'delivery_options'              => NULL,
							'paid_status'                   => isset($paid_status) ? $paid_status : 'N',
							
							'order_source'                  => $order_source,
							'waiter_id'                     => $waiter_id,
							'table_id'                      => $table_id,

							'coupon_code'                   => NULL,
							'coupon_amount'                 => NULL,
							'wallet_amount'                 => NULL,

							#'created_by'                    => $this->user_id,
							#'created_date'                  => $this->date_time,
							'last_updated_by'               => $this->user_id,
							'last_updated_date'             => $this->date_time,
						);


						$this->db->where('interface_header_id', $interface_header_id);
						$result = $this->db->update('ord_order_interface_headers', $headerData);

						/* $this->db->insert('ord_order_interface_headers',$headerData);
						$interface_header_id = $this->db->insert_id(); */
						
						if($result)
						{
							/* $this->db->where('reference_header_id', $interface_header_id);
							$this->db->delete('ord_order_interface_lines'); */

							$count = count(array_filter($_POST['text_item_id']));
							for($dp=0;$dp<$count;$dp++)
							{
								$interface_line_id = isset($_POST['interface_line_id'][$dp]) ? $_POST['interface_line_id'][$dp]:NULL;
								
								$previous_quantity = isset($_POST['exist_quantity'][$dp]) ? $_POST['exist_quantity'][$dp]:NULL; #Previous Qty
								$quantity = isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp]:NULL; #Current Qty

								$product_id = isset($_POST['text_item_id'][$dp]) ? $_POST['text_item_id'][$dp] : NULL;

								$lineDataQry = "select interface_line_id from ord_order_interface_lines
								where 1=1
								and reference_header_id ='".$interface_header_id."' 
								and interface_line_id ='".$interface_line_id."' 
								";
								$checkLineData = $this->db->query($lineDataQry)->result_array();

								$price = isset($_POST['rate'][$dp]) ? $_POST['rate'][$dp]:NULL;
								
								$discountType = isset($_POST['discount_type']) ? $_POST['discount_type'] : NULL;
								$discount = isset($_POST['discount']) ? $_POST['discount'] : NULL; 

								if( ($discountType != NULL && !empty($discountType)) && ($discount != NULL && !empty($discount)) )
								{
									if($discountType == 1) #Percentage
									{
										$discount_percentage = $discount; 
										$discount_amount = NULL;
									}
									else if($discountType == 2) #Amount
									{
										$discount_percentage = NULL; 
										$discount_amount = $discount;
									}
								}
								else
								{
									$discount_percentage = NULL;
									$discount_amount = NULL;
								}

								$tax_percentage = isset($_POST['tax_value']) ? $_POST['tax_value'] : NULL;

								if(count($checkLineData) > 0) #Update
								{
									if( $quantity > $previous_quantity)
									{
										$lineData = array(
											'quantity'	         => $quantity,
											'previous_quantity'	 => $previous_quantity,
											'offer_percentage'	 => $discount_percentage,
											'offer_amount'	     => $discount_amount,
											'tax_percentage'	 => $tax_percentage,
											'last_updated_by'    => $this->user_id,
											'last_updated_date'  => $this->date_time,
											'kot_print_status'   => 'N',
										);	

										$this->db->where('reference_header_id', $interface_header_id);
										$this->db->where('interface_line_id', $interface_line_id);
										$update_result = $this->db->update('ord_order_interface_lines', $lineData);
									}
									else if($quantity < $previous_quantity)
									{
										$lineData = array(
											'quantity'	         => $quantity,
											'previous_quantity'	 => $previous_quantity,
											'offer_percentage'	 => $discount_percentage,
											'offer_amount'	     => $discount_amount,
											'tax_percentage'	 => $tax_percentage,
											
											'last_updated_by'    => $this->user_id,
											'last_updated_date'  => $this->date_time,	
										);

										$this->db->where('reference_header_id', $interface_header_id);
										$this->db->where('interface_line_id', $interface_line_id);
										$update_result = $this->db->update('ord_order_interface_lines', $lineData);
									}
									else
									{
										$lineData = array(
											'offer_percentage'	 => $discount_percentage,
											'last_updated_by'    => $this->user_id,
											'last_updated_date'  => $this->date_time,	
										);

										$this->db->where('reference_header_id', $interface_header_id);
										$this->db->where('interface_line_id', $interface_line_id);
										$update_result = $this->db->update('ord_order_interface_lines', $lineData);
									}
								}
								else if( count($checkLineData) == 0 )#Insert
								{ 
									$lineData = array(
										'reference_header_id'=> $interface_header_id,
										'product_id'	     => $product_id,
										'price'	             => $price,
										'quantity'	         => $quantity,
										'attribute_1'	     => "test",

										'offer_percentage'	 => $discount_percentage,
										'offer_amount'	     => $discount_amount,
										'tax_percentage'	 => $tax_percentage,
										'line_status'	 	 => $order_status,

										'created_by'         => $this->user_id,
										'created_date'       => $this->date_time,
										'last_updated_by'    => $this->user_id,
										'last_updated_date'  => $this->date_time,
									);

									$this->db->insert('ord_order_interface_lines', $lineData);
									$interface_line_id = $this->db->insert_id();
								}
							}

							$response["pos_items"] = array(	
								"documentNumber"       => $documentNumber,
								"interface_header_id"  => $interface_header_id,
								"status"               => 1,
								"message"              => "Order Created Successfully!"
							);
						}
					}
				}
				else
				{
					$response["pos_items"] = array(	
						"status"       => 2,
						"message"      => "Order sequence does not exist, Order generation failed. Please contact to admin!"
					);
				}

				echo json_encode($response);
				exit;
			}
		}
		else if( $type == 'order_base_tbl' ) 
		{
			if( isset($_POST["interface_header_id"] ) && !empty($_POST["interface_header_id"]) )
			{
				$interface_header_id = $_POST["interface_header_id"];
				
				/* if(isset($_POST["pos_dine_in_type"]) && $_POST["pos_dine_in_type"] == "DINE_IN")
				{ */
					$updateHeaerInterFaceStatus = array(
						"interface_status" 	   => "Success",
						"order_status" 		   => "Delivered",
						'last_updated_by'      => $this->user_id,
						'last_updated_date'    => $this->date_time,
					);
					$this->db->where('interface_header_id', $interface_header_id);
					$headerResult = $this->db->update('ord_order_interface_headers', $updateHeaerInterFaceStatus);

					$updateLineInterFaceStatus = array(
						"line_status" 		   => "Delivered",
						'last_updated_by'      => $this->user_id,
						'last_updated_date'    => $this->date_time,
					);
					$this->db->where('reference_header_id', $interface_header_id);
					$lineResult = $this->db->update('ord_order_interface_lines', $updateLineInterFaceStatus);
				#}

				$InterFaceHeaderQry = "select * from ord_order_interface_headers 
					where 
						interface_header_id='".$interface_header_id."' ";
				$interFaceHeader = $this->db->query($InterFaceHeaderQry)->result_array();

				if(count($interFaceHeader) > 0)
				{
					if( isset($_POST['payment_method']) && $_POST['payment_method'] == 5) //Cash
					{
						$payment_type = 'Cash';
						$paid_status = 'Y';	
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 6) //Card
					{
						$payment_type = 'Card';
						$paid_status = 'Y';
						
					}
					else if(isset($_POST['payment_method']) && $_POST['payment_method'] == 7) //UPI
					{
						$payment_type = 'UPI';
						$paid_status = 'Y';
					}

					$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : NULL;

					if(isset($_POST['customer_id']) && $_POST['customer_id'] > 0)
					{
						$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : NULL;
					}
					else
					{
						if(isset($_POST['mobile_number']) && !empty($_POST['mobile_number']))
						{
							$customerData = array(
								"mobile_number"        => $_POST['mobile_number'],
								"customer_name"        => !empty($_POST['customer_name']) ? $_POST['customer_name'] : NULL,
								"address1"             => !empty($_POST['customer_address']) ? $_POST['customer_address'] : NULL,
								"mobile_num_verified"  => 'Y',
								'created_by'           => $this->user_id,
								'created_date'         => $this->date_time,
								'last_updated_by'      => $this->user_id,
								'last_updated_date'    => $this->date_time,
							);

							$this->db->insert('cus_consumers',$customerData);
							$customer_id = $this->db->insert_id();
						}else{
							$customer_id = NULL;
						}
					}

					$headerData = array(
						"reference_header_id"  => $interface_header_id,
						"order_number" 		   => isset($interFaceHeader[0]['order_number']) ? $interFaceHeader[0]['order_number'] : NULL, #$documentNumber,
						"customer_id" 		   => isset($customer_id) ? $customer_id : NULL,
						"address_id" 		   => isset($interFaceHeader[0]['address_id']) ? $interFaceHeader[0]['address_id'] : NULL,
						"ordered_date" 		   => isset($interFaceHeader[0]['ordered_date']) ? $interFaceHeader[0]['ordered_date'] : NULL,
						"organization_id" 	   => isset($interFaceHeader[0]['organization_id']) ? $interFaceHeader[0]['organization_id'] : NULL,
						"branch_id" 	       => isset($interFaceHeader[0]['branch_id']) ? $interFaceHeader[0]['branch_id'] : NULL,
						"order_status" 	       => isset($interFaceHeader[0]['order_status']) ? $interFaceHeader[0]['order_status'] : NULL,
						"order_type" 	       => isset($interFaceHeader[0]['order_type']) ? $interFaceHeader[0]['order_type'] : NULL,
						"payment_method" 	   => $payment_method,
						"paid_status" 	       => isset($paid_status) ? $paid_status : 'N',
						"order_source" 	       => isset($interFaceHeader[0]['order_source']) ? $interFaceHeader[0]['order_source'] : NULL,
						"table_id" 	           => isset($interFaceHeader[0]['table_id']) ? $interFaceHeader[0]['table_id'] : NULL,
						"waiter_id" 	       => isset($interFaceHeader[0]['waiter_id']) ? $interFaceHeader[0]['waiter_id'] : NULL,
						"sub_table" 	       => isset($interFaceHeader[0]['sub_table']) ? $interFaceHeader[0]['sub_table'] : NULL,
						"print_status" 	       => isset($interFaceHeader[0]['print_status']) ? $interFaceHeader[0]['print_status'] : 'N',
						
						'created_by'           => isset($interFaceHeader[0]['created_by']) ? $interFaceHeader[0]['created_by'] : NULL,
						'created_date'         => isset($interFaceHeader[0]['created_date']) ? $interFaceHeader[0]['created_date'] : NULL,
						'last_updated_by'      => isset($interFaceHeader[0]['last_updated_by']) ? $interFaceHeader[0]['last_updated_by'] : NULL,
						'last_updated_date'    => isset($interFaceHeader[0]['last_updated_date']) ? $interFaceHeader[0]['last_updated_date'] : NULL,
					);

					$this->db->insert('ord_order_headers',$headerData);
					$header_id = $this->db->insert_id();

					if($header_id)
					{
						#Update Next Val DOC Number tbl start
						$nextValue = $startingNumber + 1;
						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$this->db->where('branch_id', $branch_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end

						#Update Qry
						$interFaceHeaderStatus = array(
							"int_header_status" => "Processed"
						);

						$this->db->where('interface_header_id', $interface_header_id);
						$updateResult = $this->db->update('ord_order_interface_headers', $interFaceHeaderStatus);
						#Update Qry

						
						$InterFaceLineQry = "select * from ord_order_interface_lines 
							where 1=1 
							and reference_header_id='".$interface_header_id."' ";
						$interFaceLines = $this->db->query($InterFaceLineQry)->result_array();

						foreach($interFaceLines as $lineData)
						{
							$lineData = array(
								'header_id'          => $header_id,
								'reference_header_id'=> $interface_header_id,
								'reference_line_id'  => isset($lineData["interface_line_id"]) ? $lineData["interface_line_id"] : NULL,
								'product_id'	     => isset($lineData["product_id"]) ? $lineData["product_id"] : NULL,
								'price'	             => isset($lineData["price"]) ? $lineData["price"] : NULL,
								'quantity'	         => isset($lineData["quantity"]) ? $lineData["quantity"] : NULL,
								'offer_percentage'	 => isset($lineData["offer_percentage"]) ? $lineData["offer_percentage"] : NULL,
								'offer_amount'	     => isset($lineData["offer_amount"]) ? $lineData["offer_amount"] : NULL,
								'tax_percentage'	 => isset($lineData["tax_percentage"]) ? $lineData["tax_percentage"] : NULL,
								'line_status'	 	 => isset($lineData["line_status"]) ? $lineData["line_status"] : NULL,
								
								'created_by'         => isset($lineData["created_by"]) ? $lineData["created_by"] : NULL,
								'created_date'       => isset($lineData["created_date"]) ? $lineData["created_date"] : NULL,
								'last_updated_by'    => isset($lineData["last_updated_by"]) ? $lineData["last_updated_by"] : NULL,
								'last_updated_date'  => isset($lineData["last_updated_date"]) ? $lineData["last_updated_date"] : NULL,
							);

							$this->db->insert('ord_order_lines', $lineData);
							$line_id = $this->db->insert_id();
						}
					}

					$this->generatePDF($header_id);

					$response["pos_items"] = array(	
						"header_id"          => $header_id,
						"pos_dine_in_type"   => $_POST["pos_dine_in_type"],
						"status"             => 1,
						"message"            => "Order created successfully!"
					);
					
					echo json_encode($response);
					exit;
				}
			}
		}
		exit;
	}

	function updateSavePrint($interface_header_id="")
	{
		if( $interface_header_id )
		{
			$updateHeaderData = array(
				'interface_status'              => "Printed",
				'order_status'                  => "Printed",
				'last_updated_by'               => $this->user_id,
				'last_updated_date'             => $this->date_time,
			);

			$this->db->where('interface_header_id', $interface_header_id);
			$update_result = $this->db->update('ord_order_interface_headers', $updateHeaderData);

			$updateLineData = array(
				'line_status'              		=> "Printed",
				'last_updated_by'               => $this->user_id,
				'last_updated_date'             => $this->date_time,
			);
			$this->db->where('reference_header_id', $interface_header_id);
			$update_result = $this->db->update('ord_order_interface_lines', $updateLineData);
		}
		else
		{
			$type = 'order_interface_tbl';
			$button_type = 'SAVE';
			$direct_save_print = 'direct_save_print';
			$this->insertPosOrderItems($type,$button_type,$direct_save_print);
		}	
		echo "1";exit;
	}

	function updatePOSModifyStatus($interface_header_id="")
	{
		if( $interface_header_id )
		{
			$updateHeaderData = array(
				'interface_status'              => "Created",
				'order_status'                  => "Created",
				'last_updated_by'               => $this->user_id,
				'last_updated_date'             => $this->date_time,
			);

			$this->db->where('interface_header_id', $interface_header_id);
			$update_result = $this->db->update('ord_order_interface_headers', $updateHeaderData);

			$updateLineData = array(
				'line_status'              		=> "Created",
				'last_updated_by'               => $this->user_id,
				'last_updated_date'             => $this->date_time,
			);
			$this->db->where('reference_header_id', $interface_header_id);
			$update_result = $this->db->update('ord_order_interface_lines', $updateLineData);
			redirect(base_url() . 'pos/posOrder/takeaway/Created/'.$interface_header_id, 'refresh');
		}
		
	}

	function updateModifyStatus($interface_header_id="",$table_id='')
	{
		if( $interface_header_id )
		{
			$updateHeaderData = array(
				'interface_status'              => "Created",
				'order_status'                  => "Created",
				'last_updated_by'               => $this->user_id,
				'last_updated_date'             => $this->date_time,
			);

			$this->db->where('interface_header_id', $interface_header_id);
			$update_result = $this->db->update('ord_order_interface_headers', $updateHeaderData);

			$updateLineData = array(
				'line_status'              		=> "Created",
				'last_updated_by'               => $this->user_id,
				'last_updated_date'             => $this->date_time,
			);
			$this->db->where('reference_header_id', $interface_header_id);
			$update_result = $this->db->update('ord_order_interface_lines', $updateLineData);

			//$this->session->set_flashdata('flash_message' ,'Banner Added Successfully');
			redirect(base_url() . 'dine_in/dineInOrder/'.$table_id.'/Created/'.$interface_header_id, 'refresh');
		}
		
	}

	function generatePDF($id="")
    {
		$page_data['id'] = $id;
		
		$page_data['data']  = $this->orders_model->getOrderDetails($id);
		$page_data['LineData'] = $this->orders_model->getOrderItemsPrint($id);
		
		ob_start();

		#Print Receipt HTML Start
		$html = ob_get_clean();
		$html = utf8_encode($html);
		$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
      
		$mpdf = new \Mpdf\Mpdf([		
			#'setAutoTopMargin' => 'stretch',
			#'setAutoBottomMargin' => 'stretch',
			'curlAllowUnsafeSslRequests' => true,
		]);

        $mpdf->WriteHTML($html);
		$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F');
		#Print Receipt HTML End

		#KOT Bill start start
		$kot_html = ob_get_clean();
		$kot_html = utf8_encode($kot_html);
		$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);

		$kot_mpdf = new \Mpdf\Mpdf([		
			#'setAutoTopMargin' => 'stretch',
			#'setAutoBottomMargin' => 'stretch',
			'curlAllowUnsafeSslRequests' => true,
		]);
        $kot_mpdf->WriteHTML($kot_html);
		$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
		#KOT Bill start end
	}
	#POS End here

	function manageposOrders($type = '', $id = '', $status = '', $status_1 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['pos'] = $page_data['manage_pos']  = 1;
		$page_data['page_name']  = 'pos/manageposOrders';
		$page_data['page_title'] = 'POS Orders';

		switch(true)
		{
			case ($type == "payment_update"): #Update Payment Status
				
				$data=array(
					'payment_due' 		=> 'Paid',
					'last_updated_by'	=> $this->user_id,
					'last_updated_date' => $this->date_time,
				);
				$succ_msg = 'Payment status update successfully!';
	
				$this->db->where('header_id', $id);
				$this->db->update('ord_order_headers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			default : #Manage

				$totalResult = $this->pos_model->getManagePosOrders("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : NULL;
				$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['from_date']) ? $_GET['to_date'] : NULL;

				$redirectURL = 'pos/manageposOrders?branch_id='.$branch_id.'&order_number='.$order_number.'&from_date='.$from_date.'&to_date='.$to_date.'';
				
				if ( $branch_id !=NULL || $order_number !=NULL || $from_date !=NULL || $to_date !=NULL) {
					$base_url = base_url().$redirectURL;
				} else {
					$base_url = base_url().$redirectURL;
				}
				
				$config = PaginationConfig($base_url,$totalRows,$limit);
				
				$this->pagination->initialize($config);
				$str_links = $this->pagination->create_links();
				$page_data['pagination'] = explode(' ', $str_links);
				$offset = 0;
				if (!empty($_GET['per_page'])) {
					$pageNo = $_GET['per_page'];
					$offset = ($pageNo - 1) * $limit;
				}
				
				if($offset == 1 || $offset== "" || $offset== 0){
					$page_data["first_item"] = 1;
				}else{
					$page_data["first_item"] = $offset + 1;
				}
				
				$result = $this->pos_model->getManagePosOrders($limit,$offset,$this->pageCount);
				$page_data['resultData'] = $result["listing"];

				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}

				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				$pageno = isset($pageNo) ? $pageNo :"";
				
				if( $totalRows == 0 ){
					$page_data["starting"] = 0;
				}else if( $pageno==1 || $pageno=="" ){
					$page_data["starting"] = 1;
				}else{
					$pages = $pageno-1;
					$total_count = $pages * $config["per_page"];
					$page_data["starting"] = ( $config["per_page"] * $pages )+1;
				}
				
				$total_counts = $total_count + count($result["listing"]);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}

	#Mobile Number Serarch
	function ajaxSearchOnlinePOSCustomers()
    {
		if(isset($_POST["mobile_number"]))  
		{  
			$output = '';  

			$mobile_number = "concat('%','".serchFilter($_POST['mobile_number'])."','%')";
			
			$query = "select 
					per_user.user_id as customer_id,
					customer_name,
					mobile_number,
					address1,
					address2,
					address3 from cus_consumers 
					join per_user on per_user.reference_id = cus_consumers.customer_id
					where 1=1
					and ( cus_consumers.mobile_number like $mobile_number)";
			
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )  
			{  
				$output = '<ul class="list-unstyled list-unstyled-new">';  
				foreach($result as $row)  
				{	
					$customer_id = $row["customer_id"];
					$mobile_number = $row["mobile_number"];
					$customer_name = $row["customer_name"];
					$address = $row["address1"];
				
					$output .= '<li onclick="return getConsumerDetails(\'' .$customer_id. '\',\'' .$mobile_number. '\',\'' .$customer_name. '\',\'' .$address. '\');">'.$mobile_number.'</li>';  
				}  
				$output .= '</ul>';  
				echo $output;  
			}
			else
			{
				echo "no_data";  
			}  
			exit;	
		}
	}

	#CustomerMobile Number Serarch
	function ajaxSearchPOSDineInCustomers()
    {
		$mobileNumber = isset($_POST["mobile_number"]) ? $_POST["mobile_number"] : NULL;

		if( $mobileNumber != NULL )  
		{  
			$output = '';  

			$mobile_number = "concat('%','".serchFilter($mobileNumber)."','%')";
			
			$query = "select 
				per_user.user_id as customer_id,
				per_user.reference_id as consumer_id,
				customer_name,
				mobile_number,
				address1,
				address2,
				address3 from cus_consumers 
				join per_user on per_user.reference_id = cus_consumers.customer_id
				where 1=1
				and ( cus_consumers.mobile_number like $mobile_number)";
			
			$result = $this->db->query($query)->result_array();
			
			if( count($result) > 0 )  
			{  
				$output = '<ul class="list-unstyled list-unstyled-new">';  
				foreach($result as $row)  
				{	
					$consumer_id = $row["consumer_id"];
					$mobile_number = $row["mobile_number"];
					$customer_name = $row["customer_name"];
					$address = $row["address1"];
				
					$output .= '<li onclick="return getNewConsumerDetails(\'' .$consumer_id. '\',\'' .$mobile_number. '\',\'' .$customer_name. '\',\'' .$address. '\');">'.$mobile_number.'</li>';  
				}  
				$output .= '</ul>';  
				echo $output;  
			}
			else
			{
				echo "no_data";  
			}  
			exit;	
		}
	}

	function ajaxSaveCustomer()
	{
		if($_POST)
		{
			$customer_id = isset($_POST["add_customer_id"]) ? $_POST["add_customer_id"] : NULL;
			$add_mobile_number = isset($_POST["add_mobile_number"]) ? $_POST["add_mobile_number"] : NULL;
			$add_customer_name = isset($_POST["add_customer_name"]) ? $_POST["add_customer_name"] : NULL;
			$add_customer_address = isset($_POST["add_customer_address"]) ? $_POST["add_customer_address"] : NULL;

			if($customer_id != NULL && $customer_id > 0)
			{
				$customerData = array(
					#"mobile_number"        => $add_mobile_number,
					"customer_name"        => $add_customer_name,
					"address1"             => $add_customer_address,
					#"mobile_num_verified"  => 'Y',
					#'created_by'           => $this->user_id,
					#'created_date'         => $this->date_time,
					'last_updated_by'      => $this->user_id,
					'last_updated_date'    => $this->date_time,
				);

				$this->db->where('mobile_number',$add_mobile_number);
				$this->db->where('customer_id',$customer_id);
				$updateData = $this->db->update('cus_consumers', $customerData);

				#Per User Update
				$userQry = "select user_id,reference_id as customer_id from per_user where reference_id ='".$customer_id."' ";
				$getCustomer = $this->db->query($userQry)->result_array();

				$user_id = isset($getCustomer[0]["user_id"]) ? $getCustomer[0]["user_id"] : NULL;

				$postUserData = array(
					"active_flag"       => 'Y',
					"last_updated_by"   => $user_id,
					"last_updated_date" => $this->date_time,
				);
				
				$this->db->where('user_id', $user_id);
				$user_result = $this->db->update('per_user', $postUserData);
				#Per User Update
			}
			else
			{
				if( $add_mobile_number != NULL && !empty($add_mobile_number) )
				{
					#Document No Start here
					$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'CUS' 
							and dm.active_flag = 'Y'
							and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
							and coalesce(dm.to_date,CURDATE()) >= CURDATE()
						";
					$getDocumentData=$this->db->query($documentQry)->result_array();
						
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
					#Document No End here

					$customerData = array(
						"customer_number"      => $documentNumber,
						"mobile_number"        => $add_mobile_number,
						"customer_name"        => $add_customer_name,
						"address1"             => $add_customer_address,
						"mobile_num_verified"  => 'Y',
						'created_by'           => $this->user_id,
						'created_date'         => $this->date_time,
						'last_updated_by'      => $this->user_id,
						'last_updated_date'    => $this->date_time,
					);

					$this->db->insert('cus_consumers',$customerData);
					$customer_id = $this->db->insert_id();

					#Update Next Val DOC Number tbl start
					$nextValue = $startingNumber + 1;
					$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
					
					$UpdateData['next_number'] = $nextValue;
					$this->db->where('doc_num_id', $doc_num_id);
					$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
					#Update Next Val DOC Number tbl end

					#Per Users start
					$userData = array(
						"reference_id"        => $customer_id,
						"user_name" 	      => $add_mobile_number,
						"active_flag" 	      => $this->active_flag,
						"created_date"        => $this->date_time,
						"created_by"          => '-1',
						"last_updated_date"   => $this->date_time,
						"last_updated_by"     => '-1',
						"attribute2"          => 'Y', #Mobile Number Verified	
					);

					$this->db->insert('per_user', $userData);
					$user_id = $this->db->insert_id();
					#Per Users end
				}
				else
				{
					$user_id = NULL;
				}
			}

			$_SESSION["SEARCH_CUSTOMER_ID"] = $user_id;
			echo $user_id."@".$add_customer_name;
		}
		exit;
	}

	function generateOpenOrdersPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;
		$page_data['data'] = $headerDetails = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		
		$bill_print_count = isset($headerDetails[0]['bill_print_count']) ? $headerDetails[0]['bill_print_count'] + 1 : 0;
		#Suresh new changes start here
		
		$UpdateData['interface_status'] = "Printed";
		$UpdateData['bill_print_status'] = "Printed";
		$UpdateData['bill_print_count'] = $bill_print_count;
		$this->db->where('interface_header_id', $header_id);
		$resultUpdateData = $this->db->update('ord_order_interface_headers', $UpdateData);
		#Suresh new changes end here

		#Unlink PDF Start	
		if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		/* if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		} */
		#Unlink PDF end
		
		
		/* 
		if($button_type == "SAVE" || $button_type == "SAVE_PRINT") #KOT
		{
			$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		}

		if($button_type == "SAVE_PRINT") #Bill Print
		{ */
			$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		//}
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			$html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
			
			$mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);

			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F');
			#Print Receipt HTML End

			/* #KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf();
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F'); */
			#KOT Bill start end
		}
	}

	function generateKOTPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Suresh new changes start here
		$UpdateData['interface_status'] = "Printed";
		$this->db->where('interface_header_id', $header_id);
		$resultUpdateData = $this->db->update('ord_order_interface_headers', $UpdateData);
		#Suresh new changes end here
		
		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end
		
		$page_data['data']  = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);

		/* 
		if($button_type == "SAVE" || $button_type == "SAVE_PRINT") #KOT
		{
			$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		}

		if($button_type == "SAVE_PRINT") #Bill Print
		{ */
			#$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		//}
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			/* $html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F'); */
			#Print Receipt HTML End

			#KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
			#KOT Bill start end
		}
	}

    function generateCapKOTPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Suresh new changes start here
		$UpdateData['interface_status'] = "Printed";
		$this->db->where('interface_header_id', $header_id);
		$resultUpdateData = $this->db->update('ord_order_interface_headers', $UpdateData);
		#Suresh new changes end here
		
		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end
		
		$page_data['data']  = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		$LineData = $page_data['LineData'] = $this->orders_model->getDineInKOTOrderItems($id);

		/* 
		if($button_type == "SAVE" || $button_type == "SAVE_PRINT") #KOT
		{
			$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		}

		if($button_type == "SAVE_PRINT") #Bill Print
		{ */
			#$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		//}
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			/* $html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F'); */
			#Print Receipt HTML End

			#KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
			#KOT Bill start end
		}
	}

    

	function generateOnlineKOTPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end
		
		$page_data['data']  = $this->orders_model->getOrderHeaderDetails($id);#Header Qry
		$LineData = $page_data['LineData'] = $this->orders_model->getOrderLineDetails($id);

		/* 
		if($button_type == "SAVE" || $button_type == "SAVE_PRINT") #KOT
		{
			$LineData = $page_data['LineData'] = $this->orders_model->getKOTOrderItems($id);
		}

		if($button_type == "SAVE_PRINT") #Bill Print
		{ */
			#$LineData = $page_data['LineData'] = $this->orders_model->getOpenPrintOrderItems($id);
		//}
		
		if( count($LineData) > 0 )
		{
			ob_start();

			#Print Receipt HTML Start
			/* $html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/orders/printReceipt',$page_data,true);
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->WriteHTML($html);
			$mpdf->Output('uploads/auto_generate_pdf/'.$id.'.pdf', 'F'); */
			#Print Receipt HTML End

			#KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$id.'.pdf', 'F');
			#KOT Bill start end
		}
	}

	function deleteLineItems($interface_line_id="")
    {
		$this->db->where('interface_line_id', $interface_line_id);
		$this->db->delete('ord_order_interface_lines');
		echo 1;exit;
	}


	function insertPrintStatus()
	{
		if($_POST)
		{
			if($_POST["job_id"] == NULL)
			{

			}
			else
			{

				if($_POST["job_id"] == -1)
				{
					$sent_to_printer = "Cancelled";
				}
				else
				{
					$sent_to_printer = "Printed";
				}

				if($_POST["job_id"] > 0 )
				{
					$print_status = "Printed";
				}
				else
				{
					$print_status = "Not Printed";
				}

				$headerData= array(
					'jspm_status'               	=> isset($_POST["printer_status"]) ? $_POST["printer_status"] : NULL,
					'order_id'                  	=> isset($_POST["order_id"]) ? $_POST["order_id"] : NULL,
					'print_type'                  	=> isset($_POST["print_type"]) ? $_POST["print_type"] : NULL,
					'job_id'                  	    => isset($_POST["job_id"]) ? $_POST["job_id"] : NULL,
					'file'                  	    => isset($_POST["file"]) ? $_POST["file"] : NULL,
					'description'                  	=> isset($_POST["job_description"]) ? $_POST["job_description"] : NULL,
					'sent_to_printer'               => $sent_to_printer,
					'print_status'              	=> $print_status,
					'created_by'                    => $this->user_id,
					'created_date'                  => $this->date_time,
					'last_updated_by'               => $this->user_id,
					'last_updated_date'             => $this->date_time,
				);
			}
			$this->db->insert('org_print_job_status',$headerData);
			$print_job_id = $this->db->insert_id();
			
			//echo json_encode($print_job_id);
			exit;
		}
		exit;
	}


	function generateOrderSeqKOT($button_type="",$id="",$order_seq_number='')
    {
		$page_data['id'] = $header_id = $id;

		#Suresh new changes start here
		$UpdateData['interface_status'] = "Printed";
		$this->db->where('interface_header_id', $header_id);
		$resultUpdateData = $this->db->update('ord_order_interface_headers', $UpdateData);
		#Suresh new changes end here
		
		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end
		
		$page_data['data']  = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		$LineData = $page_data['LineData'] = $this->orders_model->getDineInOrderSeqKOTOrder($id,$order_seq_number);
		
		if( count($LineData) > 0 )
		{
			$pdf_name = $id."_".$order_seq_number;
			ob_start();
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$pdf_name.'.pdf', 'F');
			#KOT Bill start end
		}
	}

	function generateCapOrderKOTPDF($button_type="",$id="")
    {
		$page_data['id'] = $header_id = $id;

		#Suresh new changes start here
		$UpdateData['interface_status'] = "Printed";
		$this->db->where('interface_header_id', $header_id);
		$resultUpdateData = $this->db->update('ord_order_interface_headers', $UpdateData);
		#Suresh new changes end here
		
		#Unlink PDF Start	
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		} */
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		#Unlink PDF end

		$getBranch = $this->db->query("select branch_id,table_id from ord_order_interface_headers where interface_header_id='".$header_id."' ")->result_array();

		$table_id = isset($getBranch[0]["table_id"]) ? $getBranch[0]["table_id"] : NULL;
		$branch_id = isset($getBranch[0]["branch_id"]) ? $getBranch[0]["branch_id"] : NULL;
		$getDineInSeqOrder = $this->web_fine_dine_model->getCapDineInSeqOrder($table_id,$branch_id);

		$orderSeqNumber = isset($getDineInSeqOrder[0]["order_seq_number"]) ? $getDineInSeqOrder[0]["order_seq_number"] : NULL;
		
		if($orderSeqNumber){
			$order_seq_number = $orderSeqNumber;
		}else{
			$order_seq_number = 1;
		}

		$page_data['data']  = $this->orders_model->getOpenPrintOrderDetails($id);#Header Qry
		//$LineData = $page_data['LineData'] = $this->orders_model->getDineInKOTOrderItems($id);
		$LineData = $page_data['LineData'] = $this->orders_model->getDineInOrderSeqKOTOrder($id,$order_seq_number);

		if( count($LineData) > 0 )
		{
			ob_start();

			#KOT Bill start start
			$kot_mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);

			$pdf_name = $id."_".$order_seq_number;
			$kot_html = ob_get_clean();
			$kot_html = utf8_encode($kot_html);
			$kot_html = $this->load->view('backend/orders/kotPrint',$page_data,true);
			$kot_mpdf->WriteHTML($kot_html);
			$kot_mpdf->Output('uploads/auto_generate_pdf/kot/'.$pdf_name.'.pdf', 'F');
			#KOT Bill start end
		}
	}
}


?>
