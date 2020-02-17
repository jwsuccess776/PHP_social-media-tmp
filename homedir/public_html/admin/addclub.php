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
# Name: 		addclub.php
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

?>
<?=$skin->ShowHeader($area)?>
    <div align="left">
	 <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT ?>/admin/prgclubs.php' name="FrmClub" onSubmit="">
    <table border="0" cellpadding="0" cellspacing="0" width="482">
      <tr>
        <td align="left">&nbsp;</td>
        <td height="30" colspan="4" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td width="1" align="left">&nbsp;</td>
        <td height="30" colspan="4" align="left"><img src="<?=$CONST_IMAGE_ROOT?>/patry_time.gif"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td width="121" height="30" align="left">Club Name:</td>
        <td colspan="3" align="left"><input name="txtClubName" type="text" id="txtClubName" value="<?php echo $txtClubName?>" size="30" maxlength="50"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">Address:</td>
        <td colspan="3" align="left"><input name="txtAddress" type="text" id="txtAddress" value="" size="40"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="29" align="left">City:</td>
        <td width="129" align="left"><input name="txtCity" type="text" id="txtCity" value="" size="15"></td>
        <td width="42" align="left">State</td>
        <td width="189" align="left"><select name="txtCountry" size="1" id="txtCountry" tabindex="4">
            <option value="0" selected>- Choose -</option>
            <option value="0"></option>
            <option value="US:Alabama"><?php echo ALABAMA?></option>
            <option value="US:Alaska"><?php echo ALASKA?></option>
            <option value="US:Arizona"><?php echo ARIZONA?></option>
            <option value="US:Arkansas"><?php echo ARKANSAS?></option>
            <option value="US:California"><?php echo CALIFORNIA?></option>
            <option value="US:Colorado"><?php echo COLORADO?></option>
            <option value="US:Connecticut"><?php echo CONNECTICUT?></option>
            <option value="US:Delaware"><?php echo DELAWARE?></option>
            <option value="US:Florida"><?php echo FLORIDA?></option>
            <option value="US:Georgia"><?php echo GEORGIA?></option>
            <option value="US:Hawaii"><?php echo HAWAII?></option>
            <option value="US:Idaho"><?php echo IDAHO?></option>
            <option value="US:Illinois"><?php echo ILLINOIS?></option>
            <option value="US:Indiana"><?php echo INDIANA?></option>
            <option value="US:Iowa"><?php echo IOWA?></option>
            <option value="US:Kansas"><?php echo KANSAS?></option>
            <option value="US:Kentucky"><?php echo KENTUCKY?></option>
            <option value="US:Louisiana"><?php echo LOUISIANA?></option>
            <option value="US:Maine"><?php echo MAINE?></option>
            <option value="US:Maryland"><?php echo MARYLAND?></option>
            <option value="US:Massachusetts"><?php echo MASSACHUSETTS?></option>
            <option value="US:Michigan"><?php echo MICHIGAN?></option>
            <option value="US:Minnesota"><?php echo MINNESOTA?></option>
            <option value="US:Mississippi"><?php echo MISSISSIPPI?></option>
            <option value="US:Missouri"><?php echo MISSOURI?></option>
            <option value="US:Montana"><?php echo MONTANA?></option>
            <option value="US:N.Carolina"><?php echo N_CAROLINA?></option>
            <option value="US:N.Mexico"><?php echo N_MEXICO?></option>
            <option value="US:Nebraska"><?php echo NEBRASKA?></option>
            <option value="US:Nevada"><?php echo NEVADA?></option>
            <option value="US:New Hampshire"><?php echo NEW_HAMPSHIRE?></option>
            <option value="US:New Jersey"><?php echo NEW_JERSEY?></option>
            <option value="US:New York"><?php echo NEW_YORK?></option>
            <option value="US:North Dakota"><?php echo NORTH_DAKOTA?></option>
            <option value="US:Ohio"><?php echo OHIO?></option>
            <option value="US:Oklahoma"><?php echo OKLAHOMA?></option>
            <option value="US:Oregon"><?php echo OREGON?></option>
            <option value="US:Pennsylvania"><?php echo PENNSYLVANIA?></option>
            <option value="US:Rhode Island"><?php echo RHODE_ISLAND?></option>
            <option value="US:S.Carolina"><?php echo S_CAROLINA?></option>
            <option value="US:South Dakota"><?php echo SOUTH_DAKOTA?></option>
            <option value="US:Tennessee"><?php echo TENNESSEE?></option>
            <option value="US:Texas"><?php echo TEXAS?></option>
            <option value="US:Utah"><?php echo UTAH?></option>
            <option value="US:Vermont"><?php echo VERMONT?></option>
            <option value="US:Virginia"><?php echo VIRGINIA?></option>
            <option value="US:Washington"><?php echo WASHINGTON?></option>
            <option value="US:Washington DC"><?php echo WASHINGTON_DC?></option>
            <option value="US:West Virginia"><?php echo WEST_VIRGINIA?></option>
            <option value="US:Wisconsin"><?php echo WISCONSIN?></option>
            <option value="US:Wyoming"><?php echo WYOMING?></option>
          </select></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">Phone:</td>
        <td colspan="3" align="left"><input name="txtPhone" type="text" id="txtPhone" value="<?php echo $txtPhone?>" size="25" maxlength="25"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">Website:</td>
        <td colspan="3" align="left"><input name="txtWebsite" type="text" id="txtWebsite" value="<?php if (trim($txtWebsite) == "") echo "http://"; else echo $txtWebsite?>" size="30" maxlength="50"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">Description:</td>
        <td colspan="3" align="left"> <textarea name="txtDesc" cols="40" rows="3" id="txtDesc"></textarea></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">Graphic/Banner:</td>
        <td colspan="3" align="left"><input name="mainfupload" type="file" id="mainfupload"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">&nbsp;</td>
        <td colspan="3" align="left"><input name="imageField" onClick="document.forms[0].submit()" type="image" src="<?=$CONST_IMAGE_ROOT?>/updatenow.jpg" border="0"></td>
      </tr>
      <tr>
        <td align="left"></td>
        <td height="30" align="left">&nbsp;</td>
        <td colspan="3" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left" width="1"></td>
        <td height="30" colspan="4" align="left" valign="top"></td>
      </tr>
    </table>
          </form>
   </div>
<?=$skin->ShowFooter($area)?>