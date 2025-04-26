<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends CI_Controller 
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
	
	function ManageUsers($type = '', $id = '', $status = '', $status1 = '', $status2 = '', $status3 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageUsers'] = 1;
		$page_data['page_name']  = 'users/ManageUsers';
		$page_data['page_title'] = 'Users';
		
		switch(true)
		{
			case ($type == "add"): #add
				if($_POST)
				{
					$data['reg_user_type'] = isset($_POST['reg_user_type']) ? $_POST['reg_user_type'] :"";
					$data['person_id'] = isset($_POST['person_id']) ? $_POST['person_id'] :"";
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					
					$data['user_name'] = $_POST['user_name'];
					$data['password'] = md5($_POST['password']);
					$data['attribute1'] = $_POST['password'];

					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					
					#Audit Trails Add Start here
					$tableName = table_per_user;
					$menuName = users;
					$description = "Users created successsfully!";
					auditTrails(array_filter($data),$tableName,$type,$menuName,"",$description,"");
					#Audit Trails Add end here

					$this->db->insert('per_user', $data);
					$id = $this->db->insert_id();

					if($id)
					{
						# Add and Remove multiple roles start
						$role_id = isset($_POST['role_id']) ? count(array_filter($_POST['role_id'])) : 0;
					
						if( isset($_POST['role_id']) && $role_id > 0 )
						{
							$count=count($_POST['role_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['user_id'] = $id;
								$LineData['role_id'] = isset($_POST['role_id'][$dp]) ? $_POST['role_id'][$dp] :"";
								$LineData['active_flag'] = isset($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] :"";
								
								$LineData['created_by'] = $this->user_id;
								$LineData['created_date'] = $this->date_time;
								$LineData['last_updated_by'] = $this->user_id;
								$LineData['last_updated_date'] = $this->date_time;
								
								$this->db->insert('per_user_roles', $LineData);
								$lineID = $this->db->insert_id();
							}
						}
						#Add and Remove multiple roles end

						$this->session->set_flashdata('flash_message' , "User created successfully!");
						redirect(base_url() . 'users/ManageUsers/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #edit
				$edit_data = $page_data['edit_data'] = $this->db->get_where('per_user', array('user_id' => $id))
										->result_array();
				
				if($_POST)
				{				
					$data['reg_user_type'] = isset($_POST['reg_user_type']) ? $_POST['reg_user_type'] :"";
					$data['person_id'] = isset($_POST['person_id']) ? $_POST['person_id'] :"";
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					
					$data['user_name'] = $_POST['user_name'];
					$data['password'] = md5($_POST['password']);
					$data['attribute1'] = $_POST['password'];

					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					
					#Audit Trails Add Start here
					$tableName = table_per_user;
					$menuName = users;
					$description = "Users created successsfully!";
					auditTrails(array_filter($data),$tableName,$type,$menuName,$edit_data,$description,"");
					#Audit Trails Add end here
					
					$this->db->where('user_id', $id);
					$result = $this->db->update('per_user', $data);
					
					if($result)
					{
						# Add and Remove User Roles start
						$role_id = isset($_POST['role_id']) ? count(array_filter($_POST['role_id'])) : 0;
					
						if((isset($_POST['role_id']) && $role_id > 0 ))
						{					
							$this->db->where('user_id', $id);
							$this->db->delete('per_user_roles');
								
							$count = count($_POST['role_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['user_id'] = $id;
								$LineData['role_id'] = isset($_POST['role_id'][$dp]) ? $_POST['role_id'][$dp] :"";
								$LineData['active_flag'] = isset($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] :"";
								
								$LineData['created_by'] = $this->user_id;
								$LineData['created_date'] = $this->date_time;
								$LineData['last_updated_by'] = $this->user_id;
								$LineData['last_updated_date'] = $this->date_time;
								
								#Audit Trails Add Start here
								$tableName = table_per_user_roles;
								$menuName = roles;
								$description = "User role created successsfully!";
								auditTrails(array_filter($LineData),$tableName,$type,$menuName,$page_data['edit_data'],$description);
								#Audit Trails Edit end here

								$this->db->insert('per_user_roles', $LineData);
								$lineID = $this->db->insert_id();						
							}
						}
						else
						{
							$this->db->where('user_id', $id);
							$this->db->delete('per_user_roles');
							
							$count = isset($_POST['role_id']) ? count($_POST['role_id']) : 0;
							
							for($dp=0;$dp<$count;$dp++)
							{
								$LineData['user_id'] = $id;
								$LineData['role_id'] = isset($_POST['role_id'][$dp]) ? $_POST['role_id'][$dp] :"";
								$LineData['active_flag'] = isset($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] :"";
								
								$LineData['created_by'] = $this->user_id;
								$LineData['created_date'] = $this->date_time;
								$LineData['last_updated_by'] = $this->user_id;
								$LineData['last_updated_date'] = $this->date_time;
								
								#Audit Trails Add Start here
								$tableName = table_per_user_roles;
								$menuName = roles;
								$description = "User role created successsfully!";
								auditTrails(array_filter($LineData),$tableName,$type,$menuName,$page_data['edit_data'],$description);
								#Audit Trails Edit end here

								$this->db->insert('per_user_roles', $LineData);
								$lineID = $this->db->insert_id();	
							}
						}
						# Add and Remove User Roles end

						$this->session->set_flashdata('flash_message' , "User saved successfully!");
						redirect(base_url() . 'users/ManageUsers/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case ($type == "delete"): #delete
				$this->db->where('user_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Users deleted successfully!");
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case ($type == "status"): #status
				if($status == "Y"){
					$data['active_flag'] = "Y";
					$succ_msg = 'Users active successfully!';
				}else{
					$data['active_flag'] = "N";
					$succ_msg = 'Users Inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = table_per_user;
				$menuName = users;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('user_id', $id);
				$this->db->update('per_user', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			case ($type == "login_status"): #status
				if($status == "N"){
					$data['last_login_status'] = "N";
					$succ_msg = 'Users active successfully!';
				}
				

				#Audit Trails Start here
				$tableName = table_per_user;
				$menuName = users;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('user_id', $id);
				$this->db->update('per_user', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			case ($type == "change_password"): #status
				$data['password']             = md5($this->input->post('password'));
				$data['new_password']         = md5($this->input->post('new_password'));
				$data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));
				
				$getCurrentPasswordQry = "select password from per_user where user_id='".$id."'";
				$getCurrentPassword = $this->db->query($getCurrentPasswordQry)->result_array();

				$current_password = isset($getCurrentPassword[0]["password"]) ? $getCurrentPassword[0]["password"] : "";
				
				#$current_password = $this->db->get_where('per_user', array('user_id' => $id))->row()->password;
				
				if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) 
				{
					$this->db->where('user_id', $id);
					$this->db->update('per_user', array('password' => $data['new_password'],'attribute1' => $_POST['new_password'] ));
					$this->session->set_flashdata('flash_message', 'Password changed successfully');
				} 
				else 
				{
					$this->session->set_flashdata('error_message', 'Password mismatch');
				}
				redirect(base_url() . 'users/ManageUsers', 'refresh');
			break;
			
			default : #Manage
				$totalResult = $this->users_model->getManageUsers("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				

				$user_type = isset($_GET['user_type']) ? $_GET['user_type'] :NULL;
				$user_id = isset($_GET['user_id']) ? $_GET['user_id'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$this->redirectURL = 'users/ManageUsers?user_type='.$user_type.'&user_id='.$user_id.'&active_flag='.$active_flag;
				
				if ( $user_type != NULL || $user_id != NULL || $active_flag != NULL  ) {
					$base_url = base_url().$this->redirectURL;
				} else {
					$base_url = base_url().$this->redirectURL;
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
				
				$page_data['resultData']  = $result= $this->users_model->getManageUsers($limit,$offset,$this->pageCount);
				
				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
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
	
	public function UsernameExist()
	{
		if ( isset($_POST['user_name_check']) && $_POST['user_name_check'] == 1) 
		{
			$user_name = $_POST['user_name'];
			#$register_type = $_POST['register_type'];
			
			$results = $this->db->query("select user_id from users WHERE user_name='".$user_name."' and register_type = 3 ")->result_array(); #and register_type='".$register_type."'
			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}

	public function getRoles($role_id="")
	{
		$data = $this->db->query('select role_id,role_name from org_roles where role_id="'.$role_id.'"')->result();
			
		$roleStatus = "<select class='form-control' id='role_status' name='role_status[]'>";
		foreach($this->role_status as $key => $value)
		{
			$roleStatus .="<option value='".$key."'>".$value."</option>";
		}
		$roleStatus .="</select>";
		$data['roleStatus'] = $roleStatus;
		
		echo json_encode($data);exit;
	}

	public function getUserDetails($user_id="")
	{
		$data = $this->db->query('select user_id,first_name,last_name,email,phone_number,mobile_number,random_user_id from users where user_id="'.$user_id.'"')->result();
		echo json_encode($data);exit;
	}

	function userAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			//$select_type_condition = "users.register_type = 2";
			
			$output = '';  
			
			$condition = ' per_people_all.user_status=1 and
				(
					per_people_all.first_name like "%'.($_POST["query"]).'%" or 
					per_people_all.last_name like "%'.($_POST["query"]).'%" or
					per_people_all.mobile_number like "%'.($_POST["query"]).'%" 
				)';

			$query = "select 
						per_people_all.first_name,
						per_people_all.person_id,
						per_people_all.mobile_number,
						per_people_all.email_address
						
						from per_people_all
						
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled-new">';  
			#$output .= '<li onclick="getuserId(0);">All</li>'; 
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["person_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.ucfirst($row["first_name"]).''.'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! No data found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;
		}
	}

	public function usersList($emp_id="")
	{
		$getAllEmployeeQry = "select * from per_people_all where person_id='".$emp_id."' ";
		
		$data['empData'] = $this->db->query($getAllEmployeeQry)->result();
		echo json_encode($data);
		exit;
	}

	public function checkUserExist()
	{
		if ( isset($_POST['user_id']) && $_POST['user_id'] ) 
		{
			$user_id = $_POST['user_id'];

			if (isset($_POST['id'])) 
			{
				$results = $this->db->query("select user_id from users 
					WHERE 
						role_user_id='".$user_id."' and 
								user_id != '".$_POST['id']."' and 
									register_type = 3
						")->result_array();
			}
			else 
			{
				$results = $this->db->query("select user_id from users 
					WHERE 
						role_user_id='".$user_id."' and register_type = 3
								")->result_array();
			}
			
			if ( count($results) > 0 ) 
			{
				echo "taken";	
			}
			else
			{
				echo 'not_taken';
			}
			exit();
		}
	}

	
	public function chkUserIdExist()
	{
		if ( isset($_POST['user_id_check']) && $_POST['user_id_check'] == 1) 
		{
			if ( isset($_POST['type']) && $_POST['type'] == "add") 
			{
				$user_user_id = isset($_POST['user_user_id']) ? $_POST['user_user_id'] : 0;
				$condition = "user_user_id='".$user_user_id."' and register_type=6";
			}
			else
			{
				$user_user_id = isset($_POST['user_user_id']) ? $_POST['user_user_id'] : 0;
				$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
				$condition = "user_user_id='".$user_user_id."' and user_id !='".$user_id."' and register_type=6";
			}
			
			$results = $this->db->query("select user_id from users WHERE $condition ")->result_array();
			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}

	public function ajaxSelectEmployee($emp_id="") 
	{
        /* $id = $_POST["id"];		
		
		if($id)
		{	 */
			/*		
			$data =  $this->db->query("select 
							per_people_all.person_id,
							per_people_all.first_name,	
							per_people_all.email	
							
							from per_people_all

									where 
									per_people_all.active_flag = 'Y' and 
									per_people_all.deleted_flag = 'N' and
									per_people_all.person_id ='".$id."'
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select PO -</option>';
				foreach($data as $val)
				{
					if(isset($val['note']) && !empty($val['note']))
					{
						echo '<option value="'.$val['purchase_id'].'">'.ucfirst($val['reference_no']).' - '.ucfirst($val['note']).'</option>';	
					}
					else
					{
						echo '<option value="'.$val['purchase_id'].'">'.ucfirst($val['reference_no']).'</option>';
					}
				}
			}
			else
			{
				echo '<option value="">No PO under this Vendor!</option>';
			}
			*/
			$getEmployeeQry = "select 
							per_people_all.person_id,
							per_people_all.first_name,	
							per_people_all.email_address	
							
							from per_people_all

									where 
									per_people_all.active_flag = 'Y' and 
									per_people_all.deleted_flag = 'N' and
									per_people_all.person_id ='".$emp_id."' ";

			/* $getEmployee = $this->db->query($getAllEmployeeQry)->result_array();	
			
			$email = isset($getEmployee["email_address"]) ? $getEmployee["email_address"] : "";
			echo $email;
			print_r($email); */	
			$data['empsData'] = $this->db->query($getEmployeeQry)->result();
			echo json_encode($data);
			exit;
		/* }
		die; */
    }

	public function ajaxCheckUserPassword() 
	{
		$id = $data['user_id']        = $this->input->post('user_id');
		$data['password']             = md5($this->input->post('password'));
		$data['new_password']         = md5($this->input->post('new_password'));
		$data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));
		
		$getCurrentPasswordQry = "select password from per_user where user_id='".$id."'";
		$getCurrentPassword = $this->db->query($getCurrentPasswordQry)->result_array();

		$current_password = isset($getCurrentPassword[0]["password"]) ? $getCurrentPassword[0]["password"] : "";
		
		if ($data['new_password'] == $data['confirm_new_password']) 
		{
			echo "1"; //matched
		} 
		else 
		{
			echo "2"; //mismatched
		}
		die();
	}

}
?>
