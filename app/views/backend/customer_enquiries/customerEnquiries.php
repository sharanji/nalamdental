
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<!-- filters-->
			<form action="" class="form-validate-jquery" method="get">
				<div class="-card-header">
					<h3><b>Customer Enquires</b></h3>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="row">
							<label class="col-form-label col-md-4 text-right">Customer Name</label>
							<div class="form-group col-md-7">
								<input type="search"<?php echo $this->validation; ?> name="customer_name" class="form-control" value="<?php echo !empty($_GET['customer_name']) ? $_GET['customer_name'] :""; ?>" placeholder="Customer Name" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
							<label class="col-form-label col-md-4 text-right">Mobile Number</label>
							<div class="form-group col-md-7">
								<input type="search" name="mobile_number"id="mobile_number" minlength="10" maxlength='12' class="form-control mobile_vali" value="<?php echo !empty($_GET['mobile_number']) ? $_GET['mobile_number'] :""; ?>" placeholder="9999999999" autocomplete="off">
							</div>
						</div>
					</div>

					<div class="col-md-4 -float-right -text-right">
						<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
						
						<a href="<?php echo base_url(); ?>customer_enquiries/customerEnquiries" title="Clear" class="btn btn-default">Clear</a>
					</div>
					
				</div>
			</form>
			<!-- filters-->
			<?php 
				if(isset($_GET) &&  !empty($_GET))
				{
					?>
					<!-- Page Item Show start -->
					<div class="row mt-3">
						<div class="col-md-10">
						</div>
						<div class="col-md-2 float-right text-right">
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
					<!-- Page Item Show start -->

					<form action="" method="post">
						<div class="new-scroller">
							<table id="myTable" class="table table table-bordered">
								<thead>
									<tr>
										<th>Customer Name</th>
										<th class="text-center">Mobile Number</th>
										<th class="text-center">Selected Date</th>
										<th class="text-center">From Time</th>
										<th class="text-center">To Time</th>
										<th>Event Detail</th>
										<th>Looking For</th>
										<th class="text-center">Created Date</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if (count($resultData) > 0) 
										{
											$i=0;
											$firstItem = $first_item;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td><?php echo $row['customer_name'];?></td>
													<td class="text-center"><?php echo $row['mobile_number'];?></td>
													<td class="text-center"><?php echo date(DATE_FORMAT,strtotime($row['selected_date']));?></td>
													<td class="text-center"><?php echo $row['from_time'];?></td>
													<td class="text-center"><?php echo $row['to_time'];?></td>
													<td ><?php echo $row['event_detail'];?></td>
													<td ><?php echo $row['looking_for'];?></td>
													<td class="text-center"><?php echo date(DATE_FORMAT,strtotime($row['created_date']));?></td>
												</tr>
												<?php
												$i++;
											}											
										}
										else 
										{
											?>
											<tr>
												<td class="text-center" colspan="20">
													<img src="<?php echo base_url();?>uploads\nodata.png" style="width:200px;height:200px;"><br>
												</td>
											</tr>
											<?php 
										} 
										
									?>
									
								</tbody>
							</table>
						</div>
					</form>

				
					<?php
						if (count($resultData) > 0) 
						{
							?>
							<div class="row mt-3">
								<div class="col-md-6 showing-count">
									Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
								</div>
								<!-- pagination start here -->
								<?php 
									if( isset($pagination) )
									{
										?>	
										<div class="col-md-6">
											<?php foreach ($pagination as $link){echo $link;} ?>
										</div>
										<?php
									}
								?>
								<!-- pagination end here -->
							</div>
							<?php 
						} 
					?>
					<?php 
				} 
			?>		
		</div>
	</div>
</div>
	
