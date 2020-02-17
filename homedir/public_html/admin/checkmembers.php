<?php

/*****************************************************

* � copyright 1999 - 2020 iDateMedia, LLC.

*

* All materials and software are copyrighted by iDateMedia, LLC.

* under British, US and International copyright law. All rights reserved.

* No part of this code may be reproduced, sold, distributed

* or otherwise used in whole or in part without prior written permission.

*

*****************************************************/

######################################################################

#

# Name:                 checkmembers.php

#

# Description:  Administrators member browser page

#

# Version:                7.2

#

######################################################################



include('../db_connect.php');

include('../session_handler.inc');

include('permission.php');



$orderStru=$orderStrj=$orderStre=$orderStrf=$orderStrl=$orderStrs="<strong>&darr;</strong>";

$ordByad="DESC";$ad='a';$arrow="<font color=red>&darr;</font>";



if (isset($_REQUEST['ad'])) {

	if ($_REQUEST['ad'] == 'a') {

		$ordByad="ASC";

		$arrow="<font color=red>&uarr;</font>";

		$ad='d';

	} else {

		$ordByad="DESC";

		$ad='a';

		$arrow="<font color=red>&darr;</font>";

	}

} 

if (isset($_REQUEST['ord'])) {

	switch ($_REQUEST['ord']) {

		case 'e':

			$orderByStr="ORDER BY mem_email ";

			$orderStre="<strong>$arrow</strong>";

			break;

		case 'u':

			$orderByStr="ORDER BY mem_username ";

			$orderStru="<strong>$arrow</strong>";

			break;

		case 'j':

			$orderByStr="ORDER BY mem_joindate ";

			$orderStrj="<strong>$arrow</strong>";

			break;

		case 'l':

			$orderByStr="ORDER BY mem_surname ";

			$orderStrl="<strong>$arrow</strong>";

			break;

		case 'f':

			$orderByStr="ORDER BY mem_forename ";

			$orderStrf="<strong>$arrow</strong>";

			break;

		case 's':

			$orderByStr="ORDER BY mem_status ";

			$orderStrs="<strong>$arrow</strong>";

			break;

		default:

			$orderByStr="ORDER BY mem_joindate ";

			$orderStrj="<strong>$arrow</strong>";

			break;

	}

} else {

		$orderByStr="ORDER BY mem_joindate ";

		$orderStrj="<strong>$arrow</strong>";

}









# retrieve the template

$area = 'member';

$count = $db->get_var("SELECT count(*) FROM adverts INNER JOIN members ON (adv_userid=mem_userid)");

$limit = $pager->GetLimit($count);

$pager->SetUrl("$CONST_ADMIN_LINK_ROOT/checkmembers.php");



$query = "SELECT *,mem_password FROM adverts LEFT JOIN members ON (adv_userid=mem_userid) $orderByStr $ordByad $limit";

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

    <td class="pageheader"><?php echo CHECK_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><? include("admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td align=right><?include "../search_pager.php"?></td>

  </tr>

  <tr>

    <td ><hr></td>

  </tr>

  <tr><td>

<?php

if ($num != 0) {

    echo "<p><table cellspacing=0 cellpadding=3>";

        echo "<tr>

			  <td width=15% class='td2'>".GENERAL_USERNAME."<a href='$CONST_LINK_ROOT/admin/checkmembers.php?ord=u&ad=$ad'>$orderStru</a></td>

              <td width=10% class='td2'>".GENERAL_JOINDATE."<a href='$CONST_LINK_ROOT/admin/checkmembers.php?ord=j&ad=$ad'>$orderStrj</a></td>

              <td width=25% class='td2'>".REGISTER_EMAIL."<a href='$CONST_LINK_ROOT/admin/checkmembers.php?ord=e&ad=$ad'>$orderStre</a></td>

              <td width=15% class='td2'>".REGISTER_FIRST_NAME."<a href='$CONST_LINK_ROOT/admin/checkmembers.php?ord=f&ad=$ad'>$orderStrf</a></td>

              <td width=15% class='td2'>".REGISTER_LAST_NAME."<a href='$CONST_LINK_ROOT/admin/checkmembers.php?ord=l&ad=$ad'>$orderStrl</a></td>

              <td width=10% class='td2'>".STATUS."<a href='$CONST_LINK_ROOT/admin/checkmembers.php?ord=s&ad=$ad'>$orderStrs</a></td>

              <td width=10% class='td2'>IP address</td>

			 </tr>";

    while ($row = mysqli_fetch_object($result)) {

		$testdate=date("Y-m-d");

		if ($row->mem_userid=="") continue;

		if ($row->mem_expiredate < $testdate) {

			$status=str_replace("Member","",STATUS_S);

		} else {

			$status=str_replace("Member","",STATUS_P);

		}

        echo "<tr>

			  <td class='$td'><a href='$CONST_LINK_ROOT/prgretuser.php?userid=$row->mem_userid' target='_blank'>$row->mem_username</a></td>

              <td class='$td'>$row->mem_joindate</td>

              <td class='$td'>$row->mem_email</td>

              <td class='$td'>$row->mem_forename</td>

              <td class='$td'>$row->mem_surname</td>

              <td class='$td'>$status</td>

              <td class='$td'>$row->mem_ip</td>

			 </tr>";

       

    } echo "</table>";

} else {

  echo CHECKADS_TEXT3;

  exit;

} ?>

    </td>

  </tr>

  <tr>

    <td align=right><hr></td>

  </tr>

  <tr>

    <td align=right><?include "../search_pager.php"?></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>