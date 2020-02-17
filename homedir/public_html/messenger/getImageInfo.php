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
# Name:         getImageInfo.php
#
# Description:  Page displayed after a user pays for membership
#
# Version:      7.2
#
####################################################################
extract($_POST);
extract($_GET);

include('../db_connect.php');
require("function.php");

//============================================================================================
function createthumb($name,$filename,$new_w,$new_h){
    $ext = array_pop(explode(".",$name));
    if (preg_match("/jpg|jpeg/", $ext)){
        $src_img=imagecreatefromjpeg($name);
    }
    if (preg_match("/png/", $ext)){
        $src_img=imagecreatefrompng($name);
    }
    $old_x=imageSX($src_img);
    $old_y=imageSY($src_img);

    $thumb_w=$new_w;
    $thumb_h=$old_y*($new_w/$old_x);

    $dst_img = @ImageCreateTrueColor($thumb_w,$thumb_h);
    if (!$dst_img) {
        $dst_img = ImageCreate($thumb_w,$thumb_h);
        imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
    } else {
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
    }
    if (preg_match("/png/",$system[1])){
        imagepng($dst_img,$filename);
    } else {
        imagejpeg($dst_img,$filename);
    }
//    echo "x=".imagesx($dst_img);
//    echo "<br>y=".imagesy($dst_img)."<br>";
    $aSize = array();
    $aSize['width'] = imagesx($dst_img);
    $aSize['height'] = imagesy($dst_img);
    imagedestroy($dst_img);
    imagedestroy($src_img);
    return $aSize;
}

//============================================================================================


if (!isset($_SESSION['Sess_UserName'])){
    exit("Incorrect Parameters");
} else {
    if (isset($action)) {
        if (isset($value)) {
            if ($action == "getInfoImg") {
                if (!empty($value)) {
                    $aResult = $db->get_row("SELECT * FROM adverts WHERE adv_username ='".$value."'");
                    include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";
                    $adverts = new Adverts();
                    $adverts->InitByObject($aResult);
                    $adverts->SetImage('small');
                    $path = $adverts->adv_picture;
                    $targetfile = $CONST_INCLUDE_ROOT.$path->Path;
                    $file_name = $aResult->adv_username.".jpg";

                    $new_w=47;
                    $new_h=40;
                    $thumbfile = getcwd()."/thumbs/".$file_name;

                    $aPicSize = createthumb($targetfile, $thumbfile,$new_w,$new_h);
                    $curPach = getcwd();
                    $aCurDir = explode("/", $curPach);
                    $countArray = count($aCurDir);
                    $cur_dir = $aCurDir[$countArray-1];

                    $url = $CONST_LINK_ROOT."/".$cur_dir."/thumbs/".$file_name;

                    echo "urlimg=".$url."&w=".$aPicSize['width']."&h=".$aPicSize['height'];
                }
            }
        }
    }
}


?>