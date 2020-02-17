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

# Name:                 adm_mailtemplates.php

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

include('permission.php');


$mtm = new MTemplateManager;
$mailtemplate = $mtm->getInstance();

$lng = new Language;
$lang = $lng->getInstance();

$pager->SetUrl($CONST_LINK_ROOT."/admin/adm_mailtemplates.php");

# retrieve the template

$area = 'member';



$aMtemplates = $mailtemplate->GetList($pager);

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?php echo ADM_MAILTEMPLATES_SECTION_NAME ?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>

	<?include "../pager.php"?>

	<table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr class="tdtoprow" align="left">

      <td>

        <?=ADM_MAILTEMPLATES_DESCRIPTION?>

      </td>

      <td >

        <?=ADM_MAILTEMPLATES_TYPE?>

      </td>

      <td >

        <?=GENERAL_EDIT?>

      </td>

     </tr>

        <?php

        foreach ($aMtemplates as $row){

        ?>



    <tr align=left class="tdodd" >

      <td>

        <?=$row->comments?>

      </td>

      <td>

        <?=$row->type?>

      </td>

      <td>

      <?foreach($lang->GetActiveList() as $lang_row){?>

        <a href="<?=$CONST_LINK_ROOT?>/admin/adm_mailtemplates_edit.php?Name=<?=$row->name?>&LANG_ID=<?=$lang_row->LangID?>">

            [<?=$lang_row->LangID?>]

        </a>

        <?}?>

      </td>

    </tr>

    <?php } ?>



	<tr>

      <td colspan="3" align="center" class="tdfoot">&nbsp; </td>

    </tr>



  </table>



	  </td>

    </tr>

  </table>

<?php //mysql_close( $link ); ?>

<?=$skin->ShowFooter($area)?>