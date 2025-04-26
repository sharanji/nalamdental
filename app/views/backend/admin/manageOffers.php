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
							<?php echo ucfirst($type);?> Discount
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
				<a href="<?php echo base_url(); ?>admin/manageOffers" class="breadcrumb-item">
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
				<a href="<?php echo base_url(); ?>admin/manageOffers/add" class="btn btn-info">
					<i class="icon-plus-circle2"></i> Add Offer
				</a>
				<?php 
			} 
		?>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-header header-elements-inline">
			<h5 class="card-title"></h5>
		</div>
		<div class="card-body">
		<?php
			if( isset($type) && $type == "add" || $type == "edit")
			{
				?>
				<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
					<fieldset class="mb-3">
						<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> Offers</legend>
						<div class="row">
							<div class="form-group col-md-5">
								<label class="col-form-label">Offer Amount<span class="text-danger">*</span></label>
								<input type="text" name="offer_amount" <?php echo $this->validation;?> id="offer_amount" required class="form-control" value="<?php echo isset($edit_data[0]['offer_amount']) ? $edit_data[0]['offer_amount'] :"";?>" placeholder="">
							</div>
							
							<div class="form-group col-md-5">
								<label class="col-form-label">Offer Percentage (%)</label>
								<input type="text" name="offer_percentage" <?php echo $this->validation;?> id="offer_percentage" class="form-control" value="<?php echo isset($edit_data[0]['offer_percentage']) ? $edit_data[0]['offer_percentage'] :"";?>" placeholder="">
							</div>
						</div>
					</fieldset>
					
					<div class="d-flexad" style="align:center;">
						<a href="<?php echo base_url(); ?>admin/ManageOffers" class="btn btn-default"><?php echo get_phrase('Cancel');?>   </a>
								
						<?php 
							if($type == "edit")
							{
								?>
								<button type="submit" class="btn btn-primary ml-3"><?php echo get_phrase('Update');?>   </button>
								<?php 
							}
							else
							{
								?>
								<button type="submit" class="btn btn-primary ml-3"><?php echo get_phrase('Submit');?>   </button>
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
					
					<div class="row">
						<div class="col-md-8">
							<section class="trans-section-back-1">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
									</div>	
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
								</div>
							</section>
						</div>
						<div class="col-md-4">
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
						<table id="myTable" class="table table-bordered table-hover  --table-striped dataTable">
							<thead>
								<tr>
									<?php /* <th onclick="sortTable(0)">S.No <i class="fa fa-fw fa-sort"></i></th> */?>
									<th class="text-center">Controls</th>
									<th onclick="sortTable(1)">Offer Value</th>
									<th class="text-center" onclick="sortTable(2)">Offer Percentage (%)</th>
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
											<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
											-->
											<td style="width: 12%;" class="text-center">
												<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
													<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="<?php echo base_url(); ?>admin/ManageOffers/edit/<?php echo $row['offer_id'];?>">
																<i class="fa fa-edit"></i> Edit
															</a>
														</li>
														<li>											
															<?php 
																if($row['offer_status'] == 1)
																{
																	?>
																	<a class="unblock" href="<?php echo base_url(); ?>admin/ManageOffers/status/<?php echo $row['offer_id'];?>/0" title="Block">
																		<i class="fa fa-ban"></i> Inactive
																	</a>
																	<?php 
																} 
																else
																{  ?>
																	<a class="block" href="<?php echo base_url(); ?>admin/ManageOffers/status/<?php echo $row['offer_id'];?>/1" title="Unblock">
																		<i class="fa fa-ban"></i> Active
																	</a>
																	<?php 
																} 
															?>
														<li>
													</ul>
												</div>
												
											</td>
											<td><?php echo $row['offer_amount'];?></td>
											<td class="text-center"><?php echo $row['offer_percentage'];?> %</td>
											<td style="text-align:center;">
													<?php 
														if($row['offer_status'] == 1)
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