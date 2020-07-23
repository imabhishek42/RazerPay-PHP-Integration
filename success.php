<?php

include "Razorpay.php";
include "config.php";

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$config = new RazerPayConfig();
$apiKey = $config->apiKey;
$apiSecret = $config->apiSecret;

$api = new Api($apiKey, $apiSecret);

/* -- Capture Redirect Url Request --
 * */
$payBotman['paymentid'] = $_POST['razorpay_payment_id'];
$payBotman['orderid'] = $_POST['razorpay_order_id'];
$payBotman['signature'] = $_POST['razorpay_signature'];


/* --Verify Payment --
 * */
$success = true;
$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_POST['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{
    	$html = "<p>Your payment was successful</p>
	     <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
    	$payment = $api->payment->fetch($payBotman['paymentid']); // get all the api in the razerpay api documentation.

	/* -- Create Object Having All the Information --
	 * */
	$payBotman['amount'] =  ($payment->amount)/100;
	$payBotman['currency'] = $payment->currency;
	$payBotman['method'] = $payment->method;
	$payBotman['description'] = $payment->description;
	$uid = explode("-",$payment->description);
	$payBotman['uid'] = $uid[1];
	$payBotman['bizid'] = $uid[2];
	$payBotman['email'] =  $payment->email;
	$payBotman['contact'] =  $payment->contact;
	$payBotman['fee'] =  $payment->fee;
	$payBotman['tax'] =  $payment->tax;
	$payBotman['created_at'] =  $payment->created_at;

	/* -- Store in Database --
	 * */
	print_r($payBotman);


}
else
{
    	$html = "<p>Your payment failed</p>
             <p>{$error}</p>";
}

echo $html;



?>

