<?php
	$menus = accessMenu(menus);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> Menu</legend>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Menu Name <span class="text-danger">*</span></label>
								<div class="col-lg-3">
									<input type="text" name="menu_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['menu_name'])?$edit_data[0]['menu_name']:"";?>" placeholder="">
								</div>
							</div>
							
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Menu Description</label>
								<div class="col-lg-3">
									<textarea name="menu_description"<?php echo $this->validation;?> class="form-control"><?php echo isset($edit_data[0]['menu_description'])?$edit_data[0]['menu_description']:"";?></textarea>
								</div>
							</div>
						</fieldset>
						
						<div class="d-flex justify-content-end align-items-center">
							<a href="<?php echo base_url(); ?>menus/manageMenus" class="btn btn-default waves-effect">Close</a>
							<button type="submit" class="btn btn-primary ml-1">Save</button>
						</div>
					</form>
					<?php
				}
				else if($type == "subMenu")
				{
					$getSubMenus = "select menu_name,menu_description from org_menus 
						where 
							menu_id='".$id."'
							";
					$getSubMenus = $this->db->query($getSubMenus)->result_array();

					//and menu_layer = 1
					?>

					<div class="row mb-2">
						<div class="col-md-6"></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url(); ?>menus/manageMenus" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a>
						</div>
					</div>

					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Menu Details
						</legend>
					
						<div class="row">
							<div class="col-md-2">Menu Name</div>	
							<div class="col-md-1">:</div>	
							<div class="col-md-4"><?php echo isset($getSubMenus[0]['menu_name']) ? $getSubMenus[0]['menu_name'] : "";?></div>
						</div>
						
						<div class="row mt-2">
							<div class="col-md-2">Menu Description</div>	
							<div class="col-md-1">:</div>	
							<div class="col-md-4"><?php echo isset($getSubMenus[0]['menu_description']) ? $getSubMenus[0]['menu_description'] : "";?></div>
						</div>
					</fieldset>
					<hr>
					
					<?php
						if($menus['create_edit_only'] == 1 || $this->user_id == 1)
						{
							?>
							<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Add  <?php echo $type;?></legend>
									
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Sub Menu Name <span class="text-danger">*</span></label>
										<div class="col-lg-3">
											<input type="text" autocomplete="off" name="menu_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['menu_name'])?$edit_data[0]['menu_name']:"";?>" placeholder="">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Sub Menu Description</label>
										<div class="col-lg-3">
											<textarea name="menu_description" autocomplete="off" class="form-control"><?php echo isset($edit_data[0]['menu_description'])?$edit_data[0]['menu_description']:"";?></textarea>
										</div>
									</div>
								</fieldset>
								
								<div class="d-flex justify-content-end align-items-center">
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="update" class="btn btn-primary ml-3">Save</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" name="add" class="btn btn-primary ml-3">Save</button>
											<?php 
										}
									?>
								</div>
							</form>
							
							<hr>
							<?php 
						} 
					?>
					
					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Manage Sub Menus
						</legend>
					</fieldset>
					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<!--<p class="search-note">Note : Sub Menu Name.</p>-->
									</div>	
									<div class="col-md-4">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
								</div>
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
					</form>
					
					<form method="post">
						<div class="table-scroll">
							<table class="table --table-striped table-hover table-bordered dt-responsive-sureshchanges nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="text-align:center;width:12%;">Controls</th>
										<th>Sub Menu Name</th>
											<!--th class="text-center">Sec Sub Menu</th-->
										<th style="text-align:center;width:10%;">Status</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										if(count($resultData) > 0)
										{
											$i=0; 
											$firstItem = $first_item;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<?php /* <td align="center"><?php echo $i + $firstItem ;?></td> */ ?>
														<?php
															if($menus['create_edit_only'] == 1 || $this->user_id == 1)
															{
																?>
																<div class="dropdown" style="width: 128px;">
																	<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																		Action <i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['menu_id'];?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		
																		<li>
																			<?php 
																				if($row['menu_status'] == 1)
																				{
																					?>
																					<a class="unblock" href="<?php echo base_url(); ?>menus/manageMenus/sub_status/<?php echo $id;?>/<?php echo $row['menu_id'];?>/0" title="Block">
																						<i class="fa fa-ban"></i> Inactive
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a class="block" href="<?php echo base_url(); ?>menus/manageMenus/sub_status/<?php echo $id;?>/<?php echo $row['menu_id'];?>/1" title="Unblock">
																						<i class="fa fa-ban"></i> Active
																					</a>
																					<?php 
																				} 
																			?>
																		<li>
																	</ul>
																</div>
																<?php 
															}
															else
															{ 
																?>
																-
																<?php 
															} 
														?>
														
														
														<!-- Modal Start-->
														<div class="modal fade" id="exampleModal<?php echo $row['menu_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header" style="background: #5cbcea;color: #fff;">
																		<h5 class="modal-title" id="exampleModalLabel">Edit Sub Menus</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	
																	<form action="" method="post">
																		<div class="modal-body">
																			<div class="form-group row">
																				<label class="col-form-label col-lg-5" style="text-align: left;">Sub Menu Name <span class="text-danger">*</span></label>
																				<div class="col-lg-8">
																					<input type="text" autocomplete="off" name="menu_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo $row['menu_name'];?>" placeholder="">
																					<input type="hidden" name="menu_id" value="<?php echo $row['menu_id'];?>" >
																				</div>
																			</div>
																			
																			<div class="form-group row">
																				<label class="col-form-label col-lg-5" style="text-align: left;">Sub Menu Description</label>
																				<div class="col-lg-8">
																					<textarea name="menu_description" autocomplete="off" class="form-control"><?php echo $row['menu_description'];?></textarea>
																				</div>
																			</div>
																		</div>
																		
																		<div class="modal-footer">
																			<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
																			<button type="submit" name="update" class="btn btn-primary ml-3">Save</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<!-- Model End-->
														
													</td>
													<td><?php echo ucfirst($row['menu_name']);?><?php #echo "++ ".$row['menu_url'];?></td>


													<!--td class="text-center" style="width:18%;">
														<//?php 
															$getSubMenus = "select menu_id from org_menus 
																where 
																	main_menu_id='".$row['menu_id']."' and 
																		menu_layer = 3
																	";
															$getSubMenus = $this->db->query($getSubMenus)->result_array();
															
															if(count($getSubMenus) > 0)
															{
																$btnClass="info";
															}else{
																$btnClass="warning";
															}
														?>
														<a href="<//?php echo base_url();?>menus/manageMenus/secSubMenu/<//?php echo $id;?><//?php echo $row['menu_id'];?>" class="btn btn-outline-<//?php echo $btnClass;?> btn-sm">
															Sec Sub Menu (<//?php echo count($getSubMenus);?>)
														</a>
														<a href="#">
															<i class="fa fa-plus-circle"></i>
														</a>
														</td-->

													<td style="text-align:center;">
														<?php 
															if($row['menu_status'] == 1)
															{
																?>
																<span class="btn btn-outline-success" title="Active"> Active</span>
																<?php 
															} 
															else
															{ 
																?>
																<span class="btn btn-outline-warning" title="Inactive"> Inactive</span>
																<?php 
															} 
														?>
													</td>
												</tr>
												<?php 
												$i++;
											}
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
							if(count($resultData) >  0)
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
					</form>
					<?php 
				}
				else if($type == "secSubMenu")
				{
					$getSubMenus = "select menu_name,menu_description from org_menus 
						where 
							menu_id='".$status."' and 
								menu_layer = 2
							";
					$getSubMenus = $this->db->query($getSubMenus)->result_array();
					?>
					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Menu Details
						</legend>
					
						<div class="row">
							<div class="col-md-2">Menu Name</div>	
							<div class="col-md-1">:</div>	
							<div class="col-md-4"><?php echo $getSubMenus[0]['menu_name'];?></div>
						</div>
						
						<div class="row mt-2">
							<div class="col-md-2">Menu Description</div>	
							<div class="col-md-1">:</div>	
							<div class="col-md-4"><?php echo $getSubMenus[0]['menu_description'];?></div>
						</div>
					</fieldset>
					<hr>
					
					<?php
						if($menus['create_edit_only'] == 1 || $this->user_id == 1)
						{
							?>
							<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Add  <?php echo $type;?></legend>
									
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Sec Sub Menu Name <span class="text-danger">*</span></label>
										<div class="col-lg-3">
											<input type="text" autocomplete="off" name="menu_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo isset($edit_data[0]['menu_name'])?$edit_data[0]['menu_name']:"";?>" placeholder="">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Sec Sub Menu Description</label>
										<div class="col-lg-3">
											<textarea name="menu_description" autocomplete="off" class="form-control"><?php echo isset($edit_data[0]['menu_description'])?$edit_data[0]['menu_description']:"";?></textarea>
										</div>
									</div>
								</fieldset>
								
								<div class="d-flex justify-content-end align-items-center">
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" name="update" class="btn btn-primary ml-3">Save</button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" name="add" class="btn btn-primary ml-3">Save</button>
											<?php 
										}
									?>
								</div>
							</form>
							<hr>
							<?php 
						} 
					?>
					
					<fieldset class="mt-2">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Manage Sec Sub Menus
						</legend>
					</fieldset>
					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<!--<p class="search-note">Note : Sec Sub Menu Name.</p>-->
									</div>	
									<div class="col-md-4">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
								</div>
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
					</form>
					
					<form method="post">
						<div class="table-scroll">
							<table class="table --table-striped table-hover table-bordered dt-responsive-sureshchanges nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="text-align:center;width:12%;">Controls</th>
										<th>Sec Sub Menu Name</th>
										<!--<th class="text-center">Sec Sub Menu</th>-->
										<th style="text-align:center;width:10%;">Status</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										if(count($resultData) > 0)
										{
											$i=0; 
											$firstItem = $first_item;
											foreach($resultData as $row)
											{
												?>
												<tr>
													<td class="text-center">
														<?php /* <td align="center"><?php echo $i + $firstItem ;?></td> */ ?>
														<?php
															if($menus['create_edit_only'] == 1 || $this->user_id == 1)
															{
																?>
																<div class="dropdown" style="width: 128px;">
																	<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																		Action <i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['menu_id'];?>">
																				<i class="fa fa-edit"></i> Edit
																			</a>
																		</li>
																		
																		<li>
																			<?php 
																				if($row['menu_status'] == 1)
																				{
																					?>
																					<a class="unblock" href="<?php echo base_url(); ?>menus/manageMenus/sub_status/<?php echo $id;?>/<?php echo $row['menu_id'];?>/0" title="Block">
																						<i class="fa fa-ban"></i> Inactive
																					</a>
																					<?php 
																				} 
																				else
																				{  ?>
																					<a class="block" href="<?php echo base_url(); ?>menus/manageMenus/sub_status/<?php echo $id;?>/<?php echo $row['menu_id'];?>/1" title="Unblock">
																						<i class="fa fa-ban"></i> Active
																					</a>
																					<?php 
																				} 
																			?>
																		<li>
																	</ul>
																</div>
																<?php 
															}
															else
															{ 
																?>
																-
																<?php 
															} 
														?>
														
														
														<!-- Modal Start-->
														<div class="modal fade" id="exampleModal<?php echo $row['menu_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header" style="background: #5cbcea;color: #fff;">
																		<h5 class="modal-title" id="exampleModalLabel">Edit Sub Menus</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	
																	<form action="" method="post">
																		<div class="modal-body">
																			<div class="form-group row">
																				<label class="col-form-label col-lg-5" style="text-align: left;">Sub Menu Name <span class="text-danger">*</span></label>
																				<div class="col-lg-8">
																					<input type="text" autocomplete="off" name="menu_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo $row['menu_name'];?>" placeholder="">
																					<input type="hidden" name="menu_id" value="<?php echo $row['menu_id'];?>" >
																				</div>
																			</div>
																			
																			<div class="form-group row">
																				<label class="col-form-label col-lg-5" style="text-align: left;">Sub Menu Description</label>
																				<div class="col-lg-8">
																					<textarea name="menu_description" autocomplete="off" class="form-control"><?php echo $row['menu_description'];?></textarea>
																				</div>
																			</div>
																		</div>
																		
																		<div class="modal-footer">
																			<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
																			<button type="submit" name="update" class="btn btn-primary ml-1">Save</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
														<!-- Model End-->
														
													</td>
													<td><?php echo ucfirst($row['menu_name']);?></td>
													<?php /*
													<td class="text-center" style="width:18%;">
														<?php 
															$getSubMenus = "select menu_id from org_menus 
																where 
																	main_menu_id='".$row['menu_id']."' and 
																		menu_layer = 2
																	";
															$getSubMenus = $this->db->query($getSubMenus)->result_array();
															
															if(count($getSubMenus) > 0)
															{
																$btnClass="info";
															}else{
																$btnClass="danger";
															}
														?>
														<a href="<?php echo base_url();?>menus/manageMenus/secSubMenu/<?php echo $row['menu_id'];?>" class="btn btn-<?php echo $btnClass;?>">
															Sec Sub Menu (<?php echo count($getSubMenus);?>)
														</a>
														<a href="#">
															<i class="fa fa-plus-circle"></i>
														</a>
													</td>
													*/ ?>
													<td style="text-align:center;">
														<?php 
															if($row['menu_status'] == 1)
															{
																?>
																<span class="btn btn-outline-success" title="Active"> Active</span>
															
																<?php 
															} 
															else
															{ 
																?>
																<span class="btn btn-outline-warning" title="Inactive"> Inactive</span>
																<?php 
															} 
														?>
													</td>
												</tr>
												<?php 
												$i++;
											}
										}
										else
										{
											?>
											
											<div class="text-center">
												<img src="<?php echo base_url();?>uploads/nodata.png">
											</div>
											
											<?php 
										}
									?>
								</tbody>
							</table>
						</div>
						
						<?php
							if(count($resultData) > 0)
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
					</form>
					<?php 
				}
				else
				{
					?>
					
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<!-- <a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a> -->
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>menus/manageMenus/add" class="btn btn-info btn-sm">
								Create Main Menu
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<!--<p class="search-note">Note : Menu Name.</p>-->
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
									<div class="col-md-4">
										<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										<a href="<?php echo base_url(); ?>menus/manageMenus" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
							
							<!--<div class="col-md-4 text-right">
								<?php 
									$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
								?>
								<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
														
								<div class="filter_page">
									<label>
										<span>Show :</span> 
										<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
											<?php /*
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
											*/ ?>
										</select>
									</label>
								</div>
							</div>-->
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
					
							<form method="post">
								<div class="table-scroll">
									<table class="table --table-striped table-hover table-bordered dt-responsive-sureshchanges nowrap" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th style="text-align:center;width:12%;">Controls</th>
												<th>Menu Name</th>
												<th class="text-center">Sub Menu</th>
												<th style="text-align:center;width:10%;">Status</th>
											</tr>
										</thead>
										<tbody>
											<?php 	
												$i=0; 
												$firstItem = $first_item;
												foreach($resultData as $row)
												{
													?>
													<tr >
														<td class="text-center">
															<?php
																if($menus['create_edit_only'] == 1 || $this->user_id == 1)
																{
																	?>
																	<?php /* <td align="center"><?php echo $i + $firstItem ;?></td> */ ?>
																	<div class="dropdown" style="width: 128px;">
																		<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="false">
																			Action <i class="fa fa-angle-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li>
																				<a href="<?php echo base_url(); ?>menus/manageMenus/edit/<?php echo $row['menu_id'];?>">
																					<i class="fa fa-edit"></i> Edit
																				</a>
																			</li>
																			
																			<li>
																				<?php 
																					if($row['active_flag'] == $this->active_flag)
																					{
																						?>
																						<a class="unblock" href="<?php echo base_url(); ?>menus/manageMenus/status/<?php echo $row['menu_id'];?>/N" title="Block">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a class="block" href="<?php echo base_url(); ?>menus/manageMenus/status/<?php echo $row['menu_id'];?>/Y" title="Unblock">
																							<i class="fa fa-ban"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			<li>
																		</ul>
																	</div>
																	<?php 
																}
																else
																{
																	?>
																	-
																	<?php 
																} 
															?>
														</td>
														<td>
															<?php echo ucfirst($row['menu_name']);?>
															<?php #echo "++ ".$row['menu_url'];?>
														</td>
														<td class="text-center" style="width:20%;">
															<?php 
																$getSubMenus = "select menu_id from org_menus 
																	where 
																		main_menu_id='".$row['menu_id']."' and 
																			menu_layer = 2
																		";
																$getSubMenus = $this->db->query($getSubMenus)->result_array();
																
																if(count($getSubMenus) > 0)
																{
																	$btnClass="info";
																}
																else
																{
																	$btnClass="warning";
																}
															?>
															<a href="<?php echo base_url();?>menus/manageMenus/subMenu/<?php echo $row['menu_id'];?>" class="btn btn-outline-<?php echo $btnClass;?> btn-sm">
																Sub Menu (<?php echo count($getSubMenus);?>)
															</a>
															<a href="#">
																<i class="fa fa-plus-circle"></i>
															</a>
														</td>
														<td class="text-center">
															<?php 
																if($row['active_flag'] == $this->active_flag)
																{
																	?>
																	<span class="btn btn-outline-success" title="Active"> Active</span>
																
																	<?php 
																} 
																else
																{ 
																	?>
																	<span class="btn btn-outline-warning" title="Inactive"> Inactive</span>
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
								
								<?php
									if(count($resultData) > 0)
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
										<?php } ?>
							</form>
							<?php 
						} 
					?>

					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
</div><!-- Content end-->

