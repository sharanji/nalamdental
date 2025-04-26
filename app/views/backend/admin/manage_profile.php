<!-- Page header start-->
<div class="page-header page-header-light">
	<?php /* <div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex back-header-full">
			<h4>
				<i class="icon-arrow-left52 mr-2"></i> 
				<span class="font-weight-semibold"> 
					My Profile
				</span>
			</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div> */ ?>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="<?php echo base_url();?>admin/manage_profile" class="breadcrumb-item">
					My Profile
				</a>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	
	<?php
		if(isset($type) && $type != 'edit_profile' )
		{
			?>
			<div class="row mb-2">
				<div class="col-lg-12 mb-5">
					<div class="cover-profile">
						<div class="profile-bg-img">
							<?php 
								if (file_exists('uploads/cover_image/'.$this->user_id.'.png'))
								{
									?>
									<img style="width:1073px;height:186px;" class="profile-bg-img img-fluid"  src="<?php echo base_url()."uploads/cover_image/".$this->user_id.'.png';?>"> 
									<?php
								}
							?>
							<?php 
								if (file_exists('uploads/admin_image/'.$this->user_id.'.png'))
								{
									?>
									<img class="profile-image" width="100" height="100" src="<?php echo base_url()."uploads/admin_image/".$this->user_id.'.png';?>"> 
									<?php
								}
								else
								{
									?>
									<img class="profile-image" width="100" height="100" src="<?php echo base_url()."uploads/no-image.png";?>"> 
									<?php
								}
							?>
							<div class="profile-names">
								<?php echo $edit_data[0]['first_name'];
									if( !empty($edit_data[0]['last_name']) && $edit_data[0]['last_name'] != '0')
									{
										echo $edit_data[0]['last_name'];
									}
								?>
								<br>
								<span class="small">
									<?php echo $edit_data[0]['address1'];?>
								</span>
							</div>
							
						</div>
					</div>		
				</div>
				<?php
				/* <div class="col-lg-12">
					<div class="card personal-info">
					<p class="pt-2 py-1 px-4 mb-0 h4"><span class="text-grey">Personal Info</span></p>
					<div style="border-bottom:5px solid #01a9ac;" class="col-md-3"></div>
					</div>
				</div> */ ?>
			</div>
			<?php 
		} 
	?>
	
	<div class="card"><!-- Card start-->
		
		
		<div class="card-body">
			<?php
				if(isset($type) && $type == 'edit_profile' )
				{
					foreach($edit_data as $row)
					{
						?>
						<form action="<?php echo base_url();?>admin/manage_profile/update_profile_info/<?php echo $row['user_id'];?>" class="form-validate-jquery" enctype="multipart/form-data" method="post">
							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold">Edit Profile</legend>
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">First Name <span class="text-danger">*</span></label>
										<input type="text" name="first_name" <?php echo $this->validation; ?> class="form-control only_name" autocomplete="off" required value="<?php echo $row['first_name'];?>" placeholder="">
									</div>
									 
									<div class="form-group col-md-3">
										<label class="col-form-label">Last Name </label>
										<input type="text" name="last_name" class="form-control only_name" <?php echo $this->validation; ?>autocomplete="off" value="<?php echo $row['last_name'];?>" placeholder="">
									</div>
									<div class="form-group col-md-3">
										<label class="col-form-label">E-Mail <span class="text-danger">*</span></label>
										<input type="text" name="email" class="form-control" autocomplete="off" required value="<?php echo $row['email'];?>" placeholder="">
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
										<input type="text" name="phone_number" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="10" maxlength='12' class="form-control mobile_vali" autocomplete="off" required value="<?php echo $row['phone_number'];?>"  placeholder="">
									</div>
									
									<div class="form-group col-md-3">
										<label class="col-form-label">Country <span class="text-danger">*</span></label>
										<input type="text" name="country" class="form-control only_name" autocomplete="off" <?php echo $this->validation; ?> required value="<?php echo $row['country'];?>"  placeholder="">
									</div>
									
									<div class="form-group col-md-3">
										<label class="col-form-label">City <span class="text-danger">*</span></label>
										<input type="text" name="city" class="form-control only_name" <?php echo $this->validation; ?> autocomplete="off" required value="<?php echo $row['city'];?>"  placeholder="">
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Gender<span class="text-danger">*</span></label>
										<select name="gender" class="form-control searchDropdown">
											<option value="">- Select Gender -</option>
											<?php 
												foreach($this->gender as $key=>$value)
												{
													$selected="";
													if($row['gender'] == $key)
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
													<?php 
												} 
											?>
										</select>
									</div>
									
									<div class="form-group col-md-3">
										<label class="col-form-label">Marital Status <span class="text-danger">*</span></label>
										<select name="marital_status" class="form-control searchDropdown">
											<option value="">- Select Marital Status -</option>
											<?php 
												foreach($this->marital_status as $key=>$value)
												{
													$selected="";
													if($row['marital_status'] == $key)
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
													<?php 
												} 
											?>
										</select>
									</div>

									<div class="form-group col-md-3">
										<label class="col-form-label">Date of Birth</label>
										<input type="text" name="date_of_birth" readonly id="date_of_birth" class="form-control" value="<?php echo $row['date_of_birth'];?>"  placeholder="">
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Website </label>
										<input type="url" name="website" class="form-control" autocomplete="off" value="<?php echo $row['website'];?>"  placeholder="">
									</div>
									
									<div class="form-group col-md-3">
										<label class="col-form-label">Address <span class="text-danger">*</span></label>
										<textarea name="address1" rows="1" cols="1" required class="form-control" autocomplete="off"><?php echo $row['address1'];?></textarea>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Profile Image </label>
										<input type="file" name="userfile" class="form-control">
										<?php 
											if (file_exists('uploads/admin_image/'.$this->user_id.'.png'))
											{
												?><br>
												<img width="75" height="75" src="<?php echo base_url()."uploads/admin_image/".$this->user_id.'.png';?>"> 
												<?php
											}
										?>
									</div>

									<div class="form-group col-md-3">
										<label class="col-form-label">Cover Image </label>
										<input type="file" name="coverfile" class="form-control">
										<?php 
											if (file_exists('uploads/cover_image/'.$this->user_id.'.png'))
											{
												?><br>
												<img width="232" height="75" src="<?php echo base_url()."uploads/cover_image/".$this->user_id.'.png';?>"> 
												<?php
											}
										?>
									</div>
								</div>
								
								<div class="d-flex justify-content-end align-items-center">
									<a href="<?php echo base_url(); ?>admin/manage_profile" class="btn btn-default">Cancel</a>
									<button type="submit" class="btn btn-primary ml-1">Update</button>
								</div>
							</fieldset>	
						</form>
						<?php 
					} 
				}
				else
				{
					foreach($edit_data as $row)
					{
						?>
						<div class="row">
							<div class="col-md-12 user-name-visible" style="float:right;text-align: right;margin:10px 0px;">
								User Name : <span><?php echo $row['user_name'];?> </span>
							</div>
							<div class="col-6">
								<h4 class="page-titles">About Me : </h4>
							</div>
							
							<div class="col-6 d-flex justify-content-end p-0 px-2">
								
								<a title="Edit Profile" href="<?php echo base_url();?>admin/manage_profile/edit_profile" class="btn btn-lg btn-primary">
									<i class="fa fa-pencil-square-o"></i> Edit Profile
								</a>
								&nbsp;
								<a title="Edit User Name" class="btn btn-lg btn-success edit-username" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['user_id'];?>">
									<i class="fa fa-pencil-square-o"></i> Edit User Name
								</a>
							</div>
						</div>
						<div class="modal fade" class="form-validate-jquery" id="exampleModal<?php echo $row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['user_id'];?>" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header" --style="background: #022646;color: #fff;">
										<h5 class="modal-title" id="exampleModalLabel">Edit User Name</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									
									<form action="" method="post">
										<div class="modal-body">
											<input type="hidden" name="user_id" value="<?php echo $row['user_id'];?>"/>
											<div class="row">
												<div class="form-group col-md-8">
													<label class="col-form-label">User Name <span class="text-danger">*</span></label>
													<input type="text" name="user_name" value="<?php echo $row['user_name'];?>" required class="form-control" />
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
											<input type="submit" name="editUserName" class="btn btn-primary ml-1" value="Submit">
										</div>
									</form>
								</div>
							</div>
						</div>
						
						
						<div class="row mt-3">						
							<div class="col-md-6">				
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Full Name</label>
									</div>
									<div class="col-md-7">
										<?php echo $row['first_name'];?>
										<?php 
											if( !empty($row['last_name']) && $row['last_name'] != '0')
											{
												echo $row['last_name'];
											}
										?>
									</div>
								</div>
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Gender</label>
									</div>
									<div class="col-md-7">
										<?php 
											foreach($this->gender as $key=>$value)
											{
												if($row['gender'] == $key)
												{
													echo $value;
												}
											} 
										?>
									</div>
								</div>
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Birth Date</label>
									</div>
									<div class="col-md-7">
										<?php echo $row['date_of_birth'];?>
									</div>
								</div>
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Marital Status</label>
									</div>
									<div class="col-md-7">
										<?php 
											foreach($this->marital_status as $key=>$value)
											{
												if($row['marital_status'] == $key)
												{
													echo $value;
												}
											} 
										?>
									</div>
								</div>
								
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Country</label>
									</div>
									<div class="col-md-7">
										<?php 
											if( !empty($row['country']) && $row['country'] != '0')
											{
												echo $row['country'];
											}
											else
											{
												echo "--";
											}
										?>
									</div>
								</div>  
							</div>  
								
							
							<div class="col-md-6">
							<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Email</label>
									</div>
									<div class="col-md-7">
										<?php 
											if( !empty($row['email']) && $row['email'] != '0')
											{
												echo $row['email'];
											}
											else
											{
												echo "--";
											}
										?>
									</div>
								</div> 
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Mobile Number</label>
									</div>
									<div class="col-md-7">
										<?php 
											if( !empty($row['phone_number']) && $row['phone_number'] != '0')
											{
												echo $row['phone_number'];
											}
											else
											{
												echo "--";
											}
										?>
									</div>
								</div> 
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Address</label>
									</div>
									<div class="col-md-7">
										<?php 
											if( !empty($row['address1']) && $row['address1'] != '0')
											{
												echo $row['address1'];
											}
											else
											{
												echo "--";
											}
										?>
									</div>
								</div>
								<div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">Website</label>
									</div>
									<div class="col-md-7">
										<a href="<?php echo $row['website'];?>" target="_blank"><?php echo !empty($row['website']) ? $row['website']:"--";?></a>
									</div>
								</div><div class="horizontal-rule" style="border-top: 1px solid #e9ecef;"></div>
								
								<div class="row">
									<div class="col-md-5">
										<label class="profile-labels">City</label>
									</div>
									<div class="col-md-7">
										<?php 
											if( !empty($row['city']) && $row['city'] != '0')
											{
												echo $row['city'];
											}
											else
											{
												echo "--";
											}
										?>
									</div>
								</div> 
							
								
								<?php 
									/* if( !empty($row['address2']) && $row['address2'] != '0')
									{ 
										?>
										<div class="row">
											<div class="col-md-5">
												<label class="profile-labels"><b>Secondary Address</b></label>
											</div>
											<div class="col-md-7">
												<?php 
													echo $row['address2'];
												?>
											</div>
										</div> 
										<hr>
										<?php 
									} */
								?>
							</div>
						</div>	
						<?php 
					} 
				} 
			?>
		</div>
	</div>
 </div>

