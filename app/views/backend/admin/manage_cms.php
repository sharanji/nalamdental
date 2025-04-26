
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>assets/backend/assets/css/texteditor/jquery-te-1.4.0.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/backend/assets/css/texteditor/jquery-te-1.4.0.min.js" charset="utf-8"></script>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add" || $type == "edit")
				{
					
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> CMS</legend>
							<div class="form-group row">
								<label class="col-form-label col-lg-3">CMS Title <span class="text-danger">*</span></label>
								<div class="col-lg-3">
									<input type="text" name="cms_title" class="form-control" required value="<?php echo isset($edit_data[0]['cms_title'])?$edit_data[0]['cms_title']:"";?>" placeholder="">
								</div>
							</div>
							<div class="form-group row"id="form-control1">
								<label class="col-form-label col-lg-3">CMS Description <span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<textarea name="cms_desc" rows="20" cols="20" class="form-control jqte-test" required><?php echo isset($edit_data[0]['cms_desc'])?$edit_data[0]['cms_desc']:"";?></textarea>
								</div>
							</div>
						</fieldset>
						
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8 text-right">
								<a href="<?php echo base_url(); ?>admin/manage_cms" class="btn btn-sm btn-default">Close</a>
								<?php 
									if($type == "view")
									{

									}
									else
									{
										?>
										<button type="submit" class="btn btn-primary btn-sm">Save</button>
										<?php
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
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url(); ?>admin/manage_cms/add" class="btn btn-info btn-sm">
								Create CMS
							</a>
						</div>
					</div>
					<!-- buttons end here -->

					<table class="table datatable-responsive table-hover">
						<thead>
							<tr>
								<th class="text-center">Controls</th>
								<th>CMS Title</th>
								<th class="text-center">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php 	
								$i=1;
								foreach($cms as $row)
								{
									?>
									<tr>
										<td class="text-center">
											<div class="dropdown" >
												<button type="button" class="btn btn-outline-info gropdown-toggle btn-sm"  data-toggle="dropdown" aria-expanded="false">
													Action&nbsp;<i class="fa fa-chevron-down"></i>
												</button>
												<ul class="table-dropdown dropdown-menu dropdown-menu-right">
													<li>
														<a href="<?php echo base_url(); ?>admin/manage_cms/edit/<?php echo $row['cms_id'];?>">
															<i class="fa fa-edit"></i> Edit
														</a>
													</li>
													
													<li>
														<?php 
															if($row['active_flag'] == $this->active_flag)
															{
																?>
																<a class="unblock" href="<?php echo base_url(); ?>admin/manage_cms/status/<?php echo $row['cms_id'];?>/N" title="Active">
																	<i class="fa fa-ban"></i> Inactive
																</a>
																<?php 
															} 
															else
															{  ?>
																<a class="block" href="<?php echo base_url(); ?>admin/manage_cms/status/<?php echo $row['cms_id'];?>/Y" title="InActive">
																	<i class="fa fa-ban"></i> Active
																</a>
																<?php 
															} 
														?>
													<li>
												</ul>
											</div>
										</td>
										<td><?php echo ucfirst($row['cms_title']);?></td>
										<td class="tab-mobile-width text-center">
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
				} 
			?>
		</div>
	</div>
</div>

<script>
	$('.jqte-test').jqte();
	
	// settings of status
	var jqteStatus = true;
	$(".status").click(function()
	{
		jqteStatus = jqteStatus ? false : true;
		$('.jqte-test').jqte({"status" : jqteStatus})
	});
</script>