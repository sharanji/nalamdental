<?php
	$physical_stock_adjustment 	= accessMenu(physical_stock_adjustment);
	$getRoles					= $this->roles_model->getRolesAll();
	$getIndustry				= $this->common_model->lov('INDUSTRY-TYPE');
	$getEmployeeType			= $this->common_model->lov('EMPLOYEE-TYPE');
	$getJobCategory 			= $this->jobs_model->jobCategoryList();
	$getQualification 			= $this->qualification_model->getQualification();
	$getExperience 				= $this->common_model->lov('EXPERIENCE');
	$activeStatus 				= $this->common_model->lov('ACTIVESTATUS');

?>
		<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$fieldSetDisabled = "disabled";
						$searchDropdown = "";
						$this->fieldDisabled = $fieldDisabled = "";
						$this->fieldReadonly = $fieldReadonly = "";
					}
					else
					{
						if($type == "add" || $type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
						}

						$fieldSetDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="header-lines">
							<!-- Buttons start here -->
							<div class="row mb-3">
								<div class="col-md-6">
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
											<?php echo $page_title ?>

										</b>
									</h3>
								</div>
								<div class="col-md-6 text-right">
									<?php
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm form_submit_valid">Save</button>
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm form_submit_valid">Submit</button>
											<?php
										}
									?>
									<a href="<?php echo base_url(); ?>jobs/manageJobs" class="btn btn-default btn-sm">Close</a>

								</div>
							</div>
							<!-- Buttons end here -->

							<fieldset <?php echo $fieldSetDisabled;?>>

								<section class="header-section">
									<div class="row">
										<div class="col-md-4">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Role</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group role_id">
														<select name="role_id" id="role_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getRoles as $roles)
																{
																	$selected="";
																	if(isset($editData[0]['role_id']) && $editData[0]['role_id'] == $roles["role_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $roles["role_id"];?>" <?php echo $selected;?>><?php echo ucfirst($roles["role_name"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Employee Type</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<select name="employment_type_id" id="employment_type_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getEmployeeType as $employeeType)
																{
																	$selected="";
																	if(isset($editData[0]['employment_type_id']) && $editData[0]['employment_type_id'] == $employeeType["list_type_value_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $employeeType["list_type_value_id"];?>" <?php echo $selected;?>><?php echo ucfirst($employeeType["list_value"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Experience</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<select name="experience_id" id="experience_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getExperience as $experience)
																{
																	$selected="";
																	if(isset($editData[0]['experience_id']) && $editData[0]['experience_id'] == $experience["list_type_value_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $experience["list_type_value_id"];?>" <?php echo $selected;?>><?php echo ucfirst($experience["list_value"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Designation</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<?php
															$getDesignation = $this->common_model->getDesignation()
														?>

														<select name="designation_id" id="designation_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getDesignation as $designation)
																{
																	$selected="";
																	if(isset($editData[0]['designation_id']) && $editData[0]['designation_id'] == $designation["designation_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $designation["designation_id"];?>" <?php echo $selected;?>><?php echo ucfirst($designation["designation_name"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Job Location</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<textarea name="job_location" id="job_location" rows="1" required autocomplete="off" class="form-control" placeholder="Job Location"><?php echo isset($editData[0]['job_location']) ? $editData[0]['job_location'] :"";?></textarea>
													</div>
												</div>
											</div>
                                           
										</div>
										<div class="col-md-4">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Industry Type</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<select name="industry_type_id" id="industry_type_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getIndustry as $industryType)
																{
																	$selected="";
																	if(isset($editData[0]['industry_type_id']) && $editData[0]['industry_type_id'] == $industryType["list_type_value_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $industryType["list_type_value_id"];?>" <?php echo $selected;?>><?php echo ucfirst($industryType["list_value"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Role Category</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<select name="job_category_id" id="job_category_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getJobCategory as $jobCategory)
																{
																	$selected="";
																	if(isset($editData[0]['job_category_id']) && $editData[0]['job_category_id'] == $jobCategory["job_category_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $jobCategory["job_category_id"];?>" <?php echo $selected;?>><?php echo ucfirst($jobCategory["job_name"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label"><span class="text-danger">*</span> Salary Per Annum</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="salary" name="salary" required autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['salary']) ? $editData[0]['salary'] :"";?>" placeholder="Salary" oninput="validateNumber(this)">
														<script>
															function validateNumber(input) {
																input.value = input.value.replace(/\D/g, '');
															}
														</script>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label start_date"><span class="text-danger">*</span> Valid From Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" name="valid_from" id="start_date" class="form-control previous_date" value="<?php echo isset($editData[0]['valid_from']) ? date("d-M-Y",strtotime($editData[0]['valid_from'])) : date("d-M-Y");?>" readonly placeholder="">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label job_description"><span class="text-danger">*</span> Job Description</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<textarea name="job_description" id="job_description" rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control" placeholder="Job Description"><?php echo isset($editData[0]['job_description']) ? $editData[0]['job_description'] : NULL;?></textarea>
													</div>
												</div>
											</div>

										</div>
										<div class="col-md-4">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label functional_area"><span class="text-danger">*</span> Functional Area</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<textarea name="functional_area" id="functional_area" rows="1" <?php echo $fieldReadonly;?> autocomplete="off" class="form-control" placeholder="Functional Area"><?php echo isset($editData[0]['functional_area']) ? $editData[0]['functional_area'] : NULL;?></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label qualification_id"><span class="text-danger">*</span> Education</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<select name="qualification_id" id="qualification_id" class="form-control <?php echo $searchDropdown;?>">
															<option value="">- Select -</option>
															<?php
																foreach($getQualification as $qualification)
																{
																	$selected="";
																	if(isset($editData[0]['qualification_id']) && $editData[0]['qualification_id'] == $qualification["qualification_id"] )
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $qualification["qualification_id"];?>" <?php echo $selected;?>><?php echo ucfirst($qualification["qualification_name"]);?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label key_skills"><span class="text-danger">*</span> Key Skills</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="key_skills" name="key_skills[]" required autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['key_skills']) ? $editData[0]['key_skills'] :"";?>" placeholder="Key Skils">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label end_date"><span class="text-danger">*</span> Valid To</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" name="valid_to" id="end_date" class="form-control previous_date" value="<?php echo isset($editData[0]['valid_to']) ? date("d-M-Y",strtotime($editData[0]['valid_to'])) : date("d-M-Y");?>" readonly>
													</div>
												</div>
											</div>
											<script>
												$(document).ready(function() {
													// Initialize the start_date (Valid From Date) datepicker
													$("#start_date").datepicker({
														changeMonth: true,
														changeYear: true,
														yearRange: "1950:<?php echo date('Y') + 10; ?>",
														dateFormat: "dd-M-yy",
														minDate: 0, // Disable backdates
														onSelect: function(selectedDate) {
															// Set the minDate of end_date based on selected start_date
															$("#end_date").datepicker("option", "minDate", selectedDate);
														}
													});

													// Initialize the end_date (Valid To Date) datepicker
													$("#end_date").datepicker({
														changeMonth: true,
														changeYear: true,
														yearRange: "1950:<?php echo date('Y') + 10; ?>",
														dateFormat: "dd-M-yy",
														minDate: $("#start_date").val() // Set minDate initially based on start_date value
													});
												});
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label requirements_and_skills"><span class="text-danger">*</span>Requirements and skills</label>
													</div>
												</div>
												<div class="col-md-8">
													<div class="form-group">
										                <textarea name="requirements_and_skills" id="requirements_and_skills" required rows="2" autocomplete="off" class="form-control tokenfield" placeholder=""><?php echo isset($editData[0]['requirements_and_skills']) ? preg_replace("/\r|\n/", "", $editData[0]['requirements_and_skills']) :"";?></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label roles_and_response"><span class="text-danger">*</span> Roles and Responsibilities</label>
													</div>
												</div>
												<div class="col-md-8">
													<div class="form-group">
										                <textarea name="roles_and_response" id="roles_and_response" required rows="2" autocomplete="off" class="form-control tokenfield" placeholder=""><?php echo isset($editData[0]['roles_and_response']) ? preg_replace("/\r|\n/", "", $editData[0]['roles_and_response']) :"";?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label job_qualification"><span class="text-danger">*</span> Job Qualification</label>
													</div>
												</div>
												<div class="col-md-8">
													<div class="form-group">
										                <textarea name="job_qualification" id="job_qualification" rows="2" autocomplete="off" class="form-control tokenfield" placeholder=""><?php echo isset($editData[0]['job_qualification']) ? preg_replace("/\r|\n/", "", $editData[0]['job_qualification']) :"";?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

								</section>

									<script>
										
										$(document).ready(function() {
											CKEDITOR.replace('roles_and_response');
											CKEDITOR.replace('job_qualification');
											CKEDITOR.replace('requirements_and_skills');
										});
        
										$(document).ready(function() {
											var type = '<?php echo $type; ?>';
											$(".form_submit_valid").prop('disabled', true);

											// Function to validate form
											function validateForm() {
												var role_id                     = $("#role_id").val();
												var industry_type_id             = $("#industry_type_id").val();
												var functional_area              = $("#functional_area").val();        // Input field
												var employment_type_id           = $("#employment_type_id").val();
												var job_category_id              = $("#job_category_id").val();
												var qualification_id             = $("#qualification_id").val();
												var experience_id                = $("#experience_id").val();
												var job_description      		 = $("#job_description").val();
												var salary                       = $("#salary").val();                // Input field
												var key_skills                   = $("#key_skills").val();            // Textarea field
												var designation_id               = $("#designation_id").val();
												var start_date                   = $("#start_date").val();
												var end_date                     = $("#end_date").val();
												var job_location                 = $("#job_location").val();          // Input field
												var requirements_and_skills      = CKEDITOR.instances.requirements_and_skills.getData();
												var roles_and_response           = CKEDITOR.instances.roles_and_response.getData();
												var job_qualification            = CKEDITOR.instances.job_qualification.getData();

												// Check if all fields are filled
												if (role_id !== "" &&
													industry_type_id !== "" &&
													functional_area.trim() !== "" &&                 // Input field check
													employment_type_id !== "" &&
													job_category_id !== "" &&
													qualification_id !== "" &&
													experience_id !== "" &&
													job_description.trim() !== "" &&
													salary.trim() !== "" &&                          // Input field check
													key_skills.trim() !== "" &&                      // Textarea field check
													designation_id !== "" &&
													start_date !== "" &&
													end_date !== "" &&
													job_location.trim() !== "" &&                    // Input field check
													requirements_and_skills.trim() !== "" &&
													roles_and_response.trim() !== "" &&
													job_qualification.trim() !== ""
												) {
													$(".form_submit_valid").prop('disabled', false); // Enable submit button
												} else {
													$(".form_submit_valid").prop('disabled', true);  // Disable submit button
												}
											}

											// Add event listeners for input, select, and textarea elements
											$("input, select, textarea").on('keyup change', function() {
												validateForm();
											});

											// Listen for CKEditor content changes
											CKEDITOR.instances.requirements_and_skills.on('change', function() {
												validateForm();
											});
											
											CKEDITOR.instances.roles_and_response.on('change', function() {
												validateForm();
											});
											
											CKEDITOR.instances.job_qualification.on('change', function() {
												validateForm();
											});

											// Initial check on page load
											validateForm();

											// Function to handle form submission
											function saveBtn(val) {
												$(".form_submit_valid").prop('disabled', true);

												var role_id                     = $("#role_id").val();
												var industry_type_id             = $("#industry_type_id").val();
												var functional_area              = $("#functional_area").val();        // Input field
												var employment_type_id           = $("#employment_type_id").val();
												var job_category_id              = $("#job_category_id").val();
												var qualification_id             = $("#qualification_id").val();
												var experience_id                = $("#experience_id").val();
												var job_description      		 = $("#job_description").val();
												var salary                       = $("#salary").val();                // Input field
												var key_skills                   = $("#key_skills").val();            // Textarea field
												var designation_id               = $("#designation_id").val();
												var start_date                   = $("#start_date").val();
												var end_date                     = $("#end_date").val();
												var job_location                 = $("#job_location").val();          // Input field
												var requirements_and_skills      = CKEDITOR.instances.requirements_and_skills.getData();
												var roles_and_response           = CKEDITOR.instances.roles_and_response.getData();
												var job_qualification            = CKEDITOR.instances.job_qualification.getData();

												if (role_id !== "" &&
													industry_type_id !== "" &&
													functional_area.trim() !== "" &&                 // Input field check
													employment_type_id !== "" &&
													job_category_id !== "" &&
													qualification_id !== "" &&
													experience_id !== "" &&
													job_description.trim() !== "" &&
													salary.trim() !== "" &&                          // Input field check
													key_skills.trim() !== "" &&                      // Textarea field check
													designation_id !== "" &&
													start_date !== "" &&
													end_date !== "" &&
													job_location.trim() !== "" &&                    // Input field check
													requirements_and_skills.trim() !== "" &&
													roles_and_response.trim() !== "" &&
													job_qualification.trim() !== ""
												) {
													// All fields are valid, remove error classes
													$(".role_id").removeClass('errorClass');
													$(".industry_type_id").removeClass('errorClass');
													$(".functional_area").removeClass('errorClass');
													$(".employment_type_id").removeClass('errorClass');
													$(".job_category_id").removeClass('errorClass');
													$(".qualification_id").removeClass('errorClass');
													$(".experience_id").removeClass('errorClass');
													$(".job_description").removeClass('errorClass');
													$(".salary").removeClass('errorClass');
													$(".key_skills").removeClass('errorClass');
													$(".designation_id").removeClass('errorClass');
													$(".start_date").removeClass('errorClass');
													$(".end_date").removeClass('errorClass');
													$(".job_location").removeClass('errorClass');
													$(".requirements_and_skills").removeClass('errorClass');
													$(".roles_and_response").removeClass('errorClass');
													$(".job_qualification").removeClass('errorClass');

													return true;
												} else {
													// Re-enable submit button if validation fails
													$(".form_submit_valid").prop('disabled', false);

													// Add error classes to missing fields
													if (role_id === "") {
														$(".role_id").addClass('errorClass');
													}
													if (industry_type_id === "") {
														$(".industry_type_id").addClass('errorClass');
													}
													if (functional_area.trim() === "") {
														$(".functional_area").addClass('errorClass');
													}
													if (employment_type_id === "") {
														$(".employment_type_id").addClass('errorClass');
													}
													if (job_category_id === "") {
														$(".job_category_id").addClass('errorClass');
													}
													if (qualification_id === "") {
														$(".qualification_id").addClass('errorClass');
													}
													if (experience_id === "") {
														$(".experience_id").addClass('errorClass');
													}
													if (job_description.trim() === "") {
														$(".job_description").addClass('errorClass');
													}
													if (salary.trim() === "") {
														$(".salary").addClass('errorClass');
													}
													if (key_skills.trim() === "") {
														$(".key_skills").addClass('errorClass');
													}
													if (designation_id === "") {
														$(".designation_id").addClass('errorClass');
													}
													if (start_date === "") {
														$(".start_date").addClass('errorClass');
													}
													if (end_date === "") {
														$(".end_date").addClass('errorClass');
													}
													if (job_location.trim() === "") {
														$(".job_location").addClass('errorClass');
													}
													if (requirements_and_skills.trim() === "") {
														$(".requirements_and_skills").addClass('errorClass');
													}
													if (roles_and_response.trim() === "") {
														$(".roles_and_response").addClass('errorClass');
													}
													if (job_qualification.trim() === "") {
														$(".job_qualification").addClass('errorClass');
													}

													return false;
												}
											}
										});

									</script>

								<!-- Line level end here -->
							</fieldset>
							<div class="col-md-12 mt-3 pr-0 text-right">
								<?php
									if($type == "add" || $type == "edit")
									{
										?>
										<!-- <a href="javascript:void(0)" id="save_btn" onclick="return saveBtn('save_btn','save');" class="btn btn-primary btn-sm submit_btn_bottom">Save Bottom</a> -->
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm form_submit_valid">Save</button>
										<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm form_submit_valid">Submit</button>
										<?php
									}
								?>
								<a href="<?php echo base_url(); ?>jobs/manageJobs" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
					</form>

					
					<?php
				}
				else
				{
					?>
					<!-- Buttons start here -->
					<div class="row">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>jobs/manageJobs/add" class="btn btn-info btn-sm">
										Create Job
									</a>
									<?php
								}
							?>
						</div>
					</div>
					<!-- Buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Job Category</label>
									<div class="form-group col-md-7">
										<select name="job_category_id" id="job_category_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getJobCategory as $jobCategory)
												{
													$selected="";
													if(isset($_GET["job_category_id"]) && $_GET["job_category_id"] == $jobCategory['job_category_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $jobCategory['job_category_id'];?>" <?php echo $selected;?>><?php echo $jobCategory['job_name'];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Role Name</label>
									<div class="form-group col-md-7">
										<select name="role_id" id="role_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getRoles as $roles)
												{
													$selected="";
													if(isset($_GET["role_id"]) && $_GET["role_id"] == $roles['role_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $roles['role_id'];?>" <?php echo $selected;?>><?php echo $roles['role_name'];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Experience</label>
									<div class="form-group col-md-7">
										<select name="experience_id" id="experience_id" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getExperience as $experience)
												{
													$selected="";
													if(isset($_GET["experience_id"]) && $_GET["experience_id"] == $experience['list_type_value_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $experience['list_type_value_id'];?>" <?php echo $selected;?>><?php echo $experience['list_value'];?></option>
													<?php 
												} 
											?>
										</select>

									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Status</label>
									<div class="form-group col-md-7">
										
										<select name="active_flag" id="active_flag" class="form-control searchDropdown">
											<!-- <option value="">- Select -</option> -->
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
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
								<a href="<?php echo base_url(); ?>jobs/manageJobs" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->

					<?php
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<?php
									if( isset($resultData) && count($resultData) > 0 )
									{
										?>
											<div class="col-md-6">
												<?php /*
													<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a>
												*/ ?>
											</div>

											<div class="col-md-6 float-right text-right">
												<?php
													$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
												?>
												<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>

												<div class="filter_page pt-0">
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
										<?php
									}
								?>
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover tbl_height">
									<thead>
										<tr>
											<th class="text-center tab-md-100">Controls</th>
											<th class="text-left tab-md-140">Job Category</th>
											<th class="text-left tab-md-140">Role Name</th>
											<th class="text-left tab-md-140">Employment Type</th>
											<th class="text-left tab-md-120">Experience</th>
											<th class="text-center tab-md-140">Applied Jobs</th>
											<th class="text-left tab-md-120">Created Date</th>
											<th class="text-center tab-md-100">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$i=0;
											$firstItem = $first_item;
											$totalValue = 0;
											foreach($resultData as $row)
											{
												?>
												<tr>
												<td class="text-center">
														<div class="dropdown" >
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm"  data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="table-dropdown dropdown-menu dropdown-menu-right">
																<?php
																	if($physical_stock_adjustment['create_edit_only'] == 1 || $physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																					<li>
																						<a title="Edit" href="<?php echo base_url(); ?>jobs/manageJobs/edit/<?php echo $row['job_id'];?>/">
																							<i class="fa fa-edit"></i> Edit
																						</a>
																					</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
																			{
																				
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>jobs/manageJobs/view/<?php echo $row['job_id'];?>/">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>jobs/manageJobs/status/<?php echo $row['job_id'];?>/N" title="Inactive">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>jobs/manageJobs/status/<?php echo $row['job_id'];?>/Y" title="Active">
																								<i class="fa fa-check"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				<li>
																				<?php 
																			} 
																		?>
																		<?php 
																	} 
																?>
															</ul>
														</div>
													</td>
													<td><?php echo $row['job_name'];?></td>
													<td><?php echo $row['role_name'];?></td>
													<td><?php echo $row['employment_type'];?></td>
													<td><?php echo $row['experience'];?></td>
													<td class="tab-full-width" style="text-align:center;">
														<?php 
															$job_category_id=$row['job_category_id'];

															$jobCount=$this->jobs_model->jobsCount($job_category_id);
															
															if(count($jobCount) > 0)
															{
																$btn_class='success';
															}
															else
															{
																$btn_class='warning';
															}
														?>
														<a href="<?php echo base_url();?>appliedjobs/manageAppliedJobs?customer_name=<?php echo $row['job_name'];?>" title="Applied Jobs">
															<span class="btn btn-outline-<?php echo $btn_class; ?>" style="width:40%;">Applied Jobs (<?php echo count($jobCount);?>)</span>
														</a>
														<a href="<?php echo base_url();?>appliedjobs/manageAppliedJobs?customer_name=<?php echo $row['job_name'];?>" title="Applied Jobs">
															
														</a>
													</td>
													<td><?php echo date(DATE_FORMAT,strtotime($row['created_date']));?></td>
													<td class="text-center">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active">
																	Active 
																</span>
																<?php 
															} 
															else
															{  ?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive">
																	Inactive 
																</span>
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
								if (count($resultData) > 0)
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











