<?php
include('../db_connect.php');
include('../error.php');
include('../functions.php');

function getStatusDescription($statusResponse) {
    switch ($statusResponse) {
        Case "0"  : $result = "Not done"; break;
        Case "1"  : $result = "Completed"; break;
        Case "2"  : $result = "Declined"; break;
        default   : $result = "Unable to be determined"; break;
    }
    return $result;
}

function getResultDescription($resultResponse) {
    switch ($resultResponse) {
        Case "900001"  : $result = "Call for Approval"; break;
        Case "900002"  : $result = "Card Expired"; break;
        Case "900003"  : $result = "Insufficient Funds"; break;
        Case "900004"  : $result = "Invalid Card Number"; break;
        Case "900005"  : $result = "Bank Interface Timeout"; break;
        Case "900006"  : $result = "Invalid Card"; break;
        Case "900007"  : $result = "Declined"; break;
        Case "900009"  : $result = "Lost Card"; break;
        Case "900010"  : $result = "Invalid Card Length"; break;
        Case "900011"  : $result = "Suspected Fraud"; break;
        Case "900012"  : $result = "Card Reported As Stolen"; break;
        Case "900013"  : $result = "Restricted Card"; break;
        Case "900207"  : $result = "Declined"; break;
        Case "990020"  : $result = "Auth Declined"; break;
        Case "991001"  : $result = "Invalid expiry date"; break;
        Case "991002"  : $result = "Invalid Amount"; break;
        Case "990017"  : $result = "Auth Done"; break;
        Case "900205"  : $result = "Unexpected authentication"; break;
        Case "900206"  : $result = "Unexpected authentication"; break;
        Case "990001"  : $result = "Could not insert into Database"; break;
        Case "990022"  : $result = "Bank not available"; break;
        Case "990053"  : $result = "Error processing transaction"; break;
        Case "990024"  : $result = "Duplicate Transaction Detected"; break;
        Case "990028"  : $result = "Transaction cancelled Customer"; break;
        default   : $result = "Unable to be determined"; break;
    }
    return $result;
}

function getAuthDescription($authResponse) {
    switch ($authResponse) {
        Case "NX"  : $result = "Not Authenticated"; break;
        Case "AX"  : $result = "Authenticated"; break;
        Case "XX"  : $result = "Not Applicable"; break;
        default   : $result = "Unable to be determined"; break;
    }
    return $result;
}

$PAYGATE_ID     = formGet('PAYGATE_ID');
$REFERENCE      = formGet('REFERENCE');
$TRANSACTION_STATUS = formGet('TRANSACTION_STATUS');
$RESULT_CODE    = formGet('RESULT_CODE');
$AUTH_CODE      = formGet('AUTH_CODE');
$AMOUNT         = formGet('AMOUNT');
$RESULT_DESC    = formGet('RESULT_DESC');
$TRANSACTION_ID = formGet('TRANSACTION_ID');
$RISK_INDICATOR = formGet('RISK_INDICATOR');
$CHECKSUM       = formGet('CHECKSUM');

$key= get_payment_param_by_name('paygate','premium','KEY');

if (strlen($CHECKSUM) > 0) {
	$secure_str = "$PAYGATE_ID|$REFERENCE|$TRANSACTION_STATUS|$RESULT_CODE|$AUTH_CODE|$AMOUNT|$RESULT_DESC|$TRANSACTION_ID|$RISK_INDICATOR|$key";
    $checksum = md5($secure_str);
    if ($CHECKSUM != $checksum) {
        error_page("INVALID CHECKSUM",GENERAL_SYSTEM_ERROR);
        die;
    }
} else {
    error_page("INVALID SECURE HASH",GENERAL_SYSTEM_ERROR);
    die;
}

$pay_id = $db->get_var("SELECT pay_paymentid
              FROM payments a
                  INNER JOIN payment_service_params b
                      ON (psp_service = pay_service)
              WHERE pay_paymentid = '$REFERENCE' AND pay_transid=0");
if (!$pay_id) {
    error_page("INVALID ORDER ID",GENERAL_SYSTEM_ERROR);
    die;
}

$payment_status = getStatusDescription($TRANSACTION_STATUS);
$name = $payer_email = $tel= $address_zip = $address_country = $address = $tel = $address_country = "";
$payment = save_payment_details($REFERENCE, $TRANSACTION_ID, $payment_status, date('Y-m-d'), $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'paygate');

if($TRANSACTION_STATUS != "1" or $RISK_INDICATOR[0] == "N") {
    error_page("Payment Server System Error: $RESULT_DESC <br>".getAuthDescription($RISK_INDICATOR) ,GENERAL_SYSTEM_ERROR);
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