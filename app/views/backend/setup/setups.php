<!-- Page header start-->
<div class="second-sub-header">
	<div class="page-header page-header-light">
		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
				<div class="breadcrumb">
					<a href="<?php echo base_url();?>admin/dashboard" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
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
	<div class="row">
		<div class="col-12">
			<div class="page-title-box2"style="border-radius:2px;">
				<?php
					$edit_data1  = $this->db->get_where('users', array('user_id' => $this->user_id))->result_array();
				?>
				<h4 class="page-title page-title-dashboard"> Setups <?php //echo isset($edit_data1[0]['first_name']) ? ucfirst($edit_data1[0]['first_name']) :"";?></h4>
			</div>
		</div>
	</div>
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
					
					<ul class="settings-list">
						<a <?php echo $citySettings;?> href="<?php echo base_url();?>lov/manageLov" title="List of Values">
							<li>
								<i class="fa fa-magic fa-icon-text-red-background"></i>
								<span class="list-one">List of Values</span>
								<p>Manage all List of Values.</p>
							</li>
						</a>
						<a <?php echo $citySettings;?> href="<?php echo base_url();?>employee/ManageDesignation" title="HSN code">
							<li>
								<i class="fa fa-briefcase fa-icon-text-blue-background"></i>
								<span class="list-one">Designation</span>
								<p>Manage all Designation.</p>
							</li>
						</a>
						<?php /*
						<a <?php echo $companySettings;?> href="<?php echo base_url();?>locations/manageLocations" title="Location">
							<li>
								<i class="fa fa-map-marker fa-icon-text-green-background"></i>
								<span class="list-one">Location</span>
								<p>Manage all Location</p>
							</li>
						</a>
						
						<a <?php echo $emailSettings;?> href="<?php echo base_url(); ?>organization/manageOrganization" title="Organizations">
							<li>
								<i class="fa fa-fire fa-icon-text-red-background"></i>
								<span class="list-one">Organizations</span>
								<p>Manage all Organizations</p>
							</li>
						</a>

						<a <?php echo $emailSettings;?> href="<?php echo base_url(); ?>branches/Managebranches/" title="Branches">
							<li>
								<i class="fa fa-pie-chart fa-icon-text-pink-background"></i>
								<span class="list-one"> Branches</span>
								<p>Manage all Branches</p>
							</li>
						</a>

						<a <?php echo $emailSettings;?> href="<?php echo base_url(); ?>dine_in_tables/manageDineInTables" title="Dine In Tables">
							<li>
								<i class="fa fa-cutlery fa-icon-text-green-background"></i>
								<span class="list-one">Dine In Tables</span>
								<p>Manage all Dine In Tables</p>
							</li>
						</a>

						<a href="<?php echo base_url();?>locator/manageSubInventory" title="Sub Inventory & Locators">
							<li>
								<i class="fa fa-grav fa-icon-text-pink-background"></i>
								<span class="list-one">Sub Inventory & Locators</span>
								<p>Manage all Sub Inventory & Locators.</p>
							</li>
						</a>

						<a <?php echo $imageSettings;?> href="<?php echo base_url(); ?>menus/manageMenus" title="User Menus">
							<li>
								<i class="fa fa-server fa-icon-text-red-background"></i>
								<span class="list-one">User Menus</span>
								<p>Manage all User Menus</p>
							</li>
						</a>
						
						
						<a <?php echo $appsystemSettings;?> href="<?php echo base_url(); ?>categories/manage_category" title="Product Categories">
							<li>
								<i class="fa fa-product-hunt fa-icon-text-aqua-background"></i>
								<span class="list-one">Product Categories</span>
								<p>Manage all Product Categories</p>
							</li>
						</a>

						
						<a <?php echo $themeSettings;?> href="<?php echo base_url(); ?>paymenttype/ManagePaymenttype" title="Payment Type">
							<li>
								<i class="fa fa-money fa-icon-text-black-background"></i>
								<span class="list-one">Payment Type</span>
								<p>Manage all Payment Type</p>
							</li>
						</a>

						<a <?php echo $backupSettings;?> href="<?php echo base_url();?>uom/manageUom" title="uom">
							<li>
								<i class="fa fa-desktop fa-icon-text-gray-background"></i>
								<span class="list-one">UOM</span>
								<p>Manage all  UOM</p>
							</li>
						</a>

						<a <?php echo $countrySettings;?> href="<?php echo base_url();?>tax/manageTax" title="Tax">
							<li>
								<i class="fa fa-balance-scale fa-icon-text-orange-background"></i>
								<span class="list-one">Tax</span>
								<p>Manage all Tax.</p>
							</li>
						</a>
						<a <?php echo $stateSettings;?> href="<?php echo base_url();?>discount/manageDiscount" title="Discount">
							<li>
								<i class="fa fa-percent fa-icon-text-red-background"></i>
								<span class="list-one">Discount</span>
								<p>Manage all Discount.</p>
							</li>
						</a>
						
						<!-- <a <?php echo $citySettings;?> href="<?php echo base_url();?>warehouse/ManageWarehouse" title="Warehouse">
							<li>
								<i class="fa fa-home fa-icon-text-blue-background"></i>
								<span class="list-one">Warehouse</span>
								<p>Manage all Warehouse.</p>
							</li>
						</a> -->

						<a <?php echo $citySettings;?> href="<?php echo base_url();?>hsn/manageHsnCode" title="HSN code">
							<li>
								<i class="fa fa-hashtag fa-icon-text-orange-background"></i>
								<span class="list-one">HSN Code</span>
								<p>Manage all HSN Code.</p>
							</li>
						</a>

						<a <?php echo $citySettings;?> href="<?php echo base_url();?>employee/ManageDepartment" title="HSN code">
							<li>
							<i class="fa fa-level-up fa-icon-text-green-background"></i>
								<span class="list-one">Department</span>
								<p>Manage all Department.</p>
							</li>
						</a>

					
						*/ ?>
					</ul>
				</div>
				<?php /*
				<!-- Locations start -->
				<div class="card-setting">
					<h2>Locations</h2>
					<ul class="settings-list">
						<a href="<?php echo base_url();?>admin_location/manage_country" title="Countries">
							<li>
								<i class="fa fa-globe fa-icon-text-blue-background"></i>
								<span class="list-one">Countries</span>
								<p>Manage all Countries.</p>
							</li>
						</a>

						<a href="<?php echo base_url();?>admin_location/manage_state" title="States">
							<li>
								<i class="fa fa-compass fa-icon-text-green-background"></i>
								<span class="list-one">States</span>
								<p>Manage all States.</p>
							</li>
						</a>

						<a href="<?php echo base_url();?>admin_location/manage_city" title="Cities">
							<li>
								<i class="fa fa-map-marker fa-icon-text-red-background"></i>
								<span class="list-one">Cities</span>
								<p>Manage all Cities.</p>
							</li>
						</a>
					</ul>
				</div>
				<!-- Printer end -->
				*/ ?>
			</div>
			
			<div class="col-md-6" <?php echo $LocationSettings;?>>
				<div class="card-setting">
					<ul class="settings-list">
						<a <?php echo $citySettings;?> href="<?php echo base_url();?>seo/manageSeoContent" title="List of Values">
							<li>
								<i class="fa fa-search fa-icon-text-blue-background"></i>
								<span class="list-one">SEO</span>
								<p>Manage all SEO.</p>
							</li>
						</a>
						<a <?php echo $currencySettings;?> href="<?php echo base_url(); ?>roles/manageRoles" title="User Roles">
							<li>
								<i class="fa fa-user fa-icon-text-orange-background"></i>
								<span class="list-one">User Roles</span>
								<p>Manage all User Roles</p>
							</li>
						</a>
						<?php /*
							

							<a <?php echo $citySettings;?> href="<?php echo base_url();?>document_numbering/manageDocumentNumbering" title="Document Numbering">
								<li>
									<i class="fa fa-arrows fa-icon-text-blue-background"></i>
									<span class="list-one">Document Numbering</span>
									<p>Manage all Document Numbering.</p>
								</li>
							</a>

							<a href="<?php echo base_url();?>approval/manageApproval" title="Document Approvals">
								<li>
									<i class="fa fa-file fa-icon-text-green-background"></i>
									<span class="list-one">Document Approvals</span>
									<p>Manage all Document Approvals.</p>
								</li>
							</a>

							<a href="<?php echo base_url();?>login_audits/loginAudits" title="Login Audits">
								<li>
									<i class="fa fa-recycle fa-icon-text-blue-background"></i>
									<span class="list-one">Login Audits</span>
									<p>Manage all user login audit records.</p>
								</li>
							</a>

							<a href="<?php echo base_url();?>audit_trails/auditTrails" title="Audit Trails">
								<li>
									<i class="fa fa-snowflake-o fa-icon-text-green-background"></i>
									<span class="list-one">Audit Trails</span>
									<p>Manage all user audit records.</p>
								</li>
							</a>

							<a <?php echo $citySettings;?> href="<?php echo base_url();?>offers/manageOffers" title="Offers">
								<li>
									<i class="fa fa-ship fa-icon-text-pink-background"></i>
									<span class="list-one">Offers</span>
									<p>Manage all Offers.</p>
								</li>
							</a>

							<a <?php echo $citySettings;?> href="<?php echo base_url();?>dateformat/ManageDateformat" title="Date format">
								<li>
									<i class="fa fa-calendar fa-icon-text-red-background"></i>
									<span class="list-one">Date Format</span>
									<p>Manage all Date Format.</p>
								</li>
							</a>

						

							<a <?php echo $citySettings;?> href="<?php echo base_url();?>payment_terms/managePayment_terms" title="Payment Terms">
								<li>
									<i class="fa fa-euro fa-icon-text-yellow-background"></i>
									<span class="list-one">Payment Terms</span>
									<p>Manage all Payment Terms.</p>
								</li>
							</a>
							
							

							<a <?php echo $citySettings;?> href="<?php echo base_url();?>admin/manage_cms" title="CMS">
								<li>
									<i class="fa fa-fire fa-icon-text-orange-background"></i>
									<span class="list-one">CMS</span>
									<p>Manage all CMS.</p>
								</li>
							</a>


							<a <?php echo $citySettings;?> href="<?php echo base_url();?>admin/social_media_settings" title="Social Media Settings">
								<li>
									<i class="fa fa-share-square-o fa-icon-text-blue-background"></i>
									<span class="list-one">Social Media Settings</span>
									<p>Manage all Social Media links.</p>
								</li>
							</a>

							<a href="<?php echo base_url();?>employee/ManageBloodGroup" title="Discount">
								<li>
									<i class="fa fa-bolt fa-icon-text-red-background"></i>
									<span class="list-one">Blood Groups</span>
									<p>Manage all Blood Groups.</p>
								</li>
							</a>

							<a <?php echo $appsystemSettings;?> href="<?php echo base_url(); ?>supplierCategory/manageSupplierCategory" title="Supplier Categories">
								<li>
									<i class="fa fa-american-sign-language-interpreting fa-icon-text-green-background"></i>
									<span class="list-one">Supplier Categories</span>
									<p>Manage all Supplier Categories</p>
								</li>
							</a>
							
							<a <?php echo $appsystemSettings;?> href="<?php echo base_url(); ?>data_access/manage_data_access" title="Data Access">
								<li>
									<i class="fa fa-file-text-o fa-icon-text-aqua-background"></i>
									<span class="list-one">Data Access Control</span>
									<p>Manage all Data Access Control based on Organization and Branch</p>
								</li>
							</a>
						*/ ?>

					</ul>
				</div>

				<!-- Printer settings -->
				<?php /*
					<div class="card-setting" <?php echo $LocationSettings;?>>
						<h2>Printer Settings</h2>
						<ul class="settings-list">
							<a <?php echo $departmentsSettings;?> href="<?php echo base_url();?>printsection/ManagePrintsection" title="Manage Print Section">
								<li>
									<i class="fa fa-print fa-icon-text-blue-background"></i>
									<span class="list-one">Print Section</span>
									<p>Printing Section can consider as cashier and kitchen. There could be multiple cashier and multiple kitchen prints.</p>
								</li>
							</a>

							<a <?php echo $departmentsSettings;?> href="<?php echo base_url();?>printersettings/ManagePrintersettings" title="Manage Printer Settings">
								<li>
									<i class="fa fa-print fa-icon-text-green-background"></i>
									<span class="list-one">Printer Settings</span>
									<p>Printing Section can consider as cashier and kitcehn. There could be multiple cashier and multiple kitchen prints.</p>
								</li>
							</a>

							<a href="<?php echo base_url();?>summary/printJobStatusSummary" title="Print Job Status">
								<li>
									<i class="fa fa-file-o fa-icon-text-red-background"></i>
									<span class="list-one">Print Job Status Summary</span>
									<p>Print Job Status Summary</p>
								</li>
							</a>

							
						</ul>
					</div>
				*/ ?>
				<!-- Printer settings end -->
			</div>
		</div>	
	</div>	
 </div><!-- Content end-->

