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

# Name:                 adm_reports.php

#

# Description:

#

# Version:                7.2

#

######################################################################

include('../db_connect.php');

include('../session_handler.inc');

include('../message.php');

include('../functions.php');

include('../admin/permission.php');



include('../error.php');

# retrieve the template

$area = 'member';





$query="SELECT * FROM members WHERE mem_userid='$Sess_UserId'";

$retval=mysqli_query($globalMysqlConn,$query);

$member = mysqli_fetch_object($retval);



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader">

        <?=SD_ADM_REPORTS_SECTION_NAME?>

      </td>

    </tr>

    <tr>

      <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

          <tr>

            <td valign=top class="tdhead"><b>

              <?=SD_ADM_REPORT_EVENT_TITLE?>

              </b></td>

          </tr>

          <tr class="tdodd">

            <td valign=top class="tdodd">

              <?=SD_ADM_REPORT_EVENT_COMMENT?>

            </td>

          </tr>

          <tr>

            <td class="tdfoot">

              <b>

              <a href="<?=$CONST_LINK_ROOT?>/speeddating/adm_report_event.php"><?=SD_ADM_REPORT_EVENT_LINK?></a>

              </b> </td>

          </tr>

        </table></td>

    </tr>

  </table>









<?=$skin->ShowFooter($area)?>