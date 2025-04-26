<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Paymenttype extends CI_Controller 
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
    function managePaymenttype($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['managePaymenttype'] = 1;
		
		$page_data['page_name']  = 'payment_type/ManagePaymentType';
		$page_data['page_title'] = 'Payment Type';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$usages = isset($_POST["usages"]) ? $_POST["usages"] :  NULL;

					if ($usages !=NULL && in_array("online", $usages)){
						$usage_online = 'Y';
					}else{
						$usage_online = 'N';
					}

					if ($usages !=NULL && in_array("pos", $usages)){
						$usage_pos = 'Y';
					}else{
						$usage_pos = 'N';
					}

					$data['payment_type'] = $this->input->post('payment_type');
					$data['sequence_number'] = $this->input->post('sequence_number');
					$data['usage_online'] = $usage_online;
					$data['usage_pos'] = $usage_pos;
					
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
											
					$this->db->insert('pay_payment_types', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if( !empty($_FILES['payment_icon']['name']) )
						{  
							move_uploaded_file($_FILES['payment_icon']['tmp_name'], 'uploads/payments/'.$id.'.png');
						}
						$this->session->set_flashdata('flash_message' , "Payment type saved Successfully!");
						redirect(base_url() . 'paymenttype/ManagePaymenttype/edit/'.$id, 'refresh');
					}
				}
			break;
			
			case "edit": #Edit
				$page_data['edit_data'] = $this->db->get_where('pay_payment_types', array('payment_type_id' => $id))->result_array();
				if($_POST)
				{
					$usages = isset($_POST["usages"]) ? $_POST["usages"] :  NULL;

					if ($usages !=NULL && in_array("online", $usages)){
						$usage_online = 'Y';
					}else{
						$usage_online = 'N';
					}

					if ($usages !=NULL && in_array("pos", $usages)){
						$usage_pos = 'Y';
					}else{
						$usage_pos = 'N';
					}

					$data['payment_type'] = $this->input->post('payment_type');
					$data['sequence_number'] = $this->input->post('sequence_number');

					$data['usage_online'] = $usage_online;
					$data['usage_pos'] = $usage_pos;

					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;

					$this->db->where('payment_type_id', $id);
					$result = $this->db->update('pay_payment_types', $data);
					
					if($result > 0)
					{
						if( !empty($_FILES['payment_icon']['name']) )
						{  
							move_uploaded_file($_FILES['payment_icon']['tmp_name'], 'uploads/payments/'.$id.'.png');
						}
						$this->session->set_flashdata('flash_message' , "Payment type saved Successfully!");
						redirect(base_url() . 'paymenttype/ManagePaymenttype/edit/'.$id, 'refresh');
					}	
				}
			break;
			
			case "delete": #Delete
				$this->db->where('payment_type_id', $id);
				$this->db->delete('pay_payment_types');
				$this->session->set_flashdata('flash_message' ,'Payment type Deleted successfully!');
				redirect(base_url() . 'paymenttype/ManagePaymenttype/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 'Y'){
					$data['active_flag'] = 'Y';
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Payment type Active successfully!';
				}else{
					$data['active_flag'] = 'N';
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Payment type Inactive successfully!';
				}
				$this->db->where('payment_type_id', $id);
				$this->db->update('pay_payment_types', $data);
				$this->session->set_flashdata('flash_message' ,$succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage
				if(isset($_POST['default_submit']))
				{
					$data['default_payment'] = 'N';
					$result = $this->db->update('pay_payment_types', $data);
					
					if($result)
					{
						$payment_type_id = $_POST['default_payment'];
						$data_1['default_payment'] = 'Y';
						$this->db->where('payment_type_id', $payment_type_id);
						$result1 = $this->db->update('pay_payment_types', $data_1);
					}
					$this->session->set_flashdata('flash_message' , 'Default payment type updated successfully!');
					redirect($_SERVER['HTTP_REFERER'], 'refresh');
				}
				
				$totalResult = $this->paymenttype_model->getPaymenttype("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
				
				if(!empty($_SESSION['PAGE'])){
					$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'paymenttype/ManagePaymenttype/'.$type;
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('paymenttype/ManagePaymenttype?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('paymenttype/ManagePaymenttype?keywords=');
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
				
				$page_data['resultData']  = $result= $data =$this->paymenttype_model->getPaymenttype($limit, $offset,$this->pageCount);
				
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
