<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {
	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;
		
		#Assign all the class objects that were instantiated by the
		#bootstrap file (CodeIgniter.php) to local class variables
		#so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
		$CI =&get_instance();
        $CI->load->database();
		$this->validation = 'onkeypress="return event.charCode >= 48 && event.charCode <= 57 || event.charCode >= 97 && event.charCode <= 122 || event.charCode >= 65 && event.charCode <= 90 || event.charCode == 32"';
		$backup_morning = $CI->db->query('select description from settings where type ="backup_morning"')->result_array();
		$backup_evening = $CI->db->query('select description from settings where type ="backup_evening"')->result_array();
		
		$this->intake =array('1'=>'After Food','2'=>'Before Food');
		$this->backupTime = array('12:00 AM'=>'12:00 AM','12:30 AM'=>'12:30 AM','01:00 AM'=>'01:00 AM','01:30 AM'=>'01:30 AM','02:00 AM'=>'02:00 AM','02:30 AM'=>'02:30 AM','03:00 AM'=>'03:00 AM','03:30 AM'=>'03:30 AM','04:00 AM'=>'04:00 AM','04:30 AM'=>'04:30 AM','05:00 AM'=>'05:00 AM','05:30 AM'=>'05:30 AM','06:00 AM'=>'06:00 AM','06:30 AM'=>'06:30 AM','07:00 AM'=>'07:00 AM','07:30 AM'=>'07:30 AM','08:00 AM'=>'08:00 AM','08:30 AM'=>'08:30 AM','09:00 AM'=>'09:00 AM','09:30 AM'=>'09:30 AM','10:00 AM'=>'10:00 AM','10:30 AM'=>'10:30 AM','11:00 AM'=>'11:00 AM','11:30 AM'=>'11:30 AM','12:00 PM'=>'12:00 PM','12:30 PM'=>'12:30 PM','01:00 PM'=>'01:00 PM','01:30 PM'=>'01:30 PM','02:00 PM'=>'02:00 PM','02:30 PM'=>'02:30 PM','03:00 PM'=>'03:00 PM','03:30 PM'=>'03:30 PM','04:00 PM'=>'04:00 PM','04:30 PM'=>'04:30 PM','05:00 PM'=>'05:00 PM','05:30 PM'=>'05:30 PM','06:00 PM'=>'06:00 PM','06:30 PM'=>'06:30 PM','07:00 PM'=>'07:00 PM','07:30 PM'=>'07:30 PM','08:00 PM'=>'08:00 PM','08:30 PM'=>'08:30 PM','09:00 PM'=>'09:00 PM','09:30 PM'=>'09:30 PM','10:00 PM'=>'10:00 PM','10:30 PM'=>'10:30 PM','11:00 PM'=>'11:00 PM','11:30 PM'=>'11:30 PM');
		defined('BACKUP_MORNING') || define('BACKUP_MORNING', $backup_morning[0]['description']);
		defined('BACKUP_EVENING') || define('BACKUP_EVENING', $backup_evening[0]['description']);
		
		defined('HOST_NAME') || define('HOST_NAME', 'localhost');
		defined('USER_NAME') || define('USER_NAME', 'root');
		defined('DB_PASSWORD') || define('DB_PASSWORD', '');
		defined('DATABASE_NAME') || define('DATABASE_NAME', 'navanedha');
		
		date_default_timezone_set("Asia/Kolkata");
		$date = date('h:i A', time());
		
		# Suresh new changes sta==rt here 30-4-2019
		$this->template ="themes/template"; #Load Front template
		$this->adminTemplate ="backend/template"; #Load admin template

		$this->redirectUrl = substr($_SERVER['REQUEST_URI'],'1');
		$this->date = date("Y-m-d");
		$this->date_time = date("Y-m-d H:i:s");
		$this->active_flag = "Y";

		$this->totalCount = 1;
		$this->pageCount = 2;
		$this->time = "h:i A";
		$this->currentTime = date("H:i:s");

		$this->common_condition = "
				active_flag='Y' and 
				coalesce(start_date,'".$this->date."') <= '".$this->date."' and 
				coalesce(end_date,'".$this->date."') >= '".$this->date."' and
				deleted_flag='N' ";

		$this->user_id = $this->session->userdata('user_id'); # Admin user id - 1
		$this->branch_id = $this->session->userdata('branch_id'); # Admin user id - 1
		$this->organization_id = $this->session->userdata('organization_id');
		
		$this->web_user_id = $this->session->userdata('WebUserID'); #Web User ID / Dine In
		$this->UserID = $this->session->userdata('UserID'); # Front end
		$this->selected_branch = isset($_SESSION["SELECT_BRANCH"]) ? $_SESSION["SELECT_BRANCH"] : NULL; # Front end
		$this->waiter_login = isset($_SESSION["WAITER_LOGIN"]) ? $_SESSION["WAITER_LOGIN"] : NULL;
		$this->selected_table_id = isset($_SESSION["SELECTED_TABLE_ID"]) ? $_SESSION["SELECTED_TABLE_ID"] : NULL;

		$this->dine_in_role_id = isset($_SESSION["DINE_IN_ROLE_ID"]) ? $_SESSION["DINE_IN_ROLE_ID"] : NULL; #Cashier / Waiter
		
		if( $this->waiter_login == NULL )
        {
			$this->waiter_id = NULL;
			$this->customer_id = $this->web_user_id;
        }
        else if( $this->waiter_login != NULL )
        {
			$this->waiter_id = $this->web_user_id;
			$this->customer_id = NUll;
        }

		$this->category_level1_name = 'CATEGORYLEVEL1';
		$this->category_level2_name = 'CATEGORYLEVEL2';
		$this->category_level3_name = 'CATEGORYLEVEL3';

		#Menu URL's

	#Meta Settings start
	/* $meta_language = $CI->db->query('select description from settings where type ="meta_language"')->result_array();
	$meta_robots = $CI->db->query('select description from settings where type ="meta_robots"')->result_array();
	$meta_classification = $CI->db->query('select description from settings where type ="meta_classification"')->result_array();
	$meta_country = $CI->db->query('select description from settings where type ="meta_country"')->result_array();
	
	$meta_title = $CI->db->query('select description from settings where type ="meta_title"')->result_array();
	$meta_subject = $CI->db->query('select description from settings where type ="meta_subject"')->result_array();
	$meta_keywords = $CI->db->query('select description from settings where type ="meta_keywords"')->result_array();
	$meta_description = $CI->db->query('select description from settings where type ="meta_description"')->result_array();
	
	$meta_author = $CI->db->query('select description from settings where type ="meta_author"')->result_array();
	$meta_publisher = $CI->db->query('select description from settings where type ="meta_publisher"')->result_array();
	$meta_copyright = $CI->db->query('select description from settings where type ="meta_copyright"')->result_array();
	$meta_owner = $CI->db->query('select description from settings where type ="meta_owner"')->result_array();
	$google_analytics = $CI->db->query('select description from settings where type ="google_analytics"')->result_array();
	
	defined('META_LANGUAGE') || define('META_LANGUAGE', $meta_language[0]['description']);
	defined('META_ROBOTS') || define('META_ROBOTS', $meta_robots[0]['description']);
	defined('META_CLASSIFICATION') || define('META_CLASSIFICATION', $meta_classification[0]['description']);
	defined('META_COUNTRY') || define('META_COUNTRY', $meta_country[0]['description']);
	
	defined('META_AUTHOR') || define('META_AUTHOR', $meta_author[0]['description']);
	defined('META_PUBLISHER') || define('META_PUBLISHER', $meta_publisher[0]['description']);
	defined('META_COPYRIGHT') || define('META_COPYRIGHT', $meta_copyright[0]['description']);
	defined('META_OWNER') || define('META_OWNER', $meta_owner[0]['description']);
	
	defined('ANALYTICS_CODE') || define('ANALYTICS_CODE', $google_analytics[0]['description']);
	
	*/

	$page_title 		= $CI->db->query('select page_title from seo_settings where active_flag ="Y" ')->result_array();
	$meta_title 		= $CI->db->query('select meta_title from seo_settings where active_flag ="Y" ')->result_array();
	$meta_subject 		= $CI->db->query('select meta_subject from seo_settings where active_flag ="Y"')->result_array();
	$meta_keywords 		= $CI->db->query('select meta_keywords from seo_settings where active_flag ="Y"')->result_array();
	$meta_description 	= $CI->db->query('select meta_description from seo_settings where active_flag ="Y"')->result_array();
	$og_title 			= $CI->db->query('select og_title from seo_settings where active_flag ="Y"')->result_array();
	$og_description 	= $CI->db->query('select og_description from seo_settings where active_flag ="Y"')->result_array();
	$og_url 			= $CI->db->query('select og_url from seo_settings where active_flag ="Y"')->result_array();
	$og_sitename 		= $CI->db->query('select og_sitename from seo_settings where active_flag ="Y"')->result_array();
	$page_url 			= $CI->db->query('select page_url from seo_settings where active_flag ="Y"')->result_array();
	
	defined('PAGE_TITLE') || define('PAGE_TITLE', isset($page_title[0]['page_title']) ? $page_title[0]['page_title'] : "");
	defined('META_TITLE') || define('META_TITLE', isset($meta_title[0]['meta_title']) ? $meta_title[0]['meta_title'] : "");
	defined('META_SUBJECT') || define('META_SUBJECT', isset($meta_subject[0]['meta_subject']) ? $meta_subject[0]['meta_subject'] : "");
	defined('META_KEYWORDS') || define('META_KEYWORDS', isset($meta_keywords[0]['meta_keywords']) ? $meta_keywords[0]['meta_keywords']: "");
	defined('META_DESCRIPTION') || define('META_DESCRIPTION', isset($meta_description[0]['meta_description']) ? $meta_description[0]['meta_description'] : "");
	defined('OG_TITLE') || define('OG_TITLE', isset($og_title[0]['og_title']) ? $og_title[0]['og_title'] : "");
	defined('OG_DESCRIPTION') || define('OG_DESCRIPTION', isset($og_description[0]['og_description']) ? $og_description[0]['og_description'] : "");
	defined('OG_URL') || define('OG_URL', isset($og_url[0]['og_url']) ? $og_url[0]['og_url'] : "");
	defined('OG_SITENAME') || define('OG_SITENAME', isset($og_sitename[0]['og_sitename']) ? $og_sitename[0]['og_sitename'] : "");
	defined('PAGE_URL') || define('PAGE_URL', isset($page_url[0]['page_url']) ? $page_url[0]['page_url'] : "");
	
	#Meta Settings end
		#Tables Starts

		define('emp_departments', 'emp_departments');
		define('table_expense_header', 'expense_header');
		define('table_expense_particulars', 'expense_particulars');
		define('table_expense_type', 'expense_type');
		define('table_po_headers', 'po_headers');
		define('table_rcv_receipt_headers', 'rcv_receipt_headers');
		define('table_ord_sale_headers', 'ord_sale_headers');
		define('table_inv_invoice_headers', 'inv_invoice_headers');
		define('table_inv_invoice_payment_header', 'inv_invoice_payment_header');
		define('table_inv_supplier_payment_header', 'inv_supplier_payment_header');
		define('table_sup_suppliers', 'sup_suppliers');
		define('table_sup_supplier_sites', 'sup_supplier_sites');
		define('table_cus_customers', 'cus_customers');
		define('table_cus_customer_sites', 'cus_customer_sites');
		define('table_per_people_all', 'per_people_all');
		define('table_per_user', 'per_user');
		define('table_per_user_roles', 'per_user_roles');
		define('table_inv_category_banners', 'inv_category_banners');

		#Tables Ends

		#New Menus start
		define('dashboard', 'dashboard');
		define('orders', 'orders');
			define('open_orders', 'open-orders');
			define('manage_orders', 'manage-orders');

		define('home_delivery', 'home-delivery');
			define('home_delivery_creation', 'home-delivery-creation');
			define('home_delivery_list', 'home-delivery-list');

		define('pos', 'pos');
			define('pos_creation', 'pos-creation');
			define('pos_list', 'pos-list');

		define('dinein', 'dinein');
			define('dinein_creation', 'dine-in-creation');
			define('dinein_list', 'dine-in-list');

		define('items', 'items');
			define('item_creation', 'item-creation');
			define('assign_branch_items', 'assign-branch-items');
			define('item_ingredients', 'item-ingredients');
 		
		define('purchase', 'purchase');
			define('purchase_order', 'purchase-order');
			define('purchase_receipt', 'purchase-receipt');
			define('quick_receipt', 'quick-receipt');
		define('sales_order', 'sales-order');

		define('summary', 'summary');
			define('on_hand_availability', 'on-hand-availability');
			define('sales_summary', 'sales-summary');
			define('customer_soa', 'customer-soa');
			define('supplier_soa', 'supplier-soa');

			define('minimum_stock', 'minimum-stock');
			define('item_wise_sales_summary', 'item-wise-sales-summary');
			define('captain_wise_sales_summary', 'captain-wise-sales-summary');

		define('reports', 'reports');
			define('on_hand_availability_report', 'on-hand-availability-report');
			define('sales_report', 'sales-report');
			define('customer_soa_report', 'customer-soa-report');
			define('supplier_soa_report', 'supplier-soa-report');

			define('minimum_stock_report', 'minimum-stock-report');
			define('item_wise_sales_report', 'item-wise-sales-report');
			define('captain_wise_sales_report', 'captain-wise-sales-report');
	
		define('daily_accounts', 'daily-accounts');
			define('cash_expenses', 'cash-expenses');
			define('accounts_supplier_payment', 'accounts-supplier-payment');

		define('payments', 'payments');
			define('customer_payment', 'customer-payment');
			define('supplier_payment', 'supplier-payment');

		define('suppliers', 'suppliers');
			define('manage_suppliers', 'manage-suppliers');	
			define('supplier_sites', 'supplier-sites');

		define('consumers', 'consumers');
			define('consumer_listing', 'consumer-listing');	
			define('consumer_wallet', 'consumer-wallet');

		define('customers', 'customers');
			define('manage_customers', 'manage-customers');	
			define('customer_sites', 'customer-sites');
		
		define('expenses', 'expenses');
			define('manage_expenses', 'manage-expenses');
			define('expense_type', 'expense-type');
			define('expense_category', 'expense-category');

		define('invoice', 'invoice');

		define('employee', 'employees');
		define('employees', 'employees');
		define('users', 'users');
		define('denomination', 'denomination');
		define('banners', 'banners');
		define('customer_banner', 'category-banner');
		define('customer_feedback', 'customer-feedback');
		define('customer_enquires', 'customer-enquires');
		define('push_notification', 'push-notification');
		define('settings', 'settings');
		define('setups', 'setups');
		define('products_items', 'products_items');

		#New Menus end


		#Old app menus start ( not working)
		define('sales', 'sales');	
		define('physical_stock_adjustment', 'physical-stock-adjustment');	
		
	
		define('products', 'products');
			define('manage_products', 'manage-products');
			define('product_price', 'product-price');
			define('assign_product_locator', 'assign-product-locator');

		/* define('expense', 'expense');
		define('expenses', 'expenses');
			define('expense_category', 'expense_category');
			define('particulars', 'particulars');
			define('manage_expenses', 'manage-expenses'); */

		define('backup_database', 'backup-database');
		// define('reports', 'reports');
			// define('gst_report', 'gst-report');
			// define('sales_report', 'sales-report');
			// define('purchase_order_report', 'purchase-order-report');
		define('approvals', 'approvals');
			define('approval_summary', 'approval-summary');
			define('approval_setup', 'approval-setup');
		
		define('application_setting', 'application-setting');
			define('theme_settings', 'theme-settings');
			define('app_sys_settings', 'app-system-settings');
			define('currency_settings', 'currency-settings');
			define('image_settings', 'image-settings');
			define('mailer_settings', 'mailer-settings');
			define('email_contact_settings', 'email-contact-settings');
			define('company_settings', 'company-settings');
		
		define('location_setting', 'location-settings');
			define('country', 'country');
			define('state', 'state');
			define('city', 'city');

		define('system_manager', 'system-manager');
			define('menus', 'menus');
			define('roles', 'roles');
			define('category', 'category');
			define('payment_type', 'payment-type');
			define('uom', 'uom');
			define('tax', 'tax');
			define('brands', 'brands');
			define('discounts', 'discounts');
			define('warehouse', 'warehouse');
			define('hsn_code', 'hsn-code');
			define('offers', 'offers');
			define('date_format', 'date-format');
			define('calendar', 'calendar');
			define('lot_control', 'lot-control');
			define('locators', 'locators');
			define('payment_terms', 'payment-terms');

		define('ledger', 'ledger');
			define('customer_ledger', 'customer-ledger');
			define('supplier_ledger', 'supplier-ledger');
		define('accounts', 'accounts');
		define('bank_accounts', 'bank-accounts');
		#Old app menus end ( not working)

		define('THEME_NAME', 'default');
		define('ADMIN_USER_ID', $this->user_id);
		define('DATE_TIME', date("Y-m-d H:i:s"));
		define('BRANCH_ID', $this->branch_id);
		define('ORGANIZATION_ID', $this->organization_id);
		// define('ADMIN_BRANCH_ID', $this->admin_branch_id);

		$this->thems = array('1'=>'Default','2'=>'Green and Yellow');
		
		$this->items_per_page = array("10" => "10","25" => "25","50" => "50","100" => "100","500" => "500","1500" => "1500");
		$this->gender = array("Male"=>"Male","Female"=>"Female","Transgender"=>"Transgender");
		//$this->discountType = array("1"=>"Disc Percentage","2"=>"Disc Amount");
		$this->discountType = array("1"=>"Disc Percentage");
		$this->marital_status = array("1"=>"Married","2"=> "Unmarried");
		
		$this->from_and_to_age = array(
					"1" => "1",
					"10" => "10",
					"20" => "20",
					"30" => "30",
					"40" => "40",
					"50" => "50",
					"60" => "60",
					"70" => "70",	
					"80" => "80",
					"90" => "90",
					"100" => "100",
			);
		
		$this->timeFormat = array("08:00"=>"08:00","08:15"=>"08:15","08:30"=>"08:30","08:45"=>"08:45","09:00"=>"09:00","09:15"=>"09:15","09:30"=>"09:30","09:45"=>"09:45","10:00"=>"10:00","10:15"=>"10:15","10:30"=>"10:30","10:45"=>"10:45","11:00"=>"11:00","11:15"=>"11:15","11:30"=>"11:30","11:45"=>"11:45","12:00"=>"12:00","12:15"=>"12:15","12:30"=>"12:30","12:45"=>"12:45","13:00"=>"13:00","13:15"=>"13:15","13:30"=>"13:30","13:45"=>"13:45","14:00"=>"14:00","14:15"=>"14:15","14:30"=>"14:30","14:45"=>"14:45","15:00"=>"15:00","15:15"=>"15:15","15:30"=>"15:30","15:45"=>"15:45","16:00"=>"16:00","16:15"=>"16:15","16:30"=>"16:30","16:45"=>"16:45","17:00"=>"17:00","17:15"=>"17:15","17:30"=>"17:30","17:45"=>"17:45","18:00"=>"18:00","18:15"=>"18:15","18:30"=>"18:30","18:45"=>"18:45","19:00"=>"19:00","19:15"=>"19:15","19:30"=>"19:30","19:45"=>"19:45");
		$this->timeMinutes = array("05"=>"5 min","10"=>"10 min","15"=>"15 min","20"=>"20 min","30"=>"30 min","45"=>"45 min","50"=>"50 min","55"=>"55 min","60"=>"1 hr 0 min","90"=>"1 hr 30 min","120"=>"2 hr 0 min","150"=>"2 hr 30 min","180"=>"3 hr 0 min","210"=>"3 hr 30 min","240"=>"4 hr 0 min","270"=>"4 hr 30 min","300"=>"5 hr 0 min");
		$this->dashboardFilter = array("1"=>"Today","2"=>"This Week","3"=>"This Month","4"=>"This Year","5"=>"All Time");
		
		$this->auto_refresh_seconds = array("1000"=>"1 Sec","2000"=>"2 Sec","3000"=>"3 Sec","4000"=>"4 Sec","5000"=>"5 Sec","6000"=>"6 Sec","7000"=>"7 Sec","8000"=>"8 Sec","9000"=>"9 Sec","10000"=>"10 Sec","15000"=>"15 Sec","20000"=>"20 Sec","25000"=>"25 Sec","30000"=>"30 Sec","35000"=>"35 Sec","40000"=>"40 Sec","45000"=>"45 Sec","50000"=>"50 Sec","55000"=>"55 Sec","60000"=>"60 Sec");

		$this->read_only = array("1"=>"Yes","0"=>"No");
		$this->menu_enabled = array("1"=>"Yes","0"=>"No");

		$this->discount_type = array("Amount"=>"Amount","Percentage"=>"Percentage");
		$this->additional_charges = array("1"=>"Amount","2"=>"Percentage");
	
		$this->role_status = array("Y"=>"Active","N"=>"Inactive");
		$this->employeementType = array("1"=>"Permanent","2"=>"Contracter","3"=>"Freelancer");
		$this->pay_frequency = array("1"=>"Day","2"=>"Week","3"=>"Month");
		$this->site_type = array("All"=>"All","BILL_TO"=>"Bill To","SHIP_TO"=>"Ship To");
		$this->approvalStatus = array("Approved"=>"Approved","Rejected"=>"Rejected","Info Requested"=>"Need More Information");
		
		$this->approval_type = array(
			"PO" => "Purchase Order Approval",
			"EXP"=> "Expense Approval",
			/* ,
			"2"=>"Purchase Order Approval",
			"3"=>"Inflow Approval",
			"4"=>"Outflow Approval",
			"5"=>"Budget Expense Approval",
			"6"=>"Invoice Approval" */
		);
		$this->user_type = array("EMP"=>"Employee","CONSUMERS"=>"Customer");

		$this->print_type = array("CASHIER"=>"Cashier","KOT"=>"KOT","STORE_KOT"=>"Store KOT");
		$this->auto_print_status = array("Y"=>"Yes","N"=>"No");
		
		$this->denominations = array("2000" => 2000,"500" => 500,"200" => 200,"100" => 100, "50" => 50,"20" => 20,"10" => 10,"5" => 5,"2" => 2,"1" => 1);
		
		#SMTP Settings start
		$smtp_result = $CI->db->query('select * from email_settings where settings_id =1')->result_array();
		
		if($smtp_result[0]['email_type'] == 1) #Sendgrid
		{
			defined('EMAIL_TYPE') || define('EMAIL_TYPE', $smtp_result[0]['email_type']);
			defined('SENDGRID_HOST') || define('SENDGRID_HOST', $smtp_result[0]['sendgrid_host']);
			defined('SENDGRID_USERNAME') || define('SENDGRID_USERNAME', $smtp_result[0]['sendgrid_username']);
			defined('SENDGRID_PASSWORD') || define('SENDGRID_PASSWORD', $smtp_result[0]['sendgrid_password']);
			defined('SENDGRID_PORT') || define('SENDGRID_PORT', $smtp_result[0]['sendgrid_port']);
		}
		else if($smtp_result[0]['email_type'] == 2) #SMTP
		{
			defined('EMAIL_TYPE') || define('EMAIL_TYPE', $smtp_result[0]['email_type']);
			defined('SMTP_HOST') || define('SMTP_HOST', $smtp_result[0]['smtp_host']);
			defined('SMTP_USERNAME') || define('SMTP_USERNAME', $smtp_result[0]['smtp_username']);
			defined('SMTP_PASSWORD') || define('SMTP_PASSWORD', $smtp_result[0]['smtp_password']);
			defined('SMTP_PORT') || define('SMTP_PORT', $smtp_result[0]['smtp_port']);
		}
		#SMTP Settings end
		
		#General Settings start

		$activeStatusQry = "select sm_list_type_values.list_code,sm_list_type_values.list_value,sm_list_type_values.list_type_value_id from sm_list_type_values 
		left join sm_list_types on sm_list_types.list_type_id = sm_list_type_values.list_type_id
		where 

		sm_list_types.active_flag='Y' and 
		coalesce(sm_list_types.start_date,'".$this->date."') <= '".$this->date."' and 
		coalesce(sm_list_types.end_date,'".$this->date."') >= '".$this->date."' and
		sm_list_types.deleted_flag='N' and


		sm_list_type_values.active_flag='Y' and 
		coalesce(sm_list_type_values.start_date,'".$this->date."') <= '".$this->date."' and 
		coalesce(sm_list_type_values.end_date,'".$this->date."') >= '".$this->date."' and
		sm_list_type_values.deleted_flag='N' and 

		sm_list_types.list_name = 'ACTIVESTATUS'";

		$this->activeStatus = $CI->db->query($activeStatusQry)->result_array(); 


		$company_name = $CI->db->query('select description from settings where type ="company_name"')->result_array();
		$contact_name = $CI->db->query('select description from settings where type ="system_name"')->result_array();
		$system_title = $CI->db->query('select description from settings where type ="system_title"')->result_array();
		$welcome_content = $CI->db->query('select description from settings where type ="welcome_content"')->result_array();
		$company_youtube_url = $CI->db->query('select description from settings where type ="company_youtube_url"')->result_array();
		$gst_number = $CI->db->query('select description from settings where type ="gst_number"')->result_array();
		$fssai_number = $CI->db->query('select description from settings where type ="fssai_number"')->result_array();
		$license_number = $CI->db->query('select description from settings where type ="license_number"')->result_array();
		$company_account = $CI->db->query('select description from settings where type ="company_account"')->result_array();
		
		defined('COMPANY_NAME') || define('COMPANY_NAME', $company_name[0]['description']);
		defined('SITE_NAME') || define('SITE_NAME', $contact_name[0]['description']);
		defined('SITE_TITLE') || define('SITE_TITLE', $system_title[0]['description']);
		defined('WELCOME_CONTENT') || define('WELCOME_CONTENT', $welcome_content[0]['description']);
		defined('YOUTUBE_URL') || define('YOUTUBE_URL', $company_youtube_url[0]['description']);
		
		defined('CONTACT_NAME') || define('CONTACT_NAME', $contact_name[0]['description']);
		
		defined('GST_NUMBER') || define('GST_NUMBER', $gst_number[0]['description']);
		defined('FSSAI_NUMBER') || define('FSSAI_NUMBER', $fssai_number[0]['description']);
		defined('LICENSE_NUMBER') || define('LICENSE_NUMBER', $license_number[0]['description']);
		defined('COMPANY_ACCOUNT') || define('COMPANY_ACCOUNT', $company_account[0]['description']);
	
		#General Settings end
		
		#E-Mail & Contact Settings start
		$contact_email = $CI->db->query('select description from settings where type ="contact_email"')->result_array();
		$address1 = $CI->db->query('select description from settings where type ="address"')->result_array();
		$address2 = $CI->db->query('select description from settings where type ="address2"')->result_array();
		$address3 = $CI->db->query('select description from settings where type ="address3"')->result_array();
		$address4 = $CI->db->query('select description from settings where type ="address4"')->result_array();
		$phone1 = $CI->db->query('select description from settings where type ="phone"')->result_array();
		$phone2 = $CI->db->query('select description from settings where type ="phone2"')->result_array();
		$webmasterEmail = $CI->db->query('select description from settings where type ="webmaster_email"')->result_array();
		$no_reply_email = $CI->db->query('select description from settings where type ="no_reply_email"')->result_array();
		$cin = $CI->db->query('select description from settings where type ="cin_number"')->result_array();
		$opening_hours = $CI->db->query('select description from settings where type ="opening_hours"')->result_array();
		$latitude = $CI->db->query('select description from settings where type ="latitude"')->result_array();
		$longitude = $CI->db->query('select description from settings where type ="longitude"')->result_array();
		
		defined('CONTACT_EMAIL') || define('CONTACT_EMAIL', $contact_email[0]['description']);
		defined('WEBMASTER_EMAIL') || define('WEBMASTER_EMAIL', $webmasterEmail[0]['description']);
		defined('NOREPLY_EMAIL') || define('NOREPLY_EMAIL', $no_reply_email[0]['description']);
		defined('ADDRESS1') || define('ADDRESS1', $address1[0]['description']);
		defined('ADDRESS2') || define('ADDRESS2', $address2[0]['description']);
		defined('ADDRESS3') || define('ADDRESS3', $address3[0]['description']);
		defined('ADDRESS4') || define('ADDRESS4', $address4[0]['description']);
		defined('PHONE1') || define('PHONE1', $phone1[0]['description']);
		defined('PHONE2') || define('PHONE2', $phone2[0]['description']);
		defined('CIN') || define('CIN', $cin[0]['description']);
		defined('OPENING_HOURS') || define('OPENING_HOURS', $opening_hours[0]['description']);
		defined('LATITUDE') || define('LATITUDE', $latitude[0]['description']);
		defined('LONGITUDE') || define('LONGITUDE', $longitude[0]['description']);
		#E-Mail & Contact Settings end

		#TERMSCONDITIONS  start
		$terms = $CI->db->query('select terms_conditions_description from terms_conditions where terms_conditions_default ="1"')->result_array();
		define('TERMS_CONDITIONS', isset($terms[0]['terms_conditions_description']) ? $terms[0]['terms_conditions_description'] :"");
		#TERMSCONDITIONS  end

		#Get State Code start
		$gst_state_number = $CI->db->query('select description from settings where type ="gst_state_number"')->result_array();
		$stateID = isset($gst_state_number[0]['description']) ? $gst_state_number[0]['description'] :"";

		$getStateNumber = $this->db->query("select state_number from geo_states where state_id='".$stateID."' ")->result_array();
		$state_number = isset($getStateNumber[0]['state_number']) ? $getStateNumber[0]['state_number'] : NULL;
		
		defined('STATE_NUMBER') || define('STATE_NUMBER', $state_number);
		#Get State Code End


		$taxQry = "select tax_id,tax_value from gen_tax where active_flag='Y' AND default_tax=1";
		$getTax = $CI->db->query($taxQry)->result_array();
		$tax_value = isset($getTax[0]["tax_value"]) ? $getTax[0]["tax_value"] : NULL;

		defined('DEFAULT_TAX') || define('DEFAULT_TAX', $tax_value);


		#Thems Settings start
		$thems = $CI->db->query('select description from settings where type ="thems"')->result_array();
		define('themes', $thems[0]['description']);
		#Thems Settings end
		
		
		#Country Data start
		$countryData = $CI->db->query('select currency_symbol,currency_code,country_code from geo_countries as country where active_flag ="Y" and default_country ="1"')->result_array();
		
		if( count($countryData) > 0 )
		{
			defined('CURRENCY_SYMBOL') || define('CURRENCY_SYMBOL', $countryData[0]['currency_symbol']);  
			defined('CURRENCY_CODE') || define('CURRENCY_CODE', $countryData[0]['currency_code']);
			defined('COUNTRY_CODE') || define('COUNTRY_CODE', $countryData[0]['country_code']);
		}
		else
		{
			defined('CURRENCY_SYMBOL') || define('CURRENCY_SYMBOL', '');  
			defined('CURRENCY_CODE') || define('CURRENCY_CODE', '');
			defined('COUNTRY_CODE') || define('COUNTRY_CODE', '');
		}
		#Country end start
		
		#System Settings Start
		$getSystemSettings = $CI->db->query('select * from system_settings where system_setting_id ="1"')->result_array();
		
		if( count($countryData) > 0 )
		{
			defined('DECIMAL_VALUE') || define('DECIMAL_VALUE', $getSystemSettings[0]['decimal_digit_value']);  
			defined('HEADER_VALUE') || define('HEADER_VALUE', $getSystemSettings[0]['print_header_note']);  
			defined('FOOTER_VALUE') || define('FOOTER_VALUE', $getSystemSettings[0]['print_footer_note']);    
			defined('MULTI_LOGIN_ACCESS') || define('MULTI_LOGIN_ACCESS', $getSystemSettings[0]['multi_login_access']);    
		}
		else
		{
			defined('DECIMAL_VALUE') || define('DECIMAL_VALUE', 2); 
			defined('HEADER_VALUE') || define('HEADER_VALUE', "");
			defined('FOOTER_VALUE') || define('FOOTER_VALUE', "");  
			defined('MULTI_LOGIN_ACCESS') || define('MULTI_LOGIN_ACCESS', '');      
		}
		#System Settings End

		#BranchQry Start
		if(isset($this->user_id) && $this->user_id==1) #Admin
		{
			defined('AUTO_PRINT_STATUS') || define('AUTO_PRINT_STATUS','');
			defined('CONFIRM_PRINT_STATUS') || define('CONFIRM_PRINT_STATUS','N');
			defined('CAPTAIN_CANCEL_ITEM_STATUS') || define('CAPTAIN_CANCEL_ITEM_STATUS','N');
		}
		else if( isset($this->branch_id) && !empty($this->branch_id) )
		{
			$getBranchQry = $CI->db->query('select 
				auto_print_status,
				order_confirm_print_status,
				captain_canel_item_status 
				from branch 
			where branch_id ="'.$this->branch_id.'" ')->result_array();

			$auto_print_status = isset($getBranchQry[0]['auto_print_status']) ? $getBranchQry[0]['auto_print_status'] : NULL;
			$order_confirm_print_status = isset($getBranchQry[0]['order_confirm_print_status']) ? $getBranchQry[0]['order_confirm_print_status'] : 'N';
			$captain_canel_item_status = isset($getBranchQry[0]['captain_canel_item_status']) ? $getBranchQry[0]['captain_canel_item_status'] : 'N';

			defined('AUTO_PRINT_STATUS') || define('AUTO_PRINT_STATUS',$auto_print_status);
			defined('CONFIRM_PRINT_STATUS') || define('CONFIRM_PRINT_STATUS',$order_confirm_print_status);
			defined('CAPTAIN_CANCEL_ITEM_STATUS') || define('CAPTAIN_CANCEL_ITEM_STATUS',$captain_canel_item_status);
		}
		else
		{
			defined('AUTO_PRINT_STATUS') || define('AUTO_PRINT_STATUS','');
			defined('CONFIRM_PRINT_STATUS') || define('CONFIRM_PRINT_STATUS','N');
			defined('CAPTAIN_CANCEL_ITEM_STATUS') || define('CAPTAIN_CANCEL_ITEM_STATUS','N');
		}
		#BranchQry end

		defined('GOOGLE_SECRET_KEY') || define('GOOGLE_SECRET_KEY', $getSystemSettings[0]['google_secret_key']);
		defined('GOOGLE_CLIENT_ID') || define('GOOGLE_CLIENT_ID',$getSystemSettings[0]['google_client_id']);
		defined('GOOGLE_MAP_API_KEY') || define('GOOGLE_MAP_API_KEY',$getSystemSettings[0]['google_map_api_key']);
		
		defined('AUTO_REFRESH_SECONDS') || define('AUTO_REFRESH_SECONDS',$getSystemSettings[0]['auto_refresh_seconds']);
		defined('ORDER_AUTO_PRINT_TIMER') || define('ORDER_AUTO_PRINT_TIMER',$getSystemSettings[0]['order_auto_print_timer']);
				
		#System Settings Start
		#$getDefaultDateformat = $CI->db->query('select date_format from org_date_formats where date_format_status ="1"')->result_array();
		$getDefaultDateformat = $CI->db->query('select date_format from org_date_formats where active_flag ="Y" and date_format_default = "1" ')->result_array();
		
		if( count($getDefaultDateformat) > 0 )
		{
			defined('DATE_FORMAT') || define('DATE_FORMAT', $getDefaultDateformat[0]['date_format']);  
		}
		else
		{
			defined('DATE_FORMAT') || define('DATE_FORMAT', 'd-M-Y');  
		}  
		
		#Thems Settings start
		$thems = $CI->db->query('select description from settings where type ="thems"')->result_array();
		define('THEME', $thems[0]['description']);
		#Thems Settings end
		
		#Country Data end
		#Social Links start
		$social = $this->frontend_model->get_frontend_general_settings('social_links');
		$links = json_decode($social);
		defined('FACEBOOK') || define('FACEBOOK', $links[0]->facebook);
		defined('TWITTER') || define('TWITTER', $links[0]->twitter);
		defined('GOOGLE_PLUS') || define('GOOGLE_PLUS', $links[0]->google);
		defined('LINKEDIN') || define('LINKEDIN', $links[0]->linkedin);
		defined('YOUTUBE') || define('YOUTUBE', $links[0]->youtube);
		defined('INSTAGRAM') || define('INSTAGRAM', $links[0]->instagram);
		#Social Links end
		# Suresh new changes end here 30-4-2019
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
	
}
