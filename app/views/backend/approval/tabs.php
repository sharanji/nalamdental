<?php 
	$PRActive = $POActive = $CashOutFlowActive =
	$ProductActive = $budgetActive = $InvoiceActive = '';
	
	$segment = $this->uri->segment(2);
	
	if( isset($segment) )
	{
		if( $segment == 'pr_approvals' )
		{
			$PRActive = 'active';
		}
		/* else if( $segment == 'po_approvals' )
		{
			$POActive = 'active';
		}
		else if( $segment == 'cashoutflow_approvals' )
		{
			$CashOutFlowActive = 'active';
		}
		else if( $segment == 'budget_expense_approvals' )
		{
			$budgetActive = 'active';
		}
		else if( $segment == 'InvoiceApprovals' )
		{
			$InvoiceActive = 'active';
		}
		else
		{
			$PRActive = 'active';
		} */
	}
	
	if(isset($type) && ($type == 'add' || $type == 'edit' ))
	{
		$tabDisplay ="style='display:none;'";
	}
	else
	{
		$tabDisplay ="";
	}
	
?>
<?php 
	$purchase_order = accessMenu(purchase_order);
	/* $cashflow = accessMenu(request_per_payment);
	$budgetExpense = accessMenu(budget);
	$invoice_payment = accessMenu(invoice_payment); */

	if(
	
		$purchase_order['menu_enabled'] == 1 || 
		$this->user_id == 1
	)
	{
		?>
		<ul class="nav nav-tabs" role="tablist" <?php echo $tabDisplay;?>>
			<?php 
				if($purchase_order['menu_enabled'] == 1 || $this->user_id == 1)
				{ 
					?>
					<li class="nav-item <?php echo $PRActive;?>">
						<a href="<?php echo base_url(); ?>approval/pr_approvals" class="nav-link">Purchase Order Appprovals</a>
					</li>
					<?php 
				} 
			?>
			<?php 
				/* if($purchase_order['menu_enabled'] == 1 || $this->user_id == 1)
				{ 
					?>
					<li class="nav-item <?php echo $POActive;?>">
						<a href="<?php echo base_url(); ?>approval/po_approvals" class="nav-link">PO Appprovals</a>
					</li>
					<?php 
				} */
			?>

			<?php 
				/* if($cashflow['menu_enabled'] == 1 || $this->user_id == 1)
				{ 
					?>
					<li class="nav-item <?php echo $CashOutFlowActive;?>">
						<a href="<?php echo base_url(); ?>approval/cashoutflow_approvals" class="nav-link">Cashoutflow Appprovals</a>
					</li>
					<?php 
				} */
			?>

			<?php 
				/* if($budgetExpense['menu_enabled'] == 1 || $this->user_id == 1)
				{ 
					?>
					<li class="nav-item <?php echo $budgetActive;?>">
						<a href="<?php echo base_url(); ?>approval/budget_expense_approvals" class="nav-link">Budget Expense</a>
					</li>
					<?php 
				} */
			?>

			<?php 
				/* if($invoice_payment['menu_enabled'] == 1 || $this->user_id == 1)
				{ 
					?>
					<li class="nav-item <?php echo $InvoiceActive;?>">
						<a href="<?php echo base_url(); ?>approval/InvoiceApprovals" class="nav-link">
							Invoice Approvals
						</a>
					</li>
					<?php 
				}  */
			?>
		</ul>
		<?php 
	} 
?>