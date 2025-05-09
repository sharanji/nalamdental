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
						<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #f2f4f6;" bgcolor="#fff">

							<tr>
								<td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word">
								  <div style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;background:#644222;width:100%;padding:25px 10px;text-align:center;float:left">
									<a style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#3869d4" href="<?php echo base_url(); ?>">
									<div style="color:white; font-family: Papyrus, fantasy;  text-align: center;  font-size: 24px; font-weight: bold;">
										<?php echo SITE_NAME ?>
									</div>

									</a>
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
												
														<h2 style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box">
															<?php /*
																if( isset($full_name) ) 
																{
																	?>
																	Dear <?php echo ucfirst($full_name);?>,
																	<?php 
																} 
																else 
																{
																	?>
																	Hi,
																	<?php 
																} 
															*/ ?>
															Hi,
														</h2>
														
														<?php 
															if(isset($contact_us)) #Forgot User ID
															{
																?>
																<!-- <p style="padding-bottom:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<strong>Enquiry from customer</strong>
																</p> -->
																
																<p style="padding-bottom:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<strong>Contact Details :</strong>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Name :</b> <?php echo $name;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Email :</b> <?php echo $email;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Phone Number :</b>  <?php echo $phone;?>
																</p>
                                                                <p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Location Name :</b>  <?php echo $location_name;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Message :</b>  <?php echo $message;?>
																</p>
																
																<?php 
															}
															if(isset($basketBall)) #Forgot User ID
															{
																?>
																<!-- <p style="padding-bottom:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<strong>Enquiry from customer</strong>
																</p> -->
																
																<p style="padding-bottom:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<strong>Contact Details :</strong>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Sports Type :</b> <?php echo $sports_type;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Name :</b> <?php echo $name;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Email :</b> <?php echo $email;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Phone Number :</b>  <?php echo $phone;?>
																</p>
                                                                <p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Location Name :</b>  <?php echo $location_name;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Message :</b>  <?php echo $message;?>
																</p>
																
																<?php 
															}
															if(isset($basketBall) || isset($indoorSportsHalls) || isset($indoorSportsHalls)) #Forgot User ID
															{
																?>
																<!-- <p style="padding-bottom:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<strong>Enquiry from customer</strong>
																</p> -->
																
																<p style="padding-bottom:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<strong>Contact Details :</strong>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Sports Type :</b> <?php echo $sports_type;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Name :</b> <?php echo $name;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	 <b>Email :</b> <?php echo $email;?>
																</p>
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Phone Number :</b>  <?php echo $phone;?>
																</p>
                                                                <p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Location Name :</b>  <?php echo $location_name;?>
																</p>
																
																<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left">
																	<b>Message :</b>  <?php echo $message;?>
																</p>
																
																<?php 
															}
															
														?>
														
														<p style="padding-top:20px;font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left;margin:25px 0 10px 0">
															<b>Thanks for joining with us</b>
														</p>
											
													</div>
												</td>
											</tr>	
										</tbody>
									</table>
								</td>
							</tr>
							
							<tr>
								<td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word;color:#f2f4f6;border-top:4px solid #0c0d0e;width:100%">
								  <table style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;background: #644222;width:100%;margin:0 auto;padding:0;text-align:center;" align="center" width="570" cellpadding="0" cellspacing="0">
									<tbody style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box"><tr>
									  <td style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;word-break:break-word;padding:35px" align="center">
										
										<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:center;color:#aeaeae">
											Copyright &copy; 2024  <?php echo SITE_NAME;?>. All Rights Reserved.
										</p>
										
										<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:center;color:#aeaeae">
											<?php echo COMPANY_NAME;?>
										</p>
										
										<p style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:center;color:#aeaeae">
											<span style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box">
												<?php echo PHONE1;?><?php if(!empty(PHONE2)){echo ", ".PHONE2;}?>
											</span>
											<a href="mailto:<?php echo CONTACT_EMAIL;?>" target="_blank" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#aeaeae;text-decoration:none">
												,<?php echo CONTACT_EMAIL;?>
											</a>
										</p>
										<?php /*
										<div style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;width:130px">
											
											<ul style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;line-height:1.4;text-align:left;list-style:none;margin:0;padding:0">
											
												<li style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;float:left;padding:2px">
												  <a href="<?php echo FACEBOOK;?>" title="Facebook" target="_blank" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#3869d4">
													<img src="<?php echo base_url();?>assets/social_icons/facebook.png" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;border:none" border="0" alt="best dental hospial hosur">
												  </a>
												</li>
											
												<li style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;float:left;padding:2px">
												  <a href="<?php echo TWITTER;?>" title="Twitter" target="_blank" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#3869d4">
													<img src="<?php echo base_url();?>assets/social_icons/twitter.png" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;border:none" border="0" alt="best dental hospial hosur">
												  </a>
												</li>
											
												<li style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;float:left;padding:2px">
												  <a href="<?php echo LINKEDIN;?>" title="Linkedin" target="_blank" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#3869d4">
													<img src="<?php echo base_url();?>assets/social_icons/linkedin.png" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;border:none;width:22px;height:15px;" border="0" alt="best dental hospial hosur">
												  </a>
												</li>
												
												<li style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;float:left;padding:2px">
												  <a href="<?php echo GOOGLE_PLUS;?>" title="Google Plus" target="_blank" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#3869d4">
													<img src="<?php echo base_url();?>assets/social_icons/google_plus.png" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;border:none" border="0" alt="best dental hospial hosur">
												  </a>
												</li>
												
												<li style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;float:left;padding:2px">
												  <a href="<?php echo INSTAGRAM;?>" title="Instagram" target="_blank" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;color:#3869d4">
													<img src="<?php echo base_url();?>assets/social_icons/instagram.png" style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;box-sizing:border-box;border:none;width:22px;height:15px;" border="0" alt="best dental hospial hosur" >
												  </a>
												</li>
											
											</ul>
										  
										</div> */?>
										
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