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

# Name:         report_payments.php

#

# Description:  destroys affiliate session

#

# Version:      7.2

#

######################################################################



include('../db_connect.php');

include_once('../validation_functions.php'); 

include('../session_handler.inc');

include('permission.php');



if (isset($_POST['lstYear'])) $lstYear=sanitizeData($_POST['lstYear'], 'xss_clean');  



elseif (isset($_GET['lstYear'])) $lstYear= sanitizeData($_GET['lstYear'], 'xss_clean');  



else $lstYear=date('Y');



if (isset($_POST['lstMonth'])) $lstMonth=sanitizeData($_POST['lstMonth'], 'xss_clean');  



elseif (isset($_GET['lstMonth'])) $lstMonth=sanitizeData($_GET['lstMonth'], 'xss_clean'); 



else $lstMonth=date('m');



# retrieve the template

$area = 'member';



$query="select min(year(pay_date)) as first_year from payments";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$first_year=mysqli_fetch_object($retval);

if ($first_year->first_year=="") $first_year->first_year=date("Y");



if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {



    $query="SELECT *, DATE_FORMAT(pay_date,'%d-%M-%Y') AS paydate



        FROM payments LEFT JOIN members ON (pay_userid=mem_userid)



        WHERE MONTH(pay_date) = '$lstMonth'



        AND YEAR(pay_date) = '$lstYear' AND pay_transid !=''



        ORDER BY pay_date DESC";



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

    <td class="pageheader"><?=PAYMENTS_SECTION_NAME?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/report_payments.php' name='FrmReports'>

          <tr>

            <td colspan="6" align='left' valign='top' class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdtoprow">

            <td align='left' valign='top'> <b> </b> </td>

            <td align='left' valign='top' class="tdodd"> <b>

              <?=REPORT_PAYMENTS_MONTH?>

              <select class="inputf" size="1" name="lstMonth">

                <option value="-Choose-"  <?php if ($lstMonth == "") { print("selected");} ?>>

                <?=REPORT_PAYMENTS_CHOOSE?>

                </option>

                <option value="01" <?php if ($lstMonth == "01") { print("selected");} ?>>

                <?=MONTH_JAN?>

                </option>

                <option value="02" <?php if ($lstMonth == "02") { print("selected");} ?>>

                <?=MONTH_FEB?>

                </option>

                <option value="03" <?php if ($lstMonth == "03") { print("selected");} ?>>

                <?=MONTH_MAR?>

                </option>

                <option value="04" <?php if ($lstMonth == "04") { print("selected");} ?>>

                <?=MONTH_APR?>

                </option>

                <option value="05" <?php if ($lstMonth == "05") { print("selected");} ?>>

                <?=MONTH_MAY?>

                </option>

                <option value="06" <?php if ($lstMonth == "06") { print("selected");} ?>>

                <?=MONTH_JUN?>

                </option>

                <option value="07" <?php if ($lstMonth == "07") { print("selected");} ?>>

                <?=MONTH_JUL?>

                </option>

                <option value="08" <?php if ($lstMonth == "08") { print("selected");} ?>>

                <?=MONTH_AUG?>

                </option>

                <option value="09" <?php if ($lstMonth == "09") { print("selected");} ?>>

                <?=MONTH_SEP?>

                </option>

                <option value="10" <?php if ($lstMonth == "10") { print("selected");} ?>>

                <?=MONTH_OCT?>

                </option>

                <option value="11" <?php if ($lstMonth == "11") { print("selected");} ?>>

                <?=MONTH_NOV?>

                </option>

                <option value="12" <?php if ($lstMonth == "12") { print("selected");} ?>>

                <?=MONTH_DEC?>

                </option>

              </select>

              </b> </td>

            <td align='left' valign='top' class="tdodd"> <b>

              <?=REPORT_PAYMENTS_YEAR?>

              </b> </td>

            <td align='left' valign='top' class="tdodd"> <b>

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

            <td colspan=2 align='left' valign='top' class="tdodd"> <input name="btnSubmit" type="submit" class="button" value="<?=REPORT_PAYMENTS_GET?>">

            </td>

          </tr>

          <tr >

            <td colspan="6" align='left' valign='top' class="tdeven">&nbsp;</td>

          </tr>

          <?php

 if (isset($lstMonth) and $lstMonth != '-Choose-' and $lstYear != '-Choose-') {

    print("<tr class='tdhead'>

                             <td>&nbsp;</td>

                             <td ><b>".REPORT_PAYMENTS_TDATE."</b></td>

                             <td ><b>".GENERAL_MEMBER."</b></td>

                             <td ><b>".REPORT_PAYMENTS_TCODE."</b></td>

                             <td ><b>".REPORT_PAYMENTS_TSTATUS."</b></td>

                             <td ><b>".REPORT_PAYMENTS_AMOUNT."</b></td>

                        </tr>

                      ");

    while ($sql_array = mysqli_fetch_object($result) ) {

         if (strlen($sql_array->pay_transstatus) > 0){

		 

		   if ($sql_array->mem_username	== '') $style="style='color: red;'"; else $style='';

		   

		   print("<tr class='tdodd'>

					  <td $style>&nbsp;</td>

					  <td $style>".date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array->paydate))."</td>

					  <td $style>$sql_array->pay_username</td>

					  <td $style>$sql_array->pay_transid</td>

					  <td $style>$sql_array->pay_transstatus</td>

					  <td $style>$CONST_SYMBOL $sql_array->pay_samount</td>

					</tr> ");

        }

		if (strlen($sql_array->pay_transid) > 0 && $sql_array->pay_transstatus == 'Completed') $sum_ammount += $sql_array->pay_samount;

		if (strlen($sql_array->pay_transid) > 0 && $sql_array->pay_transstatus == 'Refunded') $sum_ammount -= $sql_array->pay_samount;

    }

    print("<tr class='tdfoot'>

             <td   colspan='6'> &nbsp;</td>

            </tr>

            <tr>

              <td  class='tdhead' colspan='6'><b>".REPORT_PAYMENTS_SUMMARY."</b></td>

            </tr>

             ");



        print("<tr class='tdodd'>

                  <td colspan='5'>&nbsp;</td>

                  <td>$CONST_SYMBOL ".round($sum_ammount,2)."</td>

                </tr> ");

    print("

            <tr class='tdfoot'>

              <td   align='right' colspan='6'><a href=\"$CONST_LINK_ROOT/admin/report_payments_csv.php?lstMonth=$lstMonth&lstYear=$lstYear\" target=_blank>".REPORT_PAYMENTS_GETCSV."</a></td>

            </tr>

");

}

// mysql_close($link);

?>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>



