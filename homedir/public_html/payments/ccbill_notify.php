<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../functions.php');
include('../error.php');
// read the post from ccbill system 

$decline =sanitizeData($_POST['reasonForDecline'], 'xss_clean') ;  
$mc_gross =sanitizeData($_POST['initialPrice'], 'xss_clean') ;  
$txn_id =sanitizeData($_POST['subscription_id'], 'xss_clean') ; 
$pending_reason =sanitizeData($_POST['price'], 'xss_clean') ;  
$first_name =sanitizeData($_POST['customer_fname'], 'xss_clean') ; 
$last_name =sanitizeData($_POST['customer_lname'], 'xss_clean') ;  
$payer_email =sanitizeData($_POST['email'], 'xss_clean') ;  
$address_street =sanitizeData($_POST['address1'], 'xss_clean') ; 
$address_city =sanitizeData($_POST['city'], 'xss_clean') ;  
$address_state =sanitizeData($_POST['state'], 'xss_clean') ;  
$address_zip =sanitizeData($_POST['zipcode'], 'xss_clean') ;  
$address_country =sanitizeData($_POST['country'], 'xss_clean') ; 
$tel =sanitizeData($_POST['phone_number'], 'xss_clean') ;  
$payment_id =sanitizeData($_POST['payment_id'], 'xss_clean') ;  
$transid = ($txn_id) ? $txn_id : $decline;
$payment_status = ($txn_id && !$decline) ? 'Completed' : 'Denied';

$name=trim($first_name)." ".trim($last_name);
$address=trim($address_street)." ".trim($address_city)." ".trim($address_state);


$payment = save_payment_details($payment_id, $txn_id, $payment_status,
                              $payment_date, $name, $payer_email,
                              $address_zip, $address_country, $address,
                              $tel, $address_country, 'ccbill');
if(!$payment){
    error_page("Incorrect order id: $payment_id",GENERAL_SYSTEM_ERROR);
    die;
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
if ($payment_status == 'Completed')
{
    payment_activation($payment->pay_paymentid);
    js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
}
else
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
?>
