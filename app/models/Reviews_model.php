<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reviews_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }


	function getReviews($offset="",$record="",$countType="")
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

			$review_type 		= !empty($_GET['review_type']) ? $_GET['review_type'] : NULL;


			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			$headerQry = "select 
			header_tbl.header_id,
			header_tbl.review_type,
			header_tbl.service_type,
			header_tbl.active_flag,
			line_tbl.review,
			line_tbl.image_path
			from reviews_headers as header_tbl
			left join reviews_lines as line_tbl on line_tbl.header_id=header_tbl.header_id
			where 1=1
			and header_tbl.review_type = coalesce(if('".$review_type."' = '',NULL,'".$review_type."'),header_tbl.review_type)
			and header_tbl.active_flag = if('".$active_flag."' = 'All',header_tbl.active_flag,'".$active_flag."')
			group by line_tbl.header_id
			order by header_tbl.header_id desc $limit" ;

			$result['headerData'] = $this->db->query($headerQry)->result_array();
		}
		else
		{
			$result['headerData'] = array();
		}
		return $result;
	}

	public function getViewData($id='')
	{
		$headerQry	="select 
		header_tbl.header_id,
		header_tbl.review_type,
		header_tbl.service_type,
		header_tbl.active_flag	
		from reviews_headers as header_tbl
		where 1=1
		and header_tbl.header_id='".$id."'";
		$result['editData'] = $this->db->query($headerQry)->result_array();

		$query ="select 
		line_tbl.line_id,
		line_tbl.header_id,
		line_tbl.customer_name,
		line_tbl.designation,	
		line_tbl.description,	
		line_tbl.review,	
		line_tbl.image_path,	
		line_tbl.active_flag	
		from reviews_lines as line_tbl
		where 1=1
		and line_tbl.header_id = '".$id."' " ;
		$result['lineData'] = $this->db->query($query)->result_array();
		return $result;
	}

	
	// function getReviewsData(){
	// 	$query="select 
	// 	reviews.review_id,
	// 	reviews.customer_name,
	// 	reviews.designation,
	// 	reviews.description,
	// 	reviews.review,
	// 	reviews.image_path,
	// 	reviews.active_flag
	// 	from reviews
	// 	where 1=1
	// 	and reviews.active_flag='".$this->active_flag."'
	// 	order by reviews.review_id desc";
	// 	$result = $this->db->query($query)->result_array();
	// 	return $result;
	// }


	function getReviewsData($service_type=''){
		$query="select 
		line_tbl.header_id,
		line_tbl.customer_name,
		line_tbl.designation,
		line_tbl.description,
		line_tbl.review,
		line_tbl.active_flag
		from reviews_lines as line_tbl
		left join reviews_headers as header_tbl on header_tbl.header_id=line_tbl.header_id
		where 1=1
		and header_tbl.review_type='SERVICE-REVIEW'
		and header_tbl.service_type='".$service_type."'
		and line_tbl.active_flag='".$this->active_flag."'
		and header_tbl.active_flag='".$this->active_flag."'
		
		order by header_tbl.header_id desc";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getCommonReviews(){
		$query="select 
		line_tbl.header_id,
		line_tbl.customer_name,
		line_tbl.designation,
		line_tbl.description,
		line_tbl.image_path,
		line_tbl.active_flag
		from reviews_lines as line_tbl
		left join reviews_headers as header_tbl on header_tbl.header_id=line_tbl.header_id
		where 1=1
		and header_tbl.review_type='COMMON-REVIEW'
		and line_tbl.active_flag='".$this->active_flag."'
		and header_tbl.active_flag='".$this->active_flag."'
		group by line_tbl.line_id
		having count(line_tbl.line_id) > 0
		order by header_tbl.header_id desc";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	
}
