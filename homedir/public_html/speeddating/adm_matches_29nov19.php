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

# Name:                 adm_matches.php

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

include('../admin/permission.php');



$event_id = $_REQUEST["event_id"];

if (isset($_GET['mode'])) $mode=$_GET['mode'];

if (isset($_POST['mode'])) $mode=$_POST['mode'];

if (isset($_GET['uid1'])) $uid1=$_GET['uid1'];

if (isset($_POST['uid1'])) $uid1=$_POST['uid1'];

if (isset($_GET['uid2'])) $uid2=$_GET['uid2'];

if (isset($_POST['uid2'])) $uid2=$_POST['uid2'];



if (empty($event_id)) {

        $error_message=SD_DATE_RESULT_ERROR_ID;

        error_page($error_message,GENERAL_USER_ERROR);

        exit;

}



if ( isset( $_POST['chkDelete']) ) {

    $chkDelete=$_POST['chkDelete'];

    foreach ( $chkDelete as $value) {

        $toDel = explode(":",$value);

        $uid1 = $toDel[0];

        $uid2 = $toDel[1];

        $query="DELETE FROM sd_matches WHERE sdm_eventid  = '$event_id' AND sdm_userid_1 = '$uid1' AND sdm_userid_2 = '$uid2'";

        $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        unset($uid1);

        unset($uid2);

    }

}



switch ($mode) {

    case 'add':

        if (!empty($uid1) && !empty($uid2)) {

            $query = "INSERT INTO sd_matches (sdm_eventid, sdm_userid_1, sdm_userid_2) VALUES ('".$event_id."', '".$uid1."', '".$uid2."')";

            mysqli_query($globalMysqlConn,$query);

            unset($uid1);

            unset($uid2);

        }

        break;

    case 'delete':

        $query="DELETE FROM sd_matches WHERE sdm_eventid  = '$event_id' AND sdm_userid_1 = '$uid1' AND sdm_userid_2 = '$uid2'";

        mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        break;

}

$ticket1_query = " SELECT * FROM `sd_tickets` a

                   INNER JOIN members b

                        ON (a.sdt_userid = b.mem_userid)

                   WHERE sdt_eventid = '$event_id'

                 ";

$ticket1_res = mysqli_query($globalMysqlConn,$ticket1_query) or die(mysqli_error());

$ticket1_count = mysqli_num_rows($ticket1_res);

/*

$ticket2_query = " SELECT * FROM `sd_tickets` a

                   LEFT JOIN members b

                        ON (a.sdt_userid = b.mem_userid)

                   WHERE sdt_eventid = '$event_id'

                   AND sdt_gender = 'Gender2'

                 ";

$ticket2_res = mysqli_query($globalMysqlConn,$ticket2_query) or die(mysqli_error());

$ticket2_count = mysqli_num_rows($ticket2_res);

*/

$event_query = " SELECT * FROM `sd_events`

                 WHERE sde_eventid = '$event_id'";

$event_res = mysqli_query($globalMysqlConn,$event_query) or die(mysqli_error());

$event = mysqli_fetch_object($event_res);



$sql_query = "  SELECT m.*,m1.mem_username as mem1_username,m1.mem_email as mem1_email,m2.mem_username as mem2_username,m2.mem_email as mem2_email

                FROM sd_matches m

                    LEFT JOIN members m1

                        ON ( m.sdm_userid_1 = m1.mem_userid )

                    LEFT JOIN members m2

                        ON ( m.sdm_userid_2 = m2.mem_userid )

                WHERE m.sdm_eventid = '$event_id'

                ORDER BY mem1_username,mem2_username";

//echo $sql_query;

$matches = mysqli_query($globalMysqlConn, $sql_query) or die(mysqli_error());

# retrieve the template

$area = 'member';



?>

<?=$skin->ShowHeader($area)?>

<script language=JavaScript>

function selectAll(frm_name,fld_name,selected){

  var elms = document.forms[frm_name].elements;

  for(var i=0; i<elms.length; i++){

    if (elms[i].name == fld_name) {

      elms[i].selected = selected;

      elms[i].checked = selected;

    }

  }

}

</script>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

    <tr>

      <td align="right">

      <?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

      </td>

    </tr>

    <tr>

      <td class="pageheader"><?=SPEED_DATE_RESULT_SECTION_NAME?></td>

    </tr>

  <tr>

    <td><? include("../admin/admin_menu.inc.php");?></td>

  </tr>

    <tr>

      <td ><?=SD_DATE_RESULT_NAME?> : <?php print("<strong>$event->sde_name</strong>"); ?></td>

    </tr>

    <tr>

      <td ><p><?=SD_DATE_RESULT_DATE?> : <?php print("<strong>$event->sde_date</strong>"); ?></p></td>

    </tr>

      <form method="post" action="<?php echo $CONST_SD_URL?>/adm_matches.php?event_id=<?=$event_id?>" name="FrmQList">

        <input type="hidden" name="mode">

        <tr>

            <td align="left"><br>

              <?=GENERAL_MEMBER?> :

                  <select class=inputf size="1" name="uid1">

                    <option value='' selected>- <?php echo GENERAL_CHOOSE?> -</option>

<?php

        while ($sql_array = mysqli_fetch_object($ticket1_res)) {

                    ?><option <?php if ($uid1==$sql_array->sdt_userid) print("selected"); ?> value="<?=$sql_array->sdt_userid?>"><?=$sql_array->mem_username?></option><?php

        }

?>

                  </select>

                  &nbsp;&nbsp;

              <?=PRGTEMPLATES_SELECT?> :

                  <select class=inputf size="1" name="uid2">

                    <option value='' selected>- <?php echo GENERAL_CHOOSE?> -</option>

<?php

        if (@mysqli_data_seek($ticket1_res,0)) {

			while ($sql_array = mysqli_fetch_object($ticket1_res)) {

						?><option <?php if ($uid2==$sql_array->sdt_userid) print("selected"); ?> value="<?=$sql_array->sdt_userid?>"><?=$sql_array->mem_username?></option><?php

			}

		}

?>

                  </select>

                  &nbsp;&nbsp;

                    <input type='submit' class='button' value='<?=BUTTON_ADD?>' onClick="FrmQList.mode.value='add';">

            </td>

        </tr>

<?php if (mysqli_num_rows($matches) > 0) { ?>

    <tr>

      <td>

      <table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

          <tr>

            <td colspan="3" align="right" class="tdhead">

            <input type="button" value="<?=MYEMAIL_SELECT_ALL?>" class="button" onclick="selectAll('FrmQList','chkDelete[]',true);">

            <input type="submit" name="submit" value="<?=BUTTON_REMOVE?>" class="button"> &nbsp;

            </td>

          </tr>

          <?php

            while($match = mysqli_fetch_object($matches))

            {

                $zebra = ($zebra == "tdodd") ? 'tdeven' : 'tdodd';

          ?>

          <tr>

            <td class='<?=$zebra?>'>

              <a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$match->sdm_userid_1?>'><?=$match->mem1_username ?></a>&nbsp;

            </td>

            <td class='<?=$zebra?>'>

              <a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$match->sdm_userid_2?>'><?=$match->mem2_username ?></a>&nbsp;

            </td>

            <td class='<?=$zebra?>' align='center' valign='middle' width='100'>

              <input type='checkbox' name=chkDelete[] value='<?=$match->sdm_userid_1?>:<?=$match->sdm_userid_2?>'><?=BUTTON_REMOVE?>

            </td>

          </tr>

          <?php

                    }

                    ?>

          <tr align=center >

            <td colspan='3' class="tdfoot">&nbsp;</td>

          </tr>

<?php } ?>

        </table></td>

    </tr>

	  <tr >

		<td align=center colspan='3'><input name="button" type="button" class='button' onClick="window.history.back()" value="<?=BUTTON_BACK?>"></td>

	  </tr>

  </table>

<?=$skin->ShowFooter($area)?>