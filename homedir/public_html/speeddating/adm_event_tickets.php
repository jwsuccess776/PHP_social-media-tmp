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

# Name:                 adm_event_tickets.php

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

include_once('../validation_functions.php');



if (!$sde_eventid){

    error_page("Incorrect event",GENERAL_USER_ERROR);

    exit;

}

if ($_REQUEST['sdt_ticket_id']){

   $sdt_ticket_id= sanitizeData($_REQUEST['sdt_ticket_id'], 'xss_clean') ;  


    $cancel_query = "  SELECT * FROM `sd_tickets` a

                       INNER JOIN members b

                            ON (a.sdt_userid = b.mem_userid)

                       INNER JOIN sd_events c

                            ON (a.sdt_eventid = c.sde_eventid)

                       WHERE sdt_ticket_id = ".$sdt_ticket_id;

    $cancel_res = mysqli_query($globalMysqlConn,$cancel_query);

    echo mysqli_error();

    $cancel_user = mysqli_fetch_object($cancel_res);

    mysqli_query($globalMysqlConn,"DELETE FROM sd_tickets WHERE sdt_ticket_id = ".$cancel_user->sdt_ticket_id);

    echo mysqli_error();

    $data['ReceiverName'] =str_replace(" ","%20",$cancel_user->mem_username);

    $data['SenderName'] = $Sess_UserName;

    $data['TicketNumber'] = $cancel_user->sdt_ticket_num;

    $data['EventName'] = $cancel_user->sde_name;

    list($type,$message) = getTemplateByName("SD_Cancel_Ticket",$data);



    # send the mail externally

    send_mail ("$cancel_user->mem_email", $CONST_MAIL, ADM_EVENT_TICKETS_SUBJECT , "$message",$type,"ON");

}



$ticket_query = "  SELECT *,now() as today FROM `sd_tickets` a

                   LEFT JOIN members b

                        ON (a.sdt_userid = b.mem_userid)

                   WHERE sdt_eventid = $sde_eventid";

$ticket_res = mysqli_query($globalMysqlConn,$ticket_query);

$ticket_count = mysqli_num_rows($ticket_res);



$event_query = " SELECT * FROM `sd_events`

                 WHERE sde_eventid = $sde_eventid";

$event_res = mysqli_query($globalMysqlConn,$event_query);

$event = mysqli_fetch_object($event_res);



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

      <td class="pageheader"><?php echo SD_EVENTS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><?php print("(<strong>$event->sde_name</strong>)"); ?> <?php printf(ADM_EVENT_TICKETS_COUNT,$event->sde_gender1_places+$event->sde_gender2_places,$ticket_count);?>

      </td>

    </tr>

    <tr>

      <td>

      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

          <tr>

            <th colspan="4"  class="tdhead"></tr>

          <tr class="tdtoprow">

            <td>

              <?=SD_EVENTS_NAME?>

            <td>

              <?=ADM_EVENT_TICKETS_GENDER?>

            <td>

              <?=ADM_EVENT_TICKETS_TICKET?>

              #

            <td>

              <?=ADM_EVENT_TICKETS_CANCEL?>

          </tr>

          <?php

                    while($ticket = mysqli_fetch_object($ticket_res))

                    {

                        ?>

          <tr class=tdodd>

            <td>

              <?=$ticket->mem_username ?>

            </td>

            <td>

              <?if ($ticket->sdt_gender == 'Gender1'){?>

              <?=$event->sde_gender1?>

              <?}else{?>

              <?=$event->sde_gender2?>

              <?}?>

            </td>

            <td>

              <?=sprintf("%02d", $ticket->sdt_ticket_num); ?>

            </td>

            <form action="<?=$PHP_SELF?>" method="post">

            <input type="hidden" name="sdt_ticket_id" value="<?=$ticket->sdt_ticket_id?>"/>

            <input type="hidden" name="sde_eventid" value="<?=$sde_eventid?>"/>

            <td>

<?if($ticket->today < $event->sde_date){?>

              <input type=button class=button name=CANCEL value="Cancel" onClick="if (confirm('Are you sure')) this.form.submit();">

<?}?>

            </td>

            </form>

          </tr>

          <?php

                    }

                    ?>

          <tr align=center >

            <td class="tdfoot">&nbsp;</td>

            <td class="tdfoot">&nbsp;</td>

            <td class="tdfoot">&nbsp;</td>

            <td class="tdfoot">&nbsp;</td>

          </tr>

        <tr>

            <td align="center" colspan=4 class="tdodd"><input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>"></td>

        </tr>

        </table></td>

    </tr>

  </table>







<?=$skin->ShowFooter($area)?>