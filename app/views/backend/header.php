<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->
<style>
	.navbar-brand{padding-top: 0px;padding-bottom:0px;}
	.navbar-brand img{height:3.1rem;}

	#nav {
		padding: 20px;
		text-align: center;
		color: white;
		position: fixed;
		height: 100%;
		top: 0;
		width: 80%;
		max-width: 300px;
		background: #F8F8F8;
		box-shadow: 3px 0 10px rgba(0,0,0,0.2);
	}
	#nav:not(:target) {
		left: -100%;
		transition: left 1.5s;
	}
	#nav:target {
		right: 0;
		transition: left 1s;
		z-index: 9999;
	}
</style>
<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark navbar-dark-header Shadow-new sticky-top">
	<div class="navbar-brand header-logo">
		<a href="<?php echo base_url();?>admin/home" class="d-inline-block" style="margin: 2px 0px 0px -71px !important">
			<img src="<?php echo base_url();?>uploads/logo.png" style="height: 30px;position: relative;top: 20px;width:199px;">
		</a>
	</div>

	<div class="d-md-none">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile" style="color:black;">
			<i class="icon-tree5"></i>
		</button>
		<button class="navbar-toggler sidebar-mobile-main-toggle testt2" type="button">
			<i class="icon-paragraph-justify3" style="color:black;"></i>
		</button>
	</div>

	<div class="collapse navbar-collapse " id="navbar-mobile">
		<?php 
			/* if($this->segment == 'home' || $this->segment == 'posOrder' )
			{

			}
			else
			{
				?>
				<ul class="navbar-nav justify-123">
					<li class="nav-item">
						<a href="#" class="new-nav-bar navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block bars-new">
							<i class="fa fa-bars"style="color:black;"></i>
						</a>
					</li>
				</ul>
				<?php
			} */
		?>

		<li class="full_screen" style="font-size: 17px;margin:-25px 0px 0px 0px;">
			<a --onclick="javascript:toggleFullScreen();" id="full_screen" href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
				<i class="fa fa-arrows-alt ic-colours" aria-hidden="true"></i>
			</a>
		</li>

		<!-- Quick Buttons-->
		<?php 
			if($this->user_id == 1)
			{
				?>
				<!-- <li class="top-new-btns" title="POS">
					<a  style="font-size:12px; border-radius: 50px;" href="<?php echo base_url(); ?>pos/posOrder/takeaway" class="btn btn-danger btn-sm">
						POS
					</a>
				</li>

				<li class="top-new-btns" title="Dine-In">
					<a style="font-size:12px; border-radius: 50px;" href="<?php echo base_url(); ?>items.html/<?php echo $this->user_id; ?>" class="btn btn-info btn-sm">
						Dine-In
					</a>
				</li>

				<li class="top-new-btns" title="Home Delivery">
					<a style="font-size:12px; border-radius: 50px;" href="<?php echo base_url(); ?>pos/posOrder/home_delivery" class="btn btn-secondary btn-sm">
						Home Delivery
					</a>
				</li> -->
				
				
				<?php 
			}
			else
			{
				?>
				<?php
					/* $posMenu = accessMenu(pos);
					if($posMenu['menu_enabled'] == 1)
					{
						$posCreationMenu = accessMenu(pos_creation);
					
						if($posCreationMenu['menu_enabled'] == 1)
						{
							?>
							<li class="top-new-btns" title="POS">
								<a  style="font-size:12px; border-radius: 50px;" href="<?php echo base_url(); ?>pos/posOrder/takeaway" class="btn btn-danger btn-sm">
									POS
								</a>
							</li>
							<?php 
						} 
					}  */
				?>

				<?php
					/* $dineinMenu = accessMenu(dinein);
					if($dineinMenu['menu_enabled'] == 1)
					{
						$dineInCreationMenu = accessMenu(dinein_creation);
					
						if($dineInCreationMenu['menu_enabled'] == 1)
						{
							?>
							<li class="top-new-btns" title="Dine-In">
								<a style="font-size:12px; border-radius: 50px;" href="<?php echo base_url(); ?>items.html/<?php echo $this->user_id; ?>" class="btn btn-info btn-sm">
									Dine-In
								</a>
							</li>
							<?php 
						} 
					} */ 
				?>

				<?php
					/* $homeDeliveryMenu = accessMenu(home_delivery);
					if($homeDeliveryMenu['menu_enabled'] == 1)
					{
						$homeDeliveryCreationMenu = accessMenu(home_delivery_creation);
					
						if($homeDeliveryCreationMenu['menu_enabled'] == 1)
						{
							?>
							<li class="top-new-btns" title="Home Delivery">
								<a style="font-size:12px; border-radius: 50px;" href="<?php echo base_url(); ?>pos/posOrder/home_delivery" class="btn btn-secondary btn-sm">
									Home Delivery
								</a>
							</li>
							<?php 
						} 
					}  */
				?>
				
				
				<?php
			} 
		?>
		<!-- Quick Buttons-->
		
		<script>
			$('#full_screen').on('click',function() 
			{
				if(document.fullscreenElement||document.webkitFullscreenElement||document.mozFullScreenElement||document.msFullscreenElement) { //in fullscreen, so exit it
					//alert('exit fullscreen');
					if(document.exitFullscreen) {
						document.exitFullscreen();
					} else if(document.msExitFullscreen) {
						document.msExitFullscreen();
					} else if(document.mozCancelFullScreen) {
						document.mozCancelFullScreen();
					} else if(document.webkitExitFullscreen) {
						document.webkitExitFullscreen();
					}
				} else { //not fullscreen, so enter it
					//alert('enter fullscreen');
					if(document.documentElement.requestFullscreen) {
						document.documentElement.requestFullscreen();
					} else if(document.documentElement.webkitRequestFullscreen) {
						document.documentElement.webkitRequestFullscreen();
					} else if(document.documentElement.mozRequestFullScreen) {
						document.documentElement.mozRequestFullScreen();
					} else if(document.documentElement.msRequestFullscreen) {
						document.documentElement.msRequestFullscreen();
					}
				}
			});
		</script>
	
	<?php  
			if(isset($this->user_id) && $this->user_id !="")
			{
				?>
				<span class="badge ml-md-3 mr-md-auto header-online">
					<div class="wrapper">
						<div class="searchBar_header">
							<!-- <input id="searchQueryInput" type="text" name="searchQueryInput" placeholder="Search" value="" />
							<button id="searchQuerySubmit" type="submit" name="searchQuerySubmit">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="#666666"
										d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" />
								</svg>
							</button> -->
						</div>
					</div>
				</span>
				<?php 
			} 
		?> 

		<a href="<?php echo base_url();?>admin/home" title="Home">
			<i class="fa fa-home" style="font-size:20px;"></i>
		</a>
			
		<ul class="navbar-nav" style="float:right;">
			

			

			<?php
				if($this->user_id == 1)
				{
					$usrQry1 = "select first_name from users where users.user_id = '".$this->user_id."' ";
					$edit_data1 = $this->db->query($usrQry1)->result_array();
				}
				else
				{
					$usrQry = "select first_name from per_people_all
					left join per_user on per_user.person_id = per_people_all.person_id
					where per_user.user_id = '".$this->user_id."' 
					";
					$edit_data1  = $this->db->query($usrQry)->result_array();
				}
			?>
			
			<li class="nav-item dropdown dropdown-user menu-nav-bar">
				
				<a href="javascript:void(0);" class="new-nav-bar navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
					<?php 
						if (file_exists('uploads/admin_image/'.$this->user_id.'.png'))
						{
							?>
							<img src="<?php echo base_url()."uploads/admin_image/".$this->user_id.'.png';?>" class="rounded-circle mr-2" height="34" alt=""> 
							<?php
						}
					?>
					
					<span class="header-profile-name">
						<?php echo isset($edit_data1[0]['first_name']) ? ucfirst($edit_data1[0]['first_name']) :"";?>
					</span>
				</a>
				
				<div class="dropdown-menu dropdown-menu-right menu-nav-bar1">
					<?php
						if($this->user_id == 1)
						{
							?>
							<a href="<?php echo base_url();?>admin/manage_profile" class="dropdown-item"><i class="icon-user-plus"></i> My Profile</a>
							<a href="<?php echo base_url();?>admin/change_password" class="dropdown-item"><i class="icon-key"></i> Change Password</a>
							<!--<div class="dropdown-divider"></div> -->
							
							
							<a href="<?php echo base_url();?>admin/system_settings/general-settings" class="dropdown-item"><i class="icon-cog5"></i>Settings</a>
							<a href="<?php echo base_url();?>setup/settings" class="dropdown-item"><i class="fa fa-cogs"></i>Setups</a>
						
							<?php 
						} 
					?>
					<?php /* <a href="<?php echo base_url();?>admin/logout" class="dropdown-item log-out"><i class="icon-switch2"></i> Logout</a> */ ?>
					
					
					<a  href="javascript:void(0);" onclick="logoutAlert()" class="dropdown-item log-out">
						<i class="fa fa-power-off" style="font-size:14px;"></i> Logout
					</a>
							
				</div>
			</li>
		</ul>
	</div>
</div>


<?php /*
<script>
	$(document).ready(function() {
	$('.icon-tree5').click(function() {
		$('.navbar-toggler').show();
		$('.sidebar-main').hide();
	});
	});
</script>

<script>
	$(document).ready(function()
	{
		$('.icon-paragraph-justify3').click(function()
		{
			$('.navbar-toggler collapsed').hide();
			$('.sidebar-main').show();	
		});
	});


    $(document).ready(function () 
	{
        
        $('#new_card').click(function (event) {
          
			event.preventDefault(); 
			
			$('#card_show').show();
            $('.custom-div').css('margin-bottom', '0px'); 
        });
    });


	$(document).ready(function() 
	{
  
		
    var overlay = $("#overlay_new");
    var confirmCard = $("#confirm-card_new");

    // Get reference to the "KOT & Print" button
    var showOverlayButton = $("#show-overlay");

    // Add a click event listener to the button
    showOverlayButton.on("click", function() {
        // Show the overlay and confirm card
        overlay.show();
        confirmCard.show();
    });

    // Add click event listeners to the cancel and proceed buttons
    $("#cancel-delete_new").on("click", function() {
        // Hide the overlay and confirm card when cancel is clicked
        overlay.hide();
        confirmCard.hide();
    });

    $("#confirm-delete_new").on("click", function() {
        // Handle the proceed action here
        // You can add your logic to proceed with the action
        // Then hide the overlay and confirm card
        overlay.hide();
        confirmCard.hide();
    });
});

$(document).ready(function() {
  
		
  var overlay = $("#overlay_new");
  var confirmCard = $("#accept_order_card");
  var newcard=$("#newcard_cancel");
  var deletecard=$("#deletecard");

  // Get reference to the "Accept orders" button
  var showOverlayButton = $("#accept_order");

  // Add a click event listener to the button
  showOverlayButton.on("click", function() {
	  // Show the overlay and confirm card
	  overlay.show();
	  confirmCard.show();
  });

  // Add click event listeners to the cancel and proceed buttons
  $("#accept_pay_cancel").on("click", function() {
	  // Hide the overlay and confirm card when cancel is clicked
	  overlay.show();
	  confirmCard.hide();
	  newcard.show();
  });

  $("#cancel_ord").on("click", function() {
	  // Hide the overlay and confirm card when cancel is clicked
	  overlay.show();
	  confirmCard.hide();
	  newcard.show();
  });

  $("#accept_pay_confirm").on("click", function() {
	  // Handle the proceed action here
	  // You can add your logic to proceed with the action
	  // Then hide the overlay and confirm card
	  overlay.hide();
	  confirmCard.hide();
  });
});



</script>
<script>
		$(document).ready(function() {
			$('.minus').click(function () {
				var $input = $(this).parent().find('input');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
		});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
      const paymentOptions = document.querySelectorAll(".payment-option");
      paymentOptions.forEach(function (option) {
        option.addEventListener("click", function () {
          paymentOptions.forEach(function (opt) {
            opt.classList.remove("active");
          });
          this.classList.add("active");
        });
      });
    });
</script>
*/ ?>
<!-- /main navbar -->