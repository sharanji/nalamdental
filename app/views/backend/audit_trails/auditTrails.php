
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
	
			<!-- buttons start here -->
			<div class="row mb-2">
				<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
				<div class="col-md-6 float-right text-right">	
					<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
						<i class="icon-arrow-left16"></i> 
						Back
					</a>
				</div>
			</div>
			<!-- buttons end here -->
			
			<!-- Filters start here -->
			<form action="" class="form-validate-jquery summary_form" method="get">
				<div class="row mt-3">
					<div class="col-md-3">
						<div class="row">
							<label class="col-form-label col-md-4 text-right">From Date</label>
							<div class="form-group col-md-8">
								<input type="text" name="from_date" placeholder="From Date" readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="row">
							<label class="col-form-label col-md-4 text-right">To Date</label>
							<div class="form-group col-md-8">
								<input type="text" name="to_date" readonly placeholder="To Date" id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<button type="submit" name="filter_search" class="btn btn-info">
							<i class="fa fa-search"></i> Search
						</button>
						<a href="<?php echo base_url(); ?>audit_trails/auditTrails" title="Clear" class="btn btn-default">Clear</a>
					</div>
				</div>
			</form>
			
			<?php 
				if(isset($_GET) &&  !empty($_GET))
				{
					?>
					<!-- Page Item Show start -->
					<div class="row">
						<div class="col-md-8 mt-3">
							<?php 
								if(count($resultData) > 0)
								{
									?>
									<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" target="_blank" title="Download Excel" class="btn btn-sm btn-primary">
										Download to Excel
									</a>
									<?php
								} 
							?>
						</div>

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
					</div>
					<!-- Page Item Show start -->

					<!-- Table start here -->
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="tab-md-100">Branch Name</th>
									<th class="tab-md-100">User Name</th>
									<!-- <th class="tab-md-120">Mobile Number</th> -->
									<th class="tab-md-150">Date</th>
									<th class="tab-md-100">Source IP</th>
									<!-- <th class="tab-md-150">Page Name</th> -->
									<th class="tab-md-120">Table Name</th>
									<th class="tab-md-120">Menu Name</th>
									<th class="tab-md-120">Field Name</th>
									<th class="tab-md-120">Old Value</th>
									<th class="tab-md-120">New Value</th>
									<th class="tab-md-200">Description</th>
									<th class="tab-md-100">Action Type</th>
								</tr>
							</thead>
							<tbody>
								<?php 	
									foreach($resultData as $row)
									{
										?>
										<tr>
											<td><?php echo $row['branch_name'];?></td>
											<td><?php echo $row['employee_name'];?></td>
											<?php /* <td><?php echo $row['mobile_number'];?></td> */ ?>
											<td><?php echo date("d-M-Y",strtotime($row['created_date']));?></td>

											<td>
												<?php echo $row['source_ip'];?>
											</td>

											<?php /* <td>
												<?php echo ucfirst($row['page_name']);?>
											</td> */ ?>

											<td>
												<?php echo $row['table_name'];?>
											</td>

											<td>
												<?php echo $row['menu_name'];?>
											</td>

											<td>
												<?php echo $row['field_name'];?>
											</td>

											<td>
												<?php echo $row['old_value'];?>
											</td>

											<td>
												<?php echo $row['new_value'];?>
											</td>

											<td>
												<?php echo ucfirst($row['description']);?>
											</td>

											<td>
												<?php 
													if ($row['action_type'] == "add") 
													{
														?>
														<span class="text-success"><?php echo ucfirst($row['action_type']); ?></span>
														<?php
													}
													else if ($row['action_type'] == "edit") 
													{
														?>
														<span class="text-blue"><?php echo ucfirst($row['action_type']); ?></span>
														<?php
													}
													else if ($row['action_type'] == "status") 
													{
														?>
														<span class="text-warning"><?php echo ucfirst($row['action_type']); ?></span>
														<?php
													} 
												?>
											</td>
										</tr>
										<?php 
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
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
