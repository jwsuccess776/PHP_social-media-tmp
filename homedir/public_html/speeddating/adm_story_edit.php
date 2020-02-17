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

# Name:              adm_story_edit.php

#

# Description:

#

# Version:             7.2

#

######################################################################

include('../db_connect.php');

include('session_handler.inc');

include('../functions.php');

include('../admin/permission.php');

include_once('../validation_functions.php');

$storyid = sanitizeData(trim($_GET['id']), 'xss_clean');  



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

      <?= $storyid ? SD_ADM_STORY_EDIT_SECTION_NAME : SD_ADM_STORY_ADD_SECTION_NAME ?>

    </td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <?php

        $sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM sd_stories WHERE sd_storyid='$storyid'");

        $story = mysqli_fetch_object($sql_result);

        ?>

        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/speeddating/adm_stories.php" enctype="multipart/form-data">

          <input type="hidden" name="act" value="save">

          <input type="hidden" name="id" value="<?=$storyid?>">

          <input type="hidden" name="MAX_FILE_SIZE" value="<?$CONST_STORYIMAGE_WEIGHT?>000">

          <tr>

            <td align="center">



       <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr class="tdodd">

                  <td>

                    <?=SD_ADM_STORY_TITLE?>

                  </td>

                  <td> <input name="title" type="text" class="input" value="<?=htmlspecialchars($story->sd_title)?>"></td>

                </tr>

                <tr class="tdeven">

                  <td>

                    <?=SD_ADM_STORY_BODY?>

                  </td>

                  <td> <textarea name="body" cols="50" rows="10" wrap="soft" class="inputl"><?=htmlspecialchars($story->sd_body)?></textarea></td>

                </tr>

                <tr class="tdeven">

                  <td>

                    <?=SD_ADM_STORY_WHO?>

                  </td>

                  <td> <input name="who" type="text" class="input" value="<?=htmlspecialchars($story->sd_who)?>"></td>

                </tr>

                <tr class="tdodd">

                  <td>

                    <?=SD_ADM_STORY_IMAGE?>

                  </td>

                  <td> <input name="imgFile" type="file" class="inputf"> <br>

                    <?=$CONST_STORYIMAGE_WIDTH?>

                    x

                    <?=$CONST_STORYIMAGE_HEIGHT?>

                  </td>

                </tr>

                <tr>

                  <td colspan="2" align="center" class="tdfoot"> <input name="submit" type="submit" class="button" value="<?=GENERAL_SAVE?>">

                    <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/speeddating/adm_stories.php'" value="<?=GENERAL_CANCEL?>">

                  </td>

                </tr>

              </table></td>

          </tr>

        </form>

      </table></td>

    </tr>

  </table>





<?=$skin->ShowFooter($area)?>

