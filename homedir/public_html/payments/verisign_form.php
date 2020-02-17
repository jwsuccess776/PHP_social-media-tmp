<?php if ($service->psp_type == 'onetime'){?>
    <form method="post" action= "https://payflowlink.verisign.com/payflowlink.cfm" name="verisign">
        <input type="hidden" name="PARTNER" value="VeriSign">
        <input type="hidden" name="TYPE" value="S">
        <input type="hidden" name="METHOD" value="CC">
        <input type="hidden" name="USER1" value="<?=$payment->pay_paymentid?>">
        <input type="hidden" name="AMOUNT" value="<?=$payment->pay_samount?>">

    <?php foreach (get_payment_params('verisign',$payment->pay_service) as $param) { ?>
        <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php } ?>
    </FORM>
<?php } elseif ($service->psp_type == 'recurring') {
switch ($payment->pay_params['period']){
    case 'day'      : $period = ''; break;    
    case 'week'     : $period = ''; break;    
    case 'month'    : $period = 'M'; break;    
    case 'year'     : $period = ''; break;    
}    
?>
<?php } ?>
