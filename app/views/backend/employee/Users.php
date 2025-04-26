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
		
		/* if(isset($_POST['delete']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$del_id=$_POST['checkbox'][$i];
				 
				$this->db->where('user_id', $del_id);
				$this->db->delete('users');
			}
			$this->session->set_flashdata('flash_message' , "Data deleted successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		} */
		
		switch($type)
		{
			case "add": #add
				if($_POST)
				{
					$data['role_user_id'] = $role_user_id = $this->input->post('new_user_id');

					$userQry = "select user_id from users where register_type=6 and role_user_id = '".$role_user_id."' ";
					$getUsers = $this->db->query($userQry)->result_array();

					if(count($getUsers) > 0)
					{
						$this->session->set_flashdata('error_message' , "User already exist!");
						redirect(base_url() . 'users/ManageUsers/add', 'refresh');
					}

					$data['user_user_id'] = $this->input->post('user_user_id');

					$data['first_name'] = $this->input->post('first_name');
					$data['last_name'] = $this->input->post('last_name');
					$data['email'] = $this->input->post('email');
					$data['phone_number'] = $this->input->post('phone_number');

					$data['register_type'] = 6; #User
					$data['joined_date'] = time();
					$data['user_status'] = 1;
					
					#Customer Login Details
					$data['user_name'] = $_POST['user_name'];
					$data['password'] = md5($_POST['password']);
					$data['original_password'] = $_POST['password'];
					
					$this->db->insert('users', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						#Users auto generate Start here
						$results = $this->db->query("select increment_id from users where register_type = '".$data['register_type']."' order by increment_id desc")->result_array();
						if( isset($results[0]['increment_id']) && $results[0]['increment_id'] == 0 )
						{
							$incrementID = 1;
						}
						else
						{
							$incID = isset($results[0]['increment_id']) ? $results[0]['increment_id'] : 0;
							$incrementID = $incID + 1; 
						}

						$getPrefix = $this->db->query("select prefix_name, prefix_length from org_prefix_length where prefix_type = 2")->result_array();
						
						$randomNumber = $getPrefix[0]['prefix_name'].str_pad($incrementID, $getPrefix[0]['prefix_length'], "0", STR_PAD_LEFT);
						
						$UpdateData['random_user_id'] = $randomNumber;
						$UpdateData['increment_id'] = $incrementID;
						
						$this->db->where('user_id', $id);
						$resultUpdateData = $this->db->update('users', $UpdateData);
						#Users auto generate end here

						# Add and Remove multiple roles start
						$role_id = isset($_POST['role_id']) ? count(array_filter($_POST['role_id'])) : 0;
					
						if( isset($_POST['role_id']) && $role_id > 0 )
						{
							$count=count($_POST['role_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['user_id'] = $id;
								$LineData['role_id'] = isset($_POST['role_id'][$dp]) ? $_POST['role_id'][$dp] :"";
								$LineData['user_role_status'] = isset($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] :"";
								$LineData['created_date'] = time();
								
								$this->db->insert('usr_user_roles', $LineData);
								$lineID = $this->db->insert_id();
							}
						}
						#Add and Remove multiple roles end

						$this->session->set_flashdata('flash_message' , "User created successfully!");
						redirect(base_url() . 'users/ManageUsers', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('users', array('user_id' => $id))
										->result_array();
				if($_POST)
				{					
					$data['role_user_id'] = $role_user_id = $this->input->post('new_user_id');

					$userQry = "select user_id from users where register_type=6 and 
									role_user_id = '".$role_user_id."' and user_id !='".$id."' ";
					$getUsers = $this->db->query($userQry)->result_array();

					if(count($getUsers) > 0)
					{
						$this->session->set_flashdata('error_message' , "User already exist!");
						redirect(base_url() . 'users/ManageUsers/edit/'.$id, 'refresh');
					}

					//$data['select_type'] = $this->input->post('select_type');
					$data['user_user_id'] = $this->input->post('user_user_id');
					$data['first_name'] = $this->input->post('first_name');
					$data['last_name'] = $this->input->post('last_name');
					$data['email'] = $this->input->post('email');
					$data['phone_number'] = $this->input->post('phone_number');

					$data['register_type'] = 6; #User
					$data['joined_date'] = time();
					$data['user_status'] = 1;
					
					#Customer Login Details
					$data['user_name'] = $_POST['user_name'];
					$data['password'] = md5($_POST['password']);
					$data['original_password'] = $_POST['password'];
					
					$this->db->where('user_id', $id);
					$result = $this->db->update('users', $data);
					
					if($result)
					{
						# Add and Remove User Roles start
						$role_id = isset($_POST['role_id']) ? count(array_filter($_POST['role_id'])) : 0;
					
						if((isset($_POST['role_id']) && $role_id > 0 ))
						{					
							$this->db->where('user_id', $id);
							$this->db->delete('usr_user_roles');
								
							$count = count($_POST['role_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['user_id'] = $id;
								$LineData['role_id'] = isset($_POST['role_id'][$dp]) ? $_POST['role_id'][$dp] :"";
								$LineData['user_role_status'] = isset($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] :"";
								$LineData['created_date'] = time();
								
								$this->db->insert('usr_user_roles', $LineData);
								$lineID = $this->db->insert_id();						
							}
						}
						else
						{
							$this->db->where('user_id', $id);
							$this->db->delete('usr_user_roles');
							
							$count = isset($_POST['role_id']) ? count($_POST['role_id']) : 0;
							
							for($dp=0;$dp<$count;$dp++)
							{
								$LineData['user_id'] = $id;
								$LineData['role_id'] = isset($_POST['role_id'][$dp]) ? $_POST['role_id'][$dp] :"";
								$LineData['user_role_status'] = isset($_POST['role_status'][$dp]) ? $_POST['role_status'][$dp] :"";
								$LineData['created_date'] = time();
								
								$this->db->insert('usr_user_roles', $LineData);
								$lineID = $this->db->insert_id();		
							}
						}
						# Add and Remove User Roles end

						$this->session->set_flashdata('flash_message' , "User updated successfully!");
						redirect(base_url() . 'users/ManageUsers/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('user_id', $id);
				$this->db->delete('users');
				
				$this->session->set_flashdata('flash_message' , "Users deleted successfully!");
				redirect(base_url() . 'users/ManageUsers', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['user_status'] = 1;
					$succ_msg = 'Users Active successfully!';
				}else{
					$data['user_status'] = 0;
					$succ_msg = 'Users Inactive successfully!';
				}
				$this->db->where('user_id', $id);
				$this->db->update('users', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'users/ManageUsers', 'refresh');
			break;
			
			case "change_password": #Block & Unblock
				$data['password']             = md5($this->input->post('password'));
				$data['new_password']         = md5($this->input->post('new_password'));
				$data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));

				$current_password = $this->db->get_where('users', array('user_id' => $id))->row()->password;
				
				if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) 
				{
					$this->db->where('user_id', $id);
					$this->db->update('users', array('password' => $data['new_password'],'original_password' => $_POST['new_password'] ));
					$this->session->set_flashdata('flash_message', get_phrase('password_changed_successfully'));
				} 
				else 
				{
					$this->session->set_flashdata('error_message', get_phrase('password_mismatch'));
				}
				redirect(base_url() . 'users/ManageUsers', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->users_model->getManageUsersCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('users/ManageUsers?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('users/ManageUsers?keywords=');
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
				
				$page_data['resultData']  = $result= $this->users_model->getManageUsers($limit, $offset);
				
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
			$select_type_condition = "users.register_type = 1";
			
			$output = '';  
			
			$condition = '
				users.user_status=1 and
				'.$select_type_condition.' and
				(
					users.first_name like "%'.($_POST["query"]).'%" or 
					users.last_name like "%'.($_POST["query"]).'%" or 
					users.random_user_id like "%'.($_POST["query"]).'%" or
					users.mobile_number like "%'.($_POST["query"]).'%" or
					users.phone_number like "%'.($_POST["query"]).'%"
				)';

			$query = "select 
						users.random_user_id,
						users.first_name,
						users.user_id,
						users.phone_number,
						users.mobile_number,
						users.email
						
						from users
						
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled-new">';  
			#$output .= '<li onclick="getuserId(0);">All</li>'; 
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["user_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["random_user_id"].' - '.ucfirst($row["first_name"]).''.'</li>';  
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

	public function usersList($user_id="")
	{
		$getAllEmployeeQry = "select * from users where user_id='".$user_id."' ";
		
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

}
?>
