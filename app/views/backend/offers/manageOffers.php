<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if( isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold">Offers</legend>
							<section class="">
														
								<div class="row">
									<div class="col-md-6">
										<div class="row">
											
											<?php $getbranchname = $this->db->query("select branch_name,branch_id from branch where active_flag='Y'  order by branch_name asc")->result_array(); ?>
											<label class="col-form-label col-md-3">Branch Name <span class="text-danger">*</span></label>
											<div class="form-group col-md-5">
												<select name="branch_name"  required class="form-control searchDropdown">
														<option value="">- Select  -</option>
														<?php 
															foreach($getbranchname as $branchname)
															{
																$selected="";
																if(isset($edit_data[0]['branch_id']) && ($edit_data[0]['branch_id'] == $branchname['branch_id']) )
																{
																	$selected="selected='selected'";
																}
																?>
																<option value="<?php echo $branchname['branch_id']; ?>" <?php echo $selected;?>><?php echo $branchname['branch_name']; ?></option>
																<?php 
															} 
														?>
												</select>
												<span class="exist_error text-warning"></span>
											</div>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="row">
											<label class="col-form-label col-md-3">Offer Percentage <span class="text-danger">*</span></label>
											<div class="form-group col-md-5">
												<input type="number" name="offer_percentage" id="offer_percentage" required class="form-control" value="<?php echo isset($edit_data[0]['offer_percentage']) ? $edit_data[0]['offer_percentage'] :'';?>" placeholder="">
												<span class="exist_error text-warning"></span>
											</div>
										</div>
									</div>
								</div>
							</section>
						</fieldset>

						<div class="row mb-3" style="margin-top:-15px;">
							<div class="col-md-7">
								<div class="row">
									
								</div>
							</div>
							
							
							
							<div class="col-md-2 mx-5">
								<div class="row" style="float:right;">
									<a href="<?php echo base_url(); ?>offers/ManageOffers" class="btn btn-default">Close</a>
									<button type="submit" class="btn btn-primary ml-1">Save</button>
								</div>
							</div>
							<div class="col-md-1">
								<div class="row">
									
								</div>
							</div>
						</div>
						
					</form>
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
							<a href="<?php echo base_url(); ?>offers/manageOffers/add" class="btn btn-info btn-sm">
								Create Offer
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-12">
								<section class="trans-section-back-1">
									<div class="row">
										
										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-4">Branch Name</label>
												<div class="form-group col-md-8">
													<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="">
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-3">Status</label>
												<div class="form-group col-md-8">
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
											<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
											&nbsp;&nbsp;<a href="<?php echo base_url(); ?>offers/manageOffers" title="Clear" class="btn btn-default">Clear</a>
										</div>
									</div>
								</section>
							</div>
						</div>
					</form>

					<?php 
					 	if(isset($_GET) && !empty($_GET))
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
												<th class="text-center" onclick="sortTable(1)">Branch Name</th>
												<th class="text-center" onclick="sortTable(2)">Offer Percentage ( % )</th>
												<th class="text-center" onclick="sortTable(3)">Status</th>
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
														<td style="width: 10%;" class="text-center">
															<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																	Action <i class="fa fa-angle-down"></i>
																</button>
																<ul class="dropdown-menu dropdown-menu-right">
																	<li>
																		<a href="<?php echo base_url(); ?>offers/ManageOffers/edit/<?php echo $row['offer_id'];?>">
																			<i class="fa fa-edit"></i> Edit
																		</a>
																	</li>

																	<li>											
																		<?php 
																			if($row['active_flag'] == $this->active_flag)
																			{
																				?>
																				<a class="unblock" href="<?php echo base_url(); ?>offers/ManageOffers/status/<?php echo $row['offer_id'];?>/N" title="Active">
																					<i class="fa fa-ban"></i> Inactive
																				</a>
																				<?php 
																			} 
																			else
																			{  ?>
																				<a class="block" href="<?php echo base_url(); ?>offers/ManageOffers/status/<?php echo $row['offer_id'];?>/Y" title="InActive">
																					<i class="fa fa-ban"></i> Active
																				</a>
																				<?php 
																			} 
																		?>
																	<li>
																</ul>
															</div>
														</td>

														<td class="text-center"><?php echo $row['branch_name'];?> </td>
														<td class="text-center"><?php echo $row['offer_percentage'];?> </td>
														<td style="text-align:center;">
															<?php 
																if($row['active_flag'] == $this->active_flag)
																{
																	?>
																	<span class="btn btn-outline-success btn-sm" title="Active">
																		Active 
																	</span>
																	<?php 
																} 
																else
																{  ?>
																	<span class="btn btn-outline-warning btn-sm" title="Inactive">
																		Inactive 
																	</span>
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
							</form>
							
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
</div><!-- Content end-->

