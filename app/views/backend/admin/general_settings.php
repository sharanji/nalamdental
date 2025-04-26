<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/settings" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="javascript:void(0)" class="breadcrumb-item">
					General Settings
				</a>
			</div>
			
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
		<div class="text-right new-import-btn">
			<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm"><i class="icon-arrow-left16"></i> Back</a>
						
		</div>
	</div>
</div>
<!-- Page header end-->


<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
	<div class="card-body"><!-- Card start-->
		
		<?php 
			$activeGeneral = $activeEmailContact = 
			$activeImageSettings = $activeCurrencySettings = 
			$activeSystemSettings = $activeThemeSettings = '';
			if( isset($type) )
			{
				if( $type == 'general-settings' )
				{
					$activeGeneral = 'active';
				}
				else if( $type == 'email-contact-settings' )
				{
					$activeEmailContact = 'active';
				}
				else if( $type == 'image-settings' )
				{
					$activeImageSettings = 'active';
				}
				else if( $type == 'currency-settings' )
				{
					$activeCurrencySettings = 'active';
				}
				else if( $type == 'theme' )
				{
					$activeThemeSettings = 'active';
				}
				else if( $type == 'system-settings' )
				{
					$activeSystemSettings = 'active';
				}
			}
		?>
		
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item <?php echo $activeGeneral;?>">
				<a href="<?php echo base_url(); ?>admin/system_settings/general-settings" class="nav-link">General Settings</a>
			</li>
			
			<li class="nav-item <?php echo $activeEmailContact;?>">
				<a href="<?php echo base_url(); ?>admin/system_settings/email-contact-settings" class="nav-link">E-Mail & Contact Settings</a>
			</li>
			
			<li class="nav-item <?php echo $activeImageSettings;?>">
				<a href="<?php echo base_url(); ?>admin/system_settings/image-settings" class="nav-link">Image Settings</a>
			</li>
			
			<?php /*
			<li class="nav-item <?php echo $activeCurrencySettings;?>">
				<a href="<?php echo base_url(); ?>admin/system_settings/currency-settings" class="nav-link">Currency Settings</a>
			</li>
			
			<li class="nav-item <?php echo $activeSystemSettings;?>">
				<a href="<?php echo base_url(); ?>admin/system_settings/system-settings" class="nav-link">System Settings</a>
			</li>
			
			<li class="nav-item <?php echo $activeThemeSettings;?>">
				<a href="<?php echo base_url(); ?>admin/system_settings/theme" class="nav-link">Theme Setup</a>
			</li>
			*/ ?>
		</ul>

		<!-- Tab panes -->
		<div class="card-body-1">
			<div class="tab-content">
				
				<!-- General Settings Start -->
				<?php 
					if(isset($type) && $type == 'general-settings')
					{
						?>
						<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Site Name <span class="text-danger">*</span></label>
									<input type="text" name="system_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo $this->db->get_where('settings' , array('type' =>'system_name'))->row()->description;?>" placeholder="">
								</div>
								 
								<div class="form-group col-md-3">
									<label class="col-form-label">Site Title </label>
									<input type="text" name="system_title" <?php echo $this->validation;?> class="form-control" value="<?php echo $this->db->get_where('settings' , array('type' =>'system_title'))->row()->description;?>" placeholder="">
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Company Name </label>
									<input type="text" name="company_name" <?php echo $this->validation;?> class="form-control" value="<?php echo $this->db->get_where('settings' , array('type' =>'company_name'))->row()->description;?>" placeholder="">
								</div>
							</div>
							<?php /*
							<input type="hidden" name="welcome_content" value="<?php echo $this->db->get_where('settings' , array('type' =>'welcome_content'))->row()->description;?>" placeholder="">
							<input type="hidden" name="company_youtube_url" class="form-control" value="<?php echo $this->db->get_where('settings' , array('type' =>'company_youtube_url'))->row()->description;?>" placeholder="">
							
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">BackUp Database Time 1 <span class="text-danger">*</span></label>
									<?php $backup_morning = $this->db->get_where('settings' , array('type' =>'backup_morning'))->row()->description;?>
									<select name="backup_morning" required class="form-control searchDropdown">
										<option value="">Select Time</option>
										<?php 
											foreach($this->backupTime as $key=>$value)
											{
												$selected="";
												if(isset($backup_morning) && $backup_morning == $key)
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $value;?>" <?php echo $selected; ?>><?php echo $value;?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								 
								<div class="form-group col-md-3">
									<label class="col-form-label">BackUp Database Time 2 </label>
									<?php $backup_evening = $this->db->get_where('settings' , array('type' =>'backup_evening'))->row()->description;?>
									<select name="backup_evening" class="form-control searchDropdown">
										<option value="">Select Time</option>
										<?php 
											foreach($this->backupTime as $key1=>$value1)
											{
												$selected="";
												if(isset($backup_evening) && $backup_evening == $key1)
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $key1;?>" <?php echo $selected; ?>><?php echo $value1;?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								
								
							</div>
							*/ ?>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">GST Number </label>
									<input type="text" name="gst_number" class="form-control" value="<?php echo $this->db->get_where('settings' , array('type' =>'gst_number'))->row()->description;?>" placeholder="">
								</div>
								<?php 
									$getGstStateNumber = $this->db->query("select state_id,state_code,state_number from geo_states where active_flag='Y' order by state_name asc")->result_array();
								
									$gst_state_number =  $this->db->get_where('settings',array('type' =>'gst_state_number'))->row()->description;
									
								?>
								<div class="form-group col-md-3">
									<label class="col-form-label">GST State Number </label>
									<select name="gst_state_number" id="gst_state_number" class="form-control searchDropdown"  required>
										<option value="">- Select State Number -</option>
										<?php 
											foreach($getGstStateNumber as $row)
											{ 
												$selected="";
												if( isset($gst_state_number) && $gst_state_number == $row['state_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_code'];?> - <?php echo $row['state_number'];?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">License Number </label>
									<input type="text" name="license_number" class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'license_number'))->row()->description;?>" placeholder="">
								</div>
								
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">CIN <!-- Corporate Identification Number --></label>
									<input type="text" name="cin_number" id="cin_number" class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'cin_number'))->row()->description;?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">FSSAI Number </label>
									<input type="text" name="fssai_number" id="fssai_number" class="form-control" value="<?php echo $this->db->get_where('settings', array('type' => 'fssai_number'))->row()->description;?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Company Account</label>
									<?php 
										$company_account = $this->db->get_where('settings' , array('type' =>'company_account'))->row()->description;
									?>
									<textarea name="company_account" rows="3" cols="3" class="form-control" placeholder=""><?php echo $company_account;?></textarea>
								</div>
								<!--<div class="form-group col-md-5">
									<label class="col-form-label">Birthday Message</label>
									<?php 
										$birthday_message = $this->db->get_where('settings' , array('type' =>'birthday_message'))->row()->description;
									?>
									<textarea name="birthday_message" rows="3" cols="3" class="form-control" placeholder=""><?php echo $birthday_message;?></textarea>
								</div>-->
							</div>
							
							
							<div class="row" style="text-align:right;">
								<div class="col-md-7">
								</div>
								<div class="col-md-5">
									<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default waves-effect">Close</a>
									<button type="submit" class="btn btn-primary ml-1">Save</button>
								</div>
							</div>
						</form>
						<?php 
					} 
				?>
				<!-- General Settings Start -->
				
				<!-- EMail Contact Settings Start -->
				<?php 
					if(isset($type) && $type == 'email-contact-settings')
					{
						?>
						<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Contact E-Mail</label>
									<input type="email" class="form-control" name="contact_email" value="<?php echo $this->db->get_where('settings' , array('type' =>'contact_email'))->row()->description;?>" required />
								</div>
								 
								<div class="form-group col-md-3">
									<label class="col-form-label">Webmaster E-Mail </label>
									<input type="email" class="form-control" name="webmaster_email" value="<?php echo $this->db->get_where('settings' , array('type' =>'webmaster_email'))->row()->description;?>" required />
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">No-reply E-Mail</label>
									<input type="email" class="form-control" name="no_reply_email" value="<?php echo $this->db->get_where('settings' , array('type' =>'no_reply_email'))->row()->description;?>" required />
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Address-1</label>
									<textarea class="form-control" rows="4" cols="4" name="address" autocomplete="off"><?php echo $this->db->get_where('settings' , array('type' =>'address'))->row()->description;?></textarea>
								</div>
								 
								<div class="form-group col-md-3">
									<label class="col-form-label">Address-2</label>
									<textarea class="form-control" rows="4" cols="4" name="address2" autocomplete="off"><?php echo $this->db->get_where('settings' , array('type' =>'address2'))->row()->description;?></textarea>
								</div>
							</div>
							
							
							<input type="hidden" name="address3" value="<?php echo $this->db->get_where('settings' , array('type' =>'address3'))->row()->description;?>">
							<input type="hidden" name="address4" value="<?php echo $this->db->get_where('settings' , array('type' =>'address4'))->row()->description;?>">
								
							<?php /*
							<div class="form-group row">
								<label class="col-form-label col-lg-3">Address-3</label>
								<div class="col-lg-9">
									<textarea class="form-control" rows="5" cols="5" name="address3"><?php echo $this->db->get_where('settings' , array('type' =>'address3'))->row()->description;?></textarea>
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-3">Address-4</label>
								<div class="col-lg-9">
									<textarea class="form-control" rows="5" cols="5" name="address4"><?php echo $this->db->get_where('settings' , array('type' =>'address4'))->row()->description;?></textarea>
								</div>
							</div>
							*/ ?>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Contact Phone 1</label>
									<input type="number" class="form-control" name="phone" minlength="10" maxlength="10" placeholder="EX : 9632587410" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?php echo $this->db->get_where('settings' , array('type' =>'phone'))->row()->description;?>" required>
								</div>
								 
								<div class="form-group col-md-3">
									<label class="col-form-label">Contact Phone 2</label>
									<input type="number" class="form-control" name="phone2" minlength="10" maxlength="10" placeholder="EX : 9632587410" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?php echo $this->db->get_where('settings' , array('type' =>'phone2'))->row()->description;?>">
								</div>
							</div>
							
							<div class="row" style="text-align:right;">
								<div class="col-md-7">
								</div>
								<div class="col-md-5">
									<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default waves-effect">Close</a>
									<button type="submit" name="email_contact" class="btn btn-primary ml-1">Save</button>
								</div>
							</div>
							
							<?php
							/* <div class="d-flex justify-content-end align-items-center">
								<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-danger">Cancel  &nbsp;&nbsp;<i class="icon-cancel-circle2"></i></a>
								<button type="submit" name="email_contact" class="btn btn-primary ml-3">Update <i class="icon-paperplane ml-2"></i></button>
							</div> */ ?>
						</form>	
						<?php 
					} 
				?>
				<!-- EMail Contact Settings end -->
				
				<!-- Currency Settings Start -->
				<?php 
					if(isset($type) && $type == 'currency-settings')
					{
						
						?>
						<form action="" class="form-validate-jquery" --id="formValidation" method="post">
						
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Country <span class="text-danger">*</span></label>
									<?php 
										$getCountry = $this->db->query("select country_name, country_id from geo_countries where active_flag='Y' order by country_name asc ")->result_array();
									?>
									
									<select name="country_id" onchange="getCountryDetails(this.value);" id="country_id" class="form-control searchDropdown" required>
										<option value="">- Select Country -</option>
										<?php 
											foreach($getCountry as $row)
											{
												$selected ='';
												if(isset($currency_data[0]['country_id']) && $currency_data[0]['country_id'] == $row['country_id'])
												{
													$selected ='selected=selected';
												}
												?>
												<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['country_name']);?></option>
												<?php 
											} 
										?>
									</select>
									<script>
										function getCountryDetails(val)
										{
											if(val !='')
											{
												$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/getCountryDetails';?>",
													data: { id: val }
												}).done(function( d ) 
												{   
													data = JSON.parse(d);
													$("#country_code").val(data[0].country_code);
													$("#currency_symbol").val(data[0].currency_symbol);
													$("#currency_code").val(data[0].currency_code);
												});
											}
											else 
											{ 
												alert("No Country Details under this Country!");
											}
										}
									</script>
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Country Code </label>
									<input type="text" name="country_code" id="country_code" readonly class="form-control" value="<?php echo isset($currency_data[0]['country_code']) ? $currency_data[0]['country_code'] :"";?>" />
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Currency Symbol</label>
									<input type="text" name="currency_symbol" id="currency_symbol" readonly class="form-control" value="<?php echo isset($currency_data[0]['currency_symbol']) ? $currency_data[0]['currency_symbol'] :"";?>">
								</div>
								 
								<div class="form-group col-md-3">
									<label class="col-form-label">Currency Code</label>
									<input type="text" name="currency_code" id="currency_code" readonly class="form-control" value="<?php echo isset($currency_data[0]['currency_code']) ? $currency_data[0]['currency_code'] :"";?>">
								</div>
							</div>
							
							<div class="row" style="text-align:right;">
								<div class="col-md-7">
								</div>
								<div class="col-md-5">
									<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default waves-effect">Close</a>
									<button type="submit" class="btn btn-primary ml-1">Save</button>
								</div>
							</div>
						</form>	
						<?php 
					} 
				?>
				<!-- Currency Settings end -->
				
				<!-- System Settings Start -->
				<?php 
					if(isset($type) && $type == 'system-settings')
					{
						?>
						<form action="" class="form-validate-jquery" --id="formValidation" method="post">

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-5 text-right">Decimal Digit Value</label>
										<div class="form-group col-md-3">
											<input type="number" required <?php echo $this->validation;?> name="decimal_digit_value" id="decimal_digit_value" class="form-control" value="<?php echo isset($currency_data[0]['decimal_digit_value']) ? $currency_data[0]['decimal_digit_value'] :2;?>" />
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-5 text-right">Order Auto Print Timer</label>
										<div class="form-group col-md-5">
											<select name="order_auto_print_timer" id="order_auto_print_timer" class="form-control searchDropdown">
												<option value="">- Select -</option>
												<?php 
													foreach ($this->auto_refresh_seconds as $key => $value) 
													{	
														$selected ="";
														if (ORDER_AUTO_PRINT_TIMER == $key) 
														{
															$selected ="selected";
														}
														?>
														<option <?php echo $selected ?> value="<?php echo $key?>"><?php echo $value; ?></option>
														<?php
													}
												?>
											</select>
											<br><span class="small" style="color:#878787;">Note : Order Auto Print Timer</span>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-5 text-right">Auto Refresh  Timer</label>
										<div class="form-group col-md-5">
											<select name="auto_refresh_seconds" id="auto_refresh_seconds" class="form-control searchDropdown">
												<option value="">- Select -</option>
												<?php 
													foreach ($this->auto_refresh_seconds as $key => $value) 
													{	
														$selected ="";
														if (AUTO_REFRESH_SECONDS == $key) 
														{
															$selected ="selected";
														}
														?>
															<option <?php echo $selected ?> value="<?php echo $key?>"><?php echo $value ?></option>
														<?php
													}
												?>
											</select>
											<br><span class="small" style="color:#878787;">Note : Order Auto Refresh & Alert Sound Timer</span>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-5 text-right">Print Header Note</label>
										<div class="form-group col-md-5">
											<input type="text" required name="print_header_note" id="print_header_note" minlength="10" maxlength="30" class="form-control" value="<?php echo isset($currency_data[0]['print_header_note']) ? $currency_data[0]['print_header_note'] :"";?>" />
											<span class="small" style="color:#878787;float:left;width:100%;">Note: Enter a max 30 chars.</span>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-5 text-right">Print Footer Note</label>
										<div class="form-group col-md-5">
											<input type="text" required  name="print_footer_note" id="print_footer_note" minlength="10" maxlength="30" class="form-control" value="<?php echo isset($currency_data[0]['print_footer_note']) ? $currency_data[0]['print_footer_note'] :"";?>" />
											<span class="small" style="color:#878787;float:left;width:100%;">Note: Enter a max 30 chars.</span>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-5 text-right">Multi Login Access</label>
										<div class="form-group col-md-3">
											<?php
												$get_multi_login_access = $this->common_model->lov('MULTI-LOGIN-ACCESS');
											?>
											<select name="multi_login_access" id='multi_login_access' class="form-control searchDropdown">
												<option value="">- Select  -</option>
												<?php 
													foreach($get_multi_login_access as $multi_login_access)
													{
														$selected="";
														if(isset($currency_data[0]['multi_login_access']) && ($currency_data[0]['multi_login_access'] == $multi_login_access['list_code']) )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $multi_login_access['list_code'];?>" <?php echo $selected;?>><?php echo $multi_login_access['list_value']; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							
							<?php /* <div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Decimal Digit Value <span class="text-danger">*</span></label>
									<input type="number" required <?php echo $this->validation;?> name="decimal_digit_value" id="decimal_digit_value" class="form-control" value="<?php echo isset($currency_data[0]['decimal_digit_value']) ? $currency_data[0]['decimal_digit_value'] :2;?>" />
								</div>
							</div> 

							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Order Auto Print Timer</label>
									<select name="order_auto_print_timer" id="order_auto_print_timer" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach ($this->auto_refresh_seconds as $key => $value) 
											{	
												$selected ="";
												if (ORDER_AUTO_PRINT_TIMER == $key) 
												{
													$selected ="selected";
												}
												?>
												<option <?php echo $selected ?> value="<?php echo $key?>"><?php echo $value; ?></option>
												<?php
											}
										?>
									</select>
									<br><span class="small" style="color:#878787;">Note : Order Auto Print Timer</span>
								</div>
								

								<div class="form-group col-md-3">
									<label class="col-form-label">Auto Refresh & Alert Sound Timer</label>
									<select name="auto_refresh_seconds" id="auto_refresh_seconds" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach ($this->auto_refresh_seconds as $key => $value) 
											{	
												$selected ="";
												if (AUTO_REFRESH_SECONDS == $key) 
												{
													$selected ="selected";
												}
												?>
													<option <?php echo $selected ?> value="<?php echo $key?>"><?php echo $value ?></option>
												<?php
											}
										?>
									</select>
									<br><span class="small" style="color:#878787;">Note : Order Page Auto Refresh Timer</span>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Print Header Note <span class="text-danger">*</span></label>
									<input type="text" required  name="print_header_note" id="print_header_note" minlength="10" maxlength="30" class="form-control" value="<?php echo isset($currency_data[0]['print_header_note']) ? $currency_data[0]['print_header_note'] :"";?>" />
									<span class="small" style="color:#878787;float:left;width:100%;">Note: Enter a maximum of 30 characters.</span>
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Print Footer Note<span class="text-danger">*</span></label>
									<input type="text" required  name="print_footer_note" id="print_footer_note" minlength="10" maxlength="30" class="form-control" value="<?php echo isset($currency_data[0]['print_footer_note']) ? $currency_data[0]['print_footer_note'] :"";?>" />
									<span class="small" style="color:#878787;float:left;width:100%;">Note: Enter a maximum of 30 characters.</span>
								</div>
							</div>

							*/ ?>

							<div class="row" style="text-align:right;">
								<div class="col-md-7">
								</div>
								<div class="col-md-5">
									<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default btn-sm">Close</a>
									<button type="submit" class="btn btn-primary btn-sm">Save</button>
								</div>
							</div>

						</form>	  
						<?php 
					} 
				?>
				<!-- System Settings end -->
				
				<!-- Image Settings Start -->
				<?php 
					if(isset($type) && $type == 'image-settings')
					{
						?>
						<form action="" id="formValidation" enctype="multipart/form-data" method="post">
						
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Logo 1 <span class="text-danger">*</span></label>
									<input type="file" name="userfile" class="form-control singleImage"  onchange="return validateSingleFileExtension(this)">
									<span class="note-class"><b>Note</b> : Logo 1 upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
								</div>
								<div class="col-md-1"></div>
								<div class="form-group col-md-3">
									<br>
									<img src="<?php echo base_url();?>uploads/logo.png" alt="..." width="150" height="75">
								</div>
							</div>
							
							<script>
								/** Single Image Type & Size Validation **/
								function validateSingleFileExtension(fld) 
								{
									var fileUpload = fld;
									
									if (typeof (fileUpload.files) != "undefined")
									{
										var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
										var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
										
										if( size > validSize )
										{
											alert("Logo 1 upload size is 1 MB");
											$('.singleImage').val('');
											var value = 1;
											return false;
										}
										else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
										{
											alert("Invalid Logo 1 file type.");      
											$('.singleImage').val('');
											return false;   
										}
										
										if(value != 1)	
											return true; 
									}
								}
							</script>
							
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Logo 2 <span class="text-danger">*</span></label>
									<input type="file" name="userfile1" class="form-control singleImage_1" onchange="return validateSingleFileExtensionLogo2(this)">
									<span class="note-class"><b>Note</b> : Logo 2 upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
								</div>
								<div class="col-md-1"></div>
								<div class="form-group col-md-3">
									<br>
									<img src="<?php echo base_url();?>uploads/logo1.png" alt="..." width="150" height="75">
								</div>
							</div>
							
							<script>
								/** Single Image Type & Size Validation **/
								function validateSingleFileExtensionLogo2(fld) 
								{
									var fileUpload = fld;
									
									if (typeof (fileUpload.files) != "undefined")
									{
										var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
										var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
										
										if( size > validSize )
										{
											alert("Logo 2 upload size is 1 MB");
											$('.singleImage_1').val('');
											var value = 1;
											return false;
										}
										else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
										{
											alert("Invalid Logo 2 file type.");      
											$('.singleImage_1').val('');
											return false;   
										}
										
										if(value != 1)	
											return true; 
									}
								}
							</script>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Favi Icon <span class="text-danger">*</span></label>
									<input type="file" name="favicon" class="form-control singleImage_2" onchange="return validateSingleFileExtensionFavicon(this)">
									<span class="note-class"><b>Note</b> : Favi Icon upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
								</div>
								<div class="col-md-1"></div>
								<div class="form-group col-md-3">
									<br><br>
									<img src="<?php echo base_url();?>uploads/favicon.png" alt="..." width="26" height="26">
								</div>
							</div>
							
							<script>
								/** Single Image Type & Size Validation **/
								function validateSingleFileExtensionFavicon(fld) 
								{
									var fileUpload = fld;
									
									if (typeof (fileUpload.files) != "undefined")
									{
										var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
										var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
										
										if( size > validSize )
										{
											alert("Favi Icon upload size is 1 MB");
											$('.singleImage_2').val('');
											var value = 1;
											return false;
										}
										else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
										{
											alert("Invalid Favi Icon file type.");      
											$('.singleImage_2').val('');
											return false;   
										}
										
										if(value != 1)	
											return true; 
									}
								}
							</script>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">No Image <span class="text-danger">*</span></label>
									<input type="file" name="noimage" class="form-control singleImage_3" onchange="return validateSingleFileExtensionNoimage(this)">
									<span class="note-class"><b>Note</b> : No Image upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
								</div>
								<div class="col-md-1"></div>
								<div class="form-group col-md-3">
									<br>
									<img width="100" height="100" src="<?php echo base_url();?>uploads/no-image.png" alt="...">
								</div>
							</div>
							<script>
								/** Single Image Type & Size Validation **/
								function validateSingleFileExtensionNoimage(fld) 
								{
									var fileUpload = fld;
									
									if (typeof (fileUpload.files) != "undefined")
									{
										var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
										var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
										
										if( size > validSize )
										{
											alert("No Image upload size is 1 MB");
											$('.singleImage_3').val('');
											var value = 1;
											return false;
										}
										else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
										{
											alert("Invalid No Image file type.");      
											$('.singleImage_3').val('');
											return false;   
										}
										
										if(value != 1)	
											return true; 
									}
								}
							</script>
							
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Login Backround Image <span class="text-danger">*</span></label>
									<input type="file" name="backround_image" class="form-control singleImage_4" onchange="return validateSingleFileExtensionBackround(this)">
									<span class="note-class"><b>Note</b> : Login Backround upload size is 4 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
								</div>
								<div class="col-md-1"></div>
								<div class="form-group col-md-3">
									<br>
									<img width="225" height="135" src="<?php echo base_url();?>uploads/backround_image.png" alt="...">
								</div>
							</div>
							
							<script>
								/** Single Image Type & Size Validation **/
								function validateSingleFileExtensionBackround(fld) 
								{
									var fileUpload = fld;
									
									if (typeof (fileUpload.files) != "undefined")
									{
										var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
										var validSize = 1024 * 4; //1024 - 1Mb multiply 4mb
										
										if( size > validSize )
										{
											alert("Login Backround upload size is 4 MB");
											$('.singleImage_4').val('');
											var value = 1;
											return false;
										}
										else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
										{
											alert("Invalid Login Backround file type.");      
											$('.singleImage_4').val('');
											return false;   
										}
										
										if(value != 1)	
											return true; 
									}
								}
							</script>
							
							
							<div class="row" style="text-align:right;">
								<div class="col-md-7">
								</div>
								<div class="col-md-5">
									<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default waves-effect">Close</a>
									<button type="submit" name="imagesettings" class="btn btn-primary ml-1">Save</button>
								</div>
							</div>
						</form>
						<?php 
					} 
				?>
				<!-- Image Settings End -->
				
				<!-- Theme Settings Start -->
				<?php 
					if(isset($type) && $type == 'theme')
					{
						?>
						<form action="<?php echo base_url();?>admin/system_settings/theme" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								
							<?php 
								$thems = $this->db->get_where('settings' , array('type' =>'thems'))->row()->description; 
							?>
							
							
							
							<div class="row mb-5">
								<div class="col-md-4">
									<div class="row mb-3">
										<a href="javascript:void(0);" data-magnify="gallery" data-caption="Default">
											<img src="<?php echo base_url(); ?>uploads/themes/deafult.png" title="Default" class="theme-images" style="width:250px;height:220px">
										</a>
									</div>
									<div class="row">
										<input type="radio" id="thems" name="thems" value="1" <?php if(isset($thems) && $thems == 1){echo 'checked="checked"';}?>> <span class="theme-title">&nbsp;&nbsp;Default</span>
									</div>
								</div>
					
								<div class="col-md-4">
									<div class="row mb-3">
										<a href="javascript:void(0);" data-magnify="gallery" data-caption="Theme 1">
											<img src="<?php echo base_url(); ?>uploads/themes/theme_1.png" title="Theme 1" class="theme-images" style="width:250px;height:220px">
										</a>
									</div>
									<div class="row">
										<input type="radio" id="thems" name="thems" value="2" <?php if(isset($thems) && $thems == 2){echo 'checked="checked"';}?>>
										<span class="theme-title">&nbsp;Theme 1</span>
									</div>
								</div>
								<?php /*
								<div class="col-md-4">
									<div class="row mb-3">
										<a href="javascript:void(0);" data-magnify="gallery" data-caption="Theme 2">
											<img src="<?php echo base_url(); ?>uploads/themes/theme_2.png" title="Theme 2" class="theme-images" style="width:250px;height:220px">
										</a>
									</div>
									<div class="row">
										<input type="radio" id="thems" name="thems" value="3" <?php if(isset($thems) && $thems == 3){echo 'checked="checked"';}?>>
										<span class="theme-title">&nbsp;Theme 2</span>
									</div>
								</div>
								*/ ?>
							</div>
							
							<?php /* <div class="row">
								<div class="col-md-4">
									<div class="row mb-3">
										<a href="javascript:void(0);" data-magnify="gallery" data-caption="Theme 3">
											<img src="<?php echo base_url(); ?>uploads/themes/theme_3.png" title="Theme 3" class="theme-images" style="width:250px;height:220px">
										</a>
									</div>
									<div class="row">
										<input type="radio" id="thems" name="thems" value="4" <?php if(isset($thems) && $thems == 4){echo 'checked="checked"';}?>>
										<span class="theme-title">&nbsp;Theme 3</span>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="row mb-3">
										<a href="javascript:void(0);" data-magnify="gallery" data-caption="Theme 4">
											<img src="<?php echo base_url(); ?>uploads/themes/theme_4.png" title="Theme 4" class="theme-images" style="width:250px;height:220px">
										</a>
									</div>
									<div class="row">
										<input type="radio" id="thems" name="thems" value="5" <?php if(isset($thems) && $thems == 5){echo 'checked="checked"';}?>>
										<span class="theme-title">&nbsp;Theme 4</span>
								
									</div>
								</div>
							</div> */ ?>
							
							<div class="d-flexad text-right">
								<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-default waves-effect">Close</a>
								<button type="submit" name="theme" class="btn btn-primary ml-1">Save</button>
							</div>
						</form>
						<?php 
					} 
				?>
				<!-- Theme Settings End -->
			</div>
		</div>
		
	</div>
	</div>
 </div>

