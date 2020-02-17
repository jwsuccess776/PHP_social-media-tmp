<form method="post" name="authorizenet_arb" action="<?=$CONST_SSL_LINK_ROOT?>/payments/authorizenet_arb_notify.php">
    <input type="hidden" name="payment_id" value="<?=$payment->pay_paymentid?>">
    <input type="submit" name="ARB" value="Pay with Credit Card" class="button">
</form>
