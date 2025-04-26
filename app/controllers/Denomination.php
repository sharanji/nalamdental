<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once('vendor/autoload.php');
class Denomination extends CI_Controller 
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
	
	function manageDenomination($type = '', $id = '', $status = '')
    {
		if (empty($this->user_id))
        {
			redirect(base_url() . 'admin/adminLogin', 'refresh');
		}
	
		$page_data['type'] = $type;
		$page_data['id'] = $id;
		
		$page_data['Denomination'] = 1;
		$page_data['page_name']  = 'denomination/manageDenomination';
		$page_data['page_title'] = 'Denomination';
		
		switch(true)
		{
			case ($type == "create"): #Add
				if($_POST)
				{
					$currencyCount = isset($_POST['currency_count']) ? count( array_filter($_POST['currency_count']) ) : 0;

					if($currencyCount == 0)
					{
						$this->session->set_flashdata('error_message' , 'Atleast 1 currency is required!');
						redirect(base_url() . 'denomination/manageDenomination/create', 'refresh');
					}

					$headerPostData = array(
						"cash_amount"  		    => $this->input->post('cash_amount'),
						"upi_amount"  			=> $this->input->post('upi_amount'),
						"card_amount"  			=> $this->input->post('card_amount'),
						"denomination_date"  	=> date("Y-m-d",strtotime($_POST['denomination_date'])),					
						"created_by" 		   	=> $this->user_id,
						"created_date"         	=> $this->date_time,
						"last_updated_by"      	=> $this->user_id,
						"last_updated_date"    	=> $this->date_time,
					);
					
					$this->db->insert('pay_denomination_headers', $headerPostData);
					$header_id = $this->db->insert_id();
					
					if ($header_id)
					{	
						$currencyCount = isset($_POST['currency_count']) ? count( array_filter($_POST['currency_count']) ) : 0;

						# Add and Remove Faq start
						if( $currencyCount > 0 )
						{	
							for($dp=0;$dp<$currencyCount;$dp++)
							{	
								$LineData['header_id'] = $header_id;
								$LineData['currency'] = $_POST['currency'][$dp];
								$LineData['currency_count'] = $_POST['currency_count'][$dp];
								$LineData['created_by'] = $this->user_id;
                                $LineData['created_date'] = $this->date_time;
                                $LineData['last_updated_by'] = $this->user_id;
                                $LineData['last_updated_date'] = $this->date_time;
								
								$this->db->insert('pay_denomination_lines', $LineData);
								$id = $this->db->insert_id();
							}
						}
						#Add and Remove menus end

						$userQry = "select 
							emp.first_name,
							emp.email_address from per_user as user

						left join per_people_all as emp on
							emp.person_id = user.person_id

						WHERE user.user_id = '".$_SESSION["user_id"]."'";
						$getUserData = $this->db->query($userQry)->result_array();

						if (count($getUserData) > 0)
						{
							if(isset($getUserData[0]["email_address"]) && !empty($getUserData[0]["email_address"]))
							{
								$from_email = $getUserData[0]["email_address"];
							}
							else
							{
								$from_email = NOREPLY_EMAIL;
							}
							
							$from = $from_email;
							$to = CONTACT_EMAIL;
							
							$fromName = isset($getUserData[0]["first_name"]) ? $getUserData[0]["first_name"] : NULL;
							$page_data['cashier_denomination'] = 1;

							$subject = "Denominaltion Details";
							$page_data['login_time'] = date("d-M-Y h:i:s A");
							$page_data['cashier_name'] = $fromName;
							$page_data['subject'] = $subject;
							/* $pdf_name = date('d-M-Y').'_'.$header_id;
							//$denomination_pdf = $page_data['denomination_pdf'] = base_url()."denomination/denominationPDF/".$header_id;
							$result = $this->denomination_model->getViewData($header_id);

							ob_start();
							$html = ob_get_clean();
							$html = utf8_encode($html);
							$html = $this->load->view('backend/denomination/denominationPDF',$result,true);
							
							$mpdf = new \Mpdf\Mpdf();
							$mpdf->WriteHTML($html);

							$mpdf->AddPage('P','','','','',3,4,4,4,4,4);
							$mpdf->WriteHTML($html);
							$mpdf->Output($pdf_name.'.pdf','I');
							$mpdf->Output('uploads/demomination_pdf/'.$pdf_name.'.pdf', 'F');

							$message = $this->load->view('mail_template/admin_mail_template', $page_data, true);
							
							if (EMAIL_TYPE == 2) { 
								
								$sendMail = Send_SMTP($from, $to, $subject, $message, $fromName);
							} else { 
								$sendMail = Send_Grid($from,  $to, $subject, $message, $fromName);
							}	 */
						}

						$this->session->set_flashdata('flash_message' , 'Denomination saved successfully');
						redirect(base_url() . 'denomination/manageDenomination', 'refresh');
					}
				}
			break;
			
			case ($type == "edit" || $type == "view"): #Edit / View
				$page_data['edit_data'] = $this->db->get_where('pay_denomination_headers', array('header_id' => $id))
										->result_array();

				if($_POST)
				{
					$header_id = $id;
					$currencyCount = isset($_POST['currency_count']) ? count( array_filter($_POST['currency_count']) ) : 0;

					if($currencyCount == 0)
					{
						$this->session->set_flashdata('error_message' , 'Atleast 1 currency is required!');
						redirect(base_url() . 'denomination/manageDenomination/edit/'.$id, 'refresh');
					}

					$headerPostData = array(
						"cash_amount"  		    => $this->input->post('cash_amount'),
						"upi_amount"  			=> $this->input->post('upi_amount'),
						"card_amount"  			=> $this->input->post('card_amount'),
						"denomination_date"  	=> date("Y-m-d",strtotime($_POST['denomination_date'])),					
						"last_updated_by"      	=> $this->user_id,
						"last_updated_date"    	=> $this->date_time,
					);

					$this->db->where('header_id', $header_id);
					$result = $this->db->update('pay_denomination_headers', $headerPostData);
					
					if ($result)
					{
						# Add and Remove Faq start
						$currencyCount = isset($_POST['currency_count']) ? count( array_filter($_POST['currency_count']) ) : 0;

						if( $currencyCount > 0 )
						{	
							for($dp=0;$dp<$currencyCount;$dp++)
							{	
								$currency = $_POST['currency'][$dp];
								
								$existQry = "select line_id from pay_denomination_lines 
									where 
									header_id='".$header_id."'
									and currency='".$currency."'
									";
								$checkExist = $this->db->query($existQry)->result_array();
								
								if(count($checkExist) == 0)
								{
									
									$LineData['header_id'] = $header_id;
									$LineData['currency'] = $currency;
									$LineData['currency_count'] = $_POST['currency_count'][$dp];
									$LineData['created_by'] = $this->user_id;
									$LineData['created_date'] = $this->date_time;
									$LineData['last_updated_by'] = $this->user_id;
									$LineData['last_updated_date'] = $this->date_time;
									
									$this->db->insert('pay_denomination_lines', $LineData);
									$insert_id = $this->db->insert_id();
								}
								else
								{
									$LineData['currency_count'] = $_POST['currency_count'][$dp];
									$LineData['last_updated_by'] = $this->user_id;
									$LineData['last_updated_date'] = $this->date_time;
									
									$this->db->where('header_id', $header_id);
									$this->db->where('currency', $currency);
									$result = $this->db->update('pay_denomination_lines', $LineData);
									
								}
							}
						}
						#Add and Remove menus end

						$userQry = "select 
							emp.first_name,
							emp.email_address from per_user as user

						left join per_people_all as emp on
							emp.person_id = user.person_id

						WHERE user.user_id = '".$_SESSION["user_id"]."'";
						$getUserData = $this->db->query($userQry)->result_array();
						
						if (count($getUserData) > 0)
						{
							if(isset($getUserData[0]["email_address"]) && !empty($getUserData[0]["email_address"]))
							{
								$from_email = $getUserData[0]["email_address"];
							}
							else
							{
								$from_email = NOREPLY_EMAIL;
							}
							
							$from = $from_email;
							$to = CONTACT_EMAIL;
							
							$fromName = isset($getUserData[0]["first_name"]) ? $getUserData[0]["first_name"] : NULL;
							$page_data['cashier_denomination'] = 1;

							$subject = "Denominaltion Details";
							$page_data['login_time'] = date("d-M-Y h:i:s A");
							$page_data['cashier_name'] = $fromName;
							$page_data['subject'] = $subject;

							$page_data['denomination_pdf'] = base_url()."denomination/denominationPDF/".$header_id;
							
							$message = $this->load->view('mail_template/admin_mail_template', $page_data, true);
							
							if (EMAIL_TYPE == 2) { 
								
								$sendMail = Send_SMTP($from, $to, $subject, $message, $fromName);
							} else { 
								$sendMail = Send_Grid($from,  $to, $subject, $message, $fromName);
							}	
						}

						$this->session->set_flashdata('flash_message' , "Denomination saved successfully!");
						redirect(base_url() . 'denomination/manageDenomination', 'refresh');
					}
				}
			break;
			
			default : #Manage
				$totalResult = $this->denomination_model->getmanageDenomination("","",$this->totalCount);
				$page_data["totalRows"] = $totalRows = count($totalResult);
	
				if(!empty($_SESSION['PAGE']))
				{$limit = $_SESSION['PAGE'];
				}else{$limit = 10;}
				
				$redirectURL = 'denomination/manageDenomination?keywords=';

				if (!empty($_GET['keywords']) ) {
					$base_url = base_url('denomination/manageDenomination?keywords='.$_GET['keywords']);
				} else {
					$base_url = base_url('denomination/manageDenomination?keywords=');
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
				
				$page_data['resultData']  = $result= $data =$this->denomination_model->getmanageDenomination($limit, $offset, $this->pageCount);
				
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
		}	
		$this->load->view($this->adminTemplate, $page_data);
	}
	
	/* function denominationPDF($header_id="")
	{
		$page_data['id'] = $header_id;
		$result = $this->denomination_model->getViewData($header_id);
		$page_data['headerData'] = $result['headerData'];
		$page_data['lineData'] = $result['lineData'];
		
		$this->load->view('backend/denomination/denominationPDF',$page_data,true);
	} */

	function denominationPDF($header_id = '')
	{
		$page_data['id'] = $header_id;
		$result = $this->denomination_model->getViewData($header_id);
		$page_data['headerData'] = $result['headerData'];
		$page_data['lineData'] = $result['lineData'];
		$date = date('d-M-Y');

		ob_start();
		$html = ob_get_clean();
		$html = utf8_encode($html);
		
		$mpdf = new \Mpdf\Mpdf([
			'setAutoTopMargin' => 'stretch',
			'setAutoBottomMargin' => 'stretch',
			'curlAllowUnsafeSslRequests' => true,
		]);

		$html = $this->load->view('backend/denomination/denominationPDF',$page_data,true);
		
		$pdf_name = $date;
		$mpdf->AddPage('P','','','','',3,4,4,4,4,4);
		$mpdf->WriteHTML($html);
		$mpdf->Output($pdf_name.'.pdf','I');
		//$mpdf->Output('uploads/demomination_pdf/'.$pdf_name.'.pdf', 'F');
		
	}

	public function ajaxCashierCloseShift()
	{
		if($_POST)
		{
			$currencyCount = isset($_POST['currency_count']) ? count( array_filter($_POST['currency_count']) ) : 0;

			$resultsQry = "select 
			header_id,
			denomination_date,
			created_by 
			from pay_denomination_headers 
			WHERE 
			created_by='".$this->user_id."' and 
			denomination_date = '".date('Y-m-d')."'
			order by pay_denomination_headers.header_id desc limit 0,1";

			$getdenomination = $this->db->query($resultsQry)->result_array();

			if($currencyCount == 0)
			{
				echo '0';
			}
			else if( count($getdenomination) == 1 )
			{
				echo 'exist';
			}
			else
			{
				$headerPostData = array(
					"cash_amount"  		    => $this->input->post('cash_amount'),
					"upi_amount"  			=> $this->input->post('upi_amount'),
					"card_amount"  			=> $this->input->post('card_amount'),
					"denomination_date"  	=> date("Y-m-d",strtotime($_POST['denomination_date'])),					
					"created_by" 		   	=> $this->user_id,
					"created_date"         	=> $this->date_time,
					"last_updated_by"      	=> $this->user_id,
					"last_updated_date"    	=> $this->date_time,
				);

				$this->db->insert('pay_denomination_headers', $headerPostData);
				$header_id = $this->db->insert_id();
				
				if ($header_id)
				{	
					$currencyCount = isset($_POST['currency_count']) ? count( array_filter($_POST['currency_count']) ) : 0;

					# Add and Remove Faq start
					if( $currencyCount > 0 )
					{	
						for($dp=0;$dp<$currencyCount;$dp++)
						{	
							$LineData['header_id'] = $header_id;
							$LineData['currency'] = $_POST['currency'][$dp];
							$LineData['currency_count'] = $_POST['currency_count'][$dp];
							$LineData['created_by'] = $this->user_id;
							$LineData['created_date'] = $this->date_time;
							$LineData['last_updated_by'] = $this->user_id;
							$LineData['last_updated_date'] = $this->date_time;
							
							$this->db->insert('pay_denomination_lines', $LineData);
							$id = $this->db->insert_id();
						}
					}
					#Add and Remove menus end

					$this->ajaxChkDenomination($this->user_id);

					$this->session->sess_destroy();

					echo $header_id;exit;
					/* $userQry = "select 
						emp.first_name,
						emp.email_address from per_user as user

					left join per_people_all as emp on
						emp.person_id = user.person_id

					WHERE user.user_id = '".$_SESSION["user_id"]."'";
					$getUserData = $this->db->query($userQry)->result_array();

					if (count($getUserData) > 0)
					{
						if(isset($getUserData[0]["email_address"]) && !empty($getUserData[0]["email_address"]))
						{
							$from_email = $getUserData[0]["email_address"];
						}
						else
						{
							$from_email = NOREPLY_EMAIL;
						}
						
						$from = $from_email;
						$to = CONTACT_EMAIL;
						
						$fromName = isset($getUserData[0]["first_name"]) ? $getUserData[0]["first_name"] : NULL;
						$page_data['cashier_denomination'] = 1;

						$subject = "Denominaltion Details";
						$page_data['login_time'] = date("d-M-Y h:i:s A");
						$page_data['cashier_name'] = $fromName;
						$page_data['subject'] = $subject;
						/* $pdf_name = date('d-M-Y').'_'.$header_id;
						//$denomination_pdf = $page_data['denomination_pdf'] = base_url()."denomination/denominationPDF/".$header_id;
						$result = $this->denomination_model->getViewData($header_id);

						ob_start();
						$html = ob_get_clean();
						$html = utf8_encode($html);
						$html = $this->load->view('backend/denomination/denominationPDF',$result,true);
						
						$mpdf = new \Mpdf\Mpdf();
						$mpdf->WriteHTML($html);

						$mpdf->AddPage('P','','','','',3,4,4,4,4,4);
						$mpdf->WriteHTML($html);
						$mpdf->Output($pdf_name.'.pdf','I');
						$mpdf->Output('uploads/demomination_pdf/'.$pdf_name.'.pdf', 'F');

						$message = $this->load->view('mail_template/admin_mail_template', $page_data, true);
						
						if (EMAIL_TYPE == 2) { 
							
							$sendMail = Send_SMTP($from, $to, $subject, $message, $fromName);
						} else { 
							$sendMail = Send_Grid($from,  $to, $subject, $message, $fromName);
						}	 */
					
					/*}

					$this->session->set_flashdata('flash_message' , 'Denomination saved successfully');
					redirect(base_url() . 'denomination/manageDenomination', 'refresh'); */
				}

				
			}
			exit;
		}
	}

	public function ajaxChkDenomination($user_id=NULL)
	{
		if($user_id != NULL){
			$userid = $user_id;
		}else{
			$userid = $_POST['id'];
		}

		$date = date('Y-m-d');
		
		if( $userid ) 
		{
			$resultsQry = "select 
			header_id,
			denomination_date,
			created_by 
			from pay_denomination_headers 
			WHERE 
			created_by='".$userid."' and 
			denomination_date = '".$date."'
			order by pay_denomination_headers.header_id desc limit 0,1";

			$getdenomination = $this->db->query($resultsQry)->result_array();

			$headerID = $header_id = isset($getdenomination[0]['header_id']) ? $getdenomination[0]['header_id'] : 0;
			
			if(count($getdenomination) > 0 ) 
			{
				#Update logout date start
				/* $lastUpdatedDate = array(
					'logout_date'   => $this->date_time,
				); */

				$lastUpdatedDate = array(
					'last_login_status'  => 'N',
					'logout_date'        => $this->date_time,
					'last_updated_by'    => $userid,
					'last_updated_date'  => $this->date_time,
				);

				$this->db->where('user_id', $userid);
				$result = $this->db->update('per_user', $lastUpdatedDate);
				#Update logout date end

				if($header_id !=NULL)
				{
					$pdf_name = $header_id;
					$getPDFDetails = $this->denomination_model->getViewData($header_id);

					#PDF Generation Start
					/* ob_start();
					$html = ob_get_clean();
					$html = utf8_encode($html);
					$html = $this->load->view('backend/denomination/denominationPDF',$getPDFDetails,true);
					
					$mpdf = new \Mpdf\Mpdf();
					$mpdf->WriteHTML($html);
					$mpdf->AddPage('P','','','','',3,4,4,4,4,4);
					
					$mpdf->Output('uploads/demomination_pdf/'.$pdf_name.'.pdf', 'F'); */

					ob_start();
					$html = ob_get_clean();
					$html = utf8_encode($html);

					$html = $this->load->view('backend/denomination/denominationPDF',$getPDFDetails,true);

					#$mpdf = new \Mpdf\Mpdf();
					
					$mpdf = new \Mpdf\Mpdf([		
						#'setAutoTopMargin' => 'stretch',
						#'setAutoBottomMargin' => 'stretch',
						'curlAllowUnsafeSslRequests' => true,
					]);

					$mpdf->WriteHTML($html);
					$mpdf->Output('uploads/demomination_pdf/'.$pdf_name.'.pdf', 'F');
					#PDF Generation End

					#Email Sent  Start Here
					$userQry = "select 
					emp.first_name,
					emp.email_address from per_user as user

					left join per_people_all as emp on
					emp.person_id = user.person_id

					WHERE user.user_id = '".$_SESSION["user_id"]."'";
					$getUserData = $this->db->query($userQry)->result_array();
						
					if (count($getUserData) > 0)
					{
						if(isset($getUserData[0]["email_address"]) && !empty($getUserData[0]["email_address"]))
						{
							$from_email = $getUserData[0]["email_address"];
						}
						else
						{
							$from_email = NOREPLY_EMAIL;
						}
						
						$from = $from_email;
						$to = CONTACT_EMAIL;
						
						$fromName = isset($getUserData[0]["first_name"]) ? $getUserData[0]["first_name"] : NULL;
						$page_data['cashier_denomination'] = 1;

						$subject = "Denominaltion Details";
						$page_data['login_time'] = date("d-M-Y h:i:s A");
						$page_data['cashier_name'] = $fromName;
						$page_data['subject'] = $subject;

						$page_data['denomination_pdf'] = base_url()."denomination/denominationPDF/".$header_id;
						
						$message = $this->load->view('mail_template/admin_mail_template', $page_data, true);
						
						if (EMAIL_TYPE == 2) { 
							
							$sendMail = Send_SMTP($from, $to, $subject, $message, $fromName);
						} else { 
							$sendMail = Send_Grid($from,  $to, $subject, $message, $fromName);
						}	
					}
					#Email Sent End Here
				}
			}
			echo $headerID;exit;
		}
		
	}




}
?>
