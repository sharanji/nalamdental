<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="javascript:void(0)" class="breadcrumb-item">
					Change Password
				</a>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<form action="<?php echo base_url();?>admin/change_password/change_password" class="form-validate-jquery" enctype="multipart/form-data" method="post">
				<fieldset class="mb-3">
					<h3><b>Change Password</b></h3>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group row">
								<label class="col-form-label col-md-4 password"><span class="text-danger">*</span> Current Password</label>
								<div class="col-md-5 password-container">
									<input type="password" name="current_password" id="password" class="form-control" value="" placeholder="Current Password">
									<a class="view-change-password" href="javascript:void(0);" onclick="viewChangePassword('current')">
										<i class="fa fa-eye open_eye_current"></i>
										<i class="fa fa-eye-slash close_eye_current" style="display:none;"></i>
									</a>
									<span class="current_password_mismatched"></span>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-md-4 new_password"><span class="text-danger">*</span> New Password</label>
								<div class="col-md-5 password-container">
									<input type="password" name="new_password" maxlength="16"  id="new_password" class="form-control" value="" placeholder="New Password">
									<span id="check_password_match" class='text-danger'></span>
									<a class="view-change-password" href="javascript:void(0);" onclick="viewChangePassword('new')">
										<i class="fa fa-eye open_eye_new"></i>
										<i class="fa fa-eye-slash close_eye_new" style="display:none;"></i>
									</a>
								</div>
								<!-- password check start here -->
								<span id="strength_message" class='end-supperlier-pwd strength_message'></span>
								<!-- password check end here -->
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-md-4 confirm_new_password"><span class="text-danger">*</span> Confirm New Password</label>
								<div class="col-md-5 password-container">
									<input type="password" name="confirm_new_password" maxlength="16" id="confirm_new_password" class="form-control" value=""  placeholder="Confirm New Password">
									<a class="view-change-password" href="javascript:void(0);" onclick="viewChangePassword('confirm')">
										<i class="fa fa-eye open_eye_confirm"></i>
										<i class="fa fa-eye-slash close_eye_confirm" style="display:none;"></i>
									</a>
									<span class="password_mismatched"></span>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-md-4"></label>
								<div class="col-md-8">
									<a href="<?php echo base_url(); ?>admin/dashboard" class="btn btn-default btn-sm">Close</a>
									<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary ml-1 btn-sm disabled-class channge_pwd_btn btn_save_btn">Save</button>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<span>
								<h4>Strong Password Pattern</h4>
								<ul>
									<li>Minimum 8 characters</li>
									<li>Atleast 1 uppercase letter (A-Z)</li>
									<li>Atleast 1 lowercase letter (a-z)</li>
									<li>Atleast 1 number (0-9)</li>
									<li>Atleast 1 special character (e.g., !, @, #, $)</li>
									<!-- <li>Note : minimum 8 character required</li> -->
								</ul>
							</span>
						</div>
					</div>
				</fieldset>	
			</form>
		</div>
	</div>
 </div>

 <style>
	span.password_mismatched,span.current_password_mismatched {
		float: left;
		color: red;
		padding: 5px 0px 0px 0px;
	}
</style>

 <!-- User Name exist start here -->
 <script>
	function viewChangePassword(val) 
	{
		if(val == 'current')
		{
			var currentPassword = document.getElementById("password");

			if (currentPassword.type === "password") 
			{
				currentPassword.type = "text";
				$('.open_eye_current').hide();
				$('.close_eye_current').show();
			} 
			else 
			{
				currentPassword.type = "password";
				$('.open_eye_current').show();
				$('.close_eye_current').hide();
			}
		}
		else if(val == 'new')
		{
			var new_password = document.getElementById("new_password");

			if (new_password.type === "password") 
			{
				new_password.type = "text";
				$('.open_eye_new').hide();
				$('.close_eye_new').show();
			} 
			else 
			{
				new_password.type = "password";
				$('.open_eye_new').show();
				$('.close_eye_new').hide();
			}
		}
		else if(val == 'confirm')
		{
			var confirm_new_password = document.getElementById("confirm_new_password");

			if (confirm_new_password.type === "password") 
			{
				confirm_new_password.type = "text";
				$('.open_eye_confirm').hide();
				$('.close_eye_confirm').show();
			} 
			else 
			{
				confirm_new_password.type = "password";
				$('.open_eye_confirm').show();
				$('.close_eye_confirm').hide();
			}
		}
	}

	$('#new_password').keyup(function() 
	{
		// If value is not empty
		if ($(this).val().length == 0) {
			// Hide the element
			$('.show-hide').hide();
		} 
		else 
		{
			// Otherwise show it
			$('.show-hide').show();
		}
	}).keyup();

	$(document).ready(function() 
	{
		$('#strength_message').html("");

		$('#new_password').keyup(function() //,#email
		{
			var password = $('#new_password').val();

			if(password.length > 0)
			{
				var message = $('#message');
				var strongPasswordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
				
				if (strongPasswordPattern.test(password)) 
				{
					$('#strength_message').html("Password is strong!");
					$('.strength_message').addClass("strong_class");
					$('.strength_message').removeClass("weak_class");

					$('.btn_save_btn').removeClass('btn-disabled');
				} 
				else 
				{
					$('#strength_message').html("Password is weak!");
					$('.strength_message').addClass("weak_class");
					$('.strength_message').removeClass("strong_class");

					$('.btn_save_btn').addClass('btn-disabled');	
				}
			}
			else
			{
				$('#strength_message').html("");
			}
		});
	});

	function saveBtn(val) 
	{
		var password 				= $("#password").val();
		var new_password 			= $("#new_password").val();
		var confirm_new_password 	= $("#confirm_new_password").val();

		if (new_password === password) {
            $(".new_password").addClass('errorClass');
            $("#check_password_match").html('New password cannot be the same as current password.');
            return false;
        }
		
		if (password && new_password && confirm_new_password) 
		{
			$(".password").removeClass('errorClass');
			$(".new_password").removeClass('errorClass');
			$(".confirm_new_password").removeClass('errorClass');
			return true;
		} 
		else 
		{
			
			if (!password) {
				$(".password").addClass('errorClass');
			} else {
				$(".password").removeClass('errorClass');
			}

			if (!new_password) {
				$(".new_password").addClass('errorClass');
			} else {
				$(".new_password").removeClass('errorClass');
			}

			if (!confirm_new_password) {
				$(".confirm_new_password").addClass('errorClass');
			} else {
				$(".confirm_new_password").removeClass('errorClass');
			}
			return false;
		}
	}
	$('document').ready(function()
	{
		$(".password_mismatched").html('');

		$(".current_password_mismatched").html('');
		
		$('#new_password,#confirm_new_password,#password').on('input', function()
		{
			var user_id 				= $('#user_id').val();
			var current_password 		= $('#password').val();
			var new_password 			= $('#new_password').val();
			var confirm_new_password 	= $('#confirm_new_password').val();

			if( new_password && current_password )
			{
				$.ajax({
					url  : '<?php echo base_url();?>admin/ajaxPasswordCheck',
					type : 'post',
					data : 
					{
						'password'     	       : current_password,
						'new_password'         : new_password
					},
					success: function(response)
					{
						if(response == 0)
						{
							$("#check_password_match").html('New password cannot be the same as current password.');
						}
						else  if(response == 1)
						{
							$("#check_password_match").html('');
						}
						
					}
				});
			}

			if( new_password && confirm_new_password )
			{
				$.ajax({
					url  : '<?php echo base_url();?>admin/ajaxCheckUserPassword',
					type : 'post',
					data : {
						'user_id'              : user_id,
						'password'     	       : current_password,
						'new_password'         : new_password,
						'confirm_new_password' : confirm_new_password
					},
					success: function(response)
					{
						if(response == 0)
						{
							$(".channge_pwd_btn").removeAttr("disabled", "disabled=disabled");
							$(".current_password_mismatched").html('');
						}
						else  if(response == 1)
						{
							$(".channge_pwd_btn").removeAttr("disabled", "disabled=disabled");
							$(".password_mismatched").html('');
						}
						else if(response == 2)
						{
							$(".password_mismatched").html('Password Mismatch!');
							$(".channge_pwd_btn").attr("disabled", "disabled=disabled");
						}
					}
				});
			}
			else
			{

			}
		});


		$(".current_password_mismatched").html('');

		$('#password').on('input', function()
		{
			var user_id = $('#user_id').val();
			var current_password = $('#password').val();
			var new_password = $('#new_password').val();
			var confirm_new_password = $('#confirm_new_password').val();
			
			if( current_password)
			{
				$.ajax({
					url  : '<?php echo base_url();?>admin/ajaxCheckCurrentPassword',
					type : 'post',
					data : {
						'user_id'              : user_id,
						'password'     	       : current_password,
						'new_password'         : new_password,
						'confirm_new_password' : confirm_new_password
					},
					success: function(response)
					{
						if(response == 1)
						{
							$(".channge_pwd_btn").removeAttr("disabled", "disabled=disabled");
							$(".current_password_mismatched").html('');
						}
						else if(response == 2)
						{
							$(".current_password_mismatched").html('Current password is incorrect!');
							$(".channge_pwd_btn").attr("disabled", "disabled=disabled");
						}
					}
				});
			}
			else
			{

			}
		});
	});
	//Customer E-mail End here


</script>
<!-- User Name exist end here -->

<style>
	.password-container .view-change-password {
		position: absolute; /* Position absolutely */
		right: 15px; /* Distance from the right side */
		top: 18px; /* Vertically center the icon */
		transform: translateY(-50%); /* Adjust for the height of the icon */
		cursor: pointer; /* Change cursor to pointer on hover */
		z-index: 1; /* Ensure the icon appears above the input */
	}
</style>