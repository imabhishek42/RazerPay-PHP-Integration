<?php
include "Razorpay.php";
include "config.php";

use Razorpay\Api\Api;

$config = new RazerPayConfig();
$apiKey = $config->apiKey;
$apiSecret = $config->apiSecret;

/* ---Create Api Object -- 
 * */
$api = new Api($apiKey, $apiSecret);

/* ---Capture Post Request--
 * */
$custName = $_POST['CUSTOMER_NAME'];
$custEmail = $_POST['CUSTOMER_EMAIL'];
$custMobile = $_POST['CUSTOMER_MOBILE'];
$custUid = $_POST['CUSTOMER_UID'];
$custBizid = $_POST['CUSTOMER_BIZID'];
$custDescription = "Description-".$custUid."-".$custBizid;
$custCurrency = $_POST['CUSTOMER_CURRENCY'];

/* --Create Order --
 * */
$order  = $api->order->create([
        'receipt'         => rand(1000,9999).'ord',
        'amount'          => $_POST['CUSTOMER_PAY_AMOUNT']*100, // Multiply by 100 since to convert from paise to rs
        'currency'        => $_POST['CUSTOMER_CURRENCY'], // Enable International Transaction in razerpay account in order to work other than INR
        'payment_capture' =>  '1'
]);

/* ---Display Information before redirecting to payment ---
 * */
if($custName) echo "<center> <h2> Name : ".$custName."<br>";
if($custEmail) echo "Email : ".$custEmail."<br>";
if($custMobile) echo " Phone : ".$custMobile."<br>";;

echo "Amount : ".$_POST['CUSTOMER_PAY_AMOUNT']." ".$custCurrency."</h2>";

?>

<!-- FORM 
    Action: Url to redirect after payment
    Method: POST
-->
<form action="success.php" method="POST">


<script src="https://checkout.razorpay.com/v1/checkout.js"
data-key="<?php echo $apiKey?>"
data-amount="<?php $order->amount?>"
data-currency="<?php echo $order->currency?>"
data-order_id="<?php echo $order->id?>"
data-buttontext="Confirm"
data-name="<?php echo $custName?>"
data-description="<?php echo $description ?>"
data-image="https://cdn.razorpay.com/logos/test_medium.jpeg" // get logo from razerpay portal
data-prefill.name="<?php echo $custUid?>"
data-prefill.email="<?php echo $custEmail?>"
data-prefill.contact="<?php echo $custMobile?>"
data-theme.color="#F37254">
</script>
<input type="hidden" custom="Hidden Element" name="hidden"></form>
