<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Services extends CI_Controller 
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

	function manageServices($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 				= $type;
		$page_data['id'] 				= $id;
		$page_data['manageServices']  	= 1;
		$page_data['page_name']  		= 'services/manageServices';
		$page_data['page_title'] 		= 'Services Details';
		
		if($_GET)
		{
			$totalResult = $this->services_model->getServices("","",$this->totalCount);
			$page_data["totalRows"] = $totalRows = count($totalResult);

			if(!empty($_SESSION['PAGE']))
			{$limit = $_SESSION['PAGE'];
			}else{$limit = 10;}

			$service_contact_type = isset($_GET['service_contact_type']) ? $_GET['service_contact_type'] :NULL;
		
			$this->redirectURL = 'services/manageServices?service_contact_type='.$service_contact_type.'';
			
			if ($service_contact_type != NULL) {
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
			
			$page_data['resultData'] = $result = $this->services_model->getServices($limit, $offset, $this->pageCount);
			

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
				header("Content-Disposition: attachment; filename=\"Services_details_".$date.".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				$handle1 = fopen('php://output', 'w');
				if($totalResult[0]['service_contact_type']=='DIGITAL-MARKETING')
				{
					fputcsv($handle, array("S.No","Service Contact Type","Full Name","Email","Mobile Number","Company Name","Marketing Goals","Current Chanllenges","Created Date"));
					$cnt=1;
					foreach ($totalResult as $row) 
					{
						$narray=array(
							$cnt,
							$row['service_contact_type'],
							$row['full_name'],
							$row['email'],
							$row['mobile_number'],
							$row['company_name'],
							$row['marketing_goals'],
							$row['current_challenges'],
							date('d-M-Y',strtotime($row['created_date']))
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
				}
				else if($totalResult[0]['service_contact_type']=='MOBILE-APP-DEVELOPMENT')
				{
					fputcsv($handle, array("S.No","Service Contact Type","Full Name","Email","Mobile Number","Company Name","Platform Type","Existing App","Project Detail","Created Date"));
					$cnt=1;
					foreach ($totalResult as $row) 
					{
						$narray=array(
							$cnt,
							$row['service_contact_type'],
							$row['full_name'],
							$row['email'],
							$row['mobile_number'],
							$row['company_name'],
							$row['platform_type'],
							$row['existing_app'],
							$row['project_detail'],
							date('d-M-Y',strtotime($row['created_date']))
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
				}

				else if($totalResult[0]['service_contact_type']=='WEBSITE-DEVELOPMENT')
				{
					fputcsv($handle, array("S.No","Service Contact Type","Full Name","Email","Mobile Number","Company Name","Website Type","Project Description","Created Date"));
					$cnt=1;
					foreach ($totalResult as $row) 
					{
						$narray=array(
							$cnt,
							$row['service_contact_type'],
							$row['full_name'],
							$row['email'],
							$row['mobile_number'],
							$row['company_name'],
							$row['website_type'],
							$row['project_description'],
							date('d-M-Y',strtotime($row['created_date']))
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
				}

				else if($totalResult[0]['service_contact_type']=='WEB-APP-DEVELOPMENT')
				{
					fputcsv($handle, array("S.No","Service Contact Type","Full Name","Email","Mobile Number","Company Name","Industry Type","Created Date"));
					$cnt=1;
					foreach ($totalResult as $row) 
					{
						$narray=array(
							$cnt,
							$row['service_contact_type'],
							$row['full_name'],
							$row['email'],
							$row['mobile_number'],
							$row['company_name'],
							$row['industry_type'],
							date('d-M-Y',strtotime($row['created_date']))
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
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
