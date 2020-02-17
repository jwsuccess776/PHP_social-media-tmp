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

# Name:         inactive_members.php

#

# Description:  manage inactive members

#

# Version:      7.2

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

include('../deletes.php');

include_once __INCLUDE_CLASS_PATH."/class.Pager.php";

include('permission.php');



set_time_limit(3600);



# retrieve the template

$area = 'member';



$months = (formGet('months')) ? formGet('months') : 1;



$pager->SetUrl("$CONST_ADMIN_LINK_ROOT/inactive_members.php?month=$months");



$countquery = "SELECT count(*) FROM members WHERE DATE_ADD(mem_lastvisit, INTERVAL $months MONTH) < CURDATE()";

$limit = $pager->GetLimit($db->get_var($countquery));

$sql_query = "SELECT * FROM members WHERE DATE_ADD(mem_lastvisit, INTERVAL $months MONTH) < CURDATE() ORDER BY mem_lastvisit DESC $limit";





if($_POST['mode'] == 'delete')

{ 

    if($months)

    {

        restrict_demo();

        $sql_result_members = mysqli_query($globalMysqlConn,$sql_query);

        while($member = mysqli_fetch_object($sql_result_members))

        {

             

			delete_advert($member->mem_userid);

            delete_me($member->mem_userid);

            delete_match($member->mem_userid);

       }

    }

}





$sql_result_members = mysqli_query($globalMysqlConn,$sql_query);

?>

<?=$skin->ShowHeader($area)?>







  <table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

    <td class="pageheader"><?=INACTIVE_MEMBERS_SECTION_NAME?></td>

    </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td><form  method="post" name="frmInactive" action="<?=$CONST_LINK_ROOT?>/admin/inactive_members.php?months=<?=$months?>" ><table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

          <tr>

            <td colspan="5" align='left' valign='top' class="tdhead">&nbsp;</td>

          </tr>

            <tr>

                <td colspan="5" align='center' valign='top' class="tdeven">

                  <? include "../pager.php"?>

                </td>

            </tr>



          <input type="hidden" name="mode" value="delete">

          <tr class="tdtoprow">

            <td align='left' valign='top' class="tdodd"></td>

            <td align='left' valign='top' class="tdodd" colspan="2">

              <b><?=INACTIVE_MEMBERS_DURATION?>: </b>

              <select class="input" name="months" onchange="window.location = '<?=$CONST_LINK_ROOT?>/admin/inactive_members.php?months=' + this.options[this.selectedIndex].value;">

                <?php for($i = 1; $i <= 136; $i++) { ?>

                    <option value="<?=$i?>"<?php if($months == $i) echo ' selected'; ?>><?=$i?>

                <?php } ?>

              </select>

              <?=INACTIVE_MEMBERS_MONTHS?>

            </td>

            <td align='left' valign='top' class="tdodd" colspan="2">

                <input type="submit" value="<?=INACTIVE_MEMBERS_DELETE?>" class="button" onclick="return(confirm('<?=INACTIVE_MEMBERS_TEXT2?>') ? confirm('<?=INACTIVE_MEMBERS_TEXT3?>') : false);">

            </td>

          </tr>

          <tr>

            <td colspan="5" align='left' valign='top' class="tdeven">&nbsp;</td>

          </tr>

          <?php if(mysqli_num_rows($sql_result_members)) { ?>

            <tr class='tdhead'>

                 <td>&nbsp;</td>

                 <td><b><?=INACTIVE_MEMBERS_USERNAME?></b></td>

                 <td><b><?=INACTIVE_MEMBERS_FORENAME?></b></td>

                 <td><b><?=INACTIVE_MEMBERS_SURNAME?></b></td>

                 <td><b><?=INACTIVE_MEMBERS_LOGIN_DATE?></b></td>

            </tr>

            <?php while ($member = mysqli_fetch_object($sql_result_members)) { ?>

                <tr class='tdodd'>

                    <td>&nbsp;</td>

                    <td><?=$member->mem_username?></td>

                    <td><?=$member->mem_surname?></td>

                    <td><?=$member->mem_forename?></td>

                    <td><?=date($CONST_FORMAT_DATE_SHORT, strtotime($member->mem_lastvisit))?></td>

                </tr>

            <?php } ?>

          <?php } else { ?>

            <tr>

                <td colspan="5" align='center' valign='top' class="tdeven"><b><?=INACTIVE_MEMBERS_TEXT1?></b></td>

            </tr>

          <?php } ?>

            <tr>

                <td colspan="5" align='center' valign='top' class="tdeven">

                  <? include "../pager.php"?>

                </td>

            </tr>

      </table></form></td>

    </tr>

  </table>

<?=$skin->ShowFooter($area)?>

