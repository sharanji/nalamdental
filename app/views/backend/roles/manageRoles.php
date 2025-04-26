<?php
	$roles = accessMenu(roles);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add")
				{
					?>
					
					<?php
				}
				else if(isset($type) && $type == "edit")
				{
					?>
					
					<?php
				}
				else if(isset($type) && $type == "ManageRoleMenus")
				{
					$getMenuItems = $this->db->query("select menu_name,menu_id from org_menus where menu_status=1 and menu_layer=1 order by menu_name asc")->result_array();
					#org_roles
					$sql = "select * from org_roles where role_id = ?";
					$getRoleDetails = $this->db->query($sql,array($id))->result_array();
					?>
					<fieldset class="mb-3">
						<legend class="text-uppercase font-size-sm font-weight-bold">
							Role Details
							
						<div class="row mb-2">
							<div class="col-lg-2">Role Name</div>
							<div class="col-lg-1">:</div>
							<div class="col-lg-4">
								<?php echo ucfirst($getRoleDetails[0]['role_name']);?>
							</div>
						</div>
				
						<?php 
							if( !empty($getRoleDetails[0]['role_description']) )
							{
								?>
								<div class="row mb-2">
									<div class="col-lg-2">Role Description</div>
									<div class="col-lg-1">:</div>
									<div class="col-lg-4">
										<?php echo ucfirst(nl2br($getRoleDetails[0]['role_description']));?>
									</div>
								</div>
								<?php 
							} 
						?>
					</fieldset>
					
					<?php 
						if($roles['create_edit_only'] == 1 || $this->user_id == 1)
						{
							?>
							<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								<fieldset class="mb-3">
									<legend class="text-uppercase font-size-sm font-weight-bold">Create Menu</legend>
									<div class="form-group row">
										<div class="col-md-3">
											<label class="col-form-label">Main Menu<span class="text-danger">*</span></label>
											<select name="menu_id" required class="form-control searchDropdown">
												<option value="">- Select Main Menu -</option>
												<?php 
													foreach( $getMenuItems as $row )
													{ 
														?>
														<option value="<?php echo $row['menu_id']; ?>"><?php echo ucfirst($row['menu_name']); ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
										
										<div class="col-md-3">
											<label class="col-form-label">Menu Enabled? <span class="text-danger">*</span></label>
											<select name="menu_enabled" required  class="form-control searchDropdown">
												<option value="">- Select Menu Enabled -</option>
												<?php 
													foreach( $this->menu_enabled as $key => $value )
													{ 
														$selected="";
														if($key == '1')
														{
															$selected="selected=selected";
														}
														?>
														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
									
									<div class="form-group row">
										<div class="col-md-3">
											<label class="col-form-label">Create / Edit Only? <span class="text-danger">*</span></label>
											<select name="create_edit_only" required class="form-control searchDropdown">
												<option value="">- Select Create / Edit Only -</option>
												<?php 
													foreach( $this->read_only as $key => $value )
													{ 
														$selected="";
														if($key == '0')
														{
															$selected="selected=selected";
														}
														?>
														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
										
										<div class="col-md-3">
											<label class="col-form-label">Read Only? <span class="text-danger">*</span></label>
											<select name="read_only" required class="form-control searchDropdown">
												<option value="">- Select Read Only -</option>
												<?php 
													foreach( $this->read_only as $key => $value )
													{ 
														$selected="";
														if($key == '0')
														{
															$selected="selected=selected";
														}
														?>
														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
														<?php 
													} 
												?>
											</select>
										</div>
									</div>
									<div class="d-flex justify-content-end align-items-center">
										<input type="submit" name="Add" value="Submit" class="btn btn-primary ml-3">
									</div>
								</fieldset>
							</form>
							<hr>
							<?php 
						} 
					?>
					<br>
					<form action="" method="get">
						<section class="mt-3">
							<div class="row">
								<div class="col-md-3">	
									<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..." autocomplete="off">
									<p class="search-note" style="font-size:12px!important;color:#888888!important"><b>Note : Menu Name</b></p>
								</div>
								
								<div class="col-md-7">
									<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
								</div>
								
								<div class="col-md-2" style="float:right;">
									<?php 
										$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
									?>
									<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
									
									<div class="row">
										<div class="col-md-8">
										</div>
										<div class="col-md-4">
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
								</div>
							</div>
						</section>
					</form>
							
					<div class="table-scroll">
						<table class="table --table-striped table-hover table-bordered dt-responsive-sureshchanges nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="text-align:center;width:12%;">Controls</th>
									<th>Menu Name</th>
									<th style="text-align:center;">Menu Enabled?</th>
									<th style="text-align:center;">Create/Edit Only?</th>
									<th style="text-align:center;">Read Only?</th>
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
												<?php /* <td align="center"><?php echo $i + $firstItem ;?></td> */ ?>
												<?php 
													if($roles['create_edit_only'] == 1 || $this->user_id == 1)
													{
														?>
														<div class="dropdown" style="width: 128px;">
															<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																Action <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu dropdown-menu-right">
																<li>
																	<a title="Edit" data-target="#UpdateRoleMenusModal<?php echo $row['role_item_id'];?>" data-toggle="modal" href="javascript:void(0);" >
																		<i class="fa fa-edit"></i> Edit
																	</a>
																</li>
																<li>
																	<a href="<?php echo base_url();?>roles/manageRoles/deleteRoleMenus/<?php echo $row['role_item_id'];?>/<?php echo $row['role_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																		<i class="fa fa-trash"></i> Delete
																	</a>
																</li>
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
												<?php echo $row['menu_name']; ?>
												
												<?php 
													if($roles['create_edit_only'] == 1 || $this->user_id == 1)
													{
														?>
														<?php
															$SubMenuQuery = "select menu_id from org_menus where 
																main_menu_id = '".$row['menu_id']."'
															";
															$getSubMenu = $this->db->query($SubMenuQuery)->result_array();
															
															if(count($getSubMenu) > 0)
															{
																?><br>
																<a href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['menu_id'];?>" style="text-decoration:underline;" title="Add Sub Menu Roles">
																	Add Sub Menu Roles (<?php echo count($getSubMenu);?>)
																</a>
																<?php
															}
														?>
														<?php 
													} 
												?>
												
												<!--Popup Sub Menu Access start-->
												<div class="modal fade" id="exampleModal<?php echo $row['menu_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog modal-lg" style="max-width:690px !important;" role="document">
														<div class="modal-content">
															<div class="modal-header" --style="background:#5cbcea;color: #fff;">
																<h5 class="modal-title" id="exampleModalLabel">Sub Menu Access Roles</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															
															<form action="" method="post">
																<div class="modal-body">
																	
																	<div class="row mb-3">
																		<div class="col-md-10"></div>
																		<div class="col-md-2" style="float-right">
																			<!--<button type="submit" name="sub_menu_access" class="btn btn-primary ml-4">Update</button>-->
																		</div>
																	</div>
																	
																	<?php
																		$SubMenuQuery = "select menu_id,menu_name from org_menus where 
																			main_menu_id = '".$row['menu_id']."'
																			
																		";
																		$getSubMenu = $this->db->query($SubMenuQuery)->result_array();
																		if(count($getSubMenu)>0)
																		{
																			?>
																			<table>
																				<tbody>
																					<tr>
																						<th>Sub Menus</th>
																						<th class="text-center">Menu Enabled?</th>
																						<th class="text-center">Create / Edit Only?</th>
																						<th class="text-center">Read Only?</th>
																					</tr>
																					<?php
																						foreach($getSubMenu as $menuRow)
																						{
																							$secSubMenuQuery = "select menu_id,menu_name from org_menus where 
																								main_menu_id = '".$menuRow['menu_id']."'
																							";
																							$getSecSubMenu = $this->db->query($secSubMenuQuery)->result_array();
																							
																							$RoleSubItemsQuery = "select * from org_roles_items where 
																								role_id = '".$id."' and
																									menu_id = '".$menuRow['menu_id']."'
																							";
																							$chkRoleSubItems = $this->db->query($RoleSubItemsQuery)->result_array();
																							?>
																							<tr>
																								<td>
																									<b>
																										<?php echo ucfirst($menuRow["menu_name"]);?>
																									</b>
																								</td>
																								<td class="text-center">
																									<input type="hidden" name="menu_id[]" value="<?php echo $menuRow["menu_id"];?>">
																									<select name="menu_enabled[]" required class="form-control -searchDropdown">
																										<option value="">- Select Menu Enabled -</option>
																										<?php 
																											foreach( $this->menu_enabled as $key => $value )
																											{ 
																												$selected="";
																												if(isset($chkRoleSubItems[0]['menu_enabled']) && $chkRoleSubItems[0]['menu_enabled'] == $key)
																												{
																													$selected="selected='selected'";
																												}
																												else if(count($chkRoleSubItems) == 0 && $key == 0)
																												{
																													$selected="selected='selected'";
																												}
																												?>
																												<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																												<?php 
																											} 
																										?>
																									</select>
																								</td>
																								
																								<td class="text-center">
																									<?php 
																										if(count($getSecSubMenu) > 0)
																										{
																											echo "-";
																										}
																										else
																										{
																											?>
																											<select name="create_edit_only[]" required class="form-control -searchDropdown">
																												<option value="">- Select Create Edit Only -</option>
																												<?php 
																													foreach( $this->read_only as $key => $value )
																													{ 	
																														$selected="";
																														if(isset($chkRoleSubItems[0]['create_edit_only']) && $chkRoleSubItems[0]['create_edit_only'] == $key)
																														{
																															$selected="selected='selected'";
																														}
																														else if(count($chkRoleSubItems) == 0 && $key == 0)
																														{
																															$selected="selected=selected";
																														}
																														?>
																														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																														<?php 
																													} 
																												?>
																											</select>
																											<?php 
																										} 
																									?>
																								</td>
																								
																								<td class="text-center">
																									<?php 
																										if(count($getSecSubMenu) > 0)
																										{
																											echo "-";
																										}
																										else
																										{
																											?>
																											<select name="read_only[]" required class="form-control -searchDropdown">
																												<option value="">- Select Read Only -</option>
																												<?php 
																													foreach( $this->read_only as $key => $value )
																													{ 
																														$selected="";
																														
																														if(isset($chkRoleSubItems[0]['read_only']) && $chkRoleSubItems[0]['read_only'] == $key)
																														{
																															$selected="selected='selected'";
																														}
																														else if(count($chkRoleSubItems) == 0 && $key == 0)
																														{
																															$selected="selected=selected";
																														}
																														?>
																														<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																														<?php 
																													} 
																												?>
																											</select>
																											<?php 
																										} 
																									?>
																								</td>
																							</tr>
																							
																							<!-- Sec Menu-->
																							<?php
																								if(count($getSecSubMenu) > 0)
																								{
																									foreach($getSecSubMenu as $secMenuRow)
																									{
																										$RolesecSubItemsQuery = "select * from org_roles_items where 
																											role_id = '".$id."' and
																												menu_id = '".$secMenuRow['menu_id']."'
																										";
																										$chkRoleSecSubItems = $this->db->query($RolesecSubItemsQuery)->result_array();
																										
																										?>
																										<tr>
																											<td>
																												<?php echo ucfirst($secMenuRow["menu_name"]);?>
																											</td>
																											<td class="text-center">
																												<input type="hidden" name="sec_sub_menu_id[]" value="<?php echo $secMenuRow["menu_id"];?>">
																												<select name="sec_sub_menu_enabled[]" required class="form-control -searchDropdown">
																													<option value="">- Select Menu Enabled -</option>
																													<?php 
																														foreach( $this->menu_enabled as $key => $value )
																														{ 
																															$selected="";
																								
																															if(isset($chkRoleSecSubItems[0]['menu_enabled']) && $chkRoleSecSubItems[0]['menu_enabled'] == $key)
																															{	
																																$selected="selected='selected'";
																															}
																															else if(count($chkRoleSecSubItems) == 0 && $key == 0)
																															{
																																$selected="selected=selected";
																															}
																															?>
																															<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																															<?php 
																														} 
																													?>
																												</select>
																											</td>
																											
																											<td class="text-center">
																												<select name="sec_sub_create_edit_only[]" required class="form-control -searchDropdown">
																													<option value="">- Select Create Edit Only -</option>
																													<?php 
																														foreach( $this->read_only as $key => $value )
																														{ 
																															$selected="";
																															
																															if(isset($chkRoleSecSubItems[0]['create_edit_only']) && $chkRoleSecSubItems[0]['create_edit_only'] == $key)
																															{
																																$selected="selected='selected'";
																															}
																															else if(count($chkRoleSecSubItems) == 0 && $key == 0)
																															{
																																$selected="selected=selected";
																															}
																															?>
																															<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																															<?php 
																														} 
																													?>
																												</select>
																											</td>
																											
																											<td class="text-center">
																												<select name="sec_sub_read_only[]" required class="form-control -searchDropdown">
																													<option value="">- Select Read Only -</option>
																													<?php 
																														foreach( $this->read_only as $key => $value )
																														{ 
																															$selected="";
																															if(isset($chkRoleSecSubItems[0]['read_only']) && $chkRoleSecSubItems[0]['read_only'] == $key)
																															{
																																$selected="selected='selected'";
																															}
																															else if(count($chkRoleSecSubItems) == 0 && $key == 0)
																															{
																																$selected="selected=selected";
																															}
																															?>
																															<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																															<?php 
																														} 
																													?>
																												</select>
																											</td>
																										</tr>
																										<?php
																									}
																								}
																							?>
																							<!-- Sec Menu end-->
																							
																							<?php
																						}
																					?>
																				</tbody>
																			</table>
																			<?php
																		}
																	?>
																</div>
																
																<div class="modal-footer">
																	<button type="button" class="btn btn-default" data-dismiss="modal">Close </button>
																	<button type="submit" name="sub_menu_access" class="btn btn-primary">Save</button>
																</div>
															</form>
														</div>
													</div>
												</div>
												<!--popup  Sub Menu Access end-->
											</td>
											
											<td style="text-align:center;">
												<?php 
													if($row['menu_enabled'] == 0)
													{
														?>
														<span class="btn btn-outline-warning" title="No"><i class="fa fa-close"></i> No</span>
														<?php 
													}
													else if($row['menu_enabled'] == 1) 
													{
														?>
														<span class="btn btn-outline-success" title="Yes"><i class="fa fa-check"></i> Yes</span>
														<?php
													} 
												?>
											</td>
											
											<td style="text-align:center;">
												<?php 
													if($row['create_edit_only'] == 0)
													{
														?>
														<span class="btn btn-outline-warning" title="No"><i class="fa fa-close"></i> No</span>
														<?php 
													}
													else if($row['create_edit_only'] == 1) 
													{
														?>
														<span class="btn btn-outline-success" title="Yes"><i class="fa fa-check"></i> Yes</span>
														<?php
													} 
												?>
											</td>
											
											<td style="text-align:center;">
												<?php 
													if($row['read_only'] == 0)
													{
														?>
														<span class="btn btn-outline-warning" title="No"><i class="fa fa-close"></i> No</span>
														<?php 
													}
													else if($row['read_only'] == 1) 
													{
														?>
														<span class="btn btn-outline-success" title="Yes"><i class="fa fa-check"></i> Yes</span>
														<?php
													} 
												?>
											</td>
										</tr>
										
										<!-- Roles Menu start -->
										<div class="modal fade" id="UpdateRoleMenusModal<?php echo $row['role_item_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header" --style="background: #1a476b;color: #fff;">
														<h5 class="modal-title" id="exampleModalLabel">Edit Menu Role</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
														</button>
													</div>
													
													<form action="" method="post">
														<div class="modal-body">
															<fieldset class="mb-3">
																<div class="form-group row">
																	<div class="col-md-6">
																		<label class="col-form-label">Menus <span class="text-danger">*</span></label>
																		<input type="hidden" name="role_item_id" value="<?php echo $row['role_item_id'];?>">
																		<select name="menu_id" required class="form-control ---searchDropdown">
																			<option value="">- Select Menu -</option>
																			<?php 
																				foreach( $getMenuItems as $menu )
																				{ 
																					$selected="";
																					if($row['menu_id'] == $menu['menu_id'])
																					{
																						$selected="selected='selected'";
																					}
																					?>
																					<option value="<?php echo $menu['menu_id']; ?>" <?php echo $selected; ?>><?php echo ucfirst($menu['menu_name']); ?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</div>
																	
																	<div class="col-md-6">
																		<label class="col-form-label">Menu Enabled? <span class="text-danger">*</span></label>
																		<select name="menu_enabled" required class="form-control -searchDropdown">
																			<option value="">- Select Menu Enabled -</option>
																			<?php 
																				foreach( $this->menu_enabled as $key => $value )
																				{ 
																					$selected="";
																					
																					if($row['menu_enabled'] == $key)
																					{
																						$selected="selected='selected'";
																					}
																					/* else if($key == '1')
																					{
																						$selected="selected=selected";
																					} */
																					?>
																					<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</div>
																</div>
																
																<div class="form-group row">
																	<div class="col-md-6">
																		<label class="col-form-label">Create / Edit Only? <span class="text-danger">*</span></label>
																		<select name="create_edit_only" required class="form-control -searchDropdown">
																			<option value="">- Select Read Only -</option>
																			<?php 
																				foreach( $this->read_only as $key => $value )
																				{ 
																					$selected="";
																					
																					if($row['create_edit_only'] == $key)
																					{
																						$selected="selected='selected'";
																					}
																					/* else if($key == '0')
																					{
																						$selected="selected=selected";
																					} */
																					?>
																					<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</div>
																	
																	<div class="col-md-6">
																		<label class="col-form-label">Read Only? <span class="text-danger">*</span></label>
																		<select name="read_only" required class="form-control -searchDropdown">
																			<option value="">- Select Read Only -</option>
																			<?php 
																				foreach( $this->read_only as $key => $value )
																				{ 
																					$selected="";
																					
																					if($row['read_only'] == $key)
																					{
																						$selected="selected='selected'";
																					}
																					/* else if($key == '0')
																					{
																						$selected="selected=selected";
																					} */
																					?>
																					<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
																					<?php 
																				} 
																			?>
																		</select>
																	</div>
																</div>
															</fieldset>
														</div>
														
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
															<input type="submit" name="Update" value="Update" class="btn btn-primary register-but">
														</div>
													</form>
												</div>
											</div>
										</div>
										<!-- Roles Menu end-->
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
						if(count($resultData)>0) 
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
					
					<div class="d-flexad" style="text-align:right;">
						<a href="<?php echo base_url(); ?>roles/manageRoles" class="btn btn-info"><i class="icon-arrow-left16"></i> Back</a>
					</div>
					<?php
				}
				else
				{
					?>
					<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm" style="float:right;">
						<i class="icon-arrow-left16"></i> 
						Back
					</a>
					<div class="row mb-2">
						<div class="col-md-6 p-0">Create Role</div>
						<div class="col-md-6 float-right text-right">
							<!-- <a href="<?php echo base_url(); ?>admin/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> Back
							</a> -->
						</div>
					</div>
					<?php 
						if($roles['create_edit_only'] == 1 || $this->user_id == 1)
						{
							?>
							<form action="<?php echo base_url();?>roles/manageRoles/add" class="form-validate-jquery" enctype="multipart/form-data" method="post">
								<fieldset class="mb-3">
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Role Name <span class="text-danger">*</span></label>
										<div class="col-lg-3">
											<input type="text" name="role_name" <?php echo $this->validation;?> class="form-control" required value="" placeholder="">
										</div>
									</div>
									
									<div class="form-group row">
										<label class="col-form-label col-lg-2">Role Description</label>
										<div class="col-lg-3">
											<textarea name="role_description" <?php echo $this->validation;?> class="form-control"></textarea>
										</div>
									</div>
								</fieldset>
								
								<div class="d-flex justify-content-end align-items-center">
									<button type="submit" class="btn btn-primary ml-3">Save</button>
								</div>
							</form>
							<hr>
							<?php 
						} 
					?>
					<br>
					<form action="" method="get">
						<div class="row mt-3">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search..." autocomplete="off">
										<p class="search-note">Note : Role Name, Role Description</p>
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
									<div class="col-md-4">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
										<a href="<?php echo base_url(); ?>roles/manageRoles" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
							<?php /*<div class="col-md-4">
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
							</div> */ ?>
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
												<th>Role Name</th>
												<th>Role Description</th>
												<th style="text-align:center;">Menus</th>
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
													<tr>
														<td class="text-center">
															<?php /* <td align="center"><?php echo $i + $firstItem ;?></td> */ ?>
															<?php 
																if($roles['create_edit_only'] == 1 || $this->user_id == 1)
																{
																	?>
																	<div class="dropdown" style="width: 128px;">
																		<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																			Action <i class="fa fa-angle-down"></i>
																		</button>
																		<ul class="dropdown-menu dropdown-menu-right">
																			<li>
																				<a title="Edit" data-target="#UpdateRoleModal<?php echo $row['role_id'];?>" data-toggle="modal" href="javascript:void(0);" >
																					<i class="fa fa-edit"></i> Edit
																				</a>
																			</li>
																			
																			<li>
																				<?php 
																					if($row['active_flag'] ==  $this->active_flag)
																					{
																						?>
																						<a class="unblock" href="<?php echo base_url(); ?>roles/manageRoles/status/<?php echo $row['role_id'];?>/N" title="Inactive">
																							<i class="fa fa-ban"></i> Inactive
																						</a>
																						<?php 
																					} 
																					else
																					{  ?>
																						<a class="block" href="<?php echo base_url(); ?>roles/manageRoles/status/<?php echo $row['role_id'];?>/Y" title="Active">
																							<i class="fa fa-check"></i> Active
																						</a>
																						<?php 
																					} 
																				?>
																			<li>
																			
																			<!-- <li>
																				<a href="<?php echo base_url(); ?>roles/viewRoleDetails/<?php echo $row['role_id'];?>">
																					<i class="fa fa-eye"></i> View
																				</a>
																			</li> -->
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
														
														<td><?php echo ucfirst($row['role_name']);?></td>
														<td class="tab-full-width">	
															<?php echo ucfirst(nl2br($row['role_description']));?>
														</td>
														
														<td class="tab-full-width" style="text-align:center;">
															<?php 
																$getRoleItemsCount = $this->db->query("select role_item_id from org_roles_items where role_id ='".$row['role_id']."'")->result_array();
																if(count($getRoleItemsCount) > 0)
																{
																	$btnClass="success";
																}
																else
																{
																	$btnClass="warning";
																}
															?>
															<a href="<?php echo base_url();?>roles/manageRoles/ManageRoleMenus/<?php echo $row['role_id'];?>" title="Manage Role Menus">
																<span class="btn btn-outline-<?php echo $btnClass;?>" style="width:60%;">Role Menus (<?php echo count($getRoleItemsCount);?>)</span>
															</a>
															<a href="<?php echo base_url();?>roles/manageRoles/ManageRoleMenus/<?php echo $row['role_id'];?>" title="Manage Role Menus">
																<i class="fa fa-plus" aria-hidden="true"></i>
															</a>
														</td>
														
														<td style="text-align:center;">
															<?php 
																if($row['active_flag'] ==  $this->active_flag)
																{
																	?>
																	<span class="btn btn-sm btn-outline-success" title="Active"> Active</span>
																	<?php 
																} 
																else
																{ 
																	?>
																	<span class="btn btn-sm btn-outline-warning" title="Inactive"> Inactive</span>
																	<?php 
																} 
															?>
														</td>
													</tr>
													<!-- Roles Menu start -->
													<div class="modal fade" id="UpdateRoleModal<?php echo $row['role_id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header" --style="background: #1a476b;color: #fff;">
																	<h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																
																<form action="" method="post">
																	<div class="modal-body">
																		<fieldset class="mb-3">
																			<div class="form-group row">
																				<label class="col-form-label col-lg-3">Role Name <span class="text-danger">*</span></label>
																				<div class="col-lg-6">
																					<input type="hidden" name="role_id" value="<?php echo $row['role_id'];?>">
																					<input type="text" name="role_name" <?php echo $this->validation;?> class="form-control" required value="<?php echo $row['role_name'];?>" placeholder="">
																				</div>
																			</div>
																			
																			<div class="form-group row">
																				<label class="col-form-label col-lg-3">Role Description</label>
																				<div class="col-lg-6">
																					<textarea name="role_description" class="form-control"><?php echo $row['role_description'];?></textarea>
																				</div>
																			</div>
																		</fieldset>
																	</div>
																	
																	<div class="modal-footer">
																		<?php /* <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> */ ?>
																		<input type="submit" name="Update" value="Update" class="btn btn-primary ml-3 register-but">
																	</div>
																</form>
															</div>
														</div>
													</div>
													<!-- Roles Menu end-->
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
										<?php 
									} 
								?>
							</form>
							<?php 
						} 
					?>
					<?php 
				} 
			?>
		</div><!-- Card end-->
	</div><!-- Card end-->
</div><!-- Content end-->

