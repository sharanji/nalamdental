<?php
	$physical_stock_adjustment 	= accessMenu(physical_stock_adjustment);
	$activeStatus 				= $this->common_model->lov('ACTIVESTATUS'); 
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
				{
					if($type == "view")
					{
						$fieldSetDisabled = "disabled";
						$searchDropdown = "";
						$this->fieldDisabled = $fieldDisabled = "";
						$this->fieldReadonly = $fieldReadonly = "";
					}
					else
					{
						if($type == "add" || $type == "edit")
						{
							$this->fieldDisabled = $fieldDisabled = "";
							$this->fieldReadonly = $fieldReadonly = "";
						}

						$fieldSetDisabled = "";
						$searchDropdown = "searchDropdown";
					}
					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="header-lines">
							<!-- Buttons start here -->
							<div class="row mb-3">
								<div class="col-md-6">
									<h3>
										<b>
											<?php
												if($type == "add")
												{
													?>
													Create
													<?php
												}
												else if($type == "edit")
												{
													echo ucfirst($type);
												}
												else if($type == "view")
												{
													echo ucfirst($type);
												}
											?>
											SEO Content
										</b>
									</h3>
								</div>
								<div class="col-md-6 text-right">
									<?php
										if($type == "add" || $type == "edit")
										{
											?>
											<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm form_submit_valid">Save</button>
											<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm form_submit_valid">Submit</button>
											<?php
										}
									?>
									<a href="<?php echo base_url(); ?>seo/manageSeoContent" class="btn btn-default btn-sm">Close</a>

								</div>
							</div>
							<!-- Buttons end here -->

							<fieldset <?php echo $fieldSetDisabled;?>>

								<section class="header-section">
									<div class="row">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label page_title"><span class="text-danger">*</span> Page Title</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group role_id">
														<input type="text" id="page_title" name="page_title" required autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['page_title']) ? $editData[0]['page_title'] :"";?>" placeholder="Page Title">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label meta_description"><span class="text-danger">*</span> Meta Description</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<textarea name="meta_description" id="meta_description" rows="1" required autocomplete="off" class="form-control" placeholder="Meta Description"><?php echo isset($editData[0]['meta_description']) ? $editData[0]['meta_description'] :"";?></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label page_url"><span class="text-danger">*</span> Link URL(Canonical)</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group role_id">
														<input type="text" id="page_url" name="page_url" autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['page_url']) ? $editData[0]['page_url'] :"";?>" placeholder="Link URL">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label og_title"><span class="text-danger">*</span> OG Title</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="og_title" name="og_title" required autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['og_title']) ? $editData[0]['og_title'] :"";?>" placeholder="OG Title">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label og_url"><span class="text-danger">*</span> OG URL</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="og_url" name="og_url" autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['og_url']) ? $editData[0]['og_url'] :"";?>" placeholder="OG URL">
													</div>
												</div>
											</div>
											
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label meta_title"><span class="text-danger">*</span> Meta Title</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="meta_title" name="meta_title" autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['meta_title']) ? $editData[0]['meta_title'] :"";?>" placeholder="Meta Title">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label meta_keywords">Meta Keywords (Comma Separated)</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<textarea name="meta_keywords" id="meta_keywords" rows="1" autocomplete="off" class="form-control" placeholder="Meta Keywords"><?php echo isset($editData[0]['meta_keywords']) ? $editData[0]['meta_keywords'] :"";?></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label og_description"><span class="text-danger">*</span> OG Description</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<textarea name="og_description" id="og_description" rows="1" autocomplete="off" class="form-control" placeholder="OG Description"><?php echo isset($editData[0]['og_description']) ? $editData[0]['og_description'] :"";?></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label og_sitename"><span class="text-danger">*</span> OG Sitename</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="og_sitename" name="og_sitename" autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['og_sitename']) ? $editData[0]['og_sitename'] :"";?>" placeholder="OG Sitename">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-4">
													<div class="form-group text-right">
														<label class="col-form-label meta_subject"> Meta Subject</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<input type="text" id="meta_subject" name="meta_subject" autocomplete="off" class="form-control" value="<?php echo isset($editData[0]['meta_subject']) ? $editData[0]['meta_subject'] :"";?>" placeholder="Meta Subject">
													</div>
												</div>
											</div>
											
											
										</div>
										
									</div>
									

								</section>

								<script>
									$(document).ready(function() {
										var type = '<?php echo $type; ?>';
										$(".form_submit_valid").prop('disabled', true);

										function validateForm() 
										{
											var page_title             	= $("#page_title").val();
											var page_url             	= $("#page_url").val();
											var meta_title              = $("#meta_title").val(); 
											/*var meta_keywords       	= $("#meta_keywords").val();*/
											var meta_description    	= $("#meta_description").val();
											var og_title    			= $("#og_title").val();
											var og_description    		= $("#og_description").val();
											var og_url    				= $("#og_url").val();
											var og_sitename    			= $("#og_sitename").val();

											if (page_title.trim() !== "" && page_url.trim() !== "" && meta_title.trim() !== "" && meta_description.trim() !== "" &&  og_title.trim() !== "" && og_description.trim() !== "" && og_url.trim() !== "" && og_sitename.trim() !== "") 
											{
												$(".form_submit_valid").prop('disabled', false);
											} else {
												$(".form_submit_valid").prop('disabled', true);
											}
										}

										$("input, select, textarea").on('keyup change', function() {
											validateForm();
										});

										validateForm();

										function saveBtn(val) {
											$(".form_submit_valid").prop('disabled', true);

											var page_title             	= $("#page_title").val();
											var page_url             	= $("#page_url").val();
											var meta_title              = $("#meta_title").val(); 
											/*var meta_keywords       	= $("#meta_keywords").val();*/
											var meta_description    	= $("#meta_description").val();
											var og_title    			= $("#og_title").val();
											var og_description    		= $("#og_description").val();
											var og_url    				= $("#og_url").val();
											var og_sitename    			= $("#og_sitename").val();


											if (page_title.trim() !== "" && page_url.trim() !== "" && meta_title.trim() !== "" && meta_description.trim() !== "" &&  og_title.trim() !== "" && og_description.trim() !== "" && og_url.trim() !== "" && og_sitename.trim() !== "") 
											{
												$(".page_title").removeClass('errorClass');
												$(".page_url").removeClass('errorClass');
												$(".meta_title").removeClass('errorClass');
												// $(".meta_keywords").removeClass('errorClass');
												$(".meta_description").removeClass('errorClass');
												$(".og_title").removeClass('errorClass');
												$(".og_description").removeClass('errorClass');
												$(".og_url").removeClass('errorClass');
												$(".og_sitename").removeClass('errorClass');
												return true;
											} 
											else 
											{
												$(".form_submit_valid").prop('disabled', false);
												
												if (page_title.trim() === "") {
													$(".page_title").addClass('errorClass');
												}
												if (page_url.trim() === "") {
													$(".page_url").addClass('errorClass');
												}
												if (meta_title.trim() === "") {
													$(".meta_title").addClass('errorClass');
												}
												// if (meta_keywords.trim() === "") {
												// 	$(".meta_keywords").addClass('errorClass');
												// }
												if (meta_description.trim() === "") {
													$(".meta_description").addClass('errorClass');
												}
												if (og_title.trim() === "") {
													$(".og_title").addClass('errorClass');
												}
												if (og_description.trim() === "") {
													$(".og_description").addClass('errorClass');
												}
												if (og_url.trim() === "") {
													$(".og_url").addClass('errorClass');
												}
												if (og_sitename.trim() === "") {
													$(".og_sitename").addClass('errorClass');
												}
												return false;
											}
										}
									});

								</script>

							</fieldset>
							<div class="col-md-12 mt-3 pr-0 text-right">
								<?php
									if($type == "add" || $type == "edit")
									{
										?>
										<button type="submit" name="save_btn" id="save_btn" onclick="return saveBtn('save_btn');" title="Save & Continue" class="btn btn-primary btn-sm form_submit_valid">Save</button>
										<button type="submit" name="submit_btn" id="submit_btn" onclick="return saveBtn('submit_btn');" title="Submit" class="btn btn-primary btn-sm form_submit_valid">Submit</button>
										<?php
									}
								?>
								<a href="<?php echo base_url(); ?>seo/manageSeoContent" class="btn btn-default btn-sm">Close</a>
							</div>
						</div>
					</form>

					
					<?php
				}
				else
				{
					?>
					<!-- Buttons start here -->
					<div class="row">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							<a href='<?php echo base_url();?>setup/settings' class='btn btn-info'>
								<i class="icon-arrow-left16"></i> Back
							</a>
							<?php
								if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>seo/manageSeoContent/add" class="btn btn-info btn-sm">
										Create SEO
									</a>
									<?php
								}
							?>
						</div>
					</div>
					<!-- Buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Keywords</label>
									<div class="form-group col-md-7">
										<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Page Title / Link Url / Meta Title" autocomplete="off">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-5 text-right">Status</label>
									<div class="form-group col-md-7">
										<select name="active_flag" id="active_flag" class="form-control searchDropdown">
											<!-- <option value="">- Select -</option> -->
											<?php 
												foreach($activeStatus as $row)
												{
													$selected="";
													if(isset($_GET['active_flag']) && $_GET['active_flag'] == $row["list_code"] )
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
							<div class="col-md-2">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search"></i></button>
								<a href="<?php echo base_url(); ?>seo/manageSeoContent" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>
					</form>
					<!-- Filters end here -->

					<?php
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<?php
									if( isset($resultData) && count($resultData) > 0 )
									{
										?>
											<div class="col-md-6">
												<?php /*
													<a href="<?php echo base_url().$this->redirectURL;?>&export=export" class="btn btn-primary btn-sm">Download Excel</a>
												*/ ?>
											</div>

											<div class="col-md-6 float-right text-right">
												<?php
													$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
												?>
												<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>

												<div class="filter_page pt-0">
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
									}
								?>
							</div>
						    <!-- Page Item Show start -->

							<!-- Table start here -->
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover tbl_height">
									<thead>
										<tr>
											<th class="text-center tab-md-100">Controls</th>
											<th class="text-left tab-md-140">Page Title</th>
											<th class="text-left tab-md-140">Page URL</th>
											<th class="text-left tab-md-140">Meta Title</th>
											<th class="text-center tab-md-100">Status</th>
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
												<td class="text-center">
														<div class="dropdown" >
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm"  data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="table-dropdown dropdown-menu dropdown-menu-right">
																<?php
																	if($physical_stock_adjustment['create_edit_only'] == 1 || $physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																					<li>
																						<a title="Edit" href="<?php echo base_url(); ?>seo/manageSeoContent/edit/<?php echo $row['seo_id'];?>/">
																							<i class="fa fa-edit"></i> Edit
																						</a>
																					</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($physical_stock_adjustment['read_only'] == 1 || $this->user_id == 1)
																			{
																				
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>seo/manageSeoContent/view/<?php echo $row['seo_id'];?>/">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($physical_stock_adjustment['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>seo/manageSeoContent/status/<?php echo $row['seo_id'];?>/N" title="Inactive">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>seo/manageSeoContent/status/<?php echo $row['seo_id'];?>/Y" title="Active">
																								<i class="fa fa-check"></i> Active
																							</a>
																							<?php 
																						} 
																					?>
																				<li>
																				<?php 
																			} 
																		?>
																		<?php 
																	} 
																?>
															</ul>
														</div>
													</td>
													<td><?php echo $row['page_title'];?></td>
													<td><?php echo $row['page_url'];?></td>
													<td><?php echo $row['meta_title'];?></td>
													<td class="text-center">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active">
																	Active 
																</span>
																<?php 
															} 
															else
															{  ?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive">
																	Inactive 
																</span>
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
					<?php
				}
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->











