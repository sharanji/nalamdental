<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Push_notification extends CI_Controller 
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
	
	function managePushNotification($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['managePushNotification'] = 1;
		
		$page_data['page_name']  = 'push_notification/managePushNotification';
		$page_data['page_title'] = 'Push Notification';

		switch($type)
		{
			case "history":
				$page_data["totalRows"] = $totalRows = $this->push_notification_model->getPushNotificationHistoryCount();
	
				if(!empty($_SESSION['PAGE']))
				{
					$limit = $_SESSION['PAGE'];
				}else{
					$limit = 10;
				}

				$keywords = isset($_GET['keywords']) ? $_GET['keywords'] : "";
				$branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : "";
				$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : "";
				$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : "";

				$redirectURL = 'push_notification/managePushNotification/history?keywords='.$keywords.'&branch_id='.$branch_id.'&from_date='.$from_date.'&to_date='.$to_date;
				
				if (!empty($_GET['keywords']) || !empty($_GET['branch_id']) || !empty($_GET['from_date']) || !empty($_GET['to_date'])) {
					$base_url = base_url('push_notification/managePushNotification/history?keywords='.$keywords.'&branch_id='.$branch_id.'&filter=10&from_date='.$from_date.'&to_date='.$to_date.'');
				} else {
					$base_url = base_url('push_notification/managePushNotification/history??keywords=&branch_id=&filter=100&from_date=&to_date=');
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
				
				$page_data['resultData']  = $result= $this->push_notification_model->getPushNotificationHistory($limit, $offset);
				
				if(isset($_GET['per_page']) && $_GET['per_page'] > 1 && count($result) == 0 )
				{
					redirect(base_url().$redirectURL, 'refresh');
				}
				
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

			default:
			break;
		}
		
		$this->load->view($this->adminTemplate, $page_data);
	}

	function ajaxPushNotification()
    {
		if($_POST)
		{
			$currentDate = time();

			$data = array(
				"user_id"                => $this->user_id,
				"branch_id"              => isset($this->admin_branch_id) ? $this->admin_branch_id : 0 ,
				"message"                => ucfirst($_POST["message"]),
				"title"                  => ucfirst($_POST["title"]),
				"notification_date"      => date("Y-m-d",$currentDate),
				"notification_str_date"  => $currentDate,
			);

			$this->db->insert('org_push_notifications', $data);
			$id = $this->db->insert_id();

			if($id)
			{
				/* $notificationQuery = "select 
						org_push_notifications.message,
						org_push_notifications.message_status,
						org_push_notifications.notification_str_date
					from org_push_notifications 
				join users on 
					users.user_id = org_push_notifications.user_id
				order by notification_id desc limit 0,10";
				$getNotification = $this->db->query($notificationQuery)->result_array();

				$page_data["getNotification"] = $getNotification;
				$pushAjaxNotification = $this->load->view('backend/push_notification/pushNotificationList',$page_data,true);
				*/

				$insertId = $id;
				$pushNotification = array(
					#'pushAjaxNotification'  => $pushAjaxNotification,
					'insertId'              => $insertId,
				);

				echo json_encode($pushNotification);
			}
		}exit;
	}
	
	function ajaxSentFirbase()
	{
		if($_POST)
		{
			$message = isset($_POST["message"]) ? ucfirst($_POST["message"]) :"";
			$title = isset($_POST["title"]) ? ucfirst($_POST["title"]) :"";
			$messageInsertId = isset($_POST["insertId"]) ? $_POST["insertId"] :"";

			#Server key
			$chkAndroidTokensQry = "select device_token from org_device_tokens where device_type = 1";
			$getAndroidDeviceTokens = $this->db->query($chkAndroidTokensQry)->result_array();

			$chkiOSTokensQry = "select device_token from org_device_tokens where device_type = 2";
			$getiOSDeviceTokens = $this->db->query($chkiOSTokensQry)->result_array();

			if( count($getAndroidDeviceTokens) > 0 )  #Android
			{ 
				#$apiKey = "AAAAKgiz3xw:APA91bG_xKtVrwrfQjyGUElobp-BZPXkOWi6h6WctPPXNHP2dH4LOIBIb4lVkaMBS92euLEihSFM39wuKqYOP8kkHy4cTLM2V7NlrpDgulsBMyKWVX4uTC2MjQvztXAsLQL91CTGqRoC";
				 $apiKey = "AAAAE4iB2WM:APA91bEwMhR-DhbKxLsm-NO-sYTdGDcxQvO20mIFk2LGLVonmdFkoiVWi2MdyJtCbu_1HugxGeXmo6NsTbztt7M8TaGBNhozaTmB_TiXqEJQmMt8jU7G59vkPDtFhP168BGpTRnFx2l-";
			}
			
			if( count($getiOSDeviceTokens) > 0 ) #iOS
			{
				$apiKey = "AAAAE4iB2WM:APA91bEwMhR-DhbKxLsm-NO-sYTdGDcxQvO20mIFk2LGLVonmdFkoiVWi2MdyJtCbu_1HugxGeXmo6NsTbztt7M8TaGBNhozaTmB_TiXqEJQmMt8jU7G59vkPDtFhP168BGpTRnFx2l-";
			}

			#Single Device Token
			#$to = "ctHk9Xn6Sp6nAF5NxnUhRg:APA91bFPnwjcRwtZjk0Bo1PsUETlWH4jrwOki9OoXBVpRqyKJc6aUCyoadzWasu5FTx_g2FJuVxtfkAG4yhI3N6F5McUbkgmbWFOtVPHB-okDJ0e0s-KKeQNJedD93cfTnPi6z0mRtUD";
			
			#Multiple Device Tokens start here
			#$deviceTokensQry = "select device_token from org_device_tokens limit 0,1000"; # where device_type = 1
        
        	$deviceTokensQry = "select device_token from org_device_tokens";
			$getDeviceTokens = $this->db->query($deviceTokensQry)->result_array();
        
       	    $deviceTokens = array_chunk($getDeviceTokens,999);
        
       
			#$response=array();
			
			if(count($deviceTokens) > 0)
			{
            	$i=0;
            	foreach($deviceTokens as $main_row)
				{
                	foreach($main_row as $row)
                    {
						$registration_ids = $response = array($row['device_token']);
						
						$notification = array(
							"title"      => $title,
							#"subtitle"  => $message,
							"body"       => $message,
							"sound"      => 'Default',
							"image"      => base_url()."uploads/no-image-mobile.png",
						);
			
						$fields = array(
							#"to"               => $to,
							"registration_ids"  => $registration_ids,
							"notification"      => $notification
						);
			
						#Compile headers in one variable
						$headers = array (
							'Authorization: key=' . $apiKey,
							'Content-Type:application/json'
						);
				
				
						$url = 'https://fcm.googleapis.com/fcm/send';
			
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($fields));
						$CurlResult = curl_exec($ch);
						$output = json_decode($CurlResult,true);
						curl_close($ch);
						$i++;
					}
                }
			}
			
		}exit;
	}

	function updateResponseMessageData()
	{
		if($_POST)
		{
			$response = isset($_POST["response"]) ? serialize($_POST["response"]) :"";
			$messageInsertId = isset($_POST["insertId"]) ? $_POST["insertId"] :"";
			
			$unserialize = unserialize($response);
			$message_response = json_decode($unserialize);				
			
			if(isset($message_response->success) && $message_response->success > 0 )
			{
				$message_status = isset($message_response->success) ? $message_response->success : 0;
			}
			else
			{
				$message_status = 0;
			}

			#Update Response Message start here
			$data["success_message"] = isset($message_response->success) ? $message_response->success : 0;
			$data["failure_message"] = isset($message_response->failure) ? $message_response->failure : 0;

			$data["message_response"] = $response;
			$data["message_status"] = $message_status;
			$this->db->where('notification_id', $messageInsertId);
			$result = $this->db->update('org_push_notifications', $data);	
			#Update Response Message end here

			if($result)
			{
				$notificationQuery = "select 
						org_push_notifications.title,
						org_push_notifications.message,
						org_push_notifications.message_status,
						org_push_notifications.notification_str_date
					from org_push_notifications 
				join users on 
					users.user_id = org_push_notifications.user_id
				order by notification_id desc limit 0,10";
				$getNotification = $this->db->query($notificationQuery)->result_array();

				$page_data["getNotification"] = $getNotification;
				
				$pushAjaxNotification = $this->load->view('backend/push_notification/pushNotificationList',$page_data,true);
				
				$pushNotification = array(
					'pushAjaxNotification'  => $pushAjaxNotification,
					'insertId'              => $messageInsertId,
				);

				echo json_encode($pushNotification);
			}
		}exit;
	}
}
?>
