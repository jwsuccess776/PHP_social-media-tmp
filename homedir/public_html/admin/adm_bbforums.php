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

include_once('../validation_functions.php');

include('../session_handler.inc');

include ('../functions.php');

include('../error.php');

include('permission.php');



$db = & db::getInstance();



if (isset($_GET['mode'])) $mode=sanitizeData($_GET['mode'], 'xss_clean'); 

if (isset($_POST['mode'])) $mode=sanitizeData($_POST['mode'], 'xss_clean'); 

if (isset($_GET['recid'])) $recid=sanitizeData($_GET['recid'], 'xss_clean');  

# retrieve the template

$area = 'member';



switch ($mode) {

    case 'add':

        $txtTitle=sanitizeData($_POST['txtTitle'], 'xss_clean'); 
        $txtTitle = mysqli_real_escape_string($globalMysqlConn,trim($txtTitle));

		if (empty($txtTitle)) {

			$error_message=FORUM_NAME_ERROR;

			display_page($error_message,GENERAL_USER_ERROR);

		}

        $query = "  INSERT INTO bb_forum

                        (forum_title, forum_time, forum_status)

                    VALUES

                        ('$txtTitle', NOW(), 1)";

        $db->query($query);

        break;

    case 'switchon':

        $query="UPDATE bb_forum SET forum_status = '1' WHERE forum_id = '$recid'";

        $db->query($query);

        break;

    case 'switchoff':

        $query="UPDATE bb_forum SET forum_status = '0' WHERE forum_id = '$recid'";

        $db->query($query);

        break;

    case 'delete':

        $query="DELETE FROM bb_forum WHERE forum_id = '$recid'";

        $db->query($query);



        $query="SELECT subtopic_id FROM bb_subtopics WHERE forum_id = '$recid'";

        $aId = $db->get_col($query);

        if (isset($aId)){

            $aId[] = 0;

            $query_topics = join(",",$aId);

            $query="DELETE FROM bb_subtopics WHERE subtopic_id  IN ($query_topics)";

            $db->query($query);

            $arr = $db->get_results("SELECT * FROM bb_posts WHERE topic_id IN ($query_topics)");

            foreach ($arr as $row) {

                include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";

                $File = new ImageFile();

                $File->Init($row->post_id,'forum',$row->post_ext);

                $File->Delete();

                $db->query("DELETE FROM bb_posts WHERE post_id = $row->post_id");

            }

        }

        break;

}

$query="SELECT f.forum_id, forum_title, forum_time, forum_status,

                COUNT(p1.post_id) as all_posts

        FROM bb_forum f

            LEFT JOIN bb_subtopics t

                ON (f.forum_id=t.forum_id)

            LEFT JOIN bb_posts p1

                ON (t.subtopic_id=p1.subtopic_id)

        GROUP BY t.forum_id, forum_title, forum_time, forum_status

        ORDER BY forum_time DESC";

$forums_arr = $db->get_results($query);

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

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>

      <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/admin/adm_bbforums.php" name="FrmTList">

        <input type="hidden" name="mode">

      <table width="100%"  border="0" cellspacing="0" cellpadding="0">

        <tr>

            <td class="tdhead">&nbsp;</td>

        </tr>

        <tr>

            <td align="center">

              <table border='0' cellpadding='2' cellspacing='1' width='100%'>

                <tr>

                  <td height='30' class="tdtoprow" align='left'><b><?=ADVERTISE_TITLE?></b></td>

                  <td height='30' class="tdtoprow" align='center'><b><?=BB_TOPICS_TIME?></b></td>

                  <td height='30' class="tdtoprow" align='center'><b><?=BB_TOPICS_POSTS?></b></td>

                  <td height='30' class="tdtoprow" align='center'><b><?=BB_TOPICS_POSTS_WAIT?></b></td>

                  <td height='30' class="tdtoprow" align='center'><b><?=STATUS?></b></td>

                  <td height='30' width='60' class="tdtoprow">&nbsp;</td>

                  <td height='30' width='60' class="tdtoprow">&nbsp;</td>

                </tr>

<?php

        foreach($forums_arr as $sql_array) {

           $query=" SELECT COUNT(post_id) as wait_posts

                    FROM bb_subtopics t

                        LEFT JOIN bb_posts p

                            ON (t.subtopic_id = p.subtopic_id)

                    WHERE post_approved=0 AND t.forum_id='$sql_array->forum_id'";



           $wait_posts = $db->get_row($query);

           $sql_array->topic_time =  date(" $CONST_FORMAT_DATE_SHORT $CONST_FORMAT_TIME_SHORT",strtotime($sql_array->forum_time));

           print("<tr onMouseOver='selected(this)' onMouseOut='deselected(this)' bgcolor='#DCE8FC'>

                    <td height='30' valign='middle'>$sql_array->forum_title</td>

                    <td height='30' align='center' valign='middle'>$sql_array->forum_time</td>

                    <td height='30' align='center' valign='middle'>$sql_array->all_posts</td>

                    <td height='30' align='center' valign='middle'>$wait_posts->wait_posts</td>

           ");

if ($sql_array->forum_status) {

             print("<td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbforums.php?mode=switchoff&recid=$sql_array->forum_id' title='Change status'>".PREMIUM_FUNC_ACTIVE."</a></td>");

} else {

             print("<td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbforums.php?mode=switchon&recid=$sql_array->forum_id' title='Change status'>".ADM_PAYMENTS_ACTIVATE."</a></td>");

}

           print("

                    <td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbtopics.php?fid=$sql_array->forum_id'>".GENERAL_DETAILS."</a></td>

                    <td height='30' align='center' valign='middle'><a href='$CONST_LINK_ROOT/admin/adm_bbforums.php?mode=delete&recid=$sql_array->forum_id'>".GENERAL_DELETE."</a></td>

                  </tr>");

        }

?>

              </table>

            </td>

        </tr>

        <tr>

            <td class="tdfoot">&nbsp;</td>

        </tr>

        <tr>

            <td align="center">

              <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr>

                  <td colspan="2" class="tdhead"><?=BB_FORUMS_ADD_NEW?></td>

                </tr>

                <tr>

                  <td class="tdeven"><b><?=ADVERTISE_TITLE?>:</b></td>

                  <td class="tdeven"><input type="text" name="txtTitle" size="40" class="inputf"></td>

                </tr>

                <tr>

                  <td colspan="2" class="tdfoot" align="center">

                    <input type='submit' class='button' value='<?=BUTTON_ADD?>' onClick="FrmTList.mode.value='add';">

                    <input type='button' class='button' value='<?=BUTTON_BACK?>' onClick="document.location='<?= $CONST_LINK_ROOT ?>/admin/index.php?';">

                  </td>

                </tr>

              </table>

            </td>

        </tr>

      </table>

      </form>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>