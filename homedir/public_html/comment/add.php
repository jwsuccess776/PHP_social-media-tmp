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
include_once __INCLUDE_CLASS_PATH.'/class.Json.php';


$ent_id = formGet('ent_id');
$ent_type = formGet('ent_type');
$text = formGet('text');
$result = array();

include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";

$comment_manager = new CommentManager($ent_type, $ent_id);

$res = $comment_manager->add($text, $Sess_UserId);
if ($res === null) {
    $result['error'] = 'YES';
    $result['text'] = join(". " , $comment_manager->error);
} else {
    $result['error'] = 'NO';
}

echo Json::php2Javascript($result);
?>