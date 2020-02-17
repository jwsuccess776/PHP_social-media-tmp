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

# Name:         search.php

#

# Description:  Displays main search page

#

# Version:               7.2.1 /Igor/

#

######################################################################

include('db_connect.php');

include('session_handler.inc');

include('functions.php');

include('pop_lists.inc');

include_once($CONST_INCLUDE_ROOT."/search_conf.inc.php");



$block = preg_match("/MSIE/",$_SERVER['HTTP_USER_AGENT'])?'block':'table-row';

# retrieve the template

$area = 'member';

# retrieve the saved search parameters

$query="SELECT * FROM search WHERE sea_userid='$Sess_UserId'";

$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

$search_num=mysqli_num_rows($retval);

$is_selected='selected';

unset($_SESSION['s_querystring']);

$search_set=false;

$agemin_selected=""; $agemax_selected="";

if ($search_num > 0) {

    $search_set=true;

    $is_selected='';

    $sql_search= mysqli_fetch_object ($retval);

    # Retrieve the saved search parameters

    switch ($sql_search->sea_seeksex) {

                case 'Women seeking men':

                        $sexfrom='M';

                        $sexto='F';

                        break;

                case 'Women seeking women':

                        $sexfrom='F';

                        $sexto='F';

                        break;

                case 'Men seeking women':

                        $sexfrom='F';

                        $sexto='M';

                        break;

                case 'Men seeking men':

                        $sexfrom='M';

                        $sexto='M';

                        break;

        }



    foreach ($aSearchFileds as $field) {

        $query="SELECT sar_value FROM sarray WHERE sar_userid='$Sess_UserId' AND sar_type='$field[name]'";

        $retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

        while ($temp= mysqli_fetch_array ($retval)) {${"sql_".$field['name']}[]=$temp[0];}

    }



    # Country

    $query="SELECT sar_value FROM sarray WHERE sar_userid='$Sess_UserId' AND sar_type='lstCountry'";

    $retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

    $countries = "";

    while ($temp= mysqli_fetch_array ($retval)) {if($countries!=""){$countries.=",";}$countries.="'$temp[0]'";}

    # State

    $query="SELECT sar_value FROM sarray WHERE sar_userid='$Sess_UserId' AND sar_type='lstState'";

    $retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

    $states = "";

    while ($temp= mysqli_fetch_array ($retval)) {if($states!=""){$states.=",";}$states.="'$temp[0]'";}

    # City

    $query="SELECT sar_value FROM sarray WHERE sar_userid='$Sess_UserId' AND sar_type='lstCity'";

    $retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

    $cities = "";

    while ($temp= mysqli_fetch_array ($retval)) {if($cities!=""){$cities.=",";}$cities.="'$temp[0]'";}

    if ($sql_search->sea_agemin == '18') $agemin_selected="selected";

    if ($sql_search->sea_agemax == '99') $agemax_selected="selected";



}


$query="SELECT mem_sex FROM members WHERE mem_userid = '$Sess_UserId'";
$currentUserSex=$db->get_var($query);

?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

        <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader"><?php echo SEARCH_MAIN_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/prgsearch.php" name='FrmSearch'>

           <input type=hidden name=SEARCH value=1>

          <tr>

            <td colspan="2" align="left" class="tdhead">

              <?=SEARCH_BASIC?>

            </td>
            <td rowspan="6">
                <?php if($currentUserSex=="M") { ?>
                <img style="width: 350px" src="<?=$CONST_IMAGE_ROOT?>/search-if-you-are-a-male.png"/>
                <?php } 
                else { ?>
                    <img style="width: 350px" src="<?=$CONST_IMAGE_ROOT?>/search-if-you-are-a-female.png"/>
                <?php }?>
                </td>

          </tr>

          <tr class="tdodd" >

            <td colspan="3" align="left"> <input name="chkSearch" type="checkbox" value="ON" >

              <?=SEARCH_CLICK2SAVE?>

              <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/hsearch1.php','Help','width=250,height=375')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?= $CONST_IMAGE_LANG ?>/help_but.gif'></a>

            </td>

          </tr>

          <tr class="tdeven" >

            <td width="28%" align="left">

              <?=SEARCH_SHOW?>

            </td>

            <td width="72%" align="left"><select class="inputf" name="lstDatingFrom" size="1">

                <option  <?php if ($search_set && $sexfrom == 'M') print("selected"); ?> <?php print("$is_selected"); ?> value='M' selected><?php echo GENDER_M?></option>

                <option  <?php if ($search_set && $sexfrom == 'F') print("selected"); ?> value='F'><?php echo GENDER_W?></option>

                <?/*                <option  <?php if ($search_set && $sexfrom == 'C') print("selected"); ?> value='C'>

                <?php echo GENDER_C?></option>*/?> </select>

              <?=SEARCH_SHOW2?>

              <select class="inputf" name="lstDatingTo" size="1">

                <option  <?php if ($search_set && $sexto == 'M') print("selected"); ?> value='M' selected><?php echo GENDER_M?></option>

                <option  <?php if ($search_set && $sexto == 'F') print("selected"); ?> <?php print("$is_selected"); ?> value='F'><?php echo GENDER_W?></option>

                <?/*                <option  <?php if ($search_set && $sexto == 'C') print("selected"); ?> value='C'>

                <?php echo GENDER_C?></option>*/?> </select> </td>

          </tr>

          <tr class="tdodd" >

            <td align="left" valign="top">

              <?=SEARCH_AGES?>

            </td>

            <td align="left" valign="top"><select class="inputf" size="1" name="txtFromAge" >

                <option value='18' <?php echo $is_selected ?><?php echo $agemin_selected ?>>18</option>

                <?php

                        for ($i=19; $i < 100; $i++) {

                            if ($search_set && $i == $sql_search->sea_agemin) {

                                print("<option value='$i' selected>$i</option>");

                            } else {

                                    print("<option value='$i'>$i</option>");

                            }

                        }

                ?>

              </select>

              -

              <select class="inputf" size="1" name="txtToAge" >

                <?php

                        for ($i=18; $i < 99; $i++) {

                            if ($search_set && $i == $sql_search->sea_agemax) {

                                print("<option value='$i' selected>$i</option>");

                            } else {

                                print("<option value='$i'>$i</option>");

                            }

                        }

                ?>

                <option value='99' <?php echo $is_selected ?><?php echo $agemax_selected ?>>99</option>

              </select></td>

          </tr>

          <? if ($GEOGRAPHY_JAVASCRIPT) {

                 if ($GEOGRAPHY_AJAX) { ?>

<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>

<script src="<?=CONST_LINK_ROOT?>/ajax_lib.js.php"></script>

          <tr class="tdodd" >

          <tr class="tdeven" >

            <td align="left" valign="top">

              <?=SEARCH_COUNTRY?>

            </td>

            <td > <select class="inputl" name="lstCountry[]" size="4" multiple id="lstCountry" onchange="sendStateRequest();sendCityRequest(); return false;">

                <option value="0"><?=SEARCH_ALLCOUNTRIES?></option>

                <option value=""></option>

<?php

    include_once __INCLUDE_CLASS_PATH."/class.Geography.php";

    $countries_a = explode(",",$countries);

    $states_a = explode(",",$states);

    $cities_a = explode(",",$cities);



    $CountriesList = Geography::getCountriesList();

    foreach ($CountriesList as $countryrow)

    {

        $selected = (in_array("'".$countryrow->gcn_countryid."'",$countries_a))?" selected":"";

        echo '<option value='.$countryrow->gcn_countryid.$selected.'>'.htmlspecialchars($countryrow->gcn_name).'</option>';

    }

?>

              </select> </td>

          </tr>

          <tr class="tdodd" >

            <td align="left" valign="top">

              <?=SEARCH_STATE?>

              <br> <span class="small"> </span> </td>

            <td align="left">

<?php

    $result = "";

    foreach ($countries_a as $curr_country) {
        $c =  new Geography;
        $country = $c->getCountryByID(str_replace("'","",$curr_country));

        if ($country) {

            $result .= "<OPTION value=0>-- ".htmlspecialchars($country->gcn_name)." --</OPTION>";

        }

        $StatesList = Geography::getStatesList(str_replace("'","",$curr_country));

        foreach ($StatesList as $staterow)

        {

            $selected = (in_array("'".$staterow->gst_stateid."'",$states_a))?" selected":"";

            $result .= "<OPTION value=".$staterow->gst_stateid.$selected.">".htmlspecialchars($staterow->gst_name)."</OPTION>";

        }

    }

    if ($result != "") {

?>

            <select class="inputl" name="lstState[]" size="4" multiple id="lstState" onchange="sendCityRequest(); return false;">

<?php

        echo $result;

    } else {

?>

            <select disabled class="inputl" name="lstState[]" size="4" multiple id="lstState" onchange="sendCityRequest(); return false;">

                <option value="0"><?=SEARCH_ALLSTATES?></option>

<?php

    }

?>

              </select> <span class="small"> <br>

              <?=SEARCH_STATE_NOTE?>

              </span> </td>

          </tr>

          <tr class="tdeven" >

            <td align="left" valign="top">

              <?=SEARCH_CITY?>

            </td>

            <td align="left">

<?php

    $result = "";

    foreach ($countries_a as $curr_country) {
        $c = new Geography;
        $country = $c->getCountryByID(str_replace("'","",$curr_country));

        if ($country) {

            $result .= "<OPTION value=0>-- ".htmlspecialchars($country->gcn_name)." --</OPTION>";

        }

        $CitiesList = $c->getCitiesList(str_replace("'","",$curr_country),0);

        foreach ($CitiesList as $cityrow)

        {

            if ($cityrow->gct_name == "Unspecified") continue;

            $selected = (in_array("'".$cityrow->gct_cityid."'",$cities_a))?" selected":"";

            $result .= "<OPTION value=".$cityrow->gct_cityid.$selected.">".htmlspecialchars($cityrow->gct_name)."</OPTION>";

        }

        foreach ($states_a as $curr_state) {

            if ($c->isStateInCountry(str_replace("'","",$curr_state),str_replace("'","",$curr_country))) {

                $state = $c->getStateByID(str_replace("'","",$curr_state));

                if ($state) {

                    $result .= "<OPTION value=0>-- ".htmlspecialchars($state->gst_name)." --</OPTION>";

                }

                $CitiesList = $c->getCitiesList(str_replace("'","",$curr_country),str_replace("'","",$curr_state));

                foreach ($CitiesList as $cityrow)

                {

                    $selected = (in_array("'".$cityrow->gct_cityid."'",$cities_a))?" selected":"";

                    $result .= "<OPTION value=".$cityrow->gct_cityid.$selected.">".htmlspecialchars($cityrow->gct_name)."</OPTION>";

                }

            }

        }

    }

    if ($result != "") {

?>

            <select class="inputl" name="lstCity[]" size="4" multiple id="lstCity">

<?php

        echo $result;

    } else {

?>

            <select disabled class="inputl" name="lstCity[]" size="4" multiple id="lstCity">

                <option value="0"><?=SEARCH_ALLCITIES?></option>

<?php

    }

?>

              </select> <span class="small"> <br>

              <?=SEARCH_CITY_NOTE?>

              </span></td>

          </tr>

              <? } else { ?>

          <script language="javascript" src="<?=$CONST_LINK_ROOT?>/geography.js"></script>

          <tr class="tdeven" >

            <td align="left" valign="top">

              <?=SEARCH_COUNTRY?>

            </td>

            <td align="left"><select class="inputl" id="lstCountry" name="lstCountry[]" size="4" multiple onChange="onCountryListChange('FrmSearch', 'lstCountry[]', 'lstState[]', 'lstCity[]');">

                <option value="0">

                <?=SEARCH_ALLCOUNTRIES?>

                </option>

                <option value=""></option>

              </select></td>

          </tr>

          <tr class="tdodd" >

            <td align="left" valign="top">

              <?=SEARCH_STATE?>

              <br> <span class="small"> </span> </td>

            <td align="left"> <select class="inputl" id="lstState" name="lstState[]" size="4" multiple onChange="onStateListChange('FrmSearch', 'lstCountry[]', 'lstState[]', 'lstCity[]');">

                <option value="0">

                <?=SEARCH_ALLSTATES?>

                </option>

                <option value=""></option>

              </select> <span class="small"> <br>

              <?=SEARCH_STATE_NOTE?>

              </span> </td>

          </tr>

          <tr class="tdeven" >

            <td align="left" valign="top">

              <?=SEARCH_CITY?>

            </td>

            <td align="left"><select class="inputl"  id="lstCity" name="lstCity[]" size="4" multiple onChange="onCityListChange('FrmSearch', 'lstCity[]');">

                <option value="0">

                <?=SEARCH_ALLCITIES?>

                </option>

                <option value=""></option>

              </select> <span class="small"> <br>

              <?=SEARCH_CITY_NOTE?>

              </span></td>

          </tr>

          <script language="javascript">

              initialize('FrmSearch', 'lstCountry[]', 'lstState[]', 'lstCity[]', new Array(<?=$countries?>), new Array(<?=$states?>), new Array(<?=$cities?>));

          </script>

              <? } ?>

          <? } else {?>

          <tr class="tdeven" >

            <td align="left" valign="top">

              <?=SEARCH_COUNTRY?>

            </td>

            <td align="left"><select class="inputl" id="lstCountry" name="lstCountry[]" size="4" multiple>

                <option value="0">

                <?=SEARCH_ALLCOUNTRIES?>

                </option>

                <option value=""></option>

                <?= country_state_list($countries);?>

              </select></td>

          </tr>

          <? } ?>

          <?php

    if ($CONST_ZIPCODES=='Y') {

        print("<tr class='tdodd'>

        <td align='left'>".SEARCH_USONLY." - ".SEARCH_MATCHUP."</td>

        <td align='left'><input type='text' name='txtMiles' size='5' maxlength='6' class='input'>&nbsp;<select name=lstUnit class='inputf'>

            <option value=\"mile\"> miles

            <option value=\"km\"> km

        </select>

        </td>

        </tr>

        <tr class='tdeven'>

        <td align='left'>

            ".SEARCH_MILES."</td>

        <td align='left'><input type='text' name='txtZipcode' size='10' maxlength='5' class='input'></td>

       </tr>");

     }

?>

          <tr class="tdodd" >

            <td colspan="2" align="left"> <input name="onlyOnline" type="checkbox" value="ON">

              <?=SEARCH_ONLY_ONLINE?>

            </td>

          </tr>

          <tr class="tdeven" >

            <td colspan="2" align="left"> <input name="withPicture" type="checkbox" value="ON">

              <?=SEARCH_WITH_PICTURE?>

            </td>

          </tr>

          <tr >

            <td colspan="2" align="center" class="tdfoot"> <input type="submit" name="SEARCH" class="button" value="<?php echo BUTTON_SEARCH ?>"></td>

          </tr>

          <tr align="left" >

            <td colspan="2" class="tdhead">

              <?=SEARCH_ADVANCED?>

            </td>

          </tr>

        <?foreach ($aSearchFileds as $field) {

        if ($field['field']){?>

          <tr class="tdodd" >

            <td align="left" valign="top" colspan=2 class=tdunderline > <div id=<?=$field['name']?>_up style="float:left;padding-right:10px;">

                <a href="#" class="switch" onClick="switchBlock('<?=$field['name']?>','<?=$block?>');return false;">[+]</a>

              </div>

              <div id=<?=$field['name']?>_down style="display:none;float:left;padding-right:10px;">

                <a href="#" class="switch" onClick="switchBlock('<?=$field['name']?>','<?=$block?>');return false;">[-]</a>

              </div>

              <?=constant($field['label'])?>

            </td>

          </tr>

          <tr class="tdodd"  id="<?=$field['name']?>" style="display:none">

            <td align="left" valign="top">&nbsp; </td>

            <td align="left" valign="top">

              <?=populate_checks($field['option'],'base','srch',${"sql_".$field['name']},$field['name'].'[]')?>

            </td>

          </tr>

          <script language=javascript>document.getElementById('<?=$field['name']?>').disabled = true;</script>

        <?}}?>

          <tr class="tdodd" >

            <td align="left">

              <?=SEARCH_MINHEIGHT?>

            </td>

            <td align="left"><select class="inputf" name="lstMinHeight" size="1">

                <?

                $out = "";

                $prev = "";

                for ($cm=122; $cm<=230; $cm++) {

                    $in_inches = round($cm/2.54);

                    $in_feets = floor($cm/30.48);

                    if ($in_inches-$in_feets*12 == 12) $in_feets++;

                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";

                    if (isset($sql_search->sea_minheight)) {

                        $selected = ($cm == $sql_search->sea_minheight) ? " SELECTED" : "";

                    }

                    else {

                        $selected = ($cm == 122) ? " SELECTED" : "";

                    }

                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';

                    $prev = $cur_i;

                }

                echo $out;

                ?>

              </select></td>

          </tr>

          <tr class="tdeven" >

            <td align="left" valign="top">

              <?=SEARCH_MAXHEIGHT?>

            </td>

            <td align="left" valign="top"> <select class="inputf" name="lstMaxHeight" size="1">

                <?

                $out = "";

                $prev = "";

                for ($cm=122; $cm<=230; $cm++) {

                    $in_inches = round($cm/2.54);

                    $in_feets = floor($cm/30.48);

                    if ($in_inches-$in_feets*12 == 12) $in_feets++;

                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";

                    if (isset($sql_search->sea_maxheight)) {

                        $selected = ($cm == $sql_search->sea_maxheight) ? " SELECTED" : "";

                    }

                    else {

                        $selected = ($cm == 230) ? " SELECTED" : "";

                    }

                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';

                    $prev = $cur_i;

                }

                echo $out;

                ?>

              </select></td>

          </tr>

          <tr class="tdodd" >

            <td align="left">

              <?=SEARCH_PERPAGE?>

            </td>

            <? $pagesize=$option_manager->GetValue('page_size'); ?>

			<td align="left"><select class="input" size="1" name="SHOWNUM">

                <option value="8" <? if ($pagesize=='8') echo 'SELECTED'; ?>>8</option>

                <option value="16" <? if ($pagesize=='16') echo 'SELECTED'; ?>>16</option>

                <option value="24" <? if ($pagesize=='24') echo 'SELECTED'; ?>>24</option>

                <option value="32" <? if ($pagesize=='32') echo 'SELECTED'; ?>>32</option>

                <option value="48" <? if ($pagesize=='48') echo 'SELECTED'; ?>>48</option>

                <option value="56" <? if ($pagesize=='56') echo 'SELECTED'; ?>>56</option>

              </select></td>

          </tr>

          <tr class="tdeven" >

            <td align="left">

              <?=SEARCH_ORDER?>

            </td>

            <td align="left"><select class="input" name="lstOrder" size="1">

                <option selected value="Latest First">

                <?=SEARCH_LATEST?>

                </option>

                <!--                 <option value="Online now">

                <?=SEARCH_ONLINE?>

                </option>

                <option value="Photos only">

                <?=SEARCH_PHOTOS?>

                </option> -->

                <option value="Premium members">

                <?=SEARCH_PREMIUM?>

                </option>

                <option value="Since last visit">

                <?=SEARCH_LASTVISIT?>

                </option>

                <option value="Order by Age">

                <?=SEARCH_BYAGE?>

                </option>

              </select> <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/help/hsearch2.php','Help','width=250,height=375')"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?= $CONST_IMAGE_LANG ?>/help_but.gif'></a>

            </td>

          </tr>

          <tr class="tdodd" >

            <td align="left">

              <?=SEARCH_RESULT_AS?>

            </td>

            <td align="left"><select class="input" name="lstResultAs" size="1">

                <option value="gallery">

                <?=SEARCH_RESULT_AS_GALLERY?>

                </option>

                <option selected value="list">

                <?=SEARCH_RESULT_AS_LIST?>

                </option>

              </select></td>

          </tr>

          <tr>

            <td colspan="2" align="center" class="tdfoot"> <input name="SEARCH" type="submit" class="button" value="<?php echo BUTTON_SEARCH ?>"></td>

          </tr>

          <tr>

            <td colspan="2" align="left" valign="top" class="tdhead">

              <?=SEARCH_USERNAME?>

            </td>

          </tr>

          <tr class="tdodd">

            <td align="left" >

              <?=GENERAL_USERNAME?>

            </td>

            <td align="left" > <input type="text" class="inputl" name="txtHandle" size="20"></td>

          </tr>

          <tr align="center"  >

            <td colspan="2" class="tdfoot"> <input name="P_SEARCH" type="submit" class="button" value="<?php echo BUTTON_SEARCH ?>">

            </td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>