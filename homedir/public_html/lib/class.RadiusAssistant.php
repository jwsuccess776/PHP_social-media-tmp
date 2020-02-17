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
# Name:         RadiusAssistant.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# Version:      7.2
#
######################################################################
class RadiusAssistant {
    var $maxLat;
    var $minLat;
    var $maxLong;
    var $minLong;
    
    function __construct($Latitude, $Longitude, $Miles,$Units = 'mile') {
        global $maxLat,$minLat,$maxLong,$minLong;
        if ($Units == 'mile') {
            $EQUATOR_LAT_MILE = 69.172;
        } else {
            $EQUATOR_LAT_MILE = 111.321;
        }
        $maxLat = $Latitude + $Miles / $EQUATOR_LAT_MILE;
        $minLat = $Latitude - ($maxLat - $Latitude);
        $maxLong = $Longitude + $Miles / (cos($minLat * M_PI / 180) * $EQUATOR_LAT_MILE);
        $minLong = $Longitude - ($maxLong - $Longitude);
    }
    function MaxLatitude() {
        return $GLOBALS["maxLat"];
    }
    function MinLatitude() {
        return $GLOBALS["minLat"];
    }
    function MaxLongitude() {
        return $GLOBALS["maxLong"];
    }
    function MinLongitude() {
        return $GLOBALS["minLong"];
    }
    
}
?>