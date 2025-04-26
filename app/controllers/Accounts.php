<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Accounts extends CI_Controller 
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
	
	function manageCashExpenses($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageAccounts'] = $page_data['manageCashExpenses'] = 1;
		$page_data['page_name']  = 'accounts/manageCashExpenses';
		$page_data['page_title'] = 'Cash Expenses';
		$cashExpenseVoucher = $page_data['cashExpenseVoucher'] = $this->common_model->documentNumber("CASH-EXPENSE-VOUCHER");
				
		switch(true)
		{
			case ($type == "create"):
				
				if($_POST)
				{
					$item_description = isset($_POST['item_description']) ? count(array_filter($_POST['item_description'])) : 0;
					
					if($item_description == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 line is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}

					#Document No Start here
					
					$getDocumentData=$this->common_model->documentNumber("CASH-EXPENSE");
						
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

					$headerData = array(
						"expense_number"    => $documentNumber,
						"branch_id"         => isset($_POST['branch_id']) ? $_POST['branch_id'] : NULL,
						"description"       => isset($_POST['header_description']) ? $_POST['header_description'] : NULL,
						"expense_date"      => isset($_POST['expense_date']) ? date("Y-m-d",strtotime($_POST['expense_date'])) : NULL,
						"created_by"      	=> $this->user_id,
						"created_date"      => $this->date_time,
						"last_updated_by"   => $this->user_id,
						"last_updated_date" => $this->date_time,
						"fin_year_status" 	=> 0,
					);

					$this->db->insert('acc_cash_expense_header', $headerData);
					$header_id = $id = $this->db->insert_id();
					
					if($id !="")
					{	
						#Update Next Val DOC Number tbl start
						$str_len = strlen($startingNumber);
						$nextValue1 = $startingNumber + 1;
						$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);

						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end

						#Cash Expense line start here
						if( isset($_POST['item_description']) && $item_description > 0 )
						{
							$count = count($_POST['item_description']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['header_id'] = $id;
								$LineData['item_description'] = isset($_POST['item_description'][$dp]) ? $_POST['item_description'][$dp] : NULL;
								$LineData['voucher_number'] = isset($_POST['voucher_number'][$dp]) ? $_POST['voucher_number'][$dp] : NULL;
								$LineData['expense_cost'] = isset($_POST['expense_cost'][$dp]) ? $_POST['expense_cost'][$dp] : NULL;
								
								$LineData['created_by'] = $this->user_id;
								$LineData['created_date'] = $this->date_time;
								$LineData['last_updated_by'] = $this->user_id;
								$LineData['last_updated_date'] = $this->date_time;
								
								$this->db->insert('acc_cash_expense_line', $LineData);
								$lindId = $this->db->insert_id();


								#Update Next Val DOC Number tbl startS
								$prefixName1 = isset($cashExpenseVoucher[0]['prefix_name']) ? $cashExpenseVoucher[0]['prefix_name'] : NULL;
								$startingNumber1 = isset($LineData['voucher_number']) ? $LineData['voucher_number'] : NULL;
								$suffixName1 = isset($cashExpenseVoucher[0]['suffix_name']) ? $cashExpenseVoucher[0]['suffix_name'] : NULL;
								#$documentNumber = $prefixName1.''.$startingNumber1.''.$suffixName1;


								$str_len1 = strlen($startingNumber1);
								$nextValue1 = $startingNumber1 + 1;
								$nextValue1 = str_pad($nextValue1,$str_len1,"0",STR_PAD_LEFT);

								$doc_num_idCashEXP = isset($cashExpenseVoucher[0]['doc_num_id']) ? $cashExpenseVoucher[0]['doc_num_id']:"";
								
								$UpdateData1['next_number'] = $nextValue1;
								$this->db->where('doc_num_id', $doc_num_idCashEXP);
								$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData1);
								#Update Next Val DOC Number tbl end
							}
						}
						#Cash line end here

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Cash expense saved successfully!");
							redirect(base_url() . 'accounts/manageCashExpenses/edit/'.$header_id, 'refresh');
						}
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"):
				
				$header_id = $id;
				
				$result = $this->accounts_model->getViewData($header_id);
				$page_data['headerData'] = $result['headerData'];
				$page_data['lineData'] = $result['lineData'];

				if($_POST)
				{
					$item_description = isset($_POST['item_description']) ? count(array_filter($_POST['item_description'])) : 0;
					
					if($item_description == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 line is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}

					$headerData = array(
						"branch_id"         => isset($_POST['branch_id']) ? $_POST['branch_id'] : NULL,
						"description"       => isset($_POST['header_description']) ? $_POST['header_description'] : NULL,
						"expense_date"      => isset($_POST['expense_date']) ? date("Y-m-d",strtotime($_POST['expense_date'])) : NULL,
						"created_by"      	=> $this->user_id,
						"created_date"      => $this->date_time,
						"last_updated_by"   => $this->user_id,
						"last_updated_date" => $this->date_time,
					);

					$this->db->where('header_id', $id);
					$result = $this->db->update('acc_cash_expense_header', $headerData);
					
					if($result)
					{
						#Cash Expense line start here
						if( isset($_POST['item_description']) && $item_description > 0 )
						{
							$count = count($_POST['item_description']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['header_id'] = $id;
								$line_id = isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] : NULL;

								$checkLineData = $this->accounts_model->checkLineData($header_id,$line_id);

								if( count($checkLineData) == 0 ) #Create
								{
									$LineData['item_description'] = isset($_POST['item_description'][$dp]) ? $_POST['item_description'][$dp] : NULL;
									$LineData['voucher_number'] = isset($_POST['voucher_number'][$dp]) ? $_POST['voucher_number'][$dp] : NULL;
									$LineData['expense_cost'] = isset($_POST['expense_cost'][$dp]) ? $_POST['expense_cost'][$dp] : NULL;
									
									$LineData['created_by'] = $this->user_id;
									$LineData['created_date'] = $this->date_time;
									$LineData['last_updated_by'] = $this->user_id;
									$LineData['last_updated_date'] = $this->date_time;
									
									$this->db->insert('acc_cash_expense_line', $LineData);
									$lindId = $this->db->insert_id();

									#Update Next Val DOC Number tbl startS
									$prefixName1 = isset($cashExpenseVoucher[0]['prefix_name']) ? $cashExpenseVoucher[0]['prefix_name'] : NULL;
									$startingNumber1 = isset($LineData['voucher_number']) ? $LineData['voucher_number'] : NULL;
									$suffixName1 = isset($cashExpenseVoucher[0]['suffix_name']) ? $cashExpenseVoucher[0]['suffix_name'] : NULL;
									#$documentNumber = $prefixName1.''.$startingNumber1.''.$suffixName1;


									$str_len1 = strlen($startingNumber1);
									$nextValue1 = $startingNumber1 + 1;
									$nextValue1 = str_pad($nextValue1,$str_len1,"0",STR_PAD_LEFT);

									$doc_num_idCashEXP = isset($cashExpenseVoucher[0]['doc_num_id']) ? $cashExpenseVoucher[0]['doc_num_id']:"";
									
									$UpdateData1['next_number'] = $nextValue1;
									$this->db->where('doc_num_id', $doc_num_idCashEXP);
									$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData1);
									#Update Next Val DOC Number tbl end
								}
								else #Update
								{
									$LineData['item_description'] = isset($_POST['item_description'][$dp]) ? $_POST['item_description'][$dp] : NULL;
									$LineData['voucher_number'] = isset($_POST['voucher_number'][$dp]) ? $_POST['voucher_number'][$dp] : NULL;
									$LineData['expense_cost'] = isset($_POST['expense_cost'][$dp]) ? $_POST['expense_cost'][$dp] : NULL;
									
									$LineData['last_updated_by'] = $this->user_id;
									$LineData['last_updated_date'] = $this->date_time;
									
									$this->db->where('header_id', $id);
									$this->db->where('line_id', $line_id);
									$updateLine = $this->db->update('acc_cash_expense_line', $LineData);

									#Update Next Val DOC Number tbl startS
									$prefixName1 = isset($cashExpenseVoucher[0]['prefix_name']) ? $cashExpenseVoucher[0]['prefix_name'] : NULL;
									$startingNumber1 = isset($LineData['voucher_number']) ? $LineData['voucher_number'] : NULL;
									$suffixName1 = isset($cashExpenseVoucher[0]['suffix_name']) ? $cashExpenseVoucher[0]['suffix_name'] : NULL;
									#$documentNumber = $prefixName1.''.$startingNumber1.''.$suffixName1;

									$str_len1 = strlen($startingNumber1);
									$nextValue1 = $startingNumber1 + 1;
									$nextValue1 = str_pad($nextValue1,$str_len1,"0",STR_PAD_LEFT);

									$doc_num_idCashEXP = isset($cashExpenseVoucher[0]['doc_num_id']) ? $cashExpenseVoucher[0]['doc_num_id']:"";
									
									$UpdateData1['next_number'] = $nextValue1;
									$this->db->where('doc_num_id', $doc_num_idCashEXP);
									$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData1);
									#Update Next Val DOC Number tbl end
								}
							}
						}
						#Cash line end here

						if(isset($_POST["save_btn"]))
						{
							
							$this->session->set_flashdata('flash_message' , "Cash expense saved successfully!");
							redirect(base_url() . 'accounts/manageCashExpenses/edit/'.$id, 'refresh');
						}
					}
				}
			break;
			
			default : #Manage
				$totalResult["header_data"] = $this->accounts_model->getManageCashExpense("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult["header_data"]);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$expense_no = isset($_GET['expense_no']) ? $_GET['expense_no'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

				$this->redirectURL = 'accounts/manageCashExpenses?expense_no='.$expense_no.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($expense_no != NULL || $from_date != NULL || $to_date != NULL ) {
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
				
				$result = $this->accounts_model->getManageCashExpense($limit, $offset, $this->pageCount);
				
				$page_data['resultData'] = $result["header_data"];
			    $page_data['lineData']  = $lineData = $result["line_data"];


				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
				}

				#Export Option
				$export = isset($_GET['export']) ? $_GET['export']:"";
				if(!empty($export))
				{
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"cash_expenses_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Expense No.","Expense Date","Item","Voucher Number","Amount"));
					$cnt=1;
					$totalExpense = 0;
					foreach ($lineData as $row) 
					{
						$narray=array(
							$cnt,
							$row["expense_number"],
							date(DATE_FORMAT,strtotime($row['expense_date'])),
							$row['item_description'],
							$row["voucher_number"],
							$row["expense_cost"]
						);
						$totalExpense += $row["expense_cost"];
						fputcsv($handle, $narray);
						$cnt++;
					}
					$narray1 = array("","","","","Total :",$totalExpense);
					fputcsv($handle, $narray1);
					fclose($handle);
					exit;
				}
				#Export Option end
				
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

	function deleteItem()
    {
		$line_id = $_POST["line_id"];
		
		$this->db->where('line_id', $line_id);
		$this->db->delete('acc_cash_expense_line');
		echo 1;exit;
	}

	function deleteItems()
    {
		$line_id = $_POST["line_id"];
		
		$this->db->where('line_id', $line_id);
		$this->db->delete('acc_supplier_payment_line');
		echo 1;exit;
	}

	function manageSupplierPayment($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		$page_data['ManageAccounts'] = $page_data['manageSupplierPayment'] = 1;
		$page_data['page_name']  = 'accounts/manageSupplierPayment';
		$page_data['page_title'] = 'Supplier Payment';
		$cashExpenseVoucher = $page_data['cashExpenseVoucher'] = $this->common_model->documentNumber("CASH-EXPENSE-VOUCHER");
				
		switch(true)
		{
			case ($type == "create"):
				
				if($_POST)
				{
					$supplier_id = isset($_POST['supplier_id']) ? count(array_filter($_POST['supplier_id'])) : 0;
					
					if($supplier_id == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 line is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}

					#Document No Start here
					
					$getDocumentData=$this->common_model->documentNumber("CASH-EXPENSE");
						
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

					$headerData = array(
						"payment_number"    => $documentNumber,
						"branch_id"         => isset($_POST['branch_id']) ? $_POST['branch_id'] : NULL,
						"description"       => isset($_POST['header_description']) ? $_POST['header_description'] : NULL,
						"payment_date"      => isset($_POST['payment_date']) ? date("Y-m-d",strtotime($_POST['payment_date'])) : NULL,
						"created_by"      	=> $this->user_id,
						"created_date"      => $this->date_time,
						"last_updated_by"   => $this->user_id,
						"last_updated_date" => $this->date_time,
						"fin_year_status" 	=> 0,
					);

					$this->db->insert('acc_supplier_payment_header', $headerData);
					$header_id = $id = $this->db->insert_id();
					
					if($id !="")
					{	
						#Update Next Val DOC Number tbl start
						$str_len = strlen($startingNumber);
						$nextValue1 = $startingNumber + 1;
						$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);

						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end

						#Cash Expense line start here
						if( isset($_POST['supplier_id']) && $supplier_id > 0 )
						{
							$count = count($_POST['supplier_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	

								$LineData = array(
									'header_id' 		=> $id,
									'supplier_id' 		=> isset($_POST['supplier_id'][$dp]) ? $_POST['supplier_id'][$dp] : NULL,
									'category_id' 		=> isset($_POST['category_id'][$dp]) ? $_POST['category_id'][$dp] : NULL,
									'description' 		=> isset($_POST['description'][$dp]) ? $_POST['description'][$dp] : NULL,
									'uom_id' 			=> isset($_POST['uom_id'][$dp]) ? $_POST['uom_id'][$dp] : NULL,
									'quantity' 			=> isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp] : NULL,
									'amount' 			=> isset($_POST['amount'][$dp]) ? $_POST['amount'][$dp] : NULL,
									'line_total'		=> isset($_POST['line_total'][$dp]) ? $_POST['line_total'][$dp] : NULL,
									'created_by' 		=> $this->user_id,
									'created_date' 		=> $this->date_time,
									'last_updated_by' 	=> $this->user_id,
									'last_updated_date' => $this->date_time
								);

								
								$this->db->insert('acc_supplier_payment_line', $LineData);
								$lindId = $this->db->insert_id();


							/*	#Update Next Val DOC Number tbl startS
								$prefixName1 = isset($cashExpenseVoucher[0]['prefix_name']) ? $cashExpenseVoucher[0]['prefix_name'] : NULL;
								$startingNumber1 = isset($LineData['supplier_id']) ? $LineData['supplier_id'] : NULL;
								$suffixName1 = isset($cashExpenseVoucher[0]['suffix_name']) ? $cashExpenseVoucher[0]['suffix_name'] : NULL;
								#$documentNumber = $prefixName1.''.$startingNumber1.''.$suffixName1;


								$str_len1 = strlen($startingNumber1);
								$nextValue1 = $startingNumber1 + 1;
								$nextValue1 = str_pad($nextValue1,$str_len1,"0",STR_PAD_LEFT);

								$doc_num_idCashEXP = isset($cashExpenseVoucher[0]['doc_num_id']) ? $cashExpenseVoucher[0]['doc_num_id']:"";
								
								$UpdateData1['next_number'] = $nextValue1;
								$this->db->where('doc_num_id', $doc_num_idCashEXP);
								$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData1);
								*/
								#Update Next Val DOC Number tbl end
							}
						}
						#Cash line end here

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Supplier payment saved successfully!");
							redirect(base_url() . 'accounts/manageSupplierPayment/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Supplier payment submitted successfully!");
							redirect(base_url() . 'accounts/manageSupplierPayment', 'refresh');
						}
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"):
				
				$header_id = $id;
				
				$result = $this->accounts_model->getsuppilerViewData($header_id);
				$page_data['headerData'] = $result['headerData'];
				$page_data['lineData'] = $result['lineData'];

				if($_POST)
				{
					$supplier_id = isset($_POST['supplier_id']) ? count(array_filter($_POST['supplier_id'])) : 0;
					
					if($supplier_id == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 line is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}

					$headerData = array(
						"branch_id"         => isset($_POST['branch_id']) ? $_POST['branch_id'] : NULL,
						"description"       => isset($_POST['header_description']) ? $_POST['header_description'] : NULL,
						"payment_date"      => isset($_POST['payment_date']) ? date("Y-m-d",strtotime($_POST['payment_date'])) : NULL,
						"created_by"      	=> $this->user_id,
						"created_date"      => $this->date_time,
						"last_updated_by"   => $this->user_id,
						"last_updated_date" => $this->date_time,
					);

					$this->db->where('header_id', $id);
					$result = $this->db->update('acc_supplier_payment_header', $headerData);
					
					if($result)
					{
						
						#Cash Expense line start here
						if( isset($_POST['supplier_id']) && $supplier_id > 0 )
						{
							$count = count($_POST['supplier_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								// $LineData['header_id'] = $id;
								$line_id = isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] : NULL;

								$checkLineData = $this->accounts_model->checkLineDatas($header_id,$line_id);

								if( count($checkLineData) == 0 ) #Create
								{

									$LineData = array(
										'header_id' 		=> $header_id,
										'supplier_id' 		=> isset($_POST['supplier_id'][$dp]) ? $_POST['supplier_id'][$dp] : NULL,
										'category_id' 		=> isset($_POST['category_id'][$dp]) ? $_POST['category_id'][$dp] : NULL,
										'description' 		=> isset($_POST['description'][$dp]) ? $_POST['description'][$dp] : NULL,
										'uom_id' 			=> isset($_POST['uom_id'][$dp]) ? $_POST['uom_id'][$dp] : NULL,
										'quantity' 			=> isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp] : NULL,
										'amount' 			=> isset($_POST['amount'][$dp]) ? $_POST['amount'][$dp] : NULL,
										'line_total'		=> isset($_POST['line_total'][$dp]) ? $_POST['line_total'][$dp] : NULL,
										'created_by' 		=> $this->user_id,
										'created_date' 		=> $this->date_time,
										'last_updated_by' 	=> $this->user_id,
										'last_updated_date' => $this->date_time
									);

								
									
									$this->db->insert('acc_supplier_payment_line', $LineData);
									$lindId = $this->db->insert_id();

								
								}
								else #Update
								{
									$LineData = array(
										'header_id' 		=> $header_id,
										'supplier_id' 		=> isset($_POST['supplier_id'][$dp]) ? $_POST['supplier_id'][$dp] : NULL,
										'category_id' 		=> isset($_POST['category_id'][$dp]) ? $_POST['category_id'][$dp] : NULL,
										'description' 		=> isset($_POST['description'][$dp]) ? $_POST['description'][$dp] : NULL,
										'uom_id' 			=> isset($_POST['uom_id'][$dp]) ? $_POST['uom_id'][$dp] : NULL,
										'quantity' 			=> isset($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp] : NULL,
										'amount' 			=> isset($_POST['amount'][$dp]) ? $_POST['amount'][$dp] : NULL,
										'line_total'		=> isset($_POST['line_total'][$dp]) ? $_POST['line_total'][$dp] : NULL,
										'last_updated_by' 	=> $this->user_id,
										'last_updated_date' => $this->date_time
									);

									$this->db->where('header_id', $header_id);
									$this->db->where('line_id', $line_id);
									$updateLine = $this->db->update('acc_supplier_payment_line', $LineData);

								}
							}
						}
						#Cash line end here

						if(isset($_POST["save_btn"]))
						{
							
							$this->session->set_flashdata('flash_message' , "Supplier payment saved successfully!");
							redirect(base_url() . 'accounts/manageSupplierPayment/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Supplier payment submitted successfully!");
							redirect(base_url() . 'accounts/manageSupplierPayment', 'refresh');
						}
					}
				}
			break;
			
			default : #Manage
				$totalResult["header_data"] = $this->accounts_model->getmanageSupplierPayment("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult["header_data"]);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$payment_number = isset($_GET['payment_number']) ? $_GET['payment_number'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

				$this->redirectURL = 'accounts/manageSupplierPayment?payment_number='.$payment_number.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($payment_number != NULL || $from_date != NULL || $to_date != NULL ) {
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
				
				$result = $this->accounts_model->getmanageSupplierPayment($limit, $offset, $this->pageCount);
				
				$page_data['resultData'] = $result["header_data"];
			    $page_data['lineData']  = $lineData = $result["line_data"];


				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$this->redirectURL, 'refresh');
				}

				#Export Option
				$export = isset($_GET['export']) ? $_GET['export']:"";
				if(!empty($export))
				{
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"Suppiler_Payements _".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Payemnt No.","Payment Date","Category","Description","UOM","Quantity","Amount","Total"));
					$cnt=1;
					$totalExpense = 0;
					foreach ($lineData as $row) 
					{
						$narray=array(
							$cnt,
							$row["payment_number"],
							date(DATE_FORMAT,strtotime($row['payment_date'])),
							$row["category_name"],
							$row['description'],
							$row["uom_code"],
							$row["quantity"],
							$row["amount"],
							$row["total_amount"]
						);
						$totalExpense += $row["total_amount"];
						fputcsv($handle, $narray);
						$cnt++;
					}
					$narray1 = array("","","","","","","","Total :",$totalExpense);
					fputcsv($handle, $narray1);
					fclose($handle);
					exit;
				}
				#Export Option end
				
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

	function getUomAll() 
	{
		
		$result = $this->uom_model->getUomAll();
		
		if(count($result)>0)
		{

			echo '<option value="">- Select -</option>';

			foreach($result as $val)
			{
				echo '<option value="'.$val['uom_id'].'">'.$val['uom_code'].'</option>';
			}

			
		}
		else {
		
			echo '<option value="">- Select -</option>';
		}
	}
}

?>
