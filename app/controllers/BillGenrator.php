<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*------------------------------
#  Author  :   Suresh M
#  Date    :   11 - Nove - 2023
#  Jesperapps Software Services
--------------------------------*/

class BillGenrator extends CI_Controller 
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
	
	#get Not Printed order ID
	function getOrderID()
    {
    	if( $this->user_id==1 ) #Admin
		{
			$condition ="1=1 and header_tbl.print_status = 'N'";
		}
		else if($this->user_id > 1) #Admin
		{
			$condition ="1=1 
			and header_tbl.print_status = 'N' 
			and branch_id='".$this->branch_id."'";
		}

		$orderQuery = "select 
		header_tbl.header_id
		from ord_order_headers as header_tbl
		left join branch on branch.branch_id = header_tbl.branch_id
		WHERE $condition 			
		";
		$orderResult = $this->db->query($orderQuery)->result_array();

		if (count($orderResult) > 0) 
		{
			$order_id = isset($orderResult[0]['header_id']) ? $orderResult[0]['header_id'] : 0;
			echo $order_id;
			exit;
		}
        else 
		{
            exit;
        }
	}
	
	#get html content view 
	function chkbill($order_id="")
    {
		#Cashier Bill
		if(file_exists("uploads/auto_generate_pdf/".$order_id.".pdf"))
		{
			$orderPDFPath = base_url()."uploads/auto_generate_pdf/".$order_id.".pdf";
		}
		else
		{
			$orderPDFPath='';
		}

		#KOT Print
		if(file_exists("uploads/auto_generate_pdf/kot/".$order_id.".pdf"))
		{
			$orderKOTPath = base_url()."uploads/auto_generate_pdf/kot/".$order_id.".pdf";
		}
		else
		{
			$orderKOTPath ='';
		}


		#Denomination Print
		if(file_exists("uploads/demomination_pdf/".$order_id.".pdf"))
		{
			$demominationPath = base_url()."uploads/demomination_pdf/".$order_id.".pdf";
		}
		else
		{
			$demominationPath ='';
		}

		if(isset($this->branch_id) && $this->branch_id > 0){
			$branch_id = $this->branch_id;
		}else{
			$branch_id = 1;
		}


		$branchPrintersQuery = "select header_id,branch_id from org_print_count_header 
		where 
		branch_id='".$branch_id."' AND 
		active_flag = 'Y' ";
		$getBranchPrinters = $this->db->query($branchPrintersQuery)->result_array();

		
		$header_id = isset($getBranchPrinters[0]["header_id"]) ? $getBranchPrinters[0]["header_id"] : 0;

		$printerLineQrykot = "select 
			org_print_section_types.print_type,
			org_print_section_types.type_name,
			org_print_count_line.printer_name,
			org_print_count_line.printer_count
			
			from org_print_count_line

			left join org_print_count_header on 
			org_print_count_header.header_id = org_print_count_line.header_id

			left join org_print_section_types on 
			org_print_section_types.type_id = org_print_count_line.type_id

			where 1=1
			and org_print_count_line.header_id='".$header_id."'  
			and org_print_count_line.branch_id='".$branch_id."' 
			and org_print_count_line.active_flag = 'Y' 
			and org_print_count_header.active_flag = 'Y' 
			and org_print_section_types.active_flag = 'Y'
			and org_print_section_types.print_type = 'KOT'
			";
		$getPrinterLinekot = $this->db->query($printerLineQrykot)->result_array();

		$data['print_itemskot'] = $getPrinterLinekot;


		$printerLineQry = "select 
		org_print_section_types.print_type,
		org_print_section_types.type_name,
		org_print_count_line.printer_name,
		org_print_count_line.printer_count
		
		from org_print_count_line

		left join org_print_count_header on 
		org_print_count_header.header_id = org_print_count_line.header_id

		left join org_print_section_types on 
		org_print_section_types.type_id = org_print_count_line.type_id

		where 1=1
		and org_print_count_line.header_id='".$header_id."'  
		and org_print_count_line.branch_id='".$branch_id."' 
		and org_print_count_line.active_flag = 'Y' 
		and org_print_count_header.active_flag = 'Y' 
		and org_print_section_types.active_flag = 'Y'
		and org_print_section_types.print_type = 'CASHIER'
		";
		$getPrinterLine = $this->db->query($printerLineQry)->result_array();

		$data['print_items'] = $getPrinterLine;


		$data['orderPDFPath'] = $orderPDFPath;
		$data['orderKOTPath'] = $orderKOTPath;
		$data['demominationPath'] = $demominationPath;
		
		echo json_encode($data);exit;
	}
	
	#Update Print Status
	function updateOrderStatus($header_id="")
	{
		$this->db->where("header_id",$header_id);
		$this->db->update("ord_order_headers",array("print_status"=>'Y'));
		
		#Unlink Order PDF After print start
		/*if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		*/
		#Unlink Order PDF After print end
		
		$this->getOrderID;
		echo 1;exit;
	}
	
	#Update KOT Print Status
	function updateKOTOrderStatus($header_id="")
	{
		$this->db->where("interface_header_id",$header_id);
		$this->db->update("ord_order_interface_headers",array("print_status"=>'Y'));

		$this->db->where("reference_header_id",$header_id);
		$this->db->update("ord_order_interface_lines",array("kot_print_status"=>'Y'));
		
		#Unlink Order PDF After print start
		/*if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		*/
		#Unlink Order PDF After print end
		#$this->getOrderID;
		echo 1;exit;
	}
	
	function updateOnlineOrderStatus($header_id="")
	{
		$this->db->where("header_id",$header_id);
		$this->db->update("ord_order_headers",array("print_status"=>'Y'));

		#Unlink Order PDF After print start
		/*if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		*/
		#Unlink Order PDF After print end
		#$this->getOrderID;
		echo 1;exit;
	}

#get Not Printed order ID
	function getDineInOrderID()
    {
       
    	if( $this->user_id==1 ) #Admin
		{
			$condition ="1=1 and header_tbl.print_status = 'N'";
		}
		else if($this->user_id > 1) #Admin
		{
			$condition ="1=1 
			and header_tbl.print_status = 'N' 
			and header_tbl.branch_id='".$this->branch_id."'";
		}

		$orderQuery = "select 
		header_tbl.interface_header_id as header_id
		from ord_order_interface_headers as header_tbl
		left join branch on branch.branch_id = header_tbl.branch_id
		WHERE $condition order by header_tbl.interface_header_id asc limit 0,1	
		";
    
		$orderResult = $this->db->query($orderQuery)->result_array();
		
		if (count($orderResult) > 0) 
		{
			$order_id = isset($orderResult[0]['header_id']) ? $orderResult[0]['header_id'] : 0;
			echo $order_id;
			exit;
		}
        else 
		{
            exit;
        }
	}

	
	
	function chkDineInbill($order_id="")
	{
		#get Order Seq Number start
		$getOrderQuery = "
		select 
		line_tbl.interface_line_id,
		line_tbl.order_seq_number 
		from ord_order_interface_lines as line_tbl
		left join ord_order_interface_headers as header_tbl on
		header_tbl.interface_header_id = line_tbl.reference_header_id
		where 1=1 
		and line_tbl.reference_header_id='".$order_id."'
		order by line_tbl.order_seq_number desc
		limit 0,1";
		$getOrderData = $this->db->query($getOrderQuery)->result_array();

		if(count($getOrderData) > 0)
		{	
			$order_seq_number = isset($getOrderData[0]["order_seq_number"]) ? $getOrderData[0]["order_seq_number"] : NULL;
		}
		else
		{

			$order_seq_number = 1;
		}
		#get Order Seq Number end

		
		#Cashier Bill
		if(file_exists("uploads/auto_generate_pdf/".$order_id.".pdf"))
		{
			$orderPDFPath = base_url()."uploads/auto_generate_pdf/".$order_id.".pdf";
		}
		else
		{
			$orderPDFPath='';
		}


		#KOT Print
		$kotOrder = $order_id."_".$order_seq_number;
		if(file_exists("uploads/auto_generate_pdf/kot/".$kotOrder.".pdf"))
		{
			$orderKOTPath = base_url()."uploads/auto_generate_pdf/kot/".$kotOrder.".pdf";
		}
		else
		{
			$orderKOTPath ='';
		}

		#Denomination Print
		if(file_exists("uploads/demomination_pdf/".$order_id.".pdf"))
		{
			$demominationPath = base_url()."uploads/demomination_pdf/".$order_id.".pdf";
		}
		else
		{
			$demominationPath ='';
		}

		if(isset($this->branch_id) && $this->branch_id > 0){
			$branch_id = $this->branch_id;
		}else{
			$branch_id = 1;
		}


		$branchPrintersQuery = "select header_id,branch_id from org_print_count_header 
		where 
		branch_id='".$branch_id."' AND 
		active_flag = 'Y' ";
		$getBranchPrinters = $this->db->query($branchPrintersQuery)->result_array();

		$header_id = isset($getBranchPrinters[0]["header_id"]) ? $getBranchPrinters[0]["header_id"] : 0;

		$printerLineQrykot = "select 
			org_print_section_types.print_type,
			org_print_section_types.type_name,
			org_print_count_line.printer_name,
			org_print_count_line.printer_count
			
			from org_print_count_line

			left join org_print_count_header on 
			org_print_count_header.header_id = org_print_count_line.header_id

			left join org_print_section_types on 
			org_print_section_types.type_id = org_print_count_line.type_id

			where 1=1
			and org_print_count_line.header_id='".$header_id."'  
			and org_print_count_line.branch_id='".$branch_id."' 
			and org_print_count_line.active_flag = 'Y' 
			and org_print_count_header.active_flag = 'Y' 
			and org_print_section_types.active_flag = 'Y'
			and org_print_section_types.print_type = 'KOT'
			";
		$getPrinterLinekot = $this->db->query($printerLineQrykot)->result_array();
		$data['print_itemskot'] = $getPrinterLinekot;


		$printerLineQry = "select 
			org_print_section_types.print_type,
			org_print_section_types.type_name,
			org_print_count_line.printer_name,
			org_print_count_line.printer_count
			
			from org_print_count_line

			left join org_print_count_header on 
			org_print_count_header.header_id = org_print_count_line.header_id

			left join org_print_section_types on 
			org_print_section_types.type_id = org_print_count_line.type_id

			where 1=1
			and org_print_count_line.header_id='".$header_id."'  
			and org_print_count_line.branch_id='".$branch_id."' 
			and org_print_count_line.active_flag = 'Y' 
			and org_print_count_header.active_flag = 'Y' 
			and org_print_section_types.active_flag = 'Y'
			and org_print_section_types.print_type = 'CASHIER'
			";
		$getPrinterLine = $this->db->query($printerLineQry)->result_array();

		$data['print_items'] = $getPrinterLine;

		$data['orderPDFPath'] = $orderPDFPath;
		$data['orderKOTPath'] = $orderKOTPath;
		$data['demominationPath'] = $demominationPath;
		
		echo json_encode($data);exit;
	}


	#Update KOT Print Status
	function updateCapOrdStatus($header_id="")
	{
		$this->db->where("interface_header_id",$header_id);
		$this->db->update("ord_order_interface_headers",array("print_status"=>'Y'));

		$this->db->where("reference_header_id",$header_id);
		$this->db->update("ord_order_interface_lines",array("kot_print_status"=>'Y'));
		
		#Unlink Order PDF After print start
		/*if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		}
		*/
		#Unlink Order PDF After print end
		//$this->getDineInOrderID;
		echo 1;exit;
	}

	function updateOnlineKOTStatus($header_id="")
	{
		$this->db->where("header_id",$header_id);
		$this->db->update("ord_order_headers",array("print_status"=>'Y'));

		/* $this->db->where("header_id",$header_id);
		$this->db->update("ord_order_lines",array("kot_print_status"=>'Y')); */
		
		#Unlink Order PDF After print start
		/* if(file_exists("uploads/auto_generate_pdf/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/".$header_id.".pdf");
		}
		if(file_exists("uploads/auto_generate_pdf/kot/".$header_id.".pdf"))
		{
			unlink("uploads/auto_generate_pdf/kot/".$header_id.".pdf");
		} */
		#Unlink Order PDF After print end
		#$this->getOrderID;
		echo 1;exit;
	}

	#get html content view 
	function chkCaptainOrder($order_id="",$order_seq_number='')
    {
		#Cashier Bill
		if(file_exists("uploads/auto_generate_pdf/".$order_id.".pdf"))
		{
			$orderPDFPath = base_url()."uploads/auto_generate_pdf/".$order_id.".pdf";
		}
		else
		{
			$orderPDFPath='';
		}

		#KOT Print
		$kotOrder = $order_id."_".$order_seq_number;

		if(file_exists("uploads/auto_generate_pdf/kot/".$kotOrder.".pdf"))
		{
			$orderKOTPath = base_url()."uploads/auto_generate_pdf/kot/".$kotOrder.".pdf";
		}
		else
		{
			$orderKOTPath ='';
		}


		#Denomination Print
		if(file_exists("uploads/demomination_pdf/".$order_id.".pdf"))
		{
			$demominationPath = base_url()."uploads/demomination_pdf/".$order_id.".pdf";
		}
		else
		{
			$demominationPath ='';
		}

		if(isset($this->branch_id) && $this->branch_id > 0){
			$branch_id = $this->branch_id;
		}else{
			$branch_id = 1;
		}


		$branchPrintersQuery = "select header_id,branch_id from org_print_count_header 
		where 
		branch_id='".$branch_id."' AND 
		active_flag = 'Y' ";
		$getBranchPrinters = $this->db->query($branchPrintersQuery)->result_array();

		
		$header_id = isset($getBranchPrinters[0]["header_id"]) ? $getBranchPrinters[0]["header_id"] : 0;

		$printerLineQrykot = "select 
			org_print_section_types.print_type,
			org_print_section_types.type_name,
			org_print_count_line.printer_name,
			org_print_count_line.printer_count
			
			from org_print_count_line

			left join org_print_count_header on 
			org_print_count_header.header_id = org_print_count_line.header_id

			left join org_print_section_types on 
			org_print_section_types.type_id = org_print_count_line.type_id

			where 1=1
			and org_print_count_line.header_id='".$header_id."'  
			and org_print_count_line.branch_id='".$branch_id."' 
			and org_print_count_line.active_flag = 'Y' 
			and org_print_count_header.active_flag = 'Y' 
			and org_print_section_types.active_flag = 'Y'
			and org_print_section_types.print_type = 'KOT'
			";
		$getPrinterLinekot = $this->db->query($printerLineQrykot)->result_array();

		$data['print_itemskot'] = $getPrinterLinekot;


		$printerLineQry = "select 
		org_print_section_types.print_type,
		org_print_section_types.type_name,
		org_print_count_line.printer_name,
		org_print_count_line.printer_count
		
		from org_print_count_line

		left join org_print_count_header on 
		org_print_count_header.header_id = org_print_count_line.header_id

		left join org_print_section_types on 
		org_print_section_types.type_id = org_print_count_line.type_id

		where 1=1
		and org_print_count_line.header_id='".$header_id."'  
		and org_print_count_line.branch_id='".$branch_id."' 
		and org_print_count_line.active_flag = 'Y' 
		and org_print_count_header.active_flag = 'Y' 
		and org_print_section_types.active_flag = 'Y'
		and org_print_section_types.print_type = 'CASHIER'
		";
		$getPrinterLine = $this->db->query($printerLineQry)->result_array();

		$data['print_items'] = $getPrinterLine;


		$data['orderPDFPath'] = $orderPDFPath;
		$data['orderKOTPath'] = $orderKOTPath;
		$data['demominationPath'] = $demominationPath;
		
		echo json_encode($data);exit;
	}
	

	/*
	function chkDineInbill($order_id="")
	{
		#Cashier Bill
		if(file_exists("uploads/auto_generate_pdf/".$order_id.".pdf"))
		{
			$orderPDFPath = base_url()."uploads/auto_generate_pdf/".$order_id.".pdf";
		}
		else
		{
			$orderPDFPath='';
		}

		#KOT Print
		if(file_exists("uploads/auto_generate_pdf/kot/".$order_id.".pdf"))
		{
			$orderKOTPath = base_url()."uploads/auto_generate_pdf/kot/".$order_id.".pdf";
		}
		else
		{
			$orderKOTPath ='';
		}


		#Denomination Print
		if(file_exists("uploads/demomination_pdf/".$order_id.".pdf"))
		{
			$demominationPath = base_url()."uploads/demomination_pdf/".$order_id.".pdf";
		}
		else
		{
			$demominationPath ='';
		}

		if(isset($this->branch_id) && $this->branch_id > 0){
			$branch_id = $this->branch_id;
		}else{
			$branch_id = 1;
		}


		$branchPrintersQuery = "select header_id,branch_id from org_print_count_header 
		where 
		branch_id='".$branch_id."' AND 
		active_flag = 'Y' ";
		$getBranchPrinters = $this->db->query($branchPrintersQuery)->result_array();

		
		$header_id = isset($getBranchPrinters[0]["header_id"]) ? $getBranchPrinters[0]["header_id"] : 0;

		$printerLineQry = "select 
			org_print_section_types.print_type,
			org_print_section_types.type_name,
			org_print_count_line.printer_name,
			org_print_count_line.printer_count
			
			from org_print_count_line

			left join org_print_count_header on 
			org_print_count_header.header_id = org_print_count_line.header_id

			left join org_print_section_types on 
			org_print_section_types.type_id = org_print_count_line.type_id

			where 1=1
			and org_print_count_line.header_id='".$header_id."'  
			and org_print_count_line.branch_id='".$branch_id."' 
			and org_print_count_line.active_flag = 'Y' 
			and org_print_count_header.active_flag = 'Y' 
			and org_print_section_types.active_flag = 'Y'
			";
		$getPrinterLine = $this->db->query($printerLineQry)->result_array();

		$data['print_items'] = $getPrinterLine;
		$data['orderPDFPath'] = $orderPDFPath;
		$data['orderKOTPath'] = $orderKOTPath;
		$data['demominationPath'] = $demominationPath;
		
		echo json_encode($data);exit;
	} */

	
	#Update Print Status
	/* function updatePrintJobStatus($order_id="")
	{
		$this->db->where("order_id",$order_id);
		$this->db->update("vb_order_header",array("print_job_status"=>1));
        echo 1;exit;
	} */
	#Controller End
}
?>
