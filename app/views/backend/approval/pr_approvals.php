<?php 
	$purchase_order = accessMenu(purchase_order);
?>

<!-- Page header start-->
<div class="page-header page-header-light">
	
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
				<a href="<?php echo base_url();?>approval/pr_approvals" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
		</div>
	</div>
</div>

<div class="content"><!-- Content start-->
	<div class="card">
		<div class="card-body">
			<?php 
				$page_data = array();
				echo $this->load->view("backend/approval/tabs.php", $page_data, true);
			?>
			
			<form action="" method="get">
				<section class="trans-section-back-1">							
					<div class="row mt-1">
						<div class="col-md-3">	
							<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..." autocomplete="off">
							<p style="font-size:12px;color:#888888;"><span class="text-muted">Note : Purchase ID, Supplier Name, Warehouse Name</span>
						</div>	

						<div class="col-md-3">	
							<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" placeholder="From Date">
							<p style="font-size:12px;color:#888888;"><b></b></p>
						</div>
						
						<div class="col-md-3">	
							<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
						</div>
					</div>
					
					<div class="row mt-1">
						<div class="col-md-3">
							<?php
								$query1 = "select first_name,user_id from users
								where user_status=1 and register_type= 4 order by first_name asc
								";
								$getSupplier = $this->db->query($query1)->result_array();
							?>
							
							<select id="supplier_id" name="supplier_id" class="form-control searchDropdown">
								<option value="">- Select Supplier -</option>
								<?php 
									foreach($getSupplier as $Supplier)
									{
										$selected="";
										if(isset($_GET["supplier_id"]) && $_GET["supplier_id"] == $Supplier['user_id'] )
										{
											$selected="selected='selected'";
										}
										?>
										<option value="<?php echo $Supplier['user_id'];?>" <?php echo $selected;?>><?php echo $Supplier['first_name'];?></option>
										<?php 
									} 
								?>
							</select>
						</div>

						<div class="col-md-3">
							<?php
								$query2 = "select warehouse_id,warehouse_name from warehouse
								where warehouse_status =1 order by warehouse_name asc ";
								$warehouse = $this->db->query($query2)->result();
							?>									
							
							<select id="warehouse_id" name="warehouse_id" class="form-control searchDropdown">
								<option value="">- Select Warehouse -</option>
								<?php 
									foreach($warehouse as $key)
									{
										$selected="";
										if(isset($_GET["warehouse_id"]) && $_GET["warehouse_id"] == $key->warehouse_id )
										{
											$selected="selected='selected'";
										}
										?>
										<option value="<?php echo $key->warehouse_id; ?>" <?php echo $selected;?>><?php echo ucfirst($key->warehouse_name); ?></option>
										<?php 
									} 
								?>
							</select>
						</div>
						
						<div class="col-md-3">
							<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
						</div>

						<div class="col-md-3">
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


			<form name="form1" method="post" action="">
				<div class="new-scroller">
					<table id="myTable" class="table table-bordered -sortable-table table-hover --table-striped dataTable">
						<thead>
							<tr>
								<th --style="text-align:center;width:12%;" class="text-center">Controls</th>
								<th onclick="sortTable(3)" class="text-center">Purchase ID</th>
								<th onclick="sortTable(3)" class="text-center">Approval Status</th>
								<th onclick="sortTable(2)" class="text-center">Purchase Date</th>
								
								<th onclick="sortTable(4)">Supplier Name</th>
								<th onclick="sortTable(4)">Warehouse Name</th>
								<th onclick="sortTable(5)"class="text-right">Grand Total (<?php echo CURRENCY_SYMBOL;?>)</th>
							</tr>
						</thead>
						
						<tbody>
							<?php 	
								$i=0;
								$firstItem = $first_item;
								$totalValue = 0;
								foreach($resultData as $row)
								{
									?>
									<tr>
										<td  class='text-center' style="width:90px;">
											<div class="dropdown" style="width:90px;">
												<button type="button" class="btn btn-outline-info gropdown-toggle" data-toggle="dropdown" aria-expanded="false">
													Action&nbsp;<i class="fa fa-chevron-down"></i>
												</button>
												<ul class="dropdown-menu dropdown-menu-right">
													<?php
														if($purchase_order['read_only'] == 1 || $this->user_id == 1)
														{
															?>
															<li>
																<a href="<?php echo base_url(); ?>purchase/detailedViewApprovals/<?php echo $row['purchase_id'];?>/<?php echo $row['warehouse_id'];?>">
																	<i class="fa fa-eye"></i> View
																</a>
															</li>
															<?php 
														} 
													?>
												</ul>
											</div>
										</td>
										<td style="text-align:center;"><?php echo $row['reference_no'];?></td>
										<td class="text-center">
											<?php
												foreach($this->po_status as $key => $value)
												{
													if($row['po_status'] == $key)
													{
														if($row['po_status'] == 4 || $row['po_status'] == 5 || $row['po_status'] == 7)
														{
															?>
															<span class="text-warning"><?php echo $value;?></span>
															<?php
														}
														else if($row['po_status'] == 2)
														{
															?>
															<span class="text-primary"><?php echo $value;?></span>
															<?php
														}
														else if($row['po_status'] == 3)
														{
															?>
															<span class="text-success"><?php echo $value;?></span>
															<?php
														}
														else
														{
															?>
															<?php echo $value;?>
															<?php
														}
													}
												}
											?> 
										</td>
										<td style="text-align:center;">
											<?php echo date("d-M-Y",strtotime($row['date']));?>
										</td>
										<td><?php echo ucfirst($row['supplier_name']);?></td>												
										<td><?php echo ucfirst($row['warehouse_name']);?></td>												
										<td style="text-align:right;"></span> <?php echo number_format($row['total'],DECIMAL_VALUE,'.','');?></td>
									</tr>

									<?php 
									$i++;
									$totalValue += $row['total'];
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

		</div>
	</div>
</div>