<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex back-header-full">
			<h4>
				<i class="icon-arrow-left52 mr-2"></i> 
				<span class="font-weight-semibold"> 
					<?php
						if(isset($type) && $type == "add" || $type == "edit")
						{ 
							?>
							<?php echo get_phrase($type);?> <?php echo get_phrase('_district');?>
							<?php 
						}
						else	
						{ 
							?>
							<?php echo get_phrase('Manage');?> <?php echo get_phrase('_district');?>
							<?php 
						} 
					?>
				</span>
			</h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
		<?php 
			if( isset($this->user_id) && ($this->user_id == 1 && $this->user_type == 1) ) #Admin
			{
				if(isset($type) && $type == "")
				{ 
					?>
					<div class="col-md-9 new-location-addtop">
						<a href="<?php echo base_url(); ?>admin_location/manage_district/add" class="btn btn-info"> <?php echo get_phrase('add_district');?></a>
					</div>
					<?php 
				} 
			}
			else
			{ 
				if ( in_array("lmda", $this->permission) )
				{
					if(isset($type) && $type == "")
					{ 
						?>
						<div class="col-md-9 new-location-addtop">
							<a href="<?php echo base_url(); ?>admin_location/manage_district/add" class="btn btn-info"><i class="icon-plus-circle2"></i> <?php echo get_phrase('add_district');?></a>
						</div>
						<?php 
					} 
				} 
			} 
		?>	
		<a href="javascript:;" class="btn btn-warning" id="show-panel"><i class="icon-upload"></i> Import</a>
			
	</div>
	
	<!-- Import popup start here -->
	<script type="text/javascript">								
		$(document).keyup(function(e) 
		{
			if (e.keyCode == 27) 
			{
				$('.popup_show_user').hide();
			} 
		});		  
		$(document).ready(function()
		{
			$('a#show-panel').click(function()
			{ 
				$('#lightbox-panel').show();
			})

			$('#cancel').click(function()
			{ 
				$('#lightbox-panel').hide();
				$('#em').text(''); 
			})
		});
	</script>

	<div class="popup_show popup_show_user import-counry-popup" id="lightbox-panel" style="display:none;">
		<form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
			<div class="mail-backend-admin loc-pop-heading">
				<p class="mail-backend-admin-topic import-country-title">Import District</p>
			</div>
			<div class="mail-click-form location-pop-show">
				<div class="import-counry-file location-choose">
					<input type="file" name="file"id="import_file" required accept=".xls,.xlsx">
					<p class="note-xl">Note : Upload Excel File (.xls,.xlsx)</p>
				</div>
				<div class="admin-popup-btns export-country-buttons loc-pop-butt">
					<input type="submit" id="submit" name="import" class="btn btn-info pop-loc-button" value="Submit">
					<input class="btn btn-danger pop-loc-button" type="reset" id="cancel" value="Cancel" />
				</div>
				<div class="loc-pop-butt2">		
					<?php 
						$url = 'uploads/import/district_sample.xlsx';
						if( file_exists( $url ) )
						{
							?>
							<span class="sample-download-new">
							<a href="<?php echo base_url().$url;?>">Sample Download </a>
							</span>
							<?php 
						} 
					?>
				</div>	
			</div>		
		</form>
	</div>
	
	<!-- Import popup end here --> 

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="javascript:void(0)" class="breadcrumb-item"><?php echo get_phrase('Locations');?></a>
				<span class="breadcrumb-item active">
					<?php
						if(isset($type) && $type == "add" || $type == "edit")
						{ 
							?>
							<?php echo get_phrase($type);?> <?php echo get_phrase('_district');?>
							<?php 
						}
						else
						{ 
							?>
							<?php echo get_phrase('Manage');?> <?php echo get_phrase('_district');?>
							<?php 
						} 
					?>
				</span>
			</div>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-header header-elements-inline">
			<h5 class="card-title"></h5>
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="collapse"></a>
					<a class="list-icons-item" data-action="reload"></a>
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>
		<?php
		if(isset($type) && $type == "add" || $type == "edit")
		{
			?>
			
				<div class="card-body">
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
						<legend class="text-uppercase font-size-sm font-weight-bold"><?php echo $type;?> <?php echo get_phrase('district');?></legend>
						
						<?php
							$country = $this->db->get_where('country', array('country_status' => '1'))->result_array();
							?>
							<div class="form-group row">
								<label class="col-sm-3 control-label"><?php echo get_phrase('country_name');?> <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<select name="country_id" onchange="selectState(this.value);" class="form-control select2" required > <!--selectboxit-->
										<option value="">Select Country</option>
										  <?php 
											foreach($country as $row)
											{
												$selected="";
												if(isset($edit_data[0]['country_id']) && $edit_data[0]['country_id'] == $row['country_id']){
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['country_id'];?>" <?php echo $selected;?>><?php echo $row['country_name'];?></option>
												<?php 
											} 
										 ?>
									</select>
								</div>
							</div>
								<?php
									$state = $this->db->get_where('state', array('state_status' => '1'))->result_array();
								?>
								<div class="form-group row">
									<label class="col-sm-3 control-label"><?php echo get_phrase('state_name');?> <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<select name="state_id" id="state_id" class="form-control select2" required> <!--selectboxit-->
											<option value=""><?php echo get_phrase('first_select_country');?></option>
											  <?php 
												if($type == "edit")
												{
													foreach($state as $row)
													{
														$selected="";
														if(isset($edit_data[0]['state_id']) && $edit_data[0]['state_id'] == $row['state_id']){
															$selected="selected='selected'";
														}
														?>
														<option value="<?php echo $row['state_id'];?>" <?php echo $selected;?>><?php echo $row['state_name'];?></option>
														<?php 
													}
												}
											 ?>
										</select>
									</div>
								</div>
								
								<script type="text/javascript">  
									function selectState(val)
									{
									   if(val !='')
									   {
											$.ajax({
											  type: "POST",
											  url:"<?php echo base_url().'welcome/ajaxSelectCity';?>",
											  data: { id: val }
											}).done(function( msg ) {   
												$( "#state_id" ).html(msg);
											});
										}
										else 
										{ 
											alert("No State under this Country!");
										}
									}
								</script>
								
								<div class="form-group row">
									<label class="col-sm-3 control-label"><?php echo get_phrase('district_name');?> <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<input type="text" class="form-control" <?php echo $this->validation; ?> name="district_name" required value="<?php echo isset($edit_data[0]['district_name'])?$edit_data[0]['district_name']:"";?>">
									</div>
								</div>
								
								<div class="d-flex justify-content-end align-items-center">
									<?php 
										if($type == "edit")
										{
											?>
											<a href="<?php echo base_url(); ?>admin_location/manage_district" class="btn btn-danger"><?php echo get_phrase('Cancel');?> </a>
											<button type="submit" class="btn btn-primary ml-3"><?php echo get_phrase('Update');?> </button>
											<?php 
										}
										else
										{
											?>
											<a href="<?php echo base_url(); ?>admin_location/manage_district" class="btn btn-danger"><?php echo get_phrase('Cancel');?> </a>
											<button type="reset" class="btn btn-light ml-3" id="reset"><?php echo get_phrase('Reset');?> </button>
											<button type="submit" class="btn btn-primary ml-3"><?php echo get_phrase('Submit');?> </button>
											<?php 
										}
									?>
								</div>
					</form>
				</div>
		
			<?php
		}
		else
		{
			?>
			<form action="" method="get">
				<div class="dataTables_filter">
					<label>
						<span>Search :</span> 
						<input type="search" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search Keywords..." autocomplete="off">
					</label>
					<button type="submit" class="btn btn-info ml-3">Search <i class="fa fa-search" aria-hidden="true"></i></button>
				</div>
				
				<?php 
					$server_uri = $_SERVER['REQUEST_URI'];  		
					$newsuburl=explode("?",$server_uri);
					$redirect_url = substr($server_uri,'1');
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
			</form>
			<?php  
				if(isset($_GET) && !empty($_GET))
				{
					?>
					<!--<table class="table table-bordered datatable" id="table_export">-->
					<table id="table_export" class="table  table-bordered dt-responsive-sureshchanges nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="15"><?php echo get_phrase('s.No');?></th>
								<th><?php echo get_phrase('country_name');?></th>
								<th><?php echo get_phrase('state_name');?></th>
								<th><?php echo get_phrase('district_name');?></th>
								<th><?php echo get_phrase('controls');?></th>
							</tr>
						</thead>
						<tbody>
							<?php 	
								$i=0;
								$firstItem = $first_item;
								foreach($projects as $row)
								{
									?>
									<tr>
										<td align="center"><?php echo $i+$firstItem;?></td>
										<td><?php echo ucfirst($row['country_name']);?></td>
										<td><?php echo ucfirst($row['state_name']);?></td>
										<td><?php echo ucfirst($row['district_name']);?></td>
										
										
										<td>
										<?php 
											if( isset($this->user_id) && ($this->user_id == 1 && $this->user_type == 1) ) #Admin
											{
												?>
												<a href="<?php echo base_url(); ?>admin_location/manage_district/edit/<?php echo $row['district_id'];?>">
												<i class="icon-pencil4"></i>
												</a> &nbsp;|&nbsp;
												
												<?php 
													if($row['district_status'] == 1)
													{
														?>
														<a class="unblock" style="color:#4cec49;" href="<?php echo base_url(); ?>admin_location/manage_district/status/<?php echo $row['district_id'];?>/0" title="Block">
															<i class="icon-blocked"></i>
														</a> &nbsp; |&nbsp;
														<?php 
													} 
													else
													{  ?>
														<a class="block" style="color:red;" href="<?php echo base_url(); ?>admin_location/manage_district/status/<?php echo $row['district_id'];?>/1" title="Unblock">
															<i class="icon-blocked"></i>
														</a> &nbsp; |&nbsp;
														<?php 
													} 
												?>
												
												<?php /* <a href="<?php echo base_url();?>admin_location/manage_district/delete/<?php echo $row['district_id'];?>" onclick="return confirm('Are you sure you want to delete?')">
													<i class="icon-trash"></i>
												</a> */ ?>
												<?php 
											}
											else
											{ 
												?>
												<?php if ( in_array("lmde", $this->permission) ){?>
												<a href="<?php echo base_url(); ?>admin_location/manage_district/edit/<?php echo $row['district_id'];?>">
													<i class="icon-pencil4"></i>
												</a> &nbsp;|&nbsp;
												<?php } ?>
												
												<?php if ( in_array("lmdb", $this->permission) ){?>
												<?php 
													if($row['district_status'] == 1)
													{
														?>
														<a class="unblock" style="color:#4cec49;" href="<?php echo base_url(); ?>admin_location/manage_district/status/<?php echo $row['district_id'];?>/0" title="Block">
															<i class="icon-blocked"></i>
														</a> &nbsp; |&nbsp;
														<?php 
													} 
													else
													{  ?>
														<a class="block" style="color:red;" href="<?php echo base_url(); ?>admin_location/manage_district/status/<?php echo $row['district_id'];?>/1" title="Unblock">
															<i class="icon-blocked"></i>
														</a> &nbsp; |&nbsp;
														<?php 
													} 
												?>
												<?php } ?>
												
												<?php if ( in_array("lmdd", $this->permission) ){?>
												<?php /* <a href="<?php echo base_url();?>admin_location/manage_district/delete/<?php echo $row['district_id'];?>" onclick="return confirm('Are you sure you want to delete?')">
													<i class="icon-trash"></i>
												</a> */ ?>
												<?php } ?>
												
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
					
					<div class="row">
						<div class="col-md-4"  style="float:left;padding: 15px 0px 0px 18px;">
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
			<?php 
		} 
	?>
	</div><!-- Card end-->
</div><!-- Content end-->

