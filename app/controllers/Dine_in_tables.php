<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dine_in_tables extends CI_Controller 
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
	
	function manageDineInTables($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['setups'] = 1;
		$page_data['page_name']  = 'dine_in_tables/manageDineInTables';
		$page_data['page_title'] = 'Dine In Tables';
		
		switch(true)
		{
			case ($type =="add"):
				if($_POST)
				{
					$table_name = isset($_POST['table_name']) ? count(array_filter($_POST['table_name'])) : 0;
					
					if($table_name == 0)
					{
						$this->session->set_flashdata('error_message' , "Atlease one line is required!");
						redirect(base_url() . 'dine_in_tables/manageDineInTables/add', 'refresh');
					}
					else
					{
						$chkExistQry = "select header_id from din_table_headers 
									where 
										branch_id='".$_POST["branch_id"]."' 
										and table_location_id='".$_POST["table_location_id"]."' 
										";
						$chkExist = $this->db->query($chkExistQry)->result_array();

						if( count($chkExist) > 0 )
						{
							$this->session->set_flashdata('error_message' , "Dine In tables already exist in this table locations !");
							redirect(base_url() . 'dine_in_tables/manageDineInTables/add', 'refresh');
						}

						$headerData = array(
							"branch_id"              => $_POST["branch_id"],
							"table_location_id"      => $_POST["table_location_id"],
							'created_by'             => $this->user_id,
							'created_date'           => $this->date_time,
							'last_updated_by'        => $this->user_id,
							'last_updated_date'      => $this->date_time,
						);

						$this->db->insert('din_table_headers', $headerData);
						$header_id = $this->db->insert_id();

						if($header_id)
						{
							#Line Table start here
							if(isset($_POST['table_name']))
							{
								$count = isset($_POST['table_name']) ? count(array_filter($_POST['table_name'])) : 0;

								if( $count > 0 )
								{
									for($dp=0;$dp<$count;$dp++)
									{
										$table_name = isset($_POST['table_name'][$dp]) ? $_POST['table_name'][$dp] :NULL;
										$table_code = isset($_POST['table_code'][$dp]) ? $_POST['table_code'][$dp] :NULL;
										$table_no_of_persons = isset($_POST['table_no_of_persons'][$dp]) ? $_POST['table_no_of_persons'][$dp] :NULL;
										$active_flag = isset($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL;
										
										$lineData =  array(
											'header_id'          	 => $header_id,
											'table_name'             => $table_name,
											'table_code'             => $table_code,
											'table_no_of_persons'    => $table_no_of_persons,
											'active_flag'            => $active_flag,

											'created_by'             => $this->user_id,
											'created_date'           => $this->date_time,
											'last_updated_by'        => $this->user_id,
											'last_updated_date'      => $this->date_time,
										);

										$this->db->insert('din_table_lines', $lineData);
										$line_id = $this->db->insert_id();
									}
								}
							}

							if(isset($_POST["save_btn"]))
							{
								$this->session->set_flashdata('flash_message' , "Dine In tables saved successfully!");
								redirect(base_url() . 'dine_in_tables/manageDineInTables/edit/'.$header_id, 'refresh');
							}
							else if(isset($_POST["submit_btn"]))
							{
								$this->session->set_flashdata('flash_message' , "Dine In tables submitted successfully!");
								redirect(base_url() . 'dine_in_tables/manageDineInTables', 'refresh');
							}
						}
					}
				}
			break;
			
			case ($type =="edit" || $type =="view" ):
				$headerQry = 'select * from din_table_headers where header_id="'.$id.'"';
				$page_data['editData'] = $this->db->query($headerQry)->result_array();
				
				$lineQry = "select line_tbl.* from din_table_lines as line_tbl where line_tbl.header_id = '".$id."' ";
				$page_data['dine_in_tables'] = $this->db->query($lineQry)->result_array();

				if($_POST)
				{
					if(isset($_POST["assignResourceBtn"]))
					{
						if(isset($_POST["user_id"]) && !empty($_POST["user_id"])){
							$resourceData = isset($_POST["user_id"]) ? count(array_filter($_POST["user_id"])) : 0;
						}else{
							$resourceData = 0;
						}

						if($resourceData > 0)
						{
							$count = $resourceData;
							
							for($dp=0;$dp<$count;$dp++)
							{
								$user_id = isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] : NULL;
								$waiter_id = isset($_POST["waiter_id"][$dp]) ? $_POST["waiter_id"][$dp] : NULL;
								$table_line_id = isset($_POST["table_line_id"][$dp]) ? $_POST["table_line_id"][$dp] : NULL;
								
								$checkQry = "select * from din_table_waiters where 
									table_header_id='".$id."' and 
									waiter_id='".$waiter_id."' and 
									table_line_id='".$table_line_id."' and 
									user_id='".$user_id."'
									";
								$checkresult = $this->db->query($checkQry)->result_array();

								if(count($checkresult) > 0)
								{
									$waiterAssignedData = array(
										"user_id"  		   => isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] : NULL,
										"last_updated_by"  	   => $this->user_id,
										"last_updated_date"    => $this->date_time,
									);
									
									$this->db->where('table_header_id', $id);
									$this->db->where('table_line_id', $table_line_id);
									$this->db->where('user_id', $user_id);
									$result1 = $this->db->update('din_table_waiters',$waiterAssignedData);
								}
								else
								{
									$resourceData = array(
										"table_header_id " 	   => $id,
										"table_line_id"        => $table_line_id,
										"user_id"  		       => isset($_POST['user_id'][$dp]) ? $_POST['user_id'][$dp] : NULL,
		
										"active_flag"  		   => $this->active_flag,
										"created_by"  		   => $this->user_id,
										"created_date"  	   => $this->date_time,
										"last_updated_by"  	   => $this->user_id,
										"last_updated_date"    => $this->date_time,
									);
		
									$this->db->insert('din_table_waiters', $resourceData);
									$waiter_assign_id = $this->db->insert_id();
								}	
							}
							
							$this->session->set_flashdata('flash_message' , "Waiter assigned successfully!");
							redirect($_SERVER['HTTP_REFERER'], 'refresh');
						}
						else
						{
							$this->session->set_flashdata('error_message' , "Atleast 1 waiter is required!");
							redirect($_SERVER["HTTP_REFERER"], 'refresh');
						}
					}
					else
					{
						if(isset($_POST['table_name']))
						{
							$itemsCount = count(array_filter($_POST['table_name']));

							if($itemsCount > 0)
							{
								for($dp=0;$dp<$itemsCount;$dp++)
								{
									$line_id = isset($_POST['line_id'][$dp]) ? $_POST['line_id'][$dp] :NULL;
									$branch_id = isset($_POST["branch_id"]) ? $_POST["branch_id"] : NULL;
		
									$chkExistQry = "select line_id from din_table_lines 
										where line_id='".$line_id."' ";
									$chkExist = $this->db->query($chkExistQry)->result_array();

									$table_name = isset($_POST['table_name'][$dp]) ? $_POST['table_name'][$dp] :NULL;
									$table_code = isset($_POST['table_code'][$dp]) ? $_POST['table_code'][$dp] :NULL;
									$table_no_of_persons = isset($_POST['table_no_of_persons'][$dp]) ? $_POST['table_no_of_persons'][$dp] :NULL;
									$active_flag = isset($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] : NULL;
											
									if(count($chkExist) == 0) #Create
									{
										$lineData = array(
											'header_id'             => $id,
											'table_name'            => $table_name,
											'table_code'            => $table_code,
											'table_no_of_persons'   => $table_no_of_persons,
											
											"active_flag"           => $active_flag,
											"created_by"            => $this->user_id,
											"created_date"          => $this->date_time,
											"last_updated_by"       => $this->user_id,
											"last_updated_date"     => $this->date_time,
										);

										$this->db->insert('din_table_lines',$lineData);
										$line_id = $this->db->insert_id();	
									}
									else #Update
									{
										$lineData = array(
											'table_name'            => $table_name,
											'table_code'            => $table_code,
											'table_no_of_persons'   => $table_no_of_persons,
											
											"last_updated_by"       => $this->user_id,
											"last_updated_date"     => $this->date_time,
										);

										$this->db->where('header_id',  $id);
										$this->db->where('line_id',  $line_id);
										$lineID = $this->db->update('din_table_lines', $lineData);				
									}
								}
							}
						}
							
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Dine In tables saved successfully!");
							redirect(base_url() . 'dine_in_tables/manageDineInTables/edit/'.$id, 'refresh');
						}
						else if(isset($_POST["submit_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Dine In tables submitted successfully!");
							redirect(base_url() . 'dine_in_tables/manageDineInTables', 'refresh');
						}
					}
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Dining table active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Dining table successfully!';
				}
				$this->db->where('header_id', $id);
				$this->db->update('din_table_headers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->dine_in_tables_model->getDineInTables("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : NULL;
				$table_location_id = isset($_GET['table_location_id']) ? $_GET['table_location_id'] : NULL;

				$redirectURL = 'dine_in_tables/manageDineInTables?branch_id='.$branch_id.'&table_location_id='.$table_location_id.'';
				
				if ( $branch_id !=NULL || $table_location_id !=NULL) {
					$base_url = base_url().$redirectURL;
				} else {
					$base_url = base_url().$redirectURL;
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
				
				$page_data['resultData']  = $result = $this->dine_in_tables_model->getDineInTables($limit, $offset,$this->pageCount);
				
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
	
	function ajaxDiningTableStatus($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status": #Block & Unblock
				if($status == 1){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Dining table available!';
				}else{
					$data['active_flag'] ='N';
					$succ_msg = 'Dining table unavailable!';
				}
				$this->db->where('line_id', $id);
				$this->db->update('din_table_lines', $data);
				echo $succ_msg;exit;
			break;
		}
	}

	public function geDineInWaiters($organization_id="")
	{
		/* $condition = "1=1 and per_user.active_flag='Y' ";

		$data['waiters'] = $this->db->query("select 
		per_user.user_id,
		per_user.user_name from per_user
		join per_people_all on per_people_all.person_id = per_user.person_id
		where $condition")->result();
 		
		*/
		$empQry = "select 
		per_people_all.person_id as user_id,
		per_people_all.first_name as user_name from per_people_all
		where 1=1 and per_people_all.active_flag='Y' order by per_people_all.first_name";

		$data['waiters'] = $this->db->query($empQry)->result();
	    echo json_encode($data);
	}
	
	public function deleteWaiter($waiter_id="")
	{
		$this->db->where('waiter_id', $waiter_id);
		$this->db->delete('din_table_waiters');
		echo '1';exit();
	}

	public function ajaxTableLocations()
	{
		$branch_id= $_POST["branch_id"];
		$table_location_id= $_POST["table_location_id"];

		$chkExistQry = "select header_id from din_table_headers 
					where 
						branch_id='".$branch_id."' 
						and table_location_id='".$table_location_id."' 
						";
		$chkExist = $this->db->query($chkExistQry)->result_array();

		if(count($chkExist) > 0)
		{
			$header_id = isset($chkExist[0]["header_id"]) ? $chkExist[0]["header_id"] : NULL;
			$result = "exist";
		}
		else 
		{
			$header_id = NULL;
			$result = "no_exist";
		}
		echo $result."@".$header_id;exit;
	}
}
?>
