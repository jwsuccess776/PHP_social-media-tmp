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
# Name:                 event_booked.php
#
# Description:
#
# Version:                7.3
#
######################################################################
include('../db_connect.php');
include('session_handler.inc');
include('../message.php');

//preparing for paging
require_once("dividing.php");

list($div_cur_page, $div_on_page) = divide_setup();

$div_page_name = "event_booked.php";
$sql_query = "  SELECT COUNT(se.sde_eventid) as qty
                FROM sd_events se
                    INNER JOIN sd_tickets st
                        ON (st.sdt_eventid = se.sde_eventid)
                WHERE st.sdt_userid = '".$_SESSION["Sess_UserId"]."'
             ";
//echo $sql_query;
$result = mysqli_query($globalMysqlConn,$sql_query);
//echo $_SESSION["Sess_UserId"];
$result = mysqli_fetch_object($result);
$div_qty = $result->qty;

$required_param = array();
$dividing = divide_results($div_cur_page, $div_on_page, $div_qty);
$div_str_bottom = result_str($div_cur_page, $div_on_page, $div_qty, $required_param, $dividing);
//end prepearing

include('../error.php');
# retrieve the template
$area = 'speeddating';

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td class="pageheader">
      <?=SD_EVENT_BOOKED_EVENTS?>
    </td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="3" align="left" valign="top" class="tdhead">&nbsp; </td>
        </tr>
        <?php
                $sql_query = "  SELECT se.*
                                FROM sd_events se
                                    INNER JOIN sd_tickets st
                                        ON (st.sdt_eventid = se.sde_eventid)
                                WHERE st.sdt_userid = '".$_SESSION["Sess_UserId"]."'
                                LIMIT ".$dividing["from"].", ".($dividing["till"] - $dividing["from"]);
//                echo $sql_query;
                $sql_result = mysqli_query($globalMysqlConn,$sql_query);
                if ($div_qty > 0) {
                        ?>
        <tr class="tdtoprow">
          <td>
            <?=SD_EVENTS_NAME?>
          </td>
          <td>
            <?=SD_EVENTS_DATE?>
          </td>
          <td>
            <?=SD_EVENT_BOOKED_MATCHES?>
          </td>
        </tr>
        <?php
//                print_r(mysqli_fetch_object($sql_result));
                                       while($event = mysqli_fetch_object($sql_result)) {
                                          $zebra = ($zebra == "tdodd") ? 'tdeven' : 'tdodd';
//                                          print_r($event);
                                          $sql_query = "SELECT * FROM sd_matches WHERE sdm_eventid = '".$event->sde_eventid."' AND sdm_userid_1 = '".$_SESSION["Sess_UserId"]."'";

//                                                    echo $sql_query;
                                          $result = mysqli_query($globalMysqlConn,$sql_query);
                                          $is_matched = (bool)mysqli_num_rows($result);
                                      ?>
       <tr class=<?=$zebra?> >
          <td ><a href="<?=$CONST_LINK_ROOT?>/speeddating/event_info.php?sde_eventid=<?=$event->sde_eventid?>&back=booked">
            <?=$event->sde_name?>
            </a></td>
          <td>
            <?=date('D j M Y', strtotime($event->sde_date))?>
          </td>
          <td>
            <?
                                        if (time() < strtotime($event->sde_date)) {
                                            echo SD_EVENT_BOOKED_FUTURE ;
                                        } elseif (!$is_matched ) {
                                            echo '<a href="match_select.php?event_id='.$event->sde_eventid.'">'.SD_EVENT_BOOKED_SET.'</a>';
                                        } else {
                                            echo '<a href="date_result.php?event_id='.$event->sde_eventid.'">'.SD_EVENT_BOOKED_VIEW.'</a>';
                                        }
                                        ?>
          </td>
        </tr>
        <?php } ?>
        <? } else { ?>
        <tr class="tdodd">
          <td colspan="3" align="center" class="tdodd">
            <?=SD_EVENT_BOOKED_VISIT?>
          </td>
        </tr>
        <? } ?>
        <tr>
          <td  colspan="3" align="left" class="tdeven">
            <?=$div_str_bottom;?>
          </td>
        </tr>

      </table> </td>
  </tr>
</table>

<?php //mysqli_close( $link ); ?>
<?=$skin->ShowFooter($area)?>
