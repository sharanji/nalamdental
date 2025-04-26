<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lov extends CI_Controller 
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
	
	#Manage Lov
    function manageLov($type = '', $id = '', $status = '',$status1='')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		
		$page_data['page_name']  = 'lov/manageLov';
		$page_data['page_title'] = 'Manage List Name';
		
		switch(true)
		{
			case ($type == "add"): #Add
				if($_POST)
				{
					$data['list_name'] = strtoupper(url($this->input->post('list_name')));
					
					# Check already exist start here
					$chkExist = $this->db->query("select list_type_id from sm_list_types 
						where 
							list_name='".$data['list_name']."' 
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "List name already exist!");
						redirect(base_url() . 'lov/manageLov/add', 'refresh');
					}
					# Check already exist end here
					
					$data['list_description'] = $this->input->post('list_description');
					
					$data['active_flag'] = $this->active_flag;
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('sm_list_types', $data);
					$id = $this->db->insert_id();

					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , 'List type added successfully!');
						redirect(base_url() . 'lov/manageLov/ManageListTypeValues/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "edit"): #Edit
				$page_data['edit_data'] = $this->db->get_where('sm_list_types', array('list_type_id' => $id))->result_array();
				if($_POST)
				{
					$data['list_name'] = strtoupper(url($this->input->post('list_name')));
					#$data['menu_url'] = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select list_type_id from sm_list_types 
						where 
							list_name='".$data['list_name']."' and
								list_type_id !='".$id."'
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "List type already exist!");
						redirect(base_url() . 'lov/manageLov/edit/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['list_description'] = $this->input->post('list_description');

					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->where('list_type_id', $id);
					$result = $this->db->update('sm_list_types', $data);
					
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , 'List type updated successfully!');
						//redirect(base_url() . 'lov/manageLov/ManageListTypeValues/'.$id, 'refresh');                    
						redirect(base_url() . 'lov/manageLov', 'refresh');
					}
					
				}
			break;
			
			case ($type == "delete"): #Delete
				$this->db->where('list_type_id', $id);
				$this->db->delete('sm_list_types');
				$this->session->set_flashdata('flash_message' , 'List type deleted successfully!');
				redirect(base_url() . 'lov/manageLov', 'refresh');
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['end_date'] = NULL;
					$succ_msg = 'List type inactive successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$data['end_date'] = $this->date;
					$succ_msg = 'List type active successfully!';
				}

				$this->db->where('list_type_id', $id);
				$this->db->update('sm_list_types', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case ($type == "ManageListTypeValues" || $type == "viewListType"): #Manage List Type Values
				
				$page_data['edit_data'] = $this->db->get_where('sm_list_types', array('list_type_id' => $id))->result_array();
				
				if(isset($_POST['Add']) && $_POST['Add'] == 'Add')
				{
					$data['list_type_id'] = $id;
					$data['list_code'] = strtoupper(url($this->input->post('list_code')));
					
					# Check already exist start here
					$chkExist = $this->db->query("select list_type_value_id from sm_list_type_values 
						where 
							list_code='".$data['list_code']."' and
								list_type_id='".$id."'
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "List code already exist!");
						redirect(base_url() . 'lov/manageLov/ManageListTypeValues/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['list_value'] = $this->input->post('list_value');
					$data['order_sequence'] = $this->input->post('order_sequence');
					$data['short_description'] = $this->input->post('short_description');

					$data['active_flag'] = $this->active_flag;
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('sm_list_type_values', $data);
					$result = $this->db->insert_id();
					if($result !="")
					{
						if( isset($_FILES['upload_image']['name']) && $_FILES['upload_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['upload_image']['tmp_name'], 'uploads/lov_images/'.$result. '.png');
						}

						$this->session->set_flashdata('flash_message' , 'List type value added successfully!');
						redirect(base_url() . 'lov/manageLov/ManageListTypeValues/'.$id, 'refresh');
					}
				}
				else if(isset($_POST['Update']) && $_POST['Update'] == 'Update')
				{
					/* print_r($_FILES);
						exit; */
						
					$list_type_value_id = $this->input->post('list_type_value_id');
					
					$data['list_code'] = strtoupper(url($this->input->post('list_code')));
					
					# Check already exist start here
					$chkExist = $this->db->query("select list_type_value_id from sm_list_type_values 
						where 
							list_code='".$data['list_code']."' and
								list_type_id='".$id."' and
								list_type_value_id !='".$list_type_value_id."'
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "List code already exist!");
						redirect(base_url() . 'lov/manageLov/ManageListTypeValues/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['list_value'] = $this->input->post('list_value');
					$data['order_sequence'] = $this->input->post('order_sequence');
					$data['short_description'] = $this->input->post('short_description');

					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					$this->db->where('list_type_value_id', $list_type_value_id);
					$result = $this->db->update('sm_list_type_values', $data);
					
					if($result !="")
					{
						
						if( isset($_FILES['upload_image']['name']) && $_FILES['upload_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['upload_image']['tmp_name'], 'uploads/lov_images/'.$list_type_value_id. '.png');
						}

						$this->session->set_flashdata('flash_message' , 'List type value updated successfully!');
						redirect(base_url() . 'lov/manageLov/ManageListTypeValues/'.$id, 'refresh');
					}
				}
				
				$totalResult = $this->lov_model->getListTypeValue("","",$id,$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'lov/manageLov/'.$type.'/'.$id;
				$base_url = base_url().$redirectURL;
				
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
				
				$page_data['resultData']  = $result= $this->lov_model->getListTypeValue($limit,$offset,$id,$this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
				#show start and ending Count
				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				$pageno = isset($pageNo) ? $pageNo :"";
				if($pageno==1 || $pageno==""){
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
			
			case ($type == "TypeValuestatus"): #Block & Unblock
				$list_type_value_id = $status;
				
				if($status1 == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['end_date'] = NULL;
					$succ_msg = 'List type active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['end_date'] = $this->date;
					$succ_msg = 'List type inactive successfully!';
				}

				$this->db->where('list_type_value_id', $list_type_value_id);
				$this->db->update('sm_list_type_values', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			default : #Manage

				$totalResult = $this->lov_model->getListType("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
			
				$page_data['edit_data'] = $this->db->get_where('sm_list_types', array('list_type_id' => $id))->result_array();
				
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'lov/manageLov?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('lov/manageLov?keywords='.$_GET['keywords'].'&active_flag='.$_GET['keywords'].'&active_flag='.$_GET['active_flag'].'');
				} else {
					$base_url = base_url('lov/manageLov?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $this->lov_model->getListType($limit, $offset,$this->pageCount);
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
				#show start and ending Count
				$total_counts = $total_count= 0;
				$pages=$page_data["starting"] = $page_data["ending"]="";
				$pageno = isset($pageNo) ? $pageNo :"";
				if($pageno==1 || $pageno==""){
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
