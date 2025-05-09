<style>
@media screen {
	
}
.otp-box {
    text-align: center;
}
.splitter {
	padding: 0 5px;
	color: white;
	font-size: 24px;
}
.otp {
	width: 40px;
	height: 40px;
	background-color: lighten($BaseBG, 5%);
	border: none;
	line-height: 50px;
	text-align: center;
	font-size: 30px;
	color: #e54750;
	margin: 0 2px;
}
.prompt {
	margin-bottom: 20px;
	font-size: 20px;
	color: white;
}
form.digit-group.otp-box {
    background: #ff3f3f;
    width: 400px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 15px;
}

input.verify-btn-new {
    background:#fafafa;
	color:black;
    width: 78%;
    margin-top: 13px;
    padding: 10px;
    font-size: 15px;
}
input.verify-btn-new:hover {
    background: #efe9e9;
}

@media screen and (max-width: 320px) {
    .digit-group.otp-box{
        width: 100%!important;
    }
	
}
@media screen and (max-width: 768px) {
    .digit-group.otp-box{
        width: 100%!important;
    }
.verify-btn-new
	{
		width:88%!important;
	}
}

</style>
<body id="register_bg">
	<div id="register1">
		<aside>
			<figure>
				<a href="<?php echo base_url();?>">
					<img src="<?php echo base_url();?>uploads/logo.png" width="200" height="80" alt="best dental hospial hosur">
				</a>
			</figure>
			
			
			<!--<form autocomplete="off" id="formValidation" method="post" style="margin-top: 40px;">
				<div class="form-group">
					<div class="otp-sent" style="color:#fff;">OTP is sent to your Mobile Number</div>
				</div>
				<div class="form-group">
					<input type="text" name="otp_number" id="otp_number" required class="form-control mobile_vali" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="6" maxlength="6" placeholder="Please enter your OTP Number.">
					<i class="icon_mail_alt"></i>
				</div>
				
				<input type="submit" class="btn btn-block submit-btn" name="sub" value="Submit">
				<div class="text-center mt-2">
					<small>
						<a href="<?php echo base_url();?>cancel-otp.html"></i> Cancel </a> 
					</small>
				</div>
			</form>-->
			<form method="POST" class="digit-group otp-box" data-group-name="digits" data-autosubmit="false" style="margin-top: 40px;" autocomplete="off">
				
				<div class="form-group">
					<div class="otp-sent" style="color:#fff;padding-bottom:20px;"><h3 style="font-weight:400;">Please Enter Your OTP</h3></div>
				</div>
				<div class="form-group">
				<input type="number" required id="digit-1" class="otp mobile_vali" name="digit_1" maxlength="1" />
				<input type="number" required id="digit-2" class="otp mobile_vali" name="digit_2" maxlength="1" />
				<input type="number" required id="digit-3" class="otp mobile_vali" name="digit_3" maxlength="1" />
				<input type="number" required id="digit-4" class="otp mobile_vali" name="digit_4" maxlength="1" />
				<input type="number" required id="digit-5" class="otp mobile_vali" name="digit_5" maxlength="1" />
				<input type="number" required id="digit-6" class="otp mobile_vali" name="digit_6" maxlength="1" />
				</div>




				
				
				<input type="submit" class="btn btn-block submit-btn verify-btn-new" name="sub" value="Verify" width="100%">

				<div class="text-center mt-2">
					<small style="font-size:14px;color:#fff;">
						<a href="<?php echo base_url();?>cancel-otp.html"  style="border: 1px solid #ddd;padding: 5px 30px;position: relative;top: 7px;background: #fff;color: red!important;border-radius: 4px;"></i> Cancel </a> 
					</small>
				</div>
			</form>
		</aside>
	</div>

	<script>
  $(document).ready(function() {
    // Get all OTP input fields
    const otpInputs = $(".otp");

    // Attach input, change, and keydown event listeners to all OTP inputs
    otpInputs.on("input change keydown", function(e) {
      const input = $(this);
      const inputValue = input.val();

      // Remove any non-digit characters
      const sanitizedValue = inputValue.replace(/\D/g, '');

      // Limit the input to only one character
      const oneCharacterValue = sanitizedValue.slice(0, 1);

      // Update the input value to only contain one character
      input.val(oneCharacterValue);

      // Find the next and previous input elements
      const nextInput = input.next('.otp');
      const previousInput = input.prev('.otp');

      if (e.type === "keydown" && e.keyCode === 8 && inputValue === '') {
        // If the backspace key is pressed and the input is empty, move focus to the previous input
        if (previousInput.length > 0) {
          previousInput.focus();
          // Clear the value of the current input if moving to the previous input
          input.val('');
        }
      } else if (e.type === "input" && oneCharacterValue) {
        // If a single character is entered, move focus to the next input
        if (nextInput.length > 0) {
          nextInput.focus();
        }
      }
    });
  });
</script> 













<!-- <script>
	$('.digit-group').find('input').each(function() {
	$(this).attr('maxlength', 1);
	$(this).on('keyup', function(e) {
		var parent = $($(this).parent());
		
		if(e.keyCode === 8 || e.keyCode === 37) {
			var prev = parent.find('input#' + $(this).data('previous'));
			
			if(prev.length) {
				$(prev).select();
			}
		} else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
			var next = parent.find('input#' + $(this).data('next'));
			
			if(next.length) {
				$(next).select();
			} else {
				if(parent.data('autosubmit')) {
					parent.submit();
				}
			}
		}
	});
});
</script> -->
	<!--Forgot Password -->
</body>
