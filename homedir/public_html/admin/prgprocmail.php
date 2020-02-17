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

# Name:         prgprocmail.php

#

# Description:  Adds and removes additional videos for members

#

# Version:      8.0

#

######################################################################



include('../db_connect.php');

include_once('../validation_functions.php'); 

include('../session_handler.inc');

include('../message.php');

include('../error.php');

include('permission.php');

restrict_demo();

$area='member';



set_time_limit(0);



if (isset($_POST['chkFile'])) $chkFile=sanitizeData($_POST['chkFile'], 'xss_clean');

if (isset($_POST['chkType'])) $chkType=sanitizeData($_POST['chkType'], 'xss_clean');   



if (isset($_POST['chkAffiliates'])) $chkAffiliates=sanitizeData($_POST['chkAffiliates'], 'xss_clean');    

$txtAddress=sanitizeData($_POST['txtAddress'], 'xss_clean');  



$txtMessage=($chkType) ? sanitizeData($_POST['txtMessageHtml'], 'xss_clean') : sanitizeData($_POST['txtMessage'], 'xss_clean');    



$txtReply=sanitizeData($_POST['txtReply'], 'xss_clean');    

$txtSubject=sanitizeData($_POST['txtSubject'], 'xss_clean');     

$lstGender=sanitizeData($_POST['lstGender'], 'xss_clean');       

$lstStatus=sanitizeData($_POST['lstStatus'], 'xss_clean');    

$lstLanguage=sanitizeData($_POST['lstLanguage'], 'xss_clean');     

$chkAllusers=sanitizeData($_POST['chkAllusers'], 'xss_clean');     

$chkSpeeddating=sanitizeData($_POST['chkSpeeddating'], 'xss_clean');     

$lstSendType=sanitizeData($_POST['lstSendType'], 'xss_clean');   

$chkIntro=sanitizeData($_POST['chkIntro'], 'xss_clean');   

$txtSubject=substr(trim($txtSubject),0,60);



if (strlen($txtSubject) < 2) {

                $error_message=PRGSENDMAIL_TEXT1;

                error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtMessage) < 20) {

                $error_message=PRGSENDMAIL_TEXT2;

                error_page($error_message,GENERAL_USER_ERROR);

}

#set the mail type

if (isset($chkType)) {

    $type="html";

} else {

    $type="text";

}

$area = 'member';

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

        <?php

                if ($Sess_UserType == "A") {

                    print("<a href='$CONST_LINK_ROOT/admin/index.php'><img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/mem_$Sess_Userlevel.gif' width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'>");

                } else {

                    print("<img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/mem_$Sess_Userlevel.gif' width='$CONST_MEMIMAGE_WIDTH' height='$CONST_MEMIMAGE_HEIGHT'>");

                }

           ?>

    </td>

  </tr>

  <tr>

    <td class="pageheader">&nbsp;</td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>

     <div id=progress>

        <h3>Putting emails to mail queue. Wait please.</h3>

        <div style="overflow:auto; width:100%; height:400px " id=log>

<?php 

# check to see if it is a single address mail

############################################

if (strlen($txtAddress) > 1) {

    $recno=0;

    $recnum=1;

    $message=stripslashes($txtMessage);

    if ($type == "html")

        $message.="<p align='left'><font size='1'>".sprintf(PRGADMINMAIL_TEXT,$CONST_LINK_ROOT,$txtAddress);

    send_mail ("$txtAddress", "$txtReply", "$txtSubject", "$message", "$type","ON",$lstSendType,'UTF-8',true);

    $recno++;

    flush();

    sleep(2);

    print("$recno) $txtAddress<br>");

# check to see if it is a file of addresses

############################################

} elseif (isset($chkFile)) {

    $recno=0;

    $recnum=1;

    $fp = @fopen ($CONST_INCLUDE_ROOT."email.txt", "r");

    if ($fp) {

    while(!feof($fp)) {

        $txtAddress = fgets($fp,200);

        $txtAddress=trim($txtAddress);

        $message=stripslashes($txtMessage);

        send_mail ("$txtAddress", "$txtReply", "$txtSubject", "$message", "$type","ON",$lstSendType,'UTF-8',true);

        $recno++;

        flush();

        print("$recno) $txtAddress<br>");

        if (is_int($recno/500)) {

            print("<br>".PRGADMINMAIL_SLEEP."<br><br>");

            flush();

            sleep(2);

        }

    }

    fclose($fp);

    } else {

        error_page("File {$CONST_INCLUDE_ROOT}email.txt not found",GENERAL_SYSTEM_ERROR);

    }

# check to see if it is a speeddating subscribers addresses

###########################################################

} elseif (isset($chkSpeeddating)) {

        switch ($lstGender) {

            case 'A':

                $condition = "";

                break;

            case 'M':

                $condition = " WHERE (sub_sex = 'M')";

                break;

            case 'F':

                $condition = " WHERE (sub_sex = 'F')";

                break;

        }

        $query="SELECT sub_email FROM sd_subscribe $condition";

        $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

        $recnum=mysqli_num_rows($result);

        $recno=0;

        print("$query<br><br>");

        # loop through selected target addresses

        while ($sql_array = mysqli_fetch_object($result)) {

                if (isset($chkIntro)) {

                    $message=stripslashes($txtMessage);

                } else {

                    $message=stripslashes($txtMessage);

                    if ($type == "html")

                        $message.="<p align='left'><font size='1'>".

                        sprintf(PRGADMINMAIL_TEXT,$CONST_LINK_ROOT,$sql_array->sub_email);

                }

                send_mail ("$sql_array->sub_email", "$txtReply", "$txtSubject", "$message", "$type","ON", $lstSendType,'UTF-8',true);

                $recno++;

                flush();

                print("$recno) $sql_array->sub_email<br>");

                if (is_int($recno/500)) {

                    print("<br>".PRGADMINMAIL_SLEEP."<br><br>");

                    flush();

                    sleep(2);

                }

        }

        // mysqli_close( $link );

# otherwise the source is from the database

############################################

} elseif (isset($chkAffiliates)){

        $query="SELECT * FROM affiliates";

        $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

        $recnum=mysqli_num_rows($result);

        $recno=0;

        # loop through selected target addresses

        while ($sql_array = mysqli_fetch_object($result)) {

                if (isset($chkIntro)) {

                    $message=GENERAL_DEAR." $sql_array->aff_username\n\n".stripslashes($txtMessage);

                } else {

                    $message=stripslashes($txtMessage);

                    if ($type == "html")

                        $message.="<p align='left'><font size='1'>".

                        sprintf(PRGADMINMAIL_TEXT,$CONST_LINK_ROOT,$sql_array->aff_email);

                }

                send_mail ("$sql_array->aff_email", "$txtReply", "$txtSubject", "$message", "$type","ON", $lstSendType,'UTF-8',true);

                $recno++;

                flush();

                print("$recno) $sql_array->aff_username - $sql_array->aff_email<br>");

                if (is_int($recno/500)) {

                    print("<br>".PRGADMINMAIL_SLEEP."<br><br>");

                    flush();

                    sleep(2);

                }

        }



} else {

    if (isset($chkAllusers)) {$condition1=" WHERE (mem_newsletter = 1 OR mem_newsletter = 0)";} else {$condition1=" WHERE (mem_newsletter = 1)";}

        switch ($lstGender) {

            case 'A':

                $condition2 = "";

                break;

            case 'M':

                $condition2 = " AND (mem_sex = 'M')";

                break;

            case 'F':

                $condition2 = " AND (mem_sex = 'F')";

                break;

            case 'C':

                $condition2 = " AND (mem_sex = 'C')";

                break;

        }

        switch ($lstStatus) {

            case 'All':

                $condition3 = "";

                break;

            case 'Premium':

                $condition3 = " AND (DATE_FORMAT(mem_expiredate,'%Y%m%d') > DATE_FORMAT(NOW(),'%Y%m%d'))";

                break;

            case 'Standard':

                $condition3 = " AND (DATE_FORMAT(mem_expiredate,'%Y%m%d') < DATE_FORMAT(NOW(),'%Y%m%d'))";

                break;

            case 'Inactive':

                $condition3 = " AND (DATE_FORMAT(mem_lastvisit , '%Y%m%d') < DATE_FORMAT(FROM_DAYS( TO_DAYS( curdate( ) ) - 90 ),'%Y%m%d'))";

                break;

            case 'Rejected':

                $condition3 = " AND adv_approved = 2";

                break;

       }



        if ($lstLanguage != "All") $condition4=" AND lang_id='$lstLanguage'"; else $condition4="";



        $query="SELECT mem_username, mem_email, adv_approved FROM members LEFT JOIN adverts ON (mem_userid=adv_userid) $condition1$state1$condition2$condition3$condition4";

        $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

        $recnum=mysqli_num_rows($result);

        $recno=0;



        # loop through selected target addresses

        while ($sql_array = mysqli_fetch_object($result)) {

                if (isset($chkIntro)) {

                    $message=GENERAL_DEAR." $sql_array->mem_username\n\n".stripslashes($txtMessage);

                } else {

                    $message=stripslashes($txtMessage);

                    if ($type == "html")

                        $message.="<p align='left'><font size='1'>".

                        sprintf(PRGADMINMAIL_TEXT,$CONST_LINK_ROOT,$sql_array->mem_email);

                }

                send_mail ($sql_array->mem_email, $txtReply, "$txtSubject", "$message", "$type","ON", $lstSendType,'UTF-8',true);

                $recno++;

                flush();

                print("$recno) $sql_array->mem_username - $sql_array->mem_email<br>");

                if ($recno%500 == 0) {

                    print("<br>".PRGADMINMAIL_SLEEP."<br><br>");

                    flush();

                    sleep(2);

                }

        }

}



print("<br><br>".PRGSEARCH_TOTAL." : $recno");

// mysqli_close( $link );

?>		</div>

        <p align="center"><input type='button' name='Submit' value="<?=BUTTON_BACK?>" class='button' onClick="parent.window.location='<?=$CONST_ADMIN_LINK_ROOT?>/adminmail.php'"></p>

     </div>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>