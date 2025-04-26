	

<!-- Content area -->
<div class="content">
	<!-- start page title -->
	<div class="row">
		<div class="col-12">
			<div class="page-title-box2">
				<?php
					$edit_data1 = $this->db->query("select first_name from users where user_id='".$this->user_id."' ")->result_array();
				?>
				<h4 class="page-title page-title-dashboard">Welcome <?php echo isset($edit_data1[0]['first_name']) ? ucfirst($edit_data1[0]['first_name']) :"";?> !</h4>
			</div>
		</div>
	</div>
	
	<!-- end page title -->

	<!-- Quick stats boxes -->
	<div class="row"> 

		<!-- Online Ordering start here -->
		<div class="col-lg-3 col-md-3"> 
			<a href="<?php echo base_url();?>admin/online_order_dashboard" title="Online Ordering">
				<div class="card"> 
					<div class="card-body"> 
						<div class="row">
							<div class="col-lg-3">  
								<img class="card-img-top icons" src="<?php echo base_url();?>uploads/online_order_icon.png" alt=""> 
							</div>

							<div class="col-lg-9 mt-3">  
								<h1 class="card-title">Online Ordering</h1> 
							</div>
						</div>
					</div> 
				</div> 
			</a> 
		</div>
		<!-- Online Ordering end here -->

		<!-- Inventory start here -->
		<?php /* <div class="col-lg-3 col-md-3"> 
			<a href="<?php echo base_url();?>admin/inventory_dashboard" title="Inventory">
				<div class="card"> 
					<div class="card-body"> 
						<div class="row">
							<div class="col-lg-6">  
								<img class="card-img-top icons" src="<?php echo base_url();?>uploads/inventory_icon.png" alt=""> 
							</div>

							<div class="col-lg-6 mt-3">  
								<h1 class="card-title">Inventory</h1> 
							</div>
						</div>
					</div> 
				</div> 
			</a> 
		</div> */ ?>
		<!-- Inventory end here -->
		
		<!-- POS start here -->
		<div class="col-lg-3 col-md-3"> 
			<a href="<?php echo base_url();?>admin/pos_dashboard" title="POS">
				<div class="card"> 	
					<div class="card-body"> 
						<div class="row">
							<div class="col-lg-3">  
								<img class="card-img-top icons" src="<?php echo base_url();?>uploads/pos_icon.png" alt=""> 
							</div>
							<div class="col-lg-9 mt-3">  
								<h1 class="card-title">POS</h1> 
							</div>
						</div>
					</div> 
				</div> 
			</a> 
		</div>
		<!-- POS end here -->

		<!-- Dining start here -->
		<div class="col-lg-3 col-md-3"> 
			<a href="<?php echo base_url();?>admin/dining_dashboard" title="Dining">
				<div class="card"> 
					<div class="card-body"> 
						<div class="row">
							<div class="col-lg-3">  
								<img class="card-img-top icons" src="<?php echo base_url();?>uploads/dining_icon.png" alt=""> 
							</div>
							<div class="col-lg-9 mt-3">  
								<h1 class="card-title">Dine In</h1> 
							</div>
						</div>
					</div> 
				</div> 
			</a> 
		</div>
		<!-- Dining end here -->
	</div> 
    


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
<!-- Content end -->

	
