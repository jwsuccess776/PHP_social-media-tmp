<?
$params = get_payment_params('ccbill',$payment->pay_service);
foreach($params as $param)
{
    if($param->psp_name == 'allowedTypes')
    {
        $values = explode(';', $param->psp_value);
        if($values[1])
            if( ((int)($values[1])) == ((int)$payment->pay_samount) )
            {
                $param->psp_value = $values[0];
                $res_params[] = $param;
                break;
            }
    } else {
        $res_params[] = $param;
    }
}

?>
<?php if ($service->psp_type == 'onetime'){?>
    <form action="https://bill.ccbill.com/jpost/signup.cgi" method="POST" name="ccbill">
    	<input type=hidden name="payment_id" value="<?=$payment->pay_paymentid?>">
    <?php foreach ($res_params as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </form>
<?php } elseif ($service->psp_type == 'recurring') {?>
    <form action="https://bill.ccbill.com/jpost/signup.cgi" method="POST" name="ccbill">
    	<input type=hidden name="payment_id" value="<?=$payment->pay_paymentid?>">
    <?php foreach ($res_params as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </form>
<?php } ?>