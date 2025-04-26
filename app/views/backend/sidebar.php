<style>
<style>
	ul.nav.nav-group-sub.round-icon li a {
		padding: 10px 15px;
		/* border-bottom: 1px solid #ddd; */
	}
	ul.nav.nav-group-sub.round-icon li a i {
		margin-right: 12px;
	}
	.new-order_notification{
		background-color:white;
		color:black;
		margin-left:17px;
		border-radius:7px;
	}
</style>


<script>
	$(document).ready(function () 
	{
		$("#hide-menu").hover(
			function () {
				$("#add_c").removeClass("sidebar-xs");
			},
			function () {
				$("#add_c").addClass("sidebar-xs");
			}
		);
	});
</script>

<!-- Main sidebar -->
<div id="hide-menu" class="sidebar sidebar-dark new-sidebar-scroll sidebar-main sidebar-expand-md">
	<!-- Sidebar mobile toggler -->
	<div class="sidebar-mobile-toggler text-center" style="background-color:#E32227 !important;">
		<a href="javascript:void(0)" class="sidebar-mobile-main-toggle">
			<i class="icon-arrow-left8"></i>
		</a>
		Navigation
		<a href="javascript:void(0)" class="sidebar-mobile-expand">
			<i class="icon-screen-full"></i>
			<i class="icon-screen-normal"></i>
		</a>
	</div>
	<!-- sidebar mobile toggle -->
	
	<!-- Sidebar content -->
	<div class="sidebar-content">
		<!-- Main navigation -->
		<div class="card card-sidebar-mobile">
			<ul class="nav nav-sidebar nav-sidebar-new" data-nav-type="accordion">
				<!-- Main
				<li class="nav-item-header">
					<div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i>
				</li> -->
				<?php
					#Admin
					if( $this->user_id == 1 ) #Admin
					{ 
						?>
						<!-- Apps start-->
						<li class="nav-item">
							<a href="<?php echo base_url();?>admin/dashboard" class="nav-link <?php if(isset($dashboard)){?>active<?php } ?>">
								<i class="fa fa-home menu-link"></i>
								<span>Dashboard</span>
							</a>
						</li>
						<!-- Apps end-->
						
						<!-- <li class="nav-item">
							<a href="<?php echo base_url();?>blogs/manageBlogs" class="nav-link <?php if(isset($manageBlogs)){?>active<?php } ?>">
								<i class="fa fa-picture-o menu-link"></i>
								<span>Blogs</span>
							</a>
						</li> -->
						
						<!-- Apps end-->

						

						<!-- Jobs start -->
						<!-- <li class="nav-item nav-item-submenu <?php if(isset($manageJobs) || isset($manageJobsCategory)){?>nav-item-open<?php } ?>">
							<a href="javascript:void(0)" class="nav-link">
								<i class="fa fa-cube menu-link" aria-hidden="true"></i><span>Jobs</span>
							</a>
							<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($manageJobs) || isset($manageJobsCategory)){?>style="display:block;"<?php } ?>>
								<li class=" nav-item ">
									<a href="<?php echo base_url();?>jobs/manageJobs" class="nav-link <?php if(isset($manageJobs)){?>active<?php } ?>">
										<i class="fa fa-dot-circle-o"></i>
										Jobs
									</a>
								</li>
								<li class=" nav-item ">
									<a href="<?php echo base_url();?>jobs/manageJobsCategory" class="nav-link <?php if(isset($manageJobsCategory)){?>active<?php } ?>">
										<i class="fa fa-dot-circle-o"></i>
										Jobs Category
									</a>
								</li>
								
							</ul>
						</li> -->
						<!-- Job end-->


						<!-- Reviews start-->

						<!-- <li class="nav-item">
							<a href="<?php echo base_url();?>review/manageReview" class="nav-link <?php if(isset($manageReview)){?>active<?php } ?>">
								<i class="fa fa-user-graduate menu-link"></i>
								<span>Reviews</span>
							</a>
						</li> -->

						<!-- Reviews end-->

						<!-- Internship start-->

						<!-- <li class="nav-item">
							<a href="<?php echo base_url();?>careers/manageInternship" class="nav-link <?php if(isset($manageInternship)){?>active<?php } ?>">
								<i class="fa fa-user-graduate menu-link"></i>
								<span>Internship</span>
							</a>
						</li> -->

						<!-- Internship end-->

						<!-- Services start-->
						<!-- <li class="nav-item">
							<a href="<?php echo base_url();?>services/manageServices" class="nav-link <?php if(isset($manageServices)){?>active<?php } ?>">
								<i class="fa fa-handshake menu-link"></i>
								<span>Services</span>
							</a>
						</li> -->
						<!-- Services end-->


						<!-- Contact Us Start-->
						<li class="nav-item">
							<a href="<?php echo base_url();?>contactus/manageContactUs" class="nav-link <?php if(isset($manageContactUs)){?>active<?php } ?>">
								<i class="fa fa-phone menu-link"></i>
								<span>Contact Us</span>
							</a>
						</li>
						<!-- Contact Us End-->

						<!-- Applied Jobs Us Start-->
						<!-- <li class="nav-item">
							<a href="<?php echo base_url();?>appliedjobs/manageAppliedJobs" class="nav-link <?php if(isset($manageAppliedJobs)){?>active<?php } ?>">
								<i class="fa fa-check-circle menu-link"></i>
								<span>Jobs Applied</span>
							</a>
						</li> -->
						<!-- Applied Jobs End-->

						<!-- Settings Start-->
						<li class="nav-item">
							<a href="<?php echo base_url();?>admin/settings" class="nav-link <?php if(isset($settings)){?>active<?php } ?>">
								<i class="fa fa-tools menu-link"></i>
								<span>Settings</span>
							</a>
						</li>

						<li class="nav-item">
							<a href="<?php echo base_url();?>setup/settings" class="nav-link <?php if(isset($setups)){?>active<?php } ?>">
								<i class="fa fa-cogs menu-link"></i>
								<span>Setups</span>
							</a>
						</li>
						<!-- Settings end-->
						<?php 
					}
					else
					{
						$module_access = isset($_SESSION["MODULE_ACCESS"]) ? $_SESSION["MODULE_ACCESS"] : NULL;
						?>
						
						<?php 
							$dashboardMenu = accessMenu(dashboard);
							if($dashboardMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>admin/dashboard" class="nav-link <?php if(isset($dashboard)){?>active<?php } ?>">
										<i class="fa fa-home menu-link"></i>
										<span>Dashboard</span>
									</a>
								</li>
								<?php 
							}
						?>

						<!-- Orders start-->
						<?php 
							$ordersMenu = accessMenu(orders);
							if($ordersMenu['menu_enabled'] == 1)
							{
								$openOrdersMenu = accessMenu(open_orders);
								$manageOrdersMenu = accessMenu(manage_orders);

								if($openOrdersMenu['menu_enabled'] == 1 || $manageOrdersMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageOrders)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-check menu-link" aria-hidden="true"></i> <span>Orders</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($ManageOrders)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
											
											<?php
												/* if($openOrdersMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Total_Orders&new=Total_Orders&open_ord=open_ord" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>Open Orders
															<span class="badge new-order_notification order-notification">
																<?php 
																	$newOrderQry = "select header_id from ord_order_headers where order_status='Booked'";
																	$getNewOrders = $this->db->query($newOrderQry)->result_array();
																?>
																<?php echo count($getNewOrders); ?>
															</span>
														</a>
													</li>
													<?php 
												}  */
											?>

											<?php
												if($manageOrdersMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>orders/manageOrders" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															Manage Orders
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Orders end-->

						<!-- POS start-->
						<?php 
							$posMenu = accessMenu(pos);
							if($posMenu['menu_enabled'] == 1)
							{
								$posCreationMenu = accessMenu(pos_creation);
								$posListMenu = accessMenu(pos_list);

								if($posCreationMenu['menu_enabled'] == 1 || $posListMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($pos)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-paper-plane menu-link" aria-hidden="true"></i> <span>POS</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="POS" <?php if(isset($pos)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
											<?php
												/* if($posCreationMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>pos/posOrder/takeaway" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															POS
														</a>
													</li>
													<?php 
												}  */
											?>

											<?php
												if($posListMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>pos/manageposOrders" class="nav-link <?php if(isset($manage_pos)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															POS List
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- POS end-->

						<!-- Home Delivery start-->
						<?php 
							$homeDeliveryMenu = accessMenu(home_delivery);
							if($homeDeliveryMenu['menu_enabled'] == 1)
							{
								$homeDeliveryCreationMenu = accessMenu(home_delivery_creation);
								$homeDeliveryListMenu = accessMenu(home_delivery_list);

								if($homeDeliveryCreationMenu['menu_enabled'] == 1 || $homeDeliveryListMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($home_delivery)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-handshake-o menu-link" aria-hidden="true"></i> <span>Home Delivery</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Home Delivery" <?php if(isset($home_delivery)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
											<?php
												/* if($homeDeliveryCreationMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>pos/posOrder/home_delivery" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															Home Delivery
														</a>
													</li>
													<?php 
												} */ 
											
												if($homeDeliveryListMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>home_delivery/manageHomeDeliveryOrders" class="nav-link <?php if(isset($manage_home_delivery)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															Home Delivery List
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								}
							}
						?>
						<!-- Home Delivery end-->

						<!-- Dining start-->
						<?php 
							$dineinMenu = accessMenu(dinein);
							if($dineinMenu['menu_enabled'] == 1)
							{
								$dineInCreationMenu = accessMenu(dinein_creation);
								$dineInListMenu = accessMenu(dinein_list);

								if($dineInCreationMenu['menu_enabled'] == 1 || $dineInListMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($dineIn)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-cutlery menu-link" aria-hidden="true"></i> <span>Dine In</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Dining" <?php if(isset($dineIn)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
											
											<?php
												/* if($dineInCreationMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>items.html/<?php echo $this->user_id; ?>" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															Dine In
														</a>
													</li>
													<?php 
												} */ 
											?>

											<?php
												if($dineInListMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>dine_in/manageDineInOrders" class="nav-link <?php if(isset($manage_dine_in)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															Dine In List
														</a>
													</li>
													<?php 
												} 
											?>

										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Dining end-->

						<!-- Products start -->
						<?php 
							$itemsMenu = accessMenu(items);
							if($itemsMenu['menu_enabled'] == 1)
							{
								$itemCreationMenu = accessMenu(item_creation);
								$assignBranchItemsMenu = accessMenu(assign_branch_items);
								$itemIngredientsMenu = accessMenu(item_ingredients);

								if($itemCreationMenu['menu_enabled'] == 1 || $assignBranchItemsMenu['menu_enabled'] == 1  || $itemIngredientsMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageProducts)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-cube menu-link" aria-hidden="true"></i><span>Items</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($ManageProducts)){?>style="display:block;"<?php } ?>>
											
											<?php
												if($itemCreationMenu['menu_enabled'] == 1)
												{
													?>
													<li class=" nav-item pt-0">
														<a href="<?php echo base_url();?>products/ManageProducts" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															Items
														</a>
													</li>
													<?php 
												} 
											?>
											
											<?php
												if($assignBranchItemsMenu['menu_enabled'] == 1)
												{
													?>
													<li class=" nav-item ">
														<a href="<?php echo base_url();?>branch_items/ManageBranchItems" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															Assign Branch Items
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($itemIngredientsMenu['menu_enabled'] == 1)
												{
													?>
													<li class=" nav-item ">
														<a href="<?php echo base_url();?>ingredients/ManageIngredients" class="nav-link">
															<i class="fa fa-dot-circle-o"></i>
															Item Ingredients
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Products end-->

						<!-- Expences start -->
						<?php 
							$expensesMenu = accessMenu(expenses);
							if($expensesMenu['menu_enabled'] == 1)
							{
								$manageExpensesMenu = accessMenu(manage_expenses);
								$expenseTypeMenu = accessMenu(expense_type);
								$expensecCategoryMenu = accessMenu(expense_category);

								if($manageExpensesMenu['menu_enabled'] == 1 || $expenseTypeMenu['menu_enabled'] == 1  || $expensecCategoryMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageExpense)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link"><i class="fa fa-money menu-link"></i> <span>Expenses</span></a>
										<ul class="nav nav-group-sub round-icon" data-submenu-title="Expense" <?php if(isset($ManageExpense)){?>style="display:block;"<?php } ?>>
											<?php 
												if($manageExpensesMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item"><a href="<?php echo base_url();?>expense/ManageExpense" class="nav-link"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Expenses</a></li>
													<?php 
												}
											
												if($expenseTypeMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item"><a href="<?php echo base_url();?>expense/ManageExpenseType" class="nav-link"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Expense Type</a></li>
													<?php 
												}
											
												if($expensecCategoryMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item"><a href="<?php echo base_url();?>expense/ManageParticulars" class="nav-link"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Expense Category</a></li>
													<?php 
												}
											?>
										</ul>
									</li> 
									<?php 
								} 
							} 
						?>
						<!-- Expences end -->

						<!-- Purchase Order / Receive -->
						<?php 
							$purchaseMenu = accessMenu(purchase);
							if($purchaseMenu['menu_enabled'] == 1)
							{
								$purchaseOrderMenu = accessMenu(purchase_order);
								$purchaseReceiptMenu = accessMenu(purchase_receipt);

								if($purchaseOrderMenu['menu_enabled'] == 1 || $purchaseReceiptMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($purchase)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-cart-arrow-down menu-link" aria-hidden="true"></i><span>Purchase</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($purchase)){?>style="display:block;"<?php } ?>>
											<?php
												if($purchaseOrderMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>purchase_order/managePurchaseOrder" class="nav-link <?php if(isset($managePurchaseOrder)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															Purchase Order
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($purchaseReceiptMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>purchase_receipt/managePurchaseReceipt" class="nav-link <?php if(isset($managePurchaseReceipt)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Purchase Receipt</span>
														</a>
													</li>	
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Purchase Order  -->
									
						<!-- Sales Order -->
						<?php 
							$salesOrderMenu = accessMenu(sales_order);
							if($salesOrderMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>sales_order/manageSalesOrder" class="nav-link <?php if(isset($sales)){?>active<?php } ?>">
										<i class="fa fa-paper-plane menu-link" aria-hidden="true"></i>
										<span>Sales Order</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Sales Order end -->


						<!-- Physical Stock Adjustment -->
						<?php 
							$physical_stock_adjustment = accessMenu(physical_stock_adjustment);
							if($physical_stock_adjustment['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>stock_adjustment/stockAdjustment" class="nav-link <?php if(isset($StockAdjustment)){?>active<?php } ?>">
									<i class="fa fa-adjust menu-link" aria-hidden="true"></i>
										<span>Physical Stock Adjustment</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Physical Stock Adjustment end -->

						<!-- Invoice -->
						<?php 
							$invoiceMenu = accessMenu(invoice);
							if($invoiceMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>invoice/manageinvoice" class="nav-link <?php if(isset($invoice)){?>active<?php } ?>">
									<i class="fa fa-file-text-o menu-link" aria-hidden="true"></i>
										<span>Invoice</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Invoice Order end -->

						<!-- Report start -->
						<?php 
							$reportMenu = accessMenu(reports);
							if($reportMenu['menu_enabled'] == 1)
							{
								$onHandAvailabilityReportMenu = accessMenu(on_hand_availability_report);

								$salesSummaryReportMenu = accessMenu(sales_report);
								$customerSOAReportMenu = accessMenu(customer_soa_report);
								$supplierSOAReportMenu = accessMenu(supplier_soa_report);

								$minimumStockReportMenu = accessMenu(minimum_stock_report);
								$item_wise_sales_reportMenu = accessMenu(item_wise_sales_report);
								$captain_wise_sales_reportMenu = accessMenu(captain_wise_sales_report);
								
								if(
									$onHandAvailabilityReportMenu['menu_enabled'] == 1 || 
									$customerSOAReportMenu['menu_enabled'] == 1 || 
									$supplierSOAReportMenu['menu_enabled'] == 1 || 
									$minimumStockReportMenu['menu_enabled'] == 1 || 
									$item_wise_sales_reportMenu['menu_enabled'] == 1 || 
									$captain_wise_sales_reportMenu['menu_enabled'] == 1
								)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($report)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-list-alt menu-link" aria-hidden="true"></i><span>Reports</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($report)){?>style="display:block;"<?php } ?>>
											
											<?php
												if($salesSummaryReportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/salesSummary" class="nav-link <?php if(isset($SalesReport)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Sales Report</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($item_wise_sales_reportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/itemWiseSalesSummary" class="nav-link <?php if(isset($itemWiseSalesReport)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Item Wise Sales Report</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($captain_wise_sales_reportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/captainWiseSalesSummary" class="nav-link <?php if(isset($captainWiseSalesReport)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Captain Wise Sales Report</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($onHandAvailabilityReportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/onhandAvailability" class="nav-link <?php if(isset($OnHandAvailabilityReport)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span> On hand Availability Report</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($minimumStockReportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/minimumStock" class="nav-link <?php if(isset($MinimumStockReport)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Minimum Stock Report</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($customerSOAReportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/customerSOA" class="nav-link <?php if(isset($customerSOA)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Customer SOA Report</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($supplierSOAReportMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>report/supplierSOA" class="nav-link <?php if(isset($supplierSOA)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Supplier SOA Report</span>
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Report end-->

						<!-- Summary end-->
						<?php 
							$summaryMenu = accessMenu(summary);
							if($summaryMenu['menu_enabled'] == 1)
							{
								$onHandAvailabilityMenu = accessMenu(on_hand_availability);

								$salesSummaryMenu = accessMenu(sales_summary);
								$customerSOAMenu = accessMenu(customer_soa);
								$supplierSOAMenu = accessMenu(supplier_soa);

								$minimumStockMenu = accessMenu(minimum_stock);
								$item_wise_sales_summaryMenu = accessMenu(item_wise_sales_summary);
								$captain_wise_sales_summaryMenu = accessMenu(captain_wise_sales_summary);
								
								if(
									$onHandAvailabilityMenu['menu_enabled'] == 1 || 
									$customerSOAMenu['menu_enabled'] == 1 || 
									$supplierSOAMenu['menu_enabled'] == 1 || 
									$minimumStockMenu['menu_enabled'] == 1 || 
									$item_wise_sales_summaryMenu['menu_enabled'] == 1 || 
									$captain_wise_sales_summaryMenu['menu_enabled'] == 1
								)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($summary)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-list-alt menu-link" aria-hidden="true"></i><span>Summary</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($summary)){?>style="display:block;"<?php } ?>>
											
											<?php
												if($salesSummaryMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/salesSummary" class="nav-link <?php if(isset($salesSummary)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Sales Summary</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($item_wise_sales_summaryMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/itemWiseSalesSummary" class="nav-link <?php if(isset($itemWiseSalesSummary)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Item Wise Sales Summary</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($captain_wise_sales_summaryMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/captainWiseSalesSummary" class="nav-link <?php if(isset($captainWiseSalesSummary)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Captain Wise Sales Summary</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($onHandAvailabilityMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/onhandAvailability" class="nav-link <?php if(isset($onhandAvailability)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span> On hand Availability</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($minimumStockMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/minimumStock" class="nav-link <?php if(isset($minimumStock)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Minimum Stock</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($customerSOAMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/customerSOA" class="nav-link <?php if(isset($customerSOA)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Customer SOA</span>
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($supplierSOAMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>summary/supplierSOA" class="nav-link <?php if(isset($supplierSOA)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Supplier SOA</span>
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Summary end-->


						<!-- Daily Accounts start -->
						<?php 
							$dailyAccountsMenu = accessMenu(daily_accounts);
							if($dailyAccountsMenu['menu_enabled'] == 1)
							{
								$cashExpensesMenu = accessMenu(cash_expenses);
								$accountsSupplierPaymentMenu = accessMenu(accounts_supplier_payment);

								if($cashExpensesMenu['menu_enabled'] == 1 || $accountsSupplierPaymentMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageAccounts)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link"><i class="fa fa-address-card-o menu-link"></i> <span>Accounts</span></a>
										<ul class="nav nav-group-sub round-icon" data-submenu-title="Accounts" <?php if(isset($ManageAccounts)){?>style="display:block;"<?php } ?>>
											<?php
												if($cashExpensesMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>accounts/manageCashExpenses" class="nav-link <?php if(isset($manageCashExpenses)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o" aria-hidden="true"></i> Cash Expenses
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($accountsSupplierPaymentMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>accounts/manageSupplierPayment" class="nav-link <?php if(isset($manageSupplierPayment)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o" aria-hidden="true"></i> Supplier Payment
														</a>
													</li>
													<?php 
												} 
											?>

										</ul>
									</li> 
									<?php 
								} 
							} 
						?>
						<!-- Daily Accounts end -->

						<!-- Payments start -->
						<?php 
							$paymentsMenu = accessMenu(payments);
							if($paymentsMenu['menu_enabled'] == 1)
							{
								$customer_paymentMenu = accessMenu(customer_payment);
								$supplier_paymentMenu = accessMenu(supplier_payment);

								if($customer_paymentMenu['menu_enabled'] == 1 || $supplier_paymentMenu['menu_enabled'] == 1)
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageCustomerPayment) || isset($ManageSupplierPayment)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-file-text-o menu-link" aria-hidden="true"></i><span>Payments</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($ManageCustomerPayment) || isset($ManageSupplierPayment)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
											
											<li class=" nav-item pt-0">
												<a href="<?php echo base_url();?>payment/manageCustomerPayment" class="nav-link <?php if(isset($ManageCustomerPayment)){?>active<?php } ?>">
													<i class="fa fa-dot-circle-o"></i>
													Customer Payment
												</a>
											</li>

											<li class=" nav-item pt-0">
												<a href="<?php echo base_url();?>payment/manageSupplierPayment" class="nav-link <?php if(isset($ManageSupplierPayment)){?>active<?php } ?>">
													<i class="fa fa-dot-circle-o"></i>
													Supplier Payment
												</a>
											</li>
											
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Payments end -->

						<!-- Suppliers start -->
						<?php 
							$suppliersMenu = accessMenu(suppliers);
							if($suppliersMenu['menu_enabled'] == 1)
							{
								$manageSuppliersMenu = accessMenu(manage_suppliers);
								$supplierSitesMenu = accessMenu(supplier_sites);
								
								if($manageSuppliersMenu['menu_enabled'] == 1 || $supplierSitesMenu['menu_enabled'] == 1 )
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageSuppliers)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa-user-circle menu-link" aria-hidden="true"></i><span>Suppliers</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($ManageSuppliers)){?>style="display:block;"<?php } ?>>
											<?php
												if($manageSuppliersMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>suppliers/ManageSuppliers" class="nav-link">
															<i class="fa fa-dot-circle-o -fa-icon-text-lime" aria-hidden="true"></i> Suppliers
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($supplierSitesMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>suppliers/ManageSupplierSites" class="nav-link">
															<i class="fa fa-dot-circle-o --fa-icon-text-lime" aria-hidden="true"></i> Supplier Sites
														</a>
													</li>
													<?php 
												} 
											?>
										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Suppliers end -->

						<!-- Consumers start -->
						<?php 
							$consumersMenu = accessMenu(consumers);
							if($consumersMenu['menu_enabled'] == 1)
							{
								$consumerListingMenu = accessMenu(consumer_listing);
								$consumerWalletMenu = accessMenu(consumer_wallet);
								
								if($consumerListingMenu['menu_enabled'] == 1 || $consumerWalletMenu['menu_enabled'] == 1 )
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($manageCustomers)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa fa-users menu-link" aria-hidden="true"></i><span>Consumers</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Consumers" <?php if(isset($manageCustomers)){?>style="display:block;"<?php } ?>>
											
											<?php
												if($consumerListingMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>consumers/ManageCustomer" class="nav-link">
															<i class="fa fa-dot-circle-o -fa-icon-text-lime" aria-hidden="true"></i> Customers
														</a>
													</li>
													<?php 
												} 
											?>

											<?php
												if($consumerWalletMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>customer_wallet/ManageCustomerWallet" class="nav-link">
															<i class="fa fa-dot-circle-o -fa-icon-text-lime" aria-hidden="true"></i> Customers Wallet
														</a>
													</li>
													<?php 
												} 
											?>

										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Consumers end -->

						<!-- Customers start -->
						<?php 
							$customersMenu = accessMenu(customers);
							if($customersMenu['menu_enabled'] == 1)
							{
								$manageCustomersMenu = accessMenu(manage_customers);
								$customerSitesMenu = accessMenu(customer_sites);
								
								if($manageCustomersMenu['menu_enabled'] == 1 || $customerSitesMenu['menu_enabled'] == 1 )
								{
									?>
									<li class="nav-item nav-item-submenu <?php if(isset($ManageCustomer)){?>nav-item-open<?php } ?>">
										<a href="javascript:void(0)" class="nav-link">
											<i class="fa fa fa-users menu-link" aria-hidden="true"></i><span>Customers</span>
										</a>
										<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($ManageCustomer)){?>style="display:block;"<?php } ?>>
											<?php
												if($manageCustomersMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item pt-0">
														<a href="<?php echo base_url();?>customer/ManageCustomer" class="nav-link <?php if(isset($Customers)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o -fa-icon-text-lime" aria-hidden="true"></i> Customers
														</a>
													</li>
													<?php 
												} 
											?>
											<?php
												if($customerSitesMenu['menu_enabled'] == 1)
												{
													?>
													<li class="nav-item">
														<a href="<?php echo base_url();?>customer/ManageCustomerSites" class="nav-link <?php if(isset($customerSites)){?>active<?php } ?>">
															<i class="fa fa-dot-circle-o"></i>
															<span>Customers Sites</span>
														</a>
													</li>
													<?php 
												} 
											?>

										</ul>
									</li>
									<?php 
								} 
							} 
						?>
						<!-- Customers end -->

						<!-- Employees start -->
						<?php 
							$employeesMenu = accessMenu(employees);
							if($employeesMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>employee/ManageEmployee" class="nav-link <?php if(isset($ManageEmployees)){?>active<?php } ?>">
										<i class="fa fa-black-tie menu-link" aria-hidden="true"></i>
										<span>Employees</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Employees end -->

						<!-- Users start -->
						<?php 
							$usersMenu = accessMenu(users);
							if($usersMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>users/ManageUsers" class="nav-link <?php if(isset($ManageUsers)){?>active<?php } ?>">
										<i class="fa fa-user menu-link"></i>
										<span>Users</span>
									</a>
								</li>	
								<?php 
							} 
						?>
						<!-- Users end -->

						<?php 
							$denominationMenu = accessMenu(denomination);
							if($denominationMenu['menu_enabled'] == 1)
							{
								?>
								<!-- Denomination start -->
								<li class="nav-item">
									<a href="<?php echo base_url();?>denomination/manageDenomination" class="nav-link <?php if(isset($Denomination)){?>active<?php } ?>">
									<i class="fa fa-money menu-link"></i>
										<span>Denomination</span>
									</a>
								</li>	
								<!-- Denomination end -->
								<?php 
							} 
						?>

						<!-- Banner start -->
						<?php 
							$bannersMenu = accessMenu(banners);
							if($bannersMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>banner/Manage_Banner" class="nav-link <?php if(isset($ManageBanner)){?>active<?php } ?>">
										<i class="fa fa-image menu-link"></i>
										<span>Banners</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Banner end -->

						<!--Category Banner Start -->
						<?php 
							$customerBannerMenu = accessMenu(customer_banner);
							if($customerBannerMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>category_banner/ManageCategoryBanner" class="nav-link <?php if(isset($ManageCategoryBanner)){?>active<?php } ?>">
										<i class="fa fa-image menu-link"></i>
										<span>Category Banner</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!--Category Banner End-->

						<!-- customer feedback start -->
						<?php 
							$customerFeedbackMenu = accessMenu(customer_feedback);
							if($customerFeedbackMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>customerfeedback/ManageCustomerfeedback" class="nav-link <?php if(isset($ManageCustomerfeedback)){?>active<?php } ?>">
										<i class="fa fa-comment menu-link"></i>
										<span>Customers Feedback</span>
									</a>
								</li>	
								<?php 
							} 
						?>
						<!-- customer feedback end -->

						<!-- customer enquiry start -->
						<?php 
							$customerEnquiresMenu = accessMenu(customer_enquires);
							if($customerEnquiresMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>customer_enquiries/customerEnquiries" class="nav-link <?php if(isset($customerEnquiries)){?>active<?php } ?>">
										<i class="fa fa-question-circle menu-link"></i>
										<span>Customer Enquiries</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- customer enquiry end -->

						<!-- Push Notification start -->
						<?php 
							$pushNotificationMenu = accessMenu(push_notification);
							if($pushNotificationMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>push_notification/managePushNotification" class="nav-link <?php if(isset($managePushNotification)){?>active<?php } ?>">
										<i class="fa fa-comments menu-link"></i>
										<span>Push Notification</span>
									</a>
								</li>	
								<?php 
							} 
						?>
						<!-- Push Notification end -->

						<!-- Settings Start-->
						<?php 
							$settingsMenu = accessMenu(settings);
							if($settingsMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>admin/settings" class="nav-link <?php if(isset($settings)){?>active<?php } ?>">
										<i class="fa fa-gear menu-link"></i>
										<span>Settings</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Settings end-->

						<!-- Setups end-->
						<?php 
							$setupsMenu = accessMenu(setups);
							if($setupsMenu['menu_enabled'] == 1)
							{
								?>
								<li class="nav-item">
									<a href="<?php echo base_url();?>setup/settings" class="nav-link <?php if(isset($setups)){?>active<?php } ?>">
										<i class="fa fa-cogs menu-link"></i>
										<span>Setups</span>
									</a>
								</li>
								<?php 
							} 
						?>
						<!-- Setups end-->

						<!--- Card Modules start --->
						<?php
							/* if( $module_access != NULL && $module_access == "online_order_dashboard" )
							{
								?>
								<!-- Orders start-->
								<li class="m-1 nav-item nav-item-submenu <?php if(isset($ManageOrders)){?>nav-item-open<?php } ?>">
									<a href="javascript:void(0)" class="nav-link">
										<i class="fa fa-check menu-link" aria-hidden="true"></i> <span>Orders</span>
									</a>
									<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Settings" <?php if(isset($ManageOrders)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
										<li class="nav-item pt-0">
											<a href="<?php echo base_url();?>orders/openOrders?order_number=&mobile_number=&payment_type_id=&from_date=&to_date=&order_status=Total_Orders&new=Total_Orders&open_ord=open_ord" class="nav-link">
												<i class="fa fa-dot-circle-o"></i>Open Orders

												<span class="badge new-order_notification order-notification">
													<?php 
														$newOrderQry = "select header_id from ord_order_headers where order_status='Booked'";
														$getNewOrders = $this->db->query($newOrderQry)->result_array();
													?>
													<?php echo count($getNewOrders); ?>
												</span>
											</a>
										</li>

										<li class="nav-item">
											<a href="<?php echo base_url();?>orders/manageOrders" class="nav-link">
												<i class="fa fa-dot-circle-o"></i>
												Manage Orders
											</a>
										</li>
									</ul>
								</li>
								<!-- Orders end-->
								<?php 
							} 
							else if( !empty($module_access) && $module_access == "dining_dashboard" )
							{
								?>
								<!-- Dining start-->
								<li class="nav-item nav-item-submenu <?php if(isset($dineIn)){?>nav-item-open<?php } ?>">
									<a href="javascript:void(0)" class="nav-link">
										<i class="fa fa-cutlery menu-link" aria-hidden="true"></i> <span>Dine In</span>
									</a>
									<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="Dining" <?php if(isset($dineIn)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
										<li class="nav-item">
											<a href="<?php echo base_url();?>pos/posOrder/dine_in" class="nav-link">
												<i class="fa fa-dot-circle-o"></i>
												Dine In
											</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>dine_in/manageDineInOrders" class="nav-link <?php if(isset($manage_dine_in)){?>active<?php } ?>">
												<i class="fa fa-dot-circle-o"></i>
												Dine In List
											</a>
										</li>
									</ul>
								</li>
								<!-- Dining end-->
								<?php 
							} 
							else if( !empty($module_access) && $module_access == "pos_dashboard" )
							{
								?>
								<!-- POS start-->
								<li class="nav-item nav-item-submenu <?php if(isset($pos)){?>nav-item-open<?php } ?>">
									<a href="javascript:void(0)" class="nav-link">
										<i class="fa fa-paper-plane menu-link" aria-hidden="true"></i> <span>POS</span>
									</a>
									<ul class="nav nav-group-sub round-icon force-overflow" data-submenu-title="POS" <?php if(isset($pos)){?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
										<li class="nav-item">
											<a href="<?php echo base_url();?>pos/posOrder/takeaway" target="_blank" class="nav-link">
												<i class="fa fa-dot-circle-o"></i>
												POS
											</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>pos/manageposOrders" class="nav-link <?php if(isset($manage_pos)){?>active<?php } ?>">
												<i class="fa fa-dot-circle-o"></i>
												POS List
											</a>
										</li>
									</ul>
								</li>
								<!-- POS end-->
								<?php 
							}  */
						?>
						<!--- Card Modules end --->
						<?php 
					} 
				?>
				

				<?php
					if(isset($this->dine_in_role_id) && $this->dine_in_role_id == 3 && $this->user_id != 1) #Cashier
					{
						?>
						<li class="nav-item">
							<a href="javascript:void(0);" onclick="closeCashierShift();" class="nav-link ">
								<i class="fa fa-power-off menu-link"></i>
								<span>Close Shift</span>
							</a>
						</li>
						<?php
					}
					else
					{
						?>
						<li class="nav-item">
							<a href="javascript:void(0);" onclick="logoutAlert()"class="nav-link ">
								<i class="fa fa-power-off menu-link"></i>
								<span>Logout</span>
							</a>
						</li>
						<?php
					}
				?>	

				<script>
					function closeCashierShift() 
					{
						Swal.fire({
							title : 'Are you sure close the shift?',
							text  : "You won't be able to revert this!",
							icon  : 'warning',
							showCancelButton: true,
							confirmButtonColor: '#070d7d',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, Close!'
						}).then((result) => 
						{
							if (result.isConfirmed) 
							{
								chkDenomination();
							}
						});
					}

					function chkDenomination()
					{
						var val = "<?php echo isset($this->user_id) ? $this->user_id : 0;?>";

						if(val > 0)
						{
							$.ajax({
								type : "POST",
								url  : "<?php echo base_url();?>denomination/ajaxChkDenomination",
								data : { id: val }
							}).done(function( result ) 
							{  
								var header_id = result;

								if(header_id == 0)
								{
									Swal.fire({
										title: 'Denomination',
										text: "Please enter denomination first.!",
										icon: 'warning',
										showCancelButton: true,
										confirmButtonColor: '#070d7d',
										cancelButtonColor: '#d33',
										confirmButtonText: 'OK'
									}).then((result) => 
									{
										if (result.isConfirmed) 
										{
											window.location = '<?php echo base_url();?>denomination/manageDenomination/create';
										}
									});
								}
								else
								{
									printDenominationBill(header_id);
								}		
							});
						}
					}

					function printDenominationBill(header_id)
					{
						if(header_id)
						{
							$.ajax({
								url      : '<?php echo base_url(); ?>billGenrator/chkbill/'+header_id,
								type     : "POST",
								data     : {},
								datatype : JSON,
								success  : function(d)
								{
									response = JSON.parse(d);
									var htmlCashierContent = response["demominationPath"];
									
									var print_items = response["print_items"];
									
									var countKey = Object.keys(print_items).length;
									
									if( countKey > 0 )
									{
										$.each(print_items, function(i, item) 
										{
											var print_type = item.print_type; // #Cashier #KOT

											if( print_type == "CASHIER")
											{
												var printer_name = item.printer_name;
												var printer_count = item.printer_count;
												
												for(i=1; i<=printer_count; i++)
												{
													denominationAutoPrint(jspmWSStatus(),htmlCashierContent,header_id,printer_name);
												}
											}
										});
									}
									closeShift();
								}
							});   
						}
					}

					function denominationAutoPrint(printerStatus,htmlContent,orderID,printer_name)
					{
						if (printerStatus && htmlContent !="") 
						{
							//Create a ClientPrintJob
							var cpj = new JSPM.ClientPrintJob();

							//Set Printer info
							//var myPrinter = new JSPM.InstalledPrinter($('#lstPrinters').val());
							//myPrinter.paperName = $('#lstPrinterPapers').val();
							//myPrinter.trayName = $('#lstPrinterTrays').val();
							//cpj.clientPrinter = myPrinter;
							
							//Cashier Printer
							//var printerPort = <?php #echo PRINTER_PORT;?>;
							//var printerName = '<?php #echo PRINTER_NAME;?>';

							//var printerPort = printer_ip;
							var printerName = printer_name;
							
							//alert(printerName);
							
							//var myPrinter = new JSPM.InstalledPrinter(printerPort,printerName); //9100 ,"192.168.1.215"
							var myPrinter = new JSPM.InstalledPrinter(printerName); //printer name
							
							//var myPrinter = new JSPM.DefaultPrinter(); //9100 ,"192.168.1.215"
							
							cpj.clientPrinter = myPrinter;
							
							//Set PDF file
							var orderPDFPath = htmlContent;
							var currenttime = '<?php echo rand();?>';
							var my_file = new JSPM.PrintFilePDF(orderPDFPath,JSPM.FileSourceType.URL, 'MyFile_'+currenttime+'.pdf', 1);
							
							//var my_file = new JSPM.PrintFile('<?php echo base_url();?>uploads/generate_pdf/251.jpg', JSPM.FileSourceType.URL, 'MyFile.jpg', 1);

							//var my_file = new JSPM.PrintFilePDF($('#txtPdfFile').val(), JSPM.FileSourceType.URL, 'MyFile.pdf', 1);
							//my_file.printRotation = JSPM.PrintRotation[$('#lstPrintRotation').val()];
							//my_file.printRange = $('#txtPagesRange').val();
							//my_file.printAnnotations = $('#chkPrintAnnotations').prop('checked');
							//my_file.printAsGrayscale = $('#chkPrintAsGrayscale').prop('checked');
							//my_file.printInReverseOrder = $('#chkPrintInReverseOrder').prop('checked');

							cpj.files.push(my_file);
							
							//Send print job to printer!
							cpj.sendToClient();
							//updateOrderStatus(orderID);
						}
					}

					function closeShift()
					{
						Swal.fire({
							position: 'top',
							//position: 'top-end',
							icon: 'success',
							title: 'Shift closed successfully!',
							showConfirmButton: false,
							timer: 500,
							width:'350px'
						});  

						window.location = '<?php echo base_url();?>admin/logout';
					}
				</script>
			</ul>
		</div><!-- /main navigation -->
	</div><!-- /sidebar content -->
</div><!-- /main sidebar -->
<script src="<?php echo base_url();?>assets/backend/toastr/sweetalert2@11.js"></script>
<script>
	function logoutAlert() 
	{
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#070d7d',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Logout!'
		}).then((result) => 
		{
			if (result.isConfirmed) 
			{
				window.location = '<?php echo base_url()?>admin/logout';
			}
		});
	}
</script>