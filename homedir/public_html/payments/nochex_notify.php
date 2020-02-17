<?
include('../db_connect.php');
include('../error.php');
include('../functions.php');


$order_id       = urldecode(formGet('order_id'));
$order_number   = formGet('transaction_id');
$email          = formGet('from_email');


$query="SELECT * FROM payments WHERE pay_paymentid = '$order_id'";
$payment = $db->get_row($query);

$test_mode = get_payment_param_by_name('nochex',$payment->pay_service,'test_transaction');
$payment_status = (formGet('status') == 'live' OR (formGet('status') == 'test' AND  $test_mode == 100)) ? 'Completed' : formGet('status');

$name=$card_holder_name;
$tel="";
$address=trim($street_address)." ".trim($city)." ".trim($state)." ".trim($zip);


$req = '';
foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "$key=$value&";
}
$req = substr($req,0,-1);
$headers = "POST /nochex.dll/apc/apc HTTP/1.0\r\n" 
         . "Content-Type: application/x-www-form-urlencoded\r\n"
         . "Content-Length: ". strlen($req) . "\r\n\r\n";
$fp = fsockopen('www.nochex.com', 80, $errno, $errstr, 10);
if (!$fp) die ("ERROR: fsockopen failed.Error no: $errno - $errstr");
fputs($fp, $headers);
fputs($fp, $req);
$ret = "";
while (!feof($fp)) $ret .= fgets($fp, 1024);
fclose($fp);

if (strstr($ret, "AUTHORISED")) {
    $payment = save_payment_details($order_id, $order_number, $payment_status, $payment_date, $card_holder_name , $email, $zip, $country, $address, $tel, $country, 'nochex');
    if (!$payment->pay_paymentid) {
        error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
    }
    include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
    payment_activation($payment->pay_paymentid);
    js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
} else {
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
    exit; 
}


?>