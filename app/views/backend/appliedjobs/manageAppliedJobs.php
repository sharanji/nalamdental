
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


			<div class="row mt-1 mb-3">
				<div class="col-md-6" style="font-size:14px;">
					<a href="javascript:void(0);" onclick="showFilter();">
						<i class="fa fa-filter" aria-hidden="true"></i> <b>Search</b>
					</a>
				</div>
			</div>

			
			<?php
				if( isset($_GET) && !empty($_GET))
				{
					$displaySearch = 'style="display:block;"';
				}
				else
				{
					$displaySearch = 'style="display:none;"';
				}
			?>
			
			<!-- Filters start here -->
			<div class="search-form" <?php #echo $displaySearch;?>>
				<form action="" class="form-validate-jquery summary_form" method="get">
					<div class="row">
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-5">Keywords</label>
								<div class="form-group col-md-7">
								<input type="saerch" name="customer_name" autocomplete="off" id="customer_name" value="<?php echo isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL; ?>" placeholder="Candidate Name / Email / Mobile Number" class="form-control">
								</div>
							</div>
							<!-- <span class="search_required"></span> -->
						</div>
						<div class="col-md-2">
							<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
							<a href="<?php echo base_url(); ?>appliedjobs/manageAppliedJobs" title="Clear" class="btn btn-default">Clear</a>
						</div>
						
					</div>
					
						
				</form>
			</div>
			
			<style>
				span.search_required {
					color: red;
				}
			</style>
			

			<?php 
				if(isset($_GET) &&  !empty($_GET))
				{
					?>
					<?php 
						if(count($resultData)>0)
						{
							?>
							<!-- Page Item Show start -->
							<div class="row">
								<div class="col-md-10 mt-3">
									<a href="<?php echo base_url().$this->redirectURL.'&download_excel=download_excel'; ?>" title="Download to Excel" class="btn btn-primary btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Download to Excel</a>
								</div>

								<div class="col-md-2 float-right text-right">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
									
									<?php 
										?>
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
										<?php
									?>
									
								</div>
							</div>
							
							<?php
						}	
					?>
						
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="tab-md-150">Candidate Name</th>
									<th class="tab-md-150">Email</th>
									<th class="tab-md-140">Mobile Number</th>
									<th class="tab-md-150">Job Category</th>
									<th class="tab-md-120">Experience</th>
									<th class="tab-md-140">Location</th>
									<th class="tab-md-140">Expected Salary</th>
									<th class="tab-md-140">Notice Period</th>
									<th class="text-center tab-md-100">Resume</th>
									<th class="text-center tab-md-100">Photo</th>
									<th class="tab-md-150">Created Date</th>
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
											<td><?php echo $row['full_name'];?></td>
											<td><?php echo $row['email'];?></td>
											<td><?php echo $row['mobile_number'];?></td>
											<td><?php echo $row['job_name'];?></td>
											<td><?php echo $row['experience'];?></td>
											<td><?php echo $row['location'];?></td>
											<td><?php echo $row['expected_salary'];?></td>
											<td><?php echo $row['list_value'];?></td>
											<td class="text-center">
												<a class="text-dark" href="<?php echo base_url() . 'uploads/jobs/candidate_resume/' . $row['applied_job_id'].'.pdf'; ?>" target="_blank"> <i class="fa fa-file-pdf-o text-danger"></i> View</a>
											</td>
											<td class="text-center">
												<?php 
													if(isset($row['applied_job_id']))
													{
														$url = "uploads/jobs/".$row['applied_job_id'].".png";
														if(file_exists($url))
														{
															?>
															<img src="<?php echo base_url().$url;?>" style="width:50px !important; height:40px !important;" alt="...">
															<?php 
														}else{
															?>
															<img src="<?php echo base_url()?>uploads/no-image.png" style="width:50px !important; height:40px !important;" alt="...">
															<?php 
														}
													} 
												?>
											</td>
											<td><?php echo date('d-M-Y', strtotime($row['created_date'])); ?></td>
											
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
