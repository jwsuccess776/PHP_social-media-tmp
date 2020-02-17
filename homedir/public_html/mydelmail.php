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



if (isset($_POST['message'])) $message=sanitizeData($_POST['message'], 'xss_clean') ;



if(!isset($block) && !isset($message)) save_request();



# retrieve the template



$area = 'member';







# check to see whether the mode is to delete mail elements (if $message exists)



if ( isset( $message) ) { // the message variable is a list of msg_ids to delete from the email



        foreach ( $message as $key=>$value) {



                $arr_value=explode("/",$value);



                if ($arr_value[1]==$Sess_UserId AND $arr_value[2]==$Sess_UserId) {



					$query="UPDATE messages SET msg_delhidesen= 'Y', msg_delhiderec= 'Y' WHERE msg_id=$arr_value[0]";



				} elseif($arr_value[1]==$Sess_UserId)  {



					$query="UPDATE messages SET msg_delhidesen= 'Y' WHERE msg_id=$arr_value[0]";



				} elseif($arr_value[2]==$Sess_UserId)  {



					$query="UPDATE messages SET msg_delhiderec= 'Y' WHERE msg_id=$arr_value[0]";



				}







				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



				$query="SELECT * FROM messages WHERE msg_id=$arr_value[0]";



				$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



				$sql_array=mysqli_fetch_object($result);







				if ($sql_array->msg_delhiderec== 'Y' AND $sql_array->msg_delhidesen== 'Y') {



					$query="DELETE FROM messages WHERE msg_id=$arr_value[0]";



					$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



				}



        }



}



# select all messages to display



$query="SELECT * FROM messages WHERE (msg_receiverid='$Sess_UserId' AND msg_receiverdel='Y' AND msg_delhiderec='N') OR (msg_senderid='$Sess_UserId' AND msg_senderdel='Y' AND msg_delhidesen='N') ORDER BY msg_dateadded ASC";



$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



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

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td >

      <?include($CONST_INCLUDE_ROOT."/mail_menu.inc.php")?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo MYEMAIL_TRASH_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td> <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/mydelmail.php' name='FrmEmail' >

          <tr>

            <td colspan="5" class="tdhead"  ><?php echo MYEMAIL_INBOX_SECTION_NAME ?></td>

          </tr>

          <tr class="tdtoprow">

            <td>&nbsp;</td>

            <td >

              <?=GENERAL_FROM?>

            </td>

            <td>

              <?=ADMINMAIL_SUB?>

            </td>

            <td >

              <?=MYEMAIL_DATE?>

            </td>

            <td align="center">

              <?=MYEMAIL_HIDE?>

            </td>

          </tr>

          <?php



$TOTAL = mysqli_num_rows($result);



if ($TOTAL > 0) {



        while ($sql_array = mysqli_fetch_object($result) ) {



                  $short_title=stripslashes(substr($sql_array->msg_title,0,30));



                  $backcolour="class='tdodd'"; $newmail="&nbsp;&nbsp;&nbsp;&nbsp;";



                  if ($sql_array->msg_read=='U') {$backcolour="class='tdmail'"; $newmail="<img border='0' src='$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/newmail.gif'>";}



                  $compvalue=$sql_array->msg_id.'/'.$sql_array->msg_senderid.'/'.$sql_array->msg_receiverid;



                        print("<tr><td align='center' $backcolour>$newmail</td>");



							print("<td $backcolour>$sql_array->msg_senderhandle</td>");



						    print("<td $backcolour><a href='$CONST_LINK_ROOT/prgshowmail.php?mailid=$sql_array->msg_id&showmode=received'>$short_title</a></td>



                          <td $backcolour>".date($CONST_FORMAT_DATE_SHORT,strtotime($sql_array->msg_dateadded))."</td>



                          <td $backcolour align='center'><input type='checkbox' name='message[]' id=inbox value='$compvalue'></td>



               </tr>");



    }



          print("<tr class='tdtoprow'>



                   		<td colspan=3 >&nbsp;</td>



                          <td >".MYEMAIL_SELECT_ALL."</td>



                          <td colspan=1  align='center'><input type='checkbox' name='select_all' value='Select All' onClick=\"selectAll(this,'inbox')\"></td>



               </tr>");







} else {



          print("<tr>



                                  <td class='tdeven' align='center' colspan='6'>".NO_RECORDS_SECTION_NAME."</td>



                          </tr>");



}



?>

          <tr align="center">

            <td colspan='6' class='tdfoot'>

              <?php if ($TOTAL > 0) { ?>

              <input name="Validate" type="submit" class="button" value="<?php echo BUTTON_REMOVE ?>">

              <br>

              <br>

              <?php } ?>

              <a href='<?=$CONST_LINK_ROOT?>/prgmailblock.php'>

              <?=GENERAL_BLOCKLIST?>

              </a> | <a href='<?=$CONST_LINK_ROOT?>/search.php'>

              <?=GENERAL_BACKTOLIST?>

              </a></td>

          </tr>

        </form>

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

