
<?php 
			$segment = $this->uri->segment(3);
			
			$activeProfile = $activeaddresslist = $activebookmarks = $activewallet = $activeorders = $activefavourite = $activelogins = '';
			
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
			else if( isset($segment) && $segment == "loginHistory" )
			{
				$activelogins = 'active';
			}		
		?>
		<ul class="nav view-client-tabs testing">
			<li class="nav-item new-nav-items <?php echo $activeProfile;?>">
				<a class="nav-link profile" aria-current="page" onclick="clientTabs(1);" href="<?php echo base_url();?>consumers/ManageCustomer/view/<?php echo $id;?>"> My Account <span style="font-size:12px;color:#979696;"></span></a>
			</li>

			<!-- <li class="nav-item new-nav-items <?php echo $activewallet;?>">
				<a class="nav-link client-users" href="<?php echo base_url();?>customer/ManageCustomer/wallet/<?php echo $id;?>" onclick="clientTabs(2);"> Wallet <span style="font-size:12px;color:#fff;"></span></a>
			</li> -->

			<li class="nav-item new-nav-items <?php echo $activeaddresslist;?>">
				<a class="nav-link client-users" href="<?php echo base_url();?>consumers/ManageCustomer/addresslist/<?php echo $id;?>" onclick="clientTabs(2);"> Address Book <span style="font-size:12px;color:#fff;"></span></a>
			</li>

			<li class="nav-item new-nav-items <?php echo $activeorders;?>">
				<a class="nav-link client-users" href="<?php echo base_url();?>consumers/ManageCustomer/ordersHistory/<?php echo $id;?>" onclick="clientTabs(2);"> My Orders <span style="font-size:12px;color:#fff;"></span></a>
			</li>

			
			<li class="nav-item new-nav-items <?php echo $activewallet;?>"> 
				<a class="nav-link client-users" href="<?php echo base_url();?>consumers/ManageCustomer/wallet/<?php echo $id;?>" onclick="clientTabs(2);"> Wallet <span style="font-size:12px;color:#fff;"></span></a>
			</li>

			<!-- <li class="nav-item new-nav-items <?php echo $activebookmarks;?>">
				<a class="nav-link client-users" href="<?php echo base_url();?>customer/ManageCustomer/bookmark/<?php echo $id;?>" onclick="clientTabs(2);">Favourite Items <span style="font-size:12px;color:#fff;"></span></a>
			</li>-->

			<li class="nav-item new-nav-items <?php echo $activefavourite;?>">
				<a class="nav-link client-users" href="<?php echo base_url();?>consumers/ManageCustomer/favourite/<?php echo $id;?>" onclick="clientTabs(2);">Favourite Orders <span style="font-size:12px;color:#fff;"></span></a>
			</li>

			<!-- <li class="nav-item new-nav-items <?php echo $activelogins;?>">
				<a class="nav-link client-users" href="<?php echo base_url();?>customer/ManageCustomer/loginHistory/<?php echo $id;?>" onclick="clientTabs(2);"> Login History <span style="font-size:12px;color:#fff;"></span></a>
			</li> -->
		</ul>

        <style>
        .nav
        {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }
        .x_content1
        {
            padding: 0px 5px 5px 6px;
            float: left;
            clear: both;
            margin-top: 0px;
            width: 100%;
        }
        ul.nav.view-client-tabs li
        {
            border: 1px solid #c3c3c3;
            padding: 0px 15px;
            background: #b1b1b1;
        }

        li.new-nav-items.active 
        {
            color: #fff !important;
            background-color: #f30505 !important;
            border: 1px solid #b1b1b1 !important;
            font-family: sans-serif !important;
        }
        ul.nav.view-client-tabs li a 
        {
            color: #fff;
        }
        ul.nav.view-client-tabs.testing {
            float: left;
            width: 100%;
        }
        </style>
