

<div class="content"><!-- Content start-->
	<div class="card"><!-- Card start-->
		<div class="card-body">
			<?php 
				if(isset($type) && $type == "history")
				{
					?>
					<h3><b>Push Notifications</b></h3>
					
					<form action="" method="get">
						<div class="row">
							<div class="col-md-2 pb-2">
								<input type="search" autocomplete="off"<?php echo $this->validation;?> class="form-control" name="keywords" value="<?php echo !empty($_GET['keywords']) ? $_GET['keywords'] :""; ?>" placeholder="Search...">
							</div>
							<?php 
								if(isset($this->role_id) && $this->role_id == 2) 
								{
									$fromDate = date('d-M-Y');
									$toDate = date('d-M-Y');
									$readonly = 'readonly';
									?>
									<div class="col-md-2 pb-2">
										<input type="text" name="from_date" id="from_date1" class="form-control" <?php echo $readonly;?> value="<?php echo $fromDate; ?>" placeholder="From Date *" required>
									</div>
									<div class="col-md-2 pb-2">
										<input type="text" name="to_date" id="to_date1" class="form-control" <?php echo $readonly;?> value="<?php echo $toDate; ?>" placeholder="To Date *" required>
									</div>
									<?php
								}
								else
								{
									$fromDate = !empty($_GET['from_date']) ? $_GET['from_date'] :"";
									$toDate = !empty($_GET['to_date']) ? $_GET['to_date'] :"";
									$readonly = 'readonly';
									?>
									<div class="col-md-2 pb-2">
										<input type="text" name="from_date" id="from_date" class="form-control from_date" <?php echo $readonly;?> value="<?php echo $fromDate; ?>" placeholder="From Date *" required>
									</div>
									<div class="col-md-2 pb-2">
										<input type="text" name="to_date" id="to_date" class="form-control to_date" <?php echo $readonly;?> value="<?php echo $toDate; ?>" placeholder="To Date *" required>
									</div>
									<?php
								}
							?>
							
							<div class="col-md-4 -float-right -text-right">
								<button type="submit" class="btn btn-info">Search <i class="fa fa-search" aria-hidden="true"></i></button>&nbsp;
								<a href="<?php echo base_url(); ?>push_notification/managePushNotification/history" title="Clear" class="btn btn-default">Clear</a>
							</div>
						</div>

						<div class="row">
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-4">	
									</div>	
									
									<?php
										/* if($this->user_id==1) #Admin
										{
											?>
											<div class="col-md-4">	
												<?php 
													$branchQuery = "select branch.branch_name,branch.branch_id  from branch
														where 
														branch.active_flag='Y'
														order by branch.branch_name asc
														";
													$getBranch = $this->db->query($branchQuery)->result_array(); 
												?>
												<select name="branch_id" class="form-control searchDropdown">
													<option value="">- Select Branch -</option>
													<?php 
														foreach($getBranch as $Branch)
														{
															$selected="";
															if(isset($_GET['branch_id']) && ($_GET['branch_id'] == $Branch['branch_id']) )
															{
																$selected="selected='selected'";
															}
															?>
															<option value="<?php echo $Branch['branch_id']; ?>" <?php echo $selected;?>><?php echo ucfirst($Branch['branch_name']); ?></option>
															<?php 
														} 
													?>
												</select>
											</div>	
											<?php 
										}  */
									?>
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
						if(isset($_GET) && !empty($_GET) && count($resultData) > 0)
						{
							?>
							<div class="new-scroller">
								<table id="myTable" class="table table-bordered table-hover --table-striped --dataTable">
									<thead>
										<tr>
											<th class="text-center">View</th>
											<th onclick="sortTable(1)">Title</th>
											<th onclick="sortTable(1)">Message</th>
											<!-- <th onclick="sortTable(2)" class="text-center">Success</th>
											<th onclick="sortTable(2)" class="text-center">Failure</th>
											
											<th onclick="sortTable(4)">Branch</th>
											<th onclick="sortTable(5)" class="text-center">Branch Code</th> -->

											<th onclick="sortTable(3)" class="text-center">Message Date</th>
											<th onclick="sortTable(6)">Sender Name</th>
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
														<a href="javascript:void(0);" data-toggle="modal" data-target="#viewMessage<?php echo $row['notification_id'];?>"><i class="fa fa-eye"></i></a>
														<!-- Modal start -->
														<div class="modal fade" id="viewMessage<?php echo $row['notification_id'];?>" role="dialog">
															<div class="modal-dialog">
																<!-- Modal content-->
																<div class="modal-content float-left" style="text-align:left;float:left;">
																	<div class="modal-header">
																		<h4 class="modal-title">View Message</h4>
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	</div>

																	<div class="modal-body">
																		<div class="row">
																			<div class="col-md-3">Title</div>
																			<div class="col-md-1">:</div>
																			<div class="col-md-5"><?php echo ucfirst($row['title']);?></div>
																		</div>

																		<div class="row mt-2">
																			<div class="col-md-3">Message</div>
																			<div class="col-md-1">:</div>
																			<div class="col-md-5"><?php echo ucfirst($row['message']);?></div>
																		</div>

																		<div class="row mt-2">
																			<div class="col-md-3">Message Date</div>
																			<div class="col-md-1">:</div>
																			<div class="col-md-5"><?php echo date("d-M-Y h:i a",$row['notification_str_date']);?></div>
																		</div>


																		<?php 
																			if(!empty($row['branch_code']))
																			{
																				?>
																				<div class="row mt-2">
																					<div class="col-md-3">Branch</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-5"><?php echo ucfirst($row['branch_name']);?></div>
																				</div>
																				<?php 
																			} 
																		?>

																		<?php 
																			if(!empty($row['branch_code']))
																			{
																				?>
																				<div class="row mt-2">
																					<div class="col-md-3">Branch Code</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-5"><?php echo $row['branch_code'];?></div>
																				</div>
																				<?php 
																			} 
																		?>

																		<div class="row mt-2">
																			<div class="col-md-3">Sender Name</div>
																			<div class="col-md-1">:</div>
																			<div class="col-md-5">
																				<?php 
																					echo ucfirst($row['first_name']);
																					if($row['user_id'] == 1)
																					{
																						?>
																						<span class="text-success">(Admin)</span>
																						<?php
																					}
																				?>
																			</div>
																		</div>
																		
																		<div class="mt-2">
																			<span>Message Response</span>
																		</div>

																		<?php 
																			if(!empty($row['message_response']))
																			{
																				$unserialize = unserialize($row['message_response']);
																				
																				$message_response = json_decode($unserialize);
																				?>
																				<div class="row mt-2">
																					<div class="col-md-3">Multicast ID</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-8">
																						<?php 
																							echo isset($message_response->multicast_id) ? $message_response->multicast_id : "--";
																						?>
																					</div>
																				</div>

																				<div class="row mt-2">
																					<div class="col-md-3">Success Message</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-8">
																						<?php 
																							echo isset($message_response->success) ? $message_response->success :"";
																						?>
																					</div>
																				</div>

																				<div class="row mt-2">
																					<div class="col-md-3">Failure Message</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-8">
																						<?php 
																							echo isset($message_response->failure) ? $message_response->failure :"";
																						?>
																					</div>
																				</div>

																				<?php /* <div class="row mt-2">
																					<div class="col-md-3">Message Staus ( Failure )</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-8">
																						<?php 
																							echo $message_response->failure;
																						?>
																					</div>
																				</div>*/ ?>
																				
																				<?php /*
																				<div class="row mt-2">
																					<div class="col-md-3">Message ID</div>
																					<div class="col-md-1">:</div>
																					<div class="col-md-8">
																						<?php 
																							if(isset($message_response->results) && !empty($message_response->results))
																							{
																								$results = $message_response->results;
																								foreach($results as $res)
																								{
																									echo isset($res->message_id)?$res->message_id:$res->message_id."<br>";
																								}
																								//echo isset($results[0]->message_id) ? $results[0]->message_id :"--";
																							}
																						?>
																					</div>
																				</div> */ ?>
																				<?php
																			}
																		?>
																	</div>

																	<!-- <div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
																	</div> -->
																</div>
															</div>
														</div>
														<!-- Modal end -->
													</td>

													<td class="tab-full-width">
														<?php echo ucfirst($row['title']);?>
													</td>

													<td class="tab-full-width">
														<?php echo ucfirst($row['message']);?>
													</td>

													<?php /* <td class="tab-medium-width text-center">
														<?php 
															echo $row['success_message'];
														?>
													</td>

													<td class="tab-medium-width text-center">
														<?php 
															echo $row['failure_message'];
														?>
													</td>

													<td class="tab-full-width text-center">
														<?php echo date("d-M-Y h:i a",$row['notification_str_date']);?>
													</td>

													<td class="tab-medium-width">
														<?php echo ucfirst($row['branch_name']);?>
													</td>

													<td class="tab-medium-width text-center">
														<?php echo $row['branch_code'];?>
													</td> */ ?>

													<td class="tab-full-width text-center">
														<?php echo date("d-M-Y h:i a",$row['notification_str_date']);?>
													</td>

													<td class="tab-full-width">
														<?php 
															echo ucfirst($row['first_name']);
															if($row['user_id'] == 1)
															{
																?>
																<span class="text-success">(Admin)</span>
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
					<?php
				}
				else
				{
					?>
					<div class="row mb-2">
						<div class="col-md-6">
							<h3><b>Push Notifications</b></h3>
						</div>
						
						<div class="col-md-6 text-right">
							<a href="<?php echo base_url(); ?>push_notification/managePushNotification/history" target="_blank" class="btn btn-info">
								History
							</a>
						</div>	
					</div>	
					<!-- <form action="" class="form-validate-jquery" enctype="multipart/form-data" method="post"> -->
						
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="form-group col-md-8">
									<label class="col-form-label">Title <span class="text-danger">*</span></label>
									<textarea name="title" id="title" rows="2" class="form-control" required placeholder="Enter title ..."></textarea>
									<span id="message_title" style="color:red;"></span>
								</div>

								<!-- <div class="form-group col-md-8">
									<label class="col-form-label">Sub Title </label>
									<textarea name="sub_title" id="sub_title" rows="1" class="form-control" placeholder="Enter sub title ..."></textarea>
									<span id="message_sub_title" style="color:red;"></span>
								</div> -->

								<div class="form-group col-md-8">
									<label class="col-form-label">Message <span class="text-danger">*</span></label>
									<textarea name="message" id="message" rows="2" class="form-control" required placeholder="Enter message ..."></textarea>
									<span id="message_error" style="color:red;"></span>
								</div>
								<div class="form-group col-md-4 mt-4" style="position: relative;top: 11px;">
									<input id="submit" type="button" class="btn btn-info" value="Send">
								</div>
							</div>
						</div>

						<div class="col-md-6 notifications-list">
							<?php
								$page_data = array();
								echo $this->load->view('backend/push_notification/pushNotificationList',$page_data,true);
							?>
						</div>
					</div>	
					<!-- </form>	 -->	

					<script>
						$(document).ready(function()
						{
							$("#submit").click(function()
							{
								var message = $("#message").val();
								var title = $("#title").val();
								
								if(message =='' || title =="")
								{
									if(message =='')
									{
										$("#message_error").html("This field is required.");
									}

									if(title =='')
									{
										$("#message_title").html("This field is required.");
									}
								}
								else
								{
									$.ajax({
										type: "POST",
										url:"<?php echo base_url().'push_notification/ajaxPushNotification';?>",
										data: { title:title,message: message }
									}).done(function( msg ) 
									{   
										var pushNotification = JSON.parse(msg); 
										var insertId =  pushNotification.insertId;
										$("#message").val("");
										$("#title").val("");
										$("#message_error").html("");
										$("#message_title").html("");
										sentFirbase(title,message,insertId);
										toastr.success("Message send successfully!");
									});
								}
								return false;
							});

							//Send Firebase
							function sentFirbase(title,message,insertId)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'push_notification/ajaxSentFirbase';?>",
									data: { title:title, message: message, insertId:insertId }
								}).done(function( msg ) 
								{   
									//console.log(msg);
									updateResponseMessageData(msg,insertId);
								});
							}

							//Update Message Response status
							function updateResponseMessageData(response,insertId)
							{
								$.ajax({
									type: "POST",
									url:"<?php echo base_url().'push_notification/updateResponseMessageData';?>",
									data: { response: response, insertId:insertId }
								}).done(function( msg ) 
								{   
									var pushNotification = JSON.parse(msg); 
									$(".notifications-list").html(pushNotification.pushAjaxNotification);
								});
							}
						});
					</script>
					<?php
				}
			?>
		</div><!-- Card end-->
	</div><!-- Content body end-->
</div><!-- Content end-->
