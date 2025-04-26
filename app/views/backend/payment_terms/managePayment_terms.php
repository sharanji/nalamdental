<?php 
	$system_manager = accessMenu(uom);
?>
<style>
	.dropdown-menu.dropdown-menu.show{
		position: absolute;
		transform: translate3d(9px, -50px, -1px) !important;
		top: 0px;
		left: 0px;
		will-change: transform;
	}
</style>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if( isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
						        <h5>
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
									   Payment terms
									</b>
								</h5>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Payment Term<span class="text-danger">*</span></label>
									<input type="text" name="payment_term" id="payment_term" required  class="form-control single_quotes" autocomplete="off" value="<?php echo isset($edit_data[0]['payment_term']) ? $edit_data[0]['payment_term'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Description </label>
									<input type="text" name="payment_description" id="payment_description" class="form-control single_quotes" autocomplete="off" value="<?php echo isset($edit_data[0]['payment_description']) ? $edit_data[0]['payment_description'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Payment Term Days <span class="text-danger">*</span></label>
									<input type="number" name="payment_days" id="payment_days" required  class="form-control single_quotes" autocomplete="off" value="<?php echo isset($edit_data[0]['payment_days']) ? $edit_data[0]['payment_days'] :"";?>" placeholder="">
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>payment_terms/managePayment_terms" class="btn btn-default">Close</a>
							<button type="submit" class="btn btn-primary ml-1">Save</button>
						</div>
					</form>
					<?php
				}
				else
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><h5><b><?php echo $page_title;?></b></h5></div>
						<div class="col-md-6 float-right text-right">
							<!-- <a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a> -->
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>payment_terms/managePayment_terms/add" class="btn btn-info btn-sm">
								Create Payment Term
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Payment Term</p>
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
				
														sm_list_types.list_name = 'ACTIVESTATUS'";
				
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
										<a href="<?php echo base_url(); ?>payment_terms/managePayment_terms" title="Clear" class="btn btn-default">Clear</a>
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
					
							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Payment Term</th>
												<th>Payment Term Description </th>
												<th class="text-center">Payment Term Days </th>
												<th onclick="sortTable(3)" class="text-center">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php 	
												$firstItem = $first_item;
												foreach($resultData as $row)
												{
													?>
													<tr>
														<td style="width: 8%;" class="text-center">
															<?php 
																if($system_manager['create_edit_only'] == 1 || $this->user_id == 1)
																{
																	?>
																	<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																		<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																			Action <i class="fa fa-angle-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right dropdown-menu-new">
																			<li>
																				<a title="Edit" href="<?php echo base_url(); ?>payment_terms/managePayment_terms/edit/<?php echo $row['payment_term_id'];?>">
																					<i class="fa fa-pencil"></i> Edit
																				</a>
																			</li>
																			<li>
																				<?php 
																					if($row['active_flag'] == 'Y')
																					{
																						?>
																						<a href="<?php echo base_url(); ?>payment_terms/managePayment_terms/status/<?php echo $row['payment_term_id'];?>/N" title="Block">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a href="<?php echo base_url(); ?>payment_terms/managePayment_terms/status/<?php echo $row['payment_term_id'];?>/Y" title="Unblock">
																							<i class="fa fa-check"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			</li>
																			<?php /* <li>
																				<a href="<?php echo base_url();?>uom/ManageUom/delete/<?php echo $row['uom_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																					<i class="fa fa-trash"></i> Delete
																				</a>
																			</li> */ ?>
																		</ul>
																	</div>
																	<?php 
																}
																else
																{
																	?>
																	-
																	<?php
																}	
															?>
														</td>
														<td><?php echo $row['payment_term'];?></td>
														<td><?php echo $row['payment_description'];?></td>
														<td class="tab-mobile-width text-center"><?php echo $row['payment_days'];?></td>
														<td class="text-center">
															<?php 
																if($row['active_flag'] == 'Y')
																{
																	?>
																	<span class="btn btn-outline-success btn-sm" title="Active"> Active</span>
																	<?php 
																} 
																else
																{ 
																	?>
																	<span class="btn btn-outline-warning btn-sm" title="Inactive"> Inactive</span>
																	<?php 
																} 
															?>
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
							</form>
							
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

					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
	<?php if(isset($type) && $type =='view'){?>
		<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
	<?php } ?>
</div><!-- Content end-->


<script>
	// select all checkbox
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