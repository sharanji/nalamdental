<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/backend/assets/css/util.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/backend/assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/backend/assets/css/main.css">
		<script src="<?php echo base_url();?>assets/backend/assets/js/jquery.js"></script>	
		<script src="<?php echo base_url();?>assets/backend/assets/js/jquery.validate.js"></script>
		<script src="<?php echo base_url();?>assets/backend/assets/js/form_validation.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/font-icons/font-awesome/css/font-awesome.css">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>uploads/favicon.png">
		<title><?php echo SITE_NAME;?> | Admin Login</title>
		<style>
			em.error {
				color: red;
				font-style: normal;
			}
			
			.message-error.message {
				text-align: center;
				color: red;
				font-size: 17px;
				position: relative;
				top: 16px;
			}
		
			a.view-password {
				float: right !important;
				padding: 10px !important;
				font-size: 15px !important;
				margin: -42px 0px 0px 0px;
			}
			a.view-password:hover {
				color: #464646;
			}
			
			.forgot-password a, .forgot-password a:hover {
				color: #000;
			}
			.login100-form-btn span {
			  cursor: pointer;
			  display: inline-block;
			  position: relative;
			  transition: 0.5s;
			}

			.login100-form-btn span:after {
			  content: '\00bb';
			  position: absolute;
			  opacity: 0;
			  top: 0;
			  right: -20px;
			  transition: 0.5s;
			}

			.login100-form-btn:hover span {
			  padding-right: 25px;
			}

			.login100-form-btn:hover span:after {
			  opacity: 1;
			  right: 0;
			}
		</style>
	</head>
	<body>
		<div class="limiter">
			<div class="container-login1001" --style="background-image: url(<?php echo base_url();?>uploads/backround_image.png); background-color:#fff!important;">
					<div class="col-md-12" --style="margin-top:-10%;">
						<div class="row">
							<!-- <div class="col-md-6 leftside-login" height="100%" style="background-image: url(<?php echo base_url();?>assets/frontend/food2.jpg); background-color:#fff!important;width:100%;height:100%;"> -->
							<div class="col-md-6 leftside-login"  style="background-image: url(<?php echo base_url();?>uploads/backround_image.png); background-color:#fff!important;width:100%;height:100%; background-size: cover; background-repeat: no-repeat;">
								<span class="welcome-login d-none">WELCOME LOGIN</span>
								<span class="welcome-login-content d-none"> Users can access the application from any computer connected to the Internet. Web based applications can offer competitive advantages to traditional software based Systems allowing businesses to streamline information and processes with reduced costs.</span>
							</div>
							<div class="col-md-6 rightsidelogin" style="background-image: url(<?php echo base_url();?>uploads/bg6.png); background-color:#fff!important;background-size: cover; background-repeat: no-repeat;">
								<div class="wrap-login100 wrap-login100-new" style="background-color: #F7F7F7;">
									<?php
										$success_message = $this->session->flashdata("success_message");
										$error_message = $this->session->flashdata("error_message");
										
										if( $success_message != "")
										{
											?>  
											<div class="message-success message">
												<i class="fa fa-check-circle"></i> <?php echo $success_message;?>
											</div>				
											<?php 
										}
										else if( $error_message != '')
										{
											?>  
											<div class="message-error message">
												<?php echo $error_message;?>
											</div>		
											<?php 
										}
									?>
									<!-- <script type="text/javascript">
										$(".message").delay(10000).fadeOut('slow');
									</script> -->
									
									<form method="post" action="" --id="formValidation" class="login100-form validate-form">
										<span class="login100-form-logo">
											<img src="<?php echo base_url();?>uploads/logo1.png" style="max-height: 45px;"/>
										</span>
										<div class="wrap-input100">
											<input class="input100 newlogin" required type="text" name="email" placeholder="Email /User Name" autocomplete="off">
											<span class="focus-input100" --data-placeholder="&#xf207;"></span>
										</div>

										<div class="wrap-input100 validate-input">
											<input class="input100 newlogin" required type="password" id="password" name="password" placeholder="Password">
											<span --class="focus-input100" --data-placeholder="&#xf191;"> 
												<a class="view-password" href="javascript:void(0);" onclick="viewPassword()">
													<i class="fa fa-eye"></i>
												</a>
											</span>
										</div>

										<div class="container-login100-form-btn">
											<button class="login100-form-btn" style="background-color:darkgoldenrod"><span>Login</span></button>
										</div>
										
                                    	<!-- <span class="downloadlink">Download Our Cashier, Waiter and Captain App <a class="appdownload" href="<?php echo base_url();?>uploads/rayalaseema.apk" download title="App">Download</a></span>
										 -->
										 
										<!-- <br>
										<div class="text-center forgot-password">
											<a href="<?php echo base_url();?>admin/forgot_password" title="Forgot Password">
												Forgot Password?
											</a>
										</div> -->
										
										<script>
											function viewPassword() 
											{
												var x = document.getElementById("password");
												if (x.type === "password") 
												{
													x.type = "text";
												} else {
													x.type = "password";
												}
											}
										</script>
										    
									</form>

									<span class="copy-right">© <?php echo date('Y');?>, <?php echo COMPANY_NAME;?>. All Rights Reserved.</span>
								</div>
								
								
							</div>
							
							<style>
                                        span.downloadlink {
    									float: left;
    									width: 100%;
    									padding-top: 10px;
                                            display: contents;
									}
                                        a.appdownload {
    									color: red;
    									font-weight: bold;
    									text-decoration: underline;
									}
								.app-download a {
										background: linear-gradient(
									45deg
									, #160442, #160442);
										color: #fff;
										padding: 15px 10px;
										border-radius: 5px;
										font-size: 26px;
									}
								.app-download {
									position: absolute;
									top: 104%;
									/* text-align: center; */
								}
								@media (max-width: 400px){
									.app-download a {
										font-size: 20px;
										float: right;
										width: 100%;
										text-align: center;
									}
									.app-download img {
										height:30px!important;
										width:30px!important;}
								}
							</style>
						</div>
					</div>
					
			</div>
		</div>
	</body>
</html>


