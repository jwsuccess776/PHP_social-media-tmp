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
# Name:                 whoson.php
#
# Description:  Main search processing program
#
# Version:               7.2
#
######################################################################

include('db_connect.php');
include('session_handler.inc');
include('error.php');
include('functions.php');
require_once ( __INCLUDE_CLASS_PATH . '/class.RadiusAssistant.php' );
save_request();

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();

# retrieve the template
$area = 'member';

if (formGet('gender')) {
	$gender = formGet('gender');
	$whos_cond = formGet('whos_cond');
	$_SESSION['wh_gender']=$gender;
	$_SESSION['wh_cond']=$whos_cond;
} elseif(isset($_SESSION['wh_gender'])) {
	$gender=$_SESSION['wh_gender'];
	$whos_cond=$_SESSION['wh_cond'];
}

$gender_qry = ($gender) ? " AND adv_sex = '$gender' " : "";
$condition = "AND unix_timestamp(mem_timeout) > unix_timestamp(NOW()) - ".ONLINE_TIMEOUT_PERIOD*60;
$qryphotos = ($whos_cond == "pic") ? " INNER JOIN pictures ON (adv_userid=pic_userid AND pic_default='Y') " : "";

$query="SELECT COUNT(adv_userid)
        FROM adverts
        $qryphotos
        LEFT JOIN members ON (adv_userid=mem_userid)
        WHERE (adv_approved=1)
        AND adv_paused='N' ".$condition.$gender_qry."

        ORDER BY adv_createdate desc";
$limit = $pager->GetLimit($db->get_var($query));
$pager->SetUrl("$CONST_LINK_ROOT/whoson.php");

$query="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,
                unix_timestamp(mem_timeout) AS session_active, mem_timeout
    FROM adverts
        $qryphotos
		LEFT JOIN members
            ON (adv_userid=mem_userid)
        LEFT JOIN geo_country
            ON (adv_countryid = gcn_countryid)
        LEFT JOIN geo_state
            ON (adv_stateid = gst_stateid)
        LEFT JOIN geo_city
            ON (adv_cityid = gct_cityid)
    WHERE (adv_approved=1)
        ".$condition.$gender_qry."
        AND adv_paused='N'
    ORDER BY adv_createdate desc";
    $result=$db->get_results($query.$limit);
?>
<?=$skin->ShowHeader($area)?>
<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>
    </td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo WHOSON_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">
        <form method="post" action="<?php echo $CONST_LINK_ROOT ?>/whoson.php">
          <tr >
            <td align="left" class="tdhead"><?=WHOSON_CONDITION;?>
              <select name="gender" class="input">
                <option value=""><?=WHOSON_A;?></option>
                <option value="M" <? if ($gender=="M") echo " SELECTED"; ?>><?=WHOSON_M;?></option>
                <option value="F" <? if ($gender=="F") echo " SELECTED"; ?>><?=WHOSON_W;?></option>
              </select>
              <input type="checkbox" name="whos_cond" <? if ($whos_cond=='pic')  echo " CHECKED"; ?> value="pic">
              (
              <?=WHOSON_PIC;?>
              )
              <input type='submit' value='<?=WHOSON_VIEW;?>' class='button'>
              <input type='button' value='<?=WHOSON_VIEW_ALL;?>' class='button' onclick="window.location='<?php echo $CONST_LINK_ROOT ?>/whoson.php'">
            </td>
          </tr>
        </form>
        <tr >
          <td  align="right"><? include("search_pager.php"); ?>
          </td>
        </tr>
        <tr>
          <td><?php
# insert the line code here
foreach ($result as $sql_array) {

$adv->InitByObject($sql_array);
$adv->SetImage('small');
$sql_array = $adv;
include("user_list.inc.php");
}
?>
          </td>
        </tr>
        <tr>
          <td  align=right><? include("search_pager.php"); ?>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<?=$skin->ShowFooter($area)?>
