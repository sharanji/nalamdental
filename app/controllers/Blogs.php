<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Blogs extends CI_Controller 
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
	
	function manageBlogs($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 			= $type;
		$page_data['id'] 			= $id;
		$page_data['manageBlogs'] 	= 1;
		$page_data['page_name']  	= 'blogs/manageBlogs';
		$page_data['page_title'] 	= 'Blogs';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$best_blog = isset($_POST['best_blog']) ? $_POST['best_blog'] : 'N';

					$blog_title		= $this->input->post('blog_title');
					$blog_category	= $this->input->post('blog_category');

					$checkBlogList 	= $this->blogs_model->checkBlogsExist($blog_title,$blog_category,$type,NULL);

					if(count($checkBlogList) > 0)
					{
						$this->session->set_flashdata('error_message' , "Blog already exist!");
						redirect(base_url() . 'blogs/manageBlogs/add', 'refresh');
					}

					$postData = array(
						'blog_title' 	     	=> $blog_title,
						'short_description' 	=> $this->input->post('short_description'),
						'client_name' 	     	=> $this->input->post('client_name'),
						'description' 			=> $this->input->post('description'),
						'editor_images' 		=> $this->input->post('editor_images'), // JSON-encoded image URLs
						'blog_category' 	 	=> $blog_category,
						'best_blog' 			=> $best_blog,
						'blog_tags'        		=> $this->input->post('blog_tags'),
						"created_by" 	  		=> $this->user_id,
						"created_date" 	  		=> $this->date_time,
						"last_updated_by" 	  	=> $this->user_id,
						"last_updated_date"		=> $this->date_time
					);

					$this->db->insert('blogs', $postData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						if( isset($_FILES['client_image']['name']) && $_FILES['client_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['client_image']['tmp_name'], 'uploads/blogs/client_image/'.$header_id.'.png');
						}
						if( isset($_FILES['blog_image']['name']) && $_FILES['blog_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['blog_image']['tmp_name'], 'uploads/blogs/'.$header_id.'.png');
						}
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Blog saved successfully!");
							redirect(base_url() . 'blogs/manageBlogs/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Blog submitted successfully!");
							redirect(base_url() . 'blogs/manageBlogs', 'refresh');
						}
						
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$page_data['editData'] = $this->blogs_model->getViewData($id);
				
				if($_POST)
				{
					$best_blog = isset($_POST['best_blog']) ? $_POST['best_blog'] : 'N';

					$blog_title		= $this->input->post('blog_title');

					$blog_category	= $this->input->post('blog_category');

					$checkBlogList 	= $this->blogs_model->checkBlogsExist($blog_title,$blog_category,$type,$id);

					if(count($checkBlogList) > 0)
					{
						$this->session->set_flashdata('error_message' , "Blog already exist!");
						redirect(base_url() . 'blogs/manageBlogs/edit/'.$id, 'refresh');
					}

					$postData = array(
						'blog_title' 	     	=> $blog_title,
						'short_description' 	=> $this->input->post('short_description'),
						'client_name' 	     	=> $this->input->post('client_name'),
						'description' 			=> $this->input->post('description'),
						'blog_category' 	 	=> $blog_category,
						'best_blog' 			=> $best_blog,
						'blog_tags'        		=> $this->input->post('blog_tags'),
						"created_by" 	  		=> $this->user_id,
						"created_date" 	  		=> $this->date_time,
						"last_updated_by" 	  	=> $this->user_id,
						"last_updated_date"		=> $this->date_time
					);
					
					$this->db->where('blog_id', $id);
					$result = $this->db->update('blogs', $postData);
					
					if($result)
					{
						if( isset($_FILES['client_image']['name']) && $_FILES['client_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['client_image']['tmp_name'], 'uploads/blogs/client_image/'.$id.'.png');
						}
						if( isset($_FILES['blog_image']['name']) && $_FILES['blog_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['blog_image']['tmp_name'], 'uploads/blogs/'.$id.'.png');
						}

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Blog saved successfully!");
							redirect(base_url() . 'blogs/manageBlogs/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Blog submitted successfully!");
							redirect(base_url() . 'blogs/manageBlogs', 'refresh');
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

					$succ_msg = 'Blog active successfully!';
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
					$succ_msg = 'Blog inactive successfully!';
				}

				$this->db->where('blog_id', $id);
				$this->db->update('blogs', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->blogs_model->getBlogs("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$keywords 			= isset($_GET['keywords']) ? $_GET['keywords'] :NULL;
				$blog_category 		= isset($_GET['blog_category']) ? $_GET['blog_category'] :NULL;
				$active_flag 		= isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				
				$this->redirectURL = 'blogs/manageBlogs?keywords='.$keywords.'&blog_category='.$blog_category.'&active_flag='.$active_flag.'';
				
				if ($keywords != NULL || $blog_category != NULL || $active_flag) {
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
				
				$page_data['resultData'] = $result = $this->blogs_model->getBlogs($limit, $offset, $this->pageCount);
				
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

	public function upload_editor_image() {
		if (isset($_FILES['image'])) {
			$target_dir = "uploads/blogs/editor_images/";
			$filename = $_FILES["image"]["name"];
			$target_file = $target_dir . $filename;
	
			if (!is_dir($target_dir)) {
				mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
			}
	
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
				echo json_encode(['status' => 'success', 'url' => base_url($target_file)]);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'No image file was uploaded.']);
		}
	}
	
	

}
?>
