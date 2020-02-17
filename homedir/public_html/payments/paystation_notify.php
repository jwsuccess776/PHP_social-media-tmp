<?php
include('../db_connect.php');
include('../error.php');
include('../functions.php');

$ti = formGet('ti');
$ec = formGet('ec');
$em = formGet('em');
$ms = formGet('ms');
$tm = formGet('tm');

$pay_row = $db->get_row("SELECT *
              FROM payments a
                  INNER JOIN payment_service_params b
                      ON (psp_service = pay_service)
              WHERE pay_paymentid = '$ms' AND pay_transid=0");
if (!$pay_row) {
    error_page("INVALID ORDER ID",GENERAL_SYSTEM_ERROR);
    die;
}

if (lower($tm) != lower(get_payment_param_by_name('paystation', $pay_row->pay_service, 'tm'))){
    error_page("Incorret payment mode" ,GENERAL_SYSTEM_ERROR);
    die;
}

$payment_status = ($ec) ? "Completed" : $ec;
$name = $payer_email = $tel= $address_zip = $address_country = $address = $tel = $address_country = "";
$payment = save_payment_details($ms, $ti, $payment_status, date('Y-m-d'), $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'paystation');

if($ec == 0) {
    error_page("Payment Server System Error: $em <br>" ,GENERAL_SYSTEM_ERROR);
    die;
}

if (!$payment->pay_paymentid) {
    error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
    die;
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
if($payment_status == 'Completed') {
    payment_activation($payment->pay_paymentid);
    js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
    exit;
} else {
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
    exit;
}
?>