<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends CI_Controller 
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
	
	function ManageProducts($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'products/ManageProducts';
		$page_data['page_title'] = 'Items';
		
		switch(true)
		{
			case ($type == "add"): #Add
				if($_POST)
				{
					// $data['item_name'] = $this->input->post('item_name');
					$data['item_code'] = $this->input->post('item_code');
					$data['item_name'] = $this->input->post('item_description');
					$data['item_description'] = $this->input->post('item_description');
					$data['long_description'] = $this->input->post('long_description');
					$data['item_type_id'] = $this->input->post('item_type');
					$data['hsn_code_id'] = $this->input->post('hsn_code_id');
					$data['category_id'] = $this->input->post('category_id');
					$data['uom'] = $this->input->post('uom');
					$data['item_cost'] = $this->input->post('item_cost');
					$data['minimum_qty'] = $this->input->post('minimum_qty');
					$data['revision_num'] = $this->input->post('revision_number');
					$data['short_code'] = $this->input->post('short_code');
					$data['active_flag'] = $this->active_flag;
					$data['deleted_flag'] = 'N';
					$data['created_by'] = $this->user_id;
					$data['created_date'] = $this->date_time;
                    $data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
					
					# Product exist start here
					$chkExistProduct = $this->db->query("select item_id from inv_sys_items 
						where item_code='".$data['item_code']."'
							")->result_array();
							
					if(count($chkExistProduct) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! item name already exist!");
						redirect(base_url() . 'products/ManageProducts/add', 'refresh');
					}
					# Lead exist end here
					
					
					#Audit Trails Start here
					$tableName = products;
					$menuName = products_items;
					$description = "Products created successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,"",$description);
					#Audit Trails end here

					$this->db->insert('inv_sys_items', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if( isset($_FILES['product_image']['name']) && $_FILES['product_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['product_image']['tmp_name'], 'uploads/products/' . $id . '.png');
						}
						
					 	$this->session->set_flashdata('flash_message' , "Item created Successfully!");
						redirect(base_url() . 'products/ManageProducts', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #Edit / View
				$page_data['edit_data'] = $this->db->get_where('inv_sys_items', array('item_id' => $id))
										->result_array();
				if($_POST)
				{
					$item_code = $this->input->post('item_code');
					$Chkitemname = $this->db->query("select item_id from inv_sys_items where item_id !='".$id."' and item_code='".$item_code."'")->result_array();
					
					if( count($Chkitemname) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! item name already exist!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					#Audit Trails Start here
					$tableName = products;
					$menuName = products_items;
					$description = "Products updated successsfully!";
					auditTrails(array_filter($_POST),$tableName,$type,$menuName,$page_data['edit_data'],$description);
					#Audit Trails end here
						
					$data['item_code'] = $this->input->post('item_code');
					// $data['item_name'] = $this->input->post('item_name');
					$data['item_name'] = $this->input->post('item_description');
					$data['item_description'] = $this->input->post('item_description');
					$data['long_description'] = $this->input->post('long_description');
					$data['item_type_id'] = $this->input->post('item_type');
					$data['hsn_code_id'] = $this->input->post('hsn_code_id');
					$data['category_id'] = $this->input->post('category_id');
					$data['item_cost'] = $this->input->post('item_cost');
					$data['uom'] = $this->input->post('uom');
					$data['minimum_qty'] = $this->input->post('minimum_qty');
					$data['revision_num'] = $this->input->post('revision_number');
					$data['short_code'] = $this->input->post('short_code');
					$data['last_updated_by'] = $this->user_id;
                    $data['last_updated_date'] = $this->date_time;
				
					$this->db->where('item_id', $id);
					$result = $this->db->update('inv_sys_items', $data);
					
					if($result > 0)
					{
						if( isset($_FILES['product_image']['name']) && $_FILES['product_image']['name'] !="" )
						{
							move_uploaded_file($_FILES['product_image']['tmp_name'], 'uploads/products/' . $id . '.png');
						}
						
					 	$this->session->set_flashdata('flash_message' , "Item updated successfully!");
					 	redirect($_SERVER["HTTP_REFERER"], 'refresh');
					}
				}
			break;
			
			case ($type == "delete"): #Delete
				$this->db->where('item_id', $id);
				$this->db->delete('inv_sys_items');
				
				$this->session->set_flashdata('flash_message' , "Product deleted successfully!");
				redirect(base_url() . 'products/ManageProducts', 'refresh');
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					#$data['end_date'] = NULL;
					$succ_msg = 'Item active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					#$data['end_date'] = $this->date;
					$succ_msg = 'Item inactive successfully!';
				}

				#Audit Trails Start here
				$tableName = products;
				$menuName = products_items;
				$id = $id;
				auditTrails($id,$tableName,$type,$menuName,"",$succ_msg);
				#Audit Trails end here
				
				$this->db->where('item_id', $id);
				$this->db->update('inv_sys_items', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;
			
			case ($type == "import"): #Import
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$chkExistQry = "select item_id from inv_sys_items where 
							item_code='".trim($data[0])."' ";
							$chkExist = $this->db->query($chkExistQry)->result_array();
							
							#Get Item Type
							$listTypeValuesQry = "select 
							sm_list_type_values.list_type_value_id,
							sm_list_type_values.list_code,
							sm_list_type_values.list_value	
							from sm_list_type_values

							left join sm_list_types on 
							sm_list_types.list_type_id = sm_list_type_values.list_type_id
							where 1=1
							and sm_list_type_values.active_flag = 'Y'  
							and sm_list_types.list_name = 'ITEMTYPE'
							and sm_list_type_values.list_code = '".trim($data[3])."'
							"; 
							$getItemType = $this->db->query($listTypeValuesQry)->result_array();
							$item_type_id = isset($getItemType[0]['list_type_value_id']) ? $getItemType[0]['list_type_value_id'] : NULL;

							#Get Category
							$chkCateQry = "select category_id from inv_categories where 
							category_name='".trim($data[4])."' ";
							$getCategories = $this->db->query($chkCateQry)->result_array();
							$category_id = isset($getCategories[0]['category_id']) ? $getCategories[0]['category_id'] : NULL;

							#Get Category
							$chkUomQry = "select uom_id from uom where 
							uom_code='".trim($data[5])."' ";
							$getUOM = $this->db->query($chkUomQry)->result_array();
							$uom = isset($getUOM[0]['uom_id']) ? $getUOM[0]['uom_id'] : NULL;

							if( count($chkExist) == 0 ) #Create
							{ 
								$postdata['item_code'] = trim($data[0]);
								$postdata['item_name'] = trim($data[1]);
								$postdata['item_description'] = trim($data[1]);
								$postdata['item_cost'] = trim($data[2]);

								$postdata['item_type_id'] = $item_type_id;
								$postdata['category_id'] = $category_id;
								$postdata['uom'] = $uom;

								$postdata['active_flag'] = $this->active_flag;
								$postdata['deleted_flag'] = 'N';
								$postdata['created_by'] = $this->user_id;
								$postdata['created_date'] = $this->date_time;
								$postdata['last_updated_by'] = $this->user_id;
								$postdata['last_updated_date'] = $this->date_time;
								
								$this->db->insert('inv_sys_items', $postdata);
								$id = $this->db->insert_id();
							}
							else
							{
								$postdata['item_code'] = trim($data[0]);
								$postdata['item_name'] = trim($data[1]);
								$postdata['item_description'] = trim($data[1]);
								$postdata['item_cost'] = trim($data[2]);
								$postdata['item_type_id'] = $item_type_id;
								$postdata['category_id'] = $category_id;
								$postdata['uom'] = $uom;

								$postdata['last_updated_by'] = $this->user_id;
								$postdata['last_updated_date'] = $this->date_time;
								
								$this->db->where('item_name', $data[1]);
								$this->db->update('inv_sys_items', $postdata);
							}
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Products imported error!");
						redirect(base_url() . 'products/ManageProducts', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Products imported successfully!");
					redirect(base_url() . 'products/ManageProducts', 'refresh');
				}
			break;
			
			default : #Manage
				$totalResult = $this->products_model->getProducts("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$active_flag = isset($_GET['active_flag']) ? $_GET['active_flag'] :NULL;
				$item_id = isset($_GET['item_id']) ? $_GET['item_id'] :NULL;
				$category_id = isset($_GET['category_id']) ? $_GET['category_id'] :NULL;
				$redirectURL = 'products/ManageProducts?item_id='.$item_id.'&category_id='.$category_id.'&active_flag='.$active_flag;
				
				if (!empty($_GET['item_id']) || !empty($_GET['category_id']) || !empty($_GET['active_flag'])) {
					$base_url = base_url('products/ManageProducts?item_id='.$_GET['item_id'].'&category_id='.$_GET['category_id'].'&active_flag='.$_GET['active_flag'].'');
				} else {
					$base_url = base_url('products/ManageProducts?item_id=&category_id=&active_flag=Y');
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
				
				$page_data['resultData']  = $result= $this->products_model->getProducts($limit, $offset, $this->pageCount);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}

				#Download CSV Start
				$export = isset($_GET['export']) ? $_GET['export']:"";
				if(!empty($export))
				{
					$date = date('d_M_Y');
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"items_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");
					
					$handle = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Item Name","Item Description","Category Name","Item Cost"));
					$cnt=1;
					foreach($totalResult as $row) 
					{
						$narray=array(
							$cnt,
							$row['item_name'],
							ucfirst($row['item_description']),
							ucfirst($row['category_name']),
							$row['item_cost'],
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
	
	function ManageServices($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageFrontOffice'] = 1;
		$page_data['page_name']  = 'products/ManageServices';
		$page_data['page_title'] = 'Manage Services';
		
		/* if(isset($_POST['delete']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$del_id=$_POST['checkbox'][$i];
				 
				$this->db->where('user_id', $del_id);
				$this->db->delete('users');
			}
			$this->session->set_flashdata('flash_message' , "Data deleted successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		} */
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['hsn_sac_code'] = $this->input->post('hsn_sac_code');
				
					# HSN CODE exist start here
					$chkExistHsncode = $this->db->query("select service_id from services 
						where 
							hsn_sac_code='".$data['hsn_sac_code']."'
							")->result_array();
					
					if(count($chkExistHsncode) > 0)
					{
						$this->session->set_flashdata('error_message' , "HSN SAC Code already exist!");
						redirect(base_url() . 'products/ManageServices/add', 'refresh');
					}
					# HSN CODE exist end here
					
					$data['product_code'] = $this->input->post('product_code');
					$data['service_name'] = $this->input->post('product_name');
					$data['price'] = $this->input->post('price');
					$data['cost'] = $this->input->post('cost');
					
					$data['unit'] = $this->input->post('unit');
					$data['size'] = isset($_POST['size']) ? $_POST['size'] :"";
					$data['alert_quantity'] = $this->input->post('alert_quantity');
					$data['category_id'] = $this->input->post('category_id');
					$data['subcategory_id'] = $this->input->post('subcategory_id');
					$data['brand_id'] = $this->input->post('brand_id');
					$data['quantity'] = isset($_POST['quantity']) ? $_POST['quantity']:"0";
					$data['tax_id'] = isset($_POST['tax_id']) ? $_POST['tax_id'] :"";
					$data['track_quantity'] = isset($_POST['track_quantity']) ? $_POST['track_quantity'] :"";
					
					$data['description'] = $this->input->post('description');
					
					$data['service_status'] = 1;
					
					
					
					$this->db->insert('services', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if( !empty($_FILES['product_image']['name']) )
						{  
							$data_1['service_image'] = $productName = $_FILES['product_image']['name'];
							move_uploaded_file($_FILES['product_image']['tmp_name'], 'uploads/products/'.$productName);
						
							$this->db->where('service_id', $id);
							$result = $this->db->update('services', $data_1);
						}
						
						$this->session->set_flashdata('flash_message' , "Service added Successfully!");
						redirect(base_url() . 'products/ManageServices', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('services', array('service_id' => $id))
										->result_array();
				if($_POST)
				{
					$product_code = $this->input->post('product_code');
					$ChkProductCode = $this->db->query("select service_id from services where service_id !='".$id."' and product_code='".$product_code."'")->result_array();
					
					if( count($ChkProductCode) > 0)
					{
						$this->session->set_flashdata('error_message' , "Sorry! Already exist Product Code!");
						redirect($_SERVER['HTTP_REFERER'], 'refresh');
					}
					
					# HSN SAC exist start here
					$data['hsn_sac_code'] = $this->input->post('hsn_sac_code');
					$chkExistHsncode = $this->db->query("select service_id from services 
						where 
							hsn_sac_code !='".$id."' and 
								( hsn_sac_code='".$data['hsn_sac_code']."' )
							")->result_array();
					
					if( count($chkExistHsncode) > 0 )
					{
						$this->session->set_flashdata('error_message' , "HSN SAC Code already exist!");
						redirect(base_url() . 'products/ManageServices/edit/'.$id, 'refresh');
					}
					# HSN SAC exist end here
									
					$data['product_code'] = $this->input->post('product_code');
					$data['service_name'] = $this->input->post('product_name');
					$data['price'] = $this->input->post('price');
					$data['cost'] = $this->input->post('cost');
					
					$data['unit'] = $this->input->post('unit');
					$data['size'] = isset($_POST['size']) ? $_POST['size'] :"";
					$data['alert_quantity'] = $this->input->post('alert_quantity');
					$data['category_id'] = $this->input->post('category_id');
					$data['subcategory_id'] = $this->input->post('subcategory_id');
					$data['brand_id'] = $this->input->post('brand_id');
					$data['quantity'] = isset($_POST['quantity']) ? $_POST['quantity']:"0";
					$data['tax_id'] = isset($_POST['tax_id']) ? $_POST['tax_id'] :"";
					$data['track_quantity'] = isset($_POST['track_quantity']) ? $_POST['track_quantity'] :"";
					
					$data['description'] = $this->input->post('description');
					
					
					
					$this->db->where('service_id', $id);
					$result = $this->db->update('services', $data);
					
					if($result)
					{
						if( !empty($_FILES['product_image']['name']) )
						{  
							$data_1['service_image'] = $productName = $_FILES['product_image']['name'];
							move_uploaded_file($_FILES['product_image']['tmp_name'], 'uploads/products/'.$productName);
						
							$this->db->where('service_id', $id);
							$result = $this->db->update('services', $data_1);
						}
						$this->session->set_flashdata('flash_message' , "Services updated Successfully!");
						redirect(base_url() . 'products/ManageServices', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('service_id', $id);
				$this->db->delete('services');
				
				$this->session->set_flashdata('flash_message' , "Service deleted successfully!");
				redirect(base_url() . 'products/ManageServices', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['service_status'] = 1;
					$succ_msg = 'Services InActive successfully!';
				}else{
					$data['service_status'] = 0;
					$succ_msg = 'Services Active successfully!';
				}
				$this->db->where('service_id', $id);
				$this->db->update('services', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'products/ManageServices', 'refresh');
			break;
			
			/* case "export":
			
				$data = $this->db->query("select 
						random_user_id,
						pic_number,
						first_name,
						phone_number,
						email,
						gender,
						age,
						address1,
						pin_code,
						blood_group
						from users where register_type =1")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Patient".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Case Number","PIC Number","Patient Name","Mobile Number","Email","Gender","Age","Blood Group","Pin Code","Address"));
				$cnt=1;
				foreach ($data as $row) 
				{
					$gender ="";
					foreach($this->gender as $key=>$value)
					{
						if($row['gender'] == $key)
						{
							$gender .=$value;
						}
					}
					$narray=array(
							$cnt,
							$row["random_user_id"],
							$row["pic_number"],
							ucfirst($row["first_name"]),
							$row["phone_number"],
							$row["email"],
							$gender,
							$row["age"],
							$row["blood_group"],
							$row["pin_code"],
							$row["address1"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			case "import":
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							$joined_date = time();
							$user_status = 1;
							$sql = "INSERT INTO `users`(`random_user_id`, `first_name`, `age`, `email`, `address1`, `phone_number`, `home_phone_number`, `work_phone_number`, `gender`,`remarks`,`register_type`,`joined_date`,`user_status`) 
									VALUES 
								('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."',1,'".$joined_date."','".$user_status."')";
							$this->db->query($sql);
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Patient import error!");
						redirect(base_url() . 'patient/ManagePatient', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Patient import successfully!");
					redirect(base_url() . 'patient/ManagePatient', 'refresh');
				}
			break; */
			
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->products_model->getServicesCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('products/ManageServices?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('products/ManageServices?keywords=');
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
				
				$page_data['resultData']  = $result= $this->products_model->getServices($limit, $offset);
				
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
	
	function patientAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$condition = 'user_type !=1 and register_type=1 
							and 
							(
								users.first_name like "%'.($_POST["query"]).'%" or 
								users.random_user_id like "%'.($_POST["query"]).'%"
							)
							';
			$query = "select 
						random_user_id,
						first_name,
						user_id

						from users 
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			
			$output = '<ul class="list-unstyled">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["user_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["first_name"].' ('.$row["random_user_id"].')'.'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! Patient Not Found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;  
		} 
	}
	
	function ManageProductsPrice($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'products/ManageProductsPrice';
		$page_data['page_title'] = 'Products Price';
		
		/* if(isset($_POST['delete']) && isset($_POST['checkbox']))
		{
			$cnt=array();
			$cnt=count($_POST['checkbox']);
			
			for($i=0;$i<$cnt;$i++)
			{
				$del_id=$_POST['checkbox'][$i];
				 
				$this->db->where('user_id', $del_id);
				$this->db->delete('users');
			}
			$this->session->set_flashdata('flash_message' , "Data deleted successfully!");
			redirect($_SERVER['HTTP_REFERER'], 'refresh');
		} */
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					# Add Rating for employee
					if( isset($_POST['cost']) && $_POST['cost'] !="" )
					{
						$count=count(array_filter($_POST['price']));
						
						for($dp=0;$dp<$count;$dp++)
						{																	
							$product_id = $data_2['product_id'] =  isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
							$data_2['price'] = isset($_POST['price'][$dp]) ? $_POST['price'][$dp] :"";
							$data_2['cost'] = isset($_POST['cost'][$dp]) ? $_POST['cost'][$dp] :"";
												
							$checkExitQuery = "select product_id,price_id from product_price 
							where 
								product_id='".$product_id."' ";
							$queryResult = $this->db->query($checkExitQuery)->result_array();

							
							if(count($queryResult) > 0)
							{
								
								$data_2['product_id'] = isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
								$data_2['price'] = isset($_POST['price'][$dp]) ? $_POST['price'][$dp] :"";
								$data_2['cost'] = isset($_POST['cost'][$dp]) ? $_POST['cost'][$dp] :"";
						
								$this->db->where('product_id',  $product_id);
								$result = $this->db->update('product_price', $data_2);
							}
							else
							{
								
								$data_2['product_id'] =  isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
								$data_2['cost'] = isset($_POST['cost'][$dp]) ? $_POST['cost'][$dp] :"";
								$data_2['price'] = isset($_POST['price'][$dp]) ? $_POST['price'][$dp] :"";
								
								$this->db->insert('product_price', $data_2);
								$id_3 = $this->db->insert_id();
							}
							
							
						/* 	$this->db->insert('product_price', $data_2);
							$id = $this->db->insert_id(); */
						}
					}
					#Add Rating for employee
					$this->session->set_flashdata('flash_message' , "Product price added Successfully!");
					redirect(base_url() . 'products/ManageProductsPrice', 'refresh');
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where( 'product_price', array('price_id' => $id))
				->result_array(); 

				if($_POST)
				{
					if( isset($_POST['cost']) && $_POST['cost'] !="" )
					{
						$count=count(array_filter($_POST['price']));
						
						for($dp=0;$dp<$count;$dp++)
						{	
							$price_id =  isset($_POST['price_id'][$dp]) ? $_POST['price_id'][$dp] :"";						
							$product_id =  isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";						
							$cost =  isset($_POST['cost'][$dp]) ? $_POST['cost'][$dp] :"";	
							$price =  isset($_POST['price'][$dp]) ? $_POST['price'][$dp] :"";	

							$checkExitQuery = "select product_id,price_id from product_price 
								where 
									product_id='".$product_id."' and
										price_id='".$price_id."' 
									
									";
							$queryResult = $this->db->query($checkExitQuery)->result_array();
							
							if(count($queryResult) > 0)
							{
								
								$data_2['product_id'] = isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
								$data_2['price'] = isset($_POST['price'][$dp]) ? $_POST['price'][$dp] :"";
								$data_2['cost'] = isset($_POST['cost'][$dp]) ? $_POST['cost'][$dp] :"";
								$this->db->where('price_id',  $price_id);
								$this->db->where('product_id',  $product_id);
								$result = $this->db->update('product_price', $data_2);
							}
							else
							{
								
								$data_2['product_id'] =  isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";
								$data_2['cost'] = isset($_POST['cost'][$dp]) ? $_POST['cost'][$dp] :"";
								$data_2['price'] = isset($_POST['price'][$dp]) ? $_POST['price'][$dp] :"";
								
								$this->db->insert('product_price', $data_2);
								$id_3 = $this->db->insert_id();
							}
						}
					}
					$this->session->set_flashdata('flash_message' , "Price updated Successfully!");
					redirect(base_url() . 'products/ManageProductsPrice', 'refresh');
				}
			break;
			
			case "import":

				
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							
							$product_status = 1;
							
							$productCode = $data[0];
							$costPrice = $data[1];
							$sellingPrice = $data[2];

							$productQry = "select product_id from products where product_code='".$productCode."' ";
							$getProduct = $this->db->query($productQry)->result_array();

							if(count($getProduct) > 0)
							{
								$product_id = isset($getProduct[0]["product_id"]) ? $getProduct[0]["product_id"] : 0;

								$existproductQry = "select price_id from product_price where product_id='".$product_id."' ";
								$chkExist = $this->db->query($existproductQry)->result_array();

                                if (count($chkExist) == 0) #Insert
								{
                                    $sql = "INSERT INTO `product_price`(`product_id`,`cost`,`price`) 
									VALUES 
									('".$product_id."','".$costPrice."','".$sellingPrice."')";
                                    $this->db->query($sql);
                                }
								else #Update
								{
									$data_2["cost"] = $costPrice;
									$data_2["price"] = $sellingPrice;

									$this->db->where('product_id',  $product_id);
									$result = $this->db->update('product_price', $data_2);
								}
							}
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Products price imported error!");
						redirect(base_url() . 'products/ManageProductsPrice', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Products price imported successfully!");
					redirect(base_url() . 'products/ManageProductsPrice', 'refresh');
				}
			break;

			/* case "export":
			
				$data = $this->db->query("select 
						random_user_id,
						pic_number,
						first_name,
						phone_number,
						email,
						gender,
						age,
						address1,
						pin_code,
						blood_group
						from users where register_type =1")->result_array();
				
				#$data[] = array('f_name'=> "Nishit", 'l_name'=> "patel", 'mobile'=> "999999999", 'gender'=> "male");
				
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"Patient".".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");

				$handle = fopen('php://output', 'w');
				fputcsv($handle, array("S.No","Case Number","PIC Number","Patient Name","Mobile Number","Email","Gender","Age","Blood Group","Pin Code","Address"));
				$cnt=1;
				foreach ($data as $row) 
				{
					$gender ="";
					foreach($this->gender as $key=>$value)
					{
						if($row['gender'] == $key)
						{
							$gender .=$value;
						}
					}
					$narray=array(
							$cnt,
							$row["random_user_id"],
							$row["pic_number"],
							ucfirst($row["first_name"]),
							$row["phone_number"],
							$row["email"],
							$gender,
							$row["age"],
							$row["blood_group"],
							$row["pin_code"],
							$row["address1"]
						);
					fputcsv($handle, $narray);
					$cnt++;
				}
				fclose($handle);
				exit;
			break;
			
			case "import":
				if($_FILES)
				{
					$filename = $_FILES["csv"]["tmp_name"];      
					
					if($_FILES["csv"]["size"] > 0)
					{
						$file = fopen($filename, "r");
						
						for ($lines = 0; $data = fgetcsv($file,1000,",",'"'); $lines++) 
						{
							if ($lines == 0) continue;
							$joined_date = time();
							$user_status = 1;
							$sql = "INSERT INTO `users`(`random_user_id`, `first_name`, `age`, `email`, `address1`, `phone_number`, `home_phone_number`, `work_phone_number`, `gender`,`remarks`,`register_type`,`joined_date`,`user_status`) 
									VALUES 
								('".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."',1,'".$joined_date."','".$user_status."')";
							$this->db->query($sql);
						}
						fclose($file); 
					}
					else
					{
						$this->session->set_flashdata('error_message' , "Patient import error!");
						redirect(base_url() . 'patient/ManagePatient', 'refresh');
					}
					$this->session->set_flashdata('flash_message' , "Patient import successfully!");
					redirect(base_url() . 'patient/ManagePatient', 'refresh');
				}
			break; 
			*/
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->products_model->getProductsPriceCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('products/ManageProductsPrice?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('products/ManageProductsPrice?keywords=');
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
				
				$page_data['resultData']  = $result= $this->products_model->getProductsPrice($limit, $offset);
				
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
	
	function products_nameAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$warehouse_id = isset($_POST["warehouse_id"]) ? $_POST["warehouse_id"] : 0;

			$output = '';  
			
			$condition = 	'products.product_status = 1 and 
							inv_product_assign_warehouse.warehouse_id = "'.$warehouse_id.'" and
							inv_product_assign_warehouse.assign_status = 1 and 
							(
								products.product_id like "%'.($_POST["query"]).'%" or 
								products.product_code like "%'.($_POST["query"]).'%" or
								products.product_name like "%'.($_POST["query"]).'%" 
							)
							';
			$query = "select 
						products.product_id,
						products.product_code,
						products.product_name

						from products 

						left join inv_product_assign_warehouse on 
							inv_product_assign_warehouse.product_id = products.product_id
								
						left join warehouse on 
							warehouse.warehouse_id = inv_product_assign_warehouse.warehouse_id

					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			$output = '<ul class="list-unstyled">';  
			$output .= '<li onclick="getuserId(0);">All</li>'; 
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["product_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["product_code"].' - '.$row["product_name"].'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! Product Not Found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;  
		} 
	}
	
	public function productList($id="", $warehouse_id="")
	{
		if($id == 0)
		{
            $condition='products.product_status = 1 and 
			inv_product_assign_warehouse.warehouse_id = "'.$warehouse_id.'" and
			inv_product_assign_warehouse.assign_status = 1';
        }
        else
        {
			$condition='products.product_status = 1 and 
			inv_product_assign_warehouse.warehouse_id = "'.$warehouse_id.'" and
			inv_product_assign_warehouse.assign_status = 1 and
			products.product_id="'.$id.'" ';
        }

		$getAllEmployeeQry = "select 
				products.product_id,
				products.product_code,
				products.product_name from products 

				left join inv_product_assign_warehouse on 
					inv_product_assign_warehouse.product_id = products.product_id
						
				left join warehouse on 
					warehouse.warehouse_id = inv_product_assign_warehouse.warehouse_id
			
			where product_status=1 and $condition ";
	
		$data['empData'] = $this->db->query($getAllEmployeeQry)->result();

		$locatorQry = "select * from inv_item_sub_inventory where inventory_status=1 and warehouse_id='".$warehouse_id."' ";
		$data['locator'] = $this->db->query($locatorQry)->result();

		echo json_encode($data);
		exit;
	}

	function assignProductLocator($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'products/assignProductLocator';
		$page_data['page_title'] = 'Assign Product Locator';
		
		switch($type)
		{
			case "add": #add
				$page_data['warehouse'] = $this->purchase_model->getWarehouse();

				if($_POST)
				{
					$count = isset($_POST['product_id']) ? count(array_filter($_POST['product_id'])) : 0;

					if($count == 0)
					{
						$this->session->set_flashdata('error_message' , "Please assign atleast one product!");
						redirect(base_url() . 'products/assignProductLocator/add', 'refresh');
					}

					$checkExitQuery = "select header_id from inv_assign_product_locator_header where warehouse_id='".$_POST["warehouse_id"]."'";
					$checkExit = $this->db->query($checkExitQuery)->result_array();

					if(count($checkExit) > 0)
					{
						$this->session->set_flashdata('error_message' , "Already assigned this warehouse!");
						redirect(base_url() . 'products/assignProductLocator/add', 'refresh');
					}

					$headerData = array(
						"warehouse_id"   => $_POST["warehouse_id"],
						"assign_status"  => 1,
						"created_date"   => time(),
					);

					$this->db->insert('inv_assign_product_locator_header', $headerData);
					$id = $this->db->insert_id();

					if($id)
					{
						if( isset($_POST['product_id']) && $_POST['product_id'] !="" )
						{
							$count=count(array_filter($_POST['product_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{																	
								$lineData = array(
									"header_id"           => $id,
									"product_id"          => isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"",
									"inventory_id"        => isset($_POST['inventory_id'][$dp]) ? $_POST['inventory_id'][$dp] :"",
									"locator_id"          => isset($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] :"",
									"assign_line_status"  => 1,
								);
								
								$this->db->insert('inv_assign_product_locator_line',$lineData);
								$lineID = $this->db->insert_id();
							}
						}
						
						$this->session->set_flashdata('flash_message' , "Product assigned successfully!");
						redirect(base_url() . 'products/assignProductLocator', 'refresh');
					}	
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('inv_assign_product_locator_header', array('header_id' => $id))
				->result_array(); 
				$page_data['warehouse'] = $this->purchase_model->getWarehouse();

				if($_POST)
				{
					$count = isset($_POST['product_id']) ? count(array_filter($_POST['product_id'])) : 0;

					if($count == 0)
					{
						$this->session->set_flashdata('error_message' , "Please assign atleast one product!");
						redirect(base_url() . 'products/assignProductLocator/edit/'.$id, 'refresh');
					}

					$headerData = array(
						"warehouse_id"   => $_POST["warehouse_id"]
					);

					$this->db->where('header_id',  $id);
					$result = $this->db->update('inv_assign_product_locator_header', $headerData);

					if($result)
					{
						if( isset($_POST['product_id']) && $_POST['product_id'] !="" )
						{
							$count=count(array_filter($_POST['product_id']));
							
							for($dp=0;$dp<$count;$dp++)
							{	
								$product_id =  isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"";						
								
								$checkExitQuery = "select line_id from inv_assign_product_locator_line 
									where 
										header_id='".$id."' and
											product_id='".$product_id."' 
										
										";
								$queryResult = $this->db->query($checkExitQuery)->result_array();
								
								if(count($queryResult) > 0)
								{
									$lineData = array(
										"product_id"          => isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"",
										"inventory_id"        => isset($_POST['inventory_id'][$dp]) ? $_POST['inventory_id'][$dp] :"",
										"locator_id"          => isset($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] :"",
									);
									
									$this->db->where('header_id',  $id);
									$this->db->where('product_id',  $product_id);
									$lineID = $this->db->update('inv_assign_product_locator_line', $lineData);
								}
								else
								{
									$lineData = array(
										"header_id"           => $id,
										"product_id"          => isset($_POST['product_id'][$dp]) ? $_POST['product_id'][$dp] :"",
										"inventory_id"        => isset($_POST['inventory_id'][$dp]) ? $_POST['inventory_id'][$dp] :"",
										"locator_id"          => isset($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] :"",
										"assign_line_status"  => 1,
									);
									
									$this->db->insert('inv_assign_product_locator_line',$lineData);
									$lineID = $this->db->insert_id();
								}
							}
						}
					}
					
					$this->session->set_flashdata('flash_message' , "Product assigned successfully!");
					redirect(base_url() . 'products/assignProductLocator/edit/'.$id, 'refresh');
				}
			break;

			case "status": #Active & InActive
				if($status == 1){
					$data['assign_status'] = 1;
					$succ_msg = 'Assign product inactive successfully!';
				}else{
					$data['assign_status'] = 0;
					$succ_msg = 'Assign product active successfully!';
				}
				$this->db->where('header_id', $id);
				$this->db->update('inv_assign_product_locator_header', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'products/assignProductLocator', 'refresh');
			break;
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->products_model->getAssignProductLocatorCount();
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('products/assignProductLocator?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('products/assignProductLocator?keywords=');
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
				
				$page_data['resultData']  = $result= $this->products_model->getAssignProductLocator($limit, $offset);
				
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

	function viewAssignedProducts($id = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['id'] = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'products/viewAssignedProducts';
		$page_data['page_title'] = 'View Assign Product Locators';
			
		$this->load->view($this->adminTemplate, $page_data);
	}

	function ajaxAssignProductLocator($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status": #Block & Unblock
				if($status == 1){
					$data['assign_line_status'] = 1;
					$succ_msg = 'Locator status active successfully!';
				}else{
					$data['assign_line_status'] = 0;
					$succ_msg = 'Locator status inactive successfully!';
				}
				$this->db->where('line_id', $id);
				$this->db->update('inv_assign_product_locator_line', $data);
				echo ($succ_msg);
				exit;
			break;
		}
	}

	function deleteAssignProducts($line_id = '')
    {
		if( $line_id > 0 && !empty($line_id) )
		{
			$this->db->where('line_id', $line_id);
			$this->db->delete('inv_assign_product_locator_line');
			echo 1;	
		}exit;
	}

	# Ajax  Change
	public function selectAjaxLocators() 
	{
        $id = $_POST["id"];		
		if($id)
		{			
			$data =  $this->db->query("select inv_item_locators.* from inv_item_locators
					
					where 
						inv_item_locators.inventory_id='".$id."' and inv_item_locators.locator_status = 1
					")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['locator_id'].'">'.ucfirst($val['rack_code']).'-'.ucfirst($val['locator_no']).'-'.ucfirst($val['locator_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">No locators under this inventory!</option>';
			}
		}
		die;
    }


	function assignProductWarehouse($id = '')
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
		
		$page_data['id'] = $product_id = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'products/assignProductWarehouse';
		$page_data['page_title'] = 'Assign Product to Warehouse';
		
		if(isset($_POST["add"]))
		{
			# Product exist start here
			$chkExistProduct = $this->db->query("select assign_id from inv_product_assign_warehouse 
			where 
				warehouse_id='".$_POST["warehouse_id"]."' and
					product_id='".$product_id."'
				")->result_array();
				
			if(count($chkExistProduct) > 0)
			{
				$this->session->set_flashdata('error_message' , "Warehouse already assigned in this product!");
				redirect(base_url() . 'products/assignProductWarehouse/'.$id, 'refresh');
			}
			# Product exist start here

			$postData = array(
				"warehouse_id"   => $_POST["warehouse_id"],
				"product_id"     => $product_id,
				"assign_status"  => 1,
				"created_date"   => time(),
			);

			$this->db->insert('inv_product_assign_warehouse', $postData);
			$id = $this->db->insert_id();
			
			if($id !="")
			{
				$this->session->set_flashdata('flash_message' , "Product assigned successfully!");
				redirect(base_url() . 'products/assignProductWarehouse/'.$product_id, 'refresh');
			}
		}

		$page_data["totalRows"] = $totalRows = $this->products_model->getProductsWarehousesCount($product_id);
	
		if(!empty($_SESSION['PAGE']))
		{$limit = $_SESSION['PAGE'];
		}else{$limit = 10;}
		
		if (!empty($_GET['keywords'])) {
			$base_url = base_url('products/assignProductWarehouse'.$product_id.'?keywords='.$_GET['keywords']);
		} else {
			$base_url = base_url('products/assignProductWarehouse'.$product_id.'?keywords=');
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
		
		$page_data['resultData']  = $result = $this->products_model->getProductsWarehouses($limit, $offset,$product_id);
		
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

		$this->load->view($this->adminTemplate, $page_data);
	}

	function assignActiveInactiveStatus($product_id = '',$id = '',$status="")
	{
		if($status == 1)
		{
			$data['assign_status'] = 1;
			$succ_msg = 'Assigned warehouse inactive successfully!';
		}
		else
		{
			$data['assign_status'] = 0;
			$succ_msg = 'Assigned warehouse active successfully!';
		}
		$this->db->where('assign_id', $id);
		$this->db->update('inv_product_assign_warehouse', $data);
		$this->session->set_flashdata('flash_message' , $succ_msg);
		redirect(base_url() . 'products/assignProductWarehouse/'.$product_id, 'refresh');
	}
	

	function productsNameAjaxSearch()
    {
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$condition = 	'products.product_status = 1 and 
							(
								products.product_id like "%'.($_POST["query"]).'%" or 
								products.product_code like "%'.($_POST["query"]).'%" or
								products.product_name like "%'.($_POST["query"]).'%" 
							)
							';
			$query = "select 
						products.product_id,
						products.product_code,
						products.product_name

						from products 

						
					where ".$condition." ";
			
			$result = $this->db->query($query)->result_array();
			$output = '<ul class="list-unstyled">';  
			$output .= '<li onclick="getuserId(0);">All</li>'; 
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$patinetID=  $row["product_id"];
					$output .= '<li onclick="getuserId('.$patinetID.');">'.$row["product_code"].' - '.$row["product_name"].'</li>';  
				}  
			}  
			else  
			{  
				$output .= '<li onclick="getuserId(0);">Sorry! Product Not Found.</li>';  
			}  
			$output .= '</ul>';  
			echo $output;  
		} 
	}
	
	public function productListAssignPrice($id="")
	{
		$warehouse_id = isset($_POST["warehouse_id"]) ? $_POST["warehouse_id"] : 0;
		
		if($id == 0)
		{
            $condition='1 = 1';
        }
        else
        {
			$condition='products.product_id="'.$id.'" ';
        }

		$getAllEmployeeQry = "select 
				products.product_id,
				products.product_code,
				products.product_name from products 

			
			where products.product_status=1 and $condition ";
	
		$data['empData'] = $this->db->query($getAllEmployeeQry)->result();

		$locatorQry = "select * from inv_item_sub_inventory where inventory_status=1 and warehouse_id='".$warehouse_id."' ";
		$data['locator'] = $this->db->query($locatorQry)->result();

		echo json_encode($data);
		exit;
	}
	
	public function productLotNoCheck()
	{
		if ( isset($_POST['product_lot_check']) && $_POST['product_lot_check'] == 1) 
		{
			$product_lot_last_number = $_POST['product_lot_last_number'];
			
			$results = $this->db->query("select product_id from products WHERE product_lot_last_number='".$product_lot_last_number."' ")->result_array(); #and register_type='".$register_type."'
			
			if ( count($results) > 0 ) {
				echo "taken";	
			}else{
				echo 'not_taken';
			}
			exit();
		}
	}

	// public function selectOrgBranchItems() 
	// {
    //     $organization_id = $_POST["organization_id"];	
    //     $branch_id = $_POST["branch_id"];	

	// 	if($organization_id && $branch_id)
	// 	{			
	// 		$result = $this->products_model->getOrgBranchItems($organization_id,$branch_id);
	
	// 		if( count($result) > 0)
	// 		{
	// 			echo '<option value="0">- Select -</option>';
	// 			foreach($result as $val)
	// 			{
	// 				echo '<option value="'.$val['item_id'].'">'.$val['item_name'].'</option>';
	// 			}
	// 		}
	// 		else
	// 		{
	// 			echo '<option value="">- Select -</option>';
	// 		}
	// 	}
	// 	die;
    // }
	
}
?>
