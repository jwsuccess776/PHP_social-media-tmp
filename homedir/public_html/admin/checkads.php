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

# Name:                 checkads.php

#

# Description:  Administrators advert browser page

#

# Version:                7.2

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('permission.php');

# retrieve the template

$area = 'member';

$count = $db->get_var("SELECT count(*) FROM adverts INNER JOIN members ON (adv_userid=mem_userid)");

$limit = $pager->GetLimit($count);

$pager->SetUrl("$CONST_ADMIN_LINK_ROOT/checkads.php");



$query = "SELECT *,mem_password FROM adverts LEFT JOIN members ON (adv_userid=mem_userid) ORDER BY adv_createdate desc $limit";

$result=mysqli_query($globalMysqlConn,$query) or die("Could not complete database query");

$num = mysqli_num_rows($result);



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo BROWSE_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td align=right><?include "../search_pager.php"?></td>

  </tr>

  <tr><td>

<?php

if ($num != 0) {

    while ($row = mysqli_fetch_array($result)) {

        $adv_comment=stripslashes($row['adv_comment']);
        $adv_username=$row['adv_username'];
        $mem_password=$row['mem_password'];

        echo "<p><table cellspacing=0 cellpadding=3><tr><td class='td2'>Username: $adv_username</td></tr>";

        echo "<tr><td class='td1'>Password: $mem_password</td></tr>";

        echo "<tr><td class='td2'>Message: $adv_comment</td></tr>";

        echo "</table></p>";

    }

} else {

  echo CHECKADS_TEXT3;

  exit;

} ?>

    </td>

  </tr>

  <tr>

    <td align=right><?include "../search_pager.php"?></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>