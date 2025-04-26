<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Offers extends CI_Controller 
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
	
	function manageOffers($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		} 
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		
		$page_data['page_name']  = 'offers/manageOffers';
		$page_data['page_title'] = 'Offers';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['branch_id'] = $this->input->post('branch_name');
					$data['offer_percentage'] = $this->input->post('offer_percentage');

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					#exist start here
					$chkExistOffer = $this->db->query("select offer_id from inv_item_offers 
						where 
							branch_id='".$data['branch_id']."'
							")->result_array();
							
					if(count($chkExistOffer) > 0)
					{
						$this->session->set_flashdata('error_message' , "Offer Percentage already exist!");
						redirect(base_url() . 'offers/ManageOffers/add', 'refresh');
					}
					# exist end here
					
					$this->db->insert('inv_item_offers', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						$this->session->set_flashdata('flash_message' , "Offer added Successfully!");
						redirect(base_url() . 'offers/ManageOffers', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('inv_item_offers', array('offer_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['branch_id'] = $this->input->post('branch_name');
					$data['offer_percentage'] = $this->input->post('offer_percentage');
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
				
					# Offer exist start here
					$chkExistOffer = $this->db->query("select offer_id from inv_item_offers 
						where 
							offer_id !='".$id."' 
							and branch_id='".$data['branch_id']."' 
							
							")->result_array();
					
					if( count($chkExistOffer) > 0 )
					{
						$this->session->set_flashdata('error_message' , "Offer percentage already exist!");
						redirect(base_url() . 'offers/ManageOffers/edit/'.$id, 'refresh');
					}
					# Offer exist end here
					
					$this->db->where('offer_id', $id);
					$result = $this->db->update('inv_item_offers', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Offer updated Successfully!");
						redirect(base_url() . 'offers/ManageOffers', 'refresh');
					}
				}
			break;

			/* case "delete": #Delete
				$this->db->where('offer_id', $id);
				$this->db->delete('inv_item_offers');
				
				$this->session->set_flashdata('flash_message' , "Product deleted successfully!");
				redirect(base_url() . 'offers/ManageOffers', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Offers active successfully!';
					
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Offers inactive successfully!';
				}

				$this->db->where('offer_id', $id);
				$this->db->update('inv_item_offers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;
			

			default : #Manage
				$totalResult = $this->offers_model->getManageOffers("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'offers/ManageOffers?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['keywords']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('offers/ManageOffers?keywords='.$_GET['keywords'].$_GET['active_flag']);
				} else {
					$base_url = base_url('offers/ManageOffers?keywords=&active_flag=');
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
				
				$page_data['resultData']  = $result= $data =$this->offers_model->getManageOffers($limit, $offset,$this->pageCount);
				#show start and ending Count
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
