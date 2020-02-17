<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../functions.php');
include('../error.php');

// read the post from iBill system and add 'cmd'
mysql_query("insert into payments set pay_notify_log = '".mysql_escape_string(var_export($_REQUEST, true))."'");
$pay_paymentid =sanitizeData($_POST[REF1], 'xss_clean') ;  

$pay_transid =sanitizeData($_POST[trans_id], 'xss_clean') ;  
$pay_transstatus = $_POST[APPROVED] == 1 ? 'Completed' : 'Failed';
$pay_transtime = substr($_POST[CHECK], 0, strpos($_POST[CHECK], '$'));
$pay_name =sanitizeData($_POST[Ecom_BillTo_Postal_Name_First], 'xss_clean') .' '. sanitizeData($_POST[Ecom_BillTo_Postal_Name_Last], 'xss_clean');
$pay_email =sanitizeData($_POST[Ecom_ReceiptTo_Online_Email], 'xss_clean') ;    
$pay_postcode =sanitizeData($_POST[Ecom_BillTo_Postal_PostalCode], 'xss_clean') ;  
$pay_country =sanitizeData($_POST[Ecom_BillTo_Postal_CountryCode], 'xss_clean') ;  
$pay_address =sanitizeData($_POST[Ecom_BillTo_Postal_Street_Line1], 'xss_clean') ; 
$pay_telephone = '';
$pay_scountry =sanitizeData($_POST[Ecom_BillTo_Postal_CountryCode], 'xss_clean') ;   

// test only 
$pay_transid = 'TEST';
$pay_transstatus = 'Completed';
// test only

$name=trim($first_name)." ".trim($last_name);
$address=trim($address_street)." ".trim($address_city)." ".trim($address_state);
$payment = save_payment_details($pay_paymentid, $pay_transid, $pay_transstatus, $pay_transtime, $pay_name, $pay_email, $pay_postcode, $pay_country, $pay_address, $pay_telephone, $pay_scountry, 'ibill');
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
