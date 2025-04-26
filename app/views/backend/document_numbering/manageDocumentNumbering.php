<?php 
	/* $listTypeModulesQry = "select 
	sm_list_type_values.list_type_value_id,
	sm_list_type_values.list_code,
	sm_list_type_values.list_value	
	from sm_list_type_values

	left join sm_list_types on 
	sm_list_types.list_type_id = sm_list_type_values.list_type_id
	where 
	sm_list_types.list_name = 'MODULES'";  */
	$getModulesCategory = $this->common_model->lov('MODULES');
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					if($type == "edit")
					{
						$searchDropdown = '';
						$dropDownStyls = 'pointer-events:none;background: #e7e3e3;';
						$readonly = 'readonly';
					}
					else if($type == "add")
					{
						$searchDropdown = 'searchDropdown';
						$dropDownStyls = '';
						$readonly = '';
					}
					?>
					<h3>
						<b>Document Numbering</b>
					</h3>
					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<fieldset class="mb-3">
									<div class="row">
										
										
										<div class="form-group col-md-3">
											<label class="col-form-label">Document Number Type <span class="text-danger">*</span></label>
											<select name="doc_type" id="doc_type" required style="<?php echo $dropDownStyls;?>" class="form-control <?php echo $searchDropdown; ?>">
												<option value="">- Select -</option>
												<?php 
													foreach($getModulesCategory as $row)
													{ 
														$selected="";

														if( isset($edit_data[0]['doc_type']) && $edit_data[0]['doc_type'] == $row['list_type_value_id'])
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $row['list_type_value_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['list_value']);?></option>
														<?php 
													} 
												?>
											</select>
										</div>

										<div class="form-group col-md-3 otherDiv">
											<label class="col-form-label">Document Type</label>
											<input type="text" name="doc_document_type"  <?php echo $readonly; ?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['doc_document_type']) ? $edit_data[0]['doc_document_type'] :"";?>" placeholder="">
										</div>	

										<div class="form-group col-md-3">
											<label class="col-form-label">Organization Name</label>
											<input type="text" name="org_name"<?php echo $this->validation;?> class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['org_name']) ? $edit_data[0]['org_name'] :"";?>" placeholder="">
										</div>
										
										<?php 
											$branchQry = "select branch_id, branch_name from branch

													where active_flag = 'Y'"; 
											$getBranch = $this->db->query($branchQry)->result_array();
										?>
										
										<div class="form-group col-md-3">
											<label class="col-form-label">Branch</label>
											<select name="branch_id" id="branch_id" class="form-control searchDropdown">
												<option value="">- Select -</option>
												<?php 
													foreach($getBranch as $row)
													{ 
														$selected="";

														if( isset($edit_data[0]['branch_id']) && $edit_data[0]['branch_id'] == $row['branch_id'])
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

									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">Prefix </label>
											<input type="text" name="prefix_name" id="prefix_name" class="form-control" autocomplete="off" value="<?php echo isset($edit_data[0]['prefix_name']) ? $edit_data[0]['prefix_name'] :"";?>" placeholder="">
										</div>
										
										<div class="form-group col-md-3">
											<label class="col-form-label">Next Number <span class="text-danger">*</span></label>
											<input type="text" name="next_number" id="next_number" required class="form-control mobile_vali" maxlength="15" autocomplete="off" value="<?php echo isset($edit_data[0]['next_number']) ? $edit_data[0]['next_number'] :"";?>" placeholder="">
										</div>
										<div class="form-group col-md-3">
											<label class="col-form-label">Suffix </label>
											<input type="text" name="suffix_name" id="suffix_name" class="form-control suffix_name" autocomplete="off" value="<?php echo isset($edit_data[0]['suffix_name']) ? $edit_data[0]['suffix_name'] :"";?>" placeholder="">
										</div>
									</div>

									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">From Date </label>
											<input type="text" name="from_date" class="form-control default_date" --id ="from_date" autocomplete="off" value="<?php echo isset($edit_data[0]['from_date']) ? date("d-M-Y",strtotime($edit_data[0]['from_date'])) :  date("d-M-Y");?>" placeholder="">
										</div>

										<div class="form-group col-md-3">
											<label class="col-form-label">To Date </label>
											<input type="text" name="to_date" class="form-control default_date" --id ="to_date" autocomplete="off" value="<?php echo isset($edit_data[0]['to_date']) ? date("d-M-Y",strtotime($edit_data[0]['to_date'])) :"";?>" placeholder="">
										</div>
										
										<script>
											$(function() {
												$('#prefix_name, #next_number, #suffix_name').on('input', function() 
												{
													$('#preview').val(
													$('#prefix_name, #next_number, #suffix_name').map(function() 
													{
														return $(this).val();
													}).get().join('') /* added space */
													);
												});
											});
										</script>

										<div class="form-group col-md-3">
											<label class="col-form-label" --id="preview">Preview </label>
											<?php 
												if($type=="edit")
												{
													$prefixName = $edit_data[0]['prefix_name'];
													$startingNumber = $edit_data[0]['next_number'];
													$suffixName = $edit_data[0]['suffix_name'];

													$vale= $prefixName.''.$startingNumber.''.$suffixName;
												}else{
													$vale= '';
												}
											?>
											<input type="text" class="form-control" readonly id="preview" value="<?php echo $vale;?>">
										</div>
									</div>
									
								</fieldset>
							</div>
						</div>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>document_numbering/manageDocumentNumbering" class="btn btn-default">Close</a>
							<button type="submit" class="btn btn-primary ml-1">Save</button>
						</div>
					</form>
					<?php
				}
				else
				{ 
					?>
					
					<div class="row mb-2">
						<div class="col-md-6">
							<h3><b>Document Numbering</b></h3>
						</div>

						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>document_numbering/manageDocumentNumbering/add" class="btn btn-info btn-sm">
								Create Document Number
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-5">	
										<select name="doc_type" id="doc_type" class="form-control searchDropdown">
											<option value="">- Select Type -</option>
											<?php 
												foreach($getModulesCategory as $row)
												{ 
													$selected="";
													if(isset($_GET['doc_type']) && $_GET['doc_type'] == $row["list_type_value_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row['list_type_value_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['list_value']);?></option>
													<?php 
												} 
											?>
										</select>
									</div>	

									<div class="col-md-4">
										<div class="row">
											<label class="col-form-label col-md-3">Status</label>
											<div class="form-group col-md-9">
												<?php 
													$activeStatusQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
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
			
													sm_list_types.list_name = 'ACTIVESTATUS'
													order by sm_list_type_values.order_sequence asc";
			
													$activeStatus = $this->db->query($activeStatusQry)->result_array(); 
												?>
												
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
									
									<div class="col-md-3">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										<a href="<?php echo base_url(); ?>document_numbering/manageDocumentNumbering" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
							
							<!--<div class="col-md-4 text-right">
								<?php 
									$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
								?>
								<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
														
								<div class="filter_page">
									<label>
										<span>Show :</span> 
										<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
											<?php /*
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
											*/ ?>
										</select>
									</label>
								</div>
							</div>-->
						</div>
					</form>

					<?php 
					if(isset($_GET) &&  !empty($_GET))
					{
						?>

						<div class="row">
							<div class="col-md-8">
								
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
					
						<div class="new-scroller">
							<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
								<thead>
									<tr>
										<th style="width:5%;" class="text-center">Controls</th>
										<th>Type</th>
										<th>Prefix</th>
										<th>Next Number</th>
										<th>Suffix</th>
										<th>Preview</th>
										<th>Branch</th>
										<th style="text-align:center;width:10%;">Status</th>
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
												<td style="width:8% !important;" class="text-center">
													<div class="dropdown" style="display: inline-block;padding-right: 10px!important;">
														<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
															Action <i class="fa fa-angle-down"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-right">
															<li>
																<a title="Edit" href="<?php echo base_url(); ?>document_numbering/manageDocumentNumbering/edit/<?php echo $row['doc_num_id'];?>">
																	<i class="fa fa-pencil"></i> Edit
																</a>
															</li>
															
															<li>
																<?php 
																	if($row['active_flag'] == $this->active_flag)
																	{
																		?>
																		<a href="<?php echo base_url(); ?>document_numbering/manageDocumentNumbering/status/<?php echo $row['doc_num_id'];?>/N" title="Inactive">
																			<i class="fa fa-ban"></i> Inactive
																		</a>
																		<?php 
																	} 
																	else
																	{  ?>
																		<a href="<?php echo base_url(); ?>document_numbering/manageDocumentNumbering/status/<?php echo $row['doc_num_id'];?>/Y" title="Active">
																			<i class="fa fa-check"></i> Active
																		</a>
																		<?php 
																	} 
																?>
															</li>
														</ul>
													</div>
												</td>
												<td><?php echo ucfirst($row['list_value']);?></td>
												<td><?php echo ucfirst($row['prefix_name']);?></td>
												<td><?php echo ucfirst($row['next_number']);?></td>
												<td><?php echo ucfirst($row['suffix_name']);?></td>

												<td>
													<?php
														$prefixName = $row['prefix_name'];
														$startingNumber = $row['next_number'];
														$suffixName = $row['suffix_name'];

														$vale= $prefixName.''.$startingNumber.''.$suffixName;
														echo $vale;
													?>
												</td>
												<td><?php echo ucfirst($row['branch_name']);?></td>
												<td class="text-center">
													<?php 
														if($row['active_flag'] == $this->active_flag)
														{
															?>
															<span class="btn btn-outline-success btn-sm" title="Active">Active</span>
															<?php 
														} 
														else
														{ 
															?>
															<span class="btn btn-outline-warning btn-sm" title="Inactive">Inactive</span>
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
	
