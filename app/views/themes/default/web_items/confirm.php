<style>
      body {
        text-align: center;
        padding: 40px 0;
        background: white;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }

	  #contBtn 
	  {
		position: relative;
		top: 1.5em;
		text-decoration: none;
		background: #e81515;
		color: #fff !important;
		margin: auto;
		padding: .8em 3em;
		box-shadow: 0px 15px 30px rgba(50, 50, 50, 0.21);
		border-radius: 25px;
		
	}
	
</style>

<main class="bg_gray margin_60">
	<div class="container margin_60 margintop_35">
		<div class="row justify-content-center margin_60">
			<div -class="card">				
      			<div style="border-radius:200px; height:200px; width:200px; background: #F8FAE7; margin:0 auto;">
        			<i class="checkmark">✓</i>
      			</div>
        		<h1>Order Place Successfully</h1> 
        		<p class="mt-3">
					<a href="<?php echo base_url();?>home.html" title="Explore more our food." id="contBtn">Explore more</a>					
				</p>
      		</div>
			
		        <!-- <div class="col-lg-6">
		        	<div class="box_order_form pt-5 pb-5">
		                <div class="head text-center">
		                    <h3>Thanks for Your Order</h3>
		                </div>
		                
		                <div class="main">
		                	<div id="confirm">
								<div class="icon icon--order-success svg add_bottom_15">
									<svg xmlns="http://www.w3.org/2000/svg" width="72" height="72">
										<g fill="none" stroke="#8EC343" stroke-width="2">
											<circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
											<path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
										</g>
									</svg>
								</div>
								<h3>Order Placed!</h3>
								
								<p class="mt-3">
									<a class="explorefood" href="<?php// echo base_url();?>" title="Explore more our food.">
										<i class="fa fa-compass" aria-hidden="true"></i> Explore more.
									</a>
								</p>
							</div>
		                </div>
		            </div>
		        </div> -->
		        <!-- /col -->
		    </div>
		    <!-- /row -->
		</div>
		<!-- /container -->
		
	</main>
	<!-- /main -->