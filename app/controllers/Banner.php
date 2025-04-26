<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banner extends CI_Controller 
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
    function manage_banner($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['ManageBanner'] = 1;
		$page_data['page_name']  = 'banner/manage_banner';
		$page_data['page_title'] = 'Banners';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['banner_type'] = $this->input->post('banner_type');
					$data['banner_title'] = $this->input->post('banner_title');

					$existBranchCode = $this->db->query("select banner_id from banner 
					where 
					banner_type='".$data['banner_type']."' and
					banner_title='".$data['banner_title']."'
					
					")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist!");
						redirect(base_url() . 'banner/manage_banner/add', 'refresh');
					}

					$data['banner_url'] = $this->input->post('banner_url');
					$data['banner_description'] = $this->input->post('banner_description');
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
								
					#Audit Trails Add Start here
					$tableName = table_banner;
					$menuName = banners;
					$description = "Banner created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails Add end here

					$this->db->insert('banner', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if(!empty($_FILES['banner_image']['name']))
						{
							move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/banner/'.$id.'.png');
						}
						$this->session->set_flashdata('flash_message' ,'Banner Added Successfully');
						redirect(base_url() . 'banner/manage_banner', 'refresh');
					}
					
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('banner', array('banner_id' => $id))->result_array();
				if($_POST)
				{
					$data['banner_type'] = $this->input->post('banner_type');
					$data['banner_title'] = $this->input->post('banner_title');

					$existBranchCode = $this->db->query("select banner_id from banner 
					where 
					banner_id !=  '".$id."'  
					and banner_type='".$data['banner_type']."' 
					and banner_title='".$data['banner_title']."' 
					
					")->result_array();
					if(count($existBranchCode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist!");
						redirect(base_url() . 'banner/manage_banner/edit/'.$id, 'refresh');
					}

					$data['banner_url'] = $this->input->post('banner_url');
					$data['banner_description'] = $this->input->post('banner_description');
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					#Audit Trails Add Start here
					$tableName = table_banner;
					$menuName = banners;
					$description = "Banner updated successsfully!";
					auditTrails(array_filter($data),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here

					$this->db->where('banner_id', $id);
					$result = $this->db->update('banner', $data);
					
					if($result > 0)
					{
						if(!empty($_FILES['banner_image']['name']))
						{
							#compressImage($_FILES['banner_image']['tmp_name'],'uploads/banner/' . $id . '.png',60);
							move_uploaded_file($_FILES['banner_image']['tmp_name'], 'uploads/banner/'.$id.'.png');
						}
						
						$this->session->set_flashdata('flash_message' ,'Banner Updated successfully');
						redirect(base_url() . 'banner/manage_banner', 'refresh');
					}
					
				}
			break;
			
			case "delete": #Delete
				$this->db->where('banner_id', $id);
				$this->db->delete('banner');
				$this->session->set_flashdata('flash_message' ,'Banner Deleted successfully!');
				redirect(base_url() . 'banner/manage_banner/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				
				if($status == 'Y')
				{
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Banner Active successfully!';
				}
				else
				{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
                    $succ_msg = 'Banner Inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = table_banner;
				$menuName = banners;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here
				
				$this->db->where('banner_id', $id);
				$this->db->update('banner', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				if(isset($_POST['default_submit']) && isset($_POST['default_banner']))
				{
					# Set Default banner
					$default_banner = $_POST["default_banner"];
					
					if($default_banner){
						$banner_update = $this->db->update("banner", array("default_banner" => 0), array("banner_id >" => 0));
					}
					$result = $this->db->update("banner", array("default_banner" => 1), array("banner_id" => $default_banner));
					
					$this->session->set_flashdata('flash_message' ,'Default banner updated successfully!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
				
				$totalResult = $this->banner_model->getBanner("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'banner/manage_banner?banner_type=&banner_title=';

				if (!empty($_GET['banner_type']) || !empty($_GET['banner_title'])) {
					$base_url = base_url('banner/manage_banner?banner_type='.$_GET['banner_type'].'&banner_title='.$_GET['banner_title']);
				} else {
					$base_url = base_url('banner/manage_banner?banner_type=&banner_title=');
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
				
				$page_data['resultData']  = $result= $data =$this->banner_model->getBanner($limit, $offset,$this->pageCount);
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
