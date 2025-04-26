<style type="text/css">
	#printable { display: none; }

	@media print
	{
		#non-printable { display: none; }
		#printable { display: block; }
	}
	.content-address-lft{float:left;}
	.content-address-rgt{float:right;}
	p.lic_no {
	float: left;
	margin: 0px 0px 2px 30px;
	}
	.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td{    border: 1px solid #bfbebe!important;}
</style>

<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="col-md-4 d-flex import-bt-new d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>roles/manageRoles" class="breadcrumb-item">
					<?php echo $page_title;?>
				</a>
			</div>
		</div>
		<div class="new-import-btn new-button-1">
			<a href="<?php echo base_url(); ?>roles/manageRoles/edit/<?php echo $id;?>" class="btn btn-info" title="Edit">
				<i class="fa fa-pencil" aria-hidden="true"></i>
			</a>
		</div>
	</div>
</div>

	<?php 
		#org_roles
		$sql = "select * from org_roles where role_id = ?";
		$getRoleDetails = $this->db->query($sql,array($id))->result_array();
		
		#org_roles_items				
		$getRoleItems = $this->db->select('si.*,p.*,m.menu_name')
		->from('org_roles_items si')
		->join('org_roles p','si.role_id = p.role_id','left')
		->join('org_menus m','si.menu_id = m.menu_id','left')
		->where('si.role_id',$id)
		->get()
		->result();	
	?>

<div class="content"><!-- Content start-->
	<div class="card box"><!-- Card start-->
		
		<div class="card-body">
			<fieldset class="mb-3" style='margin:0px 0px 0px 10px;'>
				
				<div class="row">
					<div class="col-lg-2"><b>Role Name</b></div>
					<div class="col-lg-1">:</div>
					<div class="col-lg-4">
						<?php echo ucfirst($getRoleDetails[0]['role_name']);?>
					</div>
				</div>
				
				<?php 
					if( !empty($getRoleDetails[0]['role_description']) )
					{
						?>
						<div class="row">
							<div class="col-lg-2"><b>Role Description</b></div>
							<div class="col-lg-1">:</div>
							<div class="col-lg-4">
								<?php echo ucfirst(nl2br($getRoleDetails[0]['role_description']));?>
							</div>
						</div>
						<?php 
					} 
				?>
				
			</fieldset>
					
			<div class="row">
				<div class="col-md-12">
					<?php 
						foreach($getRoleDetails as $row)
						{ 
							?>	
							<div class='new-scroller'>
								<div class="col-md-12" style="margin-top:20px;">
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th width="12%" >Menu Name</th>
												<th width="12%" style="text-align:center;">Read Only?</th>
												<th width="10%" style="text-align:center;">Menu Enabled?</th>
											</tr>
										</thead>
										<tbody>
											<?php
												foreach ($getRoleItems as  $key) 
												{
													?>
													<tr>
														<td><?php echo $key->menu_name; ?></td>
														<td style="text-align:center;">
															<?php 
																if($key->read_only == 0)
																{
																	?>
																	<span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-close"></i> No</span>
																	<?php 
																}
																else if($key->read_only == 1) 
																{
																	?>
																	<span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Yes</span>
																	<?php
																} 
															?>
														</td>
														<td style="text-align:center;">
															<?php 
																if($key->menu_enabled == 0)
																{
																	?>
																	<span class="btn btn-outline-warning" title="Inactive"><i class="fa fa-close"></i> No</span>
																	<?php 
																}
																else if($key->menu_enabled == 1) 
																{
																	?>
																	<span class="btn btn-outline-success" title="Active"><i class="fa fa-check"></i> Yes</span>
																	<?php
																} 
															?>
														</td>
													</tr>
													<?php
												}
											?>
										</tbody>
									</table>	
								</div>
							</div>
							<?php
						} 
					?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-10">
		</div>
		<div class="col-md-2" style="text-align:right;">
			<a href="<?php echo base_url();?>roles/manageRoles" class="btn btn-primary" title="Back">
				<i class="fa fa-arrow-left" aria-hidden="true"></i> Back
			</a>
		</div>
	</div>

</div>
