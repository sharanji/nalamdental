<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	if ( ! function_exists('encode'))
	{
		function encode($string="")  #echo encode_url(98); 
		{
		   return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
		}
	}
	
	if ( ! function_exists('decode'))
	{
		function decode($string="") #decode_url('OTg');
		{
		   return base64_decode(str_replace(['-','_'], ['+','/'], $string));
		}
	}

	
	if ( ! function_exists('url'))
	{
		function url($title, $separator = '-')
		{
			$separator = ($separator === '') ? '' : '-';
			$title = preg_replace ( "/&([\x{0600}-\x{06FF}a-zA-Z])(uml|acute|grave|circ|tilde|ring),/u", "", $title );
			$title = preg_replace ( "/[^\x{0600}-\x{06FF}a-zA-Z0-9_ .-]/u", "", $title );

			$title = preg_replace('/['.$separator.'\s]+/', $separator, $title);
			
			return trim(strtolower($title), $separator);
		}
	}
	
	if ( ! function_exists('read_docx'))
	{
		function read_docx($resume='')
		{
			$striped_content = '';
			$content = '';

			$zip = zip_open($resume);

			if (!$zip || is_numeric($zip)) return false;

			while ($zip_entry = zip_read($zip)) 
			{
				if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
				if (zip_entry_name($zip_entry) != "word/document.xml") continue;
				$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
				zip_entry_close($zip_entry);
			}
			zip_close($zip);
			$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
			$content = str_replace('</w:r></w:p>', "\r\n", $content);
			$striped_content = strip_tags($content);
			return $striped_content;
		}
	}
	
	if ( ! function_exists('pagination_configuration'))
	{
		function pagination_configuration($base_url, $total_rows, $per_page='50', $uri_segment='3', $num_links='4', $use_page_numbers=TRUE,$suffix) 
		{
			$config = array();
			$config["base_url"] = $base_url;
			$config["total_rows"] = $total_rows;
			$config["per_page"] = $per_page;
			$config["uri_segment"] = $uri_segment;
			$config['num_links'] = $num_links;
			$config['use_page_numbers'] = $use_page_numbers;
			$config['suffix'] = $suffix;
			
			$config['full_tag_open'] = '<ul class="dynamic_pagination">';
			$config['full_tag_close'] = '</ul>';
			
			#First Link
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			
			#Last Link
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			
			#Next Link
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			
			#Previous Link
			$config['prev_link'] = 'Prev';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			#Current link
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</li></a>';
			
			#Digits Link
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			return $config;
		}
	}
	
	if ( ! function_exists('randomNum'))
	{
		function randomNum($n='') 
		{ 
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; 
			$randomString = ''; 
		  
			for ($i = 0; $i < $n; $i++) 
			{ 
				$index = rand(0, strlen($characters) - 1); 
				$randomString .= $characters[$index]; 
			} 
			return $randomString; 
		}
	}
	
	
	if ( ! function_exists('PaginationConfig'))
	{
		function PaginationConfig($base_url="", $totalRows="", $limit="") 
		{
			$config = array();
			
			$config["base_url"] =$base_url;
			$config["total_rows"] = $totalRows;
			$config["per_page"] = $limit;
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$config['enable_query_strings'] = TRUE;
			
			$config['num_links'] = 9;
			
			$config['full_tag_open'] = '<ul class="dynamic_pagination">';
			$config['full_tag_close'] = '</ul>';
			
			#First Link
			$config['first_link'] = 'First';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			
			#Last Link
			$config['last_link'] = 'Last';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			#Next Link
			$config['next_link'] = 'Next';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			
			#Previous Link
			$config['prev_link'] = 'Prev';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			$config['cur_tag_open'] = '&nbsp;<li class="active"><a>';
			$config['cur_tag_close'] = '</a></li>';
			
			#Digits Link
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			
			///$config['next_link'] = 'Next';
			///$config['prev_link'] = 'Previous';
			return $config;
		}
	}
	
	if ( ! function_exists('moneyFormat'))
	{
		function moneyFormat($num='') 
		{
			$explrestunits = "" ;
			if(strlen($num)>3) 
			{
				$lastthree = substr($num, strlen($num)-3, strlen($num));
				$restunits = substr($num, 0, strlen($num)-3); #Extracts the last three digits
				$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; #Explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
				$expunit = str_split($restunits, 2);
				for($i=0; $i<sizeof($expunit); $i++) 
				{
					if($i==0) 
					{
						$explrestunits .= (int)$expunit[$i].","; #if is first value , convert into integer
					} 
					else 
					{
						$explrestunits .= $expunit[$i].",";
					}
				}
				$thecash = $explrestunits.$lastthree;
			} 
			else 
			{
				$thecash = $num;
			}
			return $thecash; # Writes the final format where $currency is the currency symbol.
		}
	}
	
	if ( ! function_exists('timeAgo'))
	{
		function timeAgo($tm,$rcs = 0)
		{
			 $cur_tm = time(); 
			$dif = $cur_tm-$tm;
			$pds = array('second','minute','hour','day','week','month','year','decade');
			$lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);

			for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
				$no = floor($no);
				if($no <> 1)
					$pds[$v] .="s ago";
				$x = sprintf("%d %s ",$no,$pds[$v]);
				if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0))
					$x .= time_ago($_tm);
				return $x;
		}
	}
	
	if ( ! function_exists('compressImage'))
	{
		function compressImage($source="", $destination="", $quality="") 
		{
			$info = getimagesize($source);
			
			if ($info['mime'] == 'image/jpeg') 
				$image = imagecreatefromjpeg($source);
			
			else if ($info['mime'] == 'image/jpg') 
				$image = imagecreatefromgif($source);
			
			else if ($info['mime'] == 'image/gif') 
				$image = imagecreatefromgif($source);

			else if ($info['mime'] == 'image/png') 
				$image = imagecreatefrompng($source);

			imagejpeg($image, $destination, $quality);
		}
	}
	
	# Amount In Words
	if ( ! function_exists('amountInWords'))
	{
		function amountInWords($amount="") 
		{
			$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
		   // Check if there is any number after decimal
		   $amt_hundred = null;
		   $count_length = strlen($num);
		   $x = 0;
		   $string = array();
		   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
			 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
			 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
			 10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
			 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
			 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
			 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
			 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
			 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
			$here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
			while( $x < $count_length ) {
			  $get_divider = ($x == 2) ? 10 : 100;
			  $amount = floor($num % $get_divider);
			  $num = floor($num / $get_divider);
			  $x += $get_divider == 10 ? 1 : 2;
			  if ($amount) {
			   $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
			   $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
			   $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
			   '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
			   '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
				}
		   else $string[] = null;
		   }
		   $implode_to_Rupees = implode('', array_reverse($string));
		   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
		   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
		   return ($implode_to_Rupees ? $implode_to_Rupees . 'Only' : '') . $get_paise;
		}
	}
	
	if ( ! function_exists('sendSMS'))
	{
		function sendSMS($phone_number="",$message="") 
		{
			$fields = array(
				"sender_id" => "FSTSMS",
				"message" => $message,
				"language" => "english",
				"route" => "t",
				"numbers" => $phone_number,
			);

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://www.fast2sms.com/dev/bulk",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode($fields),
			  CURLOPT_HTTPHEADER => array(
				"authorization: elNIWdPL4TVuhKAGt7BnjMoEw9ZFyYU6cXx5kg2J8zHaiOs01Dn50wUgxpFkDubhRT9Ba87Ny6vlMtWr",
				"accept: */*",
				"cache-control: no-cache",
				"content-type: application/json"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			/* if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  echo $response;
			} */
			
			if ($err) 
			{
				return 0;
			} 
			else 
			{
				return 1;
			} 
		}
	}
	
	#Access Menu
	if ( ! function_exists('accessMenu'))
	{
		function accessMenu($menu_url="")
		{
			$CI = get_instance();
			$CI->load->database();
			
			$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1 ;
			
			$query = 'select 
				org_roles_items.menu_enabled,
				org_roles_items.create_edit_only,
				org_roles_items.read_only,
				org_menus.menu_url,
				org_roles_items.role_id
				from per_user_roles

				left join per_user on per_user.user_id = per_user_roles.user_id

				left join org_roles on org_roles.role_id = per_user_roles.role_id
				left join org_roles_items on org_roles_items.role_id = org_roles.role_id
				left join org_menus on org_menus.menu_id = org_roles_items.menu_id

				where org_menus.menu_url = "'.$menu_url.'"
					and per_user.user_id = "'.$user_id.'" 
					and org_roles.active_flag = "Y"
					and org_menus.active_flag = "Y"
					and per_user_roles.active_flag = "Y"
				';
			//echo $query;
			$result = $CI->db->query($query)->result_array();
			
			if(count($result) > 0)
			{
				foreach($result as $row)
				{
					$data['menu_enabled'] = $row['menu_enabled'];
					$data['create_edit_only'] = $row['create_edit_only'];
					$data['read_only'] = $row['read_only'];
				}
			}
			else
			{
				$data['menu_enabled'] = "";
				$data['create_edit_only'] = "";
				$data['read_only'] = "";
			}
			return $data;
		}
	}

	#Audit Trails
	/* if ( ! function_exists('auditTrails'))
	{
		function auditTrails($postValue="", $tableName="", $type="", $menuName="", $editData="", $description="")
		{
			#print_r($postValue);exit;
			$CI = get_instance();
			$CI->load->database();

			if($type == "add") #Create
			{
				if(isset($postValue) && count($postValue) > 0)
				{
					foreach($postValue as $key => $value)
					{
						$insertData = array(
							"user_id"       	=> ADMIN_USER_ID,
							"table_name"    	=> $tableName,
							"menu_name"         => $menuName,
							"field_name"    	=> $key,
							"old_value"     	=> "",
							"new_value"     	=> $value,
							"created_by"    	=> ADMIN_USER_ID,
							"created_date"  	=> DATE_TIME,
							"last_updated_by"   => ADMIN_USER_ID,
							"last_updated_date" => DATE_TIME,
							"action_type"   	=> $type,
						);

						$CI->db->insert('audit_trails', $insertData);
						$id = $CI->db->insert_id();
					}
				}
				
				
					$CI->db->insert('audit_trails', $insertData);  #Base Table
					$base_id = $CI->db->insert_id();
			}
			else if($type == "edit") #Update
			{
				$edit_Data = isset($editData) ? (array_shift($editData)) : array();
			
				$created_date_string = time();

				if(isset($postValue) && count($postValue) > 0)
				{
					foreach($postValue as $post_key => $post_value)
					{
						if( in_array($post_value, $edit_Data) ) #Match found"
						{

						}
						else #Match not found
						{
							if (array_key_exists($post_key,$edit_Data))
							{
								$old_value = $edit_Data[$post_key];
								
								$insertData = array(
									"user_id"               => ADMIN_USER_ID,
									"table_name"            => $tableName,
									"menu_name"            	=> $menuName,
									"field_name"            => $post_key,
									"old_value"             => isset($old_value) ? $old_value :"",
									"new_value"             => $post_value,
									"last_updated_by"       => ADMIN_USER_ID,
									"last_updated_date"     => DATE_TIME,
									"ip_address"            => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] :"",
									"action_type"           => $type,
								);
	
								$CI->db->insert('audit_trails', $insertData);
								$base_id = $CI->db->insert_id();
							}
						}
					}
				}
			}
			else if($type == "status") #Active & Inactive
			{
				$created_date_string = time();

				$insertData = array(
					"user_id"               => ADMIN_USER_ID,
					"table_name"            => $tableName,
					"field_name"            => "",
					"old_value"             => "",
					"new_value"             => $postValue,
					"created_by"            => ADMIN_USER_ID,
					"created_date"   		=> DATE_TIME,
					"last_updated_by"       => ADMIN_USER_ID,
					"last_updated_date"     => DATE_TIME,
					"ip_address"            => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] :"",
					"action_type"           => $type,
				);
					$CI->db->insert('audit_trails', $insertData);  #Base Table
					$base_id = $CI->db->insert_id();
				
			}
			return 1;
		}
	} */

	#checkAuthorization
	if ( ! function_exists('checkAuthorization'))
	{
		function checkAuthorization($user_name="",$password="")
		{
			$CI = get_instance();
			$CI->load->database();

			$query = "select api_authorization.auth_id from api_authorization 
				where 
					api_authorization.auth_user_name='".$user_name."' and
						api_authorization.auth_password='".md5($password)."'
				";
			
			$result = $CI->db->query($query)->result_array();

			if(count($result) > 0)
			{
				return 1; #True
			} 
			else 
			{ 
				return 0;  #False
			} 
		}
	}

	if ( ! function_exists('otpNumber'))
	{
		function otpNumber($n='') 
		{ 
			$characters = '0123456789'; 
			$randomString = ''; 
		  
			for ($i = 0; $i < $n; $i++) { 
				$index = rand(0, strlen($characters) - 1); 
				$randomString .= $characters[$index]; 
			} 
			return $randomString; 
		}
	}

	#get getGeoLatLong using address
	if ( ! function_exists('getGeoLatLong'))
	{
		function getGeoLatLong($address1="")
		{
			if($address1)
			{
			    $address = str_replace(' ', '%20', $address1);
				#send request and receive json data by latitude and longitude
				#$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&key=AIzaSyAbs0hgqNa7G6Chjooy0iC_YST4kq0bCwc';
				
				$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".trim($address)."&key=".GOOGLE_MAP_API_KEY."";
				
				$json = @file_get_contents($url);
				$data = @json_decode($json);
				
				$status = isset($data->status) ? $data->status :"";
				
				#if request status is successful
				if($status == "OK")
				{
				    //get Lat,lan from json data
					$latLan['address'] = $data->results[0]->formatted_address;
					
					$latLan['latitude']   = $data->results[0]->geometry->location->lat;
                    $latLan['longitude']  = $data->results[0]->geometry->location->lng;
				}
				else
				{
					$latLan['address'] = $address;
					$latLan['latitude'] = "12.745458";
					$latLan['longitude'] ="77.81027069999999";
				}
				return $latLan;
			}
		}
	}
	
	#get getGeoAddress using latitude,longitude
	if ( ! function_exists('getGeoAddress'))
	{
		function getGeoAddress($latitude="",$longitude="") 
		{
			if($latitude !="" && $longitude !="")
			{
				#send request and receive json data by latitude and longitude
				$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".trim($latitude).",".trim($longitude)."&key=".GOOGLE_MAP_API_KEY."";
				
				#$url = "https://maps.googleapis.com/maps/api/geocode/json?address='".trim($address)."'&key='".GOOGLE_MAP_API_KEY."'";
				
				$json = @file_get_contents($url);
				$data = @json_decode($json);
				$status = isset($data->status) ? $data->status :"";
				
				#if request status is successful
				if($status == "OK")
				{
					#get Lat,lan from json data
					$address['address']    = $data->results[0]->formatted_address;
					$address['latitude']   = $data->results[0]->geometry->location->lat;
                    $address['longitude']  = $data->results[0]->geometry->location->lng;
				}
				else
				{
					$address['address'] = "Not Available";
					$address['latitude'] = "";
					$address['longitude'] ="";
				}
				return $address;
			}
		}
	}

	if ( ! function_exists('serchFilter'))
	{
		function serchFilter($string="")
		{
		   return preg_replace("/[^a-zA-Z0-9]+/", "%", $string);
		}
	}

	if ( ! function_exists('RemoveSpecialChar'))
	{
		function RemoveSpecialChar($str="") 
		{
			$res = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $str);
			return $res;
		}
	}

	if ( ! function_exists('RemoveWhiteSpace'))
	{
		function RemoveWhiteSpace($str="") 
		{
			$res = str_replace(' ','', $str);
			return $res;
		}
	}

	if ( ! function_exists('RemoveWhiteSpace'))
	{
		function RemoveWhiteSpace($str="") 
		{
			$res = str_replace(' ','', $str);
			return $res;
		}
	}

	if ( ! function_exists('nextLetter'))
	{
		function nextLetter($currentLetter="") 
		{
			$letter = $currentLetter;
			$letterAscii = ord($letter);
			$letterAscii++;
			$letter = chr($letterAscii);//'B'
			return $letter;
		}
	}

	
	if ( ! function_exists('no_to_words'))
	{
		function no_to_words(float $number)
		{
			$decimal = round($number - ($no = floor($number)), 2) * 100;
			$decimal_part = $decimal;
			$hundred = null;
			$hundreds = null;
			$digits_length = strlen($no);
			$decimal_length = strlen($decimal);
			$i = 0;
			$str = array();
			$str2 = array();

			/* $words = array(0 => '', 1 => 'One', 2 => 'two',
				3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
				7 => 'seven', 8 => 'eight', 9 => 'nine',
				10 => 'ten', 11 => 'eleven', 12 => 'twelve',
				13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
				16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
				19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
				40 => 'forty', 50 => 'fifty', 60 => 'sixty',
				70 => 'seventy', 80 => 'eighty', 90 => 'ninety'); */

			$words = array(0 => '', 1 => 'One', 2 => 'Two',
			 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
			 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
			 10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
			 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
			 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
			 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
			 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
			 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');


			$digits = array('', 'Hundred','Thousand','Lakh', 'Crore');

			while( $i < $digits_length ) {
				$divider = ($i == 2) ? 10 : 100;
				$number = floor($no % $divider);
				$no = floor($no / $divider);
				$i += $divider == 10 ? 1 : 2;
				if ($number) {
					$plural = (($counter = count($str)) && $number > 9) ? '' : null;
					$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
					$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
				} else $str[] = null;
			}

			$d = 0;
			while( $d < $decimal_length ) 
			{
				$divider = ($d == 2) ? 10 : 100;
				$decimal_number = floor($decimal % $divider);
				$decimal = floor($decimal / $divider);
				$d += $divider == 10 ? 1 : 2;
				if ($decimal_number) 
				{
					$plurals = (($counter = count($str2)) && $decimal_number > 9) ? '' : null;
					$hundreds = ($counter == 1 && $str2[0]) ? ' and ' : null;
					@$str2 [] = ($decimal_number < 21) ? $words[$decimal_number].' '. $digits[$decimal_number]. $plural.' '.$hundred:$words[floor($decimal_number / 10) * 10].' '.$words[$decimal_number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
				} else $str2[] = null;
			}

			$Rupees = implode('', array_reverse($str));
			$paise = implode('', array_reverse($str2));
			$paise = ($decimal_part > 0) ? "and ".ucfirst($paise).'Paise Only': '';

			return ($Rupees ? ucfirst($Rupees).'Rupees ' : '').$paise;
		}
	}

	#Audit Trails
	 if ( ! function_exists('auditTrails'))
	{
		function auditTrails( $postValue="", $tableName="", $type="", $menuName="", $editData="",$description="",$pageName="" )
		{
			$CI = get_instance();
			$CI->load->database();

			if($type == "add") #Create
			{
				if( isset($postValue) && count($postValue) > 0 )
				{
					foreach( $postValue as $key => $value )
					{
						$insertData = array(
							"user_id"       	=> ADMIN_USER_ID,
							"table_name"    	=> $tableName,
							"menu_name"         => $menuName,
							"field_name"    	=> $key,
							"old_value"     	=> "",
							"new_value"     	=> $value,
							"branch_id"         => BRANCH_ID,
							"created_by"    	=> ADMIN_USER_ID,
							"created_date"  	=> DATE_TIME,
							"last_updated_by"   => ADMIN_USER_ID,
							"last_updated_date" => DATE_TIME,
							"action_type"   	=> $type,
							"description"   	=> $description,
							"source_ip"     	=> isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "",
							"page_name"     	=> isset($pageName) ? $pageName :"",
						);
						#print_r($insertData);exit;
						$CI->db->insert('audit_trails', $insertData);
						$id = $CI->db->insert_id();
					}
				}
			}
			else if($type == "edit") #Update
			{
				$edit_Data = isset($editData) ? (array_shift($editData)) : array();
			
				if(isset($postValue) && count($postValue) > 0)
				{
					foreach($postValue as $key => $value)
					{
						if (array_key_exists($key,$edit_Data))
						{
							foreach($edit_Data as $key1 => $value1)
							{
								if($key == $key1)
								{
									$oldValue = $value1;
								}
							}
						}
						else
						{
							$oldValue="";
						}

						if( is_array($value) )
						{
							$new_value = "";
						}
						else
						{
							$new_value = $value;
						}	

                       	$insertData = array(
							"user_id"       => ADMIN_USER_ID,
							"table_name"    => $tableName,
							"menu_name"     => $menuName,
							"field_name"    => $key,
							"old_value"     => isset($oldValue) ? $oldValue :"",
							"new_value"     => isset($new_value) ? $new_value :"",
							"branch_id"     => BRANCH_ID,
							"created_by"    	=> ADMIN_USER_ID,
							"created_date"  	=> DATE_TIME,
							"last_updated_by"   => ADMIN_USER_ID,
							"last_updated_date" => DATE_TIME,
							"action_type"   => $type,
							"description"   => $description,
							"source_ip"     => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "",
							"page_name"     => isset($pageName) ? $pageName :"",
						);
						#print_r($oldValue);exit;
				
						$CI->db->insert('audit_trails', $insertData);
						$id = $CI->db->insert_id();
					}
				}
			}
			else if($type == "status") #status
			{
				if( isset($postValue) )
				{
					$insertData = array(
						"user_id"       => ADMIN_USER_ID,
						"table_name"    => $tableName,
						"menu_name"     => $menuName,
						"field_name"    => $postValue,
						"old_value"     => "",
						"new_value"     => "",
						"branch_id"     => BRANCH_ID,
						"created_by"    	=> ADMIN_USER_ID,
						"created_date"  	=> DATE_TIME,
						"last_updated_by"   => ADMIN_USER_ID,
						"last_updated_date" => DATE_TIME,
						"action_type"   => $type,
						"description"   => $description,
						"source_ip"     => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "",
						"page_name"     => isset($pageName) ? $pageName :"",
					);
				
					$CI->db->insert('audit_trails', $insertData);
					$id = $CI->db->insert_id();
				}
			}
			else if($type == "approval_status") #approval_status
			{
				$insertData = array(
					"user_id"       => ADMIN_USER_ID,
					"table_name"    => $tableName,
					"menu_name"     => $menuName,
					"field_name"    => "",
					"old_value"     => "",
					"new_value"     => "",
					"branch_id"     => BRANCH_ID,
					"created_by"    	=> ADMIN_USER_ID,
					"created_date"  	=> DATE_TIME,
					"last_updated_by"   => ADMIN_USER_ID,
					"last_updated_date" => DATE_TIME,
					"action_type"   => $type,
					"description"   => $description,
					"source_ip"     => isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : "",
					"page_name"     => isset($pageName) ? $pageName :"",
				);
				//print_r($insertData);exit;
				$CI->db->insert('audit_trails', $insertData);
				$id = $CI->db->insert_id();
			}
			return 1;
		}
	} 
