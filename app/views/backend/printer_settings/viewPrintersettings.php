<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 79px;
		height: 25px;
		top: 7px;
	}

	.switch input {display:none;}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #f51658;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 34px;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 15px;
		width: 15px;
		left: 5px;
		bottom: 5px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
		border-radius: 50%;
	}

	input:checked + .slider {
		background-color: #2ab934;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(26px);
		-ms-transform: translateX(26px);
		transform: translateX(55px);
	}

	/*------ ADDED CSS ---------*/
	.slider:after
	{
		content:'OFF';
		color: white;
		display: block;
		position: absolute;
		transform: translate(-50%,-50%);
		top: 50%;
		left: 50%;
		font-size: 8px;
		font-family: Verdana, sans-serif;
	}

	input:checked + .slider:after
	{  
		content:'ON';
	}
</style>
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>printersettings/ManagePrintersettings" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		
		<div class="text-right new-import-btn">
			<a href="<?php echo base_url(); ?>printersettings/ManagePrintersettings" class="btn btn-light">
				<i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back
			</a>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<fieldset class="menu-title">
				<legend class="text-uppercase font-size-sm font-weight-bold">
					<?php
						echo !empty($type) ? $type." Printer" : $page_title;
					?>
				</legend>
			</fieldset>

			<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
				<fieldset class="mb-3">
					
					<div class="row">
						<?php 
							$getBranches = $this->db->query("select branch.branch_id, branch.branch_name from branch 
							where branch_id='".$data[0]->branch_id."' ")->result_array();
						?>
						
						<div class="col-md-2">
							<label class="control-label">Branch Name</label>
						</div>
						<div class="col-md-1">:</div>
						<div class="col-md-3">
							<?php 
								foreach($getBranches as $row)
								{ 
									if( isset($data[0]->branch_id) && $data[0]->branch_id == $row['branch_id'])
									{
										echo ucfirst($row['branch_name']);
									}
								} 
							?>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<label class="control-label">Auto Printer Status</label>
						</div>
						<div class="col-md-1">:</div>
						<div class="col-md-3">
							<?php 
								if( isset($data[0]->branch_print_status) && $data[0]->branch_print_status == 1)
								{
									?>
									Active
									<?php
								}
								else
								{
									?>
									Inactive
									<?php
								}
							?>
						</div>
					</div>
				</fieldset>
				
				<div class="row mt-3">
					<div class="col-sm-12">
						<div class="form-group">
							<div style="overflow-y: auto;">
								<table class="table table-bordered table-condensed table-hover">
									<thead>
										<tr>
											<th colspan="20">Printers</th>
										</tr>
										<tr>
											<th>Print Section</th>
											<th>Printer IP / Name</th>
											<th class="text-center">Printer Count</th>
											<th class="text-center">Printer Status</th>
										</tr>
									</thead>
									<tbody id="product_table_body">
										<?php
											$getmenus = $this->db->query("select org_print_section_types.* from org_print_section_types")->result_array();
											foreach ($menuitems as $key) 
											{
												?>
												<tr>
													<td>	
														<?php 
															foreach($getmenus as $menus) 
															{
																if($key->type_id == $menus["type_id"])
																{
																	echo ucfirst($menus["type_name"]);
																}
															} 
														?>
													</td>
													
													<td>
														<?php echo ucfirst($key->printer_name); ?>
													</td>

													<td class="text-center">
														<?php echo ucfirst($key->printer_count); ?>
													</td>


													<td class="text-center">
														<?php 
															if($key->active_flag == 'Y')
															{
																?>
																<label class="switch">
																	<input class="printer_status" name="printer_status" type="checkbox" checked id="<?php echo $key->line_id;?>">
																	<div class="slider round"></div>
																</label>
																<?php 
															} 
															else
															{ 
																?>
																<label class="switch">
																	<input class="printer_status" name="printer_status" type="checkbox" id="<?php echo $key->line_id;?>">
																	<div class="slider round"></div>
																</label>
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
					</div>
				</div>
			</form>	

			<script>
				$('input[type="checkbox"]').on('click',function () 
				{
					var id = $(this).attr("id");
					
					if($(this).is(':checked',true))
					{
						$.ajax({
							type: "get",
							url:"<?php echo base_url().'printersettings/ajaxBranchPrinterStatus/status/';?>"+id+"/"+1,
							data: { }
						}).done(function( msg ) 
						{   
							//toastr.success(msg)
						});
					}
					else 
					{
						$.ajax({
							type: "get",
							url:"<?php echo base_url().'printersettings/ajaxBranchPrinterStatus/status/';?>"+id+"/"+0,
							data: { }
						}).done(function( msg ) 
						{   
							//toastr.success(msg)
						});
					}
				})
			</script>
		</div><!-- Card end-->
	</div><!-- Content body end-->
</div><!-- Content end-->
