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

# Name: 		addevent.php

#

# Description:  Displays the profile input page (after advert)

#

# Version:		7.3

#

######################################################################

 

include('../db_connect.php');

include_once('../validation_functions.php');

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



# retrieve the template

$area = 'member';



$lstCity=sanitizeData($_POST['lstCity'], 'xss_clean');  

$lstState=sanitizeData($_POST['lstState'], 'xss_clean');

$lstCountry=sanitizeData($_POST['lstCountry'], 'xss_clean');
$chkCountrySort=sanitizeData($_POST['chkCountrySort'], 'xss_clean');
 

 

function db_to_form($date, $delimiter="." ) {

      $d = array();

      $d[day] = substr($date, 6, 2);

      $d[month] = substr($date, 4, 2);

      $d[year] = substr($date, 0, 4);

      $d[hours] = substr($date, 8, 2);

      $d[minutes] = substr($date, 10, 2);

      return $d[month].$delimiter.$d[day].$delimiter.$d[year]." ".$d[hours].":".$d[minutes];

  }

# retrieve the template

$area = 'member';



//print_r($_POST);

if ($chkCountrySort == '1' || 0==0)

    {

    if ($lstCountry>0) {

        $countryquery = " AND ev_country = '$lstCountry' ";

        if ($lstState>0) $statequery = " AND ev_state = '$lstState' ";

        if ($lstCity>0) $cityquery = " AND ev_city = '$lstCity' ";

    }

    }

else

    {

    $countryquery = $statequery = $cityquery = "";

    }



# retrieve the events

$query="SELECT *

        FROM events

            LEFT JOIN geo_country

                ON (gcn_countryid = ev_country)

            LEFT JOIN geo_state

                ON (gst_stateid = ev_state)

            LEFT JOIN geo_city

                ON (gct_cityid = ev_city)

        WHERE ev_approved='1' $countryquery $statequery $cityquery ORDER by ev_schedule desc";

//echo $query;

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$count = mysqli_num_rows($retval);





$query="SELECT *

        FROM geo_country

        WHERE gcn_countryid > 0

        ORDER BY gcn_order, gcn_name";

$countries = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$query = "

        SELECT * FROM geo_state

        WHERE gst_stateid > 0

        AND gst_countryid = '$lstCountry'

        ORDER BY gst_name";

$states = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

if (isset($lstState)) {

      $query = "

        SELECT * FROM geo_state

        WHERE gst_stateid = '$lstState'

        AND gst_countryid = '$lstCountry'

        ORDER BY gst_name";

      $checkstate = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

      if (mysqli_num_rows($checkstate) == 0) unset($lstState);

}

if (isset($lstState)) {

      $query = "

        SELECT * FROM geo_city

        WHERE

            gct_cityid > 0

            AND gct_countryid = '$lstCountry'

            AND gct_stateid = '$lstState'

        ORDER BY gct_name";

      $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

} else {

      $query = "

        SELECT * FROM geo_city

        WHERE

            gct_cityid > 0

            AND gct_countryid = '$lstCountry'

        ORDER BY gct_name";

      $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

}



?>

<?=$skin->ShowHeader($area)?>

<!--<script language="javascript" src="<?php echo $CONST_LINK_ROOT?>/geography.js"></script>-->

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader"><?php echo ADDEVENTS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>

<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

  <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/prgevents.php' name="FrmEvent" onSubmit="">

       <input type='hidden' name='mode' value="add">

       <tr >

                  <td colspan="2" align="left" class="tdhead">&nbsp;</td>

                </tr>

                <tr >

                  <td align="left" class="tdodd">

                    <?= ADDEVENT_NAME?>

                    :</td>

                  <td align="left" class="tdodd"> <input name="txtEventName" type="text" class="input" id="txtEventName2" value="<?php echo htmlspecialchars($txtEventName); ?>" size="30" maxlength="50"></td>

                </tr>

                <tr >

                  <td align="left" class="tdeven">

                    <?= GENERAL_ADDRESS?>

                    :</td>

                  <td align="left" class="tdeven"> <input name="txtAddress" type="text" class="input" id="txtAddress2" value="<?php echo htmlspecialchars($txtAddress); ?>" size="40"></td>

                </tr>

                <tr >

                  <td align="left" class="tdodd">

                    <?= GENERAL_COUNTRY?>

                    :</td>

                  <td align="left" class="tdodd">

                <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" onChange="FrmEvent.action='<?php echo $CONST_LINK_ROOT ?>/admin/addevent.php'; submit(); return true;">

                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                    <option value=""></option>

                <?php

                        while ($sql_array = mysqli_fetch_object($countries)) {

                            if ($lstCountry == $sql_array->gcn_countryid)

                                print("<option value='$sql_array->gcn_countryid' selected>$sql_array->gcn_name</option>\n");

                            else

                                print("<option value='$sql_array->gcn_countryid'>$sql_array->gcn_name</option>\n");

                        }

                ?>

                </select>&nbsp;

<!--

                  <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" onchange="onCountryListChange('FrmEvent', 'lstCountry', 'lstState', 'lstCity');">

                        <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                        <option value=""></option>

                  </select>

-->

<!--                  <input name="txtCity" type="text" class="input" id="txtCity2" value="" size="15">-->

                  </td>

                </tr>



                <tr >

                  <td align="left" class="tdodd">

                    <?= GENERAL_STATE?>

                    :</td>

                  <td align="left" class="tdodd">

<!--

                    <select class="input" name="lstState" id="lstState" size="1" tabindex="1" onchange="onStateListChange('FrmEvent', 'lstCountry', 'lstState', 'lstCity');">

                        <option value="0" selected></option>

                    </select>

-->

                <select class="input" name="lstState" id="lstState" size="1" tabindex="1" onChange="FrmEvent.action='<?php echo $CONST_LINK_ROOT ?>/admin/addevent.php'; submit(); return true;">

                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                <?php

                        while ($sql_array = mysqli_fetch_object($states)) {

                            if ($lstState==$sql_array->gst_stateid )

                                print("<option value='$sql_array->gst_stateid' selected>$sql_array->gst_name</option>\n");

                            else

                                print("<option value='$sql_array->gst_stateid'>$sql_array->gst_name</option>\n");

                        }

                ?>

                </select>&nbsp;

<!--                    <input name="txtCity" type="text" class="input" id="txtCity2" value="" size="15">-->

                    </td>

                </tr>

                <tr >

                  <td align="left" class="tdodd">

                    <?= GENERAL_CITY?>

                    :</td>

                  <td align="left" class="tdodd">

<!--

                    <select class="input" name="lstCity" id="lstCity" size="1" tabindex="1" onchange="onCityListChange('FrmEvent', 'lstCity');">

                        <option value="0" selected></option>

                    </select>

-->

                <select class="input" name="lstCity" id="lstCity" size="1" tabindex="1">

                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                <?php

                        while ($sql_array = mysqli_fetch_object($cities)) {

                            if ($lstCity==$sql_array->gct_cityid)

                                print("<option value='$sql_array->gct_cityid' selected>$sql_array->gct_name</option>\n");

                            else

                                print("<option value='$sql_array->gct_cityid'>$sql_array->gct_name</option>\n");

                        }

                ?>

                </select>&nbsp;

<!--                    <input name="txtCity" type="text" class="input" id="txtCity2" value="" size="15"> -->

                  </td>

                </tr>

<!--

          <script language="javascript">

            initialize('FrmEvent', 'lstCountry', 'lstState', 'lstCity');

          </script>

-->

                <tr >

                  <td rowspan="2" align="left" class="tdodd" >

                    <?= ADDEVENT_DATE?>

                    :</td>

                  <td align="left" class="tdodd"> <select class="inputs" name=txtHours >

                      <?generate_option(23,0,12)?>

                    </select>

                    :

                    <select class="inputs" name=txtMinutes >

                      <?generate_option(59,0,0)?>

                    </select>

                    HH:MM</td>

                </tr>

                <tr >

                  <td align="left" class="tdodd"> <select class="inputs" name=txtYear >

                      <?generate_option(date("Y")+1,date("Y"),date("Y"))?>

                    </select>

                    /

                    <select class="inputs" name=txtMonth >

                      <?generate_option(12,1,date("m"))?>

                    </select>

                    /

                    <select class="inputs" name=txtDay >

                      <?generate_option(31,1,date("d"))?>

                    </select>

                    <!--        <input name="txtSchedule" type="text" class="input" id="txtSchedule" value="<?php echo $txtDate?>" size="30" maxlength="30">-->

                    YYYY/MM/DD</td>

                </tr>

                <tr >

                  <td align="left" class="tdeven">

                    <?= CLUBS_PHONE?>

                    :</td>

                  <td align="left" class="tdeven"> <input name="txtPhone" type="text" class="input" id="txtPhone2" value="<?php echo $txtPhone?>" size="25" maxlength="25"></td>

                </tr>

                <tr >

                  <td align="left" class="tdodd">

                    <?= ADDEVENT_WEB?>

                    :</td>

                  <td align="left" class="tdodd"> <input name="txtWebsite" type="text" class="input" id="txtWebsite2" value="<?php if (trim($txtWebsite) == "") echo "http://"; else echo $txtWebsite?>" size="30" maxlength="50"></td>

                </tr>

                <tr >

                  <td align="left" class="tdeven">

                    <?= GENERAL_DESCRIPTION?>

                    :</td>

                  <td align="left" class="tdeven"> <textarea  class="input"name="txtDesc" cols="40" rows="5" id="textarea3"></textarea></td>

                </tr>

                <tr >

                  <td align="left" class="tdodd">

                    <?= ADDEVENT_GRAPH?>

                    :</td>

                  <td align="left" class="tdodd"> <input class="inputf" name="mainfupload" type="file" id="mainfupload4"></td>

                </tr>

                <tr align="center" >

                  <td colspan="2" class="tdfoot"> <input type="submit" name="Add" value="<?php echo BUTTON_SUBMIT ?>" class="button">

                  </td>

                </tr> </form>

              </table> </td>

          </tr>

      </table>

<?=$skin->ShowFooter($area)?>

