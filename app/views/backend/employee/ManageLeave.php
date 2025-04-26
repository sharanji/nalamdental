<?php
	$employee = accessMenu(employee);
?>

<!-- Page header start-->
<div class="page-header page-header-light">
	
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageLeave" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		<?php
			if( isset($type) && $type == "add" || $type == "edit" )
			{ 
				
			}
			else
			{ 
				if($employee['create_edit_only'] == 1 || $this->user_id == 1)
				{
					?>
					<div class="new-import-btn" style="float:right;">
						<?php /* <a title="Download CSV" href="<?php echo base_url(); ?>employee/ManageLeave/export" class="btn btn-primary">
							<i class="fa fa-download"></i> Download CSV
						</a> */ ?>
					
						<a href="<?php echo base_url(); ?>employee/ManageLeave/add" class="btn btn-info">
							Add Employee Leave
						</a>
					</div>
					<?php 
				}				
			} 
		?>
	</div>
</div>
<!-- Page header end-->

	<div class="content"><!-- Content start-->
		<div class="card"><!-- Card start-->
			<div class="card-body">
				<?php
					if(isset($type) && $type == "add" || $type == "edit")
					{
						?>
						<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
						<legend class="text-uppercase font-size-sm font-weight-bold">
							<?php echo $type; ?> Employee Leave :
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
									
									<div class="form-group col-md-3">
										<label class="col-form-label">Leave Days <span class="text-danger">*</span></label>
										<input type="text" name="leave_days" required <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['leave_days']) ? $edit_data[0]['leave_days'] :"";?>" placeholder="">
									</div>
									*/ ?>
									
									<?php 
									$empRequired = "required";
									$textStar = '<span class="text-danger">*</span>';
									
									if(isset($type) && $type == "edit")
									{
										$empRequired = "";
										$textStar ="";
									}
									?>
									<div class="form-group col-md-3">
										<label class="col-form-label">Employee Code <?php echo $textStar;?></label>
										<input type="text" name="employee_name" id="employee_name" <?php echo $empRequired;?> value="" autocomplete="off" class="form-control">
										<div id="employeeList"></div>
										<input type="hidden" name="user_id" id="user_id" value="" class="form-control">
									</div>
									<style>
										ul.list-unstyled {
											background: whitesmoke;
											border: 1px solid #c1c1c1;
										}
										ul.list-unstyled li {
											margin-top: 5px;
											cursor: pointer;
											padding: 9px 0px 6px 11px;
										}
										ul.list-unstyled li:hover {
											background-color: #0f6475;
											color: white;
											padding: 9px 0px 6px 11px;
										}
									</style>
									
								</div>
								
								
								<?php /* <div class="row">
									<div class="form-group col-md-3">
										<label class="col-form-label">Period Start Date<span class="text-danger">*</span></label>
										<input type="text" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">
									</div>
									<div class="form-group col-md-3">
										<label class="col-form-label">Period End Date<span class="text-danger">*</span></label>
										<input type="text" name="string_to_date" id="string_to_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
									</div>
								</div> */ ?>
								<input type="hidden" name="string_from_date" id="string_from_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_from_date']) ? date('d-M-Y',$edit_data[0]['string_from_date']) :date('d-M-Y');?>">
								<input type="hidden" name="string_to_date" id="string_to_date" readonly class="form-control default_date" required value="<?php echo isset($edit_data[0]['string_to_date']) ? date('d-M-Y',$edit_data[0]['string_to_date']) :date('d-M-Y');?>">
								<?php /*
								<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageLeave" class="btn btn-danger">Cancel  </a>
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
								*/ ?>
								
								<!-- Table start here-->
								<div class="row">
									<div class="col-md-12">
										<div style="overflow-y: auto;">
											<div id="err_product" style="color:red;margin: 0px 0px 8px 0px;"></div>
											<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
												<thead>
													<tr>
														<th colspan="10">Employee Leave</th>
													</tr>
													<tr>
														<th></th>
														<th class="text-center">Employee Code</th>
														<th>Employee Name</th>
														<th class="text-center">Leave</th>
													</tr>
												</thead>
												
												<tbody id="product_table_body">
													<?php 
														if(isset($type) && $type == "edit")
														{
															$employeeLeaveQry = "select 
																emp_leaves_line.*,
																users.user_id,
																users.first_name,
																users.last_name,
																users.random_user_id
																
																from emp_leaves_line 
															
															left join emp_leave_header on 
																emp_leave_header.header_id = emp_leaves_line.header_id	
																
															left join users on 
																users.user_id = emp_leaves_line.user_id		
																
															where 
																emp_leaves_line.line_id='".$id."' and
																emp_leaves_line.user_id='".$status."'
																";
															$employeeLeave = $this->db->query($employeeLeaveQry)->result_array();
															
															if(  count($employeeLeave) > 0 )
															{
																$counter=1;
																foreach($employeeLeave as $row)
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
																			<input type="text" name="leave_days[]" required="" class="form-control" autocomplete="off" id="leave_days<?php echo $counter; ?>" value="<?php echo $row["emp_leave"];?>">
																		</td>
																	</tr>
																	<?php
																	$counter++;
																}
															}
														} 
													?>
							
												</tbody>
											</table>
										</div>
										<input type="hidden" name="table_data" id="table_data">
									</div>
								</div>
								<!-- Table start here-->
								
								<div class="d-flexad text-right mt-4">
									<a href="<?php echo base_url(); ?>employee/ManageLeave/grid_view" class="btn btn-light">Cancel  </a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" class="btn btn-primary waves-effect ml-2">Update</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" class="btn btn-info waves-effect ml-2">Submit</button>
											<?php 
										}
									?>
								</div>
								
							</form>
							
							<script> 
																		
								$(document).ready(function()
								{  
									$('#employee_name').keyup(function()
									{  
										var query = $(this).val();  
										
										if(query != '')  
										{  
											$.ajax({  
												url:"<?php echo base_url();?>employee/employee_nameAjaxSearch",  
												method:"POST",  
												data:{query:query},  
												success:function(data)  
												{  
													$('#employeeList').fadeIn();  
													$('#employeeList').html(data);  
												}  
											});  
										}  
									});
									
									$(document).on('click', '.list-unstyled li', function()
									{  
										var value = $(this).text();
										if(value === "Sorry! Employee Not Found.")
										{
											$('#employee_name').val("");  
											$('#employeeList').fadeOut();
										}
										else
										{
											$('#employee_name').val(value);  
											$('#employeeList').fadeOut();  
										}
									});
								}); 
								

								function getuserId(user_id)
								{	
									$('#user_id').val(user_id);
									
									var id = user_id;
									var dssid = user_id;
									$('#err_product').text('');
									var flag = 0;
									var counter = 1;
									var i = 1;
									
									if(id >= 0 )
									{
										$.ajax({
											url: "<?php echo base_url('employee/employeeList') ?>/"+id,
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
																"user_id"              : id,
																"employee_leaves_line"  : '0',
															};                  

															product_data[i] = product;
															length = product_data.length - 1 ;
															
															var newRow = $("<tr class='dataRowVal"+id+" table_rows'>");
															var cols = "";
															cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='user_id[]' value="+id+"></td>";
															cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
															cols += "<td class='tab-medium-width'>"+first_name+"</td>";
															cols += "<td class='tab-medium-width text-center'>"
																+"<input type='text' name='leave_days[]' required class='form-control' autocomplete='off' id='leave_days"+counter+"' value='<?php echo isset($edit_data[0]['leave_days']) ? $edit_data[0]['leave_days'] :"";?>'>"
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
									//var array_id = product_data[id].product_id;
									//product_data.splice(id, 1);
									product_data[id] = null;
									//alert(product_data);
									var table_data = JSON.stringify(product_data);
									$('#table_data').val(table_data);
								}
								
							</script>
							
						<?php
					}
					else
					{ 
						?>
						<form action="" method="get">
							<div class="row">
								<div class="col-md-8">
									<?php /* <div class="row mb-2">
										<div class="col-md-4">	
											<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] :""; ?>" placeholder="From Date">
										</div>
										
										<div class="col-md-4">	
											<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
										</div>
									</div> */ ?>
									<div class="row">
										<div class="col-md-3">	
											<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
											<p class="search-note">Note : Employee Code</p>
										</div>	

										<div class="col-md-3">	
											<?php 
												$financialQry = "select 
													org_financial_years.*
													
													from org_financial_years 

												join emp_leaves_line on 
													emp_leaves_line.financial_year_id = org_financial_years.financial_year_id
												
													where org_financial_years.financial_status = 1
												
												group by emp_leaves_line.financial_year_id
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

										<div class="col-md-3">	
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

														join emp_leaves_line on 
															emp_leaves_line.period_id = emp_periods.period_id

														where 
															emp_periods.period_status = 1 and 
																emp_periods.financial_year_id = '".$_GET["financial_year_id"]."'
														
														group by emp_leaves_line.period_id
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

										<div class="col-md-3">
											<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										</div>
									</div>

								</div>
								<div class="col-md-4 text-right">
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
							<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
								<thead>
									<tr>
										<th class="text-center">Controls</th>
										<th>Financial Year</th>
										<th class="text-center">Period</th>
										<th class="text-center">Employee Code</th>
										<th>Employee Name</th>
										<th class="text-center">Leave Days</th>
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
												<td class="text-center">
													<div class="dropdown controls-actions show">
														<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true" style="width: 70px;">
															Action <i class="fa fa-angle-down"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-right">
															<li>
																<a title="Edit" href="<?php echo base_url(); ?>employee/ManageLeave/edit/<?php echo $row['line_id'];?>/<?php echo $row['user_id'];?>">
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
												</td>

												<td class="tab-medium-width">
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
														#$currentMonth = date('m');
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
												<td class="tab-medium-width">
													<?php echo ucfirst($row['first_name']);?>
													<?php echo ucfirst($row['last_name']);?>
												</td>
												<td class="tab-medium-width text-center"><?php echo $row['emp_leave'];?></td>
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
										<div class="col-md-12 float-left text-center"> 
											<img src="<?php echo base_url(); ?>uploads/no-data.png">
											<span style="float:left;width:100%;">No Data Found</span>
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
	
