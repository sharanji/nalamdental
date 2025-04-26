
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<!-- Buttons start here -->
			<div class="row">
				<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
				<div class="col-md-6 float-right text-right">
					
				</div>
			</div>
			<!-- Buttons end here -->
			
			<!-- Filters start here -->
			<form action="" class="form-validate-jquery summary_form" method="get">
				<div class="row mt-3">

					<div class="col-md-4">
						<div class="row">
							<label class="col-form-label col-md-4"><span class="text-danger">*</span> Organization</label>
							<div class="form-group col-md-8">
								<?php
									$organizationQry = "select organization_id,organization_code,organization_name from org_organizations
									where active_flag='Y' order by organization_name asc
									";
									$getOrganization = $this->db->query($organizationQry)->result_array();
								?>
								
								<select id="organization_id" name="organization_id" class="form-control searchDropdown">
									<option value="">- Select -</option>
									<?php 
										foreach($getOrganization as $row)
										{
											$selected="";
											if(isset($_GET["organization_id"]) && $_GET["organization_id"] == $row['organization_id'] )
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row['organization_id'];?>" <?php echo $selected;?>><?php echo $row['organization_code'];?> - <?php echo $row['organization_name'];?></option>
											<?php 
										} 
									?>
								</select>
							</div>
						</div>
						<span class="search_required"></span>
					</div>

					<div class="col-md-3">
						<div class="row">
							<label class="col-form-label col-md-3"><span class="text-danger">*</span>Item</label>
							<div class="form-group col-md-8">
								<?php
									$query1 = "select item_id,item_name from inv_sys_items
									where active_flag='Y' AND item_type_id = '31'
									order by item_name asc
									";
									$getItems = $this->db->query($query1)->result_array();
								?>
								
								<select id="item_id" name="item_id" class="form-control searchDropdown">
									<option value="">- Select -</option>
									<?php 
										foreach($getItems as $items)
										{
											$selected="";
											if(isset($_GET["item_id"]) && $_GET["item_id"] == $items['item_id'] )
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $items['item_id'];?>" <?php echo $selected;?>><?php echo ucfirst($items['item_name']);?></option>
											<?php 
										} 
									?>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<button type="submit" name="filter_search" class="btn btn-info btn-sm">
							<i class="fa fa-search"></i> Search
						</button>
						<a href="<?php echo base_url(); ?>report/minimumStock" title="Clear" class="btn btn-default btn-sm">Clear</a>
					</div>
				</div>
			</form>
			
			<style>
				span.search_required {color: red;}
			</style>

			<script type="text/javascript">
				$("form.summary_form").submit(function (e) 
				{
					var organization_id = $("#organization_id").val();
					var item_id = $("#item_id").val();

					if(organization_id || item_id) //Expiry
					{		
						$(".search_required").html("");
						return true;
					}
					else 
					{
						$(".search_required").html("Atleast one search filter is required!");
						return false;
					}
				});
			</script>

			<?php 
				if(isset($_GET) &&  !empty($_GET))
				{
					?>
						<?php 
							if(count($resultData) > 0)
							{
								?>
									<!-- Page Item Show start -->
									<div class="row">
										<div class="col-md-8 mt-3">
											
											<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" title="Download Excel" class="btn btn-sm btn-primary">
												Download to Excel
											</a>
													
										</div>
										<?php /*
											?>
												<div class="col-md-4 float-right text-right">
													<?php 
														$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
													?>
													<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
																			
													<div class="filter_page">
														<label>
															<span>Show :</span> 
															<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
																<?php 
																	$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : NULL;
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
											<?php
										*/?>
									</div>
									<!-- Page Item Show start -->

								<?php
							} 
						?>

						<!-- Table start here -->
						<style>
							.minimumQtyClass{background:#e32227;color:#fff;}
							.minimumQtyClass:hover{background:#e32227 !important;color:#fff !important;}
						</style>
						<?php /*
						
							?>
								<div class="new-scroller">
									<table id="myTable" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Organization Name</th>
												<th>Item Name</th>
												<th>Sub Inventory</th>
												<th>Locator Number</th>
												<th>Lot Number</th>
												<th>Serial Number</th>
												<th>Min.Qty</th>
												<th>Available Qty</th>
											</tr>
										</thead>
										<tbody>
											<?php 	
												$i=0;
												$firstItem = $first_item;
												$totalValue = 0;
												foreach($resultData as $row)
												{
													$minimum_qty = $row['minimum_qty'];
													$trans_qty = $row['trans_qty'];
													if($minimum_qty > $trans_qty){
														$minimumQtyClass = "minimumQtyClass";
													}else{
														$minimumQtyClass = "";
													}
													?>
													<tr class="<?php echo $minimumQtyClass;?>">
														<td><?php echo $row['organization_name'];?></td>
														<td><?php echo $row['item_name'];?></td>
														<td><?php echo $row['inventory_code'];?></td>
														<td><?php echo $row['locator_no'];?></td>
														<td><?php echo $row['lot_number'];?></td>
														<td><?php echo $row['serial_number'];?></td>
														<td><?php echo $minimum_qty;?></td>
														<td><?php echo $row['trans_qty'];?></td>
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
								<!-- Table end here -->

								<!-- Pagination start here -->
								<?php 
									if (count($resultData) > 0) 
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
						<!-- Pagination end here -->
							<?php
						
						*/?>

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
						
					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
