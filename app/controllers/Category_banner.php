<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category_banner extends CI_Controller 
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
		
	#Manage Banner
    function manageCategoryBanner($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageCategoryBanner'] = 1;
		
		$page_data['page_name']  = 'category_banner/ManageCategoryBanner';
		$page_data['page_title'] = 'Category Banner';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)

				{	
					
					$data['category_name'] = $this->input->post('category_name');

					$existBranchCode = $this->db->query("select banner_id from inv_category_banners where category_name='".$data['category_name']."' ")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist!");
						redirect(base_url() . 'category_banner/ManageCategoryBanner/add', 'refresh');
					}
					$data['branch_id'] = $this->input->post('branch_id');
					$data['category_id'] = $this->input->post('category_id');
					$data['category_description'] = $this->input->post('category_description');
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
								
					#Audit Trails Add Start here
					$tableName = table_inv_category_banners;
					$menuName = customer_banner;
					$description = "Category banner created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails Add end here

					$this->db->insert('inv_category_banners', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if(!empty($_FILES['banner_image']['name']))
						{
							move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/category_sliders/'.$id.'.png');
						}
						$this->session->set_flashdata('flash_message' ,'Category sliders added successfully');
						redirect(base_url() . 'category_banner/ManageCategoryBanner', 'refresh');
					}	
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('inv_category_banners', array('banner_id' => $id))->result_array();
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['category_name'] = $this->input->post('category_name');

					$existBranchCode = $this->db->query("select banner_id from inv_category_banners 
					where 
					banner_id !=  '".$id."'  and
					category_name='".$data['category_name']."' ")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist!");
						redirect(base_url() . 'category_banner/ManageCategoryBanner/edit/'.$id, 'refresh');
					}
					$data['branch_id'] = $this->input->post('branch_id');
					$data['category_id'] = $this->input->post('category_id');
					$data['start_date'] = !empty($_POST['start_date']) ? date("Y-m-d",strtotime($_POST['start_date'])) : null;
					$data['end_date'] =!empty($_POST['end_date']) ? date("Y-m-d",strtotime($_POST['end_date'])) : null;
					$data['category_description'] = $this->input->post('category_description');

					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					#Audit Trails Add Start here
					$tableName = table_inv_category_banners;
					$menuName = customer_banner;
					$description = "Category banner updated successsfully!";
					auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here

					$this->db->where('banner_id', $id);
					$result = $this->db->update('inv_category_banners', $data);
					
					if($result > 0)
					{
						if(!empty($_FILES['banner_image']['name']))
						{
							#compressImage($_FILES['banner_image']['tmp_name'],'uploads/banner/' . $id . '.png',60);
							move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/category_sliders/'.$id.'.png');
						}
						
						$this->session->set_flashdata('flash_message' ,'Category Slider Updated successfully');
						redirect(base_url() . 'category_banner/ManageCategoryBanner', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('banner_id', $id);
				$this->db->delete('inv_category_banners');
				$this->session->set_flashdata('flash_message' ,'Banner Deleted successfully!');
				redirect(base_url() . 'category_banner/ManageCategoryBanner/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Banner Active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$succ_msg = 'Banner Inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = table_inv_category_banners;
				$menuName = customer_banner;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('banner_id', $id);
				$this->db->update('inv_category_banners', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect(base_url() . 'category_banner/ManageCategoryBanner/', 'refresh');
			break;
			
			default : #Manage
			
				if(isset($_POST['default_submit']) && isset($_POST['default_banner']))
				{
					# Set Default banner
					$default_banner = $_POST["default_banner"];
					
					if($default_banner){
						$banner_update = $this->db->update("inv_category_banners", array("default_banner" => 0), array("banner_id >" => 0));
					}
					$result = $this->db->update("inv_category_banners", array("default_banner" => 1), array("banner_id" => $default_banner));
					
					$this->session->set_flashdata('flash_message' ,'Default banner updated successfully!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
				
				$totalResult = $this->category_banner_model->getCategoryBanner("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
			
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'category_banner/ManageCategoryBanner?category_name=&active_flag='.$active_flag;

				if ( !empty($_GET['category_name']) || !empty($_GET['active_flag']) ) {
					$base_url = base_url('category_banner/ManageCategoryBanner?category_name='.$_GET['category_name'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('category_banner/ManageCategoryBanner?category_name=&active_flag=');
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
				
				$page_data['resultData']  = $result= $data =$this->category_banner_model->getCategoryBanner
				($limit, $offset,$this->pageCount);
				//$page_data['resultData']  = $result= $this->banner_model->getBanner($limit, $offset);
				
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
