<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         prn_paymnts.php
#
# Description:  destroys affiliate session
#
# Version:      7.2
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('permission.php');
include_once('../validation_functions.php'); 

if (isset($_GET['lstYear'])) $lstYear=sanitizeData(trim($_GET['lstYear']), 'xss_clean'); else $lstYear=date('Y');
if (isset($_GET['lstMonth'])) $lstMonth=sanitizeData(trim($_GET['lstMonth']), 'xss_clean'); else $lstMonth=date('m');
# retrieve the template
$area = 'member';

$Year=date("Y");
$Year1=$Year-1;
$Year2=$Year1-1;
$Year3=$Year1-2;
$Year4=$Year1-3;
if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {
    $query="SELECT *, DATE_FORMAT(rec_buydate,'%d-%M-%Y') AS buydate ,aff_surname, aff_forename FROM receipts LEFT JOIN affiliates ON (rec_affuserid=aff_userid) WHERE MONTH(rec_paydate) = '$lstMonth' AND YEAR(rec_paydate) = '$lstYear' ORDER BY aff_surname, aff_forename, rec_paydate ASC";
    $result=mysql_query($query,$link) or die(mysql_error());
    $qrynum=mysql_num_rows($result);
} else {
    $hdnmonth="";
    $hdnyear="";
}
?>

<?=$skin->ShowHeader($area)?>
 <?php
 if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {
    print("<div align='center'><center>
             <table border='0' cellpadding='0' width='610' cellspacing='0'>
            <tr>
              <td width='610' height='20' colspan='4'>".PRN_PAYMENTS_TITLE." - $lstMonth/$lstYear</td>
            </tr>
            <tr>
              <td width='610' height='20' colspan='4'><hr></td>
            </tr>                <tr>
                     <td width='152' height='20'><b>".FRN_PAYMENTS_AFFILIATE."</b></td>
                     <td width='152' height='20'><b>".AFF_PAYMENT_TRANS_CODE."</b></td>
                     <td width='153' height='20'><b>".AFF_PAYMENT_TRANS_DATE."</b></td>
                     <td width='153' height='20'><b>".FRN_PAYMENTS_AMOUNT."</b></td>
                </tr><tr>
              <td width='610' height='20' colspan='4'><hr></td>
            </tr>");
    while ($sql_array = mysql_fetch_object($result) ) {
        print("<tr>
                  <td width='152' height='20'>$sql_array->aff_surname, $sql_array->aff_forename</td>
                  <td width='152' height='20'>$sql_array->rec_transid</td>
                  <td width='153' height='20'>".date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array->buydate))."</td>
                  <td width='153' height='20'>$CONST_SYMBOL $sql_array->rec_affamount</td>
                </tr> ");
    }
    $query="SELECT SUM(rec_affamount) AS affamount, aff_surname, aff_forename FROM receipts LEFT JOIN affiliates ON (rec_affuserid=aff_userid) WHERE MONTH(rec_paydate) = '$lstMonth' AND YEAR(rec_paydate) = '$lstYear' GROUP BY aff_surname, aff_forename";
    $result=mysql_query($query,$link) or die(mysql_error());
    print("<tr>
              <td width='610' height='20' colspan='4'><hr></td>
            </tr>
            <tr>
              <td width='610' height='20' colspan='4'><b>".PRN_PAYMENTS_SUMMARY."</b></td>
            </tr>
            <tr>
              <td width='610' height='20' colspan='4'><hr></td>
            </tr>
             ");
    while ($sql_array = mysql_fetch_object($result) ) {
        $sql_array->affamount=number_format($sql_array->affamount,2);
        print("<tr>
                  <td width='152' height='20'>$sql_array->aff_surname, $sql_array->aff_forename</td>
                  <td width='152' height='20'>$CONST_SYMBOL $sql_array->affamount</td>
                  <td width='153' height='20'></td>
                  <td width='153' height='20'></td>
                </tr> ");
    }
    print("<tr>
              <td width='610' height='20' colspan='4'><hr></td>
            </tr>
    </table>
    </center></div>");
}
mysql_close($link);
?>
<?=$skin->ShowFooter($area)?>


