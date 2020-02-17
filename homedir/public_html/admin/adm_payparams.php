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

# Name:                 adm_payparams.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include_once('../validation_functions.php');

include('../session_handler.inc');

include('../message.php');

require_once('../error.php');

require_once('../functions.php');

include('permission.php');



if (empty($_REQUEST['ps_id'])) {

        $error_message=SD_PRGSUBSCRIBE_MAIL_INCORRECT;

        error_page($error_message,GENERAL_USER_ERROR);

        exit;

}



if($_POST['act']) {

    if($_POST['act'] == "save_main_settings") {
        
       $max_amount=  sanitizeData($_POST['max_amount'], 'xss_clean');
       $ps_id=  sanitizeData($_POST['ps_id'], 'xss_clean');

        $sql_query = "UPDATE payment_systems SET ps_max_amount = '".$max_amount."' WHERE ps_id = '".$ps_id."'";

        mysqli_query($globalMysqlConn,$sql_query);

    }

    elseif($_POST['act'] == "save_serv_settings") {

        foreach ($_REQUEST['params'] as $psp_id => $par_array) {
            
            $psp_value= sanitizeData($par_array['psp_value'], 'xss_clean');

            $query = "UPDATE payment_params

                      SET

                        psp_value = '".$psp_value."'

                      WHERE psp_id='$psp_id'";

            mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

    }

     header("Location: $CONST_LINK_ROOT/admin/adm_paysystems.php");

    exit;

}

$pay_service = ($_REQUEST['pay_service']) ? sanitizeData($_REQUEST['pay_service'], 'xss_clean') : 'premium';


$ps_id=sanitizeData($_REQUEST['ps_id'], 'xss_clean');
$query = "SELECT * FROM payment_systems WHERE ps_id = '".$ps_id."'";

$res = mysqli_query($globalMysqlConn,$query);

$payment_system = mysqli_fetch_object($res);



$params = get_payment_params($payment_system->ps_prefix, $pay_service, 'admin');



$query = "  SELECT *

            FROM payment_systems a

                INNER JOIN payment_services b

                    on (a.ps_id = b.ps_id)

            WHERE a.ps_id = '".$ps_id."'";

$pay_serv_res = mysqli_query($globalMysqlConn,$query);



$query = "  SELECT *

            FROM payment_service_params ";

$res = mysqli_query($globalMysqlConn,$query);

while ($row = mysqli_fetch_object($res)) {

    $SERVICES[$row->psp_service] = $row->psp_title ;

};



# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo ADM_PAYMENTS_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

        <td valign="top" align="left" class="tdtoprow"><?=$payment_system->ps_title?></td>

    </tr>

    <tr>

        <td>

            <table border="0" width="100%" cellpadding="2" cellspacing="10">

                <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_payparams.php">

                <input type="hidden" name="ps_id" value="<?=$ps_id?>">

                <input type="hidden" name="act" value="save_main_settings">

                <tr>

                    <td colspan="3" align="left" valign="top" class="tdhead">&nbsp; </td>

                </tr>

                <tr align=center>

                    <th align=right>

                        <?=ADM_PAYMENTS_MAX_AMOUNT?>

                    </th>

                    <td align="left">

                        <input type=text class="inputs" name="max_amount" value="<?=$payment_system->ps_max_amount?>">

                    </td>

                    <td></td>

                </tr>

                <tr>

                    <td align="center"  colspan="3" class="tdfoot">

                        <input type=submit class=button name=SAVE value="<?=GENERAL_SAVE?>">

                        <input type=button class=button name=CANCEL value="Cancel" onClick="location.href='<?=$CONST_LINK_ROOT?>/admin/adm_paysystems.php'">

                    </td>

                </tr>

                </form>

           </table>



        </td>

    </tr>



    <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_payparams.php" name="frmPremFunc">

    <input type="hidden" name="ps_id" value="<?=$ps_id?>">

       <input type="hidden" name="act" value="">

    <tr>

        <td align="center">



            <select name=pay_service onChange="this.form.submit();" class="inputf">

<?php while ($row = mysqli_fetch_object($pay_serv_res)) { ?>

                <option value="<?=$row->pay_service?>" <?if ($pay_service==$row->pay_service) echo "selected"?>><?=$SERVICES[$row->pay_service]?>

<?php } ?>

            </select>

        </td>

    </tr>

    <tr>

        <td>

            <table border="0" width=100% cellpadding="2" cellspacing="10">

                <tr>

                    <td colspan="4" align="left" valign="top" class="tdhead">&nbsp; </td>

                </tr>

                <?php

                foreach ($params as $param)

                {

                    ?>

                    <tr align=center>

                        <th align=right>

                            <?=$param->psp_name?>

                        </th>

                        <td>

                            <input type=text class="input" name="params[<?=$param->psp_id?>][psp_value]" value="<?=$param->psp_value?>">

                        </td>

                        <td>

                            <?=$param->psp_type?>

                        </td>

                        <td class="tdtoprow">

                            <?=$param->psp_description?>

                        </td>

                    </tr>

                    <?php

                }

                ?>

                    <tr>

                        <td align="center"  colspan="4" class="tdfoot">

                            <input type=submit class=button name=SAVE value="<?=GENERAL_SAVE?>" onClick="this.form['act'].value = 'save_serv_settings'">

                            <input type=button class=button name=CANCEL value="Cancel" onClick="location.href='<?=$CONST_LINK_ROOT?>/admin/adm_paysystems.php'">

                        </td>

                    </tr>

           </table>



        </td>

    </tr>

    </form>

</table>

<?php //mysql_close( $link ); 
?>

<?=$skin->ShowFooter($area)?>