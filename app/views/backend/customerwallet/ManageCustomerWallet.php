
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && ($type == "add" || $type == "edit" || $type == "view"))
				{
					if($type == "view"){
						$fieldSetDisabled = "disabled";
						#$dropdownDisabled = "style='pointer-events: none;'";
						$searchDropdown = "";
					}else{
						$fieldSetDisabled = "";
						#$dropdownDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<div class="row">
						<div class="col-xl-12 col-xxl-12 col-lg-12">
							<div class="-card">
								<div class="-card-header">
									<h4 class="-card-title">Tax</h4>
								</div>
								<div class="-card-body">
									<div class="">
										<form class="form-validate-jquery" action="#"  method="post" enctype="multipart/form-data">
											<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">Tax Name<span class="text-danger">*</span></label>
													<div class="col-lg-3">
														<input type="text" name="tax_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['tax_name']) ? $edit_data[0]['tax_name'] :"";?>" placeholder="">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">Tax Value</label>
													<div class="col-lg-3">
														<input type="text" name="tax_value" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['tax_value']) ? $edit_data[0]['tax_value'] :"";?>" placeholder="">
													</div>
												</div>

												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">Start Date </label>
													<?php 
														if(isset($edit_data[0]['start_date']) && !empty($edit_data[0]['start_date'])){
															$start_date = date(DATE_FORMAT,strtotime($edit_data[0]['start_date']));
														}else{$start_date = NULL;}
													?>
													<div class="col-lg-3">
														<input type="text" name="start_date" id="start_date" readonly class="form-control" value="<?php echo $start_date;?>" placeholder="">
												
													</div>
												</div>
												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-left">End Date</label>
													<?php 
														if(isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date'])){
															$end_date = date(DATE_FORMAT,strtotime($edit_data[0]['end_date']));
														}else{$end_date = NULL;}
													?>
													<div class="col-lg-3">
													<input type="text" name="end_date" id="end_date" readonly class="form-control" value="<?php echo $end_date;?>" placeholder="">
													</div>
												</div>
											</fieldset>
											<div class="form-group float-right">
												<a href="<?php echo base_url();?>tax/manageTax" class="btn btn-light btn-sm">Close</a>
												<button type="submit" class="btn btn-primary text-right btn-sm">Save  </button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				else 
				{
					?>
					
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							
						</div>
					</div>

					<!-- filters-->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Customer Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$customersQry = "select customer_id,customer_number,customer_name from cus_consumers as cus_customers 
														
														order by cus_customers.customer_number asc";

											$getCustomers = $this->db->query($customersQry)->result_array();	
										?>
										<select name="customer_id" id="customer_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getCustomers as $row)
												{
													$selected="";
													if(isset($_GET['customer_id']) && $_GET['customer_id'] == $row["customer_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["customer_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["customer_number"]);?> | <?php echo ucfirst($row["customer_name"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4">Mobile Number</label>
									<div class="form-group col-md-7">
										<input type="search" name="mobile_number" id="mobile_number" minlength="10" maxlength='12' class="form-control mobile_vali" value="<?php echo !empty($_GET['mobile_number']) ? $_GET['mobile_number'] :""; ?>" placeholder="Mobile Number" autocomplete="off">
									</div>
								</div>
							</div>


							<div class="col-md-4">
								<a href="<?php echo base_url(); ?>customer_wallet/ManageCustomerWallet" title="Clear" class="btn btn-default">Clear</a>
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
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
												<th>Customer Number</th>
												<th>Customer Name</th>
												<th>Mobile Number</th>
												<th>Branch Name</th>
												<th class="text-right">Wallet Amount</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (count($resultData) > 0) 
												{
													$i=1;
													$firstItem = $first_item;
													foreach($resultData as $row)
													{
														?>
														<tr>
															<td><?php echo $row['customer_number'];?></td>
															<td><?php echo $row['customer_name'];?></td>
															<td><?php echo $row['mobile_number'];?></td>
															<td><?php echo $row['branch_name'];?></td>
															<td class="text-right"><?php echo isset($row['wallet_amount']) ? $row['wallet_amount'] :"0.00";?></td>
														</tr>
														<?php
														$i++;
													}											
												}
												else 
												{
													?>
														<td class="text-center" colspan="20">
															<img src="<?php echo base_url();?>uploads\nodata.png" style="width:200px;height:200px;"><br>
															<!--<p class="admin-no-data">No data found.</p>-->
														</td>
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
								
					<?php
				} 
			?>
		</div>
	</div>
</div>
	
