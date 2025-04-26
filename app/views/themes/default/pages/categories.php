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
		<div class="category-section new-top -mt-5">
			<?php 
				$getMainCategory = "
				select 
				sm_list_type_values.list_type_value_id, 
				sm_list_type_values.list_type_id, 
				sm_list_type_values.list_value,
				sm_list_type_values.order_sequence 
				from sm_list_type_values 
				where list_type_id = '".$this->category_level1_id."' and 
				".$this->common_condition." ";
				$mainCategories = $this->db->query($getMainCategory)->result_array();
				
			?>
			<div class="container">
				<div class="row">
					<div class="grocino-heading mt-5 mb-2">
						<h4 class="d-none">Services for You</h4>
						<div class="heading-dots">
							<h1 class="heading_text"><span class="heading_circle">Explore By Category</span> </h1>
						</div>
					</div>
				</div>	
				<div id="category-filter">
					<div class="row">
						<div class="col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
						<!-- <div class="col-12 col-xl-9 col-lg-8 col-md-8 col-sm-12"> -->
							<div class="grid-top">
								<div class="row">
									<?php 
										/* if(count($menuData) > 0)
										{
											?>
											<div class="col-12 col-xl-9 col-lg-8 col-md-6 col-sm-12">
												<h1 class="heading1">
													<a class="list-cat-title" href="<?php echo base_url();?>category-list.html/<?php echo isset($menuData[0]['main_category_id']) ? $menuData[0]['main_category_id']:"";?>" title="<?php echo isset($menuData[0]['main_category_name']) ? $menuData[0]['main_category_name']:"";?>">
														<?php echo isset($menuData[0]['main_category_name']) ? $menuData[0]['main_category_name']:"";?>
													</a> 
													<i class="uil-angle-double-right"></i> 
													<span style="font-size:16px;">
														<?php echo $menuData[0]['category_name'];?> 
													</span>
													
												</h1>
												<p class="d-none">Showing result 1-22 of 22 result</p>
											</div>
											<?php
										}
										else
										{
											?>
											<div class="col-12 col-xl-9 col-lg-8 col-md-6 col-sm-12">
												
											</div>
											<?php
										} */
									?>
									
									<div class="col-12">
										<div class="col-sm-6 col-md-3 col-lg-3 cart-prod-new-search pull-right">
											<div class="search_form">
												<input type="search" name="search" id="search_items" autocomplete="off" class="form-control" placeholder="Search Product..." />
												<a href="javascript::void();" class="search_icon" onclick="serarcItems();">
													<i class="fa fa-search sea_icon"></i>
												</a>
												<span class="input-icon" id="loadData"></span>
											</div>
										</div>
									</div>
									<style>
										.search_form .search_icon 
										{
											position: absolute;
											right: 1px;
											top: 0px;
											border: none;
											height: 37px;
											background-color: red;
											color: #fff !important;
											width: 40px;
											border-radius: 0px;
										}
										.sea_icon
										{
											color: #fff !important;
										}
									</style>

									<script>
										$(document).on('keypress',function(e) 
										{
												if(e.which == 13) 
												{
													serarcItems();
												}
											});

											function serarcItems()
											{ 
												var query = $("#search_items").val(); 
												
												if(query != '')  
												{  
													$.ajax({  
														url:"<?php echo base_url();?>web_items/selectProductCategoryNew",  
														method:"POST",  
														data:{query:query},  //,select_type:select_type
														success:function(result)  
														{  
															data = JSON.parse(result);
															
															$("#product_data").html(data['newOrders']);
															$("#sub-categories").hide();
														}  
													});  
												} 
												else
												{
													$("#sub-categories").show();
													$("#product_data").hide();
												} 
											
											}
										
									</script>
								</div>
							</div>
						</div>
					</div>
			
					<div class="row">
						<div class="col-12 col-xl-9 col-lg-8 col-md-8 col-sm-12 product_table_body" id="myTable">
							<div class="row filter-products product_table" id="product_data">
								<?php 
									$page_data = array();
									echo $this->load->view('themes/default/pages/itemListingNew.php',$page_data,true);
								?>
							</div>
						</div>
						
					</div>
				</div>  
				<div class="row" id="sub-categories">
					<?php 
						if(count($mainCategories) > 0)
						{
							foreach($mainCategories as $cat)
							{
								?>
								<div class="col-12 col-lg-6 col-md-6 col-sm-6 col-xl-3"> 
									<a href="<?php echo base_url();?>category-list.html/<?php echo $cat['list_type_value_id'];?>" title="<?php echo $cat['list_type_value_id'];?>" class="new-sub-category">
										<div class="category-boxholder">
											<div class="category-border">
												<div class="img-product">
													<?php 
														$url = "uploads/list_image/".$cat['list_type_value_id'].".png";
														if(file_exists($url) && !empty($cat['list_type_value_id']))
														{
															?>
															<img src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($cat['list_type_value_id']);?>" data-src="<?php echo base_url().$url;?>" alt="<?php echo ucfirst($cat['list_type_value_id']);?>" class="lazy img-fluid" >
															<?php 
															$cat['list_type_value_id'];
														}
														else
														{ 
															?>
															<img src="<?php echo base_url();?>uploads/no-image.png" alt="No Image" class="lazy img-fluid">	
															<?php  
														}
													?>
												</div>
												<h4><?php echo $cat['list_value'];?></h4>
												<p style="min-height:50px;max-height:50px;display:none;"><?php echo $cat['list_value'];?> </p>
												<div class="buttons">
													<a href="<?php echo base_url();?>category-list.html/<?php echo $cat['list_type_value_id'];?>" title="<?php echo base_url();?>category-list.html/<?php echo $cat['list_type_value_id'];?>" class="btn btn-default">
														<img src="<?php echo base_url();?>assets/frontend/img/home/right-arrow.png" class="arrow-left" alt="Arrow Left"> 
													</a>
													<a href="<?php echo base_url();?>category-list.html/<?php echo $cat['list_type_value_id'];?>" class="btn btn-orange">
														View Products <img src="<?php echo base_url();?>assets/frontend/img/home/right-arrow.png" class="arrow-right" alt="Arrow Right">
													</a>
												</div>
											</div>
										</div>
									</a>
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