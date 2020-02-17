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

# Name:				 adm_event_edit.php

#

# Description:

#

# Version:				7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

include('../functions.php');

include('../admin/permission.php');

include_once('../validation_functions.php');



$sde_eventid =sanitizeData($_GET['sde_eventid'], 'xss_clean') ;    



include('../error.php');

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

        <?= $sde_eventid ? SD_EVENT_EDIT_SECTION_NAME : SD_EVENT_ADD_SECTION_NAME ?>

      </td>

    </tr>

	  <tr>

		<td><? include("../admin/admin_menu.inc.php");?></td>

	  </tr>

    <?php

        $sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM sd_events WHERE sde_eventid='$sde_eventid'");

        $event = mysqli_fetch_object($sql_result);



        $sql_query = "  SELECT COUNT(if(sdt_gender='Gender1',1,null)) G1_qty, COUNT(if(sdt_gender='Gender2',1,null)) G2_qty

                        FROM sd_tickets

                        WHERE sdt_eventid = '$sde_eventid'";

        $result = mysqli_query($globalMysqlConn,$sql_query) or die(mysqli_error());

        $booked = mysqli_fetch_object($result);



        $booked1 = $booked->G1_qty;

        $booked2 = $booked->G2_qty;



        $sde_year = date("Y", strtotime($event->sde_date));

        $sde_month = date("m", strtotime($event->sde_date));

        $sde_day = date("d", strtotime($event->sde_date));

        $sde_hour = date("H", strtotime($event->sde_date));

        $sde_minute = date("i", strtotime($event->sde_date));

        ?>

    <form method="post" action="<?php echo $CONST_LINK_ROOT?>/speeddating/adm_events.php" name="event" onsubmit="return sd_event_edit(this)">

      <input type=hidden name=action value=save>

      <input type=hidden name=sde_eventid value="<?=$sde_eventid?>">

      <tr>

        <td>

        <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

            <tr class="tdodd">

              <td height="30"><?=SD_EVENTS_NAME?></td>

              <td height="30" class="tdodd"><input name=sde_name type=text class="input" value="<?=htmlspecialchars($event->sde_name)?>"> </td>

            </tr>

            <tr class="tdeven">

              <td height="30">

                <?=SD_EVENTS_DATE?>

              </td>

              <td height="30"><span class="tdodd">

              <select name="sde_year" class="inputf">

                <option value="">

                <?=ADM_EVENT_EDIT_YEAR?>

                <?

                            $year=date("Y");

                            for($i=$year;$i<=$year+10;$i++){

                                if($i==$sde_year && !empty($sde_eventid)){

                                    echo "<option selected value='$i'>$i\n";

                                } else {

                                    echo "<option value='$i'>$i\n";

                                }

                            }

                            ?>

                            </select>

              <select name="sde_month" class="inputf">

                <option value="">

                <?=ADM_EVENT_EDIT_MONTH?>

                <?

                            $month=array(1=>"January",2=>"February",3=>"March",4=>"April",5=>"May",6=>"June",

                            7=>"July",8=>"August",9=>"September",10=>"October",11=>"November",12=>"December");

                            for($i=1;$i<=12;$i++) {

                                if($i==$sde_month && !empty($sde_eventid)) {

                                    $selected = "selected";

                                } else {

                                    $selected = "";

                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.$month[$i].'';

                            }

                            ?>

                            </select>

              <select name="sde_day" class="inputf">

                <option value="">

                <?=ADM_EVENT_EDIT_DAY?>

                <?

                            for($i=1;$i<=31;$i++){

                                if($i==$sde_day && !empty($sde_eventid)) {

                                    $selected = "selected";

                                } else {

                                    $selected = "";

                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.$i.'';

                            }

                            ?>

                            </select>

              <a href="javascript:call.from_select();"><img src="<?=$CONST_IMAGE_ROOT?>/cal/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a> </span></td>

            </tr>

            <tr class="tdeven">

              <td height="30"><?php echo ADM_EVENT_EDIT_TIME ?></td>

              <td height="30"><span class="tdodd">

                <select name="sde_hour" class="inputf">

                  <option value="">

                  <?=ADM_EVENT_EDIT_HOUR?>

                  <?

                            for($i=0;$i<=23;$i++){

                                if($i==$sde_hour && !empty($sde_eventid)) {

                                    $selected = "selected";

                                } else {

                                    $selected = "";

                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.sprintf('%02d', $i).'';

                            }

                            ?>

              </select>

                <select name="sde_minute" class="inputf">

                  <option value="">

                  <?=ADM_EVENT_EDIT_MINUTE?>

                  <?

                            for($i=0;$i<=45;$i+=15){

                                if($i==$sde_minute && !empty($sde_eventid)) {

                                    $selected = "selected";

                                } else {

                                    $selected = "";

                                }

                                echo '<option value="'.$i.'"'.$selected.'>'.sprintf('%02d', $i).'';

                            }

                            ?>

              </select>

              </span></td>

            </tr>

            <tr class="tdodd">

              <td height="30">

                <?=SD_EVENTS_GENDER1?>

              </td>

              <td height="30"><select name="sde_gender1" size="1" class="inputf">

                  <option value="M" <?if ($event->sde_gender1 == 'M'){?>SELECTED<?}?>><?php echo SEX_MALE ?></option>

                  <option value="F" <?if ($event->sde_gender1 == 'F'){?>SELECTED<?}?>><?php echo SEX_FEMALE ?></option>

                </select> </td>

            </tr>

            <tr class="tdeven">

              <td height="30">

                <?=SD_EVENTS_GENDER2?>

              </td>

              <td height="30"><select name="sde_gender2" size="1" class="inputf">

                  <option value="F" <?if ($event->sde_gender2 == 'F'){?>SELECTED<?}?>><?php echo SEX_FEMALE ?></option>

                  <option value="M" <?if ($event->sde_gender2 == 'M'){?>SELECTED<?}?>><?php echo SEX_MALE ?></option>

                </select> </td>

            </tr>

            <tr class="tdodd">

              <td height="30">

                <?=ADM_EVENT_EDIT_AGE1_F_T?>

              </td>

              <td height="30"><select name="sde_age_from" size="1" class="inputf">

                  <option value="">

                  <?=ADM_EVENT_EDIT_AGE_F?>

                  <?

                            $out = "";

                            for ($i = 20; $i < 80; $i+=5) {

                                $selected = $i == $event->sde_age_from ? " SELECTED" : "";

                                $out .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';

                            }

                            echo $out;

                            ?>

                </select> <select name="sde_age_to" size="1" class="inputf">

                  <option value="">

                  <?=ADM_EVENT_EDIT_AGE_T?>

                  <?

                            $out = "";

                            for ($i = 20; $i < 80; $i+=5) {

                                $selected = $i == $event->sde_age_to ? " SELECTED" : "";

                                $out .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';

                            }

                            echo $out;

                            ?>

                </select> </td>

            </tr>

            <tr class="tdodd">

              <td height="30">

                <?=SD_EVENTS_GENDER1_PLACES?>

              </td>

              <td height="30"><input type="text" name="sde_gender1_places" class="inputf" value="<?=$event->sde_gender1_places?>"></td>

            </tr>

            <tr class="tdeven">

              <td height="30">

                <?=SD_EVENTS_GENDER2_PLACES?>

              </td>

              <td height="30"><input name="sde_gender2_places" type="text" class="inputf" value="<?=$event->sde_gender2_places?>"></td>

            </tr>

            <?if ($sde_eventid){?>

            <tr class="tdodd">

              <td height="30">

                <?=SD_EVENTS_BOOKED1?>

              </td>

              <td height="30">

                <?=$booked1?>

              </td>

            </tr>

            <tr class="tdeven">

              <td height="30">

                <?=SD_EVENTS_BOOKED2?>

              </td>

              <td height="30">

                <?=$booked2?>

              </td>

            </tr>

            <?}?>

            <tr class="tdodd">

              <td height="30">

                <?=SD_EVENTS_VENUE?>

              </td>

              <td height="30"><select name="sde_venueid" class="input">

              <option selected>- <?php echo GENERAL_CHOOSE?> -</option>

                  <?show_dropdown('sd_venues','vnu_venueid','vnu_name',$event->sde_venueid)?>

                </select> </td>

            </tr>

            <tr class="tdeven">

              <td height="25" valign="top">

                <?=SD_EVENTS_DESCRIPTION?>

              </td>

              <td height="25"><textarea name="sde_description" cols="50" rows="10" wrap="soft" class="inputl"><?=htmlspecialchars($event->sde_description)?></textarea></td>

            </tr>

            <?if ($event->sde_is_special == 'yes') {    $checkbox= "CHECKED";} ?>

            <tr class="tdodd">

              <td height="25" colspan="2">

                <hr>

              </td>

            </tr>

            <tr class="tdodd">

              <td height="25">

                <?=ADM_EVENT_EDIT_SPECIAL?>

              </td>

              <td height="25"><input type=checkbox name=sde_is_special <?=$checkbox?>></td>

            </tr>

            <tr class="tdeven">

              <td height="25">

                <?=SD_EVENTS_DESCRIPTION?>

              </td>

              <td height="25">

                <input name=sde_special type=text class="inputl" value="<?=htmlspecialchars($event->sde_special)?>" maxlength="200">

              </td>

            </tr>

            <tr class="tdeven">

              <td height="25" colspan="2">

                <hr>

              </td>

            </tr>

            <tr class="tdodd">

              <td height="25">

                <?=SD_EVENTS_PRICE?>

              </td>

              <td height="25"><input name=sde_price type=text class="input" value="<?=$event->sde_price?>" size="8" maxlength="8">

                (<?=$CONST_SYMBOL;?>)

              </td>

            </tr>

            <tr>

              <td colspan=2 align=center class="tdfoot"><input class='button' type=submit name=SAVE value="<?=GENERAL_SAVE?>">&nbsp;<input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>"></td>

            </tr>

          </table>

        </td>

      </tr>

    </form>

</table>

<script language="JavaScript" src="jscript_sd_lib.js"></script><!-- Date only with year scrolling -->

<script language="JavaScript" src="../calendar.js"></script><!-- Date only with year scrolling -->

<script>

    var const_link = "<?=$CONST_LINK_ROOT;?>";

    forma = document.forms['event'];

    var call = new calendar(forma, 'sde_year', 'sde_month', 'sde_day');

    call.year_scroll = true;

    call.time_comp = false;

</script>



<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>