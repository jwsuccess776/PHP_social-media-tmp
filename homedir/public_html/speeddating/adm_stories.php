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

include('../admin/permission.php');

include_once('../validation_functions.php');

if($_POST['act'] == 'save') {

    if ($_POST['id'])

    {
        $title=sanitizeData(trim($_POST['title']), 'xss_clean');  
        $who=sanitizeData(trim($_POST['who']), 'xss_clean');  
        $body=sanitizeData(trim($_POST['body']), 'xss_clean');  
        $id=sanitizeData(trim($_POST['id']), 'xss_clean');  
        $sql_query = "  UPDATE sd_stories

                        SET

                            sd_title = '".$title."',

                            sd_who = '".$who."',

                            sd_body = '".$body."'

                            WHERE sd_storyid = '".$id."'";
    }
    else
    {
        $title=sanitizeData(trim($_POST['title']), 'xss_clean');  
        $who=sanitizeData(trim($_POST['who']), 'xss_clean');  
        $body=sanitizeData(trim($_POST['body']), 'xss_clean');  
        
        $sql_query = "  INSERT INTO sd_stories

                        SET

                            sd_title = '".$title."',

                            sd_who = '".$who."',

                            sd_body = '".$body."'";
    }

    mysqli_query($globalMysqlConn,$sql_query);

    if (empty($_POST['id']))

        $id = mysqli_insert_id($globalMysqlConn);

    else

        $id = sanitizeData(trim($_POST['id']), 'xss_clean');   

    if (!empty($_FILES["imgFile"]) && !$_FILES["imgFile"]["error"]) {

        $info=getimagesize($_FILES["imgFile"]["tmp_name"]);

        if (

        $info[0] == $CONST_STORYIMAGE_WIDTH &&

            $info[1] == $CONST_STORYIMAGE_HEIGHT &&

            in_array($info[2],array(1,2,3))

/*

            && filesize($_FILES["imgFile"]["tmp_name"]) <= $CONST_STORYIMAGE_WEIGHT*1024

*/

            )

            move_uploaded_file($_FILES["imgFile"]["tmp_name"],$CONST_INCLUDE_ROOT."/speeddating/stories/story_$id.gif");

        else error_page("Bad Image",GENERAL_USER_ERROR);

    }

     //else error_page("No Image",GENERAL_USER_ERROR);

}

elseif($_GET['act'] == 'remove')

{

    $sde_eventid =sanitizeData(trim($_GET['id']), 'xss_clean');    

    $sql_query = "DELETE FROM sd_stories WHERE sd_storyid = '$sde_eventid'";

    mysqli_query($globalMysqlConn,$sql_query);

}



if($_GET['act'])

    header("Location: $CONST_LINK_ROOT/speeddating/adm_stories.php");



# retrieve the template

$area = 'member';





$sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM sd_stories");



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

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>

	   <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr align=center class="tdtoprow">

      <td width=50%>

        <?=SD_ADM_STORIES_TITLE?>

      </td>



      <td width=40%>

        <?=SD_ADM_STORIES_IMAGE?>

      </td>





      <td width=10% >

        <?=GENERAL_DELETE?>

      </td>

          </tr>

        <?php

                    while($story = mysqli_fetch_object($sql_result))

                    {

                        ?>



    <tr align=center class="tdodd" <?if ($event->sde_places*2 < $event->ticket_count ){?>style="background-color: #FFAAAA"<?}?>>

      <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_story_edit.php?id=<?=$story->sd_storyid?>">

        <?=htmlspecialchars($story->sd_title)?>

        </a></td>



      <td>

        <?php if (file_exists($CONST_INCLUDE_ROOT."/speeddating/stories/story_".$story->sd_storyid.".gif")) { ?>

        <img src="<?=$CONST_LINK_ROOT?>/speeddating/stories/story_<?=$story->sd_storyid?>.gif" width="<?=$CONST_STORYIMAGE_WIDTH?>" height="<?=$CONST_STORYIMAGE_HEIGHT?>">

        <?php } else echo "none";?>

      </td>



      <td><a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_stories.php?act=remove&id=<?=$story->sd_storyid?>" onClick="if (confirm('<?=SD_ADM_STORIES_TEXT1?>')) {return true;} else {return false;}" >[

        <?=GENERAL_DELETE?>

        ]</a></td>

        </tr>

        <?php

                    }

                    ?>



     <tr>

      <td colspan="3" align="center" class="tdfoot">&nbsp;</td>

    </tr>

    <tr>

      <td colspan="3" align="center" class="tdodd">

        <input type="button" class='button' onclick="document.location.href = '<?=$CONST_LINK_ROOT?>/speeddating/adm_story_edit.php'" value="<?=SD_ADM_STORIES_ADD?>">&nbsp;<input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>"></td>

    </tr>

  </table>

	  </td>

    </tr>

  </table>

<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>