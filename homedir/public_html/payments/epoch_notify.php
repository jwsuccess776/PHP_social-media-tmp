<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../error.php');
include('../functions.php');

$test_mode = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id =sanitizeData($_POST['x_order'], 'xss_clean') ;   
    $ans =sanitizeData($_POST['ans'], 'xss_clean') ;  
    $transaction_id = formGet('transaction_id');
    $status = (preg_match('/^Y/',$ans)) ? 'Completed' : 'Rejected';
    if (!$test_mode && preg_match('/^YGOODTEST/',$ans))
        $status = 'Test';
    $address=formGet('address');
    $name = formGet('name');
    $city = formGet('city');
    $state = formGet('state');
    $address=formGet('address') . " " . $city . " " . $state;
    $zip = formGet('zip');
    $country = formGet('country');
    $email = formGet('email');
    $tel="";

    $payment = save_payment_details($order_id, $transaction_id, $status, $payment_date, $name, $email, $zip, $country, $address, $tel, $country, 'epoch');
    if (!$payment->pay_paymentid) {
        error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
        die;
    }

    if ($status  == 'Completed') {
        include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
        payment_activation($payment->pay_paymentid);
        js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
        exit;
    }
} else { // GET REDIRECT FROM PAYMENT PROVIDER. NOT TRUSTED
    $order_id =sanitizeData($_REQUEST['x_order'], 'xss_clean') ;    
    $ans =sanitizeData($_REQUEST['ans'], 'xss_clean') ;  
    if (preg_match('/^Y/',$ans)) {
        js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
        exit;
    }
}
js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
exit;
?>
