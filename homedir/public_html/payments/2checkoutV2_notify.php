<?
include('../db_connect.php');
include('../error.php');
include('../functions.php');

/* $f = fopen('temp.txt', 'w');
fwrite($f, date('H:i:s')."\n");
fwrite($f, var_export($_POST, true));
fclose($f);
 */

$order_number = urldecode($_POST['order_number']); # 2Checkout.com order number 
$key = urldecode($_POST['key']); # md5 key 
$card_holder_name = urldecode($_POST['card_holder_name']); # Card holder's name 
$street_address = urldecode($_POST['street_address']); # Card holder's address 
$city = urldecode($_POST['city']); # Card holder's city 
$state = urldecode($_POST['state']); # Card holder's state 
$zip = urldecode($_POST['zip']); # Card holder's zip 
$country = urldecode($_POST['country']); # Card holder's country 
$email = urldecode($_POST['email']); # Card holder's email 
$phone = urldecode($_POST['phone']); # Card holder's phone 
$cart_order_id = urldecode($_POST['trans_id']); # Your cart ID number passed in. 
$cart_id = urldecode($_POST['cart_id']); # Your cart ID number passed in. 
$credit_card_processed = urldecode($_POST['credit_card_processed']); # Y if successful, K if waiting for approval 
$total = urldecode($_POST['total']); # Total purchase amount. 
$ship_name = urldecode($_POST['ship_name']); # Shipping information 
$ship_street_address = urldecode($_POST['ship_street_address']); # Shipping information 
$ship_city = urldecode($_POST['ship_city']); # Shipping information 
$ship_state = urldecode($_POST['ship_state']); # Shipping information 
$ship_zip = urldecode($_POST['ship_zip']); # Shipping information 
$ship_country = urldecode($_POST['ship_country']); # Shipping information 

$cartid=$order;

$query="SELECT * FROM payments WHERE pay_paymentid = '$cart_order_id'";
$result=mysql_query($query,$link) or die(mysql_error());
$payment = mysql_fetch_object($result);
$secret = get_payment_param_by_name('2checkout',$payment->pay_service,'secret');
$sid = get_payment_param_by_name('2checkout',$payment->pay_service,'sid');

if (isset($credit_card_processed) && $credit_card_processed == 'Y')
{

    if ($secret) {
        if (isset($order_number) && isset($key) && isset($total))
        {
            if(get_payment_param_by_name('2checkout',$payment->pay_service,'demo') != 'Y')
            {
                $magic = $secret.$sid.$order_number.$total;
                $magic = strtoupper(md5($magic));
                if ($key != $magic) {
                    error_page("MD5 check fails. Posted: $key",GENERAL_SYSTEM_ERROR);
                    exit;
                }
            }
        } else {
            error_page("Can't perfome MD5 check. Not all fields needed are present.",GENERAL_SYSTEM_ERROR);
            exit;
        }
    }
} else {
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
    exit;
}

if ($credit_card_processed == 'Y'){
    $payment_status = 'Completed';
} else {
    $payment_status = $credit_card_processed;
}

$name=$card_holder_name;
$tel="";
$address=trim($street_address)." ".trim($city)." ".trim($state)." ".trim($zip);

$payment = save_payment_details($cart_order_id, $order_number, $payment_status, $payment_date, $card_holder_name , $email, $zip, $country, $address, $tel, $country, '2checkout');
if (!$payment->pay_paymentid) {
    error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
payment_activation($payment->pay_paymentid);
js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");

?>