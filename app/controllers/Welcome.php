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

		if($_POST)
		{

			$full_name			= $this->input->post('client_full_name');
			$email				= $this->input->post('client_email_id');
			$mobile_number		= $this->input->post('client_mobile_number');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			// $subject			= $this->input->post('subject');
			$postData		=array(
				'customer_name'			=> $full_name,
				'email'					=> $email,
				'mobile_number'			=> $mobile_number,
				'message'				=> $message,
				'subject'				=> $this->input->post('subject'),
				"created_by" 	  		=> -1,
				"created_date" 	 		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);

			$this->db->insert('contact_us', $postData);
			$id = $this->db->insert_id();
			// print_r($data);
			if($id !="")
			{
			
				$page_data['digitalmarketing']  	= "";
				$page_data['mobileAppDevelopement']	= ""; 
				$page_data['websitedevelopement']	= ""; 
				$page_data['webappdevelopement']	= ""; 
				$page_data['company_name']			= ""; 
				$page_data['industry_type']			= ""; 
				$page_data['contact_us'] 			= 1;
				$from 								= NOREPLY_EMAIL;				
				$to 								= CONTACT_EMAIL;
				$page_data['full_name'] 			= $full_name;
				$page_data['subject'] 				= $this->input->post('subject');
				$page_data['message'] 				= $message;	
				$page_data['email'] 				= $email;
				$page_data['mobile_number'] 		= $mobile_number;
				// $page_data['subject'] = !empty($data['subject']) ? $data['subject'] : "Contact Us";
				
				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$full_name);
					// print_r($sendMail);exit;
				}

				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$full_name);
				}
				// echo $sendMail;exit;
				
			}
			// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
			//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	public function home()
	{
		$page_data['page_title'] = SITE_NAME.' | Welcome to '.SITE_NAME; #Page Title
		$page_data['page_name']  = "home"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	public function appointment()
	{
		$page_data['page_title'] = SITE_NAME.' | appointment'; #Page Title
		$page_data['page_name']  = "appointment"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function doctors()
	{
		$page_data['page_title'] = SITE_NAME.' | doctors'; #Page Title
		$page_data['page_name']  = "doctors"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function branches()
	{
		$page_data['page_title'] = SITE_NAME.' | branches'; #Page Title
		$page_data['page_name']  = "branches"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function treatments()
	{
		$page_data['page_title'] = SITE_NAME.' | treatments'; #Page Title
		$page_data['page_name']  = "treatments"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function termsandconditions()
	{
		$page_data['page_title'] = SITE_NAME.' | blog'; #Page Title
		$page_data['page_name']  = "terms-and-conditions"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function aboutus()
	{
		$page_data['page_title'] = SITE_NAME.' | about us'; #Page Title
		$page_data['page_name']  = "about-us"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function cancellationpolicy()
	{
		$page_data['page_title'] = SITE_NAME.' | cancellationpolicy'; #Page Title
		$page_data['page_name']  = "cancellation-policy"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function googleads()
	{
		$page_data['page_title'] = SITE_NAME.' | google-ads'; #Page Title
		$page_data['page_name']  = "google-ads"; #View Page
		$this->load->view($this->template, $page_data);
	}	public function emailmarketingservices()
	{
		$page_data['page_title'] = SITE_NAME.' | email-marketing-services'; #Page Title
		$page_data['page_name']  = "email-marketing-services"; #View Page
		$this->load->view($this->template, $page_data);
	}	public function webdesignservices()
	{
		$page_data['page_title'] = SITE_NAME.' | web-design-services'; #Page Title
		$page_data['page_name']  = "web-design-services"; #View Page
		$this->load->view($this->template, $page_data);
	}	public function logodesign()
	{
		$page_data['page_title'] = SITE_NAME.' |logo-design'; #Page Title
		$page_data['page_name']  = "logo-design"; #View Page
		$this->load->view($this->template, $page_data);
	}

	public function joblist()
	{
		$page_data['page_title'] = SITE_NAME.' | Room'; #Page Title
		$page_data['page_name']  = "job-list"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function football()
	{
		$page_data['page_title'] 	= SITE_NAME.' | football'; #Page Title
		$page_data['page_name']  	= "football"; #View Page
		$page_data['footBall'] 		= 1;
		
		if($_POST)
		{
			$sports_type		= 'football';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	public function basketball()
	{
		$page_data['page_title'] 	= SITE_NAME.' | basketball'; #Page Title
		$page_data['page_name']  	= "basketball"; #View Page
		$page_data['basketBall'] 	= 1;
		
		if($_POST)
		{
			$sports_type		= 'basketball';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
				// print_r($sendMail);exit;
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			// echo $sendMail;exit;
				
			// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
			//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	public function indoorsportshalls()
	{
		$page_data['page_title'] 			= SITE_NAME.' |indoor-sports-halls'; #Page Title
		$page_data['page_name']  			= "indoor-sports-halls"; #View Page
		$page_data['indoorSportsHalls'] 	= 1;
		
		if($_POST)
		{
			$sports_type		= 'indoor-sports-halls';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
				// print_r($sendMail);exit;
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			// echo $sendMail;exit;
				
			// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
			//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	public function volleyball()
	{
		$page_data['page_title'] 			= SITE_NAME.' |volleyball'; #Page Title
		$page_data['page_name']  			= "volley-ball"; #View Page
		$page_data['volleyball'] 			= 1;

		if($_POST)
		{
			$sports_type		= 'volleyball';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	
	public function badminton()
	{
		$page_data['page_title'] 	= SITE_NAME.' | badminton'; #Page Title
		$page_data['page_name']  	= "badminton"; #View Page
		$page_data['badminton'] 	= 1;

		if($_POST)
		{
			$sports_type		= 'badminton';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	public function multisport()
	{
		$page_data['page_title'] 	= SITE_NAME.' | multisport'; #Page Title
		$page_data['page_name']  	= "multi-sport"; #View Page
		$page_data['multisport'] 	= 1;

		if($_POST)
		{
			$sports_type		= 'multisport';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	public function hockey()
	{
		$page_data['page_title'] 	= SITE_NAME.' | hockey'; #Page Title
		$page_data['page_name']  	= "hockey"; #View Page
		$page_data['hockey'] 		= 1;

		if($_POST)
		{
			$sports_type		= 'hockey';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	public function pptiles()
	{
		$page_data['page_title'] = SITE_NAME.' | PP tiles'; #Page Title
		$page_data['page_name']  = "pp-tiles"; #View Page
		
		$this->load->view($this->template, $page_data);
	}
	public function acrylicsystem()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "acrylic-system"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function artificial()
	{
		$page_data['page_title'] = SITE_NAME.' |artificial'; #Page Title
		$page_data['page_name']  = "artificial"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function bleachers()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "bleachers"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function acrylic_system()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "acrylic-system"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function pvc()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "pvc"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function redexim()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "redexim"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function sports()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "sports"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function gallery()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "gallery"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function stadium()
	{
		$page_data['page_title'] = SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  = "stadium"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function tennis()
	{
		$page_data['page_title'] 	= SITE_NAME.' | Standard'; #Page Title
		$page_data['page_name']  	= "tennis"; #View Page
		$page_data['tennis'] 		= 1;

		if($_POST)
		{
			$sports_type		= 'tennis';
			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['sports_type'] 			= $sports_type;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	public function seo()
	{
		$page_data['page_title'] = SITE_NAME.' | seo'; #Page Title
		$page_data['page_name']  = "seo"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function services()
	{
		$page_data['page_title'] = SITE_NAME.' | Room details'; #Page Title
		$page_data['page_name']  = "services"; #View Page
		$this->load->view($this->template, $page_data);
	}
	// public function contact()
	// {
	// 	$page_data['page_title'] = SITE_NAME.' | Room'; #Page Title
	// 	$page_data['page_name']  = "contact-us"; #View Page
	// 	$this->load->view($this->template, $page_data);
	// }

	public function blogDetails($blog_id='',$blog_category='')
	{
		$page_data['page_title'] 		= SITE_NAME.' | Blog Details'; #Page Title
		$page_data['page_name']  		= "blog-details"; #View Page
		$page_data['blog_id']  			= $blog_id;
		$page_data['blog_category']		= $blog_category;

		if($_POST)
		{
			$postData=array(
				'blog_id'				=> $blog_id,
				'blog_category'			=> $blog_category,
				'client_name'			=> $this->input->post('client_name'),
				'email'					=> $this->input->post('email'),
				'message'				=> $this->input->post('message'),
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);
			$this->db->insert('blog_comments', $postData);
			$id = $this->db->insert_id();

			if($id!=NULL)
			{
				if( isset($_FILES['client_image']['name']) && $_FILES['client_image']['name'] !="" )
				{
					move_uploaded_file($_FILES['client_image']['tmp_name'], 'uploads/blogs/comments/'.$id.'.png');
				}

				redirect(base_url() . "blog-details.html/" . $blog_id . "/" . $blog_category ."/". "#comment-section", 'refresh');

			}

		}
		$this->load->view($this->template, $page_data);
	}
	public function thankYou()
	{
		$page_data['page_title'] 		= SITE_NAME.' | Thank You'; #Page Title
		$page_data['page_name']  		= "thankyou"; #View Page
		$this->load->view($this->template, $page_data);
	}
	public function jobDetails($job_id='',$job_category_id='')
	{
		$page_data['page_title'] 		= SITE_NAME.' | Job Details'; #Page Title
		$page_data['page_name']  		= "job-details"; #View Page
		$page_data['job_id']  			= $job_id; #job_id
		$page_data['job_category_id']  	= $job_category_id; #job_category_id
		if($_POST)
		{
			$postData = array(
				'job_category_id'		=> $job_category_id,
				'full_name'				=> $this->input->post('full_name'),
				'email'					=> $this->input->post('email'),
				'mobile_number'			=> $this->input->post('mobile_number'),
				'experience'			=> $this->input->post('experience'),
				'location'				=> $this->input->post('location'),
				'current_company'		=> $this->input->post('current_company'),
				'expected_salary'		=> $this->input->post('expected_salary'),
				'notice_period'			=> $this->input->post('notice_period'),
				'message'				=> $this->input->post('message'),
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);
			$this->db->insert('org_applied_jobs', $postData);
			$id = $this->db->insert_id();

			if($id!=NULL)
			{
				if( isset($_FILES['resume']['name']) && $_FILES['resume']['name'] != "") 
				{							
					if(is_uploaded_file($_FILES['resume']['tmp_name']))
					{
						$file_parts = pathinfo($_FILES['resume']['name']);
						$ext = $file_parts['extension'];
						$resume= $id.".".$ext;
						move_uploaded_file($_FILES['resume']['tmp_name'], 'uploads/jobs/candidate_resume/'.$resume);
					}
					
					$candidateResume['candidate_resume'] = $resume;
					$this->db->where('applied_job_id', $id);
					$this->db->update('org_applied_jobs', $candidateResume);
				}

				if( isset($_FILES['photo']['name']) && $_FILES['photo']['name'] !="" )
				{
					move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/jobs/' . $id . '.png');
				}

				redirect(base_url()."thankyou.html", 'refresh');
			}
		}
		$this->load->view($this->template, $page_data);
	}

	public function jobLists($job_id='',$job_category_id='')
	{
		$page_data['page_title'] 		= SITE_NAME.' | Job Details'; #Page Title
		$page_data['page_name']  		= "job-lists"; #View Page
		$page_data['job_id']  			= $job_id; #job_id
		$page_data['job_category_id']  	= $job_category_id; #job_category_id
		$this->load->view($this->template, $page_data);
	}

	public function digitalmarketing()
	{
		
		$page_data['page_title'] 			= SITE_NAME.' | Welcome to '.SITE_NAME; #Page Title
		$page_data['page_name']  			= "digital-marketing"; #View Page
		$page_data['subject']				= ""; 
		$page_data['message']				= "";
		$page_data['digitalmarketing']  	= 1;
		$page_data['mobileAppDevelopement']	= ""; 
		$page_data['websitedevelopement']	= ""; 
		$page_data['websitedevelopement']	= ""; 

		if($_POST){
			$service_contact_type 	= 'CONTACT-DIGITAL-MARKETING';
			$full_name 				= $this->input->post('full_name');
			$email 					= $this->input->post('email');
			$mobile_number 			= $this->input->post('mobile_number');
			$company_name 			= $this->input->post('company_name');
			$marketing_goals 		= $this->input->post('marketing_goals');
			$current_challenges 	= $this->input->post('current_challenges');
			$subject				= "Enquiry";

			$postData = array(
				"service_contact_type"	=>  $service_contact_type,
				"full_name" 			=>  $full_name,
				"email" 				=>  $email,
				"mobile_number" 		=>  $mobile_number,
				"company_name" 			=>  $company_name,
				"marketing_goals" 		=>  $marketing_goals,
				"current_challenges" 	=>  $current_challenges,
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);

			$this->db->insert('services',$postData);
			$header_id = $this->db->insert_id();
			if($header_id !="")
			{
				$from 									= NOREPLY_EMAIL;				
				$to 									= CONTACT_EMAIL;
				$page_data['service_contact_type'] 		= $service_contact_type;
				$page_data['full_name'] 				= $full_name;
				$page_data['email'] 					= $email;
				$page_data['mobile_number'] 			= $mobile_number;
				$page_data['company_name'] 				= $company_name;
				$page_data['marketing_goals'] 			= $marketing_goals;
				$page_data['current_challenges'] 		= $current_challenges;
				$page_data['subject'] 					= $subject;
				// $page_data['subject'] = !empty($data['subject']) ? $data['subject'] : "Contact Us";

				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$full_name);
					// print_r($sendMail);exit;
				}

				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$full_name);
				}
				//echo $sendMail;exit;

				#Email sent end here				

				// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
				//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
				redirect(base_url()."thankyou.html", 'refresh');
			}
		}

		$this->load->view($this->template, $page_data);
	}
	

	public function mobileAppDevelopement()
	{	
		$page_data['page_title'] 			= SITE_NAME.' | Contact Us'; #Page Title
		$page_data['page_name']  			= "mobile-app-developement"; #View Page
		$page_data['subject']				= ""; 
		$page_data['message']				= "";
		$page_data['digitalmarketing']  	= "";
		$page_data['mobileAppDevelopement']	= 1; 
		$page_data['websitedevelopement']	= ""; 
		$page_data['websitedevelopement']	= ""; 
		
		if($_POST)
		{
			$service_contact_type	= 'CONTACT-MOBILE-APP-DEVELOPMENT';

			$full_name 			=  $this->input->post('full_name');
			$email 				=  $this->input->post('email');
			$mobile_number 		=  $this->input->post('mobile_number');
			$company_name 		=  $this->input->post('company_name');
			$platform_type 		=  $this->input->post('platform_type');
			$project_detail 	=  $this->input->post('project_detail');
			$existing_app 		=  $this->input->post('existing_app');
			$subject			= "Enquiry";

			$postData = array(
				"service_contact_type"	=>  $service_contact_type,
				"full_name" 			=>  $full_name,
				"email" 				=>  $email,
				"mobile_number" 		=>  $mobile_number,
				"company_name" 			=>  $company_name,
				"platform_type" 		=>  $platform_type,
				"project_detail" 		=>  $project_detail,
				"existing_app" 			=>  $existing_app,
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);

			$this->db->insert('services',$postData);
			$header_id = $this->db->insert_id();

			if($header_id !="")
			{
				$from 									= NOREPLY_EMAIL;				
				$to 									= CONTACT_EMAIL;
				$page_data['service_contact_type'] 		= $service_contact_type;
				$page_data['full_name'] 				= $full_name;
				$page_data['email'] 					= $email;
				$page_data['mobile_number'] 			= $mobile_number;
				$page_data['company_name'] 				= $company_name;
				$page_data['platform_type'] 			= $platform_type;
				$page_data['project_detail'] 			= $project_detail;
				$page_data['existing_app'] 				= $existing_app;
				$page_data['subject'] 					= $subject;
				// $page_data['subject'] = !empty($data['subject']) ? $data['subject'] : "Contact Us";

				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$full_name);
					// print_r($sendMail);exit;
				}

				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$full_name);
				}
				//echo $sendMail;exit;

				#Email sent end here				

				// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
				//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
				redirect(base_url()."thankyou.html", 'refresh');
			}
			
		}
		$this->load->view($this->template, $page_data);
	}
	
	public function websitedevelopement()
	{
		
		$page_data['page_title'] 			= SITE_NAME.' | Welcome to '.SITE_NAME; #Page Title
		$page_data['page_name']  			= "website-developement"; #View Page
		$page_data['subject']				= ""; 
		$page_data['message']				= "";
		$page_data['digitalmarketing']  	= "";
		$page_data['mobileAppDevelopement']	= ""; 
		$page_data['websitedevelopement']	= 1; 
		$page_data['websitedevelopement']	= ""; 
		if($_POST)
		{
			$service_contact_type	= 'CONTACT-WEBSITE-DEVELOPEMENT';
			$full_name 				=  $this->input->post('full_name');
			$email 					=  $this->input->post('email');
			$mobile_number 			=  $this->input->post('mobile_number');
			$company_name 			=  $this->input->post('company_name');
			$website_type 			=  $this->input->post('website_type');
			$project_description 	=  $this->input->post('project_description');
			$subject				= "Enquiry";

			$postData = array(
				"service_contact_type"	=>  $service_contact_type,
				"full_name" 			=>  $full_name,
				"email" 				=>  $email,
				"mobile_number" 		=>  $mobile_number,
				"company_name" 			=>  $company_name,
				"website_type" 			=>  $website_type,
				"project_description" 	=>  $project_description,
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);

			$this->db->insert('services',$postData);
			$header_id = $this->db->insert_id();

			if($header_id !="")
			{
				$from 									= NOREPLY_EMAIL;				
				$to 									= CONTACT_EMAIL;
				$page_data['service_contact_type'] 		= $service_contact_type;
				$page_data['full_name'] 				= $full_name;
				$page_data['email'] 					= $email;
				$page_data['mobile_number'] 			= $mobile_number;
				$page_data['company_name'] 				= $company_name;
				$page_data['website_type'] 				= $website_type;
				$page_data['project_description'] 		= $project_description;
				$page_data['subject'] 					= $subject;
				// $page_data['subject'] = !empty($data['subject']) ? $data['subject'] : "Contact Us";

				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$full_name);
					// print_r($sendMail);exit;
				}

				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$full_name);
				}
				//echo $sendMail;exit;

				#Email sent end here				

				// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
				//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
				redirect(base_url()."thankyou.html", 'refresh');
			}
			
		}
		$this->load->view($this->template, $page_data);
	}public function webappdevelopement()
	{
		
		$page_data['page_title'] 			= SITE_NAME.' | Welcome to '.SITE_NAME; #Page Title
		$page_data['page_name']  			= "web-app-developement"; #View Page
		$page_data['subject']				= ""; 
		$page_data['message']				= ""; 
		$page_data['digitalmarketing']  	= "";
		$page_data['websitedevelopement']	= ""; 
		$page_data['webappdevelopement']	= 1; 
		if($_POST)
		{
			$service_contact_type	= 'CONTACT-WEB-APP-DEVELOPEMENT';
			$full_name 				=  $this->input->post('full_name');
			$email 					=  $this->input->post('email');
			$mobile_number 			=  $this->input->post('mobile_number');
			$company_name 			=  $this->input->post('company_name');
			$industry_type 			=  $this->input->post('industry_type');
			$subject				= "Enquiry";

			$postData = array(
				"service_contact_type"	=>  $service_contact_type,
				"full_name" 			=>  $full_name,
				"email" 				=>  $email,
				"mobile_number" 		=>  $mobile_number,
				"company_name" 			=>  $company_name,
				"industry_type" 		=>  $industry_type,
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);

			$this->db->insert('services',$postData);
			$header_id = $this->db->insert_id();

			if($header_id !="")
			{
				$from 									= NOREPLY_EMAIL;				
				$to 									= CONTACT_EMAIL;
				$page_data['service_contact_type'] 		= $service_contact_type;
				$page_data['full_name'] 				= $full_name;
				$page_data['email'] 					= $email;
				$page_data['mobile_number'] 			= $mobile_number;
				$page_data['company_name'] 				= $company_name;
				$page_data['industry_type'] 			= $industry_type;
				$page_data['subject'] 					= $subject;
				// $page_data['subject'] = !empty($data['subject']) ? $data['subject'] : "Contact Us";

				$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				if(EMAIL_TYPE == 2) #SMTP
				{
					$sendMail = Send_SMTP($from,$to,$subject,$message,$full_name);
					// print_r($sendMail);exit;
				}

				else  #Send Grid 
				{
					$sendMail = Send_Grid($from,$to,$subject,$message,$full_name);
				}
				//echo $sendMail;exit;

				#Email sent end here				

				// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
				//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
				redirect(base_url()."thankyou.html", 'refresh');
			}
			
		}
		$this->load->view($this->template, $page_data);
	}public function crm()
	{
		
		$page_data['page_title'] = SITE_NAME.' | Welcome to '.SITE_NAME; #Page Title
		$page_data['page_name']  = "crm"; #View Page
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
	
	public function cms($url = "")
	{	
		$cmsData = $page_data['cmsData'] =  $this->db->query("select * from cms
					where cms_url='".$url."' AND cms_status = 1
				")->result_array();
				
		$page_data['page_title'] = !empty($cmsData[0]['cms_title']) ? SITE_NAME.' | '.$cmsData[0]['cms_title'] :"CMS Pages"; #Page Title
		$page_data['page_name']  = "cms"; #View Page
		$this->load->view($this->template, $page_data);
	}
	
	// public function contactUs()
	// {
	// 	$page_data['page_title'] = SITE_NAME.' | Contact Us'; #Page Title
	// 	$page_data['page_name']  = "contact_us"; #View Page
		
	// 	if($_POST)
	// 	{
	// 		$data['first_name'] = $this->input->post('first_name');
	// 		$data['email'] = $this->input->post('email');
	// 		$data['phone'] = $this->input->post('phone');
	// 		$data['subject'] = $this->input->post('subject');
	// 		$data['message'] = $this->input->post('message');
	// 		$data['project_query'] = $this->input->post('project_query');
	// 		$data['your_budget'] = $this->input->post('your_budget');
	// 		$data['contact_date'] = time();
			
	// 		$this->db->insert('contact_us', $data);
	// 		$id = $this->db->insert_id();
			
	// 		if($id !="")
	// 		{
	// 			#Email sent start here
	// 			$page_data['contact_us'] =1;
				
	// 			$to = CONTACT_EMAIL;
	// 			$from = $data['email'];
	// 			$page_data['cname'] = $fromName = $data['first_name'];
	// 			$subject = $page_data['subject'] ="";	 
	// 			$page_data['message'] = $data['message'];	 
				
	// 			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
	// 			if(EMAIL_TYPE == 2) #SMTP
	// 			{
	// 				$sendMail = Send_SMTP($from,$to,$subject,$message,$fromName);
	// 			}
	// 			else  #Send Grid 
	// 			{
	// 				$sendMail = Send_Grid($from,$to,$subject,$message,$fromName);
	// 			}
	// 			#Email sent end here
	// 		}
	// 		$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
	// 		redirect(base_url()."home.html", 'refresh');
	// 	}
	// 	$this->load->view($this->template, $page_data);
	// }
	public function contact()
	{		
		$page_data['page_title'] 	= SITE_NAME.' | Contact Us'; #Page Title
		$page_data['page_name']  	= "contact-us"; #View Page
		$page_data['contact_us'] 	= 1;
		
		if($_POST)
		{

			$name				= $this->input->post('name');
			$phone				= $this->input->post('phone');
			$email				= $this->input->post('email');
			$location_name		= $this->input->post('location_name');
			$message			= $this->input->post('message');
			$subject			= "Enquiry";
			
			$from 								= NOREPLY_EMAIL;				
			$to 								= CONTACT_EMAIL;
			$page_data['name'] 					= $name;
			$page_data['phone'] 				= $phone;
			$page_data['email'] 				= $email;
			$page_data['location_name'] 		= $location_name;
			$page_data['message'] 				= $message;	
			
			$message = $this->load->view('mail_template/front_mail_template', $page_data, true);
			
			if(EMAIL_TYPE == 2) #SMTP
			{
				$sendMail = Send_SMTP($from,$to,$subject,$message,$name);
				// print_r($sendMail);exit;
			}

			else  #Send Grid 
			{
				$sendMail = Send_Grid($from,$to,$subject,$message,$name);
			}
			// echo $sendMail;exit;
				
			
			// $this->session->set_flashdata('flash_message' , "Thank you for contact!");
			//$this->session->set_flashdata('success_message' , 'Thank you for contact!! Our Technical Team will get back to you soon...');
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}
	# ajax Select Qualification
	
	
	public function logout() 
	{
		$this->session->sess_destroy();
		
		$this->session->set_flashdata('success_message' , 'Logged out successfully!');
		redirect(base_url(), 'refresh');
		
	 	$page_data['page_title'] = SITE_NAME.' | Log Out'; #Page Title
		$page_data['page_name']  = "logout"; #logout Page 
		
        #$this->session->set_flashdata('flash_message' , get_phrase('logged_out_successfully!'));
		
		$this->load->view($this->template, $page_data);
    }
	
	# Ajax Select District
	
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
		$page_data['page_name']  = "careers"; #View Page
		
		if($_POST)
		{

			$customer_name 			= $this->input->post('customer_name');
			$email 					= $this->input->post('email');
			$mobile_number 			= $this->input->post('mobile_number');
			$internship_duration 	= $this->input->post('internshipDuration');
			$message 				= $this->input->post('message');

			$postData	= array(
				'careers_type'			=> 'INTERNSHIP',
				'customer_name'			=> $customer_name,
				'email'					=> $email,
				'mobile_number'			=> $mobile_number,
				'internship_duration'	=> $internship_duration,
				'message'				=> $message,
				"created_by" 	  		=> -1,
				"created_date" 	  		=> $this->date_time,
				"last_updated_by" 	 	=> -1,
				"last_updated_date" 	=> $this->date_time
			);
			
			
			$this->db->insert('careers', $postData);
			$id = $this->db->insert_id();
			
			if($id !="")
			{
				# Upload Resume start
				if( isset($_FILES['applicantResume']['name']) && $_FILES['applicantResume']['name'] != "") 
				{							
					if(is_uploaded_file($_FILES['applicantResume']['tmp_name']))
					{
						$file_parts = pathinfo($_FILES['applicantResume']['name']);
						$ext = $file_parts['extension'];
						$applicantResume= $id.".".$ext;
						move_uploaded_file($_FILES['applicantResume']['tmp_name'], 'uploads/careers/candidate_resume/'.$applicantResume);
					}
					
					$candidateResume['candidate_resume'] = $applicantResume;
					$this->db->where('careers_id', $id);
					$this->db->update('careers', $candidateResume);
				}
				# Upload Resume end
				
				// #Email sent start here
				// $page_data['careers'] =1;
				
				// $to = "hr@jesperapps.com";
				// $from = $data['email'];
				// $page_data['name'] = $fromName = $data['first_name']." ".$data['last_name'];
				// $subject = $page_data['subject'] = "Careers - Submitted Resume with some basic Details.";

				// $page_data['representing'] = $data['representing'];	 
				// $page_data['company_name'] = $data['company_name'];	 
				// $page_data['desigation'] = $data['desigation'];	 
				// $page_data['willing_nda'] = $data['willing_nda'];	 
				
				// $page_data['mobile_number'] = $data['mobile_number'];	 
				// $page_data['alternate_mobile_number'] = $data['alternate_mobile_number'];	 
				// $page_data['preferable_day_contact'] = $data['preferable_day_contact'];	 
				// $page_data['preferable_time_contact'] = $data['preferable_time_contact'];	 
				// $page_data['country'] = $data['country'];	 
				// $page_data['city'] = $data['city'];	
				// $page_data['key_specialisation_1'] = $data['key_specialisation_1'];	
				// $page_data['key_specialisation_2'] = $data['key_specialisation_2'];	
				// $page_data['key_specialisation_3'] = $data['key_specialisation_3'];	
				
				// $page_data['experience_key_specialisation_1'] = $data['experience_key_specialisation_1'];	
				// $page_data['experience_key_specialisation_2'] = $data['experience_key_specialisation_2'];	
				// $page_data['experience_key_specialisation_3'] = $data['experience_key_specialisation_3'];	
				
				// $page_data['employment_basis'] = $data['employment_basis'];	
				// $page_data['notice_period'] = $data['notice_period'];	
				// $page_data['other_information'] = $data['other_information'];	

				// $message = $this->load->view('mail_template/front_mail_template', $page_data, true);
				
				// if(EMAIL_TYPE == 2) #SMTP
				// {
				// 	$sendMail = Send_SMTP($from,$to,$subject,$message,$fromName);
				// }
				// else  #Send Grid 
				// {
				// 	$sendMail = Send_Grid($from,$to,$subject,$message,$fromName);
				// }
				#Email sent end here
			}
			// $this->session->set_flashdata('success_message' , 'Thank you for filling out the form. Your response has been recorded. Someone from our team would be in touch with you soon.');
			// redirect(base_url()."home.html", 'refresh');
			redirect(base_url()."thankyou.html", 'refresh');
		}
		$this->load->view($this->template, $page_data);
	}

	public function ajaxLikeCount()
	{
		if (isset($_POST['blog_id'])) {
			$blog_id = $_POST['blog_id'];
			$ip_address = $_SERVER['REMOTE_ADDR'];

			$chckLikeExist = $this->blogs_model->likeExist($blog_id, $ip_address);

			$postData = array(
				'ip_address' => $ip_address,
				'blog_id' => $blog_id,
				'last_updated_by' => -1,
				'last_updated_date' => $this->date_time,
			);

			if (empty($chckLikeExist)) 
			{
				$postData['likes'] = 1;
				$postData['created_by'] = -1;
				$postData['created_date'] = $this->date_time;
				$this->db->insert('blog_likes', $postData);
				$status = 'Liked';
			} 
			else 
			{
				
				$blogLike = $this->blogs_model->blogLike($blog_id, $ip_address);
				$likes = ($blogLike[0]['likes'] == 0) ? 1 : 0;
				$postData['likes'] = $likes;

				$this->db->where('ip_address', $ip_address);
				$this->db->where('blog_id', $blog_id);
				$this->db->update('blog_likes', $postData);

				$status = ($likes == 1) ? 'Liked' : 'Unliked';
			}

			$result = $this->blogs_model->likeCount($blog_id);

			echo json_encode([
				'status' => $status,
				'likes' => $result[0]['like_count'] ?? 0, // Ensure the correct key is used
			]);
		} else {
			echo json_encode(['error' => 'Missing required parameters']);
		}
	}



	public function ajaxViewCount()
	{
		if (isset($_POST['blog_id'])) {
			$blog_id    = $_POST['blog_id'];
			$ip_address = $_SERVER['REMOTE_ADDR']; 

			$chckViewExist = $this->blogs_model->viewsExist($blog_id, $ip_address);

			$postData = array(
				'ip_address'     => $ip_address,
				'blog_id'        => $blog_id,
				"last_updated_by" => -1,
				"last_updated_date" => $this->date_time
			);

			if (count($chckViewExist) == 0) {
				
				$postData['views'] 			= 1;
				$postData['created_by'] 	= -1;
				$postData['created_date'] 	= $this->date_time;
				$this->db->insert('blog_views', $postData);
				$status = 'Viewed';
			} 
			else 
			{
				$previousViews = $chckViewExist[0]['views'];

				$postData['views'] = $previousViews;

				$this->db->where('ip_address', $ip_address);
				$this->db->where('blog_id', $blog_id);
				$this->db->update('blog_views', $postData);
				$status = 'Viewed';
			}

			$result = $this->blogs_model->viewsCount($blog_id);

			if (!empty($result)) {
				
				echo json_encode([
					'status' => $status,
					'views' => $result[0]['view_count'] ?? 0  
				]);
			} 
			else 
			{
				echo json_encode(['error' => 'Failed to retrieve views count.']);
			}
		} 
		else 
		{
			echo json_encode(['error' => 'Missing required parameters']);
		}
	}






	
}
	
?>
