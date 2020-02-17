<?
$p = get_payment_params('epoch',$payment->pay_service);
foreach($p as $param)
{
    if($param->psp_name == 'pi_code')
    {
        $values = explode(';', $param->psp_value);
        if($values[1])
            if( ((int)($values[1])) == ((int)$payment->pay_samount) )
            {
                $param->psp_value = $values[0];
                $res_params[] = $param;
            }
    } else {
        $res_params[] = $param;
    }
}
?>
<?php if ($service->psp_type == 'onetime'){?>
        <form method="post" action="https://wnu.com/secure/fpost.cgi" name="epoch">
        <input type="hidden" name="reseller" value="a">
        <input type="hidden" name="no_userpass" value="1">
        <input type="hidden" name="response_post" value="1">
        <input type="hidden" name="pi_returnurl" value="<?=$CONST_LINK_ROOT."/payments/epoch_notify.php";?>">
        <input type="hidden" name="x_order" value="<?=$payment->pay_paymentid?>">
        <?php foreach ($res_params as $param) { ?>
            <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
        <?php } ?>
        </FORM>
<?php } elseif ($service->psp_type == 'recurring') {  ?>
        <form method="post" action="https://wnu.com/secure/fpost.cgi" name="epoch">
        <input type="hidden" name="reseller" value="a">
        <input type="hidden" name="no_userpass" value="1">
        <input type="hidden" name="response_post" value="1">
        <input type="hidden" name="pi_returnurl" value="<?=$CONST_LINK_ROOT."/payments/epoch_notify.php";?>">
        <input type="hidden" name="x_order" value="<?=$payment->pay_paymentid?>">
        <?php foreach ($res_params as $param) { ?>
            <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
        <?php } ?>
        </FORM>
<?php } ?>