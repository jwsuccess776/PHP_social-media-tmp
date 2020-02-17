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

# Name: 		adm_geography.php

#

# Description:  add and edits the templates

#

# Version:		5.0

#

######################################################################

    

include('../db_connect.php');

include_once('../validation_functions.php');

include($CONST_INCLUDE_ROOT.'/session_handler.inc');

include($CONST_INCLUDE_ROOT.'/message.php');

include('permission.php');



if (isset($_GET['mode'])) $mode=$_GET['mode'];

if (isset($_POST['mode'])) $mode=$_POST['mode'];

$lstRegion=0;
    

//$lstCountry= isset($_POST['lstCountry']) ? sanitizeData($_POST['lstCountry'], 'xss_clean') : "";
if(isset($_POST['lstCountry'])){
    $lstCountry=  sanitizeData($_POST['lstCountry'], 'xss_clean');
}
else{
    $lstCountry= $_POST['lstCountry'];
}

if(isset($_POST['lstState'])){
    $lstState=  sanitizeData($_POST['lstState'], 'xss_clean');
}
else{
    $lstState= $_POST['lstState'];
}

    

$lstCity=(array)$_POST['lstCity'];

//echo '<pre>';

//print_r($lstCity);

//echo '<br>';

# retrieve the template

restrict_demo();

switch ($mode) {

    case 'addcountry':

        if (!empty($_POST['newCountry'])) {

            $newCountry=sanitizeData($_POST['newCountry'], 'xss_clean');  

            $query="INSERT INTO geo_country ( gcn_name , gcn_status , gcn_order,gcn_regionid)

                    VALUES ('$newCountry', '1', '1000','0')";

            $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

        break;

    case 'editcountry':

        header('Location: '.$CONST_ADMIN_LINK_ROOT.'/adm_countryedit.php?country_id='.$lstCountry);

        break;

    case 'delcountry':

        $query="DELETE FROM geo_city WHERE gct_countryid = $lstCountry";

        $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        $query="DELETE FROM geo_state WHERE gst_countryid = $lstCountry";

        $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        $query="DELETE FROM geo_country WHERE gcn_countryid = $lstCountry";

        $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        unset($lstCountry);

        break;

    case 'addstate':

        if (!empty($_POST['newState'])) {

            $newState=sanitizeData($_POST['newState'], 'xss_clean');   

            $query="INSERT INTO geo_state ( gst_name, gst_countryid)

                    VALUES ('$newState', '$lstCountry')";

            $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

        break;

    case 'editstate':

        header('Location: '.$CONST_ADMIN_LINK_ROOT.'/adm_stateedit.php?state_id='.$lstState);

        break;

    case 'delstate':

        $query="DELETE FROM geo_city WHERE gct_stateid = $lstState";

        $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        $query="DELETE FROM geo_state WHERE gst_stateid = $lstState";

        $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        unset($lstState);

        break;

    case 'addcity':

        if (!empty($_POST['newCity'])) {

            $newCity=sanitizeData($_POST['newCity'], 'xss_clean');    

            $query="INSERT INTO geo_city ( gct_name, gct_countryid, gct_stateid)

                    VALUES ('$newCity', '$lstCountry', '$lstState')";

            $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

        break;

    case 'editcity':

        header('Location: '.$CONST_ADMIN_LINK_ROOT.'/adm_cityedit.php?city_id='.$lstCity[0]);

        break;

    case 'delcity':

        $query="DELETE FROM geo_city WHERE gct_cityid in ('".join("','",(array)$lstCity)."')";

        $result = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        break;

    case 'update':

        include('geography.php');

        $f = fopen($CONST_INCLUDE_ROOT.'/geography.js', 'w');

        fwrite($f, get_geography_js());

        fclose($f);

        break;

}



if (isset($lstRegion)) {



    $query = "SELECT * FROM geo_country

            WHERE gcn_countryid > 0

            ORDER BY gcn_order,gcn_name";

        $countries = mysqli_query($globalMysqlConn,$query) or die(mysqli_error()."1");



////          echo $query;

          $checkcountry = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          if (mysqli_num_rows($checkcountry) == 0) unset($lstCountry);

          $is_countries = mysqli_num_rows($checkcountry);



    if (isset($lstCountry)) {

        $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid > 0

            AND gst_countryid = $lstCountry

            ORDER BY gst_name";

        $states = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        if (isset($lstState)) {

          $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid = $lstState

            AND gst_countryid = $lstCountry

            ORDER BY gst_name";

          $checkstate = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          if (mysqli_num_rows($checkstate) == 0) unset($lstState);

        }

        if (isset($lstState)) {

          $query = "

            SELECT * FROM geo_city

            WHERE

                gct_cityid > 0

                AND gct_countryid = $lstCountry

                AND gct_stateid = $lstState

            ORDER BY gct_name";

          $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        } else {

          $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid > 0

            AND gst_countryid = $lstCountry

            ORDER BY gst_name

            LIMIT 1";

//          echo $query;

//          echo '<br>';

          $state = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          if (mysqli_num_rows($state) > 0) {

              $_state = mysqli_fetch_object($state);

              $query = "

                SELECT * FROM geo_city

                WHERE

                    gct_cityid > 0

                    AND gct_countryid = $lstCountry

                    AND gct_stateid = $_state->gst_stateid

                ORDER BY gct_name";

//              echo $query;

//          echo '<br>yes';

              $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          } else {

              $query = "

                SELECT * FROM geo_city

                WHERE

                    gct_cityid > 0

                    AND gct_countryid = $lstCountry

                ORDER BY gct_name";

//              echo $query;

//          echo '<br>no';

              $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          }

        }

    }

    else {

        $query="SELECT *

            FROM geo_country

            WHERE gcn_countryid > 0

            ORDER BY gcn_order, gcn_name

            LIMIT 1";



        $country = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        $_country = mysqli_fetch_object($country);



///////////////////////////

if ($is_countries)     {

        $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid > 0

            AND gst_countryid = $_country->gcn_countryid

            ORDER BY gst_name";

        $states = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        if (isset($lstState)) {

          $query = "

            SELECT * FROM geo_city

            WHERE

                gct_cityid > 0

                AND gct_countryid = $_country->gcn_countryid

                AND gct_stateid = $lstState

            ORDER BY gct_name";

          $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        } else {

          $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid > 0

            AND gst_countryid = $_country->gcn_countryid

            ORDER BY gst_name

            LIMIT 1";

//          echo $query;

          $state = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          if (mysqli_num_rows($state) > 0) {

            $_state = mysqli_fetch_object($state);

            $query = "

                SELECT * FROM geo_city

                WHERE

                    gct_cityid > 0

                    AND gct_countryid = $_country->gcn_countryid

                    AND gct_stateid = $_state->gst_stateid

                ORDER BY gct_name";

            $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          } else {

            $query = "

                SELECT * FROM geo_city

                WHERE

                    gct_cityid > 0

                    AND gct_countryid = $_country->gcn_countryid

                ORDER BY gct_name";

            $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          }

        }





    }

////////////////////

    }

} else {





    $query="SELECT *

        FROM geo_country

        WHERE gcn_countryid > 0

        ORDER BY gcn_order, gcn_name";

    $countries = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



        $query="SELECT *

            FROM geo_country

            WHERE gcn_countryid > 0

            ORDER BY gcn_order, gcn_name

            LIMIT 1";

        $country = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        $_country = mysqli_fetch_object($country);



        $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid > 0

            AND gst_countryid = '$_country->gcn_countryid'

            ORDER BY gst_name";



        $states = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());





          $query = "

            SELECT * FROM geo_state

            WHERE gst_stateid > 0

            AND gst_countryid = $_country->gcn_countryid

            ORDER BY gst_name

            LIMIT 1";

          $state = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          if (mysqli_num_rows($state) > 0) {

            $_state = mysqli_fetch_object($state);

            $query = "

                SELECT * FROM geo_city

                WHERE

                    gct_cityid > 0

                    AND gct_countryid = $_country->gcn_countryid

                    AND gct_stateid = $_state->gst_stateid

                ORDER BY gct_name";

            $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          } else {

            $query = "

                SELECT * FROM geo_city

                WHERE

                    gct_cityid > 0

                    AND gct_countryid = $_country->gcn_countryid

                ORDER BY gct_name";

            $cities = mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

          }

}



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



    <td class="pageheader"><?php echo GEOGRAPHY_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' name='frmGeography' action='<?php echo $CONST_ADMIN_LINK_ROOT?>/adm_geography.php' >

          <input type='hidden' name='mode' value='view'>

          <tr valign="middle">

            <td height="30" colspan="4" align="left" class="tdHead"><?php echo ADM_GEO_WARNING ?></td>

          </tr>

          <tr class="tdodd">

            <td  align="left"><?php echo SEARCH_COUNTRY?></td>

            <td  align="left"> <select class="input" size="1" name="lstCountry" tabindex="1" onChange="frmGeography.mode.value='view'; submit(); return true;">

                <?php

						while ($sql_array = mysqli_fetch_object($countries)) {

							if ($lstCountry == $sql_array->gcn_countryid)

								print("<option value='$sql_array->gcn_countryid' selected>$sql_array->gcn_name</option>\n");

							else

								print("<option value='$sql_array->gcn_countryid'>$sql_array->gcn_name</option>\n");

						}

				?>

              </select></td>

            <td  align="left"><input type="submit" name="Submit" value="<?php echo BUTTON_EDIT ?>" class="button" onClick="frmGeography.mode.value='editcountry'; return true;"> <input type="submit" name="Submit" value="<?php echo BUTTON_REMOVE ?>" class="button" onClick="frmGeography.mode.value='delcountry'; return true;"></td>

            <td  align="left"><input type="input" class="input" name="newCountry"> <input type="submit" name="Submit" value="<?php echo BUTTON_ADD ?>" class="button" onClick="frmGeography.mode.value='addcountry'; return true;"></td>

          </tr>

          <tr class="tdodd">

            <td  align="left" valign="top"><?php echo SEARCH_STATE?></td>

            <td  align="left" valign="top"> <select class="input" size="1" name="lstState" tabindex="1" onChange="frmGeography.mode.value='view'; submit(); return true;">

                <?php

						while ($states && ($sql_array = mysqli_fetch_object($states))) {

							if ($lstState==$sql_array->gst_stateid )

								print("<option value='$sql_array->gst_stateid' selected>$sql_array->gst_name</option>\n");

							else

								print("<option value='$sql_array->gst_stateid'>$sql_array->gst_name</option>\n");

						}

				?>

              </select> </td>

            <td  align="left"><input type="submit" name="Submit" value="<?php echo BUTTON_EDIT ?>" class="button" onClick="frmGeography.mode.value='editstate'; return true;"> <input type="submit" name="Submit" value="<?php echo BUTTON_REMOVE ?>" class="button" onClick="frmGeography.mode.value='delstate'; return true;"></td>

            <td  align="left" valign="top"><input type="input" class="input" name="newState"> <input type="submit" name="Submit" value="<?php echo BUTTON_ADD ?>" class="button" onClick="frmGeography.mode.value='addstate'; return true;"></td>

          </tr>

          <tr class="tdodd">

            <td  align="left"><?php echo SEARCH_CITY?></td>

            <td  align="left"> <select class="input" size="4" name="lstCity[]" tabindex="1" multiple>

                <?php

                        while ($cities && ($sql_array = mysqli_fetch_object($cities))) {

                            if ($lstDescriptions==$sql_array->gct_cityid)

                                print("<option value='$sql_array->gct_cityid' selected>$sql_array->gct_name</option>\n");

                            else

                                print("<option value='$sql_array->gct_cityid'>$sql_array->gct_name</option>\n");

                        }

                ?>

              </select></td>

            <td  align="left"><input type="submit" name="Submit" value="<?php echo BUTTON_EDIT ?>" class="button" onClick="frmGeography.mode.value='editcity'; return true;"> <input type="submit" name="Submit" value="<?php echo BUTTON_REMOVE ?>" class="button" onClick="frmGeography.mode.value='delcity'; return true;"></td>

            <td  align="left"><input type="input" class="input" name="newCity"> <input type="submit" name="Submit" value="<?php echo BUTTON_ADD ?>" class="button" onClick="frmGeography.mode.value='addcity'; return true;"></td>

          </tr>

          <tr>

            <td  align="left"></td>

            <td  align="left"></td>

            <td  align="left"></td>

            <td  align="left"></td>

          </tr>

<?if($GEOGRAPHY_JAVASCRIPT){?>

          <tr>

            <td colspan="4" align="left" valign="top" class="tdfoot"> <center>

                <input type="submit" name="Submit" value="<?php echo BUTTON_UPDATE ?>" class="button"  onClick="frmGeography.mode.value='update'; return true;">

              </center></td>

          </tr>

<?}?>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>