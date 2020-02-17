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
require(CONST_INCLUDE_ROOT.'/comment/functions.php');

$ent_id = formGet('ent_id');
$id = formGet('id');
$ent_type = formGet('ent_type');
$result = array();
$res = 0;

$can_delete =  (checkEntOwner($ent_type, $ent_id)) ? true : false;

include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";

if ($can_delete) {
    $comment_manager = new CommentManager($ent_type, $ent_id);
    $comment = $comment_manager->get($id);    
    $res = $comment->delete();
}

if ($res === null) {
    $result['error'] = 'YES';
    $result['text'] = join(". " , $comment->error);
} else {
    $result['error'] = 'NO';
}

echo Json::php2Javascript($result);
?>