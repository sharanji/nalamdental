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
						<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
							 <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<td class="alert alert-warning" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #a5cf4d; margin: 0; padding: 5px;" align="center" bgcolor="#a5cf4d" valign="top">
								   <a class="navbar-brand" href="<?php echo base_url(); ?>" title="<?php echo SITE_NAME;?>" target="_blank">
										<img style="width:150px;height:100px;" class="img-responsive" src="<?php echo base_url(); ?>uploads/logo.png" alt="<?php echo SITE_NAME;?>" title="<?php echo SITE_NAME;?>">
									</a>
								</td>
							 </tr>
							<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<td class="content-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
									<table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;margin: -28px 3px 2px 3px;">
										<tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
											<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 10px;" valign="top">
												<?php 
													if(isset($approverAdmin)) #approverAdmin
													{
														?>
														<strong style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-transform:capitalize ;box-sizing: border-box; font-size: 14px; margin: 0;">
															Hello <?php echo ucfirst($name);?>,
														</strong> 
														<?php
													}
													else if(isset($name)) 
													{
														?>
														Hi 
														<strong style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-transform:capitalize ;box-sizing: border-box; font-size: 14px; margin: 0;">
															<?php echo ucfirst($name);?>,
														</strong> 
														<?php 
													} 
													else 
													{
														?>
													   Dear 
														<strong style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-transform:capitalize ;box-sizing: border-box; font-size: 14px; margin: 0;">
															User,
														</strong> 
														<?php 
													} 
												?>
											</td>
										</tr>
										
										<?php 
											if(isset($approverAdmin)) #approverAdmin
											{
												?>
												<tr>
													<td class="content-block" valign="top" style="font-weight: 600;color: #a5cf4d;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														We have received your Payment of
															<?php if($period == 1){ ?>
																<?php echo $pay_amount;?>
															<?php }else{ ?>
																Rs.<?php echo $pay_amount;?>
															<?php } ?>
															for the 
															<?php 
																if($membership_type == 3 || $membership_type==4) #3=>Recruiter, 4=>Employer
																{
																	foreach($this->timePeriod as $key=>$val)
																	{ 
																		if($period ==  $key)
																		{
																			echo $val;
																		}
																	} 
																}
																else if($membership_type == 2) #2=>Institute
																{
																	foreach($this->instituteTimePeriod as $key=>$val)
																	{ 
																		if($period ==  $key)
																		{
																			echo $val;
																		}
																	} 
																} 
															?>
															Plan. Many Thanks!
														Your membership has been Activated.
													</td>
												</tr>
												
												</br>
												
												<?php if(!empty($company_name)){?>
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Company Name : <?php echo ucfirst($company_name);?>
													</td>
												</tr>
												<?php } ?>
												
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Login credentials
													</td>
												</tr>
												
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Plan Activation start date : <?php echo date('d-M-Y h:i:s',$payment_confirm_date);?>
													</td>
												</tr>
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Valid till : <?php echo $validTill;?>
													</td>
												</tr>
												<?php 
											}
											
											if(isset($employee_registeration))
											{
												?>
												<tr>
													<td class="content-block" valign="top" style="font-weight: 600;color: #a5cf4d;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Your Registration Details :
													</td>
												</tr></br>
												
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Company Name : <?php echo ucfirst($company_name);?>
													</td>
												</tr>
												
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Designation : <?php echo $designation;?>
													</td>
												</tr>
												
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														E-Mail : <?php echo $email;?>
													</td>
												</tr>
												
												<tr>
													<td class="content-block" valign="top" style="color: #50504f;font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 15px;vertical-align: top;margin: 0;padding: 0 0 10px;">
														Password : <?php echo $password;?>
													</td>
												</tr>
												<?php 
											}
										?>
										
										<tr>
											<td class="content-block" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; color:#a5cf4d; text-align:center;box-sizing: border-box; font-size: 17px; vertical-align: top; margin: 0; padding: 10px 0 0 10px;" valign="top">
												<?php 
													if( isset($approverAdmin) ) #approverAdmin
													{
														?>
														Admin Team <?php echo SITE_NAME;?>,
														<?php 
													}
													else 
													{ 
														?>
														Thanks for <?php echo SITE_NAME;?>,
														<?php 
													} 
												?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							 
							<tr>
								<td class="alert alert-warning" style="padding:10px 0px;" align="center" bgcolor="#252425" valign="top">
									<a style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;text-decoration: none;font-size: 16px;vertical-align: top;color: #fff;font-weight: 500;text-align: center;margin: 0;padding: 0px 0px;" href="<?php echo base_url(); ?>" target="_blank">
										<?php echo SITE_NAME;?>
									</a>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>