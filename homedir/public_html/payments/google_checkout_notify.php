<?php
include('../db_connect.php');
include('../error.php');
include('../functions.php');
require_once('library/googlecart.php');
require_once('library/googleitem.php');
include_once('../validation_functions.php');

$payment_id =sanitizeData($_REQUEST['payment_id'], 'xss_clean') ;  
if (!$payment_id) die('Incorrect payment_id');

$query = "  SELECT *
            FROM payments
                INNER JOIN members
                    ON (pay_userid = mem_userid)
            WHERE pay_paymentid = $payment_id";
$res=mysql_query($query,$link) or die(mysql_error());
$payment = mysql_fetch_object($res);

foreach (get_payment_params('google_checkout',$payment->pay_service) as $param)
{
    switch ($param->psp_name)
    {
        case 'merchantID': $merchant_id = $param->psp_value; break;
        case 'merchant_key': $merchant_key = $param->psp_value; break;
        case 'sandbox_test': $server_type = ($param->psp_value == '1')?"sandbox":""; break;
    }
}
$currency = $CONST_CURRENCY;
$cart = new GoogleCart($merchant_id, $merchant_key, $server_type, $currency);
$total_count = 1;
$item_1 = new GoogleItem($payment->pay_message,      // Item name
                         "", // Item description
                         $total_count, // Quantity
                         $payment->pay_samount); // Unit price
$item_1->SetURLDigitalContent($CONST_LINK_ROOT."/payments/thankyou.php",
                              '',
                              $payment->pay_message." is approved.");
$cart->AddItem($item_1);

// Specify <edit-cart-url>
$cart->SetEditCartUrl($CONST_LINK_ROOT."/get_premium.php");

$cart->SetMerchantPrivateData($payment_id);

list($status, $error) = $cart->CheckoutServer2Server();
// if i reach this point, something was wrong
  error_page("An error had ocurred: <br />HTTP Status: " . $status. ":<br />Error message:<br />".$error,GENERAL_SYSTEM_ERROR);

?>
