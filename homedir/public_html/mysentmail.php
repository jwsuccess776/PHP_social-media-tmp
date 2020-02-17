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

# Name:                 myemail.php

#

# Description:  Member e-mail inbox ('My E-mail')

#

# Version:                7.2

#

######################################################################



include('db_connect.php');
include_once('validation_functions.php');
include('session_handler.inc');

if (isset($_POST['block'])) $block=sanitizeData($_POST['block'], 'xss_clean') ;  

if (isset($_POST['message'])) $message= sanitizeData($_POST['message'], 'xss_clean') ;   

if(!isset($block) && !isset($message)) save_request();



# retrieve the template



$area = 'member';



# check to see whether the mode is to delete mail elements (if $message exists)



if ( isset( $message) ) { // the message variable is a list of msg_ids to delete from the email



        foreach ( $message as $key=>$value) {



                $arr_value=explode("/",$value);



                $query="UPDATE messages SET $arr_value[1] = 'Y' WHERE msg_id=$arr_value[0]";



                $result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



        }



}



?>

<?=$skin->ShowHeader($area)?>

<script language="javascript">



    var flag=true;



    function selectAll(el, id){



       var elems = el.form.elements;



       for(var i = 0; i < elems.length; i++){



          if(elems[i].type == "checkbox" && elems[i].id == id) {



            elems[i].checked = flag;



          }



       }



       if (flag) {



           el.value = "Unselect All";



       } else {



           el.value = "Select All";



       }



        flag=!flag;



    }



</script>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td ><?include($CONST_INCLUDE_ROOT."/mail_menu.inc.php")?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo MYEMAIL_SENT_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><?php



# select all sent messages to display



$query="SELECT *, mem_username FROM messages LEFT JOIN members ON (msg_receiverid=mem_userid) WHERE (msg_senderid='$Sess_UserId') AND msg_senderdel='N' ORDER BY msg_dateadded ASC";



$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



// mysql_close( $link );



?>

      <div align='left'>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

          <form method='post' action='<?php echo $CONST_LINK_ROOT?>/mysentmail.php' name='FrmEmail2' >

            <tr>

              <td colspan="5" class="tdhead"><?php echo MYEMAIL_OUTBOX_SECTION_NAME ?></td>

            </tr>

            <tr  class="tdtoprow">

              <td>&nbsp;</td>

              <td><strong>

                <?=GENERAL_TO?>

                </strong></td>

              <td><strong>

                <?=ADMINMAIL_SUB?>

                </strong></td>

              <td><strong>

                <?=MYEMAIL_DATE?>

                </strong></td>

              <td align="center"><strong>

                <?=MYEMAIL_DELETE?>

                </strong></td>

            </tr>

            <?php



$TOTAL = mysqli_num_rows($result);



if ($TOTAL > 0) {



        while ($sql_array = mysqli_fetch_object($result) ) {



                  $short_title=stripslashes(substr($sql_array->msg_title,0,30));



                  $backcolour="class='tdodd'"; $newmail="&nbsp;&nbsp;&nbsp;&nbsp;";



                  if ($sql_array->msg_read=='U') {$backcolour="class='tdmail'"; $newmail="<img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/newmail.gif'>";}

				  else {$newmail="<img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/readmail.gif'>";} 

                  $compvalue=$sql_array->msg_id.'/msg_senderdel';



          print("<tr>



                          <td align='center' $backcolour>$newmail</td>



                          <td $backcolour><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array->msg_receiverid'>$sql_array->mem_username</a></td>



                          <td $backcolour><a href='$CONST_LINK_ROOT/prgshowmail.php?mailid=$sql_array->msg_id&showmode=sent'>$short_title</a></td>



                         <td $backcolour>".date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array->msg_dateadded))."</td>



                          <td align='center' $backcolour><input type='checkbox' id=outbox name='message[]' value='$compvalue'></td>



               </tr>");



    }



          print("<tr class='tdtoprow'>



                          <td colspan=4 align='right'>".MYEMAIL_SELECT_ALL."</td>



                          <td align='center'><input type='checkbox' name='select_all' value='Select All' onClick=\"selectAll(this,'outbox')\"></td>



               </tr>");



} else {



          print("<tr class='tdeven'>



                                  <td  align='center' colspan='5'>".NO_RECORDS_SECTION_NAME."</td>



                          </tr>



                                 <tr class='tdfoot' align='center'>



              <td valign='top' align='center' colspan='5' class='tdfoot'>&nbsp;</td>



                                ");



}



if ($TOTAL > 0) {



                print("<tr class='tdfoot' align='center'>



                                  <td valign='top' align='center' colspan='5' class='tdfoot'>



                                <input name='Validate' type='submit' class='button' value='".BUTTON_REMOVE."'><br><br>



            <a href='$CONST_LINK_ROOT/search.php?'>".GENERAL_BACKTOLIST."</a> "); }



                        else { print("



            <tr class='tdfoot' align='center'>



              <td valign='top' align='center' colspan='5'> <a href='$CONST_LINK_ROOT/search.php'>".GENERAL_BACKTOLIST."</a></td></tr>



            "); } ?>

          </form>

        </table>

      </div></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

