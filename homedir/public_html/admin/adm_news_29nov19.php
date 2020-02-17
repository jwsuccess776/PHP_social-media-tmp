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

# Name:                 adm_news.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('permission.php');



if($_POST['act'] == 'save') {

    $title = stripslashes(formGet('title'));



	if (empty($title)) {

        error_page("Field Title is empty",GENERAL_USER_ERROR);

	}

	$body=stripslashes(formGet("body"));

//	$body=formGet("body");

    if ($_POST['id'])

        $sql_query = "UPDATE news SET title = '".mysqli_real_escape_string($globalMysqlConn,$title)."', body = '".mysqli_real_escape_string($globalMysqlConn,$body)."' WHERE news_id = '".$_POST['id']."'";

    else

        $sql_query = "INSERT INTO news SET title = '".mysqli_escape_string($globalMysqlConn,$title)."', body = '".mysqli_escape_string($globalMysqlConn,$body)."'";

    mysqli_query($globalMysqlConn, $sql_query);

    if (empty($_POST['id']))

        $id = mysqli_insert_id($globalMysqlConn);

    else

        $id = $_POST['id'];

}

elseif($_GET['act'] == 'remove')

{

    $row = $db->get_row("SELECT * FROM news WHERE news_id = '".$_GET['id']."'");

    if ($row) {

        $db->query("DELETE FROM news WHERE news_id = $row->news_id");

    }



}



if($_GET['act'])

    header("Location: $CONST_LINK_ROOT/admin/adm_news.php");



# retrieve the template

$area = 'member';



$sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM news");



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo SD_ADM_NEWS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>



    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr>

      <td colspan="2" align="center" class="tdhead">

        <input type="button" class='button' onclick="document.location.href = '<?=$CONST_LINK_ROOT?>/admin/adm_news_edit.php'" value="<?=SD_ADM_NEWS_ADD?>">

      </td>

    </tr>

    <tr class="tdtoprow" align="center">

      <td>

        <?=SD_ADM_NEWS_TITLE?>

      </td>

      <td >

        <?=GENERAL_DELETE?>

      </td>

     </tr>

        <?php

          while($news = mysqli_fetch_object($sql_result)) {

        ?>



    <tr align=center class="tdodd" >

      <td><a href="<?=$CONST_LINK_ROOT?>/admin/adm_news_edit.php?id=<?=$news->news_id?>">

        <?=htmlspecialchars($news->title)?>

        </a>

      </td>

      <td>

        <a href="<?=$CONST_LINK_ROOT?>/admin/adm_news.php?act=remove&id=<?=$news->news_id?>" onClick="if (confirm('<?=SD_ADM_NEWS_TEXT1?>')) {return true;} else {return false;}" >

            [<?=GENERAL_DELETE?>]

        </a>

      </td>

    </tr>

    <?php } ?>



    <tr>

      <td colspan="2" align="center" class="tdfoot">&nbsp; </td>

    </tr>



  </table>



      </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>