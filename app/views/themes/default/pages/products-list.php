<body class="grocino-home home2 grocino-category">
<div class="loader-overlay">
    <div class="custom-loader">
		<img src="<?php echo base_url();?>assets/frontend/img/supermarket.gif" alt="Loading...">
	</div>
  </div>
  <script>
$(document).ready(function() {
  // Simulating content loading delay
  setTimeout(function() {
    $('.loader-overlay').fadeOut('slow', function() {
      $('.content').fadeIn('slow');
    });
  }, 1000); // Hide loader after 2 seconds (adjust as needed)
});


</script>
	<style>
/* Reset some default margin/padding */
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
}

/* Full-page loader overlay */
.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color:#f5f5f1;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

/* Loader image */
/* Loader image */
.loader-overlay img {
  max-width: 50%;
  max-height: 50%;
  width: auto;
  height: auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

/* Hide content when loader is active */
.content {
  display: none;
}


 </style> 
	<!-- category-section area start here -->
		<div class="category-section  mt-5">
			<?php 
				$getSubCategory = "
				select 
				ltv.list_value,
				ltv.short_description
				from sm_list_type_values ltv, category cat 
					where ltv.list_type_value_id = cat.cat_level_2 and
						cat.cat_level_1 = '".$id."' ";

				$subCategories = $this->db->query($getSubCategory)->result_array();
			?>
			<div class="container">
				<div class="row">
					<div class="grocino-heading mt-5">
						<h4>Services for You</h4>
						<div class="heading-dots">
							<h1 class="heading_text"><span class="heading_circle">Explore By Category</span> </h1>
						</div>
					</div>
				</div>	  
				<div class="row">
					<?php 
						if(count($subCategories) > 0)
						{
							foreach($subCategories as $category)
							{
								?>
								<div class="col-12 col-lg-6 col-md-6 col-sm-6 col-xl-3"> 
									<div class="category-boxholder">
										<div class="category-border">
											<div class="img-product">
												<img src="<?php echo base_url();?>assets/frontend/img/category/c-1.png" alt="Tomato" class="img-fluid"/>
											</div>
											<h4><?php echo $category['list_value'];?></h4>
											<p style="min-height:50px;max-height:50px;"><?php echo $category['short_description'];?> </p>
											<div class="buttons">
												<a href="#" class="btn btn-default">
													<img src="<?php echo base_url();?>assets/frontend/img/home/right-arrow.png" class="arrow-left" alt="Arrow Left"> 
												</a>
												<a href="<?php echo base_url();?>products-list.html" class="btn btn-orange">
													View Products <img src="<?php echo base_url();?>assets/frontend/img/home/right-arrow.png" class="arrow-right" alt="Arrow Right">
												</a>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						}
						else
						{
							?>
								<img src="<?php echo base_url();?>assets/frontend/img/home/right-arrow.png" class="arrow-left" alt="Arrow Left">
							<?php
						}
					?>
				</div>
			</div>
			<div class="sideImg1">
				<img src="<?php echo base_url();?>assets/frontend/img/category/left-side-img.png" alt="Image" class="img-fluid"/>
			</div>
			<div class="sideImg2">
				<img src="<?php echo base_url();?>assets/frontend/img/category/right-side-img.png" alt="Image" class="img-fluid"/>
			</div>
		</div>
		<!-- category-section end here -->

	<!-- Popoular product area end -->
</body>