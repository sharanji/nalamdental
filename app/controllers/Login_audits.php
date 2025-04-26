<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Login_audits extends CI_Controller 
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

	function loginAudits()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'login_audits/loginAudits';
		$page_data['page_title'] = 'Login Audits';
		
		if($_GET)
		{
			$totalResult = $this->login_audits_model->loginAudits("","",$this->totalCount);
			$page_data["totalRows"] = $totalRows = count($totalResult);

			if(!empty($_SESSION['PAGE']))
			{$limit = $_SESSION['PAGE'];
			}else{$limit = 10;}

			$login_type = isset($_GET['login_type']) ? $_GET['login_type'] :NULL;
			$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
			$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

			$this->redirectURL = 'login_audits/loginAudits?login_type='.$login_type.'&from_date='.$from_date.'&to_date='.$to_date.' ';
			
			if ($login_type != NULL || $from_date != NULL || $to_date != NULL) {
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
			
			$page_data['resultData'] = $result = $this->login_audits_model->loginAudits($limit, $offset, $this->pageCount);
			
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
				header("Content-Disposition: attachment; filename=\"login_audits_".$date.".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				
				fputcsv($handle, array("S.No","Login Type","Branch Name","User Name","Mobile Number","IP Address","Login Date","Last Login Status","Logout Date","Last Login Date"));
				$cnt=1;

				foreach ($result as $row) 
				{
					if($row['last_login_status'] == 'Y')
					{
						$last_login_status= 'Yes';
					}
					else if($row['last_login_status'] == 'N')
					{
						$last_login_status= 'No';
					}

					$narray=array(
						$cnt,
						$row['login_type_name'],
						$row['branch_name'],
						$row['user_name'],
						$row['mobile_number'],
						$row['ip_address'],
						date("d-M-Y h:m:i A",strtotime($row['created_date'])),
						$last_login_status,
						date("d-M-Y h:m:i A",strtotime($row['logout_date'])),
						date("d-M-Y h:m:i A",strtotime($row['last_login_date']))
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
