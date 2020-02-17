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
# Name:                 match_select.php
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

$event_id = sanitizeData($_REQUEST['event_id'], 'xss_clean') ; 

if (empty($event_id)) {
        $error_message=SD_DATE_RESULT_ERROR_ID;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
}

$sql_query = "SELECT * FROM sd_events WHERE sde_eventid = '".$event_id."'";
//echo $sql_query;
$sql_result = mysqli_query($globalMysqlConn,$sql_query);
$cur_event =  mysqli_fetch_object($sql_result);

if (empty($cur_event)) {
    $error_message=SD_DATE_RESULT_ERROR_EVENT;
    error_page($error_message,GENERAL_USER_ERROR);
    exit;
}
else if (strtotime($cur_event->sde_date) > time()) {
    $error_message=SD_MATCH_SELECT_EVENT;
    error_page($error_message,GENERAL_USER_ERROR);
    exit;
}

$sql_query = "  SELECT st.*
                FROM sd_tickets st
                WHERE st.sdt_userid = '".$_SESSION["Sess_UserId"]."'
                    AND st.sdt_eventid = '".$event_id."'";
//                echo $sql_query;
$sql_result = mysqli_query($sql_query, $link);
$user_ticket = mysqli_fetch_object($sql_result);

if (empty($user_ticket)) {
    $error_message=SD_DATE_RESULT_ERROR_EVENT_MEMBER;
    error_page($error_message,GENERAL_USER_ERROR);
    exit;
}


$sql_query = "  SELECT *
                FROM sd_matches
                WHERE sdm_eventid = '".$event_id."'
                AND sdm_userid_1 = '".$_SESSION["Sess_UserId"]."'";

//  echo $sql_query;
$result = mysqli_query($globalMysqlConn,$sql_query);
$is_done = (bool)mysqli_num_rows($result);

if ($is_done) {
    $error_message=SD_MATCH_SELECT_CHOICE;
    error_page($error_message,GENERAL_USER_ERROR);
    exit;
}


//lets find gender with which user had a date
$another_gender = ($user_ticket->sdt_gender == 'Gender1') ? 'Gender2' : 'Gender1';

if ("select" == $act) {
    if (empty($ids)) {
        $error_message=SD_MATCH_SELECT_SELECT;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
    }
    else if (count($ids) > $CONST_SPEED_SELECT) {
        $error_message=SD_MATCH_SELECT_PERSON;
        error_page($error_message,GENERAL_USER_ERROR);
        exit;
    }
    else {
//        print_r($ids);
        $id_line = join(',',$ids);
        $sql_query = "  SELECT *
                        FROM sd_tickets st
                            INNER JOIN members m
                                ON (st.sdt_userid = m.mem_userid)
                        WHERE st.sdt_ticket_id IN ($id_line)";
        //                echo $sql_query;
        $sql_result = mysqli_query($globalMysqlConn,$sql_query);

        while ($cur_ticket = mysqli_fetch_object($sql_result)) {
            if (time() - strtotime($cur_event->sde_date) > $CONST_SPEED_MATCH)
                send_mail($cur_ticket->mem_email,$CONST_MAIL,SD_MATCH_SELECT_SUBJECT,sprintf(SD_MATCH_SELECT_BODY,$cur_ticket->mem_forename." ".$cur_ticket->mem_surname),"html","ON");
            $sql_query = "INSERT INTO sd_matches (sdm_eventid, sdm_userid_1, sdm_userid_2) VALUES ('".$event_id."', '".$_SESSION["Sess_UserId"]."', '".$cur_ticket->sdt_userid."')";
//            echo $sql_query."<br>";
            mysqli_query($globalMysqlConn,$sql_query);
        }
        header("Location: event_booked.php");
    }
}

# retrieve the template
$area = 'speeddating';

?>
<?=$skin->ShowHeader($area)?>
<script>
function test_checked(forma) {
    var result = 0;
    for (var i = 0; i < forma.length; i++) {
        if ((forma.elements[i].type == "checkbox") && forma.elements[i].checked) {
            result += 1;
        }
    }
    if (result == 0) {
        alert("<?=SD_MATCH_SELECT_SELECT?>");
        return false;
    }
    else if (result > <?=$CONST_SPEED_SELECT?>) {
        alert("<?=SD_MATCH_SELECT_PERSON?>");
        return false;
    }
    else {
        return true;
    }

}
</script>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>

    <td class="pageheader">
      <?=MATCH_SELECT_SECTION_NAME?>
    </td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="2" align="left" valign="top" class="tdhead">&nbsp;</td>
        </tr>
        <tr class="tdodd">
          <td align="left" valign="top">
            <?=ADDEVENT_NAME?>
            :</td>
          <td align="left" valign="top" >
            <?=$cur_event->sde_name?>
          </td>
        </tr>
        <tr class="tdeven">
          <td align="left" valign="top" >
            <?=ADDEVENT_DATE?>
            :</td>
          <td align="left" valign="top" >
            <?=date('l j F Y', strtotime($cur_event->sde_date))?>
          </td>
        </tr>
        <tr>
          <td  colspan="2" align="left" valign="top" class="tdfoot">&nbsp;</td>
        </tr>
        <?php
                $sql_query = "  SELECT *
                                FROM sd_tickets
                                WHERE sdt_gender = '".$another_gender."'
                                    AND sdt_eventid = '".$event_id."'
                                    AND sdt_userid != '".$_SESSION["Sess_UserId"]."'
                                    ORDER BY sdt_ticket_num";
                //                echo $sql_query;
                $sql_result = mysqli_query($globalMysqlConn,$sql_query);
                if (mysqli_num_rows($sql_result) > 0) {
                ?>
        <form method="post" action="match_select.php" onsubmit="return test_checked(this);">
          <input type="hidden" name="event_id" value="<?=$event_id;?>">
          <input type="hidden" name="act" value="select">
          <tr>
            <td colspan="2" class="tdhead">&nbsp;</td>
          </tr>
          <tr class="tdtoprow">
            <td align="center"><strong>Select</strong></td>
            <td align="center">
              <?=ADM_EVENT_TICKETS_TICKET?>
            </td>
          </tr>
          <?php
//                print_r(mysqli_fetch_object($sql_result));
                                     while($cur_person = mysqli_fetch_object($sql_result)) {
                                     $zebra = ($zebra == "tdodd") ? 'tdeven' : 'tdodd';
                                    ?>
          <tr class=<?=$zebra?>>
            <td align="center" > <input type="checkbox" name="ids[]" value="<?=$cur_person->sdt_ticket_id;?>"></td>
            <td align="center" >
              <?=sprintf("%02d", $cur_person->sdt_ticket_num); ?>
            </td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class='button' value="<?=PRGTEMPLATES_SELECT?>"></td>
        </form>
        <? } else { ?>
        <tr>
          <td align="center" colspan="2"><b>
            <?=SD_MATCH_SELECT_NO_PERSON?>
            </b></td>
        </tr>
        <? } ?>
      </table></td>
    </tr>
  </table>

<?php //mysqli_close( $link ); ?>
<?=$skin->ShowFooter($area)?>
