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
# Name: 		prgamendpro.php
#
# Description:  Displays member profile for editing
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('pop_lists.inc');
include('error.php');
# retrieve the template
	$area = 'member';
# select profiles data
//$result =  mysql_query("SELECT * FROM profiles WHERE pro_userid=$Sess_UserId",$link);
//$TOTAL = mysql_num_rows($result);
$result=$db->get_row("SELECT * FROM profiles WHERE pro_userid=$Sess_UserId");
# if nothing is returned then show error otherwise get data
/*if ($TOTAL < 1) {
	$error_message=PRGAMENDPRO_ERROR;
	error_page($error_message,GENERAL_USER_ERROR);
} else {
	$sql_array = mysql_fetch_object($result);
}*/
if(!is_null($result))
{
    $sql_array =$result;
}
else # if nothing is returned then show error otherwise get data
{
    $error_message=PRGAMENDPRO_ERROR;
	error_page($error_message,GENERAL_USER_ERROR);
}

$person1=$sql_array->pro_person1;
$person2=$sql_array->pro_person2;
$person3=$sql_array->pro_person3;
$philos1=$sql_array->pro_philos1;
$philos2=$sql_array->pro_philos2;
$philos3=$sql_array->pro_philos3;
$social1=$sql_array->pro_social1;
$social2=$sql_array->pro_social2;
$social3=$sql_array->pro_social3;
$sport1=$sql_array->pro_sport1;
$sport2=$sql_array->pro_sport2;
$sport3=$sql_array->pro_sport3;
$hobby1=$sql_array->pro_hobby1;
$hobby2=$sql_array->pro_hobby2;
$hobby3=$sql_array->pro_hobby3;
$music1=$sql_array->pro_music1;
$music2=$sql_array->pro_music2;
$music3=$sql_array->pro_music3;
$food1=$sql_array->pro_food1;
$food2=$sql_array->pro_food2;
$food3=$sql_array->pro_food3;
$goal1=$sql_array->pro_goal1;
$goal2=$sql_array->pro_goal2;
$goal3=$sql_array->pro_goal3;

?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo PROFILE_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><?php echo PRGAMENDAD_TEXT1?></td>
  </tr>
  <tr>
    <td>

	<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

  <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/prgprofile.php?mode=update' name="FrmPersonal">
    <tr >
      <td colspan="4"  align="left" class="tdhead">&nbsp;</td>
    </tr>
    <tr class="tdodd" >
      <td  align="left"><? echo OPTION_PERSONALITY?></td>
      <td >
        <select class="input" size="1" name="lstPerson1" tabindex='14'>
          <?php echo populate_lists('PST','base','adv',$person1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstPerson2" tabindex='15'>
          <option <?php if ($person2 == "- Not selected -") { print("selected");} ?> value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('PST','base','adv',$person2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstPerson3" tabindex='16'>
          <option <?php if ($person3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('PST','base','adv',$person3); ?> </select></td>
    </tr>
    <tr class="tdeven" >
      <td  align="left"><? echo OPTION_PHILOSOPHIES?></td>
      <td >
        <select class="input" size="1" name="lstPhilos1"  tabindex='17'>
          <?php echo populate_lists('PHI','base','adv',$philos1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstPhilos2"  tabindex='18'>
          <option  <?php if ($philos2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('PHI','base','adv',$philos2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstPhilos3"  tabindex='19'>
          <option  <?php if ($philos3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('PHI','base','adv',$philos3); ?> </select></td>
    </tr>
    <tr class="tdodd" >
      <td  align="left"><? echo OPTION_SOCIAL_GROUP?></td>
      <td >
        <select class="input" size="1" name="lstSocial1"  tabindex='20'>
          <?php echo populate_lists('SOG','base','adv',$social1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstSocial2"  tabindex='21'>
          <option  <?php if ($social2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('SOG','base','adv',$social2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstSocial3"  tabindex='22'>
          <option  <?php if ($social3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('SOG','base','adv',$social3); ?> </select></td>
    </tr>
    <tr class="tdeven" >
      <td  align="left"><? echo OPTION_GOALS?></td>
      <td >
        <select class="input" size="1" name="lstGoal1"  tabindex='23'>
          <?php echo populate_lists('GLS','base','adv',$goal1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstGoal2"  tabindex='24'>
          <option <?php if ($goal2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('GLS','base','adv',$goal2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstGoal3"  tabindex='25'>
          <option <?php if ($goal3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('GLS','base','adv',$goal3); ?> </select></td>
    </tr>
    <tr class="tdodd" >
      <td  align="left"><? echo OPTION_HOBBIES?></td>
      <td >
        <select class="input" size="1" name="lstHobby1"  tabindex='26'>
          <?php echo populate_lists('HBS','base','adv',$hobby1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstHobby2"  tabindex='27'>
          <option <?php if ($hobby2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('HBS','base','adv',$hobby2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstHobby3"  tabindex='28'>
          <option <?php if ($hobby3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('HBS','base','adv',$hobby3); ?> </select></td>
    </tr>
    <tr class="tdeven" >
      <td  align="left"><? echo OPTION_SPORTS?></td>
      <td >
        <select class="input" size="1" name="lstSport1"  tabindex='29'>
          <?php echo populate_lists('SPT','base','adv',$sport1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstSport2"  tabindex='30'>
          <option <?php if ($sport2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('SPT','base','adv',$sport2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstSport3"  tabindex='31'>
          <option <?php if ($sport3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('SPT','base','adv',$sport3); ?> </select></td>
    </tr>
    <tr class="tdodd" >
      <td  align="left"><? echo OPTION_MUSIC?></td>
      <td >
        <select class="input" size="1" name="lstMusic1"  tabindex='32'>
          <?php echo populate_lists('MSC','base','adv',$music1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstMusic2"  tabindex='33'>
          <option  <?php if ($music2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('MSC','base','adv',$music2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstMusic3"  tabindex='34'>
          <option  <?php if ($music3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('MSC','base','adv',$music3); ?> </select></td>
    </tr>
    <tr class="tdeven" >
      <td  align="left"><? echo OPTION_FOOD_TASTE?></td>
      <td >
        <select class="input" size="1" name="lstFood1"  tabindex='35'>
          <?php echo populate_lists('FDT','base','adv',$food1); ?> </select></td>
      <td  align="left">
        <select class="input" size="1" name="lstFood2"  tabindex='36'>
          <option <?php if ($food2 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('FDT','base','adv',$food2); ?> </select></td>
      <td >
        <select class="input" size="1" name="lstFood3"  tabindex='37'>
          <option <?php if ($food3 == "- Not selected -") { print("selected");} ?>	 	value="- Not selected -">-
          <?php echo PRGAMENDPRO_NOT_SELECTED?> -</option>
          <?php echo populate_lists('FDT','base','adv',$food3); ?> </select></td>
    </tr>
    <tr >
      <td valign="top" align="left" ></td>
      <td align="left" colspan="3" ></td>
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