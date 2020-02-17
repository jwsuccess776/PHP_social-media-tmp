<?
$amount       = $payment->pay_samount*100;
$PAYGATE_ID   = get_payment_param_by_name('paygate', $payment->pay_service, 'PAYGATE_ID');
$key          = get_payment_param_by_name('paygate', $payment->pay_service, 'KEY');
$date         = date("Y-m-d H:i");
$return_url   = "$CONST_LINK_ROOT/payments/paygate_notify.php";
$email        = $db->get_var("SELECT mem_email FROM members WHERE mem_userid=$payment->pay_userid");

$secure_str   = "$PAYGATE_ID|$payment->pay_paymentid|$amount|ZAR|$return_url|$date|$email|$key";
$checksum = md5($secure_str);
?>

<?php if ($service->psp_type == 'onetime'){?>

    <form method="post" action="https://www.paygate.co.za/PayWebv2/process.trans" name="paygate">
	    <input type="hidden" name="PAYGATE_ID" value="<?=$PAYGATE_ID?>">
        <input type="hidden" name="AMOUNT" value="<?=$amount?>">
        <input type="hidden" name="REFERENCE" value="<?=$payment->pay_paymentid?>">
		<input type="hidden" name="CURRENCY" value="ZAR">
		<input type="hidden" name="RETURN_URL" value="<?=$return_url?>">
		<input type="hidden" name="TRANSACTION_DATE" value="<?=$date?>">
		<input type="hidden" name="EMAIL" value="<?=$email?>">
		<input type="hidden" name="CHECKSUM" value="<?=$checksum?>">
    </form>
<?php } elseif ($service->psp_type == 'recurring') {?>
1
<?php } ?>