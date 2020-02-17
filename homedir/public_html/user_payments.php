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

# Name:         user_payments.php

#

# Description:  destroys affiliate session

#

# Version:      7.2

#

######################################################################





include('db_connect.php');

include('session_handler.inc');

include_once('validation_functions.php'); 


if (isset($_POST['lstYear'])) $lstYear=sanitizeData(trim($_POST['lstYear']), 'xss_clean');   

elseif (isset($_GET['lstYear'])) $lstYear=sanitizeData(trim($_GET['lstYear']), 'xss_clean');   

else $lstYear=date('Y');



if (isset($_POST['lstMonth'])) $lstMonth=sanitizeData(trim($_POST['lstMonth']), 'xss_clean');  

elseif (isset($_GET['lstMonth'])) $lstMonth=sanitizeData(trim($_GET['lstMonth']), 'xss_clean');  

else $lstMonth=date('m');



# retrieve the template

$area = 'member';



$query="SELECT *, DATE_FORMAT(pay_date,'%d-%M-%Y') AS paydate

        FROM payments

        WHERE pay_userid = '$Sess_UserId' AND pay_transid != '0'

        ORDER BY pay_date DESC";

    $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

    $qrynum=mysqli_num_rows($result);


?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo PAYMENTS_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT ?>/user_payments.php' name='FrmReports'>

          <tr>

            <td align='left' valign='top' class="tdhead">&nbsp;</td>

          </tr>

          <?php

    print("<tr >



                <td valign='top' align='left'  class='tdodd'>



                     <table border='0' cellpadding='3' width='100%' cellspacing='1'>

                         <tr class='tdtoprow'>

                             <td><b>".USER_PAYMENTS_TDATE."</b></td>

                             <td><b>".USER_PAYMENTS_TCODE."</b></td>

                             <td><b>".USER_PAYMENTS_TSTATUS."</b></td>

                             <td   class='table_end'><b>".USER_PAYMENTS_AMOUNT."</b></td>

                        </tr>

                        ");

    while ($sqli_array = mysqli_fetch_object($result) ) {

        if (strlen($sqli_array->pay_transstatus) > 0){

			print("<tr  class='tdeven'>

                  <td>".date($CONST_FORMAT_DATE_SHORT,strtotime($sqli_array->paydate))."</td>

                  <td>$sqli_array->pay_transid</td>

                  <td>$sqli_array->pay_transstatus</td>

                  <td>$CONST_SYMBOL $sqli_array->pay_samount</td>



                </tr> ");

		}

		if (strlen($sqli_array->pay_transid) > 0) $sum_ammount += $sqli_array->pay_samount;

    }



    print("

            <tr class='tdtoprow'>

              <td colspan='4' align='center'><b>".USER_PAYMENTS_SUMMARY."</b></td>

            </tr>



             ");



        print("<tr class='tdeven'>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                  <td>$CONST_SYMBOL ".round($sum_ammount,2)."</td>

                </tr> ");



    print("

            <tr  class='tdeven'>

              <td   align='right' colspan='4'><a href=\"$CONST_LINK_ROOT/user_payments_csv.php?lstMonth=$lstMonth&lstYear=$lstYear\" target=_blank>".USER_PAYMENTS_GET_CSV."</a>&nbsp;</td>

            </tr>

    </table>

");



mysqli_close($globalMysqlConn);

?>

          <tr>

            <td class='tdfoot' align='left' valign='top'>&nbsp;</td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>