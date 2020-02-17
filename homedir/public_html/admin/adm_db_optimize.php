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

# Name:                 adm_db_optimize.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('permission.php');



$message = "";



if (isset($_POST["optimize"])) {

    $query = "SHOW TABLE STATUS";

//    echo $query;

    $result=mysqli_query($globalMysqlConn, $query);

    $table_list = "";

    while ($cur_table = mysqli_fetch_object($result)) {

        $table_list .= $cur_table->Name.", ";

//        echo $cur_table->Name;

    }



    if (!empty($table_list)) {

//    echo $table_list."!!!!";

        $table_list = substr($table_list, 0, -2);

//    echo $table_list;

        $query = "OPTIMIZE TABLE ".$table_list;

        mysqli_query($globalMysqlConn,$query);

//        echo $query;

        $message = DB_OPTIMIZE_SUCCESS;

    }

}



# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo DB_OPTIMIZE_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td><?php echo DB_OPTIMIZE_TEXT?></td>

  </tr>

  <tr>

    <td>

        <table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <form action="<?php echo $CONST_LINK_ROOT?>/admin/adm_db_optimize.php" method="post" name="frmEmails">

        <input type="hidden" name="optimize" value="yes">

        <?

        if (!empty($message)) {

            echo '<tr><td colspan="2" class="tdhead">'.$message.'</td></tr>';

        }

        ?>

          <tr align="center">

            <td colspan="2" class="tdfoot"> <input name="Submit" type="submit" class="button" value="<?php echo DB_OPTIMIZE_BUTTON?>">

            </td>

          </tr>

        </form>

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>