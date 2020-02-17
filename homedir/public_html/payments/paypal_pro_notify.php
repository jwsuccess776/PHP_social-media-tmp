<?php
include ('../db_connect.php');
//include('../session_handler.inc');
include('../functions.php');
include('../error.php');

ini_set("display_errors",1);
error_reporting(E_ALL^E_NOTICE);

$payment_id = formGet('payment_id');
if (!$payment_id) die('Incorrect payment_id');

//dump($_REQUEST);
// get info by payment id
$payment = $db->get_row("  SELECT *
                           FROM payments
                               INNER JOIN members
                                   ON (pay_userid = mem_userid)
                           WHERE pay_paymentid = $payment_id");

$temp=unserialize($payment->pay_params);

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts($Sess_UserId);

# retrieve the template
$area = 'member';
$nameVar="";
foreach (get_payment_params('paypal_pro',$payment->pay_service) as $param)
{
    $nameVar=$param->psp_name;
    $$nameVar=trim($param->psp_value);
}

if (formGet('OK')) {
    include_once('PayPal.class.php');
    
    $CC_CCNumber = formGet("CC_CCNumber");

    $CC_Country = strtoupper(formGet('CC_Country'));
    $vals = array
    (
        "PaymentAction" => "Sale", # Sale or Auth * REQUIRED
        "CurrencyID" => $CONST_CURRENCY, # 3 digit country code * REQUIRED
        "OrderTotal" => $payment->pay_samount, # Total amount (inc. sh/h) * REQUIRED

        #Credit Card Details
        "FirstName" => formGet('CC_FirstName'), # * REQUIRED
        "LastName" => formGet('CC_LastName'), # * REQUIRED


        "CreditCardType" => formGet("CC_Type"), # * REQUIRED
        "CreditCardNumber" => $CC_CCNumber, # * REQUIRED
        "CVV2" => formGet("CC_CVV"), # * REQUIRED
        "ExpMonth" => formGet("CC_ExpMonth"), # * REQUIRED
        "ExpYear" => formGet("CC_ExpYear"), # * REQUIRED

        # Credit card billing address *PayerEmail is not required-other fields are
        "PayerEmail" => '',
        "PayerStreet1" => formGet("CC_Address"), # * REQUIRED
        "PayerStreet2" => formGet("CC_Address1"),
        "PayerCity" => formGet("CC_City"), # * REQUIRED
        "PayerState" => formGet("CC_State"), # * REQUIRED
        "PayerPostalCode" => formGet("CC_ZipCode"), # * REQUIRED
        "PayerCountry" => $CC_Country, # *Two digit country code * REQUIRED

        # Shipping address info * These may be REQUIRED
        "ShipToName" => formGet("CC_FirstName")." ".formGet("CC_LastName"),
        "ShipToStreet1" => formGet("CC_Address"),
        "ShipToStreet2" => formGet("CC_Address1"),
        "ShipToCity" => formGet("CC_City"),
        "ShipToState" => formGet("CC_State"),
        "ShipToPostalCode" => formGet("CC_ZipCode"),
        "ShipToCountry" => $CC_Country,

        # Additional fields
        "IPAddress" => $_SERVER["REMOTE_ADDR"], # * REQUIRED
        "NotifyURL" => $CONST_LINK_ROOT."/payments/paypal_pro_notify.php",
        "Custom" => $payment_id,
        "InvoiceID" => "",
    );
    $_POST['CC_CCNumber'] = '***************';
    $paypal =& new PayPal();
    $paypal->setCert(CONST_INCLUDE_ROOT."payments/includes/$encrypted_api_certificate");
    $paypal->setHeader($api_username, $api_password);
    $paypal->setCall('DoDirectPayment', $vals);
//dump($paypal);
    $result = $paypal->getResult();
//dump($result);
    $errors = $paypal->getErrors();
//dump($errors);
    $ack=$result["Ack"];
    $name = "$payment->mem_surname $payment->mem_forename";
    include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
    if($ack == 'Success') {
        $_REQUEST['__SESSION_DATA__']["payment_order"]=$_SESSION["payment_order"];
//        $_REQUEST=$_SESSION["payment_order"];
        $payment = save_payment_details($payment->pay_paymentid, $result['TransactionID'], "Completed", date('Y-m-d H:i'), $name, $payment->mem_email, formGet('CC_ZipCode'), $CC_Country, formGet('CC_Address'), "", $CC_Country, 'paypal_pro');
		$db->query("UPDATE payments SET pay_ip = '$_SESSION[USER_CURRENT_IP]', pay_card = '".substr($vals['CreditCardNumber'],-4)."' WHERE pay_paymentid = '$payment->pay_paymentid'");

		require_once(__INCLUDE_CLASS_PATH."/class.PaymentDetails.php");
		$payment_details = new PaymentDetails($Sess_UserId);
		if (!$payment_details->userid) $payment_details->userid = $Sess_UserId;
		$payment_details->card_type = $vals['CreditCardType']; 
		$payment_details->card_number = $vals['CreditCardNumber']; 
		$payment_details->cvv2 = $vals['CVV2']; 
		$payment_details->expire_date = $vals['ExpYear'].'/'.$vals['ExpMonth'].'/01'; 
		$payment_details->first_name = $vals['FirstName']; 
		$payment_details->last_name = $vals['LastName']; 
		$payment_details->country = $vals['PayerCountry']; 
		$payment_details->address1 = $vals['PayerStreet1']; 
		$payment_details->address2 = $vals['PayerStreet2']; 
		$payment_details->state = $vals['PayerState']; 
		$payment_details->city = $vals['PayerCity']; 
		$payment_details->zip = $vals['PayerPostalCode']; 
		$payment_details->save();

        payment_activation($payment->pay_paymentid);

        Header("Location: $CONST_LINK_ROOT/payments/thankyou.php");exit;
    } else {
        //$_REQUEST=$_SESSION["payment_order"];
        $_REQUEST['__SESSION_DATA__']["payment_order"]=$_SESSION["payment_order"];
        $payment = save_payment_details($payment->pay_paymentid, "", "Failure", date('Y-m-d H:i'), $name, $payment->mem_email, formGet('CC_ZipCode'), $CC_Country, formGet('CC_Address'), "", $CC_Country, 'paypal_pro');
        $sql_query="UPDATE payments SET pay_notify_log=concat(pay_notify_log,'\r\n\\".$ask."\n".mysql_escape_string(serialize($errors))."') WHERE pay_paymentid='$payment->pay_paymentid'";
        mysql_query($sql_query);
	    $errors = $paypal->getErrors();
		if (!$errors['ShortMessage']) $errors = $errors[0];
		error_page($errors['ShortMessage']."<br>".$errors['LongMessage'],"Error on the Transaction");
//        Header("Location: $CONST_LINK_ROOT/payments/cancel.php");exit;
    }
/*  print "<br />---------------- full dump ----------------<br />";
    print "<pre>";
    var_dump($result);
    print "</pre>";
    print "<br />---------------- errors ----------------<br />";
    print "<pre>";
    var_dump($errors);
    print "</pre>";
*/
}


?>

<?=$skin->ShowHeader($area)?>
<script>
function check_form (f)
{
  if (f.CC_Type.selectedIndex == false)
        { alert('<?php echo ALERT_CC_TYPE?>');
          f.CC_Type.focus(); return false;}
  if (f.CC_CCNumber.value.length == 0)
        { alert('<?php echo ALERT_CC_PROVNUMBER?>');
        f.CC_CCNumber.focus(); return false; }
  if(!checkCCNumber(f.CC_CCNumber, f.CC_Type.value))
        { alert('<?php echo ALERT_CC_WRONGNUMB?>');
        f.CC_CCNumber.focus();return false;
        }
  if (f.CC_ExpMonth.selectedIndex == false)
        { alert('<?php echo ALERT_CC_EXPMONTH?>');
          f.CC_ExpMonth.focus(); return false;}
  if (f.CC_ExpYear.selectedIndex == false)
        { alert('<?php echo ALERT_CC_EXPYEAR?>');
        f.CC_ExpYear.focus(); return false;}
  if (f.CC_CVV.value.length == 0)
        { alert('<?php echo ALERT_CC_CVV?>');
          f.CC_CVV.focus(); return false;}

  if (f.CC_Address.value.length == 0)
        { valid = false; alert('<?php echo ALERT_CC_ADDRESS?>');
        f.CC_Address.focus(); return false;}
  if (f.CC_City.value.length == 0)
        { alert('<?php echo ALERT_CC_CITY?>');
        f.CC_City.focus();return false; }
  if (f.CC_ZipCode.value.length == 0)
        { alert('<?php echo ALERT_CC_ZIPCODE?>');
        f.CC_ZipCode.focus();return false; }
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
/*    alert("<?php echo ALERT_CC_INVNUMB?>");
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
    <form method="post" name="paypal_pro" action="paypal_pro_notify.php" onSubmit="return check_form(this)">
        <input type="hidden" name="payment_id" value="<?=$payment_id?>">
        <input type="hidden" name="payment_id" value="<?=$payment_id?>">
    <tr>
        <td width=30%><?php echo AUTHORIZENET_CC?></td>
        <td valign=middle>
            <select name="CC_Type">
                <option value="">
                <option value="Amex">American Express
                <option value="Visa">Visa
                <option value="MasterCard">MasterCard
                <option value="Discover">Discover/Novus
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;<img border='0' align=absmiddle src='<?=$CONST_IMAGE_ROOT?>/crdcards.gif'>
        </td>
    </tr>
   <tr>
      <td> <?php echo AUTHORIZENET_CCN?></td>
      <td><input name="CC_CCNumber" type="text"  value="" size="40" class="inputl">
      </td>
   </tr>
   <tr>
      <td> <?php echo AUTHORIZENET_CCV2?></td>
      <td><input name="CC_CVV" type="text"   value="" size="40" class="inputs">
            <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/cvv2.php','<?php echo REGISTER_HELP?>','width=520,height=325')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?= $CONST_IMAGE_LANG ?>/help_but.gif'></a>
    </td>
   </tr>
   <tr>
      <td><?php echo AUTHORIZENET_EXP?></td>
      <td><select name="CC_ExpMonth" class="input">
            <option selected value=""><?php echo SELECT_APAYMENT ?></option>
            <option value="01"><?php echo MONTH_PAYMENT1 ?></option>
            <option value="02"><?php echo MONTH_PAYMENT2 ?></option>
            <option value="03"><?php echo MONTH_PAYMENT3 ?></option>
            <option value="04"><?php echo MONTH_PAYMENT4 ?></option>
            <option value="05"><?php echo MONTH_PAYMENT5 ?></option>
            <option value="06"><?php echo MONTH_PAYMENT6 ?></option>
            <option value="07"><?php echo MONTH_PAYMENT7 ?></option>
            <option value="08"><?php echo MONTH_PAYMENT8 ?></option>
            <option value="09"><?php echo MONTH_PAYMENT9 ?></option>
            <option value="10"><?php echo MONTH_PAYMENT10 ?></option>
            <option value="11"><?php echo MONTH_PAYMENT11 ?></option>
            <option value="12"><?php echo MONTH_PAYMENT12 ?></option>
         </select> <select name="CC_ExpYear" id="select4" class="input">
            <option selected value=""><?php echo SELECT_APAYMENT ?></option>
            <option value="2009">2009</option>
            <option value="2010">2010</option>
            <option value="2011">2011</option>
            <option value="2012">2012</option>
            <option value="2013">2013</option>
            <option value="2014">2014</option>
            <option value="2015">2015</option>
            <option value="2016">2016</option>
            <option value="2017">2017</option>
            <option value="2018">2018</option>
            <option value="2019">2019</option>
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            
         </select></td>
   </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_NAME?></td>
       <td><input name="CC_FirstName" type="text" value="" size="40" class="input"></td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_LASTNAME?></td>
       <td><input name="CC_LastName" type="text" value="" size="40" class="input"></td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_COUNTRY?></td>
       <td>
            <select name="CC_Country" class="inputs">
                <OPTION value='us' ><?php echo PAYMENT_COUNTRY_USA?></OPTION>
                <OPTION value='af' >Afghanistan</OPTION>
                <OPTION value='al' >Albania</OPTION>
                <OPTION value='dz' >Algeria</OPTION>
                <OPTION value='as' >American Samoa</OPTION>
                <OPTION value='ad' >Andorra</OPTION>
                <OPTION value='ao' >Angola</OPTION>
                <OPTION value='ai' >Anguilla</OPTION>
                <OPTION value='aq' >Antarctica</OPTION>
                <OPTION value='ag' >Antigua And Barbuda</OPTION>
                <OPTION value='ar' >Argentina</OPTION>
                <OPTION value='am' >Armenia</OPTION>
                <OPTION value='aw' >Aruba</OPTION>
                <OPTION value='au' >Australia</OPTION>
                <OPTION value='at' >Austria</OPTION>
                <OPTION value='az' >Azerbaijan</OPTION>
                <OPTION value='bs' >Bahamas</OPTION>
                <OPTION value='bh' >Bahrain</OPTION>
                <OPTION value='bd' >Bangladesh</OPTION>
                <OPTION value='bb' >Barbados</OPTION>
                <OPTION value='by' >Belarus</OPTION>
                <OPTION value='be' >Belgium</OPTION>
                <OPTION value='bz' >Belize</OPTION>
                <OPTION value='bj' >Benin</OPTION>
                <OPTION value='bm' >Bermuda</OPTION>
                <OPTION value='bt' >Bhutan</OPTION>
                <OPTION value='bo' >Bolivia</OPTION>
                <OPTION value='ba' >Bosnia And Herzegowina</OPTION>
                <OPTION value='bw' >Botswana</OPTION>
                <OPTION value='bv' >Bouvet Island</OPTION>
                <OPTION value='br' >Brazil</OPTION>
                <OPTION value='io' >British Indian Ocean Territory</OPTION>
                <OPTION value='bn' >Brunei Darussalam</OPTION>
                <OPTION value='bg' >Bulgaria</OPTION>
                <OPTION value='bf' >Burkina Faso</OPTION>
                <OPTION value='bi' >Burundi</OPTION>
                <OPTION value='kh' >Cambodia</OPTION>
                <OPTION value='cm' >Cameroon</OPTION>
                <OPTION value='ca' >Canada</OPTION>
                <OPTION value='cv' >Cape Verde</OPTION>
                <OPTION value='ky' >Cayman Islands</OPTION>
                <OPTION value='cf' >Central African Republic</OPTION>
                <OPTION value='td' >Chad</OPTION>
                <OPTION value='cl' >Chile</OPTION>
                <OPTION value='cn' >China</OPTION>
                <OPTION value='cx' >Christmas Island</OPTION>
                <OPTION value='cc' >Cocos (Keeling) Islands</OPTION>
                <OPTION value='co' >Colombia</OPTION>
                <OPTION value='km' >Comoros</OPTION>
                <OPTION value='cg' >Congo</OPTION>
                <OPTION value='ck' >Cook Islands</OPTION>
                <OPTION value='cr' >Costa Rica</OPTION>
                <OPTION value='ci' >Cote D'Ivoire</OPTION>
                <OPTION value='hr' >Croatia</OPTION>
                <OPTION value='cu' >Cuba</OPTION>
                <OPTION value='cy' >Cyprus</OPTION>
                <OPTION value='cz' >Czech Republic</OPTION>
                <OPTION value='dk' >Denmark</OPTION>
                <OPTION value='dj' >Djibouti</OPTION>
                <OPTION value='dm' >Dominica</OPTION>
                <OPTION value='do' >Dominican Republic</OPTION>
                <OPTION value='tp' >East Timor</OPTION>
                <OPTION value='ec' >Ecuador</OPTION>
                <OPTION value='eg' >Egypt</OPTION>
                <OPTION value='sv' >El Salvador</OPTION>
                <OPTION value='gq' >Equatorial Guinea</OPTION><OPTION value='er' >Eritrea</OPTION><OPTION value='ee' >Estonia</OPTION><OPTION value='et' >Ethiopia</OPTION><OPTION value='fk' >Falkland Islands</OPTION><OPTION value='fo' >Faroe Islands</OPTION><OPTION value='fj' >Fiji</OPTION><OPTION value='fi' >Finland</OPTION><OPTION value='fr' >France</OPTION><OPTION value='fx' >France, Metropolitan</OPTION><OPTION value='gf' >French Guiana</OPTION><OPTION value='pf' >French Polynesia</OPTION><OPTION value='tf' >French Southern Territories</OPTION><OPTION value='ga' >Gabon</OPTION><OPTION value='gm' >Gambia</OPTION><OPTION value='ge' >Georgia</OPTION><OPTION value='de' >Germany</OPTION><OPTION value='gh' >Ghana</OPTION><OPTION value='gi' >Gibraltar</OPTION><OPTION value='gr' >Greece</OPTION><OPTION value='gl' >Greenland</OPTION><OPTION value='gd' >Grenada</OPTION><OPTION value='gp' >Guadeloupe</OPTION><OPTION value='gu' >Guam</OPTION><OPTION value='gt' >Guatemala</OPTION><OPTION value='gn' >Guinea</OPTION><OPTION value='gw' >Guinea-Bissau</OPTION><OPTION value='gy' >Guyana</OPTION><OPTION value='ht' >Haiti</OPTION><OPTION value='hm' >Heard And Mc Donald Islands</OPTION><OPTION value='hn' >Honduras</OPTION><OPTION value='hk' >Hong Kong</OPTION><OPTION value='hu' >Hungary</OPTION><OPTION value='is' >Iceland</OPTION><OPTION value='in' >India</OPTION><OPTION value='id' >Indonesia</OPTION><OPTION value='en' >International</OPTION><OPTION value='ir' >Iran</OPTION><OPTION value='iq' >Iraq</OPTION><OPTION value='ie' >Ireland</OPTION><OPTION value='il' >Israel</OPTION><OPTION value='it' >Italy</OPTION><OPTION value='jm' >Jamaica</OPTION><OPTION value='jp' >Japan</OPTION><OPTION value='jo' >Jordan</OPTION><OPTION value='kz' >Kazakhstan</OPTION><OPTION value='ke' >Kenya</OPTION><OPTION value='ki' >Kiribati</OPTION><OPTION value='kw' >Kuwait</OPTION><OPTION value='kg' >Kyrgyzstan</OPTION><OPTION value='la' >Lao People's Republic</OPTION><OPTION value='lv' >Latvia</OPTION><OPTION value='lb' >Lebanon</OPTION><OPTION value='ls' >Lesotho</OPTION><OPTION value='lr' >Liberia</OPTION><OPTION value='ly' >Libyan Arab Jamahiriya</OPTION><OPTION value='li' >Liechtenstein</OPTION><OPTION value='lt' >Lithuania</OPTION><OPTION value='lu' >Luxembourg</OPTION><OPTION value='mo' >Macau</OPTION><OPTION value='mk' >Macedonia</OPTION><OPTION value='mg' >Madagascar</OPTION><OPTION value='mw' >Malawi</OPTION><OPTION value='my' >Malaysia</OPTION><OPTION value='mv' >Maldives</OPTION><OPTION value='ml' >Mali</OPTION><OPTION value='mt' >Malta</OPTION><OPTION value='mh' >Marshall Islands</OPTION><OPTION value='mq' >Martinique</OPTION><OPTION value='mr' >Mauritania</OPTION><OPTION value='mu' >Mauritius</OPTION><OPTION value='yt' >Mayotte</OPTION><OPTION value='mx' >Mexico</OPTION><OPTION value='fm' >Micronesia</OPTION><OPTION value='md' >Moldova</OPTION><OPTION value='mc' >Monaco</OPTION><OPTION value='mn' >Mongolia</OPTION><OPTION value='ms' >Montserrat</OPTION><OPTION value='ma' >Morocco</OPTION><OPTION value='mz' >Mozambique</OPTION><OPTION value='mm' >Myanmar</OPTION><OPTION value='na' >Namibia</OPTION><OPTION value='nr' >Nauru</OPTION><OPTION value='np' >Nepal</OPTION><OPTION value='nl' >Netherlands</OPTION><OPTION value='an' >Netherlands Antilles</OPTION><OPTION value='nc' >New Caledonia</OPTION><OPTION value='nz' >New Zealand</OPTION><OPTION value='ni' >Nicaragua</OPTION><OPTION value='ne' >Niger</OPTION><OPTION value='ng' >Nigeria</OPTION><OPTION value='nu' >Niue</OPTION><OPTION value='nf' >Norfolk Island</OPTION><OPTION value='kp' >North Korea</OPTION><OPTION value='mp' >Northern Mariana Islands</OPTION><OPTION value='no' >Norway</OPTION><OPTION value='om' >Oman</OPTION><OPTION value='pk' >Pakistan</OPTION><OPTION value='pw' >Palau</OPTION><OPTION value='pa' >Panama</OPTION><OPTION value='pg' >Papua New Guinea</OPTION><OPTION value='py' >Paraguay</OPTION><OPTION value='pe' >Peru</OPTION><OPTION value='ph' >Philippines</OPTION><OPTION value='pn' >Pitcairn</OPTION><OPTION value='pl' >Poland</OPTION><OPTION value='pt' >Portugal</OPTION><OPTION value='pr' >Puerto Rico</OPTION><OPTION value='qa' >Qatar</OPTION><OPTION value='re' >Reunion</OPTION><OPTION value='ro' >Romania</OPTION><OPTION value='ru' >Russian Federation</OPTION><OPTION value='rw' >Rwanda</OPTION><OPTION value='kn' >Saint Kitts And Nevis</OPTION><OPTION value='lc' >Saint Lucia</OPTION><OPTION value='vc' >Saint Vincent And The Grenadin</OPTION><OPTION value='ws' >Samoa</OPTION><OPTION value='sm' >San Marino</OPTION><OPTION value='st' >Sao Tome And Principe</OPTION><OPTION value='sa' >Saudi Arabia</OPTION><OPTION value='sn' >Senegal</OPTION><OPTION value='yu' >Serbia</OPTION><OPTION value='sc' >Seychelles</OPTION><OPTION value='sl' >Sierra Leone</OPTION><OPTION value='sg' >Singapore</OPTION><OPTION value='sk' >Slovakia</OPTION><OPTION value='si' >Slovenia</OPTION><OPTION value='sb' >Solomon Islands</OPTION><OPTION value='so' >Somalia</OPTION><OPTION value='za' >South Africa</OPTION><OPTION value='gs' >South Georgia And The South Sa</OPTION><OPTION value='kr' >South Korea</OPTION><OPTION value='es' >Spain</OPTION><OPTION value='lk' >Sri Lanka</OPTION><OPTION value='sh' >St Helena</OPTION><OPTION value='pm' >St Pierre and Miquelon</OPTION><OPTION value='sd' >Sudan</OPTION><OPTION value='sr' >Suriname</OPTION><OPTION value='sj' >Svalbard And Jan Mayen Islands</OPTION><OPTION value='sz' >Swaziland</OPTION><OPTION value='se' >Sweden</OPTION><OPTION value='ch' >Switzerland</OPTION><OPTION value='sy' >Syrian Arab Republic</OPTION><OPTION value='tw' >Taiwan</OPTION><OPTION value='tj' >Tajikistan</OPTION><OPTION value='tz' >Tanzania</OPTION><OPTION value='th' >Thailand</OPTION><OPTION value='tg' >Togo</OPTION><OPTION value='tk' >Tokelau</OPTION><OPTION value='to' >Tonga</OPTION><OPTION value='tt' >Trinidad And Tobago</OPTION><OPTION value='tn' >Tunisia</OPTION><OPTION value='tr' >Turkey</OPTION><OPTION value='tm' >Turkmenistan</OPTION><OPTION value='tc' >Turks And Caicos Islands</OPTION><OPTION value='tv' >Tuvalu</OPTION><OPTION value='ug' >Uganda</OPTION><OPTION value='ua' >Ukraine</OPTION><OPTION value='ae' >United Arab Emirates</OPTION><OPTION value='gb' >United Kingdom</OPTION><OPTION value='um' >United States Minor Outlying I</OPTION><OPTION value='uy' >Uruguay</OPTION><OPTION value='uz' >Uzbekistan</OPTION><OPTION value='vu' >Vanuatu</OPTION><OPTION value='va' >Vatican City State</OPTION><OPTION value='ve' >Venezuela</OPTION><OPTION value='vn' >Viet Nam</OPTION><OPTION value='vg' >Virgin Islands (British)</OPTION><OPTION value='vi' >Virgin Islands (U.S.)</OPTION><OPTION value='wf' >Wallis And Futuna Islands</OPTION><OPTION value='eh' >Western Sahara</OPTION><OPTION value='ye' >Yemen</OPTION><OPTION value='zm' >Zambia</OPTION><OPTION value='zw' >Zimbabwe</OPTION>
            </select>
       </td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_ADDRESS?></td>
       <td><input name="CC_Address" type="text"  value="" size="40" class="inputl"></td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_ADDRESS2?></td>
       <td><input name="CC_Address1" type="text"  value="" size="40" class="inputl"></td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_CITY?></td>
       <td><input name="CC_City" type="text" value="" size="40" class="inputs"></td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_ESTATE?></td>
       <td><input name="CC_State" type="text" value="" size="40" class="inputs"></td>
    </tr>
    <tr>
       <td> <?php echo AUTHORIZENET_ZIPCODE?></td>
       <td><input name="CC_ZipCode" type="text" value="" size="40" class="inputs"></td>
    </tr>
    <tr>
        <td class="tdfoot" colspan="2" align="center">
            <input type="submit" value="<?=BUTTON_AUTHORIZENET_ORDER?>" class="button" name="OK">
        </td>
    </tr>
       <table border="0" width="80%">
	<tr>
		<td width="909" colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="909" colspan="3"><?php echo CARD_STATEMENT_READ?></td>
	</tr>
	<tr>
		<td width="268" align="left"><!--webbot bot="HTMLMarkup" startspan --><table width="135" border="0" cellpadding="2" cellspacing="0" title="<?php echo ABOUT_SSL_CERTIFICATES2?>">
<tr>
<td width="135" align="center" valign="top"><script src=https://seal.verisign.com/getseal?host_name=www.clubome.com&size=M&use_flash=YES&use_transparent=YES&lang=en></script><br />
<a href="http://www.verisign.es/products-services/security-services/ssl/ssl-information-center/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"><?php echo ABOUT_SSL_CERTIFICATES?></a></td>
</tr>
</table>
<!--webbot bot="HTMLMarkup" endspan --></td>

		<td width="345" align="left" valign="bottom">
		<table border="0" width="80%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="17">
				<font face="Arial,Helvetica" color="#002878" size="2">
				<img src="https://www.clubome.com/skins/clearlight/images/lock.gif" width="12" height="15"></font></td>
				<td><font face="Arial,Helvetica" color="#002878" size="2"><?php echo SSL_SECURE_MODE_TEXT?></font></td>
			</tr>
		</table>
		<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
		<p style="margin-top: 0; margin-bottom: 0"><?php echo IP_SHOW_TEXT?> <?=$_SESSION["USER_CURRENT_IP"]?></td>
		
	</tr>
</table>
   </td>
   
  </tr>
</table>
</form>
<?=$skin->ShowFooter($area)?>