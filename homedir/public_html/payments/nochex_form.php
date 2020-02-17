<?php if ($service->psp_type == 'onetime'){?>

    <form enctype="multipart/form-data" name="nochex" method="post" action="https://secure.nochex.com/">
        <input name="amount" type="hidden" value="<?=$payment->pay_samount?>">
        <input name="order_id" type="hidden" value="<?=$payment->pay_paymentid?>">
        <input name="description" type="hidden" value="<?=$payment->pay_message?>">
    <?php foreach (get_payment_params('nochex',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </form>
<?php } elseif ($service->psp_type == 'recurring') {?>
<?php } ?>