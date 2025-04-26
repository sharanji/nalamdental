<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_model extends CI_Model 
{
	function __construct()
    {
        parent::__construct();
		$this->load->library('session');
    }

	function zReport($offset="",$record="")
	{
		if($_GET)
		{
			if(empty($_GET['branch_id'])){
				$branch_id = 'NULL';
			}else{
				$branch_id = $_GET['branch_id'];
			}

			$fromDate = !empty($_GET['from_date']) ? date_format(date_create($_GET['from_date']),"Y-m-d") : NULL;
			$toDate = !empty($_GET['to_date']) ? date_format(date_create($_GET['to_date']),"Y-m-d") : NULL;

			$query = "select
				t.branch_name,
				t.phone_number,
				
				SUM(t.total_cod_amount)          AS total_cod_amount,
				SUM(t.total_card_amount)         AS total_card_amount,
				SUM(t.total_upi_amount)          AS total_upi_amount,
				SUM(t.total_cash_amount)         AS total_cash_amount,
				SUM(t.total_order_amount)        AS total_order_amount,


				SUM(t.total_cancelled_amount)    AS total_cancelled_amount,
				SUM(t.tax_order_amount)          AS tax_order_amount,
				SUM(t.tax_cod_amount)            AS tax_cod_amount,
				SUM(t.tax_card_amount)           AS tax_card_amount,
				SUM(t.tax_upi_amount)            AS tax_upi_amount,
				SUM(t.tax_cash_amount)           AS tax_cash_amount,
				SUM(t.tax_cancelled_amount)      AS tax_cancelled_amount,
				SUM(t.pos_order_amount)          AS pos_order_amount,
				SUM(t.pos_cod_amount)            AS pos_cod_amount,
				SUM(t.pos_card_amount)           AS pos_card_amount,
				SUM(t.pos_upi_amount)            AS pos_upi_amount,
				SUM(t.pos_cash_amount)           AS pos_cash_amount,
				SUM(t.pos_cancelled_amount)      AS pos_cancelled_amount,

				SUM(t.din_order_amount)          AS din_order_amount,
				SUM(t.din_cod_amount)            AS din_cod_amount,
				SUM(t.din_card_amount)           AS din_card_amount,
				SUM(t.din_upi_amount)            AS din_upi_amount,
				SUM(t.din_cash_amount)           AS din_cash_amount,
				SUM(t.din_cancelled_amount)      AS din_cancelled_amount,


				SUM(t.onl_order_amount)          AS onl_order_amount,
				SUM(t.onl_cod_amount)            AS onl_cod_amount,
				SUM(t.onl_card_amount)           AS onl_card_amount,
				SUM(t.onl_upi_amount)            AS onl_upi_amount,
				SUM(t.onl_cash_amount)           AS onl_cash_amount,
				SUM(t.onl_cancelled_amount)      AS onl_cancelled_amount
				from
				(
					select 
						distinct ord_order_headers.branch_id,
						ord_order_headers.header_id,
						(select branch_name from branch as b1 where b1.branch_id = ord_order_headers.branch_id) branch_name,
						(select mobile_number from branch as b1 where b1.branch_id = ord_order_headers.branch_id) phone_number,

						
						
						(select round(sum((line_tbl1.price * line_tbl1.quantity) - ((line_tbl1.price * line_tbl1.quantity) * (coalesce(line_tbl1.offer_percentage, 0) / 100)) + ((line_tbl1.price * line_tbl1.quantity) * (coalesce(line_tbl1.tax_percentage, 0) / 100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl1
						where header_tbl2.header_id = line_tbl1.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl1.cancel_status != 'Y'
						and header_tbl2.paid_status = 'Y'
						and header_tbl2.payment_method = 1 ) as total_cod_amount,

						(select round(sum((line_tbl2.price * line_tbl2.quantity) - ((line_tbl2.price * line_tbl2.quantity) * (coalesce(line_tbl2.offer_percentage, 0) / 100)) + ((line_tbl2.price * line_tbl2.quantity) * (coalesce(line_tbl2.tax_percentage, 0) / 100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl2
						where header_tbl2.header_id = line_tbl2.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl2.cancel_status != 'Y'
						and header_tbl2.payment_method = 6) as total_card_amount,


						(select round(sum((line_tbl3.price * line_tbl3.quantity) - ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.offer_percentage, 0) / 100)) + - ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl3
						where header_tbl2.header_id = line_tbl3.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl3.cancel_status != 'Y'
						and header_tbl2.payment_method = 7) as total_upi_amount,

						(select round(sum((line_tbl4.price * line_tbl4.quantity) - ((line_tbl4.price * line_tbl4.quantity) * (coalesce(line_tbl4.offer_percentage, 0) / 100)) +  ((line_tbl4.price * line_tbl4.quantity) * (coalesce(line_tbl4.tax_percentage, 0) / 100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl4
						where header_tbl2.header_id = line_tbl4.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl4.cancel_status != 'Y'
						and header_tbl2.payment_method = 5) as total_cash_amount,


						(select round(sum((line_tbl.price * line_tbl.quantity) - ((line_tbl.price * line_tbl.quantity) * (coalesce(line_tbl.offer_percentage, 0) / 100)) + ((line_tbl.price * line_tbl.quantity) * (coalesce(line_tbl.tax_percentage, 0) / 100))),2)
						from ord_order_headers as header_tbl, ord_order_lines as line_tbl
						where header_tbl.header_id = line_tbl.header_id
						and header_tbl.header_id = ord_order_headers.header_id
						and header_tbl.order_status in ('Delivered','Closed')
						and line_tbl.cancel_status != 'Y' ) as total_order_amount,



						(select round(sum((line_tbl5.price * line_tbl5.quantity) - ((line_tbl5.price * line_tbl5.quantity) *(coalesce(line_tbl5.offer_percentage, 0) / 100)) + ((line_tbl5.price * line_tbl5.quantity) *(coalesce(line_tbl5.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl5
						where header_tbl2.header_id = line_tbl5.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl5.cancel_status = 'Y') as total_cancelled_amount,






						(select round(sum((((price * quantity) - ((price * quantity) *(coalesce(offer_percentage, 0) / 100)))*(coalesce(tax_percentage,0)/100))),2)
						from ord_order_headers as header_tbl, ord_order_lines as line_tbl
						where header_tbl.header_id = line_tbl.header_id
						and header_tbl.header_id = ord_order_headers.header_id
						and header_tbl.order_status = 'Delivered'
						and line_tbl.cancel_status != 'Y' ) as tax_order_amount,

						(select round(sum((((price * quantity) -((price * quantity) *(coalesce(offer_percentage, 0) / 100)))*(coalesce(tax_percentage,0)/100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl1
						where header_tbl2.header_id = line_tbl1.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl1.cancel_status != 'Y'
						and header_tbl2.paid_status = 'Y'
						and header_tbl2.payment_method = 1 ) as tax_cod_amount,

						(select round(sum((((price * quantity) -((price * quantity) *(coalesce(offer_percentage, 0) / 100)))*(coalesce(tax_percentage,0)/100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl2
						where header_tbl2.header_id = line_tbl2.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl2.cancel_status != 'Y'
						and header_tbl2.payment_method = 6) as tax_card_amount,

						(select round(sum((((price * quantity) -((price * quantity) *(coalesce(offer_percentage, 0) / 100)))*(coalesce(tax_percentage,0)/100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl3
						where header_tbl2.header_id = line_tbl3.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl3.cancel_status != 'Y'
						and header_tbl2.payment_method = 7) as tax_upi_amount,

						(select round(sum((((price * quantity) -((price * quantity) *(coalesce(offer_percentage, 0) / 100)))*(coalesce(tax_percentage,0)/100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl4
						where header_tbl2.header_id = line_tbl4.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl4.cancel_status != 'Y'
						and header_tbl2.payment_method = 5) as tax_cash_amount,

						(select round(sum((((price * quantity) -((price * quantity) *(coalesce(offer_percentage, 0) / 100)))*(coalesce(tax_percentage,0)/100))),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl5
						where header_tbl2.header_id = line_tbl5.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and line_tbl5.cancel_status = 'Y') as tax_cancelled_amount,




						(select round(sum((line_tbl.price * line_tbl.quantity) - ((line_tbl.price * line_tbl.quantity) *(coalesce(line_tbl.offer_percentage, 0) / 100)) + ((line_tbl.price * line_tbl.quantity) *(coalesce(line_tbl.tax_percentage, 0) / 100))),2)
						from ord_order_headers as header_tbl, ord_order_lines as line_tbl
						where header_tbl.header_id = line_tbl.header_id
						and header_tbl.header_id = ord_order_headers.header_id
						and header_tbl.order_source = 'POS'
						and header_tbl.order_status = 'Delivered'
						and line_tbl.cancel_status != 'Y' ) as pos_order_amount,

						(select round(sum((line_tbl1.price * line_tbl1.quantity) - ((line_tbl1.price * line_tbl1.quantity) *(coalesce(line_tbl1.offer_percentage, 0) / 100)) + ((line_tbl1.price * line_tbl1.quantity) *(coalesce(line_tbl1.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl1
						where header_tbl2.header_id = line_tbl1.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'POS'
						and line_tbl1.cancel_status != 'Y'
						and header_tbl2.paid_status = 'Y'
						and header_tbl2.payment_method = 1 ) as pos_cod_amount,

						(select round(sum((line_tbl2.price * line_tbl2.quantity) - ((line_tbl2.price * line_tbl2.quantity) *(coalesce(line_tbl2.offer_percentage, 0) / 100)) + ((line_tbl2.price * line_tbl2.quantity) *(coalesce(line_tbl2.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl2
						where header_tbl2.header_id = line_tbl2.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'POS'
						and line_tbl2.cancel_status != 'Y'
						and header_tbl2.payment_method = 6) as pos_card_amount,

						(select round(sum((line_tbl3.price * line_tbl3.quantity) - ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.offer_percentage, 0) / 100)) + ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl3
						where header_tbl2.header_id = line_tbl3.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'POS'
						and line_tbl3.cancel_status != 'Y'
						and header_tbl2.payment_method = 7) as pos_upi_amount,

						(select round(sum((line_tbl4.price * line_tbl4.quantity) - ((line_tbl4.price * line_tbl4.quantity) *(coalesce(line_tbl4.offer_percentage, 0) / 100)) + ((line_tbl4.price * line_tbl4.quantity) *(coalesce(line_tbl4.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl4
						where header_tbl2.header_id = line_tbl4.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'POS'
						and line_tbl4.cancel_status != 'Y'
						and header_tbl2.payment_method = 5) as pos_cash_amount,

						(select round(sum((line_tbl5.price * line_tbl5.quantity) - ((line_tbl5.price * line_tbl5.quantity) * (coalesce(line_tbl5.offer_percentage, 0) / 100)) +  ((line_tbl5.price * line_tbl5.quantity) * (coalesce(line_tbl5.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl5
						where header_tbl2.header_id = line_tbl5.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'POS'
						and line_tbl5.cancel_status = 'Y') as pos_cancelled_amount,

						(select round(sum((line_tbl.price * line_tbl.quantity) - ((line_tbl.price * line_tbl.quantity) * (coalesce(line_tbl.offer_percentage, 0) / 100)) + ((line_tbl.price * line_tbl.quantity) * (coalesce(line_tbl.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl, ord_order_lines as line_tbl
						where header_tbl.header_id = line_tbl.header_id
						and header_tbl.header_id = ord_order_headers.header_id
						and header_tbl.order_source = 'DINE_IN'
						and header_tbl.order_status = 'Delivered'
						and line_tbl.cancel_status != 'Y' ) as din_order_amount,

						(select round(sum((line_tbl1.price * line_tbl1.quantity) - ((line_tbl1.price * line_tbl1.quantity) *(coalesce(line_tbl1.offer_percentage, 0) / 100)) + ((line_tbl1.price * line_tbl1.quantity) *(coalesce(line_tbl1.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl1
						where header_tbl2.header_id = line_tbl1.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'DINE_IN'
						and line_tbl1.cancel_status != 'Y'
						and header_tbl2.paid_status = 'Y'
						and header_tbl2.payment_method = 1 ) as din_cod_amount,

						(select round(sum((line_tbl2.price * line_tbl2.quantity) - ((line_tbl2.price * line_tbl2.quantity) *(coalesce(line_tbl2.offer_percentage, 0) / 100)) + ((line_tbl2.price * line_tbl2.quantity) *(coalesce(line_tbl2.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl2
						where header_tbl2.header_id = line_tbl2.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'DINE_IN'
						and line_tbl2.cancel_status != 'Y'
						and header_tbl2.payment_method = 6) as din_card_amount,

						(select round(sum((line_tbl3.price * line_tbl3.quantity) - ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.offer_percentage, 0) / 100)) + ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl3
						where header_tbl2.header_id = line_tbl3.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'DINE_IN'
						and line_tbl3.cancel_status != 'Y'
						and header_tbl2.payment_method = 7) as din_upi_amount,

						(select round(sum((line_tbl4.price * line_tbl4.quantity) - ((line_tbl4.price * line_tbl4.quantity) *(coalesce(line_tbl4.offer_percentage, 0) / 100)) + ((line_tbl4.price * line_tbl4.quantity) *(coalesce(line_tbl4.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl4
						where header_tbl2.header_id = line_tbl4.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'DINE_IN'
						and line_tbl4.cancel_status != 'Y'
						and header_tbl2.payment_method = 5) as din_cash_amount,

						(select round(sum((line_tbl5.price * line_tbl5.quantity) - ((line_tbl5.price * line_tbl5.quantity) *(coalesce(line_tbl5.offer_percentage, 0) / 100)) + ((line_tbl5.price * line_tbl5.quantity) *(coalesce(line_tbl5.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl5
						where header_tbl2.header_id = line_tbl5.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source = 'DINE_IN'
						and line_tbl5.cancel_status = 'Y') as din_cancelled_amount,

						(select round(sum((line_tbl.price * line_tbl.quantity) - ((line_tbl.price * line_tbl.quantity) *(coalesce(line_tbl.offer_percentage, 0) / 100)) + ((line_tbl.price * line_tbl.quantity) *(coalesce(line_tbl.tax_percentage, 0) / 100))  ),2)
						from ord_order_headers as header_tbl, ord_order_lines as line_tbl
						where header_tbl.header_id = line_tbl.header_id
						and header_tbl.header_id = ord_order_headers.header_id
						and header_tbl.order_source in ('ANDROID','IOS')
						and header_tbl.order_status in ('Delivered', 'Closed')
						and line_tbl.cancel_status != 'Y' ) as onl_order_amount,

						(select round(sum((line_tbl1.price * line_tbl1.quantity) - ((line_tbl1.price * line_tbl1.quantity) *(coalesce(line_tbl1.offer_percentage, 0) / 100)) + ((line_tbl1.price * line_tbl1.quantity) *(coalesce(line_tbl1.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl1
						where header_tbl2.header_id = line_tbl1.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source in ('ANDROID','IOS')
						and line_tbl1.cancel_status != 'Y'
						and header_tbl2.paid_status = 'Y'
						and header_tbl2.payment_method = 1 ) as onl_cod_amount,

						(select round(sum((line_tbl2.price * line_tbl2.quantity) - ((line_tbl2.price * line_tbl2.quantity) *(coalesce(line_tbl2.offer_percentage, 0) / 100)) + ((line_tbl2.price * line_tbl2.quantity) *(coalesce(line_tbl2.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl2
						where header_tbl2.header_id = line_tbl2.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source in ('ANDROID','IOS')
						and line_tbl2.cancel_status != 'Y'
						and header_tbl2.payment_method = 6) as onl_card_amount,

						(select round(sum((line_tbl3.price * line_tbl3.quantity) -((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.offer_percentage, 0) / 100)) + ((line_tbl3.price * line_tbl3.quantity) *(coalesce(line_tbl3.tax_percentage, 0) / 100))  ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl3
						where header_tbl2.header_id = line_tbl3.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source in ('ANDROID','IOS')
						and line_tbl3.cancel_status != 'Y'
						and header_tbl2.payment_method = 7) as onl_upi_amount,

						(select round(sum((line_tbl4.price * line_tbl4.quantity) - ((line_tbl4.price * line_tbl4.quantity) *(coalesce(line_tbl4.offer_percentage, 0) / 100)) + ((line_tbl4.price * line_tbl4.quantity) *(coalesce(line_tbl4.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl4
						where header_tbl2.header_id = line_tbl4.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source in ('ANDROID','IOS')
						and line_tbl4.cancel_status != 'Y'
						and header_tbl2.payment_method = 5) as onl_cash_amount,

						(select round(sum((line_tbl5.price * line_tbl5.quantity) - ((line_tbl5.price * line_tbl5.quantity) *(coalesce(line_tbl5.offer_percentage, 0) / 100)) + ((line_tbl5.price * line_tbl5.quantity) *(coalesce(line_tbl5.tax_percentage, 0) / 100)) ),2)
						from ord_order_headers as header_tbl2, ord_order_lines as line_tbl5
						where header_tbl2.header_id = line_tbl5.header_id
						and header_tbl2.header_id = ord_order_headers.header_id
						and header_tbl2.order_source in ('ANDROID','IOS')
						and line_tbl5.cancel_status = 'Y') as onl_cancelled_amount

					FROM ord_order_headers
					left join ord_order_lines on ord_order_lines.header_id = ord_order_headers.header_id
					left join branch on branch.branch_id = ord_order_headers.branch_id
					WHERE 1 = 1
					AND order_status in ('Delivered', 'Closed')
					and ( ord_order_headers.branch_id = coalesce($branch_id,ord_order_headers.branch_id) )
					AND (DATE_FORMAT(ord_order_headers.ordered_date, '%Y-%m-%d') BETWEEN '".$fromDate."' AND '".$toDate."' )
					GROUP BY ord_order_headers.header_id 
				) t
			";
			
			$result = $this->db->query($query)->result_array();

			return $result;
		}
		else
		{
			return array();
		}
	}
}
