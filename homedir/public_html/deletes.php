<?php

/****************************************************

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

# Name:         deletes.php

#

# Description:  Admin tool to send latest matches to members by mail

#

# Version:      7.2

#

######################################################################



function delete_advert($userid) {

GLOBAL $globalMysqlConn,$CONST_THUMBS;

     include_once __INCLUDE_CLASS_PATH."/class.Audio.php";

     $audio = new Audio();

     include_once __INCLUDE_CLASS_PATH."/class.Picture.php";

     $picture = new Picture();

     include_once __INCLUDE_CLASS_PATH."/class.Video.php";

     $video = new Video();

     include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";

     $gallery = new Gallery();

     include_once __INCLUDE_CLASS_PATH."/class.Network.php";

     $network = new Network();

     include_once __INCLUDE_CLASS_PATH."/class.StaticProfile.php";





    # delete the advert, profile and picture

    $query="SELECT adv_userid, adv_username, adv_picture FROM adverts WHERE adv_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    if (mysqli_num_rows($result) > 0) {

        $sql_array = mysqli_fetch_object($result);



        foreach ($picture->GetListByMember($userid) as $pic) {

            $pic->Delete($userid);

        }

        foreach ($audio->GetListByMember($userid) as $vid) {

            $vid->Delete($userid);

        }

        foreach ($video->GetListByMember($userid) as $aud) {

            $aud->Delete($userid);

        }

        foreach ($gallery->GetListByMember($userid) as $gal) {

            $gal->Delete($userid);

        }

        foreach ($network->getNetwork($userid,1) as $id){

            $network->removeFriend($userid,$id);

        }

        $static_profile = new StaticProfile($sql_array->adv_username);

        $static_profile->Delete();



        # remove the advert

        $query="delete from adverts where adv_userid = '$userid'";

        if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



        # remove the mymatch

        $query="delete from mymatch where mym_userid = '$userid'";

        if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



        # remove the bb_posts

        $query="delete from bb_posts where poster_id = '$userid'";

        if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



        # remove the profile

        $query="SELECT pro_userid FROM profiles WHERE pro_userid = '$userid'";

        if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



        if (mysqli_num_rows($result) > 0) {

                $query="delete from profiles where pro_userid = '$userid'";

                $result=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        }

    }



    # delete the encounters

    $query="DELETE FROM encounters WHERE enc_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # delete the hotlist

    $query="delete from hotlist where hot_advid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # give away groups to groups manager

    include_once __INCLUDE_CLASS_PATH."/class.ItemManager.php";

    $option_manager =& OptionManager::GetInstance();

    $db =& DB::GetInstance();

    $groups_manager = $option_manager->getValue('groups_manager');
    $main = new Main;
    $groups_manager_id = $db->get_var("SELECT mem_userid FROM members WHERE mem_username = '".$main->_prepareData($groups_manager)."' LIMIT 1");

    $db->query("UPDATE groups SET owner = '$groups_manager_id' WHERE owner = '$userid'");



}



function delete_me($userid) {

GLOBAL $globalMysqlConn;



    $query="SELECT * FROM members WHERE mem_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

    else {$sql_array = mysqli_fetch_object($result); $username=trim($sql_array->mem_username);}



    # delete the member

    $query="delete from members where mem_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # delete the mails

    $query="delete from messages where msg_receiverid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # delete the online status and IM

    $query="DELETE FROM my_friends WHERE uid = '$username' OR friend_uid = '$username'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    $query="DELETE FROM online WHERE uid = '$username'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    $query="DELETE FROM iwannatalk WHERE uid = '$username' OR cid = '$username'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # delete the hotlist

    $query="delete from hotlist where hot_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # delete the encounters

    $query="DELETE FROM encounters WHERE enc_viewerid  = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # give away groups to groups manager

    include_once __INCLUDE_CLASS_PATH."/class.ItemManager.php";

    $option_manager =& OptionManager::GetInstance();

    $db =& DB::GetInstance();

    $groups_manager = $option_manager->getValue('groups_manager');
    $main = new Main;
    $groups_manager_id = $db->get_var("SELECT mem_userid FROM members WHERE mem_username = '".$main->_prepareData($groups_manager)."' LIMIT 1");

    $db->query("UPDATE groups SET owner = '$groups_manager_id' WHERE owner = '$userid'");

}



function delete_match($userid) {

GLOBAL $globalMysqlConn;



    # delete the search

    $query="delete from search where sea_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}

    $query="delete from sarray where sar_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



}



function delete_affiliate($userid) {

GLOBAL $globalMysqlConn;



    # remove the affiliate receipts

    $query="delete from receipts where rec_affuserid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



    # remove the affiliate

    $query="delete from affiliates where aff_userid = '$userid'";

    if (! $result=mysqli_query($globalMysqlConn,$query)) {error_page(mysqli_error(),GENERAL_SYSTEM_ERROR);}



}



?>