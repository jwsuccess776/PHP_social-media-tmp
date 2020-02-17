<form method="post" name="google_checkout" action="<?=$CONST_LINK_ROOT?>/payments/google_checkout_notify.php">
    <input type="hidden" name="payment_id" value="<?=$payment->pay_paymentid?>">
</form>
