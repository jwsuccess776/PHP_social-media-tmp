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
# Name: 		myinfo.php
#
# Description:  Member control panel menu ('My Info')
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
if(!isset($_POST['adchangevalue']) || $_POST['adchangevalue'] == '') save_request();
# retrieve the template
$area = 'member';

# Reset the paused / visible flag
if (isset($_POST['adchangevalue']) && $_POST['adchangevalue'] != '') {
	$adchangevalue=$_POST['adchangevalue'];
	$query="UPDATE adverts SET adv_paused='$adchangevalue' WHERE adv_userid = '$Sess_UserId'";
	$result=mysql_query($query,$link) or die(mysql_error());
}

# check users member status to display expire date
if ($Sess_Userlevel!="silver") {
	$query="SELECT mem_expiredate FROM members WHERE mem_userid = '$Sess_UserId'";
	$result=mysql_query($query,$link) or die(mysql_error());
	$sql_array = mysql_fetch_array($result);
	$retStatus= MYINFO_PR_MEMBER." ".date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array[0]));
} else {
	$retStatus=MYINFO_ST_MEMBER;
}

if (formGet('SAVE_BLOCK')) {
    $value = $db->escape(formGet('blockmail'));
	$db->query("UPDATE members SET mem_block_mail='$value' WHERE mem_userid = '$Sess_UserId'");
}

if (formGet('SAVE_SMS')) {
    $value = $db->escape(formGet('sms'));
	$db->query("UPDATE members SET mem_sms='$value' WHERE mem_userid = '$Sess_UserId'");
}


# check advert for photo
$query="SELECT adv_userid,adv_picture, adv_paused, adv_approved,mem_block_mail,mem_sms
		FROM adverts
			INNER JOIN members ON (mem_userid=adv_userid)
		WHERE adv_userid='$Sess_UserId'";
$retval=mysql_query($query,$link) or die(mysql_error());
$got_photo = mysql_fetch_object($retval);
if ($got_photo->adv_paused == 'Y') {
	$adstatus=MY_AD_STATUS_PAUSED;
	$adpausedval='N'; // opposite value for updating
} elseif  ($got_photo->adv_paused == 'N') {
	$adstatus=MY_AD_STATUS_VISIBLE;
	$adpausedval='Y'; // opposite value for updating
} else {
	$adstatus=MY_AD_STATUS_NONE;
	$adpausedval=''; // opposite value for updating
}

switch ($got_photo->adv_approved) {
	case 0:
		$approved=STATUS_PENDING;
		break;
	case 1:
		$approved=STATUS_APPROVED;
		break;
	case 2:
		$approved=STATUS_REJECTED;
		break;
}

mysql_close($link);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo MYINFO_SECTION_NAME ?></td>
    </tr>
    <tr><td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <tr>
          <td colspan="2" align="left" valign="top" class="tdhead"><?php print("$retStatus"); ?></td>
        </tr>

        <tr>
          <td align="left" valign="top" nowrap class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/prgamendad.php"><?php echo MYINFO_LINK_PROFILE?></a></td>
          <td align="left" valign="top" class="tdeven"><?php echo MYINFO_PROFILE?></td>
        </tr>
        <tr>
          <td align="left" valign="top" nowrap class="tdodd"><a href="<?php echo $CONST_LINK_ROOT?>/prgmyinfo.php?mode=deletematch"><?php echo MYINFO_LINK_REMOVE_MAIL?></a></td>
          <td align="left" valign="top" class="tdodd"><?php echo MYINFO_REMOVE_MAIL?></td>
        </tr>
        <tr>
          <td align="left" valign="top" nowrap class="tdeven"><a href="<?=$CONST_LINK_ROOT?>/prgpicadmin.php?mode=show"><?php echo MYINFO_LINK_MEDIA?></a></td>
          <td align="left" valign="top" class="tdeven"><?php echo MYINFO_MEDIA?></td>
        </tr>
        <tr>
          <td align="left" valign="top" nowrap class="tdodd"><a href="<?php echo $CONST_LINK_ROOT?>/prgamendreg.php"><?php echo MYINFO_LINK_REGISTRATION?></a></td>
          <td align="left" valign="top" class="tdodd"><?php echo MYINFO_REGISTRATION?></td>
        </tr>
        <tr>
          <td align="left" valign="top" nowrap class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/user_payments.php"><?php echo MYINFO_LINK_PAYMENT?></a></td>
          <td align="left" valign="top" class="tdeven"><?php echo MYINFO_PAYMENT?></td>
        </tr>
        <tr>
          <td align="left" valign="top" nowrap class="tdodd"><a href="<?php echo $CONST_LINK_ROOT?>/prgmailblock.php"><?php echo MYINFO_LINK_MANAGE?></a></td>
          <td align="left" valign="top" class="tdodd"><?php echo MYINFO_MANAGE?></td>
        </tr>
        <tr>
          <td align="left" valign="top" nowrap class="tdeven"><a href="<?php echo $CONST_LINK_ROOT?>/prgmyinfo.php?mode=deleteme" onClick="return delete_alert();"><?php echo MYINFO_LINK_DEL_ME?></a></td>
          <td align="left" valign="top" class="tdeven"><?php echo MYINFO_DEL_ME?></td>
        </tr>
        <form  name="frmMyinfo" method="post" action="<?=$CONST_LINK_ROOT?>/myinfo.php">
          <tr>
            <td colspan="2" align="left" valign="top" nowrap class="tdhead">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top" nowrap class="tdodd"><strong><?php echo MYINFO_AD_STATUS_SECTION_NAME ?></strong></td>
            <td align="left" valign="top" class="tdodd">
			 <input name="submit" class="button" type="submit" value="<?php echo $adstatus ?>">
              <strong>
              <input name='adchangevalue' type="hidden" value="<?php echo $adpausedval ?>">
              </strong>              <strong><?php print("($approved)"); ?></strong></td>
          </tr>
        </form>
        <form  name="frmMyinfo" method="post" action="<?=$CONST_LINK_ROOT?>/myinfo.php">
          <tr>
            <td align="left" valign="top" nowrap class="tdodd"><strong><?=MYINFO_BLOCK_TEXT?></strong></td>
            <td align="left" valign="top" class="tdodd">
				<select name="blockmail">
				<option value="0" <?if ($got_photo->mem_block_mail == 0){?>selected<?}?>> <?=GENERAL_YES?>
				<option value="1" <?if ($got_photo->mem_block_mail == 1){?>selected<?}?>> <?=GENERAL_NO?>
				</select> <input type=submit name=SAVE_BLOCK  class="button" value="<?=MYINFO_SAVE?>">
			</td>
          </tr>
        </form>
        <form  name="frmMyinfo" method="post" action="<?=$CONST_LINK_ROOT?>/myinfo.php">
          <tr>
            <td align="left" valign="top" nowrap class="tdodd"><strong><?=GET_SMS?></strong></td>
            <td align="left" valign="top" class="tdodd">
				<select name="sms">
				<option value="1" <?if ($got_photo->mem_sms == 1){?>selected<?}?>> <?=GENERAL_YES?>
				<option value="0" <?if ($got_photo->mem_sms == 0){?>selected<?}?>> <?=GENERAL_NO?>
				</select> 
				<input type=submit name=SAVE_SMS  class="button" value="<?=MYINFO_SAVE?>">
			</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="top" nowrap class="tdfoot">&nbsp;</td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>