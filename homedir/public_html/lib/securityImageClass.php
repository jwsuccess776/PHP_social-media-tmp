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
# Name:         securityImageClass.php
#
# Description:  Sends offer mails to people who have not visited for a while
#
# Version:      7.2
#
######################################################################


class securityImage {

var $inputParam = "";			// Public; $x->inputParam = "style='color:blue'"
var $name 	= "security";		// Public; $x->name = "mySecurityInputField"

var $codeLength = 5;			// Private; use setCodeLength()
var $fontSize	= 16;			// Private; use setFontSize()

var $images = array (
      		array("fontColor"   => "454545",
		          "imageFile"   => "security/s_img.jpg",
                  "imageFont"  => "security/knowyour.ttf"
			),
      		array("fontColor"   => "454545",
		          "imageFile"   => "security/s_img.jpg",
                  "imageFont"  => "security/knowyour.ttf"
			),
		);

var $securityCode = "";			// Private

function __construct() {

	$key = array_rand($this->images);
	$this->fontColor = $this->images[$key]['fontColor'];
    $this->imageFile = CONST_INCLUDE_ROOT."/".$this->images[$key]['imageFile'];
    $this->imageFont = CONST_INCLUDE_ROOT."/".$this->images[$key]['imageFont'];
	session_start();

	/*
	 * Save this so it is available in the next instantiation; required for isValid().
	*/
	if (isset($_SESSION['securityCode'])) {
		$this->userSecurityCode = $_SESSION['securityCode'];
	} else {
		$this->userSecurityCode = "";
	}

	/*
	 * Save the items required by the instance created by securityImageImage.php
	*/
	if (isset($_SESSION['codeLength'])) {
		$this->codeLength = $_SESSION['codeLength'];
	}

	if (isset($_SESSION['fontSize'])) {
		$this->fontSize = $_SESSION['fontSize'];
	}

	if (isset($_SESSION['fontColor'])) {
		$this->fontColor = $_SESSION['fontColor'];
	}

	if (isset($_SESSION['imageFile'])) {
		$this->imageFile = $_SESSION['imageFile'];
	}
}

function simpleRandString($length=16, $list="123456789ABCDEFGHJKLMNPRSTUVWXYZ") {
	/*
	 * Generates a random string with the specified length
	 * Chars are chosen from the provided [optional] list
	*/
	mt_srand((double)microtime()*1000000);

	$newstring = "";

	if ($length > 0) {
		while (strlen($newstring) < $length) {
			$newstring .= $list[mt_rand(0, strlen($list)-1)];
		}
	}
	return $newstring;
}

/*
 * Not to be called directly.  Called by securityImageImage.php.
*/
function showImage() {
	header("Content-type: image/jpeg");
	$this->generateImage();
	imagejpeg($this->img);
	imageDestroy($this->img);
}

/*
 * Private
*/
function generateImage() {

	$this->securityCode = $this->simpleRandString($this->codeLength);

	$_SESSION['securityCode'] = $this->securityCode;

	$img_path = $this->imageFile;

	$this->img = ImageCreateFromJpeg($img_path);

	$img_size = getimagesize($img_path);

	$color = imagecolorallocate($this->img,
			hexdec(substr($this->fontColor, 0, 2)),
			hexdec(substr($this->fontColor, 2, 2)),
			hexdec(substr($this->fontColor, 4, 2))
			);

    $aFont = imagettfbbox($this->fontSize,0,$this->imageFont,"W");
    $fh = abs($aFont[1] + $aFont[7]);

	// create a new string with a blank space between each letter so it looks better
	$newstr = "";
	for ($i = 0; $i < strlen($this->securityCode); $i++) {
		$newstr .= $this->securityCode[$i] ." ";
	}

	// remove the trailing blank

	$newstr = trim($newstr);

    $aFont = imagettfbbox($this->fontSize,0,$this->imageFont,$newstr);
    $str_width = abs($aFont[0] + $aFont[2])+2*strlen($newstr) ;

	// center the string
	$x = ($img_size[0] - $str_width) / 2;
	// output each character at a random height and standard horizontal spacing
	for ($i = 0; $i < strlen($newstr); $i++) {
		$hz = mt_rand($img_size[1]/5+$fh, $img_size[1]/5*4);
        $angle = mt_rand(-20, 20);
        imagettftext ($this->img, $this->fontSize, $angle, $x , $hz, $color, $this->imageFont, $newstr[$i]);
		//imagechar( $this->img, $this->fontSize, $x, $hz, $newstr[$i], $color);
        $aFont = imagettfbbox($this->fontSize,$angle,$this->imageFont,$newstr[$i]);
        $x += abs($aFont[0] + $aFont[2]) + 3;

	}
}

/*
 * PUBLIC FUNCTIONS
*/
function showFormInput() {
	return "<input $this->inputParam TYPE=text NAME=$this->name MAXLENGTH=$this->codeLength></input>";
}

function showFormImage() {
	return "<img src=\"s_image.php\">";
}

function isValid() {
	return $_POST["$this->name"] == $this->userSecurityCode;
}

function setCodeLength($p) {

	$this->codeLength = $p;
	$_SESSION['codeLength'] = $this->codeLength;
}

function setFontSize($p) {

	$this->fontSize = $p;
	$_SESSION['fontSize'] = $this->fontSize;
}

function setFontColor($p) {

	$this->fontColor = $p;
	$_SESSION['fontColor'] = $this->fontColor;
}

function setImageFile($p) {

	$this->imageFile = $p;
	$_SESSION['imageFile'] = $this->imageFile;
}

}
?>