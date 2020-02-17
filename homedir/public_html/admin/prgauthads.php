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

# Name:                 prgauthads.php

#

# Description:  Administrator advert authorisation processing

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include_once('../validation_functions.php'); 

include('../session_handler.inc');

include('../pop_lists.inc');

include('../error.php');

include('../functions.php');

include('../message.php');

include('permission.php');



$mode=$_GET['mode'];

# process an authorisation

if ($mode=='next') {

    $txtComment=sanitizeData($_POST['txtComment'], 'xss_clean');

    $txtMyComment=sanitizeData($_POST['txtMyComment'], 'xss_clean'); 

    $txtTitle=sanitizeData($_POST['txtTitle'], 'xss_clean'); 

    $rdoApprove=sanitizeData($_POST['rdoApprove'], 'xss_clean'); 

    $hiddenuserid=sanitizeData($_POST['hiddenuserid'], 'xss_clean'); 

    $hiddenname=sanitizeData($_POST['hiddenname'], 'xss_clean');

    $hiddenemail=sanitizeData($_POST['hiddenemail'], 'xss_clean'); 

    $offset=sanitizeData($_POST['offset'], 'xss_clean');  

    $txtComment=mysqli_real_escape_string($globalMysqlConn, $txtComment);

    $txtTitle=mysqli_real_escape_string($globalMysqlConn, $txtTitle);

    $txtMyComment =mysqli_real_escape_string($globalMysqlConn, $txtMyComment );

    if ($rdoApprove=='Approve') {

            # approved will show up in search

            $query="update adverts set adv_title = '$txtTitle', adv_comment = '$txtComment', adv_approved = 1 where adv_userid=$hiddenuserid";

            if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



            $query="update mymatch set mym_comment = '$txtMyComment' where mym_userid=$hiddenuserid";

            if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



            $data['ReceiverName'] = $hiddenname;

            $data['CompanyName'] = $CONST_COMPANY;

            $data['Url'] = $CONST_URL;

            $data['SupportEmail'] = $CONST_SUPPMAIL;



            list($type,$message) = getTemplateByName("Approve_Mail",$data,getDefaultLanguage($hiddenuserid));

            send_mail ("$hiddenemail", "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHADS_APR, $message,$type,"ON");

            $admin_action = 'Y';

            include("../generate_profile.php");

    } elseif ($rdoApprove=='Reject') {



    		$reason=stripslashes(sanitizeData($_POST['reason'], 'xss_clean'));

			if (empty($reason)){

				$error_message=REJECT_REASON;

				error_page($error_message,GENERAL_USER_ERROR);

			}



            # rejected will not show up in search or for approval until user amends

            $query="update adverts set adv_title = '$txtTitle', adv_comment = '$txtComment',adv_approved = 2, adv_rejectreason = '".mysqli_real_escape_string($globalMysqlConn, $reason)."' where adv_userid=$hiddenuserid";

            if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

            $query="update mymatch set mym_comment = '$txtMyComment' where mym_userid=$hiddenuserid";

            if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

            $data['ReceiverName'] = $hiddenname;

            $data['CompanyName'] = $CONST_COMPANY;

            $data['Url'] = $CONST_URL;

            $data['Reason'] = $reason;

            $data['SupportEmail'] = $CONST_SUPPMAIL;



            list($type,$message) = getTemplateByName("Reject_Mail",$data,getDefaultLanguage($hiddenuserid));

            send_mail ("$hiddenemail", "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHADS_UNAPR, $message,$type,"ON");



    } elseif ($rdoApprove=='Skip') {

        $offset++;

    }

}

# select advert data

//Time operations

$curr_time = time();

$mod_time = $curr_time - BLOCK_PERIOD_AVAILABLE;

$modified_time = date("Y-m-d H:i:s", $mod_time);

//EOF Time operations



if (!$offset) $offset=0;

$blocking = mysqli_query($globalMysqlConn,"LOCK TABLES adverts WRITE READ"); // Lock table for Read and Write

//$result = mysqli_query("SELECT *, mem_email, mem_confirm

//                       FROM adverts

//                           LEFT JOIN members ON (adv_userid=mem_userid)

//                           WHERE block_time<='$modified_time' AND adv_approved=0 AND mem_confirm = 1 LIMIT $offset,1",$link);

$result = mysqli_query($globalMysqlConn, "SELECT *, mem_email, mem_confirm

                       FROM adverts

                           LEFT JOIN members ON (adv_userid=mem_userid)

                           WHERE adv_approved=0 AND mem_confirm = 1 LIMIT $offset,1");

$TOTAL = mysqli_num_rows($result);

# if nothing is returned then show error otherwise get data

if ($TOTAL < 1) {

        $error_message=PRGAUTHADS_TEXT;

        display_page($error_message,PRGAUTHADS_TEXT1);

} else {

        $sql_array = mysqli_fetch_object($result);

        $update_blocktime = mysqli_query($globalMysqlConn,"UPDATE adverts SET block_time = NOW() WHERE adv_userid = '".$sql_array->adv_userid."'");

}

$end_blocking = mysqli_query($globalMysqlConn,"UNLOCK TABLES"); //Unlock Table

# place advert data into variables for display

$advuser=$sql_array->adv_userid;

$advname=$sql_array->adv_username;

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

$eyecolor=$sql_array->adv_eyecolor;

$haircolor=$sql_array->adv_haircolor;

$height=$sql_array->adv_height;

$advtitle=$sql_array->adv_title;

$employment=$sql_array->adv_profession;

$income=$sql_array->adv_income;

$education=$sql_array->adv_education;

$picture=$CONST_LINK_ROOT.$sql_array->adv_picture;

$email=$sql_array->mem_email;

$comment=$sql_array->adv_comment;

$comment=stripslashes($comment);

$advtitle=stripslashes($advtitle);

if ($sql_array->adv_seekmen=='Y') {$seekmen='checked';} else {$seekmen='';}

if ($sql_array->adv_seekwmn=='Y') {$seekwmn='checked';} else {$seekwmn='';}

if ($sql_array->adv_seekcpl=='Y') {$seekcpl='checked';} else {$seekcpl='';}

include($CONST_INCLUDE_ROOT.'/languages/has_profile_'.$_SESSION['lang_id'].'.inc.php');

$result=mysqli_query($globalMysqlConn,"SELECT * FROM mymatch WHERE mym_userid=$advuser") or die(mysqli_error());

if (mysqli_num_rows($result) > 0) $sql_they=mysqli_fetch_object($result);

if ($sql_they->mym_gender=='M') $mygender= PRGSTATS_MALES;

elseif ($sql_they->mym_gender=='F') $mygender= PRGSTATS_FEMALES;

elseif ($sql_they->mym_gender=='C') $mygender= PRGSTATS_COUPLE;

$area = 'member';

?>

<?=$skin->ShowHeader($area)?>

<?/*<script language="javascript" src="geography.js"></script>*/?>



  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo APPROVE_ADS_SECTION_NAME ?> </td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgauthads.php?mode=next' name="FrmAuthorise">

          <input type="hidden" name="offset" value="<?=$offset?>" />

          <tr>

            <td valign="top" class="tdhead" colspan="4"><?php print("$advname"); ?></td>

          </tr>

            <input type='hidden' name="hiddenuserid" value="<?php print("$advuser"); ?>">

            <input type='hidden' name="hiddenemail" value="<?php print("$email"); ?>">

            <input type='hidden' name="hiddenname" value="<?php print("$advname"); ?>">

          <? if ($GEOGRAPHY_JAVASCRIPT) {

                 if ($GEOGRAPHY_AJAX) { ?>

<script src="<?=CONST_LINK_ROOT?>/moo.ajax/prototype.lite.js"></script>

<script src="<?=CONST_LINK_ROOT?>/moo.ajax/moo.ajax.js"></script>

<script src="<?=CONST_LINK_ROOT?>/ajax_lib.js.php"></script>

          <tr class="tdodd" >

            <td  align="left"><?php echo GENERAL_COUNTRY?></td>

            <td > <select class="input" name="lstCountry" id="lstCountry" size="1"  tabindex='13' onchange="sendStateRequest(this.options[this.selectedIndex].value);sendCityRequest(this.options[this.selectedIndex].value,0); return false;">

                <option value="0">-- <?php echo GENERAL_CHOOSE?> --</option>

                <option value=""></option>

<?php

include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
$geo = new Geography;
$CountriesList = $geo->getCountriesList();

foreach ($CountriesList as $countryrow)

{

    $selected = ($country == $countryrow->gcn_countryid)?' selected':'';

    echo '<option value='.$countryrow->gcn_countryid.$selected.'>'.htmlspecialchars($countryrow->gcn_name).'</option>';

}

?>

              </select> </td>

            <td  align="left"><?php echo GENERAL_STATE?></td>

            <td width="272">

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

            <select <?=$disabled?> class="input" name="lstState" id="lstState" size="1"  tabindex='14' onchange="sendCityRequest(document.getElementById('lstCountry').value,this.value); return false;">

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
$geoc = new Geography;
    $CitiesList = $geoc->getCitiesList($country,$state);

    foreach ($CitiesList as $cityrow)

    {

        $selected = ($city == $cityrow->gct_cityid)?' selected':'';

        $result .= "<OPTION value=".$cityrow->gct_cityid.$selected.">".htmlspecialchars($cityrow->gct_name)."</OPTION>";

    }

}

$disabled = ($result != "")?"":" disabled";

?>

            <select <?=$disabled?> class="input" name="lstCity" id="lstCity" size="1" tabindex='15'>

                <option value="0">- <?php echo GENERAL_CHOOSE?> -</option>

                <?=$result?>

            </select></td>

          </tr>

              <? } else { ?>

          <script language="javascript" src="<?=$CONST_LINK_ROOT?>/geography.js"></script>



          <tr class="tdodd">

            <td  align="left"><?php echo GENERAL_COUNTRY?></td>

            <td > <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1" onChange="onCountryListChange('FrmAuthorise', 'lstCountry', 'lstState', 'lstCity');">

                <option value="0" selected></option>

              </select> </td>

            <td  align="left"><?php echo GENERAL_STATE?></td>

            <td > <select class="input" name="lstState" id="lstState" size="1" tabindex="1" onChange="onStateListChange('FrmAuthorise', 'lstCountry', 'lstState', 'lstCity');">

                <option value="0" selected></option>

              </select>

            </td>

          </tr>

          <tr class="tdeven">

            <td  align="left"><?php echo GENERAL_CITY?></td>

            <td > <select class="input" name="lstCity" id="lstCity" size="1" tabindex="1" onChange="onCityListChange('FrmAuthorise', 'lstCity');">

                <option value="0" selected></option>

              </select> </td>

            <td  align="left">&nbsp;</td>

            <td >&nbsp;</td>

          </tr>

          <script language="javascript">

                                initialize('FrmAuthorise', 'lstCountry', 'lstState', 'lstCity', new Array('<?=$country?>'), new Array('<?=$state?>'), new Array('<?=$city?>'));

                        </script>

              <? } ?>

<? } else { ?>

          <tr class="tdeven" >

            <td  align="left"><?php echo GENERAL_COUNTRY?>/<?php echo GENERAL_STATE?></td>

            <td >

                <select class="input" name="lstCountry" id="lstCountry" size="1" tabindex="1">

                    <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>

                    <?= country_state_list($country.";".($state?$state:""));?>

                </select>

            </td>

            <td  align="left"><?php echo GENERAL_CITY?></td>

            <td  width="272">

                <input type=text class="input" name=txtLocation value="<?=$location?>">

            </td>

          </tr>

<?}?>

          <tr class="tdodd">

            <td  align="left"><?php echo OPTION_SEEKING?></td>

            <td > <select name="lstSeeking" size="1" class="input" tabindex="3">

                <?php populate_lists('SKG','base','adv',$seeking); ?>

              </select></td>

            <td  align="left"><?php echo OPTION_BODY_TYPE?></td>

            <td > <select name="lstBodyType" size="1" class="input" tabindex="4">

                <?php populate_lists('BDY','base','adv',$bodytype); ?>

              </select></td>

          </tr>

          <tr class="tdeven">

            <td  align="left"><?php echo ADVERTISE_HEIGHT?></td>

            <td > <select name="lstHeight" size="1" class="input" tabindex="5">

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

            <td > <select name="lstChildren" size="1" class="input" tabindex="6">

                <?php populate_lists('CHL','base','adv',$children); ?>

              </select></td>

          </tr>

          <tr class="tdodd">

            <td  align="left"><?php echo OPTION_SMOKER?></td>

            <td > <select name="lstSmoker" size="1" class="input" tabindex="7">

                <?php populate_lists('SMK','base','adv',$smoker); ?>

              </select></td>

            <td  align="left"><?php echo OPTION_RELIGION?></td>

            <td > <select name="lstReligion" size="1" class="input" tabindex="8">

                <?php populate_lists('RLG','base','adv',$religion); ?>

              </select></td>

          </tr>

          <tr class="tdeven">

            <td  align="left"><?php echo OPTION_MARITAL?></td>

            <td > <select name="lstMarital" size="1" class="input" tabindex="9">

                <?php populate_lists('MRT','base','adv',$marital); ?>

              </select></td>

            <td  align="left"><?php echo OPTION_ETHNICITY?></td>

            <td > <select name="lstEthnicity" size="1" class="input" tabindex="10">

                <?php populate_lists('ETH','base','adv',$ethnicity); ?>

              </select></td>

          </tr>

          <tr class="tdodd">

            <td  align="left"><?php echo OPTION_EDUCATION?></td>

            <td > <select class="input" name="lstEducation" size="1" tabindex="11">

                <?php populate_lists('EDU','base','adv',$education); ?>

              </select></td>

            <td  align="left"><?php echo OPTION_EMPLOYMENT?></td>

            <td > <select class="input" name="lstEmployment" size="1" tabindex="12">

                <?php populate_lists('EMP','base','adv',$employment); ?>

              </select></td>

          </tr>

          <tr class="tdeven">

            <td  align="left"><?php echo OPTION_INCOME?></td>

            <td > <select class="input" name="lstIncome" size="1" tabindex="13">

                <?php populate_lists('INC','base','adv',$income); ?>

              </select></td>

            <td  align="left"><?php echo OPTION_DRINK?></td>

            <td > <select name="lstDrink" size="1" class="input" tabindex="14">

                <?php populate_lists('DNK','base','adv',$drink); ?>

              </select></td>

          </tr>

          <tr class="tdeven">

            <td  align="left"><?php echo OPTION_EYE_COLOR?></td>

            <td > <select class="input" name="lstEyecolor" size="1" tabindex="15">

                <?php populate_lists('EYE','base','adv',$eyecolor); ?>

              </select></td>

            <td  align="left"><?php echo OPTION_HAIR_COLOR?></td>

            <td > <select name="lstHaircolor" size="1" class="input" tabindex="16">

                <?php populate_lists('HAR','base','adv',$haircolor); ?>

              </select></td>

          </tr>

          <tr>

            <td colspan="4"  align="left" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="4"  align="left" class="tdhead"> <input type="checkbox" name="chkSeekmen" value="men" tabindex="11" <?php print($seekmen); ?>>

              <?php echo ADVERTISE_SEEK_M?>&nbsp; <input type="checkbox" name="chkSeekwmn" value="wmn" tabindex="12" <?php print($seekwmn); ?>>

              <?php echo ADVERTISE_SEEK_W?>&nbsp;

              <!--

              <input type="checkbox" name="chkSeekcpl" value="cpl" tabindex="15" <?php print($seekcpl); ?>> <?php echo ADVERTISE_SEEK_C?>&nbsp;

              -->

           </td>

          </tr>

          <tr class="tdeven">

            <td  align="left"><?php echo PRGAMENDAD_TITLE?></td>

            <td colspan="3" > <input type="text" class="input" name="txtTitle" size="30" maxlength='30' tabindex="15" value="<?php print("$advtitle"); ?>"></td>

          </tr>

          <tr class="tdodd">

            <td  align="left" ><?php echo ADMINMAIL_MESS?></td>

            <td colspan="3" > <textarea  class="inputl"rows="10" name="txtComment" cols="59" tabindex="15"><?php print($comment); ?></textarea></td>

          </tr>

          <tr class="tdeven">

            <td colspan="4"  align="left" ><?php print("$personality"); ?></td>

          </tr>

          <tr>

            <td colspan="4"  align="left" class="tdfoot">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="4"  align="left" class="tdhead"><b><?php echo PRGRETUSER_MYMATCH?></b></td>

          </tr>

          <tr class="tdodd">

            <td  align="left"><?php echo PRGRETUSER_GENDER?></td>

            <td ><?php echo $mygender ?></td>

            <td  align="left">

              <?=PRGRETUSER_SMOKER?>

            </td>

            <td ><?php echo $sql_they->mym_smoker ?></td>

          </tr>

          <tr class="tdeven">

            <td  align="left">

              <?=PRGRETUSER_AGES?>

            </td>

            <td ><?php echo $sql_they->mym_agemin?> - <?php echo $sql_they->mym_agemax ?></td>

            <td  align="left">

              <?=PRGRETUSER_BODYTYPE?>

            </td>

            <td ><?php echo $sql_they->mym_bodytype ?></td>

          </tr>

          <tr class="tdodd">

            <td  align="left">

              <?=PRGRETUSER_HEIGHT?>

            </td>

            <td >

              <?php



                for ($cm=122; $cm<=232; $cm++) {

                    $in_inches = round($cm/2.54);

                    $in_feets = floor($cm/30.48);

                    if ($in_inches-$in_feets*12 == 12) $in_feets++;

                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";

                    if ($sql_they->mym_minheight == $cm) {

                        echo $cur_i.' ('.$cm.ADVERTISE_CM.')';

					}

                } ?>

              -

              <?php



                for ($cm=122; $cm<=230; $cm++) {

                    $in_inches = round($cm/2.54);

                    $in_feets = floor($cm/30.48);

                    if ($in_inches-$in_feets*12 == 12) $in_feets++;

                    $cur_i = $in_feets."'".($in_inches-$in_feets*12)."&quot;";

                    if ($sql_they->mym_maxheight == $cm) {

                        echo $cur_i.' ('.$cm.ADVERTISE_CM.')';

					}

                } ?>

            </td>

            <td  align="left">

              <?=PRGRETUSER_RELATIONSHIP?>

            </td>

            <td ><?php echo $sql_they->mym_relationship ?></td>

          </tr>

          <tr class="tdeven">

            <td  align="left" >

              <?=PRGRETUSER_COMMENT?>

            </td>

            <td colspan="3" class="tdeven" > <textarea  class="inputl"rows="10" name="txtMyComment" cols="59" tabindex="15"><?php echo stripslashes($sql_they->mym_comment); ?></textarea></td>

          </tr>

          <tr align="center" class="tdodd">

            <td colspan="4" > <input type="radio" name="rdoApprove" value="Approve">

              <?php echo AFF_AUTHORISE_APPROVE?>&nbsp; <input type="radio" name="rdoApprove" value="Reject" >

              <?php echo AFF_AUTHORISE_REJECT?> &nbsp; <input type="radio" name="rdoApprove" value="Skip" checked>

              <?php echo AFF_AUTHORISE_SKIP?><br> <?php echo AFF_AUTHORISE_REASON?>

              <input name='reason' type='text' class="inputl" size='30'></td>

          </tr>

          <tr align="center">

            <td colspan="4" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">

            </td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>