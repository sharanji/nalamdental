<?php 
	$system_manager = array();
?>


<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit" || $type == "view")
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
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<!-- <legend class="text-uppercase font-size-sm font-weight-bold"><?php //echo $type;?> Category</legend> -->
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Category Name <span class="text-danger">*</span></label>
									<input type="text" name="category_name" class="form-control" required value="<?php echo isset($edit_data[0]['category_name'])?$edit_data[0]['category_name']:"";?>" placeholder="">
								</div>

								<div class="form-group col-lg-3">
									<label class="col-form-label">Category Description</label>
									<textarea name="category_description" <?php echo $this->validation;?> rows="1" id="category_description" class="form-control"><?php echo isset($edit_data[0]['category_description']) ? $edit_data[0]['category_description']:NULL;?></textarea>
								</div>

								<div class="form-group col-lg-3">
									<label class="col-form-label">Display Seq Num <span class="text-danger">*</span></label>
									<input type="text" name="disp_seq_num" class="form-control"<?php echo $this->validation;?> required value="<?php echo isset($edit_data[0]['disp_seq_num'])?$edit_data[0]['disp_seq_num']:"";?>" placeholder="">
								</div>
							
							</div>

							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Category Level 1</label>
									<?php 
										$ChildCategory1Qry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
										left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
										where 
										sm_list_types.active_flag='Y' and 
										coalesce(sm_list_types.start_date,$this->date) <= ".$this->date." and 
										coalesce(sm_list_types.end_date,$this->date) >= ".$this->date." and
										sm_list_types.deleted_flag='N' and


										sm_list_type_values.active_flag='Y' and 
										coalesce(sm_list_type_values.start_date,$this->date) <= ".$this->date." and 
										coalesce(sm_list_type_values.end_date,$this->date) >= ".$this->date." and
										sm_list_type_values.deleted_flag='N' and 

										sm_list_types.list_name = '".$this->category_level1_name."' ";

										$ChildCategory1 = $this->db->query($ChildCategory1Qry)->result_array(); 
									?>
									<select name="cat_level_1" id="cat_level_1" <?php #echo $dropdownDisabled;?> class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select -</option>
										<?php 
											foreach($ChildCategory1 as $row)
											{
												$selected="";
												if(isset($edit_data[0]['cat_level_1']) && ($edit_data[0]['cat_level_1'] == $row['list_type_value_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['list_type_value_id']; ?>" <?php echo $selected;?>><?php #echo $row['list_code']; ?><!-- &nbsp;&nbsp; | &nbsp;&nbsp;  --><?php echo $row['list_value']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Category Level 2 </label>
									<?php 
										$ChildCategory2Qry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
										left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
										where 

										sm_list_types.active_flag='Y' and 
										coalesce(sm_list_types.start_date,$this->date) <= ".$this->date." and 
										coalesce(sm_list_types.end_date,$this->date) >= ".$this->date." and
										sm_list_types.deleted_flag='N' and

										sm_list_type_values.active_flag='Y' and 
										coalesce(sm_list_type_values.start_date,$this->date) <= ".$this->date." and 
										coalesce(sm_list_type_values.end_date,$this->date) >= ".$this->date." and
										sm_list_type_values.deleted_flag='N' and 

										sm_list_types.list_name = '".$this->category_level2_name."' ";

										$ChildCategory2 = $this->db->query($ChildCategory2Qry)->result_array(); 
									?>
									<select name="cat_level_2" id="cat_level_2" <?php #echo $dropdownDisabled;?> class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select -</option>
										<?php 
											foreach($ChildCategory2 as $row)
											{
												$selected="";
												if(isset($edit_data[0]['cat_level_2']) && ($edit_data[0]['cat_level_2'] == $row['list_type_value_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['list_type_value_id']; ?>" <?php echo $selected;?>><?php #echo $row['list_code']; ?><!-- &nbsp;&nbsp; | &nbsp;&nbsp;  --><?php echo $row['list_value']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<div class="form-group col-md-3">
									<label class="col-form-label">Category Level 3</label>
									<?php 
										$ChildCategory3Qry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
										left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
										where 
											sm_list_types.active_flag='Y' and 
											coalesce(sm_list_types.start_date,$this->date) <= ".$this->date." and 
											coalesce(sm_list_types.end_date,$this->date) >= ".$this->date." and
											sm_list_types.deleted_flag='N' and

											sm_list_type_values.active_flag='Y' and 
											coalesce(sm_list_type_values.start_date,$this->date) <= ".$this->date." and 
											coalesce(sm_list_type_values.end_date,$this->date) >= ".$this->date." and
											sm_list_type_values.deleted_flag='N' and

											sm_list_types.list_name = '".$this->category_level3_name."' ";

										$ChildCategory3 = $this->db->query($ChildCategory3Qry)->result_array(); 
									?>
									<select name="cat_level_3" id="cat_level_3" <?php #echo $dropdownDisabled;?> class="form-control <?php echo $searchDropdown;?>">
										<option value="">- Select -</option>
										<?php 
											foreach($ChildCategory3 as $row)
											{
												$selected="";
												if(isset($edit_data[0]['cat_level_3']) && ($edit_data[0]['cat_level_3'] == $row['list_type_value_id']) )
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['list_type_value_id']; ?>" <?php echo $selected;?>><?php #echo $row['list_code']; ?><!-- &nbsp;&nbsp; | &nbsp;&nbsp;  --><?php echo $row['list_value']; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
							
							<div class="row">
								<div class="form-group col-md-3">
									<label class="col-form-label">Start Date </label>
									<?php 
										if(isset($edit_data[0]['start_date']) && !empty($edit_data[0]['start_date'])){
											$start_date = date(DATE_FORMAT,strtotime($edit_data[0]['start_date']));
										}else{$start_date = NULL;}
									?>
									<input type="text" name="start_date" id="start_date" readonly class="form-control" value="<?php echo $start_date;?>" placeholder="">
								</div>

								<div class="form-group col-md-3">
									<label class="col-form-label">End Date</label>
									<?php 
										if(isset($edit_data[0]['end_date']) && !empty($edit_data[0]['end_date'])){
											$end_date = date(DATE_FORMAT,strtotime($edit_data[0]['end_date']));
										}else{$end_date = NULL;}
									?>
									<input type="text" name="end_date" id="end_date" readonly class="form-control" value="<?php echo $end_date;?>" placeholder="">
								</div>	
								
								<div class="form-group col-lg-3">
									<label class="col-form-label">Category Logo</label>
									<input type="file" name="category_images" onchange="return validateSingleFileExtension(this)" class="form-control singleImage">
										<span class="note-class"><b>Note</b> : Upload size is 1 [MB] and image format is (png,gif,jpg,jpeg and bmp).</span>
											<script>
												/** Single Image Type & Size Validation **/
												function validateSingleFileExtension(fld) 
												{
													var fileUpload = fld;
													
													if (typeof (fileUpload.files) != "undefined")
													{
														var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
														var validSize = 1024 * 1; //1024 - 1Mb multiply 4mb
														
														if( size > validSize )
														{
															alert("Upload size is 1 MB");
															$('.singleImage').val('');
															var value = 1;
															return false;
														}
														else if(!/(\.png|\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
														{
															alert("Invalid file type.");      
															$('.singleImage').val('');
															return false;   
														}
														
														if(value != 1)	
															return true; 
													}
												}
											</script>
									<?php 
										if(isset($edit_data[0]['category_id']))
										{
											$url = "uploads/category_image/".$edit_data[0]['category_id'].".png";
											if(file_exists($url))
											{
												?><br>
												<div class="form-group view-form row">
													<div class="col-lg-8"><br>
														<img src="<?php echo base_url().$url;?>" style="width:200px !important; height:100px !important;" alt="...">
													</div>
												</div>
												<?php 
											}
										} 
									?>
								</div>
							</div>

							
						</fieldset>
						
						<div class="d-flex justify-content-end align-items-center">
							<a href="<?php echo base_url(); ?>categories/manage_category" class="btn btn-default">Close</a>
							<?php 
								if($type == "view")
								{
								}
								else
								{
									?>
									<button type="submit" class="btn btn-primary ml-1">Save</button>
									<?php
								}
							?>
						</div>
					</form>
					
					<?php
				}
				else
				{
					?>
					<style>
						.dropdown-menu.dropdown-menu.show{
						    position: absolute;
							/* transform: translate3d(9px, -50px, -1px) !important;
							top: 0px;
							left: 0px;
							will-change: transform; */
						}
					</style>
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>categories/manage_category/add" class="btn btn-info btn-sm">
								Create Category
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<section class="trans-section-back-1">
									<div class="row">
										<div class="col-md-4">	
											<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
											
										</div>	

										<div class="col-md-4">
											<div class="row">
												<label class="col-form-label col-md-3">Status</label>
												<div class="form-group col-md-9">
													<?php 
														$activeStatusQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
														left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
														where 
				
														sm_list_types.active_flag='Y' and 
														coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
														coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
														sm_list_types.deleted_flag='N' and
				
														sm_list_type_values.active_flag='Y' and 
														coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
														coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
														sm_list_type_values.deleted_flag='N' and 
				
														sm_list_types.list_name = 'ACTIVESTATUS'
														order by sm_list_type_values.order_sequence asc";
				
														$activeStatus = $this->db->query($activeStatusQry)->result_array(); 
													?>
													
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
										<div class="col-md-3">
											<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										</div>
									</div>
								</section>
							</div>
							
						</div>
					</form>

					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>

								<div class="row">
									<div class="col-md-8">
										
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
				
							<div class="new-scroller">
								<table class="table --datatable-responsive table-hover table-bordered">
									<thead>
										<tr>
											<th style="text-align:center;width:12%;">Controls</th>
											<th>Category Name</th>
											<th>Category Description</th>
											<th class="text-center">Category Image</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 	
											$i=1;
											foreach($category as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
															<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu">
																<li>
																	<a title="View" href="<?php echo base_url(); ?>categories/manage_category/view/<?php echo $row['category_id'];?>">
																		<i class="fa fa-eye"></i> View
																	</a>
																</li>
																
																<li>
																	<a title="Edit" href="<?php echo base_url(); ?>categories/manage_category/edit/<?php echo $row['category_id'];?>">
																		<i class="fa fa-pencil"></i> Edit
																	</a>
																</li>
																
																<li>
																	<?php 
																		if($row['active_flag'] == $this->active_flag)
																		{
																			?>
																			<a href="<?php echo base_url(); ?>categories/manage_category/status/<?php echo $row['category_id'];?>/N" title="block">
																				<i class="fa fa-ban"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a href="<?php echo base_url(); ?>categories/manage_category/status/<?php echo $row['category_id'];?>/Y" title="Unblock">
																				<i class="fa fa-ban"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																</li>
																
															</ul>
														</div>
														
													</td>
													<td><?php echo ucfirst($row['category_name']);?></td>
													<td><?php echo ucfirst($row['category_description']);?></td>
													<td class="text-center">
														<?php 
															if(isset($row['category_id']))
															{
																$url = "uploads/category_image/".$row['category_id'].".png";
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
													
													<td style="text-align:center;">
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<span class="btn btn-outline-success btn-sm" title="Active">Active</span>
																<?php 
															} 
															else
															{  ?>
																<span class="btn btn-outline-warning btn-sm" title="Inactive">Inactive</span>
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
									if(count($category) == 0)
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
								if (count($category) > 0) 
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
	</div><!-- Card end-->
</div><!-- Content end-->

