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
# Name:         prgretuser.php
#
# Description:  Returns individual member adverts from the search screen
#
# Version:      7.2
#
######################################################################
if ($option_manager->GetValue('authorisead') == 0 && $admin_action != 'Y') return "";
include($CONST_NETWORK_INCLUDE_ROOT.'/functions.php');
include_once(__INCLUDE_CLASS_PATH.'/class.StaticProfile.php');
ob_start();

$id = ($admin_action == 'Y') ? $hiddenuserid : $Sess_UserId;
$advuser=$userid=$id; // set for hasprofile
$area = 'guest';
# Select the main portion of the advert

$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);

$result=mysqli_query($conSting,"
    SELECT
        *,
        (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,
        mem_lastvisit
    FROM adverts
    LEFT JOIN members ON (adv_userid=mem_userid)
    LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)
    LEFT JOIN geo_city ON (adv_cityid = gct_cityid)
    LEFT JOIN geo_state ON (adv_stateid = gst_stateid)
    WHERE adv_userid = '$userid'"
    );
if (mysqli_num_rows($result) < 1){
    $error_message=PRGRETUSER_TEXT8;
    error_page($error_message,GENERAL_USER_ERROR);
}
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();
$adv->InitByObject(mysqli_fetch_object($result));
$adv->SetImage('medium');
$sql_array = $adv;
# check for a profile
include($CONST_INCLUDE_ROOT.'/languages/has_profile_'.$_SESSION['lang_id'].'.inc.php');

//$result=mysqli_query($conSting,"SELECT * FROM mymatch WHERE mym_userid=$userid") or die(mysqli_error($conSting));
$result=$db->get_row("SELECT * FROM mymatch WHERE mym_userid=$userid");
//if (mysqli_num_rows($result) > 0) 
    if(!is_null($result)){
    $sql_they=$result;
    if ($sql_they->mym_gender=='M') $mygender= PRGSTATS_MALES;
    elseif ($sql_they->mym_gender=='F') $mygender= PRGSTATS_FEMALES;
    elseif ($sql_they->mym_gender=='C') $mygender= PRGSTATS_COUPLE;
  //  $result=mysqli_query($conSting,"SELECT (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, adv_height, adv_bodytype, adv_seeking, adv_sex, adv_smoker FROM adverts WHERE adv_userid=$Sess_UserId") or die(mysqli_error($conSting));
    $result=$db->get_row("SELECT (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, adv_height, adv_bodytype, adv_seeking, adv_sex, adv_smoker FROM adverts WHERE adv_userid=$Sess_UserId");
    $sql_me=$result;
    #make the calculation
    $score=0;
} else {
    $theyscore=PRGRETUSER_NO_DATA;
}
?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td class="pageheader"><?php print("$sql_array->adv_username"); ?></td>
  </tr>
  <tr>
    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <tr >
          <td colspan="2" class="tdhead"><?php print(stripslashes("$sql_array->adv_title")); ?>&nbsp;</td>
        </tr>
        <tr>
          <td width="50%" align="center" valign="top" >
          <table width="100%" border="0" cellpadding="4" cellspacing="0">
              <tr>
                <td height="200" align="center" nowrap  class="retimage">
                  <?require ($CONST_INCLUDE_ROOT."/images.inc.php")?>
                </td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="tdhead"><?php echo PRGRETUSER_MESSAGE?> </td>
              </tr>
              <tr>
                <td><?php print $sql_array->adv_comment_full; ?></td>
              </tr>
              <?php print("$rowhead $personality $rowfoot"); ?>
            </table>

          </td>
          <td width="50%" align="center" valign="top"  >
            <table width="100%" border="0" cellpadding="4" cellspacing="0" >
              <tr>
                <td nowrap class="tdhead"><?php echo PRGRETUSER_DETAILS?></td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo ADVERTISE_AGE ?>:
                  </span>
                  <?php
                print($sql_array->age);
         ?>
                </td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo ADVERTISE_SIGN ?>:
                  </span>
                  <?php
                print(get_sign($sql_array->adv_dob));
         ?>
                </td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo CUPID_REGION?>: </span>
                  <?=$sql_array->adv_region?>
                </td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo GENERAL_CITY?>: </span>
                  <?php echo $sql_array->adv_location?> </td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo ADVERTISE_SEEKING?>:</span>
                  <?php echo $sql_array->adv_seeking; ?></td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo GENERAL_HEIGHT?>:</span>
                  <?php
                $cm = $sql_array->adv_height;
                if (is_numeric($cm)) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $height = $cur_i." (".$cm.ADVERTISE_CM.")";
                } else $height = PRGAMENDAD_NOT_STATED;
                print("$height");
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo OPTION_EYE_COLOR?>:</span>
                  <?php echo $sql_array->adv_eyecolor; ?></td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo OPTION_HAIR_COLOR?>:</span>
                  <?php echo $sql_array->adv_haircolor; ?></td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo OPTION_BODY_TYPE?>:</span>
                  <?php echo $sql_array->adv_bodytype; ?></td>
              </tr>
              <tr>
                <td nowrap ><span class="rettext"><?php echo OPTION_RELIGION?>:</span>
                  <?php echo $sql_array->adv_religion; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo OPTION_ETHNICITY?>:</span>
                  <?php echo $sql_array->adv_ethnicity; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo OPTION_SMOKER?>:</span>
                  <?php echo $sql_array->adv_smoker; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo ADVERTISE_DRINKING?>:</span>
                  <?php echo $sql_array->adv_drink; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo ADVERTISE_MARITAL?>:</span>
                  <?php echo $sql_array->adv_marital; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo ADVERTISE_CHILDREN?>:</span>
                  <?php echo $sql_array->adv_children; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo OPTION_EDUCATION?>:</span>
                  <?php echo $sql_array->adv_education; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo PRGRETUSER_PROFESSION?>:</span>
                  <?php echo $sql_array->adv_profession; ?></td>
              </tr>
              <tr >
                <td ><span class="rettext"><?php echo OPTION_INCOME?>:</span>
                  <?php echo $sql_array->adv_income; ?></td>
              </tr>
              <tr >
                <td >&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="center" valign="top"  ><table width="100%" border="0" cellspacing="0" cellpadding="4">
              <td class="tdhead"><?php echo PRGRETUSER_MYMATCH?></td>
              </tr>
              <tr>
                <td><span class="rettext"><?php echo PRGRETUSER_GENDER?>:</span>
                  <?php echo $mygender ?></td>
              </tr>
              <tr>
                <td><span class="rettext">
                  <?=PRGRETUSER_AGES?>
                  :</span><?php echo $sql_they->mym_agemin?> - <?php echo $sql_they->mym_agemax ?></td>
              </tr>
              <tr>
                <td><span class="rettext">
                  <?=PRGRETUSER_SMOKER?>
                  :</span><?php echo $sql_they->mym_smoker; ?></td>
              </tr>
              <tr>
                <td><span class="rettext">
                  <?=PRGRETUSER_RELATIONSHIP?>
                  :</span><?php echo $sql_they->mym_relationship; ?></td>
              </tr>
              <tr>
                <td><span class="rettext">
                  <?=PRGRETUSER_HEIGHT?>
                  :</span>
                  <?php
                $cm = $sql_they->mym_minheight;
                if (is_numeric($cm)) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $height = $cur_i." (".$cm.ADVERTISE_CM.")";
                } else $height = $sql_they->mym_minheight;
                print("$height");
                ?>
                  -
                  <?php
                $cm = $sql_they->mym_maxheight;
                if (is_numeric($cm)) {
                    $in_inches = round($cm/2.54);
                    $in_feets = floor($cm/30.48);
                    if ($in_inches-$in_feets*12 == 12) $in_feets++;
                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";
                    $height = $cur_i." (".$cm.ADVERTISE_CM.")";
                } else $height = $sql_they->mym_maxheight;
                print("$height");
                ?>
                </td>
              </tr>
              <tr>
                <td><span class="rettext">
                  <?=PRGRETUSER_BODYTYPE?>
                  :</span><?php echo $sql_they->mym_bodytype; ?></td>
              </tr>
            </table></td>
          <td align="center" valign="top"  ><table width="100%" border="0" cellspacing="0" cellpadding="4">
              <td class="tdhead">&nbsp;</td>
              </tr>
              <tr>
                <td><span class="rettext">
                  <?=PRGRETUSER_COMMENT?>
                  :</span><?php echo stripslashes(nl2br($sql_they->mym_comment)) ?></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
$buf = ob_get_contents();
ob_end_clean();
$name = ($admin_action == 'Y') ? $hiddenname : $Sess_UserName;
$st_profile = new StaticProfile($name);
$result = $st_profile->Save($buf);
?>