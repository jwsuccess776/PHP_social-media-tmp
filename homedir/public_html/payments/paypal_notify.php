<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../error.php');
include('../functions.php');
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}
// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .="Host: www.paypal.com\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n";
$header .="Connection: close\r\n\r\n";

$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
// assign posted variables to local variables
$receiver_email =sanitizeData($_POST['receiver_email'], 'xss_clean') ; 
$business =sanitizeData($_POST['business'], 'xss_clean') ;  
$item_name =sanitizeData($_POST['item_name'], 'xss_clean') ; 
$item_number =sanitizeData($_POST['item_number'], 'xss_clean') ;
$quantity =sanitizeData($_POST['quantity'], 'xss_clean') ; 
$invoice =sanitizeData($_POST['invoice'], 'xss_clean') ; 
$custom =sanitizeData($_POST['custom'], 'xss_clean') ; 
$option_name1 =sanitizeData($_POST['option_name1'], 'xss_clean') ; 
$option_selection1 =sanitizeData($_POST['option_selection1'], 'xss_clean') ;
$option_name2 =sanitizeData($_POST['option_name2'], 'xss_clean') ;  
$option_selection2 =sanitizeData($_POST['option_selection2'], 'xss_clean') ;  
$num_cart_items =sanitizeData($_POST['num_cart_items'], 'xss_clean') ; 
$payment_status =sanitizeData($_POST['payment_status'], 'xss_clean') ; 
$pending_reason =sanitizeData($_POST['pending_reason'], 'xss_clean') ; 
$payment_date =sanitizeData($_POST['payment_date'], 'xss_clean') ;  
$settle_amount =sanitizeData($_POST['settle_amount'], 'xss_clean') ;
$settle_currency =sanitizeData($_POST['settle_currency'], 'xss_clean') ;
$exchange_rate =sanitizeData($_POST['exchange_rate'], 'xss_clean') ; 
$payment_gross =sanitizeData($_POST['payment_gross'], 'xss_clean') ; 
$payment_fee =sanitizeData($_POST['payment_fee'], 'xss_clean') ;  
$mc_gross =sanitizeData($_POST['mc_gross'], 'xss_clean') ; 
$mc_fee =sanitizeData($_POST['mc_fee'], 'xss_clean') ; 
$mc_currency =sanitizeData($_POST['mc_currency'], 'xss_clean') ;
$tax =sanitizeData($_POST['tax'], 'xss_clean') ;  
$txn_id =sanitizeData($_POST['txn_id'], 'xss_clean') ;  
$txn_type =sanitizeData($_POST['txn_type'], 'xss_clean') ; 
$for_auction =sanitizeData($_POST['for_auction'], 'xss_clean') ; 
$memo =sanitizeData($_POST['memo'], 'xss_clean') ;  
$first_name = sanitizeData($_POST['first_name'], 'xss_clean') ; 
$last_name =sanitizeData($_POST['last_name'], 'xss_clean') ;  
$address_street =sanitizeData($_POST['address_street'], 'xss_clean') ; 
$address_city =sanitizeData($_POST['address_city'], 'xss_clean') ; 
$address_state =sanitizeData($_POST['address_state'], 'xss_clean') ; 
$address_zip =sanitizeData($_POST['address_zip'], 'xss_clean') ; 
$address_country =sanitizeData($_POST['address_country'], 'xss_clean') ; 
$address_status =sanitizeData($_POST['address_status'], 'xss_clean') ; 
$payer_email =sanitizeData($_POST['payer_email'], 'xss_clean') ; 
$payer_id =sanitizeData($_POST['payer_id'], 'xss_clean') ;  
$payer_status =sanitizeData($_POST['payer_status'], 'xss_clean') ;  
$payment_type =sanitizeData($_POST['payment_type'], 'xss_clean') ;  
$notify_version =sanitizeData($_POST['notify_version'], 'xss_clean') ;
$verify_sign =sanitizeData($_POST['verify_sign'], 'xss_clean') ;  
if (!$fp) {
    error_page("Can't check request from PayPal server. Please connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
// HTTP ERROR
} else {
    fputs ($fp, $header . $req);
    while (!feof($fp)) {
        $res = fgets ($fp, 1024);
//        $res= "VERIFIED";
        if (!strcmp ($res, "VERIFIED"))
        {
            $name=trim($first_name)." ".trim($last_name);
            $tel="";
            $address=trim($address_street)." ".trim($address_city)." ".trim($address_state);
            $payment = save_payment_details($custom, $txn_id, $payment_status, $payment_date, $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'paypal');
            if (!$payment->pay_paymentid) {
                error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
                die;
            }
            include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
            if($payment_status == 'Completed')
                payment_activation($payment->pay_paymentid);
            js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
            exit;
        } 
    }
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
    exit; 
}
?>
