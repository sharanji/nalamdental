<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Document_numbering_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageDocumentNumbering($offset="", $record="", $countType="")
	{
		if($_GET)
		{
			if($countType == 1) #GetTotalCount
			{
				$limit = "";
			}
			else if($countType == 2) #Get Page Wise Count
			{
				$limit = "limit ".$record." , ".$offset." "; 
			}

			
			if(empty($_GET['doc_type'])){
				$doc_type = 'NULL';
			}else{
				$doc_type = $_GET['doc_type'];
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}
			
			$query = "select 
				doc_document_numbering.*, 
				branch.branch_name, 
				sm_list_type_values.list_value from doc_document_numbering

			left join sm_list_type_values on sm_list_type_values.list_type_value_id = doc_document_numbering.doc_type
			left join branch on branch.branch_id = doc_document_numbering.branch_id
			where 1=1
			and doc_document_numbering.doc_type = coalesce($doc_type,doc_document_numbering.doc_type)
			and doc_document_numbering.active_flag = if('".$active_flag."' = 'All',doc_document_numbering.active_flag,'".$active_flag."')
			order by doc_document_numbering.doc_num_id DESC
			$limit ";

			$result = $this->db->query($query)->result_array();
			return $result;

		} 
		else
		{
			return array();
		}
	}
}
