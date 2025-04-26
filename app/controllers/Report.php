<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Report extends CI_Controller 
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

	function zReport()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['report'] = $page_data['zReport'] = 1;
		$page_data['page_name']  = 'report/zReport';
		$page_data['page_title'] = 'Z - Report';
		$page_data["resultData"] = $resultData = $this->report_model->zReport();


		$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : NULL;
		$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

		$this->redirectURL = 'report/zReport?branch_id='.$branch_id.'&from_date='.$from_date.'&to_date='.$to_date.'';
		
		$download = isset($_GET['download']) ? $_GET['download'] : NULL;

		if( ($branch_id != NULL || $from_date != NULL || $to_date != NULL) && $download != NULL) 
		{
			$date = date('d-M-Y');

			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);

			$html = $this->load->view('backend/report/zReportPdf',$page_data,true);

			$pdf_name = "zReport_".$date;
			
			$mpdf = new \Mpdf\Mpdf([		
				#'setAutoTopMargin' => 'stretch',
				#'setAutoBottomMargin' => 'stretch',
				'curlAllowUnsafeSslRequests' => true,
			]);
	
			$mpdf->AddPage('P','','','','',7,7,7,7);
			$mpdf->WriteHTML($html);
			$mpdf->Output($pdf_name.'.pdf','I');
		} 
		
		$this->load->view($this->adminTemplate, $page_data);
	}

	function salesSummary()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['report'] = $page_data['SalesReport'] = 1;
		$page_data['page_name']  = 'report/salesSummary';
		$page_data['page_title'] = 'Sales Report';

		$totalResult = $this->summary_model->getSalesSummary("","",$this->totalCount);
		$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}

		$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

		$this->redirectURL = 'report/salesSummary?from_date='.$from_date.'&to_date='.$to_date.'';
		
		if ($from_date || $to_date ) {
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
		
		$result = $this->summary_model->getSalesSummary($limit,$offset,$this->pageCount);
		$page_data['cardResult'] = $cardResult = isset($result["cardResult"]) ? $result["cardResult"] : array();
		$page_data['resultData'] = isset($result["listing"]) ? $result["listing"] : array();

		#show start and ending Count
		if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
		{
			redirect(base_url().$this->redirectURL, 'refresh');
		}

		#Download Excel start
		$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
		if($download_excel != NULL) 
		{
			$Total_Order_Amount = isset($cardResult[0]["Total_Order_Amount"]) ? $cardResult[0]["Total_Order_Amount"] : 0;
			$Total_Cancelled_Amount = isset($cardResult[0]["Total_Cancelled_Amount"]) ? $cardResult[0]["Total_Cancelled_Amount"] : 0;
					
					
			$date = date('d_M_Y');
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"sales_report_".$date.".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen('php://output', 'w');
			$handle1 = fopen('php://output', 'w');
			fputcsv($handle, array("S.No","Branch Name","Ordered Date","Total Order Amount","Total Cancelled Amount"));
			$cnt=1;
			foreach ($page_data['resultData'] as $row) 
			{
				$ordered_date = date("d-M-Y",strtotime($row['ordered_date']));
				
				$narray=array(
					$cnt,
					$row['branch_name'],
					$ordered_date,
					number_format($row['Total_Order_Amount'],DECIMAL_VALUE,'.',''),
					number_format($row['Total_Cancelled_Amount'],DECIMAL_VALUE,'.',''),
				);

				fputcsv($handle, $narray);
				$cnt++;
			}

			$narray1=array("","","Total :",$Total_Order_Amount,$Total_Cancelled_Amount,);
			fputcsv($handle1, $narray1);
			fclose($handle);
			exit;
		}
		#Download Excel end 

		#Download PDF start
		$download_pdf = isset($_GET['download_pdf']) ? $_GET['download_pdf'] : NULL;
		if($download_pdf != NULL) 
		{
			$date = date('d-M-Y');
			ob_start();
			$html = ob_get_clean();
			$html = utf8_encode($html);
			$html = $this->load->view('backend/summary/salesSummaryPDF',$page_data,true);
			$pdf_name = "sales_summary_".$date;
			$mpdf = new \Mpdf\Mpdf();
			$mpdf->AddPage('P','','','','',7,7,7,7);
			$mpdf->WriteHTML($html);
			$mpdf->Output($pdf_name.'.pdf','I');
		}
		#Download PDF end

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
		
		$total_counts = $total_count + count($result["listing"]);
		$page_data["ending"]  = $total_counts;
		#show start and ending Count end
			
		$this->load->view($this->adminTemplate, $page_data);
	}

	function itemWiseSalesSummary()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['report'] = $page_data['itemWiseSalesReport'] = 1;
		$page_data['page_name']  = 'report/itemWiseSalesReport';
		$page_data['page_title'] = 'Items Wise Sales Report';

		$totalResult = $this->summary_model->getItemWiseSalesSummary("","",$this->totalCount);
		$page_data["totalRows"] = $totalRows = count($totalResult['listing']);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}

		$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

		$this->redirectURL = 'report/itemWiseSalesSummary?from_date='.$from_date.'&to_date='.$to_date.'';
		
		if ($from_date || $to_date ) {
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
		
		$result = $this->summary_model->getItemWiseSalesSummary($limit,$offset,$this->pageCount);
		
		$page_data['resultData'] = isset($result["listing"]) ? $result["listing"] : array();


		
		#show start and ending Count
		if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
		{
			redirect(base_url().$this->redirectURL, 'refresh');
		}

		#Download Excel start
		$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
		if($download_excel != NULL) 
		{
			$date = date('d_M_Y');
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"item_wise_sales_report_".$date.".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen('php://output', 'w');
			
			fputcsv($handle, array("S.No","Branch Name","Item Name","Category Name","Total Order Amount","Offer Amount","Tax Amount","Payment Amount","Sales Count"));
			$cnt=1;

			foreach ($result["listing"] as $row) 
			{
				$narray=array(
					$cnt,
					$row['branch_name'],
					$row['item_name'],
					$row['category_name'],
					number_format($row['total_order_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['offer_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['tax_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['payment_amount'],DECIMAL_VALUE,'.',''),
					$row['sales_count'],
				);

				fputcsv($handle, $narray);
				$cnt++;
			}
			fclose($handle);
			exit;
		}
		#Download Excel end 

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
		
		$total_counts = $total_count + count($result["listing"]);
		$page_data["ending"]  = $total_counts;
		#show start and ending Count end
			
		$this->load->view($this->adminTemplate, $page_data);
	}


	function rmSalesSummary()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['rmSalesReport'] = 1;
		$page_data['page_name']  = 'report/rmSalesReport';
		$page_data['page_title'] = 'RM Sales Report';

		$totalResult = $this->summary_model->getRMSalesSummary("","",$this->totalCount);
		$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}

		$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;
		$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : NULL;
		$line_status = isset($_GET['line_status']) ? $_GET['line_status'] : NULL;

		$this->redirectURL = 'report/rmSalesSummary?from_date='.$from_date.'&to_date='.$to_date.'&order_number='.$order_number.'&line_status='.$line_status.'';
		
		if ($from_date || $to_date ) {
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
		
		$result = $this->summary_model->getRMSalesSummary($limit,$offset,$this->pageCount);
		$page_data['resultData'] = $cardResult = isset($result["data"]) ? $result["data"] : array();
		$page_data['listingData'] = isset($result["data"]) ? $result["data"] : array();

		#show start and ending Count
		if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
		{
			redirect(base_url().$this->redirectURL, 'refresh');
		}

		#Download Excel start
		$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
		if($download_excel != NULL) 
		{
			$Total_Order_Amount = isset($cardResult[0]["Total_Order_Amount"]) ? $cardResult[0]["Total_Order_Amount"] : 0;
			$Total_Cancelled_Amount = isset($cardResult[0]["Total_Cancelled_Amount"]) ? $cardResult[0]["Total_Cancelled_Amount"] : 0;
					
					
			$date = date('d_M_Y');
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"rm_so_report_".$date.".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen('php://output', 'w');
			$handle1 = fopen('php://output', 'w');
			fputcsv($handle, array(
				"S.No",
				"Order Number",
				"Organization Name",
				"Branch Name",
				"Item Name",
				"Item Desc",
				"Item Category",
				"Payment Term",
				"Status",
				"Sales Order Date",
				"UOM",
				"Qty"
			));
			$cnt=1;
			$totalAmount = 0;
			foreach ($page_data['resultData'] as $row) 
			{
				$created_date = date("d-M-Y",strtotime($row['created_date']));
				
				/* if($row['discount_type'] == 'Percentage')
				{
					$basetotal = number_format($row['basetotal'],DECIMAL_VALUE,'.','');
				}
				else
				{
					$eachDisc = $row['base_price'] - $row['price'];
					$basetotal = number_format($eachDisc,DECIMAL_VALUE,'.','');
				} */

				$narray=array(
					$cnt,
					$row['order_number'],
					$row['organization_name'],
					$row['branch_name'],
					$row['item_name'],
					$row['item_description'],
					$row['category_name'],
					$row['payment_term'],
					$row['line_status'],
					$created_date,
					$row['uom_code'],
					$row['quantity']
				);

				fputcsv($handle, $narray);
				//$totalAmount += $row['total'];
				$cnt++;
				
			}

			$narray1=array("","","","","","","","","","","","Total :","",);
			fputcsv($handle1, $narray1);
			fclose($handle);
			exit;
		}
		#Download Excel end 

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
		
		$total_counts = $total_count + count($result["data"]);
		$page_data["ending"]  = $total_counts;
		#show start and ending Count end
			
		$this->load->view($this->adminTemplate, $page_data);
	}

	function captainWiseSalesSummary()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['report'] = $page_data['captainWiseSalesReport'] = 1;
		$page_data['page_name']  = 'report/captainWiseSalesReport';
		$page_data['page_title'] = 'Captain Wise Sales Report';

		$totalResult = $this->summary_model->getCaptainWiseSalesSummary("","",$this->totalCount);
		$page_data["totalRows"] = $totalRows = count($totalResult['listing']);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}

		$user_id 	= isset($_GET['user_id']) ? $_GET['user_id'] : NULL;
		$from_date 	= isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date 	= isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

		$this->redirectURL = 'report/captainWiseSalesReport?user_id='.$user_id.'&from_date='.$from_date.'&to_date='.$to_date.'';
		
		if ($user_id != NULL || $from_date != NULL || $to_date != NULL) {
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
		
		$result = $this->summary_model->getCaptainWiseSalesSummary($limit,$offset,$this->pageCount);
		
		$page_data['resultData'] = isset($result["listing"]) ? $result["listing"] : array();

		#show start and ending Count
		if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
		{
			redirect(base_url().$this->redirectURL, 'refresh');
		}

		#Download Excel start
		$download_excel = isset($_GET['download_excel']) ? $_GET['download_excel']: NULL;
		if($download_excel != NULL) 
		{
			$date = date('d_M_Y');
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"item_wise_sales_report_".$date.".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen('php://output', 'w');
			
			fputcsv($handle, array("S.No","Branch Name","Order Number","Captain Name","Customer Name","Mobile Number","Order Status","Total Order Amount","Offer Amount","Tax Amount","Payment Amount"));
			$cnt=1;

			foreach ($result["listing"] as $row) 
			{
				$narray=array(
					$cnt,
					$row['branch_name'],
					$row['order_number'],
					$row['captain_name'],
					$row['customer_name'],
					$row['mobile_number'],
					$row['order_status'],
					number_format($row['total_order_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['offer_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['tax_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['payment_amount'],DECIMAL_VALUE,'.','')
					
				);

				fputcsv($handle, $narray);
				$cnt++;
			}
			fclose($handle);
			exit;
		}
		#Download Excel end 

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
		
		$total_counts = $total_count + count($result["listing"]);
		$page_data["ending"]  = $total_counts;
		#show start and ending Count end
			
		$this->load->view($this->adminTemplate, $page_data);
	}

	function onhandAvailability($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		 
		$page_data['report'] = $page_data['OnHandAvailabilityReport'] = 1;

		$page_data['page_name']  = 'report/onhandAvailabilityReports';
		$page_data['page_title'] = 'On hand availability reports';
		
		if($_GET)
		{
			$totalResult = $this->summary_model->onhandAvailability("","",$this->totalCount);
			$page_data["totalRows"] = $totalRows = count($totalResult);

			if(!empty($_SESSION['PAGE']))
			{$limit = $_SESSION['PAGE'];
			}else{$limit = 10;}

			$organization_id = isset($_GET['organization_id']) ? $_GET['organization_id'] :NULL;
			$item_id = isset($_GET['item_id']) ? $_GET['item_id'] :NULL;
			
			$this->redirectURL = 'report/onhandAvailability?organization_id='.$organization_id.'&item_id='.$item_id.'';
			
			if ($organization_id != NULL || $item_id != NULL) {
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
			
			$page_data['resultData'] = $result = $this->summary_model->onhandAvailability($limit, $offset, $this->pageCount);
			

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
				header("Content-Disposition: attachment; filename=\"on_hand_availability_report_".$date.".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				$handle1 = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Organization Name","Item Name","Sub Inventory","Locator Number" , "LOT Number" , "Serial Number" , "Quantity"));
				$cnt=1;
				foreach ($page_data['resultData'] as $row) 
				{
					
					$narray=array(
						$cnt,
						$row['organization_name'],
						$row['item_name'],
						$row['inventory_code'],
						$row['locator_no'],
						$row['lot_number'],
						$row['serial_number'],
						$row['trans_qty']
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

	function minimumStock($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] 			= $type;
		$page_data['id'] 			= $id;
		$page_data['report'] = $page_data['MinimumStockReport'] = 1;
		$page_data['page_name']  	= 'report/minimumStock';
		$page_data['page_title'] 	= 'Minimum Stock Report';
		
		if($_GET)
		{
			$totalResult = $this->summary_model->minimumStock("","",$this->totalCount);
			$page_data["totalRows"] = $totalRows = count($totalResult);

			if(!empty($_SESSION['PAGE']))
			{$limit = $_SESSION['PAGE'];
			}else{$limit = 10;}

			$organization_id = isset($_GET['organization_id']) ? $_GET['organization_id'] :NULL;
			$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : NULL;
			
			$this->redirectURL = 'report/minimumStock?organization_id='.$organization_id.'&item_id='.$item_id.'';
			
			if ($organization_id != NULL || $item_id != NULL) {
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
			
			$page_data['resultData'] = $result = $this->summary_model->minimumStock($limit, $offset, $this->pageCount);
			
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
				header("Content-Disposition: attachment; filename=\"minimum_stock_report".$date.".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				
				fputcsv($handle, array("S.No","Organization Name","Item Name","Sub Inventory","Locator No.","Lot No.","Serial No.","Min.Qty","Available.Qty"));
				$cnt=1;

				foreach ($totalResult as $row) 
				{
					$minimum_qty = $row['minimum_qty'];
					$trans_qty = $row['trans_qty'];

					if($minimum_qty > $trans_qty)
					{
						$narray=array(
							$cnt,
							$row['organization_name'],
							$row['item_name'],
							$row['inventory_code'],
							$row['locator_no'],
							$row['lot_number'],
							$row['serial_number'],
							$row['minimum_qty'],
							$row['trans_qty'],
						);
					
						fputcsv($handle, $narray);
					}
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

	function customerSOA()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['report'] = $page_data['CustomerSOAReport'] = 1;
		$page_data['page_name']  = 'report/customerSOA';
		$page_data['page_title'] = 'Customer SOA Report';

		$totalResult = $this->summary_model->getCustomerSOA("","",$this->totalCount);
		$page_data["totalRows"] = $totalRows = count($totalResult);
		
		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL;
		$invoice_number = isset($_GET['invoice_number']) ? $_GET['invoice_number'] : NULL;
		$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

		$this->redirectURL = 'summary/customerSOA?customer_name='.$customer_name.'&invoice_number='.$invoice_number.'&from_date='.$from_date.'&to_date='.$to_date.'';
		
		if ($customer_name || $invoice_number || $from_date || $to_date ) {
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
		
		$page_data['resultData'] = $result = $this->summary_model->getCustomerSOA($limit,$offset,$this->pageCount);
		
		#show start and ending Count
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
			header("Content-Disposition: attachment; filename=\"customer_SOA_".$date.".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen('php://output', 'w');
			
			fputcsv($handle, array("S.No","Invoice Source","Customer Name","Invoice #","Invoice Date","Invoice Amount","Paid Amount","Balance","Age (days)"));
			$cnt=1;

			foreach ($result as $row) 
			{
				$narray=array(
					$cnt,
					$row['invoice_source'],
					$row['customer_name'],
					$row['invoice_number'],
					date("d-M-Y",strtotime($row['invoice_date'])),
					number_format($row['sales_total'],DECIMAL_VALUE,'.',''),
					number_format($row['paid_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['balance_amount'],DECIMAL_VALUE,'.',''),
					$row['age'],
				);

				fputcsv($handle, $narray);
				$cnt++;
			}
			fclose($handle);
			exit;
		}
		#Download Excel end 

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
			
		$this->load->view($this->adminTemplate, $page_data);
	}

	function supplierSOA()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['report'] = $page_data['SupplierSOAReport'] = 1;
		$page_data['page_name']  = 'report/supplierSOA';
		$page_data['page_title'] = 'Supplier SOA Report';

		$totalResult = $this->summary_model->getSupplierSOA("","",$this->totalCount);
		$page_data["totalRows"] = $totalRows = count($totalResult);

		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		$supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : NULL;
		$supplier_site_id = isset($_GET['supplier_site_id']) ? $_GET['supplier_site_id'] : NULL;
		$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
		$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

		$this->redirectURL = 'report/supplierSOA?supplier_id='.$supplier_id.'&supplier_site_id='.$supplier_site_id.'&from_date='.$from_date.'&to_date='.$to_date.'';
		
		if ($supplier_id || $supplier_site_id || $from_date || $to_date ) {
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
		
		$page_data['resultData'] = $result = $this->summary_model->getSupplierSOA($limit,$offset,$this->pageCount);
		
		#show start and ending Count
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
			header("Content-Disposition: attachment; filename=\"supplier_SOA_report".$date.".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");

			$handle = fopen('php://output', 'w');
			
			fputcsv($handle, array("S.No","Supplier Name","Site Name","PO No","Receipt No","Receipt Date","Receipt Amount","Paid Amount","Balance","Age (days)"));
			$cnt=1;

			foreach ($result as $row) 
			{
				$narray=array(
					$cnt,
					$row['supplier_name'],
					$row['site_name'],
					$row['po_number'],
					$row['receipt_number'],
					date("d-M-Y",strtotime($row['receipt_date'])),
					number_format($row['sales_total'],DECIMAL_VALUE,'.',''),
					number_format($row['paid_amount'],DECIMAL_VALUE,'.',''),
					number_format($row['balance_amount'],DECIMAL_VALUE,'.',''),
					$row['age'],
				);

				fputcsv($handle, $narray);
				$cnt++;
			}
			fclose($handle);
			exit;
		}
		#Download Excel end 

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
			
		$this->load->view($this->adminTemplate, $page_data);
	}
	
}
?>
