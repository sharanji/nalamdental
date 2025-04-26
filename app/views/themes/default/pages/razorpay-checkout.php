<!-- <button class='d-none' id="rzp-button1">Pay</button> -->
<?php //print_r($customerdata);?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "<?php echo $key;?>", // Enter the Key ID generated from the Dashboard
    "amount": "<?php echo $order['amount'];?>", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "<?PHP echo CURRENCY_CODE?>",
    "name": "<?php echo COMPANY_NAME;?>",
    "description": "Test Transaction",
    "image": "<?php echo base_url();'uploads/logo.png'?>",
    "order_id": "<?php echo $razorpay_order['id'];?>", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "callback_url": '<?php echo base_url('web_items/paymentReturnUrl');?>',
    "prefill": {
        "name": "<?php echo isset($customerdata[0]['customer_name']) ? ucfirst($customerdata[0]['customer_name']) :'';?>",
        "email": "<?php echo isset($customerdata[0]['email']) ? ucfirst($customerdata[0]['email']) :'';?>",
        "contact": "<?php echo isset($customerdata[0]['mobile_number']) ? ucfirst($customerdata[0]['mobile_number']) :'';?>"
    },
    "notes": {
        "address": "<?php echo COMPANY_NAME;?>"
    },
    "theme": {
        "color": "#3399cc"
    }
};
var rzp1 = new Razorpay(options);
// document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
// }
// document.getElementById('rzp-button1').click();
</script>