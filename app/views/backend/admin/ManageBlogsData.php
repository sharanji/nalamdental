<?php /*
<div class="row page-titles mx-0">
	<div class="col-sm-6 p-md-0">
		<div class="welcome-text">
			<h4>Manage Blog</h4>
		</div>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/settings" class="">Settings</a></li>
			<li class="breadcrumb-item active">
				<a href="<?php echo base_url();?>admin/manage_seo_content" class="">
					Manage Blog
				</a>
			</li>
			<?php 
				if(isset($type) && $type == "add")
				{
					?>
					<li class="breadcrumb-item active">
						<a href="#">
							<?php echo ucfirst($type);?> SEO Blog
						</a>
					</li>
					<?php 
				}	
				else if(isset($type) && $type == "edit")
				{
					?>
					<li class="breadcrumb-item active">
						<a href="#">
							<?php echo ucfirst($type);?> SEO Blog
						</a>
					</li>
					<?php
				}
			?>
		</ol>
	</div>
	<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
		<?php 
			if(isset($type) && $type == "add" || $type == "edit")
			{
				?>
				
				<?php 
			}
			else
			{
				?>
				<div class="mr-1 mb-1">
					<a href="<?php echo base_url(); ?>admin/ManageBlogsData/add" class="btn btn-primary">Add Blog</a>
				</div>
							
				<?php
			}	
		?>
		
	</div>
</div> 
*/ ?>

<?php
	if(isset($type) && ($type == "add" || $type == "edit"))
	{
		?>
		<link href="<?php echo base_url();?>assets/frontend/css/bootstrap-tokenfield.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/frontend/css/tokenfield-typeahead.min.css" rel="stylesheet">
		<script src="<?php echo base_url();?>assets/frontend/js/bootstrap-tokenfield.min.js"></script>
		
		<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
		
		<div class="row">
			<div class="col-xl-12 col-xxl-12 col-lg-12">
				<div class="card">
					<div class="card-body">
						<div class="">
							<form class="form-valide" action="#" method="post" enctype="multipart/form-data">
								<div class="form-group row">
									<label class="col-form-label col-lg-4 text-left">Blog Title <span class="text-danger">*</span></label>
									<div class="col-lg-6">
										<input type="text" name="blog_title" required class="form-control" value="<?php echo isset($edit_data[0]['blog_title'])?$edit_data[0]['blog_title']:"";?>" placeholder="">
									</div>
								</div>

								<?php 
									$category = $this->db->query("select category_id, category_name from category where category_status=1 and main_category_id !=1")->result_array();
								?>
								<div class="form-group row">
									<label class="col-form-label col-lg-4 text-left">Category <span class="text-danger">*</span></label>
									<div class="col-lg-6">
										<select name="category_id" required class="form-control">
											<option value="">Select</option>
											<?php 
												foreach($category as $cat)
												{
													$selected="";
													if( isset($edit_data[0]['category_id']) && $edit_data[0]['category_id'] == $cat['category_id'] )
													{
														$selected="selected='selected'";
													}
													?>
													<option value="<?php echo $cat['category_id'];?>" <?php echo $selected;?>><?php echo ucfirst($cat['category_name']);?></option>
													<?php 
												} 
											?>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-4 --text-left">Description <span class="text-danger">*</span></label>
									<div class="col-lg-6">
										<textarea name="blog_description" required class="form-control" rows="3" cols="5"><?php echo isset($edit_data[0]['blog_description'])?$edit_data[0]['blog_description']:"";?></textarea>
									</div>
								</div>
								<?php /*
								<div class="form-group row">
									<label class="col-form-label col-lg-4">Detail Description <span class="text-danger">*</span></label>
									<div class="col-lg-6">
										<textarea name="detail_description" required class="form-control" rows="5" cols="5"><?php echo isset($edit_data[0]['detail_description'])?$edit_data[0]['detail_description']:"";?></textarea>
									</div> 
								</div>
								*/ ?>

								<div class="form-group row">
									<label class="col-form-label col-lg-4 text-left">Tags</label>
									<div class="col-lg-6">
										<textarea name="blog_tags" id="tokenfield" required class="form-control" rows="3" cols="5"><?php echo isset($edit_data[0]['blog_tags'])?$edit_data[0]['blog_tags']:"";?></textarea>
										<p class="search-note">Note : Type any keywords then click the enter button.</p>
									</div>
								</div>

								<style>
									.tokenfield {
										height: auto;
										min-height: 68px !important;
										padding-bottom: 0;
									}
								</style>

								<div class="form-group row">
									<label class="col-form-label col-lg-4 text-left">Blog Image<span class="text-danger">*</span></label>
									<div class="col-lg-6">
										<input type="file" name="blog_image" class="form-control p-1">
										<?php
											if(isset($edit_data[0]['blog_id']))
											{
												if( file_exists( 'uploads/blogs/'.$edit_data[0]['blog_id'].".png"))
												{
													?><br>
													<img  src='<?php echo base_url();?>uploads/blogs/<?php echo $edit_data[0]['blog_id'].".png";?>' width="100" height="100">
													<?php 
												}
											}
										?>
									</div>
								</div>

								<div class="form-group float-right">
									<a href="<?php echo base_url();?>admin/ManageBlogsData" class="btn btn-light btn-sm">Cancel</a>
									<?php 
										if($type == "edit")
										{
											?>
											<button type="submit" class="btn btn-primary text-right btn-sm">Save Changes </button>
											<?php 
										}
										else
										{
											?>
											<button type="submit" class="btn btn-primary text-right btn-sm">Create </button>
											<?php 
										}
									?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			CKEDITOR.replace( 'blog_description' );
			$(document).ready(function(){
				$('#tokenfield').tokenfield();
			});
		</script>
		<?php
	}
	else 
	{
		?>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<div class="col-12">
							<div class="row">
								<div class="col-md-4 pl-0">
									<h4 class="card-title manage-page"><?php echo $page_title; ?></h4>
								</div>
								<div class="col-md-8 major-buttons">
									<div class="row float-right">
										<div class="mr-1 mb-1">
											<a href="<?php echo base_url(); ?>admin/ManageBlogsData/add" class="btn btn-primary btn-sm">Add Blog</a>
										</div>
									</div>
								</div>
							</div>
						</div>	
					</div>
					<div class="card-body">
						<form action="" method="get">
							<div class="row">
								<div class="col-md-3">
									<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search Keywords..." aria-controls="example3">
								</div>
								<div class="col-md-3 search-button">
									<button type="submit" class="btn btn-primary text-right">Search</button>
								</div>
							
								<div class="col-md-6 text-right">
									<div --class="dataTables_length" --id="example3_length">
										<label>Show 
											<div class="dropdown bootstrap-select dropup">
												<?php 
													$redirect_url = substr($_SERVER['REQUEST_URI'],'1');
												?>
												<input type="hidden" id="redirect_url" value="<?php echo $redirect_url; ?>"/>
															
												<select name="filter" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $redirect_url; ?>'">
													<?php 
														$pageLimit = isset($_SESSION['PAGE']) ? $_SESSION['PAGE'] : 10;
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
											</div> entries
										</label>
									</div>
								</div>
							</div>
						</form>
						<form action="" method="post">
							<div class="table-responsive">
								<!-- <table id="example2" class="display" style="width:100%"> -->
								<table class="table verticle-middle table-responsive-sm mt-3" style="width:100%">
								<!--<table id="example3" class="display" style="min-width: 845px">-->
									<thead>
										<tr>
											<th class="text-center">Action</th>
											<th>Blog Title</th>
											<th class="text-center">Category</th>
											<th class="text-center">Blog Image</th>
											<th class="text-center">Posted Date</th>
											<th class="text-center">Status</th>
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
														<td class="text-center">
															<div class="dropdown custom-dropdown mb-0">
																<div data-toggle="dropdown">
																	<i class="fa fa-ellipsis-v"></i>
																</div>
																<div class="dropdown-menu dropdown-menu-right">
																	<a class="dropdown-item" href="<?php echo base_url();?>admin/ManageBlogsData/edit/<?php echo $row['blog_id'];?>">
																		<i class="la la-pencil"></i> Edit
																	</a>
																	
																	<?php 
																		if($row['blog_status'] == 1)
																		{
																			?>
																			<a class="dropdown-item" href="<?php echo base_url(); ?>admin/ManageBlogsData/status/<?php echo $row['blog_id'];?>/0" title="Inactive">
																				<i class="la la-info-circle"></i> Inactive
																			</a>
																			<?php 
																		} 
																		else
																		{  ?>
																			<a class="dropdown-item" href="<?php echo base_url(); ?>admin/ManageBlogsData/status/<?php echo $row['blog_id'];?>/1" title="Active">
																				<i class="la la-info-circle"></i> Active
																			</a>
																			<?php 
																		} 
																	?>
																	<?php /*
																	<a class="dropdown-item" href="<?php echo base_url();?>admin/ManageBlogsData/delete/<?php echo $row['blog_id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?')">
																		<i class="la la-trash"></i> Delete
																	</a>
																	*/ ?>
																</div>
															</div>													
														</td>

														<td><?php echo ucfirst($row['blog_title']);?></td>

														<td class="text-center"><?php echo $row['category_name'];?></td>

														<td class="text-center">
															<?php 
																if( file_exists( 'uploads/blogs/'.$row['blog_id']).".png")
																{
																	?>
																	<img  src='<?php echo base_url();?>uploads/blogs/<?php echo $row['blog_id'].".png";?>' width="100" height="100">
																	<?php 
																}
																else
																{ 
																	?>
																	<p style="color:red;">No Image</p>
																	<?php 
																}
															?>
														</td>
														
														<td class="text-center">
															<?php echo date('d-M-Y h:i:s a',$row['posted_date']);?>
														</td>

														<td class="text-center">
															<?php
																if ($row['blog_status'] == 1) 
																{
																	?>
																	<span class="btn btn-outline-success btn-xs" title="Active">Active</span>
																	<?php
																} 
																else 
																{
																	?>
																	<span class="btn btn-outline-warning btn-xs" title="Inactive">Inactive</span>
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
												<tr>
													<td class="text-center" colspan="20">
														<img src="<?php echo base_url(); ?>uploads/no-data.png">
														<p class="admin-no-data">No data found.</p>
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
					</div>
				</div>
			</div>
		</div>
		<?php
	} 
?>
	
