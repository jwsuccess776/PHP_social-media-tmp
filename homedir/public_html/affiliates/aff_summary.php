<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name: 		aff_summary.php
#
# Description:  Affiliate summary stats statement
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('aff_session_handler.inc');

# retrieve the template
$area = 'affiliate';

if (!isset($Sess_AffUserId)) header("Location: /index.php");

// Clickthru
$query="SELECT * , unix_timestamp(aff_joindate) AS aff_joindate FROM affiliates WHERE aff_userid='$Sess_AffUserId'";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_object($retval);
$no_clicks=$sql_array->aff_clickthru;
$company=$sql_array->aff_business;
$joindate=date($CONST_FORMAT_DATE_SHORT,$sql_array->aff_joindate);
$query="SELECT COUNT(*) FROM members WHERE mem_referrer='$Sess_AffUserId'";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$no_referred = $sql_array[0];
// Total Subscriptions
$query="SELECT COUNT(*) FROM receipts WHERE rec_affuserid='$Sess_AffUserId'";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$tSubscriptions = $sql_array[0];
// payment figures
$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND rec_paid='Y'";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$paid2date = sprintf("%.2f",$sql_array[0]);
$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId'";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$earned2date = sprintf("%.2f",$sql_array[0]);
$balance = $earned2date - $paid2date;
$balance = sprintf("%.2f",$balance);
// Current month
$currmonth=date('m');
$curryear=date('Y');
$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND MONTH(rec_buydate) = $currmonth AND YEAR(rec_buydate) = $curryear";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$currmonthearn = sprintf("%.2f",$sql_array[0]);
$query="SELECT COUNT(*) FROM members WHERE mem_referrer='$Sess_AffUserId' AND MONTH(mem_joindate) = $currmonth AND YEAR(mem_joindate) = $curryear";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$currmonthsub = $sql_array[0];
$query="SELECT COUNT(*) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND MONTH(rec_buydate) = $currmonth AND YEAR(rec_buydate) = $curryear";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$cSubscriptions = $sql_array[0];
// Previous month
$prevmonth=date('m')-1;
if ($currmonth == '1')
	$curryear=date('Y')-1;
$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND MONTH(rec_buydate) = $prevmonth AND YEAR(rec_buydate) = $curryear";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$prevmonthearn = sprintf("%.2f",$sql_array[0]);
$query="SELECT COUNT(*) FROM members WHERE mem_referrer='$Sess_AffUserId' AND MONTH(mem_joindate) = $prevmonth AND YEAR(mem_joindate) = $curryear";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$prevmonthsub = $sql_array[0];
$query="SELECT COUNT(*) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND MONTH(rec_buydate) = $prevmonth AND YEAR(rec_buydate) = $curryear";
$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
$sql_array = mysqli_fetch_array($retval);
$pSubscriptions = $sql_array[0];

mysqli_close($globalMysqlConn);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <?
	require('aff_menu.php');
?>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/affiliates/index.php' >

          <tr>

            <td valign='top' align='left' colspan="2">
            </td>
          </tr>

          <tr>

            <td colspan="2" align='left' valign='top' class="tdhead">
              <p><b><?php echo AFF_SUMMARY_ACCOUNT?>:</b>  <?php print("$company"); ?> -
              <b><?php echo AFF_SUMMARY_DATE_OPEN?>:</b>
              <?php print("$joindate"); ?></td>
          </tr>



          <tr class="tdodd">

            <td colspan="2" align='left' valign='top'>
            <b><?php echo AFF_SUMMARY_ACCOUNT_SUMMARY?>:</b>
            </td>
          </tr>

          <tr>

            <td valign='top' align='left'>
            </td>
            <td valign='top' align='left'>
            </td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_CLICK?>:</td>
            <td align='left' valign='top'><?php print("$no_clicks"); ?></td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_REG?>:</td>
            <td align='left' valign='top'>
              <?php print("$no_referred"); ?></td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_SUB?>:</td>
            <td align='left' valign='top'>
              <?php print("$tSubscriptions"); ?></td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_EARN?>:</td>
            <td align='left' valign='top'>
              <?php echo $CONST_SYMBOL ?><?php print("$earned2date"); ?></td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_PAID?>:</td>
            <td align='left' valign='top'>
              <?php echo $CONST_SYMBOL ?><?php print("$paid2date"); ?></td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_BALANCE?>:</td>
            <td align='left' valign='top'>
              <?php echo $CONST_SYMBOL ?><?php print("$balance"); ?></td>
          </tr>

          <tr>

            <td valign='top' align='left'>
            </td>
            <td valign='top' align='left'>
            </td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'>
              <b><?php echo AFF_SUMMARY_CURRENT_MONTH?>:</b></td>
            <td align='left' valign='top'>
            </td>
          </tr>

          <tr>

            <td valign='top' align='left'>
            </td>
            <td valign='top' align='left'>
            </td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_REG?>:</td>
            <td align='left' valign='top'>
            <?php print("$currmonthsub"); ?>
            </td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_SUB?>:</td>
            <td align='left' valign='top'>
            <?php print("$cSubscriptions"); ?>
            </td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_EARN?>:</td>
            <td align='left' valign='top'>
            <?php echo $CONST_SYMBOL ?><?php print("$currmonthearn"); ?>
            </td>
          </tr>

          <tr>

            <td valign='top' align='left'>
            </td>
            <td valign='top' align='left'>
            </td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'>
              <b><?php echo AFF_SUMMARY_PREVIOUS_MONTH?>:</b></td>
            <td align='left' valign='top'>
            </td>
          </tr>

          <tr>

            <td valign='top' align='left'>
            </td>
            <td valign='top' align='left'>
            </td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_REG?>:</td>
            <td align='left' valign='top'>
            <?php print("$prevmonthsub"); ?>
            </td>
          </tr>

          <tr class="tdeven">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_SUB?>:</td>
            <td align='left' valign='top'>
            <?php print("$pSubscriptions"); ?>
            </td>
          </tr>

          <tr class="tdodd">

            <td align='left' valign='top'><?php echo AFF_SUMMARY_TOTAL_EARN?>:</td>
            <td align='left' valign='top'>
            <?php echo $CONST_SYMBOL ?><?php print("$prevmonthearn"); ?>
            </td>
          </tr>
          <tr class="tdodd">
            <td colspan="2" align='left' valign='top' class="tdfoot">&nbsp;</td>
          </tr>
        </form>
      </table>
	 </td>
  </tr>

</table>
<?=$skin->ShowFooter($area)?>