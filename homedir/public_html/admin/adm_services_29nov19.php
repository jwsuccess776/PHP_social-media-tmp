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

# Name:                 adm_services.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

require_once('../error.php');

require_once('../functions.php');

include('permission.php');



if($_GET['psp_service'])

{

    $query = "  SELECT *

                FROM payment_service_params

                WHERE psp_service = '".$_GET['psp_service']."'";

    $res = mysqli_query($globalMysqlConn,$query);

    $row = mysqli_fetch_object($res);



    $type = ($row->psp_type == 'onetime') ? 'recurring' : 'onetime';

    $query = "UPDATE payment_service_params SET psp_type = '$type' WHERE psp_service='".$_GET['psp_service']."'";

	mysqli_query($globalMysqlConn,$query) || die(mysqli_error());

}



$query = "  SELECT *

            FROM payment_service_params ";

$service_res = mysqli_query($globalMysqlConn,$query);



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

    <td class="pageheader"><?php echo ADM_SERVICE_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>



    <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/adm_paysystems.php" name="frmPremFunc">

            <table width="100%"  border="0" cellspacing="0" cellpadding="4">

                <tr>

                    <th width=70% class="tdtoprow"><?=ADM_SERVICES_TITLE?></td>

                    <th width=30% class="tdtoprow"><?=ADM_SERVICES_STATUS?></td>

                </tr>

                <?php



                while  ($service = mysqli_fetch_object($service_res))

                {

                    ?>

                    <tr align=center>

                        <td class="tdodd"><?=$service->psp_title?></td>

                        <td class="tdodd"><a href="<?=$CONST_LINK_ROOT?>/admin/adm_services.php?psp_service=<?=$service->psp_service?>"><?php echo $service->psp_type?></a></td>

                    </tr>

                    <?php

                }

                ?>

                </table>

        </form>

    </td>

  </tr>

	</table>

<?php //mysqli_close( $link ); ?>

<?=$skin->ShowFooter($area)?>