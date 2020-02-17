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
# Name:                 listbox.php
#
# Description:
#
# Version:                5.0
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include('../message.php');
require_once('../error.php');
require_once('../functions.php');
include('permission.php');

if (empty($_REQUEST['region_id'])) {
     header("Location: $CONST_LINK_ROOT/admin/adm_geography.php");
    exit;
}

if($_POST[act]) {
//    $_status = (isset($_POST[gcn_status]))?1:0;
    $sql_query = "UPDATE geo_region SET grg_name = '$_POST[grg_name]', grg_order = '$_POST[grg_order]' WHERE grg_regionid = '$_POST[region_id]'";
//print_r($sql_query);
    mysql_query($sql_query, $link);
     header("Location: $CONST_LINK_ROOT/admin/adm_geography.php");
    exit;
}

$query = "SELECT * FROM geo_region WHERE grg_regionid = '".$_REQUEST['region_id']."'";
$res = mysql_query($query);
$region = mysql_fetch_object($res);

$query="SELECT *
        FROM geo_region
        ORDER BY grg_order, grg_name";
$regions = mysql_query($query,$link) or die(mysql_error());

# retrieve the template
$area = 'member';

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo GEOGRAPHY_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr>
        <td>
            <table border="0" width="100%" cellpadding="2" cellspacing="10">
                <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_regionedit.php">
                <input type="hidden" name="region_id" value="<?=$_REQUEST['region_id']?>">
                <input type="hidden" name="act" value="save">
                <tr>
                    <td colspan="3" align="left" valign="top" class="tdhead">&nbsp; </td>
                </tr>
                <tr align=center>
                    <th align=right>
                        <?=SEARCH_REGION?>
                    </th>
                    <td align="left">
                        <input type="text" class="input" name="grg_name" value="<?=$region->grg_name?>" style="width:200px">
                    </td>
                    <td></td>
                </tr>
                <tr align=center>
                    <th align=right>
                        <?=ADM_CORDER?>
                    </th>
                    <td align="left">
                        <input type="text" class="input" name="grg_order" value="<?=$region->grg_order?>" style="width:200px">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td align="center"  colspan="3" class="tdfoot">
                        <input type=submit class=button name=SAVE value="<?=GENERAL_SAVE?>">
                        <input type=button class=button name=CANCEL value="Cancel" onClick="location.href='<?=$CONST_LINK_ROOT?>/admin/adm_geography.php'">
                    </td>
                </tr>
                </form>
           </table>

        </td>
    </tr>

</table>
<?php mysql_close( $link ); ?>
<?=$skin->ShowFooter($area)?>