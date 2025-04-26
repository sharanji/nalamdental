<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
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
	
	# Admin Login
	public function adminLogin()
	{
		$page_data['page_title'] = SITE_NAME.' | Admin Login'; #Page Title
		
		if($_POST)
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			
			$status = $this->admin_model->adminLogin($email,$password);

			if($status == 10)
			{
				$this->session->set_flashdata('success_message' , 'Successfully logged!');

				if( isset($_SESSION["user_id"]) && $_SESSION["user_id"] == 1)
				{
					redirect(base_url() . 'admin/dashboard', 'refresh');
				}
				else
				{
					$getUserRoles = $this->db->query("select role_id from per_user_roles where user_id = '".$_SESSION["user_id"]."'")->result_array();
					$userRoles = isset($getUserRoles[0]) ? $getUserRoles[0] : NULL;

					$role_id = isset($getUserRoles[0]["role_id"]) ? $getUserRoles[0]["role_id"] : NULL;
					$_SESSION["DINE_IN_ROLE_ID"] = $role_id;
					
					if($userRoles != NULL)
					{
						if (in_array(5, $getUserRoles[0])) #5 => Waiter
						{
							$this->session->set_userdata('WAITER_LOGIN','Y');
							$this->session->set_userdata('WebUserID',$_SESSION["user_id"]);
							redirect(base_url() . 'items.html', 'refresh');
						}
						else if ( in_array(3, $getUserRoles[0]) || in_array(3, $userRoles) ) #3 => if Cashier login send to mail admin
						{
							$userQry = "select 
								emp.first_name,
								emp.email_address from per_user as user

							left join per_people_all as emp on
								emp.person_id = user.person_id

							WHERE user.user_id = '".$_SESSION["user_id"]."'";
							$getUserData = $this->db->query($userQry)->result_array();
							
							if (count($getUserData) > 0)
							{
								if(isset($getUserData[0]["email_address"]) && !empty($getUserData[0]["email_address"]))
								{
									$from_email = $getUserData[0]["email_address"];
								}
								else
								{
									$from_email = NOREPLY_EMAIL;
								}
							   
								$from = NOREPLY_EMAIL;
								//$from = $from_email;
								$to = CONTACT_EMAIL;
								
								$fromName = isset($getUserData[0]["first_name"]) ? $getUserData[0]["first_name"] : NULL;
								$subject = "Cashier login";
								$page_data['login_time'] = date("d-M-Y h:i:s A");
								$page_data['cashier_login'] = 1;
								$page_data['cashier_name'] = $fromName;
								$page_data['subject'] = $subject;
								$message = $this->load->view('mail_template/admin_mail_template', $page_data, true);
								
								if (EMAIL_TYPE == 2) { 
									$sendMail = Send_SMTP($from, $to, $subject, $message, $fromName);
								} else { 
									$sendMail = Send_Grid($from,  $to, $subject, $message, $fromName);
								}	
							}
							redirect(base_url() . 'fine-dine-items.html', 'refresh');
						}
						else
						{
							redirect(base_url() . 'admin/home', 'refresh');
						}			
					}
					else
					{
						redirect(base_url() . 'admin/home', 'refresh');
					}	
				}

				/* $data = array(
					'user_id'       => isset($_SESSION["user_id"]) ? $_SESSION["user_id"] :0,
					'branch_id'     => isset($_SESSION["admin_branch_id"]) ? $_SESSION["admin_branch_id"] :0,
					'login_type'    => 1, #1=>Admin Panel
					'device_type'   => 1, #1=>Web
					'audit_date'    => date("Y-m-d"),
					'created_date'  => time(),
				);
				
				$this->db->insert('org_login_audits', $data);
				$id = $this->db->insert_id(); */
			}
			else if($status == 9)
			{
				$this->session->set_flashdata('error_message' , 'Your account has been Blocked!');
				redirect(base_url() . 'admin/adminLogin', 'refresh');
			}
			else if($status == 8)
			{
				$this->session->set_flashdata('error_message' , 'Email does not exist!');
				redirect(base_url() . 'admin/adminLogin', 'refresh');
			}
			else if($status == 0)
			{
				$this->session->set_flashdata('error_message' , 'Email or Password does not match!');
				redirect(base_url() . 'admin/adminLogin', 'refresh');
			}
			else if($status == 1)
			{
				$this->session->set_flashdata('error_message' , 'Sorry, already you Logged in some other system!');
				redirect(base_url() . 'admin/adminLogin', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error_message' , "Can't login this time!");
				redirect(base_url(), 'refresh');
			}
		}

		if(isset($this->user_id) && !empty($this->user_id))
		{
			if( $this->user_id == 1)
			{
				redirect(base_url() . 'admin/dashboard', 'refresh');
			}
			else
			{
				$getUserRoles = $this->db->query("select role_id from per_user_roles where user_id = '".$this->user_id."'")->result_array();
				$userRoles = isset($getUserRoles[0]) ? $getUserRoles[0] : NULL;

				$role_id = isset($getUserRoles[0]["role_id"]) ? $getUserRoles[0]["role_id"] : NULL;
				$_SESSION["DINE_IN_ROLE_ID"] = $role_id;
				
				if($userRoles != NULL)
				{
					if (in_array(5, $getUserRoles[0])) #5 => Waiter
					{
						$this->session->set_userdata('WAITER_LOGIN','Y');
						$this->session->set_userdata('WebUserID',$this->user_id);
						redirect(base_url() . 'items.html', 'refresh');
					}
					else if ( in_array(3, $getUserRoles[0]) || in_array(3, $userRoles) ) #3 => if Cashier login send to mail admin
					{
						redirect(base_url() . 'fine-dine-items.html', 'refresh');
					}
					else
					{
						redirect(base_url() . 'admin/home', 'refresh');
					}			
				}
				else
				{
					redirect(base_url() . 'admin/home', 'refresh');
				}	
			}
		}

		$this->load->view('backend/admin/login');
	}
	
	#Home Page
	public function home()
	{
		/* if(empty($this->user_id)){
			 redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		else if(!empty($this->user_id) && $this->user_id == 1)
		{
			redirect(base_url() . 'admin/dashboard', 'refresh');
	   	} */
		redirect(base_url() . 'admin/dashboard', 'refresh');
		$page_data['dashboard'] = 1;
		$page_data['page_name']  = "dashboards/home_page"; #View Page
		$page_data['page_title'] ='Home'; #Page Title
		$this->load->view($this->adminTemplate, $page_data);
	}

	#Admin Dashboard
	public function dashboard()
	{
		
		if( empty($this->user_id) )
		{
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}


		/* if(empty($this->user_id)){
			 redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		else if(!empty($this->user_id) && $this->user_id > 1)
		{
			redirect(base_url() . 'admin/home', 'refresh');
	   	} */
		   

		$_SESSION["MODULE_ACCESS"] = 'admin_dashboard';

		$page_data['dashboard'] = 1;
		$page_data['page_name']  = "admin/dashboard"; #View Page
		$page_data['page_title'] = 'Dashboard'; #Page Title
		
		$this->load->view($this->adminTemplate, $page_data);
	}

	#Online Order Dashboard
	public function online_order_dashboard()
	{
		if(empty($this->user_id)){
			 redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		else if(!empty($this->user_id) && $this->user_id == 1)
		{
			redirect(base_url() . 'admin/dashboard', 'refresh');
	   	}

		$_SESSION["MODULE_ACCESS"] = 'online_order_dashboard';
		$page_data['dashboard'] = 1;
		$page_data['page_name']  = "dashboards/online_order_dashboard"; #View Page
		$page_data['page_title'] = 'Dashboard'; #Page Title
		$this->load->view($this->adminTemplate, $page_data);
	}

	#Inventory Dashboard
	public function inventory_dashboard()
	{
		if(empty($this->user_id)){
			 redirect(base_url() . 'admin/adminLogin', 'refresh');
		}else if(!empty($this->user_id) && $this->user_id == 1) {
			redirect(base_url() . 'admin/dashboard', 'refresh');
	   	}

		$_SESSION["MODULE_ACCESS"] = 'inventory_dashboard';
		$page_data['dashboard'] = 1;
		$page_data['page_name']  = "dashboards/inventory_dashboard"; #View Page
		$page_data['page_title'] = 'Dashboard'; #Page Title
		$this->load->view($this->adminTemplate, $page_data);
	}

	#POS Dashboard
	public function pos_dashboard()
	{
		if(empty($this->user_id)){
			 redirect(base_url() . 'admin/adminLogin', 'refresh');
		}else if(!empty($this->user_id) && $this->user_id == 1) {
			redirect(base_url() . 'admin/dashboard', 'refresh');
	   	}

		$_SESSION["MODULE_ACCESS"] = 'pos_dashboard';
		$page_data['dashboard'] = 1;
		$page_data['page_name']  = "dashboards/pos_dashboard"; #View Page
		$page_data['page_title'] = 'Dashboard'; #Page Title
		$this->load->view($this->adminTemplate, $page_data);
	}

	#Dining Dashboard
	public function dining_dashboard()
	{
		if(empty($this->user_id)){
			 redirect(base_url() . 'admin/adminLogin', 'refresh');
		}else if(!empty($this->user_id) && $this->user_id == 1) {
			redirect(base_url() . 'admin/dashboard', 'refresh');
	   	}

		$_SESSION["MODULE_ACCESS"] = 'dining_dashboard';

		$page_data['dashboard'] = 1;
		$page_data['page_name']  = "dashboards/dining_dashboard"; #View Page
		$page_data['page_title'] ='Dashboard'; #Page Title
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	# General Settings
    function system_settings($type = '', $id = '', $status = '')
    {
		if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
		
		$page_data['manage_settings'] = 1;
		$page_data['page_name']  = 'admin/general_settings';
        $page_data['page_title'] = SITE_NAME.' | General Settings';
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $page_data['type']   = $type;
		
		switch($type)
		{
			case 'general-settings':
				if($_POST)
				{
					$data['description'] = $this->input->post('system_name');
					$this->db->where('type' , 'system_name');
					$this->db->update('settings' , $data);

					$data['description'] = $this->input->post('system_title');
					$this->db->where('type' , 'system_title');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('company_name');
					$this->db->where('type' , 'company_name');
					$this->db->update('settings' , $data);

					$data['description'] = $_POST['gst_number'];
					$this->db->where('type' , 'gst_number');
					$this->db->update('settings' , $data);

					$data['description'] = $_POST['fssai_number'];
					$this->db->where('type' , 'fssai_number');
					$this->db->update('settings' , $data);

					$data['description'] = $_POST['license_number'];
					$this->db->where('type' , 'license_number');
					$this->db->update('settings' , $data);

					$data['description'] = $_POST['gst_state_number'];
					$this->db->where('type' , 'gst_state_number');
					$this->db->update('settings' , $data);
					
					$data['description'] = $_POST['company_account'];
					$this->db->where('type' , 'company_account');
					$this->db->update('settings' , $data);
					
					$data['description'] = $_POST['cin_number'];
					$this->db->where('type' , 'cin_number');
					$this->db->update('settings' , $data);
					
					/*  $data['description'] = $this->input->post('welcome_content');
					$this->db->where('type' , 'welcome_content');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('company_youtube_url');
					$this->db->where('type' , 'company_youtube_url');
					$this->db->update('settings' , $data);
					
					$data['description'] = strtoupper($_POST['backup_morning']);
					$this->db->where('type' , 'backup_morning');
					$this->db->update('settings' , $data);
					
					$data['description'] = strtoupper($_POST['backup_evening']);
					$this->db->where('type' , 'backup_evening');
					$this->db->update('settings' , $data);
					
					

					$data['description'] = $_POST['opening_hours'];
					$this->db->where('type' , 'opening_hours');
					$this->db->update('settings' , $data);

					$data['description'] = $_POST['latitude'];
					$this->db->where('type' , 'latitude');
					$this->db->update('settings' , $data);

					$data['description'] = $_POST['longitude'];
					$this->db->where('type' , 'longitude');
					$this->db->update('settings' , $data); */

					/* $data['description'] = $_POST['birthday_message'];
					$this->db->where('type' , 'birthday_message');
					$this->db->update('settings' , $data);
					 */
					
					$this->session->set_flashdata('flash_message' , 'General settings updated successfully!');
					redirect(base_url() . 'admin/system_settings/general-settings', 'refresh');
				}
			break;	
			
			case 'email-contact-settings':
				if($_POST)
				{
					$data['description'] = $this->input->post('contact_email');
					$this->db->where('type' , 'contact_email');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('webmaster_email');
					$this->db->where('type' , 'webmaster_email');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('no_reply_email');
					$this->db->where('type' , 'no_reply_email');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('address');
					$this->db->where('type' , 'address');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('address2');
					$this->db->where('type' , 'address2');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('address3');
					$this->db->where('type' , 'address3');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('address4');
					$this->db->where('type' , 'address4');
					$this->db->update('settings' , $data);

					$data['description'] = $this->input->post('phone');
					$this->db->where('type' , 'phone');
					$this->db->update('settings' , $data);
					
					$data['description'] = $this->input->post('phone2');
					$this->db->where('type' , 'phone2');
					$this->db->update('settings' , $data);
					
					$this->session->set_flashdata('flash_message' , 'E-Mail & Contact settings updated successfully!');
					redirect(base_url() . 'admin/system_settings/email-contact-settings', 'refresh');
				}
			break;
			
			case 'image-settings':
				if( $_POST || $_FILES )
				{
					if( !empty($_FILES['userfile']['name']) )
					{  
						move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
						#compressImage($_FILES['userfile']['tmp_name'],'uploads/logo.png',60);
					}
					
					if( !empty($_FILES['userfile1']['name']) )
					{  
						move_uploaded_file($_FILES['userfile1']['tmp_name'], 'uploads/logo1.png');
						#compressImage($_FILES['userfile']['tmp_name'],'uploads/logo.png',60);
					}
					
					if( !empty($_FILES['favicon']['name']) )
					{  
						move_uploaded_file($_FILES['favicon']['tmp_name'], 'uploads/favicon.png');
						#compressImage($_FILES['favicon']['tmp_name'],'uploads/favicon.png',60);
					}
					
					if( !empty($_FILES['noimage']['name']) )
					{  
						move_uploaded_file($_FILES['noimage']['tmp_name'], 'uploads/no-image.png');
						#compressImage($_FILES['noimage']['tmp_name'],'uploads/no-image.png',60);
					}
					
					if( !empty($_FILES['backround_image']['name']) )
					{  
						move_uploaded_file($_FILES['backround_image']['tmp_name'], 'uploads/backround_image.png');
						#compressImage($_FILES['backround_image']['tmp_name'],'uploads/backround_image.png',60);
					}
					
					$this->session->set_flashdata('flash_message' , 'Image settings updated successfully!');
					redirect(base_url() . 'admin/system_settings/image-settings', 'refresh');
				}
			break;
			
			case 'currency-settings':
				$page_data['currency_data'] = $this->db->query('select * from system_settings where system_setting_id=1')->result_array();
				if($_POST)
				{
					$data['country_id'] = $this->input->post('country_id');
					$data['country_code'] = $this->input->post('country_code');
					$data['currency_symbol'] = $this->input->post('currency_symbol');
					$data['currency_code'] = $this->input->post('currency_code');
					
					$this->db->where('system_setting_id' , 1);
					$this->db->update('system_settings' , $data);
					
					#Currency updated 
					$country_data['default_country'] = 0;
					$result = $this->db->update('geo_countries', $country_data);
					
					if($result)
					{
						$country_id = $data['country_id'];
						$data_1['default_country'] = 1;
						$this->db->where('country_id', $country_id);
						$result1 = $this->db->update('geo_countries', $data_1);
					}
					#Currency updated 
					
					$this->session->set_flashdata('flash_message' , 'Currency setting updated successfully!');
					redirect(base_url() . 'admin/system_settings/currency-settings', 'refresh');
				}
			break;
			
			case 'system-settings':
				$page_data['currency_data'] = $this->db->query('select * from system_settings where system_setting_id=1')->result_array();
				if($_POST)
				{
					$data['decimal_digit_value'] = $this->input->post('decimal_digit_value');
					
					$data['auto_refresh_seconds'] 	  = $this->input->post('auto_refresh_seconds'); # Orders Auto Refresh
					$data['order_auto_print_timer']   = $this->input->post('order_auto_print_timer');
					$data['print_header_note']   = $this->input->post('print_header_note');
					$data['print_footer_note']   = $this->input->post('print_footer_note');

					$data['multi_login_access']   = $this->input->post('multi_login_access');
					$this->db->where('system_setting_id' , 1);
					$this->db->update('system_settings' , $data);
					
					$this->session->set_flashdata('flash_message' , 'System setting updated successfully!');
					redirect(base_url() . 'admin/system_settings/system-settings', 'refresh');
				}
			break;
			
			case 'theme':
				
				$page_data['manage_settings'] = 1;
				$page_data['page_title'] = SITE_NAME.' | Theme Selection';
				$page_data['breadcrumb'] = 'Theme Selection';
				if($_POST)
				{
					$data['description'] = $this->input->post('thems');
					$this->db->where('type' , 'thems');
					$this->db->update('settings' , $data);
					
					$this->session->set_flashdata('flash_message' , 'Theme Selection updated successfully!');
					redirect(base_url() . 'admin/system_settings/theme', 'refresh');
				}
			break;
			
			default:
			
			break;
			
		}
		
		$this->load->view($this->adminTemplate, $page_data);
    }
	
	#Social Media Settings
	function social_media_settings($param1 = '', $param2 = '', $param3 = '') 
	{
		if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
			
		if($_POST)
		{
			$this->frontend_model->updateSocialMediaSettings();
			$this->session->set_flashdata('flash_message' , 'Social Media Settings');
			redirect(base_url() . 'admin/social_media_settings', 'refresh');
		}
		
		$page_data['manage_settings'] = 1;
		
		$page_data['page_name'] = 'admin/social_media_settings';
		$page_data['page_title']  = SITE_NAME.' | Social Media Settings';
		$this->load->view($this->adminTemplate, $page_data);
    }
	
	function mailer_settings()
    {
		if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
		
		$page_data['manage_settings'] = 1;
		
		$page_data['page_name']  = 'admin/mailer_settings';
		$page_data['page_title'] = SITE_NAME.' | Mailer Settings';
		
		$page_data['edit_data'] = $this->db->get_where('email_settings', array('settings_id' => 1))
							->result_array();
		
		if($_POST)
		{
			$data['email_type'] = $_POST['email_type'];
			
			if($_POST['email_type'] == 1)
			{
				$data['sendgrid_host'] = $this->input->post('sendgrid_host');
				$data['sendgrid_port'] = $this->input->post('sendgrid_port');
				$data['sendgrid_username'] = $this->input->post('sendgrid_username');
				$data['sendgrid_password'] = $this->input->post('sendgrid_password');
			}
			else if($_POST['email_type'] == 2)
			{
				$data['smtp_host'] = $this->input->post('smtp_host');
				$data['smtp_port'] = $this->input->post('smtp_port');
				$data['smtp_username'] = $this->input->post('smtp_username');
				$data['smtp_password'] = $this->input->post('smtp_password');
			}
			
			$this->db->where('settings_id', 1);
			$result = $this->db->update('email_settings', $data);
			if($result > 0)
			{
				$this->session->set_flashdata('flash_message' , get_phrase('mailer_settings_updated_successfully'));
				redirect(base_url() . 'admin/mailer_settings/', 'refresh');
			}
		}
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	# Manage CMS
    function manage_cms($type = '', $id = '', $status = '')
    {
		if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manage_settings'] = 1;
		
		$page_data['page_name']  = 'admin/manage_cms';
		$page_data['page_title'] = 'CMS';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['cms_title'] = $this->input->post('cms_title');
					$data['cms_url'] = url($this->input->post('cms_title'));
					$data['cms_desc'] = $this->input->post('cms_desc');

					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					
					$this->db->insert('cms', $data);
					$id = $this->db->insert_id();

					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "CMS saved successfully!");
						redirect(base_url() . 'admin/manage_cms/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('cms', array('cms_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['cms_title'] = $this->input->post('cms_title');
					$data['cms_url'] = url($this->input->post('cms_title'));
					$data['cms_desc'] = $this->input->post('cms_desc');

					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					
					$this->db->where('cms_id', $id);
					$result = $this->db->update('cms', $data);
					
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , "CMS saved successfully!");
						redirect(base_url() . 'admin/manage_cms/edit/'.$id, 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('cms_id', $id);
				$this->db->delete('cms');
				$this->session->set_flashdata('flash_message' , get_phrase('cms_deleted_successfully!'));
				redirect(base_url() . 'admin/manage_cms/', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				/* if($status == 1){
					$data['cms_status'] = 1;
					$succ_msg = 'cms_unblocked_successfully!';
				}else{
					$data['cms_status'] = 0;
					$succ_msg = 'cms_blocked_successfully!';
				}
				$this->db->where('cms_id', $id);
				$this->db->update('cms', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				redirect(base_url() . 'admin/manage_cms/', 'refresh'); */

				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					#$data['end_date'] = NULL;
					$succ_msg = 'CMS active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					#$data['end_date'] = $this->date;
					$succ_msg = 'CMS inactive successfully!';
				}
				$this->db->where('cms_id', $id);
				$this->db->update('cms', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			default : #Manage
				$page_data['cms'] = $this->admin_model->getCMS();
			break;
		}
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	# Banner settings
    function frontend_gallery($param1 = '', $param2 = '', $param3 = '') 
	{
		if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
			
		if ($param1 == 'add_gallery') 
		{
			$this->frontend_model->add_gallery();
			$this->session->set_flashdata('flash_message' , get_phrase('banner_added_successfully'));
			redirect(base_url() . 'admin/frontend_gallery', 'refresh');
	    }
	  
		if ($param1 == 'edit_gallery') 
		{
			$this->frontend_model->edit_gallery($param2);
			$this->session->set_flashdata('flash_message' , get_phrase('banner_updated_successfully'));
			redirect(base_url() . 'admin/frontend_gallery', 'refresh');
		}
	  
		if ($param1 == 'upload_images') {
			$this->frontend_model->add_gallery_images($param2);
			$this->session->set_flashdata('flash_message' , get_phrase('images_uploaded'));
			redirect(base_url().'admin/frontend_gallery/gallery_image/'.$param2, 'refresh');
		}

		if ($param1 == 'delete_image') {
			$this->frontend_model->delete_gallery_image($param2);
			$this->session->set_flashdata('flash_message' , get_phrase('images_deleted'));
			redirect(base_url().'admin/frontend_gallery/gallery_image/'.$param3, 'refresh');
		}
	  
		if ($param1 == 'delete') 
		{
			$this->frontend_model->delete_gallery($param2);
			$this->session->set_flashdata('flash_message' , get_phrase('banner_deleted'));
			redirect(base_url() . 'admin/frontend_gallery', 'refresh');
		}

		$page_data['page_name'] = 'admin/frontend_gallery';
		$page_data['page_title']  = get_phrase('manage_banner');
		$this->load->view($this->adminTemplate, $page_data);
    }
	
	function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $param1;
		
		if( !empty($_POST['editUserName']) )
		{	
			$user_id  = $this->input->post('user_id');
			$data1['user_name']  = $this->input->post('user_name');
			
			$this->db->where('user_id', $user_id);
			$this->db->update('users', $data1);
			
			$this->session->set_flashdata('flash_message', 'User Name updated successfully!');
			redirect(base_url() . 'admin/manage_profile', 'refresh');
		}
	
        if ($param1 == 'update_profile_info') 
		{
            $data['first_name']  = $this->input->post('first_name');
            $data['last_name']  = $this->input->post('last_name');
            $data['phone_number']  = $this->input->post('phone_number');
            $data['address1'] = $this->input->post('address1');
            #$data['address2'] = $this->input->post('address2');
            $data['country'] = $this->input->post('country');
            $data['city'] = $this->input->post('city');
            $data['email'] = $this->input->post('email');
			
            $data['gender'] = $this->input->post('gender');
            $data['marital_status'] = $this->input->post('marital_status');
            $data['date_of_birth'] = $this->input->post('date_of_birth');
            $data['website'] = $this->input->post('website');

            $admin_id = $param2;

            #$validation = email_validation_for_edit($data['email'], $admin_id, 'user');
			
			$this->db->where('user_id', $this->user_id);
			$this->db->update('users', $data);

			
			
			if(!empty($_FILES['userfile']['name']))
			{
				move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->user_id . '.png');
			}
			
			if(!empty($_FILES['coverfile']['name']))
			{
				move_uploaded_file($_FILES['coverfile']['tmp_name'], 'uploads/cover_image/' . $this->user_id . '.png');
			}
			
			$this->session->set_flashdata('flash_message', 'Profile updated successfully!');
			redirect(base_url() . 'admin/manage_profile/', 'refresh');
        }
		
		if ($param1 == 'change_password')
		{
            $data['password']             = md5($this->input->post('current_password'));
            $data['new_password']         = md5($this->input->post('new_password'));
            $data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));

            $current_password = $this->db->get_where('users', array('user_id' => $this->user_id))->row()->password;
			
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) 
			{
                $this->db->where('user_id', $this->user_id);
                $this->db->update('users', array('password' => $data['new_password']));
                $this->session->set_flashdata('flash_message', get_phrase('password_changed_successfully'));
            } 
			else 
			{
                $this->session->set_flashdata('error_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'admin/manage_profile/', 'refresh');
        }
		
		
        $page_data['page_name']  = 'admin/manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('users', array( 'user_id' => $this->user_id))->result_array();
        $this->load->view($this->adminTemplate, $page_data);
    }
	
	function change_password($param1 = '', $param2 = '', $param3 = '')
    {
        if(!$this->user_id)
		{
			redirect(base_url().'admin/adminLogin', 'refresh');
		}
			
        if ($param1 == 'change_password')
		{
			$currentNpassword = $this->input->post('current_password');

            $data['password']             = md5($this->input->post('current_password'));
            $data['new_password']         = md5($this->input->post('new_password'));
            $data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));

            $current_password = $this->db->get_where('per_user', array('user_id' => $this->user_id))->row()->attribute1;
			
            if ($current_password == $currentNpassword && $data['new_password'] == $data['confirm_new_password']) 
			{
                $this->db->where('user_id', $this->user_id);
                $this->db->update('per_user', array('password' => $data['new_password'],'attribute1' => $_POST['new_password'] ));
                $this->session->set_flashdata('flash_message', get_phrase('password_changed_successfully'));
            } 
			else 
			{
                $this->session->set_flashdata('error_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'admin/change_password/', 'refresh');
        }
        $page_data['page_name']  = 'admin/change_password';
        $page_data['page_title'] = SITE_NAME.' | '.get_phrase('change_password');
      
        $this->load->view($this->adminTemplate, $page_data);
    }
	
	public function logout() 
	{
		$user_id = $this->user_id;

		if($user_id)
		{
			// $multiLoginAccess = MULTI_LOGIN_ACCESS;

			// if($multiLoginAccess == 'YES') #Login Access
			// {
			// 	$data = array(
			// 		'last_login_status'  => 'N',
			// 		'logout_date'        => $this->date_time,
			// 		'last_updated_by'    => $user_id,
			// 		'last_updated_date'  => $this->date_time,
			// 	);
			// }
			// else if($multiLoginAccess =='NO')
			// {
				$data = array(
					'logout_date'        => $this->date_time,
					'last_updated_by'    => $user_id,
					'last_updated_date'  => $this->date_time,
				);
			// }


			$this->db->where('user_id', $user_id);
			$result = $this->db->update('per_user', $data);
		}
		

        $this->session->sess_destroy();
        redirect(base_url().'admin/adminLogin', 'refresh');
    }
	
	#Manage Language Known
    function manage_language_known($type = '', $id = '', $status = '')
    {
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/adminLogin', 'refresh');
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['master_data']  = 1;
		
		$page_data['page_name']  = 'admin/manage_language_known';
		$page_data['page_title'] = SITE_NAME.' | Manage Language known';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['language_known_name'] = $this->input->post('language_known_name');
					
					$data['language_known_status'] = 1;
					
					$this->db->insert('language_known', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , get_phrase('language_known_added_successfully'));
						redirect(base_url() . 'admin/manage_language_known', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('language_known', array('language_known_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['language_known_name'] = $this->input->post('language_known_name');
					
					$this->db->where('language_known_id', $id);
					$result = $this->db->update('language_known', $data);
					if($result > 0)
					{
						$this->session->set_flashdata('flash_message' , get_phrase('language_known_updated_successfully'));
						redirect(base_url() . 'admin/manage_language_known', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('language_known_id', $id);
				$this->db->delete('language_known');
				$this->session->set_flashdata('flash_message' , get_phrase('language_known_deleted_successfully!'));
				redirect(base_url() . 'admin/manage_language_known', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['language_known_status'] = 1;
					$succ_msg = 'language_known_unblocked_successfully!';
				}else{
					$data['language_known_status'] = 0;
					$succ_msg = 'language_known_blocked_successfully!';
				}
				$this->db->where('language_known_id', $id);
				$this->db->update('language_known', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				redirect(base_url() . 'admin/manage_language_known', 'refresh');
			break;
			
			default : #Manage
				$page_data['language_known'] = $this->db->query("select * from language_known")->result_array();
			break;
		}
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	public function sort_itemper_page($id="")
	{
		$page_number = $id;
		$redirecturl = isset($_GET['redirect'])?$_GET['redirect']:"";
		$_SESSION['PAGE']=$page_number;
		
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	
	#Manage Category
    function manage_category($type = '', $id = '', $status = '', $status1 = '')
    {
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/adminLogin', 'refresh');
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		$page_data['status1'] = $status1;

		$page_data['setups'] = 1;

		$page_data['page_name']  = 'admin/manage_category';
		$page_data['page_title'] = 'Categories';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['category_code'] = strtoupper($_POST['category_name']);
					$data['hsn_sac_code'] = $this->input->post('hsn_sac_code');
					$data['uom_id'] = $this->input->post('uom_id');
					$data['category_description'] = $this->input->post('category_description');
					$data['product_prefix'] = $this->input->post('product_prefix');

					$data['lot_number_prefix'] = $this->input->post('lot_number_prefix');
					$data['lot_number_prefix_length'] = $this->input->post('lot_number_prefix_length');
					$data['lot_starting_number'] = $this->input->post('lot_starting_number');

					/* $data['order_number'] = $this->input->post('order_number'); */
					$data['caytegory_layer'] = 1; #Main Category
					$data['category_status'] = 1;
					
					# Category exist start here
						$chkExistCategory = $this->db->query("select category_id from category
							where 
								category_name='".$data['category_name']."' 
								")->result_array();
								
						if(count($chkExistCategory) > 0)
						{
							$this->session->set_flashdata('error_message' , " Category Name already exist!");
							redirect(base_url() . 'admin/manage_category/add', 'refresh');
						}
					# Category exist end here
					
					$this->db->insert('category', $data);
					$id = $this->db->insert_id();
					if($id !="")
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id . '.png');
						}
						$this->session->set_flashdata('flash_message' , get_phrase('category_added_successfully'));
						redirect(base_url() . 'admin/manage_category', 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('category', array('category_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['category_code'] = strtoupper(url($_POST['category_name']));
					$data['hsn_sac_code'] = $this->input->post('hsn_sac_code');
					$data['uom_id'] = $this->input->post('uom_id');
					$data['category_description'] = $this->input->post('category_description');
					$data['product_prefix'] = $this->input->post('product_prefix');
					$data['lot_number_prefix'] = $this->input->post('lot_number_prefix');
					$data['lot_number_prefix_length'] = $this->input->post('lot_number_prefix_length');
					$data['lot_starting_number'] = $this->input->post('lot_starting_number');

					/* $data['order_number'] = $this->input->post('order_number'); */
					
					# Category exist start here
						$chkExistCategory = $this->db->query("select category_id from category
							where 
									category_id !='".$id."' and
								(category_name='".$data['category_name']."')
								")->result_array();
								
						if(count($chkExistCategory) > 0)
						{
							$this->session->set_flashdata('error_message' , " Category Name already exist!");
							redirect(base_url() . 'admin/manage_category/edit', 'refresh');
						}
					# Category exist end here
					
					$this->db->where('category_id', $id);
					$result = $this->db->update('category', $data);
					if($result > 0)
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id . '.png');
						}
						
						$this->session->set_flashdata('flash_message' , get_phrase('category_updated_successfully'));
						redirect(base_url() . 'admin/manage_category', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('category_id', $id);
				$this->db->delete('category');
				$this->session->set_flashdata('flash_message' , get_phrase('category_deleted_successfully!'));
				redirect(base_url() . 'admin/manage_category/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['category_status'] = 1;
					$succ_msg = 'category_unblocked_successfully!';
				}else{
					$data['category_status'] = 0;
					$succ_msg = 'category_blocked_successfully!';
				}
				$this->db->where('category_id', $id);
				$this->db->update('category', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				redirect(base_url() . 'admin/manage_category/', 'refresh');
			break;
			
			#Sub category start
			case "add_subcategory": #Add Subcategory
				if($_POST)
				{
					$data['main_category_id'] = $id;
					$data['category_name'] = $this->input->post('sub_category_name');
					/* $data['order_number'] = $this->input->post('order_number'); */
					$data['caytegory_layer'] = 2; #2nd Sub Category
					$data['category_status'] = 1;
					
					$checkExitQuery = "select main_category_id,category_name from category 
							where 
								category_name='".$data['category_name']."' and
									main_category_id='".$id."' ";
							$queryResult = $this->db->query($checkExitQuery)->result_array();

					if(count($queryResult) > 0)
					{
						$this->session->set_flashdata('error_message' , " Sub category already exist!");
						redirect(base_url() . 'admin/manage_category/', 'refresh');
					}

					$this->db->insert('category', $data);
					$id1 = $this->db->insert_id();

					if($id1 !="")
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id1 . '.png');
						}
						$this->session->set_flashdata('flash_message' ,'Sub category added successfully!');
						redirect(base_url() . 'admin/manage_category/manage_subcategory/'.$id, 'refresh');
					}
				}
			break;
			
			case "edit_subcategory": #Edit
				$page_data['edit_data'] = $this->db->get_where('category', array('category_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['category_name'] = $this->input->post('sub_category_name');
					/* $data['order_number'] = $this->input->post('order_number'); */
					
					$chkExistInventory = $this->db->query("select main_category_id,category_id,category_name from category
							where 
								category_id !='".$id."' and
									(  category_name='".$data['category_name']."' )
										")->result_array();
								
						if(count($chkExistInventory) > 0)
						{
							$this->session->set_flashdata('error_message' , "Sub  Category already exist!");
							redirect(base_url() . 'admin/manage_category/manage_subcategory/'.$status, 'refresh');
						}
						
					$this->db->where('category_id', $id);
					$result = $this->db->update('category', $data);
					if($result > 0)
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id . '.png');
						}
						
						$this->session->set_flashdata('flash_message' , get_phrase('sub_category_updated_successfully'));
						redirect(base_url() . 'admin/manage_category/manage_subcategory/'.$status, 'refresh');
					}
				}
			break;
			
			case "delete_subcategory": #Delete
				$this->db->where('category_id', $id);
				$this->db->delete('category');
				$this->session->set_flashdata('flash_message' , get_phrase('sub_category_deleted_successfully!'));
				redirect(base_url() . 'admin/manage_category/manage_subcategory/'.$status, 'refresh');
			break;
			
			case "status_subcategory": #Block & Unblock
				if($status == 1){
					$data['category_status'] = 1;
					$succ_msg = 'sub_category_unblocked_successfully!';
				}else{
					$data['category_status'] = 0;
					$succ_msg = 'sub_category_blocked_successfully!';
				}
				$this->db->where('category_id', $id);
				$this->db->update('category', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				redirect(base_url() . 'admin/manage_category/manage_subcategory/'.$status1, 'refresh');
			break;
			
			case "manage_subcategory": #Manage Subcategory
				$page_data['subCategory'] = $result = $this->db->query("select * from category where main_category_id ='".$id."'")->result_array();
			break;
			#Sub category end
			
			#Sec Sub category start
			
			case "add_secsubcategory": #Add Subcategory
				if($_POST)
				{
					$data['main_category_id'] = $id;
					$data['category_name'] = $this->input->post('sec_sub_category_name');
					/* $data['order_number'] = $this->input->post('order_number'); */
					$data['caytegory_layer'] = 3; #3rd Category
					$data['category_status'] = 1;
					
					$this->db->insert('category', $data);
					$id2 = $this->db->insert_id();
					if($id2 !="")
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id2 . '.png');
						}
						$this->session->set_flashdata('flash_message' , get_phrase('category_added_successfully'));
						redirect(base_url() . 'admin/manage_category/manage_subcategory/'.$status.'/'.$id, 'refresh');
					}
				}
			break;

			case "edit_secsubcategory": #edit Sec Sub category
				$page_data['edit_data'] = $this->db->get_where('category', array('category_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['category_name'] = $this->input->post('sec_sub_category_name');
					/* $data['order_number'] = $this->input->post('order_number'); */
					
					$this->db->where('category_id', $id);
					$result = $this->db->update('category', $data);
					
					if($result !="")
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id . '.png');
						}
						$this->session->set_flashdata('flash_message' , get_phrase('sub_category_Updated_successfully'));
						redirect(base_url() . 'admin/manage_category/manage_secsubcategory/'.$status.'/'.$status1, 'refresh');
					}
				}
			break;
			
			case "delete_secsubcategory": #Delete
				$this->db->where('category_id', $id);
				$this->db->delete('category');
				$this->session->set_flashdata('flash_message' , get_phrase('sec_sub_category_deleted_successfully!'));
				redirect(base_url() . 'admin/manage_category/manage_secsubcategory/'.$status, 'refresh');
			break;
			
			case "status_secsubcategory": #Block & Unblock
				if($status == 1){
					$data['category_status'] = 1;
					$succ_msg = 'sec_sub_category_unblocked_successfully!';
				}else{
					$data['category_status'] = 0;
					$succ_msg = 'sec_sub_category_blocked_successfully!';
				}
				$this->db->where('category_id', $id);
				$this->db->update('category', $data);
				$this->session->set_flashdata('flash_message' , get_phrase($succ_msg));
				//redirect(base_url() . 'admin/manage_category/manage_secsubcategory/'.$status2, 'refresh');
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			case "manage_secsubcategory": #Manage Subcategory
				$page_data['secsubCategory'] = $result = $this->db->query("select * from category where main_category_id ='".$id."'")->result_array();
			break;
			#Sub category end
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->admin_model->getManageCategoryCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$base_url = base_url().'admin/manage_category/';
				
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
				
				$page_data['category']  = $result= $data =$this->admin_model->getManageCategory($limit, $offset);
				
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
	
	function ManageBackupDatabases($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['backupdatebase'] = 1;
		$page_data['page_name']  = 'admin/ManageBackupDatabases';
		$page_data['page_title'] = 'Backup Databases';
		
		switch($type)
		{
			case "delete": #Delete
				$getDatabaseDetails = $this->db->query("select database_name from backup_databases where backup_id='".$id."'")->result_array();
				$database_name = $getDatabaseDetails[0]["database_name"];
				
				if( file_exists($database_name) && !empty($database_name) )
				{
					unlink($database_name);
				}
				
				$this->db->where('backup_id', $id);
				$this->db->delete('backup_databases');
				
				$this->session->set_flashdata('flash_message' , "Backup database deleted successfully!");
				redirect(base_url() . 'admin/ManageBackupDatabases', 'refresh');
			break;
			
			case "export":
				date_default_timezone_set('GMT');
				#Load the file helper in codeigniter
				$this->load->helper('file');
				
				$con = mysqli_connect(HOST_NAME,USER_NAME,DB_PASSWORD,DATABASE_NAME);

				$tables = array();
				$query = mysqli_query($con, 'SHOW TABLES');
				while($row = mysqli_fetch_row($query))
				{
					 $tables[] = $row[0];
				}

				$result = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
				$result .= 'SET AUTOCOMMIT = 0;';
				$result .= 'START TRANSACTION;';
				$result .= 'SET time_zone = "+00:00";';
					
				foreach($tables as $table)
				{
					$query = mysqli_query($con, 'SELECT * FROM `'.$table.'`');
					$num_fields = mysqli_num_fields($query);

					#$result .= 'DROP TABLE IF EXISTS '.$table.';';
					$row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE `'.$table.'`'));
					$result .= "\n\n".$row2[1].";\n\n";
					
					for ($i = 0; $i < $num_fields; $i++) 
					{
						while($row = mysqli_fetch_row($query))
						{
						    $result .= 'INSERT INTO `'.$table.'` VALUES(';
							for($j=0; $j<$num_fields; $j++)
							{
							    $row[$j] = addslashes($row[$j]);
							    $row[$j] = str_replace("\n","\\n",$row[$j]);
								if(isset($row[$j]))
								{
								   $result .= '"'.$row[$j].'"' ; 
								}
								else
								{ 
								   $result .= '""';
								}
								if($j<($num_fields-1))
								{ 
								   $result .= ',';
								}
							}
							$result .= ");\n";
						}
					}
					$result .="\n\n";
				}

				#Create Folder
				$folder = 'database/';
				if (!is_dir($folder))
				mkdir($folder, 0777, true);
				chmod($folder, 0777);

				$date = DATABASE_NAME."_".date('m-d-Y')."_".rand(); 
				$filename = $folder.$date; 
				$databaseName = $folder.$date.'.sql'; 

				$handle = fopen($filename.'.sql','w+');
				fwrite($handle,$result);
				fclose($handle);
				
				$data['database_name'] = $databaseName;
				$data['backup_date'] = strtotime(date('d-m-Y h:i:s a'));
				
				$this->db->insert('backup_databases', $data);
				$id = $this->db->insert_id();
				$this->session->set_flashdata('flash_message' , "Database backuped successfully!");
				redirect(base_url() . 'admin/ManageBackupDatabases', 'refresh');  
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->admin_model->getBacupDatabasesCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('admin/ManageBackupDatabases?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('admin/ManageBackupDatabases?keywords=');
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
				
				$page_data['resultData']  = $result= $this->admin_model->getBacupDatabases($limit, $offset);
				
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
	
	function ManageBranches($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'admin/ManageBranches';
		$page_data['page_title'] = 'Manage Branches';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['branch_code'] = $this->input->post('branch_code');
					$existBranchCode = $this->db->query("select branch_id from branch where branch_code='".$data['branch_code']."' ")->result_array();
					if(count($existBranchCode) >0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist branch code!");
						redirect(base_url() . 'admin/ManageBranches', 'refresh');
					}
					$data['branch_code'] = $this->input->post('branch_code');
					$data['branch_name'] = $this->input->post('branch_name');
					$data['description'] = $this->input->post('description');
					$data['phone_number'] = $this->input->post('phone_number');
					$data['address'] = $this->input->post('address');
					$data['phone_number_2'] = $this->input->post('phone_number_2');
					
					$data['branch_status'] = 1;
					
					$this->db->insert('branch', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Branch added Successfully!");
						redirect(base_url() . 'admin/ManageBranches', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('branch', array('branch_id' => $id))
										->result_array();
										
				$data['branch_code'] = $this->input->post('branch_code');
				$existBranchCode = $this->db->query("select branch_id from branch where branch_code='".$data['branch_code']."' and branch_id !='".$id."'")->result_array();
				if(count($existBranchCode) >0 )
				{
					$this->session->set_flashdata('error_message' , "Sorry! Already exist branch code!");
					redirect(base_url() . 'admin/ManageBranches/edit/'.$id, 'refresh');
				}
				if($_POST)
				{
					$data['branch_code'] = $this->input->post('branch_code');
					$data['branch_name'] = $this->input->post('branch_name');
					$data['description'] = $this->input->post('description');
					$data['phone_number'] = $this->input->post('phone_number');
					$data['address'] = $this->input->post('address');
					$data['phone_number_2'] = $this->input->post('phone_number_2');
					
					$this->db->where('branch_id', $id);
					$result = $this->db->update('branch', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Branch updated Successfully!");
						redirect(base_url() . 'admin/ManageBranches', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('branch_id', $id);
				$this->db->delete('branch');
				
				$this->session->set_flashdata('flash_message' , "Branch deleted successfully!");
				redirect(base_url() . 'admin/ManageBranches', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['branch_status'] = 1;
					$succ_msg = 'Branch InActive successfully!';
				}else{
					$data['branch_status'] = 0;
					$succ_msg = 'Branch Active successfully!';
				}
				$this->db->where('branch_id', $id);
				$this->db->update('branch', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'admin/ManageBranches', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->admin_model->getManageBranchCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('admin/ManageBranches?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('admin/ManageBranches?keywords=');
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
				
				$page_data['resultData']  = $result= $this->admin_model->getManageBranch($limit, $offset);
				
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
	
	public function ajaxSubCategory() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select category_id, category_name from category
					where main_category_id='".$id."' order by category_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select Category 1 - </option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['category_id'].'">'.ucfirst($val['category_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No sub category under this category!</option>';
			}
		}
		die;
    }

	public function ajaxSecondSubCategory() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select category_id, category_name from category
					where main_category_id='".$id."' order by category_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select Category 2 - </option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['category_id'].'">'.ucfirst($val['category_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No sub category under this category!</option>';
			}
		}
		die;
    }
	
	public function UserNameExist()
	{
		if ( isset($_POST['user_name_check']) && $_POST['user_name_check'] == 1) 
		{
			$user_name = $_POST['user_name'];
			$register_type = $_POST['register_type'];
			
			$results = $this->db->query("select user_id from users WHERE user_name='".$user_name."' and register_type ='".$register_type."' ")->result_array(); #and register_type='".$register_type."'
			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}
	
	// public function getCountryDetails()
	// {
	// 	$id = $_POST["id"];		
	// 	if($id)
	// 	{			
	// 		$data =  $this->db->query("select * from geo_countries
	// 				where country_id='".$id."' and active_flag='Y'
	// 				")->result();
	// 		echo json_encode($data);
	// 	}
	// 	die;
	// }
	
	public function sales_filter($id="")
	{
		$page_number = $id;
		$redirecturl = isset($_GET['redirect'])?$_GET['redirect']:"";
		$_SESSION['SALES_FILTER'] = $page_number;
		// print_r($_SESSION) ;exit;
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}

	public function purchase_filter($id="")
	{
		$page_number = $id;
		$redirecturl = isset($_GET['redirect'])?$_GET['redirect']:"";
		$_SESSION['PURCHASE_FILTER']=$page_number;
		//print_r($_SESSION) ;exit;
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}

	public function expense_filter($id="")
	{
		$page_number = $id;
		$redirecturl = isset($_GET['redirect'])?$_GET['redirect']:"";
		$_SESSION['EXPENSE_FILTER']=$page_number;
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}

	function settings()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['settings'] = 1;
		$page_data['page_name']  = 'admin/settings';
		$page_data['page_title'] = 'Settings';
			
		$this->load->view($this->adminTemplate, $page_data);
	}

	public function getHsnCodes()
	{
		$id = $_POST["id"];

		if($id)
		{
			$HsnCode = 'select 
					inv_hsn_codes.hsn_code, 
					inv_hsn_codes.hsn_code_id , 
					inv_hsn_codes.hsn_tax_id, 
					tax.tax_name,
					category.product_prefix from inv_hsn_codes

				left join category on category.hsn_sac_code = inv_hsn_codes.hsn_code_id
				left join tax on tax.tax_id = inv_hsn_codes.hsn_tax_id
				where 
					category.category_id="'.$id.'" ';
			$getHsnCode = $this->db->query($HsnCode)->result_array();
			
			echo json_encode($getHsnCode);
		}
		die;
	}

	# Ajax  Change
	// public function ajaxselectState() 
	// {
    //     $id = $_POST["id"];		
	// 	if($id)
	// 	{			
	// 		$data =  $this->db->query("select geo_states.* from geo_states
	// 				left join geo_countries on geo_countries.country_id = geo_states.state_id
	// 				where geo_states.country_id='".$id."' order by geo_states.state_name asc
	// 				")->result_array();
		
	// 		if( count($data) > 0)
	// 		{
	// 			echo '<option value="">- Select State -</option>';
	// 			foreach($data as $val)
	// 			{
	// 				echo '<option value="'.$val['state_id'].'">'.ucfirst($val['state_name']).'</option>';
	// 			}
	// 		}
	// 		else
	// 		{
	// 			echo '<option value="">No states under this country!</option>';
	// 		}
	// 	}
	// 	die;
    // }

	# Ajax Select cities under State
	// public function ajaxSelectCity() 
	// {
    //     $id = $_POST["id"];		
	// 	if($id)
	// 	{			
	// 		$data =  $this->db->query("select geo_cities.city_id,geo_cities.city_name from geo_cities
	// 				left join geo_states on geo_states.state_id = geo_cities.city_id
	// 				where geo_cities.state_id='".$id."' order by geo_cities.city_name asc
	// 				")->result_array();
		
	// 		if( count($data) > 0)
	// 		{
	// 			echo '<option value="">- Select City -</option>';
	// 			foreach($data as $val)
	// 			{
	// 				echo '<option value="'.$val['city_id'].'">'.ucfirst($val['city_name']).'</option>';
	// 			}
	// 		}
	// 		else
	// 		{
	// 			echo '<option value="">No cities under this state!</option>';
	// 		}
	// 	}
	// 	die;
    // }

	# Product Expiry
    function product_expiry()
    {
		$page_data['page_title'] = SITE_NAME.' | Product Expiry'; #Page Title
		
		if ($_POST) 
		{
			$data['description'] = strtotime($_POST['product_start_date']);
			$this->db->where('type' , 'product_start_date');
			$this->db->update('settings' , $data);
			
			$data['description'] = strtotime($_POST['product_end_date']);
			$this->db->where('type' , 'product_end_date');
			$this->db->update('settings' , $data);
			
			$data['description'] = $_POST['before_expire_days'];
			$this->db->where('type' , 'before_expire_days');
			$this->db->update('settings' , $data);
			
			
			$this->session->set_flashdata('flash_message' , 'Product expiry date updated successfully!');
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		}
		$this->load->view('backend/admin/product_expiry');
    }

	public function ajaxCheckCurrentPassword() 
	{
		$id = $data['user_id']        = $this->input->post('user_id');
		$data['password']             = $this->input->post('password');
		$data['new_password']         = md5($this->input->post('new_password'));
		$data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));
		
		$current_password = $this->db->get_where('per_user', array('user_id' => $this->user_id))->row()->attribute1;
		
		if($current_password == $data['password']) 
		{
			echo "1"; //matched
		} 
		else 
		{
			echo "2"; //mismatched
		}
		die();
	}

	public function ajaxCheckUserPassword() 
	{
		$id = $data['user_id']        = $this->input->post('user_id');
		$data['password']             = $this->input->post('password');
		$data['new_password']         = md5($this->input->post('new_password'));
		$data['confirm_new_password'] = md5($this->input->post('confirm_new_password'));
		
		$getCurrentPasswordQry = "select password from per_user where user_id='".$id."'";
		$getCurrentPassword = $this->db->query($getCurrentPasswordQry)->result_array();

		$current_password = isset($getCurrentPassword[0]["password"]) ? $getCurrentPassword[0]["password"] : "";
		$current_password = $this->db->get_where('users', array('user_id' => $this->user_id))->row()->original_password;
		
		if ($current_password == $data['password']) 
		{
			echo "0"; //currentmatched
		} 
		else if ($data['new_password'] == $data['confirm_new_password']) 
		{
			echo "1"; //matched
		} 
		else 
		{
			echo "2"; //mismatched
		}
		die();
	}

	public function ajaxPasswordCheck() 
	{
		$password             	= $this->input->post('password');
		$new_password        	= $this->input->post('new_password');
		
		if ($password == $new_password) 
		{
			echo "0"; 
		} 
		else 
		{
			echo "1"; //mismatched
		}
		die();
	}
}
?>
