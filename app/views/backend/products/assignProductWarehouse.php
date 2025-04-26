
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-md-6"><?php echo $page_title;?></div>
				<div class="col-md-6 float-right text-right">
					<a href="<?php echo base_url(); ?>products/ManageProducts" class="btn btn-info btn-sm">
						<i class="icon-arrow-left16"></i> Back
					</a>
				</div>
			</div>


			<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
				<div class="row">
					<div class="form-group col-md-3">
						<label class="col-form-label">Warehouse <span class="text-danger">*</span></label>
						<?php 
							$warehouseQry = "select warehouse_id,warehouse_name,warehouse_code from warehouse where warehouse_status=1";
							$getWarehouse = $this->db->query($warehouseQry)->result_array();
						?>
						<select name="warehouse_id" id="warehouse_id" required class="form-control searchDropdown">
							<option value="">- Select Warehouse -</option>
							<?php foreach($getWarehouse as $warehouse){?>
								<option value="<?php echo $warehouse["warehouse_id"];?>"><?php echo $warehouse["warehouse_code"];?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-3" style="margin:36px 0px 0px 0px;">
						<button type="submit" name="add" class="btn btn-primary ml-1"><?php echo get_phrase('Submit');?>   </button>
					</div>
				</div>
			</form>
			<hr>
			<form action="" method="get">
				<section class="trans-section-back-1">
					<div class="row">
						<div class="col-md-8">
							<div class="row mt-1">
								<div class="col-md-4">	
									<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..." autocomplete="off">
									<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Warehouse Code, Warehouse Name</span>
								</div>	
								<div class="col-md-3">
									<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
								<a class="button" href="#">
								</a>
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
				</section>
			</form>
			
			<?php 
				$prodctQry = "select product_name,product_code from products where product_id='".$id."' ";
				$getProdct = $this->db->query($prodctQry)->result_array();
			?>
			<div class="row mb-3">
				<div class="col-md-2">Product Name</div>
				<div class="col-md-1">:</div>
				<div class="col-md-3"><?php echo ucfirst($getProdct[0]["product_name"]);?></div>
			</div>

			<form action="" method="post">
				<div class="new-scroller">
					<table --id="myTable" class="table table-bordered -sortable-table table-hover --table-striped --dataTable">
						<thead>
							<tr>
								<th class="text-center">Controls</th>
								<th class="text-center">Warehouse Code</th>
								<th>Warehouse Name</th>
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
										<td class="text-center">
											<div class="dropdown text-center" --style="width:80px;">
												<button type="button" class="btn btn-outline-info gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
													Action &nbsp;<i class="fa fa-chevron-down"></i>
												</button>
												<ul class="dropdown-menu dropdown-menu-right">
													<?php /*<li>
														<a href="<?php echo base_url(); ?>products/ManageProducts/edit/<?php echo $row['product_id'];?>">
															<i class="fa fa-edit"></i> Edit
														</a>
													</li>
													*/ ?>
													<li>
														<?php 
															if($row['assign_status'] == 1)
															{
																?>
																<a class="unblock" href="<?php echo base_url(); ?>products/assignActiveInactiveStatus/<?php echo $id;?>/<?php echo $row['assign_id'];?>/0" title="Active">
																	<i class="fa fa-ban"></i> Inactive
																</a>
																<?php 
															} 
															else
															{  ?>
																<a class="block" href="<?php echo base_url(); ?>products/assignActiveInactiveStatus/<?php echo $id;?>/<?php echo $row['assign_id'];?>/1" title="InActive">
																	<i class="fa fa-ban"></i> Active
																</a>
																<?php 
															} 
														?>
													<li>
												</ul>
											</div>
										</td>
									
										<td class="tab-mobile-width text-center"><?php echo $row['warehouse_code'];?></td>
										<td class="tab-mobile-width"><?php echo ucfirst($row['warehouse_name']);?></td>
										
										<td class="tab-mobile-width" style="text-align:center;">
											<?php 
												if($row['assign_status'] == 1)
												{
													?>
													<span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Active </span>
													<?php 
												} 
												else
												{  ?>
													<span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-check"></i> Inactive </span>
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
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->

