<?php
	$manageExpensesMenu = accessMenu(manage_expenses);
?>	
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view"){
						$fieldSetDisabled = "disabled";
						#$dropdownDisabled = "style='pointer-events: none;'";
						$searchDropdown = "";
					}else{
						$fieldSetDisabled = "";
						#$dropdownDisabled = "";
						$searchDropdown = "searchDropdown";
					}

					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
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
										Expense
									</b>
								</h3>
							</div>
							<div class="col-md-6 text-right">
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" class="btn btn-primary btn-sm">Save</button>
										<button type="submit" name="submit_btn" class="btn btn-primary btn-sm">Submit</button>
										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>expense/ManageExpense" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
						<!-- Buttons end here -->

						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<div class="row">
								<div class="col-md-4">
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Date <span class="text-danger">*</span></label>
										<div class="form-group col-md-7">
										<input type='text' name="expense_date" required id="expense_date" value='<?php echo isset($edit_data[0]['expense_date']) ? date("d-M-Y",strtotime($edit_data[0]['expense_date'])) : date("d-M-Y");?>' readonly class="form-control future_date">
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Description</label>
										<div class="form-group col-md-7">
											<textarea name="header_description" rows='1' id="header_description"class="form-control"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] : NULL;?></textarea>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Expense No.</label>
										<div class="form-group col-md-7">
											<input type='text' name="expense_number" readonly id="expense_number" value='<?php echo isset($edit_data[0]['expense_number']) ? $edit_data[0]['expense_number'] : NULL;?>' class="form-control">
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="row">
										<label class="col-form-label col-md-4 text-right">Status</label>
										<div class="form-group col-md-7">
											<input type="text" name="header_status" id="header_status" readonly autocomplete="off" class="form-control no-outline" value="<?php echo isset($edit_data[0]['expense_status']) ? $edit_data[0]['expense_status'] : "Draft";?>" placeholder="">
										</div>
									</div>
								</div>
							</div>

							<?php 
								if($type == "view")
								{
									
								}
								else
								{
									?>
									<a href="javascript:void(0);" id="addExpense" class="btn btn-primary btn-sm">
										<i class="fa fa-plus"></i> Add
									</a>
									<?php
								}
							?>

							<div class="row mt-1">
								<div class="col-sm-12">
									<div class="form-group">
										<div style="overflow-y: auto;">
											<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
											<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
												<thead>
													<tr>
														<th colspan="13">Expense Items</th>
													</tr>
													<tr>
														<?php 
															if($type == "view")
															{
																
															}
															else
															{
																?>
																<th style="width:30px;">Action</th>
																<?php
															}
														?>
														<th class="text-center">Expense Type <span class="text-danger">*</span></th>
														<th class="text-center">Category <span class="text-danger">*</span></th>
														<th class="text-center">Payment Method <span class="text-danger">*</span></th>
														<th class="text-center">Reference ID</th>
														<th class="text-right">Amount ( <?php echo CURRENCY_SYMBOL;?> ) <span class="text-danger">*</span></th>
														<th class="text-center">Remarks <span class="text-danger">*</span></th>
														<th class="text-center tab-md-100">Status</th>
														<th class="text-center">Attachment</th>
													</tr>
												</thead>
												<tbody id="product_table_body">
													<?php
														$totalCost=0;
														if( isset($type) && $type == "edit" || $type == "view")		
														{
															$expenseQry = "select expense_line.* from expense_line 
															
															where expense_line.header_id ='".$id."' ";

															$expenseItems = $this->db->query($expenseQry)->result_array();
															
															if( count($expenseItems) > 0)
															{
																/* $query = "select particular_name,particular_id from expense_particulars
																		where particular_status=1 order by particular_id desc";
																$getExpenseCategory = $this->db->query($query)->result_array(); */

																$query1 = "select type_id,type_name from expense_type
																			where type_status=1 order by type_name ASC";
																$getExpenseType = $this->db->query($query1)->result_array();

																$query2 = "select payment_type,payment_type_id from pay_payment_types
																		where active_flag='Y' order by payment_type ASC";
																$getPaymentType = $this->db->query($query2)->result_array();

																$i=0;
																$counter=1;
																foreach($expenseItems as $row)
																{
																	$getExpenseCategory =  $this->db->query("select expense_particulars.* from expense_particulars
																	where expense_type_id='".$row["expense_type_id"]."' and particular_status = 1 order by particular_name asc
																	")->result_array();
		

																	?>
																	<tr>
																		<?php 
																			if($type == "view")
																			{
																				
																			}
																			else
																			{
																				?>
																				<td class="text-center"><a class='deleteRow1'><i class="fa fa-trash"></i></a></td>
																				<?php
																			}
																		?>
																		
																		<td class="tab-md-150">
																			<select class='form-control' required onchange="selectExpenseCategory(this.value,<?php echo $counter;?>);" name='expense_type_id[]' id='expense_type_id<?php echo $counter;?>'>
																				<option value=''>- Select -</option>
																				<?php
																					foreach($getExpenseType as $category)
																					{	
																						$selected= '';
																						if($row["expense_type_id"] == $category['type_id'])
																						{
																							$selected="selected='selected'";
																						}
																						?>
																						<option value='<?php echo $category['type_id'];?>' <?php echo $selected;?>><?php echo ucfirst($category['type_name'])?></option>;
																						<?php
																					}
																				?>
																			</select>
																		</td>

																		<td class="tab-md-150">
																			<select class='form-control' required name='category_id[]' id='category_id<?php echo $counter;?>'>
																				<option value=''>- Select -</option>
																				<?php
																					foreach($getExpenseCategory as $category)
																					{	
																						$selected= '';
																						if($row["category_id"] == $category['particular_id'])
																						{
																							$selected="selected='selected'";
																						}
																						?>
																						<option value='<?php echo $category['particular_id'];?>' <?php echo $selected;?>><?php echo $category['particular_name'];?></option>
																						<?php
																					}
																				?>
																			</select>
																		</td>

																		
																		<td class="tab-md-150">
																			<select class='form-control' required name='payment_type_id[]' id='payment_type_id<?php echo $counter;?>'>
																				<option value=''>- Select -</option>
																				<?php
																					foreach($getPaymentType as $category)
																					{	
																						$selected= '';

																						if($row["payment_type_id"] == $category['payment_type_id'])
																						{
																							$selected="selected='selected'";
																						}
																						?>
																						<option value='<?php echo $category['payment_type_id'];?>' <?php echo $selected;?>><?php echo $category['payment_type'];?></option>;
																						<?php
																					}
																				?>
																			</select>
																		</td>

																		<td class="tab-md-150"><input type="text" name='reference_id[]' id='reference_id<?php echo $counter;?>' value='<?php echo $row["reference_id"];?>' class='form-control'></td>
																		
																		<td class="tab-md-150">
																			<input type='text' name='expense_cost[]' required id='expense_cost_<?php echo $counter;?>' value="<?php echo number_format($row['expense_cost'],DECIMAL_VALUE,'.','');?>" class='form-control text-right' autocomplete='off'>
																		</td>
																		
																		<td class="tab-md-150"><textarea name='description[]' required id='description<?php echo $counter;?>' rows='1' class='form-control' autocomplete='off'><?php echo $row["description"];?></textarea></td>
																		<td class="tab-md-100">
																			<input type="text" class="form-control single_quotes" readonly name="line_status[]" id="line_status<?php echo $counter;?>" value="<?php echo $row["line_status"];?>">
																		</td>
																		<td class="tab-md-150">
																			<input type='file' name='upload_document[]' id='first_<?php echo $counter;?>' class='form-control' onchange='return validateFileExtension(this,"<?php echo $counter;?>")'>
																			<input type='hidden' name='image_2[]' value="<?php echo $row["upload_document"];?>" id='image_<?php echo $counter;?>' class='form-control'>
																			
																			<?php 
																				if( file_exists("uploads/expense_documents/".$row["upload_document"]) && !empty($row["upload_document"]))
																				{
																					?>
																					<a href="<?php echo base_url();?>uploads/expense_documents/<?php echo $row["upload_document"];?>" download>Download</a>		
																					<?php 
																				} 
																			?>
																		</td>
																	</tr>
																	
																	<?php /*
																	<script>
																		$("#expense_date<?php echo $counter;?>").datepicker({
																			changeMonth: true,
																			changeYear: true,
																			yearRange: "1950:<?php echo date('Y'); ?>",
																			dateFormat: "d-M-yy"
																			//dateFormat: "dd-M-yy"
																			//dateFormat: "yy-mm"											
																			//dateFormat: "mm-yy"											
																		});
																	</script>
																	*/ ?>

																	<?php 
																	$counter++;
																	$i++;
																	$totalCost +=$row['expense_cost'];
																} 
															} 
														} 
													?>
												</tbody>
											</table>
										</div>

										<input type="hidden" name="total_value" id="total_value" value="<?php echo number_format($totalCost,DECIMAL_VALUE,'.','');?>">
										<table class="table table-bordered table-condensed table-hover">
											<tr>
												<td colspan="9" class="text-right total-expense-amount" style="width:75%;"><b>Total Amount ( <?php echo CURRENCY_SYMBOL;?> ) </b></td>
												<td class="text-right" style="width:25%;"><span id="totalValue">&nbsp;<?php echo number_format($totalCost,DECIMAL_VALUE,'.','');?></span></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad" style="float:right;">
							<?php 
								if($type == "add" || $type == "edit")
								{
									?>
									<button type="submit" name="save_btn" class="btn btn-primary btn-sm">Save</button>
									<button type="submit" name="submit_btn" class="btn btn-primary btn-sm">Submit</button>
									<?php 
								} 
							?>
							<a href="<?php echo base_url(); ?>expense/ManageExpense" class="btn btn-default btn-sm">Close</a>
						</div>
					</form>

					<script>
						$(document).ready(function()
						{
							var type = '<?php echo $type;?>';
							
							if( type == 'add' )
							{
								var i = 0;
								var product_data = new Array();
								var counter = 1;
							}
							else
							{
								var counter1 = '<?php echo isset($expenseItems) ? count($expenseItems) + 1 : 1; ?>';
								
								if(counter1 == 0)
								{
									var i = 0;
									var product_data = new Array();
									var counter = 1;
								}
								else
								{
									var i = '<?php echo isset($i) ? $i++ : "0"; ?>';
									var product_data = new Array();
									var counter = '<?php echo isset($expenseItems) ? count($expenseItems) +1 : 1; ?>';
								}
							}
							
							$('#addExpense').click(function()
							{
								var id = 1;
								$('#err_product').text('');
								var flag = 0;
								
								if(id != "")
								{
									$.ajax({
										url: "<?php echo base_url('expense/getExpenseCategory') ?>/"+id,
										type: "GET",
										data:{
											'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
										},
										datatype: "JSON",
										success: function(d)
										{
											data = JSON.parse(d);
											/* $("table.product_table").find('input[name^="category_id"]').each(function () 
											{
												if(data[0].category_id  == +$(this).val())
												{
													flag = 1;
												}
											}); */

											//Expense type
											var select_expense_type = "";
											select_expense_type += '<div class="form-group">';
											select_expense_type += '<select class="form-control searchDropdown" onchange="selectExpenseCategory(this.value,'+counter+');" id="expense_type_id'+counter+'" name="expense_type_id[]">';
											select_expense_type += '<option value="">- Select -</option>';
											for(a=0;a<data['expenseType'].length;a++)
											{
												select_expense_type += '<option value="' + data['expenseType'][a].type_id + '">' + data['expenseType'][a].type_name+ '</option>';
											}
											select_expense_type += '</select></div>';


											//Expense Category
											var select_category = "";
											select_category += '<div class="form-group">';
											select_category += '<select class="form-control searchDropdown" id="category_id'+counter+'" name="category_id[]">';
											select_category += '<option value="">- Select -</option>';
											
											select_category += '</select></div>';
											
											if(flag == 0)
											{
												var expenseDate = '<?php echo date("d-M-Y");?>';
												
												/* var expenseType = data['expenseType'];
												var expenseCategory = data['expenseCategory']; */

												var paymentType = data['paymentType'];
												
												var newRow = $("<tr class='dataRowVal"+id+"'>");
												var cols = "";
												cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"></td>";
												
												cols += "<td class='tab-md-150'>"+select_expense_type+"</td>";

												cols += "<td class='tab-md-150'>"+select_category+"</td>";

												cols += "<td class='tab-md-150'>"+paymentType+"</td>";

												cols += "<td class='tab-md-150'>"
														+"<input type='text' name='reference_id[]' id='reference_id"+ counter +"' class='form-control' autocomplete='off'>"
													+"</td>";

												cols += "<td class='tab-md-150'>"
														+"<input type='number' name='expense_cost[]' id='expense_cost"+ counter +"' required class='form-control text-right' autocomplete='off'>"
													+"</td>";

												cols += "<td class='tab-md-150'>"
														+"<textarea name='description[]' required id='description"+ counter +"' required rows='1' class='form-control' autocomplete='off'></textarea>"
													+"</td>";

												cols += "<td class='tab-md-100'>" 
														+"<input type='text' class='form-control single_quotes' readonly name='line_status[]' id='line_status"+ counter +"' value='Draft'>"
													+"</td>";

												cols += "<td class='tab-md-150'>"
														+"<input type='file' name='upload_document[]' id='upload_document"+ counter +"' class='form-control' onchange='return validateFileExtension(this,"+ counter +")'>"
													+"</td>";
													
												cols += "</tr>";
												
												newRow.html(cols);
												$("table.product_table").append(newRow);
												
												$(document).ready(function()
												{ 
													$(".searchDropdown").select2();
												});

												/* $("#expense_date"+counter).datepicker({
													changeMonth: true,
													changeYear: true,
													yearRange: "1950:<?php #echo date('Y'); ?>",
													dateFormat: "d-M-yy"
													//dateFormat: "dd-M-yy"
													//dateFormat: "yy-mm"											
													//dateFormat: "mm-yy"											
												}); */

												counter++;
												i++;
											}
											else
											{
												$('#err_product').text('Document Already Exist!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										},
										error: function(xhr, status, error) 
										{
											$('#err_product').text('Select Document / Name!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
										}
									});
								}
							});
							
							$("table.product_table").on("click", "a.deleteRow,a.deleteRow1", function (event) 
							{
								//$(this).closest("tr").remove();
								//deleteRow($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							});
							
							$("table.product_table").on("input keyup change", 'input[name^="expense_cost[]"]', function (event) 
							{
								var row = $(this).closest("tr");
								var id = +row.find('input[name^="id"]').val();
								var counter = +row.find('input[name^="counter"]').val();
								
								//calculateRow($(this).closest("tr"));
								//calculateDiscountTax(row,"0","0"); //,expiry_date
								calculateGrandTotal();		
							});
							
							function calculateGrandTotal() 
							{
								var totalValue = 0;
								
								$("table.product_table").find('input[name^="expense_cost[]"]').each(function () 
								{
									totalValue += +$(this).val();
								});
								
								$('#totalValue').text(totalValue.toFixed(2));
								$('#total_value').val(totalValue.toFixed(2));	
							}

							/* $("table.product_table").on("click", "a.deleteRow1", function (event) 
							{
								deleteRow1($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							}); */
						});

						function selectExpenseCategory(val,counter)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'expense/ajaxSelectExpenseCategory';?>",
									data: { id : val }
								}).done(function( result ) 
								{   
									$("#category_id"+counter).html(result);
								});
							}
							else 
							{ 
								$("#category_id"+counter).html("<option value=''>- Select -</option>");
							}
						}
					</script>
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
								if($manageExpensesMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>expense/ManageExpense/add" class="btn btn-info btn-sm">
										Create Expense
									</a>
									<?php 
								} 
							?>
						</div>
					</div>
					<!-- Buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Expense No.</label>
									<div class="form-group col-md-7">
										<input type="search" name="expense_no" id="expense_no" class="form-control" value="<?php echo !empty($_GET['expense_no']) ? $_GET['expense_no'] :""; ?>" placeholder="Expense No.">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Expense Status</label>
									<div class="form-group col-md-7">
										<?php 
											$expenseStatusQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
											left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
											where 
	
											sm_list_types.active_flag='Y' and 
											coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
											coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
											sm_list_types.deleted_flag='N' and
	
	
											sm_list_type_values.active_flag='Y' and 
											coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
											coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
											sm_list_type_values.deleted_flag='N' and 
	
											sm_list_types.list_name = 'EXPENSE-STATUS'
											order by sm_list_type_values.order_sequence asc";
	
											$expenseStatusQry = $this->db->query($expenseStatusQry)->result_array(); 
										?>
										
										<select name="expense_status" id="expense_status" class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($expenseStatusQry as $row)
												{
													$selected="";
													if(isset($_GET['expense_status']) && $_GET['expense_status'] == $row["list_value"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["list_value"];?>" <?php echo $selected;?>><?php echo ucfirst($row["list_value"]);?></option>
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
									<label class="col-form-label col-md-4 text-right">From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control from_date" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] :""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control to_date" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>
							
							<div class="col-md-2" --style="padding:0px 4px 2px 77px;">
								<button type="submit" class="btn btn-info ">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>expense/ManageExpense" title="Clear" class="btn btn-default">Clear</a>
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
								<div class="col-md-10 mt-3">
									<?php 
										if( isset($lineData) && count($lineData) > 0 )
										{
											?>
											<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a>
											<?php 
										} 
									?>
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
							<div class="new-scroller mt-3">
								<table --id="myTable" class="table table-bordered -sortable-table table-hover --table-striped --dataTable">
									<thead>
										<tr>
											<th style="text-align:center;width:12%;">Controls</th>
											<th class="text-center">Status</th>
											<th>Expense No.</th>
											<th>Expense Status</th>
											<th>Expense Date </th>
											<th class="text-right">Amount (<?php echo CURRENCY_SYMBOL;?>)</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=0;
											$firstItem = $first_item;
											$expense_cost=0;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td style="text-align:center;">
														<div class="dropdown">
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action &nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<?php
																	if($manageExpensesMenu['create_edit_only'] == 1 || $manageExpensesMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($manageExpensesMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<?php 
																					if($row['expense_status'] == "Pending Approval" || $row['expense_status'] == "Info Requested")  #Pending Approval
																					{
																						?>
																						<li>
																							<a onclick="return confirm('Approval will be withdrawn need to submit for approval again, do you want to continue?')" href="<?php echo base_url(); ?>expense/ManageExpense/edit/<?php echo $row['header_id'];?>/pending_approval">
																								<i class="fa fa-pencil"></i> Edit
																							</a>
																						</li> 
																						<?php
																					}
																					else if($row['expense_status'] == "Approved")
																					{
																						?>
																						<li>
																							<a onclick="return confirm('Expense will go for re-approval, do you want to continue?')" href="<?php echo base_url(); ?>expense/ManageExpense/edit/<?php echo $row['header_id'];?>/re_request">
																								<i class="fa fa-pencil"></i> Edit
																							</a>
																						</li>
																						<?php
																					}
																					else if($row['expense_status'] == "Draft" || $row['expense_status'] == "Withdrawn" || $row['expense_status'] == "Rejected")
																					{
																						?>
																						<li>
																							<a href="<?php echo base_url(); ?>expense/ManageExpense/edit/<?php echo $row['header_id'];?>/re_request">
																								<i class="fa fa-pencil"></i> Edit
																							</a>
																						</li>
																						<?php
																					}
																				?>
																				<?php 
																			} 
																		?>

																		<?php
																			if($manageExpensesMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>expense/ManageExpense/view/<?php echo $row['header_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>

																				<li>
																					<a target="_blank" href="<?php echo base_url(); ?>expense/viewApprovals/<?php echo $row['header_id'];?>">
																						<i class="fa fa-eye"></i> View Approval
																					</a>
																				</li>
																				<?php 
																			} 
																		?>
																		<?php 
																	}
																?>
																
																
																<?php
																	/* if( (isset($expense['create_edit_only']) && $expense['create_edit_only'] == 1) || $this->user_id == 1)
																	{
																		if ($row['expense_status'] == 2)  #Inprogress
																		{
																			
																		}
																		else if($row['expense_status'] != 3) #if approved no need to approved
																		{
																			if($row['expense_status'] == 7)  #Rejected
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>expense/ManageExpense/edit/<?php echo $row['expense_id'];?>/re_request">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li>
																				<?php
																			}
																			else if($row['expense_status'] != 4 and $row['expense_status'] != 5)  #close & cancelled
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>expense/ManageExpense/edit/<?php echo $row['expense_id'];?>">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li>
																				<?php
																			}
																		}
																	}
																
																	if((isset($expense['read_only']) && $expense['read_only'] == 1) || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<a href="<?php echo base_url(); ?>expense/ManageExpense/view/<?php echo $row['expense_id'];?>">
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li>
																		<?php 
																	}

																	if((isset($expense['read_only']) && $expense['read_only'] == 1) || $this->user_id == 1)
																	{ 
																		?>
																		<li>
																			<a target="_blank" href="<?php echo base_url(); ?>expense/viewApprovals/<?php echo $row['expense_id'];?>">
																				<i class="fa fa-eye"></i> View Approval
																			</a>
																		</li>
																		<?php 
																	} */ 
																?>

																
															</ul>
														</div>
													</td>

													<td  class='text-center' style="width:90px;">
														<?php
															if($manageExpensesMenu['create_edit_only'] == 1 || $this->user_id == 1)
															{
																?>
																<div class="dropdown" style="width:90px;">
																	<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																		- Status -
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<?php 
																			foreach($expenseStatusQry as $line_row)
																			{
																				
																				if( $line_row["list_value"] == 'Cancelled')
																				{
																					?>
																						<li>
																							<a href="<?php echo base_url(); ?>expense/ManageExpense/approval_status/<?php echo $row['header_id'];?>/<?php echo $line_row["list_value"];?>" onclick="return confirm('Are you sure update cancel status?')">
																								<i class="fa fa-minus-circle" aria-hidden="true"></i> Cancel 
																							</a>
																						</li>	
																					<?php
																				}

																				if( $line_row["list_value"] == 'Closed')
																				{
																					?>
																						<li>
																							<a href="<?php echo base_url(); ?>expense/ManageExpense/approval_status/<?php echo $row['header_id'];?>/<?php echo $line_row["list_value"];?>" onclick="return confirm('Are you sure update close status?')">
																								<i class="fa fa-close" aria-hidden="true"></i> Close 
																							</a>
																						</li>	
																					<?php
																				}
																				?>
																				<?php 
																			}
																		?>
																	</ul>
																</div>
																<?php 
															} 
														?>
													</td>

													<td><?php echo $row['expense_number'];?></td>
													<td>
														<?php
															echo $row['expense_status'];
														?> 
													</td>
													<td><?php echo date(DATE_FORMAT,strtotime($row['expense_date']));?></td>
													
													<td class="text-right">
														<?php echo number_format($row['expense_cost'],DECIMAL_VALUE,'.','');?>
													</td>
												</tr>
												<?php 
												$i++;
												$expense_cost += $row['expense_cost'];
											}
										?>

										<?php 
											if( count($resultData) > 0 )
											{
												?>
												<tr>
													<td colspan="5" class="text-right"><b>Total Amount :</b></td>
													<td class="text-right">
														<b><?php echo number_format($expense_cost,DECIMAL_VALUE,'.','');?></b>
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
