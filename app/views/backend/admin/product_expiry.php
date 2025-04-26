<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo SITE_NAME;?> | Customer Feedback</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/assets/css/bootstrap.min.css">
		<script src="<?php echo base_url();?>assets/backend/assets/js/jquery.js"></script>	
		<script src="<?php echo base_url();?>assets/backend/assets/js/jquery.validate.js"></script>
		<script src="<?php echo base_url();?>assets/backend/assets/js/form_validation.js"></script>
		
		<link href="https://fonts.googleapis.com/css?family=Nunito:600,700,900" rel="stylesheet">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>uploads/favicon.png">
		
		<script src="<?php echo base_url();?>assets/backend/assets/js/popper.min.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/backend/assets/css/font-awesome.min.css">
		<script src="<?php echo base_url();?>assets/backend/assets/js/bootstrap.min.js"></script>
		<!-- theme JS files -->
		<link href="<?php echo base_url();?>assets/backend/toastr/toastr.css" type="text/css"  rel="stylesheet" />
		<script src="<?php echo base_url();?>assets/backend/toastr/toastr.js"></script>
		<style>
			.expiry-new-box {
				width: 75%;
				margin: 0 auto;
				position: relative;
				top: 100px;
				background: #fff;
				border-radius: 10px;
				box-shadow: 3px 2px 10px #ddd;
			}
			.logo-pro-expiry {
				margin: 0 auto;
				float: left;
				width: 100%;
				text-align: center;
			}
			.left-side-expiry {
				padding: 25px;
			}
			.right-side-pro-expiry {
				--background: #6eaca1;
				padding:0px;
			}
			.right-side-pro-expiry img {
				float: left;
				width: 100%;
				position: relative;
				top: 56px;
				padding: 0;
				margin: 0;
			}
			.star{color:red;}
			em#product_start_date-error, #product_expiry-error, #before_expire_days-error {
				color: red;
			}
			.access-buttons {
				margin-top: 20px;
			}
			a.cancelbtn-new-expiry {
				background: #fff;
				padding: 6px;
				color: #e94141;
				border: 1px solid #df7c7c;
				text-align: center;
				font-size: 15px;
				border-radius: 5px;
			}
			a.cancelbtn-new-expiry:hover {
				background: #e94141;
				color:#fff;
				transition: 1s;
				
			}
			p.pro-expiry-title {
				position: absolute;
				top: -50px;
				text-align: center;
				width: 100%;
				font-size: 26px;
				font-weight: 600;
				color: #4a9c8d;
			}
			
		</style>
	</head>
	<body style="background-color:#dcf4ff;">
		<div class="container">
			<div class="expiry-new-box">
				<p class="pro-expiry-title">Product Expiry
				</p>
				<script type="text/javascript">
					<?php
						$msg = $this->session->flashdata("success_message");
						$flash_message = $this->session->flashdata("flash_message");
						$error_message = $this->session->flashdata("error_message");
						
						if( $msg != "" || $flash_message !="" )
						{
							if($msg !="")
							{
								$message = $msg;
							}else if($flash_message !="")
							{
								$message = $flash_message;
							}
							?>  
							toastr.success('<?php echo $message;?>');			
							<?php 
						}
						
						if( $error_message != '')
						{
							?>  
							toastr.error('<?php echo $error_message;?>');			
							<?php 
						}
					?>
				</script>
				<form method="post" action="" id="formValidation" class="login100-form validate-form">				
					<link href="<?php echo base_url();?>assets/frontend/css/jquery-ui.css" rel="stylesheet">
					<script src="<?php echo base_url();?>assets/frontend/js/jquery-ui.js"></script>

					<script>
						$( function() 
						{
							$("#product_start_date").datepicker({
								changeMonth: true,
								changeYear: true,
								yearRange: "<?php echo date('Y'); ?>: <?php echo date('Y') + 10; ?>",
								dateFormat: "dd-M-yy"	
							});
							
							$("#product_expiry").datepicker({
								changeMonth: true,
								changeYear: true,
								yearRange: "<?php echo date('Y'); ?>: <?php echo date('Y') + 10; ?>",
								dateFormat: "dd-M-yy"	
							});
						});
					</script>
					
					<?php 
						$product_start_date = $this->db->get_where('settings' , array('type' =>'product_start_date'))->row()->description;
						$product_end_date = $this->db->get_where('settings' , array('type' =>'product_end_date'))->row()->description;
						$before_expire_days = $this->db->get_where('settings' , array('type' =>'before_expire_days'))->row()->description;
					?>
					
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6 right-side-pro-expiry">
								<img src="<?php echo base_url();?>uploads/pro-expiry.png">
							</div>
							<div class="col-md-6 left-side-expiry">
								<div class="logo-pro-expiry">
									<img src="<?php echo base_url();?>uploads/logo.png" alt="<?php echo SITE_NAME;?>" class="img-responsive" height="70px" width="248px">
								</div>
								<div class="form-group mb-0">
									<label style="color:#6e6d6d;margin-bottom:5px;margin-top:10px;font-size:14px;font-weight: 500;">Product Start Date <span class="star">*</span> </label>
									<input type="text" name="product_start_date" required id="product_start_date" class="form-control bacround-class" value="<?php echo !empty($product_start_date) ? date("d-M-Y",$product_start_date) : date("d-M-Y");?>" placeholder="Product start date">
								</div>
								
								<div class="form-group mb-0">
									<label style="color:#6e6d6d;margin-bottom:5px;margin-top:10px;font-size:14px;font-weight: 500;">Product End Date <span class="star">*</span> </label>
									<input type="text" name="product_end_date" required id="product_expiry" class="form-control bacround-class" value="<?php echo !empty($product_end_date) ? date("d-M-Y",$product_end_date) : date("d-M-Y");?>" placeholder="Product end date">
								</div>
								
								<div class="form-group mb-0">
									<label style="color:#6e6d6d;margin-bottom:5px;margin-top:10px;font-size:14px;font-weight: 500;">Before Expiry Days <span class="star">*</span> </label>
									<input type="text" name="before_expire_days" required id="before_expire_days" class="form-control bacround-class" value="<?php echo !empty($before_expire_days) ? $before_expire_days : 0;?>" placeholder="Before expire day">
									<span>Ex: 30</span>
								</div>
								
								<div class="access-buttons">
									<div class="row">
										<div class="col-md-6 mb-2">
											<a href="<?php echo base_url();?>" class="cancelbtn cancelbtn-new-expiry deep-purple btn-block">Cancel</a>
										</div>
										<div class="col-md-6">
											<button type="submit"  class="btn btn-primary deep-purple btn-block">Update</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>
