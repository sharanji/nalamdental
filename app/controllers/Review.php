<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Review extends CI_Controller 
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
	
	function manageReview($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type']              = $type;
		$page_data['id']                = $id;
		$page_data['manageReview']    	= 1;
		$page_data['page_name']         = 'review/manageReview';
		$page_data['page_title']        = 'Reviews';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$review_type	= $this->input->post('review_type');

					if($review_type=="SERVICE-REVIEW")
					{
						$service_type		= isset($_POST['service_type']) ? $_POST['service_type'] : NULL;
					}
					else
					{
						$service_type			= NULL;
					}
					

					$headerData		= array(
						'review_type'		=>	$review_type,
						'service_type'		=>	$service_type,
						'created_by'        => $this->user_id,
						'created_date'      => $this->date_time,
						'last_updated_by'   => $this->user_id,
						'last_updated_date' => $this->date_time
					);

					$this->db->insert('reviews_headers',$headerData);
					$header_id = $this->db->insert_id();

					if($header_id)
					{
						$customer_name_count = isset($_POST['customer_name']) ? count(array_filter($_POST['customer_name'])) : 0;

						if ($customer_name_count > 0) 
						{
							$upload_dir = 'uploads/reviews/'; 

							for ($dp = 0; $dp < $customer_name_count; $dp++) {
								// Initialize the data to insert
								$lineData = array(
									'header_id'         => $header_id,
									'customer_name'     => isset($_POST['customer_name'][$dp]) ? $_POST['customer_name'][$dp] : NULL,
									'designation'       => isset($_POST['designation'][$dp]) ? $_POST['designation'][$dp] : NULL,
									'description'       => isset($_POST['description'][$dp]) ? $_POST['description'][$dp] : NULL,
									'review'            => isset($_POST['review'][$dp]) ? $_POST['review'][$dp] : NULL,
									'created_by'        => $this->user_id,
									'created_date'      => $this->date_time,
									'last_updated_by'   => $this->user_id,
									'last_updated_date' => $this->date_time
								);
							
								if ($review_type == 'COMMON-REVIEW') {
									if (isset($_FILES['review_image']['name'][$dp]) && $_FILES['review_image']['name'][$dp] != "") {
										// File validation (just checking if it's an image and not exceeding size limit)
										$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];  // Allowed file types
										$max_file_size = 4 * 1024 * 1024;  // 4MB max file size
							
										$file_parts = pathinfo($_FILES['review_image']['name'][$dp]);
										$ext = strtolower($file_parts['extension']);
										$file_size = $_FILES['review_image']['size'][$dp];
							
										// Validate file type
										if (in_array($ext, $allowed_extensions) && $file_size <= $max_file_size) {
											// Proceed with file upload
											$random_code1 = rand();
											$file_name1 = $_FILES['review_image']['name'][$dp];
											$file_tmpname1 = $_FILES['review_image']['tmp_name'][$dp];
											$filesName = trim($random_code1 . '@' . $file_name1);
											$filepath = $upload_dir . $filesName;
							
											// Move the uploaded file to the server
											if (move_uploaded_file($file_tmpname1, $filepath)) {
												// Set the image path in the data array
												$lineData['image_path'] = $filesName;
							
												// Insert data into the database
												$this->db->insert('reviews_lines', $lineData);
												$line_id = $this->db->insert_id();
											}
										}
									}
								}
							
								if ($review_type == 'SERVICE-REVIEW') {
									// Set image_path as NULL for SERVICE-TYPE
									$lineData['image_path'] = NULL;
							
									// Insert data into the database
									$this->db->insert('reviews_lines', $lineData);
									$line_id = $this->db->insert_id();
								}
							}
							
							if($header_id)
							{
								if(isset($_POST["save_btn"]))
								{
									$this->session->set_flashdata('flash_message' , "Review saved successfully!");
									redirect(base_url() . 'review/manageReview/edit/'.$header_id, 'refresh');
								}
								else if(isset($_POST["submit_btn"])) {
									$this->session->set_flashdata('flash_message' , "Review submitted successfully!");
									redirect(base_url() . 'review/manageReview/', 'refresh');
								}
							}
						}
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$result					= $this->reviews_model->getViewData($id);
				$page_data['editData'] 	= $result['editData'];
				$page_data['lineData'] 	= $result['lineData'];

                if($_POST)
				{
					
					$review_type	= $this->input->post('review_type');
					
					if($review_type=="SERVICE-REVIEW")
					{
						$service_type		= isset($_POST['service_type']) ? $_POST['service_type'] : NULL;
					}
					else
					{
						$service_type			= NULL;
					}

					$headerData		= array(
						'review_type'		=>	$review_type,
						'service_type'		=>	$service_type,
						'last_updated_by'   => $this->user_id,
						'last_updated_date' => $this->date_time
					);

					$this->db->where('header_id', $id);
					$line_id = $this->db->update('reviews_headers', $headerData);

					$customer_name_count = isset($_POST['customer_name']) ? count(array_filter($_POST['customer_name'])) : 0;

					
					if ($customer_name_count > 0) 
					{
						$upload_dir = 'uploads/reviews/';

						
						for ($dp = 0; $dp < $customer_name_count; $dp++) {

							$line_id=isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] : 0;

							$lineData = array(
								'header_id'         => $id,
								'customer_name'     => isset($_POST['customer_name'][$dp]) ? $_POST['customer_name'][$dp] : NULL,
								'designation'       => isset($_POST['designation'][$dp]) ? $_POST['designation'][$dp] : NULL,
								'description'       => isset($_POST['description'][$dp]) ? $_POST['description'][$dp] : NULL,
								'review'            => isset($_POST['review'][$dp]) ? $_POST['review'][$dp] : NULL,
								'last_updated_by'   => $this->user_id,
								'last_updated_date' => $this->date_time
							);
							
							if($line_id==0)
							{
								if ($review_type == 'COMMON-REVIEW') {
									if (isset($_FILES['review_image']['name'][$dp]) && $_FILES['review_image']['name'][$dp] != "") {
										
										$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; 
										$max_file_size = 4 * 1024 * 1024;
							
										$file_parts = pathinfo($_FILES['review_image']['name'][$dp]);
										$ext = strtolower($file_parts['extension']);
										$file_size = $_FILES['review_image']['size'][$dp];
							
										if (in_array($ext, $allowed_extensions) && $file_size <= $max_file_size) {
										
											$random_code1 = rand();
											$file_name1 = $_FILES['review_image']['name'][$dp];
											$file_tmpname1 = $_FILES['review_image']['tmp_name'][$dp];
											$filesName = trim($random_code1 . '@' . $file_name1);
											$filepath = $upload_dir . $filesName;
							
											if (move_uploaded_file($file_tmpname1, $filepath)) {
											
												$lineData['image_path'] = $filesName;
											}
										}
									}

									$this->db->insert('reviews_lines',$lineData);
									$line_id = $this->db->insert_id();
								}
							
								if ($review_type == 'SERVICE-REVIEW') {
									$lineData['image_path'] = NULL;
							
									$this->db->insert('reviews_lines',$lineData);
									$this->db->insert_id();
								}
							}
							else{

								if ($review_type == 'COMMON-REVIEW') {

									if (isset($_FILES['review_image']['name'][$dp]) && $_FILES['review_image']['name'][$dp] != "") {
										
										$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif']; 
										$max_file_size = 4 * 1024 * 1024;
							
										$file_parts = pathinfo($_FILES['review_image']['name'][$dp]);
										$ext = strtolower($file_parts['extension']);
										$file_size = $_FILES['review_image']['size'][$dp];
							
										if (in_array($ext, $allowed_extensions) && $file_size <= $max_file_size) {
										
											$random_code1 = rand();
											$file_name1 = $_FILES['review_image']['name'][$dp];
											$file_tmpname1 = $_FILES['review_image']['tmp_name'][$dp];
											$filesName = trim($random_code1 . '@' . $file_name1);
											$filepath = $upload_dir . $filesName;
							
											if (move_uploaded_file($file_tmpname1, $filepath)) 
											{
												$lineData['image_path'] = $filesName;
											}
										}
									}
									$this->db->where('line_id', $line_id);
									$this->db->where('header_id', $id);
									$this->db->update('reviews_lines', $lineData);
								}
							
								if ($review_type == 'SERVICE-REVIEW') {
									
									$lineData['image_path'] = NULL;
									
									$this->db->where('line_id', $line_id);
									$this->db->where('header_id', $id);
									$this->db->update('reviews_lines', $lineData);
								}
							}
						}
						
						if($id)
						{
							if(isset($_POST["save_btn"]))
							{
								$this->session->set_flashdata('flash_message' , "Review saved successfully!");
								redirect(base_url() . 'review/manageReview/edit/'.$id, 'refresh');
							}
							else if(isset($_POST["submit_btn"])) {
								$this->session->set_flashdata('flash_message' , "Review submitted successfully!");
								redirect(base_url() . 'review/manageReview/', 'refresh');
							}
							
						}
					}
				}
				
			break;

            case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data=array(
						'active_flag' 		=> 'Y',
						'inactive_date' 	=> NULL,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date' => $this->date_time,
					);
					$succ_msg = 'Review active successfully!';
				}
				else
				{
					$data=array(
						'active_flag' 		=> 'N',
						'inactive_date' 	=> $this->date_time,
						'last_updated_by'	=> $this->user_id,
						'last_updated_date' => $this->date_time
					);
					#$data['end_date'] = $this->date;
					$succ_msg = 'Review inactive successfully!';
				}
				$this->db->where('header_id', $id);
				$this->db->update('reviews_headers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				$totalResult["header_data"] = $this->reviews_model->getReviews("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$review_type 		    = isset($_GET['review_type']) ? $_GET['review_type'] :NULL;
				$active_flag 			= isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$this->redirectURL = 'projects/manageProjects?review_type='.$review_type.'&active_flag='.$active_flag.'';
				
				if ($review_type != NULL || $active_flag !=NULL ) 
                {
					$base_url = base_url().$this->redirectURL;
				} 
                else 
                {
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
				
				$result = $this->reviews_model->getReviews($limit, $offset, $this->pageCount);
				
				$page_data['resultData'] = $result["headerData"];

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

	function getReviewLines()
    {	
		$active_status 	= $this->common_model->lov('ACTIVE-STATUS');
		$activeStatusOptions 	= '';
		if (count($active_status) > 0) 
		{
			foreach ($active_status as $val) 
			{
				$activeStatusOptions .= '<option value="'.$val['list_code'].'">'.ucfirst($val['list_value']).'</option>';
			}
		}

		$data['activeStatus'] = $activeStatusOptions;
		echo json_encode($data);
		exit;		
	}

	function getProjects(){
	
		$result = $this->projects_model->getProjectsAll();

	
		if( count($result) > 0)
		{
			echo '<option value="0">- Select -</option>';
			foreach($result as $val)
			{
				echo '<option value="'.$val['project_id'].'">'.ucfirst($val['project_name']).'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
	}

	function ajaxAvailableReview($type = '', $id = '', $status = '')
    {
		
		switch($type)
		{
			case "status":
				if($status == 1){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Review is Available!';
				}
				else{
					$data['active_flag'] ='N';
					$succ_msg = 'Review is Unavailable!';
				}

				$data['last_updated_by'] 	= $this->user_id;
				$data['last_updated_date'] 	= $this->date_time;

				$this->db->where('line_id', $id);
				$this->db->update('reviews_lines', $data);
				echo $succ_msg;exit;
			break;
		}
	}
}
?>
