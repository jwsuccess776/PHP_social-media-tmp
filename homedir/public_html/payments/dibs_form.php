<?php if ($service->psp_type == 'onetime'){

$amount = $payment->pay_samount*100;
$k1 = get_payment_param_by_name('dibs',$payment->pay_service,'k1');
$k2 = get_payment_param_by_name('dibs',$payment->pay_service,'k2');
$currency = get_payment_param_by_name('dibs',$payment->pay_service,'currency');
$merchant = get_payment_param_by_name('dibs',$payment->pay_service,'merchant');
$md5key = md5($k2 . md5($k1 . "merchant=$merchant&orderid=$payment->pay_paymentid&currency=$currency&amount=$amount"));
?>
    <form method="post" target=_blank action="https://payment.architrade.com/payment/start.pml" name="dibs">
          <input type="hidden" name="orderid" value="<?=$payment->pay_paymentid?>" />
          <input type="hidden" name="lang" value="da" />
          <input type="hidden" name="amount" value="<?=$amount?>" />
          <input type="hidden" name="accepturl" value="<?=$CONST_LINK_ROOT."/payments/thankyou.php";?>" />
          <input type="hidden" name="callbackurl" value="<?=$CONST_LINK_ROOT."/payments/dibs_notify.php";?>" />
          <input type="hidden" name="cancelurl" value="<?=$CONST_LINK_ROOT."/payments/cancel.php";?>" />
          <input type="hidden" name="ordertext" value="<?=$payment->pay_message?>" />
          <input type="hidden" name="md5key" value="<?=$md5key?>" />
    <?php foreach (get_payment_params('dibs',$payment->pay_service) as $param) {
          if ($param->psp_name != 'k1' && 
              $param->psp_name != 'k2' && 
              !($param->psp_name == 'test' && empty($param->psp_value))){ ?>
            <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
    <?php }} ?>
    </FORM>
<?php } elseif ($service->psp_type == 'recurring') { ?>
<?php } ?>
