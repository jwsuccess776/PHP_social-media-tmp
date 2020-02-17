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
# Name:         message.php
#
# Description:  Handles errors occuring on the member side
#
# Version:     7.2
#
######################################################################
function display_page($e_message,$type) {
    global $Sess_UserId;
    global $Sess_UserType;
    global $Sess_Userlevel;
    global $CONST_INCLUDE_ROOT;
    global $CONST_LINK_ROOT;
    global $CONST_IMAGE_LANG;
    global $CONST_TABLE_WIDTH;
    global $CONST_TABLE_CELLSPACING;
    global $CONST_TABLE_CELLPADDING;
    global $extensionsList;

    foreach ($extensionsList as $extension=>$generator) global ${$extension};

    include_once('../db_connect.php');
    $skin =& Skin::GetInstance();

    $area = 'speeddating';
    echo $skin->ShowHeader($area);?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td class="pageheader"><?php echo ABOUT_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td height="20" align="left" class="tdhead" >&nbsp;</td>
        </tr>
        <tr>
          <td height="20" align="left" valign="top" class="tdodd">
            <?=$type?>
          </td>
        </tr>
        <tr>
          <td height="20" align="left" class="tdeven" >
            <?=$e_message?>
          </td>
        </tr>
        <tr>
          <td class="tdfoot">&nbsp; </td>
        </tr>
      </table></td>
    </tr>
  </table>

<?php
    echo $skin->ShowFooter($area);
    exit;
}

function restrict_demo()
{
    global $DEMO;
    if($DEMO)
    {
        display_page(GENERAL_DEMO_RESTRICTION_ERROR_DESCRIPTION,GENERAL_DEMO_RESTRICTION_ERROR);
        exit;
    }
}
?>
