<!--Start right side div col-md-9-->
	<div class="col-md-9 col-sm-9 col-xs-12 length-catgry">
		<form action="" method="get">
			<div class="row">
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-5">	
							<input type="search" autocomplete="off" class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
							<p class="search-note">Note : Document Name, Description.</p>
						</div>	
						<div class="col-md-5">	
							<select name="document_type" class="form-control searchDropdown">
								<option value="">- Select Document Type -</option>
								<?php 
									foreach($this->document_type as $key => $value)
									{
										$selected="";
										if(isset($_GET['document_type']) && $_GET['document_type'] == $key){
											$selected="selected=selected";
										}
										?>
										<option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $value; ?></option>
										<?php 
									} 
								?>
							</select>
						</div>	
						<div class="col-md-2">
							<button type="submit" class="btn btn-info waves-effect">Search <i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</div>
				</div>
				<div class="col-md-3 text-right">
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
						<th onclick="sortTable(1)">Document Name</th>
						<th onclick="sortTable(2)" class="text-center">Documents</th>
						<th onclick="sortTable(3)">Document Type</th>
						<th onclick="sortTable(4)">Description</th>
					</tr>
				</thead>
				<tbody>
					<?php 	
						$i=1;
						foreach($resultData as $row)
						{
							?>
							<tr>
								<td><?php echo ucfirst($row['category_name']);?></td>
								<td class="text-center" style="width:18%;">
									<?php
										if(!empty($row['image_2']) && file_exists("uploads/document_attachments/".$row['image_2']) )
										{
											?>
											<?php /* <img src="<?php echo base_url();?>uploads/document_attachments/<?php echo $document['image_2'];?>" width="100px"> */ ?>
											<a class="btn btn-warning btn-block" href="<?php echo base_url()?>uploads/document_attachments/<?php echo $row['image_2'];?>" download title="Download">
												<i class="fa fa-download"></i> Download
											</a>
											<?php
										}
										else
										{
											?>
											--
											<?php 
										} 
									?>
								</td>
								<td>
									<?php 
										foreach($this->document_type as $key => $value)
										{
											if($row['document_type'] == $key)
											{
												echo $value;
											}
										}
									?>
								</td>
								<td style="wifth:20%;">
									<?php echo ucfirst($row['description']);?>
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
					<p class="admin-no-data">No data found.</p>
					<?php 
				} 
			?>
		</div>
		
		<div class="row">
			<div class="col-md-4 mt-2">
				Showing <?php echo $starting;?> to <?php echo $ending;?> of <?php echo $totalRows;?> entries
			</div>
			<!-- pagination start here -->
			<?php 
				if( isset($pagination) )
				{
					?>	
					<div class="col-md-8" class="admin_pagination" style="float:right;"><?php foreach ($pagination as $link){echo $link;} ?></div>
					<?php
				}
			?>
			<!-- pagination end here -->
		</div>
		
	</div>
	<!--End right side div col-md-9-->