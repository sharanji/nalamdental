<div class="col-md-3 col-sm-3 col-xs-12 length-catgry1">
	<div class="x_panel">
		<?php
			$query = 'select users.user_id,users.first_name,users.email,users.mobile_number from users where user_id = '.$id ;
			$user_data = $this->db->query($query)->result_array();
		?>
		<div class="x_title">
			<h2 class="left-company-name"><?php echo !empty($user_data[0]['first_name']) ? $user_data[0]['first_name'] : "-";?></h2>
			<div class="leftboxprofile">
				<?php 
					if(!empty($user_data[0]['user_id']) && file_exists("uploads/profile_image/".$user_data[0]['user_id'].'.png') )
					{
						
						?>
						<img class="img-responsive" alt="Jesper Apps 2-image" style="border:1px solid #ddd; border-radius:4px; padding:5px; width:100%;height:150px;" src="<?php echo base_url(); ?>uploads/profile_image/<?php echo $id .'.png';?>">
						<?php 
					}
					else
					{
						?>
							<img src="<?php echo base_url();?>uploads/no-user.png" style="max-width:100px !important; max-height:90px !important;" alt="...">
						<?php
					}
				?>
				<h5><i class="fa fa-phone"></i> &nbsp; <?php echo !empty($user_data[0]['mobile_number']) ? $user_data[0]['mobile_number'] :"--";?></h5>
				<h5><i class="fa fa-envelope"></i> &nbsp; <?php echo !empty($user_data[0]['email']) ? $user_data[0]['email'] :"--";?></h5>
			</div>
			<div class="clearfix"></div>
		</div>
		
		<?php 
			$segment = $this->uri->segment(3);
			
			$activeProfile = $activeaddresslist = $activebookmarks = $activewallet = $activeorders = $activefavourite ='';
			
			if( isset($segment) && $segment == "view" )
			{
				$activeProfile = 'active';
			}
			else if( isset($segment) && $segment == "addresslist" )
			{
				$activeaddresslist = 'active';
			}
			else if( isset($segment) && $segment == "bookmark" )
			{
				$activebookmarks = 'active';
			}
			else if( isset($segment) && $segment == "wallet" )
			{
				$activewallet = 'active';
			}
			else if( isset($segment) && $segment == "ordersHistory" )
			{
				$activeorders = 'active';
			}
			else if( isset($segment) && $segment == "favourite" )
			{
				$activefavourite = 'active';
			}		
		?>
		
		<div class="x_content x_content2">
			<ul class="nav nav-tabs1 tabs-left1">
				<li class="<?php echo $activeProfile;?>"><a href="<?php echo base_url();?>customer/ManageCustomer/view/<?php echo $id;?>"><i class="fa fa-user"></i> &nbsp;Profile</a></li>
				<li class="<?php echo $activeaddresslist;?>"><a href="<?php echo base_url();?>customer/ManageCustomer/addresslist/<?php echo $id;?>"><i class="fa fa-fax"></i> &nbsp;Address List</a></li>
				<li class="<?php echo $activebookmarks;?>"><a href="<?php echo base_url();?>customer/ManageCustomer/bookmark/<?php echo $id;?>"><i class="fa fa-star"></i> &nbsp;Bookmarks</a></li>
				<li class="<?php echo $activewallet;?>"><a href="<?php echo base_url();?>customer/ManageCustomer/wallet/<?php echo $id;?>"><i class="fa fa-credit-card"></i> &nbsp;Wallet</a></li>
				<li class="<?php echo $activeorders;?>"><a href="<?php echo base_url();?>customer/ManageCustomer/ordersHistory/<?php echo $id;?>"><i class="fa fa-cutlery"></i> &nbsp;Order History</a></li>
				<li class="<?php echo $activefavourite;?>"><a href="<?php echo base_url();?>customer/ManageCustomer/favourite/<?php echo $id;?>"><i class="fa fa-heart"></i> &nbsp;Favourie Orders</a></li>
			</ul>
		</div>
	</div>
</div>

<!-- End col-md-3 for left side menu -->