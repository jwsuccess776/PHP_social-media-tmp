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
# Name: 		viewclub.php
#
# Description:  Displays the profile input page (after advert)
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
# retrieve the template
$area = 'member';

# retrieve the clubs
$query="SELECT * FROM clubs WHERE cl_clubid='$clubid'";
$retval=mysql_query($query,$link) or die(mysql_error());
$row = mysql_fetch_array($retval);
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo VIEWCLUB_SECTION_NAME ?></td>
    </tr>
    <tr>
      <td>

<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="2"  align="left" nowrap class="tdhead"><font size="2" face="Arial">
            <strong>
            <?= $row[cl_clubname]?>
            </strong> </font><font size="2" face="Arial">&nbsp; </font> </td>
        </tr>
        <tr class="tdodd">
          <td align="left"><font size="2" face="Arial, Helvetica, sans-serif">
            <?= $row[cl_address]?>
            </font></td>
          <td align="left"><font size="2" face="Arial">
            <?php if (trim($row[cl_picture])<>"") echo "<a rel='lightbox' href=\"$row[cl_picture]\"><img src=\"$row[cl_picture]\" width=\"151\" height=\"48\" border=\"0\"></a>" ?>
            </font></td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdeven"><font size="2" face="Arial, Helvetica, sans-serif">
            <?= $row[cl_phone]?>
            </font></td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdodd"><font size="2" face="Arial, Helvetica, sans-serif"><a href="<?= $row[cl_website]?>" target="_blank">
            <?= $row[cl_website]?>
            </a></font></td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdfoot">&nbsp;</td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdhead"><font size="2" face="Arial, Helvetica, sans-serif"><?php echo GENERAL_DESCRIPTION ?></font></td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdodd"><font size="2" face="Arial, Helvetica, sans-serif">
            <?= $row[cl_description]?>
            </font></td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdfoot">&nbsp;</td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdhead"><font size="2" face="Arial, Helvetica, sans-serif">
            <?php echo GENERAL_REVIEWS ?></font></td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdodd">
            <?= $row[cl_review]?>
          </td>
        </tr>
        <tr>
          <td  colspan="2" align="left" class="tdfoot">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>