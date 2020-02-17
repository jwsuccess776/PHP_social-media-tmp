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

# Name:              adm_story_edit.php

#

# Description:

#

# Version:              7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../functions.php');

include('../error.php');

include("../FCKeditor/fckeditor.php");

include('permission.php');





# retrieve the template

$area = 'member';

$id        = formGet('id');

$title     = formGet("title");

$body      = formGet("body");

$act      = formGet("act");

$body=stripslashes($body);





if ($act == 'save'){

    if ($title == "") {

        error_page("Field Title is empty",GENERAL_USER_ERROR, $mode);

    }

    if ($body == "") {

        error_page("Field Body is empty",GENERAL_USER_ERROR, $mode);

    }



    if ($id) {

        $sql_query = "UPDATE stories SET title = '$title', body = '".mysqli_real_escape_string($globalMysqlConn, stripslashes($body))."' WHERE story_id = $id";

    } else {

        $sql_query = "INSERT INTO stories SET title = '$title', body = '".mysqli_real_escape_string($globalMysqlConn, stripslashes($body))."'";

    }

    $db->query($sql_query);



    header("Location: $CONST_ADMIN_LINK_ROOT/adm_stories.php");

    exit;

}





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

      <?= $id ? SD_ADM_STORY_EDIT_SECTION_NAME : SD_ADM_STORY_ADD_SECTION_NAME ?>

    </td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <?php

        $sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM stories WHERE story_id='$id'");

        $story = mysqli_fetch_object($sql_result);

        ?>

        <form method="post" id="storyForm" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_story_edit.php" enctype="multipart/form-data">

          <input type="hidden" name="act" value="save">

          <input type="hidden" name="id" value="<?=$id?>">

          <tr>

            <td align="center">



	   <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">



                <tr>

                  <td colspan="2" class="tdhead">&nbsp;</td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=SD_ADM_STORY_TITLE?>

                  </td>

                  <td> <input name="title" type="text" class="input" value="<?=htmlspecialchars($story->title)?>"></td>

                </tr>

                <tr class="tdeven">

                  <td>

                    <?=SD_ADM_STORY_BODY?>

                  </td>

                  <td>

            <?
                //$fck = createFCKEditor( 'additional_images', 'body', $story->body , 'Basic', null, 390); //html_entity_decode(stripslashes($story->body))

				      //$fck->Create() ;
            ?>
            <textarea name="body" id="editor_adm_story_edit"><?php echo $story->body; ?></textarea>

              </td>

                </tr>

                <tr>

                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">

                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/admin/adm_stories.php'" value="<?=GENERAL_CANCEL?>">

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
        CKEDITOR.replace( 'editor_adm_story_edit' );
    };
</script>
<?=$skin->ShowFooter($area)?>

