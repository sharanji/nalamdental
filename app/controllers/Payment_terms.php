<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class payment_terms extends CI_Controller 
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
	
	function managePayment_terms($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'payment_terms/managePayment_terms';
		$page_data['page_title'] = 'Payment Terms';
		
		switch($type)
		{
			// case "add": #View
			case ($type == "add"):
				if($_POST)
				{
					$data['payment_term'] = $this->input->post('payment_term');
					$data['payment_description'] = $this->input->post('payment_description');
					$data['payment_days'] = $this->input->post('payment_days');
					
					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Tax start here
					$chkExist = $this->db->query("select payment_term from payment_terms
						where  
							payment_term like '".serchFilter($data['payment_term'])."'
							")->result_array();
					
					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$payment_term = $existValue["payment_term"];

							if($payment_term == $data['payment_term'])
							{
								$this->session->set_flashdata('error_message' , " Payment term already exist!");
								redirect(base_url() . 'payment_terms/managePayment_terms/add', 'refresh');
							}
						}
					}		
					
					# Tax end here
					
					$this->db->insert('payment_terms', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Tax added Successfully!");
						redirect(base_url() . 'payment_terms/managePayment_terms', 'refresh');
					}
				}
			break;
			
			// case "edit": #edit
			case ($type == "edit"):
				$page_data['edit_data'] = $this->db->get_where('payment_terms', array('payment_term_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['payment_term'] = $this->input->post('payment_term');
					$data['payment_description'] = $this->input->post('payment_description');
					$data['payment_days'] = $this->input->post('payment_days');

                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Tax start here
					$chkExist = $this->db->query("select payment_term from payment_terms
						where 
						 payment_term like '".serchFilter($data['payment_term'])."' 
						 and payment_term_id !='".$id."'
						")->result_array();
							

					if(count($chkExist) > 0)
					{
						foreach($chkExist as $existValue)
						{
							$payment_term = $existValue["payment_term"];

							if($payment_term == $data['payment_term'])
							{
								$this->session->set_flashdata('error_message' , "Payment term already exist!");
								redirect(base_url() . 'payment_terms/managePayment_terms/edit'.$id, 'refresh');
							}
						}
					}		
				
					
					$this->db->where('payment_term_id', $id);
					$result = $this->db->update('payment_terms', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "payment Term updated Successfully!");
						redirect(base_url() . 'payment_terms/managePayment_terms', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('uom_id', $id);
				$this->db->delete('uom');
				
				$this->session->set_flashdata('flash_message' , "Uom deleted successfully!");
				redirect(base_url() . 'uom/ManageUom', 'refresh');
			break; */
			
			// case "status":
				case ($type == "status"): #Block & Unblock
				// if($status == "Y")
				// {
				// 	$data['active_flag'] = "Y";
				// 	$data['last_updated_by'] = $this->user_id;
                //     $data['last_updated_date'] = $this->date_time;
				// 	$data['inactive_date'] = NULL;
				// 	$succ_msg = 'Payment Term active successfully!';
				// }
				// else
				// {
				// 	$data['active_flag'] = "N";
				// 	$data['last_updated_by'] = $this->user_id;
                //     $data['last_updated_date'] = $this->date_time;
				// 	$data['inactive_date'] = $this->date;
                //   	$succ_msg = 'Payment Term  inactive successfully!';
				// }
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$data['end_date'] = NULL;
					$succ_msg = 'Payment Term   Active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = $this->date_time;
                    $data['end_date'] = $this->date;
					$succ_msg = 'Payment Term  InActive successfully!';
				}

				$this->db->where('payment_term_id', $id);
				$this->db->update('payment_terms', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			
			default : #Manage

				$totalResult = $this->payment_terms_model->getManagePayment_terms("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'payment_terms/managePayment_terms?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('payment_terms/managePayment_terms?keywords='.$_GET['keywords'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('payment_terms/managePayment_terms?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $data =$this->payment_terms_model->getManagePayment_terms($limit, $offset,$this->pageCount);
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
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
