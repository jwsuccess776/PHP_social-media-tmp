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
# Name: 		approveclub.php
#
# Description:  Displays the profile input page (after advert)
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
include('../error.php');
include('permission.php');

# retrieve the template
$area = 'member';

# retrieve the first un-approved club
$query = "SELECT * FROM clubs WHERE cl_approved='0'";
$retval=mysql_query($query,$link) or die(mysql_error());
if (mysql_num_rows($retval)==0)
{
 header("Location: $CONST_LINK_ROOT/admin/clubs.php");
exit();
}
$row = mysql_fetch_object($retval);
$txtClubId=$row->cl_clubid;
$txtClubName=$row->cl_clubname;
$txtAddress = $row->cl_address;
$txtCity = $row->cl_city;
$country = $row->cl_country;
$txtPhone = $row->cl_phone;
$txtWebsite = $row->cl_website;
$txtDesc = $row->cl_description;
$txtReview = $row->cl_review;
?>
<?=$skin->ShowHeader($area)?>
    <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo APPROVECLUB_SECTION_NAME ?></td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr><td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/prgclubs.php' name="FrmClub" onSubmit="">
          <input type="hidden" name="txtClubId" value="<?php echo $txtClubId ?>">
          <tr>
            <td colspan="4"  align="left" class="tdhead">&nbsp;</td>
          </tr>
          <tr>
            <td  align="left" class="tdodd">&nbsp;</td>
            <td colspan="3" align="left" class="tdodd"> <input name="txtApprove" type="radio" value="1" checked>
              Approve
              <input type="radio" name="txtApprove" value="0">
              Delete</td>
          </tr>
          <tr>
            <td  align="left" class="tdeven">Club Name:</td>
            <td colspan="3" align="left" class="tdeven"> <input name="txtClubName" type="text" class="input" id="txtClubName2" value="<?php echo $txtClubName?>" size="30" maxlength="50"></td>
          </tr>
          <tr>
            <td  align="left" class="tdodd">Address:</td>
            <td colspan="3" align="left" class="tdodd"> <textarea  class="inputl"name="txtAddress" cols="40" rows="3" id="textarea"><?php echo $txtAddress ?></textarea></td>
          </tr>
          <tr class="tdeven">
            <td height="29" align="left">City:</td>
            <td align="left"> <input name="txtCity" type="text" class="input" value="<?php echo $txtCity ?>" size="15"></td>
            <td align="left">State</td>
            <td align="left"> <select name="txtCountry" size="1" class="inputs" tabindex="3">
                <option  value="0"></option>
                <option <?php if ($country == "US:Alabama") { print("selected");} ?> value="US:Alabama"><?php echo ALABAMA?></option>
                <option <?php if ($country == "US:Alaska") { print("selected");} ?> value="US:Alaska"><?php echo ALASKA?></option>
                <option <?php if ($country == "US:Arizona") { print("selected");} ?> value="US:Arizona"><?php echo ARIZONA?></option>
                <option <?php if ($country == "US:Arkansas") { print("selected");} ?> value="US:Arkansas"><?php echo ARKANSAS?></option>
                <option <?php if ($country == "US:California") { print("selected");} ?> value="US:California"><?php echo CALIFORNIA?></option>
                <option <?php if ($country == "US:Colorado") { print("selected");} ?> value="US:Colorado"><?php echo COLORADO?></option>
                <option <?php if ($country == "US:Connecticut") { print("selected");} ?> value="US:Connecticut"><?php echo CONNECTICUT?></option>
                <option <?php if ($country == "US:Delaware") { print("selected");} ?> value="US:Delaware"><?php echo DELAWARE?></option>
                <option <?php if ($country == "US:Florida") { print("selected");} ?> value="US:Florida"><?php echo FLORIDA?></option>
                <option <?php if ($country == "US:Georgia") { print("selected");} ?> value="US:Georgia"><?php echo GEORGIA?></option>
                <option <?php if ($country == "US:Hawaii") { print("selected");} ?> value="US:Hawaii"><?php echo HAWAII?></option>
        	<option <?php if ($country == "US:Idaho") { print("selected");} ?> value="US:Idaho"><?php echo IDAHO?></option>
                <option <?php if ($country == "US:Illinois") { print("selected");} ?> value="US:Illinois"><?php echo ILLINOIS?></option>
                <option <?php if ($country == "US:Indiana") { print("selected");} ?> value="US:Indiana"><?php echo INDIANA?></option>
                <option <?php if ($country == "US:Iowa") { print("selected");} ?> value="US:Iowa"><?php echo IOWA?></option>
                <option <?php if ($country == "US:Kansas") { print("selected");} ?> value="US:Kansas"><?php echo KANSAS?></option>
                <option <?php if ($country == "US:Kentucky") { print("selected");} ?> value="US:Kentucky"><?php echo KENTUCKY?></option>
                <option <?php if ($country == "US:Louisiana") { print("selected");} ?> value="US:Louisiana"><?php echo LOUISIANA?></option>
                <option <?php if ($country == "US:Maine") { print("selected");} ?> value="US:Maine"><?php echo MAINE?></option>
                <option <?php if ($country == "US:Maryland") { print("selected");} ?> value="US:Maryland"><?php echo MARYLAND?></option>
                <option <?php if ($country == "US:Massachusetts") { print("selected");} ?> value="US:Massachusetts"><?php echo MASSACHUSETTS?></option>
                <option <?php if ($country == "US:Michigan") { print("selected");} ?> value="US:Michigan"><?php echo MICHIGAN?></option>
                <option <?php if ($country == "US:Minnesota") { print("selected");} ?> value="US:Minnesota"><?php echo MINNESOTA?></option>
                <option <?php if ($country == "US:Mississippi") { print("selected");} ?> value="US:Mississippi><?php echo MISSISSIPPI?></option>
        	<option <?php if ($country == "US:Missouri") { print("selected");} ?> value="US:Missouri><?php echo MISSOURI?></option>
                <option <?php if ($country == "US:Montana") { print("selected");} ?> value="US:Montana"><?php echo MONTANA?></option>
                <option <?php if ($country == "US:N.Carolina") { print("selected");} ?> value="US:N.Carolina"><?php echo N_CAROLINA?></option>
                <option <?php if ($country == "US:N.Mexico") { print("selected");} ?> value="US:N.Mexico"><?php echo N_MEXICO?></option>
                <option <?php if ($country == "US:Nebraska") { print("selected");} ?> value="US:Nebraska"><?php echo NEBRASKA?></option>
                <option <?php if ($country == "US:Nevada") { print("selected");} ?> value="US:Nevada"><?php echo NEVADA?></option>
                <option <?php if ($country == "US:New Hampshire") { print("selected");} ?> value="US:New Hampshire"><?php echo NEW_HAMPSHIRE?></option>
                <option <?php if ($country == "US:New Jersey") { print("selected");} ?> value="US:New Jersey"><?php echo NEW_JERSEY?></option>
                <option <?php if ($country == "US:New York") { print("selected");} ?> value="US:New York"><?php echo NEW_YORK?></option>
                <option <?php if ($country == "US:North Dakota") { print("selected");} ?> value="US:North Dakota"><?php echo NORTH_DAKOTA?></option>
                <option <?php if ($country == "US:Ohio") { print("selected");} ?> value="US:Ohio"><?php echo OHIO?></option>
                <option <?php if ($country == "US:Oklahoma") { print("selected");} ?> value="US:Oklahoma"><?php echo OKLAHOMA?></option>
                <option <?php if ($country == "US:Oregon") { print("selected");} ?> value="US:Oregon"><?php echo OREGON?></option>
                <option <?php if ($country == "US:Pennsylvania") { print("selected");} ?> value="US:Pennsylvania"><?php echo PENNSYLVANIA?></option>
                <option <?php if ($country == "US:Rhode Island") { print("selected");} ?> value="US:Rhode Island"><?php echo RHODE_ISLAND?></option>
                <option <?php if ($country == "US:S.Carolina") { print("selected");} ?> value="US:S.Carolina"><?php echo S_CAROLINA?></option>
                <option <?php if ($country == "US:South Dakota") { print("selected");} ?> value="US:South Dakota"><?php echo SOUTH_DAKOTA?></option>
                <option <?php if ($country == "US:Tennessee") { print("selected");} ?> value="US:Tennessee"><?php echo TENNESSEE?></option>
                <option <?php if ($country == "US:Texas") { print("selected");} ?> value="US:Texas"><?php echo TEXAS?></option>
                <option <?php if ($country == "US:Utah") { print("selected");} ?> value="US:Utah"><?php echo UTAH?></option>
                <option <?php if ($country == "US:Vermont") { print("selected");} ?> value="US:Vermont"><?php echo VERMONT?></option>
                <option <?php if ($country == "US:Virginia") { print("selected");} ?> value="US:Virginia"><?php echo VIRGINIA?></option>
                <option <?php if ($country == "US:Washington") { print("selected");} ?> value="US:Washington"><?php echo WASHINGTON?></option>
                <option <?php if ($country == "US:Washington DC") { print("selected");} ?> value="US:Washington DC"><?php echo WASHINGTON_DC?></option>
                <option <?php if ($country == "US:West Virginia") { print("selected");} ?> value="US:West Virginia"><?php echo WEST_VIRGINIA?></option>
                <option <?php if ($country == "US:Wisconsin") { print("selected");} ?> value="US:Wisconsin"><?php echo WISCONSIN?></option>
                <option <?php if ($country == "US:Wyoming") { print("selected");} ?> value="US:Wyoming"><?php echo WYOMING?></option>
              </select></td>
          </tr>
          <tr>
            <td  align="left" class="tdodd">Phone:</td>
            <td colspan="3" align="left" class="tdodd"> <input name="txtPhone" type="text" class="input" id="txtPhone2" value="<?php echo $txtPhone?>" size="25" maxlength="25"></td>
          </tr>
          <tr>
            <td  align="left" class="tdeven">Website:</td>
            <td colspan="3" align="left" class="tdeven"> <input name="txtWebsite" type="text" class="input" id="txtWebsite2" value="<?php if (trim($txtWebsite) == "") echo "http://"; else echo $txtWebsite?>" size="30" maxlength="50"></td>
          </tr>
          <tr>
            <td  align="left" class="tdodd">Description:</td>
            <td colspan="3" align="left" class="tdodd"> <textarea  class="inputl"name="txtDesc" cols="40" rows="3" id="textarea2"><?php echo $txtDesc ?></textarea></td>
          </tr>
          <tr align="center">
            <td colspan="4" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
            </td>
          </tr>
          <tr>
            <td  colspan="4" align="left" valign="top"></td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>
