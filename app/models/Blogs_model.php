<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blogs_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }
	
	function getBlogs($offset="",$record="",$countType="")
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

			if(empty($_GET['keywords'])){
				$keywords = 'NULL';
			}else{
				$keywords = "concat('%','".serchFilter($_GET['keywords'])."','%')";
			}

			$blog_category 		= !empty($_GET['blog_category']) ? $_GET['blog_category'] : NULL;

			$active_flag 		= !empty($_GET['active_flag']) ? $_GET['active_flag'] : 'NULL';
			

			$query = "select 
			blogs.blog_id,
			blogs.blog_title,
			blogs.description,
			blogs.short_description,
			blogs.blog_category,
			blogs.blog_tags,
			blogs.active_flag,
			ltv.list_value
			from blogs
			left join sm_list_type_values as ltv on ltv.list_code = blogs.blog_category
			where 1=1
			and	blogs.blog_title like coalesce($keywords,blogs.blog_title)
			and blogs.blog_category = coalesce(if('".$blog_category."' = '',NULL,'".$blog_category."'),blogs.blog_category)
			and blogs.active_flag = if('".$active_flag."' = 'All',blogs.active_flag,'".$active_flag."')
			order by blogs.blog_id desc $limit" ;
			
			$result = $this->db->query($query)->result_array();
		}
		else
		{
			$result = array();
			
		}
		return $result;
	}

	function checkBlogsExist($blog_title='',$blog_category='',$type='',$id='')
	{
		if($type==='add')
		{
			$condition=' 1=1';
		}
		else
		{
			$condition=" blogs.blog_id!='".$id."'";
		}

		$query="select blogs.blog_id from blogs
				where 1=1 
				and blog_title='".$blog_title."'
				and blog_category='".$blog_category."'
				and $condition";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getViewData($id='')
	{
		$query = "select 
		blogs.blog_id,
		blogs.blog_title,
		blogs.client_name,
		blogs.description,
		blogs.short_description,
		blogs.editor_images,
		blogs.blog_category,
		blogs.best_blog,
		blogs.blog_tags,
		blogs.active_flag
		from blogs
		where 1=1
		and blogs.blog_id='".$id."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getBlogsAll($category_name='')
	{
		$query="select
		blogs.blog_id,
		blogs.blog_title,
		blogs.likes,
		blogs.short_description,
		blogs.description,
		blogs.blog_category,
		blogs.blog_tags,
		blogs.active_flag,
		blogs.created_date,
		ltv.list_code
		from blogs
		left join sm_list_type_values as ltv on ltv.list_code = blogs.blog_category
		where 1=1 
		and blogs.blog_category='".$category_name."'
		and blogs.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getBestBlogs()
	{
		$query="select
		blogs.blog_id,
		blogs.blog_title,
		blogs.short_description,
		blogs.description,
		blogs.blog_category,
		blogs.best_blog,
		blogs.blog_tags,
		blogs.active_flag,
		blogs.created_date,
		ltv.list_code
		from blogs
		left join sm_list_type_values as ltv on ltv.list_code = blogs.blog_category
		where 1=1 
		and blogs.best_blog='Y'
		and blogs.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getRelatedBlog($blog_id='',$blog_category='')
	{
		$query="select
		blogs.blog_id,
		blogs.blog_title,
		blogs.short_description,
		blogs.description,
		blogs.blog_category,
		blogs.best_blog,
		blogs.blog_tags,
		blogs.active_flag,
		blogs.created_date,
		ltv.list_code
		from blogs
		left join sm_list_type_values as ltv on ltv.list_code = blogs.blog_category
		where 1=1 
		and blogs.blog_id!='".$blog_id."'
		and blogs.blog_category='".$blog_category."'
		and blogs.active_flag='".$this->active_flag."'
		order by blogs.blog_id desc";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getCategoryBlogs($blog_type='')
	{
		$query="select
		blogs.blog_id,
		blogs.blog_title,
		blogs.description,
		blogs.short_description,
		blogs.blog_category,
		blogs.best_blog,
		blogs.blog_tags,
		blogs.active_flag,
		blogs.created_date,
		ltv.list_code
		from blogs
		left join sm_list_type_values as ltv on ltv.list_code = blogs.blog_category
		where 1=1 
		and blogs.blog_category='".$blog_type."'
		and blogs.active_flag='".$this->active_flag."'
		order by blogs.blog_id desc";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getBlogDetails($blog_id='',$blog_category='')
	{
		$query="select
		blogs.blog_id,
		blogs.blog_title,
		blogs.description,
		blogs.short_description,
		blogs.client_name,
		blogs.blog_category,
		blogs.best_blog,
		blogs.blog_tags,
		blogs.active_flag,
		blogs.created_date,
		ltv.list_code,
		ltv.list_value
		from blogs
		left join sm_list_type_values as ltv on ltv.list_code = blogs.blog_category
		where 1=1 
		and blogs.blog_id='".$blog_id."'
		and blogs.blog_category='".$blog_category."'
		and blogs.active_flag='".$this->active_flag."'
		order by blogs.blog_id desc";
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	public function likeExist($blog_id = '', $ip_address = '')
	{
		$query = "SELECT blog_like_id 
		FROM blog_likes 
		WHERE 1=1
		and ip_address = '".$ip_address."' 
		and blog_id = '".$blog_id."'";
		$result = $this->db->query($query)->result_array();

		return $result;
	}
	public function blogLike($blog_id = '', $ip_address = '')
	{
		$query = "SELECT blog_like_id,likes 
		FROM blog_likes 
		WHERE 1=1
		and ip_address = '".$ip_address."' 
		and blog_id = '".$blog_id."'";
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	public function likeCount($blog_id = '')
	{
		$query = "select coalesce(sum(blogs.likes), 0) as like_count from blog_likes as  blogs
		where 1=1
		and blogs.blog_id = '".$blog_id."'
		group by blogs.blog_id
		having like_count>0";
		$result = $this->db->query($query)->result_array(); 
		return $result;
	}

	public function viewsExist($blog_id = '', $ip_address = '')
	{
		$query = "SELECT blog_view_id,views 
		FROM blog_views 
		WHERE 1=1
		and ip_address = '".$ip_address."' 
		and blog_id = '".$blog_id."'";
		$result = $this->db->query($query)->result_array();

		return $result;
	}

	public function viewsCount($blog_id = '')
	{
		$query = "select coalesce(sum(blogs.views), 0) as view_count from blog_views as blogs 
		where 1=1
		and blogs.blog_id = '".$blog_id."'
		group by blogs.blog_id";
		$result = $this->db->query($query)->result_array(); 

		
		return $result;
	}

	public function getComments($blog_id = '',$blog_category='',$comment_limit='')
	{
		$query = "select comment_id,
		client_name,
		email,
		message,
		created_date
		from blog_comments as blogs 
		where 1=1
		and blogs.blog_id = '".$blog_id."'
		and blogs.blog_category = '".$blog_category."'
		limit $comment_limit";
		$result = $this->db->query($query)->result_array(); 
		return $result;
	}

	public function blogDetailsLikeCount($blog_id = '')
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		$query = "select coalesce(sum(blogs.likes), 0) as like_count from blog_likes as  blogs
		where 1=1
		and blogs.blog_id = '".$blog_id."'
		and blogs.ip_address = '".$ip_address."'
		group by blogs.blog_id
		having like_count>0";
		$result = $this->db->query($query)->result_array(); 
		return $result;
	}

	



}
