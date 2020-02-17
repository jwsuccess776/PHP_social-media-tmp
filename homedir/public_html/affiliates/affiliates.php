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
# Name: 		affiliates.php
#
# Description:  affiliate scheme sign-up form
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');

# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">&nbsp;
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo AFF_JOIN_SECTION_NAME?></td>
  </tr>
  <tr>
    <td>
	<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/affiliates/prgaffiliate.php?mode=create" name="FrmAffiliate" onSubmit="return Validate_FrmAffiliate()" >
          <tr>
            <td valign="top" align="left" colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="top" class="tdhead">&nbsp; </td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo GENERAL_USERNAME?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtUsername" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top" ><?php echo AFF_ACCOUNT_SURNAME?></td>
            <td valign="top" align="left"> <input type="text" class="input" name="txtSurname" size="28"></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_FORENAME?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtForename" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_BUSINESS?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtBusiness" size="28"></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_ADDRESS?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtAddress" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_STREET?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtStreet" size="28"></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_CITY?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtTown" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_STATE?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtState" size="28"></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_ZIP?> Code</td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtZip" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top"><?php echo GENERAL_COUNTRY?></td>
            <td align="left" valign="top"> <select class="input" name="lstCountry" id="lstCountry" size="1"  tabindex='29'>
                <option value="0" selected>-- <?php echo GENERAL_CHOOSE?> --</option>
                <option value=""></option>
<?php
    include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
    $CountriesList = Geography::getCountriesList();
    foreach ($CountriesList as $countryrow)
    {
        echo '<option value="'.$countryrow->gcn_countryid.'">'.htmlspecialchars($countryrow->gcn_name).'</option>';
    }
?>
              </select></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_TELEPHONE?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtTelephone" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_FAX?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtFax" size="28"></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_EMAIL?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtEmail" size="28"></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_WEB?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtWebsite" size="28" value="http://"></td>
          </tr>
          <tr class="tdodd">
            <td align="left" valign="top"><?php echo AFF_ACCOUNT_CHEQUE?></td>
            <td align="left" valign="top"> <input type="text" class="input" name="txtPayable" size="28"></td>
          </tr>

          <tr class="tdeven">
            <td colspan="2" align="left" valign="top">
              <p align="center"><u><b>**
                <?php echo AFFILIATES_IMPORTANT?> **</b></u></td>
          </tr>
          <tr class="tdeven">
            <td align="left" valign="top">
              <p align="center"><b><?php echo AFFILIATES_TERMS?></b><b>
                </b></td>
            <td align="left" valign="top"><b>
              <textarea  class="inputl"rows="7" name="txtRules" cols="66"><?php echo getPageTemplate('affiliates_text');?></textarea>
              </b></td>
          </tr>
          <tr>
            <td valign="top" align="left" colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="top" class="tdodd"><b> <?php echo AFFILIATES_CLICK?>
              </b></td>
          </tr>
          <tr>
            <td valign="top" align="left" colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="top" class="tdfoot"> <center>
                <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
              </center></td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>