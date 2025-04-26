<!-- Page header start
<div class="page-header page-header-danger">
	<div class="breadcrumb-line breadcrumb-line-danger header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageEmployee" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		<?php
			if( isset($type) && $type == "add" || $type == "edit" )
			{ 
				
			}
			else if( isset($type) && $type == "view" )
			{ 
		
				?>
				<a href="<?php echo base_url(); ?>employee/ManageEmployee/edit/<?php echo $id;?>" class="btn btn-info">
					Edit Employee
				</a>
				<?php
			}
			else
			{ 
				?>
				<a href="<?php echo base_url(); ?>Employee/ManageEmployee/add" class="btn btn-info">
					Create Employee
				</a>
				<a href="<?php echo base_url(); ?>Employee/ManageEmployee/add" class="btn btn-info">
					Create Employee
				</a>
				<?php 
			} 
		?>
	</div>
</div>
Page header end-->


<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageEmployeeSalaryslip" class="breadcrumb-item">
					<?php
						echo $page_title;
					?>
				</a>
			</div>
			
		</div>
		
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{ 
				
			}
			else if( isset($type) && $type == "view" )
			{ 
		
				?>
			<div class="row">
				<a title="Save as PDF" class="btn btn-danger" style="margin-right:20px;" target="_blank" href="<?php echo base_url(); ?>employee/salaryslipDetailsPDF/<?php echo $id;?>">
					<i class="fa fa-file-pdf-o"></i>
				</a>
				<a href="<?php echo base_url(); ?>employee/ManageEmployeeSalaryslip/edit/<?php echo $id;?>" class="btn btn-info">
					Edit Employee
				</a>
			</div>
				<?php
			}
			else
			{ 
				?>
				<div class="new-import-btn" style="float:right;">
					<a href="<?php echo base_url(); ?>employee/ManageEmployeeSalaryslip/add" class="btn btn-info">
						Create Employee Salary Slip
					</a>
				</div>
				<?php 
			}
		?>
	</div>
</div>
<!-- Page header end-->

	<div class="content"><!-- Content start-->
		<div class="card"><!-- Card start-->
			<div class="card-header- header-elements-inline">
				<h5 class="card-title"></h5>
			</div>
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<div class="card-body">
						<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
							<div class="row">
											
								<!-- left side start -->
								<div class="col-sm-12 col-md-12">
									<!-- Customer Details start -->
									<fieldset class="mb-3">
									
										<div class="row">
											<?php 
												$getEmployee = $this->db->query("select user_id, random_user_id, first_name from users where user_status=1 order by first_name asc")->result_array();
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label">Employee Name<span class="text-danger">*</span></label>
												<select name="employee_id" id="employee_id" class="form-control searchDropdown" required>
													<option value="">- Select Employee Name -</option>
													<?php 
														foreach($getEmployee as $row)
														{ 
															$selected="";
															if( isset($edit_data[0]['employee_id']) && $edit_data[0]['employee_id'] == $row['user_id'])
															{
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $row['user_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['first_name']);?> - <?php echo ucfirst($row['random_user_id']);?></option>
															<?php 
														} 
													?>
												</select>
											</div>
											<div class="form-group col-md-3">
												<label class="col-form-label">Total No of Days <span class="text-danger">*</span></label>
												<input type="text" name="num_of_days" id="num_of_days" required  <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['num_of_days']) ? $edit_data[0]['num_of_days'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Monthly Salary <span class="text-danger">*</span></label>
												<input type="text" name="monthly_salary" id="monthly_salary" required  <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['monthly_salary']) ? $edit_data[0]['monthly_salary'] :"";?>" placeholder="">
											</div>
											
											<?php /* <script type="text/javascript"> 
												$(document).ready(function() 
												{
													$('#monthly_wages').on('change', function() 
													{
														$('#gross_wages').val($(this).val());
													}); 
												});
											</script> */ ?>
											
										</div>
											
										<div class="row">
										
											<div class="form-group col-md-3">
												<label class="col-form-label">Wages / Salary <span class="text-danger">*</span></label>
												<input type="text" name="salary" id="salary" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['salary']) ? $edit_data[0]['salary'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Total montly wages <span class="text-danger">*</span></label>
												<input type="text" name="monthly_wages" id="monthly_wages" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['monthly_wages']) ? $edit_data[0]['monthly_wages'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Basic Salary <span class="text-danger">*</span></label>
												<input type="text" name="basic_salary" id="basic_salary" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['basic_salary']) ? $edit_data[0]['basic_salary'] :"0";?>" placeholder="">
											</div>
											
										</div>
										
										<div class="row">	
										
											<div class="form-group col-md-3">
												<label class="col-form-label">Allowance <span class="text-danger">*</span></label>
												<input type="text" name="allowance" id="allowance" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['allowance']) ? $edit_data[0]['allowance'] :"0";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Other Benefits <span class="text-danger">*</span></label>
												<input type="text" name="other_benefits" id="other_benefits" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['other_benefits']) ? $edit_data[0]['other_benefits'] :"0";?>" placeholder="">
											</div>
										
											<div class="form-group col-md-3">
												<label class="col-form-label">Gross Wages <span class="text-danger">*</span></label>
												<input type="text" name="gross_wages" id="gross_wages" required  <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['gross_wages']) ? $edit_data[0]['gross_wages'] :"0";?>" placeholder="">
											</div>
											
										</div>
										
										<div class="row">	
										
											<div class="form-group col-md-3">
												<label class="col-form-label">PF Employee Deductions  <span class="text-danger">*</span></label>
												<input type="text" name="pf_deduction" id="pf_deduction" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pf_deduction']) ? $edit_data[0]['pf_deduction'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">ESI Employee Deductions <span class="text-danger">*</span></label>
												<input type="text" name="esi_deduction" id="esi_deduction" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['esi_deduction']) ? $edit_data[0]['esi_deduction'] :"0";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">TDS </label>
												<input type="text" name="tds" id="tds" class="form-control" value="<?php echo isset($edit_data[0]['tds']) ? $edit_data[0]['tds'] :"0";?>" placeholder="">
											</div>
											
											
										</div>
										
										<div class="row">	
																						
											<div class="form-group col-md-3">
												<label class="col-form-label">Total Deductions <span class="text-danger">*</span></label>
												<input type="text" readonly name="total_deduction" id="total_deduction" required  <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['total_deduction']) ? $edit_data[0]['total_deduction'] :"";?>" placeholder="">
											</div>
										
											<div class="form-group col-md-3">
												<label class="col-form-label">PF Employer Deductions  <span class="text-danger">*</span></label>
												<input type="text" name="pf_employer_deduction" id="pf_employer_deduction" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pf_employer_deduction']) ? $edit_data[0]['pf_employer_deduction'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">ESI Employer Deductions  <span class="text-danger">*</span></label>
												<input type="text" name="esi_employer_deduction" id="esi_employer_deduction" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['esi_employer_deduction']) ? $edit_data[0]['esi_employer_deduction'] :"";?>" placeholder="">
											</div>
										</div>
										
										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Employer Contribution  <span class="text-danger">*</span></label>
												<input type="text" name="employer_contribution" id="employer_contribution" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['employer_contribution']) ? $edit_data[0]['employer_contribution'] :"";?>" placeholder="">
											</div>
											<div class="form-group col-md-3">
												<label class="col-form-label">Reimbursement 1</label>
												<input type="text" name="reimbursement_1" id="reimbursement_1" class="form-control" value="<?php echo isset($edit_data[0]['reimbursement_1']) ? $edit_data[0]['reimbursement_1'] :"0";?>" placeholder="">
											</div>
										
											<div class="form-group col-md-3">
												<label class="col-form-label">Reimbursement 2</label>
												<input type="text" name="reimbursement_2" id="reimbursement_2" class="form-control" value="<?php echo isset($edit_data[0]['reimbursement_2']) ? $edit_data[0]['reimbursement_2'] :"0";?>" placeholder="">
											</div>
											
										</div>
										
										<div class="row">	
											<div class="form-group col-md-3">
												<label class="col-form-label">Pay Period </label>
												<input type="text" name="pay_period" id="pay_period" class="form-control" value="<?php echo isset($edit_data[0]['pay_period']) ? $edit_data[0]['pay_period'] :"";?>" placeholder="">
											</div>
											<script>
												$( function() 
												{
													//DOB
													$("#pay_date").datepicker({
														changeMonth: true,
														changeYear: true,
														yearRange: "1950:<?php echo date('Y'); ?>",
														dateFormat: "dd-M-yy"	
													});
													
												});
											</script>
											<div class="form-group col-md-3">
												<label class="col-form-label">Pay Date </label>
												<input type="text" name="pay_date" id="pay_date" class="form-control" value="<?php echo isset($edit_data[0]['pay_date']) ? $edit_data[0]['pay_date'] :" ";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Net Salary  <span class="text-danger">*</span></label>
												<input type="text" name="net_salary" id="net_salary" required readonly <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['net_salary']) ? $edit_data[0]['net_salary'] :"";?>" placeholder="">
											</div>
											
										</div>
										<div class="row">	
										
											<div class="form-group col-md-3">
												<label class="col-form-label">ESI No </label>
												<input type="text" name="esi_no" id="esi_no" class="form-control" value="<?php echo isset($edit_data[0]['esi_no']) ? $edit_data[0]['esi_no'] :"";?>" placeholder="">
											</div>
											
											<div class="form-group col-md-3">
												<label class="col-form-label">Paid Days</label>
												<input type="text" name="paid_days" id="paid_days" class="form-control" value="<?php echo isset($edit_data[0]['paid_days']) ? $edit_data[0]['paid_days'] :"";?>" placeholder="">
											</div>
										
											<div class="form-group col-md-3">
												<label class="col-form-label">LOP Days</label>
												<input type="text" name="lop_days" id="lop_days" class="form-control" value="<?php echo isset($edit_data[0]['lop_days']) ? $edit_data[0]['lop_days'] :"";?>" placeholder="">
											</div>
											
										</div>
									</fieldset>
								</div>
								<!-- Customer Details end -->
							</div>
							<script>
																		
								$(document).ready(function()
								{
									
									$(
									"#monthly_salary,#tds,#monthly_wages,#salary,#numofdays,#num_of_days,#reimbursement_1,#reimbursement_2,#gross_wages,#pf_employer_deduction,#esi_employer_deduction,#total_deduction").on("input", function()
									{
										
										var monthlysalary = parseFloat( $("#monthly_salary").val() );
										var numofdays = parseFloat( $("#num_of_days").val() );
										var wagsalary = parseFloat( $("#salary").val() );
										var grosswages = parseFloat( $("#gross_wages").val() );
										var tds = parseFloat( $("#tds").val() );
										var pfemployerdeduction = parseFloat( $("#pf_employer_deduction").val() );
										var esiemployerdeduction = parseFloat( $("#esi_employer_deduction").val() );
										var reimbursement1 = parseFloat( $("#reimbursement_1").val() );
										var reimbursement2 = parseFloat( $("#reimbursement_2").val() );
										
										var wagesalary = Math.round(monthlysalary / 30) ;
										var monthlywages = Math.round(wagesalary * numofdays) ;
										var basicsalary = Math.round(monthlywages * 70 / 100) ;
										var allowance = Math.round(monthlywages - basicsalary) ;
										var pfdeduction = Math.round(basicsalary * 12 / 100) ;
										var esideduction = Math.round(monthlywages * 0.75 / 100) ;
										var pfemployerdeduction = Math.round(basicsalary * 13.5 / 100) ;
										var esiemployerdeduction = Math.round(grosswages * 3.75 / 100) ;
										var employercontribution = Math.round(pfemployerdeduction + esiemployerdeduction) ;
										var otherbenefits = Math.round(monthlywages * 10 / 100) ;
										var totaldeduction = Math.round(pfdeduction + esideduction + tds) ;
										var totreimbursement = Math.round(reimbursement1 + reimbursement2) ;
										var grossearnings = Math.round(basicsalary + allowance + otherbenefits) ;
										var netsalary = Math.round(grossearnings - totaldeduction + totreimbursement) ;

										$("#salary").val(wagesalary);
										$("#monthly_wages").val(monthlywages);
										$("#basic_salary").val(basicsalary);
										$("#allowance").val(allowance);
										$("#pf_deduction").val(pfdeduction);
										$("#esi_deduction").val(esideduction);
										$("#total_deduction").val(totaldeduction);
										$("#pf_employer_deduction").val(pfemployerdeduction);
										$("#esi_employer_deduction").val(esiemployerdeduction);
										$("#employer_contribution").val(employercontribution);
										$("#other_benefits").val(otherbenefits);
										$("#net_salary").val(netsalary);
										
									});
								});
								
							</script>
							
							<div class="d-flexad" style="text-align:right;">
								<a href="<?php echo base_url(); ?>employee/ManageEmployeeSalaryslip" class="btn btn-light">Cancel  </a>
								<?php 
									if($type == "edit")
									{
										?>
										<button type="submit" class="btn btn-primary ml-3">Update</button>
										<?php 
									}
									else
									{
										?>
										<button type="submit" class="btn btn-primary ml-2 register-but">Submit </button>
										<?php 
									}
								?>
							</div>
						</form>
					</div>
					<?php
				}
				else if (isset($type) && $type == "view")
				{	
					?>
					<div class="row">
						<!-- Start div class col-md-9 for Right side -->
						<div class="col-md-12 col-sm-12 col-xs-12 length-catgry=">
							<!-- General Information start -->
							<div class="container tab-pane active" id="tab_content1">	
								<div class="col-md-12">
									<div class="content-address-lft center-address" style="text-align:center;width: 98%;border: 1px solid;margin-left: 11px;border-bottom:0px; ">
										<img style="width:225px;height:75px;text-align:center;" src="<?php echo base_url(); ?>uploads/logo.png"/>
									</div>
									<div style="text-align:center;width: 98%;border: 1px solid;margin-left: 11px;padding:7px 0px;border-bottom:0px;">
										<?php echo ADDRESS1;?><br>
										<?php if(!empty(PHONE2)){?>Tel : <?php echo PHONE2;} ?><?php if(!empty(PHONE1)){?>, Mob : <?php echo PHONE1;?><?php } ?><br>			
									</div>
									<div style="text-align:center;width: 98%;border: 1px solid;margin-left: 11px;padding:7px 0px;border-bottom:0px;">
										Payslip for the Month of <b>
										<?php 
											if(!empty($edit_data[0]['created_date']))
											{
												echo date('M - Y',$edit_data[0]['created_date']);
											}
											else
											{
												echo date('M - Y');
											}
										?></b>
									</div>
								</div>
								<?php 
									$Gross_earnings = $edit_data[0]['other_benefits'] + $edit_data[0]['allowance'] + $edit_data[0]['basic_salary'];
								?>
								<?php 
									$totaldeductions =  $edit_data[0]['esi_deduction'] + $edit_data[0]['tds'] +  $edit_data[0]['pf_deduction'];
								?>
								<?php
									$toalReimbursement =  $edit_data[0]['reimbursement_1'] + $edit_data[0]['reimbursement_2'];
								?>
								<?php
									$netpayable = $Gross_earnings - $totaldeductions + $toalReimbursement;
								?>
								<div class="row" style="border:1px solid #000; margin:0px 20px;border-bottom:0px;">
									<div class="col-md-6" style="border-right:1px solid #000;border-bottom:0px;">
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;font-weight:600;">
											<div class="col-md-12">Employee Pay Summary</div>
										</div>
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">Employee Name</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo ucfirst($edit_data[0]['first_name']);?></div>
										</div>
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">Designation</div>
											<div class="col-md-1">:</div>
											<div class="col-md-6"><?php echo ucfirst($edit_data[0]['designation_name']);?></div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">Date of Joining</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo $edit_data[0]['date_of_joining'];?></div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">Pay Peroid</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo $edit_data[0]['pay_period'];?></div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">Pay Date</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo $edit_data[0]['pay_date'];?></div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">PF A/C Number</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo $edit_data[0]['bank_account_no'];?></div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;">
											<div class="col-md-4">ESI No</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo $edit_data[0]['esi_no'];?></div>
										</div>
										
										<div class="row"  style="line-height: 27px;border-bottom:0px;">
											<div class="col-md-4">UAN Number</div>
											<div class="col-md-1">:</div>
											<div class="col-md-5"><?php echo $edit_data[0]['uan_number'];?></div>
										</div>
									</div>
									<div class="col-md-6 mt-2">
										<div class="row" style="line-height: 74px;border-bottom:1px solid #000;text-align:center;">
											<div class="col-md-12">Employee Net Pay</div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;text-align:center;">
											<div class="col-md-12"><b><?php echo CURRENCY_SYMBOL?> <?php echo $netpayable;?></b></div>
										</div>
										
										<div class="row" style="line-height: 27px;border-bottom:1px solid #000;text-align:center;">
											<div class="col-md-6" style="border-right:1px solid #000;">Paid Days: <?php echo $edit_data[0]['paid_days'];?> </div>
											<div class="col-md-6">LOP Days: <?php echo $edit_data[0]['lop_days'];?> </div>
										</div>
										
										<div class="row mt-2">
											<div class="col-md-12"></div>
											
										</div>
									</div>
								</div>
								
								<div class="row" style="border:1px solid #000; margin:0px 20px;border-bottom:0px;border-right:0px;">
									<div class="col-md-3" style="border-right:1px solid #000;">
										<div class="row" style="line-height: 27px;">
											<div class="col-md-12" style="border-bottom:1px solid #000;text-align:center;">EARNNGS</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;font-weight:400;">Basic Salary</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;">Allowance</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;">Other Benefits</div>
											<div class="col-md-12">Gross Earnings</div>
										</div>
									</div>
									<div class="col-md-3" style="border-right:1px solid #000;">
										<div class="row" style="line-height: 27px;text-align:center;">
											<div class="col-md-12" style="border-bottom:1px solid #000;">AMOUNT</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['basic_salary'];?></div>
											<div class="col-md-12" style="border-bottom:1px solid #000;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['allowance'];?></div>
											<div class="col-md-12" style="border-bottom:1px solid #000;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['other_benefits'];?></div>
											<div class="col-md-12" style="font-weight:400;"><?php echo CURRENCY_SYMBOL; ?>
											<?php echo $Gross_earnings;	?>
											</div>
										</div>
									</div>
									<div class="col-md-3" style="border-right:1px solid #000;">
										<div class="row" style="line-height: 27px;">
											<div class="col-md-12" style="border-bottom:1px solid #000;text-align:center;">DEDUCTIONS</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;font-weight:400;">PF</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;font-weight:400;">TDS</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;font-weight:400;">ESI</div>
											<div class="col-md-12">Total Deductions</div>
										</div>
									</div>
									<div class="col-md-3" style="border-right:1px solid #000;">
										<div class="row" style="line-height: 27px;text-align:center;">
											<div class="col-md-12" style="border-bottom:1px solid #000;">AMOUNT</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['pf_deduction'];?></div>
											<div class="col-md-12" style="border-bottom:1px solid #000;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['tds'];?></div>
											<div class="col-md-12" style="border-bottom:1px solid #000;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['esi_deduction'];?></div>
											<div class="col-md-12"><?php echo CURRENCY_SYMBOL; ?>
											<?php echo $totaldeductions; ?>
											</div>
										</div>
									</div>
								</div>
								
								<div class="row" style="border:1px solid #000; border-bottom:0px;margin:0px 20px;">
									<div class="col-md-6" style="border-right:1px solid #000;">
										<div class="row" style="font-weight:400;line-height:27px;">
											<div class="col-md-12" style="border-bottom:1px solid #000;">REIMBURSEMENTS</div>
											<div class="col-md-6" style="border-right:1px solid #000;border-bottom:1px solid #000;">Reimbursement 1</div>
											<div class="col-md-6" style="border-bottom:1px solid #000;text-align:center;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['reimbursement_1'];?></div>
											<div class="col-md-6" style="border-bottom:1px solid #000;border-right:1px solid #000;">Reimbursement 2</div>
											<div class="col-md-6" style="border-bottom:1px solid #000;text-align:center;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $edit_data[0]['reimbursement_2'];?></div>
											<div class="col-md-6" style="border-right:1px solid #000;">Total Reimbursement</div>
											<div class="col-md-6" style="text-align:center;"><?php echo CURRENCY_SYMBOL; ?>
											<?php echo $toalReimbursement; ?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
									
										<div class="row" style="line-height: 27px;font-weight:600;border-bottom:1px solid #000;">
											<div class="col-md-6" style="border-right:1px solid #000;text-align:center;"></div>
											<div class="col-md-3" style="border-right:1px solid #000;font-weight:400;"></div>
											<div class="col-md-3" style="font-weight:400;color:#fff"> .</div>
										</div>
									
										<div class="row" style="line-height: 27px;font-weight:600;border-bottom:1px solid #000;">
											<div class="col-md-6" style="border-right:1px solid #000;text-align:center;"></div>
											<div class="col-md-3" style="border-right:1px solid #000;font-weight:400;"></div>
											<div class="col-md-3" style="font-weight:400;color:#fff"> .</div>
										</div>
										<div class="row" style="line-height: 27px;font-weight:600;">
											<div class="col-md-6" style="border-bottom:1px solid #000;border-right:1px solid #000;"></div>
											<div class="col-md-3" style="border-bottom:1px solid #000;border-right:1px solid #000;"></div>
											<div class="col-md-3" style="border-bottom:1px solid #000;color:#fff"> .</div>
										</div>
										<div class="row" style="line-height: 27px;font-weight:600;">
											<div class="col-md-6" style="border-right:1px solid #000;"></div>
											<div class="col-md-3" style="border-right:1px solid #000;"></div>
											<div class="col-md-3" style="color:#fff"> .</div>
										</div>
									</div>
									
								</div>
								<!---->
								<div class="row" style="border:1px solid #000; margin:0px 20px;">
									<div class="col-md-9" style="border-right:1px solid #000;">
										<div class="row" style="font-weight:400;line-height:27px;">
											<div class="col-md-12" style="border-bottom:1px solid #000;">NET PAY</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;">Gross Earning</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;">Total Deductions</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;">Total Reimbursements</div>
											<div class="col-md-12" style="text-align:center;">Total Net Payable</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="row" style="font-weight:400;line-height:27px;">
											<div class="col-md-12" style="border-bottom:1px solid #000;">AMOUNT</div>
											<div class="col-md-12" style="border-bottom:1px solid #000;text-align:right;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $Gross_earnings;?></div>
											<div class="col-md-12" style="border-bottom:1px solid #000;text-align:right;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $totaldeductions;?></div>
											<div class="col-md-12" style="border-bottom:1px solid #000;text-align:right;"><?php echo CURRENCY_SYMBOL; ?> <?php echo $toalReimbursement;?></div>
											<div class="col-md-12" style="text-align:right;"><?php echo CURRENCY_SYMBOL; ?>
											<?php echo $netpayable; ?></div>
										</div>
									</div>
								</div>
								<div class="row" style="border:1px solid #000;border-top:0px;border-right:0px; margin:0px 20px;">
									<div class="col-md-12" style="border-right:1px solid #000;">
										<div class="row" style="font-weight:400;line-height:27px;">
											<div class="col-md-12" style="border-bottom:0px;">Total Net Payable ₹<?php echo $netpayable;?> (<?php echo amountInWords($netpayable);?>)</div>
										</div>
									</div>
								</div>
							</div>
							
							<style>
								.disabled { cursor: not-allowed !important; }
								input[type=checkbox][disabled] {
								  outline: 2px solid #339966;
								  cursor: not-allowed;
								}
							</style>
						</div>
						<!-- End div class col-md-9 for Right side -->
					</div>
					<?php
				}
				else
				{ 
					?>
					<form action="" method="get">
						<section class="trans-section-back-1">
							<div class="row col-md-12">
								<div class="col-md-4">	
									<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
									<p style="font-size:12px;color:#8e8d8d !important;font-weight:600;">Note : Employee Name</p>
								</div>	
								<div class="col-md-4">
									<button type="submit" class="btn btn-success trans-saction-butt">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
							</div>
						</section>
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
					</form>
							
					
					<style>
						div#DataTables_Table_0_filter,#DataTables_Table_0_length {
							display: none;
						}
						div#DataTables_Table_0_info {
							display: none;
						}
						div#DataTables_Table_0_paginate {
							display: none;
						}
					</style>
					
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover table-striped dataTable">
							<thead>
								<tr>
									<th style="width:15%;">Controls</th>
									<th onclick="sortTable(1)" style="text-align:center; width:10%;">Emp Name</th>
									<th onclick="sortTable(2)">Total No of Days</th>
									<th class="tab-medium-width" onclick="sortTable(3)">Wages / Salary</th>
									<th onclick="sortTable(4)">Total montly wages</th>
									<th onclick="sortTable(5)">Basic Salary</th>
									<th onclick="sortTable(6)">Allowance</th>
									<th onclick="sortTable(7)">Other Benefits</th>
									<th onclick="sortTable(8)">Gross Wages</th>
									<th onclick="sortTable(9)">PF Employee Deductions</th>
									<th onclick="sortTable(10)">ESI Employee Deductions</th>
									<th onclick="sortTable(11)">PF Employer Deductions</th>
									<th onclick="sortTable(12)">ESI Employer Deductions</th>
									<th onclick="sortTable(13)">Total Deductions</th>
									<th onclick="sortTable(14)">Employer Contribution</th>
									<th onclick="sortTable(15)">Net Salary</th>
								</tr>
							</thead>
							<tbody>
								<?php 	
									$i=0;
									$firstItem = $first_item;
									$grandTotalwages = 0;
									$grandTotaldeductions = 0;
									$grandTotalnetsalary = 0;
									$grandTotalsalary = 0;
									$grandTotalmonthlywages = 0;
									$grandTotalbasicsalary = 0;
									$grandTotalallowance = 0;
									$grandTotalbenefits = 0;
									$grandTotalpfdeduction = 0;
									$grandTotalesideduction = 0;
									$grandTotalpfemployerdeduction = 0;
									$grandTotalesiemployerdeduction = 0;
									$grandTotalemployercontribution = 0;
									foreach($resultData as $row)
									{
										$grandTotalwages += $row['gross_wages'];
										$grandTotaldeductions += $row['total_deduction'];
										$grandTotalnetsalary += $row['net_salary'];
										$grandTotalsalary += $row['salary'];
										$grandTotalmonthlywages += $row['monthly_wages'];
										$grandTotalbasicsalary += $row['basic_salary'];
										$grandTotalallowance += $row['allowance'];
										$grandTotalbenefits += $row['other_benefits'];
										$grandTotalpfdeduction += $row['pf_deduction'];
										$grandTotalesideduction += $row['esi_deduction'];
										$grandTotalpfemployerdeduction += $row['pf_employer_deduction'];
										$grandTotalesiemployerdeduction += $row['esi_employer_deduction'];
										$grandTotalemployercontribution += $row['employer_contribution'];
										?>
										<tr>
											<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
											-->
											<td>
												<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
													<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmployeeSalaryslip/edit/<?php echo $row['emp_salaryslip_id'];?>">
																<i class="fa fa-pencil"></i> Edit
															</a>
														</li>
														<li>
															<a title="View" href="<?php echo base_url(); ?>employee/ManageEmployeeSalaryslip/view/<?php echo $row['emp_salaryslip_id'];?>">
																<i class="fa fa-eye"></i> View
															</a>
														</li>
														
														<?php
														/* <li>
															<a class="btn btn-light btn-class" title="Delete" href="<?php echo base_url();?>customers/ManageCustomers/delete/<?php echo $row['user_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																<i class="icon-trash"></i>
															</a> 
														</li> */ ?>
													</ul>
													<?php /* <a title="Change Password" href="#" data-toggle="modal" style="float: right;padding-top: 7px;position: absolute; margin-left: 8px; padding-right: 10px;" data-target="#exampleModal<?php echo $row['user_id'];?>">
														<i class="icon-lock"></i>
													</a> */ ?>
												</div>
											</td>
											
											<td style="text-align:center;" class="tab-medium-width"><?php echo ucfirst($row['first_name']);?> - <?php echo ucfirst($row['random_user_id']);?></td>
											<td class="tab-medium-width"><?php echo $row['num_of_days'];?></td>
											<td><?php echo CURRENCY_SYMBOL;?> <?php echo $row['salary'];?></td>
											<?php /* <td class="tab-medium-width"><?php echo ucfirst($row['first_name'])." ".ucfirst($row['middle_name'])." ".ucfirst($row['last_name']);?></td> */ ?>
											
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['monthly_wages'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['basic_salary'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['allowance'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['other_benefits'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['gross_wages'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['pf_deduction'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['esi_deduction'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['pf_employer_deduction'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['esi_employer_deduction'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['total_deduction'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['employer_contribution'];?></td>
											<td  class="tab-medium-width"><?php echo CURRENCY_SYMBOL;?> <?php echo $row['net_salary'];?></td>
										</tr>
										
										<?php 
										$i++;
									}
								?>
							</tbody>
							<tr>
								<td style="text-align:right" colspan="4"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalsalary,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalmonthlywages,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalbasicsalary,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalallowance,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalbenefits,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalwages,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalpfdeduction,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalesideduction,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalpfemployerdeduction,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalesiemployerdeduction,DECIMAL_VALUE,'.','');?></b>
								</td>
								
								<td style="text-align:center;" colspan="1"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotaldeductions,DECIMAL_VALUE,'.','');?></b>
								</td>
								<td style="text-align:center;"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalemployercontribution,DECIMAL_VALUE,'.','');?>
								</td>
								<td style="text-align:center;"><b><?php echo CURRENCY_SYMBOL;?>
									<?php echo number_format($grandTotalnetsalary,DECIMAL_VALUE,'.','');?>
								</td>
							</tr>
							
						</table>
						<?php 
							if(count($resultData) == 0)
							{
								?>
								<p class="admin-no-data">No data found.</p>
								<?php 
							} 
						?>
					</div>
					
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
		</div><!-- Card end-->
		<?php if(isset($type) && $type =='view'){?>
			<a href='<?php echo base_url();?>employee/ManageEmployeeSalaryslip' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
		<?php } ?>
	</div><!-- Content end-->
	
<!-- View Popup Image-->	
<link href="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/backend/view_gallery/jquery.magnify.js"></script>
<script>
   /*  $('[data-magnify]').magnify({
      fixedContent: true
    }); */
</script>
<!-- View Popup Image-->
