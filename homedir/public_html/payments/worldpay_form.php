<?php if ($service->psp_type == 'onetime'){?>
    <form method="post" action= "https://select.worldpay.com/wcc/purchase" name="worldpay">
        <input type="hidden" name="amount" value="<?php echo $payment->pay_samount?>">
        <input type="hidden" name="M_period" value="<?php echo $payment->pay_params['number']?>">
        <input type="hidden" name="M_userid" value="<?php echo $payment->pay_paymentid?>">
        <input type="hidden" name="cartId" value="<?=$Sess_UserId?>">
    <?php foreach (get_payment_params('worldpay',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </FORM>
<?php } elseif ($service->psp_type == 'recurring') {
    $now = getdate();
    $pay_params = $payment->pay_params;
    switch ($pay_params['period']){
    case 'day'      : $Unit = '1'; break;
    case 'week'     : $Unit = '2'; break;
    case 'month'    : $Unit = '3'; break;
    case 'year'     : $Unit = '4'; break;
}
$Mult = $pay_params[number];
?>

        <form action="https://select.worldpay.com/wcc/purchase" method="post"  name="worldpay">
        <input type="hidden" name="normalAmount" value="<?=$payment->pay_samount?>">
        <input type="hidden" name="amount" value="<?=$payment->pay_samount?>">
        <input type="hidden" name="M_period" value="<?=$payment->pay_params['number']?>">
        <input type="hidden" name="M_userid" value="<?php echo $payment->pay_paymentid?>">
        <input type="hidden" name="futurePayType" value="regular">
        <!--
        <input type="hidden" name="startDate" value="<?=date('Y-m-d', mktime(0,0,0,$now[mon]+($pay_params[period]=='month'? $pay_params[number]:0), $now[mday]+($pay_params[period]=='day'? $pay_params[number]:0), $now[year]+($pay_params[period]=='year'? $pay_params[number]:0)))?>">
        -->
        <input type="hidden" name="option" value="1">

        <input type="hidden" name="startDelayUnit" value="<?=$Unit?>"> 
        <input type="hidden" name="startDelayMult" value="<?=$Mult?>"> 
        <input type="hidden" name="intervalUnit" value="<?=$Unit?>">
        <input type="hidden" name="intervalMult" value="<?=$Mult?>">
        
        
        <input type="hidden" name="cartId" value="<?=$Sess_UserId?>">
    <?php foreach (get_payment_params('worldpay',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
        </form> 
<?php } ?>
