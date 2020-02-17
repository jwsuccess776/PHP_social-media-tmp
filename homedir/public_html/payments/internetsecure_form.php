<?php if ($service->psp_type == 'onetime'){
?>
    <FORM action=https://secure.internetsecure.com/process.cgi method=post name="internetsecure"> 
        <input type=hidden name="language" value="French">

    <?php foreach (get_payment_params('internetsecure',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
        <input type="hidden" name="Products" value="Price::Qty::Code::Description::Flags|<?=$payment->pay_samount?>::1::<?=$payment->pay_params['code']?>::<?=$payment->pay_message?>::{CDN}"> 
        <input type="hidden" name="ReturnURL" value="<?=$CONST_LINK_ROOT."/payments/thankyou.php";?>"> 
        <input type="hidden" name="xxxCancelURL" value="<?=$CONST_LINK_ROOT."/payments/cancel.php";?>"> 
        <input type="hidden" name="xxxVar1" value="<?=$payment->pay_paymentid?>">

    </FORM>
<?php } elseif ($service->psp_type == 'recurring') {
?>
    <FORM action=https://secure.internetsecure.com/process.cgi method=post name="internetsecure"> 
        <input type=hidden name="language" value="French">

    <?php foreach (get_payment_params('internetsecure',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
        <input type="hidden" name="Products" value="Price::Qty::Code::Description::Flags|<?=$payment->pay_samount?>::1::<?=$params['code']?>::<?=$payment->pay_message?>::{RB amount=<?=$payment->pay_samount?> startmonth=+<?=$payment->pay_params['number']?> frequency=monthly duration=<?=$payment->pay_params['number']?> email=2}{CDN}"> 
        <input type="hidden" name="ReturnURL" value="<?=$CONST_LINK_ROOT."/payments/thankyou.php";?>"> 
        <input type="hidden" name="xxxCancelURL" value="<?=$CONST_LINK_ROOT."/payments/cancel.php";?>"> 
        <input type="hidden" name="xxxVar1" value="<?=$payment->pay_paymentid?>">

    </FORM>
<?php } ?>