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

# Name:         adm_bbtopics.php

#

# Description:

#

# Version:      7.2

#

######################################################################

include('../db_connect.php');

if (isset($_SESSION['Sess_UserId'])) include('../session_handler.inc');



include ('../functions.php');

include('../error.php');

include_once __INCLUDE_CLASS_PATH."/class.Emoticons.php";

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";

include_once __INCLUDE_CLASS_PATH."/class.ForumHelper.php";



$adv = new Adverts();

$emotions = new Emoticons();



$post_added = false;

$mode=formGet('mode');

$subtopic_id = formGet('stid');



# retrieve the template

# retrieve the template

if (isset($_SESSION['Sess_UserId']))

    $area = 'member';

else

    $area = 'guest';



switch ($mode) {

    case 'delete':

    $row = $db->get_row("SELECT * FROM bb_posts WHERE post_id = '".$db->escape(formGet('pid'))."'");

    if ($db->get_var("SELECT count(*) cnt FROM bb_posts WHERE  subtopic_id = '$row->subtopic_id'") == 1) $del = true;

    if ($row) {

        include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";

        $File = new ImageFile();

        $File->Init($row->post_id,'forum',$row->post_ext);

        $File->Delete();

        $db->query("DELETE FROM bb_posts WHERE post_id = $row->post_id");

        if ($del == true ){

          $db->query("DELETE FROM bb_subtopics WHERE subtopic_id = '$row->subtopic_id'");

            header("Location: $CONST_FORUM_LINK_ROOT/bb_topic.php?tid=$row->topic_id");

            exit;

        }

    }



    break;

}

$cur_topic = $db->get_row(" SELECT *, f.forum_id AS forum_id

                            FROM bb_forum f

                            LEFT JOIN  bb_subtopics st

                                ON (f.forum_id = st.forum_id)

                            WHERE subtopic_id = '$subtopic_id'");



$query = "  SELECT *

            FROM bb_posts

                LEFT JOIN adverts ON (poster_id=adv_userid)

                LEFT JOIN members ON (mem_userid=adv_userid)

            WHERE subtopic_id = '$subtopic_id' AND post_approved = 1

            ORDER BY post_time DESC";

$posts_arr = $db->get_results($query);



#emoticon array search and replace

$_SESSION['stid'] = $subtopic_id;

?>



<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>



  <tr>

    <td class="pageheader"><?=BB_FORUMS_SECTION_NAME?></td>

  </tr>

  <tr>

  <td >

    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/forums.php" class="forumlinkshd"><?=BB_FORUMS_SECTION_NAME?></a> &raquo;

    <a href="<?php echo $CONST_LINK_ROOT ?>/forum/bb.php?fid=<?php echo $cur_topic->forum_id ?>" class="forumlinkshd"><?=$cur_topic->forum_title?></a> &raquo;

    <b><?=$cur_topic->subtopic_title?></b>

    </td>

  </tr>

<?php

if ($post_added) {

?>

  <tr>

    <td><?=BB_TOPIC_POST_ALERT?></td>

  </tr>

<?php } ?>

  <tr>

    <td>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>

            <td class="tdhead" valign="middle" align="right" height="30"><a href='<?php echo $CONST_LINK_ROOT ?>/forum/bb_post.php?mode=&tid=<?php echo $cur_topic->topic_id?>&stid=<?php echo $cur_topic->subtopic_id?>' class='forumlinks'><?php echo BB_TOPIC_POST_ADD_NEW ?></a></td>

        </tr>

        <tr>

            <td align="center">

              <table border='0' cellpadding='2' cellspacing='1' width='100%'>

    <?php

        foreach ($posts_arr as $sql_array) {

            $image = '';

            $adv->InitByObject($sql_array);

            $adv->SetImage('small');

            $sql_array = $adv;

            $sql_array->post_time =  date(" $CONST_FORMAT_DATE_SHORT $CONST_FORMAT_TIME_SHORT",strtotime($sql_array->post_time));

            $sql_array->post_text=$emotions->Parse($sql_array->post_text);



            if ($sql_array->post_ext){

                $File = new ImageFile();

                $File->Init($sql_array->post_id ,'forum',$sql_array->post_ext);

                $image = $File->getInfo('medium');

                $image_full = $File->getInfo('');

            }



            if ($Sess_UserId == $sql_array->poster_id) $edit_text="<a href='$CONST_LINK_ROOT/forum/bb_subtopic.php?mode=delete&pid=$sql_array->post_id' class='forumlinks' onClick=\"return delete_alert6();\">".BB_TOPIC_POST_DELETE."</a>&nbsp;&nbsp;&nbsp;<a href='$CONST_LINK_ROOT/forum/bb_post.php?mode=edit&pid=$sql_array->post_id' class='forumlinks'>".BB_TOPIC_POST_EDIT."</a>";

            elseif ($Sess_UserType == 'A') $edit_text="<a href='$CONST_LINK_ROOT/forum/bb_subtopic.php?mode=delete&pid=$sql_array->post_id' class='forumlinks' onClick=\"return delete_alert6();\">".BB_TOPIC_POST_DELETE."</a>";

            else $edit_text="&nbsp;";

?>

            <tr>

              <td colspan='2' class='tdtoprow' align='right'><?=$edit_text?></td>

            </tr>

            <tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#f0f0f0'>

                <td  width='80' align='center' valign='top'>

                    <a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->poster_id?>'><?=$sql_array->adv_username?></a><br>

                    <a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->poster_id?>'>

                        <img border='0' src='<?=$CONST_LINK_ROOT.$sql_array->adv_picture->Path."?".time()?>' width="<?=$sql_array->adv_picture->w?>">

                    </a><br>

                    <?=$sql_array->post_time?>

                </td>

                <td align='left' valign='top'>

                    <?php $forumHelper = new ForumHelper; 
                    	echo $forumHelper->decoratePostBody($sql_array->post_text);
                    ?>

                    <?php if ($sql_array->post_ext) { ?>

                    <div style="float:left">

                    <a rel='lightbox' href="<?=$CONST_LINK_ROOT."/".$image_full->Path?>"><img src="<?=$CONST_LINK_ROOT.$image->Path?>" width="<?=$image->w?>" border=0></a></div>

                    <?php } ?>

                </td>

              </tr>

                </td>

                <td align='left' valign='top' height='5'>&nbsp;</td>

              </tr>

<?php

        }

?>

              </table>

            </td>

        </tr>

        <tr>

            <td >&nbsp;</td>

        </tr>

        <tr>

            <td ><?php echo BB_FORUM_TIMEZONE ?>&nbsp;<?=date('T');?></td>

        </tr>

      </table>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>