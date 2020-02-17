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

# Name:                 adm_stories.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include_once __INCLUDE_CLASS_PATH."/class.CropCanvas.php";

include('permission.php');





$id = formGet('id');

$act = formGet('act');

$mode = formGet('mode');



if($_GET['act'] == 'remove')

{

    $sde_eventid = $_GET['id'];

    $sql_query = "DELETE FROM stories WHERE story_id = $_GET[id]";

    mysqli_query($globalMysqlConn,$sql_query);

}



if($_GET['act'])  header("Location: $CONST_LINK_ROOT/admin/adm_stories.php");



# retrieve the template

$area = 'member';



$sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM stories");



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo SD_ADM_STORIES_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>



	   <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">



    <tr>

      <td colspan="2" align="center" class="tdhead">

        <input type="button" class='button' onclick="document.location.href = '<?=$CONST_LINK_ROOT?>/admin/adm_story_edit.php'" value="<?=SD_ADM_STORIES_ADD?>">

      </td>

    </tr>

    <tr class="tdtoprow">

      <td>

        <?=SD_ADM_STORIES_TITLE?>

      </td>

      <td >

        <?=GENERAL_DELETE?>

      </td>

          </tr>

        <?php

                    while($story = mysqli_fetch_object($sql_result))

                    {

                        ?>

    <tr align=center class="tdodd" <?if ($event->sde_places*2 < $event->ticket_count ){?>style="background-color: #FFAAAA"<?}?>>

      <td><a href="<?=$CONST_LINK_ROOT?>/admin/adm_story_edit.php?id=<?=$story->story_id?>">

        <?=htmlspecialchars($story->title)?>

        </a></td>

      <td><a href="<?=$CONST_LINK_ROOT?>/admin/adm_stories.php?act=remove&id=<?=$story->story_id?>" onClick="if (confirm('<?=SD_ADM_STORIES_TEXT1?>')) {return true;} else {return false;}" >[

        <?=GENERAL_DELETE?>

        ]</a></td>

        </tr>

        <?php } ?>

					 <tr>

      <td colspan="2" align="center" class="tdfoot">&nbsp; </td>

    </tr>

  </table>

	  </td>

    </tr>

  </table>

<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>