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

# Name:                 adm_report_event.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('session_handler.inc');

include('../message.php');

include('../admin/permission.php');



include('error.php');

# retrieve the template

$area = 'member';

$sde_eventid = formGet('sde_eventid');

?>

<?if (!isset($print)) echo $skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td class="pageheader">

        <?=SD_ADM_REPORT_EVENTS_SECTION_NAME?>

      </td>

    </tr>

  <tr>

    <td><? if (!isset($print)) include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>



   <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">



          <form method=post>

            <?php

        $sql_query = "SELECT * FROM sd_events";

        $events = mysqli_query($globalMysqlConn,$sql_query);

        ?>

            <tr>

              <td colspan="4" align="left" class="tdhead"> <select name=sde_eventid class="inputf" onChange="if(this.value!=0)this.form.submit()">

                  <option value=0>

                  <?=SD_ADM_REPORT_EVENT_SELECT?>

                  <?php while($event=mysqli_fetch_object($events)){?>

                  <option value="<?=$event->sde_eventid?>" <?if ($event->sde_eventid==$sde_eventid)echo "SELECTED" ?>>

                  <?=$event->sde_name?>

                  <?}?>

                </select> </td>

            </tr>

            <?php

        $sql_query = "  SELECT *

                        FROM sd_tickets st

                            INNER JOIN members m

                              ON (m.mem_userid = st.sdt_userid )

                        WHERE sdt_eventid = '$sde_eventid'

                        ORDER BY mem_sex,mem_surname,mem_forename,mem_userid

                    ";

        $guests = mysqli_query($globalMysqlConn,$sql_query);



        if (mysqli_num_rows($guests)) {

            ?>

            <tr class="tdtoprow">

              <td>

                <?=SD_ADM_REPORT_EVENT_NAME?>

              </td>

              <td>

                <?=SD_ADM_REPORT_EVENT_GENDER?>

              </td>

              <td>

                <?=SD_ADM_REPORT_EVENT_ID?>

              </td>

              <td>

                <?=SD_ADM_REPORT_EVENT_TICKET?>

              </td>

            </tr>

            <?php

                        while($guest = mysqli_fetch_object($guests))

                        {

                            $zebra = ($zebra == "tdodd") ? 'tdeven' : 'tdodd';

                            ?>

           <tr class=<?=$zebra?>>

              <td>

                <?=$guest->mem_forename?>

                <?=$guest->mem_surname?>

              </td>

              <td >

                <?=$guest->mem_sex?>

              </td>

              <td >

                <?=$guest->mem_userid?>

              </td>

              <td >

                <?=sprintf("%02d", $guest->sdt_ticket_num); ?>

              </td>

            </tr>

            <?php } ?>

            <? } else { ?>

            <tr >

              <td  colspan="4" align="center" class="tdodd">

                <?=SD_ADM_REPORT_EVENT_NULL?>

              </td>

            </tr>

            <? } ?>

            <tr>

              <td  colspan="4" align="left" class="tdfoot">

                <?if (!isset($print)){?>

                <input type="submit" name="print" value="<?=PRINT_VERSION?>" class="button"/>&nbsp;<input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>">

                <?}?>

              </td>

            </tr>

          </form>

        </table></td>

    </tr>

  </table>

<?if (!isset($print)) echo $skin->ShowFooter($area)?>