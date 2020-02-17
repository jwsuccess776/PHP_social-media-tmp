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
# Name:                 prgamendad.php
#
# Description:  Displays member advert for editing
#
# Version:               7.3
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('pop_lists.inc');
include('error.php');
include('functions.php');
include_once('validation_functions.php');

if (isset($_POST['avat'])) {
    $avatar_id =sanitizeData($_POST['avat'], 'xss_clean') ;   
} elseif (isset($_SESSION['post']['avatar'])) {
    $avatar_id = $_SESSION['post']['avatar'];
}

$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);

# retrieve the template
    $area = 'member';
# select advert data
$result = mysqli_query($conSting,"SELECT * FROM adverts WHERE adv_userid=$Sess_UserId");
$TOTAL = mysqli_num_rows($result);
# if nothing is returned then show error otherwise get data
# if advert does not exist go to create page
if ($TOTAL < 1) {
        header("Location: $CONST_LINK_ROOT/advertise.php");
        exit;
}
$sql_array = mysqli_fetch_object($result);
# place advert data into variables for display

$country=$sql_array->adv_countryid;
$state=$sql_array->adv_stateid;
$city=$sql_array->adv_cityid;
$location=$sql_array->adv_location;

$seeking=$sql_array->adv_seeking;
$bodytype=$sql_array->adv_bodytype;
$marital=$sql_array->adv_marital;
$ethnicity=$sql_array->adv_ethnicity;
$religion=$sql_array->adv_religion;
$children=$sql_array->adv_children;
$smoker=$sql_array->adv_smoker;
$drink=$sql_array->adv_drink;
$height=$sql_array->adv_height;
$advtitle=$sql_array->adv_title;
$employment=$sql_array->adv_profession;
$eyecolor=$sql_array->adv_eyecolor;
$haircolor=$sql_array->adv_haircolor;
$income=$sql_array->adv_income;
$education=$sql_array->adv_education;
$picture=$sql_array->adv_picture;
if ($sql_array->adv_seekmen=='Y') {$seekmen='checked';} else {$seekmen='';}
if ($sql_array->adv_seekwmn=='Y') {$seekwmn='checked';} else {$seekwmn='';}
if ($sql_array->adv_seekcpl=='Y') {$seekcpl='checked';} else {$seekcpl='';}
$comment=$sql_array->adv_comment;
$comment=stripslashes($comment);
$advtitle=stripslashes($advtitle);
if ($CONST_ZIPCODES=='Y') $zipcode=$sql_array->adv_zipcode;
/*
$country_res = mysqli_query("SELECT * FROM geo_country");
$state_res = mysqli_query("SELECT * FROM geo_state");
$city_res = mysqli_query("SELECT * FROM geo_city");
*/

$result=mysqli_query($conSting,"SELECT * FROM mymatch WHERE mym_userid=$Sess_UserId") or die(mysqli_error());
$sql_mymatch=mysqli_fetch_object($result);
// print_r($sql_mymatch);
// echo 'string';
// $sql_mymatch=stripslashes($sql_mymatch->mym_comment);
// print_r($sql_mymatch);
// die;
?>
<?=$skin->ShowHeader($area)?>
<form action="<?php echo $CONST_LINK_ROOT?>/prgamendad.php" name="gallery" method="post">
    <input type="hidden" name="avat" id="avat" value="">
</form>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo ADVERTISE_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td>
      <?=$text?>
    </td>
  </tr>
  <tr>
    <td>
        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgadvertise.php' name="FrmAdvert" onSubmit="return Validate_FrmAdvert('update')">
        <input type="hidden" name="avatar" id="avatar" value="<?=$avatar_id?>">
        <input name="mode" value="update" type="hidden">
          <?php
                        $sql_result = mysqli_query($conSting,"SELECT adv_rejectreason FROM adverts WHERE adv_userid = $Sess_UserId AND adv_approved = 2");
                        if(mysqli_num_rows($sql_result) > 0)
                        {
                                $reason = mysqli_fetch_object($sql_result);
                                $text = sprintf(PRGAMENDAD_TEXT3, $reason->adv_rejectreason);
                        }
                        else
                                $text = PRGAMENDAD_TEXT1;
          ?>
          <tr >
            <td colspan="4"  align="left" class="tdhead">&nbsp; </td>
          </tr>
          <? if ($GEOGRAPHY_JAVASCRIPT) {
                 if ($GEOGRAPHY_AJAX) { ?>
<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>
<script src="<?=CONST_LINK_ROOT?>/ajax_lib.js.php"></script>
          <tr class="tdodd" >
            <td align="left" width="25%"><?php echo GENERAL_COUNTRY?></td>
            <td width="25%"> <select class="input" name="lstCountry" id="lstCountry" size="1"   onchange="sendStateRequest(this.options[this.selectedIndex].value);sendCityRequest(this.options[this.selectedIndex].value,0); return false;">
                <option value="0">-- <?php echo GENERAL_CHOOSE?> --</option>
                <option value=""></option>
<?php
include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
$CountriesList = Geography::getCountriesList();
foreach ($CountriesList as $countryrow)
{
    $selected = ($country == $countryrow->gcn_countryid)?' selected':'';
    echo '<option value='.$countryrow->gcn_countryid.$selected.'>'.htmlspecialchars($countryrow->gcn_name).'</option>';
}
?>
              </select> </td>
            <td align="left" width="25%"><?php echo GENERAL_STATE?></td>
            <td width="25%">
<?php
$result = "";
if ($country) {
    $StatesList = Geography::getStatesList($country);
    foreach ($StatesList as $staterow)
    {
        $selected = ($state == $staterow->gst_stateid)?' selected':'';
        $result .= "<OPTION value=".$staterow->gst_stateid.$selected.">".htmlspecialchars($staterow->gst_name)."</OPTION>";
    }
}
$disabled = ($result != "")?"":" disabled";
?>
            <select <?=$disabled?> class="input" name="lstState" id="lstState" size="1"   onchange="sendCityRequest(document.getElementById('lstCountry').value,this.value); return false;">
                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>
                <?=$result?>
            </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo GENERAL_CITY?></td>
            <td colspan="3">
<?php
$result = "";
if ($country || $state) {
    $CitiesList = Geography::getCitiesList($country,$state);
    foreach ($CitiesList as $cityrow)
    {
        $selected = ($city == $cityrow->gct_cityid)?' selected':'';
        $result .= "<OPTION value=".$cityrow->gct_cityid.$selected.">".htmlspecialchars($cityrow->gct_name)."</OPTION>";
    }
}
$disabled = ($result != "")?"":" disabled";
?>
            <select <?=$disabled?> class="input" name="lstCity" id="lstCity" size="1" >
                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>
                <?=$result?>
            </select></td>
          </tr>
              <? } else { ?>
          <script language="javascript" src="geography.js"></script>
          <tr class="tdodd" >
            <td  align="left"><?php echo GENERAL_COUNTRY?></td>
            <td > <select class="input" name="lstCountry" id="lstCountry" size="1"  onchange="onCountryListChange('FrmAdvert', 'lstCountry', 'lstState', 'lstCity');">
                <option value="0" selected></option>
              </select> </td>
            <td  align="left"><?php echo GENERAL_STATE?></td>
            <td > <select class="input" name="lstState" id="lstState" size="1"  onchange="onStateListChange('FrmAdvert', 'lstCountry', 'lstState', 'lstCity');">
                <option value="0" selected></option>
              </select> </td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo GENERAL_CITY?></td>
            <td colspan="3" > <select class="input" name="lstCity" id="lstCity" size="1"  onchange="onCityListChange('FrmAdvert', 'lstCity');">
                <option value="0" selected></option>
              </select>  </td>
          </tr>
          <script language="javascript">
                        initialize('FrmAdvert', 'lstCountry', 'lstState', 'lstCity', new Array('<?=$country?>'), new Array('<?=$state?>'), new Array('<?=$city?>'));
          </script>
              <? } ?>
        <? } else {?>
          <tr class="tdeven" >
            <td  align="left"><?php echo GENERAL_COUNTRY?>/<?php echo GENERAL_STATE?></td>
            <td >
                <select class="input" name="lstCountry" id="lstCountry"  style="width:auto;" size="1" >
                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
                    <?= country_state_list($country.";".($state?$state:""));?>
                </select>
            </td>
            <td align="left"><?php echo GENERAL_CITY?></td>
            <td >
                <input type=text class="input" name=txtLocation value="<?=$location?>">
            </td>
          </tr>
          <?}?>

          <tr class="tdodd" >
            <td align="left"><?php echo OPTION_SEEKING?></td>
            <td > <select name="lstSeeking" size="1" class="input" >
                <?php populate_lists('SKG','base','adv',$seeking); ?>
              </select></td>
            <td align="left"><?php echo OPTION_BODY_TYPE?></td>
            <td > <select name="lstBodyType" size="1" class="input" >
                <?php populate_lists('BDY','base','adv',$bodytype); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo PRGAMENDAD_HEIGHT?></td>
            <td > <select name="lstHeight" size="1" class="input" >
                <option <?php if ($height == "Not stated") { print("selected");} ?> value="Not stated"><?php echo PRGAMENDAD_NOT_STATED?></option>
                <?
                $out = "";
                $prev = "";
                for ($cm=122; $cm<=230; $cm++) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $selected = ($cm == $height) ? " SELECTED" : "";
                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';
                    $prev = $cur_i;
                }
                echo $out;
                ?>
              </select></td>
            <td  align="left"><?php echo OPTION_CHILDREN?></td>
            <td > <select name="lstChildren" size="1" class="input" >
                <?php populate_lists('CHL','base','adv',$children); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left"><?php echo OPTION_EYE_COLOR?></td>
            <td > <select name="lstEyecolor" size="1" class="input" >
                <?php populate_lists('EYE','base','adv',$eyecolor); ?>
              </select></td>
            <td  align="left"><?php echo OPTION_HAIR_COLOR?></td>
            <td > <select name="lstHaircolor" size="1" class="input" >
                <?php populate_lists('HAR','base','adv',$haircolor); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left"><?php echo OPTION_SMOKER?></td>
            <td > <select name="lstSmoker" size="1" class="input" >
                <?php populate_lists('SMK','base','adv',$smoker); ?>
              </select></td>
            <td  align="left"><?php echo OPTION_RELIGION?></td>
            <td > <select name="lstReligion" size="1" class="input" >
                <?php populate_lists('RLG','base','adv',$religion); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo OPTION_MARITAL?></td>
            <td > <select name="lstMarital" size="1" class="input" >
                <?php populate_lists('MRT','base','adv',$marital); ?>
              </select></td>
            <td  align="left"><?php echo OPTION_ETHNICITY?></td>
            <td > <select name="lstEthnicity" size="1" class="input" >
                <?php populate_lists('ETH','base','adv',$ethnicity); ?>
              </select></td>
          </tr>
          <tr class="tdodd" >
            <td  align="left"><?php echo OPTION_EDUCATION?></td>
            <td > <select class="input" name="lstEducation" size="1" >
                <?php populate_lists('EDU','base','adv',$education); ?>
              </select></td>
            <td  align="left"><?php echo OPTION_EMPLOYMENT?></td>
            <td > <select class="input" name="lstEmployment" size="1" >
                <?php populate_lists('EMP','base','adv',$employment); ?>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td  align="left"><?php echo OPTION_INCOME?></td>
            <td> <select class="input" name="lstIncome" size="1" >
                <?php populate_lists('INC','base','adv',$income); ?>
              </select></td>
            <td  align="left"><?php echo ADVERTISE_DRINKING?></td>
            <td > <select class="input" name="lstDrink" size="1" >
                <?php populate_lists('DNK','base','adv',$drink); ?>
              </select></td>
          </tr>
          <?php
                                if ($CONST_ZIPCODES=='Y') {
                                  print("<tr class='tdodd'>
                                        <td align='left'>".ADVERTISE_ZIPCODE."</td>
                                        <td colspan='3'><input type='text' name='txtZipcode' size='15' maxlength='5' value='$zipcode' class='input'> (".ADVERTISE_USA.")</td>
                                  </tr>");
                                  }
                        ?>
          <tr >
            <td colspan="4" align="center" class="tdfoot"">&nbsp;</td>
          </tr>
          <tr >
            <td colspan="4" class="tdhead"">
              <input type="checkbox" name="chkSeekmen" value="men"  <?php print($seekmen); ?>>
              <?php echo PRGAMENDAD_SEEKING_MEN?>&nbsp;
              <input type="checkbox" name="chkSeekwmn" value="wmn"  <?php print($seekwmn); ?>>
              <?php echo PRGAMENDAD_SEEKING_WOMEN?>&nbsp;
              <input type="hidden" name="chkSeekcpl" value="cpl"  ></td>
          </tr>
          <tr class="tdeven" >
            <td align="left" ><?php echo PRGAMENDAD_TITLE?></td>
            <td  colspan="3" align="left"> <input type="text" class="inputl" name="txtTitle" size="30" maxlength='30'  value="<?php print("$advtitle"); ?>"></td>
          </tr>
          <tr class="tdodd" >
            <td align="left" valign="top" ><?php echo PRGAMENDAD_TEXT2?></td>
            <td  colspan="3" align="left"> <textarea  class="inputl"rows="8" name="txtComment" cols="59" ><?php print($comment); ?></textarea></td>
          </tr>

          <tr>
            <td colspan="4" class="tdfoot" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" class="tdhead" ><b><?php echo PRGRETUSER_MYMATCH?></b></td>
          </tr>
          <tr class="tdodd">
            <td ><?php echo PRGRETUSER_GENDER?></td>
            <td  colspan="3"> <select name="lstMySex" size="1" class="input"  >
                <option value='- Any -'>- <?php echo GENERAL_CHOOSE?> -</option>
                <option <?php if ($sql_mymatch->mym_gender=='M') print("selected");?> value="M"><?php echo SEX_MALE ?></option>
                <option <?php if ($sql_mymatch->mym_gender=='F') print("selected");?> value="F"><?php echo SEX_FEMALE ?></option>
              </select></td>
          </tr>
          <tr class="tdeven" >
            <td >
              <?=PRGRETUSER_AGES?>
            </td>
            <td colspan="3" class="tdeven" > <select name="txtMyFromAge" size="1" class="inputs"  >
                <?php
                        for ($i=18; $i < 100; $i++) {
                            if ($i == $sql_mymatch->mym_agemin) {
                                print("<option value='$i' selected>$i</option>");
                            } else {
                                    print("<option value='$i'>$i</option>");
                            }
                        }
                ?>
              </select>
              -
              <select class="inputs" size="1" name="txtMyToAge"  >
                <?php
                        for ($i=18; $i <= 99; $i++) {
                            if ($i == $sql_mymatch->mym_agemax) {
                                print("<option value='$i' selected>$i</option>");
                            } else {
                                print("<option value='$i'>$i</option>");
                            }
                        }
                ?>
              </select></td>
          </tr>
          <tr class="tdodd">
            <td >
              <?=PRGRETUSER_HEIGHT?>
            </td>
            <td  colspan="3"> <select class="inputs" name="lstMyMinHeight" size="1">
                <?
                $out = "";
                $prev = "";
                for ($cm=122; $cm<=232; $cm++) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $selected = ($cm == $sql_mymatch->mym_minheight) ? " SELECTED" : "";
                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';
                    $prev = $cur_i;
                }
                echo $out;
                ?>
              </select>
              -
              <select class="inputs" name="lstMyMaxHeight" size="1">
                <?
                $out = "";
                $prev = "";
                for ($cm=122; $cm<=230; $cm++) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    if (isset($sql_mymatch->mym_maxheight)) {
                        $selected = ($cm == $sql_mymatch->mym_maxheight) ? " SELECTED" : "";
                    }
                    else {
                        $selected = ($cm == 230) ? " SELECTED" : "";
                    }
                    if ($prev != $cur_i) $out .= '<option value="'.$cm.'"'.$selected.'>'.$cur_i.'('.$cm.ADVERTISE_CM.')'.'</option><br>';
                    $prev = $cur_i;
                }
                echo $out;
                ?>
              </select> </td>
          </tr>
          <tr class="tdeven">
            <td >
              <?=PRGRETUSER_SMOKER?>
            </td>
            <td > <select name="lstMySmoker" size="1" class="input" >
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('SMK','base','adv',$sql_mymatch->mym_smoker); ?>
              </select></td>
            <td >
              <?=PRGRETUSER_BODYTYPE?>
            </td>
            <td > <select class="input" name="lstMyBodyType" size="1" >
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('BDY','base','adv',$sql_mymatch->mym_bodytype); ?>
              </select> </td>
          </tr>
          <tr class="tdodd">
            <td >
              <?=PRGRETUSER_RELATIONSHIP?>
            </td>
            <td  colspan="3" class="tdodd"> <select class="input" name="lstMySeeking" size="1" >
                <option value="- Any -" selected>
                <?=SEARCH_ANY?>
                </option>
                <?php populate_lists('SKG','base','adv',$sql_mymatch->mym_relationship); ?>
              </select> </td>
          </tr>
          <tr class="tdeven">
            <td  valign="top">
              <?=PRGRETUSER_COMMENT?>
            </td>
            <td  colspan="3" class="tdeven"> <textarea  class="inputl"rows="8" name="txtMyComment" cols="59" ><?php echo stripslashes($sql_mymatch->mym_comment) ?></textarea>
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center"  valign="top" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button"></td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>