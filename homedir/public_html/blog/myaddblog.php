<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
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
include($CONST_INCLUDE_ROOT.'/session_handler.inc');
include($CONST_INCLUDE_ROOT.'/functions.php');
include($CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";
include_once __INCLUDE_CLASS_PATH."/class.Tagging.php";
include_once('../validation_functions.php');

$db = & db::getInstance();
$emotions = new Emoticons();
$tagging = new Tagging('blog');

if (isset($_REQUEST['Submit'])) {
    $approved = ($option_manager->GetValue('authorisead_blog')) ? 'Y' : 'N';
    $tags = preg_split("/,|;|\s/", formGet('tags'));

    if (isset($_REQUEST['action']) && $_REQUEST['action']=='edit') {
        $txtBlog=$db->escape(nl2br(strip_tags(formGet('txtBlog'))));
        $private=sanitizeData($_REQUEST['lstPrivate'], 'xss_clean');  
        $blogid=sanitizeData($_REQUEST['blogid'], 'xss_clean');    
        $db->query("UPDATE blogs SET blg_message=\"$txtBlog\", blg_private='$private', blg_approved='$approved' WHERE blg_id=$blogid");
    } else{
        $txtBlog=$db->escape(nl2br(strip_tags(formGet('txtBlog'))));
        $private=sanitizeData($_REQUEST['lstPrivate'], 'xss_clean'); 
        $db->query("INSERT INTO blogs (blg_userid, blg_message, blg_private, blg_approved) VALUES($Sess_UserId,\"$txtBlog\",'$private','$approved')");
        $blogid = $db->insert_id;
    }
    if (is_array($tags) && count($tags)) $tagging->set($blogid, $tags);
    header("Location: ".$CONST_BLOG_LINK_ROOT."/myblogs.php");

} elseif (isset($_REQUEST['action']) && $_REQUEST['action']=='edit') {
    $blogid=sanitizeData($_REQUEST['blogid'], 'xss_clean');  
    $sql_array=$db->get_row("
                            SELECT * FROM blogs
                            WHERE blg_userid = '$Sess_UserId'
                            AND blg_id= $blogid"
                            );
}

# retrieve the template
$area = 'member';
$blog_id= isset($_REQUEST['blogid']) ? sanitizeData($_REQUEST['blogid'], 'xss_clean') : "";   
$action= isset($_REQUEST['action']) ? sanitizeData($_REQUEST['action'], 'xss_clean') : "";

?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td><?include_once "blog_menu.inc.php"?></td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo MYADDBLOGS_SECTION_NAME ?></td>
  </tr>
  <tr>
      <td>
        <form name="frmBlog" action="<?php echo $CONST_BLOG_LINK_ROOT?>/myaddblog.php" method="post">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <input type="hidden" name="blogid" value="<?php echo $blog_id ?>">
   <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
            <tr class="tdhead" align="right"><td colspan="3" class="tdhead" ><?php echo date("D, j M Y G:i:s"); ?>&nbsp;</td></tr>
            <tr class='tdodd'>
                <td colspan="3"><?php echo GENERAL_TAGS?>: <input type='text' name='tags' class='inputl' value="<?if ($blogid) echo $tagging->getTagsList($blogid, 'string');?>"  ></td>
            </tr>
            <tr class="tdeven">
                <td rowspan="3" colspan="1" >
                <textarea id=blog_text name="txtBlog" rows="5" cols="80" class="inputl"><?php echo strip_tags($sql_array->blg_message) ?></textarea>
                </td>
                <td width="5" rowspan="3" align="left" valign="middle" >&nbsp;</td>
              <td height="25" colspan="1" align="left" valign="middle"><em><?php echo MYBLOGS_EMOTICONS ?></em></td>
            </tr>

            <tr class="tdeven">
                <td colspan=3><?echo $emotions->DisplayIcons('blog_text')?></td>
             </tr>
            <tr class="tdodd"><td colspan="1"><?php echo GENERAL_PRIVACY ?>&nbsp;
            <select name="lstPrivate" class="input">
                <option value="Y" <?php if ($sql_array->blg_private =='Y') echo "selected"; ?>><?php echo GENERAL_PRIVATE ?></option>
                <option value="N" <?php if ($sql_array->blg_private =='N') echo "selected"; ?>><?php echo GENERAL_PUBLIC ?></option>
            </select></td>
        </tr>
            <tr class="tdfoot"><td colspan="3">&nbsp;</td>
        </tr>
            <tr>
                <td colspan="3">
                <input name="Submit" type="submit" class="button" value="<?php echo BUTTON_UPDATE ?>" >
                </td>
            </tr>
        </table>
    </form>
  </td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>