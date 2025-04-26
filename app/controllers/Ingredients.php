<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ingredients extends CI_Controller 
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
	
	function ManageIngredients($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageProducts'] = 1;
		$page_data['page_name']  = 'ingredients/ManageIngredients';
		$page_data['page_title'] = 'Ingredients';
		
		switch(true)
		{
			case ($type =="add"):
				if($_POST)
				{
					$item_id = $_POST["product"];
					$headerData = array(
						"branch_id"    			=> $_POST["branch_id"],
						"item_id"             	=> $item_id,
						"active_flag"           => $this->active_flag,
						"created_by"            => $this->user_id,
						"created_date"          => $this->date_time,
						"last_updated_by"       => $this->user_id,
						"last_updated_date"     => $this->date_time,
					);

					$this->db->insert('inv_item_ingredient_header', $headerData);
					$id = $this->db->insert_id();

					if($id)
					{ 
						#Line Table start here
						if(isset($_POST['ingredient_name']))
						{
							$count = count(array_filter($_POST['ingredient_name']));

							if( $count > 0 )
							{
								for($dp=0;$dp<$count;$dp++)
								{
									$ingredient_name = isset($_POST['ingredient_name'][$dp]) ? $_POST['ingredient_name'][$dp] :NULL;
									$ingredient_description = isset($_POST['ingredient_description'][$dp]) ? $_POST['ingredient_description'][$dp] :NULL;
									$ingredient_cost = isset($_POST['ingredient_cost'][$dp]) ? $_POST['ingredient_cost'][$dp] :NULL;
									
									$active_flag = isset($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] :NULL;
									
									$itemingredientData =  array(
										'ing_header_id'          => $id,
										'item_id'                => $item_id,
										'ingredient_name'        => $ingredient_name,
										'ingredient_description' => $ingredient_description,
										'ingredient_cost'        => $ingredient_cost,
										'active_flag'            => $active_flag,

										'created_by'             => $this->user_id,
										'created_date'           => $this->date_time,
										'last_updated_by'        => $this->user_id,
										'last_updated_date'      => $this->date_time,
									);

									$this->db->insert('inv_item_ingredient_line', $itemingredientData);
									$id_1 = $this->db->insert_id();
								}
							}
						}
						$this->session->set_flashdata('flash_message' , "Item ingredient added successfully!");
						redirect(base_url() . 'ingredients/ManageIngredients/edit/'.$id, 'refresh');
					}	
				}
			break;
			
			case ($type =="edit" || $type =="view" ):
				$headerQry = 'select * from inv_item_ingredient_header where ing_header_id="'.$id.'"';
				$page_data['editData'] = $this->db->query($headerQry)->result_array();
				
				
				$lineQry = "select 
				inv_sys_items.item_name,
				inv_sys_items.item_description,
				inv_item_ingredient_line.ing_line_id,
				inv_item_ingredient_line.active_flag,
				inv_item_ingredient_line.ing_header_id,
				inv_item_ingredient_line.item_id,
				inv_item_ingredient_line.ingredient_name,
				inv_item_ingredient_line.ingredient_description,
				inv_item_ingredient_line.ingredient_cost
			
				from inv_item_ingredient_line 

				left join inv_sys_items on inv_item_ingredient_line.item_id = inv_sys_items.item_id

				where inv_item_ingredient_line.ing_header_id = '".$id."' ";

				$page_data['itemingredient'] = $this->db->query($lineQry)->result_array();

				if($_POST && $id)
				{
					$item_id = $_POST['product'];

					if(isset($_POST['ingredient_name']))
					{
						$itemsCount = count(array_filter($_POST['ingredient_name']));

						if($itemsCount > 0)
						{
							for($dp=0;$dp<$itemsCount;$dp++)
							{
								
								$ing_line_id = isset($_POST['ing_line_id'][$dp]) ? $_POST['ing_line_id'][$dp] :NULL;
								$branch_id = isset($_POST["branch_id"]) ? $_POST["branch_id"] : NULL;
	
								$chkExistQry = "select ing_line_id from inv_item_ingredient_line 
									where ing_line_id='".$ing_line_id."' ";

								$chkExist = $this->db->query($chkExistQry)->result_array();
	
								$ingredient_name = isset($_POST['ingredient_name'][$dp]) ? $_POST['ingredient_name'][$dp] :NULL;
								$ingredient_description = isset($_POST['ingredient_description'][$dp]) ? $_POST['ingredient_description'][$dp] :NULL;
								$ingredient_cost = isset($_POST['ingredient_cost'][$dp]) ? $_POST['ingredient_cost'][$dp] :NULL;
								
								$active_flag = isset($_POST['line_status'][$dp]) ? $_POST['line_status'][$dp] :NULL;
								
								if(count($chkExist) == 0) #Create
								{
									$lineData = array(
										'ing_header_id'          => $id,
										'item_id'                => $item_id,
										'ingredient_name'        => $ingredient_name,
										'ingredient_description' => $ingredient_description,
										'ingredient_cost'        => $ingredient_cost,
										
										"active_flag"           => $this->active_flag,
										"created_by"            => $this->user_id,
										"created_date"          => $this->date_time,
										"last_updated_by"       => $this->user_id,
										"last_updated_date"     => $this->date_time,
									);

									$this->db->insert('inv_item_ingredient_line',$lineData);
									$line_id = $this->db->insert_id();	
								}
								else #Update
								{
									$lineData = array(
										'ingredient_name'        => $ingredient_name,
										'ingredient_description' => $ingredient_description,
										'ingredient_cost'        => $ingredient_cost,
										
										"last_updated_by"        => $this->user_id,
										"last_updated_date"      => $this->date_time,
									);

									$this->db->where('ing_header_id',  $id);
									$this->db->where('ing_line_id',  $ing_line_id);
									$lineID = $this->db->update('inv_item_ingredient_line', $lineData);				
								}
							}
						}
					}
										
					$this->session->set_flashdata('flash_message' , "Item ingredient updated successfully!");
					redirect(base_url() . 'ingredients/ManageIngredients/edit/'.$id, 'refresh');
				}
			break;
			
			case ($type == "status"): #Block & Unblock
				if($status == "Y")
				{
					$data['active_flag'] = "Y";
					$data['inactive_date'] = NULL;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Item active successfully!';
				}
				else
				{
					$data['active_flag'] = "N";
					$data['inactive_date'] = $this->date_time;
					$data['last_updated_by'] = $this->user_id;
					$data['last_updated_date'] = $this->date_time;
					$succ_msg = 'Item inactive successfully!';
				}
				$this->db->where('ing_header_id', $id);
				$this->db->update('inv_item_ingredient_header', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER["HTTP_REFERER"], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->ingredients_item_model->getmanageIngredientsItems("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('ingredients/ManageIngredients?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('ingredients/ManageIngredients?keywords=');
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
				
				$page_data['resultData']  = $result = $this->ingredients_item_model->getmanageIngredientsItems($limit, $offset,$this->pageCount);
				
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
	
	public function getItems($item_id="",$branch_id="")
	{
		if( $item_id == 0 )
		{
			$query = "select 
			inv_sys_items.item_id,
			inv_sys_items.item_name,
			inv_sys_items.item_description 
			
			from inv_item_branch_assign

			join inv_sys_items on 
				inv_sys_items.item_id = inv_item_branch_assign.item_id

			where 
			inv_item_branch_assign.active_flag='Y' 
			AND inv_item_branch_assign.branch_id='".$branch_id."'
			order by item_description asc";

			$data['items'] = $this->db->query($query)->result();	
		}
		else
		{
			$query = "select 
			inv_sys_items.item_id,
			inv_sys_items.item_name,
			inv_sys_items.item_description 
			
			from inv_item_branch_assign

			join inv_sys_items on 
				inv_sys_items.item_id = inv_item_branch_assign.item_id

			where 
			inv_item_branch_assign.active_flag='Y'
			AND inv_item_branch_assign.branch_id='".$branch_id."'
			AND inv_item_branch_assign.item_id='".$item_id."' ";

			/* $query = "select item_id,item_name,item_description,item_cost from inv_sys_items 
			where active_flag='Y'
			and item_id='".$item_id."' "; */
			$data['items'] = $this->db->query($query)->result();
		}
		echo json_encode($data);
	}
	
	function ajaxAvailableIngredientsItems($type = '', $id = '', $status = '')
    {
		switch($type)
		{
			case "status": #Block & Unblock
				if($status == 1){
					$data['active_flag'] = 'Y';
					$succ_msg = 'Item ingredient available!';
				}else{
					$data['active_flag'] ='N';
					$succ_msg = 'Item ingredient unavailable!';
				}
				$this->db->where('ing_line_id', $id);
				$this->db->update('inv_item_ingredient_line', $data);
				echo $succ_msg;exit;
			break;
		}
	}

	public function ajaxSelectItems() 
	{		
		$branch_id = $_POST["id"];

		$query = "select 
		inv_sys_items.item_id,
		inv_sys_items.item_name,
		inv_sys_items.item_description 
		
		from inv_item_branch_assign

		join inv_sys_items on 
			inv_sys_items.item_id = inv_item_branch_assign.item_id

		where 
		inv_item_branch_assign.branch_id='".$branch_id."'
		and inv_item_branch_assign.active_flag='Y' 
		order by item_description asc";
		
		$data =  $this->db->query($query)->result_array();
	
		if( count($data) > 0)
		{
			echo '<option value="">- Select Item -</option>';
			/* echo '<option value="0">All Items</option>'; */
			foreach($data as $val)
			{
				echo '<option value="'.$val['item_id'].'">'.ucfirst($val['item_name']).' - '.ucfirst($val['item_description']).'</option>';
			}
		}
		else
		{
			echo '<option value="">No Items!</option>';
		}
		die;
    }
}
?>
