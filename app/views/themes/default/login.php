<body class="grocino-home home2 grocino-about">
<!-- Login form start here -->
<div class="grocino-form grocino-form-new">
	<div class="container">
		<div class="row">	
			<div class="col-lg-6 col-md-8 col-sm-10 col-12 mx-auto">
				<div class="grocino-heading">
					<h1 class="heading_text"> Sign In </h1> 
					<div class="graph graph-sm">
						<img src="<?php echo base_url();?>assets/frontend/img/about/graphic.png" alt="Graph" title="Graph">
					</div>
				</div>
				<form method="post" class="form-signin">
					<div class="ui form">
						<div class="ui left icon input field w-100">
							<input type="text" name="mobile_number" class="mobile_vali" minlength="10" maxlength="10" placeholder="Enter Mobile Number" required />
							<i class="uil uil-mobile-android-alt icon"></i>
						</div>
						<!-- <div class="ui left icon input w-100 field">
							<input type="password" name="password" placeholder="Enter Password" required />
							<i class="uil uil-unlock icon"></i>
						</div> -->
					</div>
					<!-- <div class="text-end mt-4">
						<span><a href="reset-password.html"> Forget Password?</a></span>
					</div> -->
					<button class="btn btn-orange w-100" type="submit">Sign In</button>
					<!-- <div class="text-center">
						<span>If Already have account? <a href="register.html"> Create Account</a></span>
					</div> -->
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Login form end here -->

</body>