<?php if ($service->psp_type == 'onetime'){
    $x_tran_key = get_payment_param_by_name('authorizenet_sim', $payment->pay_service, 'x_tran_key');
    $loginid = get_payment_param_by_name('authorizenet_sim', $payment->pay_service, 'x_login');
	srand(time());
	$sequence = rand(1, 1000);
	$tstamp = time ();
	
	if( phpversion() >= '5.1.2' )
		{ $fingerprint = hash_hmac("md5", $loginid . "^" . $sequence . "^" . $tstamp . "^" . $payment->pay_samount . "^", $x_tran_key); }
	else 
		{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $payment->pay_samount . "^", $x_tran_key)); }

	//$fingerprint = hash_hmac (md5, $x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $payment->pay_samount . "^" . "");

    if (get_payment_param_by_name('authorizenet_sim', $payment->pay_service, 'x_test_request') == 'TRUE'){
?>
		<FORM name="authorizenet_sim" action="https://test.authorize.net/gateway/transact.dll" method="POST">
	<?} else {?>
		<FORM name="authorizenet_sim" action="https://secure.authorize.net/gateway/transact.dll" method="POST">
	<? } ?>
		<input type="hidden" name="x_fp_sequence" value="<?=$sequence?>">
		<input type="hidden" name="x_fp_timestamp" value="<?=$tstamp?>">
		<input type="hidden" name="x_fp_hash" value="<?=$fingerprint?>">
        <input type="hidden" name="x_amount" value="<?=$payment->pay_samount?>">
		<input type="hidden" name="x_description" value="<?=$payment->pay_message?>">
		<input type="hidden" name="x_show_form" value="PAYMENT_FORM">
        <input type="hidden" name="x_invoice_num" value="<?=$payment->pay_paymentid?>">
    <?php foreach (get_payment_params('authorizenet_sim',$payment->pay_service) as $param) {
            if ($param->psp_name != 'x_tran_key'){
    ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php }} ?>
    </FORM>
<?php } elseif ($service->psp_type == 'recurring') {?>
<?php } ?>