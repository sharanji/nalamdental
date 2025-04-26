<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Roles extends CI_Controller 
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
    function manageRoles($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		
		$page_data['page_name']  = 'roles/manageRoles';
		$page_data['page_title'] = 'Manage Roles';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['role_name'] = $this->input->post('role_name');
					$data['role_code'] = url($this->input->post('role_name'));
					$data['role_description'] = $this->input->post('role_description');
					$data['role_status'] = 1;
					
					# Role exist start here
						$chkExistRole = $this->db->query("select role_id from org_roles
							where 
								role_name='".$data['role_name']."' 
								")->result_array();
								
						if(count($chkExistRole) > 0)
						{
							$this->session->set_flashdata('error_message' , "Role already exist!");
							redirect(base_url() . 'roles/manageRoles/', 'refresh');
						}
					# Role Category exist end here
					
					$this->db->insert('org_roles', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Role created successfully!");
						redirect(base_url() . 'roles/manageRoles', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->query("select * from org_roles 
					where role_id='".$id."' ")->result_array();
				
				if($_POST)
				{
					
					$data['role_name'] = $this->input->post('role_name');
					$data['role_code'] = url($this->input->post('role_name'));
					$data['role_description'] 	= $this->input->post('role_description');
					$data['branch_id'] 			= isset($_POST['branch_id']) ? $_POST['branch_id'] :""; 
					
					# Role exist start here
					$chkExistRole = $this->db->query("select role_id from org_roles
						where 
							role_id !='".$id."'
							and	role_name='".$data['role_name']."' 
									")->result_array();
							
					if(count($chkExistRole) > 0)
					{
						$this->session->set_flashdata('error_message' , " Role Name already exist!");
						redirect(base_url() . 'roles/manageRoles/edit/'.$id, 'refresh');
					}
					# Role exist end here
					
					$this->db->where('role_id', $id);
					$result = $this->db->update('org_roles', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Role updated Successfully!");
						redirect(base_url() . 'role/ManageRole', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('role_id', $id);
				$this->db->delete('org_roles');
				
				$this->session->set_flashdata('flash_message' , "Task deleted successfully!");
				redirect(base_url() . 'roles/manageRoles', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Role Active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$succ_msg = 'Role Inactive successfully!';
				}
				$this->db->where('role_id', $id);
				$this->db->update('org_roles', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case "ManageRoleMenus": #ManageRoleMenus

				if(isset($_POST['Add']) && !empty($_POST['Add']))
				{
					$data['role_id'] = $id;
					$data['menu_id'] = $this->input->post('menu_id');
					$data['menu_enabled'] = $this->input->post('menu_enabled');
					$data['create_edit_only'] = $this->input->post('create_edit_only');
					$data['read_only'] = $this->input->post('read_only');
					
					# Check already exist start here
					$chkExist = $this->db->query("select role_item_id from org_roles_items 
						where 
							menu_id='".$data['menu_id']."' and
								role_id='".$id."' 
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Menu already exist in this role!");
						redirect(base_url() . 'roles/manageRoles/ManageRoleMenus/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$this->db->insert('org_roles_items', $data);
					$RoleMenuID = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , 'Role Menus created successfully!');
						redirect(base_url() . 'roles/manageRoles/ManageRoleMenus/'.$id, 'refresh');
					}
				}
				else if(isset($_POST['Update']) && !empty($_POST['Update']))
				{
					$role_item_id = $this->input->post('role_item_id');
					
					$data['menu_id'] = $this->input->post('menu_id');
					$data['menu_enabled'] = $this->input->post('menu_enabled');
					$data['read_only'] = $this->input->post('read_only');
					$data['create_edit_only'] = $this->input->post('create_edit_only');
					
					# Check already exist start here
					$chkExist = $this->db->query("select role_item_id from org_roles_items 
						where 
							menu_id='".$data['menu_id']."' and
								role_id='".$id."' and 
								role_item_id !='".$role_item_id."'
							")->result_array();
					
					if( count($chkExist) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Menu already exist in this role!");
						redirect(base_url() . 'roles/manageRoles/ManageRoleMenus/'.$id, 'refresh');
					}
					# Check already exist end here
					
					$this->db->where('role_item_id', $role_item_id);
					$result = $this->db->update('org_roles_items', $data);
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , 'Role Menus updated successfully!');
						redirect(base_url() . 'roles/manageRoles/ManageRoleMenus/'.$id, 'refresh');
					}
				}
				else if(isset($_POST['menu_enabled']) && !empty($_POST['menu_enabled']))
				{
					
					if($_POST)
					{
						#Sub Menus start
						$count_menu_id = $_POST['menu_id'];
						$count = count($count_menu_id);
						
						for($dp=0;$dp<$count;$dp++)
						{	
							$role_id = $id;
							$menu_id = $_POST['menu_id'][$dp];
							
							$chkExistQry = "select role_item_id from org_roles_items
							where 
								role_id='".$role_id."' and
									menu_id='".$menu_id."'
							";
							$chkExist = $this->db->query($chkExistQry)->result_array();
							
							if(count($chkExist) > 0)
							{
								/* $this->db->where('role_id', $role_id);
								$this->db->where('menu_id', $menu_id);
								$this->db->delete('org_roles_items');
								 */
								$data['menu_enabled'] = $_POST['menu_enabled'][$dp];
								$data['create_edit_only'] = isset($_POST['create_edit_only'][$dp]) ? $_POST['create_edit_only'][$dp] : 0;
								$data['read_only'] = isset($_POST['read_only'][$dp]) ? $_POST['read_only'][$dp] : 0;
								#print_r($data['create_edit_only']);exit;
								$this->db->where('role_id', $role_id);
								$this->db->where('menu_id', $menu_id);
								$this->db->update('org_roles_items', $data);
							}
							else
							{
								$data['role_id'] = $id;
								$data['menu_id'] = $_POST['menu_id'][$dp];
								
								$data['menu_enabled'] = $_POST['menu_enabled'][$dp];
								$data['create_edit_only'] = isset($_POST['create_edit_only'][$dp]) ? $_POST['create_edit_only'][$dp] : 0;
								$data['read_only'] = isset($_POST['read_only'][$dp]) ? $_POST['read_only'][$dp] : 0;
								
								$this->db->insert('org_roles_items', $data);
								$id_3 = $this->db->insert_id();
							}
						}
						#Sub Menus end
						
						#Sec Sub Menus start
						$sec_sub_menu_id = isset($_POST['sec_sub_menu_id']) ? count(array_filter($_POST['sec_sub_menu_id'])) : 0;
						$sec_sub_count = $sec_sub_menu_id;
						
						if($sec_sub_count > 0)
						{	
							for($dp1=0;$dp1<$sec_sub_count;$dp1++)
							{	
								$role_id = $id;
								$menu_id1 = $_POST['sec_sub_menu_id'][$dp1];
								
								$chkExistQry = "select role_item_id from org_roles_items
								where 
									role_id='".$role_id."' and
										menu_id='".$menu_id1."'
								";
								$chkExist = $this->db->query($chkExistQry)->result_array();
								if(count($chkExist) > 0)
								{
									/* $this->db->where('role_id', $role_id);
									$this->db->where('menu_id', $menu_id);
									$this->db->delete('org_roles_items');
									 */
									
									$datasub['menu_enabled'] = $_POST['sec_sub_menu_enabled'][$dp1];
									$datasub['create_edit_only'] = isset($_POST['sec_sub_create_edit_only'][$dp1]) ? $_POST['sec_sub_create_edit_only'][$dp1] : 0;
									$datasub['read_only'] = isset($_POST['sec_sub_read_only'][$dp1]) ? $_POST['sec_sub_read_only'][$dp1] : 0;
									
									$this->db->where('role_id', $role_id);
									$this->db->where('menu_id', $menu_id1);
									$this->db->update('org_roles_items', $datasub);
								}
								else
								{
									$datasub['role_id'] = $id;
									$datasub['menu_id'] = $_POST['sec_sub_menu_id'][$dp1];
									$datasub['menu_enabled'] = $_POST['sec_sub_menu_enabled'][$dp1];
									$datasub['create_edit_only'] = isset($_POST['sec_sub_create_edit_only'][$dp1]) ? $_POST['sec_sub_create_edit_only'][$dp1] : 0;
									$datasub['read_only'] = isset($_POST['sec_sub_read_only'][$dp1]) ? $_POST['sec_sub_read_only'][$dp1] : 0;
									
									$this->db->insert('org_roles_items', $datasub);
									$id_4 = $this->db->insert_id();
								}
							}
						}
						#Sec Sub Menus end
						
						$this->session->set_flashdata('flash_message' , 'Role Sub Menus updated successfully!');
						#redirect(base_url() . 'roles/manageRoles/ManageRoleMenus/'.$id, 'refresh');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
				}
				
				$page_data["totalRows"] = $totalRows = $this->roles_model->getRolesMenusCount($id);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'roles/manageRoles/ManageRoleMenus/'.$id.'?keywords=';

				if (!empty($_GET['keywords'])) {
					$base_url = base_url('roles/manageRoles/ManageRoleMenus/'.$id.'?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('roles/manageRoles/ManageRoleMenus/'.$id.'?keywords=');
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
				
				$page_data['resultData']  = $result= $this->roles_model->getRolesMenus($limit, $offset,$id);
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
			
			case "deleteRoleMenus": #deleteRoleMenus
				$role_item_id = $id;
				$role_id = $status;
				
				$this->db->where('role_item_id', $role_item_id);
				$this->db->where('role_id', $role_id);
				$this->db->delete('org_roles_items');
				
				$this->session->set_flashdata('flash_message' , 'Role menu deleted successfully!');
				redirect(base_url() . 'roles/manageRoles/ManageRoleMenus/'.$role_id, 'refresh');
			break;
			
			default : #Manage
				if(isset($_POST['Update']) && !empty($_POST['Update']))
				{
					$role_id = $this->input->post('role_id');
					$data['role_name'] = $this->input->post('role_name');
					$data['role_code'] = url($this->input->post('role_name'));
					$data['role_description'] = $this->input->post('role_description');
					$data['organization_id']    = isset($_POST['organization_id']) ? $_POST['organization_id'] : NULL; 
					$data['branch_id'] 	        = isset($_POST['branch_id']) ? $_POST['branch_id'] : NULL; 

					# Check already exist start here
					$chkExistrolename = $this->db->query("select role_id from org_roles
							where 
								role_id !='".$role_id ."' and
									role_name='".$data['role_name']."' and 
									branch_id='".$data['branch_id']."'
										")->result_array();
								
						if(count($chkExistrolename) > 0)
						{
							$this->session->set_flashdata('error_message' , " Role already exist!");
							redirect(base_url() . 'roles/manageRoles', 'refresh');
						}
					# Check already exist end here
					
					$this->db->where('role_id', $role_id);
					$result = $this->db->update('org_roles', $data);
					
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message','Role updated successfully!');
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
				}
				
				$totalResult = $this->roles_model->getRoles("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'roles/manageRoles?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('roles/manageRoles?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag'].'');
				} else {
					$base_url = base_url('roles/manageRoles?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $this->roles_model->getRoles($limit,$offset,$this->pageCount);
				
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
	
	public function getMenus($menu_id="")
	{
		if($menu_id == 0) #All
		{
			$data = $this->db->select('
						menu_id,
						menu_name'
					)
			->from('org_menus')
			#->where('menu_id',$menu_id)
			->where('menu_status',1)
			->get()
			->result();
		}
		else
		{
			$data = $this->db->select('
						menu_id,
						menu_name'
					)
			->from('org_menus')
			->where('menu_id',$menu_id)
			->where('menu_status',1)
			->get()
			->result();
		}
		echo json_encode($data);
	}
	
	public function viewRoleDetails($role_id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['id'] = $role_id;
		$page_data['manage_settings'] = 1;
		$page_data['page_name']  = 'roles/viewRoleDetails';
		$page_data['page_title'] = 'View Role Details';
		$this->load->view($this->adminTemplate, $page_data);
	}
}
?>
