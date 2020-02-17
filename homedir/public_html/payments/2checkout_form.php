<?php 
    $params = get_payment_params('2checkout', $payment->pay_service); 
    foreach($params as $param)
    {
        if(substr($param->psp_name, 0, strlen('sid')) == 'sid')
        {
            $values = explode(';', $param->psp_value);
            if($values[0])
            {
                $SID = (int)$values[0]; 
                break;
            }
        }
    }
    reset($params);

    if ($SID < 200000) { 
        if ($service->psp_type == 'onetime') { ?>
<form method="post" action= "https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c" name="2checkout">
  <?php } else { ?>
<form method="post" action= "https://www.2checkout.com/cgi-bin/crbuyers/recpurchase.2c" name="2checkout">
  <?php } ?>
<?php } else { ?>
<form method="post" action= "https://www2.2checkout.com/2co/buyer/purchase" name="2checkout">
<?php } 
    foreach ($params as $param) {
        if(substr($param->psp_name, 0, strlen('product_id')) != 'product_id' && $param->psp_name != 'secret')
        {
            ?>
            <input type="hidden" name="<?=$param->psp_name?>" value="<?=$param->psp_value?>">
            <?php
        }
    }
    reset($params);
    ?>
<?php if ($service->psp_type == 'onetime'){ ?>
    <!--<INPUT type="hidden" value="cc" name="payment_method">-->
    <input type="hidden" name="cart_order_id" value="<?=$payment->pay_paymentid?>">
    <input type="hidden" name="trans_id" value="<?=$payment->pay_paymentid?>">
    <input type="hidden" name="total" value="<?=$payment->pay_samount?>">
<?php } elseif ($service->psp_type == 'recurring') { 
    $product_id = '';
    foreach($params as $param)
    {
        if(substr($param->psp_name, 0, strlen('product_id')) == 'product_id')
        {
            $values = explode(';', $param->psp_value);
            if($values[1])
                if( ((int)($values[1])) == ((int)$payment->pay_samount) )
                {
                    $product_id = $values[0];
                    break;
                }
        }
    } 
?>
    <input type="hidden" name="quantity" value="1">
    <input type="hidden" name="product_id" value="<?=$product_id?>">
    <input type="hidden" name="trans_id" value="<?=$payment->pay_paymentid?>">
<?php } ?>
</form>
