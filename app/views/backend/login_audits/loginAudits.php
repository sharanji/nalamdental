
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
							<label class="col-form-label col-md-4 text-right">Login Type</label>
							<div class="form-group col-md-8">
								<?php
									$login_type = $this->common_model->lov('LOGIN-TYPE');
								?>
								
								<select id="login_type" name="login_type" class="form-control searchDropdown">
									<option value="">- Select -</option>
									<?php 
										foreach($login_type as $row)
										{
											$selected="";
											if(isset($_GET['login_type']) && $_GET['login_type'] == $row["list_code"] )
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $row["list_code"];?>" <?php echo $selected;?>><?php echo ucfirst($row["list_value"]);?></option>
											<?php 
										} 
									?>
								</select>
							</div>
						</div>
					</div>

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
						<a href="<?php echo base_url(); ?>login_audits/loginAudits" title="Clear" class="btn btn-default">Clear</a>
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
									<th class="tab-md-100">Login Type</th>
									<th class="tab-md-150">Branch Name</th>
									<th class="tab-md-100">User Name</th>
									<th class="tab-md-150">Mobile Number</th>
									<th class="tab-md-100">IP Address</th>
									<!-- <th class="tab-md-170">Login Date</th> -->
									<th class="tab-md-150">logout date</th>
									<th class="tab-md-150">last login status</th>
									<th class="tab-md-150">last login date</th>
									
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
											<td><?php echo $row['login_type_name'];?></td>
											<td><?php echo $row['branch_name'];?></td>
											<td><?php echo $row['user_name'];?></td>
											<td><?php echo $row['mobile_number'];?></td>
											<td><?php echo $row['ip_address'];?></td>
											<!-- <td><?php #echo date("d-M-Y h:m:i A",strtotime($row['created_date']));?></td> -->
											<td><?php echo date("d-M-Y h:i:s A",strtotime($row['logout_date']));?></td>
											<td>
												<?php 
													if($row['last_login_status'] == 'Y')
													{
														echo 'Yes';
													}
													else if($row['last_login_status'] == 'N')
													{
														echo 'No';
													}
												?>
											</td>
											<td><?php echo date("d-M-Y h:i:s A",strtotime($row['last_login_date']));?></td>
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
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->
