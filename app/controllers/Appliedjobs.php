<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Appliedjobs extends CI_Controller 
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

	function manageAppliedJobs($type = '', $id = '', $status = '')
    {
		
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 					= $type;
		$page_data['id'] 					= $id;
		$page_data['manageAppliedJobs']  	= 1;
		$page_data['page_name']  			= 'appliedjobs/manageAppliedJobs';
		$page_data['page_title'] 			= 'Applied Jobs';
		
		if($_GET)
		{
			$totalResult = $this->applied_jobs_model->getAppliedJobs("","",$this->totalCount);
			$page_data["totalRows"] = $totalRows = count($totalResult);

			if(!empty($_SESSION['PAGE']))
			{$limit = $_SESSION['PAGE'];
			}else{$limit = 10;}

			$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] :NULL;
		
			$this->redirectURL = 'appliedjobs/manageAppliedJobs?customer_name='.$customer_name.'';
			
			if ($customer_name != NULL) {
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
			
			$page_data['resultData'] = $result = $this->applied_jobs_model->getAppliedJobs($limit, $offset, $this->pageCount);
			

			if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
			{
				redirect(base_url().$this->redirectURL, 'refresh');
			}


			#Download Excel start
			$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
			if($download_excel != NULL) 
			{
						
				$date = date('d_M_Y');
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Job_applied_".$date.".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				$handle1 = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Client Name","Email","Mobile Number","Job Category","Experience ","Location","Current Company","Expected Salary","Notice Period","Message","Created Date")); //, "Price" , "Total"
				$cnt=1;
				foreach ($totalResult as $row) 
				{
					$narray=array(
						$cnt,
						$row['full_name'],
						$row['email'],
						$row['mobile_number'],
						$row['job_name'],
						$row['experience'],
						$row['location'],
						$row['current_company'],
						$row['expected_salary'],
						$row['list_value'],
						$row['message'],
						date('d-M-Y',strtotime($row['created_date'])),
					);

					fputcsv($handle, $narray);
					$cnt++;
				}
				
				fclose($handle);
				
				exit;
			}
			#Download Excel end 

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
		}

		$this->load->view($this->adminTemplate, $page_data);
	}

	
}
?>
