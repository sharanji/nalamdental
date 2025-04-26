<?php $expensecCategoryMenu = accessMenu(expense_category); ?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{
				?>
				<div class="card-body">
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<h3><b><?php echo ucfirst($type);?> Expense Category</b></h3>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Expense Type <span class="text-danger">*</span></label>
									<div class="">
										<?php 
											$expenseTypeQry = "select * from expense_type where type_status=1";
											$getExpenseType = $this->db->query($expenseTypeQry)->result_array();
										?>
										<select name="expense_type_id" id="expense_type_id" required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getExpenseType as $expenseType)
												{
													$selected ="";
													if(isset($edit_data[0]['expense_type_id']) && $edit_data[0]['expense_type_id'] == $expenseType["type_id"])
													{
														$selected ="selected='selected'";
													}
													?>
													<option value="<?php echo $expenseType["type_id"];?>" <?php echo $selected; ?>><?php echo $expenseType["type_name"];?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								 </div>
							</div>
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Expense Category Name <span class="text-danger">*</span></label>
									<div class="">
										<input type="text" name="particular_name" id="particular_name" required class="form-control" value="<?php echo isset($edit_data[0]['particular_name']) ? $edit_data[0]['particular_name'] :"";?>" placeholder="">
									</div>
								 </div>
							</div>
						</fieldset>
						
						<div class="d-flexad float-right" style="align:center;">
							<a href="<?php echo base_url(); ?>expense/ManageParticulars" class="btn btn-default">Cancel</a>
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
								if((isset($expensecCategoryMenu['create_edit_only']) && $expensecCategoryMenu['create_edit_only'] == 1) || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>expense/ManageParticulars/add" class="btn btn-info btn-sm">
										Add Expense Category
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
											<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
											<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Expense Type, Expense Category</span>
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
												<th>Expense Category</th>
												<th  class="text-center">Status </th>
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
														<td class="text-center">
															<?php
																if((isset($expensecCategoryMenu['create_edit_only']) && $expensecCategoryMenu['create_edit_only'] == 1) || $this->user_id == 1)
																{
																	?>
																	<div class="dropdown">
																		<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
																			Action&nbsp;<i class="fa fa-chevron-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li>
																				<a title="Edit" href="<?php echo base_url(); ?>expense/ManageParticulars/edit/<?php echo $row['particular_id'];?>">
																					<i class="fa fa-edit"></i> Edit
																				</a> 
																			</li>
																			
																			<li>
																				<?php 
																					if($row['particular_status'] == 1)
																					{
																						?>
																						<a href="<?php echo base_url(); ?>expense/ManageParticulars/status/<?php echo $row['particular_id'];?>/0" title="Block">
																							<i class="fa fa-ban"></i>  Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a href="<?php echo base_url(); ?>expense/ManageParticulars/status/<?php echo $row['particular_id'];?>/1" title="Unblock">
																							<i class="fa fa-ban"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			<li>
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
														<td><?php echo ($row['type_name']);?></td>
														<td><?php echo ucfirst($row['particular_name']);?></td>
														<td class="text-center">
															<?php 
																if($row['particular_status'] == 1)
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
														<?php /* <td>
															
															<?php 
																if($row['particular_status'] == 1)
																{
																	?>
																	<a class="btn btn-success btn-class" class="unblock" href="<?php echo base_url(); ?>expense/ManageParticulars/status/<?php echo $row['particular_id'];?>/0" title="Block">
																		<i class="icon-blocked"></i>
																	</a>
																	<?php 
																} 
																else
																{  ?>
																	<a class="btn btn-danger btn-class" class="block" href="<?php echo base_url(); ?>expense/ManageParticulars/status/<?php echo $row['particular_id'];?>/1" title="Unblock">
																		<i class="icon-blocked"></i>
																	</a>
																	<?php 
																} 
															?>
															&nbsp;
															<a class="btn btn-danger btn-class" title="Delete" href="<?php echo base_url();?>expense/ManageParticulars/delete/<?php echo $row['particular_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																<i class="icon-trash"></i>
															</a>
														</td> */ ?>
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

