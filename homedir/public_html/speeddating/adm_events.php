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

# Name:                 adm_events.php

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

include('../admin/permission.php');



if($_POST['action'] == 'save') {

    $sde_date = $sde_year."-".$sde_month."-".$sde_day." ".$sde_hour.":".$sde_minute.":00";

    if (trim($sde_name) == "")

        $error_message=SD_EVENT_ERROR_NAME;

    elseif (!is_numeric($sde_gender1_places) || !is_numeric($sde_gender2_places))

        $error_message=SD_EVENT_ERROR_NUMBER;

    elseif (!is_numeric($sde_price))

        $error_message=SD_EVENT_ERROR_PRICE;

    elseif (!checkdate((int)$sde_month, (int)$sde_day, (int)$sde_year))

        $error_message=SD_EVENT_ERROR_DATE;

    if($error_message)

    {

        error_page($error_message,GENERAL_USER_ERROR);

        exit;

    }



    $sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM sd_events WHERE sde_eventid='$sde_eventid'");

    if (!mysqli_num_rows($sql_result)){

        $query = "

            INSERT INTO sd_events

            SET

                sde_name = '$sde_name',

                sde_date = '$sde_date',

                sde_gender1 = '$sde_gender1',

                sde_gender2 = '$sde_gender2',

                sde_age_from = '$sde_age_from',

                sde_age_to = '$sde_age_to',

                sde_gender1_places = '$sde_gender1_places',

                sde_gender2_places = '$sde_gender2_places',

                sde_venueid = '$sde_venueid',

                sde_description = '$sde_description',

                sde_special = '$sde_special',

                sde_price = '".round($sde_price,2)."'";

    }else{

        $query = "

            UPDATE sd_events

            SET

                sde_name = '$sde_name',

                sde_date = '$sde_date',

                sde_gender1 = '$sde_gender1',

                sde_gender2 = '$sde_gender2',

                sde_age_from = '$sde_age_from',

                sde_age_to = '$sde_age_to',

                sde_gender1_places = '$sde_gender1_places',

                sde_gender2_places = '$sde_gender2_places',

                sde_venueid = '$sde_venueid',

                sde_description = '$sde_description',

                sde_is_special = '".($sde_is_special ? 'yes':'no')."',

                sde_special = '$sde_special',

                sde_price = '".round($sde_price,2)."'

            WHERE sde_eventid='$sde_eventid'";

    }

    mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

}

if($_GET['action'] == 'remove')

{

    $sde_eventid = $_GET['sde_eventid'];

    $sql_query = "DELETE FROM sd_events WHERE sde_eventid = $sde_eventid";

    mysqli_query($globalMysqlConn, $sql_query);

}



# retrieve the template

$area = 'member';





$sql_result = mysqli_query($globalMysqlConn," SELECT * FROM sd_events");





?>

<?=$skin->ShowHeader($area)?>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo SD_EVENTS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <form method="post" action="<?php echo $CONST_LINK_ROOT?>/speeddating/events.php" name="frmPremFunc">

      <tr>

        <td class="tdhead">&nbsp;

</td>

      </tr>

      <tr>

        <td>

        <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

            <tr class="tdtoprow">

              <td>

                <?=SD_EVENTS_NAME?>

              </td>

              <td>

                <?=SD_EVENTS_TICKETS?>

              </td>

              <td>

                <?=SD_EVENT_BOOKED_RESULTS?>

              </td>

              <td>

                <?=SD_EVENTS_DATE?>

              </td>

              <td>

                <?=GENERAL_DELETE?>

              </td>

            </tr>

            <?php

                    while($event = mysqli_fetch_object($sql_result))

                    {

                        $sql_query = "  SELECT COUNT(if(sdt_gender='Gender1',1,null)) G1_qty, COUNT(if(sdt_gender='Gender2',1,null)) G2_qty

                                        FROM sd_tickets

                                        WHERE sdt_eventid = '$event->sde_eventid'";

                        $result = mysqli_query($globalMysqlConn,$sql_query) or die(mysqli_error());

                        $booked = mysqli_fetch_object($result);



                        $booked1 = $booked->G1_qty;

                        $booked2 = $booked->G2_qty;

                        ?>

<?php if ($event->sde_gender1_places < $booked1 || $event->sde_gender2_places < $booked2) { ?>

            <tr class="overbooked">

<?php } else { ?>

            <tr class="tdodd">

<?php } ?>

              <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_event_edit.php?sde_eventid=<?=$event->sde_eventid?>">

                <?=htmlspecialchars($event->sde_name)?>

                </a></td>

              <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_event_tickets.php?sde_eventid=<?=$event->sde_eventid?>">

                <?=SD_EVENT_SHOW?>

                </a></td>

              <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_matches.php?event_id=<?=$event->sde_eventid?>">

                <?=SD_EVENT_BOOKED_SET?>

                </a></td>

              <td>

                <?=date("$CONST_FORMAT_DATE_SHORT $CONST_FORMAT_TIME_SHORT", strtotime($event->sde_date))?>

              </td>

              <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_events.php?action=remove&sde_eventid=<?=$event->sde_eventid?>" onClick="if (confirm('<?=SD_EVENT_DELETE?>')) {return true;} else {return false;}" >[

                <?=GENERAL_DELETE?>

                ]</a></td>

            </tr>

            <?php

                    }

                    ?>

            <tr>

              <td colspan="5" class="tdfoot">

                <?=SD_EVENT_COMMENT?>

              </td>

            </tr>

          </table></td>

      </tr>

      <tr>

        <td class="tdfoot" align=center>

<input name="button" type="button" class='button' onClick="document.location.href = '<?=$CONST_LINK_ROOT?>/speeddating/adm_event_edit.php'" value="<?=SD_EVENTS_ADD?>">&nbsp;<input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>"></td>

      </tr>

    </form>

  </table>









<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>