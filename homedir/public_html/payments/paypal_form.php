<?php if ($service->psp_type == 'onetime'){?>
    <form method="post" action= "https://www.paypal.com/cgi-bin/webscr" name="paypal">

        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="item_name" value="<?=$payment->pay_message?>">
        <input type="hidden" name="custom" value="<?=$payment->pay_paymentid?>">
        <input type="hidden" name="amount" value="<?=$payment->pay_samount?>">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="return" value="<?=$CONST_LINK_ROOT."/payments/thankyou.php";?>">
        <input type="hidden" name="cancel_return" value="<?=$CONST_LINK_ROOT."/payments/cancel.php";?>">
        <input type="hidden" name="notify_url" value="<?=$CONST_LINK_ROOT."/payments/paypal_notify.php";?>">
        <input type="hidden" name="rm" value="2">

    <?php foreach (get_payment_params('paypal',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </FORM>
<?php } elseif ($service->psp_type == 'recurring') {
switch ($payment->pay_params['period']){
    case 'day'      : $period = 'D'; break;
    case 'week'     : $period = 'W'; break;
    case 'month'    : $period = 'M'; break;
    case 'year'     : $period = 'Y'; break;
}
?>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post"  name="paypal">
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
        <input type="hidden" name="item_name" value="<?=$payment->pay_message?>">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="return" value="<?=$CONST_LINK_ROOT."/payments/thankyou.php";?>">
        <input type="hidden" name="cancel_return" value="<?=$CONST_LINK_ROOT."/payments/cancel.php";?>">
        <input type="hidden" name="notify_url" value="<?=$CONST_LINK_ROOT."/payments/paypal_notify.php";?>">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="custom" value="<?=$payment->pay_paymentid?>">
        <input type="hidden" name="a3" value="<?=$payment->pay_samount?>">
        <input type="hidden" name="p3" value="<?=$payment->pay_params['number']?>">
        <input type="hidden" name="t3" value="<?=$period?>">
        <input type="hidden" name="src" value="1">
        <input type="hidden" name="sra" value="1">
        <input type="hidden" name="usr_manage" value="1">

    <?php foreach (get_payment_params('paypal',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
        </form>
<?php } ?>