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
# Name:                 date_result.php
#
# Description:
#
# Version:                7.2
#
######################################################################
include('../db_connect.php');
include('session_handler.inc');
include('../message.php');
include('error.php');
include_once('../validation_functions.php'); 
save_request();

$event_id =sanitizeData($_REQUEST['event_id'], 'xss_clean') ; 

if (empty($event_id)) {
        $error_message=SD_DATE_RESULT_ERROR_ID;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
}

$sql_query = "SELECT * FROM sd_events WHERE sde_eventid = '".$event_id."'";
//                echo $sql_query;
$sql_result = mysqli_query($globalMysqlConn,$sql_query);
$cur_event =  mysqli_fetch_object($sql_result);

if (empty($cur_event)) {
        $error_message=SD_DATE_RESULT_ERROR_EVENT;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
}

$sql_query = "  SELECT st.*
                FROM sd_tickets st
                WHERE st.sdt_userid = '".$_SESSION["Sess_UserId"]."'
                    AND st.sdt_eventid = '".$event_id."'";
//                echo $sql_query;
$sql_result = mysqli_query($globalMysqlConn,$sql_query);
$user_ticket = mysqli_fetch_object($sql_result);

if (empty($user_ticket)) {
        $error_message=SD_DATE_RESULT_ERROR_EVENT_MEMBER;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
}

$sql_query = "  SELECT *
                FROM sd_matches
                WHERE sdm_userid_1 = '".$_SESSION["Sess_UserId"]."'
                AND sdm_eventid = $event_id";
//echo $sql_query;
$my_matches = mysqli_query($globalMysqlConn,$sql_query);
$temp_array = array();
while ($row = mysqli_fetch_object($my_matches)){
    $temp_array[] = $row->sdm_userid_2;
}
if (!count($temp_array)) {
        $error_message=SD_DATE_RESULT_ERROR_MATCHES;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
}

$my_match = join(',',$temp_array);

$sql_query = "  SELECT t.*,m.mem_username as mem_username,m.mem_email as mem_email,count(m1.sdm_userid_1) as vote, count(if(m2.sdm_userid_2 = ".$_SESSION["Sess_UserId"].",1,NULL)) as ident
                FROM sd_tickets t
                    LEFT JOIN sd_matches m1
                        ON  (
                                t.sdt_userid  = m1.sdm_userid_1
                                AND t.sdt_eventid = m1.sdm_eventid
                            )
                    LEFT JOIN sd_matches m2
                        ON (
                                t.sdt_userid  = m2.sdm_userid_1
                                AND m2.sdm_userid_1 IN ($my_match)
                                AND t.sdt_eventid = m1.sdm_eventid
                            )
                    LEFT JOIN members m
                        ON ( t.sdt_userid = m.mem_userid )
                WHERE t.sdt_eventid = '$event_id'
                AND t.sdt_userid != ".$_SESSION["Sess_UserId"]."
                GROUP BY sdt_ticket_id
                ORDER BY sdt_ticket_num";
//echo $sql_query;
$tickets = mysqli_query($globalMysqlConn,$sql_query);
$my_match = $temp_array;

# retrieve the template
$area = 'speeddating';

?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

    <td class="pageheader">
      <?=SPEED_DATE_RESULT_SECTION_NAME?>
    </td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="3" class="tdhead">&nbsp;</td>
        </tr>
        <tr>
          <td valign="top" class="tdodd" >
            <?=SD_DATE_RESULT_NAME?>
            :</td>
          <td colspan="2" align="left" valign="top" class="tdodd">
            <?=$cur_event->sde_name?>
          </td>
        </tr>
        <tr>
          <td valign="top" class="tdeven">
            <?=SD_DATE_RESULT_DATE?>
            :</td>
          <td  colspan="2" align="left" valign="top" class="tdeven">
            <?=date('l j F Y', strtotime($cur_event->sde_date))?>
          </td>
        </tr>
        <?php if (mysqli_num_rows($tickets) > 0) {?>
        <tr>
          <td class="td2">
            <?=SD_DATE_RESULT_TICKET?>
          </td>
          <td class="td2">
            <?=SD_DATE_RESULT_NAME?>
          </td>
          <td class="td2">
            <?=SD_DATE_RESULT_PROFILE?>
          </td>
        </tr>
        <?php
//                print_r(mysqli_fetch_object($tickets));
            while($cur_couple = mysqli_fetch_object($tickets)) {
            $zebra = ($zebra == "zebra_white") ? 'zebra' : 'zebra_white';
         ?>

        <tr align=left class="tdeven">
          <td nowrap>
            <?=sprintf("%02d", $cur_couple->sdt_ticket_num); ?>
            <? if ($cur_couple->ident && $cur_couple->vote && in_array($cur_couple->sdt_userid,$my_match)) { ?>
            <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$cur_couple->sdt_userid?>"><?=$cur_couple->mem_username; ?></a>
            (<a href="mailto:<?=$cur_couple->mem_email;?>"><?=$cur_couple->mem_email;?></a>)
            <? } else { ?>
            <?=$cur_couple->mem_username;?>
            <? } ?>
          </td>
          <td nowrap valign="middle">
            <?if ($cur_couple->ident && $cur_couple->vote && in_array($cur_couple->sdt_userid,$my_match)) {?>
            <img border='0' src='<?=$CONST_IMAGE_ROOT?>/icons/accept.gif'>
            <?} elseif (!$cur_couple->ident && $cur_couple->vote && in_array($cur_couple->sdt_userid,$my_match)) {?>
            <img border='0' src='<?=$CONST_IMAGE_ROOT?>/icons/decline.gif'>
            <?} elseif (!$cur_couple->vote && in_array($cur_couple->sdt_userid,$my_match)) {?>
            <img border='0' src='<?=$CONST_IMAGE_ROOT?>/icons/question.gif'>
            <?} elseif (!in_array($cur_couple->sdt_userid,$my_match)) {?>
            <img border='0' src='<?=$CONST_IMAGE_ROOT?>/icons/no_action.gif'>
            <?}?>
          </td>
          <td nowrap>
            <?if ($cur_couple->vote){?>
            Vote
            <?} else {?>
            Not vote
            <?}?>
          </td>
        </tr>
        <? }?>
        <? } else { ?>
        <tr>
          <td colspan="3" align="center" class="tdodd"><b>
            <?=SD_DATE_RESULT_NOONE?>
            </b></td>
        </tr>
        <tr>
          <td colspan="3" align="center" class="tdfoot">&nbsp;</td>
        </tr>
        <? } ?>
      </table></td>
    </tr>
  </table>

<?php //mysqli_close( $link ); ?>
<?=$skin->ShowFooter($area)?>
