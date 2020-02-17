<?php
/*****************************************************
* � copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         functions.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################
include_once('db_connect.php');

if (! defined('CONST_FUNCTIONS_MODULE') ) {
define('CONST_FUNCTIONS_MODULE',1);

function createFCKEditor ( $pathFromRoot, $nameVar, $value, $typeToolbar="", $width = null , $height = null ) {
    global $__SITE_REL;
    require_once ( CONST_INCLUDE_ROOT.'/FCKeditor/fckeditor.php' );
//    require_once ( __BASE_PATH.'/FCKeditor/configPaths4FCK.php' );
    $fck = new FCKeditor($nameVar) ;
    $fck -> BasePath = CONST_LINK_ROOT."/FCKeditor/" ;
    $fck -> Value = $value;
    $fck -> Config['AutoDetectLanguage']    = false ;
    $fck -> Config['DefaultLanguage']       = 'en' ;
    $fck -> Config['SkinPath'] = CONST_LINK_ROOT."/FCKeditor/".'editor/skins/silver/' ;
    $fck -> Config['CustomFullPathForUpload'] = CONST_INCLUDE_ROOT.$pathFromRoot."/" ;
    $fck -> Config['CustomRelPathForUpload'] = $pathFromRoot."/" ;
    $fck -> Config['CustomFromRootPathForUpload'] = CONST_LINK_ROOT."/".$pathFromRoot."/" ;
    $fck -> Config['CustomConfigurationsPath'] = CONST_LINK_ROOT.'/FCKeditor/fck_custom_config.js' ;

    if (empty($typeToolbar))
        $fck -> ToolbarSet = 'Default';
    else
        $fck -> ToolbarSet = $typeToolbar;
    if ( $width != null ) {
        $fck -> Width = $width ;
    }
    if ( $height != null ) {
        $fck -> Height = $height ;
    }
    return $fck;
}

function pager($PAGE,$TOTAL,$SHOWNUM){

    $LISTSIZE = 5;
    if ($PAGE<1) $PAGE = 1;
    if ($TOTAL < 1) $PAGE=1;

    $res['LASTPAGE'] = ceil($TOTAL/$SHOWNUM);
    $PAGE = ($res['LASTPAGE'] < $PAGE) ? $res['LASTPAGE'] : $PAGE;
    $res['PAGE'] = $PAGE;
    $res['FIRSTPAGE'] = 1;
    $res['PREVPAGE'] = ($PAGE >1) ? $PAGE-1 : 1;
    $res['NEXTPAGE'] = ($PAGE < $res['LASTPAGE']) ? $PAGE+1 : $res['LASTPAGE'];

    $res['STARTPOS'] = $PAGE*$SHOWNUM - $SHOWNUM;

    if ($res['STARTPOS']+$SHOWNUM > $TOTAL) {
        $res['ENDPOS'] = $TOTAL;
    } else {
        $res['ENDPOS'] = $res['STARTPOS'] + $SHOWNUM;
    }
    $res['DISPLAYPOS']=($TOTAL) ? $res['STARTPOS']+1 : 0;
    $res['TOTAL'] = $TOTAL;
    $res['LIST'] = array();
    $i = 1;
    while ($i < ($res['PAGE']+$LISTSIZE) && $i < $res['LASTPAGE']) $res['LIST'][] = $i++;
    return $res;
}
function show_dropdown($table,$id,$label,$val){
    global $globalMysqlConn;
    $query = "SELECT $id,$label FROM $table";
//echo $query;
    $res = mysqli_query($globalMysqlConn, $query);
    while ($row = mysqli_fetch_assoc($res)){
//print_r($row);
        $selected = ($row[$id] == $val) ? "SELECTED" : "";
        echo "<option value='$row[$id]' $selected>$row[$label]</option>";
    }
}

function one_wordwrap($string,$width){
  $s=explode(" ", $string);
  foreach ($s as $k=>$v) {
    $cnt=strlen($v);
    if($cnt>$width) $v=wordwrap($v, $width, " ", true);
      $new_string.="$v ";
  }
  return $new_string;
} 

function show_gender($gender,$type,$name='gender'){
    switch ($type) {
        case 'select':
?>
            <option value="0">- <?=SEX?> -</option>
            <option value="M" <? if($gender == "M") echo " SELECTED"; ?>><?=SEX_MALE?></option>
            <option value="F" <? if($gender == "F") echo " SELECTED"; ?>><?=SEX_FEMALE?></option>
<?
            break;
        case 'radio' :
            if ($gender == '') $gender = 'M';
?>
            <input type=radio name="<?=$name?>" value="M" <? if($gender == "M") echo " CHECKED"; ?>><?=SEX_MALE?>
            <input type=radio name="<?=$name?>" value="F" <? if($gender == "F") echo " CHECKED"; ?>><?=SEX_FEMALE?>
<?
            break;
    }
}

function show_payment_modes($mode){
    echo $mode;
?>
    <option value="common" <? if($mode == "common") echo " SELECTED"; ?>>Common</option>
    <option value="onetime" <? if($mode == "onetime") echo " SELECTED"; ?>>One-time only</option>
    <option value="recurring" <? if($mode == "recurring") echo " SELECTED"; ?>>Recurring only</option>
<?php
}
function my_empty($var){
    return ($var != -1 && $var != '');
}
function del_from_array($arr,$var){
    $temp = array();
    foreach ($arr as $el) {
        if ($el != $var) $temp[] = $el;
    }
    return $temp;
}

function arrange_location($sql_record)
{
    if($sql_record->gcn_countryid == 0)
        $location = GENERAL_NOT_STATE;
    else
    {
        $location = $sql_record->gcn_name;
        if($sql_record->gst_stateid != 0)
            $location = "$sql_record->gst_name, $location";
        if($sql_record->gct_cityid != 0)
            $location = "$sql_record->gct_name, $location";
    }
    return $location;
}

// This function returns list of payment systems which exists in system
function get_payments_list($pay_service='')
{
    global $globalMysqlConn;
    global $CONST_INCLUDE_ROOT,$CONST_MAIL;
    $files = array();
    if ($dir = opendir($CONST_INCLUDE_ROOT.'/payments')) {
        while (($file = readdir($dir)) !== false) {
            if (!preg_match('/^\./',$file)) $files[] = $file;
        }
        closedir($dir);
        $query = "SELECT * FROM payment_systems";
        $res = mysqli_query($globalMysqlConn, $query);
        while ($row = mysqli_fetch_object($res)) {
            $row->set = 'no';
            if (in_array($row->ps_prefix."_notify.php",$files)
                && in_array($row->ps_prefix."_form.php",$files)) $row->set = 'yes';
            $result[] = $row;
        }
    } else {
        echo "ERROR : Payment system doesn't work. Contact with $CONST_MAIL about this problem";exit;
    }
    return $result;
}
//This function returns list of active payment systems which allow for this service and this amount
function get_allow_payments($pay_service, $amount = 0)
{
    global $globalMysqlConn;
    $result = array();
//get service params
    $query = "SELECT *
                FROM payment_service_params
                WHERE psp_service = '$pay_service'";
    $res = mysqli_query($globalMysqlConn, $query);
    $service = mysqli_fetch_object($res);
    //print_r($service);
// get list of installed payment systems
    $payments = get_payments_list($pay_service);

    foreach ($payments as $payment) {

//if payment system is installed and active
        if ($payment->ps_active == 'yes' && $payment->set == 'yes'){
            $query = "  SELECT a.ps_id
                        FROM payment_systems a
                            INNER JOIN payment_services b
                                ON (a.ps_id = b.ps_id)
                        WHERE pay_service = '$pay_service'
                            AND a.ps_id = '".$payment->ps_id."'
                            AND ps_".$service->psp_type." = 'yes' ";
            if ($amount >0) $query .= " AND (ps_max_amount >= $amount)";
            //echo $query;
            $res = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
// if payment system can work with this amount and payment type (onetime/requrring)
            if (mysqli_num_rows($res)) $result[] = $payment;
        }
    }
    return $result;
}

//This function returns list of params for current service and payment system
//if $mode == "user" it returns params only for current type ('onetime','recurring') of service
//in other case for all types
function get_payment_params ($system,$pay_service,$mode='user')
{
    global $globalMysqlConn;
    if ($mode=='user') {
        $sql_admin = "INNER JOIN payment_service_params c
                    ON (pay_service=psp_service AND (c.psp_type = b.psp_type OR b.psp_type = 'common'))";

    }
    $result = array();
    $query = "  SELECT *
                FROM payment_systems a
                    INNER JOIN payment_params b
                        ON (a.ps_id=b.ps_id)
                    $sql_admin
                WHERE ps_prefix = '$system'
                AND pay_service = '$pay_service'
                ORDER BY b.psp_type DESC, psp_id ASC";
//echo $query;
    $res = mysqli_query($globalMysqlConn, $query ) or die (mysqli_error());
    while ($row = mysqli_fetch_object($res)) {
        $result[] = $row;
    }
    return $result;
}

function get_payment_param_by_name($system,$pay_service,$name)
{
    global $globalMysqlConn;
    $query = "  SELECT *
                FROM payment_systems a
                    INNER JOIN payment_params b
                        ON (a.ps_id=b.ps_id)
                WHERE ps_prefix = '$system'
                AND pay_service = '$pay_service'
                AND psp_name = '$name'";
//echo $query;
    $res = mysqli_query($globalMysqlConn, $query ) or die (mysqli_error());
    $result =  mysqli_fetch_object($res);
    return $result->psp_value;
}

function save_payment_details($pay_paymentid, $pay_transid, $pay_transstatus,
                              $pay_transtime, $pay_name, $pay_email,
                              $pay_postcode, $pay_country, $pay_address,
                              $pay_telephone, $pay_scountry, $pay_system)
{
    global $globalMysqlConn;
    global $_POST;
    $query = "SELECT *
              FROM payments a
                  INNER JOIN payment_service_params b
                      ON (psp_service = pay_service)
              WHERE pay_paymentid = '$pay_paymentid'";
    $result = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
    $payment = mysqli_fetch_object($result);
    if (mysqli_num_rows($result) > 0) {
        if ($payment->pay_transstatus != 'Completed') {
            $query="UPDATE payments SET
                        pay_transid     =   '$pay_transid',
                        pay_transstatus =   '$pay_transstatus',
                        pay_transtime   =   '$pay_transtime',
                        pay_date        =   now(),
                        pay_name        =   '$pay_name',
                        pay_email       =   '$pay_email',
                        pay_postcode    =   '$pay_postcode',
                        pay_country     =   '$pay_country',
                        pay_address     =   '$pay_address',
                        pay_telephone   =   '$pay_telephone',
                        pay_scountry    =   '$pay_scountry',
                        pay_notify_log  =   '".mysqli_real_escape_string($globalMysqlConn,var_export($_REQUEST, true))."',
                        pay_system      =   '$pay_system'
                    WHERE pay_paymentid = '$pay_paymentid'";
            mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
        } else {
            $query="INSERT INTO payments SET
                        pay_transid     =   '$pay_transid',
                        pay_transstatus =   '$pay_transstatus',
                        pay_transtime   =   '$pay_transtime',
                        pay_date        =   now(),
                        pay_name        =   '$pay_name',
                        pay_email       =   '$pay_email',
                        pay_postcode    =   '$pay_postcode',
                        pay_country     =   '$pay_country',
                        pay_address     =   '$pay_address',
                        pay_telephone   =   '$pay_telephone',
                        pay_scountry    =   '$pay_scountry',
                        pay_system      =   '$pay_system',
                        pay_userid      =   '".$payment->pay_userid."',
                        pay_samount     =   '".$payment->pay_samount."',
                        pay_service     =   '".$payment->pay_service."',
                        pay_message     =   '".$payment->pay_message."',
                        pay_notify_log  =   '".mysqli_real_escape_string($globalMysqlConn,var_export($_REQUEST, true))."',
                        pay_params      =   '".$payment->pay_params."'";

            mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
            $pay_paymentid = mysqli_insert_id();
        }
        $query = "SELECT *
                  FROM payments a
                      INNER JOIN payment_service_params b
                          ON (psp_service = pay_service)
                  WHERE pay_paymentid = '$pay_paymentid'";
        $result = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
        return mysqli_fetch_object($result);
    } else {
        return false;
    }
}

function form_get($value){
    global $_POST,$_GET;
    if (isset($_POST[$value])) {
        $get_value=$_POST[$value];
    }
    elseif (isset($_GET[$value])) {
        $get_value=$_GET[$value];
    }
    else {
        $get_value="";
    }
    //$get_value = (get_magic_quotes_gpc()==0 && set_magic_quotes_runtime()==0) ? addslashes($get_value) : $get_value;
    return $get_value;
}

function transl_value($db_value, $type, $lang_id="") {
    global $_SESSION;
   global $globalMysqlConn;
    if (empty($lang_id)) {
        $lang_id = $_SESSION['lang_id'];
    }
    if(!is_null($db_value)){
    $db_value=mysqli_real_escape_string($globalMysqlConn,$db_value);
    }
    $query = "SELECT vv.* FROM vlistbox_values vv, vlistbox vl
                WHERE vv.lst_recid = vl.lst_recid
                AND vl.lst_value = '".$db_value."'
                AND lst_type = '".$type."' AND vv.lang_id = '".$lang_id."'";
//    echo $query;
    //$result = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
    //$cur_record = mysqli_fetch_object($result);
    $cur_record=  mysqli_query($globalMysqlConn,$query);
    if (empty($cur_record)) {
        $lang_value = $db_value;
    }
    else {
        $lang_value = $cur_record->lst_value;
    }
    return  stripslashes($lang_value);
}

function transl_checks($db_value, $type, $lang_id="") {
    global $_SESSION;
    if (empty($lang_id)) {
        $lang_id = $_SESSION['lang_id'];
    }
    
	foreach ($db_value as $value) {
		$query = "SELECT vv.* FROM vlistbox_values vv, vlistbox vl
					WHERE vv.lst_recid = vl.lst_recid
					AND vl.lst_value = '".mysqli_real_escape_string($globalMysqlConn,$value)."'
					AND lst_type = '".$type."' AND vv.lang_id = '".$lang_id."'";
	    $result = mysqli_query($globalMysqlConn, $query) or die(mysqli_error());
    	$cur_record = mysqli_fetch_object($result);
		if (empty($cur_record)) {
			$lang_value.= $value.", ";
		}
		else {
			$lang_value.= $cur_record->lst_value.", ";
		}
	}
    $newstring = substr($lang_value, 0, -2); 
	return  stripslashes($newstring);
}
//define your astrology sign by DOB
function get_sign($dob) {
//echo $dob;
    $dob_arr = explode("-", $dob);
    $dob_day = $dob_arr[2];
    $dob_month = $dob_arr[1];
//    echo $dob_day."-".$dob_month;
    $sign = "";
    if (($dob_month==3 && $dob_day>=21) || ($dob_month==4 && $dob_day<=20)) {
        $sign = HOROSCOPE_ARIES;
    }
    else if (($dob_month==4 && $dob_day>=21) || ($dob_month==5 && $dob_day<=21)) {
        $sign = HOROSCOPE_TAURUS;
    }
    else if (($dob_month==5 && $dob_day>=22) || ($dob_month==6 && $dob_day<=21)) {
        $sign = HOROSCOPE_GEMINI;
    }
    else if (($dob_month==6 && $dob_day>=22) || ($dob_month==7 && $dob_day<=22)) {
        $sign = HOROSCOPE_CANCER;
    }
    else if (($dob_month==7 && $dob_day>=23) || ($dob_month==8 && $dob_day<=22)) {
        $sign = HOROSCOPE_LEO;
    }
    else if (($dob_month==8 && $dob_day>=23) || ($dob_month==9 && $dob_day<=23)) {
        $sign = HOROSCOPE_VIRGO;
    }
    else if (($dob_month==9 && $dob_day>=24) || ($dob_month==10 && $dob_day<=23)) {
        $sign = HOROSCOPE_LIBRA;
    }
    else if (($dob_month==10 && $dob_day>=24) || ($dob_month==11 && $dob_day<=22)) {
        $sign = HOROSCOPE_SCORPIO;
    }
    else if (($dob_month==11 && $dob_day>=23) || ($dob_month==12 && $dob_day<=21)) {
        $sign = HOROSCOPE_SAGITTARIUS;
    }
    else if (($dob_month==12 && $dob_day>=22) || ($dob_month==1 && $dob_day<=20)) {
        $sign = HOROSCOPE_CAPRICORN;
    }
    else if (($dob_month==1 && $dob_day>=21) || ($dob_month==2 && $dob_day<=19)) {
        $sign = HOROSCOPE_AQUARIUS;
    }
    else if (($dob_month==2 && $dob_day>=20) || ($dob_month==3 && $dob_day<=20)) {
        $sign = HOROSCOPE_PISCES;
    }
    return $sign;
}

function js_redirect($url)
{
    echo "<script language=\"javascript\"> window.location=\"$url\";</script>";
}

function getOnlineUser ($fp_cuid) {
    $result=mysqli_query($globalMysqlConn, "SELECT * FROM online WHERE uid='".$fp_cuid."'");
    $aResult = mysqli_fetch_array($result);

    if (!empty($aResult)) {
        $userTime = (int)$aResult['tstamp'];
        $curTime = time() - 10;

        if ($curTime > $userTime) {
            return "F";
        } else {
            return "T";
        }
    } else {
        return "F";
    }
}



function country_state_list($current_value)
{
    $aValues = explode(";",$current_value);
    $country = $aValues[0];
    $state = $aValues[1];
/*
  geo_country
   gcn_countryid  int(11)   No    auto_increment
   gcn_name  char(60)   Yes  NULL
   gcn_status  tinyint(4)   No  1
   gcn_order  int(11)   No  1000
   gcn_regionid

  geo_state
   gst_stateid  int(11)   No    auto_increment
   gst_countryid  int(11)   Yes  NULL
   gst_name

  geo_region
   grg_regionid  int(11)   No    auto_increment
   grg_name  char(60)   Yes  NULL
   grg_order
    */

    $region_query = "   SELECT * FROM geo_region ORDER BY grg_order";
    $region_res = mysqli_query($globalMysqlConn, $region_query);
    while ($region_row = mysqli_fetch_object($region_res)) {
?>
        <option value="0">-- <?=$region_row->grg_name?> --</option>
<?php
        $country_query = "  SELECT *
                            FROM geo_country
                            WHERE gcn_regionid =
'".$region_row->grg_regionid."'
                            ORDER BY gcn_order";
        $country_res = mysqli_query($globalMysqlConn, $country_query);
        while ($country_row = mysqli_fetch_object($country_res)) {
            $state_query = "    SELECT *
                                FROM geo_state
                                WHERE gst_countryid =
'".$country_row->gcn_countryid."'
                                ORDER BY gst_name";
            $state_res = mysqli_query($globalMysqlConn, $state_query);
            if (mysqli_num_rows($state_res)){
                if ($country_row->gcn_countryid.";" == $current_value)
{$selected = "Selected";}
?>
                <option value="<?= $country_row->gcn_countryid?>;"
<?=$selected?>>&nbsp;- <?=$country_row->gcn_name?> -</option>
<?php                $selected = '';
            } else {
                if ($country_row->gcn_countryid.";" == $current_value)
$selected = "Selected";
?>
                <option value="<?= $country_row->gcn_countryid?>;"
<?=$selected?>>&nbsp;&nbsp;&nbsp;<?=$country_row->gcn_name?></option>
<?php          }
            $selected = '';
            while ($state_row = mysqli_fetch_object($state_res)) {
                if
($country_row->gcn_countryid.";".$state_row->gst_stateid ==
$current_value) $selected = "Selected";
?>
                <option value="<?= $country_row->gcn_countryid?>;<?=
$state_row->gst_stateid?>"
<?=$selected?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$state_row->gst_name?></option>
<?php
                $selected = '';
            }
        }
    }
}
}
?>