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



require_once __INCLUDE_CLASS_PATH."/class.SMS.php";



if($_POST['act'] == 'save') {

    $sms = new SMS();

    $sms->title = formGet('title');

    $sms->email = formGet('email');

	$sms->id = formGet('id');

	$sms->status = 1;

	$res = $sms->save();

	if ($res === null){

        error_page(join("<br>", $sms->error), GENERAL_USER_ERROR);

	} 



} elseif($_GET['act'] == 'remove') {

    $sms = new SMS(formGet('id'));

	$sms->delete();

}



if($_GET['act'])

    header("Location: $CONST_LINK_ROOT/admin/adm_sms.php");



# retrieve the template

$area = 'member';



$pager->setURL("$CONST_ADMIN_ROOT/adm_sms.php");

$ss = new SMS;
$list = $ss->getList($pager);



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?=ADM_MANAGE_SMS?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td>



    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr>

      <td colspan="2" align="center" class="tdhead">

        <input type="button" class='button' onclick="document.location.href = '<?=$CONST_LINK_ROOT?>/admin/adm_sms_edit.php'" value="ADD">

      </td>

    </tr>

    <tr class="tdtoprow" align="center">

      <td>

        <?=ADVERTISE_TITLE?>

      </td>

      <td >

        <?=GENERAL_DELETE?>

      </td>

     </tr>

        <?php

          foreach ($list as $sms) {

        ?>



    <tr align=center class="tdodd" >

        <td>

  		<a href="<?=$CONST_LINK_ROOT?>/admin/adm_sms_edit.php?id=<?=$sms->id?>"><?=$sms->title?></a>

        </td>

        <td>

          <a href="<?=$CONST_LINK_ROOT?>/admin/adm_sms.php?act=remove&id=<?=$sms->id?>" onClick="return delete_alert_general();" >

              [<?=GENERAL_DELETE?>]

          </a>

        </td>

	</tr>

    <?php } ?>

  </table>

      </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>