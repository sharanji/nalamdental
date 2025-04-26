<!-- Page header start-->
<div class="page-header page-header-light">
	<?php /* <div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex back-header-full">
			<h4>
				<i class="icon-arrow-left52 mr-2"></i> 
				<span class="font-weight-semibold"> 
					<?php
						if(isset($type) && $type == "view")
						{ 
							?>
							<?php echo ucfirst($type);?> Customer Type
							<?php 
						}
						else
						{ 
							?>
							<?php echo $page_title;?>
							<?php 
						} 
					?>
					
				</span>
			</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div> */ ?>
	

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url(); ?>customer/ManageCustomerType" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
		</div>
		
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{ 
				
			}
			else
			{ 
				?>
				<div class="text-right new-import-btn">
					<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm"><i class="icon-arrow-left16"></i> Back</a>
					
					<a href="<?php echo base_url(); ?>customer/ManageCustomerType/add" class="btn btn-info btn-sm">
						 Add Cutomer Type 
					</a>
				</div>
				
				<?php 
			} 
		?>
	</div>
</div>
<!-- Page header end-->

	<div class="content"><!-- Content start-->
		<div class="card"><!-- Card start-->
			<div class="card-body mt-2">	
					<?php
					/* <div class="header-elements">
						<div class="list-icons">
							<a class="list-icons-item" data-action="collapse"></a>
							<a class="list-icons-item" data-action="reload"></a>
							<a class="list-icons-item" data-action="remove"></a>
						</div>
					</div> */ ?>
					<?php 
						/* if( isset($resultData) && count($resultData) > 0 )
						{
							?>
							<div class="header-elements">
								<div class="list-icons">
									<a href="<?php echo base_url(); ?>customer/ManageCustomerType/export" class="btn btn-primary"><i class="fa fa-download"></i> Export</a>
									<!--<a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-info"><i class="fa fa-arrow-up"></i> Import</a>
									-->
								</div>	
							</div>
							<?php 
						}  */
					?>
				
				
				<!-- Modal 
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					  </div>
					  <div class="modal-body">
						...
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					  </div>
					</div>
				  </div>
				</div>
				-->
					
				<?php
					if(isset($type) && $type == "add" || $type == "edit")
					{
						?>

							<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> Customer Type</legend>
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">Customer Type <span class="text-danger">*</span></label>
											<div class="">
												<input type="text" name="customer_type_name" <?php echo $this->validation;?> id="customer_type_name" required class="form-control" value="<?php echo isset($edit_data[0]['customer_type_name']) ? $edit_data[0]['customer_type_name'] :"";?>" placeholder="" autocomplete="off">
											</div>
										 </div>
									</div>
								</fieldset>
								
								<div class="d-flexad" style="text-align:right;">
									<?php 
										if($type == "edit")
										{
											?>
											<a href="<?php echo base_url(); ?>customer/ManageCustomerType" class="btn btn-outline-dark waves-effect"><?php echo get_phrase('Cancel');?> </a>
											<button type="submit" class="btn btn-primary ml-1"><?php echo get_phrase('Update');?></button>
											<?php 
										}
										else
										{
											?>
											<a href="<?php echo base_url(); ?>customer/ManageCustomerType" class="btn btn-outline-dark waves-effect"><?php echo get_phrase('Cancel');?> </a>
											<!-- <button type="reset" class="btn btn-light ml-3" id="reset"><?php echo get_phrase('Reset');?></button> -->
											<button type="submit" class="btn btn-primary ml-1"><?php echo get_phrase('Submit');?></button>
											<?php 
										}
									?>
								</div>
							</form>
						<?php
					}
					else
					{ 
						?>
						<form action="" method="get">
							<section --class="trans-section-back-1">
								<div class="row">
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-4">	
												<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..."autocomplete="off">
												<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Customer Type</span>
											</div>	
											<div class="col-md-4">
												<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
											</div>
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
							</section>
							<?php 
								$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
							?>
							<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
						</form>
								
						
						<style>
							div#DataTables_Table_0_filter,#DataTables_Table_0_length {
								display: none;
							}
							div#DataTables_Table_0_info {
								display: none;
							}
							div#DataTables_Table_0_paginate {
								display: none;
							}
						</style>
						
						<form action="" method="post">
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover  dataTable">
									<thead>
										<tr>
											<!--<th onclick="sortTable(0)">S.No</th>-->
											<th style="text-align:center;width:12%;">Controls</th>
											<th onclick="sortTable(1)">Customer Type</th>
											
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
													<td style="text-align:center;">
														<div class="dropdown">
															<button type="button" class="btn btn-outline-info gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
																Action   <span class="caret"></span>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<li>
																	<a href="<?php echo base_url(); ?>customer/ManageCustomerType/edit/<?php echo $row['customer_type_id'];?>">
																		<i class="fa fa-edit"></i> Edit
																	</a>
																</li>
																<?php 
																/* <li>
																	<a href="<?php echo base_url();?>patient/ManagePatient/delete/<?php echo $row['user_id'];?>">
																		<i class="fa fa-trash-o"></i> Delete
																	</a>
																</li> */ ?>
																<li>
																	<?php 
																		if($row['customer_type_status'] == 1)
																		{
																			?>
																			<a class="unblock" href="<?php echo base_url(); ?>customer/ManageCustomerType/status/<?php echo $row['customer_type_id'];?>/0" title="Active">
																				<i class="fa fa-ban"></i> Active
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a class="block" href="<?php echo base_url(); ?>customer/ManageCustomerType/status/<?php echo $row['customer_type_id'];?>/1" title="InActive">
																				<i class="fa fa-ban"></i> InActive
																			</a>
																			<?php 
																		} 
																	?>
																<li>
															</ul>
														</div>
													</td>
													<td><?php echo $row['customer_type_name'];?></td>

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
										<p class="admin-no-data">No data found.</p>
										<?php 
									} 
								?>
							</div>
						</form>
						
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
	
	// set particular checked checkbox count
	/* $(".emp_checkbox").on('click', function(e) 
	{
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	}); */
</script>