<?php
/*****************************************************
* � copyright 1999 - 2006 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:         ajax_state.php
#
# Description:  Display states dropdown box by ajax
#
# Version:               8.0 /Catval/
#
######################################################################
include("db_connect.php");
include_once __INCLUDE_CLASS_PATH."/class.Geography.php";
if (!$Sess_UserId) {
    //exit;
}
$result = "";
$mode = (!empty($_POST['mode']))?$_POST['mode']:0;
if (!empty($_POST['countryID']) || !empty($_POST['country_a'])) {
    if (!empty($_POST['countryID'])) {
        $country_a = array ( $_POST['countryID'] );
    } else {
        $country_a = explode("_",$_POST['country_a']);
    }

    foreach ($country_a as $countryID) {
        $GeographyLink=new Geography();
         $country = $GeographyLink->getCountryByID($countryID);
      //  $country = Geography::getCountryByID($countryID);
        if ($country) {
            $result .= 'new get_option("0","-- '.htmlspecialchars($country->gcn_name).' --"),';
        }

        //$StatesList = Geography::getStatesList($countryID);
        $StatesList =$GeographyLink->getStatesList($countryID);
        foreach ($StatesList as $state)
        {
            $result .= 'new get_option("'.$state->gst_stateid.'","'.htmlspecialchars($state->gst_name).'"),';
        }
    }
    if (empty($result))
        $result .= 'new get_option("0","'.SEARCH_ALLSTATES.'"),';
} else {
    $result .= 'new get_option("0","'.SEARCH_ALLSTATES.'"),';
}

echo 'new Array('.str_replace("'", "\\'", substr($result,0,-1)).')';
?>