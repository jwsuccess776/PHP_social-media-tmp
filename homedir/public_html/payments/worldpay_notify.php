<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../functions.php');
include('../error.php');

// read the post from iBill system and add 'cmd'
mysql_query("insert into payments set pay_notify_log = '".mysql_escape_string(var_export($_REQUEST, true))."'");

if ($transStatus == 'Y') $transStatus = 'Completed';

$pay_paymentid =sanitizeData($_POST[M_userid], 'xss_clean') ;   

$pay_transid =sanitizeData($_POST[transId], 'xss_clean') ;   
$pay_transstatus = $_POST[transStatus] == 'Y' ? 'Completed' : 'Failed';
$pay_transtime =sanitizeData($_POST[transTime], 'xss_clean') ;
$pay_name =sanitizeData($_POST[name], 'xss_clean') ;    
$pay_email =sanitizeData($_POST[email], 'xss_clean') ;  
$pay_postcode =sanitizeData($_POST[postcode], 'xss_clean') ;   
$pay_country =sanitizeData($_POST[country], 'xss_clean') ;    
$pay_address =sanitizeData($_POST[address], 'xss_clean') ;    
$pay_telephone =sanitizeData($_POST[tel], 'xss_clean') ;    
$pay_scountry =sanitizeData($_POST[countryString], 'xss_clean') ;  

// test only 
$pay_transid = 'TEST';
$pay_transstatus = 'Completed';
// test only

$name=trim($first_name)." ".trim($last_name);
$address=trim($address_street)." ".trim($address_city)." ".trim($address_state);
$payment = save_payment_details($pay_paymentid, $pay_transid, $pay_transstatus, $pay_transtime, $pay_name, $pay_email, $pay_postcode, $pay_country, $pay_address, $pay_telephone, $pay_scountry, 'worldpay');
if(!$payment) {
    error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
    die;
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
if ($pay_transid && $pay_transstatus == 'Completed')
{
    payment_activation($payment->pay_paymentid);
    js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
}
else
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
?>
