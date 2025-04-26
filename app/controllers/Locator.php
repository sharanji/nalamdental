<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Locator extends CI_Controller 
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

	function manageSubInventory($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'locator/manageSubInventory';
		$page_data['page_title'] = 'Sub Inventory';
		
		switch($type)
		{
			case "add": #View
				$page_data['organization'] = $this->organization_model->getOrganizations();
				if($_POST)
				{
					$valid_from_date_string = strtotime($_POST['valid_from_date']);
					$valid_to_date_string = strtotime($_POST['valid_to_date']);

					/* $valid_from_date = date("Y-m-d",$valid_from_date_string);
					$valid_to_date = date("Y-m-d",$valid_to_date_string); */

					if( isset($_POST['valid_from_date']) && !empty($_POST['valid_from_date']) ){
						$valid_from_date = date("Y-m-d",strtotime($_POST['valid_from_date']))	;
					}else{
						$valid_from_date = NULL	;
					}

					if( isset($_POST['valid_to_date']) && !empty($_POST['valid_to_date']) ){
						$valid_to_date = date("Y-m-d",strtotime($_POST['valid_to_date']))	;
					}else{
						$valid_to_date = NULL	;
					}

					$data['organization_id'] = $this->input->post('organization_id');
					$data['inventory_code'] = $this->input->post('inventory_code');
					$data['inventory_name'] = $this->input->post('inventory_name');
					$data['inventory_description'] = $this->input->post('inventory_description');

					$data['valid_from_date'] = $valid_from_date;
					$data['valid_from_date_string'] = $valid_from_date_string;
					$data['valid_to_date'] = $valid_to_date;
					$data['valid_to_date_string'] = $valid_to_date_string;
					
					$data['inventory_status'] = 1;

					if(isset($_POST["locator_availability"]) && $_POST["locator_availability"] == 1)
					{
						$locator_availability = 1;
					}
					else 
					{
						$locator_availability = 0;
					}
					$data['locator_availability'] = $locator_availability;
					
					# subinventory exist start here
					$chkExistInventoryName = $this->db->query("select inventory_id from inv_item_sub_inventory
						where 
							organization_id='".$data['organization_id']."' and 
							(inventory_code='".$data['inventory_code']."' or
								inventory_name='".$data['inventory_name']."')
							")->result_array();
							
					if(count($chkExistInventoryName) > 0)
					{
						$this->session->set_flashdata('error_message' , " Inventory Code / Name already exist!");
						redirect(base_url() . 'locator/manageSubInventory/add', 'refresh');
					}
					# subinventory exist end here

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;


					$this->db->insert('inv_item_sub_inventory', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Sub Inventory added successfully!");
						redirect(base_url() . 'locator/manageSubInventory', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('inv_item_sub_inventory', array('inventory_id' => $id))
										->result_array();
				
				$page_data['organization'] = $this->organization_model->getOrganizations();

				if($_POST)
				{
					$valid_from_date_string = strtotime($_POST['valid_from_date']);
					$valid_to_date_string = strtotime($_POST['valid_to_date']);

					if( isset($_POST['valid_from_date']) && !empty($_POST['valid_from_date']) ){
						$valid_from_date = date("Y-m-d",strtotime($_POST['valid_from_date']))	;
					}else{
						$valid_from_date = NULL	;
					}

					if( isset($_POST['valid_to_date']) && !empty($_POST['valid_to_date']) ){
						$valid_to_date = date("Y-m-d",strtotime($_POST['valid_to_date']))	;
					}else{
						$valid_to_date = NULL	;
					}

					$data['organization_id'] = $this->input->post('organization_id');
					$data['inventory_code'] = $this->input->post('inventory_code');
					$data['inventory_name'] = $this->input->post('inventory_name');
					$data['inventory_description'] = $this->input->post('inventory_description');
					
					$data['valid_from_date'] = $valid_from_date;
					$data['valid_from_date_string'] = $valid_from_date_string;
					$data['valid_to_date'] = $valid_to_date;
					$data['valid_to_date_string'] = $valid_to_date_string;

					if(isset($_POST["locator_availability"]) && $_POST["locator_availability"] == 1)
					{
						$locator_availability = 1;
					}
					else 
					{
						$locator_availability = 0;
					}
					$data['locator_availability'] = $locator_availability;

					#  exist start here
					$chkExistInventory = $this->db->query("select inventory_id from inv_item_sub_inventory
						where 
							inventory_id !='".$id."' and
							organization_id='".$data['organization_id']."' and 
							(inventory_code='".$data['inventory_code']."' or
							inventory_name='".$data['inventory_name']."') ")->result_array();
							
					if(count($chkExistInventory) > 0)
					{
						$this->session->set_flashdata('error_message' , " Sub Inventory already exist!");
						redirect(base_url() . 'locator/manageSubInventory/edit/'.$id, 'refresh');
					}
					#  exist end here

					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;

					$this->db->where('inventory_id', $id);
					$result = $this->db->update('inv_item_sub_inventory', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Sub Inventory updated successfully!");
						redirect(base_url() . 'locator/manageSubInventory', 'refresh');
					}
				}
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Sub Inventory active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Sub Inventory Inactive successfully!';
				}
				$this->db->where('inventory_id', $id);
				$this->db->update('inv_item_sub_inventory', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
			
				$totalResult = $this->locator_model->getSubInventory("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$keywords = isset($_GET['keywords']) ? $_GET['keywords'] :NULL;
				$organization_id = isset($_GET['organization_id']) ? $_GET['organization_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$redirectURL = 'locator/manageSubInventory?keywords=&organization_id='.$organization_id.'&active_flag='.$active_flag;

				if (!empty($_GET['keywords'])  || !empty($_GET['organization_id']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('locator/manageSubInventory?keywords='.$_GET['keywords'].'&organization_id='.$_GET['organization_id'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('locator/manageSubInventory?keywords=&organization_id=&active_flag=Y');
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
				
				$page_data['resultData']  = $result = $this->locator_model->getSubInventory($limit, $offset,  $this->pageCount);
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
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
	
	function manageLocator($type = '', $id = '', $status = '', $status1 = '', $status2 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $inventory_id = $id;
		$page_data['status'] = $status;
		$page_data['status1'] = $status1;
		$page_data['status2'] = $status2;
		
		$page_data['system_settings'] = 1;
		$page_data['page_name']  = 'locator/manageLocator';
		$page_data['page_title'] = 'Locators';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['organization_id'] = $id;
					$data['inventory_id'] = $status;
					$data['rack_code'] = $this->input->post('rack_code');
					$data['row_name'] = $this->input->post('row_name');
					$data['bin_name'] = $this->input->post('bin_name');
					$data['locator_no'] = $this->input->post('locator_no');
					$data['locator_name'] = $this->input->post('locator_name');
					$data['locator_description'] = $this->input->post('locator_description');
					
					#exist start here
					$chkExistUom = $this->db->query("select locator_id from inv_item_locators
					where 
						locator_no='".$data['locator_no']."'
						and organization_id='".$id."'
						and inventory_id='".$status."'
						")->result_array();
								
					if(count($chkExistUom) > 0)
					{
						$this->session->set_flashdata('error_message' , "Locator already exist!");
						redirect(base_url() . 'locator/manageLocator/locators/'.$id.'/'.$status, 'refresh');
					}
					#exist end here

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;

					$this->db->insert('inv_item_locators', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Inventory locator added successfully!");
						redirect(base_url() . 'locator/manageLocator/locators/'.$data['organization_id'].'/'.$data['inventory_id'], 'refresh');
					
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('inv_item_locators', array('locator_id' => $id))
										->result_array();
				#$page_data['warehouse'] = $this->purchase_model->getWarehouse();

				if($_POST)
				{
					$data['rack_code'] = $this->input->post('rack_code');
					$data['row_name'] = $this->input->post('row_name');
					$data['bin_name'] = $this->input->post('bin_name');
					$data['locator_no'] = $this->input->post('locator_no');
					$data['locator_name'] = $this->input->post('locator_name');
					$data['locator_description'] = $this->input->post('locator_description');
					//$data['warehouse_id'] = $this->input->post('warehouse_id');
					
					# uom exist start here
					$chkExistUom = $this->db->query("select locator_id from inv_item_locators
						where 
						locator_id !='".$id."' and
							( locator_no='".$data['locator_no']."' or
								locator_name='".$data['locator_name']."' )
								")->result_array();
							
					if(count($chkExistUom) > 0)
					{
						$this->session->set_flashdata('error_message' , " locator Code / Name already exist!");
						redirect(base_url() . 'locator/manageLocator/'.$status.'/'.$status1.'/'.$status2, 'refresh');
					}
					# uom exist end here
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;

					$this->db->where('locator_id', $id);
					$result = $this->db->update('inv_item_locators', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Inventory locator updated successfully!");
						redirect(base_url() . 'locator/manageLocator/'.$status.'/'.$status1.'/'.$status2, 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('calendar_id', $id);
				$this->db->delete('org_calendar');
				
				$this->session->set_flashdata('flash_message' , "Calendar deleted successfully!");
				redirect(base_url() . 'lot/manageLot', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Inventory locator active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Inventory locator Inactive successfully!';
				}
				$this->db->where('locator_id', $id);
				$this->db->update('inv_item_locators', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			case "locators": #Manage
				$totalResult = $this->locator_model->getLocators("","",$id,$status,$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'locator/manageLocator/locators/'.$id.'/'.$status.'?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('locator/manageLocator/locators/'.$id.'/'.$status.'?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('locator/manageLocator/locators/'.$id.'/'.$status.'?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result =$this->locator_model->getLocators($limit, $offset,$id, $status,$this->pageCount);
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
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
}
?>
