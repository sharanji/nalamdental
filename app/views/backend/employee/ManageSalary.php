<?php /*
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageSalary" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		<?php
			if( isset($type) && $type == "add" || $type == "edit")
			{ 
				
			}
			else if($this->user_id == 1)
			{ 
				?>
				<a href="<?php echo base_url(); ?>employee/ManageSalary/add" class="btn btn-info">
					Add Employee Annexure
				</a>
				<?php 
			}
			else
			{
				
			} 
		?>
	</div>
</div>
<!-- Page header end-->
*/
?>
<?php $annexure = accessMenu(annexure); ?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add")
				{
					?>
					<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
					<legend class="text-uppercase font-size-sm font-weight-bold">
						<?php echo $type; ?> Employee Annexure :
					</legend>
					<form action="" class="form-validate-jquery salary_structure" id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<?php 
								$empQry = "select first_name,last_name,user_id,random_user_id from users 
								where 
									user_status=1 and
										register_type=1 
											order by first_name asc";
								$getEmployee = $this->db->query($empQry)->result_array();
							?>
							
							<?php 
								$empCode = isset($edit_data[0]['user_id']) ? $edit_data[0]['user_id'] :"";
								$get_emp_code = $this->db->query("select first_name,email,random_user_id,phone_number from users where user_id='".$empCode."' ")->result_array();
								$employee_code =isset($get_emp_code[0]['random_user_id']) ? $get_emp_code[0]['random_user_id'] :"";
								$employee_name =isset($get_emp_code[0]['first_name']) ? $get_emp_code[0]['first_name'] :"";
								
								$readonly = '';
								if($type == 'edit')
								{
									$readonly = 'readonly';
								}
								
							?>
							<style>
								.important
								{
									pointer-events: none !important;
								}
							</style>
							<script>  
								$(document).ready(function()
								{  
									$('#emp_code').keyup(function()
									{  
										var query = $(this).val();  
										if(query != '')  
										{  
											$.ajax({  
												url:"<?php echo base_url();?>employee/EmployeeNewAjaxSearch",  
												method:"POST",  
												data:{query:query},  
												success:function(data)  
												{  
													$('#patientList').fadeIn();  
													$('#patientList').html(data); 
													
												}  
											});  
										}  
									});

									
									$(document).on('click', '.list-unstyled-new li', function()
									{  
										var value = $(this).text();
										
										if(value === "Sorry! Employee Code Not Found.")
										{
											$('#emp_code').val("");  
											$('#patientList').fadeOut();
										}
										else
										{
											$('#emp_code').val(value);  
											$('#patientList').fadeOut();  
										}
									});
								}); 

								function getemployeeuserId(user_id, emp_name)
								{	
									if(user_id == 0)
									{
										var empCode = $('#emp_code').val();
										$('#emp_code').val(empCode);
										
										$('#emp_name').val('');												
										$("#emp_name").removeAttr("readonly","");
									}
									else
									{
										$('#user_id').val(user_id);
										$('#emp_name').val(emp_name);
										$("#emp_name").attr("readonly","readonly");

										getAnnexureDate(user_id);
									}
								}
								
								function getAnnexureDate(user_id)
								{
									if(user_id)
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'employee/getAjaxAnnexureDate';?>",
											data: { user_id: user_id }
										}).done(function( msg ) 
										{ 
											let annexureData = msg.split("@");
											let type = annexureData[2];
											
											if(type == 1) //employee annexure > 0
											{
												let string_from_date = annexureData[0];
												let string_to_date = annexureData[1];

												let addDays = 1; 

												var date = new Date(string_to_date),
													days = parseInt(addDays, 10);
												
												if(!isNaN(date.getTime()))
												{
													date.setDate(date.getDate() + days);

													var nextAnnexureStartDate = date.toInputFormat();

													$("#string_from_date").val(nextAnnexureStartDate);
													$("#string_from_date").addClass("important");
													
													//getDate1(nextAnnexureStartDate,1);
												} 
											}
											else if(type == 2)  //employee annexure == 0
											{
												let string_from_date = annexureData[0];
												$("#string_from_date").val(string_from_date);
												$("#string_from_date").removeClass("important");
											}
										});
									}
								}

								(function($, window, document, undefined)
								{
									Date.prototype.toInputFormat = function() 
									{
										const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
											"Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
										];

										const d = new Date();
										//document.write("The current month is " + monthNames[d.getMonth()]);

										var yyyy = this.getFullYear().toString();
										var mm = monthNames[(this.getMonth()).toString()];
										//var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
										var dd  = this.getDate().toString();

										var returnDate = dd + "-" + mm + "-" + yyyy;

										return returnDate;

										//return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
										//return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
									};
								})(jQuery, this, document);
							</script>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
								<input type="text" name="emp_code" id="emp_code" autocomplete="off"  <?php echo $this->validation;?> value="<?php echo $employee_code;?>" required class="form-control">
								<div id="patientList"></div>
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $empCode;?>" class="form-control">
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Name</label>
								<input type="text" name="emp_name" <?php echo $readonly;?> id="emp_name" autocomplete="off" class="form-control" value="<?php echo $employee_name; ?>" placeholder="">
							</div>
							
							<?php /* <?php 
								$empQry = "select first_name,last_name,user_id,random_user_id from users 
								where 
									user_status=1 and
										register_type=1 
											order by first_name asc";
								$getEmployee = $this->db->query($empQry)->result_array();
							?>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
								<select name="user_id" id="user_id" class="form-control searchDropdown" required>
									<option value="">- Select Employee -</option>
									<?php 
										foreach($getEmployee as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['user_id']) && $edit_data[0]['user_id'] == $row['user_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['user_id'];?>" <?php echo $selected;?>><?php echo $row['random_user_id'];?> - <?php echo ucfirst($row['first_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div> */?>
							<?php /* <div class="form-group col-md-3">
								<label class="col-form-label">Monthly Pay <span class="text-danger">*</span></label>
								<input type="text" name="monthly_pay" required <?php //echo $this->validation; ?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['monthly_pay']) ? $edit_data[0]['monthly_pay'] :"";?>" placeholder="">
							</div> 
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Yearly Pay <span class="text-danger">*</span></label>
								<input type="text" name="yearly_pay" required <?php //echo $this->validation; ?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['yearly_pay']) ? $edit_data[0]['yearly_pay'] :"";?>" placeholder="">
							</div> */ ?>
							<input type="hidden" name="monthly_pay" <?php //echo $this->validation; ?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['monthly_pay']) ? $edit_data[0]['monthly_pay'] :"";?>" placeholder="">
							<input type="hidden" name="yearly_pay" <?php //echo $this->validation; ?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['yearly_pay']) ? $edit_data[0]['yearly_pay'] :"";?>" placeholder="">
							
							<div class="form-group col-md-3">
								<label class="col-form-label">CTC <span class="text-danger">*</span></label>
								<input type="text" name="ctc" id="ctc" required <?php //echo $this->validation; ?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['ctc']) ? $edit_data[0]['ctc'] :"";?>" placeholder="">
							</div>

							<div class="form-group col-md-3">
								<label class="col-form-label">Effective From Date <span class="text-danger">*</span></label>
								<input type="text" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :"";?>">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Effective To Date <span class="text-danger">*</span></label>
								<input type="text" required name="string_to_date" id="string_to_date" class="form-control default_date" value="">
								<p id="valid_string_to_date"></p>
							</div>
							<style>
								p#valid_string_to_date{color:red !important;}
							</style>

							<script>
								$( function() 
								{
									var dateFormat = "dd-M-yy",
									from = $("#string_from_date").datepicker({
										/* changeMonth: true,
										changeYear: true,
										yearRange: "1950:<?php #echo date('Y'); ?>",
										//dateFormat: "dd-mm-yy"	
										dateFormat: "dd-M-yy" */

										changeMonth: true,
										changeYear: true,
										yearRange: "1950:<?php echo date('Y') + 10; ?>",
										dateFormat: "dd-M-yy"
									}).on("change", function() 
									{
										var toMinDate = getDate(this);
										toMinDate.setDate(toMinDate.getDate() + 1);
										to.datepicker("option", "minDate", toMinDate);
									}),
									
									to = $("#string_to_date").datepicker({
										/* defaultDate: "+1w",
										changeMonth: true,
										dateFormat: dateFormat,
										minDate: new Date(), */

										defaultDate: "+1w",
										changeMonth: true,
										changeYear: true,
										yearRange: "1950:<?php echo date('Y') + 10; ?>",
										dateFormat: "dd-M-yy",
										minDate: new Date(),
										onSelect: function(dateText, inst) 
										{
											getDate1();
										}
									})
									.on("change", function() 
									{
										var fromMaxDate = getDate(this);
										
										fromMaxDate.setDate(fromMaxDate.getDate() - 1);
										from.datepicker("option", "maxDate", fromMaxDate);
									});

									function getDate(element) 
									{
										var date;
										try 
										{
											date = $.datepicker.parseDate(dateFormat, element.value);
										} 
										catch (error) 
										{
											date = null;
										}
										return date;
									}

									function getDate1() 
									{
									  var string_to_date=$("#string_to_date").val();
									  var string_from_date=$("#string_from_date").val();

										var givenDate = Date.parse(string_to_date);
										if (!givenDate.isNaN) 
										{
											// set hours, minutes, seconds, milisecconds to zero for a comparison
											// on date only
											givenDate = new Date(givenDate).setHours(0,0,0,0);
											var todaysDate = Date.parse(string_from_date);
											
											if (givenDate > todaysDate) 
											{
												/* result.innerHTML = 'Great! You\'re starting on a future date or today!';
												result.style.color = 'green'; */
												$("#valid_string_to_date").html("");
												$('.register-but').removeAttr('disabled');
											} 
											else 
											{
												/* result.innerHTML = "Please choose a future date.";
												result.style.color = 'red'; */
												$("#valid_string_to_date").html("Please select a future date.");
												
												$('.register-but').attr('disabled','disabled');
											}
										}
									} 
								});
							</script>
							<div class="form-group col-md-3">
								<label class="col-form-label">Living City <span class="text-danger">*</span></label>
								<select name="living_city" onchange="selectLivingCity(this.value);" autocomplete="off" required class="form-control searchDropdown">
									<option value>- Select -</option>
									<?php 
										foreach($this->living_city as $key=>$value)
										{
											?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
											<?php 
										} 
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Place <span class="text-danger">*</span></label>
								<input type="text" name="place" autocomplete="off" required class="form-control" value="<?php echo isset($edit_data[0]['place']) ? $edit_data[0]['place'] :"";?>" placeholder="">
							</div>
						</div>

						<a href="javascript:void(0);" id="addElement" class="btn btn-primary">
							Add
						</a>

						<div class="row mt-2">
							<div class="col-sm-12">
								<div class="form-group">
									<div style="overflow-y: auto;">
									
										<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
										<!-- Modal Start-->
										<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header" --style="background: #022646;color: #fff;">
														<h5 class="modal-title" id="exampleModalLabel">Help</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													
													<div class="modal-body">
														<p>Plese choose the elements list as per listed below.</p>
														<p>1. Basic Salary</p>
														<p>2. HRA (Choose Living city to get its percentage)</p>
														<p>3. Variable Pay</p>
														<p>4. Provident Fund</p>
														<p>5. Other elements (if required)</p>
														<p>6. Allowance</p>
													</div>
													<!--
													<div class="modal-footer">
														<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>
														<button type="submit" class="btn btn-primary ml-3">Submit </button>
													</div>-->
													
												</div>
											</div>
										</div>
										<!-- Modal end-->

										<table class="table category --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
											<thead>
												<tr>
													<th colspan="13">
														Employee Elements
														<a title="Help" href="#" data-toggle="modal" data-target="#exampleModal">
															<i class="fa fa-question-circle" style="font-size: 15px;"></i>
														</a>
													</th>
												</tr>
												<tr>
													<th style="width:20px;"></th>
													<th class="text-center">Element Name</th>
													<th class="text-center">Element Percentage</th>
													<th class="text-center">Per month <?php echo CURRENCY_CODE;?></th>
													<th class="text-center">Per annum <?php echo CURRENCY_CODE;?></th>
												</tr>
											</thead>
											<tbody id="product_table_body">
												<!-- Basic-->
												<tr class="dataRowVal0 table_rows">
													<td>
														<input type="hidden" name="counter" class="counter_1" value="1">
														<input type="hidden" name="id" class="id_1"  value="0">
														<input type="hidden" name="category_id" id="category_id1" value="1">
														
													</td>

													<td class="text-center">
														<?php 
															$getElements = $this->db->query('select 
															hr_payslip_categories.category_id,
															hr_payslip_categories.category_name,
															hr_payslip_categories.order_number from hr_payslip_categories 
															where 
																category_id = 1 and category_status=1')->result_array();
														?>
														<select class="form-control element_id_class1 searchDropdown--" id="element_id1" name="element_id[]">
															<?php 
																foreach ($getElements as $elements) 
																{
																	?>
																	<option value="<?php echo $elements["category_id"]; ?>"><?php echo $elements["category_name"]; ?></option>
																	<?php
																}
															?>	
														</select>
													</td>

													<td class="text-center">
														<?php
															$getElementPercentage =  $this->db->query("select 
																org_elements_percentage.* from org_elements_percentage
															where 
																org_elements_percentage.element_id= 1 and
																org_elements_percentage.percentage_status= 1
																	order by org_elements_percentage.element_percentage asc
															")->result_array();
														?>
														<select class="form-control element_percentage_id1 searchDropdown--" id="element_percentage_id1" name="element_percentage_id[]">
															<?php 
																foreach($getElementPercentage as $percentage)
																{
																	?>
																	<option value="<?php echo $percentage['element_percentage'];?>"><?php echo  ucfirst($percentage['element_percentage']); ?></option>
																	<?php
																}
															?>
														</select>
													</td>
													
													<td class="tab-medium-width text-center">
														<input type="number" readonly="" class="form-control text-right" value="0.00" name="per_month_inr[]" id="per_month_inr1">
													</td>
													<td class="tab-medium-width text-center">
														<input type="number" readonly="" class="form-control text-right element_class1 element_id1" value="0.00" name="per_annum_inr[]" id="per_annum_inr1">
													</td>
												</tr>
												
												<!-- HRA Start here -->
												<tr class="dataRowVal0 table_rows">
													<td>
														<input type="hidden" name="counter" class="counter_2" value="2">
														<input type="hidden" name="id" class="id_2" value="1">
														<input type="hidden" name="category_id" id="category_id2" value="2">
													</td>

													<td class="text-center">
														<?php 
															$getElements = $this->db->query('select 
															hr_payslip_categories.category_id,
															hr_payslip_categories.category_name,
															hr_payslip_categories.order_number from hr_payslip_categories 
															where 
																category_id = 2 and category_status=1')->result_array();
														?>
														<select class="form-group form-control element_id_class1 searchDropdown--" id="element_id2" name="element_id[]">
															<?php 
																foreach ($getElements as $elements) 
																{
																	?>
																	<option value="<?php echo $elements["category_id"]; ?>"><?php echo $elements["category_name"]; ?></option>
																	<?php
																}
															?>	
														</select>
													</td>

													<td class="text-center">
														<input type="hidden" class="form-control" name="element_percentage" id="element_percentage2" value="0">
														<?php
															/*$getElementPercentage =  $this->db->query("select 
																org_elements_percentage.* from org_elements_percentage
															where 
																org_elements_percentage.element_id= 2 and
																org_elements_percentage.percentage_status= 1
																	order by org_elements_percentage.element_percentage asc
															")->result_array();*/
														?>
														<select class="form-group form-control element_percentage_id2 searchDropdown--" id="element_percentage_id2" name="element_percentage_id[]">
															<option value="">- Please select living city -</option>
															<?php 
																/* foreach($getElementPercentage as $percentage)
																{
																	?>
																	<option value="<?php echo $percentage['element_percentage'];?>"><?php echo  ucfirst($percentage['element_percentage']); ?></option>
																	<?php
																}*/
															?>
														</select>
													</td>
													
													<td class="tab-medium-width text-center">
														<input type="number" readonly="readonly" class="form-control text-right" value="0.00" name="per_month_inr[]" id="per_month_inr2">
													</td>

													<td class="tab-medium-width text-center">
														<input type="number" readonly="readonly" class="form-control text-right element_class2 element_id2" value="0.00" name="per_annum_inr[]" id="per_annum_inr2">
													</td>
												</tr>
												<!-- HRA End here -->
											</tbody>
										</table>
									</div>

									<input type="hidden" name="total_value" id="total_value" value='0.00'>
									<input type="hidden" name="grand_total" id="grand_total" value='0.00'>
									<input type="hidden" name="table_data" id="table_data" value=''>
									
									<table class="table table-bordered table-condensed table-hover">
										<tr>
											<td colspan="1" class="text-left exceed_amount_disabled" style="display:none;">
												<a href="javascript:void(0)" onclick="CalculateAnnexure();" class="text-left btn btn-primary">Validate Annexure</a>
											</td>
					
											<td colspan="1" class="text-right">
												<b>Total ( <?php echo CURRENCY_SYMBOL;?> ) </b>
											</td>

											<td class="text-right">
												<span id="grandTotalPerMonth">0.00</span>
											</td>

											<td class="text-right">
												<span id="grandTotal">0.00</span>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					
						<span class="note-content exceed_amount_disabled" style="display:none;">
							<b>Note : </b> CTC detail breakup is not matching with CTC.
						</span>

						<span class="note-content pf_contribution_amount_disabled" style="display:none;float:left;width:100%;">
							<b>Note : </b> Maximum Employer PF Per Annum <?php echo PF_CONTRIBUTION; ?>.
						</span>

						<div class="d-flexad text-right">
							<a href="<?php echo base_url(); ?>employee/ManageSalary" class="btn btn-default">Cancel</a>
							<button type="submit" class="btn btn-primary ml-2 register-but">Submit</button>
						</div>
					</form>
					<script> 
						function CalculateAnnexure()
						{
							var allowence = $("table.product_table").find(".element_category18").val();
							var element_id = $("table.product_table").find(".element_id18").val(); 
							
							if(allowence == 18) //Allowence
							{
								var id = $(".element_category18").attr('id');
								
								const myArray = id.split("id");
								let counter = myArray[1];

								var empCTC = $("#ctc").val();
								var grandTotal = 0;
								
								$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
								{	
									grandTotal += +$(this).val();
								});

								//var grandTotal = $("#grand_total").val();

								var grand_total = element_id;
								
								var flexibleBenifit = empCTC - (grandTotal - grand_total);

								$("table.product_table").find("#per_annum_inr"+counter).val(flexibleBenifit.toFixed(2));
								var flexiblePerMonth = flexibleBenifit / 12;
								$("table.product_table").find("#per_month_inr"+counter).val(flexiblePerMonth.toFixed(2));
								
								/*var grandTotal1 = $("#grand_total").val();
								if( empCTC < grandTotal1 )
								{
									$(".register-but").attr("disabled", true);
									$(".exceed_amount_disabled").show();
								}
								else
								{
									$(".register-but").removeAttr("disabled", false);
									$(".exceed_amount_disabled").hide();
								}*/

								$(".register-but").removeAttr("disabled", false);
								$(".exceed_amount_disabled").hide();
							}

							calculateGrandTotalLivingCity();	
						}

						function selectLivingCity(val)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url  :"<?php echo base_url().'employee/ajaxSelectLivingCityPercentage';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									var splitData = msg.split('@');

									$("table.product_table").find("#element_percentage_id2").html(splitData[0]);

									$("#element_percentage2").val(splitData[1]);
									
									var ctc= $("#ctc").val();
									var hrElement = $("table.product_table").find("#category_id2").val();
									var basicElement = $("table.product_table").find("#category_id1").val();
									var basicElementPer = $("table.product_table").find("#element_percentage_id1").val();
									var hrElementPer = splitData[1];
								
									hrElementCalcLivingCity(2,2,hrElement,ctc,hrElementPer,basicElement,basicElementPer);
									calculateGrandTotalLivingCity();

									var empCTC = $("#ctc").val();
									if(empCTC > 0)
									{
										$(".exceed_amount_disabled").show();
									}
								});
							}
							else 
							{ 
								alert("No Percentage under this Living City!");
							}
						}

						function calculateGrandTotalLivingCity() 
						{
							var grandTotalPerMonth = 0;
							$("table.product_table").find('input[name^="per_month_inr"]').each(function () 
							{
								grandTotalPerMonth += +$(this).val();
							});

							var grandTotal = 0;
							$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
							{
								grandTotal += +$(this).val();
							});
	
							$('#total_value').val(grandTotal.toFixed(2));
							$('#grandTotal').text(grandTotal.toFixed(2));
							$('#grand_total').val(grandTotal.toFixed(2));	

							$('#grandTotalPerMonth').text(grandTotalPerMonth.toFixed(2));
						}

						function hrElementCalcLivingCity(counter2,key2,hrElement,ctc,hrElementPer,basicElement,basicElementPer)
						{
							var employeeCTC = ctc;
							var basicPerAnnum =  basicElementPer / 100 * employeeCTC;
							var hraPerAnnum = hrElementPer / 100 * basicPerAnnum;

							$("table.product_table").find("#per_annum_inr"+counter2).val(hraPerAnnum.toFixed(2));
							var hraPerMonth = hraPerAnnum / 12;
							$("table.product_table").find("#per_month_inr"+counter2).val(hraPerMonth.toFixed(2));
						}

						$(document).ready(function()
						{
							var i = 2;
							var product_data = new Array();
							/* var counter = <?php //echo count($items);?>  + 1; */
							var counter = 3;
											
							$('#addElement').click(function()
							{
								var ctc = $("#ctc").val();
								if(ctc =="") 
								{
									$('#ctc').focus();
									$('#err_product').text('Please enter employee Annual CTC!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								}
								else 
								{
									$(".popup-overlay").hide();
									var id = 1;
									$('#err_product').text('');
									var flag = 0;
									
									if(id != "")
									{
										$.ajax({
											url        : "<?php echo base_url('employee/getElements') ?>/"+id,
											type       : "GET",
											data       : {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
											datatype   : "JSON",
											success    : function(d)
											{
												data = JSON.parse(d);

												/*
												$("table.product_table").find('input[name^="category_id"]').each(function () 
												{
													/*if(data[0].product_id  == +$(this).val())
													{
														flag = 1;
													}*/
												/*
													var row = $(this).closest("tr");
													var category_id = +row.find('input[name^="category_id"]').val();
																													
													if(category_id == 0)
													{
														flag = 1;
													}
												});
												*/
												
												if(flag == 0)
												{
													var id = "0";
													var element_percentage = "0";
													var per_month_inr = "0.00";
													var per_annum_inr = "0.00";

													var product = { 
														"element_id"           : id,
														"element_percentage"   : element_percentage,
														"per_month_inr"        : per_month_inr,
														"per_annum_inr"        : per_annum_inr,
													}; 
													
													product_data[i] = product;
													length = product_data.length - 1 ;

													//select_items
													var select_element = "";
													select_element += '<div class="form-group">';
													select_element += '<select class="form-control element_id_class'+counter+'" style="" id="element_id'+counter+'" name="element_id[]">';
													select_element += '<option value="">- Select -</option>';
													for(a=0;a<data['category'].length;a++)
													{
														select_element += '<option value="' + data['category'][a].category_id + '">' + data['category'][a].category_name+ '</option>';
													}
													select_element += '</select></div>';

													//select_items
													var select_element_percentage = "";
													select_element_percentage += '<div class="form-group">';
													select_element_percentage += '<select class="form-control element_percentage_id'+counter+'" style="" id="element_percentage_id'+counter+'" name="element_percentage_id[]">';
													select_element_percentage += '<option value="">- Select -</option>';
													
													select_element_percentage += '</select></div>';

													var newRow = $("<tr class='dataRowVal"+id+" table_rows'>");
													var cols = "";
													cols += "<td><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='category_id' class='ele_category"+ counter +"' id='category_id"+ counter +"' value="+id+"></td>";
													cols += '<td>'+select_element+'</td>';
													cols += '<td>'+select_element_percentage+'</td>';

													//Element Percentage
													/*cols += "<td class='tab-medium-width text-center'>"
														+"<input type='text' class='form-control text-right' value='"+element_percentage+"' name='element_percentage"+ counter +"' id='element_percentage"+ counter +"' >"
														+"</td>";
													*/
													
													//Per Month INR
													cols += "<td class='tab-medium-width text-center'>"
														+"<input type='number' readonly class='form-control text-right' value='"+per_annum_inr+"' name='per_month_inr[]' id='per_month_inr"+ counter +"' >"
														+"</td>";

													//Per Annum INR
													cols += "<td class='tab-medium-width text-center'>"
														+"<input type='number' readonly class='form-control text-right element_class"+ counter +"' value='"+per_annum_inr+"' name='per_annum_inr[]' id='per_annum_inr"+ counter +"' >"
														+"</td>";

													cols += "</tr>";
													newRow.html(cols);
													$("table.product_table").append(newRow);
													var table_data = JSON.stringify(product_data);
													$('#table_data').val(table_data);
													i++;
													counter++;
													calculateGrandTotal();
												}
												else
												{
													//var id = data[0].product_id;
													//var quantity = parseFloat( $(".dataRowVal"+id).find('input[name^="qty"]').val() );
													//$(".dataRowVal"+id).find('input[name^="qty"]').val(quantity + 1)
													//calculateRow( $(".dataRowVal"+id) );
													//calculateDiscountTax( $(".dataRowVal"+id) );
													//calculateGrandTotal(); 
													$('#err_product').text('Please fill the all required fields').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
												}
													
											},
											error: function(xhr, status, error) 
											{
												$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										});
										
									}
								}
								
							});

							$("table.product_table").on("click", "a.deleteRow", function (event) 
							{
								deleteRow($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							});

							function deleteRow(row)
							{
								var id = +row.find('input[name^="id"]').val();
								var array_id = product_data[id].category_id;
								//product_data.splice(id, 1);
								product_data[id] = null;
								//alert(product_data);
								var table_data = JSON.stringify(product_data);
								$('#table_data').val(table_data);
							}

							$("form.salary_structure").on("input keyup change", 'input[name^="ctc"]', function (event) 
							{
								
								var row = $(this).closest("tr");
								var ctc= $("#ctc").val();
								//Basic 
								var counter1 = $("table.product_table").find(".counter_1").val();
								var key1 = $("table.product_table").find(".id_1").val();
								var basicElement = $("table.product_table").find("#category_id"+counter1).val();
								var basicElementPer = $("table.product_table").find("#element_percentage_id"+counter1).val();
								
								//HR
								var counter2 = $("table.product_table").find(".counter_2").val();
								var key2 = $("table.product_table").find(".id_2").val();
								var hrElement = $("table.product_table").find("#category_id"+counter2).val();
								var hrElementPer = $("table.product_table").find("#element_percentage_id"+counter2).val();
								
								basicElementCalc(counter1,key1,basicElement,ctc,basicElementPer);

								hrElementCalc(counter2,key2,hrElement,ctc,hrElementPer,basicElement,basicElementPer);
								//calculateDefaultPercentage($(this).closest("tr"));
								/* calculateGrandTotal();
								validateGrandTotal(); */
								var variable_element_ID = $("table.product_table").find(".element_category4").val();
								var employerPf_element_ID = $("table.product_table").find(".element_category5").val();
								var allowence_element_ID = $("table.product_table").find(".element_category18").val();
								var gratuity_element_ID = $("table.product_table").find(".element_category17").val();
								
								//variable
								if(variable_element_ID == 4) //Variable
								{
									var variableElementPer = $("table.product_table").find(".element_percentage_class4").val();
									variableElementCalc(ctc,variableElementPer,variable_element_ID);
								}

								//Pf
								if(employerPf_element_ID == 5) //Pf
								{
									var employerPfElementPer = $("table.product_table").find(".element_percentage_class5").val();
									employerPfElementCalc(employerPfElementPer,employerPf_element_ID);
								}

								/* if(employerPf_element_ID == 5) //Pf
								{
									var employerPfElementPer = $("table.product_table").find(".element_percentage_class5").val();
									employerPfElementCalc(employerPfElementPer,employerPf_element_ID);
								} */

								if(gratuity_element_ID == 17) //gratuity
								{
									var gratuityElementPer = $("table.product_table").find(".element_percentage_class17").val();
									gratuityElementCalc(gratuityElementPer,gratuity_element_ID);
								}

								calculateDefaultPercentage($(this).closest("tr"));
								calculateGrandTotal();
								validateGrandTotal();	

								//allowence
								if(allowence_element_ID == 18) //allowence
								{
									CalculateAnnexure();
									//var employerPfElementPer = $("table.product_table").find(".element_percentage_class18").val();
									//allowenceElementCalc(employerPfElementPer,allowence_element_ID);
								}
							});

							function basicElementCalc(counter1,key1,basicElement,ctc,basicElementPer)
							{
								var employeeCTC = ctc;
								var basicPerAnnum = basicElementPer / 100 * employeeCTC;
								$("table.product_table").find("#per_annum_inr"+counter1).val(basicPerAnnum.toFixed(2));

								var basicPerMonth = basicPerAnnum / 12;
								$("table.product_table").find("#per_month_inr"+counter1).val(basicPerMonth.toFixed(2));
							}

							function hrElementCalc(counter2,key2,hrElement,ctc,hrElementPer,basicElement,basicElementPer)
							{
								var employeeCTC = ctc;
								var basicPerAnnum =  basicElementPer / 100 * employeeCTC;
								var hraPerAnnum = hrElementPer / 100 * basicPerAnnum;

								$("table.product_table").find("#per_annum_inr"+counter2).val(hraPerAnnum.toFixed(2));
								var hraPerMonth = hraPerAnnum / 12;
								$("table.product_table").find("#per_month_inr"+counter2).val(hraPerMonth.toFixed(2));
							}
							
							function variableElementCalc(employeeCTC,element_percentage,element_id)
							{
								var variablePerAnnum =  element_percentage / 100 * employeeCTC;
								
								$("table.product_table").find(".element_id_per_year4").val(variablePerAnnum.toFixed(2));
								var variablePerMonth = variablePerAnnum / 12;
								$("table.product_table").find(".element_id_per_month4").val(variablePerMonth.toFixed(2));
							}

							function employerPfElementCalc(element_percentage,element_id)
							{
								var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
								var pfPerAnnum =  element_percentage / 100 * empBasicCTC;

								var pf_contribution = '<?php echo PF_CONTRIBUTION;?>';
								
								//18000 < 21000

								if( pfPerAnnum < pf_contribution )
								{
									$(".pf_contribution_amount_disabled").hide();
									$("table.product_table").find(".element_id_per_year5").val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									$("table.product_table").find(".element_id_per_month5").val(pfPerMonth.toFixed(2));
								}
								else
								{
									$(".pf_contribution_amount_disabled").show();
									var subTotalFormatted = parseFloat(pf_contribution).toFixed(2);
									$("table.product_table").find(".element_id_per_year"+element_id).val(subTotalFormatted);
									var pfPerMonth = pf_contribution / 12;
									$("table.product_table").find(".element_id_per_month"+element_id).val(pfPerMonth.toFixed(2));
								}
							}

							function gratuityElementCalc(element_percentage,element_id)
							{
								var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
								var retiralsPerAnnum =  element_percentage / 100* empBasicCTC;

								//var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

								$("table.product_table").find(".element_id_per_year17").val(retiralsPerAnnum.toFixed(2));
								var pfRetiralsMonth = retiralsPerAnnum / 12;
								$("table.product_table").find(".element_id_per_month17").val(pfRetiralsMonth.toFixed(2));
							}

							function allowenceElementCalc(element_percentage,element_id)
							{
								var grandTotal = $("#grand_total").val();
								var employeeCTC = $('#ctc').val();

								var flexibleBenifit =  employeeCTC - grandTotal;

								$("table.product_table").find(".element_id_per_year18").val(flexibleBenifit.toFixed(2));
								var flexiblePerMonth = flexibleBenifit / 12;
								$("table.product_table").find(".element_id_per_month18").val(flexiblePerMonth.toFixed(2));
 							}

							//Select Element 
							$("table.product_table").on("change", 'select[name^="element_id"]', function (event) 
							{
								var row = $(this).closest("tr");
								var id = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								var element_id = $('#element_id'+counter).val();
								row.find('#category_id'+counter).val(element_id);

								$(".element_class"+counter).addClass("element_id"+element_id);
								$(".ele_category"+counter).addClass("element_category"+element_id);

								if(element_id !="") 
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'employee/ajaxSelectEmployeePecenatge';?>",
										data: { id: element_id }
									}).done(function( msg ) 
									{   
										$("#element_percentage_id"+counter).html(msg);
									});
								}
								else 
								{ 
									alert("No percentage under this element!");
								}

								calculateDefaultPercentage($(this).closest("tr"));
								//calculateDiscountTax($(this).closest("tr"));
								calculateGrandTotal();
								validateGrandTotal();
								//calculateGrandTotal();
								//calculateDefaultPercenage($(this).closest("tr"));
								//var category_description = $('#category_description'+counter).val();
								
								/* var grand_total = parseFloat(price) * qty; */
								/* var grand_total = $("#grand_total").val();
								if( parseFloat(grand_total) > parseFloat(currentBalance))
								{
									var quantity = row.find('input[name^="qty"]').val('1');
									row.find('input[name^="price"]').val(price);
									row.find('input[name^="price"]').val(product_price.toFixed(2));
									quantity = row.find('input[name^="qty"]').val('1');
									calculateRow($(this).closest("tr"));
									calculateDiscountTax($(this).closest("tr"));
									calculateGrandTotal();
									$("#err_product").html("Sorry, you don't have sufficient budget value.");
								}
								else
								{ 
									calculateRow($(this).closest("tr"));
									calculateDiscountTax($(this).closest("tr"),event.target.value);
									calculateGrandTotal();
									dueDate(row,request_date);
									dueDate(row,event.target.value);
								}*/
							});

							//Element Percentage
							$("table.product_table").on("change", 'select[name^="element_percentage_id"]', function (event) 
							{
								var row = $(this).closest("tr");
								
								calculateDefaultPercentage(row);
								//calculateDiscountTax($(this).closest("tr"));
								calculateGrandTotal();
								validateGrandTotal();
								//calculateDefaultPercentage($(this).closest("tr"));
							});
	
							/*function calculateRow(row) 
							{
								var employeeCTC = $('#ctc').val();

								var id = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								var element_id = $('#element_id'+counter).val();
								var element_percentage = $('#element_percentage_id'+counter).val();
							} */

							function calculateDefaultPercentage(row) 
							{
								var key = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
							
								var element_id = +row.find('#element_id'+counter).val();
								var amount = +row.find('#amount'+counter).val();
								
								var element_percentage = $('#element_percentage_id'+counter).val();
								var employeeCTC = $('#ctc').val();

								/*
								if(element_id == 1) //Basic
								{
									//var element_percentage = "0.5";
									//row.find('#element_percentage'+counter).val(element_percentage);
									//var basicPerAnnum = employeeCTC / 100 * element_percentage;

									var basicPerAnnum = element_percentage / 100 * employeeCTC;
									row.find('#per_annum_inr'+counter).val(basicPerAnnum.toFixed(2));

									var basicPerMonth = basicPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(basicPerMonth.toFixed(2));
								}
								else if(element_id == 2) //HRA
								{
									var PerannumINR = 0;
									var PerannumINRnotBasic =0;
									$("table.product_table").find('input[name^="category_id"]').each(function () 
									{
										var elementID = $(this).val();
										//var elementID = row.find('input[name^="category_id"]').val();
										
										$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
										{
											/*if( elementID == 1 )
											{
												PerannumINR += $(this).val();
											}*/
								/*
											if( elementID != 1 )
											{
												PerannumINRnotBasic += $(this).val();
											}
										});
									});

									//var element_percentage = "0.4";
									//row.find('#element_percentage'+counter).val(element_percentage);
									var basicPerAnnum =  PerannumINRnotBasic;
									var hraPerAnnum = element_percentage / 100 * basicPerAnnum;

									row.find('#per_annum_inr'+counter).val(hraPerAnnum.toFixed(2));
									var hraPerMonth = hraPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(hraPerMonth.toFixed(2));
								}else */
								
								if(element_id == 5) //PF
								{
									/* var basicPerAnnum =  element_percentage / 100 * employeeCTC;
									
									var pfPerAnnum = Math.round(basicPerAnnum * element_percentage);
									var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

									row.find('#per_annum_inr'+counter).val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(pfPerMonth.toFixed(2)); 

									*/
									
									var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
									var pfPerAnnum =  element_percentage / 100 * empBasicCTC;

									var pf_contribution = '<?php echo PF_CONTRIBUTION;?>';
									
									//18000 < 21000

									if( pfPerAnnum < pf_contribution )
									{
										$(".pf_contribution_amount_disabled").hide();
										$("table.product_table").find("#per_annum_inr"+counter).val(pfPerAnnum.toFixed(2));
										var pfPerMonth = pfPerAnnum / 12;
										$("table.product_table").find("#per_month_inr"+counter).val(pfPerMonth.toFixed(2));
									}
									else
									{
										$(".pf_contribution_amount_disabled").show();
										var subTotalFormatted = parseFloat(pf_contribution).toFixed(2);
										$("table.product_table").find("#per_annum_inr"+counter).val(subTotalFormatted);
										var pfPerMonth = pf_contribution / 12;
										$("table.product_table").find("#per_month_inr"+counter).val(pfPerMonth.toFixed(2));
									}

									//var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

									/* $("table.product_table").find("#per_annum_inr"+counter).val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(pfPerMonth.toFixed(2)); */
								
									/* row.find('#per_annum_inr'+counter).val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(pfPerMonth.toFixed(2));
									*/
								}
								else if(element_id == 17) //Gratuity
								{
									//var element_percentage = "0.0481";
									//row.find('#element_percentage'+counter).val(element_percentage);

									//var basicPerAnnum = Math.round(employeeCTC * 0.5); //Basic Per Annum
									//var retiralsPerAnnum = Math.round(basicPerAnnum * element_percentage);
									//row.find('#per_annum_inr'+counter).val(retiralsPerAnnum.toFixed(2));
								
									var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
									var retiralsPerAnnum =  element_percentage / 100 * empBasicCTC;
									
									//var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

									$("table.product_table").find("#per_annum_inr"+counter).val(retiralsPerAnnum.toFixed(2));
									var retiralsPerMonth = retiralsPerAnnum / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(retiralsPerMonth.toFixed(2));
								}
								else if(element_id == 4) //varible pay 
								{
									//var element_percentage = "0.15";
									/*row.find('#element_percentage'+counter).val(element_percentage);

									var basicPerAnnum = Math.round(employeeCTC * element_percentage); //Basic Per Annum
									var variablePerAnnum = Math.round(basicPerAnnum);
									row.find('#per_annum_inr'+counter).val(variablePerAnnum.toFixed(2));

									var variablePerMonth = Math.round(variablePerAnnum / 12);
									row.find('#per_month_inr'+counter).val(variablePerMonth.toFixed(2));*/
		
									
									var variablePerAnnum =  element_percentage / 100 * employeeCTC;

									$("table.product_table").find("#per_annum_inr"+counter).val(variablePerAnnum.toFixed(2));
									var variablePerMonth = variablePerAnnum / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(variablePerMonth.toFixed(2));
								}
								else if(element_id == 18) //Flexible Benefit Plan
								{
									/* var basicPerAnnum = Math.round(employeeCTC * 0.5);
									var pfPerAnnum = Math.round(basicPerAnnum * 0.12);
									var retiralsPerAnnum = Math.round(basicPerAnnum * 0.0481);
									var hraPerAnnum = Math.round(basicPerAnnum * 0.4);
									
									//varible pay
									var variablebasicPerAnnum = Math.round(employeeCTC * 0.15); //Basic Per Annum
									var variablePerAnnum = Math.round(variablebasicPerAnnum);
												
									var flexibleBenifit = employeeCTC - basicPerAnnum - pfPerAnnum - retiralsPerAnnum - variablePerAnnum - hraPerAnnum;
									
									var flexibleBenifitPerAnnum = Math.round(flexibleBenifit);
									row.find('#per_annum_inr'+counter).val(flexibleBenifitPerAnnum.toFixed(2));

									var flexibleBenifitPerAnnum = Math.round(flexibleBenifitPerAnnum / 12);
									row.find('#per_month_inr'+counter).val(flexibleBenifitPerAnnum.toFixed(2));*/ 
									
									var grandTotal = $("#grand_total").val();


									/*
									var basicAmount = $("table.product_table").find(".element_id1").val(); //basicID 1
									var hraAmount = $("table.product_table").find(".element_id2").val(); //HRA 2
									var variablePayAmt = $("table.product_table").find(".element_id4").val(); //Variable 4
									var pfAmt = $("table.product_table").find(".element_id5").val(); //PF 5
									var reterialsAmt = $("table.product_table").find(".element_id17").val(); //reterialsAmt 17
									var flexiavleAmt = $("table.product_table").find(".element_id18").val(); //Flexiable 18
									
									if(basicAmount == undefined){
										basicAmount = 0;
									}else{
										basicAmount = basicAmount;
									}

									if(hraAmount == undefined){
										hraAmount = 0;
									}else{
										hraAmount = hraAmount;
									}

									if(variablePayAmt == undefined){
										variablePayAmt = 0;
									}else{
										variablePayAmt = variablePayAmt;
									}

									if(pfAmt == undefined){
										pfAmt = 0;
									}else{
										pfAmt = pfAmt;
									}

									if(reterialsAmt == undefined){
										reterialsAmt = 0;
									}else{
										reterialsAmt = reterialsAmt;
									}

									if(flexiavleAmt == undefined){
										flexiavleAmt = 0;
									}else{
										flexiavleAmt = flexiavleAmt;
									}

									var grandTotal = parseInt(basicAmount) + parseInt(hraAmount) + parseInt(variablePayAmt) + parseInt(pfAmt) + parseInt(reterialsAmt) + parseInt(flexiavleAmt); 
									*/
									
									
									//alert(grandTotal);

									//per_annum_inr1

									/* var grandTotal = 0;
									$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
									{
										grandTotal += +$(this).val();
									});*/ 

									var flexibleBenifit = employeeCTC - grandTotal;

									$("table.product_table").find("#per_annum_inr"+counter).val(flexibleBenifit.toFixed(2));
									var flexiblePerMonth = flexibleBenifit / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(flexiblePerMonth.toFixed(2));
								}
							}

							/*
							function calculateDiscountTax(row,data = 0,data1 = 0)
							{
								/*var discount;
								var tax;
								
								if(data == 0 )
								{
									discount = +row.find('#discount_value').val();
								}
								else
								{
									discount = data;
								}
								
								if(data1 == 0 )
								{
									tax = +row.find('#tax_value').val();
								}
								else
								{
									tax = data1;
								}
								
								var sales_total = +row.find('input[name^="linetotal"]').val();
								var total_discount = sales_total*discount/100;
								var taxable_value = sales_total - total_discount;
								row.find('#taxable_value').text(taxable_value.toFixed(2));
								var total_tax = taxable_value*tax/100;
								
								//row.find('#product_total').val((taxable_value + total_tax).toFixed(2));
								row.find('#product_total').val(sales_total.toFixed(2));

								row.find('#hidden_discount').val(total_discount);
								row.find('#hidden_tax').val(total_tax);
								*/
							/*
								var key = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();

								/*var product_id = +row.find('input[name^="product_id"]').val();
								var project_id = +row.find('input[name^="input_project_id"]').val();
								
								var price = +row.find('input[name^="price"]').val();
								var qty = +row.find('input[name^="qty"]').val();

								
								product_data[key].discount = total_discount;
								product_data[key].discount_value = +row.find('#discount_value').val();
								product_data[key].discount_id = +row.find('#item_discount').val();
								product_data[key].tax = total_tax;
								product_data[key].tax_value = +row.find('#tax_value').val();
								product_data[key].tax_id = +row.find('#item_tax').val();
								
								product_data[key].cost = price;
								product_data[key].quantity = qty;
								product_data[key].total = (price * qty).toFixed(2);
								*/
							/*		
								product_data[key].element_id = $('#category_id'+counter).val(); //+row.find('#category_id').val();
								product_data[key].element_percentage = $('#element_percentage'+counter).val(); //+row.find('#element_percentage').val();
								product_data[key].per_month_inr = $('#per_month_inr'+counter).val();
								product_data[key].per_annum_inr = $('#per_annum_inr'+counter).val();
								var table_data = JSON.stringify(product_data);

								$('#table_data').val(table_data);
							}
							*/

							function calculateGrandTotal() 
							{
								var grandTotalPerMonth = 0;
								$("table.product_table").find('input[name^="per_month_inr"]').each(function () 
								{
									grandTotalPerMonth += +$(this).val();
								});

								var grandTotal = 0;
								$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
								{
									grandTotal += +$(this).val();
								});
		
								$('#total_value').val(grandTotal.toFixed(2));
								$('#grandTotal').text(grandTotal.toFixed(2));
								$('#grand_total').val(grandTotal.toFixed(2));	

								$('#grandTotalPerMonth').text(grandTotalPerMonth.toFixed(2));
							}

							function validateGrandTotal() 
							{
								var empCTC = parseFloat($("#ctc").val()).toFixed(2);
								var grandTotal = parseFloat($("#grand_total").val()).toFixed(2);
								
								/* if( empCTC < grandTotal )
								{
									$(".register-but").attr("disabled", true);
									$(".exceed_amount_disabled").show();
								}
								else
								{
									$(".register-but").removeAttr("disabled", false);
									$(".exceed_amount_disabled").hide();
								} */

								if(empCTC ==  grandTotal)
								{
									$(".register-but").removeAttr("disabled", false);
									$(".exceed_amount_disabled").hide();
								}else{
									$(".register-but").attr("disabled", true);
									$(".exceed_amount_disabled").show();
								}	
							}
						});	
					</script>
					<?php
				}
				else if(isset($type) && $type == "edit")
				{
					?>
					<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
					<legend class="text-uppercase font-size-sm font-weight-bold">
						<?php echo $type; ?> Employee Annexure:
					</legend>

					<form action="" class="form-validate-jquery salary_structure" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<?php 
								$empQry = "select first_name,last_name,user_id,random_user_id from users 
								where 
									user_status=1 and
										register_type=1 
											order by first_name asc";
								$getEmployee = $this->db->query($empQry)->result_array();
							?>
							<?php 
								$empCode = isset($edit_data[0]['user_id']) ? $edit_data[0]['user_id'] :"";
								$get_emp_code = $this->db->query("select first_name,email,random_user_id,phone_number from users where user_id='".$empCode."' ")->result_array();
								$employee_code =isset($get_emp_code[0]['random_user_id']) ? $get_emp_code[0]['random_user_id'] :"";
								$employee_name =isset($get_emp_code[0]['first_name']) ? $get_emp_code[0]['first_name'] :"";
								
								$readonly = '';
								if($type == 'edit')
								{
									$readonly = 'readonly';
								}
								
							?>
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
								<input type="text" name="emp_code" id="emp_code" readonly autocomplete="off"  <?php echo $this->validation;?> value="<?php echo $employee_code;?>" required class="form-control">
								<div id="patientList"></div>
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $empCode;?>" class="form-control">
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Name</label>
								<input type="text" name="emp_name" <?php echo $readonly;?> id="emp_name" autocomplete="off" class="form-control" value="<?php echo $employee_name; ?>" placeholder="">
							</div>
							<?php /*  <div class="form-group col-md-3">
								<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
								<select name="user_id" id="user_id" class="form-control searchDropdown" required>
									<option value="">- Select Employee -</option>
									<?php 
										foreach($getEmployee as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['user_id']) && $edit_data[0]['user_id'] == $row['user_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['user_id'];?>" <?php echo $selected;?>><?php echo $row['random_user_id'];?> - <?php echo ucfirst($row['first_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div> */ ?>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">CTC <span class="text-danger">*</span></label>
								<input type="text" name="ctc" id="ctc" required <?php #echo $this->validation; ?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['ctc']) ? $edit_data[0]['ctc'] :"";?>" placeholder="">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Effective From Date <span class="text-danger">*</span></label>
								<input type="text" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) : date('d-M-Y');?>">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Effective To Date <span class="text-danger">*</span></label>
								<?php 
									if (isset($edit_data[0]['string_to_date']) && $edit_data[0]['string_to_date'] !="0" && $edit_data[0]['string_to_date'] !="") 
									{
										$string_to_date = date('d-M-Y',$edit_data[0]['string_to_date']);
									}else{
										$string_to_date ="";
									}
								?>
								<input type="text" name="string_to_date" id="string_to_date" class="form-control default_date" required value="<?php echo $string_to_date;?>">
								<p id="valid_string_to_date"></p>
							</div>

							<style>
								p#valid_string_to_date{color:red !important;}
							</style>

							<script>
								$( function() 
								{
									var dateFormat = "dd-M-yy",
									from = $("#string_from_date").datepicker({
										/* changeMonth: true,
										changeYear: true,
										yearRange: "1950:<?php #echo date('Y'); ?>",
										//dateFormat: "dd-mm-yy"	
										dateFormat: "dd-M-yy" */

										changeMonth: true,
										changeYear: true,
										yearRange: "1950:<?php echo date('Y') + 10; ?>",
										dateFormat: "dd-M-yy"
									}).on("change", function() 
									{
										var toMinDate = getDate(this);
										toMinDate.setDate(toMinDate.getDate() + 1);
										to.datepicker("option", "minDate", toMinDate);
									}),
									
									to = $("#string_to_date").datepicker({
										/* defaultDate: "+1w",
										changeMonth: true,
										dateFormat: dateFormat,
										minDate: new Date(), */

										defaultDate: "+1w",
										changeMonth: true,
										changeYear: true,
										yearRange: "1950:<?php echo date('Y') + 10; ?>",
										dateFormat: "dd-M-yy",
										minDate: new Date(),
										onSelect: function(dateText, inst) 
										{
											getDate1();
										}
									})
									.on("change", function() 
									{
										var fromMaxDate = getDate(this);
										
										fromMaxDate.setDate(fromMaxDate.getDate() - 1);
										from.datepicker("option", "maxDate", fromMaxDate);
									});

									function getDate(element) 
									{
										var date;
										try 
										{
											date = $.datepicker.parseDate(dateFormat, element.value);
										} 
										catch (error) 
										{
											date = null;
										}
										return date;
									}

									function getDate1() 
									{
									  var string_to_date=$("#string_to_date").val();
									  var string_from_date=$("#string_from_date").val();

										var givenDate = Date.parse(string_to_date);
										if (!givenDate.isNaN) 
										{
											// set hours, minutes, seconds, milisecconds to zero for a comparison
											// on date only
											givenDate = new Date(givenDate).setHours(0,0,0,0);
											var todaysDate = Date.parse(string_from_date);
											
											if (givenDate > todaysDate) 
											{
												/* result.innerHTML = 'Great! You\'re starting on a future date or today!';
												result.style.color = 'green'; */
												$("#valid_string_to_date").html("");
												$('.register-but').removeAttr('disabled');
											} 
											else 
											{
												/* result.innerHTML = "Please choose a future date.";
												result.style.color = 'red'; */
												$("#valid_string_to_date").html("Please select a future date.");
												
												$('.register-but').attr('disabled','disabled');
											}
										}
									} 
								});
							</script>

							<div class="form-group col-md-3">
								<label class="col-form-label">Living City <span class="text-danger">*</span></label>
								<select name="living_city" onchange="selectLivingCity(this.value);" autocomplete="off" required class="form-control searchDropdown">
									<option value>- Select -</option>
									<?php 
										foreach($this->living_city as $key=>$value)
										{
											$selected="";

											if($edit_data[0]['living_city'] && $edit_data[0]['living_city'] == $key)
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
								<label class="col-form-label">Place <span class="text-danger">*</span></label>
								<input type="text" name="place" autocomplete="off" required class="form-control" value="<?php echo isset($edit_data[0]['place']) ? $edit_data[0]['place'] :"";?>" placeholder="">
							</div>
						</div>

						<a href="javascript:void(0);" id="addElement" class="btn btn-primary">
							Add
						</a>

						<div class="row mt-2">
							<div class="col-sm-12">
								<div class="form-group">
									<div style="overflow-y: auto;">
									
										<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
										
										<table class="table category --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
											<thead>
												<tr>
													<th colspan="13">
														Employee Elements
													</th>
												</tr>
												<tr>
													<th style="width:20px;"></th>
													<th class="text-center">Element Name</th>
													<th class="text-center">Element Percentage</th>
													<th class="text-center">Per month <?php echo CURRENCY_CODE;?></th>
													<th class="text-center">Per annum <?php echo CURRENCY_CODE;?></th>
												</tr>
											</thead>
											<tbody id="product_table_body">
												<?php 
													if ( count($items) > 0 ) 
													{
														$i = 0;
														$counter = 1;
														$product_data = [];
														$per_month_inr = 0;
														$per_annum_inr = 0;
														foreach ($items as $row)
														{
															?>
															<tr class="dataRowVal<?php echo $row["element_id"]; ?>">
																<td>
																	<?php 
																		if($row["element_id"] == 1 || $row["element_id"] == 2)
																		{

																		}
																		else
																		{
																			?>
																			<a href="javascript:void(0);" onclick="deleteSalary(<?php echo $row['header_id']; ?>,<?php echo $row['line_id']; ?>);"> <i class="fa fa-trash"></i> </a>
																			<?php
																		} 
																	?>

																	<input type="hidden" name="counter" class="counter_<?php echo $counter;?>" value="<?php echo $counter;?>">
																	<input type="hidden" name="id" class="id_<?php echo $counter;?>" value="<?php echo $i;?>">
																	<input type="hidden" name="category_id"  class='ele_category<?php echo $counter;?> element_category<?php echo $row["element_id"];?>' id="category_id<?php echo $counter;?>" value="<?php echo $row["element_id"];?>">
																	<input type="hidden" name="line_id" id="line_id[]" value="<?php echo $row["line_id"];?>">
																</td>
																<td>
																	<?php
																		$getElementsQry = "select category_id,category_name from hr_payslip_categories where category_status=1";
																		$getElements = $this->db->query($getElementsQry)->result_array(); 
																	?>
																	<div class="form-group">
																		<select class="form-control element_id_class<?php echo $counter;?>" style="" id="element_id<?php echo $counter;?>" name="element_id[]">
																			<option value="">- Select -</option>
																			<?php
																				foreach ($getElements as $element) 
																				{
																					$selected="";
																					if ($row["element_id"] == $element["category_id"]) 
																					{
																						$selected="selected='selected'";
																					}?>
																					<option value="<?php echo $element["category_id"]; ?>" <?php echo $selected;?>><?php echo ucfirst($element["category_name"]); ?></option>
																					<?php
																				} 
																			?>
																		</select>
																	</div>
																</td>
																
																<td class="text-center">
																	<input type="hidden" class="form-control" name="element_percentage" id="element_percentage<?php echo $counter;?>" value="0">
																	<?php
																		$getElementPercentage =  $this->db->query("select 
																			org_elements_percentage.* from org_elements_percentage
																		where 
																			org_elements_percentage.element_id = '".$row["element_id"]."' and
																			org_elements_percentage.percentage_status= 1
																				order by org_elements_percentage.element_percentage asc
																		")->result_array();
																	?>
																	<select class="form-group form-control element_percentage_id<?php echo $counter;?> element_percentage_class<?php echo $row['element_id'];?> searchDropdown--" id="element_percentage_id<?php echo $counter;?>" name="element_percentage_id[]">
																		<option value="">- Select -</option>
																		<?php 
																			foreach($getElementPercentage as $percentage)
																			{
																				$selected="";
																				if($percentage['element_percentage'] == $row["element_percentage"])
																				{
																					$selected="selected='selected'";
																				}
																				?>
																				<option value="<?php echo $percentage['element_percentage'];?>" <?php echo $selected;?>><?php echo  ucfirst($percentage['element_percentage']); ?></option>
																				<?php
																			}
																		?>
																	</select>
																</td>

																
																<?php /*
																<td class="tab-medium-width text-center">
																	<input type="text" class="form-control text-right" readonly value="<?php echo $row["element_percentage"]; ?>" name="element_percentage<?php echo $counter;?>" id="element_percentage<?php echo $counter;?>">
																</td> */ ?>

																<td class="tab-medium-width text-center">
																	<input type="number" readonly="" class="form-control text-right element_id_per_month<?php echo $row['element_id'];?>" value="<?php echo number_format($row['per_month_inr'],DECIMAL_VALUE,'.','');?>" name="per_month_inr[]" id="per_month_inr<?php echo $counter;?>">
																</td>

																<td class="tab-medium-width text-center">
																	<input type="number" readonly="" class="form-control text-right element_class<?php echo $counter;?> element_id<?php echo $row['element_id'];?> element_id_per_year<?php echo $row['element_id'];?>" value="<?php echo number_format($row['per_annum_inr'],DECIMAL_VALUE,'.','');?>" name="per_annum_inr[]" id="per_annum_inr<?php echo $counter;?>">
																</td>
															</tr>
															<?php
															$per_month_inr += $row['per_month_inr'];
															$per_annum_inr += $row['per_annum_inr'];
															$product_data[$i] = $row['element_id'];
															$i++;
															$counter++;
														}
														$product = json_encode($product_data); 
													} 
												?>
												<script>
													function deleteSalary(heder_id,line_id)
													{
														if(heder_id !='' && line_id !="")
														{
															$.ajax({
																type: "POST",
																url:"<?php echo base_url().'employee/ajaxDeleteSalaryAnnexure';?>",
																data: { id : heder_id,lineID : line_id }
															}).done(function( msg ) 
															{   
																location.reload();
															});
														}
														else 
														{ 
															
														}
													}
												</script>
											</tbody>
										</table>
									</div>

									<input type="hidden" name="total_value" id="total_value" value='<?php echo isset($per_annum_inr) ? $per_annum_inr :"";?>'>
									<input type="hidden" name="grand_total" id="grand_total" value='<?php echo isset($per_annum_inr) ? $per_annum_inr :"";?>'>
									<input type="hidden" name="table_data" id="table_data" value='<?php echo $product; ?>'>
									<input type="hidden" name="table_data1" id="table_data1" value=''>
									
									<table class="table table-bordered table-condensed table-hover">
										<tr>
											<td colspan="1" class="text-left exceed_amount_disabled" style="display:none;">
												<a href="javascript:void(0)" onclick="CalculateAnnexure();" class="text-left btn btn-primary">Validate Annexure</a>
											</td>
					
											<td colspan="1" class="text-right">
												<b>Total ( <?php echo CURRENCY_SYMBOL;?> ) </b>
											</td>

											<td class="text-right">
												<span id="grandTotalPerMonth">
													<?php 
														$emp_per_month_ctc = isset($per_month_inr) ? $per_month_inr :"0.00";
														echo number_format($emp_per_month_ctc,DECIMAL_VALUE,'.','');
													?>
												</span>
											</td>

											<td class="text-right">
												<span id="grandTotal">
													<?php 
														$emp_per_annum_ctc = isset($per_annum_inr) ? $per_annum_inr :"0.00";
														echo number_format($emp_per_annum_ctc,DECIMAL_VALUE,'.','');
													?>
												</span>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>

						<span class="note-content exceed_amount_disabled" style="display:none;">
							<b>Note : </b> CTC detail breakup is not matching with CTC.
						</span>

						<span class="note-content pf_contribution_amount_disabled" style="display:none;float:left;width:100%;">
							<b>Note : </b> Maximum Employer PF Per Annum <?php echo PF_CONTRIBUTION; ?>.
						</span>
						
						<div class="d-flexad text-right">
							<a href="<?php echo base_url(); ?>employee/ManageSalary" class="btn btn-default">Cancel</a>
							<button type="submit" class="btn btn-primary ml-2 register-but">Update</button>
						</div>
					</form>

					<script> 
						function CalculateAnnexure()
						{
							var allowence = $("table.product_table").find(".element_category18").val();
							var element_id = $("table.product_table").find(".element_id18").val(); 
							
							if(allowence == 18) //Allowence
							{
								var id = $(".element_category18").attr('id');
								
								const myArray = id.split("id");
								let counter = myArray[1];

								var empCTC = $("#ctc").val();
								var grandTotal = 0;
								
								$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
								{	
									grandTotal += +$(this).val();
								});

								//var grandTotal = $("#grand_total").val();

								var grand_total = element_id;
								
								var flexibleBenifit = empCTC - (grandTotal - grand_total);
								
								$("table.product_table").find("#per_annum_inr"+counter).val(flexibleBenifit.toFixed(2));
								var flexiblePerMonth = flexibleBenifit / 12;
								$("table.product_table").find("#per_month_inr"+counter).val(flexiblePerMonth.toFixed(2));
								
								/*var grandTotal1 = $("#grand_total").val();
								if( empCTC < grandTotal1 )
								{
									$(".register-but").attr("disabled", true);
									$(".exceed_amount_disabled").show();
								}
								else
								{
									$(".register-but").removeAttr("disabled", false);
									$(".exceed_amount_disabled").hide();
								}*/

								$(".register-but").removeAttr("disabled", false);
								$(".exceed_amount_disabled").hide();
							}

							calculateGrandTotalLivingCity();	
						}

						function selectLivingCity(val)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url  :"<?php echo base_url().'employee/ajaxSelectLivingCityPercentage';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									var splitData = msg.split('@');

									$("table.product_table").find("#element_percentage_id2").html(splitData[0]);

									$("#element_percentage2").val(splitData[1]);
									
									var ctc= $("#ctc").val();
									var hrElement = $("table.product_table").find("#category_id2").val();
									var basicElement = $("table.product_table").find("#category_id1").val();
									var basicElementPer = $("table.product_table").find("#element_percentage_id1").val();
									var hrElementPer = splitData[1];
								
									hrElementCalcLivingCity(2,2,hrElement,ctc,hrElementPer,basicElement,basicElementPer);
									calculateGrandTotalLivingCity();

									var empCTC = $("#ctc").val();
									if(empCTC > 0)
									{
										$(".exceed_amount_disabled").show();
									}
								});
							}
							else 
							{ 
								alert("No Percentage under this Living City!");
							}
						}

						function calculateGrandTotalLivingCity() 
						{
							var grandTotalPerMonth = 0;
							$("table.product_table").find('input[name^="per_month_inr"]').each(function () 
							{
								grandTotalPerMonth += +$(this).val();
							});

							var grandTotal = 0;
							$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
							{
								grandTotal += +$(this).val();
							});

							$('#total_value').val(grandTotal.toFixed(2));
							$('#grandTotal').text(grandTotal.toFixed(2));
							$('#grand_total').val(grandTotal.toFixed(2));
							
							$('#grandTotalPerMonth').val(grandTotalPerMonth.toFixed(2));	
						}

						function hrElementCalcLivingCity(counter2,key2,hrElement,ctc,hrElementPer,basicElement,basicElementPer)
						{
							var employeeCTC = ctc;
							var basicPerAnnum =  basicElementPer / 100 * employeeCTC;
							var hraPerAnnum = hrElementPer / 100 * basicPerAnnum;

							$("table.product_table").find("#per_annum_inr"+counter2).val(hraPerAnnum.toFixed(2));
							var hraPerMonth = hraPerAnnum / 12;
							$("table.product_table").find("#per_month_inr"+counter2).val(hraPerMonth.toFixed(2));
						}

						$(document).ready(function()
						{
							var i = <?php echo $i++; ?>;
							var product_data = new Array();
							var counter = <?php echo count($items);?>  + 1;
											
							$('#addElement').click(function()
							{
								var ctc = $("#ctc").val();
								if(ctc =="") 
								{
									$('#ctc').focus();
									$('#err_product').text('Please enter employee Annual CTC!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								}
								else 
								{
									$(".popup-overlay").hide();
									var id = 1;
									$('#err_product').text('');
									var flag = 0;
									
									if(id != "")
									{
										$.ajax({
											url        : "<?php echo base_url('employee/getElements') ?>/"+id,
											type       : "GET",
											data       : {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
											datatype   : "JSON",
											success    : function(d)
											{
												data = JSON.parse(d);

												/*
												$("table.product_table").find('input[name^="category_id"]').each(function () 
												{
													/*if(data[0].product_id  == +$(this).val())
													{
														flag = 1;
													}*/
												/*
													var row = $(this).closest("tr");
													var category_id = +row.find('input[name^="category_id"]').val();
																													
													if(category_id == 0)
													{
														flag = 1;
													}
												});
												*/
												
												if(flag == 0)
												{
													var id = "0";
													var element_percentage = "0";
													var per_month_inr = "0.00";
													var per_annum_inr = "0.00";

													var product = { 
														"element_id"           : id,
														"element_percentage"   : element_percentage,
														"per_month_inr"        : per_month_inr,
														"per_annum_inr"        : per_annum_inr,
													}; 
													
													product_data[i] = product;
													length = product_data.length - 1 ;

													//select_items
													var select_element = "";
													select_element += '<div class="form-group">';
													select_element += '<select class="form-control element_id_class'+counter+'" style="" id="element_id'+counter+'" name="element_id[]">';
													select_element += '<option value="">- Select -</option>';
													for(a=0;a<data['category'].length;a++)
													{
														select_element += '<option value="' + data['category'][a].category_id + '">' + data['category'][a].category_name+ '</option>';
													}
													select_element += '</select></div>';

													//select_items
													var select_element_percentage = "";
													select_element_percentage += '<div class="form-group">';
													select_element_percentage += '<select class="form-control element_percentage_id'+counter+'" style="" id="element_percentage_id'+counter+'" name="element_percentage_id[]">';
													select_element_percentage += '<option value="">- Select -</option>';
													
													select_element_percentage += '</select></div>';

													var newRow = $("<tr class='dataRowVal"+id+" table_rows'>");
													var cols = "";
													//cols += "<td><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='category_id' id='category_id"+ counter +"' value="+id+"></td>";
													cols += "<td><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='category_id' class='ele_category"+ counter +"' id='category_id"+ counter +"' value="+id+"></td>";
													cols += '<td>'+select_element+'</td>';
													cols += '<td>'+select_element_percentage+'</td>';

													//Element Percentage
													/*cols += "<td class='tab-medium-width text-center'>"
														+"<input type='text' class='form-control text-right' value='"+element_percentage+"' name='element_percentage"+ counter +"' id='element_percentage"+ counter +"' >"
														+"</td>";
													*/
													
													//Per Month INR
													cols += "<td class='tab-medium-width text-center'>"
														+"<input type='number' readonly class='form-control text-right' value='"+per_annum_inr+"' name='per_month_inr[]' id='per_month_inr"+ counter +"' >"
														+"</td>";

													//Per Annum INR
													/* cols += "<td class='tab-medium-width text-center'>"
														+"<input type='number' readonly class='form-control text-right' value='"+per_annum_inr+"' name='per_annum_inr[]' id='per_annum_inr"+ counter +"' >"
														+"</td>"; */

													//Per Annum INR
													cols += "<td class='tab-medium-width text-center'>"
														+"<input type='number' readonly class='form-control text-right element_class"+ counter +"' value='"+per_annum_inr+"' name='per_annum_inr[]' id='per_annum_inr"+ counter +"' >"
														+"</td>";

													cols += "</tr>";
													newRow.html(cols);
													$("table.product_table").append(newRow);
													var table_data = JSON.stringify(product_data);
													$('#table_data').val(table_data);
													i++;
													counter++;
													calculateGrandTotal();
												}
												else
												{
													//var id = data[0].product_id;
													//var quantity = parseFloat( $(".dataRowVal"+id).find('input[name^="qty"]').val() );
													//$(".dataRowVal"+id).find('input[name^="qty"]').val(quantity + 1)
													//calculateRow( $(".dataRowVal"+id) );
													//calculateDiscountTax( $(".dataRowVal"+id) );
													//calculateGrandTotal(); 
													$('#err_product').text('Please fill the all required fields').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
												}
													
											},
											error: function(xhr, status, error) 
											{
												$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										});
										
									}
								}
							});

							$("table.product_table").on("click", "a.deleteRow", function (event) 
							{
								deleteRow($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							});

							$("table.product_table").on("click", "a.deleteRow1", function (event) 
							{
								deleteRow1($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							});

							function deleteRow(row)
							{
								var id = +row.find('input[name^="id"]').val();
								//var array_id = product_data[id].product_id;
								product_data.splice(id, 1);
								var table_data = JSON.stringify(product_data);
								$('#table_data1').val(table_data);
							}

							function deleteRow1(row)
							{
								var id = +row.find('input[name^="id"]').val();
								product_data[id] = 'delete';
								var table_data = JSON.stringify(product_data);
								$('#table_data1').val(table_data);
							}

							$("form.salary_structure").on("input keyup change", 'input[name^="ctc"]', function (event) 
							{
								var row = $(this).closest("tr");
								var ctc= $("#ctc").val();


								/* var key = $("table.product_table").find('input[name^="id"]').val();
								var counter = $("table.product_table").find('input[name^="counter"]').val();

								alert(counter);
								 */
								//Basic 
								var counter1 = $("table.product_table").find(".counter_1").val();
								var key1 = $("table.product_table").find(".id_1").val();
								var basicElement = $("table.product_table").find("#category_id"+counter1).val();
								var basicElementPer = $("table.product_table").find("#element_percentage_id"+counter1).val();
								
								//HR
								var counter2 = $("table.product_table").find(".counter_2").val();
								var key2 = $("table.product_table").find(".id_2").val();
								var hrElement = $("table.product_table").find("#category_id"+counter2).val();
								var hrElementPer = $("table.product_table").find("#element_percentage_id"+counter2).val();
								
								
								basicElementCalc(counter1,key1,basicElement,ctc,basicElementPer);
								hrElementCalc(counter2,key2,hrElement,ctc,hrElementPer,basicElement,basicElementPer);
								

								var variable_element_ID = $("table.product_table").find(".element_category4").val();
								var employerPf_element_ID = $("table.product_table").find(".element_category5").val();
								var allowence_element_ID = $("table.product_table").find(".element_category18").val();
								var gratuity_element_ID = $("table.product_table").find(".element_category17").val();
								
								//variable
								if(variable_element_ID == 4) //Variable
								{
									var variableElementPer = $("table.product_table").find(".element_percentage_class4").val();
									variableElementCalc(ctc,variableElementPer,variable_element_ID);
								}

								//Pf
								if(employerPf_element_ID == 5) //Pf
								{
									var employerPfElementPer = $("table.product_table").find(".element_percentage_class5").val();
									employerPfElementCalc(employerPfElementPer,employerPf_element_ID);
								}

								/* if(employerPf_element_ID == 5) //Pf
								{
									var employerPfElementPer = $("table.product_table").find(".element_percentage_class5").val();
									employerPfElementCalc(employerPfElementPer,employerPf_element_ID);
								} */

								if(gratuity_element_ID == 17) //gratuity
								{
									var gratuityElementPer = $("table.product_table").find(".element_percentage_class17").val();
									gratuityElementCalc(gratuityElementPer,gratuity_element_ID);
								}

								calculateDefaultPercentage($(this).closest("tr"));
								calculateGrandTotal();
								validateGrandTotal();	

								//allowence
								if(allowence_element_ID == 18) //allowence
								{
									CalculateAnnexure();
									//var employerPfElementPer = $("table.product_table").find(".element_percentage_class18").val();
									//allowenceElementCalc(employerPfElementPer,allowence_element_ID);
								}
							});

							function basicElementCalc(counter1,key1,basicElement,ctc,basicElementPer)
							{
								var employeeCTC = ctc;
								var basicPerAnnum = basicElementPer / 100 * employeeCTC;
								$("table.product_table").find("#per_annum_inr"+counter1).val(basicPerAnnum.toFixed(2));

								var basicPerMonth = basicPerAnnum / 12;
								$("table.product_table").find("#per_month_inr"+counter1).val(basicPerMonth.toFixed(2));
							}

							function hrElementCalc(counter2,key2,hrElement,ctc,hrElementPer,basicElement,basicElementPer)
							{
								var employeeCTC = ctc;
								var basicPerAnnum =  basicElementPer / 100 * employeeCTC;
								var hraPerAnnum = hrElementPer / 100 * basicPerAnnum;

								$("table.product_table").find("#per_annum_inr"+counter2).val(hraPerAnnum.toFixed(2));
								var hraPerMonth = hraPerAnnum / 12;
								$("table.product_table").find("#per_month_inr"+counter2).val(hraPerMonth.toFixed(2));
							}

							function variableElementCalc(employeeCTC,element_percentage,element_id)
							{
								var variablePerAnnum =  element_percentage / 100 * employeeCTC;
								
								$("table.product_table").find(".element_id_per_year4").val(variablePerAnnum.toFixed(2));
								var variablePerMonth = variablePerAnnum / 12;
								$("table.product_table").find(".element_id_per_month4").val(variablePerMonth.toFixed(2));
							}

							function employerPfElementCalc(element_percentage,element_id)
							{
								var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
								var pfPerAnnum =  element_percentage / 100 * empBasicCTC;

								var pf_contribution = '<?php echo PF_CONTRIBUTION;?>';
								
								//18000 < 21000

								if( pfPerAnnum < pf_contribution )
								{
									$(".pf_contribution_amount_disabled").hide();
									$("table.product_table").find(".element_id_per_year5").val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									$("table.product_table").find(".element_id_per_month5").val(pfPerMonth.toFixed(2));
								}
								else
								{
									$(".pf_contribution_amount_disabled").show();
									var subTotalFormatted = parseFloat(pf_contribution).toFixed(2);
									$("table.product_table").find(".element_id_per_year"+element_id).val(subTotalFormatted);
									var pfPerMonth = pf_contribution / 12;
									$("table.product_table").find(".element_id_per_month"+element_id).val(pfPerMonth.toFixed(2));
								}
							}

							function gratuityElementCalc(element_percentage,element_id)
							{
								var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
								var retiralsPerAnnum =  element_percentage / 100* empBasicCTC;

								//var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

								$("table.product_table").find(".element_id_per_year17").val(retiralsPerAnnum.toFixed(2));
								var pfRetiralsMonth = retiralsPerAnnum / 12;
								$("table.product_table").find(".element_id_per_month17").val(pfRetiralsMonth.toFixed(2));
							}

							function allowenceElementCalc(element_percentage,element_id)
							{
								var grandTotal = $("#grand_total").val();
								var employeeCTC = $('#ctc').val();

								var flexibleBenifit =  employeeCTC - grandTotal;

								$("table.product_table").find(".element_id_per_year18").val(flexibleBenifit.toFixed(2));
								var flexiblePerMonth = flexibleBenifit / 12;
								$("table.product_table").find(".element_id_per_month18").val(flexiblePerMonth.toFixed(2));
 							}

							//Select Element 
							$("table.product_table").on("change", 'select[name^="element_id"]', function (event) 
							{
								var row = $(this).closest("tr");
								var id = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								var element_id = $('#element_id'+counter).val();
								row.find('#category_id'+counter).val(element_id);

								$(".element_class"+counter).addClass("element_id"+element_id);
								$(".ele_category"+counter).addClass("element_category"+element_id);

								if(element_id !="") 
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'employee/ajaxSelectEmployeePecenatge';?>",
										data: { id: element_id }
									}).done(function( msg ) 
									{   
										$("#element_percentage_id"+counter).html(msg);
									});
								}
								else 
								{ 
									alert("No percentage under this element!");
								}

								calculateDefaultPercentage($(this).closest("tr"));
								//calculateDiscountTax($(this).closest("tr"));
								calculateGrandTotal();
								validateGrandTotal();
								//calculateGrandTotal();
								//calculateDefaultPercenage($(this).closest("tr"));
								//var category_description = $('#category_description'+counter).val();
								
								/* var grand_total = parseFloat(price) * qty; */
								/* var grand_total = $("#grand_total").val();
								if( parseFloat(grand_total) > parseFloat(currentBalance))
								{
									var quantity = row.find('input[name^="qty"]').val('1');
									row.find('input[name^="price"]').val(price);
									row.find('input[name^="price"]').val(product_price.toFixed(2));
									quantity = row.find('input[name^="qty"]').val('1');
									calculateRow($(this).closest("tr"));
									calculateDiscountTax($(this).closest("tr"));
									calculateGrandTotal();
									$("#err_product").html("Sorry, you don't have sufficient budget value.");
								}
								else
								{ 
									calculateRow($(this).closest("tr"));
									calculateDiscountTax($(this).closest("tr"),event.target.value);
									calculateGrandTotal();
									dueDate(row,request_date);
									dueDate(row,event.target.value);
								}*/
							});

							//Element Percentage
							$("table.product_table").on("change", 'select[name^="element_percentage_id"]', function (event) 
							{
								var row = $(this).closest("tr");
								
								calculateDefaultPercentage(row);
								//calculateDiscountTax($(this).closest("tr"));
								calculateGrandTotal();
								validateGrandTotal();
								//calculateDefaultPercentage($(this).closest("tr"));
							});

							/*function calculateRow(row) 
							{
								var employeeCTC = $('#ctc').val();

								var id = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								var element_id = $('#element_id'+counter).val();
								var element_percentage = $('#element_percentage_id'+counter).val();
							} */

							function calculateDefaultPercentage(row) 
							{
								var key = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								
								var element_id = +row.find('#element_id'+counter).val();
								var amount = +row.find('#amount'+counter).val();

								
								/* var key = $("table.product_table").find('input[name^="id"]').val();
								var counter = $("table.product_table").find('input[name^="counter"]').val();
								
								var element_id = $("table.product_table").find('select[name^="element_id[]"]').val();
								var amount = $("table.product_table").find('input[id^="amount'+counter+'"]').val();

								alert(counter); */
								/* if(element_id == 1)
								{
									alert("af");
								}
								if(element_id == 2)
								{
									alert("asdsdf");
								} */

								var element_percentage = $('#element_percentage_id'+counter).val();
								var employeeCTC = $('#ctc').val();
								//alert(element_id);
								/*
								if(element_id == 1) //Basic
								{
									//var element_percentage = "0.5";
									//row.find('#element_percentage'+counter).val(element_percentage);
									//var basicPerAnnum = employeeCTC / 100 * element_percentage;

									var basicPerAnnum = element_percentage / 100 * employeeCTC;
									row.find('#per_annum_inr'+counter).val(basicPerAnnum.toFixed(2));

									var basicPerMonth = basicPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(basicPerMonth.toFixed(2));
								}
								else if(element_id == 2) //HRA
								{
									var PerannumINR = 0;
									var PerannumINRnotBasic =0;
									$("table.product_table").find('input[name^="category_id"]').each(function () 
									{
										var elementID = $(this).val();
										//var elementID = row.find('input[name^="category_id"]').val();
										
										$("table.product_table").find('input[name^="per_annum_inr"]').each(function () 
										{
											/*if( elementID == 1 )
											{
												PerannumINR += $(this).val();
											}*/
								/*
											if( elementID != 1 )
											{
												PerannumINRnotBasic += $(this).val();
											}
										});
									});

									//var element_percentage = "0.4";
									//row.find('#element_percentage'+counter).val(element_percentage);
									var basicPerAnnum =  PerannumINRnotBasic;
									var hraPerAnnum = element_percentage / 100 * basicPerAnnum;

									row.find('#per_annum_inr'+counter).val(hraPerAnnum.toFixed(2));
									var hraPerMonth = hraPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(hraPerMonth.toFixed(2));
								}else */
			

								if(element_id == 5) //PF
								{
									/* var basicPerAnnum =  element_percentage / 100 * employeeCTC;
									var pfPerAnnum = Math.round(basicPerAnnum * element_percentage);
									var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

									row.find('#per_annum_inr'+counter).val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(pfPerMonth.toFixed(2)); 
									*/
									
									var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
									var pfPerAnnum =  element_percentage / 100 * empBasicCTC;

									var pf_contribution = '<?php echo PF_CONTRIBUTION;?>';
									
									//18000 < 21000

									if( pfPerAnnum < pf_contribution )
									{
										$(".pf_contribution_amount_disabled").hide();
										$("table.product_table").find("#per_annum_inr"+counter).val(pfPerAnnum.toFixed(2));
										var pfPerMonth = pfPerAnnum / 12;
										$("table.product_table").find("#per_month_inr"+counter).val(pfPerMonth.toFixed(2));
									}
									else
									{
										$(".pf_contribution_amount_disabled").show();
										var subTotalFormatted = parseFloat(pf_contribution).toFixed(2);
										$("table.product_table").find("#per_annum_inr"+counter).val(subTotalFormatted);
										var pfPerMonth = pf_contribution / 12;
										$("table.product_table").find("#per_month_inr"+counter).val(pfPerMonth.toFixed(2));
									}

									/* 	$("table.product_table").find("#per_annum_inr"+counter).val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(pfPerMonth.toFixed(2));
									*/

									/* row.find('#per_annum_inr'+counter).val(pfPerAnnum.toFixed(2));
									var pfPerMonth = pfPerAnnum / 12;
									row.find('#per_month_inr'+counter).val(pfPerMonth.toFixed(2));
									*/
								}
								else if(element_id == 17) //Retirals || Gratuity 
								{
									//var element_percentage = "0.0481";
									//row.find('#element_percentage'+counter).val(element_percentage);

									//var basicPerAnnum = Math.round(employeeCTC * 0.5); //Basic Per Annum
									//var retiralsPerAnnum = Math.round(basicPerAnnum * element_percentage);
									//row.find('#per_annum_inr'+counter).val(retiralsPerAnnum.toFixed(2));
								
									var empBasicCTC = $("table.product_table").find("#per_annum_inr1").val();
									
									var retiralsPerAnnum =  element_percentage / 100* empBasicCTC;

									//var pfPerAnnum = element_percentage / 100 * basicPerAnnum;

									$("table.product_table").find("#per_annum_inr"+counter).val(retiralsPerAnnum.toFixed(2));
									var pfRetiralsMonth = retiralsPerAnnum / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(pfRetiralsMonth.toFixed(2));
								}
								else if(element_id == 4) //varible pay 
								{
									//var element_percentage = "0.15";
									/*row.find('#element_percentage'+counter).val(element_percentage);

									var basicPerAnnum = Math.round(employeeCTC * element_percentage); //Basic Per Annum
									var variablePerAnnum = Math.round(basicPerAnnum);
									row.find('#per_annum_inr'+counter).val(variablePerAnnum.toFixed(2));

									var variablePerMonth = Math.round(variablePerAnnum / 12);
									row.find('#per_month_inr'+counter).val(variablePerMonth.toFixed(2));*/

									
									var variablePerAnnum =  element_percentage / 100 * employeeCTC;

									$("table.product_table").find("#per_annum_inr"+counter).val(variablePerAnnum.toFixed(2));
									var variablePerMonth = variablePerAnnum / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(variablePerMonth.toFixed(2));
								}
								else if(element_id == 18) //Flexible Benefit Plan
								{
									/* var basicPerAnnum = Math.round(employeeCTC * 0.5);
									var pfPerAnnum = Math.round(basicPerAnnum * 0.12);
									var retiralsPerAnnum = Math.round(basicPerAnnum * 0.0481);
									var hraPerAnnum = Math.round(basicPerAnnum * 0.4);
									
									//varible pay
									var variablebasicPerAnnum = Math.round(employeeCTC * 0.15); //Basic Per Annum
									var variablePerAnnum = Math.round(variablebasicPerAnnum);
												
									var flexibleBenifit = employeeCTC - basicPerAnnum - pfPerAnnum - retiralsPerAnnum - variablePerAnnum - hraPerAnnum;
									
									var flexibleBenifitPerAnnum = Math.round(flexibleBenifit);
									row.find('#per_annum_inr'+counter).val(flexibleBenifitPerAnnum.toFixed(2));

									var flexibleBenifitPerAnnum = Math.round(flexibleBenifitPerAnnum / 12);
									row.find('#per_month_inr'+counter).val(flexibleBenifitPerAnnum.toFixed(2));*/ 
									var grandTotal = $("#grand_total").val();
									var flexibleBenifit =  employeeCTC - grandTotal;

									$("table.product_table").find("#per_annum_inr"+counter).val(flexibleBenifit.toFixed(2));
									var flexiblePerMonth = flexibleBenifit / 12;
									$("table.product_table").find("#per_month_inr"+counter).val(flexiblePerMonth.toFixed(2));
								}
							}
							
							/*
							function calculateDiscountTax(row,data = 0,data1 = 0)
							{
							
								/*var discount;
								var tax;
								
								if(data == 0 )
								{
									discount = +row.find('#discount_value').val();
								}
								else
								{
									discount = data;
								}
								
								if(data1 == 0 )
								{
									tax = +row.find('#tax_value').val();
								}
								else
								{
									tax = data1;
								}
								
								var sales_total = +row.find('input[name^="linetotal"]').val();
								var total_discount = sales_total*discount/100;
								var taxable_value = sales_total - total_discount;
								row.find('#taxable_value').text(taxable_value.toFixed(2));
								var total_tax = taxable_value*tax/100;
								
								//row.find('#product_total').val((taxable_value + total_tax).toFixed(2));
								row.find('#product_total').val(sales_total.toFixed(2));

								row.find('#hidden_discount').val(total_discount);
								row.find('#hidden_tax').val(total_tax);
								*/


								/*


								var key = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								
								/*var product_id = +row.find('input[name^="product_id"]').val();
								var project_id = +row.find('input[name^="input_project_id"]').val();
								
								var price = +row.find('input[name^="price"]').val();
								var qty = +row.find('input[name^="qty"]').val();

								
								product_data[key].discount = total_discount;
								product_data[key].discount_value = +row.find('#discount_value').val();
								product_data[key].discount_id = +row.find('#item_discount').val();
								product_data[key].tax = total_tax;
								product_data[key].tax_value = +row.find('#tax_value').val();
								product_data[key].tax_id = +row.find('#item_tax').val();
								
								product_data[key].cost = price;
								product_data[key].quantity = qty;
								product_data[key].total = (price * qty).toFixed(2);
								*/
							/*		
								product_data[key].element_id = $('#category_id'+counter).val(); //+row.find('#category_id').val();
								product_data[key].element_percentage = $('#element_percentage'+counter).val(); //+row.find('#element_percentage').val();
								product_data[key].per_month_inr = $('#per_month_inr'+counter).val();
								product_data[key].per_annum_inr = $('#per_annum_inr'+counter).val();
								var table_data = JSON.stringify(product_data);
								
								$('#table_data1').val(table_data);
							}
							*/

							function calculateGrandTotal() 
							{
								
								var grandTotalPerMonth = 0;
								$("table.product_table").find('input[name^="per_month_inr"]').each(function () 
								{
									grandTotalPerMonth += +$(this).val();
								});

								var grandTotal = 0;
								$("table.product_table").find('input[name^="per_annum_inr"]').each(function () {
									grandTotal += +$(this).val();
								});

								$('#total_value').val(grandTotal.toFixed(2));
								$('#grandTotal').text(grandTotal.toFixed(2));
								$('#grand_total').val(grandTotal.toFixed(2));
								
								$('#grandTotalPerMonth').text(grandTotalPerMonth.toFixed(2));
							}

							function validateGrandTotal() 
							{
								var empCTC = parseFloat($("#ctc").val()).toFixed(2);
								var grandTotal = parseFloat($("#grand_total").val()).toFixed(2);
								
								if(empCTC ==  grandTotal)
								{
									$(".register-but").removeAttr("disabled", false);
									$(".exceed_amount_disabled").hide();
								}else{
									$(".register-but").attr("disabled", true);
									$(".exceed_amount_disabled").show();
								}
								/* if( empCTC < grandTotal )
								{
									$(".register-but").attr("disabled", true);
									$(".exceed_amount_disabled").show();
								}
								else
								{
									$(".register-but").removeAttr("disabled", false);
									$(".exceed_amount_disabled").hide();
								} */
							}
						});
					</script>
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if((isset($annexure['create_edit_only']) && $annexure['create_edit_only'] == 1) || $this->user_id == 1)
								{
									?>	
									<a href="<?php echo base_url(); ?>employee/ManageSalary/add" class="btn btn-info btn-sm">
										Add Employee Annexure
									</a>
									<?php 
								}
							?>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-10">
								<div class="row mb-2">
									<div class="col-md-3">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Employee Code and Name.</p>
									</div>	
									<div class="col-md-3">	
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] :""; ?>" placeholder="Effective From Date">
									</div>

									<div class="col-md-3">	
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="Effective To Date">
									</div>
									<div class="col-md-3">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>

							<div class="col-md-2 text-right">
								<?php 
									$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
								?>
								<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
														
								<div class="filter_page">
									<label>
										<span>Show :</span> 
										<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
											<?php 
												$pageLimit = $_SESSION['PAGE'];
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
					</form>	
					
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover dataTable">
							<thead>
								<tr>
									<th class="text-center">Controls</th>
									<th class="text-center">Employee Code</th>
									<th>Employee Name</th>
									<th class="text-center">Effective From Date</th>
									<th class="text-center">Effective To Date</th>
									<th>Place</th>
									<th class="text-center">Created Date</th>
									<th class="text-center">Monthly Income</th>
									<th class="text-center">Total Compensation Per Annum</th>
								</tr>
							</thead>
							<tbody>
								<?php 	
									$i=0;
									$firstItem = $first_item;
									$empPerAnnumCtc = $totalCTC = 0;
									foreach($resultData as $row)
									{
										$getLineQry = "select per_month_inr,per_annum_inr from emp_salary_structure_line where header_id='".$row["header_id"]."' ";
										$getLineData = $this->db->query($getLineQry)->result_array();
										
										/* $getLineQry = "select per_month_inr,per_annum_inr,emp_salary_structure_line.header_id from emp_salary_structure_line 
										left join emp_salary_structure_header on emp_salary_structure_header.header_id = emp_salary_structure_line.header_id
											where emp_salary_structure_line.header_id='".$row["header_id"]."' and
												emp_salary_structure_header.default_annexure = 1 and
														emp_salary_structure_header.to_date >= '".$currentDate."'
											";
											 */
										$per_month_inr = $per_annum_inr = 0;
										foreach($getLineData as $lineData)
										{
											$per_month_inr += $lineData["per_month_inr"];
											$per_annum_inr += $lineData["per_annum_inr"];
										}
										?>
										<tr>
											<td class="text-center">
												<div class="dropdown" style="display: inline-block;width:83px;">
													<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<?php
															if((isset($annexure['create_edit_only']) && $annexure['create_edit_only'] == 1) || $this->user_id == 1)
															{
																?>	
																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>employee/ManageSalary/edit/<?php echo $row['header_id'];?>">
																		<i class="fa fa-pencil"></i> Edit
																	</a>
																</li>
																<?php 
															}
														?>
														
														<?php
															if((isset($annexure['read_only']) && $annexure['read_only'] == 1) || $this->user_id == 1)
															{
																?>
																<li>
																	<a title="View" href="<?php echo base_url(); ?>employee/viewSalary/<?php echo $row['header_id'];?>">
																		<i class="fa fa-eye"></i> View
																	</a>
																</li>
																<?php 
															}
														?>

														<?php /* <li>
															<a title="Download PDF" href="<?php echo base_url(); ?>employee/downloadPdf/<?php echo $row['header_id'];?>">
																<i class="fa fa-file-pdf-o"></i> Download PDF
															</a>
														</li> */ ?>
													</ul>
												</div>
											</td>

											<td class="tab-medium-width text-center"><?php echo $row['random_user_id'];?></td>
											<td class="tab-medium-width"><?php echo ucfirst($row['first_name']);?> <?php echo ucfirst($row['last_name']);?></td>
											
											<td class="tab-medium-width text-center">
													<?php echo date("d-M-Y",$row['string_from_date']);?>
											</td>

											<td class="tab-medium-width text-center">
												<?php 
													if ($row['string_to_date'] !=0 && $row['string_to_date'] !="") {
														echo date("d-M-Y", $row['string_to_date']);
													}
												?>
											</td>

											<td class="tab-medium-width">
												<?php echo ucfirst($row['place']);?>
											</td>

											<td class="tab-medium-width text-center"><?php echo date("d-M-Y",$row['created_date']);?></td>
											<td class="tab-medium-width text-right">
												<?php echo number_format($per_month_inr,DECIMAL_VALUE,'.','');?>
											</td>
											<td class="tab-medium-width text-right">
												<?php echo number_format($per_annum_inr,DECIMAL_VALUE,'.','');?>
											</td>
										</tr>
										<?php 
										$i++;
										$totalCTC += $per_month_inr;
										$empPerAnnumCtc += $per_annum_inr;
									}
								?>

								<?php 
									if( count($resultData) > 0 )
									{
										?>
										<tr>
											<td colspan="7" class="text-right">
												<b>Total :</b>
											</td>
											<td class="text-right">
												<?php echo number_format($totalCTC,DECIMAL_VALUE,'.','');?>
											</td>
											<td class="text-right">
												<?php echo number_format($empPerAnnumCtc,DECIMAL_VALUE,'.','');?>
											</td>
										</tr>
										<?php 
									} 
								?>
							</tbody>
						</table>
						<?php 
							if(count($resultData) == 0)
							{
								?>
								<div class="col-md-12 float-left text-center"> 
									<img src="<?php echo base_url(); ?>uploads/nodata.png">
								</div>
								<?php 
							} 
						?>
					</div>
					
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
					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
	<?php if(isset($type) && $type =='view'){?>
		<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
	<?php } ?>
</div><!-- Content end-->
	
