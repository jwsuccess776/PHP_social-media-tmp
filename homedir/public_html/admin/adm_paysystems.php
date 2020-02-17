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

# Name:                 adm_paysystems.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

require_once('../error.php');

require_once('../functions.php');

include('permission.php');

include_once('../validation_functions.php'); 

if($_POST['action'] == 'save') {

	if (trim($pay_title) == "")

		$error_message="Please enter title";

	if($error_message)

	{

		error_page($error_message,GENERAL_USER_ERROR);

		exit;

	}

	//$pay_id = $_POST['pay_id'];

	$query = "UPDATE payment_systems SET ps_title = '$pay_title' WHERE ps_id='$pay_id'";

	mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

}

if($_GET['action'] == 'status')

{

	$pay_id =sanitizeData(trim($_GET['pay_id']), 'xss_clean');  

	$sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM  payment_systems WHERE ps_id='$pay_id'");

	$payments = mysqli_fetch_object($sql_result);

	if ($payments->ps_active == 'yes') {

		$sql_query = "UPDATE payment_systems SET ps_active = 'no' WHERE ps_id='$pay_id'";

	} else {

		$sql_query = "UPDATE payment_systems SET ps_active = 'yes' WHERE ps_id='$pay_id'";

	}

	mysqli_query($globalMysqlConn,$sql_query);

}



$query = "  SELECT *

            FROM payment_service_params ";

$service_res = mysqli_query($globalMysqlConn,$query);



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

    <td>



    <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_paysystems.php" name="frmPremFunc">

            <table width="100%"  border="0" cellspacing="0" cellpadding="4">

                <tr>

                    <th width=46% class="tdtoprow"><?=ADM_PAYMENTS_TITLE?></td>

                    <th width=20% class="tdtoprow"><?=ADM_PAYMENTS_PREFIX?></td>

                    <th width=12% class="tdtoprow"><?=ADM_PAYMENTS_CONFIGURE?></td>

                    <th width=12% class="tdtoprow"><?=ADM_PAYMENTS_STATUS?></td>

                    <th width=12% class="tdtoprow"><?=ucfirst(GENERAL_EDIT)?></td>

                </tr>

                <?php

                $payments = get_payments_list();



                foreach ($payments as $payment)

                {

                    ?>

                    <tr align=left>

                        <td><?if($payment->set == 'yes'){?>

                                <font color=#0000FF><?=$payment->ps_title?> <b>(Set)</b>

                            <?}else{?>

                                <font color=#FF0000><?=$payment->ps_title?> <b>(Unset)</b>

                            <?}?></td>

                        <td class="tdodd"><?=$payment->ps_prefix?></td>

                        <td class="tdodd"><a href="<?=$CONST_LINK_ROOT?>/admin/adm_payparams.php?ps_id=<?=$payment->ps_id?>"><?=GENERAL_EDIT?></a></td>

                        <td class="tdodd"><a href="<?=$CONST_LINK_ROOT?>/admin/adm_paysystems.php?action=status&pay_id=<?=$payment->ps_id?>"><?= $payment->ps_active=='yes' ? ADM_PAYMENTS_DEACTIVATE : ADM_PAYMENTS_ACTIVATE ?></a></td>

                        <td class="tdodd"><a href="<?=$CONST_LINK_ROOT?>/admin/adm_payedit.php?pay_id=<?=$payment->ps_id?>"><?=GENERAL_EDIT?></a></td>

                    </tr>

                    <?php

                }

                ?>

                </table>

        </form>



            </td>

        </tr>

	</table>

<?php //mysqli_close( $link );
 ?>

<?=$skin->ShowFooter($area)?>