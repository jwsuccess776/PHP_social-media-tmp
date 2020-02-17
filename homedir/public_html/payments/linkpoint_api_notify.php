<?php
include ('../db_connect.php');
include('../session_handler.inc');
include('../functions.php');
include('../error.php');
include(__INCLUDE_CLASS_PATH.'/'."lphp.php");
include_once('../validation_functions.php');

$payment_id = sanitizeData($_REQUEST['payment_id'], 'xss_clean') ;  
if (!$payment_id) die('Incorrect payment_id');

$query = "  SELECT *
            FROM payments
                INNER JOIN members
                    ON (pay_userid = mem_userid)
            WHERE pay_paymentid = $payment_id";
$res=mysql_query($query,$link) or die(mysql_error());
$payment = mysql_fetch_object($res);
$payment->pay_params = unserialize($payment->pay_params);
//print_r($payment);
$query = "  SELECT *
            FROM payment_service_params
            WHERE psp_service = '$payment->pay_service'";
$res = mysql_query($query);
$service = mysql_fetch_object($res);

# retrieve the template
switch ($payment->pay_service) {
    case 'premium' : $area = 'member'; break;
    case 'sd_ticket': $area = 'speeddating'; break;
}
if ($_REQUEST['OK']){
    $linkpoint = new lphp;
    //$order["cbin"] = "true";
//    $order["cpath"] = "/usr/local/bin/curl";

  //  $order["debugging"]     = "true"; // only for developers
    $order["host"]          = "secure.linkpt.net";
    $order["port"]          = "1129";
    $order["ordertype"]     = "SALE";
    $order["chargetotal"]   = $payment->pay_samount;
    $order["comments"]      = $payment->pay_message;
    $order["orderdata"]     = $payment->pay_paymentid;
    $order["cardnumber"]    = $cardnumber;
    $order["cardexpmonth"]  = $cardexpmonth;
    $order["cardexpyear"]   = $cardexpyear;
    if ($cvmvalue) {
        $order["cvmindicator"] = "provided";
        $order["cvmvalue"] = $cvmvalue;
    }
    $order["name"] = $name;
    $order["address"] = $address1;
    $order["state"] = $state;
    $order["zip"] = $zip;
    $order["email"] = $email;

    if ($service->psp_type == 'onetime'){
    } elseif ($service->psp_type == 'recurring') {
        $now = getdate();
        $pay_params = $payment->pay_params;
        $order["action"] = "SUBMIT";
        $order["installments"] = "99";
        $order["threshold"] = "1";
        $order["startdate"] = date('Ymd', mktime(0,0,0,$now['mon']+($pay_params['period']=='month'? $pay_params['number']:0), $now['mday']+($pay_params['period']=='day'? $pay_params['number']:0), $now['year']+($pay_params['period']=='year'? $pay_params['number']:0)));
        $order["periodicity"] = ($pay_params[period]=='year'?'y':$pay_params[period]=='month'?'m':'d').$pay_params['number'];
    }
    foreach (get_payment_params('linkpoint_api',$payment->pay_service) as $param) {
        $order["$param->psp_name"] = $param->psp_value;
    }
//    print_r($order);exit;

    $result = $linkpoint->curl_process($order); # use curl methods
//print_r($result); exit;
    $OKSTATUS = 'APPROVED';
    $OKSTATUS1 = 'SUBMITTED';
    $order["cardnumber"] = "*********";

    if ($result["r_approved"] == $OKSTATUS || $result["r_approved"] == $OKSTATUS1) $result["r_approved"] = 'Completed';

    $tel="";
    $name = "$payment->mem_surname $payment->mem_forename";

    $payment = save_payment_details($payment->pay_paymentid, $result['r_ordernum'], $result["r_approved"], date('Y-m-d H:i'), $name, $payment->mem_email, $zip, $country, $address1, $tel, $address_country, 'linkpoint_api',array('request'=>$order,'responce'=>$result));
    if ($result['r_error'] != '') {
        switch ($result['r_error']) {
            case 'OrderErr_InvalidCardnumber' :     $error = "This is not a valid credit card. Please try another card.";
            case 'OrderErr_UnsupportedCardType' :   $error = "The credit card type is not supported.";break;
            case 'OrderErr_InvalidEmail' :      $error = "E-mail address must be filled in.";break;
            case 'OrderErr_InvalidBname' :      $error = "Customer's name is required for this transaction.";break;
            case 'OrderErr_InvalidBzip' :       $error = "Customer's billing zip code is required for this transaction.";break;
            default : $error = $result['r_error'];
        }
        error_page($error,GENERAL_USER_ERROR);
        die;
    }
    if (!$payment) {
        error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
        die;
    }
    include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");

    if($result["r_approved"] == 'Completed') {
        payment_activation($payment->pay_paymentid);
        Header("Location: $CONST_LINK_ROOT/payments/thankyou.php");exit;
    } else {
        Header("Location: $CONST_LINK_ROOT/payments/cancel.php");exit;
    }
}
?>
<?=$skin->ShowHeader($area)?>
<script>
function check_form (f)
{
  if (f.cardtype.selectedIndex == false)
        { alert('Card Type Must be Filled In!');
          f.cardtype.focus(); return false;}
  if (f.cardnumber.value.length == 0)
        { alert('Credit Card Number Must be Filled In!');
        f.cardnumber.focus(); return false; }
  if(!checkCCNumber(f.cardnumber, f.cardtype.value))
        { alert('Incorrect card number!');
        f.cardnumber.focus();return false;
        }
  if (f.cardexpmonth.selectedIndex == false)
        { alert('Card Expiration Month Must be Filled In!');
          f.cardexpmonth.focus(); return false;}
  if (f.cardexpyear.selectedIndex == false)
        { alert('Card Expiration Year Must be Filled In!');
        f.cardexpyear.focus(); return false;}
  if (f.cvmvalue.value.length == 0)
        { alert('Card Code Must be Filled In!');
          f.cvmvalue.focus(); return false;}

  if (f.address1.value.length == 0)
        { valid = false; alert('Street Address Must be Filled In!');
        f.address1.focus(); return false;}
  if (f.zip.value.length == 0)
        { alert('Postal Code Must be Filled In!');
        f.zip.focus();return false; }
    return true;
}

function isCreditCard(cc,accepted) {

  cc=String(cc);
  if(cc.length<4 || cc.length>30) return false;

  // Start the Mod10 checksum process...
  var checksum=0;

  // Add even digits in even length strings or odd digits in odd length strings.
  for (var location=1-(cc.length%2); location<cc.length; location+=2) {
    var digit=parseInt(cc.substring(location,location+1));
    if(isNaN(digit)) return false;
    checksum+=digit;
  }

  // Analyze odd digits in even length strings
  // or even digits in odd length strings.
  for (var location=(cc.length%2); location<cc.length; location+=2) {
    var digit=parseInt(cc.substring(location,location+1));
    if(isNaN(digit)) return false;
    if(digit<5) checksum+=digit*2;
    else checksum+=digit*2-9;
  }

  if(checksum%10!=0) return false;

  if(accepted!=null) {
    var t=parseInt(cc.substring(0,4)), l=cc.length;
    var type;
    if(t>=3000 && t<3060 && l==14) type="Diners Club";
    else if(t>=3400 && t<3500 && l==15) type="American Express";
    else if(t>=3528 && t<3590 && l==16) type="JCB";
    else if(t>=3600 && t<3700 && l==14) type="Diners Club";
    else if(t>=3700 && t<3800 && l==15) type="American Express";
    else if(t>=3800 && t<3890 && l==14) type="Diners Club";
    else if(t>=3890 && t<3900 && l==14) type="Carte Blanche";
    else if(t>=4000 && t<5000 && (l==13 || l==16)) type="Visa";
    else if(t>=5100 && t<5600 && l==16) type="MasterCard";
    else if(t==5610 && l==16) type="Australian BankCard";
    else if(t==6011 && l==16) type="Discover/Novus";
    else type=null;
    if(accepted!=type) {return false;}
  }
  return true;
}
function checkCCNumber(field_cc,field_accepted) {
  var card_types=new Array();
  var cc=field_cc.value;
  cc = cc.replace(/ |-|\*/g,"");
  var accepted=null;
  if(field_accepted!=null) {
    accepted=new Array(card_types[field_accepted.value]);
  }
  if (isCreditCard(cc,field_accepted)) {
    field_cc.value=cc;
    return true;
  } else {
/*    alert("Invalid credit card number");
    field_cc.focus();
    field_cc.select();*/
    return false;
  }
}

</script>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo PAYMENTS_SECTION_NAME?></td>
  </tr>
  <tr>
    <td>
    <table border="0" cellpadding="2" cellspacing="3" width="100%" height="136">
    <tr>
        <td class="tdhead" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td width=30%>Total</td>
        <td><?=$CONST_SYMBOL.number_format(round($payment->pay_samount,2), 2);?></td>
    </tr>
    <form action="<?=$PHP_SELF?>" method="post" onSubmit="return check_form(this)">
        <input type="hidden" name="payment_id" value="<?=$payment_id?>">
    <tr>
        <td width=30%>Credit card type</td>
        <td valign=middle>
            <select name="cardtype">
                <option value="">
<?/*           <option value="Diners Club">Diners Club*/?>
                <option value="American Express">American Express
<?/*                <option value="JCB">JCB
                <option value="Diners Club">Diners Club*/?>
<?/*                <option value="Diners Club">Diners Club
                <option value="Carte Blanche">Carte Blanche*/?>
                <option value="Visa">Visa
                <option value="MasterCard">MasterCard
<?/*                <option value="Australian BankCard">Australian BankCard*/?>
                <option value="Discover/Novus">Discover/Novus
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;<img border='0' align=absmiddle src='<?=$CONST_IMAGE_ROOT?>/crdcards.gif'>
        </td>
    </tr>
    <tr>
        <td width=30%>*Credit card number</td>
        <td><input type="text" name="cardnumber" value=""></td>
    </tr>
    <tr>
        <td width=30%>*Expire date</td>
        <td>
            <select name="cardexpmonth">
                <option value="">Month
<?for ($i=1;$i<=12;$i++){?>
                <option value="<?=sprintf('%02.2s',$i)?>"><?=sprintf('%02.2s',$i)?>
<?}?>
            </select>
            <select name="cardexpyear">
                <option value="">Year
<?for ($i=date('Y',time());$i<=date('Y',time())+10;$i++){?>
                <option value="<?=substr($i,2,2)?>"><?=$i?>
<?}?>
            </select>
        </td>
    </tr>
    <tr>
        <td width=30%>*Card Code</td>
        <td><input type="text" name="cvmvalue" value="" size=4 maxlength=4></td>
    </tr>
    <tr>
        <td width=30%>Name</td>
        <td><input type="text" name="name" value=""></td>
    </tr>
    <tr>
        <td width=30%>*Street Address</td>
        <td><input type="text" name="address1" value=""></td>
    </tr>
    <tr>
        <td width=30%>State</td>
        <td>
            <select name="state">
            <option value="">Select
            <option value="al">Alabama
            <option value="ak">Alaska
            <option value="az">Arizona
            <option value="ar">Arkansas
            <option value="ca">California
            <option value="co">Colorado
            <option value="ct">Connecticut
            <option value="dc">D.C.
            <option value="de">Delaware
            <option value="fl">Florida
            <option value="ga">Georgia
            <option value="hi">Hawaii
            <option value="id">Idaho
            <option value="il">Illinois
            <option value="in">Indiana
            <option value="ia">Iowa
            <option value="ks">Kansas
            <option value="ky">Kentucky
            <option value="la">Louisiana
            <option value="me">Maine
            <option value="md">Maryland
            <option value="ma">Massachusetts
            <option value="mi">Michigan
            <option value="mn">Minnesota
            <option value="ms">Mississippi
            <option value="mo">Missouri
            <option value="mt">Montana
            <option value="ne">Nebraska
            <option value="nv">Nevada
            <option value="nh">New Hampshire
            <option value="nj">New Jersey
            <option value="nm">New Mexico
            <option value="ny">New York
            <option value="nc">North Carolina
            <option value="nd">North Dakota
            <option value="oh">Ohio
            <option value="ok">Oklahoma
            <option value="or">Oregon
            <option value="pa">Pennsylvania
            <option value="ri">Rhode Island
            <option value="sc">South Carolina
            <option value="sd">South Dakota
            <option value="tn">Tennessee
            <option value="tx">Texas
            <option value="ut">Utah
            <option value="vt">Vermont
            <option value="va">Virginia
            <option value="wa">Washington
            <option value="wv">West Virginia
            <option value="wi">Wisconsin
            <option value="wy">Wyoming
            </select>
        </td>
    </tr>
    <tr>
        <td width=30%>*Zip</td>
        <td><input type="text" name="zip" value=""></td>
    </tr>
    <tr>
        <td width=30%>E-mail Address</td>
        <td><input type="text" name="email" value=""></td>
    </tr>
    <tr>
        <td width=30%></td>
        <td></td>
    </tr>

    <tr>
        <td class="tdfoot" colspan="2" align="center">
            <input type="button" value="Cancel" class="button" onclick="history.back()">
            <input type="submit" value="Checkout" class="button" name="OK">
        </td>
    </tr>
    </form>
    </table>
     </td>
 </tr>
</table>
<?=$skin->ShowFooter($area)?>