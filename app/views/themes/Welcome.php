<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller 
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
	
	public function index()
	{
		if (!empty($this->UserID))
        {
			redirect(base_url()."home.html", 'refresh');
		}
		
		$page_data['landing_page'] = 1;
		$page_data['page_title'] = SITE_NAME." | Best IT And Software Development Company In India"; #Page Title
		$page_data['page_name']  = "home"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	public function home()
	{
		$page_data['page_title'] = SITE_NAME.' | Welcome to '.SITE_NAME; #Page Title
		$page_data['page_name']  = "home"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	
	public function aboutUs()
	{
		$page_data['page_title'] = SITE_NAME.' | About Us'; #Page Title
		$page_data['page_name']  = "about-us"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function gallery()
	{
		$page_data['page_title'] = SITE_NAME.' | Gallery'; #Page Title
		$page_data['page_name']  = "gallery"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function aminities()
	{
		$page_data['page_title'] = SITE_NAME.' | Aminities'; #Page Title
		$page_data['page_name']  = "aminities"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	
	public function room()
	{
		$page_data['page_title'] = SITE_NAME.' | Room'; #Page Title
		$page_data['page_name']  = "room"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function roomdetails()
	{
		$page_data['page_title'] = SITE_NAME.' | Room details'; #Page Title
		$page_data['page_name']  = "room-details"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function contact()
	{
		$page_data['page_title'] = SITE_NAME.' | Room'; #Page Title
		$page_data['page_name']  = "contact-us"; #View Page
		$this->load->view($this->template, $page_data);
	}


	public function new()	
	{
		$page_data['page_title'] = SITE_NAME.' | About Us'; #Page Title
		$page_data['page_name']  = "new"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	public function privacy_policy()
	{
		$page_data['page_title'] = SITE_NAME.' | Privacy Policy'; #Page Title
		$page_data['page_name']  = "privacy_policy"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	public function error()
	{
		$page_data['page_title'] = '404 Error'; #Page Title
		$page_data['page_name']  = "error"; #View Page
		$this->load->view($this->template, $page_data);
	}

	public function terms_conditions()
	{
		$page_data['page_title'] = SITE_NAME.' | Terms Conditions'; #Page Title
		$page_data['page_name']  = "terms_conditions"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function refundpolicy()
	{
		$page_data['page_title'] = SITE_NAME.' | refundpolicy'; #Page Title
		$page_data['page_name']  = "refund-policy"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	public function cms($url = "")
	{	
		$cmsData = $page_data['cmsData'] =  $this->db->query("select * from cms
					where cms_url='".$url."' AND cms_status = 1
				")->result_array();
				
		$page_data['page_title'] = !empty($cmsData[0]['cms_title']) ? SITE_NAME.' | '.$cmsData[0]['cms_title'] :"CMS Pages"; #Page Title
		$page_data['page_name']  = "cms"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	public function contactUs()
	{
		$page_data['page_title'] = SITE_NAME.' | Contact Us'; #Page Title
		$page_data['page_name']  = "contact_us"; #View Page
		
		if($_POST)
		{
			$data['first_name'] = $this->input->post('first_name');
			$data['email'] = $this->input->post('email');
			$data['phone'] = $this->input->post('phone');
			$data['subject'] = $this->input->post('subject');
			$data['message'] = $this->input->post('message');
			$data['project_query'] = $this->input->post('project_query');
			$data['your_budget'] = $this->input->post('your_budget');
			$data['contact_date'] = time();
			
			$this->db->insert('contact_us', $data);
			$id = $this->db->insert_id();
			
			if($id !="")
			{
				#Email sent start here
				$page_data['contact_us'] =1;
				
				$to = CONTACT_EMAIL;
				$from = $data['email'];
				$page_data['cname'] = $fromName = $data['first_name'];
				$subject = $page_data['subject'] ="";	 
				$page_data['message'] = $data['message'];	 
				
				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$fromName);
				}
				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$fromName);
				}
				#Email sent end here
			}
			$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
			redirect(base_url()."home.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	# ajax Select Qualification
	public function ajaxSelectQualification() 
	{
        $id = $_POST["id"];		
		
		if($id)
		{			
			$data =  $this->db->query("select qualification.* from qualification
					left join education on education.education_id = qualification.education_id
					where qualification.education_id='".$id."' and education.education_status = 1
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select Qualification</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['qualification_id'].'">'.ucfirst($val['qualification_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No Qualification under this education</option>';
			}
		}
		die;
    }
	
	# Ajax  Change
	public function ajaxSelectSpecialisation() 
	{
        $id = $_POST["id"];		
		
		if($id)
		{			
			$data =  $this->db->query("select specialisation.* from specialisation
					left join qualification on qualification.qualification_id = specialisation.qualification_id
					where specialisation.qualification_id='".$id."'
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select Specialisation</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['specialisation_id'].'">'.ucfirst($val['specialisation_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No Specialisation under this Qualification</option>';
			}
		}
		die;
    }
	
	public function EmailExist()
	{
		if ( isset($_POST['email_check']) && $_POST['email_check'] == 1) 
		{
			$email = $_POST['email'];
			$register_type = $_POST['register_type'];
			
			$results = $this->db->query("select user_id from users WHERE email='".$email."' and register_type='".$register_type."' ")->result_array();
			if ( count($results) > 0 ) {
				echo 'Exist';exit; #Exist
			}else{
				echo 'NotExist';exit; #Not Exist
			}
			exit();
		}
	}
	
	public function logout() 
	{
		$this->session->sess_destroy();
		
		$this->session->set_flashdata('success_message' , 'Logged out successfully!');
		redirect(base_url(), 'refresh');
		
		/* $page_data['page_title'] = SITE_NAME.' | Contact Us'; #Page Title
		$page_data['page_name']  = "logout"; #logout Page */
		
        #$this->session->set_flashdata('flash_message' , get_phrase('logged_out_successfully!'));
		
		$this->load->view($this->template, $page_data);
    }
	
	# Ajax Select District
	public function ajaxSelectDistrict() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select district.* from district
					left join state on state.state_id = district.state_id
					where state.state_id='".$id."' order by district.district_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select District</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['district_id'].'">'.ucfirst($val['district_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No district under this state!</option>';
			}
		}
		die;
    }
	
	# Ajax Select CityorTown
	public function ajaxSelectCityorTown() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select city.city_id,city.city_name from city
					left join district on district.district_id = city.district_id
					where city.district_id='".$id."' order by city.city_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select City/Town</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['city_id'].'">'.ucfirst($val['city_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No city/town under this district!</option>';
			}
		}
		die;
    }
	
	
	# Ajax Select cities under State
	public function ajaxSelectPreparedState() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select city.city_id,city.city_name from city
					left join state on state.state_id = city.state_id
					where city.state_id='".$id."' order by city.city_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select City/Town</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['city_id'].'">'.ucfirst($val['city_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No city/town under this state!</option>';
			}
		}
		die;
    }
	
	
	# Ajax  Change
	public function ajaxSelectCity() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select geo_states.* from geo_states
					left join geo_countries on geo_countries.country_id = geo_states.state_id
					where geo_states.country_id='".$id."' order by geo_states.state_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select State</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['state_id'].'">'.ucfirst($val['state_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No state under this country!</option>';
			}
		}
		die;
    }

	public function ajaxSelectCities() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select city.city_id,city.city_name from geo_cities as city
					where city.state_id='".$id."' order by city.city_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['city_id'].'">'.$val['city_name'].'</option>';
				}
			}else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }
	
	public function ajaxselectbyType() 
	{
        $id = $_POST["id"];		
		
		if(!empty($id))
		{		
			if($id == 1 || $id == 2) #1=>Candidate ,  2=>Institute
			{
				$data =  $this->db->query("select institute_type_id, institute_type_name from institute_type
					where institute_type_status=1 order by institute_type_name asc
					")->result_array();
					
				if( count($data) > 0)
				{
					echo '<option value="">Select Category</option>';
					foreach($data as $val)
					{
						echo '<option value="'.$val['institute_type_id'].'">'.ucfirst($val['institute_type_name']).'</option>';
					}
				}
				else
				{
					echo '<option value="">No Category under this type!</option>';
				}
			}
			else if($id == 3 || $id == 4) #3=>Recruiter ,  4=>Employer
			{
				$data =  $this->db->query("select industry_id, industry_name from job_industries
					where industry_status=1 order by industry_name asc
					")->result_array();
					
				if( count($data) > 0)
				{
					echo '<option value="">Select Category</option>';
					foreach($data as $val)
					{
						echo '<option value="'.$val['industry_id'].'">'.ucfirst($val['industry_name']).'</option>';
					}
				}
				else
				{
					echo '<option value="">No Category under this type!</option>';
				}
			}
		
		}
		die;
    }
	
	# Ajax Select Qualification
	public function ajaxSelectQualifications() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select qualification.qualification_id,qualification.qualification_name from qualification
					left join education on education.education_id = qualification.education_id
					where qualification.education_id='".$id."' order by qualification.qualification_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">Select Qualification</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['qualification_id'].'">'.ucfirst($val['qualification_name']).'</option>';
				}
			}else
			{
				echo '<option value="">No qualification under this education!</option>';
			}
		}
		die;
    }
	
	public function sortItemperPage($id="")
	{
		$page_number = $id;
		$redirecturl = isset($_GET['redirect'])?$_GET['redirect']:"";
		$_SESSION['PAGE']=$page_number;
		
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	
	public function JobsSortBy($id="")
	{
		$JobsSortBy = $id;
		$redirecturl = isset($_GET['redirect'])?$_GET['redirect']:"";
		$_SESSION['JobsSortBy']=$JobsSortBy;
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	
	# Ajax ajax change Salary Type
	public function ajaxchangeSalaryType() 
	{
        $id = $_POST["id"];		
		if($id == 1)
		{			
			echo '<option value="">Select Salary</option>';
			foreach($this->ByMonthlySalary as $key => $value)
			{
				echo '<option value="'.$key .'">'.$value.'</option>';
			}
		}
		else if($id == 2)
		{			
			echo '<option value="">Select Salary</option>';
			foreach($this->ByYearlySalary as $key => $value)
			{
				echo '<option value="'.$key .'">'.$value.'</option>';
			}
		}
		die;
    }
	
	public function cookiePolicy()
	{
		$agree_terms = isset($_POST['agree_terms']) ? $_POST['agree_terms']:"";
		$_SESSION['agree_terms'] = $agree_terms;
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	
	public function subscribe()
	{
		if($_POST)
		{
			$data['subscribe_email'] = isset($_POST['subs_email']) ? $_POST['subs_email']:"";
			$data['subscribe_date'] = time();
			
			$this->db->insert('subscribe', $data);
			$id = $this->db->insert_id();
		}
		$this->session->set_flashdata('flash_message' , 'Thanks for subscribed!');
		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}
	
	public function careers()
	{
		$page_data['page_title'] = SITE_NAME.' | Careers'; #Page Title
		$page_data['page_name']  = "pages/careers"; #View Page
		
		if($_POST)
		{
			$data['first_name'] = $this->input->post('first_name');
			$data['last_name'] = $this->input->post('last_name');
			$data['representing'] = $this->input->post('representing');
			$data['company_name'] = $this->input->post('company_name');
			$data['desigation'] = $this->input->post('desigation');
			$data['willing_nda'] = $this->input->post('willing_nda');
			$data['mobile_number'] = $this->input->post('mobile_number');
			$data['alternate_mobile_number'] = $this->input->post('alternate_mobile_number');
			$data['preferable_day_contact'] = $this->input->post('preferable_day_contact');
			$data['preferable_time_contact'] = $this->input->post('preferable_time_contact');
			$data['email'] = $this->input->post('email');
			$data['alternate_email'] = $this->input->post('alternate_email');
			$data['country'] = $this->input->post('country');
			$data['city'] = $this->input->post('city');
			
			$data['key_specialisation_1'] = $this->input->post('key_specialisation_1');
			$data['key_specialisation_2'] = $this->input->post('key_specialisation_2');
			$data['key_specialisation_3'] = $this->input->post('key_specialisation_3');
			
			$data['experience_key_specialisation_1'] = $this->input->post('experience_key_specialisation_1');
			$data['experience_key_specialisation_2'] = $this->input->post('experience_key_specialisation_2');
			$data['experience_key_specialisation_3'] = $this->input->post('experience_key_specialisation_3');
			
			$data['employment_basis'] = $this->input->post('employment_basis');
			$data['notice_period'] = $this->input->post('notice_period');
			$data['other_information'] = $this->input->post('other_information');
			
			
			$data['joined_date'] = time();
			
			$this->db->insert('careers', $data);
			$id = $this->db->insert_id();
			
			if($id !="")
			{
				# Upload Resume start
				if( isset($_FILES['upload_resume']['name']) && $_FILES['upload_resume']['name'] != "") 
				{							
					if(is_uploaded_file($_FILES['upload_resume']['tmp_name']))
					{
						$file_parts = pathinfo($_FILES['upload_resume']['name']);
						$ext = $file_parts['extension'];
						$upload_resume= $id.".".$ext;
						move_uploaded_file($_FILES['upload_resume']['tmp_name'], 'uploads/candidate_resume/'.$upload_resume);
					}
					
					$candidateResume['candidate_resume'] = $upload_resume;
					$this->db->where('careers_id', $id);
					$this->db->update('careers', $candidateResume);
				}
				# Upload Resume end
				
				#Email sent start here
				$page_data['careers'] =1;
				
				$to = "hr@jesperapps.com";
				$from = $data['email'];
				$page_data['name'] = $fromName = $data['first_name']." ".$data['last_name'];
				$subject = $page_data['subject'] = "Careers - Submitted Resume with some basic Details.";

				$page_data['representing'] = $data['representing'];	 
				$page_data['company_name'] = $data['company_name'];	 
				$page_data['desigation'] = $data['desigation'];	 
				$page_data['willing_nda'] = $data['willing_nda'];	 
				
				$page_data['mobile_number'] = $data['mobile_number'];	 
				$page_data['alternate_mobile_number'] = $data['alternate_mobile_number'];	 
				$page_data['preferable_day_contact'] = $data['preferable_day_contact'];	 
				$page_data['preferable_time_contact'] = $data['preferable_time_contact'];	 
				$page_data['country'] = $data['country'];	 
				$page_data['city'] = $data['city'];	
				$page_data['key_specialisation_1'] = $data['key_specialisation_1'];	
				$page_data['key_specialisation_2'] = $data['key_specialisation_2'];	
				$page_data['key_specialisation_3'] = $data['key_specialisation_3'];	
				
				$page_data['experience_key_specialisation_1'] = $data['experience_key_specialisation_1'];	
				$page_data['experience_key_specialisation_2'] = $data['experience_key_specialisation_2'];	
				$page_data['experience_key_specialisation_3'] = $data['experience_key_specialisation_3'];	
				
				$page_data['employment_basis'] = $data['employment_basis'];	
				$page_data['notice_period'] = $data['notice_period'];	
				$page_data['other_information'] = $data['other_information'];	

				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$fromName);
				}
				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$fromName);
				}
				#Email sent end here
			}
			$this->session->set_flashdata('success_message' , 'Thank you for filling out the form. Your response has been recorded. Someone from our team would be in touch with you soon.');
			redirect(base_url()."home.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	# Contact Us Training
	public function contactUsTraining()
	{
		$page_data['page_title'] = SITE_NAME.' | Contact Us'; #Page Title
		$page_data['page_name']  = "pages/training/contactUsTraining"; #View Page
		
		if($_POST)
		{
			$data['first_name'] = $this->input->post('first_name');
			$data['email'] = $this->input->post('email');
			$data['phone'] = $this->input->post('phone');
			$data['subject'] = $this->input->post('subject');
			$data['course'] = $course = $this->input->post('course');
			$data['message'] = $this->input->post('message');
			$data['contact_date'] = time();
			
			$this->db->insert('contact_us_training', $data);
			$id = $this->db->insert_id();
			
			if($id !="")
			{
				#Email sent start here
				$page_data['contact_us_training'] =1;
				
				$to = CONTACT_EMAIL;
				$from = $data['email'];
				$page_data['cname'] = $fromName = $data['first_name'];
				$subject = $page_data['subject'] = $data['subject'];	 
				$page_data['course'] = $data['course'];	 
				$page_data['message'] = $data['message'];	 
				
				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$fromName);
				}
				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$fromName);
				}
				#Email sent end here
				
				if($course == 'Oracle JET Training')
				{
					$filepath = 'uploads/oracle_jet.pdf';
					if(file_exists($filepath)) 
					{
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize($filepath));
						flush(); // Flush system output buffer
						readfile($filepath);
						die();
					}
				}
			}
			$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
			redirect(base_url()."home.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	
	
	
}
	
?>
