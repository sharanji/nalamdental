<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Seo extends CI_Controller 
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
	
	function manageSeoContent($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 				= $type;
		$page_data['id'] 				= $id;
		$page_data['manageSeoContent'] 	= 1;
		$page_data['page_name']  		= 'seo/manageSeoContent';
		$page_data['page_title'] 		= 'SEO Contents';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$page_url		 = $this->input->post('page_url');

					$checkSeoList 	= $this->seo_model->checkSeoList($page_url,$type,NULL);

					if(count($checkSeoList) > 0)
					{
						$this->session->set_flashdata('error_message' , "Seo Content already exist!");
						redirect(base_url() . 'seo/manageSeoContent/add', 'refresh');
					}

					$postData = array(
						'page_title' 	         		=> $this->input->post('page_title'),
						'page_url' 			         	=> $page_url,
						'meta_subject' 	         		=> $this->input->post('meta_subject'),
						'meta_title'        			=> $this->input->post('meta_title'),
						'meta_keywords' 	         	=> $this->input->post('meta_keywords'),
						'meta_description' 	         	=> $this->input->post('meta_description'),
						'og_title' 	         			=> $this->input->post('og_title'),
						'og_description' 	         	=> $this->input->post('og_description'),
						'og_url' 	         			=> $this->input->post('og_url'),
						'og_sitename' 	         		=> $this->input->post('og_sitename'),
						"created_by" 	  		 		=> $this->user_id,
						"created_date" 	  		 		=> $this->date_time,
						"last_updated_by" 	  		 	=> $this->user_id,
						"last_updated_date" 	 		=> $this->date_time
					);

					$this->db->insert('seo_settings', $postData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Seo content saved successfully!");
							redirect(base_url() . 'seo/manageSeoContent/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Seo content submitted successfully!");
							redirect(base_url() . 'seo/manageSeoContent', 'refresh');
						}
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$page_data['editData'] = $this->seo_model->getViewData($id);
				if($_POST)
				{
					$page_url		 = $this->input->post('page_url');

					$checkSeoList 	= $this->seo_model->checkSeoList($page_url,$type,$id);

					if(count($checkSeoList) > 0)
					{
						$this->session->set_flashdata('error_message' , "Seo content already exist!");
						redirect(base_url() . 'seo/manageSeoContent/edit/'.$id, 'refresh');
					}

					$postData = array(
						'page_title' 	         		=> $this->input->post('page_title'),
						'page_url' 			         	=> $page_url,
						'meta_subject' 	         		=> $this->input->post('meta_subject'),
						'meta_title'        			=> $this->input->post('meta_title'),
						'meta_keywords' 	         	=> $this->input->post('meta_keywords'),
						'meta_description' 	         	=> $this->input->post('meta_description'),
						'og_title' 	         			=> $this->input->post('og_title'),
						'og_description' 	         	=> $this->input->post('og_description'),
						'og_url' 	         			=> $this->input->post('og_url'),
						'og_sitename' 	         		=> $this->input->post('og_sitename'),
						"created_by" 	  		 		=> $this->user_id,
						"created_date" 	  		 		=> $this->date_time,
						"last_updated_by" 	  		 	=> $this->user_id,
						"last_updated_date" 	 		=> $this->date_time
					);
					
					$this->db->where('seo_id', $id);
					$result = $this->db->update('seo_settings', $postData);
					
					if($result)
					{
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Seo content saved successfully!");
							redirect(base_url() . 'seo/manageSeoContent/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Seo content submitted successfully!");
							redirect(base_url() . 'seo/manageSeoContent', 'refresh');
						}
						
					}
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data=array(
						'active_flag'		=> "Y",
						'inactive_date'		=> NULL,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date'	=> $this->date_time,
						// 'end_date'			=> $this->date
					);

					$succ_msg = 'Seo content active successfully!';
				}
				else
				{
					$data=array(
						'active_flag'		=> "N",
						'inactive_date'		=> $this->date_time,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date'	=> $this->date_time,
						// 'end_date'			=> $this->date
					);
					$succ_msg = 'Seo content inactive successfully!';
				}

				$this->db->where('seo_id', $id);
				$this->db->update('seo_settings', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->seo_model->getSeoContents("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$keywords 		= isset($_GET['keywords']) ? $_GET['keywords'] :NULL;
				$active_flag 	= isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				
				$this->redirectURL = 'seo/manageSeoContent?keywords='.$keywords.'&active_flag='.$active_flag.'';
				
				if ($keywords != NULL || $active_flag) {
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
				
				if($offset == 1 || $offset== "" || $offset== 0)
				{
					$page_data["first_item"] = 1;
				}
				else
				{
					$page_data["first_item"] = $offset + 1;
				}
				
				$page_data['resultData'] = $result = $this->seo_model->getSeoContents($limit, $offset, $this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
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
