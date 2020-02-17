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
# Name:         ajax_city.php
#
# Description:  Display cities dropdown box by ajax
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
$mode = (!empty($_REQUEST['mode']))?$_REQUEST['mode']:0;
if (!empty($_REQUEST['countryID']) || !empty($_REQUEST['stateID']) || !empty($_REQUEST['country_a']) || !empty($_REQUEST['state_a'])) {
    if (!empty($_REQUEST['countryID'])) {
        $country_a = array ( $_REQUEST['countryID'] );
    } else {
        $country_a = explode("_",$_REQUEST['country_a']);
    }
    if (!empty($_REQUEST['stateID'])) {
        $state_a = array ( $_REQUEST['stateID'] );
    } else {
        $state_a = explode("_",$_REQUEST['state_a']);
    }
    
      $GeographyLink=new Geography();
    foreach ($country_a as $countryID) {
        
           $country = $GeographyLink->getCountryByID($countryID);
        //$country = Geography::getCountryByID($countryID);
        if ($country) {
            $result .= 'new get_option("0","-- '.htmlspecialchars($country->gcn_name).' --"),';
        }
       
        //$CitiesList = Geography::getCitiesList($countryID,0);
         $CitiesList =$GeographyLink->getCitiesList($countryID,0);
        foreach ($CitiesList as $city)
        {
            $result .= 'new get_option("'.$city->gct_cityid.'","'.htmlspecialchars($city->gct_name).'"),';
        }

        foreach ($state_a as $stateID) {
             $isStateInCountry =$GeographyLink->isStateInCountry($stateID,$countryID);
           // if (Geography::isStateInCountry($stateID,$countryID)) {
             if($isStateInCountry){
                 
                  $state =$GeographyLink->getStateByID($stateID);
                //$state = Geography::getStateByID($stateID);
                if ($state) {
                    $result .= 'new get_option("0","-- '.htmlspecialchars($state->gst_name).' --"),';
                }
                $CitiesList =$GeographyLink->getCitiesList($countryID,$stateID);
                //$CitiesList = Geography::getCitiesList($countryID,$stateID);
                foreach ($CitiesList as $city)
                {
                    $result .= 'new get_option("'.$city->gct_cityid.'","'.htmlspecialchars($city->gct_name).'"),';
                }
            }
        }
    }
    if (empty($result))
        $result .= 'new get_option("0","'.SEARCH_ALLCITIES.'"),';

} else {
    $result .= 'new get_option("0","'.SEARCH_ALLCITIES.'"),';
}

echo 'new Array('.str_replace("'", "\\'", substr($result,0,-1)).')';
?>