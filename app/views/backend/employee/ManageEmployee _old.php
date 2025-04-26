<?php
	$employee = accessMenu(employee);
?>
<style>
	.disabled { cursor: not-allowed !important; }
	input[type=checkbox][disabled] {
	outline: 2px solid #339966;
	cursor: not-allowed;
	}
</style>
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageEmployee/grid_view" class="breadcrumb-item">
					<?php
						echo $page_title;
					?>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->

	<?php 
		if(isset($type) && $type == "grid_view" || $type == "list_view" || $type == "card_view")
		{ 
		}
		else
		{
			if(isset($type) && ($type == "view" || $type == "EmployeeDocuments" || $type == "payslip" || $type == "bankDetails") )
			{
				?>
				<div class="card">
					<div class="card-body">
						<?php 
							if( isset($type) && $type == "view" )
							{ 
								if($employee['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<div class="row">
										<div class="col-md-6">Employee Profile</div>
										<div class="col-md-6 text-right">
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-outline-primary btn-sm">
												<i class="fa fa-chevron-circle-left"></i> Back
											</a>
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/edit/basic-info/<?php echo $id;?>" class="btn btn-primary btn-sm">
												Edit
											</a>
										</div>
									</div>
									<?php
								}
							}
							else if(isset($type) && $type == "EmployeeDocuments")
							{ 
								?>
								<div class="text-right">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-warning">
										<i class="fa fa-chevron-circle-left"></i> Back
									</a>
									<?php 
										if(count($resultData) > 0)
										{
											?>
											<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
												Edit Document
											</button>
											<?php 
										}
										else
										{ 
											?>
											<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
												Add Document
											</button>
											<?php 
										} 
									?>
								</div>
								
								<!-- Add Document popup Start -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Employee Documents</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form action="" method="post" enctype="multipart/form-data">
												<div class="modal-body">
													<!-- Document start here-->
													<?php 
														$categories = $this->db->query('select category_id, category_name, required_type from user_document_categories where category_status=1 and category_type=3')->result_array();
													
														if( count($resultData) == 0)
														{
															$required='required';
														}
														else  
														{
															$user_id = isset($id) ? $id : 0;
															$checkDocuments = $this->db->query("
																select 
																	user_document_attachments.category_id,
																	user_document_attachments.user_id,
																	user_document_attachments.image_2,
																	user_document_attachments.caption,
																	user_document_attachments.document_type,
																	user_document_categories.category_name

																	from user_document_attachments 
																
																left join user_document_categories on
																	user_document_categories.category_id =  user_document_attachments.category_id
																where
																	user_document_attachments.user_id ='".$user_id."'
																")->result_array();
															if(count($checkDocuments) > 0)	
															{
																$required='';
															}
															else
															{
																$required='required';
															}
														}
													?>
													<div class="row form-group">
														<label class="col-form-label col-md-2">Documents </label>
														<div class="col-md-5">
															<select class="form-control -searchDropdown" <?php /* echo $required; */?> id="documents" name="documents">
																<option value="">- Select Document -</option>					  
																<?php
																	foreach($categories as $category) 
																	{
																		/* if ($category['required_type'] == 1)
																		{
																			$requiredType = '(Required)';
																		}
																		else 
																		{
																			$requiredType = '';
																		} */
																		?>
																		<option value="<?php echo $category['category_id'];?>"><?php echo ucfirst($category['category_name']);?></option>
																		<?php
																	}
																?>
															</select>
														</div>
													</div>
													
													<div class="row mt-4 mb-4">
														<div class="col-sm-12">
															<div class="form-group">
																<div style="overflow-y: auto;">
																	<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
																	<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
																		<thead>
																			<tr>
																				<th colspan="13">
																					Attached Documents <span style="color:#969292;">( Upload Documents : png, bmp, gif, jpg, jpeg, pdf and Size is 4MB. )</span>
																				</th>
																			</tr>
																			<tr>
																				<th style="width:30px;"> </th>
																				<th>Document Name</th>
																				<th>Upload Document</th>
																				<td>Document Type</td>
																				<th>Description</th>
																			</tr>
																		</thead>
																		<tbody id="product_table_body">
																			<?php
																				if( isset($resultData) && count($resultData) > 0 )
																				{
																					$user_id = isset($id) ? $id : 0;
																					$checkDocuments = $this->db->query("
																						select 
																							user_document_attachments.attachement_id,
																							user_document_attachments.category_id,
																							user_document_attachments.user_id,
																							user_document_attachments.image_2,
																							user_document_attachments.caption,
																							user_document_attachments.description,
																							user_document_attachments.document_type,
																							user_document_categories.category_name

																							from user_document_attachments 
																						
																						left join user_document_categories on
																							user_document_categories.category_id =  user_document_attachments.category_id
																						where
																							user_document_attachments.user_id ='".$user_id."'
																						")->result_array();
																						
																					if( count($checkDocuments) > 0)
																					{
																						$i=0;
																						$counter=1;
																						foreach($checkDocuments as $documents)
																						{
																							?>
																							<tr class="dataRowVal<?php echo $documents['category_id']; ?>">
																								<td>
																									<a class='deleteRow1'> 
																										<i class="fa fa-trash"></i> 
																									</a>
																									<input type='hidden' name='image_2[]' value="<?php echo $documents['image_2']; ?>">
																									<input type='hidden' name='attachement_id[]' value="<?php echo $documents['attachement_id']; ?>">
																									<input type='hidden' name='id' name='id' value="<?php echo $i ?>">
																									<input type='hidden' name='category_id[]' value="<?php echo $documents['category_id']; ?>">
																								</td>
																								<td><?php echo $documents['category_name']; ?></td>
																								
																								<td>
																									<input type='file' class='form-control' name='upload_document[]' onchange="return validateFileExtension(this,<?php echo $counter;?>)" id='first_<?php echo $counter;?>' >
																									<?php
																										if(!empty($documents['image_2']) && file_exists("uploads/document_attachments/".$documents['image_2']) )
																										{
																											?>
																											<a href="<?php echo base_url()?>uploads/document_attachments/<?php echo $documents['image_2'];?>" download title="download">Download <i class="fa fa-download"></i></a>
																											<?php
																										}
																									?>
																								</td>
																								
																								<td>
																									<select class='form-control' id='document_type' name='document_type[]'>
																										<option value=''>- Select Document Type -</option>
																										<?php
																											foreach($this->document_type as $key => $value)
																											{
																												$selected="";
																												if($documents['document_type'] == $key)
																												{
																													$selected="selected='selected'";
																												}
																												?>
																												<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
																												<?php 
																											} 
																										?>
																									</select>
																								</td>
																								
																								<td>
																									<textarea rows="1" class='form-control' name='description[]' id='description_<?php echo $counter;?>'><?php echo $documents['description']; ?></textarea>
																								</td>
																							</tr>
																							<?php 
																							$counter++;
																							$i++;
																						} 
																					} 
																				} 
																			?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
													</div>
													
													<script>
														$(document).ready(function()
														{
															var type = '<?php echo isset($resultData) ? count($resultData) : 0;?>';
															
															if( type == 0 )
															{
																var i = 0;
																var product_data = new Array();
																var counter = 1;
															}
															else
															{
																var counter1 = '<?php echo isset($checkDocuments) ? count($checkDocuments) : 1; ?>';
																
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
																	var counter = '<?php echo isset($checkDocuments) ? count($checkDocuments) : 1; ?>';
																}
															}
															
															$('#documents').change(function()
															{
																var id = $(this).val();
																$('#err_product').text('');
																var flag = 0;
																
																if(id != "")
																{
																	$.ajax({
																		url: "<?php echo base_url('employee/getAttachedDocuments') ?>/"+id,
																		type: "GET",
																		data:{
																			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
																		},
																		datatype: "JSON",
																		success: function(d)
																		{
																			data = JSON.parse(d);
																			$("table.product_table").find('input[name^="category_id"]').each(function () 
																			{
																				if(data[0].category_id  == +$(this).val())
																				{
																					flag = 1;
																				}
																			});
																			
																			if(flag == 0)
																			{
																				var id = data[0].category_id;
																				var category_name = data[0].category_name;
																				var document_type = data['documentType'];
																				var newRow = $("<tr class='dataRowVal"+id+"'>");
																				var cols = "";
																				cols += "<td><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='category_id[]' value="+id+"></td>";
																				cols += "<td class='tab-medium-width'>"+category_name+"</td>";
																				cols += "<td class='text-center'>"
																					+"<input type='file' required class='form-control' onchange='return validateFileExtension(this,"+ counter +")' name='upload_document[]' id='first_"+ counter +"' >"
																					+"</td>";
																					
																				cols += "<td class='tab-medium-width'>"+document_type+"</td>";
																		
																				cols += "<td class='text-center'>"
																					+"<textarea rows='1' class='form-control' name='description[]' id='description_"+ counter +"'></textarea>"
																					+"</td>";
																					
																				cols += "</tr>";
																				counter++;

																				newRow.html(cols);
																				$("table.product_table").append(newRow);
																				var table_data = JSON.stringify(product_data);
																				$('#table_data').val(table_data);
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
																$(this).closest("tr").remove();
															});
															
															/* $("table.product_table").on("click", "a.deleteRow1", function (event) 
															{
																deleteRow1($(this).closest("tr"));
																$(this).closest("tr").remove();
																calculateGrandTotal();
															}); */
														});
													</script>
														
													<!-- Proof attached end here -->
													<!-- Document end here-->
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="submit" name="add_document" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<!-- Add Document popup End -->
								<?php
							}
							else if( isset($type) && $type == "payslip" )
							{ 
								?>
								<div class="text-right">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee" class="btn btn-warning">
										<i class="fa fa-chevron-circle-left"></i> Back
									</a>
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">
										Assign Pay Slips
									</button>
								</div>
								
								<!-- Payslip popup Start -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Assign Pay Slips</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form method="post">
												<div class="modal-body">
													<div class="form-group">
														<div class="row">
															<div class="col-md-3">
																<label for="category_type">Category Type <span class="text-danger">*</span> </label>
															</div>
															<?php 
																$getElement = $this->db->query("select element_name, element_id from emp_payroll_elements where element_status=1 order by element_name desc")->result_array();
															?>
															<div class="col-md-9">
																<select name="category_type" id="category_type" class="form-control" required>
																	<option value="">- Select Employee Role -</option>
																	<?php 
																		foreach($getElement as $row)
																		{ 
																			$selected="";
																			if( isset($edit_data[0]['category_type']) && $edit_data[0]['category_type'] == $row['element_id'])
																			{
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['element_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['element_name']);?></option>
																			<?php 
																		} 
																	?>
																</select>
															</div>
															<?php /* <div class="col-md-9">
																<select name="category_type" class="form-control" required id="category_type">
																	<option value=""> - Select Category type - </option>
																	<?php
																		foreach ($this->paySlipCategoryType as $key => $category_type) 
																		{
																			echo '<option value="'.$key.'">'.$category_type.'</option>';
																		}
																	?>
																</select>
															</div> */ ?>
														</div>
													</div>
													<div class="form-group">
														<div class="row">
															<div class="col-md-3">
																<label for="category_id">Category <span class="text-danger">*</span> </label>
															</div>
															<div class="col-md-9">
																<select name="category_id" class="form-control" required id="category_id">
																	<option value=""> - Select Category - </option>
																</select>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													<button type="submit" name="add" class="btn btn-primary">Assign</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<script>
									$('#category_type').change(function () 
									{
										$.ajax({
											url: '<?php echo base_url();?>employee/AjaxGetCategorys',
											type: 'POST',
											data: {
												category_type_id : $(this).val(),
											},
											success: function(result)
											{ 
												$('#category_id').html(result);
											}
										});
									});
								</script>
								<!-- Payslip popup End -->
								<?php
							}
							else if( isset($type) && $type == "bankDetails" )
							{ 
								?>
								<div class="row">
									<div class="col-md-6">Bank Details</div>
									<div class="col-md-6 text-right">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-outline-primary btn-sm">
											<i class="fa fa-chevron-circle-left"></i> Back
										</a>
										<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal">
											Add Bank
										</button>
									</div>
								</div>
								
								<!-- Add Bank Start here -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Add Bank Detail</h5>
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<form method="post">
												<div class="modal-body">
													<div class="row">
														<div class="col-md-6">
															<div class="row">
																<div class="form-group col-md-3">
																	<label class="col-form-label">A/c Noo <span class="text-danger">*</span></label>
																</div>
																<div class="form-group col-md-6">
																	<input type="text" name="account_number" id="account_number" autocomplete="off" value="" class="form-control" required="">
																</div>
															</div>
															<div class="row">
																<div class="form-group col-md-3">
																	<label class="col-form-label">A/c Holder Name <span class="text-danger">*</span></label>
																</div>
																<div class="form-group col-md-6">
																	<input type="text" name="account_name" id="account_name" autocomplete="off" value="" class="form-control" required="">
																</div>
															</div>
													
															<div class="row">
																<div class="form-group col-md-3">
																	<label class="col-form-label">Bank Name <span class="text-danger">*</span></label>
																</div>
																<div class="form-group col-md-6">
																	<input type="text" name="bank_name" id="bank_name" autocomplete="off" value="" class="form-control" required="">
																</div>

																<div class="form-group col-md-3">
																	<label class="col-form-label">Bank Branch <span class="text-danger">*</span></label>
																</div>
																<div class="form-group col-md-6">
																	<input type="text" name="branch_name" id="branch_name" autocomplete="off" value="" class="form-control" required="">
																</div>
															</div>
														
															<div class="row">
																<div class="form-group col-md-3">
																	<label class="col-form-label">IFSC Code <span class="text-danger">*</span></label>
																</div>
																<div class="form-group col-md-6">
																	<input type="text" name="ifsc_code" id="ifsc_code" autocomplete="off" value="" class="form-control" required="">
																	<span class="small note-color">(Ex : IDIB000A114)</span>
																</div>
															</div>
															<div class="row">
																<div class="form-group col-md-3">
																	<label class="col-form-label">MICR Code</label>
																</div>
																<div class="form-group col-md-6">
																	<input type="text" name="micr_code" id="micr_code" autocomplete="off" value="" class="form-control">
																	<span class="small note-color">(Ex : 600019003)</span>
																</div>
															</div>

															<div class="row">
																<div class="form-group col-md-3">
																	<label class="col-form-label">Address</label>
																</div>
																<div class="form-group col-md-6">
																	<textarea name="address" id="address" autocomplete="off" class="form-control"></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													<button type="submit" name="add" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<script>
									$('#category_type').change(function () 
									{
										$.ajax({
											url: '<?php echo base_url();?>employee/AjaxGetCategorys',
											type: 'POST',
											data: {
												category_type_id : $(this).val(),
											},
											success: function(result)
											{ 
												$('#category_id').html(result);
											}
										});
									});
								</script>
								<!-- Add Bank End here -->
								<?php
							}
						?>
					</div>
				</div>
				<?php 
			} 
		} 
	?>

	<?php 
		if(isset($type) && $type == "grid_view" || $type == "list_view" || $type == "card_view")
		{ 
			?>
			<div class="card">
				<div class="card-body">
					<?php
						if(isset($type) && $type == "grid_view" || $type == "list_view" || $type == "card_view")
						{ 
							if($type == "grid_view")
							{
								$GridIconActive="report-view-acive";
							}
							else if($type == "list_view")
							{
								$ListIconActive="report-view-acive";
							}
							else if($type == "card_view")
							{
								$CardIconActive="report-view-acive";
							}
							else
							{
								$GridIconActive="report-view-acive";
							}

							if($employee['create_edit_only'] == 1 || $this->user_id == 1)
							{
								?>
								<div class="new-import-btn" style="float:left;">
									Employees
								</div>
								<div class="new-import-btn" style="float:right;">
								
									<!-- View start here-->
									<?php /* <a href="<?php echo base_url();?>employee/ManageEmployee/grid_view" class="btn btn-light btn-sm grid-view <?php echo isset($GridIconActive) ? $GridIconActive :"";?>" title="Grid View">
										<i class="fa fa-table"></i>
									</a>
									<a href="<?php echo base_url();?>employee/ManageEmployee/list_view" class="btn btn-light btn-sm list-view <?php echo isset($ListIconActive) ? $ListIconActive :"";?>" title="List View">
										<i class="fa fa-list"></i>
									</a>
									<a href="<?php echo base_url();?>employee/ManageEmployee/card_view" class="btn btn-light btn-sm card-view <?php echo isset($CardIconActive) ? $CardIconActive :"";?>" title="Card View">
										<i class="fa fa-th"></i>
									</a> 
									<!-- View end here-->
									<a href="javascript:void(0)" onclick="filterSearch();" class="btn btn-light btn-sm" title="Filter">
										<i class="fa fa-filter"></i>
									</a>   */ ?>
									
									<a title="Download CSV" href="<?php echo base_url(); ?>employee/ManageEmployee/export" class="btn btn-primary btn-sm">
										<i class="fa fa-download"></i> Download CSV
									</a>
									
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/add/basic-info" class="btn btn-info btn-sm">
										Create Employee
									</a>
								</div>
								<?php 
							}
						}
					?>
				</div>
			</div>
			<?php 
		} 
	?>

	<?php 
		$reportPage = isset($_SESSION['REPORT_LIST']) ? $_SESSION['REPORT_LIST'] : 1;

		if(isset($type) && $type == "grid_view" || $type == "list_view" || $type == "card_view")
		{
			?>
			<!-- Search Card start-->
			<?php 
				if(isset($_GET['filter_search']))
				{
					
					$filterSearchDisplay = 'style="display:block;"';
				}
				else
				{
					$filterSearchDisplay = 'style="display:none;"';
				}
			?>
				
			<div class="card filter_card" <?php //echo $filterSearchDisplay;?>>
				<div class="card-body">
					<form action="" method="get">
						<?php 
							$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
						?>
						<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
												
						<div class="row">
							<div class="col-md-8">
								<div class="row">

									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<span class="small-1 text-muted">Note : Employee No., Employee Name, Mobile No and Email.</span>
									</div>
									
									<div class="col-md-1">
										<button type="submit" name="filter_search" class="btn btn-info">
											<i class="fa fa-search"></i>
										</button>
									</div>
									
									<?php 
										/*if(isset($type) && $type == "grid_view")
										{
											?>
											<div class="col-md-4">	
												<select name="report_list"  class="search-dropdown-new" --class="form-control searchDropdown-" onchange="location.href='<?php echo base_url(); ?>admin/report_list/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
													<?php 
														foreach($this->report_list as $key => $value)
														{
															$selected="";
															if($key == $reportPage){
																$selected="selected=selected";
															}
															?>
															<option style="border-radius:0px!important;" value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
															<?php 
														} 
													?>
												</select>
											</div>
											<?php 
										}*/ 
									?>
								</div>
							</div>
							<div class="col-md-4">
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
				</div>
			</div>
			<!-- Search Card end-->
			<?php 
		} 
	?>


	<div --class="card"><!-- Card start-->
		<!-- <div class="card-header- header-elements-inline">
			<h5 class="card-title"></h5>
		</div> -->
		<div --class="card-body">
			<legend class="text-uppercase font-size-sm font-weight-bold d-none">
				<?php echo $type; ?> Employee :
			</legend>
			<?php
				if(isset($type) && $type == "add") //$type == "edit"
				{
					?>
					<?php 
						$activeBasic = $activeCareer = 
						$activeID = $activeAddress = $activeBank =
						$activeLogin= '';
						if( isset($type) )
						{
							if( $id == 'basic-info' )
							{
								$activeBasic = 'active';
							}
							else if( $id == 'career-info' )
							{
								$activeCareer = 'active';
							}
							else if( $id == 'id-info' )
							{
								$activeID = 'active';
							}
							else if( $id == 'address-info' )
							{
								$activeAddress = 'active';
							}
							else if( $id == 'bank-info' )
							{
								$activeBank = 'active';
							}
							else if( $id == 'login-info' )
							{
								$activeLogin = 'active';
							}
						}
					?>
					<div class="card">
						<div class="card-body">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item <?php echo $activeBasic;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info/" class="nav-link">Basic Info</a>
								</li>
								
								<li class="nav-item <?php echo $activeCareer;?>">
									<?php 
										if(!empty($status))
										{
											?>
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="nav-link">
												Employment Details
											</a>
											<?php 
										}
										else
										{
											?>
											<a href="javascript:void(0);" class="nav-link">
												Employment Details
											</a>
											<?php
										} 
									?>
								</li>
								
								<li class="nav-item <?php echo $activeID;?>">
									<?php 
										if(!empty($status))
										{
											?>
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="nav-link">Identity</a>
											<?php 
										}
										else
										{
											?>
											<a href="javascript:void(0);" class="nav-link">
												Identity
											</a>
											<?php
										} 
									?>
								</li>
								
								<li class="nav-item <?php echo $activeAddress;?>">
									<?php 
										if(!empty($status))
										{
											?>
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="nav-link">Address</a>
											<?php 
										}
										else
										{
											?>
											<a href="javascript:void(0);" class="nav-link">
												Address
											</a>
											<?php
										} 
									?>
								</li>
								
								<li class="nav-item <?php echo $activeBank;?>">
									<?php 
										if(!empty($status))
										{
											?>
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/bank-info/<?php echo $status;?>" class="nav-link">Bank Details</a>
											<?php 
										}
										else
										{
											?>
											<a href="javascript:void(0);" class="nav-link">
												Bank Details
											</a>
											<?php
										} 
									?>
								</li>

								<?php /*
								<li class="nav-item <?php echo $activeLogin;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/login-info" class="nav-link">Login</a>
								</li> */?>
							</ul>
					
							<?php	
							#$random_user_id = otpNumber(6);
							/* $results = $this->db->query("select increment_id,random_user_id, email from employee_master where user_status = 1 order by increment_id desc")->result_array();
							
							if( count($results) == 0 )
							{
								$incrementID = 1;
								$random_user_id = 'EMP'.str_pad($incrementID,4, "0", STR_PAD_LEFT);
							}
							else
							{
								$incID = isset($results[0]['increment_id']) ? $results[0]['increment_id'] : 1;
								$incrementID = $incID + 1;
								$random_user_id = 'EMP'.str_pad($incrementID,4, "0", STR_PAD_LEFT);
							}	 */	
							#$random_user_id = '10000'.$incrementID;
							
							if(isset($id) && $id == 'basic-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Basic Information
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>

									<?php 
										$listTypeValuesQry = "select 
											sm_list_type_values.list_type_value_id,
											sm_list_type_values.list_code,
											sm_list_type_values.list_value	
												from sm_list_type_values

												left join sm_list_types on 
													sm_list_types.list_type_id = sm_list_type_values.list_type_id
														where 
															sm_list_type_values.list_type_status = 1 and 
																sm_list_types.list_type_id = 1"; 
										$getEmploymentType = $this->db->query($listTypeValuesQry)->result_array();
										//print_r($getEmploymentType);exit;
									?>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">Employment Type <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="employment_type" id="employment_type" class="form-control searchDropdown" required>
														<option value="">- Select -</option>
														<?php 
															foreach($getEmploymentType as $type)
															{ 
																$selected="";
																if( isset($edit_data[0]['employment_type']) && $edit_data[0]['employment_type'] == $type['list_type_value_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $type['list_type_value_id'];?>" <?php echo $selected;?>><?php echo ucfirst($type['list_value']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											
												<?php /*
												<div class="form-group col-md-3">
													<label class="col-form-label">Employee No <span class="text-danger">*</span></label>  &nbsp; &nbsp;<span class="example_text" style="color:red;">(Ex : 1001)</span>
													<input type="text" name="random_user_id" required class="form-control" value="<?php echo $random_user_id;?>" placeholder="" autocomplete="off">
													<span class="template_code_exist_error error"></span>
												</div>
												*/ ?>
												<script>
													//Template Code
													/* $("form.form-validate-jquery").on("input keyup change", 'input[name^="random_user_id"]', function (event)
													{
														var random_user_id = $(this).val();
														
														$.ajax({
															url: '<?php echo base_url();?>employee/EmployeeCodeExist',
															type: 'post',
															data: {
																'random_user_id' : random_user_id,
																<?php
																	if ($type == "edit") 
																	{
																		?>
																		'id': '<?php echo $status; ?>'
																		<?php
																	}
																?>
																
															},
															success: function(response)
															{
																if (response == 'taken') 
																{
																	$('.template_code_exist_error').html('Employee Code Already Exist');
																	$('.register-but').prop('disabled',true);
																	$(this).focus();
																	return false;
																}
																else
																{
																	$('.template_code_exist_error').html('');
																	$('.register-but').prop('disabled',false);
																	return true;
																}
															}
														});
													}); */
												</script>
											<div class="row">	
												<div class="form-group col-md-4">
													<label class="col-form-label">First Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="first_name" required  <?php #echo $this->validation; ?> class="form-control" value="" placeholder="" autocomplete="off">
													</div>
												</div>

												
											</div>
											<div class="row">	
												<div class="form-group col-md-4">
													<label class="col-form-label">Middle Name</label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="middle_name"  id="middle_name" <?php echo $this->validation; ?> class="form-control only_name" value="" placeholder="" autocomplete="off">
													</div>	
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">Last Name </label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="last_name"  autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="" placeholder="" autocomplete="off">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-4">	
													<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="row">
														<div class="col-md-3 pr-0">
															<?php 
																
																$query = "select country.country_code,country.country_id
																		from geo_countries as country 
																		where 
																		active_flag='Y'
																";
																$getmobilenumber = $this->db->query($query)->result_array();

															?>
															<select name="mob_ctry_code" id="mobile_country_code" required class="form-control searchDropdown mobile_vali">
																<option value="">- Select -</option>
																<?php 
																	foreach($getmobilenumber as $num)
																	{
																		$selected="";
																		if(isset($_GET['country_id']) && $_GET['country_id'] == $num["country_code"] )
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $num["country_id"];?>" <?php echo $selected; ?>><?php echo ucfirst($num["country_code"]);?></option>
																		<?php 
																	}
																?>
															</select>
														</div>
														<div class="col-md-9 pl-0">
															<input type="text" name="mobile_number" id="mobile_number" required autocomplete="off"  minlength="9" maxlength="12" class="form-control mobile_vali code-num1" value="<?php //echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="9632587410">
															<span class="small mobile_number_exist" style="color:red;"></span>
														</div>	
													</div>
												</div>	
											</div>
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">Alternate Mobile Number </label>
												</div>
												<div class="form-group col-md-6">
													<div class="row">
														<div class="col-md-3 pr-0">
															<?php 
																
																$query = "select country.country_code,country.country_id
																		from geo_countries as country 
																		where 
																		active_flag='Y'
																";
																$getaltmobilenumber = $this->db->query($query)->result_array();

															?>
															<?php /* <input type="text" name="alt_mobile_ctry_code" maxlength="4" id="mobile_country_code" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['alter_mobile_country_code']) ? $edit_data[0]['alter_mobile_country_code'] :"";?>" placeholder="91"> */ ?>
															<select name="alt_mob_ctry_code" id="mobile_country_code" class="form-control searchDropdown mobile_vali">
																<option value="">- Select -</option>
																<?php 
																	foreach($getaltmobilenumber as $altnum)
																	{
																		$selected="";
																		if(isset($_GET['country_id']) && $_GET['country_id'] == $altnum["country_id"] )
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $altnum["country_id"];?>" <?php echo $selected; ?>><?php echo ucfirst($altnum["country_code"]);?></option>
																		<?php 
																	}
																?>
															</select>
														</div>
														<div class="col-md-9 pl-0">
															<input type="text" name="alt_mob_number" id="alternate_contact" autocomplete="off" --oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" minlength="9" maxlength='12' class="form-control mobile_vali --code-num1" value="<?php echo isset($edit_data[0]['alternate_contact']) ? $edit_data[0]['alternate_contact'] :"";?>" placeholder="9632587410">
															<span class="mobile_number_exist"></span>
														</div>
													</div>
												</div>	
											</div>
											<div class="row">
												<div class="form-group col-md-4">
													<label class="col-form-label">Email <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email_address" id="email" required autocomplete="off" class="form-control" value="<?php //echo isset($edit_data[0]['email']) ? $edit_data[0]['email'] :"";?>" placeholder="">
														<span class='small employee_email_exist_error' style="color:red;"></span> 
												
													</div>
												</div>
											</div>
											<div class="row">	
												<div class="form-group col-md-4">
													<label class="col-form-label">Alternate E-Mail</label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="alt_email_address" --id="emp_email" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['alternate_email']) ? $edit_data[0]['alternate_email'] :"";?>" placeholder="">
													<?php /* <span class='employee_email_exist_error'></span> */?>
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-6">

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Father Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="father_name"  required id="father_name" <?php echo $this->validation; ?> class="form-control only_name" value="" placeholder="" autocomplete="off">
													</div>
												</div>
											</div>

											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Mother Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="mother_name" required id="mother_name" autocomplete="off" <?php echo $this->validation; ?> class="form-control only_name" value="<?php //echo isset($edit_data[0]['father_last_name']) ? $edit_data[0]['father_last_name'] :"";?>" placeholder="">
													</div>
												</div>
											</div> 

											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Birth <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="date_of_birth" id="date_of_birth" required class="form-control default_date" autocomplete="off" value="<?php //echo isset($edit_data[0]['date_of_birth']) ? $edit_data[0]['date_of_birth'] :"";?>" placeholder="">
												</div>
											</div> 

											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Gender <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<select name="gender" required id="gender" class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($this->gender as $key=>$value)
															{ 
																$selected="";
																if( isset($edit_data[0]['gender']) && $edit_data[0]['gender'] == $key)
																{
																	$selected="selected='selected'";
																}

																?>
																<option value="<?php echo $key;?>" <?php echo $selected; ?>><?php echo $value;?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div> 

											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Blood Group</label>
												</div>
												<div class="form-group col-md-6">
													<?php 
														$bloodgroup = $this->db->query("select blood_group_name,blood_group_id from emp_blood_group where blood_group_status=1")->result_array();
													?>
													<select name="blood_group_id" id="blood_group_id" class="form-control searchDropdown">
														<option value="">- Select -</option>
														<?php 
															foreach($bloodgroup as $row)
															{
																$selected="";
																if(isset($edit_data[0]['blood_group_id']) && $edit_data[0]['blood_group_id'] == $row['blood_group_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['blood_group_id'];?>" <?php echo $selected;?>><?php echo $row['blood_group_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div> 

											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Profile Image</label>
												</div>
												<div class="form-group col-md-6">
													<input type="file" name="profile_image" id="profile_image"class="form-control" placeholder="">
													<?php
														if($type=='edit')
														{
															if(file_exists("uploads/profile_image/".$id.'.png') )
															{
																?>
																<img class="img-responsive mt-2" alt="" style="border-radius:4px;width:75px;height:75px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $id.'.png';?>">
																<?php 
															}
														}
													?>
												</div>
											</div>
										</div>
									</div>
									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light btn-sm">Cancel</a>
										<?php 
											if($type == "edit")
											{
												?>
												<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
												<?php 
											}
											else
											{
												?>
												<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
												<?php 
											}
										?>
									</div>
								</form>
								<?php 
							} 
							else if(isset($id) && $id == 'career-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Career Information
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												
												<?php 
													$getBranch = $this->db->query("select branch_name, branch_id from branch where active_flag='Y' order by branch_name asc")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Location </label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add" title="Branch"> Add Branch </a>
														<select name="location_id" id="branch_id" class="form-control searchDropdown">
															<option value="">- Select Location -</option>
															<?php 
																foreach($getBranch as $row)
																{ 
																	$selected="";
																	if( isset($edit_data[0]['location_id']) && $edit_data[0]['location_id'] == $row['branch_id'])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']);?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<?php 
													$designation = $this->db->query("select designation_name,designation_id from emp_designations where designation_status=1")->result_array();
												?>				

												<div class="form-group col-md-3">
													<label class="col-form-label">Designation <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDesignation/add" title="Add Designation">Add Designation</a>
														<select name="designation_id" id="designation_id" required class="form-control searchDropdown">
															<option value="">- Select Designation -</option>
															<?php 
																foreach($designation as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['designation_id']) && $edit_data[0]['designation_id'] == $row['designation_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['designation_id'];?>" <?php echo $selected;?>><?php echo $row['designation_name'];?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<?php 
													$getPositions = $this->db->query("select position_name,position_id from hr_positions where position_status=1")->result_array();
												?>
												<?php /* <div class="form-group col-md-3">
													<label class="col-form-label">Position<span class="text-danger">*</span></label>
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManagePositions/add" title="Add Position">Add Position</a>
													<select name="position_id" id="position_id" class="form-control searchDropdown">
														<option value="">- Select Position -</option>
														<?php
															foreach($getPositions as $row)
															{
																$selected="";
																if(isset($edit_data[0]['position_id'])&& $edit_data[0]['position_id'] == $row['position_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																	<option value="<?php echo $row['position_id'];?>" <?php echo $selected; ?>> <?php echo $row['position_name'];?></option>
																<?php
															}
														?>
													</select>
												</div> */ ?>
												
												<?php 
													$getDepartment = $this->db->query("select department_name,department_id from emp_departments where department_status=1")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Department <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDepartment/add" title="Add Department">Add Department</a>
														<select name="department_id" id="department_id" required class="form-control searchDropdown">
															<option value="">- Select Department -</option>
															<?php
																foreach($getDepartment as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['department_id'])&& $edit_data[0]['department_id'] == $row['department_id'])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																		<option value="<?php echo $row['department_id'];?>" <?php echo $selected;?>> <?php echo $row['department_name'];?></option>
																	<?php
																}
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Joining <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">	
													<input type="text" name="date_of_joining" id="date_of_joining" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Releaving</label>
												</div>
												<div class="form-group col-md-4">
													<div class="">
														<input type="text" name="date_of_releaving" id="date_of_leaving" autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['date_of_releaving']) ? $edit_data[0]['date_of_releaving'] :"";?>" placeholder="">
													</div>
												</div>
											</div>
											
										</div>

										<div class="col-md-6">
											
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Previous Experience</label>
												</div>
												<div class="form-group col-md-6">	
												<input type="text" name="previous_experience" id="previous_experience" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['previous_experience']) ? $edit_data[0]['previous_experience'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Rate Per Day</label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="rate_per_day" id="rate_per_day" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] :"";?>" placeholder="">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Rate Per Hour</label>
												</div>
												<div class="form-group col-md-6">
													<div class="">
														<input type="text" name="rate_per_day" id="rate_per_day" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] :"";?>" placeholder="">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Pay frequency</label>
												</div>
												<div class="form-group col-md-6">
													<select name="pay_frequency" id="pay_frequency" class="form-control searchDropdown" > <!--selectboxit-->
														<option value="">- Select Pay Frequency -</option>
														<?php 
															foreach($this->pay_frequency as $key=>$value)
															{
																$selected = "";
																if( isset($edit_data[0]['pay_frequency']) && $edit_data[0]['pay_frequency'] == $key)
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
											</div>
											
										</div>
									</div>
									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info" class="btn btn-light btn-sm">Back</a>
										<?php 
											if($type == "edit")
											{
												?>
												<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
												<?php 
											}
											else
											{
												?>
												<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
												<?php 
											}
										?>
									</div>
									
									
								</form>
								<?php
							}
							else if(isset($id) && $id == 'id-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												ID Information
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Aadhar No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="aadhaar_number" data-type="adhaar-number" maxlength="14" required class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['aadhaar_number']) ? $edit_data[0]['aadhaar_number'] :"";?>" placeholder="">
													<span class="small" id="aadhaar_number_val" style="color:red;float:left;width:100%;">(Ex : 4891-1846-5046)</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">PAN Number <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="pan_number" maxlength="10" id="textPanNo" -onblur="ValidatePAN(this);" required autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number'] :"";?>" placeholder="">
													<span class="small" id="pan_number_val" style="color:red;float:left;width:100%;">(Ex : ABCDE1234F)</span>
												</div>
											</div>		
											<div class="row">						
												<div class="form-group col-md-3">
													<label class="col-form-label">Driving Licence </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="driving_licence" id="driving_licence" --maxlength="17" --oninput="LicenceNumber(this)" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['driving_licence']) ? $edit_data[0]['driving_licence'] :"";?>" placeholder="">
													<span id="licence_number_val" class="small" style="color:red;">(Ex : TN-0619850034761 )</span>
												</div>
											</div>
											<div class="row">						
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport No </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_number" id="passport_number" minlength="8" maxlength="10" data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_number']) ? $edit_data[0]['passport_number'] :"";?>" placeholder="">
													<span id="passport_number_error" class="small" style="color:red;">(Ex : A1234567)</span>
												</div>
											</div>
											<div class="row">						
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport Issue Date</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_issue_date" id="passport_issue_date" class="form-control default_date" autocomplete="off" value="" placeholder="">
												</div>
											</div>

										</div>

										<div class="col-md-6">	
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport Expiry Date</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_exp_date" id="passport_expiry_date" class="form-control default_date" autocomplete="off" value="" placeholder="">
												</div>
											</div>
											<div class="row">											
												<div class="form-group col-md-3">
													<label class="col-form-label">PF No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="pf_number" id="pf_number" required autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['pf_number']) ? $edit_data[0]['pf_number'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">ESI No.</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="esi_number" id="esi_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['esi_number']) ? $edit_data[0]['esi_number'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">UAN No.</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="uan_number" id="uan_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['uan_number']) ? $edit_data[0]['uan_number'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
									
									</div>
									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="btn btn-light btn-sm">Back</a>
										<?php 
											if($type == "edit")
											{
												?>
												<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
												<?php 
											}
											else
											{
												?>
												<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
												<?php 
											}
										?>
									</div>
								</form>
								<script type="text/javascript">
									$('[data-type="adhaar-number"]').keyup(function() 
									{
										var value = $(this).val();
										value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
										$(this).val(value);

										aadhaar_number_val = value;
										$.ajax({
											url: '<?php echo base_url();?>employee/aadhaarUnique',
											type: 'post',
											data: {
												'aadhaar_number' : aadhaar_number_val,
												'type'		 : '<?php echo $type ?>',
												<?php
													if ($type == "edit") 
													{
														?>
														'id' : '<?php echo $status; ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'already_taken') 
												{
													$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
													// Obj.focus();
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									function ValidatePAN() 
									{ 
										var Obj = document.getElementById("textPanNo");
										if (Obj.value != "") 
										{
											ObjVal = Obj.value;
											var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
											if (ObjVal.search(panPat) == -1) 
											{
												alert("Please Enter Valid PAN NO.");
												$('#textPanNo').val('');
												//Obj.focus();
												return false;
											}
											else
											{
												pan_number = $('#textPanNo').val();
												$.ajax({
													url: '<?php echo base_url();?>employee/panUnique',
													type: 'post',
													data: {
														'pan_number' : pan_number,
														'type'		 : '<?php echo $type ?>',
														<?php
															if ($type == "edit") 
															{
																?>
																'id'  : '<?php echo $status; ?>'
																<?php
															}
														?>
													},
													success: function(response)
													{
														if (response == 'already_taken') 
														{
															$('#pan_number_val').html('PAN Number Alredy Taken');
															//Obj.focus();
															$('.register-but').prop('disabled',true);
															return false;
														}
														else
														{
															$('#pan_number_val').html('(Ex : ABCDE1234F)');
															$('.register-but').prop('disabled',false);
															return true;
														}
													}
												});

											}
										}
										else
										{
											$('#pan_number_val').html('(Ex : ABCDE1234F)');
										}
									} 
						
									$('#mobile_number').blur(function () 
									{
										mobile_number = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>vendor/MobileExist',
											type: 'post',
											data: {
												'mobile_number' : mobile_number,
												<?php
													if ($type == "edit") 
													{
														?>
															'id' : '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.mobile_number_exist').html('Mobile Number Alredy Taken');
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('.mobile_number_exist').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									$('#email').blur(function () 
									{
										email = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>employee/EmailExist',
											type: 'post',
											data: {
												'email' : email,
												<?php
													if ($type == "edit") 
													{
														?>
														'id': '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.employee_email_exist_error').html('Email Alredy Taken');
													$('.register-but').prop('disabled',true);
													$(this).focus();
													return false;
												}else
												{
													$('.employee_email_exist_error').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});
									
									$('[data-type="passport-number"]').keyup(function() 
									{
										var passport = $(this).val();
										if (passport == "") {
											return;
										}
										var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
		
										if(regsaid.test(passport) == false)
										{
											document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
											$('.register-but').prop('disabled',true);
										}
										else
										{
											document.getElementById("passport_number_error").innerHTML = "";
											$('.register-but').prop('disabled',false);
										}

									});
								</script>
								<?php
							}
							else if(isset($id) && $id == 'address-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Address Information
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									
									
										<?php
											$country = $this->db->get_where('country', array('country_status' => '1'))->result_array();
										?>
										<script type="text/javascript">  
											function selectState(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectState';?>",
													data: { id: val }
													}).done(function( msg ) {   
														$("#state_id").html(msg);
														//$("#permenant_state_id").html(msg);
													});
												}
												else 
												{ 
													alert("No State under this Country!");
												}
											}
											
											function selectDistrict(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
													data: { id: val }
													}).done(function( msg ) {  
														$( "#district_id").html(msg);
														//$( "#permenant_district_id").html(msg);
													});
												}
												else 
												{ 
													alert("No districts under this state!");
												}
											}
											
											function selectCityorTown(val)
											{
											if(val !='')
											{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
													data: { id: val }
													}).done(function( msg ) {
														$( "#city_id").html(msg);
														//$( "#permenant_city_id").html(msg);
													});
												}
												else 
												{ 
													alert("No city/town's under this district!");
												}
											}

											function currentselectState(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectState';?>",
													data: { id: val }
													}).done(function( msg ) {   
														//$("#state_id").html(msg);
														$("#permenant_state_id").html(msg);
													});
												}
												else 
												{ 
													alert("No State under this Country!");
												}
											}
											
											function currentselectDistrict(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
													data: { id: val }
													}).done(function( msg ) {  
														//$( "#district_id").html(msg);
														$( "#permenant_district_id").html(msg);
													});
												}
												else 
												{ 
													alert("No districts under this state!");
												}
											}
											
											function currentselectCityorTown(val)
											{
											if(val !='')
											{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
													data: { id: val }
													}).done(function( msg ) {
														//$( "#city_id").html(msg);
														$( "#permenant_city_id").html(msg);
													});
												}
												else 
												{ 
													alert("No city/town's under this district!");
												}
											}
										</script>
										
											<div class="row">
												<div class="col-md-6">
													<span>Current Address</span>

													<div class="row mt-3">
														<div class="form-group col-md-3">
															<label class="col-form-label">Address 1 <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<textarea name="address" rows="1" id="address1" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">Address 2</label>
														</div>
														<div class="form-group col-md-6">
															<textarea name="address2" rows="1" id="address2" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] :"";?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">Address 3</label>
														</div>
														<div class="form-group col-md-6">
															<textarea name="address3" rows="1" id="address3" class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address'] :"";?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">Country <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<select name="country_id" id="country_id" required onchange="selectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
																<option value="">- Select Country -</option>
																<?php 
																	foreach($country as $row)
																	{
																		$selected="";
																		if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id']){
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																		<?php 
																	} 
																?>
															</select>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">State <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<select name="state_id" id="state_id" required onchange="selectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
																<option value="">- First Select Country -</option>
																<?php 
																	if($type == "edit" || $type == "add")
																	{
																		$country_id = isset( $edit_data[0]['country_id'] ) ?  $edit_data[0]['country_id'] : 0;
																		$state = $this->db->get_where('state', array('state_status' => '1','country_id' => $country_id))->result_array();
														
																		foreach($state as $row)
																		{
																			$selected="";
																			if(isset($edit_data[0]['state_id']) && $edit_data[0]['state_id'] == $row['state_id']){
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																			<?php 
																		}
																	}
																?>
															</select>
														</div>
													</div>
													<?php /*
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">District <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<select name="district_id" id="district_id" required onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
																<option value="">- First Select State -</option>
																<?php 
																	if( $type == "edit" || $type == "add" )
																	{
																		$state_id = isset( $edit_data[0]['state_id'] ) ?  $edit_data[0]['state_id'] : 0;
																		$district = $this->db->get_where('district', array('district_status' => '1','state_id' => $state_id))->result_array();
														
																		foreach($district as $row)
																		{
																			$selected="";
																			if( isset($edit_data[0]['district_id']) && $edit_data[0]['district_id'] == $row['district_id'])
																			{
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['district_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['district_name']);?></option>
																			<?php 
																		}
																	}
																?>
															</select>
														</div>
													</div>
													*/ ?>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">City </label>
														</div>
														<div class="form-group col-md-6">
															<select name="city_id" id="city_id" required --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
																<option value="">- First Select State -</option>
																<?php 
																	if( $type == "edit" || $type == "add" )
																	{
																		$state_id = isset( $edit_data[0]['state_id'] ) ?  $edit_data[0]['state_id'] : 0;

																		$city = $this->db->get_where('city', array('city_status' => '1','state_id' => $state_id))->result_array();
														
																		foreach($city as $row)
																		{
																			$selected="";
																			if( isset($edit_data[0]['city_id']) && $edit_data[0]['city_id'] == $row['city_id'])
																			{
																				$selected="selected='selected'";
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
														<div class="form-group col-md-3">
															<label class="col-form-label">Postal Code <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<input type="text" name="postal_code" id="postal_code" required autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
														</div>
													</div>
												</div>
													<script>
														$(document).ready(function()
														{
															$('input[name="chk_shipping_address"]').click(function()
															{
																if( $(this).prop("checked") == true ) //checked
																{
																	$('#permenant_address1').val( $('#address1').val() );
																	$('#permenant_address2').val( $('#address2').val() );
																	$('#permenant_address3').val( $('#address3').val() );
																	$('#permenant_postal_code').val( $('#postal_code').val() );
																	
																	var company_country1 = $('select#country_id option:selected').sort().clone();
																	$('select#permenant_country_id').append( company_country1 );
																	
																	var country_id = $('#country_id').val();
																	var permenant_country_id = $('#permenant_country_id').val();
																	
																	var state_id = $('#state_id').val();
																	var company_state_id = $('select#state_id option:selected').sort().clone();
																	$('select#permenant_state_id').append( company_state_id );
																	
																	var district_id = $('#district_id').val();
																	var company_district_id = $('select#district_id option:selected').sort().clone();
																	$('select#permenant_district_id').append( company_district_id );

																	var city_id = $('#city_id').val();
																	var company_city_id = $('select#city_id option:selected').sort().clone();
																	$('select#permenant_city_id').append( company_city_id );
																	
																	if(country_id == permenant_country_id);
																	{
																		$('select#permenant_country_id option[value='+country_id+']').attr('selected','selected');
																	}
																	
																	if(state_id !='');
																	{
																		$('select#permenant_state_id option[value='+state_id+']').attr('selected','selected');
																	}
																	
																	if(district_id !='');
																	{
																		$('select#permenant_district_id option[value='+district_id+']').attr('selected','selected');
																	}
																	if(city_id !='');
																	{
																		$('select#permenant_city_id option[value='+city_id+']').attr('selected','selected');
																	}
																}
																else if( $(this).prop("checked") == false ) //Unchecked
																{
																	var permenant_country_id = $('#permenant_country_id').val();
																	$("select#permenant_country_id option[value='"+permenant_country_id+"']:last").remove();
																	
																	$( "#permenant_state_id").html('<option value="">- First Select Country -</option>');
																	$( "#permenant_district_id").html('<option value="">- First Select District -</option>');
																	$( "#permenant_city_id").html('<option value="">- First Select State -</option>');
																	
																	$('#permenant_address').val('');
																	$('#permenant_postal_code').val('');
																	$('#permenant_address1').val('');
																	$('#permenant_address2').val('');
																	$('#permenant_address3').val('');
																}
															});
														});
													</script>
												
													<div class="col-md-6">
														
														<div --class="new-design-2">
															<span>Permenant Address
															<?php 
																$checked_shipping_address ="";
																if( isset($edit_data[0]['chk_shipping_address']) && $edit_data[0]['chk_shipping_address'] == 1 )
																{
																	$checked_shipping_address ="checked='checked'";
																} 
															?>
															&nbsp; &nbsp; <input type="checkbox" name="chk_shipping_address" value='1' id="chk_shipping_address" <?php echo $checked_shipping_address;?>>&nbsp; &nbsp; <span style="color:#c7c7ce;font-size: 11px;">Copy Current Address</span></span>
														</div> 
														<div class="row mt-3">
															<div class="form-group col-md-3">
																<label class="col-form-label">Address 1</label>
															</div>
															<div class="form-group col-md-6">
																<textarea name="permenant_address1" rows="1" id="permenant_address1"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address']) ? $edit_data[0]['permenant_address'] :"";?></textarea>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">Address 2</label>
															</div>
															<div class="form-group col-md-6">
																<textarea name="permenant_address2" rows="1" id="permenant_address2"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address']) ? $edit_data[0]['permenant_address'] :"";?></textarea>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">Address 3</label>
															</div>
															<div class="form-group col-md-6">
																<textarea name="permenant_address3" rows="1" id="permenant_address3"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address']) ? $edit_data[0]['permenant_address'] :"";?></textarea>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">Country </label>
															</div>
															<div class="form-group col-md-6">
																<select name="permenant_country_id" id="permenant_country_id" onchange="currentselectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
																	<option value="">- Select Country -</option>
																	<?php 
																		foreach($country as $row)
																		{
																			$selected="";
																			if(isset($edit_data[0]['permenant_country_id']) && $edit_data[0]['permenant_country_id'] == $row['country_id']){
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																			<?php 
																		} 
																	?>
																</select>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">State </label>
															</div>
															<div class="form-group col-md-6">
																<select name="permenant_state_id" id="permenant_state_id" onchange="currentselectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
																	<option value="">- First Select Country -</option>
																	<?php 
																		if($type == "edit" || $type == "add")
																		{
																			$country_id = isset($edit_data[0]['country_id']) ? $edit_data[0]['country_id'] : 0;
																			$state = $this->db->get_where('state', array('state_status' => '1','country_id' => $country_id))->result_array();
															
																			foreach($state as $row)
																			{
																				$selected="";
																				if(isset($edit_data[0]['permenant_state_id']) && $edit_data[0]['permenant_state_id'] == $row['state_id']){
																					$selected="selected='selected'";
																				}
																				?>
																				<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																				<?php 
																			}
																		}
																	?>
																</select>
															</div>
														</div>
														<?php /*
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">District </label>
															</div>
															<div class="form-group col-md-6">
																<select name="permenant_district_id" id="permenant_district_id" onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
																	<option value="">- First Select District -</option>
																	<?php 
																		if($type == "add" || $type == "edit")
																		{
																			$state_id = isset($edit_data[0]['state_id']) ? $edit_data[0]['state_id'] : 0;
																			$district = $this->db->get_where('district', array('district_status' => '1','state_id' => $state_id))->result_array();
																			
																			foreach($district as $row)
																			{
																				$selected="";
																				if( isset($edit_data[0]['permenant_district_id']) && $edit_data[0]['permenant_district_id'] == $row['district_id'])
																				{
																					$selected="selected='selected'";
																				}
																				?>
																				<option value="<?php echo $row['district_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['district_name']);?></option>
																				<?php 
																			}
																		}
																	?>
																</select>
															</div>
														</div>
														*/ ?>
														<div class="row">
															<div class="form-group col-md-3">
																<label class="col-form-label">City </label>
															</div>
															<div class="form-group col-md-6">
																<select name="permenant_city_id" id="permenant_city_id" --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
																	<option value="">- First Select State -</option>
																	<?php 
																		if($type == "add" || $type == "edit")
																		{
																			$state_id = isset($edit_data[0]['state_id']) ? $edit_data[0]['state_id'] : 0;
																			
																			$city = $this->db->get_where('city', array('city_status' => '1','state_id' => $state_id))->result_array();
															
																			foreach($city as $row)
																			{
																				$selected="";
																				if( isset($edit_data[0]['permenant_city_id']) && $edit_data[0]['permenant_city_id'] == $row['city_id'])
																				{
																					$selected="selected='selected'";
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
															<div class="form-group col-md-3">
																<label class="col-form-label">Postal Code</label>
															</div>
															<div class="form-group col-md-6">
																<input type="text" name="permenant_postal_code" id="permenant_postal_code" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['permenant_postal_code']) ? $edit_data[0]['permenant_postal_code'] :"";?>" placeholder="">
															</div>
														</div>
													</div>
												
											</div>
										

										<input type="hidden" name="address1" id="address1" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?>" placeholder="">
										<?php /* <div class="form-group col-md-3">
											<label class="col-form-label">Permenant Address</label>
											<textarea name="address1" rows="1" id="address1"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
										</div> */ ?>
										
									

									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="btn btn-light btn-sm">Back</a>
										<?php 
											if($type == "edit")
											{
												?>
												<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
												<?php 
											}
											else
											{
												?>
												<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
												<?php 
											}
										?>
									</div>
								</form>
								<?php
							}
							else if(isset($id) && $id == 'bank-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Add Bank Detail
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Save & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_number" data-type="account_number" --maxlength="14" class="form-control" required autocomplete="off" value="<?php echo isset($edit_data[0]['account_number']) ? $edit_data[0]['account_number'] :"";?>" placeholder="">
												</div>
											</div>	
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c Holder Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_name" data-type="account_name" --maxlength="14" class="form-control" required autocomplete="off" value="<?php echo isset($edit_data[0]['account_name']) ? $edit_data[0]['account_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Bank Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="bank_name" id="bank_name" autocomplete="off" <?php echo $this->validation; ?> required class="form-control" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Bank Branch </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="branch_name" id="branch_name" --data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">IFSC Code <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="ifsc_code" id="ifsc_code" required class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] :"";?>" placeholder="">
													<span id="ifsc_code_val" class="small" style="color:red;float:left;width:100%;">(Ex : IDIB000A114)</span>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">MICR Code </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="micr_code" id="micr_code" maxlength="10" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['micr_code']) ? $edit_data[0]['micr_code'] :"";?>" placeholder="">
													<span class="small" id="micr_code_val" style="color:red;">(Ex : 600019003)</span>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Address</label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address" id="address" class="form-control" value=""><?php echo isset($edit_data[0]['address']) ? $edit_data[0]['address'] :"";?></textarea>
												</div>
											</div>
										</div>
									</div>

									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light btn-sm">Cancel</a>
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="btn btn-light btn-sm">Back</a>
										
										<?php 
											if($type == "edit")
											{
												?>
												<button type="submit" name="save_only_update" class="btn btn-primary ml-1 btn-sm register-but">Update</button>
												<?php 
											}
											else
											{
												?>
												<input type="submit" name="save_only" value="Save" class="btn btn-primary ml-1 btn-sm register-but">
												<?php 
											}
										?>
									</div>
								</form>
								<script type="text/javascript">
								/*
									$('[data-type="adhaar-number"]').keyup(function() 
									{
										var value = $(this).val();
										value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
										$(this).val(value);

										aadhaar_number_val = value;
										$.ajax({
											url: '<?php echo base_url();?>employee/aadhaarUnique',
											type: 'post',
											data: {
												'account_number' : account_number_val,
												'type'		 : '<?php echo $type ?>',
												<?php
													if ($type == "edit") 
													{
														?>
														'id' : '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'already_taken') 
												{
													$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
													// Obj.focus();
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									function ValidatePAN() 
									{ 
										var Obj = document.getElementById("textPanNo");
										if (Obj.value != "") 
										{
											ObjVal = Obj.value;
											var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
											if (ObjVal.search(panPat) == -1) 
											{
												alert("Please Enter Valid PAN NO.");
												$('#textPanNo').val('');
												//Obj.focus();
												return false;
											}
											else
											{
												pan_number = $('#textPanNo').val();
												$.ajax({
													url: '<?php echo base_url();?>employee/panUnique',
													type: 'post',
													data: {
														'pan_number' : pan_number,
														'type'		 : '<?php echo $type ?>',
														<?php
															if ($type == "edit") 
															{
																?>
																'id'  : '<?php echo $id ?>'
																<?php
															}
														?>
													},
													success: function(response)
													{
														if (response == 'already_taken') 
														{
															$('#pan_number_val').html('PAN Number Alredy Taken');
															//Obj.focus();
															$('.register-but').prop('disabled',true);
															return false;
														}
														else
														{
															$('#pan_number_val').html('(Ex : ABCDE1234F)');
															$('.register-but').prop('disabled',false);
															return true;
														}
													}
												});

											}
										}
										else
										{
											$('#pan_number_val').html('(Ex : ABCDE1234F)');
										}
									} 
						
									$('#mobile_number').blur(function () 
									{
										mobile_number = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>vendor/MobileExist',
											type: 'post',
											data: {
												'mobile_number' : mobile_number,
												<?php
													if ($type == "edit") 
													{
														?>
															'id'		 : '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.mobile_number_exist').html('Mobile Number Alredy Taken');
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('.mobile_number_exist').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									$('#email').blur(function () 
									{
										email = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>employee/EmailExist',
											type: 'post',
											data: {
												'email' : email,
												<?php
													if ($type == "edit") 
													{
														?>
														'id': '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.employee_email_exist_error').html('Email Alredy Taken');
													$('.register-but').prop('disabled',true);
													$(this).focus();
													return false;
												}else
												{
													$('.employee_email_exist_error').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});
									
									$('[data-type="passport-number"]').keyup(function() 
									{
										var passport = $(this).val();
										if (passport == "") {
											return;
										}
										var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
		
										if(regsaid.test(passport) == false)
										{
											document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
											$('.register-but').prop('disabled',true);
										}
										else
										{
											document.getElementById("passport_number_error").innerHTML = "";
											$('.register-but').prop('disabled',false);
										}

									}); */
								</script>
								<?php
							}
							else if (isset($id) && $id == "login-info")
							{
								?>
									<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
										<?php
											if($emp_type == "add")
											{
												?>
												<div class="createDiv">
													<legend class="text-uppercase font-size-sm font-weight-bold">Login Details</legend>
													<div class="row">
														<?php /* <div class="form-group col-md-3">
															<label class="col-form-label">User Name<span class="text-danger">*</span></label>
															<div class="">
																<input type="text" name="user_name" autocomplete="off" readonly required id="user_name" class="form-control" value="<?php echo isset($results[0]['email']) ? $results[0]['email'] :"--";?>" placeholder="">
																<span class="user_name_exist_error error"></span>
															</div>
														</div> */ ?>
														
														<div class="form-group col-md-3">
															<label class="col-form-label">Password <span class="text-danger">*</span></label>
															<input type="password" name="password" required class="form-control" value="" placeholder="">
														</div>
													</div>
												</div>
												<?php 
											}
											else if($emp_type == "edit")
											{
												?>
												<legend class="text-uppercase font-size-sm font-weight-bold">Login Details</legend>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">User Name</label>
														<input type="text" name="user_name" id="user_name" readonly required class="form-control" value="<?php echo isset($edit_data[0]['user_name']) ? $edit_data[0]['user_name'] :$random_user_id;?>" placeholder="">
														<span class="user_name_exist_error error"></span>
													</div>
												</div>
												<?php 
											} 
										?>
										<div class="d-flexad" style="text-align:right;">
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light">Cancel  </a>
											<?php 
												if($type == "edit")
												{
													?>
													<button type="submit" class="btn btn-primary  register-but ml-3">Update</button>
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
								<?php
							}
							
							?>
						</div>
					</div>
							<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
							<!-- <form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post"> -->
								<div class="row">
									<!-- left side start -->
									<div class="col-sm-12 col-md-12">
										<!-- Customer Details start -->
										<fieldset>
											<!-- end-->
											<?php /* <div class="card">
												<div class="card-body">
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label"> Bank A/c No </label>
															<input type="text" name="bank_account_no" --required autocomplete="off" class="form-control mobile_vali" maxlength="15" value="<?php echo isset($edit_data[0]['bank_account_no']) ? $edit_data[0]['bank_account_no'] :"";?>" placeholder="">
														</div>
														
														<div class="form-group col-md-3">
															<label class="col-form-label">Bank Name </label>
															<input type="text" name="bank_name" --required autocomplete="off" class="form-control only_name" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] :"";?>" placeholder="">
														</div>
														
														<div class="form-group col-md-3">
															<label class="col-form-label">IFSC Code</label>
															<input type="text" name="ifsc_code" --required id="ifsc_code" autocomplete="off" class="form-control special_vali" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] :"";?>" placeholder="">
															<span class="small note-color">(Ex : IDIB000A114)</span>
														</div>
														<div class="form-group col-md-3">
															<label class="col-form-label">Branch</label>
															<input type="text" name="branch" --required class="form-control only_name" value="<?php echo isset($edit_data[0]['branch']) ? $edit_data[0]['branch'] :"";?>" placeholder=""  autocomplete="off">
														</div>
													</div>
												</div> 
											</div> */ ?>
											
										</fieldset>
										
										<fieldset> 
											<div class="row">
												<?php 
													/*$required ="";
													if ($type =="add") 
													{
														$required = 'required';
													}*/
												?>
												
												<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;">Upload Documents:</label> -->
												<!-- <legend class="text-uppercase font-size-sm font-weight-bold">
													Upload Documents:
												</legend> -->
												<div class="row">
													<?php /* <div class="form-group col-md-3">
														<label class="col-form-label">Identity Proof<span class="text-danger">*</span></label>
														<input type="file" name="identity_proof" id="identity_proof" accept="image/*" <?php echo $required ?>   onchange="return validateSingleFileExtension(this)" autocomplete="off" <?php echo $this->validation; ?> class="form-control singleImage" >
														<span class="note-class"><b>Note</b> : Proof upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span><br>
														<span class="small" style="color:red;">(Ex : Voter Id)</span>
														<?php 
															if ($type == "edit" && file_exists('uploads/employee/identity_proof/'.$id.'.png')) 
															{
																?>
																	<div class="mt-2">
																		<img src="<?php echo base_url()?>uploads/employee/identity_proof/<?php echo $id ?>.png" width="110px" height="110px" alt="Identity Proof">
																	</div>
																<?php
															}
														?>
													</div>
													
													<div class="form-group col-md-3"> 
														<label class="col-form-label">Address Proof<span class="text-danger">*</span></label>
														<input type="file" name="address_proof" id="address_proof" accept="image/*" <?php echo $required ?>   autocomplete="off"  onchange="return validateSingleFileExtension(this)" <?php echo $this->validation; ?> class="form-control singleImage" autocomplete="off" >
														<span class="note-class"><b>Note</b> : Proof upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span><br>
														<span class="small" style="color:red;">(Ex : Aadhar Card)</span>

														<?php 
															if ($type == "edit" && file_exists('uploads/employee/address_proof/'.$id.'.png')) 
															{
																?>
																	<div class="mt-2">
																		<img src="<?php echo base_url()?>uploads/employee/address_proof/<?php echo $id?>.png"  width="110px" height="110px" alt="Address Proof">
																	</div>
																<?php
															}
														?>
													</div>
													*/ ?>
													
													<!-- <div class="form-group col-md-3">
														<label class="col-form-label">Passport Size Photo<span class="text-danger">*</span></label>
														<input type="file" name="passport_photo" id="passport_photo" accept="image/*" <?php echo $required ?>  autocomplete="off" <?php echo $this->validation; ?>  onchange="return validateSingleFileExtension(this)" class="form-control singleImage" autocomplete="off"  >
														<span class="note-class"><b>Note</b> : Proof upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span><br>

														<?php 
															if ($type == "edit" && file_exists('uploads/employee/passport_photo/'.$id.'.png')) 
															{
																?>
																	<div class="mt-3 pt-3">
																		<img src="<?php echo base_url()?>uploads/employee/passport_photo/<?php echo $id?>.png" width="110px" height="110px" alt="Passport Size Photo">
																	</div>
																<?php
															}
														?>
													</div> -->
													<script>
														/** Single Image Type & Size Validation **/
														function validateSingleFileExtension(fld) 
														{
															var fileUpload = fld;
															
															if (typeof (fileUpload.files) != "undefined")
															{
																var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
																var validSize = 1024 * 2; //1024 - 1Mb multiply 4mb
																
																if( size > validSize )
																{
																	alert("Proof upload size is 1 MB");
																	$('.singleImage').val('');
																	$(this).val('');
																	var value = 1;
																	return false;
																}
																else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
																{
																	alert("Invalid Proof file type.");      
																	$('.singleImage').val('');
																	return false;   
																}
																
																if(value != 1)	
																	return true; 
															}
														}
													</script>
												</div>
											</div>
											
											<?php /*
											<!-- Family details start-->
											<label>
												<b>Family Details</b>
											</label>
											<?php 
												if( $type =="add" )
												{
													?>
													<div class="row">
														<div class="form-group col-md-9">
															<div id="ActionItem">
																<div class="action_inputs">
																	<div class="row">
																		<div class="col-md-4">
																			<label class="col-form-label">Name</label>
																			<input type="text" name="name[]" id="name_0" placeholder="Name" class="form-control">
																		</div>
																		<div class="col-md-4">
																			<label class="col-form-label">Address</label>
																			<textarea rows='1' name="nominee_address[]" id="address_0" placeholder="Address" class="form-control"></textarea>
																		</div>
																		<div class="col-md-4">
																			<label class="col-form-label">Relation</label>
																			<input type="text" name="relation[]" id="relation_0" placeholder="Relation" class="form-control">
																		</div>
																	</div>
																	
																	<div class="row">
																		<div class="col-md-4">
																			<label class="col-form-label">Contact Number</label>
																			<input type="text" name="contact_number[]" id="contact_number_0" placeholder="Contact Number" class="form-control">
																		</div>
																		<div class="col-md-4">
																			<label class="col-form-label">DOB</label>
																			<input type="date" name="dob[]" id="dob_0" placeholder="DOB" class="form-control">
																		</div>
																		<div class="col-md-4">
																			<label class="col-form-label">Upload Doc</label>
																			<input type="file" name="nominee_upload_document[]" id="upload_document_0" class="form-control">
																		</div>
																	</div>
																</div>
																
																<div class="add-remove-btn" style="float:right;padding: 20px 0px 0px 4px;">
																	<input type="button" id="action_file_remove" class="btn btn-warning" value="Remove More">
																	<input type="button" id="action_file_add" class="btn btn-info" value="Add More">
																</div>
															</div>
														</div>
													</div>
													<?php 
												}
												else if($type =="edit")
												{
													?>
													<div class="row">
														<div class="form-group col-md-9">
															<div id="ActionItem">
																<div class="action_inputs">
																	<?php 
																		$empFamilyDetails = "select * from employee_family_details where user_id='".$id."' ";
																		$getFamilyDetails = $this->db->query($empFamilyDetails)->result_array();
																		if(count($getFamilyDetails) > 0)
																		{
																			$j=0;
																			foreach($getFamilyDetails as $nominee)
																			{
																				?>
																				<?php if($j !=0){?>
																				<div class="action_field file-right"> 
																				<?php } ?>
																				<div class="row mt-3">
																					<div class="col-md-4">
																						<?php if($j ==0){?><label class="col-form-label">Name</label><?php } ?>
																						<input type="text" name="name[]" id="name_<?php echo $j;?>" value="<?php echo ucfirst($nominee['name']);?>" placeholder="Name" class="form-control">
																					</div>
																					<div class="col-md-4">
																						<?php if($j ==0){?><label class="col-form-label">Address</label><?php } ?>
																						<textarea rows='1' name="nominee_address[]" id="address_<?php echo $j;?>" placeholder="Address" class="form-control"><?php echo ucfirst($nominee['address']);?></textarea>
																					</div>
																					<div class="col-md-4">
																						<?php if($j ==0){?><label class="col-form-label">Relation</label><?php } ?>
																						<input type="text" name="relation[]" id="relation_<?php echo $j;?>" value="<?php echo ucfirst($nominee['relation']);?>" placeholder="Relation" class="form-control">
																					</div>
																				</div>
																				
																				<div class="row mt-3 mb-5">
																					<div class="col-md-4">
																						<?php if($j ==0){?><label class="col-form-label">Contact Number</label><?php } ?>
																						<input type="text" name="contact_number[]" id="contact_number_<?php echo $j;?>" value="<?php echo ucfirst($nominee['contact_number']);?>" placeholder="Contact Number" class="form-control">
																					</div>
																					
																					<div class="col-md-4">
																						<?php if($j ==0){?><label class="col-form-label">DOB <?php #echo date("m/d/Y",$action['target_date']);?></label><?php } ?>
																						<input type="date" name="dob[]" id="dob_<?php echo $j;?>" value="<?php echo date("Y-m-d",$nominee['dob']);?>" placeholder="Dob" class="form-control">
																					</div>
																					
																					<div class="col-md-4">
																						<?php if($j ==0){?><label class="col-form-label">Upload Doc</label><?php } ?>
																						<input type="file" name="nominee_upload_document[]" id="upload_document_<?php echo $j;?>" class="form-control">
																						<input type="hidden" value="<?php echo $nominee['upload_document'];?>" name="upload_document_2[]" id="upload_document_2_<?php echo $j;?>" class="form-control">
																						<?php
																							
																							if(!empty($nominee['upload_document']) && file_exists("uploads/employee/family_documents/".$nominee['upload_document']) )
																							{
																								?>
																								<a href="<?php echo base_url();?>uploads/employee/family_documents/<?php echo $nominee['upload_document'];?>" download title="download">
																									Download <i class="fa fa-download"></i>
																								</a>
																								<?php
																							}
																						?>
																					</div>
																				</div>
																				<?php if($j !=0){?>
																				</div>
																				<?php } ?>
																				<?php 
																				$j++;
																			}
																		}
																		else
																		{ 
																			?>
																			<div class="row">
																				<div class="col-md-4">
																					<label class="col-form-label">Name</label>
																					<input type="text" name="name[]" id="name_0" placeholder="Name" class="form-control">
																				</div>
																				<div class="col-md-4">
																					<label class="col-form-label">Address</label>
																					<textarea rows='1' name="nominee_address[]" id="address_0" placeholder="Address" class="form-control"></textarea>
																				</div>
																				<div class="col-md-4">
																					<label class="col-form-label">Relation</label>
																					<input type="text" name="relation[]" id="relation_0" placeholder="Relation" class="form-control">
																				</div>
																			</div>
																			
																			<div class="row">
																				<div class="col-md-4">
																					<label class="col-form-label">Contact Number</label>
																					<input type="text" name="contact_number[]" id="contact_number_0" placeholder="Contact Number" class="form-control">
																				</div>
																				<div class="col-md-4">
																					<label class="col-form-label">DOB</label>
																					<input type="date" name="dob[]" id="dob_0" placeholder="DOB" class="form-control">
																				</div>
																				<div class="col-md-4">
																					<label class="col-form-label">Upload Doc</label>
																					<input type="file" name="nominee_upload_document[]" --onchange="return validateSinglePDFImageExtension(this)" id="upload_document_0" class="form-control">
																				</div>
																			</div>
																			<?php 
																		} 
																	?>
																</div>
																
																<div class="add-remove-btn" style="float:right;padding: 20px 0px 0px 4px;">
																	<input type="button" id="action_file_remove" class="btn btn-warning" value="Remove More">
																	<input type="button" id="action_file_add" class="btn btn-info" value="Add More">
																</div>
															</div>
														</div>
													</div>
													<?php 
												}							
											?>
											<script>
												$(document).ready(function()
												{
													//Nominee start
													var type = '<?php echo $type;?>';
													
													if(type == 'edit')
													{
														var j = '<?php echo isset($getFamilyDetails) ? count($getFamilyDetails) : 0;?>';
														
														if( j > 1 )
														{
															$('#action_file_remove').show();
														}
														else
														{
															$('#action_file_remove').hide();
														}
													}
													else
													{
														$('#action_file_remove').hide();
														var j = 1;
													}
													
													$('#action_file_add').click(function() 
													{
														$('#action_file_remove').show();
														
														if(j != 5)  
														{	
															$('<div class="action_field file-right"> <div class="row mt-5"><div class="col-md-4"><input type="text" name="name[]" id="name_'+j+'" placeholder="Name" class="form-control"></div><div class="col-md-4"><textarea rows="1" name="nominee_address[]" id="address_'+j+'" placeholder="Address" class="form-control"></textarea></div><div class="col-md-4"><input type="text" name="relation[]" id="relation_'+j+'" placeholder="Relation" class="form-control"></div></div><div class="row mt-3"><div class="col-md-4"><input type="text" name="contact_number[]" id="contact_number_'+j+'" placeholder="Contact Number" class="form-control"></div><div class="col-md-4"><input type="date" name="dob[]" id="dob_'+j+'" placeholder="DOB" class="form-control"></div><div class="col-md-4"><input type="file" name="nominee_upload_document[]" id="upload_document_'+j+'" placeholder="Upload_document" class="form-control"></div></div></div>').fadeIn("slow").appendTo('.action_inputs');
															j++;
															if(j == 5)
															{
																$('#action_file_add').hide();
															}
														}
														else
														{ 
															$('#action_file_add').hide(); 
														} 
													});
													
													$('#action_file_remove').click(function() 
													{
														if(j > 1) 
														{
															$('#action_file_add').show();
															$('.action_field:last').remove();
															j--;
															
															if(j==1)
															{
																$('#action_file_remove').hide();
															}
														}
														else if(j == 1)
														{
															alert('No more to remove');
															j = 1;
															return false;
														}
													});
													//Nominee End
												});
											</script>
											<!-- Family details end-->
											*/ ?>
													
											<!-- Proof attached start here-->
											<?php /*
											<hr>
											<?php 
												$categories = $this->db->query('select category_id, category_name, required_type from user_document_categories where category_status=1 and category_type=3')->result_array();
											
												if(isset($type) && $type == 'add')
												{
													$required='required';
												}
												else if(isset($type) && $type == 'edit')
												{
													$user_id = isset($edit_data[0]['user_id']) ? $edit_data[0]['user_id'] : 0;
													$checkDocuments = $this->db->query("
														select 
															user_document_attachments.category_id,
															user_document_attachments.user_id,
															user_document_attachments.image_2,
															user_document_attachments.caption,
															user_document_attachments.document_type,
															user_document_categories.category_name

															from user_document_attachments 
														
														left join user_document_categories on
															user_document_categories.category_id =  user_document_attachments.category_id
														where
															user_document_attachments.user_id ='".$user_id."'
														")->result_array();
													if(count($checkDocuments) > 0)	
													{
														$required='';
													}
													else
													{
														$required='required';
													}
												}
											?>
											<div class="row form-group mt-4">
												<label class="col-form-label col-lg-2">Documents </label>
												<div class="col-lg-3">
													<select class="form-control searchDropdown" <?php //echo $required;?> id="documents" name="documents">
														<option value="">- Select Document -</option>					  
														<?php
															foreach($categories as $category) 
															{
																?>
																<option value="<?php echo $category['category_id'];?>"><?php echo ucfirst($category['category_name']);?></option>
																<?php
															}
														?>
													</select>
												</div>
											</div>
											
											<div class="row mt-4 mb-4">
												<div class="col-sm-12">
													<div class="form-group">
														<div style="overflow-y: auto;">
															<div id="err_product" style="color:red;margin: 0px 0px 10px 0px;"></div>
															<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
																<thead>
																	<tr>
																		<th colspan="13">
																			Attached Documents <span style="color:#969292;">( Upload Documents : png, bmp, gif, jpg, jpeg, pdf and Size is 4MB. )</span>
																		</th>
																	</tr>
																	<tr>
																		<th style="width:30px;"> </th>
																		<th>Document Name</th>
																		<th>Upload Document</th>
																		<td>Document Type</td>
																		<th>Description</th>
																	</tr>
																</thead>
																<tbody id="product_table_body">
																	<?php
																		if( isset($type) && $type == "edit" )
																		{
																			$user_id = isset($edit_data[0]['user_id']) ? $edit_data[0]['user_id'] : 0;
																			$checkDocuments = $this->db->query("
																				select 
																					user_document_attachments.attachement_id,
																					user_document_attachments.category_id,
																					user_document_attachments.user_id,
																					user_document_attachments.image_2,
																					user_document_attachments.caption,
																					user_document_attachments.description,
																					user_document_attachments.document_type,
																					user_document_categories.category_name

																					from user_document_attachments 
																				
																				left join user_document_categories on
																					user_document_categories.category_id =  user_document_attachments.category_id
																				where
																					user_document_attachments.user_id ='".$user_id."'
																				")->result_array();
																				
																			if( count($checkDocuments) > 0)
																			{
																				$i=0;
																				$counter=1;
																				foreach($checkDocuments as $documents)
																				{
																					?>
																					<tr class="dataRowVal<?php echo $documents['category_id']; ?>">
																						<td>
																							<a class='deleteRow1'> 
																								<i class="fa fa-trash"></i> 
																							</a>
																							<input type='hidden' name='image_2[]' value="<?php echo $documents['image_2']; ?>">
																							<input type='hidden' name='attachement_id[]' value="<?php echo $documents['attachement_id']; ?>">
																							<input type='hidden' name='id' name='id' value="<?php echo $i ?>">
																							<input type='hidden' name='category_id[]' value="<?php echo $documents['category_id']; ?>">
																						</td>
																						<td><?php echo $documents['category_name']; ?></td>
																						
																						<td>
																							<input type='file' class='form-control' name='upload_document[]' onchange="return validateFileExtension(this,<?php echo $counter;?>)" id='first_<?php echo $counter;?>' >
																							<?php
																								if(!empty($documents['image_2']) && file_exists("uploads/document_attachments/".$documents['image_2']) )
																								{
																									?>
																									<a href="<?php echo base_url()?>uploads/document_attachments/<?php echo $documents['image_2'];?>" download title="download">Download <i class="fa fa-download"></i></a>
																									<?php
																								}
																							?>
																						</td>
																						
																						<td>
																							<select class='form-control' id='document_type' name='document_type[]'>
																								<option value=''>- Select Document Type -</option>
																								<?php
																									foreach($this->document_type as $key => $value)
																									{
																										$selected="";
																										if($documents['document_type'] == $key)
																										{
																											$selected="selected='selected'";
																										}
																										?>
																										<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
																										<?php 
																									} 
																								?>
																							</select>
																						</td>
																						
																						<td>
																							<textarea rows="1" class='form-control' name='description[]' id='description_<?php echo $counter;?>'><?php echo $documents['description']; ?></textarea>
																						</td>
																					</tr>
																					<?php 
																					$counter++;
																					$i++;
																				} 
																			} 
																		} 
																	?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											
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
														var counter1 = '<?php echo isset($checkDocuments) ? count($checkDocuments) : 1; ?>';
														
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
															var counter = '<?php echo isset($checkDocuments) ? count($checkDocuments) : 1; ?>';
														}
													}
													
													$('#documents').change(function()
													{
														var id = $(this).val();
														$('#err_product').text('');
														var flag = 0;
														
														if(id != "")
														{
															$.ajax({
																url: "<?php echo base_url('employee/getAttachedDocuments') ?>/"+id,
																type: "GET",
																data:{
																	'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
																},
																datatype: "JSON",
																success: function(d)
																{
																	data = JSON.parse(d);
																	$("table.product_table").find('input[name^="category_id"]').each(function () 
																	{
																		if(data[0].category_id  == +$(this).val())
																		{
																			flag = 1;
																		}
																	});
																	
																	if(flag == 0)
																	{
																		var id = data[0].category_id;
																		var category_name = data[0].category_name;
																		var document_type = data['documentType'];
																		var newRow = $("<tr class='dataRowVal"+id+"'>");
																		var cols = "";
																		cols += "<td><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='category_id[]' value="+id+"></td>";
																		cols += "<td class='tab-medium-width'>"+category_name+"</td>";
																		cols += "<td class='text-center'>"
																			+"<input type='file' required class='form-control' onchange='return validateFileExtension(this,"+ counter +")' name='upload_document[]' id='first_"+ counter +"' >"
																			+"</td>";
																			
																		cols += "<td class='tab-medium-width'>"+document_type+"</td>";
																
																		cols += "<td class='text-center'>"
																			+"<textarea rows='1' class='form-control' name='description[]' id='description_"+ counter +"'></textarea>"
																			+"</td>";
																			
																		cols += "</tr>";
																		counter++;

																		newRow.html(cols);
																		$("table.product_table").append(newRow);
																		var table_data = JSON.stringify(product_data);
																		$('#table_data').val(table_data);
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
														$(this).closest("tr").remove();
													});
													
													//$("table.product_table").on("click", "a.deleteRow1", function (event) 
													//{
														//deleteRow1($(this).closest("tr"));
														//$(this).closest("tr").remove();
														//calculateGrandTotal();
													//});
												});
											</script>
												
											<!-- Proof attached end here -->
											*/ ?>
										</fieldset>
									</div>
									<!-- Customer Details end -->
								</div>
								
								<script type="text/javascript">    
									$(document).ready(function(){     
										$("#ifsc_code").change(function () {      
										var inputvalues = $(this).val();      
										var reg = /[A-Z|a-z]{4}[a-zA-Z0-9]{7}$/;    
											if (inputvalues.match(reg)) {    
												return true;    
											}    
											else {    
													$("#ifsc_code").val("");    
												alert("You entered invalid IFSC code");    
												//document.getElementById("txtifsc").focus();    
												return false;    
											}    
										});      
									});    

									function LicenceNumber(fieldname)
									{				
										if (fieldname.value == "") 
										{
											$('#licence_number_val').html('(Ex : TN-0619850034761 )');
											$('.register-but').prop('disabled',false);
											return
										}					
										regsaid = /^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}-[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$/;
										if(regsaid.test(fieldname.value) == false)
										{
											$('#licence_number_val').html('Licence Number Not Valid');
											$('.register-but').prop('disabled',true);
											return false;
										}
										else
										{
											$('#licence_number_val').html('(Ex : TN-0619850034761 )');
											$('.register-but').prop('disabled',false);
										}
									}

									function voterId(fieldname)
									{				
										if (fieldname.value == "") 
										{
											$('#voterid_number_val').html('(Ex : SRD0676361)');
											$('.register-but').prop('disabled',false);
											return
										}					
										regsaid = /^([a-zA-Z]){3}([0-9]){7}?$/;
										if(regsaid.test(fieldname.value) == false)
										{
											$('#voterid_number_val').html('Voter ID is Invalid!');
											$('.register-but').prop('disabled',true);
											return false;
										}
										else
										{
											$('#voterid_number_val').html('(Ex : SRD0676361)');
											$('.register-but').prop('disabled',false);
										}
									} 

									var $field1 = $("#mobile_number");
									var $field2 = $("#mobile_number_1");

									$field1.on("keydown",function()
									{
										setTimeout(checkValue,0); 
									});

									var v2 = $field2.val();
									var checkValue = function(){
										var v1 = $field1.val();
										if (v1 != v2){
											$field2.val(v1);
											v2 = v1;
										}
									};
								</script>
							<?php /* </form> */ ?>
						<?php
				}
				else if(isset($type) && $type == "edit")
				{
					
					?>
					<?php 
						$activeBasic = $activeCareer = 
						$activeID = $activeAddress = $activeBank =
						$activeLogin= '';
						if( isset($type) )
						{
							if( $id == 'basic-info' )
							{
								$activeBasic = 'active';
							}
							else if( $id == 'career-info' )
							{
								$activeCareer = 'active';
							}
							else if( $id == 'id-info' )
							{
								$activeID = 'active';
							}
							else if( $id == 'address-info' )
							{
								$activeAddress = 'active';
							}
							else if( $id == 'bank-info' )
							{
								$activeBank = 'active';
							}
							else if( $id == 'login-info' )
							{
								$activeLogin = 'active';
							}
						}
					?>
					<div class="card">
						<div class="card-body">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item <?php echo $activeBasic;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/basic-info/<?php echo $status;?>" class="nav-link">Basic Info</a>
								</li>
								
								<li class="nav-item <?php echo $activeCareer;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/career-info/<?php echo $status;?>" class="nav-link">Employee Details</a>
								</li>
								
								<li class="nav-item <?php echo $activeID;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/id-info/<?php echo $status;?>" class="nav-link">Identity</a>
								</li>
								
								<li class="nav-item <?php echo $activeAddress;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/address-info/<?php echo $status;?>" class="nav-link">Address</a>
								</li>
								
								<li class="nav-item <?php echo $activeBank;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/bank-info/<?php echo $status;?>" class="nav-link">Bank Details</a>
								</li>
								<?php /*
								<li class="nav-item <?php //echo $activeSystemSettings;?>">
									<a href="<?php echo base_url(); ?>employee/ManageEmployee/<?php echo $type;?>/login-info" class="nav-link">Login</a>
								</li> */ ?>
							</ul>
					
							<?php	
							if(isset($id) && $id == 'basic-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Basic Information 
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Update & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
										<div class="row">
											<div class="col-md-6">
												<?php 
													$listTypeValuesQry = "select 
														sm_list_type_values.list_type_value_id,
														sm_list_type_values.list_code,
														sm_list_type_values.list_value	
															from sm_list_type_values

															left join sm_list_types on 
																sm_list_types.list_type_id = sm_list_type_values.list_type_id
																	where 
																		sm_list_type_values.list_type_status = 1 and 
																			sm_list_types.list_type_id = 1"; 
													$getEmploymentType = $this->db->query($listTypeValuesQry)->result_array();
												?>

												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Employment Type <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<select name="employment_type" id="employment_type" class="form-control searchDropdown" required>
															<option value="">- Select -</option>
															<?php 
																foreach($getEmploymentType as $type)
																{ 
																	$selected="";
																	if( isset($edit_data[0]['employment_type']) && $edit_data[0]['employment_type'] == $type['list_type_value_id'])
																	{
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $type['list_type_value_id'];?>" <?php echo $selected;?>><?php echo ucfirst($type['list_value']);?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
												<div class="row">
											
													<div class="form-group col-md-3">
														<label class="col-form-label">First Name <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="first_name" required  <?php #echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['first_name']) ? $edit_data[0]['first_name'] :"";?>" placeholder="" autocomplete="off">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Middle Name</label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="middle_name"  id="middle_name" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['middle_name']) ? $edit_data[0]['middle_name'] :"";?>" placeholder="" autocomplete="off">
													</div>
												</div>
												<div class="row">	
													<div class="form-group col-md-3">
														<label class="col-form-label">Last Name</label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="last_name" autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['last_name']) ? $edit_data[0]['last_name'] :"";?>" placeholder="" autocomplete="off">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Mobile Number <span class="text-danger">*</span></label>
													</div>
													<?php
														$query = "select country.country_code,country.country_id
																from geo_countries as country 
																where 
																active_flag='Y'
														";
														$countryCode = $this->db->query($query)->result_array();
													?>
													
													<div class="form-group col-md-6">
														<div class="row">
															<div class="col-md-3 pr-0">
																<select name="mob_ctry_code" id="mobile_country_code" required class="form-control searchDropdown mobile_vali">
																	<option value="">- Select -</option>																				
																		<?php 
																			foreach($countryCode as $row)
																			{
																				$selected="";
																				if(isset($edit_data[0]['mob_ctry_code']) && $edit_data[0]['mob_ctry_code'] == $row['country_id']){
																					$selected="selected='selected'";
																				}
																				?>
																				<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_code'];?></option>
																				<?php 
																			} 
																		?>
																</select>
															</div>
															<div class="col-md-9 pl-0">
																<input type="text" name="mobile_number" id="mobile_number" required autocomplete="off"  minlength="9" maxlength="12" class="form-control mobile_vali code-num1" value="<?php echo isset($edit_data[0]['mobile_number']) ? $edit_data[0]['mobile_number'] :"";?>" placeholder="9632587410">
																<span class="small mobile_number_exist" style="color:red;"></span>
															</div>
														</div>	
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Alternate Mobile Number </label>
													</div>
													<div class="form-group col-md-6">
														<div class="row">
															<div class="col-md-3 pr-0">
																<select name="alt_mob_ctry_code" id="mobile_country_code" class="form-control searchDropdown mobile_vali">
																	<option value="">- Select -</option>																				
																		<?php 
																			foreach($countryCode as $row)
																			{
																				$selected="";
																				if(isset($edit_data[0]['alt_mob_ctry_code']) && $edit_data[0]['alt_mob_ctry_code'] == $row['country_id']){
																					$selected="selected='selected'";
																				}
																				?>
																				<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_code'];?></option>
																				<?php 
																			} 
																		?>
																</select>
															</div>
															<div class="col-md-9 pl-0">
																<input type="text" name="alt_mob_number" id="alternate_contact" autocomplete="off" --oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control mobile_vali code-num1" value="<?php echo isset($edit_data[0]['alt_mob_number']) ? $edit_data[0]['alt_mob_number'] :"";?>" placeholder="9632587410">
																<span class="mobile_number_exist"></span>
															</div>
														</div>
													</div>		
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Email <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="email_address" id="email" required autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['email_address']) ? $edit_data[0]['email_address'] :"";?>" placeholder="">
														<span class='small employee_email_exist_error' style="color:red;"></span> 
													</div>
												</div>
												<div class="row">	
													<div class="form-group col-md-3">
														<label class="col-form-label">Alternate E-Mail</label>
													</div>
													<div class="form-group col-md-6">
														<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" name="alt_email_address" --id="emp_email" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['alt_email_address']) ? $edit_data[0]['alt_email_address'] :"";?>" placeholder="">
														<?php /* <span class='employee_email_exist_error'></span> */?>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">	
													<div class="form-group col-md-3">
														<label class="col-form-label">Father Name</label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="father_name"  id="father_name" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['father_name']) ? $edit_data[0]['father_name'] :"";?>" placeholder="" autocomplete="off">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Mother Name</label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="mother_name"  id="mother_name" autocomplete="off" <?php echo $this->validation; ?> class="form-control only_name" value="<?php echo isset($edit_data[0]['mother_name']) ? $edit_data[0]['mother_name'] :"";?>" placeholder="">
													</div> 
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Date of Birth <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="date_of_birth" id="date_of_birth" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_birth']) ? $edit_data[0]['date_of_birth'] :"";?>" placeholder="">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Gender <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">	
														<select name="gender" required id="gender" class="form-control searchDropdown">
															<option value="">- Select Gender -</option>
															<?php 
																foreach($this->gender as $key => $value)
																{ 
																	$selected="";
																	if( isset($edit_data[0]['gender']) && $edit_data[0]['gender'] == $key)
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
												</div>
												<div class="row">
													<?php 
														$bloodgroup = $this->db->query("select blood_group_name,blood_group_id from emp_blood_group where blood_group_status=1")->result_array();
													?>
													<div class="form-group col-md-3">
														<label class="col-form-label">Blood Group</label>
													</div>
													<div class="form-group col-md-6">
														<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageBloodGroup/add" title="Add Blood Group"> Add Blood Group </a>
														<select name="blood_group_id" id="blood_group_id" class="form-control searchDropdown">
															<option value="">- Select Blood Group -</option>
															<?php 
																foreach($bloodgroup as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['blood_group_id']) && $edit_data[0]['blood_group_id'] == $row['blood_group_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['blood_group_id'];?>" <?php echo $selected;?>><?php echo $row['blood_group_name'];?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Profile Image</label>
													</div>
													<div class="form-group col-md-6">
														<input type="file" name="profile_image" id="profile_image"class="form-control" placeholder="">
														<?php
															if($type=='edit')
															{
																if(file_exists("uploads/profile_image/".$status.'.png') )
																{
																	?>
																	<img class="img-responsive mt-2" alt="" style="border-radius:4px;width:75px;height:75px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $status.'.png';?>">
																	<?php 
																}
															}
															else
															{

															}
														?>
													</div>
												</div>
											</div>
										</div>
									
									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light btn-sm">Cancel</a>
										<input type="submit" name="save_only" value="Update" class="btn btn-primary ml-1 btn-sm register-but">
									</div>
								</form>
								<?php 
							} 
							else if(isset($id) && $id == 'career-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Career Information
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Update & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												
												<?php 
													$getBranch = $this->db->query("select branch_name, branch_id from branch where active_flag='Y' order by branch_name asc")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Location </label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>admin/ManageBranches/add" title="Branch"> Add Branch </a>
													<select name="branch_id" id="branch_id" class="form-control searchDropdown">
														<option value="">- Select Location -</option>
														<?php 
															foreach($getBranch as $row)
															{ 
																$selected="";
																if( isset($edit_data[0]['location_id']) && $edit_data[0]['location_id'] == $row['branch_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']);?></option>
																<?php 
															} 
														?>
													</select>
												</div>
												<?php 
													$designation = $this->db->query("select designation_name,designation_id from emp_designations where designation_status=1")->result_array();
												?>		
													
											</div>		
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Designation <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDesignation/add" title="Add Designation">Add Designation</a>
													<select name="designation_id" id="designation_id" required class="form-control searchDropdown">
														<option value="">- Select Designation -</option>
														<?php 
															foreach($designation as $row)
															{
																$selected="";
																if(isset($edit_data[0]['designation_id']) && $edit_data[0]['designation_id'] == $row['designation_id']){
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $row['designation_id'];?>" <?php echo $selected;?>><?php echo $row['designation_name'];?></option>
																<?php 
															} 
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<?php 
													$getPositions = $this->db->query("select position_name,position_id from hr_positions where position_status=1")->result_array();
												?>
												<?php /* <div class="form-group col-md-3">
													<label class="col-form-label">Position<span class="text-danger">*</span></label>
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManagePositions/add" title="Add Position">Add Position</a>
													<select name="position_id" id="position_id" class="form-control searchDropdown">
														<option value="">- Select Position -</option>
														<?php
															foreach($getPositions as $row)
															{
																$selected="";
																if(isset($edit_data[0]['position_id'])&& $edit_data[0]['position_id'] == $row['position_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																	<option value="<?php echo $row['position_id'];?>" <?php echo $selected; ?>> <?php echo $row['position_name'];?></option>
																<?php
															}
														?>
													</select>
												</div> */ ?>
												
												<?php 
													$getDepartment = $this->db->query("select department_name,department_id from emp_departments where department_status=1")->result_array();
												?>
												<div class="form-group col-md-3">
													<label class="col-form-label">Department <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<a class="quicklink" target="_blank" href="<?php echo base_url()?>employee/ManageDepartment/add" title="Add Department">Add Department</a>
													<select name="department_id" id="department_id" required class="form-control searchDropdown">
														<option value="">- Select Department -</option>
														<?php
															foreach($getDepartment as $row)
															{
																$selected="";
																if(isset($edit_data[0]['department_id'])&& $edit_data[0]['department_id'] == $row['department_id'])
																{
																	$selected="selected='selected'";
																}
																?>
																	<option value="<?php echo $row['department_id'];?>" <?php echo $selected;?>> <?php echo $row['department_name'];?></option>
																<?php
															}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Joining <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="date_of_joining" id="date_of_joining" required class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['date_of_joining']) ? $edit_data[0]['date_of_joining'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Date of Releaving</label>
												</div>
												<div class="form-group col-md-4">
													<input type="text" name="date_of_releaving" id="date_of_releaving" autocomplete="off" class="form-control default_date" value="<?php echo isset($edit_data[0]['date_of_releaving']) ? $edit_data[0]['date_of_releaving'] :"";?>" placeholder="">
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Previous Experience</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="previous_experience" id="previous_experience" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['previous_experience']) ? $edit_data[0]['previous_experience'] :"";?>" placeholder="">
												</div>
											</div>	
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Rate Per Hour</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="rate_per_hour" id="rate_per_hour" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_hour']) ? $edit_data[0]['rate_per_hour'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Rate Per Day</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="rate_per_day" id="rate_per_day" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['rate_per_day']) ? $edit_data[0]['rate_per_day'] :"";?>" placeholder="">
												</div>

												<input type="hidden" name="annual_ctc" id="annual_ctc" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['annual_ctc']) ? $edit_data[0]['annual_ctc'] :"";?>" placeholder="">
												<input type="hidden" name="position_id" id="position_id" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['position_id']) ? $edit_data[0]['position_id'] :"";?>" placeholder="">
											</div>	
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Pay frequency</label>
												</div>	
												<div class="form-group col-md-6">
													<select name="pay_frequency" id="pay_frequency" class="form-control searchDropdown" > <!--selectboxit-->
														<option value="">- Select Pay Frequency -</option>
														<?php 
															foreach($this->pay_frequency as $key=>$value)
															{
																$selected = "";
																if( isset($edit_data[0]['pay_frequency']) && $edit_data[0]['pay_frequency'] == $key)
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
											</div>
										</div>
									</div>
									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light">Cancel</a>
										<input type="submit" name="save_only" value="Update" class="btn btn-primary ml-1 btn-sm register-but">
									</div>
								</form>
								<?php
							}
							else if(isset($id) && $id == 'id-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												ID Information
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Update & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">Aadhar No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="aadhaar_number" data-type="adhaar-number" required maxlength="14" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['aadhaar_number']) ? $edit_data[0]['aadhaar_number'] :"";?>" placeholder="">
													<span class="small" id="aadhaar_number_val" style="color:red;float:left;width:100%;">(Ex : 4891-1846-5046)</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">PAN Number <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="pan_number" maxlength="10" id="textPanNo" required onblur="ValidatePAN(this);" autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['pan_number']) ? $edit_data[0]['pan_number'] :"";?>" placeholder="">
													<span class="small" id="pan_number_val" style="color:red;float:left;width:100%;">(Ex : ABCDE1234F)</span>
												</div>
											</div>	
											<div class="row">								
												<div class="form-group col-md-3">
													<label class="col-form-label">Driving Licence</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="driving_licence" id="driving_licence" --maxlength="17" --oninput="LicenceNumber(this)" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['driving_licence']) ? $edit_data[0]['driving_licence'] :"";?>" placeholder="">
													<span id="licence_number_val" class="small" style="color:red;">(Ex : TN-0619850034761 )</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport No</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_number" id="passport_number" minlength="8" maxlength="10" data-type="passport-number" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_number']) ? $edit_data[0]['passport_number'] :"";?>" placeholder="">
													<span id="passport_number_error" class="small" style="color:red;">(Ex : A1234567)</span>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport Issue Date</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_issue_date" id="passport_issue_date" class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_issue_date']) ? $edit_data[0]['passport_issue_date'] :"";?>" placeholder="">
												</div>
											</div>
											
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Passport Expiry Date</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="passport_expiry_date" id="passport_expiry_date" class="form-control default_date" autocomplete="off" value="<?php echo isset($edit_data[0]['passport_exp_date']) ? $edit_data[0]['passport_exp_date'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">PF No <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="pf_number" id="pf_number" autocomplete="off" required class="form-control" value="<?php echo isset($edit_data[0]['pf_number']) ? $edit_data[0]['pf_number'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">ESI No.</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="esi_number" id="esi_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['esi_number']) ? $edit_data[0]['esi_number'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">UAN No.</label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="uan_number" id="uan_number" autocomplete="off" class="form-control" value="<?php echo isset($edit_data[0]['uan_number']) ? $edit_data[0]['uan_number'] :"";?>" placeholder="">
												</div>
											</div>
										</div>
									</div>
									<div class="d-flexad" style="text-align:right;">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light">Cancel  </a>
										<input type="submit" name="save_only" value="Update" class="btn btn-primary ml-1 btn-sm register-but">
									</div>
								</form>
								
								<script type="text/javascript">
									$('[data-type="adhaar-number"]').keyup(function() 
									{
										var value = $(this).val();
										value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
										$(this).val(value);

										aadhaar_number_val = value;
										$.ajax({
											url: '<?php echo base_url();?>employee/aadhaarUnique',
											type: 'post',
											data: {
												'aadhaar_number' : aadhaar_number_val,
												'type'		 : '<?php echo $status ?>',
												<?php
													if ($type == "edit") 
													{
														?>
														'id' : '<?php echo $status; ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'already_taken') 
												{
													$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
													// Obj.focus();
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									function ValidatePAN() 
									{ 
										var Obj = document.getElementById("textPanNo");
										if (Obj.value != "") 
										{
											ObjVal = Obj.value;
											var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
											if (ObjVal.search(panPat) == -1) 
											{
												//alert("Please Enter Valid PAN NO.");
												$('#textPanNo').val('');
												//Obj.focus();
												return false;
											}
											else
											{
												pan_number = $('#textPanNo').val();
												$.ajax({
													url: '<?php echo base_url();?>employee/panUnique',
													type: 'post',
													data: {
														'pan_number' : pan_number,
														'type'		 : '<?php echo $status; ?>',
														<?php
															if ($type == "edit") 
															{
																?>
																'id'  : '<?php echo $id ?>'
																<?php
															}
														?>
													},
													success: function(response)
													{
														if (response == 'already_taken') 
														{
															$('#pan_number_val').html('PAN Number Alredy Taken');
															//Obj.focus();
															$('.register-but').prop('disabled',true);
															return false;
														}
														else
														{
															$('#pan_number_val').html('(Ex : ABCDE1234F)');
															$('.register-but').prop('disabled',false);
															return true;
														}
													}
												});

											}
										}
										else
										{
											$('#pan_number_val').html('(Ex : ABCDE1234F)');
										}
									} 
						
									$('#mobile_number').blur(function () 
									{
										mobile_number = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>vendor/MobileExist',
											type: 'post',
											data: {
												'mobile_number' : mobile_number,
												<?php
													if ($type == "edit") 
													{
														?>
															'id'		 : '<?php echo $status ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.mobile_number_exist').html('Mobile Number Alredy Taken');
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('.mobile_number_exist').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									$('#email').blur(function () 
									{
										email = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>employee/EmailExist',
											type: 'post',
											data: {
												'email' : email,
												<?php
													if ($type == "edit") 
													{
														?>
														'id': '<?php echo $status ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.employee_email_exist_error').html('Email Alredy Taken');
													$('.register-but').prop('disabled',true);
													$(this).focus();
													return false;
												}else
												{
													$('.employee_email_exist_error').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});
									
									$('[data-type="passport-number"]').keyup(function() 
									{
										var passport = $(this).val();
										if (passport == "") {
											return;
										}
										var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
		
										if(regsaid.test(passport) == false)
										{
											document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
											$('.register-but').prop('disabled',true);
										}
										else
										{
											document.getElementById("passport_number_error").innerHTML = "";
											$('.register-but').prop('disabled',false);
										}

									});
								</script>
								<?php
							}
							else if(isset($id) && $id == 'address-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Address Information
											</div>
										
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Update & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									
										<?php
											$country = $this->db->get_where('geo_countries', array('active_flag' => 'Y'))->result_array();
										?>
										<script type="text/javascript">  
											function selectState(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectState';?>",
													data: { id: val }
													}).done(function( msg ) {   
														$("#state_id").html(msg);
														//$("#permenant_state_id").html(msg);
													});
												}
												else 
												{ 
													alert("No State under this Country!");
												}
											}
											
											function selectDistrict(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
													data: { id: val }
													}).done(function( msg ) {  
														$( "#district_id").html(msg);
														//$( "#permenant_district_id").html(msg);
													});
												}
												else 
												{ 
													alert("No districts under this state!");
												}
											}
											
											function selectCityorTown(val)
											{
											if(val !='')
											{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
													data: { id: val }
													}).done(function( msg ) {
														$( "#city_id").html(msg);
														//$( "#permenant_city_id").html(msg);
													});
												}
												else 
												{ 
													alert("No city/town's under this district!");
												}
											}

											function currentselectState(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectState';?>",
													data: { id: val }
													}).done(function( msg ) {   
														//$("#state_id").html(msg);
														$("#permenant_state_id").html(msg);
													});
												}
												else 
												{ 
													alert("No State under this Country!");
												}
											}
											
											function currentselectDistrict(val)
											{
												if(val !='')
												{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectDistrict';?>",
													data: { id: val }
													}).done(function( msg ) {  
														//$( "#district_id").html(msg);
														$( "#permenant_district_id").html(msg);
													});
												}
												else 
												{ 
													alert("No districts under this state!");
												}
											}
											
											function currentselectCityorTown(val)
											{
											if(val !='')
											{
													$.ajax({
													type: "POST",
													url:"<?php echo base_url().'admin/ajaxSelectCity';?>",
													data: { id: val }
													}).done(function( msg ) {
														//$( "#city_id").html(msg);
														$( "#permenant_city_id").html(msg);
													});
												}
												else 
												{ 
													alert("No city/town's under this district!");
												}
											}
										</script>
										<div class="row">
											<div class="col-md-6">
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Current Address:</label>
													</div>
												</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">Address 1 <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<textarea name="address" rows="1" id="address1" required class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">Address 2 <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<textarea name="address2" rows="1" id="address2" required class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address2']) ? $edit_data[0]['address2'] :"";?></textarea>
														</div>
													</div>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">Address 3 <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<textarea name="address3" rows="1" id="address3" required class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address3']) ? $edit_data[0]['address3'] :"";?></textarea>
														</div>
													</div>

												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Country <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<select name="country_id" id="country_id" required onchange="selectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
															<option value="">- Select Country -</option>
															<?php 
																foreach($country as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">State <span class="text-danger">*</span></label>
													</div>
													<div class="form-group col-md-6">
														<select name="state_id" id="state_id" required onchange="selectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
															<option value="">- First Select Country -</option>
															<?php 
																if($type == "edit")
																{
																	$state = $this->db->get_where('geo_states', array('active_flag' => 'Y','country_id' => $edit_data[0]['country_id']))->result_array();
													
																	foreach($state as $row)
																	{
																		$selected="";
																		if(isset($edit_data[0]['state_id']) && $edit_data[0]['state_id'] == $row['state_id']){
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																		<?php 
																	}
																}
															?>
														</select>
													</div>
												</div>
												<?php /*
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">District <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<select name="district_id" id="district_id" required onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
																<option value="">- First Select State -</option>
																<?php 
																	if($type == "edit")
																	{
																		$district = $this->db->get_where('district', array('district_status' => '1','state_id' => $edit_data[0]['STATE_ID']))->result_array();
														
																		foreach($district as $row)
																		{
																			$selected="";
																			if( isset($edit_data[0]['district_id']) && $edit_data[0]['district_id'] == $row['district_id'])
																			{
																				$selected="selected='selected'";
																			}
																			?>
																			<option value="<?php echo $row['district_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['district_name']);?></option>
																			<?php 
																		}
																	}
																?>
															</select>
														</div>
													</div>
													*/ ?>
													<div class="row">
														<div class="form-group col-md-3">
															<label class="col-form-label">City </label>
														</div>
														<div class="form-group col-md-6">
															<select name="city_id" id="city_id" --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
																<option value="">- First Select State -</option>
																<?php 
																	if($type == "edit")
																	{
																		$city = $this->db->get_where('geo_cities', array('active_flag' => 'Y','state_id' => $edit_data[0]['state_id']))->result_array();
														
																		foreach($city as $row)
																		{
																			$selected="";
																			if( isset($edit_data[0]['city_id']) && $edit_data[0]['city_id'] == $row['city_id'])
																			{
																				$selected="selected='selected'";
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
														<div class="form-group col-md-3">
															<label class="col-form-label">Postal Code <span class="text-danger">*</span></label>
														</div>
														<div class="form-group col-md-6">
															<input type="text" name="postal_code" id="postal_code" required autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['postal_code']) ? $edit_data[0]['postal_code'] :"";?>" placeholder="">
														</div>
													</div>
												
												<script>
													$(document).ready(function()
													{
														$('input[name="chk_shipping_address"]').click(function()
														{
															if( $(this).prop("checked") == true ) //checked
															{
																$('#permenant_address1').val( $('#address1').val() );
																$('#permenant_address2').val( $('#address2').val() );
																$('#permenant_address3').val( $('#address3').val() );
																$('#permenant_postal_code').val( $('#postal_code').val() );
																
																var company_country1 = $('select#country_id option:selected').sort().clone();
																$('select#permenant_country_id').append( company_country1 );
																
																var country_id = $('#country_id').val();
																var permenant_country_id = $('#permenant_country_id').val();
																
																var state_id = $('#state_id').val();
																var company_state_id = $('select#state_id option:selected').sort().clone();
																$('select#permenant_state_id').append( company_state_id );
																
																var district_id = $('#district_id').val();
																var company_district_id = $('select#district_id option:selected').sort().clone();
																$('select#permenant_district_id').append( company_district_id );

																var city_id = $('#city_id').val();
																var company_city_id = $('select#city_id option:selected').sort().clone();
																$('select#permenant_city_id').append( company_city_id );
																
																if(country_id == permenant_country_id);
																{
																	$('select#permenant_country_id option[value='+country_id+']').attr('selected','selected');
																}
																
																if(state_id !='');
																{
																	$('select#permenant_state_id option[value='+state_id+']').attr('selected','selected');
																}
																
																if(district_id !='');
																{
																	$('select#permenant_district_id option[value='+district_id+']').attr('selected','selected');
																}
																if(city_id !='');
																{
																	$('select#permenant_city_id option[value='+city_id+']').attr('selected','selected');
																}
															}
															else if( $(this).prop("checked") == false ) //Unchecked
															{
																var permenant_country_id = $('#permenant_country_id').val();
																$("select#permenant_country_id option[value='"+permenant_country_id+"']:last").remove();
																
																$( "#permenant_state_id").html('<option value="">- First Select Country -</option>');
																$( "#permenant_district_id").html('<option value="">- First Select District -</option>');
																$( "#permenant_city_id").html('<option value="">- First Select State -</option>');
																
																$('#permenant_address').val('');
																$('#permenant_postal_code').val('');
																$('#permenant_address1').val('');
																$('#permenant_address2').val('');
																$('#permenant_address3').val('');
																
															}
														});
													});
												</script>
											</div>
											<div class="col-md-6">
												<div --class="new-design-2">
													<span>Permenant Address
													<?php 
														$checked_shipping_address ="";
														if( isset($edit_data[0]['chk_shipping_address']) && $edit_data[0]['chk_shipping_address'] == 1 )
														{
															$checked_shipping_address ="checked='checked'";
														} 
													?>
													&nbsp; &nbsp; <input type="checkbox" name="chk_shipping_address" value='1' id="chk_shipping_address" <?php echo $checked_shipping_address;?>>&nbsp; &nbsp; <span style="color:#c7c7ce;font-size: 11px;">Copy Current Address</span></span>
												</div>
												<div class="row mt-3">
													<div class="form-group col-md-3">
														<label class="col-form-label">Address 1</label>
													</div>
													<div class="form-group col-md-6">
														<textarea name="permenant_address1" rows="1" id="permenant_address1"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address1']) ? $edit_data[0]['permenant_address1'] :"";?></textarea>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Address 2</label>
													</div>
													<div class="form-group col-md-6">
														<textarea name="permenant_address2" rows="1" id="permenant_address2"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address2']) ? $edit_data[0]['permenant_address2'] :"";?></textarea>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Address 3</label>
													</div>
													<div class="form-group col-md-6">
														<textarea name="permenant_address3" rows="1" id="permenant_address3"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['permenant_address3']) ? $edit_data[0]['permenant_address3'] :"";?></textarea>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">Country</label>
													</div>
													<div class="form-group col-md-6">
														<select name="permenant_country_id" id="permenant_country_id" onchange="currentselectState(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
															<option value="">- Select Country -</option>
															<?php 
																foreach($country as $row)
																{
																	$selected="";
																	if(isset($edit_data[0]['permenant_country_id']) && $edit_data[0]['permenant_country_id'] == $row['country_id']){
																		$selected="selected='selected'";
																	}
																	?>
																	<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
																	<?php 
																} 
															?>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">State </label>
													</div>
													<div class="form-group col-md-6">
														<select name="permenant_state_id" id="permenant_state_id" onchange="currentselectCityorTown(this.value);" class="form-control searchDropdown"> <!--selectboxit-->
															<option value="">- First Select Country -</option>
															<?php 
																if($type == "edit")
																{
																	$state = $this->db->get_where('geo_states', array('active_flag' => 'Y','country_id' => $edit_data[0]['permenant_country_id']))->result_array();
																	print_r($state);
																	foreach($state as $row)
																	{
																		$selected="";
																		if(isset($edit_data[0]['permenant_state_id']) && $edit_data[0]['permenant_state_id'] == $row['state_id']){
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
																		<?php 
																	}
																}
															?>
														</select>
													</div>
												</div>
												<?php /*
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">District </label>
													</div>
													<div class="form-group col-md-6">
														<select name="permenant_district_id" id="permenant_district_id" onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
															<option value="">- First Select State -</option>
															<?php 
																if($type == "edit")
																{
																	$district = $this->db->get_where('district', array('district_status' => '1','state_id' => $edit_data[0]['state_id']))->result_array();
													
																	foreach($district as $row)
																	{
																		$selected="";
																		if( isset($edit_data[0]['permenant_district_id']) && $edit_data[0]['permenant_district_id'] == $row['district_id'])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value="<?php echo $row['district_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['district_name']);?></option>
																		<?php 
																	}
																}
															?>
														</select>
													</div>
												</div>
												*/ ?>
												<div class="row">
													<div class="form-group col-md-3">
														<label class="col-form-label">City </label>
													</div>
													<div class="form-group col-md-6">
														<select name="permenant_city_id" id="permenant_city_id" --onchange="selectCityorTown(this.value);" class="form-control searchDropdown" > <!--selectboxit-->
															<option value="">- First Select State -</option>
															<?php 
																if($type == "edit")
																{
																	$city = $this->db->get_where('geo_cities', array('active_flag' => 'Y','state_id' => $edit_data[0]['permenant_state_id']))->result_array();
																	
																	foreach($city as $row)
																	{
																		$selected="";
																		if( isset($edit_data[0]['permenant_city_id']) && $edit_data[0]['permenant_city_id'] == $row['city_id'])
																		{
																			$selected="selected='selected'";
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
													<div class="form-group col-md-3">
														<label class="col-form-label">Postal Code</label>
													</div>
													<div class="form-group col-md-6">
														<input type="text" name="permenant_postal_code" id="permenant_postal_code" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['permenant_postal_code']) ? $edit_data[0]['permenant_postal_code'] :"";?>" placeholder="">
													</div>
												</div>
												</div>
											</div>
											
										</div>

										<?php /* <div class="form-group col-md-3">
											<label class="col-form-label">Permenant Address</label>
											<textarea name="address1" rows="1" id="address1"  class="form-control" autocomplete="off"><?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?></textarea>
										</div> */ ?>
										<input type="hidden" name="address1" id="address1" autocomplete="off" class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['address1']) ? $edit_data[0]['address1'] :"";?>" placeholder="">
									
										<div class="d-flexad pb-3 pr-3" style="text-align:right;">
											<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light">Cancel</a>
											<input type="submit" name="save_only" value="Update" class="btn btn-primary ml-1 btn-sm register-but">
										</div>
										
								</form>
								<?php
							}
							else if(isset($id) && $id == 'bank-info')
							{
								?>
								<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
									<legend class="text-uppercase font-size-sm font-weight-bold">
										<div class="row">
											<div class="col-md-6">
												Add Bank Detail
											</div>
											<div class="col-md-6 text-right">
												<input type="submit" name="save_close" value="Update & Close" class="btn btn-primary ml-1 btn-sm register-but">
											</div>
										</div>
									</legend>
									
									<div class="row">
										<div class="col-md-6">
											<div class="row">	
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c No  <span class="text-danger">*</span> </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_number" id="account_number" required class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['account_number']) ? $edit_data[0]['account_number'] :"";?>" placeholder="">
												</div>
											</div>
											<!--<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c Holder Name<span class="text-danger">*</span> </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_name" id="account_name" required <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo $edit_data[0]['account_name'];?>" placeholder="">
												</div>
											</div>-->
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">A/c Holder Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="account_name" id="account_name" required autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['account_name']) ? $edit_data[0]['account_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Bank Name <span class="text-danger">*</span></label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="bank_name" id="bank_name" required autocomplete="off" <?php echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['bank_name']) ? $edit_data[0]['bank_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Bank Branch </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="branch_name" id="branch_name" <?php echo $this->validation; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['branch_name']) ? $edit_data[0]['branch_name'] :"";?>" placeholder="">
												</div>
											</div>
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">IFSC Code <span class="text-danger">*</span> </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="ifsc_code" id="ifsc_code" required class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['ifsc_code']) ? $edit_data[0]['ifsc_code'] :"";?>" placeholder="">
													<span class="small note-color" style="float:left;width:100%;">(Ex : IDIB000A114)</span>
												</div>
											</div>	
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">MICR Code </label>
												</div>
												<div class="form-group col-md-6">
													<input type="text" name="micr_code" id="micr_code" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['micr_code']) ? $edit_data[0]['micr_code'] :"";?>" placeholder="">
													<span class="small note-color">(Ex : 600019003)</span>
												</div>
											</div>
											<div class="row">											
												<div class="form-group col-md-3">
													<label class="col-form-label">Address </label>
												</div>
												<div class="form-group col-md-6">
													<textarea name="address" id="address" class="form-control" value=""><?php echo isset($edit_data[0]['bank_address']) ? $edit_data[0]['bank_address'] :"";?></textarea>
												</div>
											</div>
										</div>
									</div>
									
									
									<div class="d-flexad text-right">
										<a href="<?php echo base_url(); ?>employee/ManageEmployee/grid_view" class="btn btn-light">Cancel</a>
										<input type="submit" name="save_only" value="Update" class="btn btn-primary ml-1 btn-sm register-but">
									</div>
								</form>
								<script type="text/javascript">
									$('[data-type="adhaar-number"]').keyup(function() 
									{
										var value = $(this).val();
										value = value.replace(/\D/g, "").split(/(?:([\d]{4}))/g).filter(s => s.length > 0).join("-");
										$(this).val(value);

										aadhaar_number_val = value;
										$.ajax({
											url: '<?php echo base_url();?>employee/aadhaarUnique',
											type: 'post',
											data: {
												'aadhaar_number' : aadhaar_number_val,
												'type'		 : '<?php echo $type ?>',
												<?php
													if ($type == "edit") 
													{
														?>
														'id' : '<?php echo $status ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'already_taken') 
												{
													$('#aadhaar_number_val').html('Aadhaar Number Alredy Taken');
													// Obj.focus();
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('#aadhaar_number_val').html('(Ex : 4891-1846-5046)');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									function ValidatePAN() 
									{ 
										var Obj = document.getElementById("textPanNo");
										if (Obj.value != "") 
										{
											ObjVal = Obj.value;
											var panPat = /^([a-zA-Z]{5})(\d{4})([a-zA-Z]{1})$/;
											if (ObjVal.search(panPat) == -1) 
											{
												alert("Please Enter Valid PAN NO.");
												$('#textPanNo').val('');
												//Obj.focus();
												return false;
											}
											else
											{
												pan_number = $('#textPanNo').val();
												$.ajax({
													url: '<?php echo base_url();?>employee/panUnique',
													type: 'post',
													data: {
														'pan_number' : pan_number,
														'type'		 : '<?php echo $type ?>',
														<?php
															if ($type == "edit") 
															{
																?>
																'id'  : '<?php echo $id ?>'
																<?php
															}
														?>
													},
													success: function(response)
													{
														if (response == 'already_taken') 
														{
															$('#pan_number_val').html('PAN Number Alredy Taken');
															//Obj.focus();
															$('.register-but').prop('disabled',true);
															return false;
														}
														else
														{
															$('#pan_number_val').html('(Ex : ABCDE1234F)');
															$('.register-but').prop('disabled',false);
															return true;
														}
													}
												});

											}
										}
										else
										{
											$('#pan_number_val').html('(Ex : ABCDE1234F)');
										}
									} 
						
									$('#mobile_number').blur(function () 
									{
										mobile_number = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>vendor/MobileExist',
											type: 'post',
											data: {
												'mobile_number' : mobile_number,
												<?php
													if ($type == "edit") 
													{
														?>
															'id'		 : '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.mobile_number_exist').html('Mobile Number Alredy Taken');
													$('.register-but').prop('disabled',true);
													return false;
												}else
												{
													$('.mobile_number_exist').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});

									$('#email').blur(function () 
									{
										email = $(this).val();

										$.ajax({
											url: '<?php echo base_url();?>employee/EmailExist',
											type: 'post',
											data: {
												'email' : email,
												<?php
													if ($type == "edit") 
													{
														?>
														'id': '<?php echo $id ?>'
														<?php
													}
												?>
											},
											success: function(response)
											{
												if (response == 'taken') 
												{
													$('.employee_email_exist_error').html('Email Alredy Taken');
													$('.register-but').prop('disabled',true);
													$(this).focus();
													return false;
												}else
												{
													$('.employee_email_exist_error').html('');
													$('.register-but').prop('disabled',false);
													return true;
												}
											}
										});
									});
									
									$('[data-type="passport-number"]').keyup(function() 
									{
										var passport = $(this).val();
										if (passport == "") {
											return;
										}
										var regsaid = /[a-zA-Z]{1}[0-9]{6}/;
		
										if(regsaid.test(passport) == false)
										{
											document.getElementById("passport_number_error").innerHTML = "Passport is valid.";
											$('.register-but').prop('disabled',true);
										}
										else
										{
											document.getElementById("passport_number_error").innerHTML = "";
											$('.register-but').prop('disabled',false);
										}

									});
								</script>
								<?php
							}	
							
						?>
						</div>
					
							<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
							<!-- <form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post"> -->
								
								<script type="text/javascript">    
									$(document).ready(function(){     
										$("#ifsc_code").change(function () {      
										var inputvalues = $(this).val();      
										var reg = /[A-Z|a-z]{4}[a-zA-Z0-9]{7}$/;    
											if (inputvalues.match(reg)) {    
												return true;    
											}    
											else {    
													$("#ifsc_code").val("");    
												alert("You entered invalid IFSC code");    
												//document.getElementById("txtifsc").focus();    
												return false;    
											}    
										});      
									});    

									function LicenceNumber(fieldname)
									{				
										if (fieldname.value == "") 
										{
											$('#licence_number_val').html('(Ex : TN-0619850034761 )');
											$('.register-but').prop('disabled',false);
											return
										}					
										regsaid = /^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}-[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$/;
										if(regsaid.test(fieldname.value) == false)
										{
											$('#licence_number_val').html('Licence Number Not Valid');
											$('.register-but').prop('disabled',true);
											return false;
										}
										else
										{
											$('#licence_number_val').html('(Ex : TN-0619850034761 )');
											$('.register-but').prop('disabled',false);
										}
									}

									function voterId(fieldname)
									{				
										if (fieldname.value == "") 
										{
											$('#voterid_number_val').html('(Ex : SRD0676361)');
											$('.register-but').prop('disabled',false);
											return
										}					
										regsaid = /^([a-zA-Z]){3}([0-9]){7}?$/;
										if(regsaid.test(fieldname.value) == false)
										{
											$('#voterid_number_val').html('Voter ID is Invalid!');
											$('.register-but').prop('disabled',true);
											return false;
										}
										else
										{
											$('#voterid_number_val').html('(Ex : SRD0676361)');
											$('.register-but').prop('disabled',false);
										}
									} 

									var $field1 = $("#mobile_number");
									var $field2 = $("#mobile_number_1");

									$field1.on("keydown",function()
									{
										setTimeout(checkValue,0); 
									});

									var v2 = $field2.val();
									var checkValue = function(){
										var v1 = $field1.val();
										if (v1 != v2){
											$field2.val(v1);
											v2 = v1;
										}
									};
								</script>
							<?php /* </form> */ ?>
						<?php
					
				} 
				else if (isset($type) && $type == "view")
				{
					?>
					<div class="card">
						<div class="card-body">
							<div class="row">
								<?php 
									$page_data = array();
									echo $this->load->view("backend/employee/view_sidebar.php", $page_data, true);
									echo $this->load->view("backend/employee/view_employee.php", $page_data, true);
								?>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "EmployeeDocuments")
				{
					?>
					<div class="card">
						<div class="card-body">
							<div class="row">
								<?php 
									$page_data = array();
									echo $this->load->view("backend/employee/view_sidebar.php", $page_data, true);
									echo $this->load->view("backend/employee/employeeDocuments.php", $page_data, true);
								?>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "payslip")
				{
					?>
					<div class="card">
						<div class="card-body">
							<div class="row">
								<?php 
									$page_data = array();
									echo $this->load->view("backend/employee/view_sidebar.php", $page_data, true);
									echo $this->load->view("backend/employee/payslip_categories.php", $page_data, true);
								?>
							</div>
						</div>
					</div>
					<?php
				}
				else if(isset($type) && $type == "bankDetails")
				{
					?>
					<div class="card">
						<div class="card-body">
							<div class="row">
								<?php 
									$page_data = array();
									echo $this->load->view("backend/employee/view_sidebar.php", $page_data, true);
									echo $this->load->view("backend/employee/bankDetails.php", $page_data, true);
								?>
							</div>
						</div>
					</div>
					<?php
				}
				else
				{ 
					if(isset($type) && $type=="grid_view")
					{
						?>
						<?php /* <fieldset class="mt-2">
							<legend class="text-uppercase font-size-sm font-weight-bold">
								Manage Users
							</legend>
						</fieldset>
						<form action="" method="get">
							<div class="row">
								<div class="col-md-8">
									<div class="row">
										<div class="col-md-4">	
											<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
											<p class="search-note">Note : User ID, User Name, Mobile Number and Email.</p>
										</div>	
										
										<div class="col-md-4">
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
						</form> */ ?>

						<div class="card"><!-- Card start-->
							<div class="card-body">
								<div class="new-scroller" --id="style-3">
									<table id="myTable" class="table table-bordered table-hover  dataTable --sortable-table">
										<thead>
											<tr>
												<th class="sticky-col-tab" style="text-align:center;width:12%;">Controls</th>
												<!-- <th --onclick="sortTable(2)" class="text-center">Employee No.</th> -->
												<th --onclick="sortTable(3)" class="text-center">Employee Number</th>
												<th --onclick="sortTable(3)">Employee Name</th>
												<th --onclick="sortTable(4)" class="text-center">Date of Birth</th>
												<th --onclick="sortTable(4)" class="text-center">Date of Joining</th>
												<th --onclick="sortTable(5)">E-Mail</th>
												<th --onclick="sortTable(6)" class="text-center">Mobile Number</th>
												<th --onclick="sortTable(7)" class="text-center">Created Date</th>
												<th --onclick="sortTable(9)" class="text-center" style="width:10%;">Status</th>
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
														<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
														-->
														<!-- Modal Start-->
														<?php /*
														<div class="modal fade" id="exampleModal<?php echo $row['emp_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['user_id'];?>" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header" --style="background: #022646;color: #fff;">
																		<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	
																	<form action="<?php echo base_url();?>Employee/ManageEmployee/change_password/<?php echo $row['emp_id'];?>" method="post">
																		<div class="modal-body">
																			
																			<div class="row">
																				<div class="form-group col-md-6">
																					<label class="col-form-label">User Name</label>
																					<input type="text" autocomplete="off" value="<?php echo $row['email'];?>" class="form-control" disabled />
																				</div>
																				<?php 
																					if($this->emp_id == 1)
																					{
																						$inputtype = "text";
																					}else
																					{
																						$inputtype = "password";
																					}
																				?>
																				<div class="form-group col-md-6">
																					<label class="col-form-label">Current Password <span class="text-danger">*</span></label>
																					<input type="password" readonly autocomplete="off" name="password" value="<?php echo $row['original_password'];?>" id="password" class="form-control" required />
																				</div>
																			</div>
																			
																			<div class="row">
																				<div class="form-group col-md-6">
																					<label class="col-form-label">New Password <span class="text-danger">*</span></label>
																					<input type="password" autocomplete="off" name="new_password" id="new_password" class="form-control"required />
																				</div>
																			
																				<div class="form-group col-md-6">
																					<label class="col-form-label">Confirm New Password <span class="text-danger">*</span></label>
																					<input type="password" autocomplete="off" name="confirm_new_password" id="confirm_new_password" class="form-control" required />
																				</div>
																			</div>
																		</div>
																		
																		<div class="modal-footer">
																			<!--<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>-->
																			<button type="submit" class="btn btn-primary ml-3">Submit </button>
																		</div>
																	</form>
																	
																</div>
															</div>
														</div>
															*/ ?>
														<td class="text-center">
															<div class="dropdown controls-actions">
																<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false" style="width: 70px;">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<?php
																		/* if((isset($employee['create_edit_only']) && $employee['create_edit_only'] == 1) || $this->emp_id == 1)
																		{ */
																			?>
																			<li>
																				<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmployee/edit/basic-info/<?php echo $row['person_id'];?>">
																					<i class="fa fa-pencil"></i> Edit
																				</a>
																			</li>
																			<?php 
																		/* }  */
																	?>
																	
																	<?php
																		/* if((isset($employee['read_only']) && $employee['read_only'] == 1) || $this->emp_id == 1)
																		{ */
																			?>
																			<!-- <li>
																				<a title="View" href="<?php echo base_url(); ?>employee/ManageEmployee/view/<?php echo $row['person_id'];?>">
																					<i class="fa fa-eye"></i> View
																				</a>
																			</li> -->
																			<?php 
																		/* } */
																	?>
																	
																	<?php /*
																		if((isset($employee['read_only']) && $employee['read_only'] == 1) || $this->emp_id == 1)
																		{
																			?>
																			<li>
																				<?php 
																					if($row['user_status'] == 1)
																					{
																						?>
																						<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['emp_id'];?>/0" title="Block">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['emp_id'];?>/1" title="Unblock">
																							<i class="fa fa-check"></i> Active
																						</a>
																						<?php 
																					}
																				?>
																			</li>
																			<?php 
																		}  */
																	?>
																</ul>
															</div>
															<!-- Modal End-->
														</td>
														<?php /*
														<td class="tab-medium-width">
															<?php echo ucfirst($row['role_name']);?>
														</td>
														
														<td style="text-align:center;" class="tab-medium-width">
															<?php echo $row['random_user_id'];?>
														</td>
														 */ ?>
														<td class="tab-medium-width text-center"><?php echo ucfirst($row['employee_number']);?></td>
														<td class="tab-full-width">
															<?php echo ucfirst($row['first_name'])." ".ucfirst($row['last_name']);?>
														</td>
														
														<td class="tab-medium-width text-center"><?php echo $row['date_of_birth'];?></td>
														<td  class="tab-medium-width text-center"><?php echo $row['date_of_joining'];?></td>
														<td  class="tab-maxfull-width"><?php echo $row['email_address'];?></td>
														<td  class="tab-full-width text-center">
															<?php echo $row['mob_ctry_code']."-".$row['mobile_number'];?>
														</td>
														
														<td  class="tab-full-width text-center">
															<?php echo $row['created_date'];?>
														</td>
																												
														<td style="text-align:center;" class="tab-medium-width">
															<?php 
																if($row['active_flag'] == "Y")
																{
																	?>
																	<span class="btn btn-outline-success btn-sm" title="Active"><i class="fa fa-check"></i> Active</span>
																	<?php 
																} 
																else
																{  
																	?>
																	<span class="btn btn-outline-warning btn-sm" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
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
											<div class="col-md-12 float-left text-center"> 
												<img src="<?php echo base_url(); ?>uploads/nodata.png">
											</div>
											<?php 
										} 
									?>
								</div>
								
								<?php 
									if(count($resultData) > 0)
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
								
							</div>
						</div>
						<?php 
					}
					else if(isset($type) && $type=="list_view")
					{
						?>
						<?php
							if(count($resultData) > 0)
							{
								foreach($resultData as $row)
								{
									?>
									<div class="row">
										<div class="col-md-12">
											<div class="card">
												<ul class="media-list">
													<li class="media view-list">
														<div class="mr-3 position-relative">
															<?php
																$imgUrl = "uploads/profile_image/".$row['user_id'].'.png';
																if( file_exists($imgUrl) )
																{
																	?>
																	<img src="<?php echo base_url().$imgUrl; ?>" class="rounded-circle" width="40" height="40">
																	<?php 
																}
																else
																{
																	?>
																	<img src="<?php echo base_url(); ?>uploads/no-image.png" class="rounded-circle" width="40" height="40">
																	<?php 
																} 
															?>
														</div>
														<div class="media-body">
															<div class="media-title">
																<span class="font-weight-semibold">
																	<?php $clientName = ucfirst($row["first_name"])." ".ucfirst($row["last_name"]);?>
																	<?php echo $clientName; ?>	
																	<span class="text-muted">
																		(# <?php echo ucfirst($row['random_user_id']);?>)
																	</span>
																</span>
																<!-- Modal Start-->
																<div class="modal fade" id="exampleModal<?php echo $row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['user_id'];?>" aria-hidden="true">
																	<div class="modal-dialog" role="document">
																		<div class="modal-content">
																			<div class="modal-header" --style="background: #022646;color: #fff;">
																				<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
																				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																					<span aria-hidden="true">&times;</span>
																				</button>
																			</div>
																			
																			<form action="<?php echo base_url();?>Employee/ManageEmployee/change_password/<?php echo $row['user_id'];?>" method="post">
																				<div class="modal-body">
																					
																					<div class="row">
																						<div class="form-group col-md-6">
																							<label class="col-form-label">User Name</label>
																							<input type="text" autocomplete="off" value="<?php echo $row['user_name'];?>" class="form-control" disabled />
																						</div>
																						<?php 
																							if($this->user_id == 1)
																							{
																								$inputtype = "text";
																							}else
																							{
																								$inputtype = "password";
																							}
																						?>
																						<div class="form-group col-md-6">
																							<label class="col-form-label">Current Password <span class="text-danger">*</span></label>
																							<input type="password" readonly autocomplete="off" name="password" value="<?php echo $row['original_password'];?>" id="password" class="form-control" required />
																						</div>
																					</div>
																					
																					<div class="row">
																						<div class="form-group col-md-6">
																							<label class="col-form-label">New Password <span class="text-danger">*</span></label>
																							<input type="password" autocomplete="off" name="new_password" id="new_password" class="form-control"required />
																						</div>
																					
																						<div class="form-group col-md-6">
																							<label class="col-form-label">Confirm New Password <span class="text-danger">*</span></label>
																							<input type="password" autocomplete="off" name="confirm_new_password" id="confirm_new_password" class="form-control" required />
																						</div>
																					</div>
																				</div>
																				
																				<div class="modal-footer">
																					<!--<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>-->
																					<button type="submit" class="btn btn-primary ml-3">Submit </button>
																				</div>
																			</form>
																		</div>
																	</div>
																</div>

																<span class="text-muted float-right">
																	<div class="dropdown list-dropdown">
																		<a href="#" data-toggle="dropdown" aria-expanded="false">
																			<i class="fa fa-ellipsis-v"></i>
																		</a>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<?php
																				if($employee['create_edit_only'] == 1 || $this->user_id == 1)
																				{
																					?>
																					<li>
																						<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmployee/edit/basic-info/<?php echo $row['user_id'];?>">
																							<i class="fa fa-pencil"></i> Edit
																						</a>
																					</li>
																					<?php 
																				} 
																			?>
																			<?php
																				if($employee['read_only'] == 1 || $this->user_id == 1)
																				{
																					?>
																					<li>
																						<a title="View" href="<?php echo base_url(); ?>employee/ManageEmployee/view/<?php echo $row['user_id'];?>">
																							<i class="fa fa-eye"></i> View
																						</a>
																					</li>
																					<?php 
																				}
																			?>
																			<?php
																				if($employee['create_edit_only'] == 1 || $this->user_id == 1)
																				{
																					?>
																					<li>
																						<?php 
																							if($row['user_status'] == 1)
																							{
																								?>
																								<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['user_id'];?>/0" title="Block">
																									<i class="fa fa-ban"></i> Inactive
																								</a>
																								<?php 
																							} 
																							else
																							{  ?>
																								<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['user_id'];?>/1" title="Unblock">
																									<i class="fa fa-check"></i> Active
																								</a>
																								<?php 
																							}
																						?>
																					</li>
																					<?php 
																				} 
																			?>
																			<li>
																				<a title="Change Password" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['user_id'];?>">
																					<i class="fa fa-key"></i>Change Password
																				</a>
																			</li> 

																			<li>
																				<a href="<?php echo base_url(); ?>employee/annexureDetails/<?php echo $row['user_id'];?>" title="Annexure Details">
																					<i class="fa fa-thumbs-up"></i> Annexure Details
																				</a>
																			</li> 
																		</ul>
																	</div>
																</span>
																<br>
																<span class="text-success font-weight-bold float-right">
																	<?php 
																		if ($row['user_status'] == 1) 
																		{
																			echo '<span class="text-success">Active</span>';
																		}
																		else
																		{
																			echo '<span class="text-orange">Inactive</span>';
																		}
																	?>
																</span>
																<br>
																<span class="text-info font-weight-bold float-right">	
																	<?php 
																		if ($row['gender'] == 1) 
																		{
																			echo '<span class="text-info">Male</span>';
																		}
																		elseif($row['gender'] == 2)
																		{
																			echo '<span class="text-orange">Female</span>';
																		}
																		else
																		{
																			echo '<span class="text-primary">Transgender</span>';
																		}
																	?>
																</span>
																<span class="text-muted ">
																	<i class="fa fa-calendar"></i> <?php echo $row['date_of_birth']?>
																	<br>
																</span>	
															</div>
															<span class="text-muted">
																<i class="fa fa-suitcase"></i> <?php echo $row['date_of_birth'];?>
															</span>
															<br>												
															<span class="text-muted">
																<i style="font-size:10px;" class="fa fa-phone"></i>
																<?php echo $row['country_code']."-".$row['mobile_number'];?>
															</span><br>
															<span class="text-muted">
																<?php /*echo $row['request_id']; */?>
															</span>	
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<?php 
								} 
							}
							else
							{ 
								?>
								<div class="col-md-12 float-left text-center"> 
									<img src="<?php echo base_url(); ?>uploads/nodata.png">
								</div>
								<?php 
							} 
						?>
					
						<?php
						if(count($resultData) > 0)
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
					else if( isset($type) && $type=="card_view")
					{
						?>
						<div class="card">
							<div class="card-body">
								<div class="row t-Cards--basic">
									<?php
										foreach($resultData as $row)
										{
											?>	
											<div class="col-sm-6 col-md-6 col-lg-4">
												<div class="card bg-white p-3 shadow">
													<div class="d-flex justify-content-between">
														<div class="user-info mt-0 mb-2">
															<div class="user-info__img">
															<?php
																$imgUrl = "uploads/profile_image/".$row['user_id'].'.png';
																if( file_exists($imgUrl) )
																{
																	?>
																	<img src="<?php echo base_url().$imgUrl; ?>" class="rounded-circle" width="40" height="40">
																	<?php 
																}
																else
																{
																	?>
																	<img src="<?php echo base_url(); ?>uploads/no-image.png" class="rounded-circle" width="40" height="40">
																	<?php 
																} 
															?>
															</div>
															<div class="user-info__basic">
															<h5 class="mb-0" style="font-weight:500;">
																<?php $clientName = ucfirst($row["first_name"])." ".ucfirst($row["last_name"]);?>
																<?php echo $clientName; ?>											
															</h5>
															<p class="text-muted mb-0">
																<?php 
																	if ($row['gender'] == 1) 
																	{
																		echo "Male";
																	}
																	elseif($row['gender'] == 2)
																	{
																		echo "Female";
																	}
																	else
																	{
																		echo "Transgender";
																	}
																?>
															</p>
															</div>
														</div>
														<!--Modal Start-->
														<div class="modal fade" id="exampleModal<?php echo $row['user_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?php echo $row['user_id'];?>" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header" --style="background: #022646;color: #fff;">
																		<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																		
																	<form action="<?php echo base_url();?>Employee/ManageEmployee/change_password/<?php echo $row['user_id'];?>" method="post">
																		<div class="modal-body">
																				
																			<div class="row">
																				<div class="form-group col-md-6">
																					<label class="col-form-label">User Name</label>
																					<input type="text" autocomplete="off" value="<?php echo $row['user_name'];?>" class="form-control" disabled />
																				</div>
																				<?php 
																					if($this->user_id == 1)
																					{
																						$inputtype = "text";
																					}else
																					{
																						$inputtype = "password";
																					}
																				?>
																				<div class="form-group col-md-6">
																					<label class="col-form-label">Current Password <span class="text-danger">*</span></label>
																					<input type="password" readonly autocomplete="off" name="password" value="<?php echo $row['original_password'];?>" id="password" class="form-control" required />
																				</div>
																			</div>
																			
																			<div class="row">
																				<div class="form-group col-md-6">
																					<label class="col-form-label">New Password <span class="text-danger">*</span></label>
																					<input type="password" autocomplete="off" name="new_password" id="new_password" class="form-control"required />
																				</div>
																			
																				<div class="form-group col-md-6">
																					<label class="col-form-label">Confirm New Password <span class="text-danger">*</span></label>
																					<input type="password" autocomplete="off" name="confirm_new_password" id="confirm_new_password" class="form-control" required />
																				</div>
																			</div>
																		</div>
																		
																		<div class="modal-footer">
																			<!--<button type="button" class="btn btn-light" data-dismiss="modal">Close </button>-->
																			<button type="submit" class="btn btn-primary ml-3">Submit </button>
																		</div>
																	</form>
																</div>
															</div>
														</div>

														<div class="dropdown">
															<a href="#" data-toggle="dropdown" aria-expanded="false">
																<i class="fa fa-ellipsis-v"></i>
															</a>
															<ul class="dropdown-menu dropdown-menu-right">
																<?php
																	if($employee['create_edit_only'] == 1 || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<a title="Edit" href="<?php echo base_url(); ?>employee/ManageEmployee/edit/<?php echo $row['user_id'];?>">
																				<i class="fa fa-pencil"></i> Edit
																			</a>
																		</li>
																		<?php 
																	} 
																?>
																
																<?php
																	if($employee['read_only'] == 1 || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<a title="View" href="<?php echo base_url(); ?>employee/viewEmployee/<?php echo $row['user_id'];?>">
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li>
																		<?php 
																	}
																?>
																
																<?php
																	if($employee['create_edit_only'] == 1 || $this->user_id == 1)
																	{
																		?>
																		<li>
																			<?php 
																				if($row['user_status'] == 1)
																				{
																					?>
																					<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['user_id'];?>/0" title="Block">
																						<i class="fa fa-ban"></i> Inactive
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a href="<?php echo base_url(); ?>employee/ManageEmployee/status/<?php echo $row['user_id'];?>/1" title="Unblock">
																						<i class="fa fa-check"></i> Active
																					</a>
																					<?php 
																				}
																			?>
																		</li>
																		<?php 
																	} 
																?>
																
																<li>
																	<a title="Change Password" href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['user_id'];?>">
																		<i class="fa fa-key"></i> Change Password
																	</a>
																</li>

																<li>
																	<a href="<?php echo base_url(); ?>employee/annexureDetails/<?php echo $row['user_id'];?>" title="Annexure Details">
																		<i class="fa fa-thumbs-up"></i> Annexure Details
																	</a>
																</li>
															</ul>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6 col-sm-6">
															<h5 class="mb-0">
																<small>
																	<i class="fa fa-calendar"></i> <?php echo $row['date_of_birth']?>
																</small>
															</h5>
															<h5 class="mb-0">
																<small>
																	<i class="fa fa-suitcase"></i> <?php echo $row['date_of_birth'];?>
																</small>
															</h5>
														</div>
														<div class="col-md-6 col-sm-6 text-right">
															<h6 class="mb-1"># <?php echo ucfirst($row['random_user_id']);?></h6>
															<h6 class="mb-0"><?php echo $row['country_code']."-".$row['mobile_number'];?></h6>
														</div>
													</div>
													<div -class="appointment-status">
														<a href="javascript:void(0);" title="Full body Scanning">
															<span class="float-left">
																<?php 												
																	echo $row['role_name'];
																?>	
															</span>
														</a>
														<span class="text-success font-weight-bold float-right">
															<?php 
																if ($row['user_status'] == 1) 
																{
																	echo '<span class="text-success">Active</span>';
																}
																else
																{
																	echo '<span class="text-orange">Inactive</span>';
																}
															?>
														</span>
													</div>
												</div>
											</div>
											<?php 
										} 
									?>

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
							</div>
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
				} 
			?>
		</div>
	</div><!-- Card end-->
	<?php /* if(isset($type) && $type =='view'){?>
		<a href='<?php echo base_url();?>employee/ManageEmployee/grid_view' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
	<?php }  */?>
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
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#email').on('input', function()
		{
			var email = $('#email').val();
			
			if (email == '') 
			{
				emp_email_state = false;
				return;
			}
			else
			{
				var type = '<?php echo $type;?>';
				if(type == 'add')
				{
					var id = 0;
				}
				else
				{
					var id = '<?php echo $id; ?>';
				}
				
				$.ajax({
					url: '<?php echo base_url();?>employee/EmailExist',
					type: 'post',
					data: {
						'email_check' : 1,
						'email' : email,
						'id' : id,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							emp_email_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".employee_email_exist_error").addClass("error");
							$(".employee_email_exist_error").attr("id", "email-error");
							$(".employee_email_exist_error").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.employee_email_exist_error').html('Sorry... Email already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".employee_email_exist_error").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>

<!--Employee Phone-->
<script type="text/javascript">  
	$('document').ready(function()
	{
		//Customer E-mail Start here
		$(".register-but").removeClass("disabled-class");
		
		var emp_email_state = false;

		$('#mobile_number').on('input', function()
		{
			var email = $('#mobile_number').val();
			
			if (email == '') 
			{
				emp_mob_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>employee/MobileExist',
					type: 'post',
					data: {
						'mob_check' : 1,'email' : email,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							emp_mob_state = false;
							
							/* $('.form-control.email').removeClass("valid");
							$('.form-control.email').addClass("error");
							
							$(".form-control.email").attr("aria-required", "true");
							$(".form-control.email").attr("aria-describedby", "email-error");
							$(".form-control.email").attr("aria-invalid", "true"); */
							
							$(".mobile_number_exist").addClass("error");
							$(".mobile_number_exist").attr("id", "email-error");
							$(".mobile_number_exist").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.mobile_number_exist').html('Sorry... Mobile Number already taken');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".mobile_number_exist").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>
<!--Employee Phone-->


<script type="text/javascript">  
	$('document').ready(function()
	{
		$(".register-but").removeClass("disabled-class");
		
		var user_name_state = false;

		$('#user_name').on('input', function()
		{
			var user_name = $('#user_name').val();
			
			if (user_name == '') 
			{
				user_name_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>employee/UsernameExist',
					//url: '<?php echo base_url();?>admin/EmailExist',
					type: 'post',
					data: {
						'user_name_check' : 1,'user_name' : user_name,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							user_name_state = false;
							
							$(".user_name_exist_error").addClass("error");
							$(".user_name_exist_error").attr("id", "email-error");
							$(".user_name_exist_error").attr("style", "display: inline;");
							
							$(".register-but").attr("disabled", "disabled=disabled");
							$(".register-but").addClass("disabled-class");
							$('.user_name_exist_error').html('Sorry... User Name already Exist!');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							
							$(".user_name_exist_error").attr("style", "display: none;");
							$(".register-but").removeAttr("disabled", "disabled=disabled");
							$(".register-but").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
	});
</script>

<script>
	$('#select_all').on('click', function(e) 
	{
		if($(this).is(':checked',true)) {
			$(".emp_checkbox").prop('checked', true);
		}
		else {
			$(".emp_checkbox").prop('checked',false);
		}
		// set all checked checkbox count
		//$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	});
</script>