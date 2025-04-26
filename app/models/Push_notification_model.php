<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Push_notification_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function getPushNotificationHistoryCount()
	{
		if($this->user_id==1) #Admin
		{
			$condition = " 1=1";
			$joinQuery ="left join branch on 
								branch.branch_id = org_push_notifications.branch_id";
		}
		else #Branch Admins
		{
			$condition = " 1=1 and 
				org_push_notifications.user_id='".$this->user_id."' and 
					org_push_notifications.branch_id='".$this->admin_branch_id."'
			";
			
			$joinQuery ="left join branch on 
								branch.branch_id = org_push_notifications.branch_id";
		}
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_push_notifications.message like "%'.serchFilter($_GET['keywords']).'%" or 
								branch.branch_code like "%'.serchFilter($_GET['keywords']).'%" or
								branch.branch_name like "%'.serchFilter($_GET['keywords']).'%" or
								users.first_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}

		if (!empty($_GET['branch_id'])) {
			$condition .= ' and org_push_notifications.branch_id='.$_GET['branch_id'];
		}

		#From & To Date search start
		if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
			$fromDate = date("Y-m-d", strtotime($_GET['from_date']));
			$toDate = date("Y-m-d", strtotime($_GET['to_date']));
			
			$condition .= " and (org_push_notifications.notification_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if (!empty($_GET['from_date']) && empty($_GET['to_date'])) {
			$fromDate = date("Y-m-d", strtotime($_GET['from_date']));
			
			$condition .= " and org_push_notifications.notification_date <= '".$fromDate."' ";
		}
		
		if (empty($_GET['from_date']) && !empty($_GET['to_date'])) {
			$toDate = date("Y-m-d", strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and org_push_notifications.notification_date >= '".$toDate."' ";
		}
		#From & To Date search end
		
		$query = "select 
			org_push_notifications.notification_id from org_push_notifications

			left join users ON 
				users.user_id = org_push_notifications.user_id
			
			$joinQuery
		where 
			$condition";


		$result = $this->db->query($query)->result_array();
		return count($result);
	}
	
	function getPushNotificationHistory($offset="",$record="")
	{
		if($this->user_id==1) #Admin
		{
			$condition = " 1=1";
			$joinQuery ="left join branch on 
								branch.branch_id = org_push_notifications.branch_id";
		}
		else #Branch Admins
		{
			$condition = " 1=1 and 
				org_push_notifications.user_id='".$this->user_id."' and 
					org_push_notifications.branch_id='".$this->admin_branch_id."'
			";
			
			$joinQuery ="left join branch on 
								branch.branch_id = org_push_notifications.branch_id";
		}
		
		if(!empty($_GET['keywords']))
		{
			$condition .= ' and (
								org_push_notifications.message like "%'.serchFilter($_GET['keywords']).'%" or 
								branch.branch_code like "%'.serchFilter($_GET['keywords']).'%" or
								branch.branch_name like "%'.serchFilter($_GET['keywords']).'%" or
								users.first_name like "%'.serchFilter($_GET['keywords']).'%"
							)
							';
		}

		if (!empty($_GET['branch_id'])) {
			$condition .= ' and org_push_notifications.branch_id='.$_GET['branch_id'];
		}

		#From & To Date search start
		 if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
			$fromDate = date("Y-m-d", strtotime($_GET['from_date']));
			$toDate = date("Y-m-d", strtotime($_GET['to_date']));
			
			$condition .= " and (org_push_notifications.notification_date BETWEEN '".$fromDate."' and '".$toDate."') ";
		}
		
		if (!empty($_GET['from_date']) && empty($_GET['to_date'])) {
			$fromDate = date("Y-m-d", strtotime($_GET['from_date']));
			
			$condition .= " and org_push_notifications.notification_date <= '".$fromDate."' ";
		}
		
		if (empty($_GET['from_date']) && !empty($_GET['to_date'])) {
			$toDate = date("Y-m-d", strtotime($_GET['to_date']));
			
			#$condition .= ' and invoice_billing_date >= '.strtotime($_GET['to_date']).' ';
			$condition .= " and org_push_notifications.notification_date >= '".$toDate."' ";
		}
		#From & To Date search end
		
		$query = "select 
			org_push_notifications.*,
			users.first_name,
			branch.branch_name,
			branch.branch_code
			
			from org_push_notifications

			left join users ON 
				users.user_id = org_push_notifications.user_id
			
			$joinQuery
		where 
			$condition
				order by org_push_notifications.notification_id desc 
					limit ".$record." , ".$offset." ";
		
		$result = $this->db->query($query)->result_array();

		return $result;
	}
}
