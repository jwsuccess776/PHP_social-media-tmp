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
# Name: 		profile.php
#
# Description:  Displays the profile input page (after advert)
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('pop_lists.inc');
# retrieve the template
if(isset($_SESSION['Sess_JustRegistered']))
    $area = 'guest';
else
    $area = 'member';
# check no profiles exists
$query="SELECT pro_userid FROM profiles WHERE pro_userid = '$Sess_UserId'";
//$retval=mysql_query($query,$link) or die(mysql_error());
//$result=mysql_num_rows($retval);
$retval=$db->get_results($query) ;
if(!is_null($retval)) {
$result=sizeof($retval);
if ($result > 0) {
	header("Location: $CONST_LINK_ROOT/prgamendpro.php");
	exit;
}
}

?>
<?=$skin->ShowHeader($area)?>
    <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
<?if($Sess_UserId){?>
      <tr>
        <td align="right">
        	<?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
        </td>
      </tr>
<?}?>
      <tr>

      <td class="pageheader"><?php echo PROFILE_SECTION_NAME ?></td>
      </tr>
      <tr>


    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgprofile.php?mode=create' name="FrmPersonal" onSubmit="return Validate_FrmPersonal()">
          <tr >
            <td colspan="4" class="join_head" ><?php echo ADVERTISE_MESSAGE5?></td>
          </tr>
    <tr>
            <td  align="left" colspan="4"><?php echo PROFILE_TEXT?>&nbsp; <a href="<?php echo $CONST_LINK_ROOT?>/prgprofile.php?mode=skip" onclick="return skip_alert();"><?php echo PROFILE_SKIP ?></a></td>
    </tr>
    <tr  class="tdodd">
      <td  align="left"><?php echo OPTION_PERSONALITY?></td>
      <td >
        <select class="input" size="1" name="lstPerson1" tabindex='14'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('PST','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstPerson2" tabindex='15'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('PST','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstPerson3" tabindex='16'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('PST','base','adv',''); ?> </select></td>
    </tr>
    <tr class="tdeven">
      <td  align="left"><?php echo OPTION_PHILOSOPHIES?></td>
      <td >
        <select class="input" size="1" name="lstPhilos1"  tabindex='17'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('PHI','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstPhilos2"  tabindex='18'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('PHI','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstPhilos3"  tabindex='19'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('PHI','base','adv',''); ?> </select></td>
    </tr>
    <tr class="tdodd">
      <td  align="left"><?php echo OPTION_SOCIAL_GROUP?></td>
      <td >
        <select class="input" size="1" name="lstSocial1"  tabindex='20'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('SOG','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstSocial2"  tabindex='21'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('SOG','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstSocial3"  tabindex='22'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('SOG','base','adv',''); ?> </select></td>
    </tr>
    <tr  class="tdeven">
      <td  align="left"><?php echo OPTION_GOALS?></td>
      <td >
        <select class="input" size="1" name="lstGoal1"  tabindex='23'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('GLS','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstGoal2"  tabindex='24'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('GLS','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstGoal3"  tabindex='25'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('GLS','base','adv',''); ?> </select></td>
    </tr>
    <tr class="tdodd">
      <td  align="left"><?php echo OPTION_HOBBIES?></td>
      <td >
        <select class="input" size="1" name="lstHobby1"  tabindex='26'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('HBS','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstHobby2"  tabindex='27'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('HBS','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstHobby3"  tabindex='28'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('HBS','base','adv',''); ?> </select></td>
    </tr>
    <tr  class="tdeven">
      <td  align="left"><?php echo OPTION_SPORTS?></td>
      <td >
        <select class="input" size="1" name="lstSport1"  tabindex='29'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('SPT','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstSport2"  tabindex='30'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('SPT','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstSport3"  tabindex='31'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('SPT','base','adv',''); ?> </select></td>
    </tr>
    <tr class="tdodd">
      <td  align="left"><?php echo OPTION_MUSIC?></td>
      <td >
        <select class="input" size="1" name="lstMusic1"  tabindex='32'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('MSC','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstMusic2"  tabindex='33'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('MSC','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstMusic3"  tabindex='34'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('MSC','base','adv',''); ?> </select></td>
    </tr>
    <tr  class="tdeven">
      <td  align="left"><?php echo OPTION_FOOD_TASTE?></td>
      <td >
        <select class="input" size="1" name="lstFood1"  tabindex='35'>
          <option value="0" selected>- <?php echo GENERAL_CHOOSE?> -</option>
          <?php echo populate_lists('FDT','base','adv',''); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstFood2"  tabindex='36'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('FDT','base','adv',''); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstFood3"  tabindex='37'>
          <option value="- Not selected -" selected>- <?=GENERAL_NOT_STATE?> - </option>
          <?php echo populate_lists('FDT','base','adv',''); ?> </select></td>
    </tr>
    <tr>
      <td colspan="4" align="left" valign="top" class="tdfoot">
        <center>
                   <input type="submit" name="Submit" value="<?php echo BUTTON_UPDATE ?>" class="button">
        </center></td>
    </tr>
  </form>
</table>
</td>
      </tr>
    </table>
<?=$skin->ShowFooter($area)?>