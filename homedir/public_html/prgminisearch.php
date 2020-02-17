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
# Name: 		prgminisearch.php
#
# Description:  Basic search for non-members
#
# # Version:      8.0
#
######################################################################

include('db_connect.php');
include('functions.php');
include_once 'validation_functions.php';
include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
$adv = new Adverts();
if (!formGet('SHOWNUM')) $pager->PAGESIZE = 16;
#save_request();
# Return the search results

$lstDatingCountry =(($lstDatingCountry = formGet('lstDatingCountry')) > 0 ) ? $lstDatingCountry : 0;

$lstDatingCountry =sanitizeData($lstDatingCountry , 'xss_clean');
$lstDatingFrom=sanitizeData(formGet('lstDatingFrom'), 'xss_clean');
$lstDatingTo=sanitizeData(formGet('lstDatingTo'), 'xss_clean');

$_SESSION['lstDatingFrom'] = $lstDatingFrom;
$_SESSION['lstDatingTo'] = $lstDatingTo;
$_SESSION['lstDatingCountry'] = $lstDatingCountry;

# retrieve the template
$area = 'guest';

# construct the query
switch ($lstDatingFrom) {
    case "M":
        $qrygender=" AND adv_sex='$lstDatingTo' AND adv_seekmen='Y'";
        break;
    case "F":
        $qrygender=" AND adv_sex='$lstDatingTo' AND adv_seekwmn='Y'";
        break;
}
$qrycountry = ($lstDatingCountry != '0') ? " AND ADV_COUNTRYID='$lstDatingCountry' " : "";

$query = "SELECT COUNT(adv_userid)
			FROM adverts
			WHERE (adv_paused='N' $qrygender $qrycountry)
			AND adv_approved=1";
$limit = $pager->GetLimit($db->get_var($query));
$pager->SetUrl("$CONST_LINK_ROOT/prgminisearch.php");

$query="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,
unix_timestamp(mem_timeout) AS session_active, mem_timeout
        FROM adverts
            LEFT JOIN members
                ON (adv_userid=mem_userid)
            LEFT JOIN geo_country
                ON (adv_countryid = gcn_countryid)
            LEFT JOIN geo_state
                ON (adv_stateid = gst_stateid)
            LEFT JOIN geo_city
                ON (adv_cityid = gct_cityid)
        WHERE (adv_paused='N' $qrygender $qrycountry)
        AND adv_approved=1
        ORDER BY if(adv_expiredate>now(), adv_expiredate, adv_createdate) desc ";
//echo $query;
$result=$db->get_results($query.$limit);

?>
<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">
  <tr>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td class="pageheader"><?php echo SEARCH_SECTION_NAME ?></td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">
        <tr>
          <td align="right"><? include "search_pager.php"?>
          </td>
        </tr>
        <tr>
          <td><?php
// insert the line code here
foreach ($result as $sql_array) {
$adv->InitByObject($sql_array);
$adv->SetImage('medium');
$sql_array = $adv;
include("user_list.inc.php");
}?></td>
        </tr>
        <tr>
          <td align="right"><? include "search_pager.php"?>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<? //mysqli_close($link); ?>
<?=$skin->ShowFooter($area)?>
