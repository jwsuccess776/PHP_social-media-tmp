<?php
include ('../db_connect.php');
//include('../session_handler.inc');
include('../functions.php');
include('../error.php');
include_once('../validation_functions.php');

$payment_id =sanitizeData($_REQUEST['payment_id'], 'xss_clean') ;  
if (!$payment_id) die('Incorrect payment_id');

$query = "  SELECT *
            FROM payments
                INNER JOIN members
                    ON (pay_userid = mem_userid)
            WHERE pay_paymentid = $payment_id";
$res=mysql_query($query,$link) or die(mysql_error());
$payment = mysql_fetch_object($res);

# retrieve the template
$area = 'member';

include( __INCLUDE_CLASS_PATH . '/usaepay.php');



if ($_REQUEST['OK']){

	$tran=new umTransaction;

	//$tran->ip=$REMOTE_ADDR;   // This allows fraud blocking on the customers ip address

	$tran->key = get_payment_param_by_name('usaepay', $payment->pay_service, 'key');
	$tran->testmode = get_payment_param_by_name('usaepay', $payment->pay_service, 'testmode');
	$tran->command="sale";

	$tran->card        =sanitizeData($_POST['CC_CCNumber'], 'xss_clean') ;   	// card number, no dashes, no spaces
	$tran->exp         =sanitizeData($_POST['CC_ExpMonth'], 'xss_clean') .sanitizeData($_POST['CC_ExpYear'], 'xss_clean'); // expiration date 4 digits no MMYY/
	$tran->amount      = $payment->pay_samount;			// charge amount in dollars
	$tran->invoice     = $payment->pay_paymentid ;   		// invoice number.  must be unique.
	$tran->cardholder  =sanitizeData($_POST['CC_FirstName'], 'xss_clean') ." ".sanitizeData($_POST['CC_LastName'], 'xss_clean') ;	// name of card holder
	$tran->street      =sanitizeData($_POST['CC_Address'], 'xss_clean') ;   	// street address
	$tran->zip         =sanitizeData($_POST['CC_ZipCode'], 'xss_clean') ;   		// zip code
	$tran->description = $payment->pay_message;	// description of charge
	$tran->cvv2        =sanitizeData($_POST['CC_CVV'], 'xss_clean') ;  			// cvv2 code

    if($result = $tran->Process())
    {
        $payment = save_payment_details($payment->pay_paymentid, $tran->cctransid, 'Completed', date('Y-m-d H:i'), $tran->cardholder, $payment->mem_email, $tran->zip, '', $tran->street, '', '', 'authorizenet_aim');
    } else {
        $payment = save_payment_details($payment->pay_paymentid, '', $tran->result, date('Y-m-d H:i'), $tran->cardholder, $payment->mem_email, $tran->zip, '', $tran->street, '', '', 'authorizenet_aim');
        $error = "<b>Card Declined</b> ($tran->result)<br><b>Reason:</b>$tran->error<br>";
        if($tran->curlerror) $error .= "<b>Curl Error:</b> " . $tran->curlerror . "<br>";
        error_page($error,GENERAL_USER_ERROR);
        die;
    }
    if (!$payment) {
        error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
        die;
    }
    include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");

    if ($result) {
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
  if (f.CC_CCNumber.value.length == 0)
        { alert('Credit Card Number Must be Filled In!');
        f.CC_CCNumber.focus(); return false; }
  if(!checkCCNumber(f.CC_CCNumber, f.CC_Type.value))
        { alert('Incorrect card number!');
        f.CC_CCNumber.focus();return false;
        }
  if (f.CC_ExpMonth.selectedIndex == false)
        { alert('Card Expiration Month Must be Filled In!');
          f.CC_ExpMonth.focus(); return false;}
  if (f.CC_ExpYear.selectedIndex == false)
        { alert('Card Expiration Year Must be Filled In!');
        f.CC_ExpYear.focus(); return false;}
  if (f.CC_CVV.value.length == 0)
        { alert('Card Code Must be Filled In!');
          f.CC_CVV.focus(); return false;}

  if (f.CC_Address.value.length == 0)
        { valid = false; alert('Street Address Must be Filled In!');
        f.CC_Address.focus(); return false;}
  if (f.CC_ZipCode.value.length == 0)
        { alert('Postal Code Must be Filled In!');
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
    <form method="post" name="usaepay" action="usaepay_notify.php" onSubmit="return check_form(this)">
        <input type="hidden" name="payment_id" value="<?=$payment_id?>">
   <tr>
      <td> Credit Card Number:</td>
      <td><input name="CC_CCNumber" type="text"  value="" size="40" class="inputl">
      </td>
   </tr>
   <tr>
      <td> CCV2:</td>
      <td><input name="CC_CVV" type="text"   value="" size="40" class="inputs"></td>
   </tr>
   <tr>
      <td>Expiration Date:</td>
      <td><select name="CC_ExpMonth" class="input">
            <option selected value="">-Choose-</option>
            <option value="01">01 - January</option>
            <option value="02">02 - February</option>
            <option value="03">03 - March</option>
            <option value="04">04 - April</option>
            <option value="05">05 - May</option>
            <option value="06">06 - June</option>
            <option value="07">07 - July</option>
            <option value="08">08 - August</option>
            <option value="09">09 - September</option>
            <option value="10">10 - October</option>
            <option value="11">11 - November</option>
            <option value="12">12 - December</option>
         </select> <select name="CC_ExpYear" id="select4" class="input">
            <option selected value="">-Choose-</option>
            <option value="05">2005</option>
            <option value="06">2006</option>
            <option value="07">2007</option>
            <option value="08">2008</option>
            <option value="09">2009</option>
            <option value="10">2010</option>
         </select></td>
   </tr>
    <tr>
       <td> First Name:</td>
       <td><input name="CC_FirstName" type="text" value="" size="40" class="inputs"></td>
    </tr>
    <tr>
       <td> Last Name:</td>
       <td><input name="CC_LastName" type="text" value="" size="40" class="inputs"></td>
    </tr>
    <tr>
       <td> Address:</td>
       <td><input name="CC_Address" type="text"  value="" size="40" class="inputl"></td>
    </tr>
    <tr>
       <td> Zip:</td>
       <td><input name="CC_ZipCode" type="text" value="" size="40" class="inputs"></td>
    </tr>
    <tr align="left" valign="top">
       <td width="281"> Country: </td>
       <td>
           <select name="CC_Country" class="input">
             <option selected value="">Choose a Country
             <option value="USA" selected>United States of America
             <option value="CAN">Canada
             <option value="DEU">Germany
             <option value="FRA">France
             <option value="GBR">United Kingdom
             <option value="IND">India
             <option value="">---------------------
             <option value="AFG">Afghanistan
             <option value="ALB">Albania
             <option value="DZA">Algeria
             <option value="ASM">American Samoa
             <option value="AND">Andorra
             <option value="AGO">Angola
             <option value="AIA">Anguilla
             <option value="ATA">Antarctica
             <option value="ATG">Antigua and Barbuda
             <option value="ARG">Argentina
             <option value="ARM">Armenia
             <option value="ABW">Aruba
             <option value="AUS">Australia
             <option value="AUT">Austria
             <option value="AZE">Azerbaijan
             <option value="BHS">Bahamas
             <option value="BHR">Bahrain
             <option value="BGD">Bangladesh
             <option value="BRB">Barbados
             <option value="BLR">Belarus
             <option value="BEL">Belgium
             <option value="BLZ">Belize
             <option value="BEN">Benin
             <option value="BMU">Bermuda
             <option value="BTN">Bhutan
             <option value="BOL">Bolivia
             <option value="BIH">Bosnia and Herzegowina
             <option value="BWA">Botswana
             <option value="BVT">Bouvet Island
             <option value="BRA">Brazil
             <option value="IOT">British Indian Ocean Territory
             <option value="BRN">Brunei Darussalam
             <option value="BGR">Bulgaria
             <option value="BFA">Burkina Faso
             <option value="BDI">Burundi
             <option value="KHM">Cambodia
             <option value="CMR">Cameroon
             <option value="CPV">Cape Verde
             <option value="CYM">Cayman Islands
             <option value="CAF">Central African Republic
             <option value="TCD">Chad
             <option value="CHL">Chile
             <option value="CHN">China
             <option value="CXR">Christmas Island
             <option value="CCK">Cocoa (Keeling) Islands
             <option value="COL">Colombia
             <option value="COM">Comoros
             <option value="COG">Congo
             <option value="COK">Cook Islands
             <option value="CRI">Costa Rica
             <option value="CIV">Cote Divoire
             <option value="HRV">Croatia (local name: Hrvatska)
             <option value="CUB">Cuba
             <option value="CYP">Cyprus
             <option value="CZE">Czech Republic
             <option value="DNK">Denmark
             <option value="DJI">Djibouti
             <option value="DMA">Dominica
             <option value="DOM">Dominican Republic
             <option value="TMP">East Timor
             <option value="ECU">Ecuador
             <option value="EGY">Egypt
             <option value="SLV">El Salvador
             <option value="GNQ">Equatorial Guinea
             <option value="ERI">Eritrea
             <option value="EST">Estonia
             <option value="ETH">Ethiopia
             <option value="FLK">Falkland Islands (Malvinas)
             <option value="FRO">Faroe Islands
             <option value="FJI">Fiji
             <option value="FIN">Finland
             <option value="FXX">France, Metropolitan
             <option value="GUF">French Guiana
             <option value="PYF">French Polynesia
             <option value="ATF">French Southern Territories
             <option value="GAB">Gabon
             <option value="GMB">Gambia
             <option value="GEO">Georgia
             <option value="GHA">Ghana
             <option value="GIB">Gibraltar
             <option value="GRC">Greece
             <option value="GRL">Greenland
             <option value="GRD">Grenada
             <option value="GLP">>Guadeloupe
             <option value="GUM">Guam
             <option value="GTM">Guatemala
             <option value="GIN">Guinea
             <option value="GNB">Guinea-Bissau
             <option value="GUY">Guyana
             <option value="HTI">Haiti
             <option value="HMD">Heard and Mc Donald Islands
             <option value="HND">Honduras
             <option value="HKG">Hong Kong
             <option value="HUN">Hungary
             <option value="ISL">Iceland
             <option value="IDN">Indonesia
             <option value="IRN">Iran (Islamic Republic of)
             <option value="IRQ">Iraq
             <option value="IRL">Ireland
             <option value="ISR">Israel
             <option value="ITA">Italy
             <option value="JAM">Jamaica
             <option value="JPN">Japan
             <option value="JOR">Jordan
             <option value="KAZ">Kazakhstan
             <option value="KEN">Kenya
             <option value="KIR">Kiribati
             <option value="PRK">Korea, Democratic Peoples Republic of
             <option value="KOR">Korea, Republic of
             <option value="KWT">Kuwait
             <option value="KGZ">Kyrgyzstan
             <option value="LAO">Lao Peoples Democratic Republic
             <option value="LVA">Latvia
             <option value="LBN">Lebanon
             <option value="LSO">Lesotho
             <option value="LBR">Liberia
             <option value="LBY">Libyan Arab Jamahiriya
             <option value="LIE">Liechtenstein
             <option value="LTU">Lithuania
             <option value="LUX">Luxembourg
             <option value="MAC">Macau
             <option value="MKD">Macedonia, The Former Yugoslav Republic of
             <option value="MDG">Madagascar
             <option value="MWI">Malawi
             <option value="MYS">Malaysia
             <option value="MDV">Maldives
             <option value="MLI">Mali
             <option value="MLT">Malta
             <option value="MHL">Marshall Islands
             <option value="MTQ">Martinique
             <option value="MRT">Mauritania
             <option value="MVS">Mauritius
             <option value="MYT">Mayotte
             <option value="MEX">Mexico
             <option value="FSM">Micronesia, Federated States of
             <option value="MDA">Moldova, Republic of
             <option value="MCO">Monaco
             <option value="MNG">Mongolia
             <option value="MSR">Montserrat
             <option value="MAR">Morocco
             <option value="MOZ">Mozambique
             <option value="MMR">Myanmar
             <option value="NAM">Namibia
             <option value="NRU">Nauru
             <option value="NPL">Nepal
             <option value="NLD">Netherlands
             <option value="ANT">Netherlands Antilles
             <option value="NCL">New Caledonia
             <option value="NZL">New Zealand
             <option value="NIC">Nicaragua
             <option value="NER">Niger
             <option value="NGA">Nigeria
             <option value="NIU">Niue
             <option value="NFK">Norfolk Island
             <option value="MNP">Northern Mariana Islands
             <option value="MOR">Norway
             <option value="OMN">Oman
             <option value="PAK">Pakistan
             <option value="PLW">Palau
             <option value="PAN">Panama
             <option value="PNG">Papua New Guinea
             <option value="PRY">Paraguay
             <option value="PER">Peru
             <option value="PHL">Philippines
             <option value="PCN">Pitcairn
             <option value="POL">Poland
             <option value="PRT">Portugal
             <option value="PRI">Puerto Rico
             <option value="QAT">Qatar
             <option value="REU">Reunion
             <option value="ROM">Romania
             <option value="RUS">Russian Federation
             <option value="RWA">Rwanda
             <option value="KNA">Saint Kitts and Nevis
             <option value="LCA">Saint Lucia
             <option value="VCT">Saint Vincent and the Grenadines
             <option value="WSM">Samoa
             <option value="SMR">San Marino
             <option value="STP">Sao Tome and Principe
             <option value="SAU">Saudi Arabia
             <option value="SEN">Senegal
             <option value="SYC">Seychelles
             <option value="SLE">Sierra Leone
             <option value="SGP">Singapore
             <option value="SVK">Slovakia (Slovak Republic)
             <option value="SVN">Slovenia
             <option value="SLB">Solomon Islands
             <option value="SOM">Somalia
             <option value="ZAF">South Africa
             <option value="SGS">South Georgia and the South Sandwich Islands
             <option value="ESP">Spain
             <option value="LKA">Sri Lanka
             <option value="SHN">St. Helena
             <option value="SPM">St. Pierre and Miquelon
             <option value="SDN">Sudan
             <option value="SUR">Suriname
             <option value="SJM">Svalbard and Jan Mayen Islands
             <option value="SWZ">Swaziland
             <option value="SWE">Sweden
             <option value="CHE">Switzerland
             <option value="SYR">Syrian Arab Republic
             <option value="TWN">Taiwan
             <option value="TJK">Tajikistan
             <option value="TZA">Tanzania, United Republic of
             <option value="THA">Thailand
             <option value="TGO">Togo
             <option value="TKL">Tokelau
             <option value="TON">Tonga
             <option value="TTO">Trinidad and Tobago
             <option value="TUN">Tunisia
             <option value="TUR">Turkey
             <option value="TKM">Turkmenistan
             <option value="TCA">Turks and Caicos Islands
             <option value="TUV">Tuvalu
             <option value="UGA">Uganda
             <option value="UKR">Ukraine
             <option value="ARE">United Arab Emirates
             <option value="UMI">United States Minor Outlying Islands
             <option value="URY">Uruguay
             <option value="UZB">Uzbekistan
             <option value="VUT">Vanuatu
             <option value="VAT">Vatican City State (Holy See)
             <option value="VEN">Venezuela
             <option value="VNM">Viet Nam
             <option value="VGB">Virgin Islands (British)
             <option value="VIR">Virgin Islands (U.S.)
             <option value="WLF">Wallisw and Futuna Islands
             <option value="ESH">Western Sahara
             <option value="YEM">Yeman
             <option value="YUG">Yugoslavia
             <option value="ZAR">Zaire
             <option value="ZMB">Zambia
             <option value="ZWE">Zimbabwe
             <option value="UNK">Not Listed___________________________
          </select></td>
    </tr>
    <tr>
       <td> Email Address:</td>
       <td><input name="Cust_EmailAddress" type="text"  value="" size="40" class="inputl"></td>
    </tr>
    <tr>
        <td class="tdfoot" colspan="2" align="center">
            <input type="button" value="Cancel" class="button" onclick="history.back()">
            <input type="submit" value="Checkout" class="button" name="OK">
        </td>
    </tr>
 </table> </td>
   </tr>
</table>
</form>
<?=$skin->ShowFooter($area)?>
