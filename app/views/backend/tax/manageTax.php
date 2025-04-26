
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && ($type == "add" || $type == "edit" || $type == "view"))
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
					<div class="row">
						<div class="col-xl-12 col-xxl-12 col-lg-12">
							<div class="-card">
								<div class="-card-header">
									<h4 class="-card-title">Tax</h4>
								</div>
								<div class="-card-body">
									<div class="">
										<form class="form-validate-jquery" action="#"  method="post" enctype="multipart/form-data">
											<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">Tax Name<span class="text-danger">*</span></label>
													<div class="col-lg-3">
														<input type="text" name="tax_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['tax_name']) ? $edit_data[0]['tax_name'] :"";?>" placeholder="">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">Tax Value</label>
													<div class="col-lg-3">
														<input type="text" name="tax_value" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['tax_value']) ? $edit_data[0]['tax_value'] :"";?>" placeholder="">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">Start Date </label>
													<?php 
														if(isset($edit_data[0]['start_date']) && !empty($edit_data[0]['start_date'])){
															$start_date = date(DATE_FORMAT,strtotime($edit_data[0]['start_date']));
														}else{$start_date = NULL;}
													?>
													<div class="col-lg-3">
														<input type="text" name="start_date" id="start_date_1" readonly class="form-control start_date" value="<?php echo $start_date;?>" placeholder="">
												
													</div>
												</div>
												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">End Date</label>
													<?php 
														if(isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date'])){
															$end_date = date(DATE_FORMAT,strtotime($edit_data[0]['end_date']));
														}else{$end_date = NULL;}
													?>
													<div class="col-lg-3">
													<input type="text" name="end_date" id="end_date_1" readonly class="form-control end_date" value="<?php echo $end_date;?>" placeholder="">
													</div>
												</div>
											</fieldset>
											<div class="form-group float-right">
												<a href="<?php echo base_url();?>tax/manageTax" class="btn btn-light btn-sm">Close</a>
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
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>tax/manageTax/add" class="btn btn-info btn-sm">
								Create Tax
							</a>
						</div>
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Tax Name</label>
									<div class="form-group col-md-7">
										<input type="search" name="tax_name" class="form-control" value="<?php echo !empty($_GET['tax_name']) ? $_GET['tax_name'] :""; ?>" placeholder="" autocomplete="off">
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
										<a href="<?php echo base_url(); ?>tax/ManageTax" title="Clear" class="btn btn-default">Clear</a>
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
							<!-- Page Item Show start -->

							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table table-bordered">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Tax Name</th>
												<th class="text-center">Tax Value</th>
												<th class="text-center">Status</th>
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
																			<a title="View" href="<?php echo base_url(); ?>tax/manageTax/view/<?php echo $row['tax_id'];?>">
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li>
																		<li>
																			<a href="<?php echo base_url();?>tax/manageTax/edit/<?php echo $row['tax_id'];?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		<li>											
																			<?php 
																				if($row['active_flag'] == $this->active_flag)
																				{
																					?>
																					<a class="unblock" href="<?php echo base_url(); ?>tax/manageTax/status/<?php echo $row['tax_id'];?>/N" title="Block">
																						<i class="fa fa-ban"></i> In Active
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a class="block" href="<?php echo base_url(); ?>tax/manageTax/status/<?php echo $row['tax_id'];?>/Y" title="Unblock">
																						<i class="fa fa-check"></i> Active
																					</a>
																					<?php 
																				} 
																			?>
																		<li>
																	</ul>
																</div>																		
															</td>

															<td><?php echo ucfirst($row['tax_name']);?></td>

															<td class="text-center"><?php echo ucfirst($row['tax_value']);?></td>

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
																  <input type="radio" name="default_tax" <?php if($row['default_tax'] == 1){?>checked<?php }?> value="<?php echo $row['tax_id']; ?>"/>
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
														<td colspan="4"></td>
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
	
