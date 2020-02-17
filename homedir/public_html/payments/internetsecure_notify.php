<?php
include('../db_connect.php');
include('../error.php');
include('../functions.php');
// read the post from PayPal system and add 'cmd'

$name = formGet('xxxName');
$address_street = formGet('xxxAddress');
$address_city = formGet('xxxCity');
$address_state = formGet('xxxProvince');
$address_zip = formGet('xxxPostal');
$address_country = formGet('xxxCountry');
$tel = formGet('xxxPhone');
$payer_email = formGet('xxxEmail');
$custom = formGet('xxxVar1');
$live = formGet('Live');
$amount = formGet('xxxAmount');
$payment_date = formGet('TimeStamp');
$txn_id = formGet('receiptnumber');
$ApprovalCode = formGet('ApprovalCode');
$NiceVerbage = formGet('NiceVerbage');
$PageNumber = formGet('PageNumber');

if ($live=='1') {
	if ($PageNumber=='90000') {
		$payment_status = 'Completed'; 
		$address=trim($address_street)." ".trim($address_city)." ".trim($address_state);
		$payment = save_payment_details($custom, $txn_id, $payment_status, $payment_date, $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'internetsecure');
		if (!$payment->pay_paymentid) {
			error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
			die;
		}
		include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
		# Not needed as only donating
		# payment_activation($payment->pay_paymentid);
		js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
		exit;
		
	} else {
		$payment_status = 'Declined'; 
		$address=trim($address_street)." ".trim($address_city)." ".trim($address_state);
		$payment = save_payment_details($custom, $txn_id, $payment_status, $payment_date, $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'internetsecure');
		if (!$payment->pay_paymentid) {
			error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
			die;
		}
	}
} 
js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
exit; 
?>
