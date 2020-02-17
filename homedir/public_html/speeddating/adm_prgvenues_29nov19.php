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

# Name: 		adm_prgvenues.php

#

# Description:  Administrator can amend members records

#

# # Version:      8.0

#

######################################################################

include('../db_connect.php');

include('session_handler.inc');

include('../error.php');

include('../functions.php');

include('../geography.php');

include('../admin/permission.php');



if (isset($_GET['mode'])) $mode=$_GET['mode'];

if (isset($_POST['mode'])) $mode=$_POST['mode'];



# retrieve the template

$area = 'member';





if(isset($_POST['id']))

    $current_venue_id = $_POST['id'];

switch ($mode) {

    case 'update':

        $vnu_venueid = $_POST['id'];

        $vnu_name = form_get('name');

        $vnu_countryid = $_POST['country'];

        $vnu_stateid = $_POST['state'];

        $vnu_cityid = $_POST['city'];

        $vnu_address = form_get('address');

        $vnu_phone = form_get('phone');

        $vnu_website = form_get('website');

        $vnu_description = form_get('description');

        $vnu_directions = form_get('directions');

        $vnu_map = form_get('map');

        $sql_query = "

            UPDATE sd_venues

            SET

                vnu_name = '$vnu_name',

                vnu_countryid = '$vnu_countryid',

                vnu_stateid = '$vnu_stateid',

                vnu_cityid = '$vnu_cityid',

                vnu_address = '$vnu_address',

                vnu_phone = '$vnu_phone',

                vnu_website = '$vnu_website',

                vnu_description = '$vnu_description',

                vnu_directions = '$vnu_directions',

                vnu_map = '$vnu_map'

            WHERE vnu_venueid = $vnu_venueid";

//echo $sql_query;

        mysqli_query($globalMysqlConn,$sql_query) or die(mysqli_error());

        $txtVenue = $vnu_name;

        break;

    case 'create':

        $vnu_name = form_get('name');

        $vnu_countryid = $_POST['country'];

        $vnu_stateid = $_POST['state'];

        $vnu_cityid = $_POST['city'];

        setcookie("lstCountry", $vnu_countryid);

        setcookie("lstState", $vnu_stateid);

        setcookie("lstCity", $vnu_cityid);

        $vnu_address = form_get('address');

        $vnu_phone = form_get('phone');

        $vnu_website = form_get('website');

        $vnu_description = form_get('description');

        $vnu_directions = form_get('directions');

        $vnu_map = form_get('map');

        if (!$vnu_name) {

                error_page(ADM_PRGVENUES_ERROR_NAME,GENERAL_USER_ERROR);

                exit;

        }

        if (!$vnu_countryid) {

                error_page(ADM_PRGVENUES_ERROR_COUNTRY,GENERAL_USER_ERROR);

                exit;

        }

        if (!$vnu_cityid) {

                error_page(ADM_PRGVENUES_ERROR_CITY,GENERAL_USER_ERROR);

                exit;

        }

        if (!$vnu_address) {

                error_page(ADM_PRGVENUES_ERROR_ADDRESS,GENERAL_USER_ERROR);

                exit;

        }

        if (!$vnu_phone) {

                error_page(ADM_PRGVENUES_ERROR_PHONE,GENERAL_USER_ERROR);

                exit;

        }

        if (!$vnu_description) {

                error_page(ADM_PRGVENUES_ERROR_DESCRIPTION,GENERAL_USER_ERROR);

                exit;

        }

        if (!$vnu_directions) {

                error_page(ADM_PRGVENUES_ERROR_DIRECTION,GENERAL_USER_ERROR);

                exit;

        }

        $sql_query = "

            INSERT INTO sd_venues (

                vnu_name, vnu_countryid, vnu_stateid, vnu_cityid, vnu_address, vnu_phone, vnu_website, vnu_description, vnu_directions, vnu_map)

            VALUES (

                '$vnu_name', '$vnu_countryid', '$vnu_stateid', '$vnu_cityid', '$vnu_address', '$vnu_phone', '$vnu_website', '$vnu_description', '$vnu_directions', '$vnu_map')";

        mysqli_query($globalMysqlConn,$sql_query) or die(mysqli_error());

        $txtVenue = $vnu_name;

        $current_venue_id = mysqli_insert_id($globalMysqlConn);

        $lstVenue = $current_venue_id;

        setcookie("lstCountry", "");

        setcookie("lstState", "");

        setcookie("lstCity", "");

        break;

    case 'delete':

        $vnu_venueid = $_POST['id'];

        $sql_query = "SELECT COUNT(*) FROM sd_events WHERE sde_venueid = $vnu_venueid";

        if(mysqli_num_rows(mysqli_query($globalMysqlConn,$sql_query))){

            error_page(SD_VENUES_TEXT2,GENERAL_SYSTEM_ERROR);

            die;

        }

        $sql_query = "DELETE FROM sd_venues WHERE vnu_venueid = $vnu_venueid";

        mysqli_query($globalMysqlConn,$sql_query) or die(mysqli_error());

        $filename = "venues/$vnu_venueid.jpg";

        // Delete previously uploaded picture.

        @unlink($filename);

        // Delete record from the database

        mysqli_query($globalMysqlConn,"DELETE FROM sd_venue_pic WHERE vnp_venueid = $current_venue_id");

        break;

    case 'delete_picture':

        $vnu_venueid = $_POST['id'];

        $filename = "venues/$_POST[id].jpg";

        unlink($filename);

        mysqli_query($globalMysqlConn, "DELETE FROM sd_venue_pic WHERE vnp_venueid = $current_venue_id");

        break;

}

// Update the geographic javascript

if (!$error && ($mode == 'update' || $mode == 'create' || $mode == 'delete')) {

    $f = fopen('geography.js', 'w');

    fwrite($f, get_geography_js('sd_venues', 'vnu_countryid', 'vnu_stateid', 'vnu_cityid'));

    fclose($f);

}

// Upload photo.

if(isset($_FILES['picture']) && isset($current_venue_id))

{

    if($_FILES['picture']['name'] != '')

    {

        if ($_FILES['picture']['type'] != "image/pjpeg" && $_FILES['picture']['type'] != "image/jpeg")

            error_page(SD_VENUES_TEXT1,GENERAL_USER_ERROR);

        else

        {

            $filename = "venues/$current_venue_id.jpg";

            // Delete previously uploaded picture.

            @unlink($filename);

            mysqli_query($globalMysqlConn,"DELETE FROM sd_venue_pic WHERE vnp_venueid = $current_venue_id");

            // Save new picture.

            copy($_FILES['picture']['tmp_name'], $filename);

            mysqli_query($globalMysqlConn,"INSERT INTO sd_venue_pic (vnp_venueid) VALUES ($current_venue_id)");

        }

    }

}



if(isset($lstVenue) && ($mode == 'fetch' || $mode == 'update' || $mode == 'create' || $mode == 'delete_picture'))

{

    # select venue data

    $result = mysqli_query($globalMysqlConn,"SELECT * FROM sd_venues LEFT JOIN sd_venue_pic ON (vnp_venueid = vnu_venueid) WHERE vnu_venueid = '$lstVenue'");

    $TOTAL = mysqli_num_rows($result);



    if ($TOTAL > 0)

        $selected_venue = mysqli_fetch_object($result);

}



setcookie("lstCountry", "");

setcookie("lstState", "");

setcookie("lstCity", "");

?>

<?=$skin->ShowHeader($area)?>



<script language="JavaScript" src="jscript_sd_lib.js"></script>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo SPEED_VENUES_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

          <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/speeddating/adm_prgvenues.php' name="frmVenues" onsubmit="return sd_venue_edit(this,mode)">

            <input type="hidden" name="mode" value="">

            <tr>

              <td align="left" colspan="3"></td>

            </tr>

            <tr>

              <td colspan="3" align="left" class="tdhead">&nbsp;</td>

            </tr>

            <tr class="tdodd">

              <td width="8%" align="left">Search</td>

              <td width="40%" class="tdodd"> <input name="txtVenue" type="text" class="input" value="<?= htmlspecialchars(stripslashes($txtVenue)) ?>">

              </td>

              <td width="52%" align="left" class="tdodd"> <input name="submit" class='button' type='submit' value='<?=ADM_PRGVENUES_SEARCH?>'>

              </td>

            </tr>

            <tr align="left" valign="top" class="tdeven">

              <td > Venue</td>

              <td class="tdeven"> <select name="lstVenue" size="6" class="inputl">

                  <option value='0' <?php if (!isset($lstVenue)) print("selected"); ?>>--

                  <?php echo SPEED_VENUE_NAME?> --</option>

                  <?php

                    if (isset($_POST['submit'])) {

                        $txtHandle=$_POST['txtHandle'];

                        $query="

                            SELECT *

                            FROM sd_venues

                            LEFT JOIN geo_country ON (vnu_countryid = gcn_countryid)

                            LEFT JOIN geo_state ON (vnu_stateid = gst_stateid)

                            LEFT JOIN geo_city ON (vnu_cityid = gct_cityid)

                            WHERE vnu_name LIKE '$txtVenue%' ORDER BY vnu_name ASC";

                        $result=mysqli_query($globalMysqlConn,$query);

                        while ($venue = mysqli_fetch_object($result)) {

                            if (isset($lstVenue) && $lstVenue == $venue->vnu_venueid)

                                $selected = ' selected';

                            else

                                $selected = '';

                            print("<option value='$venue->vnu_venueid'$selected>".htmlspecialchars("$venue->vnu_name (".arrange_location($venue).")")."</option>\n");

                        }

                    }

                    ?>

                </select>&nbsp;              </td>

              <td valign="bottom" class="tdeven">

                <input name="submit2" class='button' type='submit' value='<?=ADM_PRGVENUES_GET?>' onClick="document.forms['frmVenues']['mode'].value = 'fetch'">

              </td>

            </tr>

            <tr>

              <td colspan="3" align="left" class="tdfoot">&nbsp;</td>

            </tr>

            <tr>

              <td colspan="3" align="left" class="tdhead">&nbsp; </td>

            </tr>

            <input type="hidden" name="id" value="<?= $selected_venue->vnu_venueid ?>">

            <tr class="tdodd">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_VENUE?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdodd"> <input name="name" type="text" class="inputl" value="<?= htmlspecialchars($selected_venue->vnu_name) ?>" size="40"></td>

            </tr>

                <? if ($GEOGRAPHY_AJAX) { ?>

<script src="<?=CONST_LINK_ROOT?>/moo.ajax/prototype.lite.js"></script>

<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>

<script src="<?=CONST_LINK_ROOT?>/ajax_lib.js.php"></script>

            <tr class="tdeven">

              <td align="left" valign="middle">

                <?=GENERAL_COUNTRY?>

              </td>

            <td colspan="2" align="left" valign="middle" class="tdeven"> <select class="input" name="country" id="lstCountry" size="1"  tabindex='13' onchange="sendStateRequest(this.options[this.selectedIndex].value);sendCityRequest(this.options[this.selectedIndex].value,0); return false;">

                <option value="0">-- <?php echo GENERAL_CHOOSE?> --</option>

                <option value=""></option>

<?php

include_once __INCLUDE_CLASS_PATH."/class.Geography.php";

$CountriesList = Geography::getCountriesList();

foreach ($CountriesList as $countryrow)

{

    $selected = ($selected_venue->vnu_countryid == $countryrow->gcn_countryid)?' selected':'';

    echo '<option value='.$countryrow->gcn_countryid.$selected.'>'.htmlspecialchars($countryrow->gcn_name).'</option>';

}

?>

              </select> </td>

          </tr>

          <tr class="tdodd">

            <td align="left" valign="middle">

                <?=GENERAL_STATE?>

            </td>

            <td colspan="2" align="left" valign="middle" class="tdodd">

<?php

$result = "";

if ($selected_venue->vnu_countryid) {
    $geo = new Geography;
    $StatesList = $geo->getStatesList($selected_venue->vnu_countryid);

    foreach ($StatesList as $staterow)

    {

        $selected = ($selected_venue->vnu_stateid == $staterow->gst_stateid)?' selected':'';

        $result .= "<OPTION value=".$staterow->gst_stateid.$selected.">".htmlspecialchars($staterow->gst_name)."</OPTION>";

    }

}

$disabled = ($result != "")?"":" disabled";

?>

            <select <?=$disabled?> class="input" name="state" id="lstState" size="1"  tabindex='14' onchange="sendCityRequest(document.getElementById('lstCountry').value,this.value); return false;">

                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>

                <?=$result?>

            </select></td>

          </tr>

          <tr class="tdeven">

            <td align="left" valign="middle">

                <?=GENERAL_CITY?>

            </td>

            <td colspan="2" align="left" valign="middle" class="tdeven">

<?php

$result = "";

if ($selected_venue->vnu_countryid || $selected_venue->vnu_stateid) {
    $geo2 = new Geography;
    $CitiesList = $geo2->getCitiesList($selected_venue->vnu_countryid,$selected_venue->vnu_stateid);

    foreach ($CitiesList as $cityrow)

    {

        $selected = ($selected_venue->vnu_cityid == $cityrow->gct_cityid)?' selected':'';

        $result .= "<OPTION value=".$cityrow->gct_cityid.$selected.">".htmlspecialchars($cityrow->gct_name)."</OPTION>";

    }

}

$disabled = ($result != "")?"":" disabled";

?>

            <select <?=$disabled?> class="input" name="city" id="lstCity" size="1" tabindex='15'>

                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>

                <?=$result?>

            </select></td>

          </tr>

<? if (empty($current_venue_id)) { ?>

<script language="javascript">

country = getCookie('lstCountry');

state = getCookie('lstState');

city = getCookie('lstCity');

function initialize(SelectedCountry, SelectedState, SelectedCity)

{

    var lstCountry = new getObj('lstCountry').obj;

    if(SelectedCountry)

    {

        for(iOption = 0; iOption < lstCountry.options.length; iOption++)

            if(lstCountry.options[iOption].value == SelectedCountry) {

                lstCountry.options[iOption].selected = true;

            }

        sendStateRequest(SelectedCountry,SelectedState);

        sendCityRequest(SelectedCountry,SelectedState,SelectedCity);

    }

}

initialize(country, state, city);

</script>

<? } ?>

              <? } else { ?>

<script language="javascript" src="../geography.js"></script>

            <tr class="tdeven">

              <td align="left" valign="middle">

                <?=GENERAL_COUNTRY?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdeven"> <select name="country" id="country" size="1" class="inputl" tabindex="1" onchange="onCountryListChange('frmVenues', 'country', 'state', 'city');">

                  <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                  <option value=""></option>

                </select> </td>

            </tr>

            <tr class="tdodd">

              <td align="left" valign="middle">

                <?=GENERAL_STATE?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdodd"> <select name="state" id="state" size="1" class="inputl" tabindex="1" onchange="onStateListChange('frmVenues', 'country', 'state', 'city');">

                  <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                  <option value=""></option>

                </select> </td>

            </tr>

            <tr class="tdeven">

              <td align="left" valign="middle">

                <?=GENERAL_CITY?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdeven"> <select name="city" id="city" size="1" class="inputl" tabindex="1" onchange="onCityListChange('frmVenues', 'city');">

                  <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                  <option value=""></option>

                </select> </td>

            </tr>

            <script language="javascript">

            initialize('frmVenues', 'country', 'state', 'city', new Array('<?=$selected_venue->vnu_countryid?>'), new Array('<?=$selected_venue->vnu_stateid?>'), new Array('<?=$selected_venue->vnu_cityid?>'));

        </script>

              <? } ?>

            <tr class="tdodd">

              <td align="left" valign="middle" class="tdodd">

                <?=ADM_PRGVENUES_ADDRESS?>

              </td>

              <td colspan="2" align="left" valign="middle"> <textarea name="address" cols="40" rows="6" class="inputl"><?= htmlspecialchars($selected_venue->vnu_address) ?></textarea></td>

            </tr>

            <tr class="tdeven">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_PHONE?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdeven"> <input name="phone" type="text" class="inputl" value="<?= htmlspecialchars($selected_venue->vnu_phone) ?>" size="40"></td>

            </tr>

            <tr class="tdodd">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_URL?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdodd"> <input name="website" type="text" class="inputl" value="<?= htmlspecialchars($selected_venue->vnu_website) ?>" size="40"></td>

            </tr>

            <tr class="tdeven">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_PICTURE?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdeven">

                <?php

                if(isset($selected_venue))

                {

                    $filename = "venues/$selected_venue->vnu_venueid.jpg";

                    $pictureurl = "venues/$selected_venue->vnu_venueid.jpg";;

                    if(file_exists($filename))

                    {

                        srand((double) microtime() * 1000000);

                        ?>

                <img src="<?=$pictureurl?>?<?=$selected_venue->vnp_id?>" height="100">

                <input name="submit" class='button' type='submit' value='delete' onclick="document.forms['frmVenues']['mode'].value = 'delete_picture'">

                <br>

                <?php

                    }

                }

                ?>

                <input name="picture" type="file" class="inputf" size="30"> </td>

            </tr>

            <tr class="tdodd">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_DESC?>

              </td>

              <td valign="middle" align="left" colspan="2"> <textarea name="description" cols="40" rows="6" class="inputl"><?= htmlspecialchars($selected_venue->vnu_description) ?></textarea></td>

            </tr>

            <tr class="tdeven">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_DIR?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdeven"> <textarea name="directions" cols="40" rows="6" class="inputl"><?= htmlspecialchars($selected_venue->vnu_directions) ?></textarea></td>

            </tr>

            <tr class="tdodd">

              <td align="left" valign="middle">

                <?=ADM_PRGVENUES_MAP?>

              </td>

              <td colspan="2" align="left" valign="middle" class="tdodd"> <input name="map" type="text" class="inputl" value="<?= htmlspecialchars($selected_venue->vnu_map) ?>" size="40"></td>

            </tr>

            <tr align="center">

              <td colspan="3" valign="top" class="tdfoot">

                <?php if($selected_venue) { ?>

                <input name="submit3" class='button' type='submit' value='<?=ADM_PRGVENUES_UPDATE?>' onClick="document.forms['frmVenues']['mode'].value = 'update'">

                <input name="submit3" class='button' type='submit' value='<?=ADM_PRGVENUES_DELETE?>' onClick="if(!confirm('<?=ADM_PRGVENUES_SURE?>')){return false;} document.forms['frmVenues']['mode'].value = 'delete'">

                <input name="submit3" class='button' type='submit' value='<?=ADM_PRGVENUES_CANCEL?>' onClick="document.forms['frmVenues']['mode'].value = 'cancel'">

                <?php } else { ?>

                <input name="submit3" class='button' type='submit' value='<?=ADM_PRGVENUES_ADD?>' onClick="document.forms['frmVenues']['mode'].value = 'create'">

                <input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>">

				<?php } ?>

              </td>

            </tr>

          </form>

        </table>

      </td>

    </tr>

  </table>



<?=$skin->ShowFooter($area)?>