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
# Name:                 prgauthads.php
#
# Description:  Administrator advert authorisation processing
#
# Version:                7.2
#
######################################################################
include('../db_connect.php');
include('../session_handler.inc');
include('../functions.php');
include('../error.php');
include('../message.php');
include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
include('permission.php');

$mode=$_GET['mode'];
# retrieve the template
$area = 'member';

# process an authorisation
if ($mode=='next') {
    $txtBlog=$db->escape(nl2br(strip_tags($_POST['txtBlog'])));
    $lstPrivate=$_POST['lstPrivate'];
    $rdoApprove=$_POST['rdoApprove'];
    $hiddenuserid=$_POST['hiddenuserid'];
    $hiddenname=$_POST['hiddenname'];
    $hiddenemail=$_POST['hiddenemail'];
    $offset=$_POST['offset'];
    $reason=stripslashes($_POST['reason']);
    if ($rdoApprove=='Approve') {
            # approved will show up in search
            $post = $db->get_row("SELECT * FROM bb_posts WHERE post_id=$hiddenid");
            $db->query("update bb_posts set post_text= \"$txtBlog\", post_approved ='1' where post_id=$post->post_id");
            if ($db->get_var("SELECT count(*) cnt FROM bb_posts WHERE  subtopic_id = '$post->subtopic_id'") == 1) {
                $db->query("update bb_subtopics set subtopic_status ='1' where subtopic_id=$post->subtopic_id");
            }
            $data['ReceiverName'] = $hiddenname;
            $data['CompanyName'] = $CONST_COMPANY;
            $data['Url'] = $CONST_URL;
            $data['SupportEmail'] = $CONST_SUPPMAIL;

            list($type,$message) = getTemplateByName("Approve_Forum",$data,getDefaultLanguage($hiddenuserid));
            send_mail ("$hiddenemail", "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHFORUM_APR, $message,$type,"ON");

    } elseif ($rdoApprove=='Reject') {
            # rejected will not show up in search or for approval until user amends
            $query="update bb_posts set post_text= \"$txtBlog\", post_approved='2' where post_id=$hiddenid";
            $db->query($query);

            $reason=trim($reason);
            if (empty($reason)) $reason="NULL";

            $data['ReceiverName'] = $hiddenname;
            $data['CompanyName'] = $CONST_COMPANY;
            $data['Url'] = $CONST_URL;
            $data['Reason'] = $reason;
            $data['SupportEmail'] = $CONST_SUPPMAIL;

            list($type,$message) = getTemplateByName("Reject_Forum",$data,getDefaultLanguage($hiddenuserid));
            send_mail ("$hiddenemail", "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHFORUM_UNAPR, $message,$type,"ON");

    } elseif ($rdoApprove=='Skip') {
        $offset++;
    }
}

if (!$offset) $offset=0;
$oPost = $db->get_row("SELECT *, UNIX_TIMESTAMP(post_time) as postdate, mem_username, mem_email
						FROM bb_posts p
            	        	LEFT JOIN members ON (p.poster_id=mem_userid)
       		             	INNER JOIN bb_subtopics st ON (p.subtopic_id=st.subtopic_id)
                        WHERE post_approved='N' LIMIT $offset,1");
if ($oPost->post_ext){
    $File = new ImageFile();
    $File->Init($oPost->post_id ,'forum',$oPost->post_ext);
    $image = $File->getInfo('small');
    $image_full = $File->getInfo('');
}
# if nothing is returned then show error otherwise get data
if (!$oPost) {
        $error_message=PRGAUTHFORUM_TEXT;
        display_page($error_message,PRGAUTHFORUM_TEXT1);
}
?>
<?=$skin->ShowHeader($area)?>
  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
    <tr>
      <td align="right">
      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
      </td>
    </tr>
    <tr>

    <td class="pageheader"><?php echo ADM_FORUM_APPROVE_SECTION_NAME ?> </td>
    </tr>
  <tr>
    <td><? include("admin_menu.inc.php");?></td>
  </tr>
    <tr><td>
        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgauthforum.php?mode=next' name="FrmAuthorise">
            <input type='hidden' name="hiddenuserid" value="<?php print("$oPost->poster_id"); ?>">
            <input type='hidden' name="hiddenid" value="<?php print("$oPost->post_id"); ?>">
            <input type='hidden' name="hiddenemail" value="<?php print("$oPost->mem_email"); ?>">
            <input type='hidden' name="hiddenname" value="<?php print("$oPost->mem_username"); ?>">
          <input type="hidden" name="offset" value="<?=$offset?>" />
          <tr>
            <td>
                <table width="100%" align="left" border="0" cellspacing="0" cellpadding="5">
                    <tr class="tdhead" >
                        <td align="left">&nbsp;</td>
                        <td align="right"><?php echo date("D, j M Y G:i:s",$oPost->postdate); ?>&nbsp;</td>
                    </tr>
                    <tr class="tdeven">

                        <td colspan="2">
	                        <?php echo $oPost->subtopic_title; ?><br>
	                        <textarea name="txtBlog" rows="5" cols="80" class="inputl"><?php echo $oPost->post_text; ?></textarea>
	                        <?php if($image->Path){?>
	                        <a rel='lightbox' href="<?=$CONST_LINK_ROOT."/".$image_full->Path?>"><img src="<?=$CONST_LINK_ROOT.$image->Path?>" width="<?=$image->w?>" border=0></a>
	                        <?php } ?>
                        </td>
                    </tr>
                    <tr align="right" class="tdfoot"><td colspan="2" >&nbsp;</td></tr>
                    <tr align="right"><td colspan="2">&nbsp;</td></tr>
                </table>
          </td>
          </tr>
          <tr align="center" class="tdodd">
            <td colspan="4" > <input type="radio" name="rdoApprove" value="Approve">
              <?php echo AFF_AUTHORISE_APPROVE?>&nbsp; <input type="radio" name="rdoApprove" value="Reject" >
              <?php echo AFF_AUTHORISE_REJECT?> &nbsp; <input type="radio" name="rdoApprove" value="Skip" checked>
              <?php echo AFF_AUTHORISE_SKIP?><br> <?php echo AFF_AUTHORISE_REASON?>
              <input name='reason' type='text' class="inputl" size='30'></td>
          </tr>
          <tr align="center">
            <td colspan="4" class="tdfoot"> <input type="submit" name="Submit" value="<?php echo BUTTON_SUBMIT ?>" class="button">
            </td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
<?=$skin->ShowFooter($area)?>