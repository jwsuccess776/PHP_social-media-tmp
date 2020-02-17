<?php if ($service->psp_type == 'onetime'){?>
    <form method="post" action="https://www.paystation.co.nz/dart/darthttp.dll" name="paystation">
        <input type="hidden" name="paystation" value="">
        <input type="hidden" name="am" value="<?=$payment->pay_samount*100?>">
        <input type="hidden" name="ms" value="<?=$payment->pay_paymentid?>">
    <?php foreach (get_payment_params('paystation',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </form>
<?php } elseif ($service->psp_type == 'recurring') {?>
1
<?php } ?>