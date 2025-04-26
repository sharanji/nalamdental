<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Dine_in extends CI_Controller 
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
	
	#Dine In Tables Start here
	function dineInTables()
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'login.html', 'refresh');
		}

		$_SESSION["MODULE_ACCESS"] = 'dining_dashboard';

		$page_data['dining'] = 1;
		$page_data['page_name']  = 'dine_in/dineInTables';
		$page_data['page_title'] = 'Dine In';

		$page_data["item_list"] = $this->pos_model->getPosItems();

		$this->load->view($this->adminTemplate, $page_data);
	}

	function dineInOrder($table_id="",$interface_status="",$interface_header_id='')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'login.html', 'refresh');
		}

		$page_data['table_id'] = !empty($table_id) ? $table_id : NULL;
		$page_data['interface_status'] = !empty($interface_status) ? $interface_status : NULL;
		$page_data['interface_header_id'] = !empty($interface_header_id) ? $interface_header_id : NULL;

		#Dine In Tables start
		if($interface_header_id > 0)
		{
			$tablesQry = "select 
			header_tbl.interface_status,
			header_tbl.interface_header_id,
			header_tbl.sub_table,
			tbl_line_tbl.table_code,
			CONCAT(tbl_line_tbl.table_code,coalesce(header_tbl.sub_table,'')) as sub_table_code,
			header_tbl.customer_id,
			customer.customer_name,
			customer.mobile_number,
			customer.address1,
			line_tbl.offer_percentage

			from ord_order_interface_headers as header_tbl
			left join ord_order_interface_lines as line_tbl on line_tbl.reference_header_id = header_tbl.interface_header_id


			left join din_table_lines as tbl_line_tbl on tbl_line_tbl.line_id = header_tbl.table_id
			
			left join cus_consumers as customer on 
			customer.customer_id = header_tbl.customer_id

			where 1=1
			and header_tbl.order_source = 'DINE_IN'
			and header_tbl.interface_status != 'Success'
			and header_tbl.table_id = '".$table_id."'
			and header_tbl.interface_header_id = '".$interface_header_id."'
			group by header_tbl.table_id,header_tbl.sub_table
			";
		}
		else
		{
			$tablesQry = "select table_code as sub_table_code from din_table_lines where line_id='".$table_id."' ";
		}

		$page_data['table_data'] = $tables = $this->db->query($tablesQry)->result_array();
		#Dine In Tables end

		#Dine In list start
		$dineInOrderQry = "select 
		line_tbl.*,
		(line_tbl.price * line_tbl.quantity) as line_total,
		item.item_name,
		item.item_description
		
		from ord_order_interface_lines as line_tbl 

		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id

		left join inv_sys_items as item on
		item.item_id = line_tbl.product_id

		where 1=1 
		and header_tbl.table_id='".$table_id."' 
		and header_tbl.interface_status='".$interface_status."'
		and header_tbl.interface_header_id='".$interface_header_id."'
		";

		$page_data['dineInOrders'] = $dineInOrders = $this->db->query($dineInOrderQry)->result_array();
		#Dine In list end

		$page_data['pos'] = 1;
		$page_data['page_name']  = 'pos/posOrder';
		$page_data['page_title'] = 'Dine In Order';
		$page_data["item_list"] = $this->pos_model->getPosItems();
		$this->load->view($this->adminTemplate, $page_data);
	}

	function manageDineInOrders($type = '', $id = '', $status = '', $status_1 = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'login.html', 'refresh');
		}
		
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['dineIn'] = $page_data['manage_dine_in'] = 1;

		$page_data['page_name']  = 'dine_in/manageDineInOrders';
		$page_data['page_title'] = 'Dine In Orders';

		switch(true)
		{
			case ($type == "payment_update"): #Update Payment Status	
				$data=array(
					'payment_due' 		=> 'Paid',
					'last_updated_by'	=> $this->user_id,
					'last_updated_date' => $this->date_time,
				);
				$succ_msg = 'Payment status updated successfully!';
	
				$this->db->where('header_id', $id);
				$this->db->update('ord_order_headers', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect($_SERVER['HTTP_REFERER'], 'refresh');
			break;

			default : #Manage
				$totalResult = $this->dine_in_model->getManageDineInOrders("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult['totalCount']);

				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : NULL;

				$table_location_id = isset($_GET['table_location_id']) ? $_GET['table_location_id'] : NULL;
				$table_id = isset($_GET['tables']) ? $_GET['tables'] : NULL;

				$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : NULL;
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : NULL;
				$to_date = isset($_GET['from_date']) ? $_GET['to_date'] : NULL;

				$redirectURL = 'dine_in/manageDineInOrders?branch_id='.$branch_id.'&table_location_id='.$table_location_id.'&tables='.$table_id.'&order_number='.$order_number.'&from_date='.$from_date.'&to_date='.$to_date.'';
				
				if ( $branch_id !=NULL || $table_location_id !=NULL || $table_id !=NULL || $order_number !=NULL || $from_date !=NULL || $to_date !=NULL) {
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
				
				$result = $this->dine_in_model->getManageDineInOrders($limit,$offset,$this->pageCount);
				$page_data['resultData'] = $result["listing"];

				#show start and ending Count
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result["listing"]) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}

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
			break;
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}

	# Ajax  Change
	public function ajaxSelectTableLocations() 
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'login.html', 'refresh');
		}

        $id = $_POST["id"];

		if($id)
		{			
			$data =  $this->db->query("select 
				sm_list_type_values.list_type_value_id,
				sm_list_type_values.list_value from din_table_headers

				left join sm_list_type_values on
				sm_list_type_values.list_type_value_id = din_table_headers.table_location_id

				where din_table_headers.branch_id='".$id."'
				")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['list_type_value_id'].'">'.$val['list_value'].'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }

	# Ajax  Change
	public function ajaxSelectLocationTables() 
	{
		if (empty($this->user_id))
        {
			redirect(base_url() . 'login.html', 'refresh');
		}

        $branch_id = $_POST["branch_id"];
        $table_location_id = $_POST["table_location_id"];
		
		if( $branch_id && $table_location_id )
		{			
			$data =  $this->db->query("select 
				line_tbl.line_id,
				line_tbl.table_name
				
				from din_table_lines as line_tbl
				
				left join din_table_headers as header_tbl on
					header_tbl.header_id = line_tbl.header_id
				
				where 1=1
				and header_tbl.branch_id='".$branch_id."'
				and header_tbl.table_location_id='".$table_location_id."'
				and line_tbl.active_flag='Y'
				")->result_array();
		
			if( count($data) > 0)
			{
				echo '<option value="">- Select -</option>';
				foreach($data as $val)
				{
					echo '<option value="'.$val['line_id'].'">'.$val['table_name'].'</option>';
				}
			}
			else
			{
				echo '<option value="">- Select -</option>';
			}
		}
		die;
    }

	function ajaxCheckOpenOrders()
	{
		$table_id = isset($_POST["table_id"]) ? $_POST["table_id"] :  NULL;
		
		if($table_id != NULL)
		{
			$checkOrders = "select 
			header_tbl.interface_header_id,
			header_tbl.sub_table,
			tbl_line.table_name,
			tbl_line.table_code

			from ord_order_interface_headers as header_tbl
			left join din_table_lines as tbl_line on
			tbl_line.line_id = header_tbl.table_id
			where 1=1
			and header_tbl.order_source = 'DINE_IN'
			and header_tbl.interface_status != 'Success'
			and header_tbl.table_id = '".$table_id."'

			ORDER BY header_tbl.sub_table desc limit 0,1
			";

			$data['getOpenOrders'] = $getOpenOrders = $this->db->query($checkOrders)->result_array();


			$sub_table = isset($getOpenOrders[0]["sub_table"]) ? $getOpenOrders[0]["sub_table"] : NULL;
			$table_code = isset($getOpenOrders[0]["table_code"]) ? $getOpenOrders[0]["table_code"] : NULL;

			if($sub_table == NULL)
			{
				$data['next_sub_table'] = NULL;
			}
			else
			{
				$data['sub_table_code'] = $sub_table_code = nextLetter($sub_table);
				$data['next_sub_table'] = $table_code.$sub_table_code;
			}

	    	echo json_encode($data);
		}exit;
	}
	#Dining End here
}


?>
