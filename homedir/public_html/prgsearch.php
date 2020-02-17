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

# Name:                 prgsearch.php

#

# Description:  Main search processing program

#

# Version:               7.4

#

######################################################################

include('db_connect.php');
include_once 'validation_functions.php';


header("Cache-Control: private");



include('session_handler.inc');

include('error.php');

include('functions.php');

require_once ( __INCLUDE_CLASS_PATH . '/class.RadiusAssistant.php' );

include_once __INCLUDE_CLASS_PATH."/class.Pager.php";

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

include_once($CONST_INCLUDE_ROOT."/search_conf.inc.php");

$adv = new Adverts();

save_request();

$SEARCH = formGet('SEARCH');

$P_SEARCH = formGet('P_SEARCH');

$pager->SetUrl("$CONST_LINK_ROOT/prgsearch.php");



if ($SEARCH ||$P_SEARCH || $_SESSION['s_querystring'] == "") {

    $agetext = SEARCH_AGELOCALITY ;

    $lstOrder=sanitizeData(trim($_POST['lstOrder']), 'xss_clean'); 

    $lstDatingFrom=sanitizeData(trim($_POST['lstDatingFrom']), 'xss_clean'); 

    $lstDatingTo=sanitizeData(trim($_POST['lstDatingTo']), 'xss_clean');

    $onlyOnline=sanitizeData(trim($_POST['onlyOnline']), 'xss_clean'); 

    $withPicture=sanitizeData(trim($_POST['withPicture']), 'xss_clean'); 

    if ($GEOGRAPHY_JAVASCRIPT){

        $lstCountry=array_filter((array)$_POST['lstCountry'], "my_empty");

        $lstState =array_filter((array)$_POST['lstState'], "my_empty");

        $lstCity=array_filter((array)$_POST['lstCity'], "my_empty");

    } else {

        $aCountry=array_filter((array)$_POST['lstCountry'], "my_empty");

        foreach ($aCountry as $_val) {

            $a= explode(";",$_val);

            $_Country[]=$a[0];

            $_State[]=$a[1];

        }

        $lstCountry=array_filter((array)$_Country, "my_empty");

        $lstState=array_filter((array)$_State, "my_empty");

    }

    $txtToAge=(isset($_POST['txtToAge']))? sanitizeData(trim($_POST['txtToAge']), 'xss_clean') :'99';

    $txtFromAge=(isset($_POST['txtFromAge']))? sanitizeData(trim($_POST['txtFromAge']), 'xss_clean') :'18';

    foreach ($aSearchFileds as $field) {

        if ($field['table'] == 'adverts')

            $$field['name'] = (!isset($_POST[$field['name']])) ? array($field['empty']) : $_POST[$field['name']] ;

    }

    $lstMinHeight=(isset($_POST['lstMinHeight']))? sanitizeData(trim($_POST['lstMinHeight']), 'xss_clean'):'122';

    $lstMaxHeight=(isset($_POST['lstMaxHeight']))? sanitizeData(trim($_POST['lstMaxHeight']), 'xss_clean') :'230';

    $txtHandle=sanitizeData(trim($_POST['txtHandle']), 'xss_clean') ;

    if ($CONST_ZIPCODES=='Y') {

        $txtZipcode=sanitizeData(trim($_POST['txtZipcode']), 'xss_clean');

        $txtMiles=sanitizeData(trim($_POST['txtMiles']), 'xss_clean');

        $lstUnit=sanitizeData(trim($_POST['lstUnit']), 'xss_clean');

    }

    $lstResultAs =sanitizeData(trim($_POST['lstResultAs']), 'xss_clean');  

} else {

    $querystring=FormGet('s_querystring');
    
    //$TOTAL = FormGet('STOTAL');

    $TOTAL = 9;

    $sreset = FormGet('SRESET');

    $lstResultAs = FormGet('RESULTAS');

}

if (isset($_POST['chkSearch'])) $chkSearch=sanitizeData(trim($_POST['chkSearch']), 'xss_clean');   

# retrieve the template

$area = 'member';

if (isset( $chkSearch )) {

        $checkqry="SELECT COUNT(sea_userid) FROM search WHERE sea_userid = $Sess_UserId";

        $result=mysqli_query($globalMysqlConn, $checkqry);

        # update the search table

        switch ($lstDatingFrom) {

            case "M":

                    if ($lstDatingTo =='M') $lstType="Men seeking men";

                    elseif ($lstDatingTo =='F') $lstType="Women seeking men";

                    elseif ($lstDatingTo =='C') $lstType="Couples seeking men";

                    break;

            case "F":

                    if ($lstDatingTo =='M') $lstType="Men seeking women";

                    elseif ($lstDatingTo =='F') $lstType="Women seeking women";

                    elseif ($lstDatingTo =='C') $lstType="Couples seeking women";

                    break;

        }

        if (mysqli_num_rows($result) > 0) {

                $globalMysqlConn->query("DELETE FROM sarray where sar_userid= $Sess_UserId");

                $globalMysqlConn->query("

                        UPDATE search

                            SET

                            sea_minheight='$lstMinHeight',

                            sea_maxheight='$lstMaxHeight',

                            sea_seeksex='$lstType',

                            sea_agemin='$txtFromAge',

                            sea_agemax='$txtToAge'

                        WHERE sea_userid= $Sess_UserId");

        } else {

                $sql_array2 = $globalMysqlConn->get_row("SELECT par_lastcupidrun, par_lastrun FROM params");

                $checkqry="INSERT INTO search (sea_userid,sea_minheight,sea_maxheight, sea_seeksex,sea_agemin,sea_agemax, sea_date)

                         values('$Sess_UserId','$lstMinHeight','$lstMaxHeight','$lstType','$txtFromAge','$txtToAge','$sql_array2->par_lastrun')";

                $result=$globalMysqlConn->query($checkqry);

        }

        # update the sarray table

    foreach ($aSearchFileds as $field) {

        $data = $$field['name'];

        if (!empty($data) && $data[0] != $field['empty']) {

            foreach ($data as $value) {

                $checkqry="INSERT INTO sarray (sar_userid, sar_type, sar_value)

                            VALUES ('$Sess_UserId', '".$field['name']."', '$value')";

                $result=mysqli_query($globalMysqlConn, $checkqry);

            }

        } else {

            $checkqry="INSERT INTO sarray (sar_userid, sar_type, sar_value)

                        VALUES ('$Sess_UserId', '".$field['name']."', '".$field['emptyset']."')";

            $result=mysqli_query($globalMysqlConn, $checkqry);

        }

    }

}

//dump($_REQUEST);

# Return the search results

if ($SEARCH && !$txtHandle) {

        # construct the query

        ####################

        $lstOrder = strtolower($lstOrder);

        switch ($lstOrder) {

                case "premium members":

                        $qryorder="ORDER BY if(adv_expiredate>now(), adv_expiredate, adv_createdate) desc";

                        break;

                case "latest First":

                        $qryorder="ORDER BY adv_createdate desc";

                        break;

                case "since last visit":

                        $qryorder="AND adv_createdate >= '$Sess_LastVisit' ORDER BY adv_createdate desc";

                        break;

                case "order by age":

                        $qryorder="ORDER BY adv_dob desc";

                        break;

                default:

                        $qryorder="ORDER BY adv_createdate desc";

                        break;

        }

        switch ($lstDatingFrom) {

                case "M":

                        $qrygender="AND adv_sex='$lstDatingTo' AND adv_seekmen='Y'";

                        break;

                case "F":

                        $qrygender="AND adv_sex='$lstDatingTo' AND adv_seekwmn='Y'";

                        break;

        }

         /*

         * Generate query for fields from aSearchFileds exept geo field

         */

        $qrywhere = '';

        foreach ($aSearchFileds as $field) {

            $data = $$field['name'];

            if ($field['table'] == 'adverts'){

                if (!empty($data) && $data[0] !=  $field['empty']) {

                    $count=0;

                    foreach ($data as $value) {

                            if ($count==0) {

                                    $qrywhere.=" AND ('".$field['field']."'='$value' ";

                            } else {

                                    $qrywhere.=" OR '".$field['field']."'='$value' ";

                            }$count++;

                    }

                    $qrywhere.=")";

                }

            }

        }

        ####################

        # START COUNTRY CODE

        if ($CONST_ZIPCODES=='Y' && trim($txtZipcode) !="") {

            $zip_row = $globalMysqlConn->get_row("SELECT zip_latitude,zip_longitude FROM zipcodes WHERE zip_zipcode = '$txtZipcode' LIMIT 1");

            if (!$zip_row) {

                    $error_message=PRGADVERTISE_TEXT4;

                    error_page($error_message,GENERAL_USER_ERROR);

            }

            //ZIP code exists

            $zcdRadius = new RadiusAssistant($zip_row->zip_latitude,$zip_row->zip_longitude,$txtMiles,$lstUnit);

            $minLat = $zcdRadius->MinLatitude();

            $maxLat = $zcdRadius->MaxLatitude();

            $minLong = $zcdRadius->MinLongitude();

            $maxLong = $zcdRadius->MaxLongitude();

            //mySQL Query

            $sql = "SELECT DISTINCT zip_zipcode FROM zipcodes

                    WHERE zip_latitude >= $minLat

                    AND zip_latitude <= $maxLat

                    AND zip_longitude >= $minLong

                    AND zip_longitude <= $maxLong";

            $zip_col = $globalMysqlConn->get_col($sql);

            $zip_col[] = -1;

            $qrygeo=" AND adv_zipcode IN ('". join("', '",$zip_col)."') ";

        } else {

            $qrygeo = "";

            if (count($lstCity)>0 && $lstCity[0] != "0") {

                foreach ($lstCity as $value) {

                    $res = mysqli_query($globalMysqlConn, "SELECT * FROM geo_city WHERE gct_cityid=$value");

                    $row = mysqli_fetch_object($res);

                    if ($row->gct_countryid != 0 ) $lstCountry = del_from_array($lstCountry,$row->gct_countryid);

                    if ($row->gct_stateid != 0 ) $lstState = del_from_array($lstState,$row->gct_stateid);

                    $qrygeo=$qrygeo." OR ADV_CITYID='$value'";

                }

            }

            if (count($lstState)>0 && $lstState[0] != "0") {

                foreach ($lstState as $value) {

                    $res = mysqli_query($globalMysqlConn, "SELECT * FROM geo_state WHERE gst_stateid=$value");

                    $row = mysqli_fetch_object($res);

                    if ($row->gst_countryid != 0 ) $lstCountry = del_from_array($lstCountry,$row->gst_countryid);

                    $qrygeo=$qrygeo." OR ADV_STATEID='$value'";

                }

            }

            if (count($lstCountry)>0) {

                if($lstCountry[0] != "0")

                    foreach ($lstCountry as $value)

                        $qrygeo=$qrygeo." OR ADV_COUNTRYID='$value'";

                else

                    $qrygeo = "";

            }

            if ($qrygeo != "") $qrygeo =" AND (0 $qrygeo) ";

        }



        # END OF COUNTRY CODE

        $qryAge=" AND ((YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5))) BETWEEN $txtFromAge AND $txtToAge ";

        # begin on line code

        ####################

        if (!empty($onlyOnline) && $onlyOnline != "") {

                $qryOnline=" AND unix_timestamp(mem_timeout) > unix_timestamp(NOW())-".(ONLINE_TIMEOUT_PERIOD*60);

        } else {$qryOnline="";}

        # end online code

        # begin with photos code

        ####################

        if (!empty($withPicture) && $withPicture != "") {

                $qryphotos=" INNER JOIN pictures a ON (adv_userid=a.pic_userid AND a.pic_default='Y') ";

        } else {$qryphotos="";}

        # end photos code

        $qryheight=" AND (adv_height >= $lstMinHeight AND adv_height <= $lstMaxHeight OR adv_height = 'Not stated')";

        if($lstResultAs == 'gallery')

            $gallery_from = "INNER JOIN pictures b ON (adv_userid=b.pic_userid AND b.pic_default='Y')";

        else

            $gallery_from = '';

        $countquery="SELECT COUNT(adv_userid)

                FROM adverts

                    LEFT JOIN members ON (adv_userid=mem_userid)

               $gallery_from

               $qryphotos

                WHERE adv_paused='N' $qrygender $qrygeo $qrywhere $qryAge $qryOnline $qryheight AND adv_approved=1 $qryorder";

        $limit = $pager->GetLimit($globalMysqlConn->query($countquery));

        $resquery="SELECT *, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,

                    unix_timestamp(mem_timeout) AS session_active, mem_timeout

                FROM adverts

                    LEFT JOIN members ON (adv_userid=mem_userid)

                    LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)

                    LEFT JOIN geo_state ON (adv_stateid = gst_stateid)

                    LEFT JOIN geo_city ON (adv_cityid = gct_cityid)

                    $gallery_from

                    $qryphotos

                WHERE adv_paused='N' $qrygender $qrygeo $qrywhere  $qryAge  $qryOnline $qryheight AND adv_approved=1 $qryorder";

        $_SESSION['s_querystring'] = $resquery;



} else {

    if ($txtHandle) {

		$txtHandle=mysqli_real_escape_string($globalMysqlConn,$txtHandle);

        $sql_condition = "(adv_paused='N' AND adv_username LIKE '$txtHandle%') AND adv_approved=1";

        $countquery = "SELECT COUNT(*) FROM adverts WHERE $sql_condition";

        $limit = $pager->GetLimit($globalMysqlConn->query($countquery));

        $resquery="

            SELECT *,

                    (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age,

                    unix_timestamp(mem_timeout) AS session_active, mem_timeout

            FROM adverts

            LEFT JOIN members ON (adv_userid=mem_userid)

            LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)

            LEFT JOIN geo_city ON (adv_cityid = gct_cityid)

            LEFT JOIN geo_state ON (adv_stateid = gst_stateid)

            WHERE $sql_condition ";

        $_SESSION['s_querystring'] = $resquery;

    } else {

        $resquery=str_replace ('\\', '', $querystring);

        $limit = $pager->GetLimit($TOTAL);

    }

}
//echo $resquery."!!!!!";
if (empty($resquery)) {

    header("Location: $CONST_LINK_ROOT/search.php");

    exit;

}

//echo $resquery;

$search_result = mysqli_query($globalMysqlConn, $resquery.$limit);

//$globalMysqlConn->debug();

$_SESSION['SRESET']="0";

$_SESSION['SHOWNUM']=$pagesize;

$_SESSION['STOTAL']=$pager->TOTAL;

$_SESSION['RESULTAS']=$lstResultAs;

?>

<?=$skin->ShowHeader($area)?>

<table width="<?php print("$CONST_TABLE_WIDTH"); ?>" align="<?php print("$CONST_TABLE_ALIGN"); ?>" border="0" cellspacing="<?php print("$CONST_TABLE_CELLSPACING"); ?>" cellpadding="<?php print("$CONST_TABLE_CELLPADDING"); ?>">

  <tr>

    <td align="right"><?php require_once("$CONST_INCLUDE_ROOT/user_status.inc.php");?>

    </td>

  </tr>

  <tr>

    <td class="pageheader"><?php echo SEARCH_SECTION_NAME ?></td>

  </tr>

  <tr>

    <td><table width="100%"  border="0" cellpadding="<?php print("$CONST_SUBTABLE_CELLPADDING"); ?>" cellspacing="<?php print("$CONST_SUBTABLE_CELLSPACING"); ?>">

        <tr >

          <td  align="right"><? include("search_pager.php");?>

          </td>

        </tr>

        <tr>

          <td><?php

# insert the line code here

$curr_row_num = 0;

// $row_count = mysqli_num_rows($search_result);

foreach($search_result as $sql_array) {

$adv->InitByObject($sql_array);

$adv->SetImage('medium');

$sql_array = $adv;

$curr_row_num++;

# start print

if($lstResultAs == 'gallery') {

print("<div class=\"gallery\">

 			<a href='$CONST_LINK_ROOT/prgretuser.php?userid=$sql_array->adv_userid' class=\"imageframe\"><img border='0' src='$CONST_LINK_ROOT{$sql_array->adv_picture->Path}?".time()."' width={$sql_array->adv_picture->w}></a><br />

			<a href=' $CONST_LINK_ROOT/prgretuser.php?userid=$sql_array->adv_userid'>$sql_array->adv_username</a><br />

 			 $online

		</div>");

        } else {

            include("user_list.inc.php");

    }        }

?>

          </td>

        </tr>

        <tr>

          <td  align="right"><? include "search_pager.php"?>

          </td>

        </tr>

        <tr>

          <td ><center>

              <input type="button" name="Submit" value="<?php echo SEARCH_NEW ?>" class="button" onClick="document.location.href='<?php echo $CONST_LINK_ROOT?>/search.php'">

            </center></td>

        </tr>

        </form>

        

      </table></td>

  </tr>

</table>
<script>
    function submitearchform() {
        document.getElementById("shownum").value = document.getElementById("lstShownum").value;
        document.getElementById("search_pager_myForm").submit();
    }
</script>
<?=$skin->ShowFooter($area)?>

