<?php
	$expenseTypeMenu = accessMenu(expense_type);
?>	
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{
				?>
				<div class="card-body">
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<h3><b><?php echo ucfirst($type);?> Expense Type</b></h3>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Expense Type <span class="text-danger">*</span></label>
									<input type="text" name="type_name" id="type_name" autocomplete="off" required class="form-control" value="<?php echo isset($edit_data[0]['type_name']) ? $edit_data[0]['type_name'] :"";?>" placeholder="">
								</div>	 
							</div>

							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Expense Type Description</label>
									<textarea name="type_description" id="type_description" autocomplete="off" class="form-control"><?php echo isset($edit_data[0]['type_description']) ? $edit_data[0]['type_description'] :"";?></textarea>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flexad float-right">
							<a href="<?php echo base_url(); ?>expense/ManageExpenseType" class="btn btn-default">Cancel</a>
							<?php 
								if($type == "edit")
								{
									?>
									<button type="submit" class="btn btn-primary ml-1">Update</button>
									<?php 
								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary">Submit</button>
									<?php 
								}
							?>
						</div>
					</form>
				</div>
				<?php
			}
			else
			{ 
				?>
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<?php
								if((isset($expenseTypeMenu['create_edit_only']) && $expenseTypeMenu['create_edit_only'] == 1) || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>expense/ManageExpenseType/add" class="btn btn-info btn-sm">
										Add Expense Type
									</a>
									<?php 
								} 
							?>
						</div>
					</div>

					<form action="" method="get">
						<section class="trans-section-back-1">
							<div class="col-md-12 row px-0 mx-0">
								<div class="col-md-6">	
									<div class="row">	
										<div class="col-md-6 pl-0">	
											<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" autocomplete="off" placeholder="Search...">
											<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Expense Type</span>
										</div>	
										<div class="col-md-4">
											<button type="submit" class="btn btn-info --trans-saction-butt">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										</div>
									</div>
								</div>
								<div class="col-md-6 pr-0">
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
						</section>
					</form>
				
					<?php 
						if ( (isset($_GET) && !empty($_GET)) && count($resultData) > 0) 
						{
							?>
							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Expense Type</th>
												<th>Expense Type Description</th>
												<th class="text-center">Status</th>
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
														<td class="text-center" style="width:10%;">
															<?php
																if((isset($expenseTypeMenu['create_edit_only']) && $expenseTypeMenu['create_edit_only'] == 1) || $this->user_id == 1)
																{
																	?>
																	<div class="dropdown">
																		<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																			Action&nbsp;<i class="fa fa-chevron-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<?php
																				if($expenseTypeMenu['create_edit_only'] == 1 || $this->user_id == 1)
																				{
																					?>
																					<li>
																						<a title="Edit" href="<?php echo base_url(); ?>expense/ManageExpenseType/edit/<?php echo $row['type_id'];?>">
																							<i class="fa fa-edit"></i> Edit
																						</a> 
																					</li>
																					
																			
																					<li>
																						<?php 
																							if($row['type_status'] == 1)
																							{
																								?>
																								<a href="<?php echo base_url(); ?>expense/ManageExpenseType/status/<?php echo $row['type_id'];?>/0" title="Block">
																									<i class="fa fa-ban"></i>  Inactive
																								</a>
																								<?php 
																							} 
																							else
																							{  ?>
																								<a href="<?php echo base_url(); ?>expense/ManageExpenseType/status/<?php echo $row['type_id'];?>/1" title="Unblock">
																									<i class="fa fa-ban"></i> Active
																								</a>
																								<?php 
																							} 
																						?>
																					</li>
																					<?php 
																				} 
																			?>
																		</ul>
																	</div>
																	<?php 
																}
																else
																{
																	?>
																	--
																	<?php
																}
															?>
														</td>
														<td><?php echo ucfirst($row['type_name']);?></td>
														<td><?php echo ucfirst($row['type_description']);?></td>
														<td class="text-center" style="width:10%;">
															<?php 
																if($row['type_status'] == 1)
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
				</div>
				<?php 
			} 
		?>
	</div><!-- Card end-->
</div><!-- Content end-->

