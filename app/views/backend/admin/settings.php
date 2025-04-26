<!-- Page header start-->
<div class="second-sub-header">
	<div class="page-header page-header-light">
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<?php /* <a href="javascript:void(0)" class="breadcrumb-item">Profile</a> */?>
					<span class="breadcrumb-item active">
						Settings
					</span>
				</div>
				<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
			</div>
		</div>
	</div>
</div>
<!-- Page header end-->

<div class="content"><!-- Content start-->	
	<?php 
		if(isset($_SESSION['register_type']) && $_SESSION['register_type'] == 0)
		{
			$displaySettings = $ApplicationSettings = $SystemSettings ="";
			$LocationSettings = $companySettings = $emailSettings ="";
			$imageSettings = $currencySettings = $appsystemSettings ="";
			$themeSettings = $countrySettings = $stateSettings = $backupSettings = "";
			$citySettings = $designationsSettings = $bloodGroupsSettings ="";
			$categoriesSettings = $clientCategorySettings = $uomSettings ="";
			$vatSettings = $visaStatusSettings = $relationshipSettings = $documentCategorySettings ="";
			$requestTypesSettings = $menusSettings = $rolesSettings = $branchesSettings ="";
			$departmentsSettings = $requestCategoriesSettings ="";
			$appointmentSlotsSettings ="";
		}
		else
		{
			
			/*
			$menus = accessMenu(menus);
			$roles = accessMenu(roles);
			$relations = accessMenu(relations);
			$brands = accessMenu(brands);
			$uom = accessMenu(uom);
			$tax = accessMenu(tax);
			$discounts = accessMenu(discounts);
			$hsn_code = accessMenu(hsn_code);
			$product_category = accessMenu(product_category);
			$project_category = accessMenu(project_category);
			# $budget_category = accessMenu(budget_category);
			$document_category = accessMenu(document_category);
			$cashflow_type = accessMenu(cashflow_type);
			$payment_type = accessMenu(payment_type);
			 #Settings
			$settingsMenu = accessMenu(settings);
			
			if(isset($settingsMenu['menu_enabled']) && $settingsMenu['menu_enabled'] == 1)
			{
				$displaySettings = "style='display:block;'"	;
			}
			else
			{
				$displaySettings = "style='display:none;'"	;
			}
			
			

			
			#Application Settings
			$application_settings = accessMenu(application_settings);
			
			if(isset($application_settings['menu_enabled']) && $application_settings['menu_enabled'] == 1)
			{
				$ApplicationSettings = "style='display:block;'"	;
			}
			else
			{
				$ApplicationSettings = "style='display:none;'"	;
			}
			
			#System Settings
			$system_settings = accessMenu(system_settings);
			
			if(isset($system_settings['menu_enabled']) && $system_settings['menu_enabled'] == 1)
			{
				$SystemSettings = "style='display:block;'"	;
			}
			else
			{
				$SystemSettings = "style='display:none;'"	;
			}
			
			#Location Settings
			$location_settings = accessMenu(location_settings);
			
			if(isset($location_settings['menu_enabled']) && $location_settings['menu_enabled'] == 1)
			{
				$LocationSettings = "style='display:block;'";
			}
			else
			{
				$LocationSettings = "style='display:none;'"	;
			}
			
			$company_settings = accessMenu(company_settings);
			if(isset($company_settings['menu_enabled']) && $company_settings['menu_enabled'] == 1)
			{
				$companySettings = "style='display:block;'";
			}
			else
			{
				$companySettings = "style='display:none;'"	;
			}
			
			$email_contact_settings = accessMenu(email_contact_settings);
			if(isset($email_contact_settings['menu_enabled']) && $email_contact_settings['menu_enabled'] == 1)
			{
				$emailSettings = "style='display:block;'";
			}
			else
			{
				$emailSettings = "style='display:none;'";
			}
			
			$image_settings = accessMenu(image_settings);
			if(isset($image_settings['menu_enabled']) && $image_settings['menu_enabled'] == 1)
			{
				$imageSettings = "style='display:block;'";
			}
			else
			{
				$imageSettings = "style='display:none;'";
			}
			
			$currency_settings = accessMenu(image_settings);
			if(isset($currency_settings['menu_enabled']) && $currency_settings['menu_enabled'] == 1)
			{
				$currencySettings = "style='display:block;'";
			}
			else
			{
				$currencySettings = "style='display:none;'";
			}
			
			$app_system_settings = accessMenu(image_settings);
			if(isset($app_system_settings['menu_enabled']) && $app_system_settings['menu_enabled'] == 1)
			{
				$appsystemSettings = "style='display:block;'";
			}
			else
			{
				$appsystemSettings = "style='display:none;'";
			}
			
			$theme_settings = accessMenu(image_settings);
			if(isset($theme_settings['menu_enabled']) && $theme_settings['menu_enabled'] == 1)
			{
				$themeSettings = "style='display:block;'";
			}
			else
			{
				$themeSettings = "style='display:none;'";
			}
			
			$country_settings = accessMenu(country);
			if(isset($country_settings['menu_enabled']) && $country_settings['menu_enabled'] == 1)
			{
				$countrySettings = "style='display:block;'";
			}
			else
			{
				$countrySettings = "style='display:none;'";
			}
			
			$state_settings = accessMenu(state);
			if(isset($state_settings['menu_enabled']) && $state_settings['menu_enabled'] == 1)
			{
				$stateSettings = "style='display:block;'";
			}
			else
			{
				$stateSettings = "style='display:none;'";
			}
			
			$city_settings = accessMenu(city);
			if(isset($city_settings['menu_enabled']) && $city_settings['menu_enabled'] == 1)
			{
				$citySettings = "style='display:block;'";
			}
			else
			{
				$citySettings = "style='display:none;'";
			}
			
			$menus = accessMenu(menus);
			if(isset($menus['menu_enabled']) && $menus['menu_enabled'] == 1)
			{
				$menusSettings = "style='display:block;'";
			}
			else
			{
				$menusSettings = "style='display:none;'";
			}
			
			$roles = accessMenu(roles);
			if(isset($roles['menu_enabled']) && $roles['menu_enabled'] == 1)
			{
				$rolesSettings = "style='display:block;'";
			}
			else
			{
				$rolesSettings = "style='display:none;'";
			}
			
			$branches = accessMenu(branches);
			if(isset($branches['menu_enabled']) && $branches['menu_enabled'] == 1)
			{
				$branchesSettings = "style='display:block;'";
			}
			else
			{
				$branchesSettings = "style='display:none;'";
			}
			
			$departments = accessMenu(departments);
			if(isset($departments['menu_enabled']) && $departments['menu_enabled'] == 1)
			{
				$departmentsSettings = "style='display:block;'";
			}
			else
			{
				$departmentsSettings = "style='display:none;'";
			}
			
			$request_categories = accessMenu(request_categories);
			if(isset($request_categories['menu_enabled']) && $request_categories['menu_enabled'] == 1)
			{
				$requestCategoriesSettings = "style='display:block;'";
			}
			else
			{
				$requestCategoriesSettings = "style='display:none;'";
			}
			
			$request_types = accessMenu(request_types);
			if(isset($request_types['menu_enabled']) && $request_types['menu_enabled'] == 1)
			{
				$requestTypesSettings = "style='display:block;'";
			}
			else
			{
				$requestTypesSettings = "style='display:none;'";
			}
			
			$designations = accessMenu(designations);
			if(isset($designations['menu_enabled']) && $designations['menu_enabled'] == 1)
			{
				$designationsSettings = "style='display:block;'";
			}
			else
			{
				$designationsSettings = "style='display:none;'";
			}
			
			$blood_groups = accessMenu(blood_groups);
			if(isset($blood_groups['menu_enabled']) && $blood_groups['menu_enabled'] == 1)
			{
				$bloodGroupsSettings = "style='display:block;'";
			}
			else
			{
				$bloodGroupsSettings = "style='display:none;'";
			}
			
			$categories = accessMenu(categories);
			if(isset($categories['menu_enabled']) && $categories['menu_enabled'] == 1)
			{
				$categoriesSettings = "style='display:block;'";
			}
			else
			{
				$categoriesSettings = "style='display:none;'";
			}
			
			$client_category = accessMenu(client_category);
			if(isset($client_category['menu_enabled']) && $client_category['menu_enabled'] == 1)
			{
				$clientCategorySettings = "style='display:block;'";
			}
			else
			{
				$clientCategorySettings = "style='display:none;'";
			}
			
			$uom = accessMenu(uom);
			if(isset($uom['menu_enabled']) && $uom['menu_enabled'] == 1)
			{
				$uomSettings = "style='display:block;'";
			}
			else
			{
				$uomSettings = "style='display:none;'";
			}
			
			$vat = accessMenu(vat);
			if(isset($vat['menu_enabled']) && $vat['menu_enabled'] == 1)
			{
				$vatSettings = "style='display:block;'";
			}
			else
			{
				$vatSettings = "style='display:none;'";
			}
			
			$visa_status = accessMenu(visa_status);
			if(isset($visa_status['menu_enabled']) && $visa_status['menu_enabled'] == 1)
			{
				$visaStatusSettings = "style='display:block;'";
			}
			else
			{
				$visaStatusSettings = "style='display:none;'";
			}
			
			$relationship = accessMenu(relationship);
			if(isset($relationship['menu_enabled']) && $relationship['menu_enabled'] == 1)
			{
				$relationshipSettings = "style='display:block;'";
			}
			else
			{
				$relationshipSettings = "style='display:none;'";
			}
			
			$document_category = accessMenu(document_category);
			if(isset($document_category['menu_enabled']) && $document_category['menu_enabled'] == 1)
			{
				$documentCategorySettings = "style='display:block;'";
			}
			else
			{
				$documentCategorySettings = "style='display:none;'";
			}

			$appointmentSlots = accessMenu(appointment_slots);
			if(isset($appointmentSlots['menu_enabled']) && $appointmentSlots['menu_enabled'] == 1)
			{
				$appointmentSlotsSettings = "style='display:block;'";
			}
			else
			{
				$appointmentSlotsSettings = "style='display:none;'";
			}
			*/
			
			
			$displaySettings = $ApplicationSettings = $SystemSettings ="";
			$LocationSettings = $companySettings = $emailSettings ="";
			$imageSettings = $currencySettings = $appsystemSettings ="";
			$themeSettings = $countrySettings = $stateSettings = $backupSettings = "";
			$citySettings = $designationsSettings = $bloodGroupsSettings ="";
			$categoriesSettings = $clientCategorySettings = $uomSettings ="";
			$vatSettings = $visaStatusSettings = $relationshipSettings = $documentCategorySettings ="";
			$requestTypesSettings = $menusSettings = $rolesSettings = $branchesSettings ="";
			$departmentsSettings = $requestCategoriesSettings ="";
			$appointmentSlotsSettings ="";
			
		}
		
		
	?>
	<div class="settings" <?php echo $displaySettings;?>>
		<div class="row">
			<div class="col-md-6" >
				<div class="card-setting" <?php echo $ApplicationSettings;?>>
					<h2>Application Settings</h2>
					<ul class="settings-list">
						<a <?php echo $companySettings;?> href="<?php echo base_url();?>admin/system_settings/general-settings" title="General Settings">
							<li>
								<i class="fa fa-cogs fa-icon-text-maroon-background"></i>
								<span class="list-one">Company Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>
						
						<a <?php echo $emailSettings;?> href="<?php echo base_url(); ?>admin/system_settings/email-contact-settings" title="Email & Contact Settings">
							<li>
								<i class="fa fa-envelope fa-icon-text-blue-background"></i>
								<span class="list-one">Email & Contact Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>

						
						
						<?php /*
						<a <?php echo $backupSettings;?> href="<?php echo base_url();?>admin/ManageBackupDatabases" title="Backup Database">
							<li>
								<i class="fa fa-desktop fa-icon-text-gray-background"></i>
								<span class="list-one">Backup Database</span>
								<p>A user will download the database of the application.</p>
							</li>
						</a>
						
						 <a <?php echo $menusSettings;?> href="<?php echo base_url();?>admin/loginRecords" title="Login Records">
							<li>
								<i class="fa fa-cogs fa-icon-text-blue-background"></i>
								<span class="list-one">Login Records</span>
								<p>You can use the Login audit log to track user sign-ins to your domain. You can review all sign-ins from web browsers.</p>
							</li>
						</a> */?>
					</ul>
				</div>
			</div>
			
			<div class="col-md-6" <?php echo $LocationSettings;?>>
				<div class="card-setting">
					<h2>Application Settings</h2>
					<ul class="settings-list">
						<a <?php echo $emailSettings;?> href="<?php echo base_url(); ?>admin/mailer_settings/" title="Email & Contact Settings">
							<li>
								<i class="fa fa-envelope fa-icon-text-blue-background"></i>
								<span class="list-one">Mailer Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>
						<a <?php echo $imageSettings;?> href="<?php echo base_url(); ?>admin/system_settings/image-settings" title="Image Settings">
							<li>
								<i class="fa fa-picture-o fa-icon-text-yellow-background"></i>
								<span class="list-one">Image Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>
						<?php /*

						<a <?php echo $currencySettings;?> href="<?php echo base_url(); ?>admin/system_settings/currency-settings" title="Currency Settings">
							<li>
								<i class="fa fa-money fa-icon-text-orange-background"></i>
								<span class="list-one">Currency Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>
						
						<a <?php echo $appsystemSettings;?> href="<?php echo base_url(); ?>admin/system_settings/system-settings" title="App System Settings">
							<li>
								<i class="fa fa-sliders fa-icon-text-aqua-background"></i>
								<span class="list-one">App System Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>
						
						<a <?php echo $themeSettings;?> href="<?php echo base_url(); ?>admin/system_settings/theme" title="Theme Settings">
							<li>
								<i class="fa fa-desktop fa-icon-text-gray-background"></i>
								<span class="list-one">Theme Settings</span>
								<p>A user profile is a visual display of personal data associated with a specific user, or a customized desktop environment.</p>
							</li>
						</a>

						<a <?php echo $countrySettings;?> href="<?php echo base_url();?>admin_location/manage_country" title="Country">
							<li>
								<i class="fa fa-globe fa-icon-text-orange-background"></i>
								<span class="list-one">Country</span>
								<p>Manage all Country.</p>
							</li>
						</a>
						<a <?php echo $stateSettings;?> href="<?php echo base_url();?>admin_location/manage_state" title="State">
							<li>
								<i class="fa fa-map-marker fa-icon-text-green-background"></i>
								<span class="list-one">State</span>
								<p>Manage all State.</p>
							</li>
						</a>
						
						<a <?php echo $citySettings;?> href="<?php echo base_url();?>admin_location/manage_city" title="City">
							<li>
								<i class="fa fa-map-signs fa-icon-text-blue-background"></i>
								<span class="list-one">City</span>
								<p>Manage all City.</p>
							</li>
						</a>
						*/ ?>
					</ul>
				</div>
			</div>
		</div>	
	</div>	
 </div><!-- Content end-->

