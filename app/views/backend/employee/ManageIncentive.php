<?php
	$revenue_adjustment = accessMenu(revenue_adjustment);
?>


<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add")
				{
					?>
					<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
					<legend class="text-uppercase font-size-sm font-weight-bold">
						<?php echo $type; ?> Revenue Adjustment
					</legend>
					<form action="<?php echo base_url();?>employee/ManageIncentive/add" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<?php 
								$empQry = "select first_name,last_name,user_id,random_user_id from users 
								where 
									user_status=1 and
										register_type=1 
											order by first_name asc";
								$getEmployee = $this->db->query($empQry)->result_array();
							
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
							<script>  
								/* function getemployeeuserId(user_id, emp_name)
								{	
									checkAnnuexure(user_id);
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
									}
								}

								function checkAnnuexure(user_id)
								{
									$.ajax({
										url: '<?php echo base_url();?>employee/AnnexureExist',
										type: 'post',
										data: {
											'user_id_check' : 1,
											'user_id' : user_id
										},
										success: function(response)
										{
											if (response == 'taken' ) 
											{
												$(".employee_user_id_exist_error").attr("style", "display: none;");
												$(".register-but").removeAttr("disabled", "disabled=disabled");
												$(".register-but").removeClass("disabled-class");
												return true;
											}
											else if (response == 'not_taken') 
											{
												emp_email_state = false;
												
												$(".employee_user_id_exist_error").addClass("error");
												$(".employee_user_id_exist_error").attr("id", "email-error");
												$(".employee_user_id_exist_error").attr("style", "display: inline;");
												
												$(".register-but").attr("disabled", "disabled=disabled");
												$(".register-but").addClass("disabled-class");
												$('.employee_user_id_exist_error').html('Valid CTC is not available for this employee.');
												
												return false;
											}
										}
									});
								} */
							</script> 
							
							<?php /* 
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Name</label>
								<input type="text" name="emp_name" <?php echo $readonly;?> id="emp_name" autocomplete="off" class="form-control" value="<?php echo $employee_name; ?>" placeholder="">
							</div> */ ?>
						
							<?php 
								$payrollElementQry = "select category_id,category_type,category_name from hr_payslip_categories 
								where category_type=1 order by category_name asc";
								$getElements = $this->db->query($payrollElementQry)->result_array();
							?>
						
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Category <span class="text-danger">*</span></label>
								<select name="element_category_id" required id="element_category_id" class="form-control searchDropdown">
									<option value="">- Select Element -</option>
									<?php 
										foreach($this->paySlipCategoryType as $key => $value)
										{ 
											$selected="";

											if($key == 1)
											{
												$selected="selected='selected'";
											}
											/* if($type == "add")
											{
												if($key == 2)
												{
													$selected="selected='selected'";
												}
											}
											else
											{
												if( isset($edit_data[0]['element_category_id']) && $edit_data[0]['element_category_id'] == $key)
												{
													$selected="selected='selected'";
												}
											} */
											?>
											<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
											<?php 
										} 
									?>
								</select>
							</div>

							<div class="form-group col-md-3">
								<label class="col-form-label">Element Name <span class="text-danger">*</span></label>
								<select name="element_id" id="element_id" onchange="getProjectCost(this.value);"class="form-control searchDropdown" required>
									<option value="">- Select Element -</option>
									<?php 
										foreach($getElements as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['element_id']) && $edit_data[0]['element_id'] == $row['category_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['category_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['category_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div> 

							<div class="form-group col-md-3">
								<label class="col-form-label">Financial Year <span class="text-danger">*</span></label>
								<?php 
									$financialYear = "select * from org_financial_years where financial_status=1 order by financial_year_id asc";
									$getfinancialYear = $this->db->query($financialYear)->result_array();
								?>
								<select name="financial_year_id" required id="financial_year_id" onchange="selectFinancialPeriod(this.value);" class="form-control searchDropdown">
									<option value="">- Select -</option>
									<?php 
										foreach($getfinancialYear as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['financial_year_id']) && $edit_data[0]['financial_year_id'] == $row['financial_year_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['financial_year_id'];?>" <?php echo $selected;?>>
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_from_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_from_year'];?> 
												to 
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_to_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_to_year'];?>
											</option>
											<?php 
											} 
										?>
									</select>
							</div>

							<script>
								function selectFinancialPeriod(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'payroll/ajaxSelectPayroll';?>",
											data: { id: val }
										}).done(function( msg ) 
										{   
											$("#period_id").html(msg);
										});
									}
									else 
									{ 
										alert("No Periods under this financial year!");
									}
								}

								function getProjectCost(val)
								{
									/* if(val == 21)
									{
										$(".employee_recovery_table").hide();
									}
									else
									{
										$(".employee_recovery_table").show();
									} */
									/* var userID = $("#user_id").val();
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php #echo base_url().'employee/getProjectCost';?>",
											data: { id: val,user_id:userID }
										}).done(function( d ) 
										{   
											var employeeAmount = d;
											//data = JSON.parse(d);
											
											$("#amount").val(employeeAmount);
											
											//("#paid_amount").val('');
											//$("#balance_amount").val('');
										});
									}
									else 
									{ 
										alert("No Employer Cost added for this employee");
									} */
								}
							</script>

							<div class="form-group col-md-3">
								<label class="col-form-label">Period <span class="text-danger">*</span></label>
								<select name="period_id" id="period_id" class="form-control searchDropdown" required>
									<option value="">- Select Period -</option>
									<?php 
										if ($type =="edit")
										{
											$Period = "select * from emp_periods where 
											period_status=1 and financial_year_id = '".$edit_data[0]['financial_year_id']."' 
													order by period_id asc";

											$getPeriod = $this->db->query($Period)->result_array();
										
											foreach($getPeriod as $row)
											{ 
												$selected="";
												if( isset($edit_data[0]['period_id']) && $edit_data[0]['period_id'] == $row['period_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['period_id'];?>" <?php echo $selected;?>>
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if($month == $row['month'])
														{
															echo $Month_value;
														}
													} 
												?>
												<?php echo $row['year'];?>
												</option>
												<?php 
											} 
										} 
									?>
								</select>
							</div>
							
						</div> 

						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
								<input type="text" name="emp_code" id="emp_code" autocomplete="off"  <?php echo $this->validation;?> value="<?php echo $employee_code;?>" required class="form-control">
								<div id="patientList"></div>
								<span class='small employee_user_id_exist_error' --style="color:red;"></span> 
								<input type="hidden" name="new_user_id" id="new_user_id" value="<?php echo $empCode;?>" class="form-control">
							</div>

							<div class="form-group col-md-3">
								<label class="col-form-label">Remarks</label>
								<textarea name="remarks" id="remarks"  rows="1" class="form-control"><?php echo isset($edit_data[0]['remarks']) ? $edit_data[0]['remarks'] :"";?></textarea>
							</div>
						</div>

						<input type="hidden" name="string_to_date" id="string_to_date" readonly class="form-control default_date"  value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
						<input type="hidden" name="string_from_date" id="string_from_date" readonly class="form-control default_date" value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">
											
						<!-- Table start here-->
						<div class="row employee_recovery_table mt-2">
							<div class="col-md-12">
								<div style="overflow-y: auto;">
									<div id="err_product" style="color:red;margin: 0px 0px 8px 0px;"></div>
									<table class="table items table-striped-- table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
										<thead>
											<tr>
												<th colspan="10">Employee Recovery</th>
											</tr>
											<tr>
												<th></th>
												<th class="text-center">Employee Code</th>
												<th>Employee Name</th>
												<th class="text-center">Amount</th>
											</tr>
										</thead>
										
										<tbody id="product_table_body">
											<?php 
												/* if(isset($type) && $type == "edit")
												{
													$employeeRatingQry = "select 
														emp_rating_line.*,
														users.user_id,
														users.first_name,
														users.last_name,
														users.random_user_id
														
														from emp_rating_line 
													
													left join emp_rating_header on 
														emp_rating_header.header_id = emp_rating_line.header_id	
														
													left join users on 
														users.user_id = emp_rating_line.user_id		
														
													where 
														emp_rating_line.line_id='".$id."' and 
														emp_rating_line.user_id='".$status."'
													";
													$employeeRating = $this->db->query($employeeRatingQry)->result_array();
													
													if(  count($employeeRating) > 0 )
													{
														$counter=1;
														foreach($employeeRating as $row)
														{
															?>
															<tr class="dataRowVal17 table_rows">
																<td class="text-center">
																	<a class="deleteRow"> <i class="fa fa-trash"></i> </a>
																	<input type="hidden" name="id" value="0">
																	<input type="hidden" name="counter" value="<?php echo $counter;?>">
																	<input type="hidden" name="user_id[]" value="<?php echo $row["user_id"];?>">
																	<input type="hidden" name="line_id[]" value="<?php echo $row["line_id"];?>">
																</td>
																<td class="tab-medium-width text-center"><?php echo $row["random_user_id"];?></td>
																<td class="tab-medium-width"><?php echo ucfirst($row["first_name"]);?></td>
																<td class="tab-medium-width text-center">
																	<input type="text" name="rating[]" required="" class="form-control" autocomplete="off" id="rating<?php echo $counter; ?>" value="<?php echo $row["emp_rating"];?>">
																</td>
															</tr>
															<?php
															$counter++;
														}
													}
												} */
											?>
										</tbody>
									</table>
								</div>
								<input type="hidden" name="table_data" id="table_data">
							</div>
						</div>
						<!-- Table start here-->

						<div class="d-flexad text-right mt-4">
							<a href="<?php echo base_url(); ?>employee/ManageIncentive" class="btn btn-light">Cancel  </a>
							<button type="submit" class="btn btn-primary ml-2 register-but">Submit</button>
						</div>
					</form>

					<script>
						$(document).ready(function()
						{  
							$('#emp_code').keyup(function()
							{  
								var element_id = $("#element_id").val();

								var financial_year_id = $("#financial_year_id").val();
								var period_id = $("#period_id").val();
								
								if( element_id == "" || financial_year_id == "" || period_id == "" )
								{
									alert("Please select required fields above!");
									$("#emp_code").val("");
								}
								else
								{
									var query = $(this).val();  

									if(query != '')  
									{  
										$.ajax({  
											url:"<?php echo base_url();?>employee/EmployeeAjaxSearch",  
											method:"POST",  
											data:{
												query             : query,
												financial_year_id : financial_year_id,
												period_id         : period_id,
											},  
											success:function(data)  
											{  
												$('#patientList').fadeIn();  
												$('#patientList').html(data);  
											}  
										});  
									}  
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

						function getuserId(user_id)
						{	
							$('#new_user_id').val(user_id);
							
							var id = user_id;
							var dssid = user_id;
							$('#err_product').text('');
							var flag = 0;
							var counter = 1;
							var i = 1;
							var element_id = $("#element_id").val();

							var financial_year_id = $("#financial_year_id").val();
							var period_id = $("#period_id").val();
							
							if(id >= 0 && element_id !=21) //not Employee PF
							{
								$.ajax({
									url: "<?php echo base_url('employee/employeeList') ?>/"+id+"/"+financial_year_id+"/"+period_id,
									type: "GET",
									data:{
										'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
									},
									datatype: "JSON",
									success: function(d)
									{
										data = JSON.parse(d);
										var countKey = Object.keys(data['empData']).length;
										var flag = 0;
										
										if(countKey > 0)
										{
											$.each(data['empData'], function(i, item) 
											{
												$("table.product_table").find('input[name^="user_id"]').each(function () 
												{
													var user_id =$(this).closest("tr").find('input[name^="user_id[]"]').val();
													
													if( user_id == item.user_id )
													{
														flag = 1;
													}
												}); 
								
												if(flag == 0)
												{
												
													if( item.user_id == null ){
														var id = 0;
													}else{
														var id = item.user_id;
													}

													if( item.random_user_id == null ){
														var code = "";
													}else{
														var code = item.random_user_id;
													}
													
													if( item.first_name == null ){
														var first_name = "";
													}else{
														var first_name = item.first_name;
													}
													
													
													var product = { 
														"user_id"          : id,
														"employee_rating_line"  : '0',
													};                  

													product_data[i] = product;
													length = product_data.length - 1 ;
													
													var newRow = $("<tr class='dataRowVal"+id+" table_rows'>");
													var cols = "";
													cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='user_id[]' value="+id+"></td>";
													cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
													cols += "<td class='tab-medium-width'>"+first_name+"</td>";
													cols += "<td class='tab-medium-width text-center'>"
														+"<input type='number' name='amount[]' required class='form-control' autocomplete='off' id='rating"+counter+"' value='<?php //echo isset($edit_data[0]['rating']) ? $edit_data[0]['rating'] :"";?>'>"
														+"</td>";
													newRow.html(cols);
													$("table.product_table").append(newRow);
													var table_data = JSON.stringify(product_data);
													$('#table_data').val(table_data);		
													
													counter++;
													i++;
												}
											});
										}
									},
									error: function(xhr, status, error) 
									{
										$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
									}
								});
							}
						}	

						$("table.product_table").on("click", "a.deleteRow", function (event) 
						{
							deleteRow($(this).closest("tr"));
							$(this).closest("tr").remove();
							//calculateGrandTotal();
						});

						function deleteRow(row)
						{
							var id = +row.find('input[name^="id"]').val();
							// var array_id = product_data[id].product_id;
							//product_data.splice(id, 1);
							product_data[id] = null;
							//alert(product_data);
							var table_data = JSON.stringify(product_data);
							$('#table_data').val(table_data);
						}
					</script>
					<?php
				}
				else if(isset($type) && $type == "edit")
				{
					?>
					<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
					<legend class="text-uppercase font-size-sm font-weight-bold">
						<?php echo $type; ?> Revenue Adjustment
					</legend>
					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
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
							<script>  
								$(document).ready(function()
								{  
									$('#emp_code').keyup(function()
									{  
										var query = $(this).val();  
										if(query != '')  
										{  
											$.ajax({  
												url:"<?php echo base_url();?>employee/EmployeeAjaxSearch",  
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
									checkAnnuexure(user_id);
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
									}
								}

								function checkAnnuexure(user_id)
								{
									$.ajax({
										url: '<?php echo base_url();?>employee/AnnexureExist',
										type: 'post',
										data: {
											'user_id_check' : 1,
											'user_id' : user_id
										},
										success: function(response)
										{
											if (response == 'taken' ) 
											{
												$(".employee_user_id_exist_error").attr("style", "display: none;");
												$(".register-but").removeAttr("disabled", "disabled=disabled");
												$(".register-but").removeClass("disabled-class");
												return true;
											}
											else if (response == 'not_taken') 
											{
												emp_email_state = false;
												
												$(".employee_user_id_exist_error").addClass("error");
												$(".employee_user_id_exist_error").attr("id", "email-error");
												$(".employee_user_id_exist_error").attr("style", "display: inline;");
												
												$(".register-but").attr("disabled", "disabled=disabled");
												$(".register-but").addClass("disabled-class");
												$('.employee_user_id_exist_error').html('Valid CTC is not available for this employee.');
												
												return false;
											}
										}
									});
								}							
							</script> 
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
								<input type="text" name="emp_code" id="emp_code" autocomplete="off" readonly <?php echo $this->validation;?> value="<?php echo $employee_code;?>" required class="form-control">
								<div id="patientList"></div>
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $empCode;?>" class="form-control">
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Name</label>
								<input type="text" name="emp_name" <?php echo $readonly;?> id="emp_name" autocomplete="off" class="form-control" value="<?php echo $employee_name; ?>" placeholder="">
							</div>
							
							<?php /* <div class="form-group col-md-3">
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
							<?php 
								$payrollElementQry = "select category_id,category_type,category_name from hr_payslip_categories 
								
								where category_type=1 order by category_name asc";
								$getElements = $this->db->query($payrollElementQry)->result_array();
							?>
						
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Category <span class="text-danger">*</span></label>
								<select name="element_category_id" required id="element_category_id" class="form-control searchDropdown">
									<option value="">- Select Element -</option>
									<?php 
										foreach($this->paySlipCategoryType as $key => $value)
										{ 
											$selected="";
											if($type == "add")
											{
												if($key == 1){
													$selected="selected='selected'";
												}
											}else
											{
												if( isset($edit_data[0]['element_category_id']) && $edit_data[0]['element_category_id'] == $key)
												{
													$selected="selected='selected'";
												}
											}
											?>
											<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
											<?php 
										} 
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Name <span class="text-danger">*</span></label>
								<select name="element_id" id="element_id" onchange="getProjectCost(this.value);" class="form-control searchDropdown" required>
									<option value="">- Select Element -</option>
									<?php 
										foreach($getElements as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['element_id']) && $edit_data[0]['element_id'] == $row['category_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['category_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['category_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div> 
							<?php /* <div class="form-group col-md-3">
								<label class="col-form-label">Element Name <span class="text-danger">*</span></label>
								<input type="text" name="element_id" readonly required <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($getElements[0]['element_name']) ? $getElements[0]['element_name'] :"";?>" placeholder="">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Category <span class="text-danger">*</span></label>
								<input type="text" name="element_category_id" required readonly <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($getElements[0]['category_name']) ? $getElements[0]['category_name'] :"";?>" placeholder="">
							</div>
						
							<div class="form-group col-md-3">
								<label class="col-form-label">Period Start Date<span class="text-danger">*</span></label>
								<input type="text" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Period End Date<span class="text-danger">*</span></label>
								<input type="text" name="string_to_date" id="string_to_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
							</div> */ ?>
							<div class="form-group col-md-3">
								<label class="col-form-label">Financial Year <span class="text-danger">*</span></label>
								<?php 
									$financialYear = "select * from org_financial_years where financial_status=1 order by financial_year_id asc";
									$getfinancialYear = $this->db->query($financialYear)->result_array();
								?>
								<select name="financial_year_id" required id="financial_year_id" onchange="selectFinancialPeriod(this.value);" class="form-control searchDropdown">
									<option value="">- Select -</option>
									<?php 
										foreach($getfinancialYear as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['financial_year_id']) && $edit_data[0]['financial_year_id'] == $row['financial_year_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['financial_year_id'];?>" <?php echo $selected;?>>
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_from_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_from_year'];?> 
												to 
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_to_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_to_year'];?>
											</option>
											<?php 
										} 
									?>
								</select>
							</div>
							<script>
								function selectFinancialPeriod(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'payroll/ajaxSelectPayroll';?>",
											data: { id: val }
										}).done(function( msg ) 
										{   
											$("#period_id").html(msg);
										});
									}
									else 
									{ 
										alert("No Periods under this financial year!");
									}
								}

								function getProjectCost(val)
								{
									var userID = $("#user_id").val();
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'employee/getProjectCost';?>",
											data: { id: val,user_id:userID }
										}).done(function( d ) 
										{   
											var employeeAmount = d;
											//data = JSON.parse(d);
											
											$("#amount").val(employeeAmount);
											
											//("#paid_amount").val('');
											//$("#balance_amount").val('');
										});
									}
									else 
									{ 
										alert("No Employer Cost added for this employee");
									}
								}
								
							</script>
							<div class="form-group col-md-3">
								<label class="col-form-label">Period <span class="text-danger">*</span></label>
								<select name="period_id" id="period_id" class="form-control searchDropdown" required>
									<option value="">- Select Period -</option>
									<?php 
										if ($type =="edit")
										{
											$Period = "select * from emp_periods where 
											period_status=1 and financial_year_id = '".$edit_data[0]['financial_year_id']."' 
													order by period_id asc";

											$getPeriod = $this->db->query($Period)->result_array();
										
											foreach($getPeriod as $row)
											{ 
												$selected="";
												if( isset($edit_data[0]['period_id']) && $edit_data[0]['period_id'] == $row['period_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['period_id'];?>" <?php echo $selected;?>>
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if($month == $row['month'])
														{
															echo $Month_value;
														}
													} 
												?>
												<?php echo $row['year'];?>
												</option>
												<?php 
											} 
										} 
									?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Amount<span class="text-danger">*</span></label>
								<input type="text" name="amount" id="amount" class="form-control" required value="<?php echo isset($edit_data[0]['amount']) ? $edit_data[0]['amount']:"";?>">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Remarks</label>
								<textarea name="remarks" id="remarks" rows="1" class="form-control"><?php echo isset($edit_data[0]['remarks']) ? $edit_data[0]['remarks'] :"";?></textarea>
							</div>
							
						</div>

						<div class="row">
							
						</div>

						<input type="hidden" name="string_to_date" id="string_to_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
						<input type="hidden" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">


						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>employee/ManageIncentive/grid_view" class="btn btn-light">Cancel  </a>
							<?php 
								if($type == "edit")
								{
									?>
									<button type="submit" class="btn btn-primary ml-2">Update</button>
									<?php 
								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary ml-2 register-but">Submit</button>
									<?php 
								}
							?>
						</div>
					</form>
					<?php
				}
				else
				{ 
					?>
					<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
					<!-- <legend class="text-uppercase font-size-sm font-weight-bold">
						<?php echo $type; ?> Employee Incentive :
					</legend>
					<form action="<?php echo base_url();?>employee/ManageIncentive/add" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
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
							
							<script>  
								$(document).ready(function()
								{  
									$('#emp_code').keyup(function()
									{  
										var query = $(this).val();  
										if(query != '')  
										{  
											$.ajax({  
												url:"<?php echo base_url();?>employee/EmployeeAjaxSearch",  
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
									checkAnnuexure(user_id);
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
									}
								}	
								
								
								function checkAnnuexure(user_id)
								{
									$.ajax({
										url: '<?php echo base_url();?>employee/AnnexureExist',
										type: 'post',
										data: {
											'user_id_check' : 1,
											'user_id' : user_id
										},
										success: function(response)
										{
											if (response == 'taken' ) 
											{
												$(".employee_user_id_exist_error").attr("style", "display: none;");
												$(".register-but").removeAttr("disabled", "disabled=disabled");
												$(".register-but").removeClass("disabled-class");
												return true;
											}
											else if (response == 'not_taken') 
											{
												emp_email_state = false;
												
												$(".employee_user_id_exist_error").addClass("error");
												$(".employee_user_id_exist_error").attr("id", "email-error");
												$(".employee_user_id_exist_error").attr("style", "display: inline;");
												
												$(".register-but").attr("disabled", "disabled=disabled");
												$(".register-but").addClass("disabled-class");
												$('.employee_user_id_exist_error').html('Valid CTC is not available for this employee.');
												
												return false;
											}
										}
									});
								}
							</script> 
							
						
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Code <span class="text-danger">*</span></label>
								<input type="text" name="emp_code" id="emp_code" autocomplete="off"  <?php echo $this->validation;?> value="<?php echo $employee_code;?>" required class="form-control">
								<div id="patientList"></div>
								<span class='small employee_user_id_exist_error' --style="color:red;"></span> 
								<input type="hidden" name="user_id" id="user_id" value="<?php echo $empCode;?>" class="form-control">
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Employee Name</label>
								<input type="text" name="emp_name" <?php echo $readonly;?> id="emp_name" autocomplete="off" class="form-control" value="<?php echo $employee_name; ?>" placeholder="">
							</div>
							
							<?php /*
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
							</div>
							*/ ?>
					
							<?php 
								$payrollElementQry = "select category_id,category_type,category_name
								from hr_payslip_categories 
								where category_type=1 order by category_name asc";
								$getElements = $this->db->query($payrollElementQry)->result_array();
							?>
						
							<?php /* <div class="form-group col-md-3">
								<label class="col-form-label">Element Name <span class="text-danger">*</span></label>
								<select name="element_id" id="element_id" class="form-control searchDropdown" required>
									<option value="">- Select Element -</option>
									<?php 
										foreach($getElements as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['element_id']) && $edit_data[0]['element_id'] == $row['category_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['category_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['category_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div> */ ?>
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Category <span class="text-danger">*</span></label>
								<select name="element_category_id" required id="element_category_id" class="form-control searchDropdown">
									<option value="">- Select Element -</option>
									<?php 
										foreach($this->paySlipCategoryType as $key => $value)
										{ 

											$selected="";

											if($key == 1)
											{
												$selected="selected='selected'";
											}

											/*if($type == "add")
											{
												if($key == 1){
													$selected="selected='selected'";
												}
											}
											else
											{
												if( isset($edit_data[0]['element_category_id']) && $edit_data[0]['element_category_id'] == $key)
												{
													$selected="selected='selected'";
												}
											}*/
											?>
											<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
											<?php 
										} 
									?>
								</select>
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Name <span class="text-danger">*</span></label>
								<select name="element_id" id="element_id" class="form-control searchDropdown" required>
									<option value="">- Select Element -</option>
									<?php 
										foreach($getElements as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['element_id']) && $edit_data[0]['element_id'] == $row['category_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['category_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['category_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div> 
							<?php /* <div class="form-group col-md-3">
								<label class="col-form-label">Element Name <span class="text-danger">*</span></label>
								<input type="text" name="element_id" readonly required <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($getElements[0]['element_name']) ? $getElements[0]['element_name'] :"";?>" placeholder="">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Element Category <span class="text-danger">*</span></label>
								<input type="text" name="element_category_id" required readonly <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($getElements[0]['category_name']) ? $getElements[0]['category_name'] :"";?>" placeholder="">
								<input type="hidden" name="element_category_id" required readonly <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($getElements[0]['category_id']) ? $getElements[0]['category_id'] :"";?>" placeholder="">
							</div> */?>
								<input type="hidden" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">
								<input type="hidden" name="string_to_date" id="string_to_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
							
						
							<?php /* <div class="form-group col-md-3">
								<label class="col-form-label">Period Start Date<span class="text-danger">*</span></label>
								<input type="text" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">
							</div>
							<div class="form-group col-md-3">
								<label class="col-form-label">Period End Date<span class="text-danger">*</span></label>
								<input type="text" name="string_to_date" id="string_to_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
							</div>  */?>
							<div class="form-group col-md-3">
								<label class="col-form-label">Financial Year <span class="text-danger">*</span></label>
								<?php 
									$financialYear = "select * from org_financial_years where financial_status=1 order by financial_year_id asc";
									$getfinancialYear = $this->db->query($financialYear)->result_array();
								?>
								<select name="financial_year_id" required id="financial_year_id" onchange="selectFinancialPeriod(this.value);" class="form-control searchDropdown">
									<option value="">- Select -</option>
									<?php 
										foreach($getfinancialYear as $row)
										{ 
											$selected="";
											if( isset($edit_data[0]['financial_year_id']) && $edit_data[0]['financial_year_id'] == $row['financial_year_id'])
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['financial_year_id'];?>" <?php echo $selected;?>>
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_from_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_from_year'];?> 
												to 
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_to_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_to_year'];?>
											</option>
											<?php 
										} 
									?>
								</select>
							</div>
							<script>
								function selectFinancialPeriod(val)
								{
									if(val !='')
									{
										$.ajax({
											type: "POST",
											url:"<?php echo base_url().'payroll/ajaxSelectPayroll';?>",
											data: { id: val }
										}).done(function( msg ) 
										{   
											$("#period_id").html(msg);
										});
									}
									else 
									{ 
										alert("No Periods under this financial year!");
									}
								}
							</script>
							<div class="form-group col-md-3">
								<label class="col-form-label">Period <span class="text-danger">*</span></label>
								<select name="period_id" id="period_id" class="form-control searchDropdown" required>
									<option value="">- Select Period -</option>
									<?php 
										if ($type =="edit")
										{
											$Period = "select * from emp_periods where 
											period_status=1 and financial_year_id = '".$edit_data[0]['financial_year_id']."' 
													order by period_id asc";

											$getPeriod = $this->db->query($Period)->result_array();
										
											foreach($getPeriod as $row)
											{ 
												$selected="";
												if( isset($edit_data[0]['period_id']) && $edit_data[0]['period_id'] == $row['period_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['period_id'];?>" <?php echo $selected;?>>
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if($month == $row['month'])
														{
															echo $Month_value;
														}
													} 
												?>
												<?php echo $row['year'];?>
												</option>
												<?php 
											} 
										} 
									?>
								</select>
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Amount<span class="text-danger">*</span></label>
								<input type="text" name="amount" id="amount" class="form-control mobile_vali" required value="<?php echo isset($edit_data[0]['amount']) ? $edit_data[0]['amount']:"";?>">
							</div>
							
							<div class="form-group col-md-3">
								<label class="col-form-label">Remarks</label>
								<textarea name="remarks" id="remarks" rows="1" class="form-control"><?php echo isset($edit_data[0]['remarks']) ? $edit_data[0]['remarks'] :"";?></textarea>
							</div>
						
						</div>
						<div class="d-flexad text-right">
							<button type="submit" class="btn btn-primary ml-2 register-but">Submit </button>
						</div>
					</form>
					
					<hr><br> -->


					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if((isset($revenue_adjustment['create_edit_only']) && $revenue_adjustment['create_edit_only'] == 1) || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>employee/ManageIncentive/add" class="btn btn-info btn-sm">
										Add Revenue Adjustment
									</a>
									<?php 
								} 
							?>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-2">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Employee Code, Employee Name.</p>
									</div>	
									
									<div class="col-md-2">	
										<?php 
											$financialQry = "select 
												org_financial_years.*
												
												from org_financial_years 

											join emp_incentive on 
												emp_incentive.financial_year_id = org_financial_years.financial_year_id
											
												where org_financial_years.financial_status = 1
											
											group by emp_incentive.financial_year_id
											";
											$getFinancial = $this->db->query($financialQry)->result_array();
										?>
										<select name="financial_year_id" onchange="selectFinancialPeriodFilter(this.value);" class="form-control searchDropdown">
											<option value="">- Select Financial Year -</option>
											<?php 
												foreach ($getFinancial as $row) 
												{
													$selected="";
													if(isset($_GET["financial_year_id"]) && $_GET["financial_year_id"] == $row["financial_year_id"])
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["financial_year_id"];?>" <?php echo $selected;?>>
														<?php
															for($iM =01;$iM<=12;$iM++)
															{
																$month = date("m", strtotime("$iM/12/10"));
																#$Month_value = date("M", strtotime("$iM/12/10"));
																$Month_value = date("F", strtotime("$iM/12/10"));
																
																if( $row['financial_from_month'] == $month)
																{
																	echo $Month_value;
																}
															}
														?>
														-
														<?php echo $row['financial_from_year'];?> 
														to 
														<?php
															for($iM =01;$iM<=12;$iM++)
															{
																$month = date("m", strtotime("$iM/12/10"));
																#$Month_value = date("M", strtotime("$iM/12/10"));
																$Month_value = date("F", strtotime("$iM/12/10"));
																
																if( $row['financial_to_month'] == $month)
																{
																	echo $Month_value;
																}
															}
														?>
														-
														<?php echo $row['financial_to_year'];?>
													</option>
													<?php
												} 
											?>
										</select>
									</div>

									<div class="col-md-2">	
										<select name="period_id" id="period_id_filter" class="form-control searchDropdown">
											<option value="">- Select Period -</option>
											<?php 
												if(isset($_GET["financial_year_id"]) && !empty($_GET["financial_year_id"]))
												{
													$periodQry = "select 
														emp_periods.period_id,
														emp_periods.month,
														emp_periods.year 
														from emp_periods 

													join emp_incentive on 
													emp_incentive.period_id = emp_periods.period_id

													where 
														emp_periods.period_status = 1 and 
															emp_periods.financial_year_id = '".$_GET["financial_year_id"]."'
													
													group by emp_incentive.period_id
													";
													
													$getPeriod = $this->db->query($periodQry)->result_array();
													
													foreach ($getPeriod as $row) 
													{
														$selected="";
														if(isset($_GET["period_id"]) && $_GET["period_id"] == $row["period_id"])
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $row["period_id"];?>" <?php echo $selected;?>>
															<?php 
																for($iM =01;$iM<=12;$iM++)
																{
																	$month = date("m", strtotime("$iM/12/10"));
																	$Month_value = date("F", strtotime("$iM/12/10"));
																	
																	if($month == $row['month'])
																	{
																		echo $Month_value;
																	}
																} 
															?>
															<?php echo $row['year'];?>	
														</option>
														<?php
													} 
												} 
											?>
										</select>
									</div>

									<script>
										function selectFinancialPeriodFilter(val)
										{
											if(val !='')
											{
												$.ajax({
													type: "POST",
													url:"<?php echo base_url().'payroll/ajaxSelectPayroll';?>",
													data: { id: val }
												}).done(function( msg ) 
												{   
													$("#period_id_filter").html(msg);

													if(msg == "No Periods under this financial year!")
													{
														$("#period_id_filter").html("<option value=''>- Select -</option>");
													}
												});
											}
											else 
											{ 
												alert("No Periods under this financial year!");
												$("#period_id_filter").html("<option value=''>- Select -</option>");
											}
										}
									</script>


									<div class="col-md-2">	
										
										<select name="element_id" class="form-control searchDropdown">
											<option value="">- Select Element Name -</option>
											<?php 
												
												$elementQry = "select 
													category_id,category_type,category_name from hr_payslip_categories 

													join emp_incentive on 
														emp_incentive.element_id = hr_payslip_categories.category_id
												where 
													hr_payslip_categories.category_type=1 

														group by emp_incentive.element_id

															order by category_name asc";
												$getFilterElements = $this->db->query($elementQry)->result_array();
												
												foreach($getFilterElements as $row)
												{ 
													$selected="";
													if( isset($_GET['element_id']) && $_GET['element_id'] == $row['category_id'])
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['category_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['category_name']);?></option>
													<?php 
												} 
											?>
										</select>
									</div>


									<div class="col-md-2">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
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
						</div>

						<!-- <div class="row">
							<div class="col-md-12 text-right">
								<?php 
									$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
								?>
								<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
														
								
							</div>
						</div> -->
					</form>
					
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
							<thead>
								<tr>
									<th class="text-center">Controls</th>
									<th>Financial Year</th>
									<th class="text-center">Period</th>
									<th class="text-center">Employee Code</th>
									<th>Employee Name</th>
									<th>Element Category</th>
									<th>Element Name</th>
									<th class="text-center">Incentive ( <?php echo CURRENCY_SYMBOL;?> )</th>
								</tr>
							</thead>
							<tbody>
								<?php 	
									$totalAmount = $i = 0;
									$firstItem = $first_item;
									foreach($resultData as $row)
									{
										?>
										<tr>
											<td class="text-center">
												<?php
													if((isset($revenue_adjustment['create_edit_only']) && $revenue_adjustment['create_edit_only'] == 1) || $this->user_id == 1)
													{
														?>
														<div class="dropdown controls-actions show">
															<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true" style="width: 70px;">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>employee/ManageIncentive/edit/<?php echo $row['incentive_id'];?>">
																		<i class="fa fa-pencil"></i> Edit
																	</a>
																</li>
																
																<?php /* <li>
																	<?php 
																		if($row['department_status'] == 1)
																		{
																			?>
																			<a href="<?php echo base_url(); ?>employee/ManageDepartment/status/<?php echo $row['department_id'];?>/0" title="Block">
																				<i class="fa fa-ban"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a href="<?php echo base_url(); ?>employee/ManageDepartment/status/<?php echo $row['department_id'];?>/1" title="Unblock">
																				<i class="fa fa-check"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																</li> */ ?>
															</ul>
														</div>
														<?php 
													}
													else
													{
														?>
														--
														<?php
													}
												?>
											</td>

											<td class="tab-full-width">
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_from_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_from_year'];?> 
												to 
												<?php
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														#$Month_value = date("M", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if( $row['financial_to_month'] == $month)
														{
															echo $Month_value;
														}
													}
												?>
												-
												<?php echo $row['financial_to_year'];?>
											</td>

											<td class="tab-medium-width text-center">
												<?php 
													for($iM =01;$iM<=12;$iM++)
													{
														$month = date("m", strtotime("$iM/12/10"));
														$Month_value = date("F", strtotime("$iM/12/10"));
														
														if($month == $row['month'])
														{
															echo $Month_value;
														}
													} 
												?>
												<?php echo $row['year'];?>
											</td>
											<td class="tab-medium-width text-center"><?php echo $row['random_user_id'];?></td>
											<td>
												<?php echo ucfirst($row['first_name'])." ".ucfirst($row['last_name']);?>
											</td>
											<!-- <td class="text-center"style="width:5%;"><?php //echo ucfirst($row['category_name']);?></td> -->
											<td class="tab-medium-width">
												<?php 												
													foreach ($this->paySlipCategoryType as $key => $type) 
													{
														if ($row['element_category_id'] == $key) 
														{
															echo $type;
														}
													}
												?>	
											</td>
											<td class="tab-medium-width">
												<?php echo ucfirst($row['category_name']);?>
											</td>
											<td class="text-right">
												<?php echo number_format($row['amount'],DECIMAL_VALUE,'.',''); ?>
											</td>
										</tr>
										<?php 
										$i++;
										$totalAmount += $row['amount'];
									}
								?>
								<?php 
									if (count($resultData) > 0) 
									{
										?>
										<tr>
											<td class="text-right" colspan="7">
												<b>Total :</b>
											</td>
											<td class="text-right">
												<b><?php echo number_format($totalAmount, DECIMAL_VALUE, '.', ''); ?></b>
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
									<img src="<?php echo base_url(); ?>uploads/no-data.png">
								</div>
								<?php 
							} 
						?>
					</div>
							
					<?php 
						if (count($resultData) > 0) 
						{
							?>
							<div class="row">
								<div class="col-md-4 showing-count">
									Showing <?php echo $starting; ?> to <?php echo $ending; ?> of <?php echo $totalRows; ?> entries
								</div>
								<!-- pagination start here -->
								<?php
									if (isset($pagination)) {
										?>	
										<div class="col-md-8" class="admin_pagination" style="float:right;padding: 0px 20px 0px 0px;"><?php foreach ($pagination as $link) {
											echo $link;
										} ?></div>
										<?php
									} ?>
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
	