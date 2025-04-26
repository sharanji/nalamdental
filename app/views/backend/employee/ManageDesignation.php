<!-- Page header start-->
<div class="page-header page-header-light">
	
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManageDesignation" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		<?php
			if( isset($type) && $type == "add" || $type == "edit" )
			{ 
				
			}
			else
			{ 
				?>
				<div class="d-flexad" style="text-align:right;">
					<a href="<?php echo base_url();?>admin/settings" class='btn btn-light'>Back</a>
					<a href="<?php echo base_url(); ?>employee/ManageDesignation/add" class="btn btn-info">
						Add Designation
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
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<legend class="text-uppercase font-size-sm font-weight-bold">
						<?php echo $type; ?> Designation :
					</legend>
					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<fieldset class="mb-3">
									<div class="row">
										<div class="form-group col-md-3">
											<label class="col-form-label">Designation Name <span class="text-danger">*</span></label>
											<input type="text" name="designation_name" required <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['designation_name']) ? $edit_data[0]['designation_name'] :"";?>" placeholder="">
										</div>
									
										<div class="form-group col-md-3">
											<label class="col-form-label">Designation Description</label>
											<textarea name="designation_description" class="form-control"><?php echo isset($edit_data[0]['designation_description']) ? $edit_data[0]['designation_description'] :"";?></textarea>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
						
						<div class="d-flexad" style="text-align:right;">
							<a href="<?php echo base_url(); ?>employee/ManageDesignation" class="btn btn-light">Cancel</a>
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
									<button type="submit" class="btn btn-primary ml-1 register-but">Submit</button>
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
					<div class="row mb-2">
						<div class="col-md-6"><h3><?php echo $page_title;?></h3></div>
						<div class="col-md-6 float-right text-right">
							<a href='<?php echo base_url();?>setup/settings' class='btn btn-info'>
								<i class="icon-arrow-left16"></i> Back
							</a>
							<?php /*
								<a href='<?php echo base_url();?>setup/settings' class='btn btn-outline-info btn-sm btn-info'>
									<i class="icon-arrow-left16"></i> Back
								</a>
							*/ ?>
							<a href="<?php echo base_url(); ?>employee/ManageDesignation/add" class="btn btn-info btn-sm">
								Add Designation
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row mt-3 mb-3">
							
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-5">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Designation Name</p>
									</div>	
									
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
										<a href="<?php echo base_url(); ?>employee/ManageDesignation" title="Clear" class="btn btn-default">Clear</a>
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
					</form>
					
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
							<thead>
								<tr>
									<th style="width:5%;" class="text-center">Controls</th>
									<th onclick="sortTable(1);">Designation Name</th>
									<th onclick="sortTable(2);" style="text-align:center;width:10%;">Status</th>
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
											<td style="width:5% !important;" class="text-center">
												<div class="dropdown" style="display: inline-block;padding-right: 10px!important;">
													<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a title="Edit" href="<?php echo base_url(); ?>employee/ManageDesignation/edit/<?php echo $row['designation_id'];?>">
																<i class="fa fa-pencil"></i> Edit
															</a>
														</li>
														
														<li>
															<?php 
																if($row['designation_status'] == 1)
																{
																	?>
																	<a href="<?php echo base_url(); ?>employee/ManageDesignation/status/<?php echo $row['designation_id'];?>/0" title="Block">
																		<i class="fa fa-ban"></i> Inactive
																	</a>
																	<?php 
																} 
																else
																{  ?>
																	<a href="<?php echo base_url(); ?>employee/ManageDesignation/status/<?php echo $row['designation_id'];?>/1" title="Unblock">
																		<i class="fa fa-check"></i> Active
																	</a>
																	<?php 
																} 
															?>
														</li>
													</ul>
												</div>
											</td>
											
											<td style="width:10%;"><?php echo ucfirst($row['designation_name']);?></td>
											
											<td style="text-align:center;width:10%;">
												<?php 
													if($row['designation_status'] == 1)
													{
														?>
														<span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Active</span>
														<?php 
													} 
													else
													{  
														?>
														<span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
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
								<p class="admin-no-data">No data found.</p>
								<?php 
							} 
						?>
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
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
	
