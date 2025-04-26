<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getManageUsers($offset="",$record="", $countType="")
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

			if(empty($_GET['user_id'])){
				$user_id = 'NULL';
			}else{
				$user_id = $_GET['user_id'];
			}

			if(empty($_GET['active_flag'])){
				$active_flag = 'NULL';
			}else{
				$active_flag = $_GET['active_flag'];
			}

			if(!empty($_GET['user_type']) && $_GET['user_type'] == "EMP")
			{
				$user_type = ' and per_user.reg_user_type=coalesce("'.$_GET['user_type'].'",per_user.reg_user_type)';
			}
			else if(!empty($_GET['user_type']) && $_GET['user_type'] == "CONSUMERS")
			{ 
				$user_type = ' and per_user.reg_user_type IS NULL';
			}else{
				$user_type = '';
			}
			
			$query = "select 
					per_user.user_id,
					per_user.reg_user_type,
					customer.customer_number,
					customer.customer_name,
					customer.email_address,
					per_user.user_name,
					per_user.created_date,
					per_user.last_login_date,
					per_user.last_login_status,
					per_user.active_flag,
					per_user.attribute1,
					per_people_all.first_name as emp_name
					from per_user
					
					left join cus_customers as customer on 
						customer.customer_id = per_user.reference_id

					left join per_people_all on 
						per_people_all.person_id = per_user.person_id

				where 1=1 
					AND per_user.user_id != 1
					$user_type
					and per_user.user_id = coalesce($user_id,per_user.user_id)
					and per_user.active_flag = if('".$active_flag."' = 'All', per_user.active_flag,'".$active_flag."')
					order by per_user.user_id desc $limit";
			
			$result = $this->db->query($query)->result_array();

			return $result;
		}
		else
		{
			return array();
		}
	}

	function getAjaxUsersAll($person_name='')
	{
		$query="select 
					users.user_id,
					per_people.employee_number,
					per_people.first_name,
					per_people.middle_name,
					per_people.last_name
					from per_user as users
					left join per_people_all as per_people on per_people.person_id =users.person_id
					where 1=1
					and ((per_people.employee_number LIKE '%" . $person_name . "%' ) or (per_people.first_name LIKE '%" . $person_name . "%'))
					and users.active_flag='".$this->active_flag."'";
		$result = $this->db->query($query)->result_array();
		return $result;
	}
}
