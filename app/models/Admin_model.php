<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function adminLogin($email="",$password="")
	{
		$loginQry = "select 
		per_user.user_id,
		per_user.active_flag,
		per_people_all.organization_id,
		per_people_all.branch_id,
		per_user.last_login_status
			
		from per_user 
		left join per_people_all on 
		per_people_all.person_id = per_user.person_id
		where 1=1
		 
		and user_name ='".$email."'  
		and password='".md5($password)."'";

		$result = $this->db->query($loginQry)->result_array();
		#and last_login_status ='N'

		$userId = isset($result[0]['user_id']) ? $result[0]['user_id']:NULL;
			
		if(count($result) == 1)
		{
			$multiLoginAccess = MULTI_LOGIN_ACCESS;
			
			if( !empty($result[0]['active_flag']) && $result[0]['active_flag'] == "Y" )
			{
				if($multiLoginAccess == 'YES') #Login Access
				{
					$checkLoginUser = "select 
					per_user.user_id,
					per_user.active_flag,
					per_people_all.branch_id,
					per_user.last_login_status
						
					from per_user 
					left join per_people_all on 
					per_people_all.person_id = per_user.person_id
					where 1=1
					
					and per_user.user_id ='".$userId."'";
		
					$getLoginUser = $this->db->query($checkLoginUser)->result_array();

					if( $userId == 1 ) #Admin
					{
						$this->session->set_userdata('organization_id',$result[0]['organization_id']);
						$branch_id = isset($result[0]['branch_id']) ? $result[0]['branch_id'] :  NULL;
						$this->session->set_userdata('branch_id',$branch_id);
						$this->session->set_userdata('user_id',$result[0]['user_id']);

						$data = array(
							'last_login_status'  => 'Y',
							'last_login_date'    => $this->date_time,
						);

						$this->db->where('user_id', $result[0]['user_id']);
						$result = $this->db->update('per_user', $data);
						
						$data = array(
							'user_id'       	=> $userId,
							'created_by'    	=> $userId,
							'created_date'  	=> $this->date_time,
							'last_updated_by'   => $userId,
							'last_updated_date' => $this->date_time,
							'login_type' 		=> "INTERNAL-USER",
							'ip_address' 		=> $_SERVER['SERVER_ADDR'],
							'branch_id' 		=> $branch_id,
						);

						$this->db->insert('org_login_audits', $data);
						$id = $this->db->insert_id();
						return 10;
					}
					else if( !empty($getLoginUser[0]['last_login_status']) && $getLoginUser[0]['last_login_status'] == "N" ) #Users
					{
						$this->session->set_userdata('organization_id',$result[0]['organization_id']);
						$branch_id = isset($result[0]['branch_id']) ? $result[0]['branch_id'] :  NULL;
						$this->session->set_userdata('branch_id',$branch_id);
						$this->session->set_userdata('user_id',$result[0]['user_id']);

						$data = array(
							'last_login_status'  => 'Y',
							'last_login_date'    => $this->date_time,
						);

						$this->db->where('user_id', $result[0]['user_id']);
						$result = $this->db->update('per_user', $data);
						
						$data = array(
							'user_id'       	=> $userId,
							'created_by'    	=> $userId,
							'created_date'  	=> $this->date_time,
							'last_updated_by'   => $userId,
							'last_updated_date' => $this->date_time,
							'login_type' 		=> "INTERNAL-USER",
							'ip_address' 		=> $_SERVER['SERVER_ADDR'],
							'branch_id' 		=> $branch_id,
						);

						$this->db->insert('org_login_audits', $data);
						$id = $this->db->insert_id();
						return 10;
					}
					else
					{
						return 1;
					}
				}
				else if($multiLoginAccess =='NO')
				{
					$this->session->set_userdata('organization_id',$result[0]['organization_id']);
					$branch_id = isset($result[0]['branch_id']) ? $result[0]['branch_id'] :  NULL;
					$this->session->set_userdata('branch_id',$branch_id);
					$this->session->set_userdata('user_id',$result[0]['user_id']);

					$data = array(
						'last_login_date'    => $this->date_time,
					);

					$this->db->where('user_id', $result[0]['user_id']);
					$result = $this->db->update('per_user', $data);
					
					$data = array(
						'user_id'       	=> $userId,
						'created_by'    	=> $userId,
						'created_date'  	=> $this->date_time,
						'last_updated_by'   => $userId,
						'last_updated_date' => $this->date_time,
						'login_type' 		=> "INTERNAL-USER",
						'ip_address' 		=> $_SERVER['SERVER_ADDR'],
						'branch_id' 		=> $branch_id,
					);

					$this->db->insert('org_login_audits', $data);
					$id = $this->db->insert_id();
					return 10;
				}
			}
			return 9;
		}
		else
		{
			if($result == 0)
			{
				return 8;
			}
			return 0;
		}
	}
	
	function get_email_settings()
	{
		$result = $this->db->query("select * from email_settings where settings_id=1")->result_array();
		return $result;
	}	
	
	function getCMS()
	{
		$result = $this->db->query("select * from cms")->result_array();
		return $result;
	}
	
	function getManageCategoryCount()
	{
		$condition = " 1=1 and main_category_id =0"; #2=> Customer
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								category.category_name like "%'.($_GET['keywords']).'%" OR
								category.category_description like "%'.($_GET['keywords']).'%" OR
								inv_hsn_codes.hsn_code like "%'.($_GET['keywords']).'%" OR
								uom.uom_code like "%'.($_GET['keywords']).'%" OR
								category.product_prefix like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select 
					category.category_id
								
		from category

		left join inv_hsn_codes on 
			inv_hsn_codes.hsn_code_id = category.hsn_sac_code
		
		left join uom on 
			uom.uom_id = category.uom_id
		
		where $condition";
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageCategory($offset="",$record="")
	{
		$condition = " 1=1 and main_category_id =0"; #2=> Customer
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								category.category_name like "%'.($_GET['keywords']).'%" OR
								category.category_description like "%'.($_GET['keywords']).'%" OR
								inv_hsn_codes.hsn_code like "%'.($_GET['keywords']).'%" OR
								uom.uom_code like "%'.($_GET['keywords']).'%" OR
								category.product_prefix like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select 
		category.*,
		inv_hsn_codes.hsn_code,				
		uom.uom_code from category

		left join inv_hsn_codes on 
			inv_hsn_codes.hsn_code_id = category.hsn_sac_code
		
		left join uom on 
			uom.uom_id = category.uom_id
			
		where $condition
			order by category.category_id desc
				limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		
		return $result;
	}
	
	
	
	function getManageBranchCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								branch.branch_name like "%'.($_GET['keywords']).'%" or 
								branch.branch_code like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select branch_id from branch
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageBranch($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								branch.branch_name like "%'.($_GET['keywords']).'%" or 
								branch.branch_code like "%'.($_GET['keywords']).'%"
							)
							';
		}
		
		$query = "select * from branch
		where $condition
				order by branch_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getManageOffersCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							org_offers.offer_amount like "%'.($_GET['keywords']).'%" or 
							org_offers.offer_percentage like "%'.($_GET['keywords']).'%"
						)
						';
		}
		$query = "select offer_id from org_offers
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageOffers($offset="",$record="")
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							org_offers.offer_amount like "%'.($_GET['keywords']).'%" or 
							org_offers.offer_percentage like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select * from org_offers
		where $condition
				order by offer_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	
	function getManageMfrCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							mfr.mfr like "%'.($_GET['keywords']).'%" 
						)
						';
		}
		$query = "select mfr_id from mfr
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageMfr($offset="",$record="")
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							mfr.mfr like "%'.($_GET['keywords']).'%" 
						)
						';
		}
		
		$query = "select * from mfr
		where $condition
				order by mfr_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}


	function getBacupDatabasesCount()
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							backup_databases.backup_date like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		$query = "select backup_id from backup_databases
		
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getBacupDatabases($offset="",$record="")
	{
		$condition = " 1=1";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
							backup_databases.backup_date like "%'.($_GET['keywords']).'%"
						)
						';
		}
		
		
		$query = "select * from backup_databases
		
		where $condition
				order by backup_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function getDiscount()
	{
		$query = "select discount_id,discount_name,discount_value from discount where active_flag='Y' order by discount_value";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
	

	function getCashierName($user_id='')
	{
		$getuserNameQry = "select user_name from per_user where user_id = '".$user_id."' ";
		$getuserName = $this->db->query($getuserNameQry)->result_array();
		$userName = isset($getuserName[0]['user_name']) ? $getuserName[0]['user_name'] : NULL;
		return $userName;
	}

	function ManageBlogsData($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['ManageBlogsData'] = 1;
		$page_data['page_name']  = 'admin/ManageBlogsData';
		$page_data['page_title'] = 'Manage Blogs';
		
		switch($type)
		{
			case "add": #View
				if($_POST)
				{
					$data['blog_title'] = $this->input->post('blog_title');
					$data['blog_description'] = $this->input->post('blog_description');
					/* $data['detail_description'] = $this->input->post('detail_description'); */
					$data['category_id'] = $this->input->post('category_id');
					$data['blog_tags'] = $this->input->post('blog_tags');
					$data['posted_date'] = time();
					$data['blog_status'] = 1;
					
					$this->db->insert('blogs', $data);
					$id = $this->db->insert_id();
					
					if($id !="")
					{
						if( !empty($_FILES['blog_image']['name']) )
						{  
							$blog_image = $id.'.png';
							compressImage($_FILES['blog_image']['tmp_name'],'uploads/blogs/'.$blog_image,60);
							
						}
							
						$this->session->set_flashdata('flash_message' , "Blogs added Successfully!");
						redirect(base_url() . 'admin/ManageBlogsData/', 'refresh');
					}
				}
			break;
			
			case "edit": #edit
				$page_data['edit_data'] = $this->db->get_where('blogs', array('blog_id' => $id))
										->result_array();
				if($_POST)
				{
					$data['blog_title'] = $this->input->post('blog_title');
					$data['blog_description'] = $this->input->post('blog_description');
					/* $data['detail_description'] = $this->input->post('detail_description'); */
					$data['category_id'] = $this->input->post('category_id');
					$data['blog_tags'] = $this->input->post('blog_tags');
					
					$this->db->where('blog_id', $id);
					$result = $this->db->update('blogs', $data);
					
					if($result)
					{
						if( !empty($_FILES['blog_image']['name']) )
						{  
							$blog_image = $id.'.png';
							compressImage($_FILES['blog_image']['tmp_name'],'uploads/blogs/'.$blog_image,60);
						}
							
						$this->session->set_flashdata('flash_message' , "Blogs added Successfully!");
						redirect(base_url() . 'admin/ManageBlogsData/', 'refresh');
					}
				}
			break;
			
			case "delete": #Delete
				$this->db->where('blog_id', $id);
				$this->db->delete('blogs');
				
				if (file_exists('uploads/blogs/'.$id.'.png'))
				{
					unlink('uploads/blogs/'.$id.'.png');
				}
				
				$this->session->set_flashdata('flash_message' , "Blogs deleted successfully!");
				redirect(base_url() . 'admin/ManageBlogsData/', 'refresh');
			break;
			
			case "status": #Block & Unblock
				if($status == 1){
					$data['blog_status'] = 1;
					$succ_msg = 'Blog unblocked successfully!';
				}else{
					$data['blog_status'] = 0;
					$succ_msg = 'Blog blocked successfully!';
				}
				$this->db->where('blog_id', $id);
				$this->db->update('blogs', $data);
				$this->session->set_flashdata('flash_message' , $succ_msg);
				redirect(base_url() . 'admin/ManageBlogsData/', 'refresh');
			break;
			
			
			default : #Manage
				$page_data["totalRows"] = $totalRows = $this->admin_model->getManageBlogCount();#
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				if (!empty($_GET['keywords'])) {
					$base_url = base_url('admin/ManageBlogsData?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('admin/ManageBlogsData?keywords=');
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
				
				$page_data['resultData']  = $result= $this->admin_model->getManageBlog($limit, $offset);
				
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
	

	function getManageBlogCount()
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
									blog_title like "%'.($_GET['keywords']).'%" or 
									blog_description like "%'.($_GET['keywords']).'%"
								
								)
								';
		}
		
		$query = "select blog_id from blogs
		left join category on category.category_id = blogs.category_id
		where $condition";
		
		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getManageBlog($offset="",$record="")
	{
		$condition = " 1=1 ";
		if(!empty($_GET['keywords']))
		{
				$condition .= ' and (
									blog_title like "%'.($_GET['keywords']).'%" or 
									blog_description like "%'.($_GET['keywords']).'%"
								
								)
								';
		}
		
		$query = "select blogs.*,category.category_id,category.category_name from blogs
		left join category on category.category_id = blogs.category_id
		where $condition
				order by blog_id desc
					limit ".$record." , ".$offset."
		";
		
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
