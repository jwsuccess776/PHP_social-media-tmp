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

# Name:              adm_mailtemplates_edit

#

# Description:

#

# Version:              7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../functions.php');

include('../message.php');

include('../error.php');

include('permission.php');



$Name = FormGet('Name');

$LANG_ID = FormGet('LANG_ID');

$Body = FormGet('Body');

$act = FormGet('act');

$ptm = new PTemplateManager;
$pagetemplate =& $ptm->getInstance();

$p_template = $pagetemplate->Get($Name);



if($act == 'save') {

    restrict_demo();

    $res = $p_template->Save($Body,null,$LANG_ID);

    if ($res===null ) {

        error_page(join("<br>",$p_template->error),GENERAL_USER_ERROR);

    } else {

         header("Location: $CONST_LINK_ROOT/admin/adm_pagetemplates.php");

        exit;

    }

}

# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader">

      <?= ADM_MAILTEMPLATES_EDIT_SECTION_NAME?>

    </td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_pagetemplates_edit.php" enctype="multipart/form-data">

          <input type="hidden" name="act" value="save">

          <input type="hidden" name="Name" value="<?=$Name?>">

          <input type="hidden" name="LANG_ID" value="<?=$LANG_ID?>">

          <tr>

            <td align="center">

    	    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr>

                  <td colspan="2" class="tdhead">&nbsp;</td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=ADM_MAILTEMPLATES_DESCRIPTION?>

                  </td>

                  <td height="25"> <?= $p_template->comments?></td>

                </tr>

                <tr class="tdeven">

<!--                  <td>

                    <?=ADM_MAILTEMPLATES_MESSAGE?>

                  </td>

-->

                  <td colspan=2>

		            <?

						//$fck = createFCKEditor( "additional_images", 'Body', $p_template->value[$LANG_ID] , 'Basic', null, 390); //html_entity_decode(stripslashes($news->body))

						//$fck->Create() ;

		            ?>

                <textarea name="Body" id="editor_adm_pagetemp_edit" ><?php echo $p_template->value[$LANG_ID]; ?></textarea>

                   </td>

                </tr>

                <tr>

                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">

                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/admin/adm_pagetemplates.php'" value="<?=GENERAL_CANCEL?>">

                  </td>

                </tr>

              </table></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>
<script>
    window.onload = function() {
        CKEDITOR.replace( 'editor_adm_pagetemp_edit' );
    };
</script>
<?=$skin->ShowFooter($area)?>

