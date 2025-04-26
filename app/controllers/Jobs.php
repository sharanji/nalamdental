<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Jobs extends CI_Controller 
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
	
	function manageJobs($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 			= $type;
		$page_data['id'] 			= $id;
		$page_data['manageJobs'] 	= 1;
		$page_data['page_name']  	= 'jobs/manageJobs';
		$page_data['page_title'] 	= 'Jobs';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$job_category_id	= $this->input->post('job_category_id');
					$employment_type_id	= $this->input->post('employment_type_id');
					$qualification_id	= $this->input->post('qualification_id');
					$experience_id		= $this->input->post('experience_id');
					$designation_id		= $this->input->post('designation_id');

					$valid_from 	= isset($_POST['valid_from']) ? date("Y-m-d",strtotime($_POST['valid_from'])) : NULL;
					$valid_to 		= isset($_POST['valid_to']) ? date("Y-m-d",strtotime($_POST['valid_to'])) : NULL;
					

					$checkJobExist 	= $this->jobs_model->checkJobExist($job_category_id,$employment_type_id,$qualification_id,$experience_id,$designation_id,$type,NULL);

					if(count($checkJobExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Job already exist!");
						redirect(base_url() . 'jobs/manageJobs/add', 'refresh');
					}

					$postData = array(
						'job_category_id' 	         	=> $job_category_id,
						'role_id' 			         	=> $this->input->post('role_id'),
						'industry_type_id' 	         	=> $this->input->post('industry_type_id'),
						'employment_type_id'        	=> $employment_type_id,
						'designation_id' 	         	=> $designation_id,
						'qualification_id' 	         	=> $qualification_id,
						'experience_id' 		     	=> $experience_id,
						'job_description' 		     	=> $this->input->post('job_description'),
						'salary' 			         	=> $this->input->post('salary'),
						'functional_area' 	         	=> $this->input->post('functional_area'),
						'job_location' 		         	=> $this->input->post('job_location'),
						'roles_and_response'          	=> $this->input->post('roles_and_response'),
						'job_qualification' 	     	=> $this->input->post('job_qualification'),
						'requirements_and_skills' 	 	=> $this->input->post('requirements_and_skills'),
						'valid_from' 		         	=> $valid_from,
						'valid_to' 			         	=> $valid_to,
						// 'status' 			         	=> 1,
						"created_by" 	  		 		=> $this->user_id,
						"created_date" 	  		 		=> $this->date_time,
						"last_updated_by" 	  		 	=> $this->user_id,
						"last_updated_date" 	 		=> $this->date_time
					);

					if( isset($_POST['key_skills']) && !empty($_POST['key_skills']) )
					{
						$key_skills = array_filter($_POST['key_skills']);
						$postData['key_skills'] = implode(',',$key_skills);
					}
					else
					{
						$postData['key_skills'] ='';
					}
					$this->db->insert('org_jobs', $postData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Job saved successfully!");
							redirect(base_url() . 'jobs/manageJobs/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Job submitted Successfully!");
							redirect(base_url() . 'jobs/manageJobs', 'refresh');
						}
						
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$page_data['editData'] = $this->jobs_model->getViewData($id);
				if($_POST)
				{
					$job_category_id	= $this->input->post('job_category_id');
					$employment_type_id	= $this->input->post('employment_type_id');
					$qualification_id	= $this->input->post('qualification_id');
					$experience_id		= $this->input->post('experience_id');
					$designation_id		= $this->input->post('designation_id');
					$valid_from 		= isset($_POST['valid_from']) ? date("Y-m-d",strtotime($_POST['valid_from'])) : NULL;
					$valid_to 			= isset($_POST['valid_to']) ? date("Y-m-d",strtotime($_POST['valid_to'])) : NULL;
					

					$checkJobExist 	= $this->jobs_model->checkJobExist($job_category_id,$employment_type_id,$qualification_id,$experience_id,$designation_id,$type,$id);

					if(count($checkJobExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Job already exist!");
						redirect(base_url() . 'jobs/manageJobs/edit/'.$id, 'refresh');
					}

					$postData = array(
						'job_category_id' 	         	=> $job_category_id,
						'role_id' 			         	=> $this->input->post('role_id'),
						'industry_type_id' 	         	=> $this->input->post('industry_type_id'),
						'employment_type_id'        	=> $employment_type_id,
						'designation_id' 	         	=> $designation_id,
						'qualification_id' 	         	=> $qualification_id,
						'experience_id' 		     	=> $experience_id,
						'job_description' 		     	=> $this->input->post('job_description'),
						'salary' 			         	=> $this->input->post('salary'),
						'functional_area' 	         	=> $this->input->post('functional_area'),
						'job_location' 		         	=> $this->input->post('job_location'),
						'roles_and_response'          	=> $this->input->post('roles_and_response'),
						'job_qualification' 	     	=> $this->input->post('job_qualification'),
						'requirements_and_skills' 	 	=> $this->input->post('requirements_and_skills'),
						'valid_from' 		         	=> $valid_from,
						'valid_to' 			         	=> $valid_to,
						// 'status' 			         	=> 1,
						"last_updated_by" 	  		 	=> $this->user_id,
						"last_updated_date" 	 		=> $this->date_time
					);

					if( isset($_POST['key_skills']) && !empty($_POST['key_skills']) )
					{
						$key_skills = array_filter($_POST['key_skills']);
						$postData['key_skills'] = implode(',',$key_skills);
					}
					else
					{
						$postData['key_skills'] ='';
					}
					
					$this->db->where('job_id', $id);
					$result = $this->db->update('org_jobs', $postData);
					
					if($result)
					{
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Job saved successfully!");
							redirect(base_url() . 'jobs/manageJobs/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Job submitted successfully!");
							redirect(base_url() . 'jobs/manageJobs', 'refresh');
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

					$succ_msg = 'Job active successfully!';
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
					$succ_msg = 'Job inactive successfully!';
				}

				$this->db->where('job_id', $id);
				$this->db->update('org_jobs', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->jobs_model->getJobs("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$job_category_id 	= isset($_GET['job_category_id']) ? $_GET['job_category_id'] :NULL;
				$role_id 			= isset($_GET['role_id']) ? $_GET['role_id'] :NULL;
				$experience_id		= isset($_GET['experience_id']) ? $_GET['experience_id'] :NULL;
				$active_flag 		= isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				
				$this->redirectURL = 'jobs/manageJobs?job_category_id='.$job_category_id.'&role_id='.$role_id.'&experience_id='.$experience_id.'&active_flag='.$active_flag.'';
				
				if ($job_category_id != NULL || $role_id != NULL || $experience_id != NULL || $active_flag) {
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
				
				$page_data['resultData'] = $result = $this->jobs_model->getJobs($limit, $offset, $this->pageCount);
				
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
	function manageJobsCategory($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 					= $type;
		$page_data['id'] 					= $id;
		$page_data['manageJobsCategory'] 	= 1;
		$page_data['page_name']  			= 'jobs/manageJobsCategory';
		$page_data['page_title'] 			= 'Jobs Category';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$job_name 				= $this->input->post('job_name');
					$checkJobCategoryExist 	= $this->jobs_model->checkJobCategoryExist($job_name,$type,NULL);

					if(count($checkJobCategoryExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Job Category already exist!");
						redirect(base_url() . 'jobs/manageJobsCategory/add', 'refresh');
					}

					$postData = array(
						'job_name' 	         	=> $job_name,
						"created_by" 	 		=> $this->user_id,
						"created_date" 	  		=> $this->date_time,
						"last_updated_by" 		=> $this->user_id,
						"last_updated_date" 	=> $this->date_time
					);

					$this->db->insert('org_job_category', $postData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						if( isset($_FILES['job_category_image']['name']) && $_FILES['job_category_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['job_category_image']['tmp_name'], 'uploads/job_category_images/'.$header_id.'.png');
						}
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Job category saved successfully!");
							redirect(base_url() . 'jobs/manageJobsCategory/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Job category submitted Successfully!");
							redirect(base_url() . 'jobs/manageJobsCategory', 'refresh');
						}
						
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$page_data['editData'] = $this->jobs_model->getJobsCategoryView($id);
				if($_POST)
				{

					$job_name 				= $this->input->post('job_name');
					$checkJobCategoryExist 	= $this->jobs_model->checkJobCategoryExist($job_name,$type,$id);

					if(count($checkJobCategoryExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Job Category already exist!");
						redirect(base_url() . 'jobs/manageJobsCategory/edit/'.$id, 'refresh');
					}


					$postData = array(
						'job_name' 	         	=> $job_name,
						"last_updated_by" 		=> $this->user_id,
						"last_updated_date" 	=> $this->date_time
					);
					
					$this->db->where('job_category_id', $id);
					$result = $this->db->update('org_job_category', $postData);
					
					if($result)
					{
						if( isset($_FILES['job_category_image']['name']) && $_FILES['job_category_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['job_category_image']['tmp_name'], 'uploads/job_category_images/'.$id.'.png');
						}

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Job category saved successfully!");
							redirect(base_url() . 'jobs/manageJobsCategory/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Job category submitted Successfully!");
							redirect(base_url() . 'jobs/manageJobsCategory', 'refresh');
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
					$succ_msg = 'Job category active successfully!';
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
					$succ_msg = 'Job category inactive successfully!';
				}
				$this->db->where('job_category_id', $id);
				$this->db->update('org_job_category', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				$totalResult 			= $this->jobs_model->getJobsCategory("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{
					$limit = $_SESSION['PAGE'];
				}
				else
				{
					$limit = 10;
				}

				$job_category_id 	= isset($_GET['job_category_id']) ? $_GET['job_category_id'] :NULL;
				$active_flag 		= isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				
				$this->redirectURL = 'jobs/manageJobsCategory?job_category_id='.$job_category_id.'&active_flag='.$active_flag.'';
				
				if ($job_category_id != NULL || $active_flag) {
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
				
				$page_data['resultData'] = $result = $this->jobs_model->getJobsCategory($limit, $offset, $this->pageCount);
				
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

	
	function getItemList(){
	
		$result = $this->common_model->lov("PERIOD");;

	
		if( count($result) > 0)
		{
			echo '<option value="">- Select -</option>';
			foreach($result as $val)
			{
				echo '<option value="'.$val['list_code'].'">'.ucfirst($val['list_value']).'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
	}

	public function getLineDatas()
	{
		
		/* $itemQuery = "select
			transaction.transaction_id,
			sum(transaction.transaction_qty) as trans_qty,
			products.product_id as item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.lot_number,
			transaction.serial_number,
			products.product_code as item_name,
			products.product_name as item_description,
			category.category_name,
			sub_inventory.inventory_code,
			sub_inventory.inventory_name,
			item_locators.locator_no,
			item_locators.locator_name

			from inv_transactions as transaction
			left join products on products.product_id = transaction.item_id
			left join category on category.category_id = products.category_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
			group by 
			transaction.item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.lot_number,
			transaction.serial_number
			
			HAVING trans_qty > 0 "; */

			
		/* $itemQuery = "select 
			products.product_id as item_id,
			products.product_code as item_name,
			products.product_name as item_description

			from products where product_status=1 order by item_name asc"; */

		$itemQuery = "select 
			locator_line_tbl.header_id,
			locator_line_tbl.line_id,
			items.item_id,
			items.item_name,
			items.item_description

			from inv_assign_product_locator_line as locator_line_tbl


			left join inv_sys_items as items on 
			items.item_id = locator_line_tbl.product_id

			where 1=1
			
			and items.active_flag='Y'
			and locator_line_tbl.assign_line_status=1
			";
		
		$data['items'] = $this->db->query($itemQuery)->result_array();

		#$data['discount'] = $this->db->query("select discount_id,discount_name from discount where active_flag='Y'")->result();
		
		/* $taxQry = "select tax_id,tax_name,tax_value from gen_tax 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['tax'] = $this->db->query($taxQry)->result_array(); */
		
		/* $uomQry = "select uom_id,uom_code,uom_description from uom 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['uom'] = $this->db->query($uomQry)->result_array(); */

		/* $discountType = [];

		foreach( $this->discount_type as $key => $value )
		{
			$discountType[] = array(
				'discount_type' =>  $value,
			);
		}
		$data['discount_type'] = $discountType;

		$organizationQry = "select organization_id,organization_name from org_organizations 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['organization'] = $this->db->query($organizationQry)->result_array();
		
		$requestedByQry = "select person_id,first_name,last_name from per_people_all 
			where active_flag='Y'
			";
		$data['requestedBy'] = $this->db->query($requestedByQry)->result_array();

		$subInvQry = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['subInvQry'] = $this->db->query($subInvQry)->result_array(); */

	    echo json_encode($data);
		exit;
	}

	function ajaxItemList() {
		if(isset($_POST["query"])) {  

			
			$output = '';  
			$item_name = $_POST['query'];
			$counter = $_POST['counter'];
			
			$result = $this->stock_adjustment_model->getAjaxItemlist($item_name);
			
			$output = '<ul class="list-unstyled-item_id">';  
			
			if(count($result) > 0) {  
				foreach($result as $row) {  
					$item_name = $row["item_name"];
					$item_id = $row["item_id"];
					$item_description = $row['item_description'];
					$uom_id = $row['uom'];
					
					$output .= '<a><li onclick="return getItemList(\'' .$item_id. '\',\'' .$item_name. '\',\'' .$item_description. '\',\'' .$uom_id. '\');">'.$item_name.'</li></a>';

				}  
			} 
			else {  
				$item_name = "";
				$item_id = "";
				$item_description = '';
				$uom_id = '';
				
				$output .= '<li onclick="return getItemList(\'' .$item_id. '\',\'' .$item_name. '\',\'' .$item_description. '\', \'' .$uom_id. '\');">Sorry! Item Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}
	function ajaxUom() {
		if(isset($_POST["uom_id"])) {
			
			$uom_id = $_POST['uom_id'];
			$result = $this->stock_adjustment_model->getAjaxUom($uom_id);
			
			$output = '';
			if(count($result) > 0) {
				
				foreach($result as $row) {
					$uom_id = $row["uom_id"];
					$uom_code = $row["uom_code"];
					
					$output .= $uom_id . '@' . $uom_code;
				}
			}
			echo $output;
		} 
		else {
		
			echo "uom_id is not set";
		}
	}
	
	
	function ajaxTransQty() {
		if(isset($_POST["item_id"])) {
			$item_id = $_POST['item_id'];
			$result = $this->stock_adjustment_model->getAjaxTransQty($item_id);
			
			$output = '';
			if(count($result) > 0) {
				
				foreach($result as $row) {
					$transaction_id = $row["transaction_id"];
					$transaction_qty = $row["transaction_qty"];
					
					$output .= $transaction_id . '@' . $transaction_qty;
				}
			}
			echo $output;
		} 
		else {
		
			echo "Transcation Qty is not found";
		}
	}
	
	
	function ajaxOrganization() 
	{
		$result = $this->stock_adjustment_model->getAjaxOrganization();

		if( count($result) > 0)
		{
			echo '<option value="">- Select -</option>';
			foreach($result as $val)
			{
				echo '<option value="'.$val['organization_id'].'">'.ucfirst($val['organization_name']).'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
		
		die;
	}
	function ajaxselectSubInventory() 
	{
		if(isset($_POST["query"])) {  
			$organization_id=$_POST['query'];
			$result = $this->stock_adjustment_model->getAjaxSubInventory($organization_id);

			if( count($result) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($result as $val)
				{
					echo '<option value="'.$val['inventory_id'].'">'.$val['inventory_code'].'-'.ucfirst($val['inventory_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
			
			die;
		}
	}
	function ajaxSubInventoryLocators() 
	{
		if(isset($_POST["query"])) { 

			$inventory_id=$_POST['query'];

			$result = $this->stock_adjustment_model->getAjaxSubInventoryLocators($inventory_id);

			if( count($result) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($result as $val)
				{
					echo '<option value="'.$val['locator_id'].'">'.ucfirst($val['locator_no']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
			
			die;
		}
	}


	function ajaxAdjustmentNumberList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$adj_number = $_POST['query'];

			$result = $this->stock_adjustment_model->getAjaxAdjustNumberAll($adj_number);
			
			$output = '<ul class="list-unstyled-adj_number_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$adj_number = $row["adj_number"];
					$adj_number_id = $row["header_id"];
					$output .= '<a><li onclick="return getAdjustNumberList(\'' .$adj_number_id. '\',\'' .$adj_number. '\');">'.$adj_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$adj_number = "";
				$adj_number_id = "";
				
				$output .= '<li onclick="return getAdjustNumberList(\'' .$adj_number_id. '\',\'' .$adj_number. '\');">Sorry! Adjust Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	public function ajaxSelectLineItemDetails()
	{
		$organization_id = isset($_POST["organization_id"]) ? $_POST["organization_id"] : NULL;
		$item_id = isset($_POST["item_id"]) ? $_POST["item_id"] : NULL;

		if($item_id !=NULL)
		{
			$itemQuery = "select 
			
			products.product_id as item_id,
			products.product_code as item_name,
			products.product_name as item_description,
            products.unit as uom_code,
            
            sub_inventory.inventory_code, 
            sub_inventory.inventory_name, 
            item_locators.locator_no, 
            item_locators.locator_name,
            locator_line_tbl.inventory_id,
            locator_line_tbl.locator_id

			from inv_assign_product_locator_line as locator_line_tbl

			left join inv_assign_product_locator_header as locator_header_tbl on 
				locator_header_tbl.header_id = locator_line_tbl.header_id

			left join products on 
				products.product_id = locator_line_tbl.product_id
                
                
            left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = locator_line_tbl.inventory_id 
            left join inv_item_locators as item_locators on item_locators.locator_id = locator_line_tbl.locator_id 

			where 1=1
			and locator_header_tbl.warehouse_id='".$organization_id."'
            and locator_line_tbl.product_id='".$item_id."'
			and products.product_status=1
			and locator_line_tbl.assign_line_status=1";
			
			$data = $this->db->query($itemQuery)->result_array();

			echo json_encode($data);
		}exit;
	}

	public function ajaxSelectTransactionLot() 
	{
        $organization_id = $_POST["organization_id"];	
        $item_id = $_POST["item_id"];
        $sub_inventory_id = $_POST["sub_inventory_id"];
        $locator_id = $_POST["locator_id"];

		if($organization_id)
		{			
			$lotQry = "
			select sum(transaction.transaction_qty) as trans_qty, 
			transaction.lot_number
			from inv_transactions as transaction
			where 1=1
			and transaction.organization_id = '".$organization_id."'
			and transaction.item_id = '".$item_id."'
			and transaction.sub_inventory_id = '".$sub_inventory_id."'
			and transaction.locator_id = '".$locator_id."'
			group by 
			transaction.item_id, 
			transaction.organization_id, 
			transaction.sub_inventory_id, 
			transaction.locator_id, 
			transaction.lot_number
			";
			
			$data = $this->db->query($lotQry)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					$lot_number = $val['lot_number']."@". $val['trans_qty'];

					echo '<option value="'.$lot_number.'">'.$val['lot_number'].'</option>';
				}
			}
			else
			{
				echo '<option value="">Lot Not Exists!</option>';
			}
		}
		die;
    }
}
?>
