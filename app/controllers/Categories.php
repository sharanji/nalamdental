<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller 
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
	
	#Manage Category
    function manage_category($type = '', $id = '', $status = '', $status1 = '')
    {
		if (isset($this->user_id) && $this->user_id == '')
            redirect(base_url() . 'admin/adminLogin', 'refresh');
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['status'] = $status;
		$page_data['status1'] = $status1;
		
		$page_data['system_settings'] = 1;
		$page_data['setup_settings'] = 1;
		$page_data['page_name']  = 'categories/manage_category';
		$page_data['page_title'] = 'Categories';
		
		switch(true)
		{
			case ($type == "add"): #Add
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['disp_seq_num'] = $this->input->post('disp_seq_num');
					$data['cat_level_1'] = $this->input->post('cat_level_1');
					$data['cat_level_2'] = $this->input->post('cat_level_2');
					$data['cat_level_3'] = $this->input->post('cat_level_3');
					$data['category_description'] = $this->input->post('category_description');
					$data['active_flag'] = $this->active_flag;
					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Category exist start here
					$chkExist = $this->db->query("select category_name from inv_categories
						where 
							category_name like'".serchFilter($data['category_name'])."' 
							")->result_array();

					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$category_name = $existValue["category_name"];

							if($category_name == $data['category_name'])
							{
								$this->session->set_flashdata('error_message' , " Category name already exist!");
								redirect(base_url() . 'categories/manage_category/add', 'refresh');
							}
						}
					}		
							
					
					# Category exist end here
					
					$this->db->insert('inv_categories', $data);
					$id = $this->db->insert_id();

					if($id !="")
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id . '.png');
						}

						$this->session->set_flashdata('flash_message' , 'Category created successfully');
						redirect(base_url() . 'categories/manage_category', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view" ): #Edit || view
				$page_data['edit_data'] = $this->db->get_where('inv_categories', array('category_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['disp_seq_num'] = $this->input->post('disp_seq_num');
					$data['cat_level_1'] = $this->input->post('cat_level_1');
					$data['cat_level_2'] = $this->input->post('cat_level_2');
					$data['cat_level_3'] = $this->input->post('cat_level_3');
					$data['category_description'] = $this->input->post('category_description');
					

					$data['start_date'] = !empty($_POST["start_date"]) ? date("Y-m-d",strtotime($_POST["start_date"])) : NULL;
					$data['end_date'] = !empty($_POST["end_date"]) ? date("Y-m-d",strtotime($_POST["end_date"])) : NULL;
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Category exist start here
					$chkExist = $this->db->query("select category_name from inv_categories
						where 
						   category_name like '".serchFilter($data['category_name'])."' 
						   and category_id !='".$id."'
					       ")->result_array();
							
					if(count($chkExist) > 0)
					{	{
					
						foreach($chkExist as $existValue)
							$category_name = $existValue["category_name"];

							if($category_name == $data['category_name'])
							{
								$this->session->set_flashdata('error_message' , "Category already exist!");
								redirect(base_url() . 'categories/manage_category/edit/'.$id, 'refresh');
							}
						}
					}
					# Category exist end here
					
					$this->db->where('category_id', $id);
					$result = $this->db->update('inv_categories', $data);

					if($result > 0)
					{
						if( isset($_FILES['category_images']['name']) && $_FILES['category_images']['name'] !="" )
						{
							move_uploaded_file($_FILES['category_images']['tmp_name'], 'uploads/category_image/' . $id . '.png');
						}
						
						$this->session->set_flashdata('flash_message' ,'Category updated successfully!');
						redirect(base_url() . 'categories/manage_category', 'refresh');
					}
				}
			break;

			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['end_date'] = NULL;
					$succ_msg = 'Category active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$data['end_date'] = $this->date;
					$succ_msg = 'Category inactive successfully!';
				}

				$this->db->where('category_id', $id);
				$this->db->update('inv_categories', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->categories_model->getManageCategories("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				

				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'categories/manage_category?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('categories/manage_category?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('categories/manage_category?keywords=&active_flag=');
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
				
				$page_data['category']  = $result= $data =$this->categories_model->getManageCategories($limit, $offset,$this->pageCount);
				
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
