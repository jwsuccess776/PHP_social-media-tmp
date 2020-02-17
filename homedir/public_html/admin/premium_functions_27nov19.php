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

# Name: 		premium_functions.php

#

# Description:

#

# # Version:      8.0

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

include('../error.php');

include('permission.php');



if($_POST['action'] == 'add')

{

    restrict_demo();

    $name = mysqli_real_escape_string($globalMysqlConn,$_POST['name']);

    $uri = mysqli_real_escape_string($globalMysqlConn,$_POST['uri']);

    $sql_query = "INSERT INTO premium_func (prf_name, prf_uri) VALUES ('$name', '$uri')";

    mysqli_query($globalMysqlConn,$sql_query);

    header('Location: '.$CONST_LINK_ROOT.'/admin/premium_functions.php');

    exit;

}

if($_POST['action'] == 'save')

{

    restrict_demo();

    $sql_result = mysqli_query($globalMysqlConn,"SELECT prf_id FROM premium_func");

    while($func = mysqli_fetch_object($sql_result))

    {

        $chkName = 'active'.$func->prf_id;

        if(isset($_POST[$chkName]) && $_POST[$chkName] == '1')

            $active = 1;

        else

            $active = 0;

        $sql_query = "

            UPDATE premium_func

            SET

                prf_name = '".mysqli_real_escape_string($globalMysqlConn,$_POST['name'.$func->prf_id])."',

                prf_uri = '".mysqli_real_escape_string($globalMysqlConn,$_POST['uri'.$func->prf_id])."',

                prf_app = ".$_POST['app'.$func->prf_id].",

                prf_active = $active

            WHERE prf_id = $func->prf_id

        ";

        mysqli_query($globalMysqlConn, $sql_query);

    }

//	header('Location: '.$CONST_LINK_ROOT.'/premium_functions.php');

//	exit;

}

if($_GET['action'] == 'remove')

{

    restrict_demo();

    $prf_id = $_GET['prf_id'];

    $sql_query = "DELETE FROM premium_func WHERE prf_id = $prf_id";

    mysqli_query($globalMysqlConn,$sql_query);

    header('Location: '.$CONST_LINK_ROOT.'/admin/premium_functions.php');

    exit;

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

    <td class="pageheader"><?php echo PREMIUM_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td><?php echo PREMIUM_FUNC_HELP ?></td>

  </tr>

  <tr>

    <td>

    <table width="100%"  border="0" cellspacing="" cellpadding="">

        <?php

            $sql_result = mysqli_query($globalMysqlConn,"SELECT * FROM premium_func");

            if(mysqli_num_rows($sql_result))

            {

                ?>



        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/premium_functions.php" name="frmPremFunc">

          <input type="hidden" name="action" value="save">

          <tr>

            <td align="center">

    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr  class="tdhead">

                  <td colspan="7" align="center" class="tdhead">&nbsp;</td>

                </tr>

                <tr>

                  <td rowspan="3" align="center" class="tdtoprow"><b>

                    <?=PREMIUM_FUNC_NAME?>

                    </b></td>

                  <td rowspan="3" align="center" class="tdtoprow"><b>

                    <?=PREMIUM_FUNC_URI?>

                    </b></td>

                  <td colspan="3" align="center" class="tdtoprow"><b>

                    <?=PREMIUM_FUNC_ERROR_APP?>

                </b></td>

                  <td rowspan="3" align="center" class="tdtoprow"><b>

                    <?=PREMIUM_FUNC_ACTIVE?>

                    </b></td>

                  <td rowspan="3" align="center" class="tdtoprow"><b>

                    <?=PREMIUM_FUNC_ACTION?>

                    </b></td>

                </tr>



                <tr>

                  <td rowspan="2" align="center" class="tdeven">

                    <?=PREMIUM_FUNC_ERROR_APP_WINDOW?>



              </td>

                  <td colspan="2" align="center" class="tdeven">

                    <?=PREMIUM_FUNC_ERROR_APP_MESSAGE?>

              </td>

                </tr>



                <tr>

                  <td align="center" class="tdodd">

                    <?=PREMIUM_FUNC_ERROR_APP_WINDOW_CLOSE?>

              </td>

                  <td align="center" class="tdodd">

                    <?=PREMIUM_FUNC_ERROR_APP_WINDOW_BACK?>

              </td>

                </tr>



                <?php

                            while($func = mysqli_fetch_object($sql_result))

                            {

                                ?>



                <tr class="tdodd">



                  <td><input type="text" class="input" name="name<?=$func->prf_id?>" value="<?=htmlspecialchars($func->prf_name)?>" class=input></td>

                  <td><input type="text" class="input" name="uri<?=$func->prf_id?>" value="<?=htmlspecialchars($func->prf_uri)?>" class=input></td>

                  <td align="center"><input type="radio" name="app<?=$func->prf_id?>" value="0"<?php if($func->prf_app == 0) echo ' checked'; ?>></td>

                  <td align="center"><input type="radio" name="app<?=$func->prf_id?>" value="1"<?php if($func->prf_app == 1) echo ' checked'; ?>></td>

                  <td align="center"><input type="radio" name="app<?=$func->prf_id?>" value="2"<?php if($func->prf_app == 2) echo ' checked'; ?>></td>

                  <td align="center"><input type="checkbox" name="active<?=$func->prf_id?>" value="1"<?php if($func->prf_active) echo ' checked'; ?>></td>

                  <td><a href="<?=$CONST_LINK_ROOT?>/admin/premium_functions.php?action=remove&prf_id=<?=$func->prf_id?>">remove</a></td>

                </tr>



                <?php

                            }

                            ?>

                <tr>

                  <td align="center" class="tdfoot" colspan="7"><input name="submit" type="submit" class="button" value="<?=PREMIUM_FUNC_SAVE?>"></td>

                </tr>

              </table>

            </td>

          </tr>

          <tr>

            <td align="left"></td>

          </tr>

          <tr>

            <td align="left"></td>

          </tr>

        </form>

        <?php

            }

            ?>

        <tr>

          <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/premium_functions.php" name="frmPremFunc">

            <input type="hidden" name="action" value="add">

            <td align="center">

    <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

                <tr>

                  <td colspan="2" class="tdhead">&nbsp;</td>

                </tr>

                <tr>

                  <td width="25%" class="tdodd"><b>

                    <?=PREMIUM_FUNC_NAME?>

                :</b></td>

                  <td width="75%" class="tdodd"><input type="text" name="name" class="inputl"></td>

                </tr>

                <tr>

                  <td class="tdeven"><b>

                    <?=PREMIUM_FUNC_URI?>

                :</b></td>

                  <td class="tdeven"><input type="text" name="uri"  class="inputl"></td>

                </tr>

                <tr>

                  <td colspan="2"   class="tdfoot" align="center"><input name="submit" type="submit" class="button" value="<?=PREMIUM_FUNC_ADD?>"></td>

                </tr>

                </table></td>

          </form>

        </tr>

  </table>

    </td>

  </tr>

</table>

<?php

// mysql_close( $link );

?>

<?=$skin->ShowFooter($area)?>