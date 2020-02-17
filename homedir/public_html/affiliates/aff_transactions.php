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

# Name: 		aff_transactions.php

#

# Description:  Affiliate transactions statement

#

# # Version:      8.0

#

######################################################################

include('../db_connect.php');
include_once('../validation_functions.php');
include('aff_session_handler.inc');



# retrieve the template

$area = 'affiliate';



$query="select min(year(pay_date)) as first_year from payments";

$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

$first_year = mysqli_fetch_object($retval);

if ($first_year->first_year=="") $first_year->first_year=date("Y");



if (isset($_POST['lstPeriod']) and $_POST['lstPeriod'] != '-Choose-' and $_POST['lstYear'] != '-Choose-') {



	$lstPeriod= sanitizeData($_POST['lstPeriod'], 'xss_clean') ;

	$lstYear= sanitizeData($_POST['lstYear'], 'xss_clean');

	$hdnyear= sanitizeData($_POST['lstYear'], 'xss_clean');

	$hdnperiod= sanitizeData($_POST['lstPeriod'], 'xss_clean') ;



	if (is_numeric($lstPeriod)) {

		$query="SELECT *,unix_timestamp(rec_buydate) as rec_buydate,unix_timestamp(rec_paydate) as rec_paydate FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND MONTH(rec_buydate) = '$lstPeriod' AND YEAR(rec_buydate) = '$lstYear'";

		$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

		$qrynum=mysqli_num_rows($result);

		$query="select SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND MONTH(rec_buydate) = '$lstPeriod' AND YEAR(rec_buydate) = '$lstYear'";

		$result2=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

		$sql_array2 = mysqli_fetch_array($result2);

		$sum_due = $sql_array2[0];

	} else {

		switch ($lstPeriod) {

			case 'Q1':

				$quarter='1';

				$query="SELECT *,unix_timestamp(rec_buydate) as rec_buydate,unix_timestamp(rec_paydate) as rec_paydate FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$qrynum=mysqli_num_rows($result);

				$query="select SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result2=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$sql_array2 = mysqli_fetch_array($result2);

				$sum_due = $sql_array2[0];

				break;

			case 'Q2':

				$quarter='2';

				$query="SELECT *,unix_timestamp(rec_buydate) as rec_buydate,unix_timestamp(rec_paydate) as rec_paydate FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$qrynum=mysqli_num_rows($result);

				$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result2=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$sql_array2 = mysqli_fetch_array($result2);

				$sum_due = $sql_array2[0];

				break;

			case 'Q3':

				$quarter='3';

				$query="SELECT *,unix_timestamp(rec_buydate) as rec_buydate,unix_timestamp(rec_paydate) as rec_paydate FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$qrynum=mysqli_num_rows($result);

				$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result2=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$sql_array2 = mysqli_fetch_array($result2);

				$sum_due = $sql_array2[0];

				break;

			case 'Q4':

				$quarter='4';

				$query="SELECT *,unix_timestamp(rec_buydate) as rec_buydate,unix_timestamp(rec_paydate) as rec_paydate FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$qrynum=mysqli_num_rows($result);

				$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND QUARTER(rec_buydate) = '$quarter' AND YEAR(rec_buydate) = '$lstYear'";

				$result2=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$sql_array2 = mysqli_fetch_array($result2);

				$sum_due = $sql_array2[0];

				break;

			case 'YR':

				$quarter='1';

				$query="SELECT *,unix_timestamp(rec_buydate) as rec_buydate,unix_timestamp(rec_paydate) as rec_paydate FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND YEAR(rec_buydate) = '$lstYear'";

				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$qrynum=mysqli_num_rows($result);

				$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$Sess_AffUserId' AND YEAR(rec_buydate) = '$lstYear'";

				$result2=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

				$sql_array2 = mysqli_fetch_array($result2);

				$sum_due = $sql_array2[0];

				break;

		}

	}

} else {

	$hdnperiod="";

	$hdnyear="";

}



mysqli_close($globalMysqlConn);



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <?

	require('aff_menu.php');

?>

  <tr>



    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/affiliates/aff_transactions.php' name='FrmReports'  onsubmit="return Validate_FrmReports();">

          <input type='hidden' name='hdnyear'>

          <input type='hidden' name='hdnperiod'>

          <tr>

            <td colspan="7" align='left' valign='top' class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdtoprow">

            <td align='left' valign='top'>

            <b><?php echo AFF_TRANSACTION_PERIOD?></b>

            </td>

            <td align='left' valign='top'>

            <b>

              <select class="inputf" size="1" name="lstPeriod">

                <option value="-<?php echo GENERAL_CHOOSE?>-"  <?php if ($hdnperiod == "") { print("selected");} ?>>-<?php echo GENERAL_CHOOSE?>-</option>

                <option value="01" <?php if ($hdnperiod == "01") { print("selected");} ?>><?php echo MONTH_F_JAN?></option>

                <option value="02" <?php if ($hdnperiod == "02") { print("selected");} ?>><?php echo MONTH_F_FEB?></option>

                <option value="03" <?php if ($hdnperiod == "03") { print("selected");} ?>><?php echo MONTH_F_MAR?></option>

                <option value="04" <?php if ($hdnperiod == "04") { print("selected");} ?>><?php echo MONTH_F_APR?></option>

                <option value="05" <?php if ($hdnperiod == "05") { print("selected");} ?>><?php echo MONTH_F_MAY?></option>

                <option value="06" <?php if ($hdnperiod == "06") { print("selected");} ?>><?php echo MONTH_F_JUN?></option>

                <option value="07" <?php if ($hdnperiod == "07") { print("selected");} ?>><?php echo MONTH_F_JUL?></option>

                <option value="08" <?php if ($hdnperiod == "08") { print("selected");} ?>><?php echo MONTH_F_AUG?></option>

                <option value="09" <?php if ($hdnperiod == "09") { print("selected");} ?>><?php echo MONTH_F_SEP?></option>

                <option value="10" <?php if ($hdnperiod == "10") { print("selected");} ?>><?php echo MONTH_F_OCT?></option>

                <option value="11" <?php if ($hdnperiod == "11") { print("selected");} ?>><?php echo MONTH_F_NOV?></option>

                <option value="12" <?php if ($hdnperiod == "12") { print("selected");} ?>><?php echo MONTH_F_DEC?></option>

                <option value="Q1" <?php if ($hdnperiod == "Q1") { print("selected");} ?>><?php echo AFF_TRANSACTION_QUATER?> 1</option>

                <option value="Q2" <?php if ($hdnperiod == "Q2") { print("selected");} ?>><?php echo AFF_TRANSACTION_QUATER?> 2</option>

                <option value="Q3" <?php if ($hdnperiod == "Q3") { print("selected");} ?>><?php echo AFF_TRANSACTION_QUATER?> 3</option>

                <option value="Q4" <?php if ($hdnperiod == "Q4") { print("selected");} ?>><?php echo AFF_TRANSACTION_QUATER?> 4</option>

                <option value="YR" <?php if ($hdnperiod == "YR") { print("selected");} ?>><?php echo AFF_TRANSACTION_SELECT_YEAR?></option>

              </select>

            </b>

            </td>

            <td align='left' valign='top'>

            <b>

            Year</b>

            </td>

            <td align='left' valign='top'>

            <b>

              <select class="inputf" size="1" name="lstYear">

                <option value="-Choose-"><?=REPORT_PAYMENTS_CHOOSE?></option>

				<?php

					for ($i=date("Y"); $i >= $first_year->first_year; $i--) {

						if ($i==$lstYear) $selected="selected"; else $selected="";

						print("<option value='$i' $selected>$i</option>");

					}

				?>

              </select>

        </b>

            </td>

            <td align='left' valign='top'>

            <b>

              <input name="btnSubmit" type="submit" class="button" value="<?php echo AFF_TRANSACTION_GET_REPORT?>">

        </b>

            </td>

            <td align='left' valign='top'>&nbsp;</td>

          </tr>

          <?php

if (isset($lstPeriod) and $lstPeriod != '-Choose-' and $lstYear != '-Choose-') {

print("

                 <tr class='tdodd'>

			         <td  ><b>".AFF_TRANSACTION_ID."</b></td>

			         <td  ><b>".AFF_TRANSACTION_TRANS_CODE."</b></td>

			         <td  ><b>".AFF_TRANSACTION_TRANS_DATE."</b></td>

			         <td  ><b>".AFF_TRANSACTION_AFF." %</b></td>

			  	     <td  ><b>".AFF_TRANSACTION_DUE."</b></td>

			  	     <td  ><b>".AFF_TRANSACTION_PAY_DATE."</b></td>

                </tr>



");

	while ($sql_array = mysqli_fetch_object($result) ) {

			print("

                <tr class='tdeven'>

                  <td  >$sql_array->rec_recno</td>

                  <td  >$sql_array->rec_transid</td>

                  <td  >".date($CONST_FORMAT_DATE_SHORT,$sql_array->rec_buydate)."</td>

                  <td  >$sql_array->rec_percentage %</td>

                  <td  >$CONST_SYMBOL $sql_array->rec_affamount</td>

                  <td  >".date($CONST_FORMAT_DATE_SHORT,$sql_array->rec_paydate)."</td>

                </tr>");

	}

	print("

	              <tr class='tdodd'>

	                  <td  >&nbsp;</td>

	                  <td  >&nbsp;</td>

	                  <td  >&nbsp;</td>

	                  <td  ><b>".AFF_TRANSACTION_TOTAL."</b></td>

	                  <td  ><b>$CONST_SYMBOL $sum_due</b></td>

	                  <td  >&nbsp;</td>

                </tr>

	");

}

?>

          <tr>



            <td valign='top' align='left' colspan="7" class="tdfoot">&nbsp;



            </td>

        </form>

      </table>

	 </td>

  </tr>



</table>

<?=$skin->ShowFooter($area)?>