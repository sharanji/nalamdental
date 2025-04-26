<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Expense extends CI_Controller 
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
	
	function ManageExpense($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageExpense'] = 1;
		$page_data['page_name']  = 'expense/ManageExpense';
		$page_data['page_title'] = 'Expenses';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					$expense_type_id = isset($_POST['expense_type_id']) ? count(array_filter($_POST['expense_type_id'])) : 0;
					
					if($expense_type_id == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 line is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}

					#Document No Start here
					$documentQry = "select doc_num_id,prefix_name,suffix_name,next_number 
					from doc_document_numbering as dm
					left join sm_list_type_values ltv on 
						ltv.list_type_value_id = dm.doc_type
					where 
						ltv.list_code = 'EXP' 
						and dm.active_flag = 'Y'
						and coalesce(dm.from_date,CURDATE()) <= CURDATE() 
						and coalesce(dm.to_date,CURDATE()) >= CURDATE()
					";
					$getDocumentData=$this->db->query($documentQry)->result_array();
						
					$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
					$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
					$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
					$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;

					$headerData = array(
						"expense_number"    => $documentNumber,
						"expense_status" 	=>  $_POST["header_status"],
						"description"       => isset($_POST['header_description']) ? $_POST['header_description'] : NULL,
						"expense_date"      => isset($_POST['expense_date']) ? date("Y-m-d",strtotime($_POST['expense_date'])) : NULL,
						"created_by"      	=> $this->user_id,
						"created_date"      => $this->date_time,
						"last_updated_by"   => $this->user_id,
						"last_updated_date" => $this->date_time,
						"fin_year_status" 	=> 0,
					);
					
					#Audit Trails Start here
					$tableName = table_expense_header;
					$menuName = expenses;

					$description = "Expense Header created successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here

					$this->db->insert('expense_header', $headerData);
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

						#Expense line start here
						$expense_type_id = isset($_POST['expense_type_id']) ? count(array_filter($_POST['expense_type_id'])) : 0;
					
						if( isset($_POST['expense_type_id']) && $expense_type_id > 0 )
						{
							$count = count($_POST['expense_type_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['header_id'] = $id;
								$LineData['expense_type_id'] = isset($_POST['expense_type_id'][$dp]) ? $_POST['expense_type_id'][$dp] : NULL;
								$LineData['category_id'] = isset($_POST['category_id'][$dp]) ? $_POST['category_id'][$dp] : NULL;
								$LineData['description'] = isset($_POST['description'][$dp]) ? $_POST['description'][$dp] :"";
								$LineData['payment_type_id'] = isset($_POST['payment_type_id'][$dp]) ? $_POST['payment_type_id'][$dp] : NULL;
								$LineData['reference_id'] = isset($_POST['reference_id'][$dp]) ? $_POST['reference_id'][$dp] : NULL;
								$LineData['expense_cost'] = isset($_POST['expense_cost'][$dp]) ? $_POST['expense_cost'][$dp] : NULL;
								$LineData['line_status'] =  !empty($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL;
								$LineData['created_by'] = $this->user_id;
								$LineData['created_date'] = $this->date_time;
								$LineData['last_updated_by'] = $this->user_id;
								$LineData['last_updated_date'] = $this->date_time;
								
								$upload_document = isset($_FILES['upload_document']['name']) ? count(array_filter($_FILES['upload_document']['name'])) : 0;
								
								if( $upload_document > 0 )
								{
									$upload_dir = 'uploads/expense_documents/'; 
									#Loop through each file in files[] array 
									
									$filesNameArr = array();
									#$filterName=array_filter($_FILES['upload_document']["name"]);
									
									#foreach($filterName as $key => $value) 
									#{ 
										$random_code = rand();
										$file_parts = pathinfo($_FILES['upload_document']['name'][$dp]);
										$file_name = $_FILES['upload_document']['name'][$dp];
										$ext = $file_parts['extension'];
										
										$file_tmpname1 = $_FILES['upload_document']['tmp_name'][$dp]; 
										$image['name'] = preg_replace('/\s+/', '', $_FILES['upload_document']['name'][$dp]);
										$image['type'] = $_FILES['upload_document']['type'][$dp];
										$image['tmp_name'] = $_FILES['upload_document']['tmp_name'][$dp];
										$image['error'] = $_FILES['upload_document']['error'][$dp];
										$image['size'] = $_FILES['upload_document']['size'][$dp];							
										#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
										$filesNameArr[] = $filesName = trim($random_code.'@'.$file_name);
										
										$filepath = $upload_dir.$filesName; #Set upload file path 
										move_uploaded_file($file_tmpname1, $filepath);
										
										$LineData['upload_document'] = trim($filesName);
										
									#} 
								}
								else
								{
									$LineData['upload_document'] = "";
								}
								
								$this->db->insert('expense_line', $LineData);
								$lindId = $this->db->insert_id();
							}
						}
						#Expense line end here

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Expense saved successfully!");
							redirect(base_url() . 'expense/ManageExpense/edit/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							if(isset($_POST['expense_type_id']))
							{
								$headerQry ="select 
									sum(line_tbl.expense_cost) as total_expense
									from expense_header as header_tbl

									left join expense_line as line_tbl on 
										line_tbl.header_id = header_tbl.header_id

									where header_tbl.header_id='".$header_id."' 
									
									group by header_tbl.header_id" ;
								$edit_data = $this->db->query($headerQry)->result_array();

								$approvalAmount = isset($edit_data[0]['total_expense']) ? $edit_data[0]['total_expense'] : "0.00";

								
								$approvalLevelqry = "select user_id,level_id from org_approval_line 
								where 
								org_approval_line.approver_type = 'EXP'
								and $approvalAmount between from_amount and to_amount"; 
								#group by org_approval_levels.level_id
								$getApprovalLevel = $this->db->query($approvalLevelqry)->result_array();
								
								$user_id = isset($getApprovalLevel[0]["user_id"]) ? $getApprovalLevel[0]["user_id"] : NULL;
								$level_id = isset($getApprovalLevel[0]["level_id"]) ? $getApprovalLevel[0]["level_id"] : NULL;

								$approvalLevQry = "select 
									org_approval_line.line_id,
									org_approval_line.user_id,
									org_approval_line.level_id,
									org_approval_line.from_amount,
									org_approval_line.to_amount,
									org_approval_levels.level_name,
									org_approval_levels.level_id,
									per_people_all.first_name,
									per_people_all.last_name
								from org_approval_line 

								left join org_approval_levels on 
									org_approval_levels.level_id = org_approval_line.level_id

								left join per_user on
									per_user.user_id = org_approval_line.user_id
								
								left join per_people_all on
									per_people_all.person_id = per_user.person_id

								where 
									org_approval_line.approver_type = 'EXP' 
									and org_approval_line.level_id <= (select level_id from org_approval_line 
									where 
									org_approval_line.approver_type = 'EXP' 
									and $approvalAmount between from_amount and to_amount)
								"; #group by org_approval_levels.level_id

								$getApprovalLevels = $this->db->query($approvalLevQry)->result_array();

								$chkExistQry = "select approval_status_id from org_approval_status 
										where approval_type= 'EXP' and reference_id= '".$header_id."' ";
								$chkExist = $this->db->query($chkExistQry)->result_array();

								if(count($chkExist) == 0)
								{
									$instances_id = $header_id."0001";
								}
								else
								{
									$instances_id = NULL;
								}

								foreach($getApprovalLevels as $levels)
								{
									$user_id = $levels['user_id'];
									$level_id = $levels['level_id'];

									if($user_id == '-1' && $level_id == '1')
									{
										$approval_status = 'Approved';
									}else{
										$approval_status = 'Pending';
									}

									$approvalsLevels = array(
										"reference_id" 		     => $header_id,
										"instances_id" 		     => $instances_id,
										"user_id" 	 		     => $user_id,
										"level_id" 	 		     => $level_id,
										"approval_status" 	     => $approval_status,
										"approval_type" 	     => "EXP",
										"created_by" 	  		 =>  $this->user_id,
										"created_date" 	  		 =>  $this->date_time,
										"last_updated_by" 	  	 =>  $this->user_id,
										"last_updated_date" 	 =>  $this->date_time
									);

									$this->db->insert('org_approval_status', $approvalsLevels);
									$approvalId = $this->db->insert_id();
								}


								if($user_id == '-1' && $level_id == '1') #Auto Approval
								{
									$expense_status = "Approved";

									#Header Tbl
									$headerUpdateData = array(
										"expense_status" 	  	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time,
										"submission_date" 	  =>  $this->date_time,
									);
									$this->db->where('header_id', $header_id);
									$headerTbl = $this->db->update('expense_header', $headerUpdateData);

									#Line Tbl
									$lineUpdateData = array(
										"line_status" 	  	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time
									);
									$this->db->where('header_id', $header_id);
									$lineTbl = $this->db->update('expense_line', $lineUpdateData);
								}
								else
								{
									$expense_status = "Pending Approval";

									#Header Tbl
									$headerUpdateData = array(
										"expense_status" 	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time,
										"submission_date" 	  =>  $this->date_time,
									);
									$this->db->where('header_id', $header_id);
									$headerTbl = $this->db->update('expense_header', $headerUpdateData);

									#Line Tbl
									$lineUpdateData = array(
										"line_status" 	  	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time
									);
									$this->db->where('header_id', $header_id);
									$lineTbl = $this->db->update('expense_line', $lineUpdateData);	
								}
							}

							$this->session->set_flashdata('flash_message' , "Expense submitted successfully!");
							redirect(base_url() . 'expense/ManageExpense', 'refresh');
						}

						/* $this->session->set_flashdata('flash_message' , "Expense saved successfully!");
						redirect(base_url() . 'expense/ManageExpense/edit/'.$id, 'refresh'); */
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"):
				
				$header_id = $id;

				if($type =="edit")
				{
					if($status == "pending_approval" || $status == "re_request")
					{
						$header_id = $id;

						if($status == "pending_approval")
						{
							$expense_status = "Withdrawn";
						}
						else if($status == "re_request")
						{
							$expense_status = "Requires Reapproval";
						}

						$getInstanceQry = "select max(instances_id) as instances_id from org_approval_status 
							where 
								reference_id='".$header_id."'
								and approval_type = 'EXP'
								and coalesce(flow_status,'') != 'Completed'
								";
						$getInstanceID = $this->db->query($getInstanceQry)->result_array();

						$instances_id = isset($getInstanceID[0]['instances_id']) ? $getInstanceID[0]['instances_id'] : NULL; 
						
						$flow_status = "Completed";

						$approvalData = array(
							"flow_status" 		  =>  $flow_status,
							"last_updated_by" 	  =>  $this->user_id,
							"last_updated_date"   =>  $this->date_time
						);

						$this->db->where('instances_id', $instances_id);
						$this->db->where('reference_id', $header_id);
						$this->db->where('approval_type', 'EXP');
						$approvalStatusId = $this->db->update('org_approval_status', $approvalData);
						#Update Complletion status end

						#Header Tbl
						$headerUpdateData = array(
							"expense_status" 	  	 =>  $expense_status,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('header_id', $header_id);
						$headerTbl = $this->db->update('expense_header', $headerUpdateData);

						#Line Tbl
						$lineUpdateData = array(
							"line_status" 	  		 =>  $expense_status,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('header_id', $header_id);
						$lineTbl = $this->db->update('expense_line', $lineUpdateData);

						$levelQry = 'select approval_status_id from org_approval_status 
							where 
							reference_id="'.$header_id.'"
							and approval_type="EXP"
							and approval_status ="Pending"
							';
						/* $updateQry = "update org_approval_status set approval_status='Withdrawn' 
									where approval_status_id in($levelQry)";
						$this->db->query($updateQry); */

						$updateQry = "update org_approval_status oas, ($levelQry) as tmp
									set oas.approval_status = 'Withdrawn' 
									where oas.approval_status_id = tmp.approval_status_id";
						$this->db->query($updateQry);
					}
				}

				$page_data['edit_data'] = $this->db->get_where('expense_header', array('header_id' => $id))
										->result_array();


				if($_POST)
				{
					/* $expense_type_id = isset($_POST['expense_type_id']) ? count(array_filter($_POST['expense_type_id'])) : 0;
					
					if($expense_type_id == 0)
					{
						$this->session->set_flashdata('error_message' , "Atleast 1 line is required!");
						redirect($_SERVER["HTTP_REFERER"], 'refresh');
					} */

					if(isset($_POST["submit_btn"]))
					{
						$count = isset($_POST['expense_type_id']) ? count(array_filter($_POST['expense_type_id'])) : 0;

						if($count == 0)
						{
							$this->session->set_flashdata('error_message' , "Atleast one item is required!");
							redirect($_SERVER["HTTP_REFERER"], 'refresh');
						}
					}

					$headerData = array(
						"expense_status" 	=>  $_POST["header_status"],
						"description"       => isset($_POST['header_description']) ? $_POST['header_description'] : NULL,
						"expense_date"      => isset($_POST['expense_date']) ? date("Y-m-d",strtotime($_POST['expense_date'])) : NULL,
						"last_updated_by"   => $this->user_id,
						"last_updated_date" => $this->date_time,
					);

					#Audit Trails Start here
					$tableName = table_expense_header;
					$menuName = expenses;
					$description = "Expense Header created successsfully!";
					auditTrails(array_filter($headerData),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails end here

					$this->db->where('header_id', $id);
					$result = $this->db->update('expense_header', $headerData);
					
					if($result)
					{
						#Expense items start
						$expense_type_id = isset($_POST['expense_type_id']) ? count(array_filter($_POST['expense_type_id'])) : 0;
					
						if(
							( isset($_FILES['upload_document']) &&
								count(array_filter($_FILES['upload_document']['name']) ) >0 
							) and 
							(isset($_POST['expense_type_id']) && $expense_type_id > 0 )
						)
						{					
							$this->db->where('header_id', $id);
							$this->db->delete('expense_line');
								
							$count=count($_POST['expense_type_id']);
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$LineData['header_id'] = $id;
								$LineData['expense_type_id'] = isset($_POST['expense_type_id'][$dp]) ? $_POST['expense_type_id'][$dp] : NULL;
								$LineData['category_id'] = isset($_POST['category_id'][$dp]) ? $_POST['category_id'][$dp] : NULL;
								$LineData['description'] = isset($_POST['description'][$dp]) ? $_POST['description'][$dp] :"";
								$LineData['payment_type_id'] = isset($_POST['payment_type_id'][$dp]) ? $_POST['payment_type_id'][$dp] : NULL;
								$LineData['reference_id'] = isset($_POST['reference_id'][$dp]) ? $_POST['reference_id'][$dp] : NULL;
								$LineData['expense_cost'] = isset($_POST['expense_cost'][$dp]) ? $_POST['expense_cost'][$dp] : NULL;
								$LineData['line_status'] =  !empty($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL;
										
								$LineData['created_by'] = $this->user_id;
								$LineData['created_date'] = $this->date_time;
								$LineData['last_updated_by'] = $this->user_id;
								$LineData['last_updated_date'] = $this->date_time;
								
								$upload_document = isset($_FILES['upload_document']['name']) ? count(array_filter($_FILES['upload_document']['name'])) : 0;
								$image_2 = isset($_POST['image_2'][$dp]) ? $_POST['image_2'][$dp] : NULL;

								if( $upload_document > 0 )
								{
									$upload_dir = 'uploads/expense_documents/'; 
									#Loop through each file in files[] array 
									
									$filesNameArr = array();

									
									#$filterName=array_filter($_FILES['upload_document']["name"]);
									
									#foreach($filterName as $key => $value) 
									#{ 
										$random_code = rand();
										$file_parts = pathinfo($_FILES['upload_document']['name'][$dp]);
										$file_name = $_FILES['upload_document']['name'][$dp];
										$ext = isset($file_parts['extension']) ? $file_parts['extension'] :"";
										
										$file_tmpname1 = $_FILES['upload_document']['tmp_name'][$dp]; 
										$image['name'] = preg_replace('/\s+/', '', $_FILES['upload_document']['name'][$dp]);
										$image['type'] = $_FILES['upload_document']['type'][$dp];
										$image['tmp_name'] = $_FILES['upload_document']['tmp_name'][$dp];
										$image['error'] = $_FILES['upload_document']['error'][$dp];
										$image['size'] = $_FILES['upload_document']['size'][$dp];							
										#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
										$filesNameArr[] = $filesName = trim($random_code.'@'.$file_name);
										
										$filepath = $upload_dir.$filesName; #Set upload file path 
										move_uploaded_file($file_tmpname1, $filepath);
										
										if(!empty($file_name))
										{
											$LineData['upload_document'] = trim($filesName);
										}else{
											$LineData['upload_document'] = trim($image_2);
										}
									#} 
								}
								else
								{
									$LineData['upload_document'] = $LineData['upload_document'] = trim($image_2);
								}
								
								$this->db->insert('expense_line', $LineData);
								$lindId = $this->db->insert_id();
							}
						}
						else
						{
							$this->db->where('header_id', $id);
							$this->db->delete('expense_line');
							
							if( isset($_POST['expense_type_id']) && $expense_type_id > 0 )
							{
								$count = count($_POST['expense_type_id']);
								
								for($dp=0;$dp<$count;$dp++)
								{	
									$LineData['header_id'] = $id;
									$LineData['expense_type_id'] = isset($_POST['expense_type_id'][$dp]) ? $_POST['expense_type_id'][$dp] : NULL;
									$LineData['category_id'] = isset($_POST['category_id'][$dp]) ? $_POST['category_id'][$dp] : NULL;
									$LineData['description'] = isset($_POST['description'][$dp]) ? $_POST['description'][$dp] :"";
									$LineData['payment_type_id'] = isset($_POST['payment_type_id'][$dp]) ? $_POST['payment_type_id'][$dp] : NULL;
									$LineData['reference_id'] = isset($_POST['reference_id'][$dp]) ? $_POST['reference_id'][$dp] : NULL;
									$LineData['expense_cost'] = isset($_POST['expense_cost'][$dp]) ? $_POST['expense_cost'][$dp] : NULL;
									$LineData['line_status'] =  !empty($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL;
									$LineData['last_updated_by'] = $this->user_id;
									$LineData['last_updated_date'] = $this->date_time;
									
									$upload_document = isset($_FILES['upload_document']['name']) ? count(array_filter($_FILES['upload_document']['name'])) : 0;
									$image_2 = isset($_POST['image_2'][$dp]) ? $_POST['image_2'][$dp] : NULL;
									if( $upload_document > 0 )
									{
										$upload_dir = 'uploads/expense_documents/'; 
										#Loop through each file in files[] array 
										
										$filesNameArr = array();
										#$filterName=array_filter($_FILES['upload_document']["name"]);
										
										#foreach($filterName as $key => $value) 
										#{ 
											$random_code = rand();
											$file_parts = pathinfo($_FILES['upload_document']['name'][$dp]);
											$file_name = $_FILES['upload_document']['name'][$dp];
											$ext = $file_parts['extension'];
											
											$file_tmpname1 = $_FILES['upload_document']['tmp_name'][$dp]; 
											$image['name'] = preg_replace('/\s+/', '', $_FILES['upload_document']['name'][$dp]);
											$image['type'] = $_FILES['upload_document']['type'][$dp];
											$image['tmp_name'] = $_FILES['upload_document']['tmp_name'][$dp];
											$image['error'] = $_FILES['upload_document']['error'][$dp];
											$image['size'] = $_FILES['upload_document']['size'][$dp];							
											#$filesNameArr[] = $filesName = trim($random_code.'.'.$ext);
											$filesNameArr[] = $filesName = trim($random_code.'@'.$file_name);
											
											$filepath = $upload_dir.$filesName; #Set upload file path 
											move_uploaded_file($file_tmpname1, $filepath);
											
											$LineData['upload_document'] = trim($filesName);
											
										#} 
									}
									else
									{
										$LineData['upload_document'] = $image_2;
									}
									
									$this->db->insert('expense_line', $LineData);
									$lindId = $this->db->insert_id();
								}
							}
						}
						#Expense items end

						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Expense saved successfully!");
							redirect(base_url() . 'expense/ManageExpense/edit/'.$id.'/'.$status, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							if(isset($_POST['expense_type_id']))
							{
								$headerQry ="select 
									sum(line_tbl.expense_cost) as total_expense
									from expense_header as header_tbl

									left join expense_line as line_tbl on 
										line_tbl.header_id = header_tbl.header_id

									where header_tbl.header_id='".$header_id."' 
									
									group by header_tbl.header_id" ;
								$edit_data = $this->db->query($headerQry)->result_array();

								$approvalAmount = isset($edit_data[0]['total_expense']) ? $edit_data[0]['total_expense'] : "0.00";

								$approvalLevelqry = "select user_id,level_id from org_approval_line 
								where 
								org_approval_line.approver_type = 'PO'
								and $approvalAmount  between from_amount and to_amount"; #group by org_approval_levels.level_id
								$getApprovalLevel = $this->db->query($approvalLevelqry)->result_array();
								
								$user_id = isset($getApprovalLevel[0]["user_id"]) ? $getApprovalLevel[0]["user_id"] : NULL;
								$level_id = isset($getApprovalLevel[0]["level_id"]) ? $getApprovalLevel[0]["level_id"] : NULL;


								$approvalLevQry = "select 
									org_approval_line.line_id,
									org_approval_line.user_id,
									org_approval_line.level_id,
									org_approval_line.from_amount,
									org_approval_line.to_amount,
									org_approval_levels.level_name,
									org_approval_levels.level_id,
									per_people_all.first_name,
									per_people_all.last_name
								from org_approval_line 

								left join org_approval_levels on 
									org_approval_levels.level_id = org_approval_line.level_id

								left join per_user on
									per_user.user_id = org_approval_line.user_id
								
								left join per_people_all on
									per_people_all.person_id = per_user.person_id

								where 
									org_approval_line.approver_type = 'EXP'
									and org_approval_line.level_id <= (select level_id from org_approval_line 
									where org_approval_line.approver_type = 'EXP' and $approvalAmount between from_amount and to_amount)
								"; #group by org_approval_levels.level_id

								$getApprovalLevels = $this->db->query($approvalLevQry)->result_array();


								$chkExistQry = "select max(instances_id) as instances_id from org_approval_status 
										where 
										approval_type = 'EXP'
										AND reference_id= '".$header_id."' ";
								$chkExist = $this->db->query($chkExistQry)->result_array();

								if( count($chkExist) > 0 )
								{
									$instancesId = isset($chkExist[0]['instances_id']) ? $chkExist[0]['instances_id'] : NULL;

									if($instancesId !=NULL)
									{
										$instances_id = $instancesId + 1;
									}
									else
									{
										$instances_id = '10001';
									}
								}
								else
								{
									$instances_id = '10001';
								}

								foreach($getApprovalLevels as $levels)
								{
									$user_id = $levels['user_id'];
									$level_id = $levels['level_id'];

									if($user_id == '-1' && $level_id == '1'){
										$approval_status = 'Approved';
									}else{
										$approval_status = 'Pending';
									}

									$approvalsLevels = array(
										"reference_id" 		     => $header_id,
										"instances_id" 		     => $instances_id,
										"user_id" 	 		     => $user_id,
										"level_id" 	 		     => $level_id,
										"approval_status" 	     => $approval_status,
										"approval_type" 	     => "EXP",
										"created_by" 	  		 => $this->user_id,
										"created_date" 	  		 => $this->date_time,
										"last_updated_by" 	  	 => $this->user_id,
										"last_updated_date" 	 => $this->date_time
									);

									$this->db->insert('org_approval_status', $approvalsLevels);
									$approvalId = $this->db->insert_id();
								}


								if($user_id == '-1' && $level_id == '1') #Auto Approval
								{
									$expense_status = "Approved";

									#Header Tbl
									$headerUpdateData = array(
										"expense_status" 	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time,
										"submission_date" 	  =>  $this->date_time,
									);
									$this->db->where('header_id', $header_id);
									$headerTbl = $this->db->update('expense_header', $headerUpdateData);

									#Line Tbl
									$lineUpdateData = array(
										"line_status" 	  	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time
									);
									$this->db->where('header_id', $header_id);
									$lineTbl = $this->db->update('expense_line', $lineUpdateData);
								}
								else
								{
									$expense_status = "Pending Approval";

									#Header Tbl
									$headerUpdateData = array(
										"expense_status" 	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time,
										"submission_date" 	  =>  $this->date_time,
									);
									$this->db->where('header_id', $header_id);
									$headerTbl = $this->db->update('expense_header', $headerUpdateData);

									#Line Tbl
									$lineUpdateData = array(
										"line_status" 	  	  =>  $expense_status,
										"last_updated_by" 	  =>  $this->user_id,
										"last_updated_date"   =>  $this->date_time
									);
									$this->db->where('header_id', $header_id);
									$lineTbl = $this->db->update('expense_line', $lineUpdateData);
								}
							}
							
							$this->session->set_flashdata('flash_message' , "Expense submitted successfully!");
							redirect(base_url() . 'expense/ManageExpense', 'refresh');
						}

						/* $this->session->set_flashdata('flash_message' , "Expense saved successfully!");
						redirect(base_url() . 'expense/ManageExpense/edit/'.$id, 'refresh'); */
						#redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
				}
			break;
			
			case ($type == "approval_status") : #approval_status
				$successMsg = "Expense ".strtolower($status)." successfully!";

				#Header Table
				$headerData = array(
					"expense_status"     => $status,
					"cancel_flag"        => 'Y',
					"cancelled_reason"   => NULL,
					"cancelled_date"     => $this->date_time,
					"cancelled_by"       => $this->user_id,
				);
				
				#Audit Trails Start here
				$tableName = table_expense_header;
				$menuName = expenses;
				$description = "Expenses Status updated successsfully!";
				auditTrails("",$tableName,$type,$menuName,"",$successMsg);
				#Audit Trails end here
				
				$this->db->where('header_id', $id);
				$header_result = $this->db->update('expense_header', $headerData);

				#Line Table
				$lineData = array(
					"line_status"     => $status,
					"cancel_flag"        => 'Y',
					"cancelled_reason"   => NULL,
					"cancelled_date"     => $this->date_time,
					"cancelled_by"       => $this->user_id,
				);
				$this->db->where('header_id', $id);
				$line_result = $this->db->update('expense_line', $lineData);

				$this->session->set_flashdata('flash_message' , $successMsg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult["header_data"] = $this->expense_model->getManageExpense("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$expense_no = isset($_GET['expense_no']) ? $_GET['expense_no'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : NULL;

				$this->redirectURL = 'expense/ManageExpense?expense_no='.$expense_no.'&from_date='.$from_date.'&to_date='.$to_date;
				
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
				
				$result = $this->expense_model->getManageExpense($limit, $offset, $this->pageCount);
				
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
					header("Content-Disposition: attachment; filename=\"Expense_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Expense No.","Expense Date","Status","Expense Type","Category","Payment Method","Reference ID","Remarks","Amount"));
					$cnt=1;
					$totalExpense = 0;
					foreach ($lineData as $row) 
					{
						$narray=array(
							$cnt,
							$row["expense_number"],
							date(DATE_FORMAT,strtotime($row['expense_date'])),
							$row['expense_status'],
							$row["type_name"],
							$row["particular_name"],
							$row["payment_type"],
							$row["reference_id"],
							$row["description"],
							$row["expense_cost"],
						);
						$totalExpense += $row["expense_cost"];
						fputcsv($handle, $narray);
						$cnt++;
					}
					$narray1 = array("","","","","","","","","Total :",$totalExpense);
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
	
	function ManageParticulars($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageExpense'] = 1;
		$page_data['page_name']  = 'expense/ManageParticulars';
		$page_data['page_title'] = 'Expense Categories';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['particular_name'] = ucfirst($this->input->post('particular_name'));
					$data['expense_type_id'] = $this->input->post('expense_type_id');
					$data['particular_status'] = 1;
					
					#particular exist start here
					$chkExistParticulars = $this->db->query("select particular_id from expense_particulars 
						where 
						expense_type_id='".$data['expense_type_id']."' 
						and particular_name='".$data['particular_name']."' 
						")->result_array();
							
					if(count($chkExistParticulars) > 0)
					{
						$this->session->set_flashdata('error_message' , "Expense Category Name already exist!");
						redirect(base_url() . 'expense/ManageParticulars/add', 'refresh');
					}
					#particular exist end here
					
					#Audit Trails Start here
					$tableName = table_expense_particulars;
					$menuName = expense_category;
					$description = "Expense Category created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here

					$this->db->insert('expense_particulars', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						#Audit Trails Start here
						/* $tableName = expense_category_tbl;
						$createdMessage = "Expense category created successfully!";
						auditTrails($createdMessage,$tableName,$type); */
						#Audit Trails end here

						$this->session->set_flashdata('flash_message' , "Expense Category added Successfully!");
						redirect(base_url() . 'expense/ManageParticulars', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('expense_particulars', array('particular_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['particular_name'] = ucfirst($this->input->post('particular_name'));
					$data['expense_type_id'] = $this->input->post('expense_type_id');
					#particular exist start here
						$chkExistParticulars = $this->db->query("select particular_id from expense_particulars 
							where 
							particular_id !='".$id."' and 
							expense_type_id ='".$data['expense_type_id']."' and
							particular_name  ='".$data['particular_name']."'
							
							")->result_array();
								
						if(count($chkExistParticulars) > 0)
						{
							$this->session->set_flashdata('error_message' , "Expense Category Name already exist!");
							redirect(base_url() . 'expense/ManageParticulars/edit'.$id, 'refresh');
						}
					#particular exist end here

					#Audit Trails Start here
					$tableName = table_expense_particulars;
					$menuName = expense_category;
					$description = "Expense Category updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails end here

					$this->db->where('particular_id', $id);
					$result = $this->db->update('expense_particulars', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Expense Category updated Successfully!");
						redirect(base_url() . 'expense/ManageParticulars', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('particular_id', $id);
				$this->db->delete('expense_particulars');
				
				$this->session->set_flashdata('flash_message' , "Particular deleted successfully!");
				redirect(base_url() . 'expense/ManageParticulars', 'refresh');
			break;
			
			case "status": #Active & Inactive
				if($status == 1){
					$data['particular_status'] = 1;
					$succ_msg = 'Expense category active successfully!';
				}else{
					$data['particular_status'] = 0;
					$succ_msg = 'Expense category inactive successfully!';
				}

				#Audit Trails Start here
				/* $tableName = expense_category_tbl;
				auditTrails($succ_msg,$tableName,$type); */
				#Audit Trails end here

				#Audit Trails Start here
				$tableName = table_expense_particulars;
				$menuName = expense_category;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('particular_id', $id);
				$this->db->update('expense_particulars', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'expense/ManageParticulars', 'refresh');
			break;
			
			case "export":
				$data = $this->db->query("select * from expense_particulars order by particular_id desc")->result_array();
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Particular".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Particular"));
				$cnt=1;
				foreach ($data as $row) 
				{
					$narray=array(
							$cnt,
							$row["particular_name"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->expense_model->getManageExpenseParticularCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('expense/ManageParticulars?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('expense/ManageParticulars?keywords=');
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
				
				$page_data['resultData']  = $result= $this->expense_model->getManageParticular($limit, $offset);
				
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
	
	function ManagePaymentType($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['manage_settings'] = $page_data['Setups'] = 1;
		
		$page_data['page_name']  = 'expense/ManagePaymentType';
		$page_data['page_title'] = 'Payment Methods';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['payment_type'] 			= $this->input->post('payment_type');
					$data['payment_type_status'] 	= 1;
					$data['sequence_number'] 		= $this->input->post('sequence_number');
					$data['default_payment'] 		= 0;
					
					# PaymentType exist start here
						$chkExistPaymenttype = $this->db->query("select payment_type_id from expense_payment_type
							where 
								payment_type='".$data['payment_type']."'
								")->result_array();
								
						if(count($chkExistPaymenttype) > 0)
						{
							$this->session->set_flashdata('error_message' , " PaymentType already exist!");
							redirect(base_url() . 'expense/ManagePaymentType/add', 'refresh');
						}
					# PaymentType exist end here
					
					$this->db->insert('expense_payment_type', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						if( !empty($_FILES['payment_icon']['name']) )
						{  
							move_uploaded_file($_FILES['payment_icon']['tmp_name'], 'uploads/payments/'.$id.'.png');
						}

						#Audit Trails Start here
						/* $tableName = payment_type_tbl;
						$createdMessage = "Payment type created successfully!";
						auditTrails($createdMessage,$tableName,$type); */
						#Audit Trails end here

						$this->session->set_flashdata('flash_message' , "Payment Type added Successfully!");
						redirect(base_url() . 'expense/ManagePaymentType', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('expense_payment_type', array('payment_type_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['payment_type'] 		= $this->input->post('payment_type');
					$data['sequence_number'] 	= $this->input->post('sequence_number');
					
					# PaymentType exist start here
						
						$chkExistPaymenttype = $this->db->query("select payment_type_id from expense_payment_type
							where 
								payment_type_id !='".$id."' and 
								( payment_type='".$data['payment_type']."' )
								")->result_array();
								
						if(count($chkExistPaymenttype) > 0)
						{
							$this->session->set_flashdata('error_message' , " PaymentType already exist!");
							redirect(base_url() . 'expense/ManagePaymentType/edit/'.$id, 'refresh');
						}
					# PaymentType exist end here
					
					#Audit Trails Start here
					/* $tableName = payment_type_tbl;
					auditTrails(array_filter($_POST),$tableName,$type,$page_data['edit_data']); */
					#Audit Trails end here

					$this->db->where('payment_type_id', $id);
					$result = $this->db->update('expense_payment_type', $data);
					
					if($result)
					{
						if( !empty($_FILES['payment_icon']['name']) )
						{  
							move_uploaded_file($_FILES['payment_icon']['tmp_name'], 'uploads/payments/'.$id.'.png');
						}
						$this->session->set_flashdata('flash_message' , "Payment Type updated Successfully!");
						redirect(base_url() . 'expense/ManagePaymentType', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('payment_type_id', $id);
				$this->db->delete('expense_payment_type');
				
				$this->session->set_flashdata('flash_message' , "Payment Type deleted successfully!");
				redirect(base_url() . 'expense/ManagePaymentType', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['payment_type_status'] = 1;
					$succ_msg = 'Payment Type unblocked successfully!';
				}else{
					$data['payment_type_status'] = 0;
					$succ_msg = 'Payment Type blocked successfully!';
				}

				#Audit Trails Start here
				/* $tableName = payment_type_tbl;
				auditTrails($succ_msg,$tableName,$type); */
				#Audit Trails end here

				$this->db->where('payment_type_id', $id);
				$this->db->update('expense_payment_type', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'expense/ManagePaymentType', 'refresh');
			break;
			
			case "export":
			
				$data = $this->db->query("select * from expense_payment_type order by payment_type_id desc")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"PaymentType".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Payment Type"));
				$cnt=1;
				foreach ($data as $row) 
				{
					$narray=array(
							$cnt,
							$row["payment_type"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			default : #Manage
				if (isset($_POST['default_payemnt_id'])) {
					
					$id = $this->input->post('default_payemnt_id');

					$data['default_payment'] = 0;
					$this->db->where('default_payment',1);
					$result = $this->db->update('expense_payment_type', $data);

					$data['default_payment'] = 1;
					$this->db->where('payment_type_id',$id);
					$result = $this->db->update('expense_payment_type', $data);
				
					if ($result) {
						$this->session->set_flashdata('flash_message' , 'Default Payment Method Updated');
						redirect(base_url() . 'expense/ManagePaymentType', 'refresh');
					}
					
				}

				$page_data["totalRows"] = $totalRows = $this->expense_model->getManageExpensePaymentTypeCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('expense/ManagePaymentType?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('expense/ManagePaymentType?keywords=');
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
				
				$page_data['resultData']  = $result= $this->expense_model->getManagePaymentType($limit, $offset);
				
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
	
	public function getExpenseCategory($category_id="")
	{
		$query1 = "select type_id,type_name from expense_type
					where type_status=1 order by type_name ASC";
		$data['expenseType'] =  $getExpenseType = $this->db->query($query1)->result_array();

		$query = "select particular_name,particular_id from expense_particulars
					where particular_status=1 order by particular_id desc";
		$data['expenseCategory'] = $getExpenseCategory = $this->db->query($query)->result_array();
		
		/* $expenseCategory = "<select class='form-control' id='category_id' name='category_id[]'><option value=''>- Select -</option>";
		foreach($getExpenseCategory as $category)
		{
			$expenseCategory .="<option value='".$category['particular_id']."'>".ucfirst($category['particular_name'])."</option>";
		}
		$expenseCategory .="</select>";
		$data['expenseCategory'] = $expenseCategory; */

		
		
		/* $expenseType = "<select class='form-control' required id='expense_type_id' name='expense_type_id[]'><option value=''>- Select -</option>";
		foreach($getExpenseType as $category)
		{
			$expenseType .="<option value='".$category['type_id']."'>".ucfirst($category['type_name'])."</option>";
		}
		$expenseType .="</select>";
		$data['expenseType'] = $expenseType; */

		$query2 = "select payment_type,payment_type_id from pay_payment_types
				where active_flag='Y' order by payment_type asc";
		$getPaymentType = $this->db->query($query2)->result_array();
		
		$paymentType = "<select class='form-control searchDropdown' required id='payment_type_id' name='payment_type_id[]'>
		<option value=''>- Select -</option>";
		foreach($getPaymentType as $category)
		{
			$paymentType .="<option value='".$category['payment_type_id']."'>".ucfirst($category['payment_type'])."</option>";
		}
		$paymentType .="</select>";
		$data['paymentType'] = $paymentType;
		
		echo json_encode($data);
	}

	#Generate PDF
	function generatePDF($id="")
    {
		$page_data['id'] = $id;
		
		$date = date('d-M-Y');

		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$html = $this->load->view('backend/expense/generateExpensePDF',$page_data,true);
		$pdf_name = $date;
		
		$mpdf = new \Mpdf\Mpdf();
        //$mpdf->AddPage('L','','','','',7,7,7,7);
		$mpdf->WriteHTML($html);
		/*
		$path_to_directory = "uploads/".$pdf_name;

        if (!file_exists($path_to_directory) && !is_dir($path_to_directory)) 
		{
            mkdir($path_to_directory, 0777, true);
			$mpdf->Output($path_to_directory."/".$pdf_name.'.pdf', 'F');
        }
		else
		{
			$mpdf->Output($path_to_directory."/".$pdf_name.'.pdf', 'F');
		}
		*/
		#$mpdf->Output('uploads/generate_pdf/'.$pdf_name.'.pdf', 'F');
		$mpdf->Output($pdf_name.'.pdf','I');
		
		/* $mpdf = new mPDF();
        $mpdf->allow_charset_conversion = true;
        $mpdf->charset_in = 'UTF-8';
        $mpdf->WriteHTML($html); */
	}

	function ManageExpenseType( $type = '', $id = '', $status = '' )
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageExpense'] = 1;
		$page_data['page_name']  = 'expense/ManageExpenseType';
		$page_data['page_title'] = 'Expense Type';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['type_name'] = ucfirst($this->input->post('type_name'));
					$data['type_description'] = ucfirst($this->input->post('type_description'));
					$data['type_status'] = 1;
					
					#Exist start here
					$chkExist = $this->db->query("select type_id from expense_type 
						where type_name='".$data['type_name']."' ")->result_array();
							
					if(count($chkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Expense type already exist!");
						redirect(base_url() . 'expense/ManageExpenseType', 'refresh');
					}
					#Exist end here
					
					
					#Audit Trails Start here
					$tableName = table_expense_type;
					$menuName = expense_type;
					$description = "Expense Created updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here

					$this->db->insert('expense_type', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{	
						#Audit Trails Start here
						/* $tableName = expense_type_tbl;
						$createdMessage = "Expense type created successfully!";
						auditTrails($createdMessage,$tableName,$type); */
						#Audit Trails end here

						$this->session->set_flashdata('flash_message' , "Expense type added Successfully!");
						redirect(base_url() . 'expense/ManageExpenseType', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('expense_type', array('type_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['type_name'] = ucfirst($this->input->post('type_name'));
					$data['type_description'] = ucfirst($this->input->post('type_description'));
					$data['type_status'] = 1;
					
					#Exist start here
					$chkExist = $this->db->query("select type_id from expense_type 
						where type_name='".$data['type_name']."' and type_id !='".$id."'  ")->result_array();
							
					if(count($chkExist) > 0)
					{
						$this->session->set_flashdata('error_message' , "Expense type already exist!");
						redirect(base_url() . 'expense/ManageExpenseType/'.$id, 'refresh');
					}
					#Exist end here

					#Audit Trails Start here
					$tableName = table_expense_type;
					$menuName = expense_type;
					$description = "Expense Updated updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails end here

					$this->db->where('type_id', $id);
					$result = $this->db->update('expense_type', $data);
					
					if($result)
					{
						$this->session->set_flashdata('flash_message' , "Expense type updated successfully!");
						redirect(base_url() . 'expense/ManageExpenseType', 'refresh');
					}
				}
			break;
			
			case "status": #Active & Inactive
				if($status == 1){
					$data['type_status'] = 1;
					$succ_msg = 'Expense type active successfully!';
				}else{
					$data['type_status'] = 0;
					$succ_msg = 'Expense type inactive successfully!';
				}

				#Audit Trails Start here
				/* $tableName = expense_type_tbl;
				auditTrails($succ_msg,$tableName,$type); */
				#Audit Trails end here

				#Audit Trails Start here
				$tableName = table_expense_type;
				$menuName = expense_type;
				$id = $id;
				auditTrails(($id),$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here

				$this->db->where('type_id', $id);
				$this->db->update('expense_type', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'expense/ManageExpenseType', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->expense_model->getManageExpenseTypeCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('expense/ManageExpenseType?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('expense/ManageExpenseType?keywords=');
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
				
				$page_data['resultData']  = $result= $this->expense_model->getManageExpenseType($limit, $offset);
				
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

	public function viewApprovals($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['ManageExpense'] = 1;
		$page_data['id'] = $id;

		$page_data['page_name']  = 'expense/viewApprovals';
		$page_data['page_title'] = 'Expense Approval';

		if(isset($_POST["approval_status_btn"]))
		{
			$approval_status_id = $_POST["approval_status_id"];
			$header_id = $reference_id = $_POST["reference_id"];
			$user_id = $_POST["user_id"];
			$approval_status = $_POST["approval_status"];

			$user_level_id = $_POST["user_level_id"];
			$max_level_id = $_POST["max_level_id"];
			
			if($user_level_id == $max_level_id)
			{
				#Update Completion status - Flow
				$getInstanceQry = "select max(instances_id) as instances_id from org_approval_status 
					where 
						reference_id='".$reference_id."'
						and approval_type = 'EXP'
						and coalesce(flow_status,'') != 'Completed'
						";
				$getInstanceID = $this->db->query($getInstanceQry)->result_array();

				$instances_id = isset($getInstanceID[0]['instances_id']) ? $getInstanceID[0]['instances_id'] : NULL; 
				
				$flow_status = "Completed";
				$approvalData = array(
					"flow_status" 		  =>  $flow_status,
					"last_updated_by" 	  =>  $this->user_id,
					"last_updated_date"   =>  $this->date_time
				);

				$this->db->where('instances_id', $instances_id);
				$this->db->where('reference_id', $reference_id);
				$this->db->where('approval_type', 'EXP');
				$approvalStatusId = $this->db->update('org_approval_status', $approvalData);
 				#Update Completion status end - Flow

				$approvalData = array(
					"approval_status" 		 =>  $_POST["approval_status"],
					"approval_remarks" 		 =>  $_POST["approval_remarks"],
					"action_date" 			 =>  $this->date_time,
					"last_updated_by" 	  	 =>  $this->user_id,
					"last_updated_date" 	 =>  $this->date_time
				);

				$this->db->where('approval_status_id', $approval_status_id);
				$this->db->where('reference_id', $reference_id);
				$this->db->where('user_id', $user_id);
				$approvalStatusId = $this->db->update('org_approval_status', $approvalData);

				if($approvalStatusId)
				{
					#Header Tbl
					$headerUpdateData = array(
						"expense_status" 	  =>  $approval_status,
						"last_updated_by" 	  =>  $this->user_id,
						"last_updated_date"   =>  $this->date_time,
						"approved_date" 	  =>  $this->date_time,
					);
					$this->db->where('header_id', $header_id);
					$headerTbl = $this->db->update('expense_header', $headerUpdateData);

					#Line Tbl
					$lineUpdateData = array(
						"line_status" 	  	  =>  $approval_status,
						"last_updated_by" 	  =>  $this->user_id,
						"last_updated_date"   =>  $this->date_time
					);
					$this->db->where('header_id', $header_id);
					$lineTbl = $this->db->update('expense_line', $lineUpdateData);
					
					$this->session->set_flashdata('flash_message' , "Expense has been ". $approval_status);
					redirect(base_url() . 'admin/dashboard', 'refresh');
				}
			}
			else if($user_level_id < $max_level_id && ($_POST["approval_status"] == "Rejected" || $_POST["approval_status"] == "Info Requested"))
			{
				/* $approvalStatusQry  = "select org_approval_status.* from org_approval_status 
					where 
					user_id='".$user_id."' and 
					reference_id='".$reference_id."' 
					and approval_type= 'PO' 
					and level_id = '".$user_level_id."' 
					and coalesce(flow_status,'') != 'Completed'"; */

				#Update Completion status - Flow
				$getInstanceQry = "select max(instances_id) as instances_id from org_approval_status 
					where 
						reference_id='".$reference_id."'
						and approval_type = 'EXP'
						and coalesce(flow_status,'') != 'Completed'
						";
				$getInstanceID = $this->db->query($getInstanceQry)->result_array();

				$instances_id = isset($getInstanceID[0]['instances_id']) ? $getInstanceID[0]['instances_id'] : NULL; 
				

				$flow_status = "Completed";

				$approvalData = array(
					"flow_status" 		  =>  $flow_status,
					"last_updated_by" 	  =>  $this->user_id,
					"last_updated_date"   =>  $this->date_time
				);

				$this->db->where('reference_id', $reference_id);
				$this->db->where('instances_id', $instances_id);
				$this->db->where('approval_type', 'EXP');
				$approvalStatusId = $this->db->update('org_approval_status', $approvalData);
 				#Update Completion status end - Flow


				$updateQry = "update org_approval_status set 
									approval_status='".$_POST["approval_status"]."',
									approval_remarks='".$_POST["approval_remarks"]."',
									action_date='".$this->date_time."',
									last_updated_by='".$this->user_id."',
									last_updated_date='".$this->date_time."'
									where 
										approval_type = 'EXP'
										and reference_id='".$reference_id."'
										and level_id >= '".$user_level_id."'
									 
									";

				$approvalStatusId = $this->db->query($updateQry);

				if($approvalStatusId)
				{
					#Header Tbl
					$headerUpdateData = array(
						"expense_status" 	  =>  $approval_status,
						"last_updated_by" 	  =>  $this->user_id,
						"last_updated_date"   =>  $this->date_time,
						"approved_date" 	  =>  $this->date_time,
					);
					$this->db->where('header_id', $header_id);
					$headerTbl = $this->db->update('expense_header', $headerUpdateData);

					#Line Tbl
					$lineUpdateData = array(
						"line_status" 	  	  =>  $approval_status,
						"last_updated_by" 	  =>  $this->user_id,
						"last_updated_date"   =>  $this->date_time
					);
					$this->db->where('header_id', $header_id);
					$lineTbl = $this->db->update('expense_line', $lineUpdateData);
					
					$this->session->set_flashdata('flash_message' , "Expense has been ". $approval_status);
					redirect(base_url() . 'admin/dashboard', 'refresh');
				}
			}
			else
			{
				$approvalData = array(
					"approval_status" 		 =>  $_POST["approval_status"],
					"approval_remarks" 		 =>  $_POST["approval_remarks"],
					"action_date" 			 =>  $this->date_time,
					"last_updated_by" 	  	 =>  $this->user_id,
					"last_updated_date" 	 =>  $this->date_time
				);

				$this->db->where('approval_status_id', $approval_status_id);
				$this->db->where('reference_id', $reference_id);
				$this->db->where('user_id', $user_id);
				$approvalStatusId = $this->db->update('org_approval_status', $approvalData);

				$this->session->set_flashdata('flash_message' , "Expense has been ". $approval_status);
				redirect(base_url() . 'admin/dashboard', 'refresh');
			}
		}
		
		$this->load->view($this->adminTemplate, $page_data);
	}


	/* public function viewApprovals($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}

		$page_data['ManageExpense'] = 1;
		$page_data['id'] = $id;

		$page_data['page_name']  = 'expense/viewApprovals';
		$page_data['page_title'] = 'Expense Approval';

		if(isset($_POST["approval_status_btn"]))
		{
			#"Approver not Found for this Request Amount start here
			$approvalsQry = "select org_approval_line.level_id,org_approval_line.amount from org_approval_line 
				left join org_approval_header on
					org_approval_header.header_id = org_approval_line.header_id
				where 
					org_approval_line.approver_type = 2
					
					order by level_id desc limit 0,1";
			$getApprovals = $this->db->query($approvalsQry)->result_array();

			$finalApproverAmount = isset($getApprovals[0]["amount"]) ? $getApprovals[0]["amount"] : 0;
			$finalApproverLevelID = isset($getApprovals[0]["level_id"]) ? $getApprovals[0]["level_id"] : 0;

			$levelID = isset($_POST['level_id']) ? $_POST['level_id'] : 0;
			$approvalAmount = isset($_POST['approval_amount']) ? $_POST['approval_amount'] : 0;

			/* $prAmount = "select gross_total from purchase_items where purchase_id='".$id."' ";
			$getprAmount = $this->db->query($prAmount)->result_array(); */
		/*
			$prAmount = "select coalesce(sum(line_tbl.expense_cost),0) as total_expense_cost from expense_header
			left join expense_line as line_tbl on 
				line_tbl.header_id = expense_header.expense_id
			where expense_id='".$id."' ";
			$getprAmount = $this->db->query($prAmount)->result_array();

			$prTotalAmount = 0;

			foreach($getprAmount as $prAmount)
			{
				#$prTotalAmount += $prAmount["gross_total"];
				$prTotalAmount += $prAmount["total_expense_cost"];
			}
			
			if( $finalApproverLevelID == $levelID && $finalApproverAmount < $prTotalAmount )
			{
				$this->session->set_flashdata('error_message' , "Approver not found for this Request Amount!");
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			}
			#"Approver not Found for this Request Amount end here


			$re_request_id = isset($_POST["re_request_id"]) ? $_POST["re_request_id"] : 0;

			$chkExistQry = "select approval_status_id from org_approval_status where 
				request_id='".$id."' and 
					user_id='".$this->user_id."' and 
						level_id='".$levelID."' and 
							approval_type = 2 and 
								submit_level = 0
				 ";
			$chkExist = $this->db->query($chkExistQry)->result_array();
			
            if ( count($chkExist) == 0 )  #Insert
			{
				$data = array(
                    "request_id"         => $id,
                    "user_id"            => $this->user_id,
                    "level_id"           => $levelID,
                    "approval_status"    => $_POST["approval_status"],
                    "approval_remarks"   => $_POST["approval_remarks"],
                    "approval_type"      => 2, #Expense
                    "re_request_id"      => $re_request_id,
                    "approval_date"      => time()
                );

                $this->db->insert('org_approval_status', $data);
                $purchase_id = $this->db->insert_id();

                if ($purchase_id) 
				{
					if($_POST["approval_status"] == 2 || $_POST["approval_status"] == 3) #2=>Rejected, 3=>Need More Information
					{
						$purchaseData["expense_status"] = 7; #Rejected
                        $this->db->where('expense_id', $id);
                        $result = $this->db->update('expense_header', $purchaseData);
					}

                    if ($levelID == 1 && $_POST["approval_status"] == 1) 
					{  
						#1st Level
                        $purchaseData["expense_status"] = 2; #InProcess
                        $this->db->where('expense_id', $id);
                        $result = $this->db->update('expense_header', $purchaseData);
                    }

					
				}
            }
			else #Update
			{
				$data = array(
                    #"request_id"         => $id,
                    #"user_id"            => $this->user_id,
                    #"level_id"           => $levelID,
                    "approval_status"    => $_POST["approval_status"],
                    "approval_remarks"   => $_POST["approval_remarks"],
                    #"approval_type"      => 1,
                    #"approval_date"      => time()
                );

				$this->db->where('request_id', $id);
				$this->db->where('user_id', $this->user_id);
				$this->db->where('level_id', $levelID);
				$result = $this->db->update('org_approval_status', $data);
			}

			#Update Approval Datas start here
			$approvalLevelQry = "select line_id from org_approval_line 
				where approver_type = 2";
			$getApprovalLevel = $this->db->query($approvalLevelQry)->result_array();
			$levelCount  = count($getApprovalLevel);
			
			$approvalStatusQry = "select approval_status_id from org_approval_status 
			where 
				request_id = '".$id."' and
					approval_type = 2 and 
						submit_level = 0";
			$getApprovalStatus = $this->db->query($approvalStatusQry)->result_array();
			$approvalLevelCount  = count($getApprovalStatus);

			$checkaRejectionReqQry = "select approval_status_id from org_approval_status 
			where 
				request_id = '".$id."' and
					approval_type = 2 and
						approval_status = 2 and
							submit_level = 0
				";
			$checkaRejectionReq = $this->db->query($checkaRejectionReqQry)->result_array();

			$rejectionCount = count($checkaRejectionReq);

			if ($levelCount == $approvalLevelCount) 
			{
				if($rejectionCount == 0)
				{
					if($_POST["approval_status"] == 2 || $_POST["approval_status"] == 3) #2=>Rejected, 3=>Need More Information
					{
						$purchaseData["expense_status"] = 7;
					}else{
						$purchaseData["expense_status"] = 3;
					}

					$this->db->where('expense_id', $id);
					$result = $this->db->update('expense_header', $purchaseData);

					#Update Rerquest Data
					$ReRequestData["request_status"] = $_POST["approval_status"];
					$this->db->where('re_request_id', $re_request_id);
					$this->db->where('request_id', $id);
					$this->db->where('request_type', 2); #request_type = 2 #Expense
					$result = $this->db->update('pr_po_rerequest', $ReRequestData);

					/*$submit_levelData["submit_level"] =1; //all levels Completed
					$this->db->where('submit_level !=', 1);
					$this->db->where('request_id', $id);
					$result = $this->db->update('org_approval_status', $submit_levelData);*/
				//}
			//}
			#Update Approval Datas start here
			/*
			if( $prTotalAmount <= $approvalAmount && $_POST["approval_status"] == 1 ) #5015==20000
			{
				#$purchaseData1["po_status"] = 3; #Approved

				if($_POST["approval_status"] == 2 || $_POST["approval_status"] == 3) #2=>Rejected, 3=>Need More Information
				{
					$purchaseData1["expense_status"] = 7;
				}
				else
				{
					$purchaseData1["expense_status"] = 3;
				}

				$this->db->where('expense_id', $id);
				$result = $this->db->update('expense_header', $purchaseData1);
			}

			$this->session->set_flashdata('flash_message' , "Approval status updated successfully!");
			redirect(base_url() . 'expense/viewApprovals/'.$id, 'refresh');
		}
		
		$this->load->view($this->adminTemplate, $page_data);
	//} */

	public function detailedViewApprovals($id="")
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		$page_data['manageApproval'] = 1;
		$page_data['id'] = $id;

		$page_data['page_name']  = 'expense/detailedViewApprovals';
		$page_data['page_title'] = 'Expense Approvals';

		$this->load->view($this->adminTemplate, $page_data);
	}

	# Ajax  Change
	public function ajaxSelectExpenseCategory() 
	{
        $id = $_POST["id"];

		if($id)
		{			
			$data =  $this->db->query("select expense_particulars.* from expense_particulars
					where expense_type_id='".$id."' and particular_status = 1 order by particular_name asc
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['particular_id'].'">'.ucfirst($val['particular_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }
}
?>
