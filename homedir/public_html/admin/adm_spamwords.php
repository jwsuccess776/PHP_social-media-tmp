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

# Name:                 adm_mailtemplates.php

#

# Description:

#

# Version:                7.2

#

######################################################################
require_once('../db_connect.php');

require_once('../session_handler.inc');

require_once('../error.php');

require_once('../functions.php');

require_once __INCLUDE_CLASS_PATH."/class.SpamChecker.php";

include('permission.php');



$area = 'member';

$pager->SetUrl($CONST_LINK_ROOT."/admin/adm_spamwords.php");

$spamChecker = new SpamChecker;



$action = formGet ( 'action' );



switch ( $action ) {

    case 'save':

        $name = formGet ( 'name' );

        $spamChecker -> InitForSave ( (object) array ( 'SpamWord' => $name ) );

        $spamChecker -> Save();

    break;

    case 'delete':

        $id = formGet ( 'id' );

        $spamChecker -> Delete( $id );

        redirect ($CONST_LINK_ROOT.'/admin/adm_spamwords.php');

    break;

    default:

    break;

}



$spamWords = $spamChecker -> getList ( $pager );

?>

<?=$skin->ShowHeader($area)?>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo ADM_SPAMWORDS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

<form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_spamwords.php" name="frmAddSpamword">

<input type="hidden" name="action" value="save">

            <td align="center">

    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr>

                  <td colspan="2" class="tdhead">&nbsp;</td>

                </tr>

                <tr>

                  <td width="25%" class="tdodd"><b>

                    <?=ADM_SPAMWORDS_WORD_TITLE;?>

                :</b></td>

                  <td width="75%" class="tdodd">

                  	<input type="text" name="name" class="inputl"> <input name="submit" type="submit" class="button" value="<?=ADM_SPAMWORDS_ADD_LINK_TITLE;?>">

                  </td>

                </tr>

                </table></td>

          </form>





      </td>

    </tr>

    <tr>

      <td>



<?php /* LIST TABLE */ 

    include "../pager.php";
    ?>

    <table width="80%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

<?php foreach ( $spamWords as $spamRow ) { ?>

    <tr align=left class="tdodd" >

      <td>

        <?=$spamRow->SpamWord?>

      </td>

      <td align="right" width="5%" nowrap="nowrap">

        <a href="<?=$CONST_LINK_ROOT;?>/admin/adm_spamwords.php?action=delete&id=<?=$spamRow->SpamWord_ID;?>"><?=ADM_SPAMWORDS_DELETE_LINK_TITLE;?></a>

      </td>

    </tr>

<?php } ?>

    </table>



      </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>