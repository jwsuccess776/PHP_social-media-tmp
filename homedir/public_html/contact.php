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
# Name: 		contact.php
#
# Description:  Page containing the company contact details ('Contact')
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
//include('session_handler.inc');
# retrieve the template
if ($_REQUEST['speeddating'] == 1) {
    $area="speeddating";
} else {
    if (isset($_SESSION['Sess_UserId']))
        $area = 'member';
    else
        $area = 'guest';
}
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>

    <td class="pageheader"><?php echo CONTACT_SECTION_NAME ?></td>
    </tr>
    <tr><td>
    <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <tr>
          <td  colspan="2" align="left" valign="top" class="tdhead">&nbsp;</td>
        </tr>
        <tr class="tdodd" >
          <td width="12%" align="left" valign="top" nowrap> <b><?php echo GENERAL_COMPANY_NAME?>:</b></td>
          <td width="88%" align="left" valign="top"> <?php print("$CONST_COMPANY"); ?></td>
        </tr>

        <tr class="tdeven" >
          <td rowspan="4" align="left" valign="top" nowrap> <b><?php echo GENERAL_ADDRESS?>:</b>   </td>
          <td align="left" valign="top"> <?php print("$CONST_ADDR1"); ?></td>
        </tr>
        <tr class="tdeven" >
          <td align="left" valign="top"> <?php print("$CONST_ADDR2"); ?></td>
        </tr>
        <tr class="tdeven" >
          <td align="left" valign="top"> <?php print("$CONST_ADDR3"); ?></td>
        </tr>
        <tr class="tdeven" >
          <td align="left" valign="top"> <?php print("$CONST_ADDR4"); ?></td>
        </tr>

        <tr class="tdodd" >
          <td align="left"> <b>Email:</b></td>
          <td align="left" valign="top"> <a href="mailto:<?php print("$CONST_MAIL"); ?>"><?php print("$CONST_MAIL"); ?></a></td>
        </tr>
        <tr >
          <td colspan="2" align="left" valign="top" class="tdfoot">&nbsp;</td>
        </tr>

      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>