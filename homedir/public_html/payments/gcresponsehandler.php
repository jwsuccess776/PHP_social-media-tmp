<?php

/**
 * Copyright (C) 2007 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

 /* This is the response handler code that will be invoked every time
  * a notification or request is sent by the Google Server
  *
  * To allow this code to receive responses, the url for this file
  * must be set on the seller page under Settings->Integration as the
  * "API Callback URL'
  * Order processing commands can be sent automatically by placing these
  * commands appropriately
  *
  * To use this code for merchant-calculated feedback, this url must be
  * set also as the merchant-calculations-url when the cart is posted
  * Depending on your calculations for shipping, taxes, coupons and gift
  * certificates update parts of the code as required
  *
  */
include('../db_connect.php');
include('../functions.php');
require_once('library/googleresponse.php');
require_once('library/googlemerchantcalculations.php');
require_once('library/googleresult.php');
require_once('library/googlerequest.php');

define('RESPONSE_HANDLER_ERROR_LOG_FILE', 'gc_log/googleerror.log');
define('RESPONSE_HANDLER_LOG_FILE', 'gc_log/googlemessage.log');

$merchant_id = "";  // Your Merchant ID
$merchant_key = "";  // Your Merchant Key
$server_type = "sandbox";  // change this to go live
foreach (get_payment_params('google_checkout', 'premium') as $param)
{
    switch ($param->psp_name)
    {
        case 'merchantID': $merchant_id = $param->psp_value; break;
        case 'merchant_key': $merchant_key = $param->psp_value; break;
        case 'sandbox_test': $server_type = ($param->psp_value == '1')?"sandbox":""; break;
    }
}
$currency = 'USD';  // set to GBP if in the UK

$Gresponse = new GoogleResponse($merchant_id, $merchant_key);

$Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

//Setup the log file
$Gresponse->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE,
                                        RESPONSE_HANDLER_LOG_FILE, L_ALL);

// Retrieve the XML sent in the HTTP POST request to the ResponseHandler
$xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
}
list($root, $data) = $Gresponse->GetParsedXML($xml_response);
$Gresponse->SetMerchantAuthentication($merchant_id, $merchant_key);

$status = $Gresponse->HttpAuthentication();
if(! $status) {
    die('authentication failed');
}

  /* Commands to send the various order processing APIs
   * Send charge order : $Grequest->SendChargeOrder($data[$root]
   *    ['google-order-number']['VALUE'], <amount>);
   * Send process order : $Grequest->SendProcessOrder($data[$root]
   *    ['google-order-number']['VALUE']);
   * Send deliver order: $Grequest->SendDeliverOrder($data[$root]
   *    ['google-order-number']['VALUE'], <carrier>, <tracking-number>,
   *    <send_mail>);
   * Send archive order: $Grequest->SendArchiveOrder($data[$root]
   *    ['google-order-number']['VALUE']);
   *
   */

  switch ($root) {
    case "request-received": {
      break;
    }
    case "error": {
      break;
    }
    case "diagnosis": {
      break;
    }
    case "checkout-redirect": {
      break;
    }
    case "merchant-calculation-callback": {
      break;
    }
    case "new-order-notification": {
        $payment_id = $data[$root]['shopping-cart']['merchant-private-data']['VALUE'];
        $google_order_number = (isset($data[$root]['google-order-number']))?$data[$root]['google-order-number']['VALUE']:"no data";
        $order_status = (isset($data[$root]['financial-order-state']))?$data[$root]['financial-order-state']['VALUE']:"no data";
        $email = (isset($data[$root]['buyer-billing-address']['email']))?$data[$root]['buyer-billing-address']['email']['VALUE']:"no data";
        $name = (isset($data[$root]['buyer-billing-address']['contact-name']))?$data[$root]['buyer-billing-address']['contact-name']['VALUE']:"no data";
        $zip = (isset($data[$root]['buyer-billing-address']['postal-code']))?$data[$root]['buyer-billing-address']['postal-code']['VALUE']:"no data";
        $country = (isset($data[$root]['buyer-billing-address']['country-code']))?$data[$root]['buyer-billing-address']['country-code']['VALUE']:"no data";
        $address1 = (isset($data[$root]['buyer-billing-address']['address1']))?$data[$root]['buyer-billing-address']['address1']['VALUE']:"no data";
        $tel = (isset($data[$root]['buyer-billing-address']['phone']))?$data[$root]['buyer-billing-address']['phone']['VALUE']:"no data";
        $address_country = (isset($data[$root]['buyer-billing-address']['country-code']))?$data[$root]['buyer-billing-address']['country-code']['VALUE']:"no data";
        $payment = save_payment_details($payment_id, $google_order_number, $order_status, date('Y-m-d H:i'), $name, $email, $zip, $country, $address1, $tel, $address_country, 'google_checkout');
        $Gresponse->SendAck();
        break;
    }
    case "order-state-change-notification": {
      $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
      $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];
      $trans_id = $data[$root]['google-order-number']['VALUE'];
      $query="UPDATE payments SET
                    pay_transstatus =   '$new_financial_state',
                    pay_date        =   now()
              WHERE pay_transid = '$trans_id'";
      mysql_query($query) or die(mysql_error());

      switch($new_financial_state) {
        case 'REVIEWING': {
          break;
        }
        case 'CHARGEABLE': {
          //$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
          //$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
          break;
        }
        case 'CHARGING': {
          break;
        }
        case 'CHARGED': {
            $query = "SELECT *
                      FROM payments
                      INNER JOIN members
                      ON (pay_userid = mem_userid)
                      WHERE pay_transid = '$trans_id'";
            $res=mysql_query($query,$link) or die(mysql_error());
            $payment = mysql_fetch_object($res);
            include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
            payment_activation($payment->pay_paymentid);
          break;
        }
        case 'PAYMENT_DECLINED': {
          break;
        }
        case 'CANCELLED': {
          break;
        }
        case 'CANCELLED_BY_GOOGLE': {
          //$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
          //    "Sorry, your order is cancelled by Google", true);
          break;
        }
        default:
          break;
      }

      switch($new_fulfillment_order) {
        case 'NEW': {
          break;
        }
        case 'PROCESSING': {
          break;
        }
        case 'DELIVERED': {
          break;
        }
        case 'WILL_NOT_DELIVER': {
          break;
        }
        default:
          break;
      }
      $Gresponse->SendAck();
      break;
    }
    case "charge-amount-notification": {
      //$Grequest->SendDeliverOrder($data[$root]['google-order-number']['VALUE'],
      //    <carrier>, <tracking-number>, <send-email>);
      //$Grequest->SendArchiveOrder($data[$root]['google-order-number']['VALUE'] );
      $Gresponse->SendAck();
      break;
    }
    case "chargeback-amount-notification": {
      $Gresponse->SendAck();
      break;
    }
    case "refund-amount-notification": {
      $Gresponse->SendAck();
      break;
    }
    case "risk-information-notification": {
      $Gresponse->SendAck();
      break;
    }
    default:
      $Gresponse->SendBadRequestStatus("Invalid or not supported Message");
      break;
  }
  /* In case the XML API contains multiple open tags
     with the same value, then invoke this function and
     perform a foreach on the resultant array.
     This takes care of cases when there is only one unique tag
     or multiple tags.
     Examples of this are "anonymous-address", "merchant-code-string"
     from the merchant-calculations-callback API
  */
  function get_arr_result($child_node) {
    $result = array();
    if(isset($child_node)) {
      if(is_associative_array($child_node)) {
        $result[] = $child_node;
      }
      else {
        foreach($child_node as $curr_node){
          $result[] = $curr_node;
        }
      }
    }
    return $result;
  }

  /* Returns true if a given variable represents an associative array */
  function is_associative_array( $var ) {
    return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
  }
?>