
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/settings" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Home</a>
				<a href="javascript:void(0)" class="breadcrumb-item">Settings</a>
				<span class="breadcrumb-item active">
					Mailer Settings
				</span>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>
</div>
<!-- Page header end-->


<?php $email_type = $edit_data[0]['email_type'];?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
				<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo get_phrase('Sendgrid / SMTP Mailer Settings');?></legend>
				<?php 
					if($email_type == 2)
					{ 
						?>
						<div class="smtp_active">SMTP Active</div>
						<?php 
					}
					else if($email_type == 1)
					{ 
						?>
						<div class="smtp_active">Sendgrid Active</div>
						<?php 
					} 	
				?>
				<fieldset class="mb-3">
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-lg-9">
							<input type="radio" name="email_type" value="1" class="sendgrid_class" <?php if($email_type == 1){?>checked<?php }?>> Sendgrid
							<input type="radio" name="email_type" value="2" class="smtp_class" <?php if($email_type == 2){?>checked<?php }?>> SMTP
						</div>
					</div>
				</fieldset>
			
				<!-- send grid start here-->
				<div class="sendgrid">
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Sendgrid Host');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="sendgrid_host" <?php echo $this->validation;?> class="form-control" required value="<?php echo $edit_data[0]['sendgrid_host'];?>" required>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Sendgrid Port');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="sendgrid_port" <?php echo $this->validation;?> class="form-control" required value="<?php echo $edit_data[0]['sendgrid_port'];?>" required>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Sendgrid User Name');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="sendgrid_username" class="form-control" required value="<?php echo $edit_data[0]['sendgrid_username'];?>" required>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Sendgrid Password');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="sendgrid_password" class="form-control" required value="<?php echo $edit_data[0]['sendgrid_password'];?>" required>
						</div>
					</div>
				</div>
				<!-- send grid end here-->
					
				<!--SMTP start here-->
				<div class="smtp">
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Smtp_Host');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="smtp_host" <?php echo $this->validation;?> class="form-control" required value="<?php echo $edit_data[0]['smtp_host'];?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Smtp_Port');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="smtp_port" <?php echo $this->validation;?> class="form-control mobile_vali" required value="<?php echo $edit_data[0]['smtp_port'];?>" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Smtp_User_Name');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="smtp_username" class="form-control" required value="<?php echo $edit_data[0]['smtp_username'];?>" required>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-sm-3 control-label"><?php echo get_phrase('Smtp_Password');?> <span class="text-danger">*</span></label>
						<div class="col-lg-3">
							<input type="text" name="smtp_password" class="form-control" required value="<?php echo $edit_data[0]['smtp_password'];?>" required>
						</div>
					</div>
				</div>
				<!--SMTP end here-->
				
				
				<div class="form-group">
					<div class="d-flex justify-content-end align-items-center">
						<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default">Close</a>
						<button type="submit" class="btn btn-primary ml-1">Save</button>
					</div>
				</div>
			</form>
		</div>
					
	</div>
</div>
			

<script>
	$( document ).ready(function() 
	{
		var email_type ='<?php echo $email_type;?>';
		
		if(email_type == 1)//sendgrid
		{
			$(".sendgrid").show();
			$(".smtp").hide();
		}
		else if(email_type == 2) //smtp
		{
			$(".smtp").show();
			$(".sendgrid").hide();
		}
		
		$('.sendgrid_class').click(function()
		{
			$(".sendgrid").show();
			$(".smtp").hide();
		});
		
		$('.smtp_class').click(function()
		{
			$(".smtp").show();
			$(".sendgrid").hide();
		});	
	});
</script>


		
