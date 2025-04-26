<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Menus extends CI_Controller 
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
	
	#Manage Menus
    function manageMenus($type = '', $id = '', $status = '',$status_1='')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		
		$page_data['setups'] = 1;
		
		$page_data['page_name']  = 'menus/manageMenus';
		$page_data['page_title'] = 'Menus';
		
		switch($type)
		{
			case "add": #Add
				$page_data['postValue'] ='';
				if($_POST)
				{
					$data['menu_name'] = ucfirst($this->input->post('menu_name'));
					$data['menu_url'] = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select menu_id from org_menus 
						where 
							menu_name='".$data['menu_name']."' and 
								menu_layer= 1
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Menu already exist!");
						redirect(base_url() . 'menus/manageMenus', 'refresh');
					}
					# Check already exist end here
					
					$data['menu_description'] = $this->input->post('menu_description');
					$data['menu_layer'] = 1; #Main Menu ID
					$data['menu_status'] = 1;
					
					$this->db->insert('org_menus', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , 'Menu added successfully!');
						redirect(base_url() . 'menus/manageMenus', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('org_menus', array('menu_id' => $id))->result_array();
				if($_POST)
				{
					$data['menu_name'] = ucfirst($this->input->post('menu_name'));
					$data['menu_url'] = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select menu_id from org_menus 
						where 
							menu_name='".$data['menu_name']."' and
								menu_layer = 1 and
									menu_id !='".$id."'
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Menu already exist!");
						redirect(base_url() . 'menus/manageMenus/edit/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['menu_description'] = $this->input->post('menu_description');
					
					$this->db->where('menu_id', $id);
					$result = $this->db->update('org_menus', $data);
					
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , 'Menu updated successfully!');
						redirect(base_url() . 'menus/manageMenus', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('menu_id', $id);
				$this->db->delete('org_menus');
				$this->session->set_flashdata('flash_message' , 'Menu deleted successfully!');
				redirect(base_url() . 'menus/manageMenus', 'refresh');
			break;
			
			case "status": #Block & Unblock
				
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Menu inactive successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
                    $succ_msg = 'Menu active successfully!';
				}

				$this->db->where('menu_id', $id);
				$this->db->update('org_menus', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case "subMenu": #subMenu
				if(isset($_POST) && isset($_POST['add']))
				{
					$data['menu_name'] = ucfirst($this->input->post('menu_name'));
					$data['menu_url']  = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select menu_id from org_menus 
						where 
							menu_name='".$data['menu_name']."' and 
								menu_layer= 2
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sub Menu already exist!");
						redirect(base_url() . 'menus/manageMenus/subMenu/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['menu_description'] = $this->input->post('menu_description');
					$data['menu_layer'] = 2; #Sub Main Menu ID
					$data['main_menu_id'] = $id;
					$data['menu_status'] = 1;
					
					$this->db->insert('org_menus', $data);
					$id_1 = $this->db->insert_id();
					if($id_1 !="")
					{
						$this->session->set_flashdata('flash_message' , 'Sub Menu added successfully!');
						redirect(base_url() . 'menus/manageMenus/subMenu/'.$id, 'refresh');
					}
				}
				else if(isset($_POST) && isset($_POST['update']))
				{
					$menu_id = $this->input->post('menu_id');
					
					$data['menu_name'] = ucfirst($this->input->post('menu_name'));
					#$data['menu_url']  = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select menu_id from org_menus 
						where 
							menu_name='".$data['menu_name']."' and 
								main_menu_id ='".$id."' and 
									menu_id !='".$menu_id."' and
										menu_layer= 2
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sub Menu already exist!");
						redirect(base_url() . 'menus/manageMenus/subMenu/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['menu_description'] = $this->input->post('menu_description');
					
					$this->db->where('main_menu_id', $id);
					$this->db->where('menu_id', $menu_id);
					$id_1 = $this->db->update('org_menus', $data);
					
					if($id_1 !="")
					{
						$this->session->set_flashdata('flash_message' , 'Sub Menu updated successfully!');
						redirect(base_url() . 'menus/manageMenus/subMenu/'.$id, 'refresh');
					}
				}
			
				$menu_layer = 2;
				$page_data["totalRows"] = $totalRows = $this->menus_model->getSubMenusCount($id,$menu_layer);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'menus/manageMenus/subMenu/'.$id.'?keywords=';

				if (!empty($_GET['keywords'])) {
					$base_url = base_url('menus/manageMenus/subMenu/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('menus/manageMenus/subMenu/'.$id.'?keywords=');
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
				
				$page_data['resultData']  = $result= $this->menus_model->getSubMenus($limit, $offset,$id,$menu_layer);
				
				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
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
				
				$total_counts = $total_count + count($result);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
			
			case "sub_status": #Block & Unblock
				if($status_1 == 1){
					$data['menu_status'] = 1;
					$succ_msg = 'Sub Menu unblocked successfully!';
				}else{
					$data['menu_status'] = 0;
					$succ_msg = 'Sub Menu blocked successfully!';
				}
				$this->db->where('menu_id', $status);
				$this->db->update('org_menus', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'menus/manageMenus/subMenu/'.$id, 'refresh');
			break;
			
			case "secSubMenu": #Sec Sub Menu
				if(isset($_POST) && isset($_POST['add']))
				{
					$data['menu_name'] = ucfirst($this->input->post('menu_name'));
					$data['menu_url']  = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select menu_id from org_menus 
						where 
							menu_name='".$data['menu_name']."' and 
								menu_layer = 3
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sub Menu already exist!");
						redirect(base_url() . 'menus/manageMenus/secSubMenu/'.$id.'/'.$status, 'refresh');
					}
					# Check already exist end here
					
					$data['menu_description'] = $this->input->post('menu_description');
					$data['menu_layer'] = 3; #Sub Main Menu ID
					$data['main_menu_id'] = $status;
					$data['menu_status'] = 1;
					
					$this->db->insert('org_menus', $data);
					$id_1 = $this->db->insert_id();
					if($id_1 !="")
					{
						$this->session->set_flashdata('flash_message' , 'Sec Sub Menu Sub Menu added successfully!');
						redirect(base_url() . 'menus/manageMenus/secSubMenu/'.$id.'/'.$status, 'refresh');
					}
				}
				else if(isset($_POST) && isset($_POST['update']))
				{
					$menu_id = $this->input->post('menu_id');
					
					$data['menu_name'] = ucfirst($this->input->post('menu_name'));
					#$data['menu_url']  = url($this->input->post('menu_name'));
					
					# Check already exist start here
					$chkExist = $this->db->query("select menu_id from org_menus 
						where 
							menu_name='".$data['menu_name']."' and 
								main_menu_id ='".$status."' and 
									menu_id !='".$menu_id."' and
										menu_layer= 3
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sec Sub Menu already exist!");
						redirect(base_url() . 'menus/manageMenus/secSubMenu/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$data['menu_description'] = $this->input->post('menu_description');
					
					$this->db->where('main_menu_id', $status);
					$this->db->where('menu_id', $menu_id);
					$id_1 = $this->db->update('org_menus', $data);
					
					if($id_1 !="")
					{
						$this->session->set_flashdata('flash_message' , 'Sec Sub Menu updated successfully!');
						redirect(base_url() . 'menus/manageMenus/secSubMenu/'.$id.'/'.$status, 'refresh');
					}
				}
			
				$menu_layer = 3;
				$page_data["totalRows"] = $totalRows = $this->menus_model->getSubMenusCount($status,$menu_layer);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('menus/manageMenus/secSubMenu/'.$id.'/'.$status.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('menus/manageMenus/secSubMenu/'.$id.'/'.$status.'?keywords=');
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
				
				$page_data['resultData']  = $result= $this->menus_model->getSubMenus($limit, $offset,$status,$menu_layer);
				
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
			
			default :	
				$totalResult = $this->menus_model->getMenus("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'menus/manageMenus?keywords=&active_flag='.$active_flag;
				
				if (!empty($_GET['keywords']) || !empty($_GET['active_flag']) ) 
				{
					$base_url = base_url('menus/manageMenus?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('menus/manageMenus?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $this->menus_model->getMenus($limit, $offset,$this->pageCount);
				
				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
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
				
				$total_counts = $total_count + count($result);
				$page_data["ending"]  = $total_counts;
				#show start and ending Count end
			break;
		}
		$this->load->view($this->adminTemplate, $page_data);
	}
}
?>
