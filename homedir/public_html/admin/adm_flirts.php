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

# Name:                 adm_flirts.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../error.php');

include('../functions.php');

include('../message.php');

include('permission.php');



$text = formGet('text');

$Flirt_ID = formGet('Flirt_ID');

$lang = formGet('lang');

if($_POST['act'] == 'save') {

    restrict_demo();

    if ($Flirt_ID) {

        $data = $db->escape($text[$lang]);

        $Flirt_ID = $db->escape($Flirt_ID);

        $lang = $db->escape($lang);

	    $db->query("REPLACE lang_flirt SET Text = '$data', Flirt_ID = '$Flirt_ID', lang_id = '$lang' ");

    } else {

	    $sql_query = "INSERT INTO flirt SET Flirt_ID = NULL";

	    $db->query($sql_query);

	    $id = $db->insert_id;

	    foreach ($text as $lang=> $data){

	        $data = $db->escape($data);

	        $sql_query = "INSERT INTO lang_flirt SET

	        				Text     = '$data',

	        				lang_id  = '$lang',

	        				Flirt_ID = '$id'";

	        $db->query($sql_query);

	    }

    }

}

elseif($_GET['act'] == 'remove')

{

    restrict_demo();

	$Flirt_ID = $db->escape(formGet('Flirt_ID'));

    $db->query("DELETE FROM flirt WHERE Flirt_ID = '$Flirt_ID'");

    $db->query("DELETE FROM lang_flirt WHERE Flirt_ID = '$Flirt_ID'");

}



if($_GET['act'])

    header("Location: $CONST_LINK_ROOT/admin/adm_flirts.php");



# retrieve the template

$area = 'member';



$sql_result = mysqli_query($globalMysqlConn," SELECT *,f.Flirt_ID

							FROM flirt AS f

							LEFT JOIN lang_flirt as fl ON (fl.Flirt_ID = f.Flirt_ID AND lang_id='$language->LangID')

							");



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo ADM_FLIRTS_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>



	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr>

      <td colspan="2" align="left" class="tdhead">

        <input type="button" class='button' onclick="document.location.href = '<?=$CONST_LINK_ROOT?>/admin/adm_flirts_add.php'" value="<?=ADM_FLIRTS_ADD?>">

      </td>

    </tr>

    <tr class="tdtoprow" align="left">

      <td>

        <?=ADM_FLIRTS_TEXT?>

      </td>

      <td>

        <?=BUTTON_EDIT?>

      </td>

      <td >

        <?=GENERAL_DELETE?>

      </td>

     </tr>

        <?php

          while($flirt = mysqli_fetch_object($sql_result)) {

        ?>



    <tr align=left class="tdodd" >

      <td>

        <?=htmlspecialchars($flirt->Text)?>

      </td>

      <td>

		<?foreach($language->GetActiveList() as $lang) {?>

		<a href="<?=$CONST_LINK_ROOT?>/admin/adm_flirts_add.php?mode=edit&Flirt_ID=<?=$flirt->Flirt_ID?>&lang=<?=$lang->LangID?>">[<?=$lang->LangID?>]</a>

		<?}?>

      </td>

      <td>

        <a href="<?=$CONST_LINK_ROOT?>/admin/adm_flirts.php?act=remove&Flirt_ID=<?=$flirt->Flirt_ID?>" onClick="if (confirm('<?=ADM_FLIRTS_DELETE_CONFIRM?>')) {return true;} else {return false;}" >

            [<?=GENERAL_DELETE?>]

        </a>

      </td>

    </tr>

    <?php } ?>

	<tr>

      <td colspan="2" align="left" class="tdfoot">&nbsp; </td>

    </tr>

  </table>



	  </td>

    </tr>

  </table>

<?php // mysql_close( $link ); ?>

<?=$skin->ShowFooter($area)?>