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

# Name:                 adm_waiting_list.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../functions.php');

include('../admin/permission.php');

include_once('../validation_functions.php'); 



$result=mysqli_query($globalMysqlConn,"SELECT DISTINCT sde_eventid,

                            sde_name

                       FROM sd_events

                 INNER JOIN sd_waiting

                         ON sde_eventid=swt_eventid

                   ORDER BY sde_name");

$events = array();

while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC))

    $events[$row["sde_eventid"]] = $row["sde_name"];



$waitingUsers = array();

if ($_REQUEST["eventid"] && in_array($_REQUEST["eventid"],array_keys($events))) {

    $eventid=sanitizeData($_REQUEST['eventid'], 'xss_clean') ;  

    $result=mysqli_query($globalMysqlConn,"SELECT mem_username,

                                CONCAT(mem_forename,' ',mem_surname) AS mem_name,

                                mem_email,

                                mem_sex,

                                swt_date,

                                unix_timestamp(swt_date) as date

                           FROM members

                     INNER JOIN sd_waiting

                             ON swt_userid=mem_userid &&

                                swt_eventid='$eventid'

                       ORDER BY swt_date ASC");

    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC))

        $waitingUsers[] = $row;

} else $eventid=0;



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

    <td class="pageheader">

      <?=SD_ADM_WAITING_LIST_SECTION_NAME?>

    </td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr>

          <td align="center" class="tdhead" colspan="5">

            <?php if (count($events)) { ?>

            <select name="event" onChange="document.location='adm_waiting_list.php?eventid='+this.value;">

              <option value="">&mdash;

              <?=SD_ADM_WAITING_LIST_EVENTS?>

              &mdash;

              <?php foreach ($events as $id=>$name) echo "<option value=\"$id\"".(($id == $eventid)?" selected":"").">$name"; ?>

            </select>

            <?php } else echo SD_ADM_WAITING_LIST_NOEVENTS; ?>

          </td>

        </tr>

        <?php if (count($waitingUsers)) { ?>

        <tr class="tdtoprow">

          <td>

            <?=SD_ADM_WAITING_LIST_USERNAME?>

          </td>

          <td>

            <?=SD_ADM_WAITING_LIST_NAME?>

          </td>

          <td>

            <?=SD_ADM_WAITING_LIST_SEX?>

          </td>

          <td>

            <?=SD_ADM_WAITING_LIST_EMAIL?>

          </td>

          <td>

            <?=SD_ADM_WAITING_LIST_DATE?>

          </td>

        </tr>

        <?php foreach ($waitingUsers as $user) { ?>

        <tr class="tdodd">

          <td>

            <?=$user["mem_username"]?>

          </td>

          <td>

            <?=$user["mem_name"]?>

          </td>

          <td align="center">

            <?=$user["mem_sex"]?>

          </td>

          <td><a href="mailto:<?=$user["mem_email"]?>?subject=<?=sprintf(SD_ADM_WAITING_LIST_MAIL_SUBJECT,$events[$eventid])?>">

            <?=$user["mem_email"]?>

            </a></td>

          <td align="center">

            <?=date($CONST_FORMAT_DATE_SHORT,$user["date"])?>

          </td>

        </tr>

        <?php } ?>

        <tr>

          <td class="tdfoot" colspan="5">&nbsp;</td>

        </tr>

        <?php } ?>

      </table></td>

    </tr>

  </table>





<?=$skin->ShowFooter($area)?>

