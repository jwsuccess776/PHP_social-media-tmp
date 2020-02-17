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
# Name:                 home.php
#
# Description:
#
# Version:                7.2
#
######################################################################
include('../db_connect.php');
//include('../session_handler.inc');
include('../message.php');
include('../functions.php');

include('../error.php');
# retrieve the template
$area = 'speeddating';

$query="SELECT * FROM members WHERE mem_userid='$Sess_UserId'";
$retval=mysqli_query($globalMysqlConn,$query);
$member = mysqli_fetch_object($retval);

?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td class="pageheader">
      <?=SD_HOME_SECTION_NAME?>
    </td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <tr>
          <td colspan="2" align="left" class="tdhead" >Welcome
            <?=$member->mem_forename?>
            <?=$member->mem_surname?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left" class="td1" >
            <?=SD_HOME_WELLCOME?>
          </td>
        </tr>
<!--
        <tr>
          <td colspan="2" align="left" class="td2" >
            <?=SD_HOME_INFO?>
          </td>
        </tr>
-->
        <tr valign="top">
          <td align="left" class="td3" > <table border="0" cellpadding="3" cellspacing="1" width="100%" class="border_full">
              <tr class="tdodd">
                <td class="td2">
                  <?=SD_HOME_SEC1_TITLE?>
                </td>
              </tr>
              <tr>
                <td class="td1" >
                  <?=SD_HOME_SEC1_BODY?>
                </td>
              </tr>
              <tr>
                <td class="td1 link"><a href="<?=$CONST_LINK_ROOT?>/speeddating/event_booked.php" style="color:#000; text-decoration: none;"><div><b>
                  <?=SD_HOME_SEC1_LINK?>
                  </b></div></a></td>
              </tr>
            </table></td>
          <td align="left" class="td3" > <table border="0" cellpadding="3" cellspacing="1" width="100%">
              <tr>
                <td class=td2>
                  <?=SD_HOME_SEC4_TITLE?>
                </td>
              </tr>
              <tr class="td1">
                <td>
                  <?=SD_HOME_SEC4_BODY?>
                </td>
              </tr>
              <tr class="td1 link">
                <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/prgamendreg.php" style="color:#000; text-decoration: none;"><div><b>
                  <?=SD_HOME_SEC4_LINK?>
                  </b></div></a></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="2" align="left" class="tdfoot" >&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
