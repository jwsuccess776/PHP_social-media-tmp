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

# Name: 		approveevent.php

#

# Description:  Displays the profile input page (after advert)

#

# Version:		7.3

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('permission.php');



function generate_option($count,$start=1,$selected=1){

    for ($i=$start;$i<=$count;$i++) {

        if ($i==$selected) $sel = "selected";

        printf("<option value=\"%02d\" %s>%02d</option>",$i,$sel,$i);

        $sel = '';

    }

}

function db_to_form($date, $delimiter="/" ) {

      $d = array();

      $d['day'] = substr($date, 6, 2);

      $d['month'] = substr($date, 4, 2);

      $d['year'] = substr($date, 0, 4);

      $d['hours'] = substr($date, 8, 2);

      $d['minutes'] = substr($date, 10, 2);

      return $d['hours'].":".$d['minutes']." ".$d['month'].$delimiter.$d['day'].$delimiter.$d['year'];

  }



# retrieve the template

$area = 'member';



# retrieve the first un-approved event

$query = "	SELECT *,

            date_format(ev_schedule,'%d') day,

            date_format(ev_schedule,'%H') hour,

            date_format(ev_schedule,'%i') minute,

            date_format(ev_schedule,'%m') month,

            date_format(ev_schedule,'%Y') year

            FROM events

                LEFT JOIN geo_country

                    ON (gcn_countryid = ev_country)

                LEFT JOIN geo_state

                    ON (gst_stateid = ev_state)

                LEFT JOIN geo_city

                    ON (gct_cityid = ev_city)

            WHERE ev_approved='0'";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

if (mysqli_num_rows($retval)==0)

{

 header("Location: ".$CONST_LINK_ROOT."/admin/events.php");

exit();

}

$row = mysqli_fetch_object($retval);

$txtEventId     = $row->ev_eventid;

$txtEventName   = $row->ev_eventname;

$txtAddress     = $row->ev_address;

$lstCity        = $row->ev_city;

$lstState       = $row->ev_state;

$lstCountry     = $row->ev_country;

$txtSchedule    = db_to_form($row->ev_schedule);

$txtDay         = $row->day;

$txtMonth       = $row->month;

$txtYear        = $row->year;

$txtHour        = $row->hour;

$txtMinute      = $row->minute;

$txtPhone       = $row->ev_phone;

$txtWebsite     = $row->ev_website;

$txtDesc        = $row->ev_description;

$txtReview      = $row->ev_review;

?>

<?=$skin->ShowHeader($area)?>

<script language="javascript" src="<?php echo $CONST_LINK_ROOT?>/geography.js"></script>

    <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo APPROVEEVENTS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>

    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/prgevents.php' name="FrmEvent" onSubmit="">

          <tr >

            <td colspan="4"  align="left" class="tdhead"> <input type="hidden" name="txtEventId" value="<?php echo $txtEventId ?>"></td>

          </tr>

          <tr >

            <td  align="left" class="tdodd">&nbsp;</td>

            <td colspan="3" align="left" class="tdodd"> <input name="txtApprove" type="radio" value="1" checked>

              <?=GENERAL_APPROVE?>

              <input type="radio" name="txtApprove" value="0">

              <?=GENERAL_DELETE?></td>

          </tr>

          <tr >

            <td  align="left" class="tdeven"><?=ADDEVENT_NAME?>:</td>

            <td colspan="3" align="left" class="tdeven"> <input name="txtEventName" type="text" class="input" id="txtEventName4" value="<?php echo htmlspecialchars($txtEventName); ?>" size="30" maxlength="50"></td>

          </tr>

          <tr >

            <td  align="left" class="tdodd"><?=GENERAL_ADDRESS?>:</td>

            <td colspan="3" align="left" class="tdodd"> <textarea  class="inputl"name="txtAddress" cols="40" rows="3" id="textarea5"><?php echo $txtAddress ?></textarea></td>

          </tr>

        <tr >

          <td align="left" class="tdodd">

            <?= GENERAL_COUNTRY?>

            :</td>

          <td colspan="3" align="left" class="tdodd">

          <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" onchange="onCountryListChange('FrmEvent', 'lstCountry', 'lstState', 'lstCity');">

                <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                <option value=""></option>

          </select>

          </td>

        </tr>



        <tr >

          <td align="left" class="tdodd">

            <?= GENERAL_STATE?>

            :</td>

          <td colspan="3" align="left" class="tdodd">

            <select class="input" name="lstState" id="lstState" size="1" tabindex="1" onchange="onStateListChange('FrmEvent', 'lstCountry', 'lstState', 'lstCity');">

                <option value="0" selected></option>

            </select>

            </td>

        </tr>



        <tr >

          <td align="left" class="tdodd">

            <?= GENERAL_CITY?>

            :</td>

          <td colspan="3" align="left" class="tdodd">

            <select class="input" name="lstCity" id="lstCity" size="1" tabindex="1" onchange="onCityListChange('FrmEvent', 'lstCity');">

                <option value="0" selected></option>

            </select>

          </td>

        </tr>

          <script language="javascript">

            initialize('FrmEvent', 'lstCountry', 'lstState', 'lstCity', new Array('<?=$lstCountry?>'), new Array('<?=$lstState?>'), new Array('<?=$lstCity?>'));

          </script>

          <tr >

            <td rowspan="2" align="left" class="tdodd">Event Date/Time:</td>

            <td colspan="3" align="left" class="tdodd"> <select class="inputs" name=txtHours >

                <?generate_option(23,0,$txtHour)?>

              </select>

              :

              <select class="inputs" name=txtMinutes >

                <?generate_option(59,0,$txtMinute)?>

              </select>

              HH:MM </td>

          </tr>

          <tr >

            <td colspan="3" align="left" class="tdodd"> <select name=txtYear class="inputs" >

                <?generate_option(date("Y")+1,date("Y")-1,$txtYear)?>

              </select>

              /

              <select class="inputs" name=txtMonth >

                <?generate_option(12,1,$txtMonth)?>

              </select>

              /

              <select class="inputs" name=txtDay >

                <?generate_option(31,1,$txtDay)?>

              </select>

              YYYY/MM/DD</td>

          </tr>

          <tr >

            <td  align="left" class="tdeven">Phone:</td>

            <td colspan="3" align="left" class="tdeven"> <input name="txtPhone" type="text" class="input" id="txtPhone4" value="<?php echo $txtPhone?>" size="25" maxlength="25"></td>

          </tr>

          <tr >

            <td  align="left" class="tdodd">Website:</td>

            <td colspan="3" align="left" class="tdodd"> <input name="txtWebsite" type="text" class="inputl" id="txtWebsite4" value="<?php if (trim($txtWebsite) == "") echo "http://"; else echo $txtWebsite?>" size="30" maxlength="50"></td>

          </tr>

          <tr >

            <td  align="left" class="tdeven">Description:</td>

            <td colspan="3" align="left" class="tdeven"> <textarea  class="inputl"name="txtDesc" cols="40" rows="3" id="textarea6"><?php echo $txtDesc ?></textarea></td>

          </tr>

          <tr >

            <td colspan="4"  align="center" class="tdfoot"><input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">

            </td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>

