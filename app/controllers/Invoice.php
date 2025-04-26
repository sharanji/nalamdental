<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Invoice extends CI_Controller 
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
	
	function manageinvoice($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['invoice'] = $page_data['manageinvoice'] = 1;
		$page_data['page_name']  = 'invoice/manageinvoice';
		$page_data['page_title'] = 'Invoice';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$invoiceType = $this->input->post('invoice_type');
					$invoice_due_date = !empty($_POST["invoice_due_date"]) ? date('Y-m-d',strtotime($_POST["invoice_due_date"])):NULL;
					$invoice_status = "DRAFT";
					$headerData = array(
						"invoice_type" 			 =>  $invoiceType,
						"customer_id" 	     	 =>  $this->input->post('customer_id'),
						"invoice_date" 	  		 =>  date('Y-m-d',strtotime($_POST["invoice_date"])),
						"invoice_due_date" 	  	 =>  $invoice_due_date,
						"payment_term_id" 	  	 =>  $_POST["payment_term_id"],
						"description" 	  	     =>  $this->input->post('header_description'),
						"created_by" 	  		 =>  $this->user_id,
						"created_date" 	  		 =>  $this->date_time,
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time,
						"po_number" 	         =>  $_POST["po_number"],
						"po_date" 	             =>  !empty($_POST["po_date"]) ? date('Y-m-d',strtotime($_POST["po_number"])):NULL,
						"dc_number"  	         =>  $this->input->post('dc_number'),
						"dc_date"    	         =>  !empty($_POST["dc_date"]) ? date('Y-m-d',strtotime($_POST["dc_date"])) : NULL,
						"invoice_status"  	     =>  $invoice_status,
					);

					
					#Audit Trails Start here
					$tableName = table_inv_invoice_headers;
					$menuName = invoice;
					$description = "Invoice created successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here


					$this->db->insert('inv_invoice_headers', $headerData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						if($invoiceType == 'WITH-GST')
						{
							$listCode= "invoice_with_gst";
						}
						else if($invoiceType == "WITH-OUT-GST")
						{
							$listCode= "invoice_with_out_gst";
						}

						
						
						#Document No Start here
						$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
						from doc_document_numbering as dm
						left join sm_list_type_values ltv on 
							ltv.list_type_value_id = dm.doc_type
						where 
							ltv.list_code = 'INVOICE' 
							and dm.doc_document_type = '".$listCode."' 
							and dm.active_flag = 'Y'
							and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
							and coalesce(dm.to_date,CURDATE()) >= CURDATE()
						";
						$getDocumentData=$this->db->query($documentQry)->result_array();
							
						$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
						$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
						$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
						$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
						$updateDocNum = array(
							"invoice_number" 	  	 =>  $documentNumber,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);

						
						$this->db->where('header_id', $header_id);
						$headerTbl1 = $this->db->update('inv_invoice_headers', $updateDocNum);


						#Update Next Val DOC Number tbl start
						$str_len = strlen($startingNumber);
						$nextValue1 = $startingNumber + 1;
						$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);

						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end
						#Document No End here

						#Line Data start
						if(isset($_POST['line_num']))
						{
							$count = count(array_filter($_POST['line_num']));
							for($dp=0;$dp<$count;$dp++)
							{
								$lineData = array(
									"header_id" 			 =>  $header_id,
									"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
									"item_description" 		 =>  !empty($_POST['line_description'][$dp]) ? $_POST['line_description'][$dp] : NULL,
									"quantity" 				 =>  !empty($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp] : 0,
									"hsn" 				 	 =>  !empty($_POST['hsn'][$dp]) ? $_POST['hsn'][$dp] : NULL,
									"uom" 				 	 =>  $_POST['uom'][$dp],
									"base_price" 			 =>  $_POST['base_price'][$dp],
									"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
									"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
									"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
									"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
									"price" 		 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
									"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
									"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
									"total_discount" 		 =>  !empty($_POST['discount_amount'][$dp]) ? $_POST['discount_amount'][$dp] : NULL,
									"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
									"created_by" 	  		 =>  $this->user_id,
									"created_date" 	  		 =>  $this->date_time,
									"last_updated_by" 	  	 =>  $this->user_id,
									"last_updated_date" 	 =>  $this->date_time
								);

								$this->db->insert('inv_invoice_lines', $lineData);
								$line_id = $this->db->insert_id();
							}
						}
						#Line Data end

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Invoice saved successfully!");
							redirect(base_url() . 'invoice/manageinvoice/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Invoice submitted successfully!");
							redirect(base_url() . 'invoice/manageinvoice', 'refresh');
						}
					}
				}
			break;
			
			case ($type =="edit" || $type =="view"): #edit
				$header_id = $id;

				$invoiceType = $this->input->post('invoice_type');
				
				$page_data['discount'] = $this->purchase_order_model->getDiscount();
				$page_data['tax'] = $this->purchase_order_model->getTax();

				$invoice_due_date = !empty($_POST["invoice_due_date"]) ? date('Y-m-d',strtotime($_POST["invoice_due_date"])):NULL;

				$result = $this->invoice_model->getViewData($id);
				$page_data['edit_data'] = $result['edit_data'];
				$page_data['line_data'] = $result['line_data'];

				if($_POST)
				{
					$headerData = array(
						"invoice_type" 			 =>  $invoiceType,
						"customer_id" 	     	 =>  $this->input->post('customer_id'),
						"invoice_date" 	  		 =>  date('Y-m-d',strtotime($_POST["invoice_date"])),
						"invoice_due_date" 	  	 =>  $invoice_due_date,
						"payment_term_id" 	  	 =>  $_POST["payment_term_id"],
						"description" 	  	     =>  $this->input->post('header_description'),
						
						"po_number" 	  	     =>  !empty($_POST["po_number"]) ? $_POST["po_number"]:NULL,
						"po_date" 	  	    	 =>  !empty($_POST["po_date"]) ? date('Y-m-d',strtotime($_POST["po_date"])):NULL,
						"dc_number" 	  	     =>  !empty($_POST["dc_number"]) ? $_POST["dc_number"]:NULL,
						"dc_date" 	  	    	 =>  !empty($_POST["dc_date"]) ? date('Y-m-d',strtotime($_POST["dc_date"])):NULL,

						"created_by" 	  		 =>  $this->user_id,
						"created_date" 	  		 =>  $this->date_time,
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time,	
					);
					
					#Audit Trails Edit Start here
					$tableName = table_inv_invoice_headers;
					$menuName = invoice;
					$description = "Invoice updated successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails Edit end here
					
					
					$this->db->where('header_id', $id);
					$result = $this->db->update('inv_invoice_headers', $headerData);
					
					if($result)
					{
						#Line Data start
						if(isset($_POST['line_num']))
						{
							$count = count(array_filter($_POST['line_num']));
							for($dp=0;$dp<$count;$dp++)
							{
								$line_id = $_POST['line_id'][$dp];

								$checkLineExist = $this->invoice_model->checkLineExist($header_id, $line_id);
								
								if(count($checkLineExist) == 0) #Insert
								{
									$lineData = array(
										"header_id"				 =>  $header_id,
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_description" 		 =>  !empty($_POST['line_description'][$dp]) ? $_POST['line_description'][$dp] : NULL,
										"quantity" 				 =>  $_POST['quantity'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"base_price" 			 =>  $_POST['base_price'][$dp],
										"tax" 			 		 =>  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL,
										"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
										"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
										"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
										"price" 		 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
										"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
										"total_tax" 		 	 =>  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL,
										"total_discount" 		 =>  !empty($_POST['discount_amount'][$dp]) ? $_POST['discount_amount'][$dp] : NULL,
										"hsn" 				 	 =>  !empty($_POST['hsn'][$dp]) ? $_POST['hsn'][$dp] : NULL,
										"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);
									
									$this->db->insert('inv_invoice_lines', $lineData);
									$line_id = $this->db->insert_id();
									
									
								}
								else #Update
								{
									$lineData = array(
										"line_num" 			 	 =>  !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
										"item_description" 		 =>  !empty($_POST['line_description'][$dp]) ? $_POST['line_description'][$dp] : NULL,
										"quantity" 				 =>  $_POST['quantity'][$dp],
										"uom" 				 	 =>  $_POST['uom'][$dp],
										"base_price" 			 =>  $_POST['base_price'][$dp],
										"discount_type" 		 =>  !empty($_POST['discount_type'][$dp]) ? $_POST['discount_type'][$dp] : NULL,
										"discount" 		 		 =>  !empty($_POST['discount'][$dp]) ? $_POST['discount'][$dp] : NULL,
										"discount_reason" 		 =>  !empty($_POST['discount_reason'][$dp]) ? $_POST['discount_reason'][$dp] : NULL,
										"total_discount" 		 =>  !empty($_POST['discount_amount'][$dp]) ? $_POST['discount_amount'][$dp] : NULL,
										"price" 		 		 =>  !empty($_POST['price'][$dp]) ? $_POST['price'][$dp] : NULL,
										"line_value" 		 	 =>  !empty($_POST['line_value'][$dp]) ? $_POST['line_value'][$dp] : NULL,
										"total" 		 	 	 =>  !empty($_POST['total'][$dp]) ? $_POST['total'][$dp] : NULL, 
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									if($invoiceType == "WITH_GST")
									{
										$lineData["tax"] 		 =  !empty($_POST['tax'][$dp]) ? $_POST['tax'][$dp] : NULL;
										$lineData["total_tax"]   =  !empty($_POST['total_tax'][$dp]) ? $_POST['total_tax'][$dp] : NULL;
										$lineData["hsn"]   		 =  !empty($_POST['hsn'][$dp]) ? $_POST['hsn'][$dp] : NULL;
									}
									else if($invoiceType == "WITH_OUT_GST")
									{
										$lineData["tax"] 		 =  NULL;
										$lineData["total_tax"]   =  NULL;
										$lineData["hsn"]   		 =  NULL;
									}
									
									$this->db->where('header_id', $id);
									$this->db->where('line_id', $line_id);
									$lineTbl = $this->db->update('inv_invoice_lines', $lineData);
								}
								#Line Data end
							}
						}
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Invoice saved successfully!");
							redirect(base_url() . 'invoice/manageinvoice/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Invoice submitted successfully!");
							redirect(base_url() . 'invoice/manageinvoice', 'refresh');
						}
					}
				}
			break;
			
			default : #Manage

				if( isset($_POST["update_status"]) )
				{
					$header_id = $_POST["header_id"];
					$invoice_status = $_POST["invoice_status"];

					$updateDate = array(
						"invoice_status"       => $invoice_status,
						
						"last_updated_date"  => $this->date_time,
						"last_updated_by"    => $this->user_id,
					);

					$this->db->where('header_id', $header_id);
					$result = $this->db->update('inv_invoice_headers', $updateDate);
					
					
					$this->session->set_flashdata('flash_message',"Invoice status updated successfully");
					redirect($_SERVER["HTTP_REFERER"], 'refresh');
				}

				$totalResult = $this->invoice_model->getmanageinvoice("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult["header_data"]);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$invoice_type 	= isset($_GET['invoice_type']) ? $_GET['invoice_type'] :NULL;
				$header_id 		= isset($_GET['header_id']) ? $_GET['header_id'] :NULL;
				$customer_id 	= isset($_GET['customer_id']) ? $_GET['customer_id'] :NULL;
				$customer_name 	= isset($_GET['customer_name']) ? $_GET['customer_name'] :NULL;
				$from_date 		= isset($_GET['from_date']) ? $_GET['from_date'] :NULL;
				$to_date 		= isset($_GET['to_date']) ? $_GET['to_date'] :NULL;

				$this->redirectURL = 'invoice/manageinvoice?invoice_type='.$invoice_type.'&header_id='.$header_id.'&customer_id='.$customer_id.'&customer_name='.$customer_name.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($invoice_type != NULL || $header_id != NULL || $customer_id != NULL || $customer_name != NULL || $from_date != NULL || $to_date != NULL) {
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
				
				$result = $this->invoice_model->getmanageinvoice($limit, $offset, $this->pageCount);
				
				$page_data['resultData'] = $result["header_data"];
			    $page_data['lineData']  = $lineData = $result["line_data"];

				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
				}

				#Download CSV Start
				$export = isset($_GET['export']) ? $_GET['export']:"";
				if(!empty($export))
				{
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"invoice_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");
					
					$handle = fopen('php://output', 'w');
					fputcsv($handle, 
						array(
							"S.No",
							"Invoice Type",
							"Invoice Number",
							"Invoice Date",
							"Customer Name",
							"Payment Terms",
							"Invoice Due Date",
							"Description",
							"PO Number",
							"PO Date",
							"DC Number",
							"DC Date",

							"Line No",
							"Description",
							"HSN/SAC Code",
							"Quantity",
							"UOM",
							"Base Price",
							"Tax (%)",
							"Discount Type",
							"Discount",
							"Discount Reason",
							"Total Discount",
							"Price",
							"Line Value",
							"Total Tax",
							"Total",
						)
					);
					$cnt=1;
					$invoiceTypelov = $this->common_model->lov('INVOICE-TYPE');
					foreach($lineData as $row) 
					{
						$invoiceType ='';
						foreach($invoiceTypelov as $WorkOrderStatus)
						{
							if(isset($row['invoice_type']) && $row['invoice_type'] == $WorkOrderStatus["list_code"] )
							{
								$invoiceType .= $WorkOrderStatus["list_value"];
							}
						} 
						
						if($row['invoice_date'] !=NULL){
							$invoice_date = date(DATE_FORMAT,strtotime($row['invoice_date']));
						}else{
							$invoice_date = '';
						}

						if($row['invoice_due_date'] !=NULL){
							$invoice_due_date = date(DATE_FORMAT,strtotime($row['invoice_due_date']));
						}else{
							$invoice_due_date = '';
						}

						if($row['po_date'] !=NULL){
							$po_date = date(DATE_FORMAT,strtotime($row['po_date']));
						}else{
							$po_date = '';
						}

						if($row['dc_date'] !=NULL){
							$dc_date = date(DATE_FORMAT,strtotime($row['dc_date']));
						}else{
							$dc_date = '';
						}

						$narray=array(
							$cnt,
							$invoiceType,
							$row['invoice_number'],
							$invoice_date,
							$row['customer_name'],
							$row['payment_term'],
							$invoice_due_date,
							$row['description'],
							$row['po_number'],
							$po_date,
							$row['dc_number'],
							$dc_date,
							
							$row["line_num"],
							$row["item_description"],
							$row["hsn"],
							$row["quantity"],
							$row["uom_code"],
							$row["base_price"],
							$row["tax"],
							$row["discount_type"],
							$row["total_discount"],
							$row["discount_reason"],
							$row["total_discount"],
							$row["price"],
							$row["line_value"],
							$row["total_tax"],
							$row["total"],
						);

						fputcsv($handle, $narray);
						$cnt++;
					}
					fclose($handle);
					exit;
				}
				#Download CSV End


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

	public function deleteLineItems()
	{
		$line_id = isset($_POST["line_id"]) ? $_POST["line_id"] : NULL;

		$this->db->where('line_id', $line_id);
		$this->db->delete('inv_invoice_lines');
		echo 1;exit;
	}

	function generatePDF($id="")
    {
		$page_data['id'] = $id;

		$result = $this->invoice_model->getViewData($id);
		$page_data['edit_data'] = $result['edit_data'];
		$page_data['line_data'] = $result['line_data'];

		$date = date('d-M-Y');
		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$pdf_name = "invoice_".$date;
		$mpdf = new \Mpdf\Mpdf([
			'setAutoTopMargin' => 'stretch',
			'curlAllowUnsafeSslRequests' => true,
		]);

		//$mpdf->SetHTMLHeader($this->load->view('backend/invoice/invoice_pdf/header',$page_data,true));
		$html = $this->load->view('backend/invoice/invoice_pdf/content',$page_data,true);
		//$mpdf->SetHTMLFooter($this->load->view('backend/invoice/invoice_pdf/footer',$page_data,true));
		
        $mpdf->AddPage('P','','','','',7,7,7,7,7,7);
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_name.'.pdf','I');
	}

	# get Ajax Brand Items
	public function getAjaxInvoice() 
	{
        $id = $_POST["id"];

		if($id)
		{		
			$getInvoices = $this->invoice_model->getAjaxInvoice($id);	

			if( count($getInvoices) > 0)
			{
				echo '<option value="">- Select -</option>';

				foreach($getInvoices as $val)
				{
					echo '<option value="'.$val['header_id'].'">'.$val['invoice_number'].'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }

	function ajaxInvoiceList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$invoice_number = $_POST['query'];

			$result = $this->invoice_model->getAjaxInvoiceAll($invoice_number);
			
			$output = '<ul class="list-unstyled-invoice_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$invoice_number = $row["invoice_number"];
					$invoice_id = $row["header_id"];
					$output .= '<a><li onclick="return getInvoiceList(\'' .$invoice_id. '\',\'' .$invoice_number. '\');">'.$invoice_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$invoice_number = "";
				$invoice_id = "";
				
				$output .= '<li onclick="return getInvoiceList(\'' .$invoice_id. '\',\'' .$invoice_number. '\');">Sorry! Invoice Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	function sendInvoice($id = '')
	{	
		$result = $this->invoice_model->getViewData($id);
		$headerData = $result['edit_data'];
		
		$mobile_number = $headerData[0]["mobile_number"];
		
		$invoiceLink = base_url()."invoice/generatePDF/".$id;

		$html = "Dear Customer,";  
		$html .= " Invoice Link : ".$invoiceLink;
		#$html .= '   '.SITE_NAME;
		
		$countoryCode = "+91";
		$whatsappNumber = $mobile_number;

		$url = 'https://wa.me/'.$countoryCode.$whatsappNumber.'?text='.$html.'';
		header('Location: ' . $url);
	}
}
?>
