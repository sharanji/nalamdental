<?php
	$cashExpensesMenu = accessMenu(cash_expenses);
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "create" || $type == "edit" || $type == "view")
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
								<h3><b><?php echo ucfirst($type); ?> <?php echo $page_title;?></b></h3>
							</div>
							<div class="col-md-6 text-right">
								<?php 
									if($type == "create" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" class="btn btn-primary btn-sm">Save</button>
										<!-- <button type="submit" name="submit_btn" class="btn btn-primary btn-sm">Submit</button> -->
										<?php 
									} 
								?>
								<a href="<?php echo base_url(); ?>accounts/manageCashExpenses" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
						<!-- Buttons end here -->

						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<!-- Header Section Start Here-->
							<div class="row">
								<div class="col-md-12 header-filters">
									<a href="javascript:void(0)" class="filter-icons first_sec_hide" onclick="sectionShow('FIRST_SECTION','SHOW');">
										<i class="fa fa-chevron-circle-down"></i>
									</a>
									<a href="javascript:void(0)" class="filter-icons first_sec_show" onclick="sectionShow('FIRST_SECTION','HIDE');" style="display:none;">
										<i class="fa fa-chevron-circle-right"></i>
									</a>
									<h4 class="pl-1"><b>Header</b></h4>
								</div>
							</div>

							<section class="header-section first_section">
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-4 text-right">
												<label class="col-form-label text-right expense_number"> Expense No.</label>
											</div>
											<div class="form-group col-md-5">
												<input type='text' name="expense_number" readonly id="expense_number" value='<?php echo isset($headerData[0]['expense_number']) ? $headerData[0]['expense_number'] : NULL;?>' class="form-control">
											</div>
										</div>

										<div class="row">
											<div class="col-md-4 text-right">
												<label class="col-form-label text-right branch_id"> <span class="text-danger">*</span> Branch</label>
											</div>
											<div class="form-group col-md-5">
												<?php 
													$getBranch = $this->branches_model->getBranchAll();
												?>
												<select name="branch_id" id="branch_id" class="form-control <?php echo $searchDropdown;?>">
													<option value="">- Select -</option>
													<?php 
														foreach($getBranch as $row)
														{
															$selected='';
															if(isset($headerData[0]['branch_id']) && $headerData[0]['branch_id'] == $row["branch_id"])
															{
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $row["branch_id"];?>" <?php echo $selected;?>><?php echo $row["branch_name"];?></option>
															<?php 
														} 
													?>
												</select>
											</div>
										</div>

										<div class="row">
											<div class="col-md-4 text-right">
												<label class="col-form-label text-right header_description">Description</label>
											</div>
											<div class="form-group col-md-5">
												<textarea name="header_description" rows='1' id="header_description" class="form-control"><?php echo isset($headerData[0]['description']) ? $headerData[0]['description'] : NULL;?></textarea>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-4 text-right">
												<label class="col-form-label text-right expense_date"> <span class="text-danger">*</span> Expense Date</label>
											</div>
											<div class="form-group col-md-5">
												<input type='text' name="expense_date" required id="expense_date" value='<?php echo isset($headerData[0]['expense_date']) ? date("d-M-Y",strtotime($headerData[0]['expense_date'])) : date("d-M-Y");?>' readonly class="form-control future_date">
											</div>
										</div>
										<div class="row">
											<div class="col-md-4 text-right">
												<label class="col-form-label text-right total_amount"> Total Amount</label>
											</div>
											<div class="form-group col-md-5">
												<input type='text' name="total_amount" id="total_amount" value='<?php echo isset($headerData[0]['total_amount']) ? number_format($headerData[0]['total_amount'],DECIMAL_VALUE,'.','') : NULL;?>' readonly class="form-control no-outline">
											</div>
										</div>
									</div>
								</div>	
							</section>
							<!-- Header Section End Here-->

							<!-- Line level start here -->
							<section>
								<div class="row">
									<div class="col-md-1 header-filters">
										<a href="javascript:void(0)" class="filter-icons thi_sec_hide" onclick="sectionShow('THIRD_SECTION','SHOW');">
											<i class="fa fa-chevron-circle-down"></i>
										</a>
										<a href="javascript:void(0)" class="filter-icons thi_sec_show" onclick="sectionShow('THIRD_SECTION','HIDE');" style="display:none;">
											<i class="fa fa-chevron-circle-right"></i>
										</a>
										<h4 class="pl-1"><b>Lines</b></h4>
									</div>	
								</div>

								<div class="line-section mt-2  thi_section">
									
									<div class="line-section-overflow mt-3">
										<table class="table table-bordered table-hover line_items" id="line_items">
											<thead>
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
													<th>Item <span class="text-danger">*</span></th>
													<th>Voucher No.</th>
													<th class="text-right">Amount ( <?php echo CURRENCY_SYMBOL;?> ) <span class="text-danger">*</span></th>
												</tr>
											</thead>
											<tbody>
												 <?php
													if( isset($type) && $type == "edit" || $type == "view")		
													{
														if( count($lineData) > 0)
														{
															$counter=1;
															foreach($lineData as $row)
															{
																?>
																<tr class="deleteRow<?php echo $counter;?>">
																	<?php 
																		if($type == "view")
																		{
																			
																		}
																		else
																		{
																			?>
																			<td class="text-center">
																				<a onclick="deleteItem('<?php echo $row['line_id'];?>','<?php echo $counter;?>');"><i class="fa fa-trash"></i></a>
																				<input type="hidden" name='counter[]' value='<?php echo $counter;?>' class='form-control'>
																				<input type="hidden" name='line_id[]' id='line_id<?php echo $counter;?>' value='<?php echo $row["line_id"];?>' class='form-control'>
																			</td>
																			<?php
																		}
																	?>
																	
																	<td><textarea name='item_description[]' id='item_description<?php echo $counter;?>' required  rows='1' class='form-control' autocomplete='off'><?php echo $row["item_description"];?></textarea></td>
																	<td><input type="text" name='voucher_number[]' readonly id='voucher_number<?php echo $counter;?>' value='<?php echo $row["voucher_number"];?>' class='form-control'></td>
																	<td>
																		<input type='text' name='expense_cost[]' required id='expense_cost<?php echo $counter;?>' value="<?php echo number_format($row['expense_cost'],DECIMAL_VALUE,'.','');?>" class='form-control text-right' autocomplete='off'>
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

									<?php 
										if($type == "view")
										{
											
										}
										else
										{
											?>
											<div class="row mt-2 mb-2">
												<div class="col-md-6">
													<a href="javascript:void(0);" onclick="saveBtn('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>
												</div>
												<div class="col-md-6 text-right">
													<a href="javascript:void(0);" onclick="saveBtn('add_line_item');" id="addLineItem" class="btn btn-primary btn-sm">Add</a>
												</div>
											</div>
											<?php
										}
									?>

									<div class="row mt-2 mb-2">
										<div class="col-md-12">
											<div class="line-items-error"></div>
										</div>
									</div>
								</div>	
							</section>
							<!-- Line level end here -->
						</fieldset>

						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8 text-right">
								<?php 
									if($type == "create" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" class="btn btn-primary btn-sm">Save</button>
										<?php 
									}
								?>
								<a href="<?php echo base_url(); ?>accounts/manageCashExpenses" class="btn btn-sm btn-default">Close</a>
								
							</div>
						</div>	
					</form>

					<?php 
						$prefixName = isset($cashExpenseVoucher[0]['prefix_name']) ? $cashExpenseVoucher[0]['prefix_name'] : NULL;
						$startingNumber = isset($cashExpenseVoucher[0]['next_number']) ? $cashExpenseVoucher[0]['next_number'] : NULL;
						$suffixName = isset($cashExpenseVoucher[0]['suffix_name']) ? $cashExpenseVoucher[0]['suffix_name'] : NULL;
						$documentNumber = $prefixName.''.$startingNumber.''.$suffixName;
					?>
					
					<script>
						function saveBtn(val)
						{
							var expense_date = $("#expense_date").val();
							var branch_id = $("#branch_id").val();
							
							if( expense_date && branch_id)
							{
								$(".expense_date").removeClass('errorClass');
								$(".branch_id").removeClass('errorClass');
								
								if(val == 'save_btn')
								{
									var lineTotalCount = $("table.line_items > tbody  > tr").length;

									if(lineTotalCount > 0)
									{
										return true;
									}
									else
									{
										Swal.fire({
											icon: 'error',
											//title: 'Amount Mismatch...',
											text: 'Atleast one line is required!',
											//footer: '<a href="">Why do I have this issue?</a>'
										})
										return false;
									}	
								}
								else if(val == 'add_line_item')
								{
									addLines();
								}
							}
							else
							{
								if(expense_date) {
									$(".expense_date").removeClass('errorClass');
								} else{
									$(".expense_date").addClass('errorClass');
								}	

								if(branch_id) {
									$(".branch_id").removeClass('errorClass');
								} else{
									$(".branch_id").addClass('errorClass');
								}
								return false;	
							}
						}

						var type = '<?php echo $type;?>';

						if(type == 'create')
						{
							var i = 0;
							var counter = 1;
						}
						else if(type == 'edit')
						{
							var counter = '<?php echo isset($lineData) ? count($lineData) + 1 : 1; ?>';
							var i = 0;
						}

						function addLines()
						{
							$('.line-items-error').text('');
							var flag = 0;

							$("table.line_items").find('textarea[name^="item_description[]"]').each(function () 
							{
								var row = $(this).closest("tr");
								var item_description = +row.find('textarea[name^="item_description[]"]').val();
								
								if(item_description == "")
								{
									flag = 1;
								}
							});
							
							if(flag == 0)
							{
								var newRow = $("<tr class='remove_tr'>");
								var cols = "";

								var document_number = '<?php echo $documentNumber; ?>';
								var voucher_number = parseInt(document_number) + parseInt(i);
								
								cols += "<td class='tab-md-50 text-center'><a class='deleteRow'><i class='fa fa-trash' style='font-size:14px;'></i></a>"+
									"<input type='hidden' name='line_id[]' id='line_id"+ counter +"' value='0'>"+
									"<input type='hidden' name='counter' value='"+counter+"'></td>";
								
								cols += "<td class='tab-md-150'>"
										+"<textarea name='item_description[]' id='item_description"+ counter +"' required rows='1' placeholder='Item' class='form-control' autocomplete='off'></textarea>"
									"</td>";
				
								cols += "<td class='tab-md-150'>" 
										+"<input type='text' name='voucher_number[]' readonly id='voucher_number"+ counter +"' class='form-control' placeholder='Voucher No.'  value='"+ voucher_number +"'>"
									+"</td>";
								
								cols += "<td class='tab-md-100'>" 
										+"<input type='number' name='expense_cost[]' style='text-align:right;' id='expense_cost"+ counter +"' class='form-control' placeholder='Amount' required  value=''>"
									+"</td>";

								cols += "</tr>";
								
								newRow.html(cols);
								//$("table.line_items").prepend(newRow);
								$("table.line_items").append(newRow);
								counter++;	
								i++;	
							}
							else 
							{
								$('.line-items-error').text('Please fill the all required fields.').animate({opacity: '0.0'}, 2000).animate({}, 1000).animate({opacity: '1.0'}, 2000);
							}
						}

						$("table.line_items").on("click", "a.deleteRow,a.deleteRow1", function(event) 
						{
							$(this).closest("tr").remove();
						});

						$("table.line_items").on("input keyup change", 'input[name^="expense_cost[]"]', function (event) 
						{
							calculateGrandTotal();
						});

						function deleteItem(line_id,counter)
						{
							$.ajax({
								url: "<?php echo base_url('accounts/deleteItem'); ?>",
								type: "POST",
								data:{
									line_id : line_id
								},
								success: function(result)
								{
									$(".deleteRow"+counter).remove();
									calculateGrandTotal();
								}
							});
						}

						function calculateGrandTotal() 
						{
							var totalValue = 0;
							
							$("table.line_items").find('input[name^="expense_cost[]"]').each(function () 
							{
								totalValue += +$(this).val();
							});

							$('#total_amount').val(totalValue.toFixed(2));
						}
					</script>

					<script>
						function sectionShow(section_type,show_hide_type)
						{	
							if(section_type == 'FIRST_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".first_sec_hide").hide();
									$(".first_sec_show").show();

									$(".first_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".first_sec_hide").show();
									$(".first_sec_show").hide();

									$(".first_section").show("slow");
								}
							}
							else if(section_type == 'SECOND_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".sec_sec_hide").hide();
									$(".sec_sec_show").show();

									$(".sec_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".sec_sec_hide").show();
									$(".sec_sec_show").hide();

									$(".sec_section").show("slow");
								}
							}
							else if(section_type == 'THIRD_SECTION')
							{
								if(show_hide_type == 'SHOW')
								{
									$(".thi_sec_hide").hide();
									$(".thi_sec_show").show();

									$(".thi_section").hide("slow");
								}
								else if(show_hide_type == 'HIDE')
								{
									$(".thi_sec_hide").show();
									$(".thi_sec_show").hide();

									$(".thi_section").show("slow");
								}
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
						<div class="col-md-6"><h5><b><?php echo $page_title;?></b></h5></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if($cashExpensesMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>accounts/manageCashExpenses/create" class="btn btn-info btn-sm">
										Create Cash Expense
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

							<div class="col-md-3 p-0">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Expense #</label>
									<div class="form-group col-md-7">
										<input type="search" name="expense_no" id="expense_no" class="form-control" value="<?php echo !empty($_GET['expense_no']) ? $_GET['expense_no'] :""; ?>" placeholder="Expense No.">
									</div>
								</div>
							</div>	
						
							<div class="col-md-3 p-0">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">From Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="from_date" id="from_date" class="form-control from_date" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] :""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>

							<div class="col-md-3 p-0">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">To Date</label>
									<div class="form-group col-md-7">
										<input type="text" name="to_date" id="to_date" class="form-control to_date" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="From Date">
									</div>
								</div>
							</div>
							
							<div class="col-md-2 p-0">
								<button type="submit" class="btn btn-info ">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="<?php echo base_url(); ?>accounts/manageCashExpenses" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->
	
					<?php 
						if( isset($_GET) && !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row">
								<div class="col-md-10 mt-3">
									<?php 
										if( isset($resultData) && count($resultData) > 0 )
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
											<th>Expense No.</th>
											<th>Description</th>
											<th>Expense Date </th>
											<th class="text-right">Amount (<?php echo CURRENCY_SYMBOL;?>)</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$expense_cost=0;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<div class="dropdown">
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action &nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<?php
																	if($cashExpensesMenu['create_edit_only'] == 1 || $cashExpensesMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($cashExpensesMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>accounts/manageCashExpenses/edit/<?php echo $row['header_id'];?>">
																						<i class="fa fa-pencil"></i> Edit
																					</a>
																				</li> 
																				<?php 
																			} 
																		?>
																		<?php
																			if($cashExpensesMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>accounts/manageCashExpenses/view/<?php echo $row['header_id'];?>">
																						<i class="fa fa-eye"></i> View
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
													</td>
													<td><?php echo $row['expense_number'];?></td>
													<td><?php echo $row['description'];?></td>
													
													<td><?php echo date(DATE_FORMAT,strtotime($row['expense_date']));?></td>
													
													<td class="text-right">
														<?php echo number_format($row['total_amount'],DECIMAL_VALUE,'.','');?>
													</td>
												</tr>
												<?php 
												$expense_cost += $row['total_amount'];
											}
										?>

										<?php 
											if( count($resultData) > 0 )
											{
												?>
												<tr>
													<td colspan="4" class="text-right"><b>Total Amount :</b></td>
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
