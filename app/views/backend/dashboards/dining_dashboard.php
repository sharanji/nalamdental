<style>
	.section-new-1 {
		padding: 17px 10px;
	}

	span.users-section.purchase {
		color: #007de9;
		font-size: 18px;
	}
	span.users-section.expenses {
		color: #ff7600;
		font-size: 18px;
	}	
	span.users-section-count.purchase{
		color: #007de9;
		font-family: roboto !important;
	}
	span.users-section-count.expenses{
		color: #ff7600;
	}
	.new-icons {
		background: #f58d17;
		position: relative;
		left: 7px;
		top: 1px;
		border-radius: 6px;
		color: #fff;
		width: auto;
		text-align: center;
	}
	.count-name {
		position: relative;
		left: 10px;
	}
	i.products {
		position: relative;
		color: #fff;
	}
	i.suppilers {
		color: #fff;
	}
	i.employee {
		color: #fff;
	}
	i.customer {
		color: #fff;
	}
	.chart {
		background: #fff;
		/* box-shadow: 0px 0px 8px 0px #cfcfcf; */
	}
	.section-new-1.mb-3.dashboard-gradients {
		/* box-shadow: 0px 0px 8px 0px #cfcfcf; */
	}
	span.sales-text {
		position: relative;
		top: 20px;
		left: 2px;
		font-weight: 700;
		border: 1px solid #409bdc;
		padding: 10px;
		border-radius: 50px;
		color: #409bdc;
		background-color: #e8f5ff;
	}
	@media (max-width: 667px){
	  .right-side-down {
		 padding-left:0px!important;
	  }}
	select.newselectList {
		padding: 6px 10px;
		border: 1px solid #d3caca;
		border-radius: 3px;
	}
	select.newselectList:focus {
		outline:none!important;
	}
	select.newselectList option.newselectListoption {
	
	}
</style>
<script src="<?php echo base_url();?>assets/backend/assets/js/Chart.min.js"></script>	

<!-- Content area -->
	<div class="content">
		<!-- start page title -->
		<div class="row">
			<div class="col-12">
				<div class="page-title-box2">
					<?php
						$edit_data1  = $this->db->get_where('users', array('user_id' => $this->user_id))->result_array();
					?>
					<h4 class="page-title page-title-dashboard">Welcome <?php echo isset($edit_data1[0]['first_name']) ? ucfirst($edit_data1[0]['first_name']) :"";?> !</h4>
				</div>
			</div>
		</div>
		
		<!-- end page title -->
		<!-- Quick stats boxes -->
		<?php  
			/* if(isset($this->user_id) && $this->user_id == 1) 
			{ 
				?>
				<div --class="container">
					<div class="row">
						<div class="col-md-3">
							<?php
								$Patient = $this->db->query("select users.user_id from users
								where users.register_type =1")->result_array(); #Customers = 1
							?>
							<a href="<?php echo base_url();?>patient/ManagePatient?keywords=&register_type=1&filter=10" title="Patient">
								<div class="card --bg-success-300 content-box">
									<div class="card-body card-details">
										<div class="row">
											<div class="col-lg-4 new-icons bg-primary">
												<i class="fa fa-users icons-font customer" aria-hidden="true"></i>
											</div>
											<div class="col-lg-6 count-name">
												<div class="d-flex">
													<h3 class="font-weight-bold mb-0 count-num-land">
														<span><?php echo count($Patient);?></span>
													</h3>
												</div>
												<div class="menu-titles">Customers</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						
						<div class="col-md-3">
							<?php
								$products = $this->db->query("select products.product_id from products
								where products.product_id")->result_array();
							?>
							<a href="<?php echo base_url();?>products/ManageProducts?keywords=&register_type=2&filter=10" title="products">
								<div class="card --bg-info-300 content-box">
									<div class="card-body card-details">
										<div class="row">
											<div class="col-lg-4 new-icons bg-info">
												<i class="fa fa-server icons-font products" aria-hidden="true"></i>
											</div>
											<div class="col-lg-8 count-name">
												<div class="d-flex">
													<h3 class="font-weight-bold mb-0 count-num-land">
														<span><?php echo count($products);?></span>
													</h3>
												</div>
												<div class="menu-titles">Products</div>
											</div>
											
										</div>
									</div>
								</div>
							</a>
						</div>
						
						<div class="col-md-3">
							<?php
								$supplier = $this->db->query("select users.user_id from users
								where users.register_type =4")->result_array();
							?>
							<a href="<?php echo base_url();?>suppliers/ManageSuppliers?keywords=&register_type=4&filter=10" title="supplier">
								<div class="card --bg-warning-300 content-box">
									<div class="card-body card-details">
										<div class="row">
											<div class="col-lg-4 new-icons bg-success">
												<i class="fa fa-exchange icons-font suppilers" aria-hidden="true"></i>
											</div>
											<div class="col-lg-8 count-name">
												<div class="d-flex">
													<h3 class="font-weight-bold mb-0 count-num-land">
														<span><?php echo count($supplier);?></span>
													</h3>
												</div>
												<div class="menu-titles">Suppilers</div>
											</div>
											
										</div>
									</div>
								</div>
							</a>
						</div>
						
						<div class="col-md-3">
							<?php
								$employee = $this->db->query("select users.user_id from users
								where users.register_type = 3")->result_array(); 
							?>
							<a href="<?php echo base_url();?>users/ManageUsers?keywords=&register_type=3&filter=10" title="Employee">
								<div class="card --bg-pink-300 content-box">
									<div class="card-body card-details">
										<div class="row">
											<div class="col-lg-4 new-icons bg-warning">
												<i class="fa fa-user icons-font employee" aria-hidden="true"></i>
											</div>
											<div class="col-lg-8 count-name">
												<div class="d-flex">
													<h3 class="font-weight-bold mb-0 count-num-land">
														<span><?php echo count($employee);?></span>
													</h3>
												</div>
												<div class="menu-titles">Users</div>
											</div>
											
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
		
				
				<?php 
			}  */
		?>
	</div>

	
