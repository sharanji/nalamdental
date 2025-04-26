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
							<?php echo ucfirst($type);?> Uom
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
				<a href="<?php echo base_url(); ?>uom/ManageUom" class="breadcrumb-item">
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
					<a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
						<i class="icon-arrow-left16"></i> Back
					</a>
					
					<a href="<?php echo base_url(); ?>uom/ManageUom/add" class="btn btn-info btn-sm">
						<i class="icon-plus-circle2"></i> Add UOM
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
				if( isset($type) && $type == "add" || $type == "edit")
				{
					?>
						<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
							<fieldset class="mb-3">
								<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> Uom</legend>
								<div class="row">
									<div class="form-group col-md-5">
										<label class="col-form-label">UOM <span class="text-danger">*</span></label>
										<input type="text" name="uom_code" <?php echo $this->validation;?> id="uom_code" required class="form-control" value="<?php echo isset($edit_data[0]['uom_code']) ? $edit_data[0]['uom_code'] :"";?>" placeholder="">
									</div>
									
									<div class="form-group col-md-5">
										<label class="col-form-label">UOM Description</label>
										<input type="text" name="uom_description" <?php echo $this->validation;?> id="uom_description" class="form-control" value="<?php echo isset($edit_data[0]['uom_description']) ? $edit_data[0]['uom_description'] :"";?>" placeholder="">
									</div>
								</div>
							</fieldset>
							
							<div class="d-flexad" style="align:center;">
								<a href="<?php echo base_url(); ?>uom/ManageUom" class="btn btn-outline-dark waves-effect"><?php echo get_phrase('Cancel');?>   </a>
								<?php 
									if($type == "edit")
									{
										?>
										<button type="submit" class="btn btn-primary ml-1"><?php echo get_phrase('Update');?>   </button>
										<?php 
									}
									else
									{
										?>
										<button type="submit" class="btn btn-primary ml-1"><?php echo get_phrase('Submit');?>   </button>
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
							<!-- <a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a> -->
							<a href="<?php echo base_url(); ?>admin/ManageBackupDatabases/export" class="btn btn-info btn-sm">
								Export Database
							</a>
						</div>
					</div>

					<form action="" method="get">
						<?php 
							$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
						?>
						<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
												
						<div class="row">
							<div class="col-md-8">
								<!-- <section class="trans-section-back-1">
									<div class="row">
										<div class="col-md-4">	
											<input type="search" class="form-control" autocomplete="off" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
											<span class="small-1 text-muted">Note : UOM</span>
										</div>	
										<div class="col-md-3">
											<button type="submit" class="btn btn-info">
												Search <i class="fa fa-search"></i>
											</button>
										</div>
									</div>
								</section> -->
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
					</form>
					
					<form action="" method="post">
						<div class="new-scroller">
							<table id="myTable" class="table table-bordered table-hover  --table-striped dataTable">
								<thead>
									<tr>
										<th class="text-center" onclick="sortTable(1)">Action</th>
										<th class="text-center" onclick="sortTable(2)">Backup Date</th>
										<th class="text-center" onclick="sortTable(3)">Download Database</th>
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
											<td style="width: 12%;" class="text-center">
												<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
													<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a  title="Delete" href="<?php echo base_url();?>admin/ManageBackupDatabases/delete/<?php echo $row['backup_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																<i class="fa fa-trash"></i> Delete
															</a>
														</li>
													</ul>
												</div>
											</td>

											<td class="text-center"><?php echo date("d M Y h:i:s a",$row['backup_date']);?></td>
											
											<td class="text-center">
												<?php 
													if( file_exists($row['database_name']) && !empty($row['database_name']) )
													{ 	
														?>
														<a href="<?php echo base_url(); ?><?php echo $row['database_name'];?>" class="btn btn-info btn-sm" title="Download Database">
															<i class="fa fa-download"></i>
														</a>
														<?php 
													}
													else
													{ 
														?>
														<span style="color:red;">No Database</span>
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


