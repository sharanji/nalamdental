<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * CodeIgniter
	 *
	 * An open source application development framework for PHP 5.1.6 or newer
	 *
	 * @package		CodeIgniter
	 * @author		ExpressionEngine Dev Team
	 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
	 * @license		http://codeigniter.com/user_guide/license.html
	 * @link		http://codeigniter.com
	 * @since		Version 1.0
	 * @filesource
	 */
	 
	if ( ! function_exists('Send_SMTP'))
	{
		function Send_SMTP($from="",$to="",$subject="",$message="",$fromName="") 
		{
			if( !empty( $to ) )
			{
				$to = $to;
				$subject = $subject.' from '.SITE_NAME;
				
				$message = $message;
				$from = $from;
				$fromName = $fromName;
				$mail = Send_Mail($to, $subject, $message, $from, $fromName);
				#Do stuff (display error message, log it, redirect user, etc)
				
				if($mail == 1){
					return 1;
				} else {
					return 2;
				}  
			}
		}
    }
	
	 if ( ! function_exists('Send_Grid'))
	{
		function Send_Grid($from="",$to="", $subject="", $message="",$fromName="") 
		{
			if( !empty( $to ) )
			{
				#require APPLICATION_PATH.'/smtp/send_mail.php';
				$subject = $subject.' from '.SITE_NAME;
				$mail = Send_Mail($to, $subject, $message, $from, $fromName);
				#$mail = Send_Grid($to, $subject, $message, $from, $fromName);
				#Do stuff (display error message, log it, redirect user, etc)
				
				if($mail == 2)#Mail sent
				{
					return 1; 
				} 
				else 
				{
					return 2; #Mail not sent
				}  
			}
		}
	}
	
	/* if ( ! function_exists('send_SMS'))
	{
		function send_SMS($getData="", $message="") 
		{
			if( !empty( $getData['mobile'] ) )
			{
				$message = "<b>Hi ".isset($getData['name']) ? $getData['name'] : "".",</b> <p>Your A/C created successfully.</p> <br /> 
				<h3>Login Details : </h3> <br>
				<p>URL : <a href=".$getData['activation_code']." title='Activation Link'>Activation Link</a></p><br/>  
				<p>User Name : ".isset($getData['user_name']) ? $getData['user_name'] : ""." </p><br/> 
				<p>Password : ".isset($getData['password']) ? $getData['password'] : ""."</p>";
				
				$url = 'http://api.smsgatewayhub.com/smsapi/pushsms.aspx?';
				$data = "user=nixon&pwd=968808&to=".$getData['mobile']."&sid=TWSMSG&msg=".$message."&fl=0&gwid=1";
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $url);
				#curl_setopt($ch, CURLOPT_POST, count($kv));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				
				$result = curl_exec($ch);
				
				curl_close($ch);
			}		
		}
	} */

// ------------------------------------------------------------------------
/* End of file User_validation.php */
/* Location: ./system/helpers/User_validation.php */
