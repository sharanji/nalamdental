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
							<?php echo ucfirst($type);?> Services
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
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="<?php echo base_url(); ?>products/ManageServices" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{ 
				
			}
			else
			{ 
				?>
				<a href="<?php echo base_url(); ?>products/ManageServices/add" class="btn btn-info">
					<i class="icon-plus-circle2"></i> Add Services
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
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{
				?>
				<div class="card-body">
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> Service</legend>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Service Code <span class="text-danger">*</span></label>
									<input type="text" name="product_code" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['product_code']) ? $edit_data[0]['product_code'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Service Name <span class="text-danger">*</span></label>
									<input type="text" name="product_name" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['service_name']) ? $edit_data[0]['service_name'] :"";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Net Dealer Price <span class="text-danger">*</span></label>
									<input type="text" name="cost" <?php echo $this->validation;?> required class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['cost']) ? $edit_data[0]['cost'] :"";?>" placeholder="">
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Selling Price <span class="text-danger">*</span></label>
									<input type="text" name="price" <?php echo $this->validation;?> required class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['price']) ? $edit_data[0]['price'] :"0";?>" placeholder="">
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">HSN/SAC Code </label>
									<input type="text" name="hsn_sac_code" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['hsn_sac_code']) ? $edit_data[0]['hsn_sac_code'] :"";?>" placeholder="">
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Alert Quantity </label>
									<input type="text" name="alert_quantity" <?php echo $this->validation;?> class="form-control mobile_vali" value="<?php echo isset($edit_data[0]['alert_quantity']) ? $edit_data[0]['alert_quantity'] :'0';?>" placeholder="">
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Category</label>
									<?php $getCategory = $this->db->query("select category_name,category_id from category where category_status=1 and main_category_id =0 order by category_name asc")->result_array(); ?>
									<select name="category_id" onchange="selectSubCategory(this.value);" class="form-control">
										<option value="">- Select Category -</option>
										<?php 
											foreach($getCategory as $category)
											{
												$selected="";
												if(isset($edit_data[0]['category_id']) && ($edit_data[0]['category_id'] == $category['category_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $category['category_id']; ?>" <?php echo $selected;?>><?php echo $category['category_name']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Sub Category </label>
									<?php 
										if( isset($edit_data[0]['category_id']) && !empty($edit_data[0]['category_id']) )
										{
											$getsubCategory = $this->db->query("select category_name,category_id from category where category_status=1 and main_category_id = '".$edit_data[0]['category_id']."' order by category_name asc")->result_array(); 
										}
									?>
									<select name="subcategory_id" id="subcategory_id" class="form-control">
										<option value="">- Select Sub Category -</option>
										<?php
											if( isset($edit_data[0]['category_id']) && !empty($edit_data[0]['category_id']) )
											{										
												foreach($getsubCategory as $category)
												{
													$selected="";
													if(isset($edit_data[0]['subcategory_id']) && ($edit_data[0]['subcategory_id'] == $category['category_id']) )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $category['category_id']; ?>" <?php echo $selected;?>><?php echo $category['category_name']; ?></option>
													<?php 
												} 
											} 
										?>
									</select>
								</div>
								
								<script>
									function selectSubCategory(val)
									{
									   if(val !='')
									   {
											$.ajax({
											  type: "POST",
											  url:"<?php echo base_url().'admin/ajaxSubCategory';?>",
											  data: { id: val }
											}).done(function( msg ) {   
												$( "#subcategory_id").html(msg);
											});
										}
										else 
										{ 
											alert("No sub category under this category!");
										}
									}
								</script>
								<?php $get_tax = $this->db->query("select tax_name, tax_id from tax where tax_status=1")->result_array(); ?>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Product Tax </label>
									<select name="tax_id" class="form-control">
										<option value="">- Select Tax -</option>
										<?php 
											foreach($get_tax as $row)
											{
												$selected="";
												if(isset($edit_data[0]['tax_id']) && ($edit_data[0]['tax_id'] == $row['tax_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['tax_id']; ?>" <?php echo $selected;?>><?php echo $row['tax_name']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">UOM</label>
									<?php $getUOM = $this->db->query("select uom_code,uom_id from uom where uom_status=1")->result_array(); ?>
									<select name="unit" class="form-control">
										<option value="">- Select UOM -</option>
										<?php 
											foreach($getUOM as $row)
											{
												$selected="";
												if(isset($edit_data[0]['unit']) && ($edit_data[0]['unit'] == $row['uom_code']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['uom_code']; ?>" <?php echo $selected;?>><?php echo $row['uom_code']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<?php $getbrand = $this->db->query("select * from brand where brand_status=1")->result_array(); ?>
									
								<div class="form-group col-md-3">
									<label class="col-form-label">Brand </label>
									<select name="brand_id" class="form-control">
										<option value="">- Select Brand -</option>
										<?php 
											foreach($getbrand as $row)
											{
												$selected="";
												if(isset($edit_data[0]['brand_id']) && ($edit_data[0]['brand_id'] == $row['brand_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['brand_id']; ?>" <?php echo $selected;?>><?php echo $row['brand_name']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								
								<div class="form-group col-md-3">
									<label class="col-form-label">Product Image </label>
									<input type="file" name="product_image" class="form-control">
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-6">
									<label class="col-form-label">Detailed Description </label>
									<textarea name="description" class="form-control" placeholder="Please enter detailed description"><?php echo isset($edit_data[0]['description']) ? $edit_data[0]['description'] :"";?></textarea>
								</div>
								<?php 
									if($type == "edit")
									{
										if( !empty($edit_data[0]['service_image']) && file_exists("uploads/products/".$edit_data[0]['service_image']) )
										{
											
											?>
											<div class="form-group col-md-3">
												<label class="col-form-label"></label>
												<img src="<?php echo base_url(); ?>uploads/products/<?php echo $edit_data[0]['service_image'];?>" width="75" height="75">
											</div>
											<?php 
										}
									} 
								?>
							</div>
						</fieldset>
						
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8" style="text-align:right;">
								<?php 
									if($type == "edit")
									{
										?>
										<a href="<?php echo base_url(); ?>products/ManageServices" class="btn btn-danger">Cancel &nbsp;&nbsp;<i class="icon-cancel-circle2"></i></a>
										<button type="submit" class="btn btn-primary ml-3">Update <i class="icon-paperplane ml-2"></i></button>
										<?php 
									}
									else
									{
										?>
										<a href="<?php echo base_url(); ?>products/ManageServices" class="btn btn-danger">Cancel &nbsp;&nbsp;<i class="icon-cancel-circle2"></i></a>
										<?php /* <button type="reset" class="btn btn-light ml-3" id="reset">Reset <i class="icon-reload-alt ml-2"></i></button> */ ?>
										<button type="submit" class="btn btn-primary ml-3">Submit <i class="icon-paperplane ml-2"></i></button>
										<?php 
									}
								?>
							</div>
						</div>
					</form>
				</div>
				<?php
			}
			else
			{ 
				?>
				<form action="" method="get">
					<section class="trans-section-back-1">
						<div class="row col-md-12">
							<div class="col-md-4">	
								<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..."autocomplete="off">
								<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Project Code, Project Name, Scheme</span>
							</div>	
							<div class="col-md-4">
								<button type="submit" class="btn btn-success trans-saction-butt">Search <i class="fa fa-search" aria-hidden="true"></i></button>
							</div>
						</div>
					</section>
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
					<?php /* <a href="javascript::void(0);" onclick="showbtn();" style="margin: 0px 0px 13px 0px;" class="showbtn btn btn-warning">Show Multi Delete<br></a>
					<script>
						function showbtn()
						{
							$('.deleteBtn').toggle();
						}
					</script> */ ?>
					
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
							<thead>
								<tr>
									<?php
									/* <th>
										<input type="checkbox" id="select_all">&nbsp;
										<button style="display:none;" class="deleteBtn" type="submit" name="delete" value="delete" title="Patient Multi Delete"><i class="fa fa-trash" style="font-size:16px;"></i></button>
									</th>
									<th onclick="sortTable(0)" style="width: 10%;">S.No</th>
									 */ ?><th style="width: 10%;">Image</th>
									<th onclick="sortTable(1)" style="width: 10%;">Service Code</th>
									<th onclick="sortTable(2)" style="width: 10%;">HSN/SAC Code</th>
									<th onclick="sortTable(3)" style="width: 20%;">Service Name</th>
									<th onclick="sortTable(4)" style="width: 20%;">Category</th>
									<th onclick="sortTable(5)" style="width: 15%;">Cost</th>
									<th onclick="sortTable(6)" style="width: 15%;">Price</th>
									
									<!--
									<th onclick="sortTable(7)" style="width: 10%;">Quantity <i class="fa fa-fw fa-sort"></i></th>
									<th onclick="sortTable(8)" style="width: 10%;">UOM <i class="fa fa-fw fa-sort"></i></th>
									<th onclick="sortTable(9)" style="width: 10%;">Alert Quantity<i class="fa fa-fw fa-sort"></i></th>
									-->
									<th>
										Controls
									</th>
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
											<?php 
											/* <td>
												<input type="checkbox" name="checkbox[]" class="emp_checkbox" value="<?php echo $row['user_id']; ?>">
											</td>
											<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
											 */ ?>
											

											<td style="text-align:center;">
												<?php
													if(!empty($row['service_image']) && file_exists("uploads/products/".$row['service_image']) )
													{
														?>
														<img src="<?php echo base_url(); ?>uploads/products/<?php echo $row['service_image'];?>" width="75" height="75">
														<?php 
													}
													else
													{
														?>
														<img src="<?php echo base_url(); ?>uploads/no-image.png" width="75" height="75">
														<?php 
													} 
												?>
											</td>
											<td style="text-align:center;"><?php echo $row['product_code'];?></td>
											<td style="text-align:center;"><?php echo $row['hsn_sac_code'];?></td>
											<td style="width:5% !important;"><?php echo ucfirst($row['service_name']);?></td>
											<td style="width:5% !important;"><?php echo ucfirst($row['category_name']);?></td>
											<td style="text-align:center;"><span class="fa fa-rupee"></span> <?php echo number_format($row['cost'],DECIMAL_VALUE,'.','');?></td>
											<td style="text-align:center;"><span class="fa fa-rupee"></span> <?php echo number_format($row['price'],DECIMAL_VALUE,'.','');?></td>
											<?php
											/* <td style="text-align:center;"><?php echo $row['quantity'];?></td>
											<td style="text-align:center;"><?php echo $row['unit'];?></td>
											<td style="text-align:center;"><?php echo $row['alert_quantity'];?></td> */ ?>
											<td>
												<div class="dropdown">
													<button type="button" class="btn btn-default gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														Action   <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a href="<?php echo base_url(); ?>products/ManageServices/edit/<?php echo $row['service_id'];?>">
																<i class="fa fa-edit"></i> Edit
															</a>
														</li>
														<?php /* <li>
															<a href="<?php echo base_url();?>products/ManageServices/delete/<?php echo $row['service_id'];?>">
																<i class="fa fa-trash-o"></i> Delete
															</a>
														</li> */ ?>
														<li>
															<?php 
																if($row['service_status'] == 1)
																{
																	?>
																	<a class="unblock" href="<?php echo base_url(); ?>products/ManageServices/status/<?php echo $row['service_id'];?>/0" title="Block">
																		<i class="fa fa-ban"></i> Block
																	</a>
																	<?php 
																} 
																else
																{  ?>
																	<a class="block" href="<?php echo base_url(); ?>products/ManageServices/status/<?php echo $row['service_id'];?>/1" title="Unblock">
																		<i class="fa fa-ban"></i> Unblock
																	</a>
																	<?php 
																} 
															?>
														<li>
													</ul>
												</div>
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
