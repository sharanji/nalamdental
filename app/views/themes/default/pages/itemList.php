<body class="grocino-home home2 grocino-about">
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
<!-- category-section1 start here -->
<div class="category-section1 cate-top-new new-top-pro --mt-5"> 
    <div class="container">
		<!-- category-filter start here -->
		<div id="category-filter">
			<div class="row">
				<div class="col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-4">
				<!-- <div class="col-12 col-xl-9 col-lg-8 col-md-8 col-sm-12"> -->
					<div class="grid-top">
						<div class="row">
							<?php 
								if(count($menuData) > 0)
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
								}
							?>
							
							<script>
								/*
									$(document).ready(function()
									{
									$(".search-pro-name .list-of-food").closest('.product-layout').removeClass('hide-products');

									$("#myInput").on("input keyup", function() {
										var value = $(this).val().toLowerCase();

										$(".search-pro-name .list-of-food").each(function() {
										var productName = $(this).text().toLowerCase();
										var productLayout = $(this).closest('.product-layout');
										if (productName.indexOf(value) > -1) {
											productLayout.show();
										} else {
											productLayout.hide();
										}
										});
									});
									});
									*/
							</script>		
								
							<div class="col-12 col-xl-3 col-lg-4 col-md-6 col-sm-12">
								<div class="search_form">
									<input type="search" name="search" id="search_items" autocomplete="off" class="form-control" placeholder="Search Product..." />
									<a href="javascript::void();" class="search_icon" onclick="serarcItems();">
										<i class="fa fa-search sea_icon"></i>
									</a>
									<span class="input-icon" id="loadData"></span>
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
												url:"<?php echo base_url();?>web_items/selectProductCategory",  
												method:"POST",  
												data:{query:query},  //,select_type:select_type
												success:function(result)  
												{  
													data = JSON.parse(result);
													
													$("#product_data").html(data['newOrders']);
												}  
											});  
										} 
										else
										{
											location.reload();
										} 
									
									}
								
							</script>
						</div>
					</div>
				</div>

				
				<!-- <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 category-sidebar">
					<div class="filter-group">
						<div class="filter-content list_inline">
							<span class="custom-dropdown">
								<select class="form-control">
									<option value="0">Short by latest</option>
									<option value="1">10</option>
									<option value="2">25</option>
									<option value="3">50</option>
									<option value="4">All</option>
								</select>
							</span>
							<div class="btn-group">
								<button type="button" class="btn grid" id="grid-view" data-toggle="tooltip" title="Grid"> 
									<i class="fa fa-th" aria-hidden="true"></i>
								</button>
								<button type="button" class="btn list" id="list-view" data-toggle="tooltip" title="List"> 
									<i class="fa fa-list-ul" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
				</div> -->
			</div>
			
			<div class="row">
				<div class="col-12 col-xl-9 col-lg-8 col-md-8 col-sm-12 product_table_body" id="myTable">
					<div class="row filter-products product_table" id="product_data">
						<?php 
							$page_data = array();
							echo $this->load->view('themes/default/pages/itemListing.php',$page_data,true);
						?>
					</div>

					<script>
						//Cart Plus
						/* function cartPlus(product_id)
						{
							var oldVal = $('.cart_qty'+product_id).val();
							var newVal = (parseInt(oldVal,10) + 1);
							
							$('.cart_qty'+product_id).val(newVal);
							
							var price = $('#price'+product_id).val();
							var quantity = newVal;
							itemCalculateRow(product_id,quantity,price);
							//itemCalculateGrandTotal();	
						} */
						
						//Cart Minus
						/* function cartMinus(product_id)
						{
							var oldVal = $('.cart_qty'+product_id).val();
							
							if (oldVal == 0)
							{
								var newVal = 0;
							}
							else
							{
								var newVal = (parseInt(oldVal,10) -1);
							}

							
							$('.cart_qty'+product_id).val(newVal);
							
							var price = $('#price'+product_id).val();
							var quantity = newVal;
							
							itemCalculateRow(product_id,quantity,price);
							//itemCalculateGrandTotal();
						} */
						
						//Cart enter Qty 
						/* $(".product_table").on("input keyup change", 'input[name^="cart_qty"]', function (event) 
						{
							
							var attribute = $(this).attr("id");
							var attr_arr = attribute.split('@');
							
							var product_id = attr_arr[0]; 
							var quantity = $(this).val();
							var price = attr_arr[1];

							itemCalculateRow(product_id,quantity,price);
						});
						 */
						/* function itemCalculateRow(product_id,quantity,price) 
						{	
							var cartItemsType = 1; //Item Page Cart
							
							if(product_id != "")
							{
								$.ajax({
									url: '<?php echo base_url(); ?>web_items/AjaxItemListCart/'+product_id+'/'+quantity+'/'+price+'/'+cartItemsType,
									type: "POST",
									data:{
										'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
									},
									success: function(result)
									{
										data = JSON.parse(result);
										
										
										if(data['cartCount'] == 0)
										{
											$(".ajaxOrderSummary").hide();
										}
										else
										{
											$(".ajaxOrderSummary").show();
										}
										
										$(".ajaxOrderSummary").html(data['itemList']);
										$(".cartCount").html(data['cartCount']);
									}
								});
							}
							else
							{
								
							}
						} */
						
						//ShowCart Btn
						/* function showCart(product_id)
						{
							$(".cartBtn"+product_id).hide();
							$(".cartBtnPlusMinus"+product_id).show();
						} */
					</script>
				</div>

				<div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 category-sidebar">
					<div class="filter-group">
						<h3 class="title">Categories</h3> 
						<?php 
							foreach($cateData as $categories)
							{
								?>
								<div class="why-sliderright home-pages">
									<div class="faq-container">
										<div class="accordion" id="faqExample<?php echo $categories['list_type_value_id'];?>">
											<div class="accordion-item">
												<h2 class="accordion-header" id="headingOne">
													<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $categories['list_type_value_id'];?>" aria-expanded="true" aria-controls="collapseOne<?php echo $categories['list_type_value_id'];?>">
														<i class="uil uil-toggle-off"></i> <?php echo $categories['list_value'];?>
													</button>
												</h2>
												<div id="collapseOne<?php echo $categories['list_type_value_id'];?>" class="accordion-collapse collapse hide" aria-labelledby="headingOne" data-bs-parent="#faqExample<?php echo $categories['list_type_value_id'];?>">
													<div class="accordion-body p-0">
													<?php 
														$getsubCategory = "
														select 
														category.cat_level_1, 
														category.cat_level_2, 
														ltv.list_value as sub_category, 
														ltv.list_type_value_id


														from category, sm_list_type_values ltv
														
														where 
															cat_level_1 = '".$categories['list_type_value_id']."' and
															ltv.list_type_value_id = category.cat_level_2
														
														";
														$subcategory = $this->db->query($getsubCategory)->result_array();
													?>
													<ul class="subcategory-sidebar">
														
														<?php
															foreach($subcategory as $scategory)
															{
																?>
																	<li>
																		<a href="<?php echo base_url();?>products-list.html/<?php echo $scategory['list_type_value_id'];?>"><?php echo $scategory['sub_category']; ?></a>
																	</li>
																<?php
															}
														?>
													</ul>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						?>
					</div>
			
				</div>
			</div>
		</div>
		<!-- category-filter start here -->
	</div>
</div>
</body>
<!-- category-section1 start here -->
