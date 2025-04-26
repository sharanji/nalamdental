<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">

			<div class="row">
				<div class="col-md-6">
					<h3><b><?php echo $page_title;?></b></h3>
				</div>
			</div>

			<!-- Filters start here -->
			<div class="search-form">
				<form action="" class="form-validate-jquery" method="get" >
					
					<div class="row">
						
						<div class="col-md-4">
							<div class="row">
								<label class="col-form-label col-md-5 text-right">Branch</label>
								<div class="form-group col-md-7">
									<?php 
										$getBranch = $this->db->query("select branch_id,branch_name from branch where active_flag='Y'")->result_array();
									?>
									<select name="branch_id" id="branch_id" class="form-control searchDropdown">
										<option value="">- Select -</option>
										<?php 
											foreach($getBranch as $row)
											{
												$selected="";
												if(isset($_GET["branch_id"]) && $_GET["branch_id"] == $row["branch_id"])
												{
													$selected="selected='selected'";
												}
												?>
												<option value="<?php echo $row["branch_id"]; ?>" <?php echo $selected; ?>><?php echo $row["branch_name"]; ?></option>
												<?php 
											} 
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> From Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="from_date" placeholder="From Date" required readonly id="from_date_1" autocomplete="off" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ""; ?>" class="form-control">
								</div>
							</div>
						</div>

						<div class="col-md-3">
							<div class="row">
								<label class="col-form-label col-md-5 text-right"><span class="text-danger">*</span> To Date</label>
								<div class="form-group col-md-7">
									<input type="text" name="to_date" placeholder="To Date" readonly required id="to_date_1" autocomplete="off" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : "" ;?>" class="form-control">
								</div>
							</div>
						</div>

						<div class="col-md-2">
							<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>
							&nbsp;<a href="<?php echo base_url(); ?>report/zReport" title="Clear" class="btn btn-default">Clear</a>
						</div>
					</div>
				</form>
			</div>
			<!-- Filters end here -->

			<?php 
				if( isset($_GET) && !empty($_GET))
				{
					?>
					<div class="row mt-3">
						<div class="col-md-6">
							<a href="<?php echo base_url().$this->redirectURL;?>&download=download" target="_blank" class="btn btn-danger btn-sm" title="Download Z - Report">
								<i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; Download Z - Report
							</a>
						</div>
					</div>
					<?php 
				} 
			?>

			<script>
				$( function() 
				{
					//var dateFormat = "dd-mm-yy",
					var dateFormat = "dd-M-yy",
					from1 = $("#from_date_1").datepicker({
						minDate: new Date(2024, 5 -1, 27),
						changeMonth   : true,
						changeYear    : true,
						yearRange     : "1950:<?php echo date('Y') + 10; ?>",
						//dateFormat: "dd-mm-yy"	
						dateFormat    : "dd-M-yy",
						maxDate       : new Date(),			
					}).on("change", function() {
						var toMinDate = getDateNew(this);
						toMinDate.setDate(toMinDate.getDateNew() + 0);
						to1.datepicker("option", "minDate", toMinDate);
					}),
					to1 = $("#to_date_1").datepicker({
						minDate: new Date(2024, 5 -1, 27),
						defaultDate  : "+1w",
						changeMonth  : true,
						dateFormat   : dateFormat,
						maxDate      : new Date(),
					})
					.on("change", function() 
					{
						/* var fromMaxDate = getDate(this);
						fromMaxDate.setDate(fromMaxDate.getDate() - 1);
						from1.datepicker("option", "maxDate", fromMaxDate); */
						//suresh new changes start here
						var fromMaxDate = getDateNew(this);
						var to_date = $("#to_date_1").val();
						fromMaxDate.setDate(to_date);
						from1.datepicker("option", "maxDate", fromMaxDate);
						//suresh new changes end here
					});
					
					function getDateNew(element) 
					{
						var date;
						try {
							date = $.datepicker.parseDate(dateFormat, element.value);
						} catch (error) {
							date = null;
						}
						return date;
					}
				});
			</script>
		</div><!-- Card body end-->
	</div><!-- Card end-->
</div><!-- Content end-->
