<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Stock_adjustment extends CI_Controller 
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
	
	function stockAdjustment($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['stockAdjustment'] = 1;

		$page_data['page_name']  = 'stock_adjustment/stockAdjustment';
		$page_data['page_title'] = 'Physical Stock Adjustment';
		
		switch(true)
		{
			case ($type == "add"):
				if($_POST)
				{
					
					$adj_date=date('Y-m-d',strtotime($_POST["adj_date"]));

					$headerData = array(
						
						"adj_date" 	  		 	 =>  $adj_date,
						"remarks" 			     =>  $this->input->post('remarks'),
						"created_by" 	  		 =>  $this->user_id,
						"created_date" 	  		 =>  $this->date_time,
						"last_updated_by" 	  	 =>  $this->user_id,
						"last_updated_date" 	 =>  $this->date_time
					);

					$this->db->insert('inv_adjustment_header',$headerData);
					$header_id = $this->db->insert_id();
					
					if($header_id)
					{
						$getDocumentData=$this->common_model->documentNumber('PHYSICAL-STOCK-ADJUSTMENT');
							
						$prefixName = isset($getDocumentData[0]['prefix_name']) ? $getDocumentData[0]['prefix_name'] : NULL;
						$startingNumber = isset($getDocumentData[0]['next_number']) ? $getDocumentData[0]['next_number'] : NULL;
						$suffixName = isset($getDocumentData[0]['suffix_name']) ? $getDocumentData[0]['suffix_name'] : NULL;
						$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
						$updateDocNum = array(
							"adj_number" 	  		 =>  $documentNumber,
							"last_updated_by" 	  	 =>  $this->user_id,
							"last_updated_date" 	 =>  $this->date_time
						);
						$this->db->where('header_id', $header_id);
						$headerTbl1 = $this->db->update('inv_adjustment_header',$updateDocNum);

						#Update Next Val DOC Number tbl start
						$str_len = strlen($startingNumber);
						$nextValue1 = $startingNumber + 1;
						$nextValue = str_pad($nextValue1,$str_len,"0",STR_PAD_LEFT);
						$doc_num_id = isset($getDocumentData[0]['doc_num_id']) ? $getDocumentData[0]['doc_num_id']:"";
						
						$UpdateData['next_number'] = $nextValue;
						$this->db->where('doc_num_id', $doc_num_id);
						$resultUpdateData = $this->db->update('doc_document_numbering', $UpdateData);
						#Update Next Val DOC Number tbl end

						#Line Data start
						if(isset($_POST['quantity']))
						{
							$count = count(array_filter($_POST['quantity']));

							for($dp=0;$dp<$count;$dp++)
							{
								$uom_id 			= !empty($_POST['uom_id'][$dp]) ? $_POST['uom_id'][$dp] : NULL;

								$quantity 			= !empty($_POST['quantity'][$dp]) ? $_POST['quantity'][$dp] : NULL;

								$item_id 			= !empty($_POST['item_id'][$dp]) ? $_POST['item_id'][$dp] : NULL;
								
								$organization_id 	= !empty($_POST['organization_id'][$dp]) ? $_POST['organization_id'][$dp] : NULL;

								$sub_inventory_id 	= !empty($_POST['sub_inventory_id'][$dp]) ? $_POST['sub_inventory_id'][$dp] : NULL;

								$locator_id 		= !empty($_POST['locator_id'][$dp]) ? $_POST['locator_id'][$dp] : NULL;
								
								$lot_number 		= !empty($_POST['lot_number'][$dp]) ? $_POST['lot_number'][$dp] : NULL;

									
								
								$lineData = array(
									"header_id" 		 	=> $header_id,
									"line_num" 			 	=> !empty($_POST['line_num'][$dp]) ? $_POST['line_num'][$dp] : NULL,
									"item_id" 			 	=> $item_id,
									"uom_id" 				=> $uom_id,
									"quantity" 				=> $quantity,
									"organization_id" 		=> $organization_id,
									"sub_inventory_id" 		=> $sub_inventory_id,
									"locator_id" 		    => $locator_id,
									"lot_number" 		    => $lot_number,
									"serial_number" 		=> !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
									"reason" 		        => !empty($_POST['reason'][$dp]) ? $_POST['reason'][$dp] : NULL,
									"created_by" 	  		=> $this->user_id,
									"created_date" 	  		=> $this->date_time,
									"last_updated_by" 	  	=> $this->user_id,
									"last_updated_date" 	=> $this->date_time
								);

								$this->db->insert('inv_adjustment_line', $lineData);
								$line_id = $this->db->insert_id();
								

								#Insert Transaction data start here
								
								$invTrnData = array(
									"transaction_type" 	 	=> "ADJ",
									"item_id" 			 	=> $item_id,
									"organization_id" 		=> $organization_id,
									"sub_inventory_id" 		=> $sub_inventory_id,
									"locator_id" 		 	=> $locator_id,
									"lot_number" 		 	=> $lot_number,
									#"serial_number" 		=> !empty($_POST['serial_number'][$dp]) ? $_POST['serial_number'][$dp] : NULL,
									"transaction_qty" 		=> $quantity,
									"uom" 					=> $uom_id,
									"adj_header_id" 	 	=> $header_id,
									"adj_line_id" 	      	=> $line_id,
									"transaction_date" 	  	=> $this->date_time,
									"created_by" 	  		=> $this->user_id,
									"created_date" 	  		=> $this->date_time,
									"last_updated_by" 	  	=> $this->user_id,
									"last_updated_date" 	=> $this->date_time
								);
								$this->db->insert('inv_transactions', $invTrnData);
								$trnsId = $this->db->insert_id();
								#Insert Transaction data end here
							}
						}
						#Line Data end
						
						if(isset($_POST["save_btn"]))
						{
							$this->session->set_flashdata('flash_message' , "Stock adjustment successfully!");
							redirect(base_url() . 'stock_adjustment/stockAdjustment/view/'.$header_id, 'refresh');
						}
						else if(isset($_POST["submit_btn"])) {
							$this->session->set_flashdata('flash_message' , "Stock adjustment Submitted Successfully!");
							redirect(base_url() . 'stock_adjustment/stockAdjustment', 'refresh');
						}
						
					}
				}
			break;

			case ($type == "edit" || $type == "view"):

				$result = $this->stock_adjustment_model->getViewData($id);
				$page_data['headerData'] = $result['headerData'];
				$page_data['lineData'] = $result['lineData'];

				
			break;
			
			default : #Manage
				$totalResult["header_data"] = $this->stock_adjustment_model->getStockAdjustment("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}

				$adj_number 	= isset($_GET['adj_number']) ? $_GET['adj_number'] :NULL;
				$adj_number_id 	= isset($_GET['adj_number_id']) ? $_GET['adj_number_id'] :NULL;
				$from_date 		= isset($_GET['from_date']) ? $_GET['from_date'] :NULL;
				$to_date 		= isset($_GET['to_date']) ? $_GET['to_date'] :NULL;

				$this->redirectURL = 'stock_adjustment/stockAdjustment?adj_number='.$adj_number.'&adj_number_id='.$adj_number_id.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if ($adj_number != NULL || $adj_number_id || $from_date != NULL || $to_date != NULL) {
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
				
				$result = $this->stock_adjustment_model->getStockAdjustment($limit, $offset, $this->pageCount);
				
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
					header("Content-Disposition: attachment; filename=\"stock_adjustment_".$date.".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");

					$handle = fopen('php://output', 'w');
					fputcsv($handle, array("S.No","Organization Name","Adjustment Number","Adjustment Date","Remarks","Item","UOM","Sub Inventory","Locator","Lot Number","Quantity","Reason"));
					$cnt=1;
					foreach ($lineData as $row) 
					{
						$narray=array(
							$cnt,
							$row["organization_name"],
							$row["adj_number"],
							date(DATE_FORMAT,strtotime($row['adj_date'])),
							$row['remarks'],
							$row['item_name'],
							$row['uom'],
							$row['inventory_code'],
							$row['locator_no'],
							$row['lot_number'],
							$row['quantity'],
							$row['reason'],

						);
						fputcsv($handle, $narray);
						$cnt++;
					}
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

	
	function getItemList(){
	
		$result = $this->common_model->lov("PERIOD");;

	
		if( count($result) > 0)
		{
			echo '<option value="">- Select -</option>';
			foreach($result as $val)
			{
				echo '<option value="'.$val['list_code'].'">'.ucfirst($val['list_value']).'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
	}

	public function getLineDatas()
	{
		
		/* $itemQuery = "select
			transaction.transaction_id,
			sum(transaction.transaction_qty) as trans_qty,
			products.product_id as item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.lot_number,
			transaction.serial_number,
			products.product_code as item_name,
			products.product_name as item_description,
			category.category_name,
			sub_inventory.inventory_code,
			sub_inventory.inventory_name,
			item_locators.locator_no,
			item_locators.locator_name

			from inv_transactions as transaction
			left join products on products.product_id = transaction.item_id
			left join category on category.category_id = products.category_id
			left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = transaction.sub_inventory_id
			left join inv_item_locators as item_locators on item_locators.locator_id = transaction.locator_id
			group by 
			transaction.item_id,
			transaction.organization_id,
			transaction.sub_inventory_id,
			transaction.locator_id,
			transaction.lot_number,
			transaction.serial_number
			
			HAVING trans_qty > 0 "; */

			
		/* $itemQuery = "select 
			products.product_id as item_id,
			products.product_code as item_name,
			products.product_name as item_description

			from products where product_status=1 order by item_name asc"; */

		$itemQuery = "select 
			locator_line_tbl.header_id,
			locator_line_tbl.line_id,
			items.item_id,
			items.item_name,
			items.item_description

			from inv_assign_product_locator_line as locator_line_tbl


			left join inv_sys_items as items on 
			items.item_id = locator_line_tbl.product_id

			where 1=1
			
			and items.active_flag='Y'
			and locator_line_tbl.assign_line_status=1
			";
		
		$data['items'] = $this->db->query($itemQuery)->result_array();

		#$data['discount'] = $this->db->query("select discount_id,discount_name from discount where active_flag='Y'")->result();
		
		/* $taxQry = "select tax_id,tax_name,tax_value from gen_tax 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['tax'] = $this->db->query($taxQry)->result_array(); */
		
		/* $uomQry = "select uom_id,uom_code,uom_description from uom 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['uom'] = $this->db->query($uomQry)->result_array(); */

		/* $discountType = [];

		foreach( $this->discount_type as $key => $value )
		{
			$discountType[] = array(
				'discount_type' =>  $value,
			);
		}
		$data['discount_type'] = $discountType;

		$organizationQry = "select organization_id,organization_name from org_organizations 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['organization'] = $this->db->query($organizationQry)->result_array();
		
		$requestedByQry = "select person_id,first_name,last_name from per_people_all 
			where active_flag='Y'
			";
		$data['requestedBy'] = $this->db->query($requestedByQry)->result_array();

		$subInvQry = "select inventory_id,inventory_code,inventory_name from inv_item_sub_inventory 
			where active_flag='Y'
			and coalesce(start_date,'".$this->date."') <= '".$this->date."'
			and coalesce(end_date,'".$this->date."') >= '".$this->date."'
			";
		$data['subInvQry'] = $this->db->query($subInvQry)->result_array(); */

	    echo json_encode($data);
		exit;
	}

	function ajaxItemList() {
		if(isset($_POST["query"])) {  

			
			$output = '';  
			$item_name = $_POST['query'];
			$counter = $_POST['counter'];
			
			$result = $this->stock_adjustment_model->getAjaxItemlist($item_name);
			
			$output = '<ul class="list-unstyled-item_id">';  
			
			if(count($result) > 0) {  
				foreach($result as $row) {  
					$item_name = $row["item_name"];
					$item_id = $row["item_id"];
					$item_description = $row['item_description'];
					$uom_id = $row['uom'];
					
					$output .= '<a><li onclick="return getItemList(\'' .$item_id. '\',\'' .$item_name. '\',\'' .$item_description. '\',\'' .$uom_id. '\');">'.$item_name.'</li></a>';

				}  
			} 
			else {  
				$item_name = "";
				$item_id = "";
				$item_description = '';
				$uom_id = '';
				
				$output .= '<li onclick="return getItemList(\'' .$item_id. '\',\'' .$item_name. '\',\'' .$item_description. '\', \'' .$uom_id. '\');">Sorry! Item Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}
	function ajaxUom() {
		if(isset($_POST["uom_id"])) {
			
			$uom_id = $_POST['uom_id'];
			$result = $this->stock_adjustment_model->getAjaxUom($uom_id);
			
			$output = '';
			if(count($result) > 0) {
				
				foreach($result as $row) {
					$uom_id = $row["uom_id"];
					$uom_code = $row["uom_code"];
					
					$output .= $uom_id . '@' . $uom_code;
				}
			}
			echo $output;
		} 
		else {
		
			echo "uom_id is not set";
		}
	}
	
	
	function ajaxTransQty() {
		if(isset($_POST["item_id"])) {
			$item_id = $_POST['item_id'];
			$result = $this->stock_adjustment_model->getAjaxTransQty($item_id);
			
			$output = '';
			if(count($result) > 0) {
				
				foreach($result as $row) {
					$transaction_id = $row["transaction_id"];
					$transaction_qty = $row["transaction_qty"];
					
					$output .= $transaction_id . '@' . $transaction_qty;
				}
			}
			echo $output;
		} 
		else {
		
			echo "Transcation Qty is not found";
		}
	}
	
	
	function ajaxOrganization() 
	{
		$result = $this->stock_adjustment_model->getAjaxOrganization();

		if( count($result) > 0)
		{
			echo '<option value="">- Select -</option>';
			foreach($result as $val)
			{
				echo '<option value="'.$val['organization_id'].'">'.ucfirst($val['organization_name']).'</option>';
			}
		}
		else
		{
			echo '<option value="">- Select -</option>';
		}
		
		die;
	}
	function ajaxselectSubInventory() 
	{
		if(isset($_POST["query"])) {  
			$organization_id=$_POST['query'];
			$result = $this->stock_adjustment_model->getAjaxSubInventory($organization_id);

			if( count($result) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($result as $val)
				{
					echo '<option value="'.$val['inventory_id'].'">'.$val['inventory_code'].'-'.ucfirst($val['inventory_name']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
			
			die;
		}
	}
	function ajaxSubInventoryLocators() 
	{
		if(isset($_POST["query"])) { 

			$inventory_id=$_POST['query'];

			$result = $this->stock_adjustment_model->getAjaxSubInventoryLocators($inventory_id);

			if( count($result) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($result as $val)
				{
					echo '<option value="'.$val['locator_id'].'">'.ucfirst($val['locator_no']).'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
			
			die;
		}
	}


	function ajaxAdjustmentNumberList() 
	{
		if(isset($_POST["query"]))  
		{  
			$output = '';  
			
			$adj_number = $_POST['query'];

			$result = $this->stock_adjustment_model->getAjaxAdjustNumberAll($adj_number);
			
			$output = '<ul class="list-unstyled-adj_number_id">';  
			
			if( count($result) > 0 )  
			{  
				foreach($result as $row)  
				{	
					$adj_number = $row["adj_number"];
					$adj_number_id = $row["header_id"];
					$output .= '<a><li onclick="return getAdjustNumberList(\'' .$adj_number_id. '\',\'' .$adj_number. '\');">'.$adj_number.'</li></a>';  
				}  
			}  
			else  
			{  
				$adj_number = "";
				$adj_number_id = "";
				
				$output .= '<li onclick="return getAdjustNumberList(\'' .$adj_number_id. '\',\'' .$adj_number. '\');">Sorry! Adjust Number Not Found.</li>';  
			}
			$output .= '</ul>';  
			echo $output;  
		}
	}

	public function ajaxSelectLineItemDetails()
	{
		$organization_id = isset($_POST["organization_id"]) ? $_POST["organization_id"] : NULL;
		$item_id = isset($_POST["item_id"]) ? $_POST["item_id"] : NULL;

		if($item_id !=NULL)
		{
			$itemQuery = "select 
			
			products.product_id as item_id,
			products.product_code as item_name,
			products.product_name as item_description,
            products.unit as uom_code,
            
            sub_inventory.inventory_code, 
            sub_inventory.inventory_name, 
            item_locators.locator_no, 
            item_locators.locator_name,
            locator_line_tbl.inventory_id,
            locator_line_tbl.locator_id

			from inv_assign_product_locator_line as locator_line_tbl

			left join inv_assign_product_locator_header as locator_header_tbl on 
				locator_header_tbl.header_id = locator_line_tbl.header_id

			left join products on 
				products.product_id = locator_line_tbl.product_id
                
                
            left join inv_item_sub_inventory as sub_inventory on sub_inventory.inventory_id = locator_line_tbl.inventory_id 
            left join inv_item_locators as item_locators on item_locators.locator_id = locator_line_tbl.locator_id 

			where 1=1
			and locator_header_tbl.warehouse_id='".$organization_id."'
            and locator_line_tbl.product_id='".$item_id."'
			and products.product_status=1
			and locator_line_tbl.assign_line_status=1";
			
			$data = $this->db->query($itemQuery)->result_array();

			echo json_encode($data);
		}exit;
	}

	public function ajaxSelectTransactionLot() 
	{
        $organization_id = $_POST["organization_id"];	
        $item_id = $_POST["item_id"];
        $sub_inventory_id = $_POST["sub_inventory_id"];
        $locator_id = $_POST["locator_id"];

		if($organization_id)
		{			
			$lotQry = "
			select sum(transaction.transaction_qty) as trans_qty, 
			transaction.lot_number
			from inv_transactions as transaction
			where 1=1
			and transaction.organization_id = '".$organization_id."'
			and transaction.item_id = '".$item_id."'
			and transaction.sub_inventory_id = '".$sub_inventory_id."'
			and transaction.locator_id = '".$locator_id."'
			group by 
			transaction.item_id, 
			transaction.organization_id, 
			transaction.sub_inventory_id, 
			transaction.locator_id, 
			transaction.lot_number
			";
			
			$data = $this->db->query($lotQry)->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				
				foreach($data as $val)
				{
					$lot_number = $val['lot_number']."@". $val['trans_qty'];

					echo '<option value="'.$lot_number.'">'.$val['lot_number'].'</option>';
				}
			}
			else
			{
				echo '<option value="">Lot Not Exists!</option>';
			}
		}
		die;
    }
}
?>
