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
# Name: 		aff_contact.php
#
# Description:  Company contact information
#
# # Version:      8.0
#
######################################################################

include('../db_connect.php');
include('aff_session_handler.inc');

# retrieve the template
$area = 'affiliate';

mysql_close($link);

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <?
	require('aff_menu.php');
?>
  <tr>

    <td>

	<table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">


        <tr>
          <td colspan="2" align="left" valign="top" class="tdhead">&nbsp;</td>
        </tr>
        <tr class="tdodd">
          <td valign="top" align="left"><b><?php echo AFF_CONTACT_EMAIL?>:</b></td>
          <td valign="top" align="left"><a href="mailto:<?php print("$CONST_AFFMAIL"); ?>"><?php echo $CONST_AFFMAIL ?></a></td>
        </tr>
        <tr  class="tdeven">

          <td valign="top" align="left">
              <b><?php echo AFF_CONTACT_COMPANY_NAME?>:</b></td>
          <td valign="top" align="left">
              <?php print("$CONST_COMPANY"); ?></td>
        </tr>

        <tr class="tdodd">

          <td valign="top" align="left">
              <b><?php echo AFF_CONTACT_ADDRESS?>:</b></td>
          <td valign="top" align="left">
              <?php print("$CONST_COMPANY"); ?><br>
      <?php print("$CONST_ADDR1"); ?><br>
      <?php print("$CONST_ADDR2"); ?><br>
      <?php print("$CONST_ADDR3"); ?><br>
      <?php print("$CONST_ADDR4"); ?></td>
        </tr>

        <tr>

          <td colspan="2" align="left" valign="top"  class="tdfoot">&nbsp;

            </td>
        </tr>

</table>
	 </td>
  </tr>

</table>
<?=$skin->ShowFooter($area)?>