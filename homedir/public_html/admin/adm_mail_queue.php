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

require_once __INCLUDE_CLASS_PATH."/class.MailQueue.php";





$queue             = new MailQueue();

$pager->SetUrl($CONST_LINK_ROOT."/admin/adm_mail_queue.php");



if (formGet('DEL')) {



    foreach ((array)formGet('MailQueue_ID') as $id ) {

        $queue->Init($id);

        $queue->Delete();

    }

}



if (formGet('DELALL')) {

	

	$result=mysql_query($globalMysqlConn,"DELETE FROM mail_queue");



}



# retrieve the template

$area = 'member';

$aList = $queue->getList($pager);

?>

<?=$skin->ShowHeader($area)?>

<script language="javascript">

    var flag=true;

    function selectAll(el, id){

       var elems = el.form.elements;

       for(var i = 0; i < elems.length; i++){

          if(elems[i].type == "checkbox" && elems[i].id == id) {

            elems[i].checked = flag;

          }

       }

       if (flag) {

           el.value = "Unselect All";

       } else {

           el.value = "Select All";

       }

        flag=!flag;

    }

</script>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

        <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?=ADM_MAIL_QUEUE_SECTION_NAME?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td Align=right>

    <?include "../pager.php"?>

    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

    <tr class="tdtoprow" align="left">

      <td width="3%">&nbsp;</td>

      <td width="30%"><?=AFF_ACCOUNT_EMAIL?></td>

      <td width="67%"><?=ADMINMAIL_SUB?></td>

     </tr>

    <form method=POST action="<?=$CONST_ADMIN_LINK_ROOT?>/adm_mail_queue.php">

     <?php foreach ($aList as $row){ ?>

    <tr align=left class="tdodd" >

      <td><input type='checkbox' name='MailQueue_ID[]' id=member value="<?=$row->MailQueue_ID?>"></td>

      <td><?=$row->Email?></td>

      <td><?=$row->Subject?></td>

    </tr>

    <?php } ?>

    <tr align=left>

      <td colspan=3><input type='checkbox' name='select_all' value='Select All' onClick="selectAll(this,'member')"><?=MYEMAIL_SELECT_ALL?></td>

    </tr>

    <tr align=left>

      <td align=center colspan=4> <input type=submit name="DEL" value="<?=BUTTON_REMOVE_SELECTED?>" class=button> <input type=submit name="DELALL" value="<?=BUTTON_REMOVE_QUEUE?>" class=button onClick="return delete_alert_general();"></td>

    </tr>

    </form>

    <tr>

      <td colspan="3" align="center" class="tdfoot"><a href="<?=$CONST_ADMIN_LINK_ROOT?>"><?=BUTTON_BACK?></a> </td>

    </tr>



  </table>



      </td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>