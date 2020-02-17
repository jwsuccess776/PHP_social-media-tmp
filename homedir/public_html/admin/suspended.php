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

# Name:                 unconfirmed.php

#

# Description:  Administrators advert browser page

#

# Version:                7.2

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('permission.php');



$area = 'member';

$list = formGet('userid');

if (formGet('Activate')){
if(!empty($list)) {
    foreach ($list as $id){

        $id = $db->escape($id);

        $db->query("UPDATE members SET mem_suspend='N' WHERE mem_userid='$id'");

    }
    
  }

}



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

    <td class="pageheader"><?=ADM_SUSPENDED_REPORT_SECTION_NAME?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>

      <?php

        $query = "  SELECT *

                    FROM members

                    WHERE mem_suspend = 'Y'

                    ORDER BY mem_username ASC";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$num = mysqli_num_rows($result);

?>

<table width=100%>

    <tr align=left>

      <td align=center colspan=4><?=$num?> <?php echo GENERAL_FOUND_USERS ?></td>

    </tr>

    <tr align=left>

      <th>&nbsp;</th><th><?php echo GENERAL_USERNAME ?></th><th><?php echo REGISTER_EMAIL ?></th><th><?php echo GENERAL_JOINDATE ?></th>

    </tr>

    <form method=POST action="suspended.php">

<?

while ($row = mysqli_fetch_array($result)){?>

    <tr>

	  <td width=3%><input type='checkbox' name='userid[]' id=member value="<?=$row['mem_userid']?>">

      <td width=32%><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$row['mem_userid']?>"> <?=$row[mem_username]?></a></td>

      <td width=32%><a href="mailto:<?=$row[mem_email]?>"><?=$row[mem_email]?></a></td>

      <td width=32%><?=date("$CONST_FORMAT_DATE_SHORT",strtotime($row[mem_joindate]))?></td>

    </tr>

<? } ?>

    <tr align=left>

      <td colspan=4><input type='checkbox' name='select_all' value='Select All' onClick="selectAll(this,'member')"><?=MYEMAIL_SELECT_ALL?></td>

    </tr>

    <tr align=left>

      <td align=center colspan=4><?=$num?> <?php echo GENERAL_FOUND_USERS ?></td>

    </tr>

    <tr align=left>

      <td align=center colspan=4> <input type=submit name="Activate" value="<?=ADM_REACTIVATE_BUTTON?>" class=button></td>

    </tr>

	</form>

  </table>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>