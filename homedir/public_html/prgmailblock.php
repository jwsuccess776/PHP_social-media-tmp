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

# Name:         prgmailinfo.php

#

# Description:  Sends offer mails to people who have not visited for a while

#

# Version:      7.2

#

######################################################################



include('db_connect.php');

include('session_handler.inc');

include_once('validation_functions.php');

if (isset($_POST['block'])) $block=sanitizeData($_POST['block'], 'xss_clean') ;  

if(!isset($block)) save_request();

# retrieve the template

$area = 'member';



# check to see whether the mode is to unblock senders

if ( isset( $block) ) { // the block variable is a list of senderids to block

	foreach ( $block as $key=>$value) {

		$query="DELETE FROM blockmail WHERE blk_receiverid='$Sess_UserId' AND blk_senderid = '$value'";

		$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

	}

}

# select all messages to display

$query="SELECT * FROM blockmail a,messages b

	WHERE a.blk_senderid = b.msg_senderid

	AND blk_receiverid='$Sess_UserId'

	GROUP BY msg_senderid

	ORDER BY msg_senderhandle ASC";

$result=mysqli_query($globalMysqlConn, $query) or die(mysqli_error());

?>

<?=$skin->ShowHeader($area)?>

<div align='left'> </div>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

            <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>



    <td class="pageheader"><?php echo BLOCKMAIL_SECTION_NAME ?></td>

    </tr>

    <tr><td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form method='post' action='<?php echo $CONST_LINK_ROOT?>/prgmailblock.php' name='FrmEmail' >

          <tr>

            <td colspan="2" class="tdhead">&nbsp;</td>

          </tr>

          <tr class="tdtoprow">

            <td><b><?php echo PRGMAILBLOCK_SENDER?></b></td>

            <td align="center"><?php echo PRGMAILBLOCK_DELETE?></td>

          </tr>

          <?php

$TOTAL = mysqli_num_rows($result);

if ($TOTAL > 0) {

	while ($sql_array = mysqli_fetch_object($result) ) {

		  $newmail="";

?>

          <tr class="tdodd">

            <td><?php echo $sql_array->msg_senderhandle?></td>

            <td align='center'> <input type='checkbox' name='block[]' value='<?php echo $sql_array->msg_senderid?>'></td>

          </tr>

          <?php } ?>

          <tr>

            <td colspan='2' align='center' class="tdfoot">

              <input type='button' name='Submit' value='<?php echo BUTTON_BACK ?>' class='button' onClick="window.location = '<?=str_replace("'", "\\'", get_prev_page_url())?>'">

              <input type='submit' name='UNBLOCK' value='<?php echo BUTTON_REMOVE ?>' class='button'>

            </td>

          </tr>

          <?php } else { ?>

          <tr>

            <td colspan='2' align='center' class="tdfoot"><?php echo NO_RECORDS_SECTION_NAME ?> - <a href="<?=get_prev_page_url()?>"><?php echo BUTTON_BACK ?></a></td>

          </tr>



          <?php } ?>

        </form>

      </table></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>