<style type="text/css">
	img {
	  max-width: 60%;
	}

	body {
	  -webkit-font-smoothing: antialiased;
	  -webkit-text-size-adjust: none;
	  width: 100% !important;
	  height: 100%;
	  line-height: 1.6em;
	}

	body {
	  background-color: #f6f6f6;
	}

	@media only screen and (max-width: 640px) {
	  body {
		padding: 0 !important;
	  }

	  h1 {
		font-weight: 800 !important;
		margin: 20px 0 5px !important;
	  }

	  h2 {
		font-weight: 800 !important;
		margin: 20px 0 5px !important;
	  }

	  h3 {
		font-weight: 800 !important;
		margin: 20px 0 5px !important;
	  }

	  h4 {
		font-weight: 800 !important;
		margin: 20px 0 5px !important;
	  }

	  h1 {
		font-size: 22px !important;
	  }

	  h2 {
		font-size: 18px !important;
	  }

	  h3 {
		font-size: 16px !important;
	  }

	  .container {
		padding: 0 !important;
		width: 100% !important;
	  }

	  .content {
		padding: 0 !important;
	  }

	  .content-wrap {
		padding: 10px !important;
	  }

	  .invoice {
		width: 100% !important;
	  }
	}

</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" /> 
		<title><?php echo SITE_NAME;?></title>
	</head>
	<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
		<table class="body-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
			<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
				<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
				<td class="container" width="600" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
					<div class="content" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
						<table class="main" width="100%" cellpadding="0" cellspacing="0" style="box-shadow: rgba(169, 169, 169, 0.4) 5px 5px, rgba(169, 169, 169, 0.3) 10px 10px, rgba(169, 169, 169, 0.2) 15px 15px, rgba(169, 169, 169, 0.1) 20px 20px, rgba(169, 169, 169, 0.05) 25px 25px;
;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #f2f4f6;" bgcolor="#fff">
							
							<tr>
								<td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word">
								<div style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;background:#111822;width:100%;padding:25px 10px;text-align:center;float:left; border-top-left-radius: 10px; border-top-right-radius: 10px;">
								<img style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;border:none;height:60px" src="assets/frontend/img/Yakira 130X130.png" border="0" alt="<?php echo SITE_NAME;?>" title="<?php echo SITE_NAME;?>">

								<p style="color:white;font-size:25px;font-weight:400;">Customer Form Submission</p>
</div>

								</td>
							</tr>
							 
							<tr>
								<td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word">
									<table style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box" width="100%" cellpadding="0" cellspacing="0">
										
										<tbody style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box">
											<tr>
												<td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word">
													<div style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;padding:15px 20px;float:left">
												
														
														
														<?php 
															
															if(isset($forgotUserid)) #Forgot User ID
															{
																?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Your Forgot User ID :
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Password : <?php echo $password;?>
																</p>

																<?php 
															}
															
															if(isset($forgot_password)) #Forgot Password
															{
																?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Your Forgot Password Details :
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Password : <?php echo $password;?>
																</p>

																<?php 
																	if( isset($activation_code) && $activation_code !="")
																	{ 
																		?>
																		<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																			If did't activate your account, Please click and activate your account &nbsp;
																		</p>
																		<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																			<a href="<?php echo $activation_code;?>" target="_blank" class="btn-primary" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;font-size: 15px;color: #FFF;text-decoration: none;font-weight: n;text-align: center;cursor: pointer;display: inline-block;border-radius: 5px;text-transform: capitalize;padding: 10px 20px 10px 20px;background-color: #3a5bbc;margin: 0;">
																				Activate your Account
																			</a>
																		</p>
																		<?php 
																	} 
																?>
																<?php 
															}
															
															if(isset($change_password)) #Change Password
															{
																?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Your Change Password Details :
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Your New Password : <?php echo $password;?>
																</p>
																<?php 
															}
															
															if(isset($contact_us)) #Contact uS
															{
																?>
																
																
																
																			<table style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;width:100%;border-collapse: collapse;">
																				<tr>
																					<td style="text-align:left;padding:10px;">Name</td>
																					<td style="text-align:left;padding:10px;"><?php echo $customer_name;?></td>
																				</tr>
																				<tr>
																					<td style="text-align:left;padding:10px;">Email</td>
																					<td style="text-align:left;padding:10px;"><?php echo $email;?></td>
																				</tr>
																				<tr>
																					<td style="text-align:left;padding:10px;">Phone Number</td>
																					<td style="text-align:left;padding:10px;"><?php echo $mobile_number;?></td>
																				</tr>
																				<tr>
																					<td style="text-align:left;padding:10px;">Location</td>
																					<td style="text-align:left;padding:10px;"><?php echo $subject;?></td>
																				</tr>
																				<tr>
																					<td style="text-align:left;padding:10px;width:100">Message</td>
																					<td style="text-align:left;padding:10px;"><?php echo $message;?></td>
																				</tr>
																			</table>

																
																
															
																<?php 
															}
															
															if(isset($contact_us_training)) #Contact uS
															{
																?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Contact Details :
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Name : <?php echo $cname;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Course : <?php echo $cname;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Description : <?php echo $message;?>
																</p>
																<?php 
															}
															
															if(isset($careers)) #Contact uS
															{
																?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Careers Details :
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Name : <?php echo $name;?>
																</p>
																
																<?php 
																	if( !empty($representing) )
																	{
																		if( $representing == 1 ) # If Yes
																		{
																			?>
																			<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																				Representing a Company : Yes
																			</p>
																			
																			
																			<?php if( !empty($company_name) ){?>
																			<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																				Company Name : <?php echo $company_name;?>
																			</p>
																			<?php } ?>
																			
																			<?php if( !empty($desigation) ){?>
																			<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																				Desigation : <?php echo $desigation;?>
																			</p>
																			<?php } ?>
																			
																			<?php if( !empty($willing_nda) ){?>
																			<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																				Willing to sign the NDA : <?php echo $willing_nda;?>
																			</p>
																			<?php } ?>
																
																			<?php 
																		} 
																		else if( $representing == 2 )  # If No
																		{
																			?>
																		
																			<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																				Representing a Company : No
																			</p>
																			<?php 
																		} 
																	} 
																?>
																
																<?php if( !empty($alternate_mobile_number) ){?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Alternate Mobile Number : <?php echo $alternate_mobile_number;?>
																</p>
																<?php } ?>
																
																<?php if( !empty($preferable_day_contact) ){?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Preferable Day Contact : <?php echo $preferable_day_contact;?>
																</p>
																<?php } ?>
																
																<?php if( !empty($preferable_time_contact) ){?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Preferable Time Contact : <?php echo $preferable_time_contact;?>
																</p>
																<?php } ?>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Country : <?php echo $country;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	City : <?php echo $city;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Key Specialisation 1 and Experience Key Specialisation 1 : <?php echo $key_specialisation_1;?> and <?php echo $experience_key_specialisation_1;?> days
																</p>
																
																<?php if(!empty($key_specialisation_2) && !empty($experience_key_specialisation_2 )){?>
																	<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																		Key Specialisation 2 and Experience Key Specialisation 2 : <?php echo $key_specialisation_2;?>  and <?php echo $experience_key_specialisation_2;?> days
																	</p>
																<?php } ?>
																
																<?php if(!empty($key_specialisation_3) && !empty($experience_key_specialisation_3 )){?>
																	<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																		Key Specialisation 3 and Experience Key Specialisation 3 : <?php echo $key_specialisation_3;?> and <?php echo $experience_key_specialisation_3;?> days
																	</p>
																<?php } ?>
																
																<?php if( !empty($employment_basis) ){?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Employment Basis : <?php echo $employment_basis;?>
																</p>
																<?php } ?>
																
																<?php if( !empty($notice_period) ){?>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Notice Period : <?php echo $notice_period;?> days
																</p>
																<?php } ?>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	Other Information : <?php echo nl2br($other_information);?>
																</p>
																<?php 
															}
															
														?>
														

											
													</div>
												</td>
											</tr>	
										</tbody>
									</table>
								</td>
							</tr>
							
							<tr>
								<td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word;color:#f2f4f6;border-top:4px solid;width:100%">
								  <table style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;background:#111822;color:white;width:100%;margin:0 auto;padding:0;text-align:center;" align="center" width="570" cellpadding="0" cellspacing="0">
									<tbody style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box"><tr>
									  <td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word;padding:35px" align="center">
										
										<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:center;color:white">
											Copyright &copy;  <?php echo date('Y');?> <?php echo SITE_NAME;?>. All Rights Reserved.
										</p>
										
										
										
										
										
										
									  </td>
									</tr>
								  </tbody></table>
								</td>
							  </tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>