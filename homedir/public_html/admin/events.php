<?php

/*****************************************************

* � copyright 1999 - 2020 iDateMedia, LLC.

*

* All materials and software are copyrighted by iDateMedia, LLC.

* under British, US and International copyright law. All rights reserved.

* No part of this code may be reproduced, sold, distributed

* or otherwise used in whole or in part without prior written permission.

*

****************************************************/



######################################################################

#

# Name: 		events.php

#

# Description:  Displays the profile input page (after advert)

#

# Version:		7.3

#

######################################################################



include('../db_connect.php');
include_once('../validation_functions.php');  include('../session_handler.inc');

include('../error.php');

include('permission.php');



if ($_GET['del']==1){

    if ($_GET['event_id']){ $event_id=sanitizeData(trim($_GET['event_id']), 'xss_clean');   

        $query = "SELECT * FROM events WHERE ev_eventid=".$event_id;

        $res= mysqli_query($globalMysqlConn,$query);

        $row = mysqli_fetch_object($res);

        mysqli_query($globalMysqlConn,"DELETE FROM events WHERE ev_eventid=".$event_id);

        if ($row->ev_picture) unlink($CONST_INCLUDE_ROOT."/".$row->ev_picture);

        mysqli_query($globalMysqlConn,"DELETE FROM reviews WHERE review_id=".$event_id);

    }



}

$lstCity=sanitizeData($_POST['lstCity'], 'xss_clean');  

$lstState=sanitizeData($_POST['lstState'], 'xss_clean'); 

$lstCountry=sanitizeData($_POST['lstCountry'], 'xss_clean'); 



$chkCountrySort=sanitizeData($_POST['chkCountrySort'], 'xss_clean'); 

function db_to_form($date, $delimiter="/" ) {

      $d = array();

      $d['day'] = substr($date, 6, 2);

      $d['month'] = substr($date, 4, 2);

      $d['year'] = substr($date, 0, 4);

      $d['hours'] = substr($date, 8, 2);

      $d['minutes'] = substr($date, 10, 2);

      return $d['month'].$delimiter.$d['day'].$delimiter.$d['year']." ".$d['hours'].":".$d['minutes'];

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

<script language="javascript" src="<?php echo $CONST_LINK_ROOT?>/geography.js"></script>

<script>

function chkCountrySort_click()

    {

    if (document.forms[0].chkCountrySort.checked == true)

        {

        document.forms[0].lstCountry.disabled = false;

        document.forms[0].lstState.disabled = false;

        document.forms[0].lstCity.disabled = false;

        }

    else

        {

        document.forms[0].lstCountry.disabled = true;

        document.forms[0].lstState.disabled = true;

        document.forms[0].lstCity.disabled = true;

        }

    }

function delete_event(id)

    {

        document.location = "<?=$PHP_SELF?>?event_id="+id+"&del=1";

    }



</script>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo EVENTS_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>

        <form enctype='multipart/form-data' action="<?php echo $CONST_LINK_ROOT ?>/admin/events.php" method='post' name="FrmEvent" id="FrmEvent">

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <input type='hidden' name='event_id' value="">

        <input type='hidden' name='del' value="">

        <input type='hidden' name='mode' value="view">

          <tr>

            <td colspan="4"  align="left" class="tdhead">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="4" class="tdodd" >

<!--                <input type="checkbox" name="chkCountrySort" value="1" onClick="chkCountrySort_click()">-->

                Show only:

<!--

                <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" disabled onchange="onCountryListChange('FrmEvent', 'lstCountry', 'lstState', 'lstCity');">

                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                    <option value=""></option>

                </select>

                <select class="input" name="lstState" id="lstState" size="1" tabindex="1" disabled onchange="onStateListChange('FrmEvent', 'lstCountry', 'lstState', 'lstCity');">

                    <option value="0" selected></option>

                </select>

                <select class="input" name="lstCity" id="lstCity" size="1" tabindex="1" disabled onchange="onCityListChange('FrmEvent', 'lstCity');">

                    <option value="0" selected></option>

                </select>

                <script language="javascript">

                    initialize('FrmEvent', 'lstCountry', 'lstState', 'lstCity');

                </script>

-->

                <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" onChange="FrmEvent.mode.value='view'; submit(); return true;">

                    <option value="0" selected><?php echo SEARCH_ANY?></option>

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

                <select class="input" name="lstState" id="lstState" size="1" tabindex="1" onChange="FrmEvent.mode.value='view'; submit(); return true;">

                    <option value="0" selected><?php echo SEARCH_ANY?></option>

                <?php

                        while ($sql_array = mysqli_fetch_object($states)) {

                            if ($lstState==$sql_array->gst_stateid )

                                print("<option value='$sql_array->gst_stateid' selected>$sql_array->gst_name</option>\n");

                            else

                                print("<option value='$sql_array->gst_stateid'>$sql_array->gst_name</option>\n");

                        }

                ?>

                </select>&nbsp;

                <select class="input" name="lstCity" id="lstCity" size="1" tabindex="1" onChange="FrmEvent.mode.value='view'; submit(); return true;">

                    <option value="0" selected><?php echo SEARCH_ANY?></option>

                <?php

                        while ($sql_array = mysqli_fetch_object($cities)) {

                            if ($lstCity==$sql_array->gct_cityid)

                                print("<option value='$sql_array->gct_cityid' selected>$sql_array->gct_name</option>\n");

                            else

                                print("<option value='$sql_array->gct_cityid'>$sql_array->gct_name</option>\n");

                        }

                ?>

                </select>

                <input name="search" type="submit" class="button" value="Filter Now">

             </td>

          </tr>

          <tr>

            <td  align="left" class="tdeven">Event</td>

            <td  align="left" class="tdeven">City, State, Country</td>

            <td  align="left" class="tdeven">Date/Time</td>

            <td  align="center" class="tdeven">Delete</td>

          </tr>

          <?php for($i=0;$i<$count;$i++)

                    {

                    $row=mysqli_fetch_array($retval);

                    $address = array();

                    if ($row['gcn_name']) $address[] = $row['gct_name'];

                    if ($row['gst_name']) $address[] = $row['gst_name'];

                    if ($row['gct_name']) $address[] = $row['gcn_name'];

                    $address = join(', ',$address);

                    ?>

          <tr class="tdodd">

            <td  align="left"><a href="<?= "$CONST_LINK_ROOT/viewevent.php?eventid=".$row['ev_eventid'] ?>">

              <?=$row['ev_eventname'] ?>

              </a> </td>

            <td  align="left">

                <?=$address?>

              </td>

            <td  align="left">

              <?=$row['ev_schedule']?>

            </td>

            <td  align="center">

              <input type=button class="button" value="Delete" name=del onClick="if (confirm('Delete events?')) delete_event(<?=$row['ev_eventid']?>)">

            </td>

          </tr>

          <?php } ?>

          <tr>

            <td colspan="4" align="left" valign="top" class="tdfoot">&nbsp;</td>

          </tr>

      </table>

        </form>

      </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

