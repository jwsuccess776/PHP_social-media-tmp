<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../error.php');
include('../functions.php');

/*
$f = fopen('temp.txt', 'w');
fwrite($f, date('H:i:s')."\n");
fwrite($f, var_export($_POST, true));
fclose($f);
*/
$transact =sanitizeData($_POST['transact'], 'xss_clean') ;  
$transact = urldecode($transact); #  Transaction number � whole numbers consisting of 7 to 9 digits

$paytype =sanitizeData($_POST['paytype'], 'xss_clean') ;
$paytype = urldecode($paytype); # The type of payment the customer has used for this particular payment.
										 # The payment type is returned as a string. Paytype is only returned if you have activated "return of all values" in your
$acquirer =sanitizeData($_POST['acquirer'], 'xss_clean') ;										 # DIBS Admin (under installation + return values)
$acquirer = urldecode($acquirer); # The acquirer used for the specific trasaction. See also our list of valid values for acquirer

$authkey =sanitizeData($_POST['authkey'], 'xss_clean') ;
$authkey = urldecode($authkey); # The MD5 check sum for verification of the authenticity of the transaction. This is only returned if an

$suspect =sanitizeData($_POST['suspect'], 'xss_clean') ;# MD5 key is created within the administration (under installation + MD5 keys).
$suspect = urldecode($suspect); # Is returned if the build in fraud protection evaluates the specific transaction as possible fraud.

$fee =sanitizeData($_POST['fee'], 'xss_clean') ;
$fee = urldecode($fee); # When calcfee is used, the calculated fee is returned so it can be shown on the receipt.

$severity =sanitizeData($_POST['severity'], 'xss_clean') ;
$severity = urldecode($severity); # Whole numbers. Is returned if fraud control has noted the transaction as a potential fraud and if fraud protection
                                           # is activated in the administration interface. The higher the amount, the more questionable the transaction.
                                           # We generally recommend closer checks of transactions with severity > 5
$orderid =sanitizeData($_POST['orderid'], 'xss_clean') ;
$orderid = urldecode($orderid);

$query="SELECT * FROM payments WHERE pay_paymentid = '$orderid'";
$result=mysql_query($query,$link) or die(mysql_error());
$payment = mysql_fetch_object($result);

$amount = $payment->pay_samount*100;
$k1 = get_payment_param_by_name('dibs',$payment->pay_service,'k1');
$k2 = get_payment_param_by_name('dibs',$payment->pay_service,'k2');
$currency = get_payment_param_by_name('dibs',$payment->pay_service,'currency');

if (isset($orderid) && isset($authkey) )
{
    $magic = md5($k2 . md5($k1 . "transact=$transact&amount=$amount&currency=$currency"));
    if ($authkey != $magic) {
        error_page("MD5 check fails. Posted: $authkey",GENERAL_SYSTEM_ERROR);
        exit;
    }
} else {
    error_page("Can't perfome MD5 check. Not all fields needed are present.",GENERAL_SYSTEM_ERROR);
    exit;
}

$payment_status = 'Completed';

$name=$card_holder_name;
$tel="";
$address=trim($street_address)." ".trim($city)." ".trim($state)." ".trim($zip);
dump($_REQUEST);
$payment = save_payment_details($orderid, $transact, $payment_status, $payment_date, $card_holder_name , $email, $zip, $country, $address, $tel, $country, 'dibs');
if (!$payment->pay_paymentid) {
    error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
payment_activation($payment->pay_paymentid);
js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");

?>