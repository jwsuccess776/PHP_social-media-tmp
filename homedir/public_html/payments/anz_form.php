<?php if ($service->psp_type == 'onetime'){?>
    
    <form method="post" action= "<?=$CONST_LINK_ROOT?>/payments/anz_notify.php" name="anz">

        <input type="hidden" name="vpc_Version" value="1">
        <input type="hidden" name="vpc_Command" value="pay">
        <input type="hidden" name="vpc_Amount" value="<?=$payment->pay_samount*100?>">
        <input type="hidden" name="vpc_ReturnURL" value="<?=$CONST_LINK_ROOT."/payments/anz_notify.php";?>">
        <input type="hidden" name="vpc_OrderInfo" value="<?=$payment->pay_paymentid?>">
        <input type="hidden" name="vpc_MerchTxnRef" value="<?=$payment->pay_paymentid?>">

    <?php foreach (get_payment_params('anz',$payment->pay_service) as $param) { 
        if ( $param->psp_name != 'SecureHash') {?>
            <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php }} ?>
        <input type="hidden" name="sender" value="form">
        <input type="hidden" name="service" value="<?=$payment->pay_service?>">

    </FORM>
<?php } elseif ($service->psp_type == 'recurring') {
?>
        <form action="https://migs.mastercard.com.au/vpcpay" method="post"  name="anz">

    <?php foreach (get_payment_params('anz',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
        </form>
<?php } ?>