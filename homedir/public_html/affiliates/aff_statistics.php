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

# Name:                 aff_statistics.php

#

# Description:  Affiliates performance page

#

# Version:                7.2

#

######################################################################



include('../db_connect.php');

include_once('../validation_functions.php');

include('../session_handler.inc');

include('../admin/permission.php');



$area = 'member';



$mode=(!empty($_POST['mode']))? sanitizeData($_POST['mode'], 'xss_clean') :"view";

$chkASC=(!empty($_POST['chkASC'])) ? sanitizeData($_POST['chkASC'], 'xss_clean') :"DESC";

$lstOrder=(!empty($_POST['lstOrder']))? sanitizeData($_POST['lstOrder'], 'xss_clean') :"aff_username";



?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right">

    <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?=AFF_PERFORM_SECTION_NAME?></td>

  </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

  <tr>

    <td>

      <?php

$query="SELECT aff_userid,aff_username,aff_business,aff_clickthru,SUM(rec_affamount) as earned

FROM affiliates

LEFT JOIN receipts ON (aff_userid=rec_affuserid)

WHERE aff_approved=1

GROUP BY aff_userid,aff_username,aff_business,aff_clickthru

ORDER BY $lstOrder $chkASC";



//$query="SELECT * FROM affiliates WHERE aff_approved=1 ORDER BY $lstOrder $chkASC";

$result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$num = mysqli_num_rows($result);

?>

<table width=100% border="0" cellpadding="2" cellspacing="0">

    <tr>

      <td align="left" valign="top" class="tdhead" colspan="6">&nbsp;</td>

    </tr>

    <form enctype='multipart/form-data' method='post' action='<?php echo $CONST_LINK_ROOT?>/affiliates/aff_statistics.php' name="FrmAffStats">

    <input type='hidden' name='mode'>

    <tr>

      <td align=center valign="top" colspan="6"><?= AFF_ORDER_BY?>

      <select class="input" name="lstOrder" size="1" onChange="FrmAffStats.mode.value='view'; FrmAffStats.submit(); return true;">

        <option value="aff_username"<?php if ($lstOrder=="aff_username") print(" selected"); ?>><?=AFF_USERNAME?></option>

        <option value="aff_business"<?php if ($lstOrder=="aff_business") print(" selected"); ?>><?=AFF_COMPANY?></option>

        <option value="aff_clickthru"<?php if ($lstOrder=="aff_clickthru") print(" selected"); ?>><?=AFF_CLICKS?></option>

        <option value="earned"<?php if ($lstOrder=="earned") print(" selected"); ?>><?=AFF_EARNED?></option>

      </select>

      <select class="inputf" name="chkASC" size="1" onChange="FrmAffStats.mode.value='view'; FrmAffStats.submit(); return true;">

        <option value="ASC"<?php if ($chkASC=="ASC") print(" selected"); ?>><?=AFF_ASC?></option>

        <option value="DESC"<?php if ($chkASC=="DESC") print(" selected"); ?>><?=AFF_DESC?></option>

      </select>

      </td>

    </tr>

    </form>

    <tr>

      <td align="left" valign="top" colspan="6">&nbsp;</td>

    </tr>

    <tr align=left>

      <td align=center colspan=6><?=$num?> <?=AFF_AFFILIATES?></td>

    </tr>

    <tr class='tdtoprow'>

      <th align="left"><?=AFF_USERNAME?></td>

      <th align="left"><?=AFF_COMPANY?></td>

      <th><?=AFF_CLICKS?></td>

      <th><?=AFF_EARNED?></td>

      <th><?=AFF_PAID?></td>

      <th><?=AFF_REFERRED?></td>

    </tr>

<?

while ($row = mysqli_fetch_object($result)) {?>

    <tr>

      <td><?=$row->aff_username?></td>

      <td><?=$row->aff_business?></td>

      <td align="left"><?=$row->aff_clickthru?></td>

<?php

$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$row->aff_userid'";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array = mysqli_fetch_array($retval);

$earned = sprintf("%.2f",$sql_array[0]);

?>

      <td align="left"><?=$earned?></td>

<?php

$query="SELECT SUM(rec_affamount) FROM receipts WHERE rec_affuserid='$row->aff_userid' AND rec_paid='Y'";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array = mysqli_fetch_array($retval);

$paid = sprintf("%.2f",$sql_array[0]);

?>

      <td align="left"><?=$paid?></td>

<?php

$query="SELECT COUNT(*) FROM members WHERE mem_referrer='$row->aff_userid'";

$retval=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

$sql_array = mysqli_fetch_array($retval);

$referred = $sql_array[0];

?>

      <td align="left"><?=$referred?></td>

    </tr>

<? } ?>

    <tr align=left>

      <td class="tdfoot" align=left colspan=6><?=$num?> <?=AFF_AFFILIATES?></td>

    </tr>



  </table>

    </td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>