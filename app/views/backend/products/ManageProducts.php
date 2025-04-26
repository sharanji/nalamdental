<!-- Import csv start -->
<div class="modal fade" id="importcountryCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" --style="background: #1a4363;color: #fff;">
				<h5 class="modal-title" id="exampleModalLabel">Import Item</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?php echo base_url(); ?>products/ManageProducts/import" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					
					<div class="row">
						<div class="col-md-12 mb-3">
							<div class="well well-small">
								The correct column order is <span class="text-info-"> ( Item Name, Item Description, Item Cost, Item Type, Category and UOM  )</span>&nbsp; &amp; You must follow this.
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<span class="text-danger-" style="font-size:12px !important;"><b>Note : </b> The first line in downloaded csv file should remain as it is. Please do not change the order of columns and Update valid data..</span><br>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-9">
							<input type="file" name="csv"  id="chooseFile" class="form-control singleDocument" onchange="return validateSingleDocumentExtension(this)" required />
							<span style="color:#a0a0a0;">Note : Upload format CSV and upload size is 5 mb.</span>
						</div>
						<div class="col-md-3">
							<a href="<?php echo base_url(); ?>assets/sample_products.csv" class="btn btn-info btn-flat btn-sm pull-right" title="Download Sample File">
								<i class="fa fa-download"></i> Download
							</a>
						</div>
					</div>
					
				
					<script>
						/** Single Document Type & Size Validation **/
						function validateSingleDocumentExtension(fld) 
						{
							var fileUpload = fld;
							
							if (typeof (fileUpload.files) != "undefined")
							{
								var size = parseFloat( fileUpload.files[0].size / 1024 ).toFixed(2);
								var validSize = 1024 * 5; //1024 - 1Mb multiply 4mb
								
								//var validSize = 500; 
								
								if( size > validSize )
								{
									//alert("Document upload size is 4 MB");
									alert("File size should not exceed 5 MB.");
									$('.singleDocument').val('');
									var value = 1;
									return false;
								}
								else if(!/(\.csv)$/i.test(fld.value))
								//else if(!/(\.pdf)$/i.test(fld.value))
								{
									alert("Invalid document file type.");      
									$('.singleDocument').val('');
									return false;   
								}
								
								if(value != 1)	
									return true; 
							}
						}
					</script>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
					<button type="submit" class="btn btn-primary btn-sm ml-1">Import</button>
				</div>
			</form>
		</div>
	</div>
</div>	
<!-- Import csv end -->

<?php
	$itemCreationMenu = accessMenu(item_creation);
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
						<div class="row">
							<div class="col-md-6">
								<h3><b>Item</b></h3>
							</div>
							
							<?php 
								if($type == "view")
								{
									?>
									<div class="col-md-6 text-right">
										<a class="btn btn-sm btn-primary edit-icon" href="<?php echo base_url(); ?>products/ManageProducts/edit/<?php echo $id;?>" title="Edit">
											<i class="fa fa-edit"></i>
										</a>
									</div>
									<?php 
								} 
							?>
						</div>

						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Item Code <span class="text-danger">*</span></label>
										<div class="form-group col-md-5">
											<input type="hidden" name="item_name" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['item_name']) ? $edit_data[0]['item_name'] :"";?>" placeholder="Item Name">
											<input type="number" name="item_code" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['item_code']) ? $edit_data[0]['item_code'] :"";?>" placeholder="Item Code">
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Item Description <span class="text-danger">*</span></label>
										<div class="form-group col-md-5">
											<input type="text" name="item_description" <?php echo $this->validation;?> required class="form-control" value="<?php echo isset($edit_data[0]['item_description']) ? $edit_data[0]['item_description'] :"";?>" placeholder="Item Description">
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Long Description <span class="text-danger"></span></label>
										<div class="form-group col-md-6">
											<textarea name="long_description" class="form-control" rows="1" placeholder="Long Description"><?php echo isset($edit_data[0]['long_description']) ? $edit_data[0]['long_description'] :"";?></textarea>
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>

								<?php 
									/* $listTypeValuesQry = "select 
									sm_list_type_values.list_type_value_id,
									sm_list_type_values.list_code,
									sm_list_type_values.list_value	
									from sm_list_type_values

									left join sm_list_types on 
									sm_list_types.list_type_id = sm_list_type_values.list_type_id
									where 
									sm_list_type_values.active_flag = 'Y' and 
									sm_list_types.list_name = 'ITEMTYPE'"; 
									$getitemCategory = $this->db->query($listTypeValuesQry)->result_array(); */
									
									$getitemCategory = $this->common_model->lov('ITEMTYPE');
								?>
								
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Item Type <span class="text-danger">*</span></label>
										<div class="form-group col-md-5">
											<select name="item_type"   class="form-control <?php echo $searchDropdown;?>" required>
												<option value="">- Select  -</option>
												<?php 
													foreach($getitemCategory as $itemcategory)
													{
														$selected="";
														if(isset($edit_data[0]['item_type_id']) && ($edit_data[0]['item_type_id'] == $itemcategory['list_type_value_id']) )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $itemcategory['list_type_value_id'];?>" <?php echo $selected;?>><?php echo $itemcategory['list_value']; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
								</div>
							</div>

							<?php 
								$getHsnCode = $this->db->query("select hsn_code_id,hsn_code from inv_hsn_codes where active_flag='Y'  
								order by hsn_code asc")->result_array(); 
							?>
								
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">HSN Code <span class="text-danger"></span></label>
										<div class="form-group col-md-5">
											<select name="hsn_code_id" class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select  -</option>
												<?php 
													foreach($getHsnCode as $itemcategory)
													{
														$selected="";
														if(isset($edit_data[0]['hsn_code_id']) && ($edit_data[0]['hsn_code_id'] == $itemcategory['hsn_code_id']) )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $itemcategory['hsn_code_id']; ?>" <?php echo $selected;?>><?php echo $itemcategory['hsn_code']; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Category <span class="text-danger">*</span></label>
										<div class="form-group col-md-5">
											<?php $getCategory = $this->db->query("select category_name,category_id from inv_categories where active_flag='Y'  order by category_name asc")->result_array(); ?>
											<select name="category_id" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select  -</option>
												<?php 
													foreach($getCategory as $category)
													{
														$selected="";
														if(isset($edit_data[0]['category_id']) && ($edit_data[0]['category_id'] == $category['category_id']) )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $category['category_id']; ?>" <?php echo $selected;?>><?php echo $category['category_name']; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Item Cost  <span class="text-danger">*</span></label>
										<div class="form-group col-md-5">
											<input type="number" name="item_cost" required class="form-control" value="<?php echo isset($edit_data[0]['item_cost']) ? $edit_data[0]['item_cost'] :"";?>" placeholder="Item Cost">
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">UOM  <span class="text-danger">*</span></label>
										<div class="form-group col-md-3">
											<?php $getUOM = $this->db->query("select uom_code,uom_id from uom where active_flag='Y'")->result_array(); ?>
											<select name="uom" required class="form-control <?php echo $searchDropdown;?>">
												<option value="">- Select -</option>
												<?php 
													foreach($getUOM as $row)
													{
														$selected="";
														if(isset($edit_data[0]['uom']) && ($edit_data[0]['uom'] == $row['uom_id']) )
														{
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $row['uom_id']; ?>" <?php echo $selected;?>><?php echo $row['uom_code']; ?></option>
														<?php 
													} 
												?>
											</select>
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Minimum Qty <span class="text-danger"></span></label>
										<div class="form-group col-md-5">
											<input type="number" name="minimum_qty" min="0" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['minimum_qty']) ? $edit_data[0]['minimum_qty'] : '';?>" placeholder="Minimum Qty">
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Revision Number <span class="text-danger"></span></label>
										<div class="form-group col-md-5">
											<input type="text" name="revision_number" id="thickness" <?php echo $this->validation;?> class="form-control" value="<?php echo isset($edit_data[0]['revision_num']) ? $edit_data[0]['revision_num'] :'';?>" placeholder="Revision Number">
											<span class="exist_error text-warning"></span>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Short Code</label>
										<div class="form-group col-md-5">
											<input type="text" name="short_code" id="short_code" class="form-control" value="<?php echo isset($edit_data[0]['short_code']) ? $edit_data[0]['short_code'] : NULL;?>" placeholder="Short Code">
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="row">
										<label class="col-form-label col-md-3 text-right">Item Image <span class="text-danger"></span></label>
										<div class="form-group col-md-6">
											<input type="file" name="product_image" onchange="return validateSingleFileExtension(this)" class="form-control singleImage">
											<span class="text-muted" >Note : Upload format is (png,gif,jpg,jpeg) and size is 1 mb.</span>
											<span class="exist_error text-warning"></span>
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
												if( ($type == "edit" || $type == "view") && isset($id))
												{
													$url = "uploads/products/".$id.".png";
													if(file_exists($url))
													{
														?><br>
														<div class="form-group view-form row">
															<div class="col-lg-8"><br>
																<img src="<?php echo base_url().$url;?>" style="width:100px !important; height:75px !important;" alt="...">
															</div>
														</div>
														<?php 
													}
												} 
											?>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
						
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8 text-right">
								<a href="<?php echo base_url(); ?>products/ManageProducts" class="btn btn-sm btn-default">Close</a>
								<?php 
									if($type == "view")
									{

									}
									else
									{
										if($type == "edit")
										{
											?>
											<button type="submit" class="btn btn-primary ml-1">Save</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" class="btn btn-primary ml-1">Save</button>
											<?php 
										}
									}
								?>
							</div>
						</div>

						
						
					</form>
					<?php
				}
				else
				{ 
					?>
					<!-- buttons start here -->
					<div class="row mb-2">
						<div class="col-md-6"><h3><b><?php echo $page_title;?></b></h3></div>
						<div class="col-md-6 float-right text-right">
							
							<?php
								if($itemCreationMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="#" data-toggle="modal" data-target="#importcountryCSV" title="Import" class="btn btn-warning btn-sm">
										<i class="icon-import"></i> Import
									</a>
									<a href="<?php echo base_url(); ?>products/ManageProducts/add" class="btn btn-info btn-sm">
										Create Item
									</a>
									<?php
								} 
							?>
						</div>
					</div>
					<!-- buttons end here -->

					<!-- Filters start here -->
					<form action="" class="form-validate-jquery" method="get">
						<div class="row mt-3">
							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Item Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$itemQry = "select item_id,item_name,item_description from inv_sys_items 
														
														order by inv_sys_items.item_name asc";

											$getItems = $this->db->query($itemQry)->result_array();	
										?>
										<select name="item_id" id="item_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getItems as $row)
												{
													$selected="";
													if(isset($_GET['item_id']) && $_GET['item_id'] == $row["item_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["item_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["item_name"]);?> | <?php echo ucfirst($row["item_description"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">Category Name <!-- <span class="text-danger">*</span> --></label>
									<div class="form-group col-md-8">
										<?php 
											$categoryQry = "select category_id,category_name from inv_categories 
														
														order by inv_categories.category_name asc";

											$getCategory = $this->db->query($categoryQry)->result_array();	
										?>
										<select name="category_id" id="category_id" --required class="form-control searchDropdown">
											<option value="">- Select -</option>
											<?php 
												foreach($getCategory as $row)
												{
													$selected="";
													if(isset($_GET['category_id']) && $_GET['category_id'] == $row["category_id"] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $row["category_id"];?>" <?php echo $selected;?>><?php echo ucfirst($row["category_name"]);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-3 text-right">Status</label>
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
	
											sm_list_types.list_name = 'ACTIVESTATUS'";
	
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
						</div>

						<div class="row mt-2">
							<div class="col-md-8"></div>
							
							<div class="col-md-4" style="padding:0px 4px 2px 77px;">
							    <a href="<?php echo base_url(); ?>products/ManageProducts" title="Clear" class="btn btn-default">Clear</a>&nbsp;
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								
							</div>
						</div>
					</form>
					<!-- Filters end here -->
					
					<?php 
						if( isset($_GET) && !empty($_GET))
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
							<div class="new-scroller mt-3">
								<table --id="myTable" class="table table-bordered -sortable-table table-hover --table-striped --dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Item Code</th>
											<th>Item Description</th>
											<th>Category</th>
											<th>UOM</th>
											<th>Short Code</th>
											<th class="text-right">Item Cost (₹)</th>
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
														<div class="dropdown" >
															<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm"  data-toggle="dropdown" aria-expanded="false">
																Action&nbsp;<i class="fa fa-chevron-down"></i>
															</button>
															<ul class="table-dropdown dropdown-menu dropdown-menu-right">
																<?php
																	if($itemCreationMenu['create_edit_only'] == 1 || $itemCreationMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<?php
																			if($itemCreationMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a href="<?php echo base_url(); ?>products/ManageProducts/edit/<?php echo $row['item_id'];?>">
																						<i class="fa fa-edit"></i> Edit
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($itemCreationMenu['read_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<a title="View" href="<?php echo base_url(); ?>products/ManageProducts/view/<?php echo $row['item_id'];?>">
																						<i class="fa fa-eye"></i> View
																					</a>
																				</li>
																				<?php 
																			} 
																		?>

																		<?php
																			if($itemCreationMenu['create_edit_only'] == 1 || $this->user_id == 1)
																			{
																				?>
																				<li>
																					<?php 
																						if($row['active_flag'] == $this->active_flag)
																						{
																							?>
																							<a class="unblock" href="<?php echo base_url(); ?>products/ManageProducts/status/<?php echo $row['item_id'];?>/N" title="Active">
																								<i class="fa fa-ban"></i> Inactive
																							</a>
																							<?php 
																						} 
																						else
																						{  ?>
																							<a class="block" href="<?php echo base_url(); ?>products/ManageProducts/status/<?php echo $row['item_id'];?>/Y" title="InActive">
																								<i class="fa fa-ban"></i> Active
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
													<?php /* <td class="text-center">
														<?php
															if(!empty($row['product_image']) && file_exists("uploads/products/".$row['product_image']) )
															{
																?>
																<img src="<?php echo base_url(); ?>uploads/products/<?php echo $row['product_image'];?>" width="75" height="75">
																<?php 
															}
															else
															{
																?>
																<img src="<?php echo base_url(); ?>uploads/no-image.png" width="75" height="75">
																<?php 
															} 
														?>
													</td> */ ?>
													<td><?php echo $row['item_code'];?></td>
													<td><?php echo $row['item_description'];?></td>
													<td><?php echo $row['category_name'];?></td>

													<td><?php echo $row['uom_code'];?></td>
													<td><?php echo $row['short_code'];?></td>

													<td class="text-right">
														<?php echo number_format($row['item_cost'],DECIMAL_VALUE,'.','');?>
													</td>
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


<script>
	// select all checkbox
	$('#select_all').on('click', function(e) 
	{
		if($(this).is(':checked',true)) {
			$(".emp_checkbox").prop('checked', true);
		}
		else {
			$(".emp_checkbox").prop('checked',false);
		}
		// set all checked checkbox count
		//$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	});
	
	// set particular checked checkbox count
	/* $(".emp_checkbox").on('click', function(e) 
	{
		$("#select_count").html($("input.emp_checkbox:checked").length+" Selected");
	}); */
</script>


<script type="text/javascript">  
	$('document').ready(function()
	{
		$(".register-but-1").removeClass("disabled-class");
		
		var product_lot_state = false;

		$('#product_lot_last_number').on('input', function()
		{
			var product_lot_last_number = $('#product_lot_last_number').val();
			
			if (product_lot_last_number == '') 
			{
				product_lot_state = false;
				return;
			}
			else
			{
				$.ajax({
					url: '<?php echo base_url();?>products/productLotNoCheck',
					type: 'POST',
					data: {
						'product_lot_check' : 1,'product_lot_last_number' : product_lot_last_number,
					},
					success: function(response)
					{
						if (response == 'taken' ) 
						{
							product_lot_state = false;
							
							$(".product_lot_exist_error").addClass("error");
							$(".product_lot_exist_error").attr("id", "user_name-error");
							$(".product_lot_exist_error").attr("style", "display: inline;");
							
							$(".register-but-1").attr("disabled", "disabled=disabled");
							$(".register-but-1").addClass("disabled-class");
							$('.product_lot_exist_error').html('Sorry... Product last number already taken!');
							
							return false;
						}
						else if (response == 'not_taken') 
						{
							$(".product_lot_exist_error").attr("style", "display: none;");
							$(".register-but-1").removeAttr("disabled", "disabled=disabled");
							$(".register-but-1").removeClass("disabled-class");
							return true;
						}
					}
				});
			}
		});
		//Customer E-mail End here
	});
</script>
