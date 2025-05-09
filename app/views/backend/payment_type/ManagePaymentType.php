
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && ($type == "add" || $type == "edit"))
				{
					?>
					<div class="row">
						<div class="col-xl-12 col-xxl-12 col-lg-12">
							<div class="-card">
								<div class="-card-header">
									<h5><b>Payment type</b></h5>
								</div>
								<div class="-card-body">
									<div class="">
										<form class="form-validate-jquery" action="#" method="post" enctype="multipart/form-data">
											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Payment Type <span class="text-danger">*</span></label>
												<div class="col-lg-3">
													<input type="text" name="payment_type" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['payment_type']) ? $edit_data[0]['payment_type'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Sequence Number</label>
												<div class="col-lg-3">
													<input type="text" name="sequence_number" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['sequence_number']) ? $edit_data[0]['sequence_number'] :"";?>" placeholder="">
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Usage</label>

												<div class="col-lg-3">
													<?php
														if(isset($edit_data[0]['usage_online']) && $edit_data[0]['usage_online'] == 'Y'){
															$usage_online_checked='checked=checked';
														}else{
															$usage_online_checked='';
														}
													
														if(isset($edit_data[0]['usage_pos']) && $edit_data[0]['usage_pos'] == 'Y'){
															$usage_pos_checked='checked=checked';
														}else{
															$usage_pos_checked='';
														}
													?>
													<label class="chk-label" class='mr-3'>Online</label>
													<input type="checkbox" name="usages[]" required class='mr-4' id="usage_online" value="online" <?php echo $usage_online_checked; ?>>
													
													<label class="chk-label">POS / Dine-In</label>
													<input type="checkbox" name="usages[]" required class='mr-1' id="usage_pos" value="pos" <?php echo $usage_pos_checked; ?>>
												</div>
											</div>

											<style>
												label.chk-label {
													/* padding: 0px 5px 3px 5px; */
													margin: -1px 3px 4px 4px;
													top: -2px;
													position: relative;
												}
											</style>

											<div class="form-group row">
												<label class="col-form-label col-lg-2 text-left">Payment Icon</label>
												<div class="col-lg-3">
												<input type="file" name="payment_icon" onchange="return validateSingleFileExtension(this)" class="form-control singleImage">
													<span class="note-class"><b>Note</b> : Upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
													<script>
														/** Single Image Type & Size Validation **/
														function validateSingleFileExtension(fld) 
														{
															var fileUpload = fld;
															
															if (typeof (fileUpload.files) != "undefined")
															{
																var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
																var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
																
																if( size > validSize )
																{
																	alert("Upload size is 1 MB");
																	$('.singleImage').val('');
																	var value = 1;
																	return false;
																}
																else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
																{
																	alert("Invalid file type.");      
																	$('.singleImage').val('');
																	return false;   
																}
																
																if(value != 1)	
																	return true; 
															}
														}
													</script>
													
												</div>
												<?php
													if ($type == "edit") {
														if(file_exists("uploads/payments/".$id.'.png') )
														{
															$photo_url = base_url().'uploads/payments/'.$id.'.png';
															?>
																<img src="<?php echo $photo_url;?>" style="height:100px; width:100px;margin-top:20px" alt="best dental hospial hosur">
															<?php 
														}	
													}
												?>
											</div>
											
											<div class="form-group float-right">
												<a href="<?php echo base_url();?>paymenttype/managePaymenttype" class="btn btn-light btn-sm">Close</a>
												<button type="submit" class="btn btn-primary text-right btn-sm">Save  </button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else 
				{
					?>
					
					<div class="row mb-2">
						<div class="col-md-6"><h5><b><?php echo $page_title;?></b></h5></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>paymenttype/ManagePaymenttype/add" class="btn btn-info btn-sm">
								Create Payment Type
							</a>
						</div>
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Payment Type</label>
									<div class="form-group col-md-7">
										<input type="search" name="payment_type" class="form-control" value="<?php echo !empty($_GET['payment_type']) ? $_GET['payment_type'] :""; ?>" placeholder="" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="col-md-3">
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

							<div class="col-md-4">
								<div class="row">
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
									<div class="col-md-3">
										<a href="<?php echo base_url(); ?>paymenttype/ManagePaymenttype" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!-- filters-->
									

					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<div class="col-md-10">
								</div>
								<div class="col-md-2 float-right text-right">
														
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
							<!-- Page Item Show start -->

							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table table-bordered">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<!-- <th onclick="sortTable(2)" class="text-center">Payment Icon</th> -->
												<th>Payment Type</th>
												<th class="text-center">Sequence Number</th>
												<th class="text-center">Usage Online</th>
												<th class="text-center">Usage POS / Dine-In</th>
												<th class="text-center" style="width: 15%;">Status</th>
												<th class="text-center">Default</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (count($resultData) > 0) 
												{
													$i=0;
													$firstItem = $first_item;
													foreach($resultData as $row)
													{
														?>
														<tr>
															<td style="width: 12%;" class="text-center">
																<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																	<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																		Action <i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a href="<?php echo base_url();?>paymenttype/ManagePaymenttype/edit/<?php echo $row['payment_type_id'];?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		<li>											
																			<?php 
																				if($row['active_flag'] == $this->active_flag)
																				{
																					?>
																					<a class="unblock" href="<?php echo base_url(); ?>paymenttype/ManagePaymenttype/status/<?php echo $row['payment_type_id'];?>/N" title="Block">
																						<i class="fa fa-ban"></i> In Active
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a class="block" href="<?php echo base_url(); ?>paymenttype/ManagePaymenttype/status/<?php echo $row['payment_type_id'];?>/Y" title="Unblock">
																						<i class="fa fa-check"></i> Active
																					</a>
																					<?php 
																				} 
																			?>
																		<li>
																	</ul>
																</div>																		
															</td>
															
															<?php /*
															<td class="text-center">
																<?php 
																	if(file_exists("uploads/payments/".$row['payment_type_id'].'.png') )
																	{
																		$photo_url = base_url().'uploads/payments/'.$row['payment_type_id'].'.png';
																		?>
																		<a href="<?php echo $photo_url;?>" data-magnify="gallery" data-caption="<?php echo $row['payment_type'];?>">
																			<img src="<?php echo $photo_url;?>" style="border:1px solid #ddd; border-radius:4px; padding:5px; height:60px; width:60px;" alt="best dental hospial hosur">
																		</a>
																		<?php 
																	}
																	else
																	{
																		?>
																		<img src="<?php echo base_url();?>uploads/no-image.png" style="max-width:60px !important; max-height:60px !important;" alt="...">
																		<?php
																	}
																?>
															</td> */ ?>

															<td><?php echo ucfirst($row['payment_type']);?></td>

															<td class="text-center"><?php echo ucfirst($row['sequence_number']);?></td>
															
															<td class="text-center">
																<?php 
																	if($row['usage_online'] == 'Y')
																	{
																		?>
																		<span class="text-success">Yes<s/pan>
																		<?php
																	}
																	else
																	{
																		?>
																		<span class="text-danger">No</span>
																		<?php
																	}
																?>
															</td>

															<td class="text-center">
																<?php 
																	if($row['usage_pos'] == 'Y')
																	{
																		?>
																		<span class="text-success">Yes<s/pan>
																		<?php
																	}
																	else
																	{
																		?>
																		<span class="text-danger">No</span>
																		<?php
																	}
																?>
															</td>

															<td class="text-center">
																<?php
																	if($row['active_flag'] == $this->active_flag)
																	{
																		?>
																		<span class="btn btn-outline-success btn-xs" title="Active">Active</span>
																		<?php
																	} 
																	else 
																	{
																		?>
																		<span class="btn btn-outline-warning btn-xs" title="Inactive">Inactive</span>
																		<?php
																	} 
																?>
															</td>
																																															
															<td class="text-center">
																<?php 
															      	if($row['active_flag'] == 'Y')
															        {
																		?>
																		<input type="radio" name="default_payment" <?php if($row['default_payment'] == 'Y'){?>checked<?php }?> value="<?php echo $row['payment_type_id']; ?>"/>
																		<?php 	
															        } 
															    ?>
															</td>
														</tr>
														<?php
														$i++;
													}											
												}
												else 
												{
													?>
													<tr>
														<td class="text-center" colspan="20">
															<img src="<?php echo base_url();?>uploads\nodata.png" style="width:200px;height:200px;"><br>
															<!--<p class="admin-no-data">No data found.</p>-->
														</td>
													</tr>
													<?php 
												} 
											?>
											<?php
												if (count($resultData) > 0) 
												{
													?>
													<tr>
														<td colspan="6"></td>
														<td class="text-center">
															<button type="submit" name="default_submit" class="btn btn-outline-primary ml-3 btn-xs updates">Update</button>
														</td>
													</tr>
													<?php 
												} 
											?>
										</tbody>
									</table>
								</div>
							</form>

							<?php
								if (count($resultData) > 0) 
								{
									?>
									<div class="row mt-3">
										<div class="col-md-6 showing-count">
											Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
										</div>
										<!-- pagination start here -->
										<?php 
											if( isset($pagination) )
											{
												?>	
												<div class="col-md-6">
													<?php foreach ($pagination as $link){echo $link;} ?>
												</div>
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
	</div>
</div>
	
