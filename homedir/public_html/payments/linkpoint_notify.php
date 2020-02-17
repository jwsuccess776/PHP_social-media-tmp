<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../error.php');
include('../functions.php');

$txn_id =sanitizeData($_POST['oid'], 'xss_clean') ; 
$payer_email =sanitizeData($_POST['email'], 'xss_clean') ; 
$item_number =sanitizeData($_POST['orderdata'], 'xss_clean') ;

$txn_type =sanitizeData($_POST['status'], 'xss_clean') ; 
$payment_status =sanitizeData($_POST['status'], 'xss_clean') ; 
$payment_gross =sanitizeData($_POST['chargetotal'], 'xss_clean') ; 
$mc_gross =sanitizeData($_POST['chargetotal'], 'xss_clean') ; 

$name =sanitizeData($_POST['bname'], 'xss_clean') ; 
$address_street =sanitizeData($_POST['baddr1'], 'xss_clean') ; 
$address_city =sanitizeData($_POST['bcity'], 'xss_clean') ; 
$address_state =sanitizeData($_POST['bstate'], 'xss_clean') ; 
$address_zip =sanitizeData($_POST['bzip'], 'xss_clean') ; 
$address_country =sanitizeData($_POST['bcountry'], 'xss_clean') ; 
$pending_reason =sanitizeData($_POST['comments'], 'xss_clean') ; 


$OKSTATUS = 'APPROVED';
$OKSTATUS1 = 'SUBMITTED';

if ($payment_status == $OKSTATUS || $payment_status == $OKSTATUS1) $payment_status = 'Completed';


$tel="";
$address = $address_street." ".$address_city." ".$address_state." ".$address_zip;

$payment = save_payment_details($item_number, $txn_id, $payment_status, date ("l dS of F Y h:i:s A"), $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'linkpoint');
if (!$payment) {
    error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
    die;
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
if($payment_status == 'Completed') {
    payment_activation($payment->pay_paymentid);
    js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");exit;
} else {
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");exit;
}

?>
