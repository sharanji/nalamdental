<?php /*
<!-- Page header start-->
<div class="page-header page-header-light">
	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <?php echo get_phrase('Home');?></a>
				<a href="<?php echo base_url();?>products/assignProductLocator" class="breadcrumb-item"><?php echo $page_title;?></a>
			</div>
		</div>
		
		<?php
			if( isset($type) && $type == "add" || $type == "edit" )
			{ 
				
			}
			else
			{ 
				
				?>
				<div class="new-import-btn" style="float:right;">
					<?php 
						if($this->user_id == 1)
						{
							?>
							
							<a href="<?php echo base_url(); ?>products/assignProductLocator/add" class="btn btn-info">
								Add Product Locator
							</a>
							<?php 
						} 
					?>	
				</div>
				<?php 
			} 
		?>
	</div>
</div>
<!-- Page header end-->
*/
?>
<?php /*
<!-- Import csv start -->
<div class="modal fade" id="importcountryCSV" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Import Products Price</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?php echo base_url(); ?>products/ManageProductsPrice/import" enctype="multipart/form-data" method="post">
				<div class="modal-body">
					
					<div class="row">
						<div class="form-group col-md-9">
							<!-- <label class="col-form-label">Upload File</label> -->
							<input type="file" name="csv"  id="chooseFile" class="form-control singleDocument" onchange="return validateSingleDocumentExtension(this)" required />
							<span style="color:#a0a0a0;">Note : Upload format CSV and upload size is 5 mb.</span>
						</div>
						<div class="col-md-3">
							<a href="<?php echo base_url(); ?>assets/sample_products_price.csv" class="btn btn-info btn-flat pull-right" title="Download Sample File">
								<i class="fa fa-download"></i> Download
							</a>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 mb-3">
							<div class="well well-small">
								The correct column order is <span class="text-info"> (Product Code, Cost, Price) </span> &nbsp; &amp; You must follow this.
							</div>
						</div>
						<div class="col-md-12 pl-0">
							<span class="text-danger" style="font-size:12px !important;">Note : The first line in downloaded csv file should remain as it is. Please do not change the order of columns and Update valid data..</span><br>
						</div>
					</div>
					<script>
						
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
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary ml-1">Import</button>
				</div>
			</form>
		</div>
	</div>
</div>	
<!-- Import csv end -->
*/
?>

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "add")
				{
					?>
					<legend class="text-uppercase font-size-sm font-weight-bold">
						Assign  Product Locator
					</legend>
					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Warehouse <span class="text-danger">*</span></label>
								<select id="warehouse_id" name="warehouse_id" onchange="selectProducts(this.value);" required class="form-control searchDropdown">
									<option value="">- Select Warehouse -</option>
									<?php 
										foreach($warehouse as $key)
										{
											?>
											<option value="<?php echo $key->warehouse_id; ?>"><?php echo ucfirst($key->warehouse_name); ?></option>
											<?php 
										} 
									?>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Select Assigned Warehouse Product</label>
								<input type="text" name="product_name1" id="product_name" value="" autocomplete="off" class="form-control">
								<div id="productList"></div>
								<input type="hidden" name="product_id1" id="product_id" value="" class="form-control">
							</div>
						</div>
						
						<!-- Table start here-->
						<div class="row">
							<div class="col-md-12">
								<div style="overflow-y: auto;">
									<div id="err_product" style="color:red;margin: 0px 0px 8px 0px;"></div>
									<table class="table items table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
										<thead>
											<tr>
												<th colspan="10">Products</th>
											</tr>
											<tr>
												<th></th>
												<th class="text-center">Product Code</th>
												<th class="text-left">Product Description</th>
												<th>Sub Inventory</th>
												<th>Locators</th>
											</tr>
										</thead>
										
										<tbody id="product_table_body">
											
										</tbody>
									</table>
								</div>
								<input type="hidden" name="table_data" id="table_data">
							</div>
						</div>
						<!-- Table start here-->
						
						<div class="d-flexad text-right mt-4">
							<a href="<?php echo base_url(); ?>products/assignProductLocator" class="btn btn-light">Cancel  </a>
							<button type="submit" class="btn btn-info waves-effect ml-1">Submit</button>
						</div>
					</form>
					<script> 
						function selectProducts(val)
						{
							$('.table_rows').remove();
							$('#product_name').val("");
							$('#product_id').val("");
						}

						$(document).ready(function()
						{  
							$('#product_name').keyup(function()
							{   
								var warehouse_id = $("#warehouse_id").val();  
								
								if(warehouse_id !="")
								{
									//$("table #product_table_body tr td").remove();

									var query = $(this).val();  
								
									if(query != '')  
									{  
										$.ajax({  
											url:"<?php echo base_url();?>products/products_nameAjaxSearch",  
											method:"POST",  
											data:{query:query,warehouse_id:warehouse_id},
											success:function(data)  
											{  
												$('#productList').fadeIn();  
												$('#productList').html(data);  
											}  
										});  
									}  
								}
								else
								{
									alert("Please first select warehouse!");
									$("#product_name").val("");
								}
							});
							
							$(document).on('click', '.list-unstyled li', function()
							{  
								var value = $(this).text();
								if(value === "Sorry! Product Not Found.")
								{
									$('#product_name').val("");  
									$('#productList').fadeOut();
								}
								else
								{
									$('#product_name').val(value);  
									$('#productList').fadeOut();  
								}
							});
						}); 
						
						function getuserId(product_id)
						{	
							var warehouse_id = $("#warehouse_id").val();

							$('#product_id').val(product_id); 
							$('#product_id').val(id); 

							var id = product_id;
							var dssid = product_id; 

							$('#err_product').text('');
							var counter = 1;
							var i = 0;
							//var product_data = new Array();
							/*  
							if(id )
							{ 
							/* if(id != "")
							{  */
								$.ajax({
									url: "<?php echo base_url('products/productList') ?>/"+id+"/"+warehouse_id,
									
									type: "GET",
									data:{
										'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
									},
									datatype: "JSON",
									success: function(d)
									{
										$('#product_name').val("");
										$('#product_id').val("");

										data = JSON.parse(d);
										/* if(dssid == 0) //All Items
										{ */
											var countKey = Object.keys(data['empData']).length;
											//var flag = 0;
											if(countKey > 0)
											{
												$.each(data['empData'], function(i, item) 
												{
													var flag = 0;

													$("table.product_table").find('input[id^="product_id'+item.product_id+'"]').each(function ()
													{
														var row = $(this).closest("tr");
														var product_id = +row.find('input[id^="product_id'+item.product_id+'"]').val();

														if(item.product_id == product_id)
														{
															flag = 1; //already existing the table
														}
													});
													
													/* $("table.product_table").find('input[name^="product_id"]').each(function () 
													{
														var row = $(this).closest("tr");
														
														var product_id =$(this).closest("tr").find('input[name^="product_id[]"]').val();
														
														if( product_id == item.product_id )
														{
															flag = 1;
														}
													});  */
									
													if(flag == 0)
													{
														if( item.product_id == null ){
															var id = 0;
														}else{
															var id = item.product_id;
														}

														if( item.product_code == null ){
															var code = "";
														}else{
															var code = item.product_code;
														}
														
														if( item.product_name == null ){
															var product_name = "";
														}else{
															var product_name = item.product_name;
														}
														
														var product = { 
															"product_id" : id,
															"product_price"  : '0',
														};                  
														
														//inventory
														var select_inventory = "";
														select_inventory += '<div class="form-group">';
														select_inventory += '<select class="form-control" id="inventory_id'+id+'" onchange="selectAjaxLocators(this.value,'+id+');" name="inventory_id[]">';
														select_inventory += '<option value="">- Select -</option>';
														for(a=0;a<data['locator'].length;a++)
														{
															select_inventory += '<option value="' + data['locator'][a].inventory_id + '">' + data['locator'][a].inventory_code+' - '+data['locator'][a].inventory_name+ '</option>';
														}
														select_inventory += '</select></div>';

														//locator
														var select_locator = "";
														select_locator += '<div class="form-group">';
														select_locator += '<select class="form-control" id="locator_id'+id+'" name="locator_id[]">';
														select_locator += '<option value="">- Select -</option>';
														select_locator += '</select></div>';

														var newRow = $("<tr class='dataRowVal"+id+" table_rows'>");
														var cols = "";
														cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='product_id[]' id='product_id"+id+"' value="+id+"></td>";
														cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
														cols += "<td class='tab-medium-width text-left'>"+product_name+"</td>";
														/* cols += "<td class='tab-medium-width text-center'>"
															+"<input type='text' name='cost[]' required class='form-control' autocomplete='off' id='cost"+counter+"' value='<?php echo isset($edit_data[0]['cost']) ? $edit_data[0]['cost'] :"";?>'>"
															+"</td>"; */

														cols += '<td class="tab-medium-width text-center">'+select_inventory+'</td>';
														cols += '<td class="tab-medium-width text-center">'+select_locator+'</td>';

														counter++;
														newRow.html(cols);
														$("table.product_table").append(newRow);
														i++;
													}
													
												});
											}
											else
											{
												$('#err_product').text('No Data Found!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										//}
										/* else //Single Items
										{	
											$("table.product_table").find('input[name^="product_id"]').each(function () 
											{
												if(data['empData'][0].product_id  == +$(this).val())
												{
													flag = 1;
												}
											});
											
											if(flag == 0)
											{
												
											var id = data['empData'][0].product_id;
												var price = data['empData'][0].price;
												var cost = data['empData'][0].cost;
												var code = data['empData'][0].product_code;
												var product_name = data['empData'][0].product_name;
												
												var product = { 
													"product_id"             : id,
													"price"                  : price,
													"cost"                   : cost,
												};                  

												product_data[i] = product;
												length = product_data.length - 1 ;
									
												var newRow = $("<tr class='dataRowVal"+id+"'>");
												var cols = "";
												cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='product_id[]' value="+id+"></td>";
												cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
												cols += "<td class='tab-medium-width  text-left'>"+product_name+"</td>";
												cols += "<td class='tab-medium-width text-center'>"
													+"<input type='text' name='cost[]' required class='form-control' autocomplete='off' id='cost"+counter+"' value='<?php echo isset($edit_data[0]['cost']) ? $edit_data[0]['cost'] :"";?>'>"
													+"</td>";
												cols += "<td class='tab-medium-width text-center'>"
													+"<input type='text' name='price[]' required class='form-control' autocomplete='off' id='price"+counter+"' value='<?php echo isset($edit_data[0]['price']) ? $edit_data[0]['price'] :"";?>'>"
													+"</td>";
												
												cols += "</tr>";
												counter++;

												newRow.html(cols);
												$("table.product_table").append(newRow);
												var table_data = JSON.stringify(product_data);
												$('#table_data').val(table_data);
												i++;
											}
											else
											{
												$('#err_product').text('Item Already Added!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
												
										} */
									},
									error: function(xhr, status, error) 
									{
										$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
									}
								});
							//}
						}	

						$("table.product_table").on("click", "a.deleteRow", function (event) 
						{
							deleteRow($(this).closest("tr"));
							$(this).closest("tr").remove();
							//calculateGrandTotal();
						});

						function deleteRow(row)
						{
							var id = +row.find('input[name^="id"]').val();
							// var array_id = product_data[id].product_id;
							//product_data.splice(id, 1);
							product_data[id] = null;
							//alert(product_data);
							var table_data = JSON.stringify(product_data);
							$('#table_data').val(table_data);
						}

						function selectAjaxLocators(val,counter)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'products/selectAjaxLocators';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									$( "#locator_id"+counter ).html(msg);
								});
							}
							else 
							{ 
								alert("No locators under this sub inventory!");
							}
						}
					</script>
					<?php
				}
				else if(isset($type) && $type == "edit")
				{
					$warehouse_id = isset($edit_data[0]["warehouse_id"]) ? $edit_data[0]["warehouse_id"] : 0;
					?>
					<legend class="text-uppercase font-size-sm font-weight-bold">
						Assign  Product Locator
					</legend>

					<form action="" class="form-validate-jquery" --id="formValidation" enctype="multipart/form-data" method="post">
						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Warehouse <span class="text-danger">*</span></label>
								<select id="existing_warehouse_id" name="existing_warehouse_id" disabled onchange="selectProducts(this.value);" required class="form-control searchDropdown">
									<option value="">- Select Warehouse -</option>
									<?php 
										foreach($warehouse as $key)
										{
											$selected="";
											if(isset($edit_data[0]["warehouse_id"]) && $edit_data[0]["warehouse_id"] == $key->warehouse_id )
											{
												$selected="selected='selected'";
											}
											?>
											<option value="<?php echo $key->warehouse_id; ?>" <?php echo $selected;?>><?php echo ucfirst($key->warehouse_name); ?></option>
											<?php 
										} 
									?>
								</select>
								<input type="hidden" id="warehouse_id" name="warehouse_id" value="<?php echo $warehouse_id;?>">
							</div>
						</div>

						<div class="row">
							<div class="form-group col-md-3">
								<label class="col-form-label">Select Product</label>
								<input type="text" name="product_name1" id="product_name" value="" autocomplete="off" class="form-control">
								<div id="productList"></div>
								<input type="hidden" name="product_id1" id="product_id" value="" class="form-control">
							</div>
						</div>
						<?php
							$assignedQry = "select 
							inv_assign_product_locator_line.*,
							products.product_code,
							products.product_name,
							inv_item_locators.locator_no,
							inv_item_locators.locator_name
							
							from inv_assign_product_locator_line

							left join products on 
								products.product_id = inv_assign_product_locator_line.product_id
							
							left join inv_item_locators on 
								inv_item_locators.locator_id = inv_assign_product_locator_line.locator_id
							
							where 
								inv_assign_product_locator_line.header_id='".$id."'
						";
						$assignedProducts = $this->db->query($assignedQry)->result_array();
						
						?>
						<span>Total Product Locators : <?php echo count($assignedProducts); ?></span>
						<!-- Table start here-->
						<div class="row">
							<div class="col-md-12">
								<div style="overflow-y: auto;">
									<div id="err_product" style="color:red;margin: 0px 0px 8px 0px;"></div>
									<table class="table items --table-striped-- table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
										<thead>
											<tr>
												<th colspan="10">Products</th>
											</tr>
											<tr>
												<th></th>
												<th class="text-center">Product Code</th>
												<th>Product Description</th>
												<th>Sub Inventory</th>
												<th>Locators</th>
											</tr>
										</thead>
										
										<tbody id="product_table_body">
											<?php 
												
												
												$invQry = "select * from inv_item_sub_inventory where inventory_status = 1 and warehouse_id='".$warehouse_id."' ";
												$getInv = $this->db->query($invQry)->result_array();
					
												if(  count($assignedProducts) > 0 )
												{
													$counter=1;
													foreach($assignedProducts as $row)
													{
														$locatorQry = "select * from inv_item_locators where locator_status = 1 and inventory_id='".$row["inventory_id"]."' ";
														$getLocators = $this->db->query($locatorQry)->result_array();

														?>
														<tr class="dataRowVal<?php echo $row['line_id']; ?> table_rows">
															<td class="text-center" >
																<a onclick="deleteAssignedProducts('<?php echo $row['line_id']; ?>');" > <i class="fa fa-trash"></i> </a>
																<!-- <input type="hidden" name="id" value="0"> -->
																<input type="hidden" name="counter" value="<?php echo $counter;?>">
																<input type="hidden" name="product_id[]" id="product_id<?php echo $row["product_id"];?>" value="<?php echo $row["product_id"];?>">
																<input type="hidden" name="line_id[]" value="<?php echo $row["line_id"];?>">
															</td>
															<td class="tab-medium-width text-center"><?php echo $row["product_code"];?></td>
															<td class="tab-medium-width"><?php echo ucfirst($row["product_name"]);?></td>
															<td class="tab-medium-width text-center">
																<select name="inventory_id[]" id="inventory_id<?php echo $row["product_id"];?>" onchange="selectAjaxLocators(this.value,'<?php echo $row['product_id'];?>');" class="form-control">
																	<option value="">- Select -</option>
																	<?php 
																		foreach($getInv as $locator)
																		{
																			$selected="";
																			if($row["inventory_id"] == $locator["inventory_id"])
																			{
																				$selected="selected='selected'";
																			}	
																			?>
																			<option value="<?php echo $locator["inventory_id"];?>" <?php echo $selected;?>><?php echo $locator["inventory_code"];?> - <?php echo ucfirst($locator["inventory_name"]);?></option>
																			<?php
																		}
																	?>
																</select>
															</td>
															<td class="tab-medium-width text-center">
																<select name="locator_id[]" id="locator_id<?php echo $row["product_id"];?>"  class="form-control">
																	<option value="">- Select -</option>
																	<?php 
																		foreach($getLocators as $locator)
																		{
																			$selected="";
																			if($row["locator_id"]==$locator["locator_id"])
																			{
																				$selected="selected='selected'";
																			}	
																			?>
																			<option value="<?php echo $locator["locator_id"];?>" <?php echo $selected;?>><?php echo $locator["locator_no"];?> - <?php echo ucfirst($locator["locator_name"]);?></option>
																			<?php
																		}
																	?>
																</select>
															</td>
														</tr>
														<?php
														$counter++;
													}
												}
											?>
										</tbody>
									</table>
								</div>
								<input type="hidden" name="table_data" id="table_data">
							</div>
						</div>
						<!-- Table start here-->
						
						<div class="d-flexad text-right mt-4">
							<a href="<?php echo base_url(); ?>products/assignProductLocator" class="btn btn-light">Cancel  </a>
							<button type="submit" class="btn btn-primary waves-effect ml-1">Update</button>
						</div>
					</form>
						
					<script> 
						function deleteAssignedProducts(line_id)
						{
							$.ajax({
								url: "<?php echo base_url('products/deleteAssignProducts') ?>/"+line_id,
								type: "GET",
								data:{
									'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
								},
								success: function(returnData)
								{
									$(".dataRowVal"+line_id).remove();
								}
							});
						}

						function selectProducts(val)
						{
							$('.table_rows').remove();
							$('#product_name').val("");
							$('#product_id').val("");
						}	
														
						$(document).ready(function()
						{  
							$('#product_name').keyup(function()
							{   
								var warehouse_id = $("#warehouse_id").val();  

								if(warehouse_id !="")
								{
									//$("table #product_table_body tr td").remove();

									var query = $(this).val();  
								
									if(query != '')  
									{  
										$.ajax({  
											url:"<?php echo base_url();?>products/products_nameAjaxSearch",  
											method:"POST",  
											data:{query:query,warehouse_id:warehouse_id},
											success:function(data)  
											{  
												$('#productList').fadeIn();  
												$('#productList').html(data);  
											}  
										});  
									}  
								}
								else
								{
									alert("Please first select warehouse!");
									$("#product_name").val("");
								}
							});
							
							$(document).on('click', '.list-unstyled li', function()
							{  
								var value = $(this).text();
								if(value === "Sorry! Product Not Found.")
								{
									$('#product_name').val("");  
									$('#productList').fadeOut();
								}
								else
								{
									$('#product_name').val(value);  
									$('#productList').fadeOut();  
								}
							});
						}); 
						
						function getuserId(product_id)
						{	
							var warehouse_id = $("#warehouse_id").val();

							$('#product_id').val(product_id); 
							$('#product_id').val(id); 

							var id = product_id;
							var dssid = product_id; 

							$('#err_product').text('');
							var counter = 1;
							var i = 0;
							//var product_data = new Array();
							/*  
							if(id )
							{ 
							/* if(id != "")
							{  */
								$.ajax({
									url: "<?php echo base_url('products/productList') ?>/"+id+"/"+warehouse_id,
									
									type: "GET",
									data:{
										'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
									},
									datatype: "JSON",
									success: function(d)
									{
										$('#product_name').val("");
										$('#product_id').val("");

										data = JSON.parse(d);
										/* if(dssid == 0) //All Items
										{ */
											var countKey = Object.keys(data['empData']).length;
											//var flag = 0;
											if(countKey > 0)
											{
												$.each(data['empData'], function(i, item) 
												{
													var flag = 0;

													$("table.product_table").find('input[id^="product_id'+item.product_id+'"]').each(function ()
													{
														var row = $(this).closest("tr");
														var product_id = +row.find('input[id^="product_id'+item.product_id+'"]').val();

														if(item.product_id == product_id)
														{
															flag = 1; //already existing the table
														}
													});
													
													/* $("table.product_table").find('input[name^="product_id"]').each(function () 
													{
														var row = $(this).closest("tr");
														
														var product_id =$(this).closest("tr").find('input[name^="product_id[]"]').val();
														
														if( product_id == item.product_id )
														{
															flag = 1;
														}
													});  */
									
													if(flag == 0)
													{
														if( item.product_id == null ){
															var id = 0;
														}else{
															var id = item.product_id;
														}

														if( item.product_code == null ){
															var code = "";
														}else{
															var code = item.product_code;
														}
														
														if( item.product_name == null ){
															var product_name = "";
														}else{
															var product_name = item.product_name;
														}
														
														var product = { 
															"product_id" : id,
															"product_price"  : '0',
														};                  
														
														//inventory
														var select_inventory = "";
														select_inventory += '<div class="form-group">';
														select_inventory += '<select class="form-control" id="inventory_id'+id+'" onchange="selectAjaxLocators(this.value,'+id+');" name="inventory_id[]">';
														select_inventory += '<option value="">- Select -</option>';
														for(a=0;a<data['locator'].length;a++)
														{
															select_inventory += '<option value="' + data['locator'][a].inventory_id + '">' + data['locator'][a].inventory_code+' - '+data['locator'][a].inventory_name+ '</option>';
														}
														select_inventory += '</select></div>';

														//locator
														var select_locator = "";
														select_locator += '<div class="form-group">';
														select_locator += '<select class="form-control" id="locator_id'+id+'" name="locator_id[]">';
														select_locator += '<option value="">- Select -</option>';
														select_locator += '</select></div>';
														var newRow = $("<tr class='dataRowVal"+id+" table_rows'>");
														var cols = "";
														cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='product_id[]' id='product_id"+id+"' value="+id+"></td>";
														cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
														cols += "<td class='tab-medium-width text-left'>"+product_name+"</td>";
														/* cols += "<td class='tab-medium-width text-center'>"
															+"<input type='text' name='cost[]' required class='form-control' autocomplete='off' id='cost"+counter+"' value='<?php echo isset($edit_data[0]['cost']) ? $edit_data[0]['cost'] :"";?>'>"
															+"</td>"; */

														cols += '<td class="tab-medium-width text-center">'+select_inventory+'</td>';
														cols += '<td class="tab-medium-width text-center">'+select_locator+'</td>';

														counter++;
														newRow.html(cols);
														$("table.product_table").append(newRow);
														i++;
													}
													
												});
											}
											else
											{
												$('#err_product').text('No Data Found!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
										//}
										/* else //Single Items
										{	
											$("table.product_table").find('input[name^="product_id"]').each(function () 
											{
												if(data['empData'][0].product_id  == +$(this).val())
												{
													flag = 1;
												}
											});
											
											if(flag == 0)
											{
												
											var id = data['empData'][0].product_id;
												var price = data['empData'][0].price;
												var cost = data['empData'][0].cost;
												var code = data['empData'][0].product_code;
												var product_name = data['empData'][0].product_name;
												
												var product = { 
													"product_id"             : id,
													"price"                  : price,
													"cost"                   : cost,
												};                  

												product_data[i] = product;
												length = product_data.length - 1 ;
									
												var newRow = $("<tr class='dataRowVal"+id+"'>");
												var cols = "";
												cols += "<td class='text-center'><a class='deleteRow'> <i class='fa fa-trash'></i> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='counter' name='counter' value="+counter+"><input type='hidden' name='product_id[]' value="+id+"></td>";
												cols += "<td class='tab-medium-width text-center'>"+code+"</td>";
												cols += "<td class='tab-medium-width  text-left'>"+product_name+"</td>";
												cols += "<td class='tab-medium-width text-center'>"
													+"<input type='text' name='cost[]' required class='form-control' autocomplete='off' id='cost"+counter+"' value='<?php echo isset($edit_data[0]['cost']) ? $edit_data[0]['cost'] :"";?>'>"
													+"</td>";
												cols += "<td class='tab-medium-width text-center'>"
													+"<input type='text' name='price[]' required class='form-control' autocomplete='off' id='price"+counter+"' value='<?php echo isset($edit_data[0]['price']) ? $edit_data[0]['price'] :"";?>'>"
													+"</td>";
												
												cols += "</tr>";
												counter++;

												newRow.html(cols);
												$("table.product_table").append(newRow);
												var table_data = JSON.stringify(product_data);
												$('#table_data').val(table_data);
												i++;
											}
											else
											{
												$('#err_product').text('Item Already Added!').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
											}
												
										} */
									},
									error: function(xhr, status, error) 
									{
										$('#err_product').text('Enter Product Code / Name').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
									}
								});
							//}
						}	

						$("table.product_table").on("click", "a.deleteRow", function (event) 
						{
							deleteRow($(this).closest("tr"));
							$(this).closest("tr").remove();
							//calculateGrandTotal();
						});

						function deleteRow(row)
						{
							var id = +row.find('input[name^="id"]').val();
							// var array_id = product_data[id].product_id;
							//product_data.splice(id, 1);
							product_data[id] = null;
							//alert(product_data);
							var table_data = JSON.stringify(product_data);
							$('#table_data').val(table_data);
						}

						function selectAjaxLocators(val,counter)
						{
							if(val !='')
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'products/selectAjaxLocators';?>",
									data: { id: val }
								}).done(function( msg ) 
								{   
									$( "#locator_id"+counter ).html(msg);
								});
							}
							else 
							{ 
								alert("No locators under this sub inventory!");
							}
						}
					</script>
					<?php
				}
				else 
				{ 
					?>
					<div class="row mb-2">
						<div class="col-md-6"><?php echo $page_title;?></div>
						<div class="col-md-6 float-right text-right">
							<?php /* <a href="#" data-toggle="modal" data-target="#importcountryCSV" title="Import" class="btn btn-warning">
								<i class="icon-import"></i> Import
							</a>*/ ?>
							<a href="<?php echo base_url(); ?>products/assignProductLocator/add" class="btn btn-info btn-sm">
								Add Product Locator
							</a>
						</div>
					</div>

					<form action="" method="get">
						<div class="row">
							<div class="col-md-8">
								<?php /* <div class="row mb-2">
									<div class="col-md-4">	
										<input type="text" name="from_date" id="from_date" class="form-control" readonly value="<?php echo !empty($_GET['from_date']) ? $_GET['from_date'] :""; ?>" placeholder="From Date">
									</div>
									
									<div class="col-md-4">	
										<input type="text" name="to_date" id="to_date" class="form-control" readonly value="<?php echo !empty($_GET['to_date']) ? $_GET['to_date'] :""; ?>" placeholder="To Date">
									</div>
								</div> */ ?>
								<div class="row">
									<div class="col-md-4">	
										<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
										<p class="search-note">Note : Product Code and Product Description.</p>
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
				
					<div class="new-scroller">
						<table id="myTable" class="table table-bordered table-hover --table-striped dataTable">
							<thead>
								<tr>
									<th class="text-center">Controls</th>
									<th>Warehouse</th>
									<th class="text-center">Assign Products Count</th>
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
												<div class="dropdown controls-actions show">
													<button type="button" class="btn btn-outline-info gropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-expanded="true" style="width: 74px;">
														Action <i class="fa fa-angle-down"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-right">
														<li>
															<a title="Edit" href="<?php echo base_url(); ?>products/assignProductLocator/edit/<?php echo $row['header_id'];?>">
																<i class="fa fa-pencil"></i> Edit
															</a>
														</li>

														<li>
															<a title="Edit" href="<?php echo base_url(); ?>products/viewAssignedProducts/<?php echo $row['header_id'];?>">
																<i class="fa fa-eye"></i> View
															</a>
														</li>
														
														<li>
															<?php 
																if($row['assign_status'] == 1)
																{
																	?>
																	<a href="<?php echo base_url(); ?>products/assignProductLocator/status/<?php echo $row['header_id'];?>/0" title="Inactive">
																		<i class="fa fa-ban"></i> Inactive
																	</a>
																	<?php 
																} 
																else
																{  
																	?>
																	<a href="<?php echo base_url(); ?>products/assignProductLocator/status/<?php echo $row['header_id'];?>/1" title="Active">
																		<i class="fa fa-check"></i> Active
																	</a>
																	<?php 
																} 
															?>
														</li>
													</ul>
												</div>
											</td>

											<td class="tab-medium-width"><?php echo ucfirst($row['warehouse_name']);?></td>
											<td class="tab-medium-width text-center">
												<?php
													$assignQry = "select line_id from inv_assign_product_locator_line where header_id='".$row['header_id']."' "; 
													$getAssignProducts = $this->db->query($assignQry)->result_array();

													if(count($getAssignProducts) > 0)
													{
														$btnClass="primary";
													}else{
														$btnClass="warning";
													}	
												?>
												<a title="View" class="btn btn-outline-<?php echo $btnClass; ?> btn-sm" href="<?php echo base_url(); ?>products/viewAssignedProducts/<?php echo $row['header_id'];?>">
													Assign Products ( <?php echo count($getAssignProducts);?> )	
												</a>
											</td>
											
											<td class="text-center" style="width:15%;">
												<?php 
													if($row['assign_status'] == 1)
													{
														?>
														<span class="btn btn-outline-success btn-sm" title="Active"><i class="fa fa-check"></i> Active</span>
														<?php 
													} 
													else
													{  
														?>
														<span class="btn btn-outline-warning btn-sm" title="Inactive"><i class="fa fa-close"></i> Inactive</span>
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
						if (count($resultData) > 0) 
						{
							?>
							<div class="row">
								<div class="col-md-4 showing-count">
									Showing <?php echo $starting; ?> to <?php echo $ending; ?> of <?php echo $totalRows; ?> entries
								</div>
								<!-- pagination start here -->
								<?php
									if (isset($pagination)) 
									{
										?>	
										<div class="col-md-8" class="admin_pagination" style="float:right;padding: 0px 20px 0px 0px;"><?php foreach ($pagination as $link) {
											echo $link;
										} ?></div>
										<?php
									} ?>
								<!-- pagination end here -->
							</div>
							<?php
						} 
					?>

					<?php 
				} 
			?>
		</div>
	</div><!-- Card end-->
	<?php if(isset($type) && $type =='view'){?>
		<a href='<?php echo $_SERVER['HTTP_REFERER'];?>' class='btn btn-info' style="float:right;"><i class="icon-arrow-left16"></i> Back</a>
	<?php } ?>
</div><!-- Content end-->
	
