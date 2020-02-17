<form method="get" name="paypal_pro" action="<?=preg_replace('/^http/',"https",CONST_LINK_ROOT)?>/payments/paypal_pro_notify.php">
    <input type="hidden" name="payment_id" value="<?=$payment->pay_paymentid?>">
</form>
