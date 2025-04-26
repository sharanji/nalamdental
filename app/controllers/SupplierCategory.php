<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SupplierCategory extends CI_Controller 
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
	
	
	#Manage supplier category Starts

	function ManageSupplierCategory($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['SupplierCategory'] = 1;
		$page_data['page_name']  = 'suppliercategory/ManageSupplierCategory';
		$page_data['page_title'] = 'Supplier Category';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['category_description'] = $this->input->post('category_description');
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;


					$this->db->insert('sup_supplier_category', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier category saved successfully!");
							redirect(base_url() . 'supplierCategory/ManageSupplierCategory/edit/'.$id, 'refresh');
						}
						

					}
				}
			break;
			
			case "edit": #edit
				
				$page_data['edit_data'] = $this->db->query("select * from sup_supplier_category where category_id ='".$id."' ")
								->result_array();
				if($_POST)
				{
					$data['category_name'] = $this->input->post('category_name');
					$data['category_description'] = $this->input->post('category_description');
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$this->db->where('category_id', $id);
					$result = $this->db->update('sup_supplier_category', $data);
					
					if($result)
					{
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier category saved successfully!");
							redirect(base_url() . 'supplierCategory/ManageSupplierCategory/edit/'.$id, 'refresh');
						}
						

						
					}
				}
			break;
			
		
			case "status": 
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Supplier category active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
					$succ_msg = 'Supplier category inactive successfully!';
				}
				$this->db->where('category_id', $id);
				$this->db->update('sup_supplier_category', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			default : #Manage
				$totalResult = $this->suppliercategory_model->getManageSupplierCategory("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
			
				$keywords = isset($_GET['keywords']) ? $_GET['keywords'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;

				$this->redirectURL = 'supplierCategory/ManageSupplierCategory?keywords='.$keywords.'&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
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
				
				if($offset == 1 || $offset== "" || $offset== 0){
					$page_data["first_item"] = 1;
				}else{
					$page_data["first_item"] = $offset + 1;
				}
				
				$page_data['resultData'] = $resultData = $result = $this->suppliercategory_model->getManageSupplierCategory($limit, $offset,$this->pageCount);
				
				#Download Excel start
				$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;

				if($download_excel != NULL) 
				{
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"supplier_category_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");
	
					$handle = fopen('php://output', 'w');
					
					fputcsv($handle, array("S.No","Category Name","Category Description"));
					$cnt=1;
	
					foreach ($resultData as $supplierCategory) 
					{
						$narray=array(
							$cnt,
							$supplierCategory['category_name'],
							$supplierCategory['category_description'],
						);
	
						fputcsv($handle, $narray);
						$cnt++;
					}
					fclose($handle);
					exit;
				}
				#Download Excel end 

				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
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
	
	

	function getCategoryDescription() 
	{
		$category_id=isset($_POST["category_id"]) ? $_POST["category_id"] : NULL;
		
		if($category_id !=NULL)
		{
			$result = $this->suppliercategory_model->getCategoryDescription($category_id);
		
			echo json_encode($result);

				
			
		}
		
	}
	
	
}
?>
