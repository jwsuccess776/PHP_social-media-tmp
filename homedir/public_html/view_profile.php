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
# Name:         view_profile.php
#
# Description:  Returns individual member adverts from the search screen
#
# Version:      7.2
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('functions.php');
include('error.php');
include(__INCLUDE_CLASS_PATH.'/class.StaticProfile.php');
include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";
$st_profile = new StaticProfile($Sess_UserName);

save_request();
$userid = $Sess_UserId;
$advuser=$userid; // set for hasprofile

# retrieve the template
$area = 'member';
//$conSting=@mysqli_connect(__CONST_DB_HOST,__CONST_DB_USER, __CONST_DB_PASS,__CONST_DB_NAME);
# Select the main portion of the advert
/*$result=mysqli_query($conSting,"
    SELECT
        *,
        (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,
        mem_lastvisit
    FROM adverts
    LEFT JOIN members ON (adv_userid=mem_userid)
    LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)
    LEFT JOIN geo_city ON (adv_cityid = gct_cityid)
    LEFT JOIN geo_state ON (adv_stateid = gst_stateid)
    WHERE adv_userid = $userid"
    );*/
$query="SELECT *,
        (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,
        mem_lastvisit
    FROM adverts
    LEFT JOIN members ON (adv_userid=mem_userid)
    LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)
    LEFT JOIN geo_city ON (adv_cityid = gct_cityid)
    LEFT JOIN geo_state ON (adv_stateid = gst_stateid)
    WHERE adv_userid = $userid";
$result=$db->get_row($query);

//if (mysqli_num_rows($result) < 1){
if(is_null($result)) {
    $error_message=PRGRETUSER_TEXT8;
    error_page($error_message,GENERAL_USER_ERROR);
}
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();
$adv->InitByObject($result);
$adv->SetImage('medium');
$sql_array = $adv;
# check for a profile
include($CONST_INCLUDE_ROOT.'/languages/has_profile_'.$_SESSION['lang_id'].'.inc.php');

# fetch the my match info
//$result=    mysqli_query($conSting,"SELECT * FROM mymatch WHERE mym_userid=$Sess_UserId") or die(mysqli_error($conSting));
$query="SELECT * FROM mymatch WHERE mym_userid=$Sess_UserId";
$result=$db->get_row($query);
//if (mysqli_num_rows($result) > 0) {
    if(!is_null($result)) {
    //$sql_me=mysqli_fetch_object($result);
     $sql_me=$result;

    #make the calculation
    $score=0;

    if ($sql_me->mym_gender == $sql_array->adv_sex) $score+=50;
    if (($sql_array->age >= $sql_me->mym_agemin) && ($sql_array->age <= $sql_me->mym_agemax)) $score+=10;
    if (($sql_array->adv_height >= $sql_me->mym_minheight) && ($sql_array->adv_height <= $sql_me->mym_maxheight)) $score+=10;
    if (($sql_array->adv_bodytype == $sql_me->mym_bodytype) OR ($sql_me->mym_bodytype=='Not stated')) $score+=10;
    if (($sql_array->adv_seeking == $sql_me->mym_relationship) OR ($sql_me->mym_mym_relationship=='Not stated')) $score+=10;
    if (($sql_array->adv_smoker == $sql_me->mym_smoker) OR ($sql_me->mym_smoker=='Not stated')) $score+=10;

        if ($score < 60) $myscore=PRGRETUSER_POOR;
        elseif ($score < 80) $myscore=PRGRETUSER_FAIR;
        elseif ($score < 100) $myscore=PRGRETUSER_GOOD;
} else {
        $myscore=PRGRETUSER_NO_DATA;
}

# fetch the thir match info
//$result=mysqli_query($conSting,"SELECT * FROM mymatch WHERE mym_userid=$userid") or die(mysqli_error($conSting));
$query="SELECT * FROM mymatch WHERE mym_userid=$userid";

$result=$db->get_row($query);
//if (mysqli_num_rows($result) > 0) {
    if(!is_null($result)) {
    //$sql_they=mysqli_fetch_object($result);
             $sql_they=$result;

    if ($sql_they->mym_gender=='M') $mygender= PRGSTATS_MALES;
    elseif ($sql_they->mym_gender=='F') $mygender= PRGSTATS_FEMALES;
    elseif ($sql_they->mym_gender=='C') $mygender= PRGSTATS_COUPLE;

    //$result=mysqli_query($conSting,"SELECT (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, adv_height, adv_bodytype, adv_seeking, adv_sex, adv_smoker FROM adverts WHERE adv_userid=$Sess_UserId") or die(mysqli_error($conSting));
    //$sql_me=mysqli_fetch_object($result);
    
    $sql_me=$db->get_row("SELECT (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, adv_height, adv_bodytype, adv_seeking, adv_sex, adv_smoker FROM adverts WHERE adv_userid=$Sess_UserId");

    #make the calculation
    $score=0;

    if ($sql_they->mym_gender == $sql_me->adv_sex) $score+=50;
    if (($sql_me->age >= $sql_they->mym_agemin) && ($sql_me->age <= $sql_they->mym_agemax)) $score+=10;
    if (($sql_me->adv_height >= $sql_they->mym_minheight) && ($sql_me->adv_height <= $sql_they->mym_maxheight)) $score+=10;
    if (($sql_me->adv_bodytype == $sql_they->mym_bodytype) OR ($sql_they->mym_bodytype=='Not stated')) $score+=10;
    if (($sql_me->adv_seeking == $sql_they->mym_relationship) OR ($sql_they->mym_relationship=='Not stated')) $score+=10;
    if (($sql_me->adv_smoker == $sql_they->mym_smoker) OR ($sql_they->mym_smoker=='Not stated')) $score+=10;

        if ($score < 60) $theyscore=PRGRETUSER_POOR;
        elseif ($score < 80) $theyscore=PRGRETUSER_FAIR;
        elseif ($score < 100) $theyscore=PRGRETUSER_GOOD;

    } else {
        $theyscore=PRGRETUSER_NO_DATA;
}

?>
<?=$skin->ShowHeader($area)?>
<style>
    #mainpicture{
        border-radius: 4px;
    }
    .profile_link{
        color:#999999;
    }
    .tdhead1{
        border:0px !important;
    }
    </style>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
 
 

  <tr>
    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        
        <tr>
          <td width="70%" valign="top"  >
              <table width="100%" border="0" cellpadding="4" cellspacing="0" >
              <tr>
                <td width="20%" nowrap >
                  <?php require ($CONST_INCLUDE_ROOT."/images.inc.php")?>
                  <br>
                  <?php
                  $gallery = new Gallery();
                  if (count($gallery->GetListByMember($userid))){
                  ?>
                  <a href="<?=$CONST_GALLERY_LINK_ROOT?>/gallery.php?user_id=<?=$userid?>">
                    <img src="<?=$CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/gallery.gif" border=0>
                  </a>
                  <?php }?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                       
              <tr>
                <td align="left"><a href='#' onClick="window.open('<?=$CONST_LINK_ROOT?>/add2hotlist.php?userid=<?=$userid?>&handle=<?=$sql_array->adv_username?>','','toolbar=no,menubar=no,height=150,width=200,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;" title="<?=PRGRETUSER_TEXT7?>">
                  <?php
            if (isset($Sess_UserId)) {
                ?>
                  <?php if ($userid != $Sess_UserId) { ?>
                  <a href='#' onClick="window.open('<?=$CONST_LINK_ROOT?>/add2hotlist.php?userid=<?=$userid?>&handle=<?=$sql_array->adv_username?>','','toolbar=no,menubar=no,height=150,width=200,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;" title="<?=PRGRETUSER_TEXT7?>">
                  <img src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist.gif' hspace="2" border='0' align="absmiddle"></a>
                  <?php } ?>
                  <?php if (!$USERPLANE_IM && !$option_manager->GetValue('userplane_im_free')) { ?>
                  <a href='#' onClick="window.open('<?=$CONST_LINK_ROOT?>/add2im.php?userid=<?=$userid?>&handle=<?=$sql_array->adv_username?>','','toolbar=no,menubar=no,height=150,width=200,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;" title="<?=PRGRETUSER_TEXT6?>"><img src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/addimfriend.gif' hspace="2" border='0' align="absmiddle" ></a>
                  <?php }
             //   $blkquery=mysqli_query($conSting,"SELECT * FROM blockmail WHERE blk_receiverid = $userid AND blk_senderid = $Sess_UserId");
               // $isblocked=mysqli_num_rows($blkquery);
                 $isblocked= $db->get_var("SELECT count(*) FROM blockmail WHERE blk_receiverid = $userid AND blk_senderid = $Sess_UserId");
                if ($isblocked > 0)
                    $sendmail_link = "javascript: alert('".str_replace("'", "\\'", PRGRETUSER_TEXT3)."')";
                else
                    $sendmail_link = "$CONST_LINK_ROOT/sendmail.php?userid=$userid&handle=$sql_array->adv_username";
                ?>
                  <a href="<?=$sendmail_link?>" title="<?=PRGRETUSER_TEXT5?>"><img src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/mailme.gif' hspace="2" border='0' align="absmiddle" ></a>
                  <?php
                    //$advQuery=mysqli_query($conSting,"SELECT * FROM adverts where adv_userid=$Sess_UserId AND adv_approved=1");
                    //$advNote=mysqli_num_rows($advQuery);
                    $advNote=$db->get_var("SELECT count(*) FROM adverts where adv_userid=$Sess_UserId AND adv_approved=1");
                    if ($CONST_FLIRT=='Y' && $advNote > 0) {
                        //$NoteQuery=mysqli_query("SELECT * FROM notifications where ntf_senderid=$Sess_UserId and ntf_receiverid=$userid",$link);
                        //$hadNote=mysqli_num_rows($NoteQuery);
                        $hadNote=$db->get_var("SELECT count(*) FROM notifications where ntf_senderid=$Sess_UserId and ntf_receiverid=$userid");
                        
                        
                        
                        if ($hadNote > 0) {
                            print("<img src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/sent4free.gif' ALT='".PRGRETUSER_FLIRTED."' align=\"absmiddle\">");
                        }else {
                            print("<a href='$CONST_LINK_ROOT/prgsendflirt.php?userid=$userid&handle=$sql_array->adv_username' title='".PRGRETUSER_TEXT4."'><img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/flirt4free.gif' align=\"absmiddle\"></a>");
                        }
                    } elseif ($CONST_FLIRT=='Y' && $advNote < 1) {
                            print("<img src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/block4free.gif' ALT='".PRGRETUSER_TEXT1."' align=\"absmiddle\">");
                    }
                ?>
                  <?php } ?>
                  <a href="<?php echo $CONST_LINK_ROOT?>/tipafriend.php?handle=<?php print("$sql_array->adv_username"); ?>" title='<?=PRGRETUSER_TEXT2?>'><img src='<?php echo $CONST_IMAGE_ROOT?><?= $CONST_IMAGE_LANG ?>/tipfriend.gif' hspace="2" border='0' align="absmiddle"></a>
                  <?php
                  $temptime=mktime (date("H"),date("i")-30,date("s"),date("m") ,date("d"),date("Y"));
                  if ($sql_array->mem_timeout >= date('YmdHis',$temptime) && $USERPLANE_IM && $Sess_UserId != $sql_array->adv_userid ) { ?>
                  <a href="#" onClick="up_launchIC( '<?php echo( $Sess_UserId ) ?>', '<?echo $sql_array->adv_userid?>' ); return false;" title="Launch IM Now!"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/addimfriend.gif' align="absmiddle"></a>
                  <?php } ?>
                </td>
              </tr>
            </table>
                </td>
                <td width="70%" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="4">
                         <tr>
    <td class="pageheader"><?php print("$sql_array->adv_username"); ?></td>
  </tr>
                        <?php if (file_exists($st_profile->fileName)) {?>
                    <tr>
                      <td class=""><?=VIEW_PROFILE_URL?>
                          <a class="profile_link" href="<?=$st_profile->Url;?>"><?=$st_profile->Url;?></a></td>
                    </tr>
                      <?php }?>
                    <tr>
                        <td colspan="2" class="tdhead tdhead1"><?php print(stripslashes("$sql_array->adv_title")); ?>&nbsp;</td>
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
              
                    </table>
                </td>
              </tr>
            </table>
            <table width="70%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="tdhead"><?php echo PRGRETUSER_MESSAGE?> </td>
              </tr>
              <tr>
                <td>
                <div style="overflow:hidden; width:<?=$CONST_COMMENT_WIDTH?> px;">
                <?php print $sql_array->adv_comment_full; ?>
                </div>

                </td>
              </tr>
              <?php print("$rowhead $personality $rowfoot"); ?>
            </table>

          </td>
          <td style="text-align:right;" width="30%" valign="top"  > 
              
              <img width="286px" src="<?php echo $CONST_IMAGE_ROOT;?>/couple-profile-view-page.jpg"/>
              <br><br>
              <input onclick="location.href ='search.php';" type="button" value="<?php echo FIND_YOUR_SOULMATE;?>"  class="button"/>
              
              
          </td>
        </tr></table>
        <table width="100%" border="0" cellpadding="4" cellspacing="0" >
        <tr>
            <td width="50%">
                <table width="100%" border="0" cellpadding="4" cellspacing="0" >
              <tr>
                  <td colspan="2" nowrap class="tdhead"><?php echo PRGRETUSER_DETAILS?></td>
              </tr>
              <tr>
                  <td>
                      <table>
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
                <td >&nbsp;</td>
              </tr>
                      </table>
                  </td>
                  <td valign="top">
                      <table>
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
                <td ><span class="rettext"><?php echo PRGRETUSER_LASTVISIT?>:</span>
                  <?php print $sql_array->lastvisit?></td>
              </tr>
              </table>
                  </td>
              </tr>
              
            </table>
            <?php  include_once "$CONST_NETWORK_INCLUDE_ROOT/action.inc.php"?>
            </td>
            <td width="50%" valign="top"  style="padding-left:10px;">
              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                  <tr>
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
               <tr>
                   <td ><span class="rettext">
                  <?=PRGRETUSER_COMMENT;?>
                  :</span><?php echo stripslashes(nl2br($sql_they->mym_comment)) ?></td>
              </tr>
            </table></td>
           
        </tr>
      </table>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>