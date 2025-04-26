<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Audit_trails extends CI_Controller 
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

	function auditTrails()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'audit_trails/auditTrails';
		$page_data['page_title'] = 'Audit Trails';
		
		if($_GET)
		{
			$totalResult = $this->audit_trails_model->auditTrails("","",$this->totalCount);
			$page_data["totalRows"] = $totalRows = count($totalResult);

			if(!empty($_SESSION['PAGE']))
			{$limit = $_SESSION['PAGE'];
			}else{$limit = 10;}

			$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
			$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

			$this->redirectURL = 'audit_trails/auditTrails?from_date='.$from_date.'&to_date='.$to_date.' ';
			
			if ($from_date != NULL || $to_date != NULL) {
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
			
			$page_data['resultData'] = $result = $this->audit_trails_model->auditTrails($limit, $offset, $this->pageCount);
			
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
				header("Content-Disposition: attachment; filename=\"audit_trails".$date.".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				
				fputcsv($handle, array("S.No","Branch Name","User Name","Date","Source IP","Table Name","Menu Name","Field Name","Old Value","New Value","Description","Action Type"));
				$cnt=1;

				foreach ($result as $row) 
				{
					$narray=array(
						$cnt,
						
						$row['branch_name'],
						$row['employee_name'],
						date("d-M-Y h:m:i A",strtotime($row['created_date'])),
						$row['source_ip'],
						$row['table_name'],
						$row['menu_name'],
						$row['field_name'],
						$row['old_value'],
						$row['new_value'],
						$row['description'],
						ucfirst($row['action_type'])
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
