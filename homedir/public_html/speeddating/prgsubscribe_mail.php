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

# Name:                 prgsuscribe_mail.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

//include('session_handler.inc');

include('../message.php');

include('../functions.php');

include('../error.php');



if (empty($_REQUEST['subscribe_mail']) && empty($_REQUEST['unsubscribe_mail'])) {

    $error_message=SD_PRGSUBSCRIBE_MAIL_INCORRECT;

    error_page($error_message,GENERAL_USER_ERROR);

    exit;

}

if (empty($_REQUEST['email'])) {

    $error_message=SD_PRGSUBSCRIBE_MAIL_EMPTY;

    error_page($error_message,GENERAL_USER_ERROR);

    exit;

}



if ($_REQUEST["subscribe_mail"]) {

    $sql_query = "REPLACE INTO sd_subscribe

                (sub_email,sub_sex)

                 VALUES

                ('$email', '$sex')";

    mysqli_query($globalMysqlConn,$sql_query);

}

if ($_REQUEST["unsubscribe_mail"]) {

    $sql_query = "DELETE FROM sd_subscribe WHERE sub_email = '$email'";

    mysqli_query($globalMysqlConn,$sql_query);

}





# retrieve the template

$area = 'speeddating';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td class="pageheader"><?php echo ABOUT_SECTION_NAME ?></td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr>

          <td align="left" class="tdhead" >&nbsp;</td>

        </tr>

        <tr>

          <td  align="left" class="tdodd">

            <?=SD_SUBSCRIBE_SECTION_NAME?>

          </td>

        </tr>

        <tr>

          <td align="center" class="tdfoot"><b>

            <?if ($_REQUEST['subscribe_mail']) {?>

            <?=SD_PRGSUBSCRIBE_MAIL_SUB_OK?>

            <?} elseif ($_REQUEST['unsubscribe_mail']) {?>

            <?=SD_PRGSUBSCRIBE_MAIL_UNSUB_OK?>

            <?}?>

            </b>

            <input type=button class='button' name=back value="<?=SD_PRGSUBSCRIBE_MAIL_BACK?>" onClick="history.back()">

          </td>

        </tr>

      </table></td>

    </tr>

  </table>



<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>

