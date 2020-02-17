<?php 
    $params = get_payment_params('2checkoutV2', $payment->pay_service); 
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
?>
<form method="post" action= "https://www.2checkout.com/checkout/purchase" name="2checkoutV2">
<?php    
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
    <input type='hidden' name='id_type' value='1'>
    <input type='hidden' name='c_prod' value='<?=$payment->pay_paymentid?>'>
    <input type='hidden' name='c_name' value='<?=$payment->pay_message?>'>    
    <input type='hidden' name='c_description' value='<?=$payment->pay_message?>'> 
    <input type='hidden' name='c_price' value='<?=$payment->pay_samount?>'>
    <input type='hidden' name='c_tangible' value='N'>    
    
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
    <input type='hidden' name='id_type' value='2'>
    <input type='hidden' name='c_prod' value='<?=$product_id?>'>
    <input type="hidden" name="quantity" value="1">
    <input type="hidden" name="product_id" value="<?=(int)$product_id?>">
    <input type="hidden" name="trans_id" value="<?=$payment->pay_paymentid?>">
<?php } ?>
</form>
