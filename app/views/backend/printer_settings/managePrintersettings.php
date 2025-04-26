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
		
		<?php
			if(isset($type) && $type == "add" || $type == "edit")
			{ 
				
			}
			else
			{ 
				?>
			
				<?php 
			} 
		?>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if( isset($type) && $type == "add")
				{
					?>
					
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							<div class="row">
								
								<?php 
									$getBranches = $this->db->query("select branch_id,branch_name from branch where active_flag= 'Y'
									order by branch.branch_name asc")->result_array();
								?>
								<?php 
									$getEmp = $this->db->query("select type_id,type_name from org_print_section_types where type_status = 1
									order by org_print_section_types.type_name asc")->result_array();
								?>
								<div class="col-sm-3">
									<label class="control-label">Branch <span class="text-danger">*</span></label>
									<select name="branch_id" id="branch_id" required --onchange="selectMenu(this.value);" class="form-control searchDropdown" required > <!--selectboxit-->
										<option value="">- Select -</option>
										<?php 
											foreach($getBranches as $row)
											{ 
												$selected="";
												if( isset($data[0]['branch_id']) && $data[0]['branch_id'] == $row['branch_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']);?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<div class="col-sm-3">
									<input type="hidden" class="form-control" name="version_id" placeholder="1.0.1" value="<?php #echo $row['version_id']; ?>">
								</div>
							</div>
						</fieldset>

						<fieldset class="mb-3">
							<a href="javascript:void(0);" id="addprint" class="btn btn-primary">
								Add
							</a>
						</fieldset>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<div style="overflow-y: auto;">
										<div id="err_product" style="color:red;margin: 0px 0px 9px 0px;"></div>
										<table class="table table table-bordered product_table" name="product_data" id="product_data">
											<thead>
												<tr>
													<th colspan="20">Printers</th>
												</tr>
												<tr>
													<th></th>
													<th>Print Section</th>
													<th>Printer IP / Name</th>
													<th>Printer Count </th>
													<th class="text-center">Printer Status</th>
												</tr>
											</thead>
											<tbody id="product_table_body">
                        
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="row text-right">
							<div class="col-md-12">
								<a href="<?php echo base_url(); ?>printersettings/Manageprintersettings" class="btn btn-default">Close</a>
								<button type="submit" id="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>	
				
					<script> 
						$(document).ready(function()
						{
							var i = 0;
							var product_data = new Array();
							var counter = 1;
							
							$('#addprint').click(function()
							{
								
								var branch_id = $("#branch_id").val();
								var id = 1;
								
								$('#err_product').text('');
								$('#barcodeText').val('');
								var flag = 0;
								
								if(branch_id != "")
								{
									$.ajax({
										url: "<?php echo base_url('printersettings/getPrintsections') ?>",
										type: "GET",
										data:{
											'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
										},
										datatype: "JSON",
										success: function(d)
										{
											data = JSON.parse(d);
											$("table.product_table").find('select[name^="type_id[]"]').each(function () 
											{
												
												var row = $(this).closest("tr");
												var type_id = +row.find('select[name^="type_id[]"]').val();
												var printer_name = +row.find('input[name^="printer_name[]"]').val();
												var printer_count = +row.find('input[name^="printer_count[]"]').val();
												
												if( type_id == "" || printer_name == "" || printer_count == "" )
												{
													flag = 1;
												}
											});
											

											if(flag == 0)
											{
												var print_id ="0";
												var type_id ="0";
												var printer_name ="";
												
												var printer_count ="";

												//Printer Types
												var printer_types = "";
												printer_types += '<div class="form-group">';
												printer_types += "<select class='form-control type_id' required name='type_id[]' id='type_id"+ i +"'>";
												
												printer_types += '<option value="">- Select -</option>';
												for(a=0;a<data.length;a++)
												{
													printer_types += '<option value="' + data[a].type_id + '">' + data[a].type_name+'</option>';
												}
												printer_types += '</select></div>';

												var select_printer_status = '';
												select_printer_status += "<label class='switch'>";
												select_printer_status += "<input type='checkbox' checked name='item_status"+ i +"' id='item_status"+ i +"' class='item_status'>";
												select_printer_status += "<div class='slider round'></div></label>";
							
												var newRow = $("<tr class='dataRowVal"+id+"'>");
												var cols = "";

												cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='print_id' id='print_id"+ i +"' value="+print_id+"></td>";
												cols += '<td>'+printer_types+'</td>';
												cols += "<td>" 
															+"<input type='text' class='form-control' autocomplete='off' name='printer_name[]' id='printer_name"+ i +"' required>"
														+"</td>";
												
												cols += "<td>" 
															+"<input type='number' class='form-control' autocomplete='off' name='printer_count[]' id='printer_count"+ i +"' required>"
														+"</td>";
												cols += '<td class="text-center">'+select_printer_status+'</td>';

												cols += "</tr>";
												counter++;

												newRow.html(cols);
												$("table.product_table").append(newRow);
												i++;
											}
											else
											{
												$('#err_product').text('Please fill all the required fields').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										},
										error: function(xhr, status, error) 
										{
											$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
										}
									});
								} 
								else
								{
									$('#err_product').text('Please select Branch!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								} 
							});

							$("table.product_table").on("click", "a.deleteRow", function (event) 
							{
								deleteRow($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							});

							function deleteRow(row)
							{
								var id = +row.find('input[name^="id"]').val();
								var array_id = product_data[id].task_id;
								//product_data.splice(id, 1);
								product_data[id] = null;
								//alert(product_data);
								var table_data = JSON.stringify(product_data);
								$('#table_data').val(table_data);
							}

							$("table.product_table").on("input keyup change",'select[name^="type_id[]"]', function (event) 
							{		
								var row = $(this).closest("tr");
								var id = +row.find('input[name^="id"]').val();			
								
								var printer_type = $("#type_id"+id).val();					

								var printer_text_type = $("#print_id"+id).val();
								
								/* $("table.product_table").find('input[name^="print_id"]').each(function () 
								{
									var purchase_item_id =$(this).closest("tr").find('input[name^="print_id"]').val();
									if(purchase_item_id == printer_text_type)
									{
										alert(purchase_item_id+"="+printer_text_type);
									}
								}); */

								$("#print_id"+id).val(printer_type);
							});
						});
					</script>	
					<?php
				}
				else if(isset($type) && $type == "edit")
				{
					?>
					<form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<fieldset class="mb-3">
							
							<div class="row">
								<?php 
									$getBranches = $this->db->query("select branch.branch_id, branch.branch_name from branch 
									order by branch.branch_name asc")->result_array();
								?>
								
								<div class="col-sm-3">
									<label class="control-label">Branch <span class="text-danger">*</span></label>
									<select name="branch_id1" disabled required id="branch_id1" --onchange="selectMenu(this.value);" class="form-control searchDropdown" required > <!--selectboxit-->
										<option value="">- Select -</option>
										<?php 
											foreach($getBranches as $row)
											{ 
												$selected="disabled";
												#$selected="selected";
												if( isset($data[0]->branch_id) && $data[0]->branch_id == $row['branch_id'])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row['branch_id'];?>" <?php echo $selected;?>><?php echo ucfirst($row['branch_name']);?></option>
												<?php 
											} 
										?>
									</select>
								</div>
								<input type="hidden" id="branch_id" name="branch_id" value="<?php echo isset($data[0]->branch_id) ? $data[0]->branch_id : 0;?>">
							</div>
						</fieldset>
						
						<fieldset class="mb-3">
							<a href="javascript:void(0);" id="addprint" class="btn btn-primary">
								Add
							</a>
						</fieldset>
					
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<div style="overflow-y: auto;">
										<div id="err_product" style="color:red;margin: 0px 0px 9px 0px;"></div>
										<table class="table items --table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
											<thead>
												<tr>
													<th colspan="20">Printers</th>
												</tr>
												<tr>
													<th></th>
													<th>Print Section</th>
													<th>Printer IP / Name</th>
													<th>Printer Count</th>
													<th class="text-center">Printer Status</th>
												</tr>
											</thead>
											<tbody id="product_table_body">
											<?php
												$i=0;
												$tot=0;
												$product_data = [];
											
													$getmenus = $this->db->query("select org_print_section_types.* from org_print_section_types 
													")->result_array();
												#where type_id = '".$data[0]->type_id."' 
												foreach ($menuitems as $key) 
												{
													?>
													<tr class="dataRowVal<?php echo $key->type_id?>">
														<td class="text-center">
															<a href="javascript:void(0);" onclick="deleteBranchPrinters(<?php echo $id;?>,<?php echo $key->line_id;?>);"> 
																<i class="fa fa-trash"></i> 
															</a>
															<input type='hidden' name='id' name='id' value="<?php echo $i ?>" />
															<input type='hidden' name='line_id[]' id='line_id' value="<?php echo $key->line_id;?>" />
														</td>
														
														<td>	
															<select class="form-control " id="type_id<?php echo $i; ?>" name="type_id[]">
																<option value="">- Select -</option>
																<?php 
																	foreach($getmenus as $menus) 
																	{
																		$selected="";
																		if($key->type_id == $menus["type_id"])
																		{
																			$selected="selected='selected'";
																		}
																		?>
																		<option value='<?php echo $menus["type_id"]; ?>' <?php echo $selected;?>><?php echo $menus["type_name"]; ?></option>
																		<?php
																	} 
																?>
															</select>
														</td>
														
														<td>
															<input class="form-control" name="printer_name[]" id="printer_name<?php echo $i ?>"value="<?php echo $key->printer_name; ?>" >
														</td>
														<td>
															<input class="form-control" name="printer_count[]" id="printer_count<?php echo $i ?>" value="<?php echo $key->printer_count; ?>">
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
													$i++;
												}
											?>
											</tbody>
										</table>
									</div>
									<input type="hidden" name="table_data" id="table_data">
								</div>
							</div>
						</div>

						<div class="row text-right">
							<div class="col-md-12">
								<a href="<?php echo base_url(); ?>printersettings/ManagePrintersettings" class="btn btn-default">Cancel</a>
								<button type="submit" id="submit" class="btn btn-primary">Update</button>
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
					<script> 
						function deleteBranchPrinters(header_id,line_id)
						{
							if(line_id !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'printersettings/ajaxDeletePrinter';?>",
									data: {header_id:header_id,line_id:line_id}
								}).done(function( msg ) 
								{  
									location.reload();
								});
							}
							else 
							{ 
								
							}
						}

						$(document).ready(function()
						{
							var i = 0;
							var product_data = new Array();
							var counter = 1;
							
							$('#addprint').click(function()
							{
								$(".popup-overlay").hide();
								
								//var id = $(this).val();
								//var project_budget = $("#project_budget").val();

								var id = 1;
								
								$('#err_product').text('');
								$('#barcodeText').val('');

								var branch_id = $('#branch_id').val();
								
								var flag = 0;
								
								/* if(branch_id != "")
								{ */
									$('#err_product').text('');

									$.ajax({
										url: "<?php echo base_url('printersettings/getPrintsections') ?>",
										type: "GET",
										data:{
											'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
										},
										datatype: "JSON",
										success: function(d)
										{
											
											data = JSON.parse(d);
											$("table.product_table").find('select[name^="type_id[]"]').each(function () 
											{
												/* if(data[0].type_id  == +$(this).val())
												{
													flag = 1;
												}  */

												var row = $(this).closest("tr");
												var type_id = +row.find('select[name^="type_id[]"]').val();
												
												var printer_name = +row.find('textarea[name^="printer_name[]"]').val();
												var printer_count = +row.find('textarea[name^="printer_count[]"]').val();
												
												if( type_id == "" || printer_name == "" || printer_count == "" )
												{
													flag = 1;
												}
											});
											/* $("table.product_table").find('input[name^="task_id"]').each(function () 
											{
												if(data[0].task_id  == +$(this).val())
												{
													flag = 1;
												}
											});
												*/
											
											if(flag == 0)
											{
												var type_id ="";
												var printer_name ="";
												var printer_ip ="";
												var printer_count ="";

												var product = { 
													"menu_item_id"  : type_id,
													"printer_name"  : printer_name,
													"printer_count" : printer_count,
												};  

												product_data[i] = product;
												length = product_data.length - 1 ;

												//select_menus
												var select_menus = "";
												select_menus += '<div class="form-group">';
												select_menus += "<select class='form-control type_id' required name='type_id[]' id='type_id"+ i +"' value='"+type_id+"'>";
												
												select_menus += '<option value="">- Select -</option>';
												for(a=0;a<data.length;a++)
												{
													select_menus += '<option value="' + data[a].type_id + '">' + data[a].type_name+'</option>';
												}
												select_menus += '</select></div>';


												var select_printer_status = '';
												select_printer_status += "<label class='switch'>";
												select_printer_status += "<input type='checkbox' checked name='item_status"+ i +"' id='item_status"+ i +"' class='item_status'>";
												select_printer_status += "<div class='slider round'></div></label>";
												
												var newRow = $("<tr class='dataRowVal"+id+"'>");
												var cols = "";

												cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' value="+i+"><input type='hidden' name='line_id[]' id='line_id[]' value='0'></td>";
												
												cols += '<td>'+select_menus+'</td>';
															
												cols += "<td>" 
															+"<input class='form-control' name='printer_name[]' id='printer_name"+ i +"' required>"
														+"</td>";

												cols += "<td>" 
															+"<input class='form-control' name='printer_count[]' id='printer_count"+ i +"' required>"
														+"</td>";
												
												cols += '<td class="text-center">'+select_printer_status+'</td>';
												
												cols += "</tr>";
												
												newRow.html(cols);
												$("table.product_table").append(newRow);
												var table_data = JSON.stringify(product_data);
												$('#table_data').val(table_data);
												
												i++;
												counter++;	
											}
											else
											{
												$('#err_product').text('Please fill the all required fields').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										},
										error: function(xhr, status, error) {
											$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
										}
									});
								//}
								/* else
								{
									$('#err_product').text('Please select Project').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
								} */
							});

							$("table.product_table").on("click", "a.deleteRow", function (event) 
							{
								deleteRow($(this).closest("tr"));
								$(this).closest("tr").remove();
								calculateGrandTotal();
							});

							function deleteRow(row)
							{
								var id = +row.find('input[name^="id"]').val();
								var array_id = product_data[id].type_id;
								//product_data.splice(id, 1);
								product_data[id] = null;
								//alert(product_data);
								var table_data = JSON.stringify(product_data);
								$('#table_data').val(table_data);
							}
						});
					</script>
					<?php
				}
				else
				{ 
					?>

					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<a href="<?php echo base_url();?>setup/settings" class="btn btn-info btn-sm">
								<i class="icon-arrow-left16"></i> 
								Back
							</a>
							<a href="<?php echo base_url(); ?>printersettings/ManagePrintersettings/add" class="btn btn-info btn-sm">
								Create Branch Printer
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Branch Name, Branch Code</p>
									</div>	
									<div class="col-md-3">
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

					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<form action="" method="post">
								<div class="new-scroller">
									<table id="myTable" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th class="text-center">Controls</th>
												<th>Branch Name</th>
												<th class="text-center">Branch Code</th>
												<th class="text-center">Print Section Count</th>
												<th class="text-center">Auto Print Status</th>
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
															<!--<td style="text-align:center;"><?php echo $i + $firstItem;?></td>
															-->
															<td style="width: 12%;" class="text-center">
																<div class="dropdown" style="display: inline-block;padding-right: 10px!important;width:92px;">
																	<button type="button" class="btn btn-outline-primary gropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">
																		Action <i class="fa fa-angle-down"></i>
																	</button>
																	<ul class="dropdown-menu dropdown-menu-right">
																		<li>
																			<a title="Edit" href="<?php echo base_url(); ?>printersettings/ManagePrintersettings/edit/<?php echo $row['header_id'];?>">
																				<i class="fa fa-pencil"></i> Edit
																			</a>
																		</li>
																		<li>
																			<a title="View" href="<?php echo base_url(); ?>printersettings/viewPrintersettings/<?php echo $row['header_id'];?>">
																				<i class="fa fa-eye"></i> View
																			</a>
																		</li>
																	</ul>
																</div>
															</td>

															<td>
																<?php echo ucfirst($row['branch_name']);?>
															</td>

															<td class="text-center">
																<a title="<?php echo $row['branch_code'];?>" target="_blank" href="<?php echo base_url();?>branches/ManageBranches/view/<?php echo $row['branch_id'];?>">
																	<?php echo $row['branch_code'];?>
																</a>
															</td>		

															<td class="text-center">
																<?php 
																	$branchPrinterCountQry = "select line_id from org_print_count_line where header_id ='".$row['header_id']."' ";
																	$getBranchPrinterCount = $this->db->query($branchPrinterCountQry)->result_array();

																	if (count($getBranchPrinterCount) > 0) {
																		$btnClass = "primary";
																	}else{
																		$btnClass = "danger";
																	}
																?>
																<span class="btn btn-outline-<?php echo $btnClass;?>"><?php echo count($getBranchPrinterCount);?></span>
															</td>
															
															<td class="text-center">
																<?php 
																	if($row['active_flag'] == 'Y')
																	{
																		?>
																		<label class="switch">
																			<input type="checkbox" checked id="<?php echo $row['header_id'];?>">
																			<div class="slider round"></div>
																		</label>
																		<?php 
																	} 
																	else
																	{ 
																		?>
																		<label class="switch">
																			<input type="checkbox" id="<?php echo $row['header_id'];?>">
																			<div class="slider round"></div>
																		</label>
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
													
													<?php
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
							</form>

							<script>
								$('input[type="checkbox"]').on('click',function () 
								{
									id = $(this).attr("id");
									if($(this).is(':checked',true))
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'printersettings/managePrintersettings/status/';?>"+id+"/"+1,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg)
										});
									}
									else 
									{
										$.ajax({
											type: "get",
											url:"<?php echo base_url().'printersettings/managePrintersettings/status/';?>"+id+"/"+0,
											data: { }
										}).done(function( msg ) 
										{   
											toastr.success(msg)
										});
									}
								})
							</script>
									
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
												<div class="col-md-8" class="admin_pagination"><?php foreach ($pagination as $link){echo $link;} ?></div>
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
		</div><!-- Card end-->
	</div><!-- Content body end-->
</div><!-- Content end-->