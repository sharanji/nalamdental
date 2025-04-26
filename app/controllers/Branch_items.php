<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Branch_items extends CI_Controller 
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
	
	function ManageBranchItems($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'branch_items/ManageBranchItems';
		$page_data['page_title'] = 'Branch Items';
		
		switch(true)
		{
			case ($type =="add"):
				if($_POST)
				{
					$branch_id = $this->input->post('branch_id');
					# Check Exist Start
					$chkExistProduct = $this->db->query("select assignment_id from inv_item_branch_assign 
						where branch_id='".$branch_id."' 
							")->result_array();
							
					if(count($chkExistProduct) > 0)
					{
						$this->session->set_flashdata('error_message' , "Branch items already assigned!");
						redirect(base_url() . 'branch_items/ManageBranchItems/add', 'refresh');
					}
					# Check Exist End
					
					$count = isset($_POST['counter']) ? count($_POST['counter']) : 0;

					if( $count > 0 )
					{
						for($dp=0;$dp<$count;$dp++)
						{
							$counter = isset($_POST['counter'][$dp]) ? $_POST['counter'][$dp] :NULL;
							$item_id = isset($_POST['item_id'][$dp]) ? $_POST['item_id'][$dp] :NULL;
							$item_price = isset($_POST['item_price'][$dp]) ? $_POST['item_price'][$dp] :NULL;
							$dine_in_price = isset($_POST['dine_in_price'][$dp]) ? $_POST['dine_in_price'][$dp] :NULL;
							$available_quantity = isset($_POST['available_quantity'][$dp]) ? $_POST['available_quantity'][$dp] :NULL;
							$minimum_order_quantity = isset($_POST['minimum_order_quantity'][$dp]) ? $_POST['minimum_order_quantity'][$dp] :NULL;
							
							$breakfast_flag = isset($_POST['breakfast_flag'][$counter]) ? $_POST['breakfast_flag'][$counter]: 'N';
							
							
							
							$lunch_flag = isset($_POST['lunch_flag'][$counter]) ? $_POST['lunch_flag'][$counter] : 'N';
							$dinner_flag = isset($_POST['dinner_flag'][$counter]) ? $_POST['dinner_flag'][$counter] : 'N';
							$best_selling = isset($_POST['best_selling'][$counter]) ? $_POST['best_selling'][$counter] : 'N';
							
							$active_flag = isset($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] :NULL;
							$branch_id = isset($_POST["branch_id"]) ? $_POST["branch_id"] : NULL;

							$assignmentData =  array(
								'branch_id'              => $branch_id,
								'item_id'                => $item_id,
								'item_price'             => $item_price,
								'dine_in_price'          => $dine_in_price,
								'available_quantity'     => $available_quantity,
								'minimum_order_quantity' => $minimum_order_quantity,

								'breakfast_flag'         => $breakfast_flag,
								'lunch_flag'           	 => $lunch_flag,
								'dinner_flag'            => $dinner_flag,
								'best_selling'           => $best_selling,

								/* 'from_time_am'           => $from_time_am,
								'to_time_am'             => $to_time_am,
								'from_time_pm'           => $from_time_pm,
								'to_time_pm'             => $to_time_pm, */

								'active_flag'            => $active_flag,

								'created_by'             => $this->user_id,
								'created_date'           => $this->date_time,
								'last_updated_by'        => $this->user_id,
								'last_updated_date'      => $this->date_time,
							);

							#Audit Trails Start here
							$tableName = assign_branch_items;
							$menuName = assign_branch_items;
							$pageName = page_branch_items;
							$description = "Branch Items created successsfully!";
							auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description,$pageName);
							#Audit Trails end here
							
							
							$this->db->insert('inv_item_branch_assign', $assignmentData);
							$id_1 = $this->db->insert_id();
						}
					}

					$this->session->set_flashdata('flash_message' , "Branch items assigned successfully!");
					redirect(base_url() . 'branch_items/ManageBranchItems/edit/'.$branch_id, 'refresh');	
				}
			break;
			
			case ($type =="edit" || $type =="view" ):
				$page_data['assignedItems'] = $this->branch_items_model->getBranchItems($id);	

				if($_POST)
				{			
					$data['branch_id'] = $this->input->post('branch_id');
					
					$count = isset($_POST['item_id']) ? count(array_filter($_POST['item_id'])) : 0;
					
					if( $count > 0 )
					{
						foreach($_POST['item_id'] as $dp => $value)
						{
							$item_id = isset($_POST['item_id'][$dp]) ? $_POST['item_id'][$dp] :NULL;
							
							$assignment_id = isset($_POST['assignment_id'][$dp]) ? $_POST['assignment_id'][$dp] :NULL;
							$branch_id = isset($_POST["branch_id"]) ? $_POST["branch_id"] : NULL;

							$chkExistQry = "select assignment_id from inv_item_branch_assign 
								where assignment_id='".$assignment_id."' 
									AND item_id='".$item_id."'
									AND branch_id='".$branch_id."'
									";
							$chkExist = $this->db->query($chkExistQry)->result_array();

							$item_price = isset($_POST['item_price'][$dp]) ? $_POST['item_price'][$dp] :NULL;
							$dine_in_price = isset($_POST['dine_in_price'][$dp]) ? $_POST['dine_in_price'][$dp] :NULL;
							$available_quantity = isset($_POST['available_quantity'][$dp]) ? $_POST['available_quantity'][$dp] :NULL;
							$minimum_order_quantity = isset($_POST['minimum_order_quantity'][$dp]) ? $_POST['minimum_order_quantity'][$dp] :NULL;
							
							$active_flag = isset($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] :NULL;
							
							$counter = isset($_POST['counter'][$dp]) ? $_POST['counter'][$dp] :NULL;
							$breakfast_flag = isset($_POST['breakfast_flag'][$counter]) ? $_POST['breakfast_flag'][$counter]: 'N';
							$lunch_flag = isset($_POST['lunch_flag'][$counter]) ? $_POST['lunch_flag'][$counter] : 'N';
							$dinner_flag = isset($_POST['dinner_flag'][$counter]) ? $_POST['dinner_flag'][$counter] : 'N';
							$best_selling = isset($_POST['best_selling'][$counter]) ? $_POST['best_selling'][$counter] : 'N';
							
							/* end */

							if( count($chkExist) == 0 )
							{
								$assignmentData =  array(
									'branch_id'              => $branch_id,
									'item_id'                => $item_id,
									'item_price'             => $item_price,
									'dine_in_price'          => $dine_in_price,
									'available_quantity'     => $available_quantity,
									'minimum_order_quantity' => $minimum_order_quantity,

									'breakfast_flag'         => $breakfast_flag,
									'lunch_flag'           	 => $lunch_flag,
									'dinner_flag'            => $dinner_flag,
									'best_selling'           => $best_selling,
									
									'active_flag'            => $active_flag,

									'created_by'             => $this->user_id,
									'created_date'           => $this->date_time,
									'last_updated_by'        => $this->user_id,
									'last_updated_date'      => $this->date_time,
								);

								$this->db->insert('inv_item_branch_assign', $assignmentData);
								$id_1 = $this->db->insert_id();
							}
							else
							{
								/* $breakfast_flag = isset($_POST['breakfast_flag'][$dp]) ? $_POST['breakfast_flag'][$dp] : 'N';
								$lunch_flag = isset($_POST['lunch_flag'][$dp]) ? $_POST['lunch_flag'][$dp] : 'N';
								$dinner_flag = isset($_POST['dinner_flag'][$dp]) ? $_POST['dinner_flag'][$dp] : 'N';
								$best_selling = isset($_POST['best_selling'][$dp]) ? $_POST['best_selling'][$dp] : 'N'; */

								$assignmentData =  array(
									'item_price'             => $item_price,
									'dine_in_price'             => $dine_in_price,
									'available_quantity'     => $available_quantity,
									'minimum_order_quantity' => $minimum_order_quantity,
									/* 'breakfast_flag'         => $breakfast_flag,
									'lunch_flag'             => $lunch_flag,
									'dinner_flag'            => $dinner_flag,
									'best_selling'           => $best_selling, */
									
									'last_updated_by'        => $this->user_id,
									'last_updated_date'      => $this->date_time,
								);

								$this->db->where('item_id', $item_id);
								$this->db->where('branch_id', $branch_id);
								$this->db->where('assignment_id', $assignment_id);
								$this->db->update('inv_item_branch_assign', $assignmentData);
							}
						}
					}

					$this->session->set_flashdata('flash_message' , "Branch item assigned successfully!");
					redirect(base_url() . 'branch_items/ManageBranchItems/edit/'.$id, 'refresh');
				}
			break;
			
			default : #Manage
				$totalResult = $this->branch_items_model->getmanegeBranchItems("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords']) || !empty($_GET['mobile_number'])) {
					$base_url = base_url('branch_items/ManageBranchItems?branch_id='.$_GET['branch_id'].'&mobile_number='.$_GET['mobile_number']);
				} else {
					$base_url = base_url('branch_items/ManageBranchItems?branch_id=&mobile_number=');
				}
				
				$config = PaginationConfig($base_url,$totalRows,$limit);
				$this->pagination->initialize($config);
				$str_links = $this->pagination->create_links();
				$page_data['pagination'] = explode('&nbsp;', $str_links);
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
				
				$page_data['resultData']  = $result = $this->branch_items_model->getmanegeBranchItems($limit, $offset,$this->pageCount);
				
				#show start and ending Count
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
				
				$total_counts = $total_count + count($result);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	public function getItems($item_id="")
	{
		if( $item_id == 0 )
		{
			$query = "select item_id,item_name,item_description,item_cost from inv_sys_items where active_flag='Y'";
			$data['items'] = $this->db->query($query)->result();	
		}
		else
		{
			$query = "select item_id,item_name,item_description,item_cost from inv_sys_items 
			where active_flag='Y'
			and item_id='".$item_id."' ";
			$data['items'] = $this->db->query($query)->result();
		}

		/* $itemActive= '';	
		foreach($this->product_branch_status as $key=>$value)
		{
			$itemActive .= '<option value="'.$key.'">'.$value.'</option>';
		}	
		$data['itemStatus'] = $itemActive; */
		echo json_encode($data);exit;
	}
	
	public function viewBranchItems($branch_item_header_id="", $branch_id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['id'] = $branch_item_header_id;
		$page_data['branch_id'] = $branch_id;
		$page_data['Inventory'] = 1;
		$page_data['page_name']  = 'branch_items/viewBranchItems';
		$page_data['page_title'] = 'Branch Items';

		
		# Add start
		
		if( isset($_POST) && isset($_POST['product_id']) && $_POST['product_id'] !="" )
		{
		
			$product_id =$_POST['product_id'][0];
			
			$checkIngredients = $this->db->query("
			select branch_item_ingredient_id from vb_branch_item_ingredients 
			where 
			branch_id ='".$branch_id."' and 
				product_id ='".$product_id."'
			")->result_array();
			
			if( count($checkIngredients) > 0 )
			{
				$this->db->where('branch_id',$branch_id);
				$this->db->where('product_id',$product_id);
				$this->db->delete('vb_branch_item_ingredients');
			}

			$count=count($_POST['product_id']);

			for($dp=0;$dp<$count;$dp++)
			{
				$data = array(
					"branch_item_header_id"    => $branch_item_header_id,
					"branch_id"                => $branch_id,
					"product_id"               => $_POST['product_id'][$dp],
					"ingredient_id"            => $_POST['ingredient_id'][$dp],
					"price"                    => $_POST['price'][$dp],
					"ingredient_branch_status" => $_POST['ingredient_branch_status'][$dp],
				);
				
				$this->db->insert('vb_branch_item_ingredients', $data);
				$BranchItems  = $this->db->insert_id();
			}

			$this->session->set_flashdata('flash_message' , 'Ingredient added successfully!');
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		#Add end
		
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	
	function ajaxAvailableBranchItems($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status": #Block & Unblock
				if($status == 1){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Item is Available!';
				}else{
					$data['active_flag'] ='N';
					$succ_msg = 'Item is Unavailable!';
				}
				$this->db->where('assignment_id', $id);
				$this->db->update('inv_item_branch_assign', $data);
				echo $succ_msg;exit;
			break;
		}
	}

	public function ajaxSelectItems() 
	{			
		$data = $this->db->query("select item_id,item_name,item_description 
		from inv_sys_items
		where 1=1
		and active_flag = 'Y'
		and item_type_id = '30'
		order by item_description asc")->result_array();
	
		if( count($data) > 0)
		{
			echo '<option value="">- Select Item -</option>';
			echo '<option value="0">All Items</option>';
			foreach($data as $val)
			{
				echo '<option value="'.$val['item_id'].'">'.ucfirst($val['item_name']).' - '.ucfirst($val['item_description']).'</option>';
			}
		}
		else
		{
			echo '<option value="">No Items!</option>';
		}
		die;
    }

	function ajaxBreakfastFlag($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status":
				if($status == 'Y'){
					$succ_msg = 'Breakfast is Available!';
				}else{
					$succ_msg = 'Breakfast is Unavailable!';
				}
				$data['breakfast_flag'] = $status;
				$this->db->where('assignment_id', $id);
				$this->db->update('inv_item_branch_assign', $data);
				echo $succ_msg;exit;
			break;
		}
	}

	function ajaxLunchFlag($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status":
				if($status == 'Y'){
					$succ_msg = 'Lunch is Available!';
				}else{
					$succ_msg = 'Lunch is Unavailable!';
				}
				$data['lunch_flag'] = $status;
				$this->db->where('assignment_id', $id);
				$this->db->update('inv_item_branch_assign', $data);
				echo $succ_msg;exit;
			break;
		}
	}

	function ajaxDinnerFlag($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status":
				if($status == 'Y'){
					$succ_msg = 'Dinner is Available!';
				}else{
					$succ_msg = 'Dinner is Unavailable!';
				}
				$data['dinner_flag'] = $status;
				$this->db->where('assignment_id', $id);
				$this->db->update('inv_item_branch_assign', $data);
				echo $succ_msg;exit;
			break;
		}
	}

	function ajaxBestSelling($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status":
				if($status == 'Y'){
					$succ_msg = 'Best selling is Available!';
				}else{
					$succ_msg = 'Best selling is Unavailable!';
				}
				$data['best_selling'] = $status;
				$this->db->where('assignment_id', $id);
				$this->db->update('inv_item_branch_assign', $data);
				echo $succ_msg;exit;
			break;
		}
	}
}
?>
