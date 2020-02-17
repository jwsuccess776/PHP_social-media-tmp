<form name="linkpoint" action="https://secure.linkpt.net/lpcentral/servlet/lppay" method="post">
<input type="hidden" name="mode" value="payonly">
<input type="hidden" name="chargetotal" value="<?php echo $payment->pay_samount;?>">
<input type="hidden" name="comments" value="<?php echo $payment->pay_message?>">
<input type="hidden" name="orderdata" value="<?php echo $payment->pay_paymentid?>">
<input type="hidden" name="responseSuccessURL" value="<?=$CONST_LINK_ROOT?>/payments/linkpoint_notify.php">
<input type="hidden" name="responseFailURL" value="<?=$CONST_LINK_ROOT?>/payments/cancel.php">
<?php if ($service->psp_type == 'onetime'){?>
<?php } elseif ($service->psp_type == 'recurring') {
        $now = getdate();
        $pay_params = $payment->pay_params;
        ?>
    <input type="hidden" name="submode" value="periodic">
    <input type="hidden" name="txntype" value="sale">
    <input type="hidden" name="periodicity" value="<?=$pay_params[period]=='year'?'y':$pay_params[period]=='month'?'m':'d'?><?=$pay_params[number]?>">
    <input type="hidden" name="startdate" value="<?=date('Ymd')?>">
    <input type="hidden" name="installments" value="999">
    <input type="hidden" name="threshold" value="1">
<?php } ?>
<?php foreach (get_payment_params('linkpoint',$payment->pay_service) as $param) { ?>
    <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
<?php } ?>
</form>