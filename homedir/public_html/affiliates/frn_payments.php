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

# Name: 		frn_payments.php

#

# Description:  destroys affiliate session

#

# # Version:      8.0

#

######################################################################



include_once('../db_connect.php');
include_once('../validation_functions.php');
include_once('../session_handler.inc');

include('../admin/permission.php');

if (isset($_POST['lstYear'])) $lstYear=sanitizeData($_POST['lstYear'], 'xss_clean') ; else $lstYear=date('Y');

if (isset($_POST['lstMonth'])) $lstMonth=sanitizeData($_POST['lstMonth'], 'xss_clean') ;  else $lstMonth=date('m');

# retrieve the template

$area = 'member';



if (isset($_POST['chkPaid'])) $chkPaid=sanitizeData($_POST['chkPaid'], 'xss_clean') ; 



if (isset($_POST['UPDATE'])) {

	if ( isset( $chkPaid) ) { // the message variable is a list of msg_ids to delete from the email

			foreach ( $chkPaid as $value) {

					$query="UPDATE receipts SET rec_paid = 'Y' WHERE rec_recno=$value";

					$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

			}

	}

}



$query="select min(year(pay_date)) as first_year from payments";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$first_year=mysqli_fetch_object($retval);

if ($first_year->first_year=="") $first_year->first_year=date("Y");



if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {

    $query="SELECT *, unix_timestamp(rec_buydate) AS buydate ,aff_surname, aff_forename FROM receipts LEFT JOIN affiliates ON (rec_affuserid=aff_userid) WHERE MONTH(rec_paydate) = '$lstMonth' AND YEAR(rec_paydate) = '$lstYear' ORDER BY aff_surname, aff_forename, rec_paydate ASC";

    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    $qrynum=mysqli_num_rows($result);

}

?>

<?=$skin->ShowHeader($area)?>

    <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

      <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

      </tr>

      <tr>

    <td class="pageheader"><?php echo FRN_PAYMENTS_SECTION_NAME ?></td>

      </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

      <tr><td>

      <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/affiliates/frn_payments.php' name='FrmReports'>

          <tr>

            <td colspan="5" align='left' valign='top' class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdodd">

            <td align='left'> <b> <?php echo AFF_PAYMENT_MONTH ?></b> </td>

            <td align='left' valign='top'> <b>

              <select class="inputf" size="1" name="lstMonth">

                <option value="-Choose-"  <?php if ($lstMonth == "") { print("selected");} ?>>-<?php echo GENERAL_CHOOSE?>-</option>

                <option value="01" <?php if ($lstMonth == "01") { print("selected");} ?>><?php echo MONTH_F_JAN?></option>

                <option value="02" <?php if ($lstMonth == "02") { print("selected");} ?>><?php echo MONTH_F_FEB?></option>

                <option value="03" <?php if ($lstMonth == "03") { print("selected");} ?>><?php echo MONTH_F_MAR?></option>

                <option value="04" <?php if ($lstMonth == "04") { print("selected");} ?>><?php echo MONTH_F_APR?></option>

                <option value="05" <?php if ($lstMonth == "05") { print("selected");} ?>><?php echo MONTH_F_MAY?></option>

                <option value="06" <?php if ($lstMonth == "06") { print("selected");} ?>><?php echo MONTH_F_JUN?></option>

                <option value="07" <?php if ($lstMonth == "07") { print("selected");} ?>><?php echo MONTH_F_JUL?></option>

                <option value="08" <?php if ($lstMonth == "08") { print("selected");} ?>><?php echo MONTH_F_AUG?></option>

                <option value="09" <?php if ($lstMonth == "09") { print("selected");} ?>><?php echo MONTH_F_SEP?></option>

                <option value="10" <?php if ($lstMonth == "10") { print("selected");} ?>><?php echo MONTH_F_OCT?></option>

                <option value="11" <?php if ($lstMonth == "11") { print("selected");} ?>><?php echo MONTH_F_NOV?></option>

                <option value="12" <?php if ($lstMonth == "12") { print("selected");} ?>><?php echo MONTH_F_DEC?></option>

              </select>

              </b> </td>

            <td align='left' valign='top'> <b> <?php echo AFF_PAYMENT_YEAR ?></b>

            </td>

            <td align='left' valign='top'> <b>

              <select class="inputf" size="1" name="lstYear">

                <option value="-Choose-"><?=REPORT_PAYMENTS_CHOOSE?></option>

				<?php

					for ($i=date("Y"); $i >= $first_year->first_year; $i--) {

						if ($i==$lstYear) $selected="selected"; else $selected="";

						print("<option value='$i' $selected>$i</option>");

					}

				?>

              </select>

              </b> </td>

            <td align='left' valign='top'> <b>

              <input name="btnSubmit" type="submit" class="button" value="<?php echo AFF_PAYMENT_GET_REPORT?>">

              </b> </td>

          </tr>

          <tr>

            <td colspan="5" align='left' class="tdeven">&nbsp;</td>

          </tr>

          <?php

 if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {

    print("

                         <tr class='tdodd'>

                             <td ><b>".FRN_PAYMENTS_AFFILIATE."</b></td>

                             <td ><b>".AFF_PAYMENT_TRANS_CODE."</b></td>

                             <td><b>".AFF_PAYMENT_TRANS_DATE."</b></td>

                             <td><b>".FRN_PAYMENTS_AMOUNT."</b></td>

                             <td ><b>".REPORT_PAYMENTS_PAID."</b></td>

                        </tr>

                        ");

    while ($sql_array = mysqli_fetch_object($result) ) {

        print("<tr class='tdeven'>

                  <td >$sql_array->aff_surname, $sql_array->aff_forename</td>

                  <td >$sql_array->rec_transid</td>

                     <td>".date($CONST_FORMAT_DATE_SHORT,$sql_array->buydate)."</td>

                     <td>$CONST_SYMBOL $sql_array->rec_affamount</td>

                                       <td >");

				  if ($sql_array->rec_paid == 'Y')

					  print("<input type='checkbox' name=chkPaid[] value='$sql_array->rec_recno' checked disabled></td></tr> ");

				  else

					  print("<input type='checkbox' name=chkPaid[] value='$sql_array->rec_recno'></td></tr>");

    }

    print("

            <tr class='tdfoot'>

              <td colspan='5' align='right'><input class='button' type='submit' name='UPDATE' value='".BUTTON_UPDATE."'></td>

              </tr>



             ");

    $query="SELECT SUM(rec_affamount) AS affamount, aff_surname, aff_forename FROM receipts LEFT JOIN affiliates ON (rec_affuserid=aff_userid) WHERE MONTH(rec_paydate) = '$lstMonth' AND YEAR(rec_paydate) = '$lstYear' GROUP BY aff_surname, aff_forename";

    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    print("

            <tr class='tdeven'>

              <td colspan='5'><b>".FRN_PAYMENTS_SUMMARY."</b></td>

              </tr>



             ");

    while ($sql_array = mysqli_fetch_object($result) ) {

        $sql_array->affamount=number_format($sql_array->affamount,2);

        print("<tr class='tdodd'>

                  <td colspan='2'>$sql_array->aff_surname, $sql_array->aff_forename</td>

                  <td >$CONST_SYMBOL $sql_array->affamount</td>

                     <td></td>

                     <td></td>

                </tr> ");

    }

    print("

            <tr class='tdfoot'>

              <td align='center' colspan='5'><input type='button' class='button' name='printer' value='".FRN_PAYMENTS_PRINTER."' onclick=\"MDM_openWindow('$CONST_LINK_ROOT/prn_payments.php?lstMonth=$lstMonth&lstYear=$lstYear','".FRN_PAYMENTS_PAYMENTS."','resizable=yes, scrollbars=yes, personalbars=no, toolbar=yes, width=640,height=550')\"></td>

              </tr>

    </table>

    </center></div>");

}

// mysqli_close($link);

?>

        </form>

      </table></td>

      </tr>

    </table>



<?=$skin->ShowFooter($area)?>