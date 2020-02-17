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

# Name:                 prgauthads.php

#

# Description:  Administrator advert authorisation processing

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include_once('../validation_functions.php'); 

include('../session_handler.inc');

include('../functions.php');

include('../error.php');

include('../message.php');

include('permission.php');



$mode=$_GET['mode'];

# retrieve the template

$area = 'member';



# process an authorisation

if ($mode=='next') {

    $txtBlog=$db->escape(formGet('txtBlog'));

    $lstPrivate=sanitizeData($_POST['lstPrivate'], 'xss_clean'); 

    $rdoApprove=sanitizeData($_POST['rdoApprove'], 'xss_clean');

    $hiddenuserid=sanitizeData($_POST['hiddenuserid'], 'xss_clean');

    $hiddenname=sanitizeData($_POST['hiddenname'], 'xss_clean');

    $hiddenemail=sanitizeData($_POST['hiddenemail'], 'xss_clean');

    $offset=sanitizeData($_POST['offset'], 'xss_clean');

    $reason=stripslashes(sanitizeData($_POST['reason'], 'xss_clean'));

    if ($rdoApprove=='Approve') {

            # approved will show up in search

            $query="update blogs set blg_message= \"$txtBlog\", blg_approved='Y' where blg_id=$hiddenid";

            if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



            $data['ReceiverName'] = $hiddenname;

            $data['CompanyName'] = $CONST_COMPANY;

            $data['Url'] = $CONST_URL;

            $data['SupportEmail'] = $CONST_SUPPMAIL;



            list($type,$message) = getTemplateByName("Approve_Blog",$data,getDefaultLanguage($hiddenuserid));

            send_mail ("$hiddenemail", "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHBLOG_APR, $message,$type,"ON");



    } elseif ($rdoApprove=='Delete') {

            # delete blog and associated comments

			$result=$db->get_results("DELETE FROM blogs WHERE blg_id=$hiddenid");

			$result=$db->get_results("DELETE FROM comments WHERE ent_id=$hiddenid AND type='blog'");



    } elseif ($rdoApprove=='Reject') {

            # rejected will not show up in search or for approval until user amends

            $query="update blogs set blg_message= \"$txtBlog\", blg_approved='R' where blg_id=$hiddenid";

            if (!mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



            $reason=trim($reason);

            if (empty($reason)) $reason="NULL";



            $data['ReceiverName'] = $hiddenname;

            $data['CompanyName'] = $CONST_COMPANY;

            $data['Url'] = $CONST_URL;

            $data['Reason'] = $reason;

            $data['SupportEmail'] = $CONST_SUPPMAIL;



            list($type,$message) = getTemplateByName("Reject_Blog",$data,getDefaultLanguage($hiddenuserid));

            send_mail ("$hiddenemail", "$CONST_MAIL", "$CONST_COMPANY ".PRGAUTHBLOG_UNAPR, $message,$type,"ON");



    } elseif ($rdoApprove=='Skip') {

        $offset++;

    }

}



if (!$offset) $offset=0;

$result = mysqli_query($globalMysqlConn,"SELECT *, UNIX_TIMESTAMP(blg_datetime) as blgdate, mem_username, mem_email FROM blogs

                    LEFT JOIN members ON (blg_userid=mem_userid)

                           WHERE blg_approved='N' LIMIT $offset,1");

$TOTAL = mysqli_num_rows($result);

# if nothing is returned then show error otherwise get data

if ($TOTAL < 1) {

        $error_message=PRGAUTHBLOG_TEXT;

        display_page($error_message,PRGAUTHBLOG_TEXT1);

} else {

        $sql_array = mysqli_fetch_object($result);

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



    <td class="pageheader"><?php echo ADM_BLOGS_APPROVE_SECTION_NAME ?> </td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr><td>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/admin/prgauthblogs.php?mode=next' name="FrmAuthorise">

            <input type='hidden' name="hiddenuserid" value="<?php print("$sql_array->blg_userid"); ?>">

            <input type='hidden' name="hiddenid" value="<?php print("$sql_array->blg_id"); ?>">

            <input type='hidden' name="hiddenemail" value="<?php print("$sql_array->mem_email"); ?>">

            <input type='hidden' name="hiddenname" value="<?php print("$sql_array->mem_username"); ?>">

          <input type="hidden" name="offset" value="<?=$offset?>" />

          <tr>

            <td>

                <table width="100%" align="left" border="0" cellspacing="0" cellpadding="5">

                    <tr class="tdhead" >

                      <td align="left"><strong><?=GENERAL_POSTED?>: <i><?php print("$sql_array->mem_username"); ?></i></strong></td>

                      <td align="right">&nbsp;</td>

                    </tr>

                    <tr class="tdhead" >

                        <td align="left"><?php $text=($sql_array->blg_private=='Y')?GENERAL_PRIVATE:GENERAL_PUBLIC; echo $text;  ?>&nbsp;</td>

                        <td align="right"><?php echo date("D, j M Y G:i:s",$sql_array->blgdate); ?>&nbsp;</td>

                    </tr>

                    <tr class="tdeven">

                        <td colspan="2"><textarea name="txtBlog" rows="5" cols="80" class="inputl"><?php echo $sql_array->blg_message; ?></textarea></td>

                    </tr>

                    <tr align="right" class="tdfoot"><td colspan="2" >&nbsp;</td></tr>

                    <tr align="right"><td colspan="2">&nbsp;</td></tr>

                </table>

          </td>

          </tr>

          <tr align="center" class="tdodd">

            <td colspan="4" ><input type="radio" name="rdoApprove" value="Approve"> <?php echo AFF_AUTHORISE_APPROVE?>&nbsp; 

			<input type="radio" name="rdoApprove" value="Reject" > <?php echo AFF_AUTHORISE_REJECT?>&nbsp; 

			<input type="radio" name="rdoApprove" value="Delete"> <?php echo MYDELBLOGS_SECTION_NAME?>&nbsp; 

			<input type="radio" name="rdoApprove" value="Skip" checked> <?php echo AFF_AUTHORISE_SKIP?><br> 

             <?php echo AFF_AUTHORISE_REASON?><input name='reason' type='text' class="inputl" size='30'></td>

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