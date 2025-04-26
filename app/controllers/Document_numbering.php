<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Document_numbering extends CI_Controller 
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
	
	function manageDocumentNumbering($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'document_numbering/manageDocumentNumbering';
		$page_data['page_title'] = 'Document Numbering';
		
		switch($type)
		{
			case "add": #Add
				if($_POST)
				{
					$data['doc_type'] 	       = $this->input->post('doc_type');
					$data['doc_document_type'] = url(strtolower($_POST['doc_document_type']));
					$data['org_name']          = $_POST['org_name'];
					$data['branch_id']         = $_POST['branch_id'];

					$data['from_date']         = !empty($_POST['from_date']) ? date('Y-m-d',strtotime($_POST['from_date'])) :NULL;
					$data['to_date']           = !empty($_POST['to_date']) ? date('Y-m-d',strtotime($_POST['to_date'])) :NULL;
					$data['next_number']  = $this->input->post('next_number');
					$data['prefix_name'] 	= strtoupper($this->input->post('prefix_name'));
					$data['suffix_name'] 	= strtoupper($this->input->post('suffix_name'));

					$data['active_flag'] = $this->active_flag;
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					if( empty($data['branch_id']) )
					{
						$ChkPrefixnumber = $this->db->query("select doc_type from doc_document_numbering where 
							doc_type='".$data['doc_type']."' 
							and from_date='".$data['from_date']."' 
							and to_date='".$data['to_date']."'
							")->result_array();
					}
					else
					{
						$ChkPrefixnumber = $this->db->query("select doc_type from doc_document_numbering where 
							doc_type='".$data['doc_type']."' 
							and from_date='".$data['from_date']."' 
							and to_date='".$data['to_date']."'  
							and branch_id='".$data['branch_id']."' 
							")->result_array();
					}

					if( count($ChkPrefixnumber) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Prefix type is Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
				
					$this->db->insert('doc_document_numbering', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						$this->session->set_flashdata('flash_message' , "Document numbering created successfully!");
						redirect(base_url() . 'document_numbering/manageDocumentNumbering', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->query("select * from doc_document_numbering where doc_num_id='".$id."' ")
								->result_array();
				if($_POST)
				{
					$data['doc_type'] 	= $this->input->post('doc_type');
					$data['doc_document_type'] = url(strtolower($_POST['doc_document_type']));
					$data['org_name']          = $_POST['org_name'];
					$data['from_date']         = !empty($_POST['from_date']) ? date('Y-m-d',strtotime($_POST['from_date'])) :NULL;
					$data['to_date']           = !empty($_POST['to_date']) ? date('Y-m-d',strtotime($_POST['to_date'])) :NULL;
					$data['next_number']       = $this->input->post('next_number');
					$data['prefix_name'] 	   = strtoupper($this->input->post('prefix_name'));
					$data['suffix_name'] 	   = $this->input->post('suffix_name');
					$data['branch_id']          = $_POST['branch_id'];
					
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					if( empty($data['branch_id']) )
					{
						$ChkPrefixnumber = $this->db->query("select doc_type from doc_document_numbering where 
							doc_type='".$data['doc_type']."' 
							and from_date='".$data['from_date']."' 
							and to_date='".$data['to_date']."' 
							and doc_num_id !='".$id."'
								")->result_array();
					}
					else
					{
						$ChkPrefixnumber = $this->db->query("select doc_type from doc_document_numbering where 
							doc_type='".$data['doc_type']."' 
							and	from_date='".$data['from_date']."'
							and	to_date='".$data['to_date']."' 
							and	branch_id='".$data['branch_id']."' 
							and	doc_num_id !='".$id."'
							")->result_array();
					}

					if( count($ChkPrefixnumber) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Document numbering is Already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					$this->db->where('doc_num_id', $id);
					$result = $this->db->update('doc_document_numbering', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Document numbering updated successfully!");
						redirect(base_url() . 'document_numbering/manageDocumentNumbering', 'refresh');
					}
				}
			break;
			
			/* case "delete": #Delete
				$this->db->where('doc_num_id', $id);
				$this->db->delete('doc_document_numbering');
				
				$this->session->set_flashdata('flash_message' , "Prefix deleted successfully!");
				redirect(base_url() . 'prefix/ManagePrefix', 'refresh');
			break; */
			
			case "status": #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					$data['inactive_date'] = NULL;
					$succ_msg = 'Document numbering active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
                    $data['inactive_date'] = $this->date_time;
                    $succ_msg = 'Document numbering inactive successfully!';
				}

				$this->db->where('doc_num_id', $id);
				$this->db->update('doc_document_numbering', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'document_numbering/manageDocumentNumbering', 'refresh');
			break;
			
			default : #Manage
				$totalResult = $this->document_numbering_model->getManageDocumentNumbering("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				$doc_type = isset($_GET['doc_type']) ? $_GET['doc_type'] :NULL;
				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$redirectURL = 'document_numbering/manageDocumentNumbering?keywords=&active_flag='.$active_flag;

				if (!empty($_GET['doc_type']) || !empty($_GET['active_flag']) ) 
				{
					$base_url = base_url('document_numbering/manageDocumentNumbering?doc_type='.$_GET['doc_type'].'&active_flag='.$_GET['active_flag']);
				} else {
					$base_url = base_url('document_numbering/manageDocumentNumbering?doc_type=&active_flag=');
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
				
				$page_data['resultData']  = $result = $this->document_numbering_model->getManageDocumentNumbering($limit, $offset,$this->pageCount);
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
