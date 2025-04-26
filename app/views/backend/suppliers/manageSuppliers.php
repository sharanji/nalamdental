

<!-- Import csv start -->
<div class="modal fade" id="importcountryCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" --style="background: #1a4363;color: #fff;">
				<h5 class="modal-title" id="exampleModalLabel">Import</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?php echo base_url(); ?>suppliers/ManageSuppliers/import" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					
					<div class="row">
						<!-- <div class="col-md-12 mb-3">
							<div class="well well-small">
								The correct column order is <span class="text-info-"> ( Item Name, Item Description & Item Cost  )</span>&nbsp; &amp; You must follow this.
							</div>
						</div> -->
						<div class="col-md-12 mb-3">
							<span class="text-danger-" style="font-size:12px !important;"><b>Note : </b> The first line in downloaded csv file should remain as it is. Please do not change the order of columns and Update valid data..</span><br>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-9">
							<input type="file" name="csv"  id="chooseFile" class="form-control singleDocument" onchange="return validateSingleDocumentExtension(this)" required />
							<span style="color:#a0a0a0;">Note : Upload format CSV and upload size is 5 mb.</span>
						</div>
						<div class="col-md-3">
							<a href="<?php echo base_url(); ?>assets/sample_suppliers.csv" class="btn btn-info btn-flat btn-sm pull-right" title="Download Sample File">
								<i class="fa fa-download"></i> Download
							</a>
						</div>
					</div>
					
					<script>
						/** Single Document Type & Size Validation **/
						function validateSingleDocumentExtension(fld) 
						{
							var fileUpload = fld;
							
							if (typeof (fileUpload.files) != "undefined")
							{
								var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
								var validSize = 1024 * 5; //1024 - 1Mb multiply 4mb
								
								//var validSize = 500; 
								
								if( size > validSize )
								{
									//alert("Document upload size is 4 MB");
									alert("File size should not exceed 5 MB.");
									$('.singleDocument').val('');
									var value = 1;
									return false;
								}
								else if(!/(\.csv)$/i.test(fld.value))
								//else if(!/(\.pdf)$/i.test(fld.value))
								{
									alert("Invalid document file type.");      
									$('.singleDocument').val('');
									return false;   
								}
								
								if(value != 1)	
									return true; 
							}
						}
					</script>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
					<button type="submit" class="btn btn-primary btn-sm ml-1">Import</button>
				</div>
			</form>
		</div>
	</div>
</div>	
<!-- Import csv end -->

<?php
	$manageSuppliersMenu = accessMenu(manage_suppliers);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{  
					if($type == "view"){
						$fieldSetDisabled = "disabled";
						$dropdownDisabled = "style='pointer-events: none;'";
						$searchDropdown = "";
					}else{
						$fieldSetDisabled = "";
						$dropdownDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					
					$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag='Y' order by country_name asc")->result_array();
					
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row">
							
							<div class="col-md-4">
								<h3>
									<b>
										<?php 
											if($type == "add")
											{
												?>
												Create
												<?php 
											}
											else if($type == "edit")
											{
												echo ucfirst($type);
											}
											else if($type == "view")
											{
												echo ucfirst($type);
											}  
										?>
										<?php echo $page_title?>
										
									</b>
								</h3>
							</div>

							<div class="col-md-8 text-right">
								<?php 
									/* if($type == "edit")
									{
										?>
											<span class="invoicenumber"> Invoice Number : <?php echo isset($edit_data[0]['invoice_number']) ? $edit_data[0]['invoice_number'] : NULL;?></span>
										<?php 
									}  */
								?>
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
										
										<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
									
										<?php 
									} 
								?>
								
								<a href="<?php echo base_url(); ?>suppliers/ManageSuppliers" class="btn btn-default btn-sm">Close</a>
								
							</div>
							
							<?php /* 
								if($type == "view")
								{
									?>
									<div class="col-md-6 text-right">
										<a class="btn btn-sm btn-primary edit-icon" href="<?php echo base_url(); ?>suppliers/ManageSuppliers/edit/<?php echo $id;?>" title="Edit">
											<i class="fa fa-edit"></i>
										</a>
									</div>
									<?php 
								} 
							*/ ?>
						</div>
						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-4 text-right supplier_name"><span class="text-danger">*</span> Supplier Name</label>
										<div class="form-group col-md-5">
											<input type="text" name="supplier_name" autocomplete="off" id="supplier_name" required class="form-control single_quotes" value="<?php echo isset($edit_data[0]['supplier_name']) ? $edit_data[0]['supplier_name'] : NULL;?>" placeholder="Supplier Name">					
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Contact Person</label>
										<div class="form-group col-md-5">
											<input type="text" name="contact_person" autocomplete="off" id="contact_person" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['contact_person']) ? $edit_data[0]['contact_person'] :NULL;?>" placeholder="Contact Person">
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right">GST Number</label>
										<div class="form-group col-md-5">
											<input type="text" name="gst_number" autocomplete="off" id="gst_number" maxlength="15" minlength="15" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['gst_number']) ? $edit_data[0]['gst_number'] : NULL;?>" placeholder="GST Number">
											<span class="small" id="gst_number_vali" style="color:#a19f9f;float:left;width:100%;">(Ex : 22AAAAA0000A1Z5)</span>

										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right">CIN Number</label>
										<div class="form-group col-md-5">
											<input type="text" name="cin_number" autocomplete="off" id="cin_number" maxlength="21" minlength="21" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['cin_number']) ? $edit_data[0]['cin_number'] : NULL;?>" placeholder="CIN Number">
											<span class="small" id="cin_number_vali" style="color:#a19f9f;float:left;width:100%;">(Ex :  U74900DL2015PTC282029)</span>

										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right country_id"><span class="text-danger">*</span> Country</label>
										<div class="form-group col-md-5">
											<select name="country_id" id="country_id" onchange="selectState(this.value);" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
												<?php 
													$getCountry = $this->db->query("select country_id,country_name from geo_countries where active_flag='Y' order by country_name asc")->result_array();
							
													foreach($getCountry as $row)
													{
														$selected="";
														if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id']== $row['country_id'])
														{
															$selected="selected";
														}
														?>
														<option value="<?php echo $row['country_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($row['country_name']);?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right state_id"><span class="text-danger">*</span> State</label>
										<div class="form-group col-md-5">
											<select name="state_id" id="state_id" required onchange="selectCity(this.value);" class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
												<?php 
													if($edit_data[0]['country_id'] !=0 && $edit_data[0]['country_id'] !="")
													{
														$state = $this->db->query("select state.state_id,state.state_name from geo_states as state
																where active_flag='Y' and state.country_id='".$edit_data[0]['country_id']."'")->result_array();
																
														foreach($state as $row)
														{
															$selected='';
															if($edit_data[0]['state_id'] == $row['state_id'])
															{
																$selected='selected="selected"';
															}
															?>
															<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['state_name']);?></option>
															<?php 
														} 
													} 
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right city_id"><span class="text-danger">*</span> City</label>
										<div class="form-group col-md-5">
											<select name="city_id" id="city_id" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
												<?php 
													if($edit_data[0]['state_id'] !=0 && $edit_data[0]['state_id'] !="")
													{
														$city= $this->db->query("select city.city_id,city.city_name from geo_cities as city
																where active_flag='Y' and city.state_id='".$edit_data[0]['state_id']."'")->result_array();
																
														foreach($city as $row)
														{
															$selected='';
															if($edit_data[0]['city_id'] == $row['city_id'])
															{
																$selected='selected="selected"';
															}
															?>
															<option value="<?php echo $row['city_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['city_name']);?></option>
															<?php 
														} 
													} 
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right address1"><span class="text-danger">*</span> Address 1</label>
										<div class="form-group col-md-5">
											<textarea name="address1" id="address1" rows="1" required autocomplete="off" class="form-control single_quotes" placeholder="Address 1"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] : NULL;?></textarea>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Address 2</label>
										<div class="form-group col-md-5">
											<textarea name="address2" id="address2" rows="1" autocomplete="off" class="form-control single_quotes" placeholder="Address 2"><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] : NULL;?></textarea>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Address 3</label>
										<div class="form-group col-md-5">
											<textarea name="address3" id="address3" rows="1" autocomplete="off" class="form-control single_quotes" placeholder="Address 3"><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] : NULL;?></textarea>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right postal_code"><span class="text-danger">*</span> Postal Code</label>
										<div class="form-group col-md-5">
											<input type="text" maxlength="6"  minlength="6" name="postal_code" autocomplete="off" id="postal_code" <?php #echo $this->validation;?> required class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] : NULL;?>" placeholder="Postal Code">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-4 text-right mobile_number"><span class="text-danger">*</span> Mobile Number</label>
										<div class="form-group col-md-5">
											<input type="text" name="mobile_number" autocomplete="off" required <?php echo $this->validation;?> id="mobile_number" class="form-control mobile_vali" minlength="10" maxlength='10' value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :NULL;?>" placeholder="Mobile Number">
										</div>
									</div>
									
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Email</label>
										<div class="form-group col-md-5">
											<input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" autocomplete="off" id="email" class="form-control single_quotes" value="<?php echo isset($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] :NULL;?>" placeholder="Email">
											<span class='small employee_email_exist_error' style="color:#a19f9f;"></span>
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right pan_number"><span class="text-danger">*</span> PAN Number</label>
										<div class="form-group col-md-5">
											<input type="text" name="pan_number" maxlength="10" autocomplete="off" id="pan_number" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number'] : NULL;?>" placeholder="Pan Number">
											<span class="small" id="pan_number_val" style="color:#a19f9f;float:left;width:100%;">(Ex : ABCDE1234F)</span>
											
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right account_number"><span class="text-danger">*</span> Account Number</label>
										<div class="form-group col-md-5">
											<input type="text" name="account_number" autocomplete="off" id="account_number" maxlength="20" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['account_number']) ? $edit_data[0]['account_number'] : NULL;?>" placeholder="Account Number">
											<span class="small" id="account_number_val" style="color:#a19f9f;float:left;width:100%;">(Ex : 0112345678956554 )</span>
											
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right account_holder_name"><span class="text-danger">*</span> Account Holder Name</label>
										<div class="form-group col-md-5">
											<input type="text" name="account_holder_name" autocomplete="off" id="account_holder_name" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['account_holder_name']) ? $edit_data[0]['account_holder_name'] : NULL;?>" placeholder="Account Holder Name">
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right ifsc_code"><span class="text-danger">*</span> IFSC Code</label>
										<div class="form-group col-md-5">
											<input type="text" name="ifsc_code" autocomplete="off" id="ifsc_code" maxlength="11" minlength="11" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] : NULL;?>" placeholder="IFSC Code">
											<span id="ifsc_code_val" class="small" style="color:#a19f9f;float:left;width:100%;">(Ex : IDIB000A114)</span>
											
										</div>
									</div>

									<div class="row">
										<label class="col-form-label col-md-4 text-right bank_name"><span class="text-danger">*</span> Bank Name</label>
										<div class="form-group col-md-5">
											<input type="text" name="bank_name" autocomplete="off" id="bank_name" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] : NULL;?>" placeholder="Bank Name">
										</div>
									</div>

									<div class="row">
										<label class="col-form-label col-md-4 text-right branch_name"><span class="text-danger">*</span> Branch Name</label>
										<div class="form-group col-md-5">
											<input type="text" name="branch_name" autocomplete="off" id="branch_name" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] : NULL;?>" placeholder="Branch Name">
										</div>
									</div>
									
									<div class="row">
										<label class="col-form-label col-md-4 text-right">MICR Code</label>
										<div class="form-group col-md-5">
											<input type="text" name="micr_code" autocomplete="off" maxlength="10" id="micr_code" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['micr_code']) ? $edit_data[0]['micr_code'] : NULL;?>" placeholder="MICR Code">
											<span class="small" id="micr_code_val" style="color:#a19f9f;">(Ex : 600019003)</span>
											
										</div>
									</div>
									<div class="row">
										<label class="col-form-label col-md-4 text-right swift_code">Swift Code</label>
										<div class="form-group col-md-5">
											<input type="text" name="swift_code" autocomplete="off" maxlength="15" id="swift_code" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['swift_code']) ? $edit_data[0]['swift_code'] : NULL;?>" placeholder="Swift Code">
											<span class="small" id="swift_code_val" style="color:#a19f9f;">(Ex : AAAA-BB-CC-123)</span>
											
										</div>
									</div>
								</div>
							</div>
							<!-- Address -->
						</fieldset>
						
						<div class="col-md-12 mt-3 pr-0 text-right">
							<?php 
								if($type == "add" || $type == "edit")
								{
									?>
									<!-- <a href="javascript:void(0)" id="save_btn" onclick="return saveBtn('save_btn','save');" class="btn btn-primary btn-sm submit_btn_bottom">Save Bottom</a> -->
									<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm">Save</button>
									<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm">Submit</button>
									<?php 
								} 
							?>
							<a href="<?php echo base_url(); ?>suppliers/ManageSuppliers" class="btn btn-default btn-sm">Close</a>
						</div>
						
					</form>
					<script type="text/javascript">  

						function saveBtn(val) 
						{
							var supplier_name			= $("#supplier_name").val();
							var mobile_number 			= $("#mobile_number").val();
							var country_id 				= $("#country_id").val();
							var state_id 				= $("#state_id").val();
							var city_id 				= $("#city_id").val();
							var address1 				= $("#address1").val();
							var postal_code 			= $("#postal_code").val();
							var pan_number 				= $("#pan_number").val();
							var account_number			= $("#account_number").val();
							var account_holder_name 	= $("#account_holder_name").val();
							var ifsc_code 				= $("#ifsc_code").val();
							var branch_name 			= $("#branch_name").val();
							var bank_name 				= $("#bank_name").val();
							
							if (supplier_name && mobile_number && country_id &&  state_id && city_id && address1 && postal_code && pan_number && account_number && account_holder_name && ifsc_code && branch_name && bank_name)
							{
								$(".supplier_name").removeClass('errorClass');
								$(".mobile_number").removeClass('errorClass');
								$(".country_id").removeClass('errorClass');
								$(".state_id").removeClass('errorClass');
								$(".city_id").removeClass('errorClass');
								$(".address1").removeClass('errorClass');
								$(".postal_code").removeClass('errorClass');
								$(".pan_number").removeClass('errorClass');
								$(".account_number").removeClass('errorClass');
								$(".account_holder_name").removeClass('errorClass');
								$(".ifsc_code").removeClass('errorClass');
								$(".branch_name").removeClass('errorClass');
								$(".bank_name").removeClass('errorClass');
								
								return true; 
							} 
							else 
							{
								if (supplier_name) {
									$(".supplier_name").removeClass('errorClass');
								} else {
									$(".supplier_name").addClass('errorClass');
								}
								if (mobile_number) {
									$(".mobile_number").removeClass('errorClass');
								} else {
									$(".mobile_number").addClass('errorClass');
								}
								
								if (country_id) {
									$(".country_id").removeClass('errorClass');
								} else {
									$(".country_id").addClass('errorClass');
								}
								if (state_id) {
									$(".state_id").removeClass('errorClass');
								} else {
									$(".state_id").addClass('errorClass');
								}
								if (city_id) {
									$(".city_id").removeClass('errorClass');
								} else {
									$(".city_id").addClass('errorClass');
								}
								if (address1) {
									$(".address1").removeClass('errorClass');
								} else {
									$(".address1").addClass('errorClass');
								}
								if (postal_code) {
									$(".postal_code").removeClass('errorClass');
								} else {
									$(".postal_code").addClass('errorClass');
								}
								if (pan_number) {
									$(".pan_number").removeClass('errorClass');
								} else {
									$(".pan_number").addClass('errorClass');
								}
								if (account_number) {
									$(".account_number").removeClass('errorClass');
								} else {
									$(".account_number").addClass('errorClass');
								}
								if (account_holder_name) {
									$(".account_holder_name").removeClass('errorClass');
								} else {
									$(".account_holder_name").addClass('errorClass');
								}
								if (ifsc_code) {
									$(".ifsc_code").removeClass('errorClass');
								} else {
									$(".ifsc_code").addClass('errorClass');
								}
								if (branch_name) {
									$(".branch_name").removeClass('errorClass');
								} else {
									$(".branch_name").addClass('errorClass');
								}
								if (bank_name) {
									$(".bank_name").removeClass('errorClass');
								} else {
									$(".bank_name").addClass('errorClass');
								}
								return false;
							}
						}
						function selectState(val,type)
						{
							if(val !='')
							{
								$( "#address1").val("");
								$( "#address2").val("");
								$( "#address3").val("");
								$( "#postal_code").val("");

								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'admin/ajaxselectState';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									if(msg == "no_date_found")
									{
										$( "#state_id").html('<option value="">- Select -</option>');
										$( "#city_id").html('<option value="">- Select -</option>');
									}
									else
									{
										$( "#state_id").html(msg);
										$( "#city_id").html('<option value="">- Select -</option>');
									}
								});
							}
							else 
							{ 
								$( "#state_id").html('<option value="">- Select -</option>');
								$( "#city_id").html('<option value="">- Select -</option>');
							}
						}
						
						function selectCity(val,type)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									if(msg == "no_date_found")
									{

									}
									else
									{
										$( "#city_id").html(msg);
									}	
								});
							}
							else 
							{ 
								$( "#city_id").html('<option value="">- Select -</option>');
							}
						}
					</script>
					<?php
				}
				else
				{ 
					?>
					<!-- buttons start here -->
					<div class="row mb-2">
						<div class="col-md-6"><h3><b>Suppliers</b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($manageSuppliersMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="#" data-toggle="modal" data-target="#importcountryCSV" title="Import" class="btn btn-warning btn-sm">
										<i class="icon-import"></i> Import
									</a>
									
									<a href="<?php echo base_url(); ?>suppliers/ManageSuppliers/add" class="btn btn-info btn-sm">
										Create Supplier
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Supplier Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$supplierQry = "select supplier_id,supplier_name from sup_suppliers 
														
														order by sup_suppliers.supplier_name asc";

											$getSupplier = $this->db->query($supplierQry)->result_array();	
										?>
										<select name="supplier_id" id="supplier_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getSupplier as $row)
												{
													$selected="";
													if(isset($_GET['supplier_id']) && $_GET['supplier_id'] == $row["supplier_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["supplier_id"];?>" <?php echo $selected;?>><?php echo $row["supplier_name"];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3">Status</label>
									<div class="form-group col-md-9">
										<?php 
											$activeStatus = $this->common_model->lov('ACTIVESTATUS'); 
										?>
										
										<select name="active_flag" id="active_flag" class="form-control searchDropdown">
											<?php 
												foreach($activeStatus as $row)
												{
													$selected="";
													if(isset($_GET['active_flag']) && $_GET['active_flag'] == $row["list_code"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["list_code"];?>" <?php echo $selected;?>><?php echo ucfirst($row["list_value"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>suppliers/ManageSuppliers" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->

					<?php 
						if( isset($_GET) && !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<div class="col-md-10">
									
								</div>

								<div class="col-md-2 float-right text-right">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
									<div class="filter_page">
										<label>
											<span>Show :</span> 
											<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
												<?php 
													$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : NULL;
													foreach($this->items_per_page as $key => $value)
													{
														$selected="";
														if($key == $pageLimit){
															$selected="selected=selected";
														}
														?>
														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
														<?php 
													} 
												?>
											</select>
										</label>
									</div>
								</div>
							</div>
							<!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th onclick="sortTable(1)">Supplier Name</th>
											<th onclick="sortTable(4)">Postal Code</th>
											<th onclick="sortTable(5)">Contact Person</th>
											<th onclick="sortTable(5)" style="text-align:center;">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class="text-center" style="width:90px;">
														<div class="dropdown" style="width:90px;">
															<button type="button" class="btn btn-outline-info gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																<?php
																	if($manageSuppliersMenu['create_edit_only'] == 1 || $manageSuppliersMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($manageSuppliersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="Edit" href="<?php echo base_url(); ?>suppliers/ManageSuppliers/edit/<?php echo $row['supplier_id'];?>">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($manageSuppliersMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>suppliers/ManageSuppliers/view/<?php echo $row['supplier_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($manageSuppliersMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a href="<?php echo base_url(); ?>suppliers/ManageSuppliers/status/<?php echo $row['supplier_id'];?>/N" title="Block">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a href="<?php echo base_url(); ?>suppliers/ManageSuppliers/status/<?php echo $row['supplier_id'];?>/Y" title="Unblock">
																								<i class="fa fa-ban"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php 
																	} 
																?>
															</ul>
														</div>
													</td>
													<td><?php echo ucfirst($row['supplier_name']);?></td>
													<td>
														<?php 
															if($row['postal_code'] !='0')
															{
																echo $row['postal_code'];
															}
														?>
													</td>
													<td><?php echo $row['contact_person'];?></td>
													<td class="text-center">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-outline-success" title="Active">Active</span>
																<?php 
															} 
															else
															{ 
																?>
																<span class="btn btn-outline-warning" title="Inactive">Inactive</span>
																<?php 
															} 
														?>
													</td>
												</tr>
												<?php 
												$i++;
											}
										?>
									</tbody>
								</table>
								<?php 
									if(count($resultData) == 0)
									{
										?>
										<div class="text-center">
											<img src="<?php echo base_url();?>uploads/nodata.png">
										</div>
										<?php 
									} 
								?>
							</div>
							<!-- Table end here -->

							<!-- Pagination start here -->
							<?php 
								if( count($resultData) > 0 )
								{
									?>
									<div class="row">
										<div class="col-md-4 showing-count">
											Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
										</div>
										
										<!-- pagination start here -->
										<?php 
											if( isset($pagination) )
											{
												?>	
												<div class="col-md-8" class="admin_pagination" style="float:right;padding: 0px 20px 0px 0px;"><?php foreach ($pagination as $link){echo $link;} ?></div>
												<?php
											}
										?>
										<!-- pagination end here -->
									</div>
									<?php 
								} 
							?>
							<!-- Pagination end here -->
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
