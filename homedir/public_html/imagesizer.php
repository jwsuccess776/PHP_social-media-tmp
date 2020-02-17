<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         imagesizer.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################

function createthumb($name,$filename,$new_w,$new_h){
    $system=explode(".",$name);
	$filetype=array_pop($system);

    if (preg_match("/jpg|jpeg/",$filetype)){
        $src_img=imagecreatefromjpeg($name);
    }
    if (preg_match("/png/",$filetype)){
        $src_img=imagecreatefrompng($name);
    }
    if (preg_match("/gif/",$filetype)){
        $src_img=imagecreatefromgif($name);
    }

    if (!$src_img) error_page('Incorrect image format',GENERAL_USER_ERROR);
//createthumb() is called with the following variables: The name of the original image (if needed with folder name), the name of the thumbnail picture, and the dimensions.
//These lines get the information if gd is at least version 2.0 and check if the original image is a JPEG or PNG.
//Accordingly, a new image object is created called src_image.
//These lines get the dimensions of the original image by using imageSX() and imageSY(), and calculate the dimensions of the thumbnail accordingly, keeping the correct aspect ratio. The desired dimensions are stored in thumb_w and thumb_h.
    $old_x=imageSX($src_img);
    $old_y=imageSY($src_img);
    if ($old_x > $old_y) {
        $thumb_w=$new_w;
        $thumb_h=$old_y*($new_w/$old_x);
    } else {
        $thumb_w=$old_x*($new_h/$old_y);
        $thumb_h=$new_h;
    }
/*
    // TargetAspectRatio is the aspect ratio of the target area
		$TargetAspectRatio = $new_w / $new_h;
    // ActualAspectRatio is the aspect ratio of the picture
		$ActualAspectRatio = $old_x / $old_y;
    // Assume we have a square picture
		$thumb_w = $new_w;
		$thumb_h = $new_h;
    // Now check the real aspect ratios
		if ( $ActualAspectRatio > $TargetAspectRatio )
    {
				$thumb_w = $new_w;
				$thumb_h = $new_w / $old_x * $old_y;
    }
		if ( $ActualAspectRatio < $TargetAspectRatio )
    {
				$thumb_w = $new_h / $old_y * $old_x;
				$thumb_h = $new_h;
    }
*/
	$dst_img = @ImageCreateTrueColor($thumb_w,$thumb_h);
	if (!$dst_img) {
		$dst_img = ImageCreate($thumb_w,$thumb_h);
        imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	} else {
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	}
//These lines check the version of gd (coming from checkgd()) and create the image either as a 256 colour version using ImageCreate() or as a true colour version using ImageCreateTrueColor().
//Then the original image gets resized, respectively resampled and copied into the new thumbnail image, on the top left position.
    if (preg_match("/png/",$filetype)){
        imagepng($dst_img,$filename);
    } elseif (preg_match("/gif/",$filetype)) {
        imagegif($dst_img,$filename);
    } else {
        imagejpeg($dst_img,$filename);
	}
	
    imagedestroy($dst_img);
    imagedestroy($src_img);
}
?>