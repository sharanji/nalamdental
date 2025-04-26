<style>
	table, th, td {
		border:1px solid black;
	}
	label.col-form-label {
		color: #252323 !important;
		font-weight: 400;
		font-size: 14px;
	}

	.text-left {
		text-align: center!important;
	}
</style>
<?php 
	$denominationMenu = accessMenu(denomination);
?>
<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php
				if(isset($type) && $type == "create" || $type == "edit" || $type == "view")
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

					$cash_amount = isset($edit_data[0]['cash_amount']) ? $edit_data[0]['cash_amount'] : NULL;
					$upi_amount = isset($edit_data[0]['upi_amount']) ? $edit_data[0]['upi_amount'] : NULL;
					$card_amount = isset($edit_data[0]['card_amount']) ? $edit_data[0]['card_amount'] : NULL;

					$total = $cash_amount + $cash_amount + $card_amount;


					?>
					<form action="" --class="form-validate-jquery" enctype="multipart/form-data" method="post">
						<div class="row mb-2">
							
							<div class="col-md-6">
								<h3><b><?php echo ucfirst($type);?> Denomination</b></h3>
							</div>

							<div class="col-md-6 text-right">
								<a href="<?php echo base_url(); ?>denomination/manageDenomination" title="Close" class="btn btn-default btn-sm">Close</a>
								<?php 
									if($type == "add" || $type == "edit")
									{
										?>
										<button type="submit" class="btn btn-primary ml-1">Save</button>
										<?php
									} 
								?>	
							</div>
						</div>

						<fieldset class="mb-3" <?php echo $fieldSetDisabled;?>>
							<section class="header_data">
								<div class="form-group row">
									<label class="col-form-label col-md-1 text-right">Date</label>
									<div class="col-md-2">
										<?php
											$denomination_date = isset($edit_data[0]['denomination_date']) ? date('d-M-Y',strtotime($edit_data[0]['denomination_date'])) : date('d-M-Y');
										?>
										<input type="text" name="denomination_date" readonly class="form-control" required value="<?php echo $denomination_date; ?>" placeholder="">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-md-1 text-right"><span class="text-danger">*</span> Cash</label>
									<div class="col-md-2">
										<input type="text" name="cash_amount" id="cash" class="form-control calc-input mobile_vali" required value="<?php echo number_format($cash_amount,DECIMAL_VALUE,'.',''); ?>" placeholder="">
									</div>
								</div>

								
								<div class="form-group row">
									<label class="col-form-label col-md-1 text-right"><span class="text-danger">*</span> UPI</label>
									<div class="col-md-2">
										<input type="text" name="upi_amount" id="upi" class="form-control calc-input mobile_vali" required value="<?php echo number_format($upi_amount,DECIMAL_VALUE,'.',''); ?>" placeholder="">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-md-1 text-right"><span class="text-danger">*</span> Card</label>
									<div class="col-md-2">
										<input type="text" name="card_amount" id="card" class="form-control calc-input mobile_vali" required value="<?php echo number_format($card_amount,DECIMAL_VALUE,'.',''); ?>" placeholder="">
									</div>
								</div>


								<div class="form-group row">
									<label class="col-form-label col-md-1 text-right">Total</label>
									<div class="col-md-2">
										<input type="text" name="total" id="total" readonly class="form-control" value="<?php echo $total; ?>" placeholder="">
									</div>
								</div>

								<script>
									$(document).ready(function() 
									{	
										function calculateTotal() {
											var cash = parseFloat($('#cash').val()) || 0;
											var card = parseFloat($('#card').val()) || 0;
											var upi = parseFloat($('#upi').val()) || 0;

											var total = cash + card + upi;
											$('#total').val(total.toFixed(2));
										}

										$('.calc-input').on('input', function() {
											calculateTotal();
										});

										calculateTotal();
									});
								</script>
							</section>
							<!-- header data end -->

							<!-- line data start -->
							<section class="line_data">
								<table class="table table-bordered table-hover">
									<tr>
										<th colspan='3'><h3>Grand Total</h3></th>
									</tr>
									<tr>
										<th class="text-right"><b>Currency ( <?php echo CURRENCY_SYMBOL; ?> )</b></th>
										<th><b>Currency Count</b></th>
										<th class="text-left"><b>Amount ( <?php echo CURRENCY_SYMBOL; ?> )</b></th>
									</tr>

									<?php 
										$totalAmount = 0;
										foreach ($this->denominations as $key => $value) 
										{ 
											$lineQry = "select * from pay_denomination_lines
												where 
												header_id='".$id."'
												and currency='".$key."'
												";
											$getLineData = $this->db->query($lineQry)->result_array();
											$currency = isset($getLineData[0]["currency"]) ? $getLineData[0]["currency"] : NULL;
											$currency_count = isset($getLineData[0]["currency_count"]) ? $getLineData[0]["currency_count"] :  NULL;
											
											if($currency !=NULL && $currency_count !=NULL ){
												$currency_amount = $currency * $currency_count;
											}else{
												$currency_amount = NULL;
											}

											$totalAmount += $currency_amount;
											?>
											<tr>
												<td class="text-right">
													<b><?php echo $key; ?> x</b>
													<input type="hidden" name="currency[]" value="<?php echo $key; ?>">
												</td>

												<td>
													<input type="text" name="currency_count[]" autocomplete="off" class="form-control count-input mobile_vali" value="<?php echo $currency_count;?>" placeholder="">
												</td>

												<td class="text-right">
													<input type="text" name="currency_amount[]" class="form-control amount-input" value="<?php echo number_format($currency_amount,DECIMAL_VALUE,'.',''); ?>" placeholder="" readonly>
												</td>
											</tr>
											<?php 
										} 
									?>

									<tr>
										<td></td>
										<td class="text-right"><h5><b>Total</b></h5></td>
										<td colspan='2'><input type="text" name="total[]" class="form-control total-input" value="<?php echo number_format($totalAmount,DECIMAL_VALUE,'.',''); ?>" placeholder="" readonly></td>
									</tr>
								</table>

								<script>
									$(document).ready(function() 
									{
										$('.count-input').on('input', function() 
										{
											var totalAmount = 0;
											$('table.table tr').each(function(index, row) 
											{
												var count = parseInt($(row).find('.count-input').val()) || 0;
												var denomination = parseInt($(row).find('input[name="currency[]"]').val()) || 0;
												var amount = count * denomination;
												totalAmount += amount;
												$(row).find('.amount-input').val(amount.toFixed(2));
											});

											if(totalAmount > 0){
												$('.total-input').val(totalAmount.toFixed(2));
											}
										});
									});
								</script>
							</section>
							<!-- line data end -->
						</fieldset>
						
						<script>
							/* function chkDenomination()
							{
								var val = "<?php echo $this->user_id;?>";
								$.ajax({
										type: "POST",
										url:"<?php echo base_url().'denomination/ajaxChkDenomination';?>",
										data: { id: val }
									}).done(function( msg ) 
									{   
										if(msg == "0")
										{
											alert("Please enter denomination first.!")
										}
										else
										{
											
										}	
									});
							} */
						</script>
						<div class="row">
							<div class="col-md-4"></div>
							<div class="col-md-8 text-right">
								<a href="<?php echo base_url(); ?>denomination/manageDenomination" class="btn btn-sm btn-default">Close</a>
								<?php 
									if($type == "view")
									{

									}
									else
									{
										?>
										<!-- <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="chkDenomination(this.value);">Save</a> -->
										<button type="submit" class="btn btn-primary ml-1" --onclick="chkDenomination(this.value);">Save</button>
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
					<div class="row">
						<div class="col-md-6"><h5><h3><b>Denomination</b></h3></div>
						<div class="col-md-6 float-right text-right">	
							<?php
								if($denominationMenu['create_edit_only'] == 1 || $this->user_id == 1)
								{
									?>
									<a href="<?php echo base_url(); ?>denomination/manageDenomination/create" class="btn btn-info btn-sm">
										Create Denomination
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
							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">From Date</label>
									<div class="form-group col-md-8">
										<input type="text" name="from_date" readonly id="from_date" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
									</div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="row">
									<label class="col-form-label col-md-4 text-right">To Date</label>
									<div class="form-group col-md-8">
										<input type="text" name="to_date" readonly id="to_date" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="row">
									<div class="col-md-3">
										<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
									</div>
									<div class="col-md-3">
										<a href="<?php echo base_url(); ?>denomination/manageDenomination" title="Clear" class="btn btn-default">Clear</a>
									</div>
								</div>
							</div>
						</div>
					</form>
					<!-- Filters end here -->
					
					<?php 
						if(isset($_GET) &&  !empty($_GET))
						{
							?>
							<!-- Page Item Show start -->
							<div class="row mt-3">
								<div class="col-md-10">
									<b>Currency : <?php echo CURRENCY_SYMBOL;?></b>
								</div>

								<div class="col-md-2 float-right text-right">
									<div class="filter_page-- float-right text-right">
										<span class="tbl-pagination-show">Show :</span> 
										<select name="filter" class="searchDropdown" onchange="location.href='<?php echo base_url(); ?>admin/sort_itemper_page/'+$(this).val()+'?redirect=<?php echo $this->redirectUrl; ?>'">
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
									</div>
								</div>
							</div>
						    <!-- Page Item Show start -->
						
							<!-- Table start here -->
							<div class="new-scroller mt-3">
								<table id="myTable" class="table table-bordered table-hover  --table-striped dataTable">
									<thead>
										<tr>
											<th class="text-center">Controls</th>
											<th>Cashier Name</th>
											<th>Date</th>
											<th class="text-right">Cash Amount</th>
											<th class="text-right">UPI Amount</th>
											<th class="text-right">Card Amount</th>
											<th class="text-right">Total</th>
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
													<td style="width: 8%;" class="text-center">
														<?php
															if($denominationMenu['create_edit_only'] == 1 || $denominationMenu['read_only'] == 1 || $this->user_id == 1)
															{ 
																?>
																<?php 
																	if($this->user_id == 1)
																	{
																		?>
																		<a class="mr-2" title='Edit' href="<?php echo base_url(); ?>denomination/manageDenomination/edit/<?php echo $row['header_id'];?>">
																			<i class="fa fa-edit"></i>
																		</a>
																		<?php 
																	} 
																?>
																
																<?php
																	if( $denominationMenu['read_only'] == 1 || $this->user_id == 1)
																	{ 
																		?>
																		<a title='View' href="<?php echo base_url(); ?>denomination/manageDenomination/view/<?php echo $row['header_id'];?>">
																			<i class="fa fa-eye"></i>
																		</a>
																		&nbsp;
																		<a title='Download PDF' target="_blank" href="<?php echo base_url(); ?>denomination/denominationPDF/<?php echo $row['header_id'];?>">
																			<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
																		</a>
																		<?php 
																	} 
																?>	
																<?php 
															} 
														?>

													</td>
													<td>
														<?php 
															if($row['created_by'] == 1)
															{
																?>Admin
																<?php
															}
															else
															{
																echo $row['first_name'];
															}
														?>
													</td>
													<td><?php echo date("d-M-Y",strtotime($row['denomination_date']));?></td>
													<td class="text-right"><?php echo number_format($row['cash_amount'],DECIMAL_VALUE,'.',''); ?></td>
													<td class="text-right"><?php echo number_format($row['card_amount'],DECIMAL_VALUE,'.',''); ?></td>
													<td class="text-right"><?php echo number_format($row['upi_amount'],DECIMAL_VALUE,'.',''); ?></td>
													<td class="text-right"><?php echo number_format($row['cash_amount'] + $row['card_amount'] + $row['upi_amount'],DECIMAL_VALUE,'.',''); ?></td>
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