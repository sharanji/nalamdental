<!-- Page header start-->
<div class="page-header page-header-light">
	
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>employee/ManagePositions" class="breadcrumb-item"><?php echo $page_title;?></a>
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
					<a href="<?php echo base_url(); ?>employee/ManagePositions/add" class="btn btn-info">
						Add Position
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
						<!-- <label class="text-capitalize" style="font-size:18px;color: #13111188;font-weight: 600;"><?php echo $type ?> Employee :</label> -->
						<legend class="text-uppercase font-size-sm font-weight-bold">
							<?php echo $type; ?> Position :
						</legend>
							<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
								<div class="row">
									<div class="col-sm-12 col-md-12">
										<fieldset class="mb-3">
											<div class="row">
												<div class="form-group col-md-3">
													<label class="col-form-label">Position Name <span class="text-danger">*</span></label>
													<input type="text" name="position_name" required <?php //echo $this->validation; ?> class="form-control" value="<?php echo isset($edit_data[0]['position_name']) ? $edit_data[0]['position_name'] :"";?>" placeholder="">
												</div>
											</div>
											
										</fieldset>
									</div>
								</div>
								
								<div class="d-flexad" style="text-align:right;">
									<a href="<?php echo base_url(); ?>employee/ManagePositions" class="btn btn-light">Cancel  </a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" class="btn btn-primary ml-3">Update</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" class="btn btn-primary ml-2 register-but">Submit </button>
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
						<div class="col-md-6"><?php echo $page_title;?></div>

						<div class="col-md-6 float-right text-right">
							<a href='<?php echo base_url();?>admin/settings' class='btn btn-outline-info btn-sm btn-info'>
								<i class="icon-arrow-left16"></i> Back
							</a>
							<a href="<?php echo base_url(); ?>employee/ManagePositions/add" class="btn btn-info btn-sm">
								Add Position
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row mt-3 mb-3">
							
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-5">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note :  Position Name</p>
									</div>	
									
									<div class="col-md-3">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
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
										<th onclick="sortTable(1);">Position Name</th>
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
																<a title="Edit" href="<?php echo base_url(); ?>employee/ManagePositions/edit/<?php echo $row['position_id'];?>">
																	<i class="fa fa-pencil"></i> Edit
																</a>
															</li>
															
															<li>
																<?php 
																	if($row['position_status'] == 1)
																	{
																		?>
																		<a href="<?php echo base_url(); ?>employee/ManagePositions/status/<?php echo $row['position_id'];?>/0" title="Block">
																			<i class="fa fa-ban"></i> Inactive
																		</a>
																		<?php 
																	} 
																	else
																	{  ?>
																		<a href="<?php echo base_url(); ?>employee/ManagePositions/status/<?php echo $row['position_id'];?>/1" title="Unblock">
																			<i class="fa fa-check"></i> Active
																		</a>
																		<?php 
																	} 
																?>
															</li>
														</ul>
													</div>
												</td>
												
												<td style="width:10%;"><?php echo ucfirst($row['position_name']);?></td>
												
												<td style="text-align:center;width:10%;">
													<?php 
														if($row['position_status'] == 1)
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
		<?php if(isset($type) && $type =='view'){?>
			<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
		<?php } ?>
	</div><!-- Content end-->
	
