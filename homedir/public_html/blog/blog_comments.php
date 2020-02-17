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
# Name:         myblogs.php
#
# Description:  Returns individual member blogs
#
# Version:      7.2
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";

$db = & db::getInstance();

$emotions = new Emoticons();

# retrieve the template
if (isset($_SESSION['Sess_UserId']))
    $area = 'member';
else
    $area = 'guest';

if ($Sess_UserId){
    $private_list = $db->get_col("SELECT hot_userid FROM hotlist WHERE hot_advid = $Sess_UserId AND hot_private='Y'");
}
$private_list[] = -1;
$allow_private = join(",",$private_list);


$blog=$db->get_row("
                SELECT *, UNIX_TIMESTAMP(blg_datetime) as blgdate, adv_picture, adv_username, adv_userid
                FROM blogs
                    INNER JOIN adverts ON (blg_userid=adv_userid)
                WHERE
                    blg_approved='Y'
                    AND (blg_private = 'N' OR (blg_private = 'Y' AND blg_userid IN ($allow_private)) OR blg_userid = '$Sess_UserId')
                    AND blg_id = '".formGet('id')."'
                ");

if (!$blog) error_page("Incorrect post", GENERAL_USER_ERROR);

$adv = new Adverts($blog->blg_userid);
$adv->SetImage('small');
$blog->blg_message=$emotions->Parse($blog->blg_message);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td align=center> 
        <table width="100%" align="left" border="0"  cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
            <tr class="tdhead" align="right">
              <td colspan="2" class="blogdate"><?php echo date("D, j M Y G:i:s",$blog->blgdate); ?>&nbsp;</td>
            </tr>
            <tr class="tdeven">
              <td width="20%" align="center" valign="top"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$blog->adv_userid?>"><img src='<?= $CONST_LINK_ROOT?><?=$adv->adv_picture->Path?>' width=<?=$adv->adv_picture->w?> name='pic' hspace="5" border=0 id=mainpicture></a><br>
                <em class="small">
                <?=$blog->adv_username?>
                </em></td>
              <td width="80%" align="left" valign="top"><?php echo $blog->blg_message; ?>&nbsp;</td>
            </tr>
         </table>
    </td>
  </tr>
  <tr>
    <td align=center> 
       <?
            include $CONST_INCLUDE_ROOT."/comment/functions.php";
            show_comments('blog', $blog->blg_id, $viewer_type);
        ?>
    </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
