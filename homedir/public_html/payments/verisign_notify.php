<?php
include('../db_connect.php');
include('../error.php');
include('../functions.php');
include_once('../validation_functions.php');

$txn_id =sanitizeData($_POST['PNREF'], 'xss_clean') ;   
$payer_email =sanitizeData($_POST['EMAIL'], 'xss_clean') ; 
$item_number =sanitizeData($_POST['USER1'], 'xss_clean') ; 
$txn_type =sanitizeData($_POST['RESPMSG'], 'xss_clean') ;  
$payment_status = ($_POST['RESULT'] != 0) ? sanitizeData($_POST['RESULT'], 'xss_clean')  : 'Completed';
$payment_gross =sanitizeData($_POST['AMOUNT'], 'xss_clean') ;
$mc_gross =sanitizeData($_POST['AMOUNT'], 'xss_clean') ;    

$name =sanitizeData($_POST['NAME'], 'xss_clean') ;    
$address_street =sanitizeData($_POST['ADDRESS'], 'xss_clean') ;
$address_city =sanitizeData($_POST['CITY'], 'xss_clean') ;    
$address_state =sanitizeData($_POST['STATE'], 'xss_clean') ;  
$address_zip =sanitizeData($_POST['ZIP'], 'xss_clean') ;   
$address_country =sanitizeData($_POST['COUNTRY'], 'xss_clean') ;   
$pending_reason =sanitizeData($_POST['DESCRIPTION'], 'xss_clean') ;

$tel="";
$address = $address_street." ".$address_city." ".$address_state." ".$address_zip;

$payment = save_payment_details($item_number, $txn_id, $payment_status, date ("l dS of F Y h:i:s A"), $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'verisign');
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