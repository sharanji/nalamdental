
<!-- Start div class col-md-9 for Right side -->
<div class="col-md-9 col-sm-9 col-xs-12">
	<!-- General Information start -->
	<!-- <h4>Employee Details</h4> -->

	<div class="row">
		<!-- Basic Info start -->
		<div class="col-md-6 card">
			<h4>Basic Info</h4>
			<div class="row">
				<div class="col-md-5">Employment Type</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6">
					<?php 
						foreach($this->employeementType as $key => $value)
						{
							if( isset($edit_data[0]['employeement_type']) && $edit_data[0]['employeement_type'] == $key )
							{
								echo $value;
							}
						} 
					?>	
				</div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Employee No.</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo isset($edit_data[0]['random_user_id']) ? $edit_data[0]['random_user_id']:"--";?></div>
			</div>
			
			<div class="row mt-1">
				<div class="col-md-5">Employee First Name</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['first_name']);?></div>
			</div>

			<?php if(!empty($edit_data[0]['last_name'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Employee Last Name</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['last_name']);?></div>
			</div>
			<?php } ?>

			<?php if(!empty($edit_data[0]['middle_name'])){?>
				<div class="row mt-1">
					<div class="col-md-5">Surname</div>
					<div class="col-md-1">:</div>
					<div class="col-md-6"><?php echo isset($edit_data[0]['middle_name']) ? $edit_data[0]['middle_name']:"--";?></div>
				</div>
			<?php } ?>

			<div class="row mt-1">
				<div class="col-md-5">Mobile Number</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo $edit_data[0]['mobile_country_code'].'-'.$edit_data[0]['mobile_number'];?></div>
			</div>

			<?php if(!empty($edit_data[0]['alternate_contact'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Alter Mobile Number</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['alternate_contact']) ? $edit_data[0]['alter_mobile_country_code'].'-'.$edit_data[0]['alternate_contact'] : "--";?></div>
			</div>
			<?php } ?>

			<div class="row mt-1">
				<div class="col-md-5">Email</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo isset($edit_data[0]['email']) ? $edit_data[0]['email']:"--";?></div>
			</div>

			<?php if(!empty($edit_data[0]['alternate_email'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Alter E-Mail</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['alternate_email']) ? $edit_data[0]['alternate_email'] : "--";?></div>
			</div>
			<?php } ?>

			<div class="row mt-1">
				<div class="col-md-5">Date of Birth</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo isset($edit_data[0]['date_of_birth']) ? $edit_data[0]['date_of_birth']:"--";?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Gender</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6">
					<?php 
						foreach($this->gender as $key => $value)
						{
							if( isset($edit_data[0]['gender']) && $edit_data[0]['gender'] == $key )
							{
								echo $value;
							}
						} 
					?>	
				</div>
			</div>

			<?php if(!empty($edit_data[0]['blood_group_name'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Blood Group</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['blood_group_name']) ? $edit_data[0]['blood_group_name'] : "--";?></div>
			</div>
			<?php } ?>	
		</div>
		<!-- Basic Info end -->

		<!-- Employee Details start -->
		<div class="col-md-6 card">
			<h4>Employee Details</h4>

			<?php if(!empty($edit_data[0]['branch_name'])){?>
			<div class="row">
				<div class="col-md-5">Branch</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['branch_name']);?></div>
			</div>
			<?php } ?>

			<div class="row mt-1">
				<div class="col-md-5">Designation</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['designation_name']) ? $edit_data[0]['designation_name'] : "--";?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Department</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['department_name']) ? $edit_data[0]['department_name'] : "--";?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Date of Joining</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining']:"--";?></div>
			</div>

			<?php if(!empty($edit_data[0]['date_of_leaving'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Date of Leaving</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo isset($edit_data[0]['date_of_leaving']) ? $edit_data[0]['date_of_leaving']:"--";?></div>
			</div>
			<?php } ?>

			<?php if(!empty($edit_data[0]['year_of_experience'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Year of experience</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['year_of_experience']) ? $edit_data[0]['year_of_experience'] : "--";?></div>
			</div>
			<?php } ?>

			<?php if(!empty($edit_data[0]['rate_per_hour'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Rate Per Hour</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['rate_per_hour']) ? $edit_data[0]['rate_per_hour'] : "--";?></div>
			</div>
			<?php } ?>

			<?php if(!empty($edit_data[0]['rate_per_day'])){?>
			<div class="row mt-1">
				<div class="col-md-5">Rate Per Day</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo !empty($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] : "--";?></div>
			</div>
			<?php } ?>

			<?php if(!empty($edit_data[0]['pay_frequency']) && $edit_data[0]['pay_frequency'] > 0){?>
			<div class="row mt-1">
				<div class="col-md-5">Pay Frequency</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6">
					<?php 
						foreach($this->pay_frequency as $key => $value)
						{
							if( isset($edit_data[0]['pay_frequency']) && $edit_data[0]['pay_frequency'] == $key )
							{
								echo $value;
							}
						} 
					?>
				</div>
			</div>
			<?php } ?>
		</div>
		<!-- Employee Details end -->

		<!-- Identity start -->
		<div class="col-md-6 mt-3 card">
			<h4>Identity</h4>
			<div class="row">
				<div class="col-md-5">Aadhar No</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['aadhaar_number']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">PAN Number</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['pan_number']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Passport No</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['passport_number']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Driving Licence</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['licence_number']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Voter ID</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['voter_id']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">PF No.</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['pf_number']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">ESI No.</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['esi_number']);?></div>
			</div>
		</div>
		<!-- Identityend -->

		<!-- address start -->
		<div class="col-md-6 mt-3 card">
			<h4>Address</h4>
			<h5>Current Address</h5>
			<div class="row">
				<div class="col-md-5">Country</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['country_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">State</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['state_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">District</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['district_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">City</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['city_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Address</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['address']);?></div>
			</div>

			<div class="row mt-1 mb-2">
				<div class="col-md-5">Postal Code</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['postal_code']);?></div>
			</div>

			<label><h5>Permenant Address</h5></label>
			<div class="row">
				<div class="col-md-5">Country</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['permenant_country_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">State</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['permenant_state_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">District</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['permenant_district_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">City</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['permenant_city_name']);?></div>
			</div>

			<div class="row mt-1">
				<div class="col-md-5">Address</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['permenant_address']);?></div>
			</div>

			<div class="row mt-1 mb-2">
				<div class="col-md-5">Postal Code</div>
				<div class="col-md-1">:</div>
				<div class="col-md-6"><?php echo ucfirst($edit_data[0]['permenant_postal_code']);?></div>
			</div>
		</div>
		<!-- address end -->
	</div>
</div>
<!-- End div class col-md-9 for Right side -->
