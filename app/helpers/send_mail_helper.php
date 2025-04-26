<?php
if ( ! function_exists('Send_Mail'))
{
	function Send_Mail($to="", $subject="", $body="", $from="", $fromName="")
	{
		require_once 'class.phpmailer.php';
		
		$mail = new PHPMailer();
		$mail->IsSMTP(true); // SMTP
		$mail->SMTPOptions = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			],
		];
		
		$mail->SMTPAuth   = true;  #SMTP authentication
		#$mail->SMTPDebug = 1;
		$mail->SMTPDebug = false;
		$mail->SMTPSecure= "tls";
		
		if( EMAIL_TYPE == 1 ) #Sendgrid
		{
			$mail->Mailer = "sendmail";
			#$mail->SMTPSecure = 'ssl';
			$mail->Host       = SMTP_HOST; # Amazon SES server, note "tls://" protocol
			$mail->Port       = SMTP_PORT; # set the SMTP port
			$mail->Username   = SMTP_USERNAME;  # SES SMTP  username
			$mail->Password   = SMTP_PASSWORD;  # SES SMTP password
		}
		else if( EMAIL_TYPE == 2 ) #SMTP
		{ 
			$mail->Mailer = "smtp";
			#$mail->SMTPSecure = 'ssl';
			$mail->Host       = SMTP_HOST; #Amazon SES server, note "tls://" protocol
			$mail->Port       = SMTP_PORT; #set the SMTP port
			$mail->Username   = SMTP_USERNAME;  #SES SMTP  username
			$mail->Password   = SMTP_PASSWORD;  #SES SMTP password
		}
		
		$mail->SetFrom($from, $fromName);
		//$mail->AddReplyTo($from,'Sales');
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$address = $to;
		//$mail->AddAddress($address, $to);
		$mail->AddAddress($address, $from);
		
		if(!$mail->Send())
		{
			return 2;
		}
		else
		{ 
			return 1;
		}
	}
}
?>