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

# Name:                 prgsendmail.php

#

# Description:  member mail sending program

#

# Version:               7.3

#

######################################################################



include('db_connect.php');
include_once 'validation_functions.php';
include('session_handler.inc');

include('functions.php');

include('error.php');



$cnt = $db->get_var("SELECT count(*) FROM adverts WHERE adv_userid='$Sess_UserId' AND adv_paused='N' AND adv_approved = 1");

if ($cnt < 1) {

    error_page(PRGSENDMAIL_TEXT7,GENERAL_USER_ERROR);

}



$txtSubject=sanitizeData(formGet('txtSubject'), 'xss_clean');

$txtMessage=sanitizeData(formGet('txtMessage'), 'xss_clean');

$userid=sanitizeData(formGet('userid'), 'xss_clean');

$myhandle=sanitizeData(formGet('myhandle'), 'xss_clean');



# retrieve the template

$area = 'member';



# Check the values received from the form



$txtSubject=substr(trim($txtSubject),0,60);

$txtMessage=trim($txtMessage);



if (strlen($txtSubject) < 2) {

                $error_message=PRGSENDMAIL_TEXT1;

                error_page($error_message,GENERAL_USER_ERROR);

}

if (strlen($txtMessage) < 20) {

                $error_message=PRGSENDMAIL_TEXT2;

                error_page($error_message,GENERAL_USER_ERROR);

}

$query = "  SELECT *

            FROM blockmail

            WHERE blk_receiverid = '$userid'

            AND blk_senderid =  '$Sess_UserId'";

$res = mysqli_query($globalMysqlConn,$query);

if (mysqli_num_rows($res)) {

        $error_message=PRGSENDMAIL_TEXT9;

        error_page($error_message,GENERAL_USER_ERROR);

}



####################

# ANTI SPAM CHECK

####################

if ($CONST_SPAM_ON=='Y'){

        $spam=false;

        $spamtxtMessage=$db->escape($txtMessage);

        $spamtxtSubject=$db->escape($txtSubject);

        $spam_result=$db->get_var("select count(*) from messages where msg_title='$spamtxtSubject' and msg_text='$spamtxtMessage' and msg_senderid=$Sess_UserId");

//        $db->debug();

        if ($spam_result >= $CONST_SPAM_TOLERANCE) {

                $spam_t=true;

        } else {

                require_once __INCLUDE_CLASS_PATH."/class.SpamChecker.php";

                $spamChecker = new SpamChecker ();

                $aSpam = $spamChecker -> checkText ( $txtMessage );

                if (count($aSpam) >= $option_manager->GetValue('spam_limit')) $spam_w=true;

        }

        if ($spam_t==true || $spam_w==true) {

                $qry_spam="select * from members where mem_userid=$Sess_UserId";

                $mem_array= $db->get_row($qry_spam);

                if ($spam_w) {

                    $reason = "In the message he/she used words [".join(", ",$aSpam)."].";

                } else {

                    $reason = "He/She sent ".($spam_result+1)." identical emails.";

                }

                $message =  "   $mem_array->mem_username ($mem_array->mem_userid) has been suspended for possible spamming.<br><br>

                				$reason <br><br><p><i>$txtMessage</i></p>

                				If this was <u>not</u> spam you can reinstate the user through Admin->Member Administration and update the expire date as noted above.";

                send_mail ("$CONST_MAIL", "$CONST_MAIL", "Spammer detected", $message ,"html", "ON");

                $qry_spam="update members set mem_suspend ='Y' where mem_userid=$Sess_UserId";

                $globalMysqlConn->query($qry_spam);

                session_destroy();

                header("Location: $CONST_LINK_ROOT/spam.php");

                exit;

        }

}

####################

# END OF SPAM CHECK

####################



//$tempdate=date("Y/m/d");

# Add the message to the database and mail the user

include_once __INCLUDE_CLASS_PATH."/class.Messages.php";

$messages = new Messages();

$messages->send($Sess_UserId,$userid,$myhandle,$txtSubject,$txtMessage);



?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

          <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo RESPONSE_SECTION_NAME ?></td>

    </tr>

    <tr>



    <td><?php echo PRGSENDMAIL_TEXT5 ?>

    	<p>

    		<a href='<?php echo $CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$userid?>'><?php echo GENERAL_CONTINUE?></a>

    	</p>

    </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>