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

# Name: 		get_premium.php

#

# Description:  Member payment term selection screen

#

# # Version:      8.0

#

######################################################################

include('functions.php');

include_once('db_connect.php');

include('session_handler.inc');

include('error.php');


# retrieve the template

    $area = 'member';

if ($_REQUEST['lstPayOptions']){

    switch ($_REQUEST['lstPayOptions']) {

        case '1':

            $amount = $option_manager->GetValue('1month');

            $desc = GETPREMIUM_1MONTH_MEMBERSHIP;

            $params['period'] = 'month';

            $params['number'] = 1;

            break;

        case '2':

            $amount = $option_manager->GetValue('3month');

            $desc = GETPREMIUM_3MONTHS_MEMBERSHIP;

            $params['period'] = 'month';

            $params['number'] = 3;

            break;

        case '3':

            $amount = $option_manager->GetValue('6month');

            $desc = GETPREMIUM_6MONTHS_MEMBERSHIP;

            $params['period'] = 'month';

            $params['number'] = 6;

            break;

        case '4':

            $amount = $option_manager->GetValue('12month');

            $desc = GETPREMIUM_12MONTHS_MEMBERSHIP;

            $params['period'] = 'month';

            $params['number'] = 12;

            break;

    }

    $params = mysqli_real_escape_string($globalMysqlConn, serialize($params));

    $query="INSERT INTO payments SET

                        pay_userid  = '$Sess_UserId',

						pay_username = '$Sess_UserName',

                        pay_samount = '$amount',

                        pay_service = 'premium',

                        pay_message = '$desc',

                        pay_params  = '$params'";

    $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

    header("Location: $CONST_LINK_ROOT/payments/payment_passthru.php?payment_id=".mysqli_insert_id($globalMysqlConn));

}

function get_available_ps($amount)

{

    global $CONST_INCLUDE_ROOT, $CONST_LINK_ROOT;

    $systems = get_allow_payments('premium',$amount);

//print_r($systems);

    foreach ($systems as $ps){

       if(file_exists($CONST_INCLUDE_ROOT."/help/desc_".$ps->ps_prefix.".php")) {

           $ps_list[] = "<a target=_blank title='Read Disclaimer' href='".$CONST_LINK_ROOT."/help/desc_".$ps->ps_prefix.".php'>".$ps->ps_title."</a>";

       } else {

           $ps_list[] = $ps->ps_title;

       }

    }

    return implode((array)$ps_list, ', ');

}

$query = "  SELECT *

            FROM payment_service_params

            WHERE psp_service = 'premium'";

$res = mysqli_query($globalMysqlConn, $query);

$service = mysqli_fetch_object($res);

$month1= $option_manager->GetValue('1month');

$month3= $option_manager->GetValue('3month');

$month6= $option_manager->GetValue('6month');

$month12= $option_manager->GetValue('12month');

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr valign="top">

      <td colspan="2" align="right" class="bl">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <form action="<?= $CONST_LINK_ROOT ?>/get_premium.php" method="post">

    <tr>

      <td width="45%" class="pageheader"><?php echo JOIN_SECTION_NAME?></td>
      <td align="right" width="55%" rowspan="2"><img src="<?=$CONST_LINK_ROOT?>/images/premium-membership.png"></td>
    </tr>

    <tr>

      <td valign="middle"> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

          <tr>

            <td class="tdhead"><?php echo GET_PREMIUM_TEXT ?></td>

          </tr>

          <?php if(strlen($ps_list = get_available_ps($month1)) && $month1 > 0) { ?>

          <tr>

            <td class="tdodd"> <input type="radio" name="lstPayOptions" value="1" checked>

              <?php echo $CONST_SYMBOL ?><?php print($month1); ?> - <?php echo GETPREMIUM_1MONTH_MEMBERSHIP ?>

              (

              <?=$ps_list?>

              ) </td>

          </tr>

          <?php } ?>

          <?php if(strlen($ps_list = get_available_ps($month3)) && $month3 > 0) { ?>

          <tr>

            <td class="tdodd"> <input type="radio" name="lstPayOptions" value="2">

              <?php echo $CONST_SYMBOL ?><?php print($month3); ?> - <?php echo GETPREMIUM_3MONTHS_MEMBERSHIP ?>

              (

              <?=$ps_list?>

              ) </td>

          </tr>

          <?php } ?>

          <?php if(strlen($ps_list = get_available_ps($month6)) && $month6 > 0) { ?>

          <tr>

            <td class="tdodd"> <input type="radio" name="lstPayOptions" value="3">

              <?php echo $CONST_SYMBOL ?><?php print($month6); ?> - <?php echo GETPREMIUM_6MONTHS_MEMBERSHIP ?>

              (

              <?=$ps_list?>

              ) </td>

          </tr>

          <?php } ?>

          <?php if(strlen($ps_list = get_available_ps($month12)) && $month12 > 0) { ?>

          <tr>

            <td class="tdodd"> <input type="radio" name="lstPayOptions" value="4">

              <?php echo $CONST_SYMBOL ?><?php print($month12); ?> - <?php echo GETPREMIUM_12MONTHS_MEMBERSHIP ?>

              (

              <?=$ps_list?>

              ) </td>

          </tr>

          <?php } ?>

          <tr>

            <td class="tdfoot"> <input type="submit" value="<?=BUTTON_CONTINUE?>" class=button name="btnSubmit">

            </td>

          </tr>

          <tr>

            <td >

              <?if ($service->psp_type == 'recurring'){?>

              <?=GET_PREMIUM_NOTE?>

              <?}?>

            </td>

          </tr>

        </table></td>

    </tr>

  </form>

</table>

<?=$skin->ShowFooter($area)?>