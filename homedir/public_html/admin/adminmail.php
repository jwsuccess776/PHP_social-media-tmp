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
# Name:         adminmail.php
#
# Description:  Administrators mailing input screen
#
# Version:      7.2
#
######################################################################

include('../db_connect.php');
include('../session_handler.inc');
require_once ('../pop_lists.inc');
include('permission.php');

# retrieve the template
$area = 'member';
$sd="OFF";
if(file_exists($CONST_INCLUDE_ROOT."/speeddating/index.php")) $sd="ON";
?>
<?=$skin->ShowHeader($area)?>
<script>
	function GetFCK(fckeditorvar)
	{
		try {
		// Get the editor instance that we want to interact with.
		var oEditor = FCKeditorAPI.GetInstance(fckeditorvar) ;
		return oEditor;
		}
		catch(e) {
			return false;
		}
	}
	function setVisibleFCK(name,mode) {
		var plainBody=document.getElementById("plainMailTempl");
		var oFCK= GetFCK(name);
		if ( mode ) {
			document.getElementById(name+"___Frame").style.display="";

		}
		else {
			document.getElementById(name+"___Frame").style.display="none";
		}

	}
	function showBodyMailTempl(name, value) {
		var plainBody=document.getElementById("plainMailTempl");
		var htmlBody=document.getElementById("htmlMailTempl");
		if (value) {
			plainBody.style.display="none";
			htmlBody.style.display="";
		}
		else {
			plainBody.style.display="";
			htmlBody.style.display="none";
		}
	}
</script>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo SENDMAIL_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgprocmail.php' name="mailForm">
          <tr>
            <td colspan="2" align="left" class="tdhead">&nbsp;</td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo ADMINMAIL_EMAILTO?></td>
            <td align="left"><input type="text" class="input" name="txtAddress" size="36" tabindex="1" onKeyDown="admin_mail('txtAddress','<?php echo $sd ?>');" onKeyUp="admin_mail('txtAddress','<?php echo $sd ?>');">
              (<?php echo ADMINMAIL_SEND?>) </td>
          </tr>
          <tr class="tdeven">
            <td align="left"><?php echo ADMINMAIL_ALL?></td>
            <td align="left"><input type="checkbox" name="chkAllusers" value="ON" onClick="admin_mail('chkAllusers','<?php echo $sd ?>');">
              (<?php echo ADMINMAIL_TICK1?>)</td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo ADM_AFFILIATES ?></td>
            <td align="left"><input type="checkbox" name="chkAffiliates" value="ON" onClick="admin_mail('chkAffiliates','<?php echo $sd ?>');">
              (<?php echo ADMINMAIL_TICK5?>)</td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo ADMINMAIL_FROMFILE?></td>
            <td align="left"><input type="checkbox" name="chkFile" value="ON" onClick="admin_mail('chkFile','<?php echo $sd ?>');">
              (<?php echo ADMINMAIL_TICK2?>)</td>
          </tr>
          <?php if(file_exists($CONST_INCLUDE_ROOT."/speeddating/index.php")) { ?>
          <tr class="tdeven">
            <td align="left"><?php echo ADM_SPEEDDATING ?></td>
            <td align="left"><input type="checkbox" name="chkSpeeddating" value="ON" onClick="admin_mail('chkSpeeddating','<?php echo $sd ?>');">
              (<?php echo ADMINMAIL_SPEEDDATING?>)</td>
          </tr>
          <?php } ?>
          <tr class="tdeven">
            <td align="left"><?php echo ADMINMAIL_INTR?></td>
            <td align="left"><input type="checkbox" name="chkIntro" value="ON">
              (<?php echo ADMINMAIL_TICK3?>)</td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo ADMINMAIL_HTML?></td>
            <td align="left"><input type="checkbox" name="chkType" id="mailType" value="ON" onClick="showBodyMailTempl('BodyHTML',this.checked);">
              (<?php echo ADMINMAIL_TICK4?>) </td>
          </tr>
          <tr class="tdeven">
            <td align="left"><?php echo SEX?></td>
            <td align="left"><select name="lstGender" size="1" class="inputs">
                <option selected value="A"><?php echo GENERAL_ALL?></option>
                <option value="M"><?php echo SEX_MALE?></option>
                <option value="F"><?php echo SEX_FEMALE?></option>
              </select>
              (<?php echo ADMINMAIL_SELECT?>)</td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo STATUS?> </td>
            <td align="left"><select class="input" name="lstStatus">
                <option value="All"><?php echo STATUS_A?></option>
                <option value="Standard"><?php echo STATUS_S?></option>
                <option value="Premium"><?php echo STATUS_P?></option>
                <option value="Inactive"><?php echo STATUS_I?></option>
                <option value="Rejected"><?php echo STATUS_REJECTED ?></option>
              </select>
            </td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo LANGUAGE_SECTION_NAME?> </td>
            <td align="left"><select class="input" name="lstLanguage">
                <option value="All"><?php echo STATUS_A?></option>
                <?php
					foreach ($language->getActiveList() as $lang) {
						echo "<option value='$lang->LangID'>$lang->Name</option>";
					}
				?>
              </select>
            </td>
          </tr>
          <tr class="tdeven">
            <td align="left"><?php echo ADMINMAIL_SEND_TYPE?></td>
            <td align="left"><select size="1" name="lstSendType" class="input">
                <option selected value="outside"><?php echo ADMINMAIL_OUT?></option>
                <option value="inside_hidden"><?php echo ADMINMAIL_INS?></option>
              </select>
              <?php echo ADMINMAIL_TEXT?></td>
          </tr>
          <tr class="tdodd">
            <td align="left"><?php echo ADMINMAIL_SUB?></td>
            <td align="left" valign="top"><input type="text" class="input" name="txtSubject" size="50" tabindex="2">
            </td>
          </tr>
          <tr class="tdeven" id="htmlMailTempl">
            <td><?php echo ADMINMAIL_MESS?> </td>
            <td><?
     				$fck = createFCKEditor( "additional_images", 'txtMessageHtml', '', 'mailTempl', 500, 300);
     				$fck->Create() ;
        		?>
            </td>
          </tr>
          <tr class="tdeven" id="plainMailTempl">
            <td><?php echo ADMINMAIL_MESS?> </td>
            <td><textarea  class="inputl"rows="10" name="txtMessage" cols="64" tabindex="2" style="width:400px;height:300px;"></textarea>
            </td>
          </tr>
          <? if ($m_template->type!="text/html") { ?>
          <script>
				if (document.getElementById("mailType").checked) { 
	      				document.getElementById("plainMailTempl").style.display="none";
				} else {
	      				document.getElementById("htmlMailTempl").style.display="none";
				}
      	               	</script>
          <? } ?>
          <tr class="tdodd">
            <td align="left"><?php echo ADMINMAIL_FROM?></td>
            <td align="left"><input type="text" class="inputl" name="txtReply" size="33" tabindex="7" value="<?php print("$CONST_MAIL"); ?>">
            </td>
          </tr>
          <tr align="center">
            <td colspan="2" class="tdfoot"><input type="button" name="Button" value="<?php echo BUTTON_BACK ?>" class="button" OnClick="javascript:history.go(-1);">
              <input type="submit" name="Submit2" value="<?php echo BUTTON_SEND ?>" class="button"></td>
          </tr>
        </form>
    </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
