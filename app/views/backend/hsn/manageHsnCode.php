<?php 
	#$system_manager = accessMenu(hsn_code);
?>

<style>
	.dropdown-menu.dropdown-menu.show{
		position: absolute;
		/* transform: translate3d(9px, -50px, -1px) !important;
		top: 0px;
		left: 0px;
		will-change: transform; */
	}
</style>

<div class="content">
	<div class="card">
		<div class="card-body">
			<?php
				if( isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold">
								HSN Code
							</legend>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">HSN Code <span class="text-danger">*</span></label>
									<input type="text" name="hsn_code" id="hsn_code" maxlength="8"<?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['hsn_code']) ? $edit_data[0]['hsn_code'] :"";?>" placeholder="">
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Description</label>
									<input type="text" name="hsn_code_description" <?php echo $this->validation;?> id="hsn_code_description" class="form-control" value="<?php echo isset($edit_data[0]['hsn_code_description']) ? $edit_data[0]['hsn_code_description'] :"";?>" placeholder="">
								</div>

								
								
								<div class="form-group col-md-3">
									<label class="col-form-label">TAX </label>
									<?php 
										$gettax = $this->db->query("select tax_id, tax_name from gen_tax where active_flag='Y' order by tax_name asc")->result_array();
									?>
									
									<select name="tax_id" id="tax_id" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach($gettax as $row)
											{ 
												$selected="";
												if( isset($edit_data[0]['tax_id']) && $edit_data[0]['tax_id'] == $row['tax_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['tax_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['tax_name']);?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Start Date </label>
									<input type="text" name="start_date" id="start_date_1" readonly  class="form-control start_date" value="<?php echo isset($edit_data[0]['start_date']) ? date("d-M-Y",strtotime($edit_data[0]['start_date'])) : "";?>" placeholder="">
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">End Date</label>
									<?php 
										if( isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date']) )
										{
											$end_date = date('d-M-Y',strtotime($edit_data[0]['end_date']));
										}
										else
										{
											$end_date ='';
										}
									?>
									<input type="text" name="end_date" id="end_date_1" readonly <?php echo $this->validation;?> class="form-control end_date" value="<?php echo $end_date;?>" placeholder="">
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad" style="float:right;">
							<a href="<?php echo base_url(); ?>hsn/manageHsnCode" class="btn btn-default">Close</a>
							<button type="submit" class="btn btn-primary ml-1">Save</button>
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
							<!-- <a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a> -->
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>hsn/manageHsnCode/add" class="btn btn-info btn-sm">
								Create HSN Code
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<section class="trans-section-back-1">
									<div class="row">
										<div class="col-md-4">	
											<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
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
											<a href="<?php echo base_url(); ?>hsn/manageHsnCode" title="Clear" class="btn btn-default">Clear</a>
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
												<th style="text-align:center;width:12%;">Controls</th>
												<th style="text-align:center;" onclick="sortTable(1)">HSN Code</th>
												<th>Description</th>
												<th onclick="sortTable(2)" class="text-center">Tax Name</th>
												<th style="text-align:center;" onclick="sortTable(3)">Start Date</th>
												<th style="text-align:center;" onclick="sortTable(4)">End Date</th>
												<th style="text-align:center;width:10%;" onclick="sortTable(5)">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php 	
												$i=0;
												$firstItem = $first_item;
												foreach($resultData as $row)
												{
													?>
													<tr class="data-new-table">
														<td class="text-center">
															<?php 
																/* if($system_manager['create_edit_only'] == 1 || $this->user_id == 1)
																{ */
																	?>
																	<div class="dropdown">
																		<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																			Action <i class="fa fa-angle-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li>
																				<a title="Edit" href="<?php echo base_url(); ?>hsn/manageHsnCode/edit/<?php echo $row['hsn_code_id'];?>">
																					<i class="fa fa-pencil"></i> Edit
																				</a>
																			</li>
																			<li>
																				<?php 
																					if($row['active_flag'] == 'Y')
																					{
																						?>
																						<a href="<?php echo base_url(); ?>hsn/manageHsnCode/status/<?php echo $row['hsn_code_id'];?>/N" title="Block">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a href="<?php echo base_url(); ?>hsn/manageHsnCode/status/<?php echo $row['hsn_code_id'];?>/Y" title="Unblock">
																							<i class="fa fa-ban"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			</li>
																		</ul>
																	</div>
																	<?php 
																/* }
																else
																{
																	?>
																	-
																	<?php
																} */	
															?>
														</td>
														<td style="text-align:center;"><?php echo $row['hsn_code'];?></td>
														<td style="text-align:left;"><?php echo ucfirst($row['hsn_code_description']);?></td>
														<td style="text-align:center;"><?php echo $row['tax_name'];?>
															
														</td>
														<td style="text-align:center;">
															<?php 
																if($row['start_date'] != NULL)
																{	
																	echo date(DATE_FORMAT,strtotime($row['start_date']));
																}
															?>
														</td>
														<td style="text-align:center;">
															<?php 
																if(!empty($row['end_date']) && $row['end_date'] != NULL)
																{
																	echo date(DATE_FORMAT,strtotime($row['end_date']));
																}
															?>
														</td>
														
														<td style="text-align:center;">
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
	</div>
</div>


