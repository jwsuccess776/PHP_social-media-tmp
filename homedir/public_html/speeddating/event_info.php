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

# Name:                 event_info.php

#

# Description:

#

# Version:                7.3

#

######################################################################

include('../db_connect.php');

include('../message.php');

include('../functions.php');

include_once('../validation_functions.php');


include('error.php');

$sde_eventid =sanitizeData(trim($_GET['sde_eventid']), 'xss_clean');   



if (empty($sde_eventid)) {

    $error_message=SD_DATE_RESULT_ERROR_ID;

    error_page($error_message,GENERAL_USER_ERROR);

    exit;

}



$sql_result = mysqli_query($globalMysqlConn," SELECT *,COUNT(if(sdt_gender='Gender1',1,null)) G1_qty, COUNT(if(sdt_gender='Gender2',1,null)) G2_qty,unix_timestamp(sde_date) AS sde_date

                            FROM sd_events a

                            LEFT JOIN sd_tickets b

                                ON (a.sde_eventid = b.sdt_eventid)

                            WHERE sde_eventid='$sde_eventid'

                            GROUP BY sde_eventid

                            ");

$event = mysqli_fetch_object($sql_result);



$back = (isset($_REQUEST["back"]))?$_REQUEST["back"]:"";

if (empty($event)) {

    $error_message=SD_DATE_RESULT_ERROR_EVENT;

    error_page($error_message,GENERAL_USER_ERROR);

    exit;

}



$booked1 = $event->G1_qty;

$booked2 = $event->G2_qty;

$free1 = $event->sde_gender1_places - $event->G1_qty;

$free2 = $event->sde_gender2_places - $event->G2_qty;



if ($_SESSION["Sess_UserId"]) {

$sql_result = mysqli_query($globalMysqlConn," SELECT *,(YEAR(CURDATE())-YEAR(mem_dob)) - (RIGHT(CURDATE(),5) < RIGHT(mem_dob,5)) AS age

                            FROM members

                            WHERE mem_userid='".$_SESSION["Sess_UserId"]."'");

$cur_member = mysqli_fetch_object($sql_result);



if ($cur_member->mem_sex == $event->sde_gender1

    && $cur_member->age >= $event->sde_age_from

    && $cur_member->age <= $event->sde_age_to) $cur_gender_free = $free1;

if ($cur_member->mem_sex == $event->sde_gender2

    && $cur_member->age >= $event->sde_age_from

    && $cur_member->age <= $event->sde_age_to) $cur_gender_free = $free2;

if ($cur_member->age >= $event->sde_age_from &&

    $cur_member->age <= $event->sde_age_to)

    $cur_gender_ok = true;

else

    $cur_gender_ok = false;



$sql_result = mysqli_query($globalMysqlConn," SELECT *

                            FROM sd_tickets

                            WHERE sdt_eventid='".$sde_eventid."'

                            AND sdt_userid = '".$_SESSION["Sess_UserId"]."'"

                          ) or die(mysqli_error());



$is_bought = mysqli_fetch_object($sql_result);

}

if ($_REQUEST["action"] == "buy") {

include('session_handler.inc');

    $error_message = "";

    if ($cur_gender_free <= 0) {

        $error_message = SD_EVENT_INFO_PLACES;

    }

    if (!empty($is_bought)) {

        $error_message = SD_EVENT_INFO_ALREADY;

    }



    if (!empty($error_message)) {

        error_page($error_message,GENERAL_USER_ERROR);

        exit;

    }

    else {

        $sum = round($event->sde_price,2);

        $extra = serialize(array("eventid" => $sde_eventid,"gender"=>$cur_member->mem_sex));

        $desc = constant("SD_INDEX_GENDER_".strtoupper($cur_member->mem_sex))." - $event->sde_name booking (".$_SESSION["Sess_UserName"].")";

        $desc=mysqli_real_escape_string($globalMysqlConn,$desc);

		$query="INSERT INTO payments SET

                        pay_userid  = '$Sess_UserId',

                        pay_samount = '$sum',

                        pay_service = 'sd_ticket',

                        pay_message = '$desc',

                        pay_params  = '$extra'";



        mysqli_query($globalMysqlConn,$query);

        //echo mysqli_error();

        $payment_id = mysqli_insert_id($globalMysqlConn);

        header("Location: $CONST_LINK_ROOT/payments/payment.php?payment_id=$payment_id&speeddating=1");

        exit;

    }

}







# retrieve the template

$area = 'speeddating';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>



    <td class="pageheader">

      <?=SD_EVENT_INFO_SECTION_NAME?>

    </td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/speeddating/events.php" name="frmPremFunc">

          <input type=hidden name=action value=save>

          <input type=hidden name=sde_eventid value="<?=$sde_eventid?>">

          <tr>

            <td colspan="3" class="tdhead" >&nbsp;</td>

          </tr>

          <tr class='tdodd'>

            <td width="20%" >

              <?=SD_EVENTS_NAME?>

            </td>

            <td >

              <?=htmlspecialchars($event->sde_name)?>

            </td>

            <td>&nbsp;

            <?php

                $sql_result = mysqli_query(" SELECT *

                                            FROM sd_venues

                                                LEFT JOIN geo_country ON vnu_countryid = gcn_countryid

                                                LEFT JOIN geo_state ON vnu_stateid = gst_stateid

                                                LEFT JOIN geo_city ON vnu_cityid = gct_cityid

                                            WHERE vnu_venueid='$event->sde_venueid'", $link);

                $venue = mysqli_fetch_object($sql_result);

            ?>

			

			<?php if ($venue->vnu_website) { ?>

              <input name="button" type="button" class='button' onclick="window.open('<?= htmlspecialchars($venue->vnu_website) ?>','','')" value="<?=SD_EVENT_INFO_MAP?>">

              <?php } ?>

			</td>

          </tr>

          <tr class="tdeven">

            <td width="20%" >

              <?=SD_EVENTS_DATE?>

            </td>

            <td colspan="2" >

              <?=date($CONST_FORMAT_DATE_SHORT,$event->sde_date)?>

            </td>

          </tr>

          <tr class=tdodd>

            <td width="20%" >

              <?=ADM_EVENT_EDIT_AGE1_F_T?>

            </td>

            <td colspan="2" >

              <?=sprintf(SD_EVENT_INFO_AGE, $event->sde_age_from."-".$event->sde_age_to)?>

            </td>

          </tr>

          <tr class=tdeven>

            <td width="20%" >

              <?=($event->sde_gender1 == 'M' ? SEX_MALE : SEX_FEMALE)?>

            </td>

            <td colspan="2" >

<?php

$t1=date("Y-m-d H:i:s",$event->sde_date);

$t2=date("Y-m-d H:i:s");

if (strtotime($t1) > strtotime($t2)) {

    if ($free1) {

              echo sprintf(SD_EVENT_INFO_PLACES_AVAILABLE, "");

    } else {

              echo SD_EVENT_INFO_NOPLACES;

    }

} else {

              echo SD_EVENT_INFO_CLOSED;

}

?>

            </td>

          </tr>

          <tr class=tdodd>

            <td width="20%" >

              <?=($event->sde_gender2 == 'M' ? SEX_MALE : SEX_FEMALE)?>

            </td>

            <td colspan="2" >

<?php

if (strtotime($t1) > strtotime($t2)) {

    if ($free2) {

              echo sprintf(SD_EVENT_INFO_PLACES_AVAILABLE, "");

    } else {

              echo SD_EVENT_INFO_NOPLACES;

    }

} else {

              echo SD_EVENT_INFO_CLOSED;

}

?>

            </td>

          </tr>

          <tr class=tdeven>

            <td width="20%" >

              <?=SD_EVENTS_VENUE?>

            </td>

            <td colspan="2" >

              <?=htmlspecialchars($venue->vnu_name)?>

            </td>

          </tr>

          <tr class=tdodd>

            <td width="20%" >

              <?=SD_EVENTS_LOCATION?>

            </td>

            <td colspan="2" >

              <?=arrange_location($venue)?>

            </td>

          </tr>

          <tr class=tdeven>

            <td width="20%" valign="top" >

              <?=SD_EVENTS_DESCRIPTION?>

            </td>

            <td colspan="2" >

              <?=htmlspecialchars($event->sde_description)?>

            </td>

          </tr>

          <tr class=tdodd>

            <td width="20%" ><?php echo ADM_EVENT_EDIT_SPECIAL ?></td>

            <td colspan="2" >

              <?=htmlspecialchars($event->sde_special)?>

            </td>

          </tr>

          <tr class=tdeven>

            <td width="20%" >

              <?=SD_EVENTS_PRICE?>

            </td>

            <td colspan="2" >

              <?=$CONST_SYMBOL;?>

              <?=$event->sde_price?>

            </td>

          </tr>

          <?php if ($_SESSION["Sess_UserId"] && $is_bought) { ?>

          <tr class=tdodd>

            <td colspan=3 align=center>

              <?=SD_EVENT_INFO_TICKET?>

              <b>

              <?=$event->sdt_ticket_num?>

              </b> </td>

          </tr>

          <?php } elseif ($_SESSION["Sess_UserId"] && !$cur_gender_ok)  {?>

          <tr class=tdeven>

            <td colspan=3  align=center>

              <?=SD_EVENT_INFO_GENDER?>

            </td>

          </tr>

          <?php } ?>

          <tr align="center">

            <td colspan=3 class="tdfoot" nowrap>

              <?php if ($_SESSION["Sess_UserId"] && !$cur_gender_free && $cur_gender_ok && !$is_bought) {?>

              <input name="button" type="button" class="button" onClick="document.location='<?=$CONST_LINK_ROOT?>/speeddating/waiting_list.php?action=add&sde_eventid=<?=$event->sde_eventid?>'" value="<?=SD_EVENT_WAIT?>">

              <?php }?>

              <?php if ((!$_SESSION["Sess_UserId"] && ($free1 || $free2)) || ($_SESSION["Sess_UserId"] && $cur_gender_free > 0 && !$is_bought)) { ?>

              <input name="button" type="button" class='button' onclick="document.location.href='<?php echo $CONST_LINK_ROOT?>/speeddating/event_info.php?action=buy&sde_eventid=<?=$event->sde_eventid?>'" value="<?=SD_EVENT_BOOK_ONLINE?>">

              <?php } ?>

              <input name="button" type="button" class='button' onclick="document.location.href='<?php echo $CONST_LINK_ROOT?>/speeddating/venue_detail.php?sde_venueid=<?=$event->sde_venueid?>&sde_eventid=<?=$event->sde_eventid?>'" value="<?=SD_EVENT_INFO_VENUE?>">

              <?php if ($back == "index") { ?>

              <input name="button" type="button" class='button' onclick="document.location.href='<?php echo $CONST_LINK_ROOT?>/speeddating/index.php'" value="<?=BUTTON_BACK?>">

              <?php } elseif ($back == "booked") { ?>

              <input name="button" type="button" class='button' onclick="document.location.href='<?php echo $CONST_LINK_ROOT?>/speeddating/event_booked.php'" value="<?=BUTTON_BACK?>">

              <?php } else { ?>

              <input name="button" type="button" class='button' onclick="document.location.href='<?php echo $CONST_LINK_ROOT?>/speeddating/event_list.php'" value="<?=BUTTON_BACK?>">

              <?php } ?>

            </td>

          </tr>

        </form>

      </table>

      </td>

    </tr>

  </table>



<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>

