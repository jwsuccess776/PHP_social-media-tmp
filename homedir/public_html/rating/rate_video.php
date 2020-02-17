<?
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
# Name:         prgvideodmin.php
#
# Description:  Adds and removes additional videos for members
#
# Version:      8.0
#
######################################################################

include('../db_connect.php');
include(CONST_INCLUDE_ROOT.'/session_handler.inc');
include(CONST_INCLUDE_ROOT.'/error.php');
include_once __INCLUDE_CLASS_PATH."/class.Json.php";

include_once __INCLUDE_CLASS_PATH."/class.Video.php";
$video = new Video();


if (formGet('vote')) {
    $video->InitById(formGet('vid_id'));
    $result = $video->rating->getRating();
    $res = $video->vote($Sess_UserId, $vote);
    if ($res !== NULL)  $result = $video->rating->getRating();
}

echo Json::php2Javascript( array ('rating' => $result->rating, 'voted' => $result->voted));
?>
