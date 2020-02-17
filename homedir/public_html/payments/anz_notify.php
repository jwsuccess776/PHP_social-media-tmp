<?php
include('../db_connect.php');
include_once('../validation_functions.php');
include('../error.php');
include('../functions.php');


// This method uses the QSI Response code retrieved from the Digital
// Receipt and returns an appropriate description for the QSI Response Code
// @param $responseCode String containing the QSI Response Code
// @return String containing the appropriate description
function getResponseDescription($responseCode) {

    switch ($responseCode) {
        case "0" : $result = "Completed"; break;
        case "?" : $result = "Transaction status is unknown"; break;
        case "1" : $result = "Unknown Error"; break;
        case "2" : $result = "Bank Declined Transaction"; break;
        case "3" : $result = "No Reply from Bank"; break;
        case "4" : $result = "Expired Card"; break;
        case "5" : $result = "Insufficient funds"; break;
        case "6" : $result = "Error Communicating with Bank"; break;
        case "7" : $result = "Payment Server System Error"; break;
        case "8" : $result = "Transaction Type Not Supported"; break;
        case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
        case "A" : $result = "Transaction Aborted"; break;
        case "C" : $result = "Transaction Cancelled"; break;
        case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
        case "F" : $result = "3D Secure Authentication failed"; break;
        case "I" : $result = "Card Security Code verification failed"; break;
        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
        case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
        case "S" : $result = "Duplicate SessionID (OrderInfo)"; break;
        case "T" : $result = "Address Verification Failed"; break;
        case "U" : $result = "Card Security Code Failed"; break;
        case "V" : $result = "Address Verification and Card Security Code Failed"; break;
        default  : $result = "Unable to be determined"; 
    }
    return $result;
}

// This method uses the verRes status code retrieved from the Digital
// Receipt and returns an appropriate description for the QSI Response Code
// @param statusResponse String containing the 3DS Authentication Status Code
// @return String containing the appropriate description
function getStatusDescription($statusResponse) {
    if ($statusResponse == "" || $statusResponse == "No Value Returned") {
        $result = "3DS not supported or there was no 3DS data provided";
    } else {
        switch ($statusResponse) {
            Case "Y"  : $result = "The cardholder was successfully authenticated."; break;
            Case "E"  : $result = "The cardholder is not enrolled."; break;
            Case "N"  : $result = "The cardholder was not verified."; break;
            Case "U"  : $result = "The cardholder's Issuer was unable to authenticate due to some system error at the Issuer."; break;
            Case "F"  : $result = "There was an error in the format of the request from the merchant."; break;
            Case "A"  : $result = "Authentication of your Merchant ID and Password to the ACS Directory Failed."; break;
            Case "D"  : $result = "Error communicating with the Directory Server."; break;
            Case "C"  : $result = "The card type is not supported for authentication."; break;
            Case "S"  : $result = "The signature on the response received from the Issuer could not be validated."; break;
            Case "P"  : $result = "Error parsing input from Issuer."; break;
            Case "I"  : $result = "Internal Payment Server system error."; break;
            default   : $result = "Unable to be determined"; break;
        }
    }
    return $result;
}

function null2unknown($data) {
    if ($data == "") {
        return "No Value Returned";
    } else {
        return $data;
    }
} 

$service = ($_POST["service"]) ? sanitizeData($_POST['service'], 'xss_clean') : 'premium';
$SecureHash = get_payment_param_by_name('anz',$service,'SecureHash');
$md5HashData = $SecureHash;

//this part of code sends data to ANZ server 
if ($_POST['sender'] == 'form'){

    unset($_POST["sender"]); 
    unset($_POST["service"]); 
    $vpcURL = "https://migs.mastercard.com.au/vpcpay?";

    ksort ($_POST);
    // set a parameter to show the first pair in the URL
    $appendAmp = 0;
    foreach($_POST as $key => $value) {
        // create the md5 input and URL leaving out any fields that have no value
        if (strlen($value) > 0) {
            // this ensures the first paramter of the URL is preceded by the '?' char
            if ($appendAmp == 0) {
                $vpcURL .= urlencode($key) . '=' . urlencode($value);
                $appendAmp = 1;
            } else {
                $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
            }
            $md5HashData .= $value;
        }
    }
    
    // Create the secure hash and append it to the Virtual Payment Client Data if
    // the merchant secret has been provided.
    if (strlen($SecureHash) > 0) {
        $vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($md5HashData));
    }

    //echo "Location: ".$vpcURL;exit;
    header("Location: ".$vpcURL);
    exit;
}

$vpc_Txn_Secure_Hash = $_GET["vpc_SecureHash"];
unset($_GET["vpc_SecureHash"]); 

$txnResponseCode = null2unknown($_GET["vpc_TxnResponseCode"]);

if (strlen($SecureHash) > 0) {
    if($txnResponseCode == "7") {
        error_page("Payment Server System Error: ".$_GET["vpc_Message"],GENERAL_SYSTEM_ERROR);
        die;
    }
    if($txnResponseCode == "No Value Returned") {
        error_page("Can't determinate response code",GENERAL_SYSTEM_ERROR);
        die;
    }

    $md5HashData = $SecureHash;
    // sort all the incoming vpc response fields and leave out any with no value
    // Order for md5HashData
    //md5_input = Secure_Secret + amount + authorizeID + batchNo + locale + merchantId +
    //            orderInfo + qsiResponseCode + receiptNo + transactionNo + version
    foreach($_GET as $key => $value) {
        if ($key != "vpc_SecureHash" or strlen($value) > 0) {
            $md5HashData .= $value;
            $md5_input .= "+".$key;
        }
    }
    //print $md5_input;
    // The hash check is all about detecting if the data has changed in transit.
    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData))) {
        $hashValidated = "<FONT color='#00AA00'><strong>CORRECT</strong></FONT>";
    } else {
        error_page("INVALID HASH",GENERAL_SYSTEM_ERROR);
        die;
    }
}

$amount          = null2unknown($_GET["vpc_Amount"]);
$locale          = null2unknown($_GET["vpc_Locale"]);
$batchNo         = null2unknown($_GET["vpc_BatchNo"]);
$command         = null2unknown($_GET["vpc_Command"]);
$message         = null2unknown($_GET["vpc_Message"]);
$version         = null2unknown($_GET["vpc_Version"]);
$cardType        = null2unknown($_GET["vpc_Card"]);
$orderInfo       = null2unknown($_GET["vpc_OrderInfo"]);
$receiptNo       = null2unknown($_GET["vpc_ReceiptNo"]);
$merchantID      = null2unknown($_GET["vpc_Merchant"]);
$authorizeID     = null2unknown($_GET["vpc_AuthorizeId"]);
$merchTxnRef     = null2unknown($_GET["vpc_MerchTxnRef"]);
$transactionNo   = null2unknown($_GET["vpc_TransactionNo"]);
$acqResponseCode = null2unknown($_GET["vpc_AcqResponseCode"]);


// 3-D Secure Data
$verType         = array_key_exists("vpc_VerType", $_GET)          ? $_GET["vpc_VerType"]          : "No Value Returned";
$verStatus       = array_key_exists("vpc_VerStatus", $_GET)        ? $_GET["vpc_VerStatus"]        : "No Value Returned";
$token           = array_key_exists("vpc_VerToken", $_GET)         ? $_GET["vpc_VerToken"]         : "No Value Returned";
$verSecurLevel   = array_key_exists("vpc_VerSecurityLevel", $_GET) ? $_GET["vpc_VerSecurityLevel"] : "No Value Returned";
$enrolled        = array_key_exists("vpc_3DSenrolled", $_GET)      ? $_GET["vpc_3DSenrolled"]      : "No Value Returned";
$xid             = array_key_exists("vpc_3DSXID", $_GET)           ? $_GET["vpc_3DSXID"]           : "No Value Returned";
$acqECI          = array_key_exists("vpc_3DSECI", $_GET)           ? $_GET["vpc_3DSECI"]           : "No Value Returned";
$authStatus      = array_key_exists("vpc_3DSstatus", $_GET)        ? $_GET["vpc_3DSstatus"]        : "No Value Returned";

$payment_status = getResponseDescription($_GET["vpc_TxnResponseCode"]);
$name = $payer_email = $tel= $address_zip = $address_country = $address = $tel = $address_country = "";

$payment = save_payment_details($orderInfo, $transactionNo, $payment_status, date('Y-m-d'), $name, $payer_email, $address_zip, $address_country, $address, $tel, $address_country, 'anz');
if (!$payment->pay_paymentid) {
    error_page("Incorrect order id connect with $CONST_MAIL",GENERAL_SYSTEM_ERROR);
    die;
}
include_once(__INCLUDE_CLASS_PATH.'/'.$payment->pay_service.".php");
if($payment_status == 'Completed') {
    payment_activation($payment->pay_paymentid);
    js_redirect("$CONST_LINK_ROOT/payments/thankyou.php");
    exit;
} else { 
    js_redirect("$CONST_LINK_ROOT/payments/cancel.php");
    exit; 
}
?>