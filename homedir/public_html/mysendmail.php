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

# Name:         sendmail.php

#

# Description:  Displays the page that a member uses to send mail

#

# # Version:      8.0

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

include('error.php');



# retrieve the template

$area = 'member';



$query="SELECT * FROM hotlist WHERE hot_userid='$Sess_UserId'";

$retval=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());



// mysql_close( $link );

?>

<?=$skin->ShowHeader($area)?>

  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td>

		<?include($CONST_INCLUDE_ROOT."/mail_menu.inc.php")?>

	  </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo SEND_MAIL_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/prgmysendmail.php' name="FrmSendMail" onSubmit="return Validate_FrmSendMail()" >

          <tr>

            <td colspan="2" class="tdhead" >

              <?=SENDMAIL_TO?>

              : <select name='userid' class="input" style="width:150px">

                <option value='' selected>- <?php echo GENERAL_CHOOSE?> -</option>

				<?php

				while($arr_hotlist = mysqli_fetch_object($retval)) {

	               print("<option value='$arr_hotlist->hot_advid'>$arr_hotlist->hot_screenname</option>");

				}

				?>

			  </select>

              <input type='hidden' name='myhandle' value='<?php print("$Sess_UserName"); ?>'>

            </td>

          </tr>

          <tr class="tdodd">

            <td ><b>

              <?=SENDMAIL_SUBJECT?>

              :</b></td>

            <td > <input type="text" class="input" name="txtSubject" size="50" tabindex="1"></td>

          </tr>

          <tr class="tdeven" >

            <td valign="top" ><b>

              <?=SENDMAIL_MESSAGE?>

              :</b></td>

            <td > <textarea  class="inputl"rows="15" name="txtMessage" cols="54" tabindex="2"></textarea></td>

          </tr>

          <tr >

            <td colspan="2" align="center" valign="top" class="tdfoot" > <input name="Validate2" type="submit" class="button" value="<?php echo BUTTON_SENDMAIL ?>">



              </td>

          </tr>

          <tr >

            <td colspan="2" valign="top" >&nbsp;</td>

          </tr>

          <tr >

            <td colspan="2" valign="top" ><a href='javascript:history.go(-1);'>

              <?=BUTTON_BACK?>

              </a></td>

          </tr>

          <tr >

            <td colspan="2" valign="top" >&nbsp;</td>

          </tr>

          <tr >

            <td colspan="2" valign="top" ><font face="Verdana" size="1">

              <?=SENDMAIL_NOTE?>

              </font></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>