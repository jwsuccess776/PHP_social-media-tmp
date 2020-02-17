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

# Name:                 my_matches.php

#

# Description:  Main search processing program

#

# Version:                7.2

#

######################################################################

include('db_connect.php');

include('session_handler.inc');

include('error.php');

require_once ( __INCLUDE_CLASS_PATH . '/class.RadiusAssistant.php' );

save_request();



include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

$adv = new Adverts();

$pager->SetUrl("$CONST_LINK_ROOT/my_matches.php");



# retrieve the template

$area = 'member';



# Return the search results

$m_result = array();

$result=mysqli_query($globalMysqlConn,"SELECT * FROM mymatch WHERE mym_userid=$Sess_UserId") or die(mysqli_error());

if (mysqli_num_rows($result) > 0) {

    $sql_mymatch=mysqli_fetch_object($result);

    $txtFromAge=$sql_mymatch->mym_agemin;

    $txtToAge=$sql_mymatch->mym_agemax;



    $tempYear=date("Y"); $tempMonth=date("n"); $tempDay=date("j");

    $upperYear=($tempYear-$txtToAge)-1;

    $upperDate=$upperYear."-".$tempMonth."-".$tempDay;

    $lowerYear=($tempYear-$txtFromAge);

    $lowerDate=$lowerYear."-".$tempMonth."-".$tempDay;



    $conditions=" AND ADV_DOB BETWEEN '$upperDate' AND '$lowerDate'";

    if ($sql_mymatch->mym_gender !='- Any -') $conditions.=" AND ADV_SEX = '".$db->escape($sql_mymatch->mym_gender)."'";

    if ($sql_mymatch->mym_bodytype !='- Any -') $conditions.=" AND ADV_BODYTYPE = '".$db->escape($sql_mymatch->mym_bodytype)."'";

    if ($sql_mymatch->mym_relationship !='- Any -') $conditions.=" AND ADV_SEEKING = '".$db->escape($sql_mymatch->mym_relationship)."'";

    if ($sql_mymatch->mym_smoker !='- Any -') $conditions.=" AND ADV_SMOKER = '".$db->escape($sql_mymatch->mym_smoker)."'";



    $lstMinHeight=($sql_mymatch->mym_minheight == 'Not stated')?$sql_mymatch->mym_minheight:'121';

    $lstMaxHeight=($sql_mymatch->mym_maxheight == 'Not stated')?$sql_mymatch->mym_maxheight:'229';

    $conditions.=" AND (adv_height >= $lstMinHeight AND adv_height <= $lstMaxHeight OR adv_height = 'Not stated')";



    $query="SELECT COUNT(adv_userid)

            FROM adverts LEFT JOIN members ON (adv_userid=mem_userid)

            WHERE (adv_approved=1) AND adv_paused='N' ".$conditions;

    $limit = $pager->GetLimit($db->get_var($query));



    $query="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, unix_timestamp(mem_timeout) AS session_active,

                mem_timeout

            FROM adverts

                LEFT JOIN members

                    ON (adv_userid=mem_userid)

                LEFT JOIN geo_country

                    ON (adv_countryid = gcn_countryid)

                LEFT JOIN geo_state

                    ON (adv_stateid = gst_stateid)

                LEFT JOIN geo_city

                    ON (adv_cityid = gct_cityid)

            WHERE (adv_approved=1)

                $conditions

                AND adv_paused='N'

            ORDER BY adv_createdate desc

            ";

    $m_result=$db->get_results($query.$limit);

}

?>

<?=$skin->ShowHeader($area)?>



<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo HOME_MYMATCH ?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>">

        <tr align=right>

          <td ><? include("search_pager.php");?></td>

        </tr>

        <tr>

          <td><?php

# insert the line code here

$curr_row_num = 0;

$row_count = count($m_result);

if ($row_count > 0) {

	foreach ($m_result as $sql_array) {

		$adv->InitByObject($sql_array);

		$adv->SetImage('small');

		$sql_array = $adv;

		include("user_list.inc.php");

	}

 } else {

	echo "<p>".NOMYMATCHES."</p>";

}

?></td>

        </tr>

        <tr align=right>

          <td ><? include("search_pager.php");?>

          </td>

        </tr>

        </form>

        

      </table></td>

  </tr>

</table>

<?=$skin->ShowFooter($area)?>

