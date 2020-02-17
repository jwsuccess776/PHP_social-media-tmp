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

# Name:              adm_news_edit.php

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



$newsid = FormGet('id');



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

      <?= $newsid ? SD_ADM_NEWS_EDIT_SECTION_NAME : SD_ADM_NEWS_ADD_SECTION_NAME ?>

    </td>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <?php

        $news = $db->get_row("SELECT * FROM news WHERE news_id='$newsid'");

        ?>

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_news.php" enctype="multipart/form-data">

          <input type="hidden" name="act" value="save">

          <input type="hidden" name="id" value="<?=$newsid?>">

          <tr>

            <td align="center">

       <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

            <tr class="tdodd">

              <td>

                <?=SD_ADM_NEWS_TITLE?>

              </td>

              <td> <input name="title" type="text" class="input" value="<?=htmlspecialchars($news->title)?>"></td>

            </tr>

            <tr class="tdeven">

              <td height="400px" valign=top>

                <?=SD_ADM_NEWS_BODY?>

              </td>

              <td>

            <?

                //$fck = createFCKEditor( 'additional_images', 'body', $news->body , 'Basic', null, 390); //html_entity_decode(stripslashes($news->body))

				        //$fck->Create() ;

            ?>

            <textarea name="body" id="editor_adm_news_edit"><?php echo $news->body; ?></textarea>

            </tr>

            <tr>

              <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">

                <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_ADMIN_LINK_ROOT?>/adm_news.php'" value="<?=GENERAL_CANCEL?>">

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
        CKEDITOR.replace( 'editor_adm_news_edit' );
    };
</script>
<?=$skin->ShowFooter($area)?>

